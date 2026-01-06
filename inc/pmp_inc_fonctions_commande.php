<?php
function CalculeStatus($commande)
{
	$status_calcule=$commande['cmd_status'];
	if( ($commande['cmd_status'] == 12) || ($commande['cmd_status'] == 13) || ($commande['cmd_status'] == 15) ) // On passe les cde groupée a prix proposé si un prix est présent
	{
		if( ($commande['cmd_typefuel'] == 1) && ($commande['cmd_prix_ord'] != "") && ($commande['cmd_prix_ord'] != 0) )
			$status_calcule= 17;
		if( ($commande['cmd_typefuel'] == 2) && ($commande['cmd_prix_sup'] != "") && ($commande['cmd_prix_sup'] != 0) )
			$status_calcule= 17;
	}
	if($commande['cmd_status'] == 17) // On passe les cde prix proposé à groupé si un prix n'est pas présent
	{
		if( ($commande['cmd_typefuel'] == 1) && ($commande['cmd_prix_ord'] == "") )
			$status_calcule= 15;
		if( ($commande['cmd_typefuel'] == 2) && ($commande['cmd_prix_sup'] == "") )
			$status_calcule= 15;
	}
	return $status_calcule;
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

function VerifierPlagesPrix(&$co_pmp, $regrp_id, $quantite)
{
	$query = "	SELECT volume, prix_ord, prix_sup
				FROM pmp_regrp_plages
				WHERE regrp_id = '" . $regrp_id . "'
				AND volume >= '" . mysqli_real_escape_string($co_pmp, $quantite) . "'
				ORDER BY volume DESC
				LIMIT 1 ";
	$res = my_query($co_pmp, $query);
	$plages = mysqli_fetch_array($res);
	return $plages;
}

function ChargeGroupementCp(&$co_pmp, $cpid)
{
	$query = "  SELECT pmp_regroupement.id, pmp_regroupement.libelle
                FROM pmp_regroupement, pmp_regrp_zone, pmp_zone_cp
                WHERE pmp_regroupement.id = pmp_regrp_zone.regrp_id
                AND pmp_regrp_zone.zone_id = pmp_zone_cp.zone_id
                AND pmp_zone_cp.code_postal_id = '$cpid'
                AND pmp_regroupement.statut = '10'
                AND pmp_regrp_zone.in_out = 1
                AND pmp_zone_cp.actif = '1'
                AND not exists (select    1
                    from pmp_zone_cp, pmp_regrp_zone
                    where pmp_regroupement.id = pmp_regrp_zone.regrp_id
                    AND pmp_regrp_zone.zone_id = pmp_zone_cp.zone_id
                    AND pmp_regrp_zone.in_out = 0
                    AND pmp_zone_cp.actif = '1'
                    AND pmp_zone_cp.code_postal_id = '$cpid')
				GROUP BY pmp_regroupement.id	";
	$res = my_query($co_pmp, $query);
	return $res;
}

function ChargeGroupement(&$co_pmp, $commande)
{
	if($commande['groupe_cmd'] !=0)
	{
		$groupe_cmd = mysqli_real_escape_string($co_pmp, $commande['groupe_cmd']);
		$query = "	SELECT id, planning, id_four
					FROM pmp_regroupement
					WHERE id= '" . $groupe_cmd . "'";
		$res = my_query($co_pmp, $query);
		return mysqli_fetch_array($res);
	}
	return "";
}

function checkSMSEnvoye($co_pmp, $id)
{
	$query = "  SELECT id
	 			FROM pmp_sms
				WHERE cmd_id = '" . mysqli_real_escape_string($co_pmp, $id) . "' ";
	$res = my_query($co_pmp, $query);
	return mysqli_fetch_array($res);
}

function chargeAnciennesCommandes($co_pmp, $user_id)
{
	$query = "  SELECT id, cmd_dt, cmd_typefuel, cmd_qte, cmd_qtelivre, cmd_prix_sup, cmd_prix_ord
				FROM pmp_commande
				WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id) . "'
				AND cmd_status = 40 ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function AfficheInfosGroupement(&$co_pmp, $regroupement, $status_calcule)
{
	if( ($regroupement != false) && ($status_calcule != 54) )
	{
		$planning = $regroupement['planning'];

		print '<div class="infosGroupement">';
		print '		<legend style="color:#f8f6f4">Information sur le groupement</legend>';
		print '     <div class="ligne-center orange"></div>';

		// On affiche les info du gpe
		if($regroupement['planning'] !="")
		{
			print '<p style="color:#f8f6f4">' . $planning . '</p>';
		}

		//* On affiche les prix si il y en a
		if($regroupement != false)
		{
			// Si le gpe est au status "prix" // TODO
			if($status_calcule == 17)
			{
				$id_grp = mysqli_real_escape_string($co_pmp, $regroupement['id']);
				$query = "SELECT volume, prix_ord, prix_sup FROM pmp_regrp_plages WHERE regrp_id='$id_grp' ORDER BY volume";
				$res= my_query($co_pmp, $query);
				// Si le gpe à au moins un prix
				if($res!=null)
				{
					// On affiche la grille de prix
					print '<div class="tarifGroupement">';
					print '	<table class="radius5 table_gpe_prix">';
					print '		<tbody>';
					print '		<tr class="ligne_entete">';
					print '			<th class="colComm">Volume</th>';
					print '			<th class="colOrd">Ordinaire</th>';
					print '			<th class="colSup">Supérieur</th>';
					print '		</tr>';
					// Pour toutes les plages
					while($plages = mysqli_fetch_array($res))
					{
						$prix_ord = mysqli_real_escape_string($co_pmp, $plages['prix_ord']);
						$prix_sup = mysqli_real_escape_string($co_pmp, $plages['prix_sup']);
						print '<tr class="ligne_1000">';
						print '<td>' . mysqli_real_escape_string($co_pmp, $plages['volume']) . '</td>';
						print '<td class="td_ord">' . number_format($prix_ord/1000,3,","," ") . '€</td>';
						print '<td class="td_sup">' . number_format($prix_sup/1000,3,","," ") . '€</td>';
						print '</tr>';
					}
					print '		</tbody>';
					print '	</table>';
					print '</div>'; // Fin div tarifGroupement
				}
			}
		}
		print '</div>';	// Fin div infosGroupement
	}
}

function AfficheInfosCommande($commande)
{
    $prixLitre = 0;
    $prixTotal = 0;
    $textPrixCommande = '';
    $titrePrixCommande = '';
    $PrixCommande = '';

    if ($commande['cmd_qte'] == 0) {
        $qte = 1000;
    } else {
        $qte = $commande['cmd_qte'];
    }

    if (($commande['cmd_typefuel'] == '1') && ($commande['cmd_prix_ord'] != "") && ($commande['cmd_prix_ord'] != 0)) {
        $prixLitre = number_format($commande['cmd_prix_ord'] / 1000, 3, ",", " ");
        $prixTotal = number_format(($qte * $commande['cmd_prix_ord']) / 1000, 2, ",", " ");
    }
    if (($commande['cmd_typefuel'] == '2') && ($commande['cmd_prix_sup'] != "") && ($commande['cmd_prix_sup'] != 0)) {
        $prixLitre = number_format($commande['cmd_prix_sup'] / 1000, 3, ",", " ");
        $prixTotal = number_format(($qte * $commande['cmd_prix_sup']) / 1000, 2, ",", " ");
    }

    if ($prixTotal != 0) {
        $titrePrixCommande = 'Total de votre commande';
        $PrixCommande = $prixTotal . '€ TTC';
        $textPrixCommande = '(<strong>' . $prixLitre . '€</strong> le litre)<br>livraison incluse';
    }

    if (!empty($prixLitre)) {
        echo '<div class="proposee">';
        echo '<p class="titre-bloc">' . $titrePrixCommande . '</p>';
        echo '<hr class="separe">';
        echo '<div class="prix-proposee">' . $PrixCommande . '</div>';
        echo '<p>' . $textPrixCommande . '</p>';
        echo '</div>';
    } else {
        echo '<div class="proposee">';
        echo '<p>' . $textPrixCommande . '</p>';
        echo '</div>';
    }
}


function  ModalitesFournisseur($co_pmp, $id_four)
{
	$query = "  SELECT modalite
				FROM pmp_fournisseur
				WHERE id = '" . mysqli_real_escape_string($co_pmp, $id_four) . "' ";
	$res = my_query($co_pmp, $query);
	$four = mysqli_fetch_array($res);
	return $four;
}

function resAvisCmd(&$co_pmp, $cmd_id)
{
	$query = "  SELECT message, note
				FROM pmp_livre_or
				WHERE commande_id = '" . mysqli_real_escape_string($co_pmp, $cmd_id) . "' ";
	$res = my_query($co_pmp, $query);
	$avis = mysqli_fetch_array($res);
	return $avis;
}

function ChargeRaisonsRefu()
{
	$res = array(	"",
					"J'ai déjà effectué une commande ailleurs",
					"J'ai trouvé un concurrent moins cher",
					"Je suis absent aux dates de livraison",
					"Ma commande est urgente",
					"Le mode de paiement ne me convient pas",
					"Je souhaite commander plus tard",
					"Autre"
					);
	return $res;
}

function TraceHisto(&$co_pmp, $cmd_id, $his_action, $status, $quantite, $qualite, $commentaire)
{
	// On a soit un status, soit une quantite soit une qualite

	$cmd_id = mysqli_real_escape_string($co_pmp, $cmd_id);
	$his_action = mysqli_real_escape_string($co_pmp, $his_action);
	$status = mysqli_real_escape_string($co_pmp, $status);
	$quantite = mysqli_real_escape_string($co_pmp, $quantite);
	$qualite = mysqli_real_escape_string($co_pmp, $qualite);
	$commentaire = mysqli_real_escape_string($co_pmp, $commentaire);

	$commentaire_final = "";

	if(strlen($status)>0)
	{
		if($status == "10")
			$commentaire_final = $status . " - Utilisateur";
		else if($status == "12")
			$commentaire_final = $status . " - Attachée";
		else if($status == "13")
			$commentaire_final = $status . " - Proposé";
		else if($status == "15")
			$commentaire_final = $status . " - Groupée";
		else if($status == "17")
			$commentaire_final = $status . " - Prix proposé";
		else if($status == "20")
			$commentaire_final = $status . " - Prix validé";
		else if($status == "25")
			$commentaire_final = $status . " - Livrable";
		else if($status == "30")
			$commentaire_final = $status . " - Livrée";
		else if($status == "40")
			$commentaire_final = $status . " - Terminée";
		else if($status == "50")
			$commentaire_final = $status . " - Annulée";
		else if($status == "55")
			$commentaire_final = $status . " - Annulée prix";
		else
			$commentaire_final = $status . " - ?";
	}

	if(strlen($quantite)>0)
	{
		$commentaire_final = $quantite . "L";
	}

	if(strlen($qualite)>0)
	{
		if($qualite == "1")
			$commentaire_final = "Ordinaire";
		else if($qualite == "2")
			$commentaire_final = "Supérieur";
		else
			$commentaire_final = "Ordinaire";
	}

	if(strlen($commentaire)>0)
	{
		if(strlen($commentaire_final)>0)
			$commentaire_final .= " - " . $commentaire;
		else
			$commentaire_final .= $commentaire;
	}

	$milli = microtime();
	$query = "	INSERT INTO pmp_commande_histo (cmd_id, his_date, his_date_milli, his_intervenant, his_action, his_valeur )
				VALUES ('" . $cmd_id . "',SYSDATE(),'" . substr($milli,2,5) . "','site','" . $his_action . "','" . $commentaire_final . "')";
	$res = my_query($co_pmp, $query);
}

function AnnulerSMS(&$co_pmp, $utilisateur, $commande)
{
	$cmd_id = mysqli_real_escape_string($co_pmp, $commande['id']);
	$query = "UPDATE pmp_sms SET etat = '2' WHERE etat = '0' AND cmd_id = '$cmd_id'";
	$res = my_query($co_pmp, $query);
	if(isset($utilisateur["tel_port"]))
	{
		$tel = $utilisateur["tel_port"];
	}
	else
	{
		$tel = $utilisateur["tel_fixe"];
	}
	$tel = mysqli_real_escape_string($co_pmp, $tel);
	TraceHisto($co_pmp,$cmd_id, "SMS Annulé", "", "", "", $tel);
}

function TraceHistoRefus(&$co_pmp, $cmd_id, $his_action, $status)
{
	// On a soit un status, soit une quantite soit une qualite

	$cmd_id = mysqli_real_escape_string($co_pmp, $cmd_id);
	$his_action = mysqli_real_escape_string($co_pmp, $his_action);
	$status = mysqli_real_escape_string($co_pmp, $status);

	$milli = microtime();
	$query = "	INSERT INTO pmp_commande_histo (cmd_id, his_date, his_date_milli, his_intervenant, his_action, his_valeur )
				VALUES ('" . $cmd_id . "',SYSDATE(),'" . substr($milli,2,5) . "','site','" . $his_action . "','" . $status . "')";
	$res = my_query($co_pmp, $query);
}

function InsereLivreOr(&$co_pmp, $user_id, $message, $signature, $note, $commande_id)
{
	if(strlen($message)>0)
	{
		$query = "	INSERT INTO pmp_livre_or (user_id, message, signature, date, note, commande_id)
					VALUES ('$user_id', '" . mysqli_real_escape_string($co_pmp, $message) . "', '" . mysqli_real_escape_string($co_pmp, $signature) . "', now(), '" . mysqli_real_escape_string($co_pmp, $note) . "', '" . mysqli_real_escape_string($co_pmp, $commande_id) . "')";
	}
	else
	{
		if($note == 5)
		{
			$query = "	INSERT INTO pmp_livre_or (user_id, signature, valide, date, note, commande_id)
						VALUES ('$user_id', '" . mysqli_real_escape_string($co_pmp, $signature) . "', 1, now(), '" . mysqli_real_escape_string($co_pmp, $note) . "', '" . mysqli_real_escape_string($co_pmp, $commande_id) . "')";
		}
		else
		{
			$query = "	INSERT INTO pmp_livre_or (user_id, signature, date, note, commande_id)
						VALUES ('$user_id', '" . mysqli_real_escape_string($co_pmp, $signature) . "', now(), '" . mysqli_real_escape_string($co_pmp, $note) . "', '" . mysqli_real_escape_string($co_pmp, $commande_id) . "')";
		}

	}
	$res = my_query($co_pmp, $query);
}

//Verifier si commande à déjà été dans le groupement avec offre de prix refusé
function getCommandeGroupementRefus(&$co_pmp, $id, $id_grp)
{
	$query = "  SELECT *
				FROM pmp_commande
				WHERE user_id = '$id'
				AND groupe_cmd = '$id_grp'
				AND cmd_status = '55' ";
	$res = my_query($co_pmp, $query);
	return $res;
}

//Créer une intention de commande
if(!$recharge)
{
	if(!empty($_POST["creer_commande"]))
	{
		$id = $_SESSION["id"];
		$utilisateur = ChargeCompteFioul($co_pmp, $id);
		$jjj_users = ChargeCompteJoomla($co_pmp, $id);
		$fuel = mysqli_real_escape_string($co_pmp, $_POST["qualite"]);
		$qte = mysqli_real_escape_string($co_pmp, $_POST["quantite"]);

		if(empty($qte)) // Si la quantité est vide, on affiche le message d'erreur
		{
			$message_info = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message = "La quantité doit être renseignée";
			$valid = false;
		}
		else
		{
			if($qte < 500 || $qte > 99999) // Si la quantité est remplis mais pas bonne saisie, on affiche le message d'erreur
			{
				$message_info = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message = "La quantité doit être comprise entre 500 et 99999 litres";
				$valid = false;
			}
		}
		// Si la qualité est vide, on affiche le message d'erreur
		if(empty($fuel))
		{
			$message_info = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message = "La qualité doit être renseignée";
			$valid = false;
		}

		if (!isset($valid)) // Si aucune erreur on insert l'intention de commande
		{

			if($fuel == 0) { $fuel = "1"; }
			$qte = htmlentities($_POST["quantite"]);

			$query = "  INSERT INTO pmp_commande (user_id, cmd_dt, cmd_typefuel, cmd_qte, cmd_status)
						VALUES ('$id', NOW(), '$fuel', '$qte', '10') ";
			$res = my_query($co_pmp, $query);
			if(!$res)
			{
				return false;
			}
			else
			{
				if ($fuel == "1"){ $fuel = "ordinaire"; }
				if ($fuel == "2"){ $fuel = "supérieur"; }

				if($qte >= 4000)
				{

					EnvoyerMail("Groupement de " . $jjj_users['email'] . " (" . $qte . "L " . $fuel . ")", "Rappelons rapidement pour gérer ce groupement individuellement");
				}

				$cmd_id = mysqli_insert_id($co_pmp);
				TraceHisto($co_pmp, $cmd_id, "Statut", "10", "", "", "");
				TraceHisto($co_pmp, $cmd_id, "Quantite", "", $qte, "", "");
				TraceHisto($co_pmp, $cmd_id, "Type Fuel", "", "", $fuel, "");

				$cpid = $utilisateur["code_postal_id"];
				$res_grp = ChargeGroupementCp($co_pmp, $cpid);
				$num_grpts = mysqli_num_rows($res_grp);
				if($num_grpts == 1)
				{
					$res_grp = mysqli_fetch_array($res_grp);
					$id_grp = $res_grp["id"];

					$res_refus = getCommandeGroupementRefus($co_pmp, $id, $id_grp);
					$num_refus = mysqli_num_rows($res_refus);
					if($num_refus > 0)
					{
						if ($fuel == "1"){ $fuel = "ordinaire"; }
						if ($fuel == "2"){ $fuel = "supérieur"; }

						$message_info = "Succès";
						$message_type = "success";
						$message_icone = "fa-check";
						$message = "Intention de commande correctement créée : " . $qte . "L de fioul " . $fuel;
					}
					else
					{
						$plages = ChargePlagesPrix($co_pmp, $id_grp, $qte);

						if($plages['prix_ord'] > 0 && $plages['prix_ord'] != NULL || $plages['prix_sup'] > 0 && $plages['prix_sup'] != NULL)
						{
							$query = "  UPDATE pmp_commande
										SET groupe_cmd = '$id_grp', cmd_status = '17', cmd_prix_ord='" . $plages['prix_ord'] . "', cmd_prix_sup='" . $plages['prix_sup'] . "'
										WHERE id = '$cmd_id' ";
							$res = my_query($co_pmp, $query);
						
							if($res)
							{
								$prixLitre = number_format($plages['prix_ord'] / 1000,3,","," ");
								TraceHisto($co_pmp, $cmd_id, "Prix litre Ord", "", "", "", $prixLitre);
								$prixLitre = number_format($plages['prix_sup'] / 1000,3,","," ");
								TraceHisto($co_pmp, $cmd_id, "Prix litre Sup", "", "", "", $prixLitre);
							
								if ($fuel == "1"){ $fuel = "ordinaire"; }
								if ($fuel == "2"){ $fuel = "supérieur"; }
							
								TraceHisto($co_pmp, $cmd_id, "Statut", "", "", "", "17 Prix proposé - Commande affectée au groupement ". $id_grp);
								$message_info = "Succès";
								$message_type = "success";
								$message_icone = "fa-check";
								$message = "Votre commande a été groupée : " . $qte . "L de fioul " . $fuel;
							}
						}

						else
						{
							$update = " UPDATE pmp_commande
										SET groupe_cmd = '$id_grp', cmd_status = '15'
										WHERE id = '$cmd_id' ";
							$res = my_query($co_pmp, $update);

							if($res)
							{
								if ($fuel == "1"){ $fuel = "ordinaire"; }
								if ($fuel == "2"){ $fuel = "supérieur"; }

								TraceHisto($co_pmp, $cmd_id, "Statut", "", "", "", "15 Groupée - Commande affectée au groupement ". $id_grp);
								$message_info = "Succès";
								$message_type = "success";
								$message_icone = "fa-check";
								$message = "Votre commande a été groupée : " . $qte . "L de fioul " . $fuel;
							}
						}
					}
				}
				else
				{
					if ($fuel == "1"){ $fuel = "ordinaire"; }
					if ($fuel == "2"){ $fuel = "supérieur"; }

					$message_info = "Succès";
					$message_type = "success";
					$message_icone = "fa-check";
					$message = "Intention de commande correctement créée : " . $qte . "L de fioul " . $fuel;
				}
			}
		}
	}
}
// Récupère la commande en cours de l'utilisateur
function getCommandeUtilisateur(&$co_pmp, $id)
{
	$query = "	SELECT id, cmd_status, groupe_cmd, cmd_typefuel, cmd_qte, cmd_prix_ord, cmd_prix_sup, cmd_comment, user_id
			FROM pmp_commande
			WHERE user_id= '" . mysqli_real_escape_string($co_pmp, $id) . "'
			AND cmd_status < 40
			ORDER BY id DESC";
	$res = my_query($co_pmp, $query);
	$cmd = mysqli_fetch_array($res);
	return $cmd;
}

// Récupère la commande annulée prix pour réactiver
function getCommandeUtilisateurAnnuleePrix(&$co_pmp, $id)
{
	$query = "	SELECT id, cmd_status, groupe_cmd, cmd_typefuel, cmd_qte, cmd_prix_ord, cmd_prix_sup, cmd_comment, user_id
			FROM pmp_commande
			WHERE user_id= '" . mysqli_real_escape_string($co_pmp, $id) . "'
			AND cmd_status = 55
			ORDER BY id DESC ";
	$res = my_query($co_pmp, $query);
	$cmd = mysqli_fetch_array($res);
	return $cmd;
}

function ChargeGroupementCree(&$co_pmp, $commande)
{
	if($commande['groupe_cmd'] !=0)
	{
		$groupe_cmd = mysqli_real_escape_string($co_pmp, $commande['groupe_cmd']);
		$query = "	SELECT id, planning, statut
					FROM pmp_regroupement
					WHERE id = '" . $groupe_cmd . "'
					AND statut = '10' ";
		$res = my_query($co_pmp, $query);
		return mysqli_fetch_array($res);
	}
	return "";
}

// Si modifie la commande en cours

if(!$recharge)
{
	if(isset($_POST["qualite"]) || isset($_POST["quantite"]))
	{
		$id = mysqli_real_escape_string($co_pmp, $_SESSION["id"]);
		$fuel = mysqli_real_escape_string($co_pmp, $_POST["qualite"]);
		$qte = mysqli_real_escape_string($co_pmp, $_POST["quantite"]);
		$cmd = getCommandeUtilisateur($co_pmp, $id);

		if($fuel == 0) { $fuel = "1"; }

		if($cmd["cmd_status"] != 13)
		{
			if(empty($qte)) // Si la quantité est vide, on affiche le message d'erreur
			{
				$message_info = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message = "La quantité doit être renseignée";
				$valid = false;
			}
			else
			{
				if($qte < 500 || $qte > 99999) // Si la quantité est remplis mais pas bonne saisie, on affiche le message d'erreur
				{
					$message_info = "Erreur";
					$message_type = "no";
					$message_icone = "fa-times";
					$message = "La quantité doit être comprise entre 500 et 99999 litres";
					$valid = false;
				}
				else
				{

					if($cmd["groupe_cmd"] != 0)
					{
						if(!ChargePlagesPrix($co_pmp, $cmd["groupe_cmd"], $qte))
						{
							if($cmd["groupe_cmd"] == '9707')
							{
								$message_info = "Erreur";
								$message_type = "no";
								$message_icone = "fa-times";
								$message = "En raison des difficultés d'approvisionnement actuels des fournisseurs, la quantité maximum est limitée à 500 L";
								$valid = false;
							}
							else
							{
								$message_info = "Erreur";
								$message_type = "no";
								$message_icone = "fa-times";
								$message = "La quantité n'est pas suffisante pour participer à ce groupement. Veuillez modifier la quantité commandée.";
								$valid = false;
							}
						}
					}
				}
			}
			if(empty($fuel)) // Si la qualité est vide, on affiche le message d'erreur
			{
				$message_info = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message = "La qualité doit être renseignée";
				$valid = false;
			}

			if (!isset($valid)) // Sinon on update la commde
			{
				$interdit = array(">", "<",  ":", "*","\\", "/", "|", "?", "\"", "!", "'", "(", ")", "$", ".", ",", ";" );
				$qte = str_replace($interdit, "", $qte);
			
				if ($qte != $cmd["cmd_qte"])
				{
					TraceHisto($co_pmp,$cmd['id'], "Quantité", "", $qte, "", "");
				}
				if ($fuel != $cmd["cmd_typefuel"])
				{
					TraceHisto($co_pmp,$cmd['id'], "Type Fuel", "", "", $fuel, "");
				}
			
				if($cmd['cmd_prix_ord'] > 0 && $cmd['cmd_prix_ord'] != NULL)
				{
					if($cmd["cmd_status"] == 17 || $cmd["cmd_status"] == 15 || $cmd["cmd_status"] == 12 )
					{
						$plages = ChargePlagesPrix($co_pmp, $cmd["groupe_cmd"], $qte);
						if( ($cmd['cmd_prix_ord'] != $plages['prix_ord']) || ($cmd['cmd_prix_sup'] != $plages['prix_sup']) )
						{
							// On trace
							$prixLitre = number_format($plages['prix_ord'] / 1000,3,","," ");
							TraceHisto($co_pmp,$cmd['id'], "Prix litre Ord", "", "", "", $prixLitre);
							$prixLitre = number_format($plages['prix_sup'] / 1000,3,","," ");
							TraceHisto($co_pmp,$cmd['id'], "Prix litre Sup", "", "", "", $prixLitre);
						}
						$udapteCommande = "  UPDATE pmp_commande
											 SET cmd_typefuel = '$fuel', cmd_qte = '$qte',
											 cmd_prix_ord='" . $plages['prix_ord'] . "',
											 cmd_prix_sup='" . $plages['prix_sup'] . "'
											 WHERE user_id = '$id'
											 AND cmd_status < 40
											 AND id = '" . mysqli_real_escape_string($co_pmp, $cmd['id']) . "'";
						$res = my_query($co_pmp, $udapteCommande);
					
						if(!$res)
						{
							return false;
						}
						else
						{
							if ($fuel == "1"){ $fuel = "ordinaire"; }
							if ($fuel == "2"){ $fuel = "supérieur"; }
						
							if($qte >= 4000)
							{
								$utilisateur = ChargeCompteFioul($co_pmp, $id);
								$jjj_users = ChargeCompteJoomla($co_pmp, $id);
								EnvoyerMail("Groupement de " . $jjj_users['email'] . " (" . $qte . "L " . $fuel . ")", "Rappelons rapidement pour gérer ce groupement individuellement");
							}
						
							$message_info = "Succès";
							$message_type = "success";
							$message_icone = "fa-check";
							$message = "Commande correctement modifiée : " . $qte . "L de fioul " . $fuel;
						
						}
					}
				}
				else
				{
				
					$udapteCommande = "  UPDATE pmp_commande
										 SET cmd_typefuel = '$fuel', cmd_qte = '$qte'
										 WHERE user_id = '$id'
										 AND cmd_status < 40
										 AND id = '" . mysqli_real_escape_string($co_pmp, $cmd['id']) . "'";
					$res = my_query($co_pmp, $udapteCommande);
				
					if(!$res)
					{
						return false;
					}
					else
					{
						if ($fuel == "1"){ $fuel = "ordinaire"; }
						if ($fuel == "2"){ $fuel = "supérieur"; }
					
						if($qte >= 4000)
						{
							$utilisateur = ChargeCompteFioul($co_pmp, $id);
							$jjj_users = ChargeCompteJoomla($co_pmp, $id);
							EnvoyerMail("Groupement de " . $jjj_users['email'] . " (" . $qte . "L " . $fuel . ")", "Rappelons rapidement pour gérer ce groupement individuellement");
						}
					
						$message_info = "Succès";
						$message_type = "success";
						$message_icone = "fa-check";
						$message = "Commande correctement modifiée : " . $qte . "L de fioul " . $fuel;
					
					}
				}
			}

		}
	}
}


//Joindre commande groupée
if(!$recharge)
{
	if(!empty($_POST["joindre_commande"]))
	{
		$id = mysqli_real_escape_string($co_pmp, $_SESSION["id"]);
		$fuel = mysqli_real_escape_string($co_pmp, $_POST["qualite"]);
		$qte = (int)$_POST["quantite"];
		$cmd = getCommandeUtilisateur($co_pmp, $id);
		$plages = ChargePlagesPrix($co_pmp, $cmd["groupe_cmd"], $qte);
		$id_grp = $cmd["groupe_cmd"];

		if(empty($qte)) // Si la quantité est vide, on affiche le message d'erreur
		{
			$message_info = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message = "La quantité doit être renseignée";
			$valid = false;
		}
		else
		{
			if($qte < 500 || $qte > 99999) // Si la quantité est remplis mais pas bonne saisie, on affiche le message d'erreur
			{
				$message_info = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message = "La quantité doit être comprise entre 500 et 99999 litres";
				$valid = false;
			}
			else
			{
				if(isset($plages["id"]))
				{
					if(!ChargePlagesPrix($co_pmp, $cmd["groupe_cmd"], $qte))
					{
						if($cmd["groupe_cmd"] == '9707')
						{
							$message_info = "Erreur";
							$message_type = "no";
							$message_icone = "fa-times";
							$message = "En raison des difficultés d'approvisionnement actuels des fournisseurs, la quantité maximum est limitée à 500 L";
							$valid = false;
						}
						else
						{
							$message_info = "Erreur";
							$message_type = "no";
							$message_icone = "fa-times";
							$message = "La quantité n'est pas suffisante pour participer à ce groupement. Veuillez modifier la quantité commandée.";
							$valid = false;
						}
					}
				}
			}
		}

		if(empty($fuel)) // Si la qualité est vide, on affiche le message d'erreur
		{
			$message_info = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message = "La qualité doit être renseignée";
			$valid = false;
		}

		// if($cmd["cmd_status"] <= 25)
		// {
		// 	$message_info = "Erreur";
		// 	$message_type = "no";
		// 	$message_icone = "fa-times";
		// 	$message = "Votre commande est déjà dans un groupement";
		// 	$valid = false;
		// }

		if (!isset($valid)) // Sinon on update la commde
		{
			if ($qte != $cmd["cmd_qte"])
			{
				TraceHisto($co_pmp,$cmd['id'], "Quantité", "", $qte, "", "");
			}
			if ($fuel != $cmd["cmd_typefuel"])
			{
				TraceHisto($co_pmp, $cmd['id'], "Type Fuel", "", "", $fuel, "");
			}

			if($fuel == 0) { $fuel = "1"; }

			if($plages['prix_ord'] > 0 && $plages['prix_ord'] != NULL || $plages['prix_sup'] > 0 && $plages['prix_sup'] != NULL)
			{
				$udapteCommande = "  UPDATE pmp_commande
									 SET cmd_typefuel = '$fuel', cmd_qte = '$qte',
									 cmd_prix_ord='" . $plages['prix_ord'] . "',
									 cmd_prix_sup='" . $plages['prix_sup'] . "',
									 cmd_status = '17',
									 groupe_cmd = '$id_grp'
									 WHERE user_id = '$id'
									 AND cmd_status < 25
									 AND id = '" . mysqli_real_escape_string($co_pmp, $cmd['id']) . "'";
				$res = my_query($co_pmp, $udapteCommande);
			
				if($res)
				{
					$prixLitre = number_format($plages['prix_ord'] / 1000,3,","," ");
					TraceHisto($co_pmp,$cmd['id'], "Prix litre Ord", "", "", "", $prixLitre);
					$prixLitre = number_format($plages['prix_sup'] / 1000,3,","," ");
					TraceHisto($co_pmp,$cmd['id'], "Prix litre Sup", "", "", "", $prixLitre);
				
					if ($fuel == "1"){ $fuel = "ordinaire"; }
					if ($fuel == "2"){ $fuel = "supérieur"; }
				
					TraceHisto($co_pmp, $cmd['id'], "Statut", "", "", "", "17 Prix proposé - Commande affectée au groupement ". $id_grp);
					$message_info = "Succès";
					$message_type = "success";
					$message_icone = "fa-check";
					$message = "Votre commande a été groupée : " . $qte . "L de fioul " . $fuel;
				}
			}

			else
			{
				$udapteCommande = "  UPDATE pmp_commande
				 					 SET cmd_typefuel = '$fuel', cmd_qte = '$qte', cmd_status = '15', groupe_cmd = '$id_grp'
									 WHERE user_id = '$id'
									 AND cmd_status < 25
									 AND id = '" . mysqli_real_escape_string($co_pmp, $cmd['id']) . "'";
				$res = my_query($co_pmp, $udapteCommande);

				if($res)
				{
					if ($fuel == "1"){ $fuel = "ordinaire"; }
					if ($fuel == "2"){ $fuel = "supérieur"; }

					TraceHisto($co_pmp, $cmd['id'], "Statut", "", "", "", "15 Groupée - Commande affectée au groupement ". $id_grp);
					$message_info = "Succès";
					$message_type = "success";
					$message_icone = "fa-check";
					$message = "Votre commande a été groupée : " . $qte . "L de fioul " . $fuel;
				}
			}



			if($qte >= 4000)
			{
				$utilisateur = ChargeCompteFioul($co_pmp, $id);
				$jjj_users = ChargeCompteJoomla($co_pmp, $id);
				EnvoyerMail("Groupement de " . $jjj_users['email'] . " (" . $qte . "L " . $fuel . ")", "Rappelons rapidement pour gérer ce groupement individuellement");
			}
		}
	}
}


// Supprimer la commande en cours
if(!empty($_POST["supp_commande"]))
{
	$id = mysqli_real_escape_string($co_pmp, $_SESSION["id"]);
	$cmd = getCommandeUtilisateur($co_pmp, $id);

	$status_nouveau = 50;
	$message_info = "Succès";
	$message_type = "success";
	$message_icone = "fa-check";
	$message = "La commande a été supprimée, vous ne recevrez pas l'offre de prix par mail ";
	TraceHisto($co_pmp, $cmd['id'], "Statut", "50", "", "", "");

	$supprimerCommande = "  UPDATE pmp_commande
						 	SET cmd_status = '$status_nouveau'
						 	WHERE user_id = '$id'
						 	AND cmd_status < 40
							AND id = '" . mysqli_real_escape_string($co_pmp, $cmd['id']) . "'";
	$res = my_query($co_pmp, $supprimerCommande);

	if(!$res)
	{
		return false;
	}
}

//Si accepter tarfi
if(!$recharge)
{
	if(!empty($_POST["accepter_tarif"]) && isset($_SESSION["id"]))
	{
		// if (isset($_SESSION["id"]))
		// {
			$id = mysqli_real_escape_string($co_pmp, $_SESSION["id"]);
			$qte = mysqli_real_escape_string($co_pmp, $_POST["quantite"]);
			$cmd = getCommandeUtilisateur($co_pmp, $id);
			$utilisateur = ChargeCompteFioul($co_pmp, $id);
			$jjj_users = ChargeCompteJoomla($co_pmp, $id);
			$plages = ChargePlagesPrix($co_pmp, $cmd["groupe_cmd"], $qte);
			$commentaire = $_POST["cmd_comment"];
			$status_nouveau = 20;

			if($qte < 500 || $qte > 99999) // Si la quantité est remplis mais pas bonne saisie, on affiche le message d'erreur
			{
				$message_info = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message = "La quantité doit être comprise entre 500 et 99999 litres";
				$valid = false;
			}
			else
			{
				if(!ChargePlagesPrix($co_pmp, $cmd["groupe_cmd"], $qte))
				{
					if($cmd["groupe_cmd"] == '9707')
					{
						$message_info = "Erreur";
						$message_type = "no";
						$message_icone = "fa-times";
						$message = "En raison des difficultés d'approvisionnement actuels des fournisseurs, la quantité maximum est limitée à 500 L";
						$valid = false;
					}
					else
					{
						$message_info = "Erreur";
						$message_type = "no";
						$message_icone = "fa-times";
						$message = "La quantité n'est pas suffisante pour participer à ce groupement. Veuillez modifier la quantité commandée.";
						$valid = false;
					}
				}
			}

			if(strlen($commentaire)>0)
			{
				$commentaire = str_replace("’", "'", $commentaire);
				$commentaire = str_replace("‘", "'", $commentaire);
			}
			
			if (!isset($valid))
			{
				// On trace les éventuelle modification sur le prix
				if( ($cmd['cmd_prix_ord'] != $plages['prix_ord']) || ($cmd['cmd_prix_sup'] != $plages['prix_sup']) )
				{
					// On trace
					$prixLitre = number_format($plages['prix_ord'] / 1000,3,","," ");
					TraceHisto($co_pmp,$cmd['id'], "Prix litre Ord", "", "", "", $prixLitre);
				
					$prixLitre = number_format($plages['prix_sup'] / 1000,3,","," ");
					TraceHisto($co_pmp,$cmd['id'], "Prix litre Sup", "", "", "", $prixLitre);
				}
			
				if($cmd['cmd_status'] != $status_nouveau)
				{
					TraceHisto($co_pmp,$cmd['id'], "Statut", $status_nouveau, "", "", "");
				}
			
				if($cmd['cmd_comment'] != $commentaire)
				{
					TraceHisto($co_pmp,$cmd['id'], "Commentaire", "", "", "", $commentaire);
					EnvoyerMail("Commentaire commande de " . $jjj_users['email'],  $commentaire);
				}
			
				$query = "	UPDATE pmp_commande SET
							cmd_status = '20',
							cmd_prix_ord='" . $plages['prix_ord'] . "',
							cmd_prix_sup='" . $plages['prix_sup'] . "',
							cmd_qte = '" . mysqli_real_escape_string($co_pmp, $qte) . "',
							cmd_comment='" . mysqli_real_escape_string($co_pmp, $commentaire) . "'
							WHERE id = '" . $cmd['id'] . "'";
				$res = my_query($co_pmp, $query);
				if($res)
				{
					$sms = checkSMSEnvoye($co_pmp, $cmd['id']);
					if(isset($sms["id"]))
					{
						AnnulerSMS($co_pmp, $utilisateur, $cmd);
					}
					$message_info = "Succès";
					$message_type = "success";
					$message_icone = "fa-check";
					$message = "Le tarif a été accepté";
				}
				else
				{
					return false;
				}
			}

	}
}

if(!$recharge)
{
	if(isset($_SESSION["id"]))
	{
		if(!empty($_POST["valider_refus"]))
		{
			$id = mysqli_real_escape_string($co_pmp, $_SESSION["id"]);
			$cmd = getCommandeUtilisateur($co_pmp, $id);
			$utilisateur = ChargeCompteFioul($co_pmp, $id);
			$jjj_users = ChargeCompteJoomla($co_pmp, $id);
			$raison_refus = mysqli_real_escape_string($co_pmp, $_POST["raison_refus"]);
			// $raison_refus = addslashes($raison_refus);
			$tabRaisonsRefu = ChargeRaisonsRefu();

			if($_POST['raison_refus_selectionne'] == 0)
			{
				$message_info = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message = "Veuillez selectionnez une raison de refus de tarif";
			}
			elseif ($_POST['raison_refus_selectionne'] == 7 && empty($raison_refus))
			{
				$message_info = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message = "Veuillez nous précisez la raison du refus de tarif";
			}
			if($_POST['raison_refus_selectionne'] == 0)
			{
				$message_info = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message = "Veuillez sélectionner une raison de refus de tarif";
			}
			elseif ($_POST['raison_refus_selectionne'] == 7 && empty($raison_refus))
			{
				$message_info = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message = "Veuillez nous préciser la raison du refus de tarif";
			}
			elseif ($_POST['raison_refus_selectionne'] == 2 && empty($raison_refus))
			{
				$message_info = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message = "Veuillez nous indiquer en commentaire le nom du fournisseur moins cher";
			}
			elseif ($_POST['raison_refus_selectionne'] == 5 && empty($raison_refus))
			{
				$message_info = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message = "Veuillez nous indiquer en commentaire le mode de paiement qui vous aurait convenu";
			}
			else
			{
				if($_POST['raison_refus_selectionne'] == 1) { $raison = "J'ai trouvé plus cher ailleurs mais j'ai déjà commandé"; }
				if($_POST['raison_refus_selectionne'] == 2) { $raison = "J'ai trouvé moins cher (indiquez en commentaire le nom du fournisseur moins cher svp)"; }
				if($_POST['raison_refus_selectionne'] == 3) { $raison = "Je suis absent aux dates de livraison"; }
				if($_POST['raison_refus_selectionne'] == 4) { $raison = "Ma commande est urgente"; }
				if($_POST['raison_refus_selectionne'] == 5) { $raison = "Le mode de paiement ne me convient pas"; }
				if($_POST['raison_refus_selectionne'] == 6) { $raison = "Je préfère attendre"; }
				if($_POST['raison_refus_selectionne'] == 7) { $raison = $raison_refus; }

				$supprimerCommande = "  UPDATE pmp_commande
									 	SET cmd_status = '55', cmd_comment = '" . mysqli_real_escape_string($co_pmp,$raison) . "'
									 	WHERE user_id = '$id'
									 	AND cmd_status < 40
										AND id = '" . mysqli_real_escape_string($co_pmp, $cmd['id']) . "'";
				$res = my_query($co_pmp, $supprimerCommande);

				if(!$res)
				{
					return false;
				}
				else
				{
					if(isset($cmd["groupe_cmd"]) && $cmd["groupe_cmd"] != '0')
					{
						$message_info = "Succès";
						$message_type = "success";
						$message_icone = "fa-check";
						$message = "Le tarif a été refusé";
						TraceHisto($co_pmp, $cmd['id'], "Statut", "55", "", "", "");
						TraceHistoRefus($co_pmp, $cmd['id'], "Raison refus", $raison);
						TraceHistoRefus($co_pmp, $cmd['id'], "Commentaire refus", $raison_refus);
						EnvoyerMailRefusTarif($co_pmp, $cmd["groupe_cmd"], $jjj_users["email"], $tabRaisonsRefu[$_POST['raison_refus_selectionne']], $raison_refus);
					}


					$sms = checkSMSEnvoye($co_pmp, $cmd['id']);
					if(isset($sms["id"]))
					{
						AnnulerSMS($co_pmp, $utilisateur, $cmd);
					}
					header('Location: /ma_commande.php');
				}
			}
		}
	}
}

//Réactiver une commande
if(!$recharge)
{
	if(!empty($_POST["reactiver_commande"]))
	{
		$id = $_SESSION["id"];
		$id_cmd = $_POST["id_cmd_a"];
		$udapteCommande = "  UPDATE pmp_commande
							 SET cmd_status = 17
							 WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $id) . "'
							 AND cmd_status = 55
							 AND id = '" . mysqli_real_escape_string($co_pmp, $id_cmd) . "'";
		$res = my_query($co_pmp, $udapteCommande);

		if(!$res)
		{
			return false;
		}
		else
		{
			TraceHisto($co_pmp, $id_cmd, "Cmd reactivée - Statut", "17", "", "", "");
			$message_info = "Succès";
			$message_type = "success";
			$message_icone = "fa-check";
			$message = "Votre commande a bien été reactivée";
		}

	}
}

