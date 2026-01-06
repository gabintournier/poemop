<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

header('Content-Type: application/json; charset=UTF-8');

function resolveIncPath($candidate)
{
    return file_exists($candidate) ? $candidate : '';
}

$coConnectPath = resolveIncPath(__DIR__ . '/inc/pmp_co_connect.php') ?: resolveIncPath(__DIR__ . '/../inc/pmp_co_connect.php');
$groupementsPath = resolveIncPath(__DIR__ . '/inc/pmp_inc_fonctions_groupements.php') ?: resolveIncPath(__DIR__ . '/../inc/pmp_inc_fonctions_groupements.php');
$commandesPath = resolveIncPath(__DIR__ . '/inc/pmp_inc_fonctions_commandes.php') ?: resolveIncPath(__DIR__ . '/../inc/pmp_inc_fonctions_commandes.php');

if (!$coConnectPath || !$groupementsPath || !$commandesPath) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Impossible de charger les scripts de configuration.',
    ]);
    exit;
}

require_once $coConnectPath;
require_once $groupementsPath;
require_once $commandesPath;

function respond($message, $code = 400, $payload = [])
{
    http_response_code($code);
    echo json_encode(array_merge(['success' => $code === 200, 'message' => $message], $payload));
    exit;
}

function sanitizeFilename($value)
{
    $value = trim($value);
    $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value) ?: $value;
    $value = preg_replace('/[^a-zA-Z0-9-_]/', '-', $value);
    $value = preg_replace('/-+/', '-', $value);
    return trim($value, '-_');
}

function booleanField($name)
{
    return isset($_POST[$name]) && $_POST[$name] !== '' && $_POST[$name] !== '0';
}

if (!isset($_SESSION['user'])) {
    respond("Vous devez être connecté pour lancer un export.", 401);
}

$id_grp = filter_input(INPUT_POST, 'id_grp', FILTER_VALIDATE_INT);
if (!$id_grp) {
    respond('Paramètre de groupement manquant ou invalide.', 422);
}

$grp = getGroupementDetails($co_pmp, $id_grp);
if (!$grp) {
    respond('Groupement introuvable.', 404);
}

$statut1 = filter_input(INPUT_POST, 'statut_1_export', FILTER_VALIDATE_INT);
$statut2 = filter_input(INPUT_POST, 'statut_2_export', FILTER_VALIDATE_INT);
$statut1 = ($statut1 === false || $statut1 === null) ? 10 : $statut1;
$statut2 = ($statut2 === false || $statut2 === null) ? 0 : $statut2;
if ($statut2 === 0) {
    $statut2 = $statut1;
}

$statut_min = min($statut1, $statut2);
$statut_max = max($statut1, $statut2);

$res_export = getCommandesGroupementsExportStatut($co_pmp, $id_grp, $statut_min, $statut_max);
if (!$res_export) {
    respond('Impossible d\'obtenir les commandes pour cet export.', 500);
}

$exportDir = __DIR__ . '/export';
if (!is_dir($exportDir)) {
    mkdir($exportDir, 0755, true);
}

$label = sanitizeFilename($grp['libelle'] ?? 'groupement-' . $id_grp);
if ($label === '') {
    $label = 'groupement-' . $id_grp;
}
$filename = sprintf('export-commande-groupements-%d-%s-%s.xls', $id_grp, $label, date('YmdHis'));
$filePath = $exportDir . '/' . $filename;

$handle = fopen($filePath, 'w');
if ($handle === false) {
    respond('Impossible de créer le fichier d\'export.', 500);
}

fwrite($handle, chr(239) . chr(187) . chr(191));

