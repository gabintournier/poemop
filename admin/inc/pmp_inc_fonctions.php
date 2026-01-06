<?php
//Afficher le nombre d'avis à traiter sur le tablau de bord
function getAvis0(&$co_pmp)
{
	$query = "  SELECT id
				FROM pmp_livre_or
				WHERE valide = '0' ";
	$res = my_query($co_pmp, $query);
	return $res;
}

// Afficher le nombre de commande passées entre créer et terminée
function getTotalCommande(&$co_pmp)
{
	$query = "  SELECT count(*) AS cmd
				FROM pmp_commande
				WHERE cmd_status >= '40' ";
	$res = my_query($co_pmp, $query);
	$cmd = mysqli_fetch_array($res);
	return $cmd;
}

// Afficher le nombre total de client avec coordonnées
function getClientsCoord(&$co_pmp)
{
	$query = "  SELECT count(*) AS coord
				FROM pmp_utilisateur
				";
	$res = my_query($co_pmp, $query);
	$coord = mysqli_fetch_array($res);
	return $coord;
}


// Afficher le nombre de groupements
function getGroupements(&$co_pmp)
{
	$query = "  SELECT id
				FROM pmp_regroupement
				WHERE statut >= 40 ";
	$res = my_query($co_pmp, $query);
	return $res;
}

// Afficher le nombre de clients
function getClientsNet(&$co_pmp)
{
	$query = "  SELECT count(*) as client
				FROM jjj_users
				WHERE id > 77";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getClientsTel(&$co_pmp)
{
	$query = "  SELECT count(*) as client
				FROM pmp_utilisateur
				WHERE  user_id > 1000010
				AND internet = 0 ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

// Afficher le nombre de clients actif
function getClientsInactif($co_pmp)
{
	$query = "  SELECT count(*) AS inactif
				FROM pmp_utilisateur
				WHERE actif IN (0, 1, 4) ";
	$res = my_query($co_pmp, $query);
	$actif = mysqli_fetch_array($res);
	return $actif;
}

// Afficher le nombre de clients inactif
function getClientsActif($co_pmp)
{
	$query = "  SELECT count(*) AS actif
				FROM pmp_utilisateur
				WHERE actif IN (2, 3) ";
	$res = my_query($co_pmp, $query);
	$inactif = mysqli_fetch_array($res);
	return $inactif;
}

// Stats mois en cours
function getStatsEnCours($co_pmp)
{
	$date = new DateTime();
    $dateDeb = $date -> format('Y-m-01');
    $dateFin = $date -> format('Y-m-t');

	$date_min = date_format(new DateTime($dateDeb), 'Y-m-d' );
	$date_max = date_format(new DateTime($dateFin), 'Y-m-d' );

	$query = "  SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) AS statut
				FROM pmp_commande, pmp_regroupement
				WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
                AND pmp_commande.cmd_status IN ('12','15','17')
				AND pmp_regroupement.statut BETWEEN '10' AND '40'
				AND pmp_regroupement.date_grp BETWEEN '$date_min' AND '$date_max'";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getStatsValide($co_pmp, $statut)
{
	$date = new DateTime();
	$dateDeb = $date -> format('Y-m-01');
	$dateFin = $date -> format('Y-m-t');

	$date_min = date_format(new DateTime($dateDeb), 'Y-m-d' );
	$date_max = date_format(new DateTime($dateFin), 'Y-m-d' );

	if($statut >= '30') { $qte = 'ROUND(SUM(pmp_commande.cmd_qtelivre)/1000)'; } else { $qte = 'ROUND(SUM(pmp_commande.cmd_qte)/1000)'; }

	$query = "  SELECT $qte AS statut
				FROM pmp_commande, pmp_regroupement
				WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
				AND pmp_commande.cmd_status = '$statut'
				AND pmp_regroupement.statut BETWEEN '10' AND '40'
				AND pmp_regroupement.date_grp BETWEEN '$date_min' AND '$date_max'";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

// function getStatsValide($co_pmp)
// {
// 	$date = new DateTime();
//     $dateDeb = $date -> format('Y-m-01');
//     $dateFin = $date -> format('Y-m-t');
//
// 	$date_min = date_format(new DateTime($dateDeb), 'Y-m-d' );
// 	$date_max = date_format(new DateTime($dateFin), 'Y-m-d' );
//
// 	$query = "  SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) AS statut
// 				FROM pmp_commande, pmp_regroupement
// 				WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
//                 AND pmp_commande.cmd_status IN ('20','25','30','40')
// 				AND pmp_regroupement.statut BETWEEN '10' AND '40'
// 				AND pmp_regroupement.date_grp BETWEEN '$date_min' AND '$date_max'";
// 	$res = my_query($co_pmp, $query);
// 	$res = mysqli_fetch_array($res);
// 	return $res;
// }

function getStatsAnnulee($co_pmp)
{
	$date = new DateTime();
    $dateDeb = $date -> format('Y-m-01');
    $dateFin = $date -> format('Y-m-t');

	$date_min = date_format(new DateTime($dateDeb), 'Y-m-d' );
	$date_max = date_format(new DateTime($dateFin), 'Y-m-d' );

	$query = "  SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) AS statut
				FROM pmp_commande, pmp_regroupement
				WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
                /*AND pmp_commande.cmd_status IN ('55')*/
				AND pmp_commande.cmd_status IN ('50','52','55','99')
				AND pmp_regroupement.statut BETWEEN '10' AND '40'
				AND pmp_regroupement.date_grp BETWEEN '$date_min' AND '$date_max'";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

// Recherche client rapide
function getClientRapide(&$co_pmp, $email)
{
	$query = "  SELECT id
				FROM jjj_users
				where email = '$email' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}
