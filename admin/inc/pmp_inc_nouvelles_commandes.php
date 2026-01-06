<?php
function TraceHisto(&$co_pmp, $id, $param1, $param2)
{
	$user = $_SESSION['user'];
	$param2 = addslashes($param2);
	$query = "  INSERT INTO pmp_commande_histo (cmd_id, his_intervenant, his_date, his_action, his_valeur)
				VALUES ('$id', '$user', NOW(), '$param1', '$param2') ";
	$res = my_query($co_pmp, $query);
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

function getNouvellesCommandes(&$co_pmp)
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

function getCommandesOrphelines(&$co_pmp)
{
	$query = "  SELECT DISTINCT pmp_commande.id, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.name, pmp_commande.cmd_status, pmp_commande.cmd_qte,
                  pmp_utilisateur.prenom,  pmp_utilisateur.user_id, pmp_utilisateur.code_postal_id, pmp_commande.cmd_dt, pmp_commande.cmd_typefuel
                FROM pmp_commande, pmp_utilisateur, jjj_users
                WHERE pmp_commande.user_id = pmp_utilisateur.user_id
                AND pmp_utilisateur.user_id = jjj_users.id
                AND pmp_commande.groupe_cmd = '0'
                AND (pmp_commande.cmd_status = 10 or pmp_commande.cmd_status = 12)
                 AND not exists (select pmp_regroupement.id from pmp_regroupement, pmp_regrp_zone, pmp_zone_cp
                                WHERE pmp_utilisateur.code_postal_id = pmp_zone_cp.code_postal_id
                                AND pmp_zone_cp.actif = '1'
                                AND pmp_regrp_zone.zone_id = pmp_zone_cp.zone_id
                                AND pmp_regroupement.id = pmp_regrp_zone.regrp_id
                                AND pmp_regroupement.statut = 10)
                ORDER BY pmp_commande.cmd_dt, pmp_commande.id DESC ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getCommandesOrphelinesVol(&$co_pmp, $type)
{
	$query = "  SELECT SUM(pmp_commande.cmd_qte) AS vol
                FROM pmp_commande, pmp_utilisateur, jjj_users
                WHERE pmp_commande.user_id = pmp_utilisateur.user_id
                AND pmp_utilisateur.user_id = jjj_users.id
                AND pmp_commande.groupe_cmd = '0'
				AND pmp_commande.cmd_typefuel = '$type'
                AND (pmp_commande.cmd_status = 10 or pmp_commande.cmd_status = 12)
                 AND not exists (select pmp_regroupement.id from pmp_regroupement, pmp_regrp_zone, pmp_zone_cp
                                WHERE pmp_utilisateur.code_postal_id = pmp_zone_cp.code_postal_id
                                AND pmp_zone_cp.actif = '1'
                                AND pmp_regrp_zone.zone_id = pmp_zone_cp.zone_id
                                AND pmp_regroupement.id = pmp_regrp_zone.regrp_id
                                AND pmp_regroupement.statut = 10)
                ORDER BY pmp_commande.cmd_dt, pmp_commande.id DESC ";
	$res = my_query($co_pmp, $query);
	$sum = mysqli_fetch_array($res);
	return $sum;
}

function getGroupementPossible(&$co_pmp)
{
	$query = "  SELECT DISTINCT pmp_commande.id, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.name, pmp_commande.cmd_status, pmp_commande.cmd_qte,
                  pmp_utilisateur.prenom,  pmp_utilisateur.user_id, pmp_utilisateur.code_postal_id, pmp_commande.cmd_dt, pmp_regroupement.id AS id_grp, pmp_commande.cmd_typefuel, pmp_regroupement.libelle, COUNT(pmp_utilisateur.user_id) as nb
                FROM pmp_commande, pmp_utilisateur, jjj_users, pmp_zone_cp, pmp_regrp_zone, pmp_regroupement
                WHERE pmp_commande.user_id = pmp_utilisateur.user_id
                AND pmp_utilisateur.user_id = jjj_users.id
                AND pmp_utilisateur.code_postal_id = pmp_zone_cp.code_postal_id
                AND pmp_zone_cp.actif = '1'
                AND pmp_regrp_zone.zone_id = pmp_zone_cp.zone_id
                AND pmp_regroupement.id = pmp_regrp_zone.regrp_id
                AND pmp_regroupement.statut = 10
                AND pmp_commande.groupe_cmd = '0'
                AND (pmp_commande.cmd_status = 10 or pmp_commande.cmd_status = 12)
				AND not exists (select    1
				                        from    pmp_zone_cp, pmp_regrp_zone
				                        where    pmp_regroupement.id = pmp_regrp_zone.regrp_id
				                        AND    pmp_regrp_zone.zone_id = pmp_zone_cp.zone_id
				                        AND    pmp_regrp_zone.in_out = 0
				                        AND pmp_zone_cp.actif = '1'
				                        AND pmp_utilisateur.code_postal_id = pmp_zone_cp.code_postal_id)
				GROUP BY pmp_commande.id
				HAVING COUNT(pmp_utilisateur.user_id) = 1
                ORDER BY pmp_commande.cmd_dt, pmp_commande.id DESC";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getPlusieursGroupementsPossibles(&$co_pmp)
{
	$query = "  SELECT DISTINCT pmp_commande.id, pmp_utilisateur.code_postal, pmp_utilisateur.ville, jjj_users.name, pmp_commande.cmd_status, pmp_commande.cmd_qte,
                  pmp_utilisateur.prenom,  pmp_utilisateur.user_id, pmp_utilisateur.code_postal_id, pmp_commande.cmd_dt, pmp_regroupement.id AS id_grp, pmp_commande.cmd_typefuel, pmp_regroupement.libelle, COUNT(pmp_utilisateur.user_id)
                FROM pmp_commande, pmp_utilisateur, jjj_users, pmp_zone_cp, pmp_regrp_zone, pmp_regroupement
                WHERE pmp_commande.user_id = pmp_utilisateur.user_id
                AND pmp_utilisateur.user_id = jjj_users.id
                AND pmp_utilisateur.code_postal_id = pmp_zone_cp.code_postal_id
                AND pmp_zone_cp.actif = '1'
                AND pmp_regrp_zone.zone_id = pmp_zone_cp.zone_id
                AND pmp_regroupement.id = pmp_regrp_zone.regrp_id
                AND pmp_regroupement.statut = 10
                AND pmp_commande.groupe_cmd = '0'
                AND (pmp_commande.cmd_status = 10 or pmp_commande.cmd_status = 12)
				AND not exists (select    1
				                        from    pmp_zone_cp, pmp_regrp_zone
				                        where    pmp_regroupement.id = pmp_regrp_zone.regrp_id
				                        AND    pmp_regrp_zone.zone_id = pmp_zone_cp.zone_id
				                        AND    pmp_regrp_zone.in_out = 0
				                        AND pmp_zone_cp.actif = '1'
				                        AND pmp_utilisateur.code_postal_id = pmp_zone_cp.code_postal_id)
				GROUP BY pmp_commande.id
				HAVING COUNT(pmp_utilisateur.user_id) > 1
                ORDER BY pmp_commande.cmd_dt, pmp_commande.id DESC";
	$res = my_query($co_pmp, $query);
	return $res;
}


function getGroupementCree(&$co_pmp, $cp_id)
{
	$query = "  SELECT pmp_regroupement.id, pmp_regroupement.libelle
				FROM pmp_regroupement, pmp_regrp_zone, pmp_zone_cp
				WHERE pmp_regroupement.id = pmp_regrp_zone.regrp_id
				AND pmp_regrp_zone.zone_id = pmp_zone_cp.zone_id
				AND pmp_regroupement.statut = '10'
				AND pmp_zone_cp.actif = '1'
				AND pmp_zone_cp.code_postal_id = '$cp_id'
				AND not exists (select    1
				                        from    pmp_zone_cp, pmp_regrp_zone, pmp_utilisateur
				                        where    pmp_regroupement.id = pmp_regrp_zone.regrp_id
				                        AND    pmp_regrp_zone.zone_id = pmp_zone_cp.zone_id
				                        AND    pmp_regrp_zone.in_out = 0
				                        AND pmp_zone_cp.actif = '1'
				                        AND pmp_utilisateur.code_postal_id = pmp_zone_cp.code_postal_id)
				GROUP BY pmp_regroupement.id";
	$res = my_query($co_pmp, $query);
	return $res;
}

if(!empty($_POST["affecter_cmde"]))
{
	if (!empty($_POST["cmd_id"]) && !empty($_POST["id_grp"]))
	{
		$cmd_id = $_POST["cmd_id"];
		$id_grp = $_POST["id_grp"];
		$cmd_qte = $_POST["cmd_qte"];

		$plages = ChargePlagesPrix($co_pmp, $id_grp, $cmd_qte);
		if($plages['prix_ord'] > 0 && $plages['prix_ord'] != NULL || $plages['prix_sup'] > 0 && $plages['prix_sup'] != NULL)
		{
			$query = "  UPDATE pmp_commande
						SET groupe_cmd = '$id_grp', cmd_status = '17', cmd_prix_ord='" . $plages['prix_ord'] . "', cmd_prix_sup='" . $plages['prix_sup'] . "'
						WHERE id = '$cmd_id' ";
			$res = my_query($co_pmp, $query);

			if($res)
			{
				$prixLitre = number_format($plages['prix_ord'] / 1000,3,","," ");
				TraceHisto($co_pmp, $cmd_id, "Prix litre Ord",  $prixLitre);
				$prixLitre = number_format($plages['prix_sup'] / 1000,3,","," ");
				TraceHisto($co_pmp, $cmd_id, "Prix litre Sup",  $prixLitre);

				TraceHisto($co_pmp, $cmd_id, 'Statut', "17 Prix proposé - Commande affectée au groupement " . $id_grp);

				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "La commandes a bien été affectée au groupement au statut 17.";
			}
		}
		else
		{
			$query = "  UPDATE pmp_commande
						SET groupe_cmd = '$id_grp', cmd_status = '15'
						WHERE id = '$cmd_id' ";
			$res = my_query($co_pmp, $query);

			if($res)
			{
				TraceHisto($co_pmp, $cmd_id, 'Statut', "15 Groupée - Commande affecter au groupement " . $id_grp);

				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "La commande a bien été affectée au groupement.";
			}
			else
			{
				$message_type = "no";
				$message_icone = "fa-times";
				$message_titre = "Erreur";
				$message = "Une erreur s'est produite";
			}
		}
	}
}

if(!empty($_POST["affecter_auto"]))
{
	$res_cmd = getNouvellesCommandes($co_pmp);
	while ($cmd = mysqli_fetch_array($res_cmd))
	{
		$res_grp = getGroupementCree($co_pmp, $cmd["code_postal_id"]);
		$num_grp = mysqli_num_rows($res_grp);
		if($num_grp == 1)
		{
			$grp = mysqli_fetch_array($res_grp);
			$cmd_id = $cmd["id"];
			$id_grp = $grp["id"];

			$plages = ChargePlagesPrix($co_pmp, $id_grp, $cmd["cmd_qte"]);

			if($plages['prix_ord'] > 0 && $plages['prix_ord'] != NULL || $plages['prix_sup'] > 0 && $plages['prix_sup'] != NULL)
			{
				$query = "  UPDATE pmp_commande
							SET groupe_cmd = '$id_grp', cmd_status = '17', cmd_prix_ord='" . $plages['prix_ord'] . "', cmd_prix_sup='" . $plages['prix_sup'] . "'
							WHERE id = '$cmd_id' ";
				$res = my_query($co_pmp, $query);

				if($res)
				{
					$prixLitre = number_format($plages['prix_ord'] / 1000,3,","," ");
					TraceHisto($co_pmp, $cmd_id, "Prix litre Ord",  $prixLitre);
					$prixLitre = number_format($plages['prix_sup'] / 1000,3,","," ");
					TraceHisto($co_pmp, $cmd_id, "Prix litre Sup",  $prixLitre);

					TraceHisto($co_pmp, $cmd_id, 'Statut', "17 Prix proposé - Commande affectée au groupement " . $id_grp);

					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les commandes ont bien été affectées au groupement.";
				}
				else
				{
					$message_type = "no";
					$message_icone = "fa-times";
					$message_titre = "Erreur";
					$message = "Une erreur s'est produite";
				}
			}
			else
			{
				$query = "  UPDATE pmp_commande
							SET groupe_cmd = '$id_grp', cmd_status = '15'
							WHERE id = '$cmd_id' ";
				$res = my_query($co_pmp, $query);

				if($res)
				{
					TraceHisto($co_pmp, $cmd_id, 'Statut', "15 Groupée - Commande affectée au groupement " . $id_grp);

					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les commandes ont bien été affectées au groupement.";
				}
				else
				{
					$message_type = "no";
					$message_icone = "fa-times";
					$message_titre = "Erreur";
					$message = "Une erreur s'est produite";
				}
			}
		}
		elseif($num_grp == 0)
		{
			$message_type = "no";
			$message_icone = "fa-times";
			$message_titre = "Erreur";
			$message = "Aucune commande à affecter";
		}
	}
}


if(!empty($_POST["valider_commande_groupement"]))
{
	if (!empty($_POST["cmd_id"]))
	{
		$cmd_id = $_POST["cmd_id"];

		for ($i=0; $i < $_POST["nb_cmd"]; $i++)
		{
			$tmp = 'select_grp_' . $i;
			$id = $cmd_id[$i];
			$grp = $_POST[$tmp];

			if($grp > 0)
			{
				$query = "  UPDATE pmp_commande
							SET groupe_cmd = '$grp', cmd_status = '15'
							WHERE id = '$id' ";
				$res = my_query($co_pmp, $query);

				if($res)
				{
					TraceHisto($co_pmp, $id, 'Statut', "15 Groupée - Commande affecter au groupement " . $grp);

					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les commandes ont bien été affectées au groupement sélectionné";
				}
				else
				{
					$message_type = "no";
					$message_icone = "fa-times";
					$message_titre = "Erreur";
					$message = "Une erreur s'est produite";
				}
			}
		}
	}
}

?>
