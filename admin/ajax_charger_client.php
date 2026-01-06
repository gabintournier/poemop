<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user'])) {
	http_response_code(403);
	echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
	exit;
}

include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_groupements.php";

function json_response($data, $code = 200)
{
	http_response_code($code);
	echo json_encode($data);
	exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;

if ($action === 'start') {
	$id_grp = intval($_POST['id_grp'] ?? $_GET['id_grp'] ?? 0);
	$batchSize = intval($_POST['batch_size'] ?? $_GET['batch_size'] ?? 500);
	if ($id_grp <= 0) {
		json_response(['status' => 'error', 'message' => 'id_grp manquant'], 400);
	}

	// Récupérer la liste des utilisateurs éligibles
	$resUsers = getCommandesUtilisateurs($co_pmp, $id_grp);
	$userIdsBruts = [];
	while ($row = mysqli_fetch_assoc($resUsers)) {
		$userIdsBruts[] = intval($row['user_id']);
	}

	$actionableUserIds = filterChargerClientUserIds($co_pmp, $userIdsBruts);
	$total = count($actionableUserIds);
	$displayTotal = $total;

	if ($total === 0) {
		json_response([
			'status' => 'empty',
			'message' => 'Aucun utilisateur éligible (déjà groupés ou commandes verrouillées)',
			'group_id' => $id_grp
		], 200);
	}

	$payload = [
		'id_grp' => $id_grp,
		'user_ids' => $actionableUserIds,
		'batch_size' => max(100, $batchSize) // on impose un minimum
	];

	$payloadJson = mysqli_real_escape_string($co_pmp, json_encode($payload));

	$insert = "
        INSERT INTO pmp_job (type, payload, status, total, processed, current_index)
        VALUES ('charger_client', '$payloadJson', 'pending', '$total', 0, 0)
    ";
	$resInsert = my_query($co_pmp, $insert);
	if (!$resInsert) {
		json_response(['status' => 'error', 'message' => 'Impossible de créer le job'], 500);
	}

	$jobId = mysqli_insert_id($co_pmp);
	json_response([
		'status' => 'ok',
		'job_id' => $jobId,
		'total' => $total,
		'total_display' => $displayTotal,
		'group_id' => $id_grp
	]);
}

if ($action === 'process') {
	$jobId = intval($_POST['job_id'] ?? $_GET['job_id'] ?? 0);
	if ($jobId <= 0) {
		json_response(['status' => 'error', 'message' => 'job_id manquant'], 400);
	}

	$resJob = my_query($co_pmp, "SELECT * FROM pmp_job WHERE id = '$jobId' AND type = 'charger_client' LIMIT 1");
	$job = mysqli_fetch_assoc($resJob);
	if (!$job) {
		json_response(['status' => 'error', 'message' => 'Job introuvable'], 404);
	}

	$payload = json_decode($job['payload'], true);
	if (!$payload || empty($payload['user_ids'])) {
		json_response(['status' => 'error', 'message' => 'Payload invalide'], 500);
	}

	$batchSize = intval($payload['batch_size'] ?? 500);
	$userIds = $payload['user_ids'];
	$id_grp = intval($payload['id_grp']);

	$currentIndex = intval($job['current_index']);
	$total = intval($job['total']);
	$processed = intval($job['processed']);

	if ($job['status'] === 'done') {
		json_response([
			'status' => 'done',
			'processed' => $processed,
			'total' => $total,
			'percent' => 100,
			'group_id' => $id_grp,
			'message' => sprintf('%d importés dans groupement %d', $processed, $id_grp)
		]);
	}

	$batchIds = array_slice($userIds, $currentIndex, $batchSize);
	$result = traiterBatchChargerClient($co_pmp, $id_grp, $batchIds);

	$newProcessed = $processed + count($batchIds);
	$newIndex = $currentIndex + count($batchIds);
	$newStatus = ($newIndex >= $total) ? 'done' : 'in_progress';

	$update = "
        UPDATE pmp_job
        SET status = '$newStatus',
            processed = '$newProcessed',
            current_index = '$newIndex'
        WHERE id = '$jobId'
    ";
	my_query($co_pmp, $update);

	$percent = $total > 0 ? round(($newProcessed / $total) * 100, 2) : 100;

	$message = null;
	if ($newStatus === 'done') {
		$message = sprintf('%d importés dans groupement %d', $newProcessed, $id_grp);
	}

	json_response([
		'status' => $newStatus,
		'processed' => $newProcessed,
		'total' => $total,
		'percent' => $percent,
		'updates' => $result['updates'] ?? 0,
		'inserts' => $result['inserts'] ?? 0,
		'group_id' => $id_grp,
		'message' => $message
	]);
}

json_response(['status' => 'error', 'message' => 'Action inconnue'], 400);
