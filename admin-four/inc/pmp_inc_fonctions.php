<?php
function getNbZones(&$co_pmp)
{
	$four_id = $_SESSION["four_id"];

	$query = "  SELECT pmp_fournisseur_zone.id
				FROM pmp_fournisseur_zone
				LEFT JOIN pmp_zone_cp
				ON pmp_fournisseur_zone.id = pmp_zone_cp.zone_id
				LEFT JOIN pmp_utilisateur
				ON pmp_zone_cp.code_postal_id = pmp_utilisateur.code_postal_id
				LEFT JOIN pmp_commande
				ON pmp_utilisateur.user_id = pmp_commande.user_id
				LEFT JOIN pmp_regroupement
				ON pmp_commande.groupe_cmd = pmp_regroupement.id
				WHERE pmp_fournisseur_zone.fournisseur_id = '$four_id'
				AND pmp_zone_cp.actif = '1'
				AND pmp_commande.cmd_status  BETWEEN '10' AND '15'
				AND pmp_commande.cmd_qte > '0'
				AND pmp_commande.groupe_cmd != ''
				AND pmp_regroupement.id_four = '$four_id'
				GROUP BY pmp_fournisseur_zone.id ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getNbCotations(&$co_pmp)
{
	$four_id = $_SESSION["four_id"];

	$query = "  SELECT COUNT(id) AS nb
				FROM pmp_fournisseur_zone
				WHERE fournisseur_id = '$four_id'
				AND cotation = '1' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getNbGroupements(&$co_pmp, $statut)
{
	$four_id = $_SESSION["four_id"];

	$query = "  SELECT COUNT(pmp_regroupement.id) as nb
				FROM pmp_regroupement
				WHERE statut = '$statut'
				AND id_four = '$four_id' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getNbGroupementsTermines(&$co_pmp)
{
	$four_id = $_SESSION["four_id"];

	$query = "  SELECT COUNT(id) as nb
				FROM pmp_regroupement
				WHERE statut BETWEEN 33 AND 40
				AND id_four = '$four_id' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getCommandesCotations(&$co_pmp)
{
	$four_id = $_SESSION["four_id"];
	$query = "  SELECT COUNT(pmp_fournisseur_zone.id) as nb
				FROM pmp_fournisseur_zone
				LEFT JOIN pmp_zone_cp
				ON pmp_fournisseur_zone.id = pmp_zone_cp.zone_id
				LEFT JOIN pmp_utilisateur
				ON pmp_zone_cp.code_postal_id = pmp_utilisateur.code_postal_id
				LEFT JOIN pmp_commande
				ON pmp_utilisateur.user_id = pmp_commande.user_id
				WHERE pmp_fournisseur_zone.fournisseur_id = '$four_id'
				AND pmp_zone_cp.actif = '1'
				AND pmp_commande.cmd_status  = '15'
				AND pmp_commande.cmd_qte > '0'
				GROUP BY pmp_fournisseur_zone.id ";
	$res = my_query($co_pmp, $query);
	return $res;
}
?>