//
// function refuserTarif(&$co_pmp)
// {
// 	$id = $_SESSION["id"];
// 	$cmd = getCommandeUtilisateur($co_pmp, $id);
// 	if($cmd['cmd_status'] == 13 || $cmd['cmd_status'] == 10 || $cmd['cmd_status'] == 15)
// 	{
// 		$status_nouveau = 50;
// 		$message_info = "Succès";
// 		$message_type = "success";
// 		$message_icone = "fa-check";
// 		$message = "Le commande a été supprimé";
// 		TraceHisto($co_pmp, $cmd['id'], "Statut", "50", "", "", "");
// 	}
// 	else
// 	{
// 		$status_nouveau = 55;
// 		$message_info = "Succès";
// 		$message_type = "success";
// 		$message_icone = "fa-check";
// 		$message = "Le tarif a été refusé";
// 		TraceHisto($co_pmp, $cmd['id'], "Statut", "55", "", "", "");
//
// 	}
//
// 	$supprimerCommande = "  UPDATE pmp_commande
// 						 	SET cmd_status = '$status_nouveau'
// 						 	WHERE user_id = '$id'
// 						 	AND cmd_status < 40 ";
// 	$res = my_query($co_pmp, $supprimerCommande);
//
// 	if(!$res)
// 	{
// 		return false;
// 	}
// }
// if(!$recharge)
// {
// 	if(!empty($_POST["prochain_groupement"]))
// 	{
// 		$id = mysqli_real_escape_string($co_pmp, $_SESSION["id"]);
// 		$cmd = getCommandeUtilisateur($co_pmp, $id);
// 		$utilisateur = ChargeCompteFioul($co_pmp, $id);
//
// 		// AnnulerSMS($co_pmp, $utilisateur, $commande);
//
// 		// $query = "  DELETE FROM pmp_commande
// 		//             WHERE user_id = '$id'
// 		// 			AND cmd_status < 40 ";
//
// 		$query = "  UPDATE pmp_commande
// 					SET cmd_status = '10', groupe_cmd = '0',
// 					cmd_prix_ord = NULL, cmd_prix_sup = NULL, cmd_prixfm = NULL, cmd_prixfr = NULL
// 					WHERE id = '" . mysqli_real_escape_string($co_pmp, $cmd['id']) . "' ";
// 		$res = my_query($co_pmp, $query);
// 		if (!$res)
// 		{
// 			return false;
// 		}
// 		else
// 		{
// 			$sms = checkSMSEnvoye($co_pmp, $cmd['id']);
// 			if(isset($sms["id"]))
// 			{
// 				AnnulerSMS($co_pmp, $utilisateur, $cmd);
// 			}
// 			TraceHisto($co_pmp, $cmd['id'], "Statut", "10", "", "", "");
// 			TraceHisto($co_pmp,$cmd['id'], "Attendre prochain groupement", "", "", "", $cmd{'groupe_cmd'} . "- Supprimé");
// 			TraceHisto($co_pmp,$cmd['id'], "Prix litre Sup", "", "", "", "Supprimé");
// 			TraceHisto($co_pmp,$cmd['id'], "Prix litre Ord", "", "", "", "Supprimé");
// 			$message_info = "Succès";
// 			$message_type = "success";
// 			$message_icone = "fa-check";
// 			$message = "Commande correctement détachée du groupement";
// 		}
// 	}
// }

