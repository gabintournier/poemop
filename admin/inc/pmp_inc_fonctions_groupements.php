<?php
function cacul_pourcentage($nombre, $total, $pourcentage)
{
	if ($total == 0) {
		$resultat = "";
	} else {
		$resultat = ($nombre / $total) * $pourcentage;
		return round($resultat); // Arrondi la valeur
	}
}

function getOrdreDeTrie(&$co_pmp, $id_grp)
{
	$query = "  SELECT options
				FROM pmp_regroupement
				WHERE id = '$id_grp' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

// Rechercher groupements
function getFiltresGroupements(&$co_pmp)
{
	if (isset($_SESSION["etat_four"])) {
		$etat1 = $_SESSION["etat_four"];
	} else {
		$etat1 = "10";
	}
	if (isset($_SESSION["etat_four2"])) {
		$etat2 = $_SESSION["etat_four2"];
	} else {
		$etat2 = "10";
	}

	if (!empty($_SESSION["resp"])) {
		$resp = $_SESSION["resp"];
		$resp2 = $_SESSION["resp"];
		$resp3 = $_SESSION["resp"];
	} else {
		$resp = "MAG";
		$resp2 = "STE";
		$resp3 = " ";
	}

	if (!empty($_SESSION["date_min"]) || !empty($_SESSION["date_max"])) {

		if (isset($_SESSION["date_min"])) {
			$date_min = date_format(new DateTime($_SESSION["date_min"]), 'Y-m-d');
		} elseif (isset($_SESSION["date_max"]) && !isset($_SESSION["date_min"])) {
			$date_max = date_format(new DateTime($_SESSION["date_max"]), 'Y-m-d');
			$date_min = date('Y-m-d', strtotime('-16 month', strtotime($date_max)));
		}

		if (isset($_SESSION["date_max"])) {
			$date_max = date_format(new DateTime($_SESSION["date_max"]), 'Y-m-d');
		} else {
			$date_max = (new DateTime())->format('Y-m-d'); // On prend la date du jour
		}


		if (!empty($_SESSION["fournisseur_id"])) {
			$four_id = $_SESSION["fournisseur_id"];
			$query = "  SELECT *
						FROM pmp_regroupement
						WHERE statut BETWEEN '$etat1' AND '$etat2'
						AND id_four = '$four_id'
						AND responsable IN ('$resp', '$resp2', '$resp3')
						AND date_grp BETWEEN '$date_min' AND '$date_max'
						ORDER BY libelle";
			$res = my_query($co_pmp, $query);
			return $res;
		} else {
			$query = "  SELECT *
						FROM pmp_regroupement
						WHERE statut BETWEEN '$etat1' AND '$etat2'
						AND responsable IN ('$resp', '$resp2', '$resp3')
						AND date_grp BETWEEN '$date_min' AND '$date_max'
						ORDER BY libelle";
			$res = my_query($co_pmp, $query);
			return $res;
		}
	} else {
		if (!empty($_SESSION["fournisseur_id"])) {
			$four_id = $_SESSION["fournisseur_id"];
			$query = "  SELECT *
						FROM pmp_regroupement
						WHERE statut BETWEEN '$etat1' AND '$etat2'
						AND id_four = '$four_id'
						AND responsable IN ('$resp', '$resp2', '$resp3')
						ORDER BY libelle";
			$res = my_query($co_pmp, $query);
			return $res;
		} else {
			$query = "  SELECT *
						FROM pmp_regroupement
						WHERE statut BETWEEN '$etat1' AND '$etat2'
						AND responsable IN ('$resp', '$resp2', '$resp3')
						ORDER BY libelle";
			$res = my_query($co_pmp, $query);
			return $res;
		}
	}

}

//TVA
function getTva(&$co_pmp)
{
	$query = "  SELECT tva
				FROM pmp_parametres ";
	$res = my_query($co_pmp, $query);
	$tva = mysqli_fetch_array($res);
	return $tva;
}

//Charger groupement si id_four en get
function getListeRegroupementsFournisseur(&$co_pmp, $id)
{
	$query = "  SELECT *
				FROM pmp_regroupement
    WHERE statut BETWEEN '10' AND '37'   
				AND id_four = '$id' ";
	$res = my_query($co_pmp, $query);
	return $res;
}

//Charger groupement par nuÃ¹ero de facture
function getFiltresGroupementsFacture(&$co_pmp, $n_fact)
{
	$query = "  SELECT *
				FROM pmp_regroupement
				WHERE numfact = '$n_fact'
				ORDER BY date_grp ";
	$res = my_query($co_pmp, $query);
	return $res;
}

if (!empty($_POST["appliquer_terminer"])) {
	$n_fact = $_SESSION["n_fact"];
	$res_fact = getFiltresGroupementsFacture($co_pmp, $n_fact);
	foreach ($res_fact as $key) {
		$id_grp = $key["id"];
		$query = "  UPDATE pmp_regroupement
					SET statut = '37'                   
					WHERE id = '$id_grp'
					AND numfact = '$n_fact' ";
		$res = my_query($co_pmp, $query);
		if ($res) {
			TraceHistoGrpt($co_pmp, $id_grp, 'Statut', '37 - Facturé');
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Les commandes sont bien passée au statut 37 - Facturé";
		}
	}
}

if (!empty($_POST["commandes_termines"])) {
	if (isset($_GET["id_grp"])) {
		$id_grp = $_GET["id_grp"];
		$query = "  SELECT id
					FROM pmp_commande
					WHERE groupe_cmd = '$id_grp'
					AND cmd_status IN ('25', '30') ";
		$res = my_query($co_pmp, $query);
		$num = mysqli_num_rows($res);
		if ($num > 0) {
			foreach ($res as $cmd) {
				$id = $cmd["id"];
				$update = " UPDATE pmp_commande SET cmd_status = '40'
							WHERE id = '$id' ";
				$res = my_query($co_pmp, $update);
				if ($res) {
					TraceHisto($co_pmp, $id, 'Changement de status', '40 - Terminée');
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les commandes sont bien passée au statut 40 - Terminée";
				}
			}
		} else {
			$message_type = "info";
			$message_icone = "fa-exclamation";
			$message_titre = "Info";
			$message = "Il n'y a aucune commande à passer au statut 40 - Terminée.";
		}
	}
}

//Charger les données du bouton "Charger et Calculer"
function getFiltresGroupementsCalculer(&$co_pmp)
{
	$etat1 = isset($_SESSION["etat_four"]) ? $_SESSION["etat_four"] : '10';
	$etat2 = isset($_SESSION["etat_four2"]) ? $_SESSION["etat_four2"] : '10';
	$_SESSION["etat_four"] = $etat1;
	$_SESSION["etat_four2"] = $etat2;

	if (!empty($_SESSION["resp"])) {
		$resp = $_SESSION["resp"];
		$resp2 = $_SESSION["resp"];
		$resp3 = $_SESSION["resp"];
	} else {
		$resp = "MAG";
		$resp2 = "STE";
		$resp3 = " ";
	}

	if (!empty($_SESSION["date_min"]) || !empty($_SESSION["date_max"])) {
		if (isset($_SESSION["date_min"])) {
			$date_min = date_format(new DateTime($_SESSION["date_min"]), 'Y-m-d');
		}

		if (isset($_SESSION["date_max"])) {
			$date_max = date_format(new DateTime($_SESSION["date_max"]), 'Y-m-d');
		} elseif (isset($_SESSION["date_max"]) && !isset($_SESSION["date_min"])) {
			$date_max = date_format(new DateTime($_SESSION["date_max"]), 'Y-m-d');
			$date_min = date('Y-m-d', strtotime('-16 month', strtotime($date_max)));
		} else {
			$date_max = date_format(new DateTime($_SESSION["date_min"]), 'Y-m-d');
		}

		if (!empty($_SESSION["fournisseur_id"])) {
			$four_id = $_SESSION["fournisseur_id"];
			$query = "  SELECT *, pmp_regroupement.id AS groupe_cmd,
						(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '12' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS attachee,
						(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '15' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS groupee,
						(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '17' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS p_propose,
						(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '20' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS p_valide,
						(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '25' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS livrable,
						(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '30' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS livree,
						(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '40' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS terminee_livree,
						(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '40' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS terminee,
						(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '55' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS annulp,
						(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '52' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS annul
						FROM pmp_regroupement, pmp_commande
						WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
						AND pmp_regroupement.statut BETWEEN '$etat1' AND '$etat2'
						AND pmp_regroupement.id_four = '$four_id'
						AND pmp_regroupement.responsable IN ('$resp', '$resp2', '$resp3')
						AND pmp_regroupement.date_grp BETWEEN '$date_min' AND '$date_max'
						GROUP BY pmp_regroupement.id
						ORDER BY date_grp";
			$res = my_query($co_pmp, $query);
			return $res;
		} else {
			$query = "  SELECT *, pmp_regroupement.id AS groupe_cmd,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '12' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS attachee,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '15' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS groupee,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '17' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS p_propose,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '20' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS p_valide,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '25' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS livrable,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '30' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS livree,
						(SELECT ROUND(SUM(pmp_commande.cmd_qtelivre)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '40' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS terminee_livree,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '40' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS terminee,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '55' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS annulp,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '52' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS annul
						FROM pmp_regroupement, pmp_commande
						WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
						AND pmp_regroupement.statut BETWEEN '$etat1' AND '$etat2'
						AND pmp_regroupement.responsable IN ('$resp', '$resp2', '$resp3')
						AND pmp_regroupement.date_grp BETWEEN '$date_min' AND '$date_max'
						GROUP BY pmp_regroupement.id
						ORDER BY date_grp";
			$res = my_query($co_pmp, $query);
			return $res;
		}
	} else {
		if (!empty($_SESSION["fournisseur_id"])) {
			$four_id = $_SESSION["fournisseur_id"];
			$query = "  SELECT *, pmp_regroupement.id AS groupe_cmd,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '12' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS attachee,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '15' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS groupee,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '17' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS p_propose,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '20' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS p_valide,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '25' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS livrable,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '30' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS livree,
						(SELECT ROUND(SUM(pmp_commande.cmd_qtelivre)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '40' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS terminee_livree,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '40' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS terminee,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '55' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS annulp,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '52' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS annul
						FROM pmp_regroupement, pmp_commande
						WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
						AND pmp_regroupement.statut BETWEEN '$etat1' AND '$etat2'
						AND pmp_regroupement.id_four = '$four_id'
						AND pmp_regroupement.responsable IN ('$resp', '$resp2', '$resp3')
						GROUP BY pmp_regroupement.id
						ORDER BY libelle";
			$res = my_query($co_pmp, $query);
			return $res;
		} else {
			$query = "  SELECT *, pmp_regroupement.id AS groupe_cmd,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '12' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS attachee,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '15' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS groupee,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '17' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS p_propose,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '20' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS p_valide,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '25' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS livrable,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '30' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS livree,
						(SELECT ROUND(SUM(pmp_commande.cmd_qtelivre)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '40' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS terminee_livree,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '40' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS terminee,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '55' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS annulp,
						(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '52' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS annul
						FROM pmp_regroupement, pmp_commande
						WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
						AND pmp_regroupement.statut BETWEEN '$etat1' AND '$etat2'
						AND pmp_regroupement.responsable IN ('$resp', '$resp2', '$resp3')
						GROUP BY pmp_regroupement.id
						ORDER BY libelle ";
			$res = my_query($co_pmp, $query);
			return $res;
		}
	}
}

function getFiltresGroupementsCalculerFacture(&$co_pmp, $fact)
{
	$query = "  SELECT *, pmp_regroupement.id AS groupe_cmd,
				(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '12' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS attachee,
				(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '15' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS groupee,
				(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '17' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS p_propose,
				(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '20' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS p_valide,
				(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '25' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS livrable,
				(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '30' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS livree,
				(SELECT ROUND(SUM(pmp_commande.cmd_qtelivre)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '40' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS terminee_livree,
				(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '40' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS terminee,
				(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '55' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS annulp,
				(SELECT ROUND(SUM(pmp_commande.cmd_qte)/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '52' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS annul
				FROM pmp_regroupement, pmp_commande
				WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
				AND pmp_regroupement.numfact = '$fact'
				GROUP BY pmp_regroupement.id
				ORDER BY pmp_regroupement.date_grp ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function GetStatsGroupementFact(&$co_pmp, $statut, $fact)
{
	$query = "  SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) as statut
				FROM pmp_commande, pmp_regroupement
				WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
				AND pmp_commande.cmd_status = '$statut'
				AND pmp_regroupement.numfact = '$fact' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

// Appliquer numéro de facture groupement
if (!empty($_POST["appliquer_facture"])) {
	// Récupération alternative si id_grp[] absent mais ids_grp (liste concaténée) présent
	if ((empty($_POST["id_grp"]) || !is_array($_POST["id_grp"])) && !empty($_POST["ids_grp"])) {
		$ids_parts = array_filter(explode(";", $_POST["ids_grp"]));
		if (!empty($ids_parts)) {
			$_POST["id_grp"] = array_values($ids_parts);
			$_POST["nb_groupement"] = count($ids_parts);
		}
	}

	// ContrÃ´le présence de groupements
	if (empty($_POST["nb_groupement"]) || empty($_POST["id_grp"]) || !is_array($_POST["id_grp"])) {
		$message_type = "info";
		$message_icone = "fa-exclamation";
		$message_titre = "Info";
		$message = "Aucun groupement chargé : le numéro n'a pas été appliqué.";
		return;
	}

	if (isset($_POST["n_fact"])) {
		$id_grp = $_POST["id_grp"];
		$fact = $_POST["n_fact"];
		$nb = (int) $_POST["nb_groupement"];
		$applied = 0;

		for ($i = 0; $i < $nb; $i++) {
			if (!isset($id_grp[$i])) {
				continue;
			}
			$id = $id_grp[$i];
			$query = "  UPDATE pmp_regroupement SET numfact = '$fact'
                        WHERE id = '$id' ";
			$res = my_query($co_pmp, $query);
			if ($res) {
				$applied++;
				TraceHistoGrpt($co_pmp, $id, 'Numéro de Facture', $fact);
			}
		}

		if ($applied > 0) {
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le numéro de facture a été appliqué à " . $applied . " groupement(s).";
			$_SESSION["n_fact"] = $fact;
			return $res;
		} else {
			$message_type = "info";
			$message_icone = "fa-exclamation";
			$message_titre = "Info";
			$message = "Aucun groupement n'a été mis à jour (liste vide ou non transmise).";
		}
	}
}

//Charger les stats du bas de tableau de 'Charger et calculer'
function GetStatsGroupement(&$co_pmp, $statut)
{
	$etat1 = isset($_SESSION["etat_four"]) ? $_SESSION["etat_four"] : '10';
	$etat2 = isset($_SESSION["etat_four2"]) ? $_SESSION["etat_four2"] : '10';
	$_SESSION["etat_four"] = $etat1;
	$_SESSION["etat_four2"] = $etat2;

	if (!empty($_SESSION["resp"])) {
		$resp = $_SESSION["resp"];
		$resp2 = $_SESSION["resp"];
		$resp3 = $_SESSION["resp"];
	} else {
		$resp = "MAG";
		$resp2 = "STE";
		$resp3 = " ";
	}

	// if($statut >= '30') { $qte = 'ROUND(SUM(pmp_commande.cmd_qtelivre)/1000)'; } else { $qte = 'ROUND(SUM(pmp_commande.cmd_qte)/1000)'; }

	if (!empty($_SESSION["date_min"]) && !empty($_SESSION["date_max"])) {
		$date_min = date_format(new DateTime($_SESSION["date_min"]), 'Y-m-d');
		$date_max = date_format(new DateTime($_SESSION["date_max"]), 'Y-m-d');

		if (!empty($_SESSION["fournisseur_id"])) {
			$four_id = $_SESSION["fournisseur_id"];
			$query = "  SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) as statut
						FROM pmp_commande, pmp_regroupement
						WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
						AND pmp_commande.cmd_status = '$statut'
						AND pmp_regroupement.statut BETWEEN '$etat1' AND '$etat2'
						AND pmp_regroupement.id_four = '$four_id'
						AND pmp_regroupement.responsable IN ('$resp', '$resp2', '$resp3')
						AND pmp_regroupement.date_grp BETWEEN '$date_min' AND '$date_max'";
			$res = my_query($co_pmp, $query);
			$res = mysqli_fetch_array($res);
			return $res;
		} else {
			$query = "  SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) as statut
						FROM pmp_commande, pmp_regroupement
						WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
						AND pmp_commande.cmd_status = '$statut'
						AND pmp_regroupement.statut BETWEEN '$etat1' AND '$etat2'
						AND pmp_regroupement.responsable IN ('$resp', '$resp2', '$resp3')
						AND pmp_regroupement.date_grp BETWEEN '$date_min' AND '$date_max'";
			$res = my_query($co_pmp, $query);
			$res = mysqli_fetch_array($res);
			return $res;
		}
	} else {
		if (!empty($_SESSION["fournisseur_id"])) {
			$four_id = $_SESSION["fournisseur_id"];
			$query = "  SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) as statut
						FROM pmp_commande, pmp_regroupement
						WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
						AND pmp_commande.cmd_status = '$statut'
						AND pmp_regroupement.statut BETWEEN '$etat1' AND '$etat2'
						AND pmp_regroupement.id_four = '$four_id'
						AND pmp_regroupement.responsable IN ('$resp', '$resp2', '$resp3') ";
			$res = my_query($co_pmp, $query);
			$res = mysqli_fetch_array($res);
			return $res;
		} else {
			$query = "  SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) as statut
						FROM pmp_commande, pmp_regroupement
						WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
						AND pmp_commande.cmd_status = '$statut'
						AND pmp_regroupement.statut BETWEEN '$etat1' AND '$etat2'
						AND pmp_regroupement.responsable IN ('$resp', '$resp2', '$resp3') ";
			$res = my_query($co_pmp, $query);
			$res = mysqli_fetch_array($res);
			return $res;
		}
	}
}


//Charger le mois en cours
function GetMoisEnCours(&$co_pmp)
{
	$date_min = date_format(new DateTime($_SESSION["date_min"]), 'Y-m-d');
	$date_max = date_format(new DateTime($_SESSION["date_max"]), 'Y-m-d');

	$query = "  SELECT *, pmp_regroupement.id AS groupe_cmd,
				(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '12' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS attachee,
				(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '15' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS groupee,
				(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '17' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS p_propose,
				(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '20' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS p_valide,
				(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '25' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS livrable,
				(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '30' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS livree,
				(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '40' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS terminee,
				(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '55' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS annulp,
				(SELECT ROUND(SUM(if(pmp_commande.cmd_qtelivre=0,pmp_commande.cmd_qte,pmp_commande.cmd_qtelivre))/1000) FROM pmp_commande WHERE pmp_commande.cmd_status = '52' AND pmp_commande.groupe_cmd = pmp_regroupement.id ) AS annul
				FROM pmp_regroupement, pmp_commande
				WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
				AND pmp_regroupement.statut BETWEEN '10' AND '37'
				AND pmp_regroupement.date_grp BETWEEN '$date_min' AND '$date_max'
				GROUP BY pmp_regroupement.id";
	$res = my_query($co_pmp, $query);
	return $res;
}

//Afficher les regroupements par défault au statut 10 créer
function getListeRegroupementsCréer(&$co_pmp)
{
	$query = "  SELECT *
				FROM pmp_regroupement
				WHERE statut = '10'
				ORDER BY libelle";
	$res = my_query($co_pmp, $query);
	return $res;
}

// Exporter les groupements
function ExporterListeGrpt(&$co_pmp, $res)
{
	$date = date("Y-m-d");
	$fichier = fopen('export/export-groupements' . $date . '.xls', 'w');
	fclose($fichier);

	$fichier = fopen('export/export-groupements' . $date . '.xls', 'w+');
	fwrite($fichier, chr(239) . chr(187) . chr(191));
	$chaine = "";
	$col = "N°;Etat;Libelle;Date;Vol Ord;Vol Sup;Resp;";
	fwrite($fichier, $col . "\r\n");

	while ($export = mysqli_fetch_array($res)) {
		$chaine = '"' . $export["id"] . '";"' . $export["statut"] . '";"' . $export["libelle"] . '";"' . $export["date_grp"] . '";"' . $export["volord"] . '";"' . $export["volsup"] . '";"' . $export["responsable"] . '"';

		fwrite($fichier, $chaine . "\r\n");
	}

	fclose($fichier);

	header('Location: /admin/export/export-groupements' . $date . '.xls');
	header('Content-Encoding: UTF-8');
	header('Content-type: text/xls; charset=UTF-8');
}

function ExporterListeGrptStats(&$co_pmp, $res)
{
	$date = date("Y-m-d");
	$fichier = fopen('export/export-groupements' . $date . '.xls', 'w');
	fclose($fichier);

	$fichier = fopen('export/export-groupements' . $date . '.xls', 'w+');
	fwrite($fichier, chr(239) . chr(187) . chr(191));
	$chaine = "";
	$col = "N°;Etat;Libelle;Date;Resp;Attaché;Groupé;Prix P; Prix V;Livrable;Livrée;Terminée;Annulée P;% Annulée P;Annulée;% Annulée";
	fwrite($fichier, $col . "\r\n");

	while ($export = mysqli_fetch_array($res)) {
		$total_grp_ap = $export["p_valide"] + $export["livrable"] + $export["livree"] + $export["terminee"] + $export["annulp"];
		$total_grp_a = $export["p_valide"] + $export["livrable"] + $export["livree"] + $export["terminee"] + $export["annul"];


		$pannulp_grp = cacul_pourcentage($export["annulp"], $total_grp_ap, '100');
		$pannul_grp = cacul_pourcentage($export["annul"], $total_grp_a, '100');

		if ($pannulp_grp == 0) {
			$pourc_annulp_grp = "";
		} else {
			$pourc_annulp_grp = $pannulp_grp;
		}
		if ($pannul_grp == 0) {
			$pourc_annul_grp = "";
		} else {
			$pourc_annul_grp = $pannul_grp;
		}

		$chaine = '"' . $export["id"] . '";"' . $export["statut"] . '";"' . $export["libelle"] . '";"' . $export["date_grp"]
			. '";"' . $export["responsable"] . '";"' . $export["attachee"] . '";"' . $export["groupee"] . '";"' . $export["p_propose"]
			. '";"' . $export["p_valide"] . '";"' . $export["p_valide"] . '";"' . $export["livree"] . '";"' . $export["terminee"]
			. '";"' . $export["annulp"] . '";"' . $pourc_annulp_grp . '";"' . $export["annul"] . '";"' . $pourc_annul_grp . '"';

		fwrite($fichier, $chaine . "\r\n");
	}

	fclose($fichier);

	header('Location: /admin/export/export-groupements' . $date . '.xls');
	header('Content-Encoding: UTF-8');
	header('Content-type: text/xls; charset=UTF-8');
}

// Exporter les commandes du groupement


function ExporterCommandesGrpt(&$co_pmp, $res)
{
	$date = date("Y-m-d");
	$id_grp = $_GET["id_grp"];
	$fichier = fopen('export/export-commande-groupements-' . $id_grp . '-' . $date . '.xls', 'w');
	fclose($fichier);

	$fichier = fopen('export/export-commande-groupements-' . $id_grp . '-' . $date . '.xls', 'w');
	fwrite($fichier, chr(239) . chr(187) . chr(191));
	$chaine = "";
	$col = "Nom;Prénom;Adresse;CP;Ville;Date;Qté;Livré;Prix O; Prix S;Fuel;Statut;PMP;AF;FMC;FR;FM;Mail;Commentaire Fournisseur;Statut Client;Tel1;Tel2;";
	fwrite($fichier, $col . "\r\n");

	while ($export = mysqli_fetch_array($res)) {
		if ($export["cmd_typefuel"] == 1) {
			$type = 'O';
		}
		if ($export["cmd_typefuel"] == 2) {
			$type = 'S';
		}
		if ($export["cmd_typefuel"] == 3) {
			$type = 'GNR';
		}
		if ($export["cmd_status"] == 0) {
			$status = " 0 - Pas de commande";
		}
		if ($export["cmd_status"] == 10) {
			$status = " 10 - Utilisateur";
		}
		if ($export["cmd_status"] == 12) {
			$status = " 12 - Attaché";
		}
		if ($export["cmd_status"] == 13) {
			$status = " 13 - Proposé";
		}
		if ($export["cmd_status"] == 15) {
			$status = " 15 - Groupée";
		}
		if ($export["cmd_status"] == 17) {
			$status = " 17 - P. Proposé";
		}
		if ($export["cmd_status"] == 20) {
			$status = " 20 - Validé";
		}
		if ($export["cmd_status"] == 25) {
			$status = " 25 - Livrable";
		}
		if ($export["cmd_status"] == 30) {
			$status = " 30 - Livré";
		}
		if ($export["cmd_status"] == 40) {
			$status = " 37 - Facturée";
		}
		if ($export["cmd_status"] == 50) {
			$status = " 50 - Annulée";
		}
		if ($export["cmd_status"] == 52) {
			$status = " 52 - Annulée/livraison";
		}
		if ($export["cmd_status"] == 55) {
			$status = " 55 - Annulée/prix";
		}
		if ($export["cmd_status"] == 99) {
			$status = " 99 - Annulée/Compte désactivé";
		}
		// $dateg = date_format(new DateTime($cmd["cmd_dt"]), 'd/m/Y' );

		/*
		$chaine = '"' . $export["nom"] .'";"' . $export["prenom"] . '";"' . $export["adresse"] . '";"' . $export["code_postal"] . '";"' . $export["ville"] . '";"' . $dateg .  '";"' . $export["cmd_qte"] . '";"'
		. $export["cmd_qtelivre"] . '";"' . $export["cmd_prix_ord"] . '";"' . $export["cmd_prix_sup"] . '";"' . $type . '";"' . $status . '";"' . $export["cmd_prixpmp"] . '";"' . $export["cmd_prixaf"] . '";"'
		. $export["cmd_prixfmc"] . '";"' . $export["cmd_prixfr"] . '";"' . $export["cmd_prixfm"] . '";"' . $export["email"] . '";"' . $export["cmd_commentfour"] . '";" statut";"' . $export["tel_fixe"] . '";"'
		. $export["tel_port"] . '" ';
		*/
		$chaine = '"' . $export["nom"] . '";"' . $export["prenom"] . '";"' . $export["adresse"] . '";"' . $export["code_postal"] . '";"' . $export["ville"] . '";"' . $export["cmd_qte"] . '";"'
			. $export["cmd_qtelivre"] . '";"' . $export["cmd_prix_ord"] . '";"' . $export["cmd_prix_sup"] . '";"' . $type . '";"' . $status . '";"' . $export["cmd_prixpmp"] . '";"' . $export["cmd_prixaf"] . '";"'
			. $export["cmd_prixfmc"] . '";"' . $export["cmd_prixfr"] . '";"' . $export["cmd_prixfm"] . '";"' . $export["email"] . '";"' . $export["cmd_commentfour"] . '";" statut";"' . $export["tel_fixe"] . '";"'
			. $export["tel_port"] . '" ';

		fwrite($fichier, $chaine . "\r\n");
	}

	fclose($fichier);

	header('Location: /admin/export/export-commande-groupements-' . $id_grp . '-' . $date . '.xls');
	header('Content-Encoding: UTF-8');
	header('Content-type: text/xls; charset=UTF-8');
}

function getCommandeUtilisateur(&$co_pmp, $id)
{
	$query = "
        SELECT id, cmd_status, groupe_cmd, cmd_typefuel, cmd_qte, cmd_prix_ord, cmd_prix_sup, cmd_comment, user_id
        FROM pmp_commande
        WHERE user_id= '" . mysqli_real_escape_string($co_pmp, $id) . "'
        AND cmd_status < 40
        ORDER BY id DESC
        LIMIT 1
    ";
	$res = my_query($co_pmp, $query);
	if (!$res) {
		return null; // requÃªte échouée
	}
	$cmd = mysqli_fetch_array($res);
	if (!$cmd) {
		return null; // aucune ligne trouvée
	}
	return $cmd;
}


function getCommandeUtilisateurEnCours(&$co_pmp, $id)
{
	$query = "	SELECT id, cmd_status, groupe_cmd, cmd_typefuel, cmd_qte, cmd_prix_ord, cmd_prix_sup, cmd_comment, user_id
			FROM pmp_commande
			WHERE user_id= '" . mysqli_real_escape_string($co_pmp, $id) . "'
			AND cmd_status <= 20
			ORDER BY id DESC ";
	$res = my_query($co_pmp, $query);
	$cmd = mysqli_fetch_array($res);
	return $cmd;
}

/* Liste commandes tableau */
function getCommandesGroupementsCache(&$co_pmp, $id_grp)
{
	$trie = getOrdreDeTrie($co_pmp, $id_grp);
	$arr = str_split($trie["options"], 2);

	$ordre1 = "pmp_commande.cmd_dt";
	$ordre2 = "pmp_commande.cmd_dt";

	if (isset($trie["options"])) {
		if ($arr[0] == "00") {
			$ordre1 = "pmp_commande.cmd_dt";
		} elseif ($arr[0] == "01") {
			$ordre1 = "jjj_users.name";
		} elseif ($arr[0] == "02") {
			$ordre1 = "pmp_utilisateur.code_postal";
		} elseif ($arr[0] == "03") {
			$ordre1 = "pmp_utilisateur.ville";
		} elseif ($arr[0] == "04") {
			$ordre1 = "pmp_commande.cmd_status";
		} elseif ($arr[0] == "05") {
			$ordre1 = "pmp_commande.cmd_qte";
		} elseif ($arr[0] == "06") {
			$ordre1 = "jjj_users.email";
		} elseif ($arr[0] == "07") {
			$ordre1 = "pmp_commande.cmd_prixfm";
		} elseif ($arr[0] == "08") {
			$ordre1 = "pmp_commande.cmd_prixfr";
		} else {
			$ordre2 = "pmp_commande.cmd_dt";
		}

		if ($arr[1] == "00") {
			$ordre2 = "pmp_commande.cmd_dt";
		} elseif ($arr[1] == "01") {
			$ordre2 = "jjj_users.name";
		} elseif ($arr[1] == "02") {
			$ordre2 = "pmp_utilisateur.code_postal";
		} elseif ($arr[1] == "03") {
			$ordre2 = "pmp_utilisateur.ville";
		} elseif ($arr[1] == "04") {
			$ordre2 = "pmp_commande.cmd_status";
		} elseif ($arr[1] == "05") {
			$ordre2 = "pmp_commande.cmd_qte";
		} elseif ($arr[1] == "06") {
			$ordre2 = "jjj_users.email";
		} elseif ($arr[1] == "07") {
			$ordre2 = "pmp_commande.cmd_prixfm";
		} elseif ($arr[1] == "08") {
			$ordre2 = "pmp_commande.cmd_prixfr";
		} else {
			$ordre2 = "pmp_commande.cmd_dt";
		}
	} else {
		$ordre1 = "pmp_commande.cmd_dt";
		$ordre2 = "pmp_commande.cmd_dt";
	}

	$query = "  SELECT pmp_regroupement.id, pmp_commande.cmd_dt, pmp_commande.cmd_typefuel, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, pmp_commande.cmd_prix_ord, pmp_commande.cmd_prix_sup, pmp_commande.cmd_status, pmp_commande.cmd_prixpmp,
				pmp_commande.cmd_prixaf, pmp_commande.cmd_prixfmc, pmp_commande.cmd_prixfr, pmp_commande.cmd_prixfm, pmp_commande.cmd_commentfour, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal,
				jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_commande.id AS id_cmd, jjj_users.name
				FROM pmp_regroupement, pmp_commande, pmp_utilisateur, jjj_users
				WHERE pmp_regroupement.id = pmp_commande.groupe_cmd
				AND pmp_commande.user_id = pmp_utilisateur.user_id
				AND pmp_utilisateur.user_id = jjj_users.id
				AND pmp_regroupement.id = '$id_grp'
				AND pmp_commande.cmd_status != '50'
				AND pmp_commande.cmd_status != '52'
				AND pmp_commande.cmd_status != '55'
				AND pmp_commande.cmd_status != '99'
				ORDER BY $ordre1 ASC, $ordre2 ASC";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getCommandes(&$co_pmp, $id_grp)
{
	$trie = getOrdreDeTrie($co_pmp, $id_grp);
	$arr = str_split($trie["options"], 2);

	$ordre1 = "pmp_commande.cmd_dt";
	$ordre2 = "pmp_commande.cmd_dt";

	if (isset($trie["options"])) {
		if ($arr[0] == "00") {
			$ordre1 = "pmp_commande.cmd_dt";
		} elseif ($arr[0] == "01") {
			$ordre1 = "jjj_users.name";
		} elseif ($arr[0] == "02") {
			$ordre1 = "pmp_utilisateur.code_postal";
		} elseif ($arr[0] == "03") {
			$ordre1 = "pmp_utilisateur.ville";
		} elseif ($arr[0] == "04") {
			$ordre1 = "pmp_commande.cmd_status";
		} elseif ($arr[0] == "05") {
			$ordre1 = "pmp_commande.cmd_qte";
		} elseif ($arr[0] == "06") {
			$ordre1 = "jjj_users.email";
		} elseif ($arr[0] == "07") {
			$ordre1 = "pmp_commande.cmd_prixfm";
		} elseif ($arr[0] == "08") {
			$ordre1 = "pmp_commande.cmd_prixfr";
		} else {
			$ordre2 = "pmp_commande.cmd_dt";
		}

		if ($arr[1] == "00") {
			$ordre2 = "pmp_commande.cmd_dt";
		} elseif ($arr[1] == "01") {
			$ordre2 = "jjj_users.name";
		} elseif ($arr[1] == "02") {
			$ordre2 = "pmp_utilisateur.code_postal";
		} elseif ($arr[1] == "03") {
			$ordre2 = "pmp_utilisateur.ville";
		} elseif ($arr[1] == "04") {
			$ordre2 = "pmp_commande.cmd_status";
		} elseif ($arr[1] == "05") {
			$ordre2 = "pmp_commande.cmd_qte";
		} elseif ($arr[1] == "06") {
			$ordre2 = "jjj_users.email";
		} elseif ($arr[1] == "07") {
			$ordre2 = "pmp_commande.cmd_prixfm";
		} elseif ($arr[1] == "08") {
			$ordre2 = "pmp_commande.cmd_prixfr";
		} else {
			$ordre2 = "pmp_commande.cmd_dt";
		}
	} else {
		$ordre1 = "pmp_commande.cmd_dt";
		$ordre2 = "pmp_commande.cmd_dt";
	}

	$query = "  SELECT pmp_regroupement.id, pmp_commande.cmd_dt, pmp_commande.cmd_typefuel, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, pmp_commande.cmd_prix_ord, pmp_commande.cmd_prix_sup, pmp_commande.cmd_status, pmp_commande.cmd_prixpmp,
				pmp_commande.cmd_prixaf, pmp_commande.cmd_prixfmc, pmp_commande.cmd_prixfr, pmp_commande.cmd_prixfm, pmp_commande.cmd_commentfour, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal,
				jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_commande.id AS id_cmd, jjj_users.name
				FROM pmp_regroupement, pmp_commande, pmp_utilisateur, jjj_users
				WHERE pmp_regroupement.id = pmp_commande.groupe_cmd
				AND pmp_commande.user_id = pmp_utilisateur.user_id
				AND pmp_utilisateur.user_id = jjj_users.id
				AND pmp_regroupement.id = '$id_grp'
				ORDER BY $ordre1 ASC, $ordre2 ASC";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getCommandesGroupementsSansProposees(&$co_pmp, $id_grp)
{
	$trie = getOrdreDeTrie($co_pmp, $id_grp);
	$arr = str_split($trie["options"], 2);

	$ordre1 = "pmp_commande.cmd_dt";
	$ordre2 = "pmp_commande.cmd_dt";

	if (isset($trie["options"])) {
		if ($arr[0] == "00") {
			$ordre1 = "pmp_commande.cmd_dt";
		} elseif ($arr[0] == "01") {
			$ordre1 = "jjj_users.name";
		} elseif ($arr[0] == "02") {
			$ordre1 = "pmp_utilisateur.code_postal";
		} elseif ($arr[0] == "03") {
			$ordre1 = "pmp_utilisateur.ville";
		} elseif ($arr[0] == "04") {
			$ordre1 = "pmp_commande.cmd_status";
		} elseif ($arr[0] == "05") {
			$ordre1 = "pmp_commande.cmd_qte";
		} elseif ($arr[0] == "06") {
			$ordre1 = "jjj_users.email";
		} elseif ($arr[0] == "07") {
			$ordre1 = "pmp_commande.cmd_prixfm";
		} elseif ($arr[0] == "08") {
			$ordre1 = "pmp_commande.cmd_prixfr";
		} else {
			$ordre2 = "pmp_commande.cmd_dt";
		}

		if ($arr[1] == "00") {
			$ordre2 = "pmp_commande.cmd_dt";
		} elseif ($arr[1] == "01") {
			$ordre2 = "jjj_users.name";
		} elseif ($arr[1] == "02") {
			$ordre2 = "pmp_utilisateur.code_postal";
		} elseif ($arr[1] == "03") {
			$ordre2 = "pmp_utilisateur.ville";
		} elseif ($arr[1] == "04") {
			$ordre2 = "pmp_commande.cmd_status";
		} elseif ($arr[1] == "05") {
			$ordre2 = "pmp_commande.cmd_qte";
		} elseif ($arr[1] == "06") {
			$ordre2 = "jjj_users.email";
		} elseif ($arr[1] == "07") {
			$ordre2 = "pmp_commande.cmd_prixfm";
		} elseif ($arr[1] == "08") {
			$ordre2 = "pmp_commande.cmd_prixfr";
		} else {
			$ordre2 = "pmp_commande.cmd_dt";
		}
	} else {
		$ordre1 = "pmp_commande.cmd_status";
		$ordre2 = "jjj_users.name";
	}

	$query = "  SELECT pmp_regroupement.id, pmp_commande.cmd_dt, pmp_commande.cmd_typefuel, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, pmp_commande.cmd_prix_ord, pmp_commande.cmd_prix_sup, pmp_commande.cmd_status, pmp_commande.cmd_prixpmp,
				pmp_commande.cmd_prixaf, pmp_commande.cmd_prixfmc, pmp_commande.cmd_prixfr, pmp_commande.cmd_prixfm, pmp_commande.cmd_commentfour, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal,
				jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_commande.id AS id_cmd, jjj_users.name
				FROM pmp_regroupement, pmp_commande, pmp_utilisateur, jjj_users
				WHERE pmp_regroupement.id = pmp_commande.groupe_cmd
				AND pmp_commande.user_id = pmp_utilisateur.user_id
				AND pmp_utilisateur.user_id = jjj_users.id
				AND pmp_regroupement.id = '$id_grp'
				AND pmp_commande.cmd_status != '50'
				AND pmp_commande.cmd_status != '52'
				AND pmp_commande.cmd_status != '55'
				AND pmp_commande.cmd_status != '13'
				AND pmp_commande.cmd_status != '99'
				ORDER BY $ordre1 ASC, $ordre2 ASC";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getCommandesGroupementsActif(&$co_pmp, $id_grp)
{
	$query = "  SELECT pmp_regroupement.id, pmp_commande.cmd_dt, pmp_commande.cmd_typefuel, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, pmp_commande.cmd_prix_ord, pmp_commande.cmd_prix_sup, pmp_commande.cmd_status, pmp_commande.cmd_prixpmp,
				pmp_commande.cmd_prixaf, pmp_commande.cmd_prixfmc, pmp_commande.cmd_prixfr, pmp_commande.cmd_prixfm, pmp_commande.cmd_commentfour, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal,
				jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_commande.id AS id_cmd, jjj_users.name
				FROM pmp_regroupement, pmp_commande, pmp_utilisateur, jjj_users
				WHERE pmp_regroupement.id = pmp_commande.groupe_cmd
				AND pmp_commande.user_id = pmp_utilisateur.user_id
				AND pmp_utilisateur.user_id = jjj_users.id
				AND pmp_regroupement.id = '$id_grp'
				AND pmp_commande.cmd_status != '50'
				AND pmp_commande.cmd_status != '52'
				AND pmp_commande.cmd_status != '55'
				AND pmp_commande.cmd_status != '99'
				-- AND pmp_commande.cmd_status != '13'
				AND pmp_utilisateur.actif IN('2', '3')
				ORDER BY pmp_commande.id ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getCommandesGroupements(&$co_pmp, $id_grp)
{

	$trie = getOrdreDeTrie($co_pmp, $id_grp);
	$arr = str_split($trie["options"], 2);

	$ordre1 = "pmp_commande.cmd_status";
	$ordre2 = "jjj_users.name";

	if (isset($trie["options"])) {
		if ($arr[0] == "00") {
			$ordre1 = "pmp_commande.cmd_dt";
		} elseif ($arr[0] == "01") {
			$ordre1 = "jjj_users.name";
		} elseif ($arr[0] == "02") {
			$ordre1 = "pmp_utilisateur.code_postal";
		} elseif ($arr[0] == "03") {
			$ordre1 = "pmp_utilisateur.ville";
		} elseif ($arr[0] == "04") {
			$ordre1 = "pmp_commande.cmd_status";
		} elseif ($arr[0] == "05") {
			$ordre1 = "pmp_commande.cmd_qte";
		} elseif ($arr[0] == "06") {
			$ordre1 = "jjj_users.email";
		} elseif ($arr[0] == "07") {
			$ordre1 = "pmp_commande.cmd_prixfm";
		} elseif ($arr[0] == "08") {
			$ordre1 = "pmp_commande.cmd_prixfr";
		} else {
			$ordre2 = "pmp_commande.cmd_dt";
		}

		if ($arr[1] == "00") {
			$ordre2 = "pmp_commande.cmd_dt";
		} elseif ($arr[1] == "01") {
			$ordre2 = "jjj_users.name";
		} elseif ($arr[1] == "02") {
			$ordre2 = "pmp_utilisateur.code_postal";
		} elseif ($arr[1] == "03") {
			$ordre2 = "pmp_utilisateur.ville";
		} elseif ($arr[1] == "04") {
			$ordre2 = "pmp_commande.cmd_status";
		} elseif ($arr[1] == "05") {
			$ordre2 = "pmp_commande.cmd_qte";
		} elseif ($arr[1] == "06") {
			$ordre2 = "jjj_users.email";
		} elseif ($arr[1] == "07") {
			$ordre2 = "pmp_commande.cmd_prixfm";
		} elseif ($arr[1] == "08") {
			$ordre2 = "pmp_commande.cmd_prixfr";
		} else {
			$ordre2 = "pmp_commande.cmd_dt";
		}
	} else {
		$ordre1 = "pmp_commande.cmd_status";
		$ordre2 = "jjj_users.name";
	}

	$query = "  SELECT pmp_regroupement.id, pmp_commande.cmd_dt, pmp_commande.cmd_typefuel, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, pmp_commande.cmd_prix_ord, pmp_commande.cmd_prix_sup, pmp_commande.cmd_status, pmp_commande.cmd_prixpmp,
				pmp_commande.cmd_prixaf, pmp_commande.cmd_prixfmc, pmp_commande.cmd_prixfr, pmp_commande.cmd_prixfm, pmp_commande.cmd_commentfour, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal,
				jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_commande.id AS id_cmd, jjj_users.name
				FROM pmp_regroupement, pmp_commande, pmp_utilisateur, jjj_users
				WHERE pmp_regroupement.id = pmp_commande.groupe_cmd
				AND pmp_commande.user_id = pmp_utilisateur.user_id
				AND pmp_utilisateur.user_id = jjj_users.id
				AND pmp_regroupement.id = '$id_grp'
				AND pmp_commande.cmd_status != '13'
				ORDER BY $ordre1 ASC, $ordre2 ASC";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getCommandesGroupementsEnvoisMail(&$co_pmp, $id_grp)
{
	$query = "SELECT pmp_regroupement.id, pmp_commande.cmd_dt, pmp_commande.cmd_typefuel, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, pmp_commande.cmd_prix_ord, pmp_commande.cmd_prix_sup, pmp_commande.cmd_status, pmp_commande.cmd_prixpmp,
				pmp_commande.cmd_prixaf, pmp_commande.cmd_prixfmc, pmp_commande.cmd_prixfr, pmp_commande.cmd_prixfm, pmp_commande.cmd_commentfour, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal,
				jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_commande.id AS id_cmd, jjj_users.name
				FROM pmp_regroupement, pmp_commande, pmp_utilisateur, jjj_users
				WHERE pmp_regroupement.id = pmp_commande.groupe_cmd
				AND pmp_commande.user_id = pmp_utilisateur.user_id
				AND pmp_utilisateur.user_id = jjj_users.id
				AND pmp_regroupement.id = '$id_grp'
				AND    pmp_utilisateur.inscrit = 1
				AND pmp_utilisateur.bloquemail != 1
				AND pmp_commande.cmd_status != '50'
				AND pmp_commande.cmd_status != '52'
				AND pmp_commande.cmd_status != '55'
				AND pmp_commande.cmd_status != '99'
				AND pmp_utilisateur.disabled_account != 1
				ORDER BY pmp_commande.id";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getCommandesGroupementsExportStatut(&$co_pmp, $id_grp, $status1, $status2)
{
	$query = "  SELECT pmp_regroupement.id, pmp_commande.cmd_dt, pmp_commande.cmd_typefuel, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, pmp_commande.cmd_prix_ord, pmp_commande.cmd_prix_sup, pmp_commande.cmd_status, pmp_commande.cmd_prixpmp,
				pmp_commande.cmd_prixaf, pmp_commande.cmd_prixfmc, pmp_commande.cmd_prixfr, pmp_commande.cmd_prixfm, pmp_commande.cmd_commentfour, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal,
				jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_commande.id AS id_cmd, jjj_users.name
				FROM pmp_regroupement, pmp_commande, pmp_utilisateur, jjj_users
				WHERE pmp_regroupement.id = pmp_commande.groupe_cmd
				AND pmp_commande.user_id = pmp_utilisateur.user_id
				AND pmp_utilisateur.user_id = jjj_users.id
				AND pmp_regroupement.id = '$id_grp'
				AND pmp_commande.cmd_status BETWEEN $status1 AND $status2
				ORDER BY jjj_users.name, pmp_utilisateur.prenom";
	$res = my_query($co_pmp, $query);
	return $res;
}

// Remplir les information de Nombre de litre Total
function getNombreDeLitre(&$co_pmp, $id_grp, $status)
{
	$query = "  SELECT COUNT(id) AS total, SUM(cmd_qte) as sum_qte
				FROM pmp_commande
				WHERE groupe_cmd = '$id_grp'
				AND cmd_status = '$status' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getNombreDeLitreLivree(&$co_pmp, $id_grp, $status)
{
	$query = "  SELECT COUNT(id) AS total, SUM(if(cmd_qtelivre=0,cmd_qte,cmd_qtelivre)) as sum_qte
				FROM pmp_commande
				WHERE groupe_cmd = '$id_grp'
				AND cmd_status = '$status' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

//Ajouter commande au TABLEAU
// if(!empty($_POST["valider_cmd"]))
// {
// 	$id = $_POST["n_id_cmd"];
// 	echo $id;
// 	// $id = $_POST["n_id_cmd"];
// 	// $id_grp = $_GET["id_grp"];
// 	// $query = "  UPDATE pmp_commande
// 	// 			SET groupe_cmd = '$id_grp'
// 	// 			WHERE id = '$id' ";
// 	// $res = my_query($co_pmp, $query);
// 	// if($res)
// 	// {
// 	// 	$message_type = "success";
// 	// 	$message_icone = "fa-check";
// 	// 	$message_titre = "Succès";
// 	// 	$message = "OK";
// 	// 	return $res;
// 	// }
// }

/* Liste commandes tableau Statut */
function getCommandesGroupementsStatut(&$co_pmp, $id_grp, $statut)
{
	$query = "  SELECT pmp_regroupement.id, pmp_commande.cmd_dt, pmp_commande.cmd_typefuel, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, pmp_commande.cmd_prix_ord, pmp_commande.cmd_prix_sup, pmp_commande.cmd_status, pmp_commande.cmd_prixpmp,
				pmp_commande.cmd_prixaf, pmp_commande.cmd_prixfmc, pmp_commande.cmd_prixfr, pmp_commande.cmd_prixfm, pmp_commande.cmd_commentfour, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal,
				jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_commande.id AS id_cmd
				FROM pmp_regroupement, pmp_commande, pmp_utilisateur, jjj_users
				WHERE pmp_regroupement.id = pmp_commande.groupe_cmd
				AND pmp_commande.user_id = pmp_utilisateur.user_id
				AND jjj_users.id = pmp_utilisateur.user_id
				AND pmp_regroupement.id = '$id_grp'
				AND pmp_commande.cmd_status = '$statut' ";
	$res = my_query($co_pmp, $query);
	return $res;
}

/* Tableau plage prix */
function getPlagePrix(&$co_pmp, $id_grp)
{
	$query = "  SELECT *
				FROM pmp_regrp_plages
				WHERE regrp_id = '$id_grp'
				ORDER BY volume";
	$res = my_query($co_pmp, $query);
	return $res;
}

/* Récup quantité tableau */
function getQuantiteVolumeOrdTableau(&$co_pmp, $id_grp)
{
	$query = "  SELECT SUM(cmd_qtelivre) AS qte_ord, id
				FROM pmp_commande
				WHERE groupe_cmd = '$id_grp'
				AND cmd_typefuel = '1'
				AND cmd_status != '50'
				AND cmd_status != '51'
				AND cmd_status != '99'
				AND cmd_status != '55' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}
function getQuantiteVolumeSupTableau(&$co_pmp, $id_grp)
{
	$query = "  SELECT SUM(cmd_qtelivre) AS qte_sup
				FROM pmp_commande
				WHERE groupe_cmd = '$id_grp'
				AND cmd_typefuel = '2'
				AND cmd_status != 50
				AND cmd_status != 51
				AND cmd_status != 55";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getQuantiteVolumeStatusGrp(&$co_pmp, $id_grp, $fuel, $statut1, $statut2)
{
	$query = "  SELECT SUM(cmd_qte) AS qte
				FROM pmp_commande
				WHERE groupe_cmd = '$id_grp'
				AND cmd_typefuel = '$fuel'
				AND cmd_status IN ('$statut1', '$statut2')";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}
function getCommandesGroupementsStatutBatch($co_pmp, $id_grp, $statut_cmd, $limit, $offset)
{
	$query = "
        SELECT id AS id_cmd
        FROM pmp_commande
        WHERE cmd_status = '$statut_cmd'
          AND groupe_cmd = '$id_grp'
        ORDER BY id ASC
        LIMIT $limit OFFSET $offset
    ";
	return my_query($co_pmp, $query);
}

// Nettoie les commandes d'un groupement dont le compte utilisateur est désactivé
// - Passe les commandes au statut 99
// - D Détache du groupement (groupe_cmd = 0)
// - Purge les prix (cmd_prix_ord/cmd_prix_sup = NULL)
// Retourne le nombre de commandes impactées
function cleanDisabledForGroup(&$co_pmp, $id_grp)
{
	if (!$id_grp)
		return 0;

	$id_grp = mysqli_real_escape_string($co_pmp, $id_grp);

	// Récupérer les IDs et l'ancien groupement pour tracer ensuite
	$select_ids = "
        SELECT c.id, c.groupe_cmd
        FROM pmp_commande c
        JOIN pmp_utilisateur u ON u.user_id = c.user_id
        WHERE c.groupe_cmd = '$id_grp'
          AND c.cmd_status <= 17
          AND c.cmd_status NOT IN (50, 52, 55, 99)
          AND u.disabled_account = 1
        ORDER BY c.id ASC
    ";
	$res_ids = my_query($co_pmp, $select_ids);

	$ids = [];
	$trace_oldgrp = [];
	$user_trace = mysqli_real_escape_string($co_pmp, $_SESSION['user'] ?? 'System');
	while ($row = mysqli_fetch_array($res_ids)) {
		$id_cmd = intval($row['id']);
		$old_grp = intval($row['groupe_cmd']);
		$ids[] = $id_cmd;
		if ($old_grp > 0) {
			$action = mysqli_real_escape_string($co_pmp, 'Ancien groupement');
			$valeur = mysqli_real_escape_string($co_pmp, $old_grp . ' (commande détachée)');
			$trace_oldgrp[] = "($id_cmd, '$user_trace', NOW(), '$action', '$valeur')";
		}
	}

	if (!empty($trace_oldgrp)) {
		my_query(
			$co_pmp,
			"
            INSERT INTO pmp_commande_histo (cmd_id, his_intervenant, his_date, his_action, his_valeur)
            VALUES " . implode(',', $trace_oldgrp)
		);
	}

	if (empty($ids)) {
		return 0;
	}

	// Mise à jour des commandes ciblées
	$update = "
        UPDATE pmp_commande c
        JOIN pmp_utilisateur u ON u.user_id = c.user_id
        SET c.cmd_status = 99,
            c.groupe_cmd = '0',
            c.cmd_prix_ord = NULL,
            c.cmd_prix_sup = NULL
        WHERE c.groupe_cmd = '$id_grp'
          AND c.cmd_status <= 17
          AND c.cmd_status NOT IN (50, 52, 55, 99)
          AND u.disabled_account = 1
    ";
	my_query($co_pmp, $update);

	// Tracer l'historique en batch
	$user = mysqli_real_escape_string($co_pmp, $_SESSION['user'] ?? 'System');
	$param1 = mysqli_real_escape_string($co_pmp, 'Statut');
	$param2 = mysqli_real_escape_string($co_pmp, '99 - Annulée / Compte désactivé');

	$chunks = [];
	foreach ($ids as $id_cmd) {
		$chunks[] = "($id_cmd, '$user', NOW(), '$param1', '$param2')";
	}
	if (!empty($chunks)) {
		my_query(
			$co_pmp,
			"
            INSERT INTO pmp_commande_histo (cmd_id, his_intervenant, his_date, his_action, his_valeur)
            VALUES " . implode(',', $chunks)
		);
	}

	return count($ids);
}

if (!empty($_POST["applique_n_statut"])) {
	$statut_cmd = intval($_POST["statut_cmd"]);
	$n_statut_cmd = intval($_POST["n_statut_cmd"]);
	$id = intval($_GET["id_grp"]);

	$statuts = [
		10 => "10 - Utilisateur",
		12 => "12 - Attachée",
		13 => "13 - Proposée",
		15 => "15 - Groupée",
		17 => "17 - Prix proposé",
		20 => "20 - Prix validé",
		25 => "25 - Livrable",
		30 => "30 - Livrée",
		40 => "37 - Facturée",
		50 => "50 - Annulée",
		52 => "52 - Annulée / Livraison",
		55 => "55 - Annulée / Prix",
		99 => "99 - Annulée / Compte désactivé"
	];

	if ($statut_cmd !== $n_statut_cmd) {
		$statut = $statuts[$statut_cmd] ?? $statut_cmd;
		$statut2 = $statuts[$n_statut_cmd] ?? $n_statut_cmd;

		// 1ï¸âƒ£ On récupère par batch les IDs pour tracer
		$batch_size = 500;
		$offset = 0;
		$user = mysqli_real_escape_string($co_pmp, $_SESSION['user']);
		$ids_cmdes = [];

		do {
			$res_cmdes = getCommandesGroupementsStatutBatch($co_pmp, $id, $statut_cmd, $batch_size, $offset);
			$count = mysqli_num_rows($res_cmdes);

			while ($cmd = mysqli_fetch_array($res_cmdes)) {
				$ids_cmdes[] = intval($cmd["id_cmd"]);
			}
			$offset += $batch_size;
		} while ($count === $batch_size);

		// 2ï¸âƒ£ UPDATE global (pas de liste énorme)
		$update_status = "
            UPDATE pmp_commande
            SET cmd_status = '$n_statut_cmd'
            WHERE cmd_status = '$statut_cmd'
              AND groupe_cmd = '$id'";
		my_query($co_pmp, $update_status);

		// 3ï¸âƒ£ Tracer lâ€™historique en batch
		if (!empty($ids_cmdes)) {
			$traces = [];
			foreach ($ids_cmdes as $id_cmd) {
				$param1 = mysqli_real_escape_string($co_pmp, "Changement de statut");
				$param2 = mysqli_real_escape_string($co_pmp, "$statut -> $statut2");
				$traces[] = "($id_cmd, '$user', NOW(), '$param1', '$param2')";
			}
			my_query(
				$co_pmp,
				"
                INSERT INTO pmp_commande_histo (cmd_id, his_intervenant, his_date, his_action, his_valeur)
                VALUES " . implode(',', $traces)
			);
		}

		// 4ï¸âƒ£ Si annulé â†’ mise à NULL
		if (in_array($n_statut_cmd, [50, 52, 55, 99])) {
			my_query(
				$co_pmp,
				"
                UPDATE pmp_commande
                SET cmd_prix_ord = NULL, cmd_prix_sup = NULL
                WHERE cmd_status = '$n_statut_cmd'
                  AND groupe_cmd = '$id'"
			);

			$traces_prix = [];
			foreach ($ids_cmdes as $id_cmd) {
				$traces_prix[] = "($id_cmd, '$user', NOW(), 'Prix Ord', '0')";
				$traces_prix[] = "($id_cmd, '$user', NOW(), 'Prix Sup', '0')";
			}
			if (!empty($traces_prix)) {
				my_query(
					$co_pmp,
					"
                    INSERT INTO pmp_commande_histo (cmd_id, his_intervenant, his_date, his_action, his_valeur)
                    VALUES " . implode(',', $traces_prix)
				);
			}
		}

		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Les commandes au statut '{$statut_cmd}' ont été modifiées avec le statut '{$n_statut_cmd}'";
	}
}


/* Modifier qté livrée tableau */
if (!empty($_POST["valider_tableau"])) {
	$nb_commande = $_POST['nb_commande_i'];
	for ($i = 0; $i < $nb_commande; $i++) {
		$id = 'id_cmde_' . $i;
		$id_cmde = $_POST[$id];

		if (isset($id_cmde)) {
			$qte = 'qte_livree_' . $id_cmde;
			$qte_livree = $_POST[$qte];
			$commande_details = getCommandeDetailsClients($co_pmp, $id_cmde);

			if ($qte_livree == "0") {
				$qte_livree = $commande_details["cmd_qte"];
			}
			if ($qte_livree == "") {
				$qte_livree = "0";
			}

			if ($qte_livree != $commande_details["cmd_qtelivre"]) {
				$updateQte = " UPDATE pmp_commande SET cmd_qtelivre = '$qte_livree'
							   WHERE id = '$id_cmde' ";
				$res = my_query($co_pmp, $updateQte);
				TraceHisto($co_pmp, $id_cmde, 'Quantité Livrée', $qte_livree);
			}
		}
	}
	if (isset($res)) {
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "La quantité livrée des commandes a bien été modifiée.";
		return $res;
	} else {
		return false;
	}
}

if (!empty($_POST["valider_tableau_qte_0"])) {
	$nb_commande = $_POST['nb_commande_i'];
	for ($i = 0; $i < $nb_commande; $i++) {
		$id = 'id_cmde_' . $i;
		$id_cmde = $_POST[$id];

		if (isset($id_cmde)) {
			$qte = 'qte_livree_' . $id_cmde;
			$qte_livree = $_POST[$qte];
			$commande_details = getCommandeDetailsClients($co_pmp, $id_cmde);

			if ($qte_livree == "") {
				$qte_livree = "0";
			}

			if ($qte_livree != $commande_details["cmd_qtelivre"]) {
				$updateQte = " UPDATE pmp_commande SET cmd_qtelivre = '$qte_livree'
							   WHERE id = '$id_cmde' ";
				$res = my_query($co_pmp, $updateQte);
				TraceHisto($co_pmp, $id_cmde, 'Quantité Livrée', $qte_livree);
			}
		}
	}
	if (isset($res)) {
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "La quantité livrée des commandes a bien été modifiée.";
		return $res;
	} else {
		return false;
	}
}
function recupDepGroupement(&$co_pmp, $id)
{
	// 1. Je vide la table
	$delete = "DELETE FROM `pmp_regrp_dep` WHERE `regrp_id` = '$id'";
	my_query($co_pmp, $delete);

	// 2. Je compte le nombre de commandes
	$select = "SELECT COUNT(*) AS nb FROM `pmp_commande` WHERE `groupe_cmd` = '$id'";
	$res_select = my_query($co_pmp, $select);
	$num_cmdes = mysqli_fetch_array($res_select);

	if ($num_cmdes["nb"] > 0) {
		// 3a. Il y a des commandes â†’ insérer départements depuis utilisateurs
		$insert = "INSERT INTO `pmp_regrp_dep` (`regrp_id`, `departement`)
            SELECT DISTINCT c.`groupe_cmd`, SUBSTR(u.`CODE_POSTAL`,1,2)
            FROM `pmp_commande` c
            JOIN `pmp_utilisateur` u ON c.`user_id` = u.`user_id`
            WHERE c.`groupe_cmd` = '$id'";
		$res = my_query($co_pmp, $insert);
	} else {
		// 3b. Pas de commandes â†’ insérer départements depuis zones
		$insert = "INSERT INTO `pmp_regrp_dep` (`regrp_id`, `departement`)
            SELECT DISTINCT rz.`regrp_id`, SUBSTR(cp.`CODE_POSTAL`,1,2)
            FROM `pmp_regrp_zone` rz
            JOIN `pmp_zone_cp` zc ON zc.`zone_id` = rz.`zone_id`
            JOIN `pmp_code_postal` cp ON zc.`code_postal_id` = cp.`id`
            WHERE zc.`actif` = 1
            AND rz.`regrp_id` = '$id'";
		$res = my_query($co_pmp, $insert);
	}

	// 4. Plus d'UPDATE sur livre car la colonne n'existe pas
	return $res;
}


/* Modifier le groupement */
if (!empty($_POST["update_grpt"]) || !empty($_POST["update_grpt_sortie"]) || !empty($_GET['nouveau'])) {
	if (isset($_GET["id_grp"])) {
		$cacher = isset($_POST["cacher_annules"]) ? "1" : "0";
		$cacher_p = isset($_POST["cacher_propose"]) ? "1" : "0";
		$grp = getGroupementDetails($co_pmp, $_GET["id_grp"]);
		$id = $_GET["id_grp"];
		$libelle = $_POST["libelle_grpt"];
		$date = $_POST["date_grpt"];
		$com = $_POST["com_grpt"];
		$statut = $_POST["statut_grpt"];
		$resp = $_POST["resp_grpt"];
		$fact = $_POST["numfact"];
		$infofour = $_POST["info_four"];

		recupDepGroupement($co_pmp, $id);

		if (isset($_GET["n_id_four"])) {
			$id_four = $_GET["n_id_four"];
		} else {
			$id_four = $grp["id_four"];
		}

		$ord = number_format((float) $_POST["com_ord"], 0);
		$sup = number_format((float) $_POST["com_sup"], 0);

		if ($ord != $grp["mtcomordht"]) {
			$com_ord = $ord;
		} else {
			$com_ord = $grp["mtcomordht"];
		}
		if ($sup != $grp["mtcomsupht"]) {
			$com_sup = $sup;
		} else {
			$com_sup = $grp["mtcomsupht"];
		}

		if ($statut == 5) {
			$statut_n = "5 - Prévu";
		}
		if ($statut == 10) {
			$statut_n = "10 - Crée";
		}
		if ($statut == 15) {
			$statut_n = "15 - Envoyé";
		}
		if ($statut == 30) {
			$statut_n = "30 - Livré";
		}
		if ($statut == 33) {
			$statut_n = "33 - A facturé";
		}
		if ($statut == 37) {
			$statut_n = "37 - Facturé";
		}
		if ($statut == 50) {
			$statut_n = "50 - Annulé";
		}

		if ($grp["statut"] == 5) {
			$statut_a = "5 - Prévu";
		}
		if ($grp["statut"] == 10) {
			$statut_a = "10 - Crée";
		}
		if ($grp["statut"] == 15) {
			$statut_a = "15 - Envoyé";
		}
		if ($grp["statut"] == 30) {
			$statut_a = "30 - Livré";
		}
		if ($grp["statut"] == 33) {
			$statut_a = "33 - A facturé";
		}
		if ($grp["statut"] == 37) {
			$statut_a = "37 - Facturé";
		}
		if ($grp["statut"] == 50) {
			$statut_a = "50 - Annulé";
		}

		//Planning
		$planning = "";
		$inscription = $_POST["inscription"];
		$annonce_prix = $_POST["annonce_prix"];
		$validation = $_POST["validation"];
		$livraison = $_POST["livraison"];
		$prochain_grp = $_POST["prochain_grp"];

		$planning .= $inscription . "\r" . $annonce_prix . "\r" . $validation . "\r" . $livraison . "\r" . $prochain_grp;

		if ($_POST["inscription"] != $_POST["inscription_hidden"] || $_POST["annonce_prix"] != $_POST["annonce_prix_hidden"] || $_POST["validation"] != $_POST["validation_hidden"] || $_POST["livraison"] != $_POST["livraison_hidden"] || $_POST["prochain_grp"] != $_POST["prochain_grp_hidden"]) {
			$planning = mysqli_real_escape_string($co_pmp, $planning);
			$query = "  UPDATE pmp_regroupement
						SET planning = '$planning'
						WHERE id = '$id' ";
			$res = my_query($co_pmp, $query);

			$str = str_replace("\'", "'", $planning);
			TraceHistoGrpt($co_pmp, $id, 'Planning', $str);
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le groupement a bien été modifié.";
		}

		if ($libelle != $grp["libelle"]) {
			$libelle = mysqli_real_escape_string($co_pmp, $libelle);
			$query = "  UPDATE pmp_regroupement
						SET libelle = '$libelle'
						WHERE id = '$id' ";
			$res = my_query($co_pmp, $query);
			TraceHistoGrpt($co_pmp, $id, 'Changement Libellé', $grp["libelle"] . " -> " . $libelle);
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le groupement a bien été modifié.";
		}

		if ($fact != $grp["numfact"]) {
			$fact = mysqli_real_escape_string($co_pmp, $fact);
			$query = "  UPDATE pmp_regroupement
						SET numfact = '$fact'
						WHERE id = '$id' ";
			$res = my_query($co_pmp, $query);
			TraceHistoGrpt($co_pmp, $id, 'Numéro de Facture', $fact);
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le groupement a bien été modifié.";
		}

		if ($id_four != $grp["id_four"]) {
			// Récupération propre du texte fournisseur
			$query = "
			    SELECT 
			        REPLACE(
			            REPLACE(
			                CONCAT(facturation, ' ', modalite, ' ', facilite),
			                '\r', ''
			            ),
			            '\n', ''
			        ) AS info
			    FROM pmp_fournisseur
			    WHERE id = $id_four
			";
			$res = my_query($co_pmp, $query);
			$infofour = mysqli_fetch_array($res);
			$infofour = $infofour["info"] ?? '';

			// Nettoyage des espaces avant les balises HTML
			$infofour = trim(preg_replace('/\s+</', '<', $infofour));

			// Sécurisation avant insertion SQL
			$infofour = mysqli_real_escape_string($co_pmp, $infofour);



			$id_four = mysqli_real_escape_string($co_pmp, $id_four);
			$query = "  UPDATE pmp_regroupement
						SET id_four = '$id_four', infofour = '$infofour'
						WHERE id = '$id' ";
			$res = my_query($co_pmp, $query);
			TraceHistoGrpt($co_pmp, $id, 'Changement de fournisseur', $grp["id_four"] . " -> " . $id_four);

			$four = getFournisseurDetails($co_pmp, $id_four);
			$com_ord = $four["comord"];
			$com_sup = $four["comsup"];
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le groupement a bien été modifié.";
		}
		if ($resp != $grp["responsable"]) {
			$resp = mysqli_real_escape_string($co_pmp, $resp);
			$query = "  UPDATE pmp_regroupement
						SET responsable = '$resp'
						WHERE id = '$id' ";
			$res = my_query($co_pmp, $query);
			TraceHistoGrpt($co_pmp, $id, 'Responsable', $resp);
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le groupement a bien été modifié.";
		}

		if ($date != $grp["date_grp"]) {
			$date = mysqli_real_escape_string($co_pmp, $date);
			$query = "  UPDATE pmp_regroupement
						SET date_grp = '$date'
						WHERE id = '$id' ";
			$res = my_query($co_pmp, $query);
			TraceHistoGrpt($co_pmp, $id, 'Changement date', $grp["date_grp"] . " -> " . $date);
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le groupement a bien été modifié.";
		}

		if ($com != $grp["commentaire"]) {
			$com = mysqli_real_escape_string($co_pmp, $com);
			$query = "  UPDATE pmp_regroupement
						SET commentaire = '$com'
						WHERE id = '$id' ";
			$res = my_query($co_pmp, $query);
			TraceHistoGrpt($co_pmp, $id, 'Commentaire grp', $com);
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le groupement a bien été modifié.";
		}

		if ($statut != $grp["statut"]) {
			if ($statut > 10) {
				$query = "	SELECT *
						FROM pmp_commande
						WHERE groupe_cmd = '" . mysqli_real_escape_string($co_pmp, $id) . "'
						AND cmd_status < 25
						 ";
				$res = my_query($co_pmp, $query);
				$cmd = mysqli_fetch_array($res);

				if ($cmd) {
					// Il y a au moins une commande
					$message_type = "info";
					$message_icone = "fa-exclamation";
					$message_titre = "Info";
					$message = "Impossible de passer le groupement au statut " . $statut . " . Des commandes inférieures au statut 25 sont présentes dans le groupement.";
				} else {
					// Aucune commande concernée
					$statut = mysqli_real_escape_string($co_pmp, $statut);
					$query = "UPDATE pmp_regroupement
				              SET statut = '$statut'
				              WHERE id = '$id'";
					$res = my_query($co_pmp, $query);
					TraceHistoGrpt($co_pmp, $id, 'Statut', $statut_a . " -> " . $statut_n);
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Le groupement a bien été modifié.";
				}

			} elseif ($statut == 37) {
				$query = "	SELECT *
						FROM pmp_commande
						WHERE groupe_cmd = '" . mysqli_real_escape_string($co_pmp, $id) . "'
						AND cmd_status < 40
						 ";
				$res = my_query($co_pmp, $query);
				$cmd = mysqli_fetch_array($res);

				if ($cmd) {
					$message_type = "info";
					$message_icone = "fa-exclamation";
					$message_titre = "Info";
					$message = "Impossible de passer le groupement au statut 37 - Facturé. Des commandes inférieurs au statut 37 - Facturée sont présentes dans le groupement.";
				} elseif ($grp["numfact"] == "") {
					$message_type = "info";
					$message_icone = "fa-exclamation";
					$message_titre = "Info";
					$message = "Impossible de passer le groupement au statut 37 - Facturé. Aucun numéro de facture n'a été appliqué.";
				} else {
					$statut = mysqli_real_escape_string($co_pmp, $statut);
					$query = "  UPDATE pmp_regroupement
								SET statut = '$statut'
								WHERE id = '$id' ";
					$res = my_query($co_pmp, $query);
					TraceHistoGrpt($co_pmp, $id, 'Statut', $statut_a . " -> " . $statut_n);
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Le groupement a bien été modifié.";
				}
			} elseif ($statut == 50) {
				$cmdes = getCommandesGroupementsCache($co_pmp, $_GET["id_grp"]);
				$num_cmdes = mysqli_num_rows($cmdes);

				if ($num_cmdes > 0) {
					header('Location: /admin/details_groupement.php?id_grp=' . $_GET["id_grp"] . '&verification=oui');
				} else {
					$statut = mysqli_real_escape_string($co_pmp, $statut);
					$query = "  UPDATE pmp_regroupement
								SET statut = '$statut'
								WHERE id = '$id' ";
					$res = my_query($co_pmp, $query);
					TraceHistoGrpt($co_pmp, $id, 'Statut', $statut_a . " -> " . $statut_n);
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Le groupement a bien été modifié.";
				}
			} else {
				$statut = mysqli_real_escape_string($co_pmp, $statut);
				$query = "  UPDATE pmp_regroupement
							SET statut = '$statut'
							WHERE id = '$id' ";
				$res = my_query($co_pmp, $query);
				TraceHistoGrpt($co_pmp, $id, 'Statut', $statut_a . " -> " . $statut_n);

				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "Le groupement a bien été modifié.";
			}
		}

		if ($infofour != $grp["infofour"]) {
			TraceHistoGrpt($co_pmp, $id, 'Info Fournisseur', $infofour);

			$infofour = mysqli_real_escape_string($co_pmp, $infofour);
			$query = "  UPDATE pmp_regroupement
						SET infofour = '$infofour'
						WHERE id = '$id' ";
			$res = my_query($co_pmp, $query);
		}

		if ($com_ord != $grp["mtcomordht"]) {
			$com_ord = mysqli_real_escape_string($co_pmp, $com_ord);
			$tva = getTva($co_pmp);
			$com_ord_ttc = $tva["tva"] * $com_ord;
			$com_ord_ttc = mysqli_real_escape_string($co_pmp, $com_ord_ttc);
			$query = "  UPDATE pmp_regroupement
						SET mtcomord = '$com_ord_ttc', mtcomordht = '$com_ord'
						WHERE id = '$id'  ";
			$res = my_query($co_pmp, $query);
			if ($res) {
				TraceHistoGrpt($co_pmp, $id, 'Com Ord HT', $com_ord);
				TraceHistoGrpt($co_pmp, $id, 'Com Ord TTC', $com_ord_ttc);
				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "Le groupement a bien été modifié.";
			}
		}

		if ($com_sup != $grp["mtcomsupht"]) {
			$tva = getTva($co_pmp);
			$com_sup = mysqli_real_escape_string($co_pmp, $com_sup);

			$com_sup_ttc = $tva["tva"] * $com_sup;
			$com_sup_ttc = mysqli_real_escape_string($co_pmp, $com_sup_ttc);
			$query = "  UPDATE pmp_regroupement
						SET mtcomsup = '$com_sup_ttc', mtcomsupht = '$com_sup'
						WHERE id = '$id'  ";
			$res = my_query($co_pmp, $query);
			if ($res) {
				TraceHistoGrpt($co_pmp, $id, 'Com Sup HT', $com_sup);
				TraceHistoGrpt($co_pmp, $id, 'Com Sup TTC', $com_sup_ttc);
				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "Le groupement a bien été modifié.";
			}
		}

		if ($_POST["com_ord_ttc"] == "0,00") {
			$four = getFournisseurDetails($co_pmp, $grp["id_four"]) ?? [];
			$tva = getTva($co_pmp) ?? [];

			$com_ord = isset($four["comord"]) ? (float) $four["comord"] : 0;
			$com_sup = isset($four["comsup"]) ? (float) $four["comsup"] : 0;

			$tva_value = isset($tva["tva"]) ? (float) $tva["tva"] : 1;

			$com_ord_ttc = $tva_value * $com_ord;
			$com_sup_ttc = $tva_value * $com_sup;

			// Utilisation de cast string pour éviter le warning
			$com_ord_ttc_safe = mysqli_real_escape_string($co_pmp, (string) $com_ord_ttc);
			$com_sup_ttc_safe = mysqli_real_escape_string($co_pmp, (string) $com_sup_ttc);

			$query = "UPDATE pmp_regroupement
		              SET mtcomsup = '$com_sup_ttc_safe', mtcomord = '$com_ord_ttc_safe'
		              WHERE id = '$id'";

			$res = mysqli_query($co_pmp, $query);

			if ($res) {
				TraceHistoGrpt($co_pmp, $id, 'Com Sup TTC', $com_sup_ttc_safe);
				TraceHistoGrpt($co_pmp, $id, 'Com Ord TTC', $com_ord_ttc_safe);

				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "Le groupement a bien été modifié.";
			}
		}

		// if(isset($res))
		// {
		// 	$message_type = "success";
		// 	$message_icone = "fa-check";
		// 	$message_titre = "Succès";
		// 	$message = "Le groupement a bien été modifié.";
		// 	return $res;
		//
		// }
		// else
		// {
		// 	return false;
		// }
	} else {
		$_POST["libelle_grpt"] = "";
		$_POST["date_grpt"] = date("Y-m-d");
		$_POST["com_grpt"] = "";
		$_POST["resp_grpt"] = "";
		$_POST["add_id_four"] = "";

		if (isset($_POST["libelle_grpt"])) {
			if (isset($_POST["date_grpt"])) {
				$libelle = $_POST["libelle_grpt"];
				$date = $_POST["date_grpt"];
				$statut = $_POST["statut_grpt"];
				$com_grpt = $_POST["com_grpt"];
				$id_four = $_POST["add_id_four"];
				$resp = $_POST["resp_grpt"];

				if ($statut == 5) {
					$statut_n = "5 - Prévu";
				}
				if ($statut == 10) {
					$statut_n = "10 - Crée";
				}
				if ($statut == 15) {
					$statut_n = "15 - Envoyé";
				}
				if ($statut == 30) {
					$statut_n = "30 - Livré";
				}
				if ($statut == 33) {
					$statut_n = "33 - A facturé";
				}
				if ($statut == 37) {
					$statut_n = "37 - Facturé";
				}
				if ($statut == 50) {
					$statut_n = "50 - Annulé";
				}

				$libelle = mysqli_real_escape_string($co_pmp, $libelle);
				$date = mysqli_real_escape_string($co_pmp, $date);
				$statut = mysqli_real_escape_string($co_pmp, $statut);
				$com_grpt = mysqli_real_escape_string($co_pmp, $com_grpt);
				$id_four = mysqli_real_escape_string($co_pmp, $id_four);
				$resp = mysqli_real_escape_string($co_pmp, $resp);
				$statut = mysqli_real_escape_string($co_pmp, $statut);

				$query = "  SELECT planning
							from    pmp_regroupement
							where id = (select    max(id) from    pmp_regroupement where planning is not null)";
				$res = my_query($co_pmp, $query);
				$planning = mysqli_fetch_array($res);
				$planning = $planning["planning"];
				$planning = mysqli_real_escape_string($co_pmp, $planning);

				if ($id_four != "") {
					$query = "  SELECT nom, concat(facturation, char(13), char(10), modalite, char(13), char(10), facilite) as info
 								from     pmp_fournisseur
 								where     id = $id_four";
					$res = my_query($co_pmp, $query);
					$infofour = mysqli_fetch_array($res);
					$infofour = $infofour["info"];
					$infofour = mysqli_real_escape_string($co_pmp, $infofour);
				}

				$query = "  INSERT INTO pmp_regroupement (libelle, date_grp, statut, commentaire, id_four, responsable, planning, infofour, options)
							VALUES ('$libelle', '$date', '$statut', '$com_grpt', '$id_four', '$resp', '$planning', '$infofour', '0401') ";
				$res = my_query($co_pmp, $query);
				if ($res) {
					$last_id = mysqli_insert_id($co_pmp);
					$four = getFournisseurDetails($co_pmp, $id_four);
					$com_ord = $four["comord"];
					$com_sup = $four["comsup"];

					$query = "  INSERT INTO pmp_regrp_plages(id, regrp_id, volume, prix_ord, prix_sup)
								VALUES ('', '$last_id', '500', NULL, NULL) ";
					$res = my_query($co_pmp, $query);

					$query = "  INSERT INTO pmp_regrp_plages(id, regrp_id, volume, prix_ord, prix_sup)
								VALUES ('', '$last_id', '1000', NULL, NULL) ";
					$res = my_query($co_pmp, $query);

					$query = "  INSERT INTO pmp_regrp_plages(id, regrp_id, volume, prix_ord, prix_sup)
								VALUES ('', '$last_id', '2000', NULL, NULL) ";
					$res = my_query($co_pmp, $query);

					TraceHistoGrpt($co_pmp, $last_id, 'Groupement', 'Nouveau groupement');

					if (isset($_POST["libelle_grpt"])) {
						TraceHistoGrpt($co_pmp, $last_id, 'Libellé', $libelle);
					}
					if (isset($_POST["resp_grpt"]) && $_POST["resp_grpt"] != '') {
						TraceHistoGrpt($co_pmp, $last_id, 'Responsable', $resp);
					}
					if ($date != '') {
						TraceHistoGrpt($co_pmp, $last_id, 'Date', $_POST["date_grpt"]);
					}
					if (isset($_POST["statut_grpt"])) {
						TraceHistoGrpt($co_pmp, $last_id, 'Statut', $statut_n);
					}
					if ($_POST["com_grpt"] != '') {
						TraceHistoGrpt($co_pmp, $last_id, 'Commentaire', $com_grpt);
					}
					if ($id_four != '') {
						TraceHistoGrpt($co_pmp, $last_id, 'Fournisseur', $id_four);
					}
					if ($planning != '') {
						$str = str_replace("\'", "'", $planning);
						TraceHistoGrpt($co_pmp, $last_id, 'Planning', $str);
					}
					if (!empty($infofour) && is_array($infofour) && isset($infofour["info"])) {
						$info_four = $infofour["info"];
						TraceHistoGrpt($co_pmp, $last_id, 'Info Fournisseur', $info_four);
					}

					if (isset($com_ord)) {
						$com_ord = mysqli_real_escape_string($co_pmp, $com_ord);
						$tva = getTva($co_pmp);
						$com_ord_ttc = $tva["tva"] * $com_ord;
						$com_ord_ttc = mysqli_real_escape_string($co_pmp, $com_ord_ttc);
						$query = "  UPDATE pmp_regroupement
									SET mtcomord = '$com_ord_ttc', mtcomordht = '$com_ord'
									WHERE id = '$last_id'  ";
						$res = my_query($co_pmp, $query);
						if ($res) {
							TraceHistoGrpt($co_pmp, $last_id, 'Com Ord HT', $com_ord);
							TraceHistoGrpt($co_pmp, $last_id, 'Com Ord TTC', $com_ord_ttc);
						}
					}

					if (isset($com_sup)) {
						$tva = getTva($co_pmp);
						$com_sup_ttc = $tva["tva"] * $com_sup;
						$com_sup = mysqli_real_escape_string($co_pmp, $com_sup);
						$com_sup_ttc = mysqli_real_escape_string($co_pmp, $com_sup_ttc);
						$query = "  UPDATE pmp_regroupement
									SET mtcomsup = '$com_sup_ttc', mtcomsupht = '$com_sup'
									WHERE id = '$last_id'  ";
						$res = my_query($co_pmp, $query);
						if ($res) {
							TraceHistoGrpt($co_pmp, $last_id, 'Com Sup HT', $com_sup);
							TraceHistoGrpt($co_pmp, $last_id, 'Com Sup TTC', $com_sup_ttc);
						}
					}

					header('Location: /admin/details_groupement.php?id_grp=' . $last_id);
				}
			} else {
				$message_type = "no";
				$message_icone = "fa-times";
				$message_titre = "Erreur";
				$message = "La date est obligatoire pour créer un nouveau groupement.";
			}
		} else {
			$message_type = "no";
			$message_icone = "fa-times";
			$message_titre = "Erreur";
			$message = "Le nom est obligatoire pour créer un nouveau groupement.";
		}
	}
}

if (!empty($_POST["validation_statut"])) {
	$id = $_GET["id_grp"];
	$grp = getGroupementDetails($co_pmp, $id);

	if ($grp["statut"] == 5) {
		$statut_a = "5 - Prévu";
	}
	if ($grp["statut"] == 10) {
		$statut_a = "10 - Crée";
	}
	if ($grp["statut"] == 15) {
		$statut_a = "15 - Envoyé";
	}
	if ($grp["statut"] == 30) {
		$statut_a = "30 - Livré";
	}
	if ($grp["statut"] == 33) {
		$statut_a = "33 - A facturé";
	}
	if ($grp["statut"] == 37) {
		$statut_a = "37 - Facturé";
	}
	if ($grp["statut"] == 50) {
		$statut_a = "50 - Annulé";
	}

	if (isset($grp['statut'])) {
		if ($grp['statut'] <= 10) {
			$query = "	SELECT *
					FROM pmp_commande
					WHERE groupe_cmd = '" . mysqli_real_escape_string($co_pmp, $id) . "'
					AND cmd_status < 25
					 ";
			$res = my_query($co_pmp, $query);
			$cmd = mysqli_fetch_array($res);
			if (strlen($cmd[0]) > 0) {
				$message_type = "info";
				$message_icone = "fa-exclamation";
				$message_titre = "Info";
				$message = "Impossible de passer le groupement au statut 15 - Envoyé. Des commandes inférieurs au statut 25 - Livrable sont présentes dans le groupement.";
			} else {
				$query = "  UPDATE pmp_regroupement
							SET statut = '15'
							WHERE id = '$id' ";
				$res = my_query($co_pmp, $query);
				TraceHistoGrpt($co_pmp, $id, 'Statut', $statut_a . " -> 15 - Envoyé ");
				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "Le groupement a bien été modifié au statut 15 - Envoyé.";
			}
		} elseif ($grp['statut'] == 15) {
			$query = "  UPDATE pmp_regroupement
						SET statut = '30'
						WHERE id = '$id' ";
			$res = my_query($co_pmp, $query);
			TraceHistoGrpt($co_pmp, $id, 'Statut', $statut_a . " -> 30 - Livré ");
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le groupement a bien été modifié au statut 30 - Livré.";
		} elseif ($grp['statut'] >= 30 && $grp['statut'] < 40) {
			$query = "  UPDATE pmp_regroupement
						SET statut = '37'
						WHERE id = '$id' ";
			$res = my_query($co_pmp, $query);
			TraceHistoGrpt($co_pmp, $id, 'Statut', $statut_a . " -> 37 - Facturé ");
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le groupement a bien été modifié au statut 37 - Facturé.";
		}
	}
}


if (!empty($_POST["annule_groupement"])) {
	if (isset($_GET["id_grp"])) {
		$id = $_GET["id_grp"];
		$grp = getGroupementDetails($co_pmp, $_GET["id_grp"]);
		if ($grp["statut"] == 5) {
			$statut_a = "5 - Prévu";
		}
		if ($grp["statut"] == 10) {
			$statut_a = "10 - Crée";
		}
		if ($grp["statut"] == 15) {
			$statut_a = "15 - Envoyé";
		}
		if ($grp["statut"] == 30) {
			$statut_a = "30 - Livré";
		}
		if ($grp["statut"] == 33) {
			$statut_a = "33 - A facturé";
		}
		if ($grp["statut"] == 37) {
			$statut_a = "37 - Facturé";
		}
		if ($grp["statut"] == 50) {
			$statut_a = "50 - Annulé";
		}

		$query = "  UPDATE pmp_regroupement
					SET statut = '50'
					WHERE id = '$id' ";
		$res = my_query($co_pmp, $query);
		if ($res) {
			if ($statut == 50) {
				$statut_n = "50 - Annulé";
			}
			TraceHistoGrpt($co_pmp, $id, 'Statut', $statut_a . " -> " . "50 - Annulé");
			header('Location: /admin/details_groupement.php?id_grp=' . $id);
		}
	}
}


if (!empty($_POST["supp_groupe"])) {
	$id_grp = $_GET["id_grp"];
	$query = "DELETE FROM pmp_regroupement WHERE id = '$id_grp'";
	$res = my_query($co_pmp, $query);
	$query = "DELETE FROM pmp_regroupement_histo WHERE grp_id = '$id_grp'";
	$res = my_query($co_pmp, $query);
	$query = "DELETE FROM pmp_regrp_dep WHERE regrp_id = '$id_grp'";
	$res = my_query($co_pmp, $query);
	$query = "DELETE FROM pmp_regrp_plages WHERE regrp_id = '$id_grp'";
	$res = my_query($co_pmp, $query);
	$query = "DELETE FROM pmp_regrp_zone WHERE regrp_id = '$id_grp'";
	$res = my_query($co_pmp, $query);
	$query = "UPDATE pmp_commande SET groupe_cmd = '0' WHERE groupe_cmd = '$id_grp'";
	$res = my_query($co_pmp, $query);

	header('Location: /admin/liste_regroupements.php?grp=supp');
}

/* MODAL commentaire historique */
function getHistoriqueGroupement(&$co_pmp, $id_grp)
{
	$query = "   SELECT *
				 FROM pmp_regroupement_histo
				 WHERE grp_id = '$id_grp'
				 ORDER BY hisg_date DESC";
	$res = my_query($co_pmp, $query);
	return $res;
}



/* Ajouter commentaire historique */
if (!empty($_POST["add_histo"]) || !empty($_POST["add_histo_ok"])) {
	$id_grp = $_GET["id_grp"];
	$user = $_SESSION['user'];
	$param = $_POST["com_histo"];
	$param = mysqli_real_escape_string($co_pmp, $param);

	if (!empty($_POST["com_histo"])) {
		$query = "  INSERT INTO pmp_regroupement_histo (id, grp_id, hisg_date, hisg_date_milli, hisg_intervenant, hisg_action, hisg_valeur)
		 			VALUES ('', '$id_grp', NOW(), '0', '$user', 'Commentaire Histo', '$param') ";
		$res = my_query($co_pmp, $query);

		if ($res) {
			if (!empty($_POST["add_histo"])) {
				header('Location: /admin/details_groupement.php?id_grp=' . $_GET["id_grp"]);
			} elseif (!empty($_POST["add_histo"])) {
				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "Le commentaire a bien été ajouté au groupement";
			}
		} else {
			$message_type = "no";
			$message_icone = "fa-times";
			$message_titre = "Erreur";
			$message = "Une erreur s'est produite";
		}
	} else {
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Le champs 'Commentaire pour historique' est obligatoire";
	}
}

function getNouveauFournisseur(&$co_pmp, $id)
{
	$query = "  SELECT *
				FROM pmp_fournisseur
				WHERE id = '$id' ";
	$res = my_query($co_pmp, $query);
	$fournisseur_details = mysqli_fetch_array($res);
	return $fournisseur_details;
	/*
		$message_type = "info";
		$message_icone = "fa-exclamation";
		$message_titre = "Info";
		$message = "Attention, si vous valider, le fournisseur sera remplacer.";
	*/
}

// Ajouter plages prix au tableau
if (!empty($_POST["ajouter_plages_prix"])) {
	$id_grp = $_GET["id_grp"];
	$query = "  INSERT INTO pmp_regrp_plages(id, regrp_id, volume, prix_ord, prix_sup)
				VALUES ('', '$id_grp', '0', NULL, NULL) ";
	$res = my_query($co_pmp, $query);
	return $res;
}

//Supprimer palges prix
if (!empty($_POST["supprimer_plages_prix"])) {
	if (isset($_POST["id_select_plages_prix"])) {
		$id_plages = $_POST["id_select_plages_prix"];
		$query = "  DELETE FROM pmp_regrp_plages
					WHERE id = '$id_plages' ";
		$res = my_query($co_pmp, $query);
		return $res;
	}
}

function ChargePlagesPrix(&$co_pmp, $regrp_id, $quantite)
{
	$query = "	SELECT volume, prix_ord, prix_sup
				FROM pmp_regrp_plages
				WHERE regrp_id = '" . $regrp_id . "'
				AND volume <= '" . mysqli_real_escape_string($co_pmp, $quantite) . "'
				ORDER BY volume DESC
				LIMIT 1 ";
	$res = my_query($co_pmp, $query);
	$plages = mysqli_fetch_array($res);
	return $plages;
}

//Modifier les plages prix
if (!empty($_POST["nb_plages"])) {
	if (!empty($_POST["plage_id"])) {
		$plages_id = $_POST["plage_id"];

		for ($i = 0; $i < $_POST['nb_plages']; $i++) {
			$id = $plages_id[$i];

			$plage_volume = 'plage_volume_' . $i;
			$volume = $_POST[$plage_volume];


			$plage_prix_ord = 'plage_prix_ord_' . $id;
			$prix_ord = $_POST[$plage_prix_ord];

			$plage_prix_sup = 'plage_prix_sup_' . $id;
			$prix_sup = $_POST[$plage_prix_sup];

			//supp point virgule espace
			$prix_ord = str_replace(',', '', $prix_ord);
			$prix_sup = str_replace(',', '', $prix_sup);
			$prix_ord = str_replace('.', '', $prix_ord);
			$prix_sup = str_replace('.', '', $prix_sup);
			$prix_ord = str_replace(' ', '', $prix_ord);
			$prix_sup = str_replace(' ', '', $prix_sup);

			if (strlen($prix_ord) == 2) {
				$prix_ord = $prix_ord . "00";
			} elseif (strlen($prix_ord) == 1) {
				$prix_ord = $prix_ord . "000";
			} elseif (strlen($prix_ord) == 3) {
				$prix_ord = $prix_ord . "0";
			}

			if (strlen($prix_sup) == 2) {
				$prix_sup = $prix_sup . "00";
			} elseif (strlen($prix_sup) == 1) {
				$prix_sup = $prix_sup . "000";
			} elseif (strlen($prix_sup) == 3) {
				$prix_sup = $prix_sup . "0";
			}

			$query = "  UPDATE pmp_regrp_plages
						SET volume = '$volume', prix_ord = '$prix_ord', prix_sup = '$prix_sup'
						WHERE id = '$id' ";
			$res = my_query($co_pmp, $query);
		}
	}
}

// Appliquer prix commandes optimisé tout en gardant TraceHisto et messages
if (!empty($_POST["appliquer_plages_prix"])) {
	$id_grp = $_GET["id_grp"];
	$status = $_POST["statut_cmd"];

	if (isset($id_grp)) {
		$res_cmd = getCommandesGroupementsStatut($co_pmp, $id_grp, $status);

		while ($cmd = mysqli_fetch_array($res_cmd)) {
			$id_cmde = $cmd["id_cmd"];
			$qte = ($cmd["cmd_qte"] > 0) ? $cmd["cmd_qte"] : $_POST["plage_volume_0"];
			$plages = ChargePlagesPrix($co_pmp, $id_grp, $qte);

			$prix_ord = $plages["prix_ord"];
			$prix_sup = $plages["prix_sup"];

			// Update cmd_prixpmp selon conditions
			if ($cmd["cmd_prixpmp"] == '1') {
				$update = "UPDATE pmp_commande SET cmd_prixpmp = '$prix_ord' WHERE id = '$id_cmde'";
				$res = my_query($co_pmp, $update);
			} elseif ($cmd["cmd_typefuel"] == '2') {
				$update = "UPDATE pmp_commande SET cmd_prixpmp = '$prix_sup' WHERE id = '$id_cmde'";
				$res = my_query($co_pmp, $update);
			}

			// Update cmd_prix_ord / cmd_prix_sup si nécessaire
			if ($cmd["cmd_prix_ord"] != $prix_ord || $cmd["cmd_prix_sup"] != $prix_sup) {
				$update = "UPDATE pmp_commande SET cmd_prix_ord = '$prix_ord', cmd_prix_sup = '$prix_sup' WHERE id = '$id_cmde'";
				$res = my_query($co_pmp, $update);

				// TraceHisto comme avant
				TraceHisto($co_pmp, $id_cmde, 'Prix Ord', $prix_ord);
				TraceHisto($co_pmp, $id_cmde, 'Prix Sup', $prix_sup);

				// Message succès comme avant
				if (isset($res)) {
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les prix ont bien été appliqués aux commandes.";
				}
			}
		}
	}
}

if (!empty($_POST["recup_qte"])) {
	$id = $_GET["id_grp"];

	$vol_ord = getQuantiteVolumeOrdTableau($co_pmp, $_GET["id_grp"]);
	$vol_sup = getQuantiteVolumeSupTableau($co_pmp, $_GET["id_grp"]);
	$grp = getGroupementDetails($co_pmp, $id);

	// --- VOLUME ORDONNÃ‰ ---
	if (isset($vol_ord["qte_ord"]) && $vol_ord["qte_ord"] != NULL) {
		$vol_ord = $vol_ord["qte_ord"];
		$update = "UPDATE pmp_regroupement
					SET volord = '$vol_ord'
					WHERE id = '$id'";
		$res = my_query($co_pmp, $update);

		if ($vol_ord != $grp["volord"]) {
			TraceHistoGrpt($co_pmp, $id, 'Qté Vol Ord', $vol_ord);
		}
	} else {
		$update = "UPDATE pmp_regroupement
					SET volord = NULL
					WHERE id = '$id'";
		$res = my_query($co_pmp, $update);

		// Forcer valeur NULL pour éviter lâ€™enregistrement du tableau
		$vol_ord = NULL;

		if ($grp["volord"] != NULL) {
			TraceHistoGrpt($co_pmp, $id, 'Qté Vol Ord', $vol_ord);
		}
	}

	// --- VOLUME SUPPLÃ‰MENTAIRE ---
	if (isset($vol_sup["qte_sup"]) && $vol_sup["qte_sup"] != NULL) {
		$vol_sup = $vol_sup["qte_sup"];
		$update = "UPDATE pmp_regroupement
					SET volsup = '$vol_sup'
					WHERE id = '$id'";
		$res = my_query($co_pmp, $update);

		if ($vol_sup != $grp["volsup"]) {
			TraceHistoGrpt($co_pmp, $id, 'Qté Vol Sup', $vol_sup);
		}
	} else {
		$update = "UPDATE pmp_regroupement
					SET volsup = NULL
					WHERE id = '$id'";
		$res = my_query($co_pmp, $update);

		// Forcer valeur NULL pour éviter lâ€™enregistrement du tableau
		$vol_sup = NULL;

		if ($grp["volsup"] != NULL) {
			TraceHistoGrpt($co_pmp, $id, 'Qté Vol Sup', $vol_sup);
		}
	}
}


if (!empty($_POST["calc_fact"])) {
	if (isset($_GET["id_grp"])) {
		$id = $_GET["id_grp"];
		$vol_ord = getQuantiteVolumeOrdTableau($co_pmp, $_GET["id_grp"]);
		$vol_sup = getQuantiteVolumeSupTableau($co_pmp, $_GET["id_grp"]);

		$grp = getGroupementDetails($co_pmp, $id);

		//Calculer montant fact HT
		$ComOrdiHT = $_POST["com_ord"];
		$VolOrdi = $_POST["vol_ord"];

		$ComSupHT = $_POST["com_sup"];
		$VolSup = $_POST["vol_sup"];

		$ComOrdiHT = str_replace(',', '.', $ComOrdiHT);
		$ComSupHT = str_replace(',', '.', $ComSupHT);

		$MtFactHT = (((float) $ComOrdiHT * (float) $VolOrdi) + ((float) $ComSupHT * (float) $VolSup)) / 1000;
		$MtFactHT = number_format($MtFactHT, 2, ',', '');

		$update = " UPDATE pmp_regroupement
					SET mtfactht = '$MtFactHT'
					WHERE id = '$id' ";
		$res = my_query($co_pmp, $update);
		if ($MtFactHT != $grp["mtfactht"]) {
			TraceHistoGrpt($co_pmp, $id, 'Montant Fact HT', $MtFactHT);
		}

		// //Calculer montant fact TTC
		$ComOrdiTTC = $_POST["com_ord_ttc"];
		$ComSupTTC = $_POST["com_sup_ttc"];
		$ComOrdiTTC = str_replace(',', '.', $ComOrdiTTC);
		$ComSupTTC = str_replace(',', '.', $ComSupTTC);
		$MtFact = (((float) $ComOrdiTTC * (float) $VolOrdi) + ((float) $ComSupTTC * (float) $VolSup)) / 1000;
		$MtFact = number_format($MtFact, 2, ',', '');
		$update = " UPDATE pmp_regroupement
					SET mtfact = '$MtFact'
					WHERE id = '$id' ";
		$res = my_query($co_pmp, $update);
		if ($MtFact != $grp["mtfact"]) {
			TraceHistoGrpt($co_pmp, $id, 'Montant Fact TTC', $MtFact);
		}

	}
}

//Afficher les zones dans groupements
function getRegroupementZones(&$co_pmp, $id, $inout)
{
	$query = "  SELECT pmp_fournisseur_zone.libelle, pmp_fournisseur_zone.id, pmp_fournisseur.nom
	 			FROM pmp_fournisseur_zone, pmp_fournisseur, pmp_regrp_zone
				WHERE pmp_fournisseur_zone.fournisseur_id = pmp_fournisseur.id
				AND pmp_fournisseur_zone.id = pmp_regrp_zone.zone_id
				AND pmp_regrp_zone.regrp_id = '$id'
				AND pmp_regrp_zone.in_out = '$inout'";
	$res = my_query($co_pmp, $query);
	return $res;
}

// Définir zone groupement
if (!empty($_POST["definir_zone_grp"])) {
	if (isset($_POST["zone_groupement"])) {
		$id_grp = $_GET["id_grp"];
		$id_zone = $_POST["zone_groupement"];
		$fournisseur = $_POST["fournisseur_ajax"];
		$query = "  INSERT INTO pmp_regrp_zone (id, regrp_id, zone_id, in_out)
					VALUES ('', $id_grp, $id_zone, '1') ";
		$res = my_query($co_pmp, $query);

		if ($res) {
			$four = " SELECT nom FROM pmp_fournisseur
						WHERE id = '$fournisseur' ";
			$four = my_query($co_pmp, $four);
			$four = mysqli_fetch_array($four);

			$zone = " SELECT libelle FROM pmp_fournisseur_zone
						WHERE id = '$id_zone' ";
			$zone = my_query($co_pmp, $zone);
			$zone = mysqli_fetch_array($zone);


			TraceHistoGrpt($co_pmp, $id_grp, 'Zone à inclure', 'Fournisseur : ' . $four["nom"] . " - Zone : " . $zone["libelle"]);
		}
	}
}

// Supprimer zone gorupement
if (!empty($_POST["supprimer_zone_grp"])) {
	if (isset($_POST["supp_grp_zone_id"])) {
		$id_grp = $_GET["id_grp"];
		$id_zone = $_POST["supp_grp_zone_id"];
		$query = "  DELETE FROM pmp_regrp_zone
					WHERE zone_id = '$id_zone'
					AND regrp_id = '$id_grp'";
		$res = my_query($co_pmp, $query);
		if ($res) {
			$zone = " SELECT libelle FROM pmp_fournisseur_zone
						WHERE id = '$id_zone' ";
			$zone = my_query($co_pmp, $zone);
			$zone = mysqli_fetch_array($zone);

			TraceHistoGrpt($co_pmp, $id_grp, 'Zone supprimé', " Zone : " . $zone["libelle"]);
		}
	}
}

//Définir zone à exclure groupement
if (!empty($_POST["definir_zone_grp_exclure"])) {
	if (isset($_POST["zone_groupement_exclure"])) {
		$id_grp = $_GET["id_grp"];
		$id_zone = $_POST["zone_groupement_exclure"];
		$fournisseur = $_POST["fournisseur_id_2"];
		$query = "  INSERT INTO pmp_regrp_zone (id, regrp_id, zone_id, in_out)
					VALUES ('', $id_grp, $id_zone, '0') ";
		$res = my_query($co_pmp, $query);
		if ($res) {
			$four = " SELECT nom FROM pmp_fournisseur
						WHERE id = '$fournisseur' ";
			$four = my_query($co_pmp, $four);
			$four = mysqli_fetch_array($four);

			$zone = " SELECT libelle FROM pmp_fournisseur_zone
						WHERE id = '$id_zone' ";
			$zone = my_query($co_pmp, $zone);
			$zone = mysqli_fetch_array($zone);

			TraceHistoGrpt($co_pmp, $id_grp, 'Zone à exclure', 'Fournisseur : ' . $four["nom"] . " - Zone : " . $zone["libelle"]);
		}
	}
}

// Supprimer zone exclus gorupement
if (!empty($_POST["supp_zone_grp_exclure"])) {
	if (isset($_POST["supp_grp_zone_id_exclus"])) {
		$id_grp = $_GET["id_grp"];
		$id_zone = $_POST["supp_grp_zone_id_exclus"];
		$query = "  DELETE FROM pmp_regrp_zone
					WHERE zone_id = '$id_zone'
					AND regrp_id = '$id_grp'";
		$res = my_query($co_pmp, $query);
		if ($res) {
			$zone = " SELECT libelle FROM pmp_fournisseur_zone
						WHERE id = '$id_zone' ";
			$zone = my_query($co_pmp, $zone);
			$zone = mysqli_fetch_array($zone);

			TraceHistoGrpt($co_pmp, $id_grp, 'Zone supprimé', " Zone : " . $zone["libelle"]);
		}
	}
}

// Supprimer une commande du groupement
if (!empty($_POST["supp_cmdes_grps"])) {
	if ($_POST["id_cmdes_supp"] != "") {
		$id_grp = $_GET["id_grp"];
		$id_cmde = $_POST["id_cmdes_supp"];
		$commande_details = getCommandeDetailsClients($co_pmp, $id_cmde);
		$query = "  UPDATE pmp_commande
					SET groupe_cmd = '0', cmd_prix_ord = NULL, cmd_prix_sup = NULL
					WHERE id = '$id_cmde' ";
		$res = my_query($co_pmp, $query);
		if (isset($res)) {
			TraceHisto($co_pmp, $id_cmde, 'Supprimer Groupement', $id_grp);
			if ($commande_details["cmd_status"] >= 12) {
				$query = "  UPDATE pmp_commande
							SET cmd_status = '10'
							WHERE id = '$id_cmde' ";
				$res = my_query($co_pmp, $query);
				if (isset($res)) {
					TraceHisto($co_pmp, $id_cmde, 'Statut', '10');
				}
			}

			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "La commande n°" . $id_cmde . " a bien été supprimée du groupement.";
			return $res;
		}
	} else {
		$message_type = "info";
		$message_icone = "fa-exclamation";
		$message_titre = "Info";
		$message = "Aucune commande sélectionnée.";
	}
}

// Supprimer toutes les commandes du groupement
if (!empty($_POST["supprimer_tout"])) {
	$id_grp = $_GET["id_grp"];
	$res_cmd = getCommandesGroupements($co_pmp, $id_grp);

	while ($cmd = mysqli_fetch_array($res_cmd)) {
		$id_cmde = $cmd["id_cmd"];
		$query = "  UPDATE pmp_commande
					SET groupe_cmd = '0', cmd_prix_ord = NULL, cmd_prix_sup = NULL
					WHERE id = '$id_cmde' ";
		$res = my_query($co_pmp, $query);
		if (isset($res)) {
			TraceHisto($co_pmp, $id_cmde, 'Supprimer Groupement', $id_grp);
			if ($cmd["cmd_status"] >= 12) {
				$query = "  UPDATE pmp_commande
							SET cmd_status = '10'
							WHERE id = '$id_cmde' ";
				$res = my_query($co_pmp, $query);
				if (isset($res)) {
					TraceHisto($co_pmp, $id_cmde, 'Statut', '10 - Utilisateur');
				}
			}
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Les commandes ont bien été supprimées du groupement.";
		}
	}
}

// Récuperation prix
// On regarde par requÃªte s'il y a des demandes de récupérations déjà en cours pour le groupement en question
function getRecupEnCoursGroupement(&$co_pmp, $id_grp)
{
	$query = "  SELECT count(*) as en_cours
         		FROM pmp_commande
         		WHERE (cmd_prixfm = '1' or cmd_prixfr = '1' or cmd_prixaf = '1' or cmd_prixfmc = '1')
         		AND groupe_cmd = '$id_grp' ";
	$res = my_query($co_pmp, $query);
	$nTotal_l = mysqli_fetch_array($res);
	return $nTotal_l;
}
// On regarde par requÃªte s'il y a des demandes de récupérations déjà en cours sur toute la base:
function getRecupEnCours(&$co_pmp)
{
	$query = "  SELECT count(*) as en_cours
         		FROM pmp_commande
         		WHERE (cmd_prixfm = '1' or cmd_prixfr = '1' or cmd_prixaf = '1' or cmd_prixfmc = '1') ";
	$res = my_query($co_pmp, $query);
	$nTotal_l = mysqli_fetch_array($res);
	return $nTotal_l;
}
//On regarde par requÃªte s'il y a des demandes de récupérations pour le groupement qui n'ont pas fonctionné :
function getRecupGroupement(&$co_pmp, $id_grp)
{
	$query = "  SELECT count(*) as commande
         		FROM pmp_commande
         		WHERE (cmd_prixfm = '0' or cmd_prixfr = '0' or cmd_prixaf = '0' or cmd_prixfmc = '0')
         		AND groupe_cmd = '$id_grp' ";
	$res = my_query($co_pmp, $query);
	$nTotal_l = mysqli_fetch_array($res);
	return $nTotal_l;
}
//On compte le nombre de calcul à faire pour l'afficher dans le dernier message;
function getNombreCalcul(&$co_pmp, $id_grp)
{
	$query = "  SELECT count(*) as nombre
         		FROM pmp_commande
         		WHERE groupe_cmd = '$id_grp'
         		AND cmd_status = 15 ";
	$res = my_query($co_pmp, $query);
	$nTotal_l = mysqli_fetch_array($res);
	return $nTotal_l;
}
//Et on lance la récupération
function updateRecuperation(&$co_pmp, $id_grp, $nombre)
{
	$query = "  UPDATE  pmp_commande
				SET cmd_prixfm = '1', cmd_prixfr = '1', cmd_prixaf = '1', cmd_prixfmc = '1'
				WHERE groupe_cmd = '$id_grp'
         		AND cmd_status = 15 ";

	$res = my_query($co_pmp, $query);
}

// CHARGER CLIENT (nouvelle version)
// Fonction utilitaire : récupère tous les utilisateurs d’un groupement (avec ou sans commande) en excluant 25/30
function getCommandesUtilisateurs_legacy(&$co_pmp, $id_grp)
{
	$query = "
        SELECT
            u.user_id,
            MAX(c.cmd_dt) AS last_cmd_dt
        FROM pmp_utilisateur u
        INNER JOIN jjj_users j ON j.id = u.user_id
        INNER JOIN pmp_code_postal cp ON u.code_postal = cp.code_postal
        INNER JOIN pmp_zone_cp zc ON zc.code_postal_id = cp.id
        INNER JOIN pmp_regrp_zone rz ON rz.zone_id = zc.zone_id
        LEFT JOIN pmp_commande c ON u.user_id = c.user_id
            AND c.cmd_status NOT IN (25, 30)
            AND (c.groupe_cmd != " . intval($id_grp) . " OR c.groupe_cmd IS NULL)
        WHERE u.inscrit = 1
		  AND COALESCE(u.disabled_account, 0) = 0
          AND rz.regrp_id = " . intval($id_grp) . "
          AND rz.in_out = 1
          AND zc.actif = 1
          AND NOT EXISTS (
              SELECT 1 FROM pmp_commande c25
              WHERE c25.user_id = u.user_id
              AND c25.cmd_status IN (25, 30)
          )
        GROUP BY u.user_id
    ";
	return my_query($co_pmp, $query);
}


if (!empty($_POST["charger_client"])) {
	set_time_limit(0); // Evite un timeout PHP

	$id_grp = intval($_GET["id_grp"]);

	// ?tape 1 : R?cup?rer tous les utilisateurs concern?s
	$usersRes = getCommandesUtilisateurs($co_pmp, $id_grp);

	$allUserIds = [];
	while ($row = mysqli_fetch_assoc($usersRes)) {
		$allUserIds[] = (int) $row['user_id'];
	}

	if (empty($allUserIds)) {
		exit("Aucun utilisateur à traiter. Merci de vérifier les critères du groupement (fournisseur/zone).");
	}

	// ?tape 2 : Filtrer les utilisateurs réellement actionnables et récupérer leur dernière commande
	$commandesParUser = [];
	$actionableUserIds = filterChargerClientUserIds($co_pmp, $allUserIds, $commandesParUser);

	if (empty($actionableUserIds)) {
		exit("Aucune commande n'est éligible (utilisateurs déjà groupés ou commandes verrouillées).");
	}

	// ?tape 3 : Pr?parer les updates / inserts
	$updates = [];
	$inserts = [];

	foreach ($commandesParUser as $user_id => $cmd) {
		if ($cmd["cmd_status"] >= 10 && $cmd["cmd_status"] <= 20 && $cmd["groupe_cmd"] == 0) {
			$updates[] = $cmd['id'];
		} elseif ($cmd["cmd_status"] < 10 || $cmd["cmd_status"] > 20) {
			$inserts[] = $user_id;
		}
	}

	// Ajouter les utilisateurs sans commande aux inserts
	$usersSansCommande = array_diff($actionableUserIds, array_keys($commandesParUser));
	if (!empty($usersSansCommande)) {
		$inserts = array_merge($inserts, $usersSansCommande);
	}

	// ?tape 4 : Ex?cuter les actions group?es
	if (!empty($updates)) {
		$idsList = implode(',', array_map('intval', $updates));
		$query = "
            UPDATE pmp_commande
            SET groupe_cmd = '$id_grp'
            WHERE id IN ($idsList) AND groupe_cmd = '0'
        ";
		my_query($co_pmp, $query);

		foreach ($updates as $id) {
			TraceHisto($co_pmp, $id, 'Ajout Groupement', $id_grp);
		}
	}

	if (!empty($inserts)) {
		$values = [];
		foreach ($inserts as $user_id) {
			$values[] = "('$user_id', '$id_grp', NOW(), '1', '0', '0', '13')";
		}
		$query = "
            INSERT INTO pmp_commande (
                user_id, groupe_cmd, cmd_dt, cmd_typefuel, cmd_qte, cmd_qtelivre, cmd_status
            ) VALUES " . implode(',', $values);

		my_query($co_pmp, $query);

		$firstId = mysqli_insert_id($co_pmp);
		foreach (range($firstId, $firstId + count($inserts) - 1) as $id) {
			TraceHisto($co_pmp, $id, 'Statut', '13 - Groupement ' . $id_grp);
		}
	}

	header('Location: /admin/details_groupement.php?id_grp=' . $id_grp . '&charger_client=oui');
	exit;
}



// Ancienne version conservée en dessous (désactivée)
// CHARGER CLIENT
// Fonction utilitaire : récupère tous les utilisateurs dâ€™un groupement (avec ou sans commande)
function getCommandesUtilisateurs(&$co_pmp, $id_grp)
{
	$query = "
        SELECT
            u.user_id,
            MAX(c.cmd_dt) AS last_cmd_dt
        FROM pmp_utilisateur u
        INNER JOIN jjj_users j ON j.id = u.user_id
        INNER JOIN pmp_code_postal cp ON u.code_postal = cp.code_postal
        INNER JOIN pmp_zone_cp zc ON zc.code_postal_id = cp.id
        INNER JOIN pmp_regrp_zone rz ON rz.zone_id = zc.zone_id
        LEFT JOIN pmp_commande c ON u.user_id = c.user_id
            AND c.cmd_status NOT IN (25, 30)
            AND (c.groupe_cmd != " . intval($id_grp) . " OR c.groupe_cmd IS NULL)
        WHERE u.inscrit = 1
		  AND COALESCE(u.disabled_account, 0) = 0
          AND rz.regrp_id = " . intval($id_grp) . "
          AND rz.in_out = 1
          AND zc.actif = 1
          AND NOT EXISTS (
              SELECT 1 FROM pmp_commande c25
              WHERE c25.user_id = u.user_id
              AND c25.cmd_status IN (25, 30)
          )
        GROUP BY u.user_id
    ";
	return my_query($co_pmp, $query);
}

function filterChargerClientUserIds(&$co_pmp, array $userIds, &$commandesParUserRef = null)
{
	if (empty($userIds)) {
		if (is_array($commandesParUserRef)) {
			$commandesParUserRef = [];
		}
		return [];
	}

	$userIds = array_values(array_unique(array_map('intval', $userIds)));
	if (empty($userIds)) {
		if (is_array($commandesParUserRef)) {
			$commandesParUserRef = [];
		}
		return [];
	}

	$commandesParUser = [];
	$chunks = array_chunk($userIds, 500);
	foreach ($chunks as $chunk) {
		$idsList = implode(',', $chunk);
		$query = "
            SELECT c.*
            FROM pmp_commande c
            INNER JOIN (
                SELECT user_id, MAX(cmd_dt) AS last_cmd_dt
                FROM pmp_commande
                WHERE user_id IN ($idsList) AND cmd_status NOT IN (25, 30)
                GROUP BY user_id
            ) AS last_cmd
            ON c.user_id = last_cmd.user_id AND c.cmd_dt = last_cmd.last_cmd_dt
            WHERE c.cmd_status NOT IN (25, 30)
        ";
		$resCmd = my_query($co_pmp, $query);
		while ($cmd = mysqli_fetch_assoc($resCmd)) {
			$commandesParUser[(int) $cmd["user_id"]] = $cmd;
		}
	}

	$actionable = [];

	foreach ($commandesParUser as $user_id => $cmd) {
		$statut = (int) $cmd["cmd_status"];
		$groupe = (int) $cmd["groupe_cmd"];

		if ($statut >= 10 && $statut <= 20) {
			if ($groupe === 0) {
				$actionable[] = $user_id;
			}
		} else {
			$actionable[] = $user_id;
		}
	}

	$usersWithoutCmd = array_diff($userIds, array_keys($commandesParUser));
	if (!empty($usersWithoutCmd)) {
		$actionable = array_merge($actionable, $usersWithoutCmd);
	}

	$actionable = array_values(array_unique($actionable));

	if (is_array($commandesParUserRef)) {
		$filtered = [];
		foreach ($actionable as $uid) {
			if (isset($commandesParUser[$uid])) {
				$filtered[$uid] = $commandesParUser[$uid];
			}
		}
		$commandesParUserRef = $filtered;
	}

	return $actionable;
}

// Traite un batch d'utilisateurs pour "charger client" (exclut 25/30)
function traiterBatchChargerClient(&$co_pmp, $id_grp, array $userIds)
{
	if (empty($userIds)) {
		return ['updates' => 0, 'inserts' => 0];
	}

	// 1) Dernière commande éligible par utilisateur (hors 25/30)
	$idsList = implode(',', array_map('intval', $userIds));
	$commandesParUser = [];

	$query = "
        SELECT c.*
        FROM pmp_commande c
        INNER JOIN (
            SELECT user_id, MAX(cmd_dt) AS last_cmd_dt
            FROM pmp_commande
            WHERE user_id IN ($idsList) AND cmd_status NOT IN (25, 30)
            GROUP BY user_id
        ) AS last_cmd
        ON c.user_id = last_cmd.user_id AND c.cmd_dt = last_cmd.last_cmd_dt
        WHERE c.cmd_status NOT IN (25, 30)
    ";
	$resCmd = my_query($co_pmp, $query);
	while ($cmd = mysqli_fetch_assoc($resCmd)) {
		$commandesParUser[$cmd["user_id"]] = $cmd;
	}

	// 2) Préparer updates / inserts
	$updates = [];
	$inserts = [];

	foreach ($commandesParUser as $user_id => $cmd) {
		if ($cmd["cmd_status"] >= 10 && $cmd["cmd_status"] <= 20 && $cmd["groupe_cmd"] == 0) {
			$updates[] = $cmd['id'];
		} elseif ($cmd["cmd_status"] < 10 || $cmd["cmd_status"] > 20) {
			$inserts[] = $user_id;
		}
		// Si status 10-20 mais déjà groupé ailleurs : on ignore (comportement historique)
	}

	// Ajouter les utilisateurs sans commande éligible
	$usersSansCommande = array_diff($userIds, array_keys($commandesParUser));
	if (!empty($usersSansCommande)) {
		$inserts = array_merge($inserts, $usersSansCommande);
	}

	// 3) Exécuter updates
	if (!empty($updates)) {
		$idsUpdate = implode(',', array_map('intval', $updates));
		$updateQuery = "
            UPDATE pmp_commande
            SET groupe_cmd = '" . intval($id_grp) . "'
            WHERE id IN ($idsUpdate) AND groupe_cmd = '0'
        ";
		my_query($co_pmp, $updateQuery);

		foreach ($updates as $id) {
			TraceHisto($co_pmp, $id, 'Ajout Groupement', $id_grp);
		}
	}

	// 4) Exécuter inserts
	if (!empty($inserts)) {
		$values = [];
		foreach ($inserts as $user_id) {
			$values[] = "('" . intval($user_id) . "', '" . intval($id_grp) . "', NOW(), '1', '0', '0', '13')";
		}
		$insertQuery = "
            INSERT INTO pmp_commande (
                user_id, groupe_cmd, cmd_dt, cmd_typefuel, cmd_qte, cmd_qtelivre, cmd_status
            ) VALUES " . implode(',', $values);

		my_query($co_pmp, $insertQuery);

		$firstId = mysqli_insert_id($co_pmp);
		foreach (range($firstId, $firstId + count($inserts) - 1) as $id) {
			TraceHisto($co_pmp, $id, 'Statut', '13 - Groupement ' . $id_grp);
		}
	}

	return ['updates' => count($updates), 'inserts' => count($inserts)];
}

function ajouterCommandegroupement(&$co_pmp, $id, $id_grp)
{
	$query = "  UPDATE pmp_commande
				SET groupe_cmd = '$id_grp'
				WHERE id = '$id' ";
	$res = my_query($co_pmp, $query);

	if ($res) {
		TraceHisto($co_pmp, $id, 'Commande ajoutée groupement', $id_grp);
		header('Location: /admin/details_groupement.php?id_grp=' . $id_grp);

	}
}

if (!empty($_POST["ordre_tri"])) {
	$ordre_tri_1 = $_POST["ordre_tri_1"];
	$ordre_tri_2 = $_POST["ordre_tri_2"];
	$id_grp = $_GET["id_grp"];

	$ordre = $ordre_tri_1 . "" . $ordre_tri_2;

	$query = "  UPDATE pmp_regroupement
				SET options = '$ordre'
				WHERE id = '$id_grp' ";
	$res = my_query($co_pmp, $query);
	return $res;
}

//Afficher les commandes à affecter pour un groupement
function getCommandesAffecterGroupement(&$co_pmp, $id_grp)
{
	$query = "  SELECT DISTINCT pmp_commande.id, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.name, pmp_commande.cmd_status, pmp_commande.cmd_qte,
                  pmp_utilisateur.prenom,  pmp_utilisateur.user_id, pmp_utilisateur.code_postal_id, pmp_commande.cmd_dt, pmp_regroupement.id AS id_grp, pmp_commande.cmd_typefuel, pmp_regroupement.libelle
                FROM pmp_commande, pmp_utilisateur, jjj_users, pmp_zone_cp, pmp_regrp_zone, pmp_regroupement
                WHERE pmp_commande.user_id = pmp_utilisateur.user_id
                AND pmp_utilisateur.user_id = jjj_users.id
                AND    pmp_utilisateur.inscrit = 1
                AND pmp_regroupement.id = pmp_regrp_zone.regrp_id
                AND pmp_regrp_zone.zone_id = pmp_zone_cp.zone_id
                AND    pmp_regrp_zone.in_out = 1
                AND pmp_zone_cp.actif = '1'
                AND pmp_utilisateur.code_postal_id = pmp_zone_cp.code_postal_id
                AND pmp_regroupement.statut = 10
                AND pmp_commande.groupe_cmd = '0'
				AND pmp_regroupement.id = '$id_grp'
                AND (pmp_commande.cmd_status = 10 or pmp_commande.cmd_status = 12)
                AND not exists (select    1
                            from    pmp_zone_cp, pmp_regrp_zone
                            where    pmp_regroupement.id = pmp_regrp_zone.regrp_id
                            AND    pmp_regrp_zone.zone_id = pmp_zone_cp.zone_id
                            AND    pmp_regrp_zone.in_out = 0
                            AND pmp_zone_cp.actif = '1'
                            AND pmp_utilisateur.code_postal_id = pmp_zone_cp.code_postal_id) ";
	$res = my_query($co_pmp, $query);
	return $res;
}

if (!empty($_POST["valider_commandes_affecter"])) {
	if (!empty($_POST["cmd_id"])) {
		$id_grp = $_GET["id_grp"];
		$id_cmd = $_POST["cmd_id"];
		$cmd_qte = $_POST["cmd_qte"];
		for ($i = 0; $i < $_POST['nb_cmd']; $i++) {
			$tmp = 'select_cmd_' . $i;
			$id = $id_cmd[$i];
			$qte = $cmd_qte[$i];
			$cmd = isset($_POST[$tmp]) ? "1" : "0";
			if ($cmd == 1) {
				$plages = ChargePlagesPrix($co_pmp, $id_grp, $qte);
				if ($plages['prix_ord'] > 0 && $plages['prix_ord'] != NULL || $plages['prix_sup'] > 0 && $plages['prix_sup'] != NULL) {
					$update = " UPDATE pmp_commande SET groupe_cmd = '$id_grp', cmd_status = '17', cmd_prix_ord='" . $plages['prix_ord'] . "', cmd_prix_sup='" . $plages['prix_sup'] . "'
								WHERE id = '$id' ";
					$res = my_query($co_pmp, $update);
					if ($res) {
						$prixLitre = number_format($plages['prix_ord'] / 1000, 3, ",", " ");
						TraceHisto($co_pmp, $id, 'Prix litre Ord', $prixLitre);

						$prixLitre = number_format($plages['prix_sup'] / 1000, 3, ",", " ");
						TraceHisto($co_pmp, $id, 'Prix litre Sup', $prixLitre);

						TraceHisto($co_pmp, $id, 'Statut', "17 Prix proposé - Commande affectée au groupement " . $id_grp);
						$message_titre = "Succès";
						$message_type = "success";
						$message_icone = "fa-check";
						$message = "Les commandes ont été affectées au groupement avec le status 17 - Prix proposé.";
					}
				} else {
					$update = " UPDATE pmp_commande SET groupe_cmd = '$id_grp', cmd_status = '15'
								WHERE id = '$id' ";
					$res = my_query($co_pmp, $update);
					if ($res) {
						TraceHisto($co_pmp, $id, 'Statut', "15 Groupée - Commande affectée au groupement " . $id_grp);
						$message_titre = "Succès";
						$message_type = "success";
						$message_icone = "fa-check";
						$message = "Les commandes ont été affectées au groupement avec le status 15 - Groupée.";
					}
				}
			}
		}
	}
}

if (!empty($_POST["creer_commande_groupement"])) {
	if (strlen($_POST["user_id_client"]) > 0) {
		$user_id = $_POST["user_id_client"];
		$id_grp = $_GET["id_grp"];

		$cmd = getCommandeUtilisateurEnCours($co_pmp, $user_id);
		if (strlen($cmd["id"]) > 0) {
			$message_type = "info";
			$message_icone = "fa-exclamation";
			$message_titre = "Info";
			$message_modal = "Le client a déjà une commande en cours sur un autre groupement.";
		} else {
			$query = "  INSERT INTO pmp_commande (id, user_id, groupe_cmd, cmd_dt, cmd_typefuel, cmd_qte, cmd_qtelivre, cmd_prixfm, cmd_prixfr, cmd_prixfmc, cmd_prixpmp, cmd_prixaf, cmd_status, cmd_comment, cmd_commentfour, cmd_nbcuve, cmd_prix_ord, cmd_prix_sup, cmd_comment_du_four )
						VALUES ('', '$user_id', '$id_grp', NOW(), '1', '0', '0', NULL, NULL, NULL, NULL, NULL, '13', NULL, NULL, NULL, NULL, NULL, NULL) ";
			$res = my_query($co_pmp, $query);
			if ($res) {
				$last_id = mysqli_insert_id($co_pmp);
				TraceHisto($co_pmp, $last_id, 'Statut', '13 - Groupement ' . $id_grp);
				$message_titre = "Succès";
				$message_type = "success";
				$message_icone = "fa-check";
				$message_modal = "Le client a été ajouté au groupement au statuts 13 - Proposé.";
			}
		}
	}


}



if (!empty($_POST["ajouter_liste"])) {
	$id_grp = $_GET["id_grp"];

	if (isset($_POST["nb_client"])) {
		$nb = $_POST["nb_client"];
	} elseif (isset($_POST["nb_commande"])) {
		$nb = $_POST["nb_commande"];
	}
	for ($i = 0; $i < $nb; $i++) {
		$tmp = 'id_client_' . $i;
		$user_id = $_POST[$tmp];
		$cmd = getCommandeUtilisateurEnCours($co_pmp, $user_id);
		if (strlen($cmd["id"]) <= 0) {
			$query = "  INSERT INTO pmp_commande (id, user_id, groupe_cmd, cmd_dt, cmd_typefuel, cmd_qte, cmd_qtelivre, cmd_prixfm, cmd_prixfr, cmd_prixfmc, cmd_prixpmp, cmd_prixaf, cmd_status, cmd_comment, cmd_commentfour, cmd_nbcuve, cmd_prix_ord, cmd_prix_sup, cmd_comment_du_four )
						VALUES ('', '$user_id', '$id_grp', NOW(), '1', '0', '0', NULL, NULL, NULL, NULL, NULL, '13', NULL, NULL, NULL, NULL, NULL, NULL) ";
			$res = my_query($co_pmp, $query);
			if ($res) {
				$last_id = mysqli_insert_id($co_pmp);
				TraceHisto($co_pmp, $last_id, 'Statut', '13 - Groupement ' . $id_grp);
				$message_titre = "Succès";
				$message_type = "success";
				$message_icone = "fa-check";
				$message_modal = "Les clients sans commande en cours ont été ajoutés au groupement au statuts 13 - Proposé.";
			}
		}
	}
}

if (!empty($_POST["modifier_statut_grp"])) {
	$id = $_POST["ids_grp"] ?? '';
	$statut = $_POST["nouveau_statut"];
	$message_mod = "";

	if ($statut == 5) {
		$statut_a = "5 - Prévu";
	}
	if ($statut == 10) {
		$statut_a = "10 - Crée";
	}
	if ($statut == 15) {
		$statut_a = "15 - Envoyé";
	}
	if ($statut == 30) {
		$statut_a = "30 - Livré";
	}
	if ($statut == 33) {
		$statut_a = "33 - A facturé";
	}
	if ($statut == 37) {
		$statut_a = "37 - Facturé";
	}
	if ($statut == 50) {
		$statut_a = "50 - Annulé";
	}

	$id_grps = array_filter(array_map('trim', explode(";", $id)));

	if (empty($id_grps)) {
		$message_type = "info";
		$message_icone = "fa-exclamation";
		$message_titre = "Info";
		$message = "Aucun groupement n'a été sélectionné pour la mise à jour.";
	} else {
		foreach ($id_grps as $id_grp) {
			$grp = getGroupementDetails($co_pmp, $id_grp);

			if ($grp["statut"] == 5) {
				$statut_b = "5 - Prévu";
			}
			if ($grp["statut"] == 10) {
				$statut_b = "10 - Crée";
			}
			if ($grp["statut"] == 15) {
				$statut_b = "15 - Envoyé";
			}
			if ($grp["statut"] == 30) {
				$statut_b = "30 - Livré";
			}
			if ($grp["statut"] == 33) {
				$statut_b = "33 - A facturé";
			}
			if ($grp["statut"] == 37) {
				$statut_b = "37 - Facturé";
			}
			if ($grp["statut"] == 50) {
				$statut_b = "50 - Annulé";
			}

			if ($statut == 15) {
				$query = "	SELECT *
					FROM pmp_commande
					WHERE groupe_cmd = '" . mysqli_real_escape_string($co_pmp, $id_grp) . "'
					AND cmd_status < 25
					 ";
				$res = my_query($co_pmp, $query);
				$cmd = mysqli_fetch_array($res);
				if (isset($cmd)) {
					if (strlen($cmd[0]) > 0) {
						$message_mod .= "1";
					}
				} else {
					$query = "  UPDATE pmp_regroupement
							SET statut = '$statut'
							WHERE id = '$id_grp' ";
					$res = my_query($co_pmp, $query);
					if ($res) {
						TraceHistoGrpt($co_pmp, $id_grp, 'Statut', $statut_b . " -> " . $statut_a);

					}
				}
			} elseif ($statut == 37) {
				$query = "	SELECT *
					FROM pmp_commande
					WHERE groupe_cmd = '" . mysqli_real_escape_string($co_pmp, $id_grp) . "'
					AND cmd_status < 40
					 ";
				$res = my_query($co_pmp, $query);
				$cmd = mysqli_fetch_array($res);



				if (isset($cmd)) {
					if (strlen($cmd[0]) > 0) {
						$message_mod .= "1";
					}
				} elseif ($grp["numfact"] == "") {
					$message_mod .= "1";
				} else {
					$query = "  UPDATE pmp_regroupement
							SET statut = '$statut'
							WHERE id = '$id_grp' ";
					$res = my_query($co_pmp, $query);
					if ($res) {
						TraceHistoGrpt($co_pmp, $id_grp, 'Statut', $statut_b . " -> " . $statut_a);

					}
				}
			} elseif ($statut == 40) {
				$query = "	SELECT *
					FROM pmp_commande
					WHERE groupe_cmd = '" . mysqli_real_escape_string($co_pmp, $id_grp) . "'
					AND cmd_status < 25
					 ";
				$res = my_query($co_pmp, $query);
				$cmd = mysqli_fetch_array($res);
				if (isset($cmd)) {
					if (strlen($cmd[0]) > 0) {
						$message_mod .= "1";
					}
				} else {
					$query = "  UPDATE pmp_regroupement
							SET statut = '$statut'
							WHERE id = '$id_grp' ";
					$res = my_query($co_pmp, $query);
					if ($res) {
						TraceHistoGrpt($co_pmp, $id_grp, 'Statut', $statut_b . " -> " . $statut_a);

					}
				}
			} else {
				$query = "  UPDATE pmp_regroupement
						SET statut = '$statut'
						WHERE id = '$id_grp' ";
				$res = my_query($co_pmp, $query);
				if ($res) {
					TraceHistoGrpt($co_pmp, $id_grp, 'Statut', $statut_b . " -> " . $statut_a);

				}
			}
		}
		if (isset($statut_a)) {
			if (strlen($message_mod) > 0) {
				$message_type = "info";
				$message_icone = "fa-exclamation";
				$message_titre = "Info";
				$message = "Les groupements sans commandes en cours ont bien été modifiés au statut " . $statut_a . ".";
			} else {
				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "Les groupements ont bien été modifiés au statut " . $statut_a . ".";
			}
		}
	}
}