$headers = [];
if (booleanField('nom_prenom')) {
    $headers[] = 'Nom';
    $headers[] = 'Prénom';
}
if (booleanField('adresse')) {
    $headers[] = 'Adresse';
}
if (booleanField('cp_ville')) {
    $headers[] = 'CP';
    $headers[] = 'Ville';
}
if (booleanField('date')) {
    $headers[] = 'Date';
}
if (booleanField('quantite')) {
    $headers[] = 'Qté';
}
if (booleanField('quantite_livree')) {
    $headers[] = 'Livré';
}
if (booleanField('les_prix')) {
    $headers[] = 'Prix O';
    $headers[] = 'Prix S';
}
if (booleanField('type_fioul')) {
    $headers[] = 'Type Fioul';
}
if (booleanField('statut_exp')) {
    $headers[] = 'Statut';
}
if (booleanField('aspiration_prix')) {
    $headers[] = 'PMP';
    $headers[] = 'AF';
    $headers[] = 'FMC';
    $headers[] = 'FR';
    $headers[] = 'FM';
}
if (booleanField('mail')) {
    $headers[] = 'Mail';
}
if (booleanField('commentaire_cmd')) {
    $headers[] = 'Commentaire Fournisseur';
}
if (booleanField('tel')) {
    $headers[] = 'Tel1';
    $headers[] = 'Tel2';
}

if (!empty($headers)) {
    fwrite($handle, implode(';', $headers) . "\r\n");
}

$statusLabels = [
    0 => ' 0 - Pas de commande',
    10 => ' 10 - Utilisateur',
    12 => ' 12 - Attachée',
    13 => ' 13 - Proposée',
    15 => ' 15 - Groupée',
    17 => ' 17 - P. Proposée',
    20 => ' 20 - Validée',
    25 => ' 25 - Livrable',
    30 => ' 30 - Livrée',
    40 => ' 40 - Terminée',
    50 => ' 50 - Annulée',
    52 => ' 52 - Annulée / Livraison',
    55 => ' 55 - Annulée / Prix',
    99 => ' 99 - Annulée / Compte désactivé'
];

while ($export = mysqli_fetch_assoc($res_export)) {
    $row = [];
    $dateValue = '';
    if (!empty($export['cmd_dt'])) {
        $dateValue = (new DateTime($export['cmd_dt']))->format('d/m/Y');
    }

    $typeChar = '';
    if ((int) $export['cmd_typefuel'] === 1) {
        $typeChar = 'O';
    } elseif ((int) $export['cmd_typefuel'] === 2) {
        $typeChar = 'S';
    }

    $statusText = $statusLabels[$export['cmd_status']] ?? (' ' . $export['cmd_status']);

    if (booleanField('nom_prenom')) {
        $row[] = str_replace(';', '', $export['name'] ?? '');
        $row[] = str_replace(';', '', $export['prenom'] ?? '');
    }
    if (booleanField('adresse')) {
        $row[] = str_replace(';', '', $export['adresse'] ?? '');
    }
    if (booleanField('cp_ville')) {
        $row[] = str_replace(';', '', $export['code_postal'] ?? '');
        $row[] = str_replace(';', '', $export['ville'] ?? '');
    }
    if (booleanField('date')) {
        $row[] = $dateValue;
    }
    if (booleanField('quantite')) {
        $row[] = $export['cmd_qte'] ?? '';
    }
    if (booleanField('quantite_livree')) {
        $row[] = $export['cmd_qtelivre'] ?? '';
    }
    if (booleanField('les_prix')) {
        $row[] = $export['cmd_prix_ord'] ?? '';
        $row[] = $export['cmd_prix_sup'] ?? '';
    }
    if (booleanField('type_fioul')) {
        $row[] = $typeChar;
    }
    if (booleanField('statut_exp')) {
        $row[] = $statusText;
    }
    if (booleanField('aspiration_prix')) {
        $row[] = $export['cmd_prixpmp'] ?? '';
        $row[] = $export['cmd_prixaf'] ?? '';
        $row[] = $export['cmd_prixfmc'] ?? '';
        $row[] = $export['cmd_prixfr'] ?? '';
        $row[] = $export['cmd_prixfm'] ?? '';
    }
    if (booleanField('mail')) {
        $row[] = $export['email'] ?? '';
    }
    if (booleanField('commentaire_cmd')) {
        $commentFour = str_replace(';', '', $export['cmd_commentfour'] ?? '');
        $row[] = $commentFour;
    }
    if (booleanField('tel')) {
        $row[] = $export['tel_fixe'] ?? '';
        $row[] = $export['tel_port'] ?? '';
    }

    fwrite($handle, implode(';', $row) . "\r\n");
}

fclose($handle);

$downloadUrl = '/admin/export/' . $filename;
respond('Export prêt', 200, ['downloadUrl' => $downloadUrl]);