if(!$recharge)
{
	if(!empty($_POST["signale_livree"]))
	{
		$id = mysqli_real_escape_string($co_pmp, $_SESSION["id"]);
		$cmd = getCommandeUtilisateur($co_pmp, $id);

		$_POST['livre_or'] = str_replace("’", "'", $_POST['livre_or']);
		$_POST['livre_or'] = str_replace("‘", "'", $_POST['livre_or']);

		$livre_or  = htmlspecialchars($_POST['livre_or']);
		$signature = htmlspecialchars($_POST['signature']);
		$rating    = htmlspecialchars($_POST['rating']);


		if(!VerifierAlphaNum($_POST['livre_or'],0,2000))
		{
			$message_info = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message = "Votre commentaire doit être du texte uniquement de moins de 2000 caractères alphanumériques";
			$valid = false;
		}
		if (!isset($valid))
		{
			$query = "  UPDATE pmp_commande
								 	SET cmd_status = '30'
								 	WHERE user_id = '$id'
								 	AND cmd_status < 40
									AND id = '" . mysqli_real_escape_string($co_pmp, $cmd['id']) . "'";
			$res = my_query($co_pmp, $query);
			if (!$res)
			{
				return false;
			}
			else
			{
				TraceHisto($co_pmp, $cmd['id'], "Statut", "30", "", "", "");
				TraceHisto($co_pmp,$cmd['id'], "Livre d'or", "", "", "", "Note " . $_POST['rating'] . "/5 ");
				TraceHisto($co_pmp,$cmd['id'], "Message livre d'or", "", "", "", $livre_or);
				InsereLivreOr($co_pmp, $id, $livre_or, $_POST['signature'], $_POST['rating'], $cmd['id']);

				$message_info = "Succès";
				$message_type = "success";
				$message_icone = "fa-check";
				$message = "Commande correctement signalée comme livrée ";
				header('Location: /ma_commande.php');
			}
		}
	}
}
