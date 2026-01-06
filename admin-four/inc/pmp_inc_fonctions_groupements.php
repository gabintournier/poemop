<?php
include_once  "pmp_inc_fonctions_mail.php";
function TraceHisto(&$co_pmp, $id, $user,  $param1, $param2)
{
	$param2 = addslashes($param2);
	$query = "  INSERT INTO pmp_commande_histo (cmd_id, his_intervenant, his_date, his_action, his_valeur)
				VALUES ('$id', '$user', NOW(), '$param1', '$param2') ";
	$res = my_query($co_pmp, $query);
}

function TraceHistoGrpt(&$co_pmp, $id, $user, $param1, $param2)
{
	$param2 = addslashes($param2);
	$query = "  INSERT INTO pmp_regroupement_histo (grp_id, hisg_intervenant, hisg_date, hisg_action, hisg_valeur)
				VALUES ('$id', '$user', NOW(), '$param1', '$param2') ";
	$res = my_query($co_pmp, $query);
}

function getListeGroupements(&$co_pmp, $four_id)
{
	$query = "  SELECT *
				FROM pmp_regroupement
				WHERE statut BETWEEN 10 AND 15
				AND id_four = '$four_id'
				ORDER BY date_grp DESC";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getListeGroupementsLivre(&$co_pmp, $four_id)
{
	$query = "  SELECT *
				FROM pmp_regroupement
				WHERE statut = 30
				AND id_four = '$four_id'
				ORDER BY date_grp DESC";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getListeGroupementsTermines(&$co_pmp)
{
	$four_id = $_SESSION["four_id"];

	$query = "  SELECT *
				FROM pmp_regroupement
				WHERE statut BETWEEN 33 AND 40
				AND id_four = '$four_id'
				ORDER BY date_grp DESC";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getGroupementDetails(&$co_pmp, $id_grp)
{
	$query = "  SELECT *
				FROM pmp_regroupement
				WHERE id = '$id_grp' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

/* Liste commandes tableau */
function getCommandesGroupements(&$co_pmp, $id_grp)
{
	$query = "  SELECT pmp_regroupement.id, pmp_commande.cmd_typefuel, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, pmp_commande.cmd_prix_ord, pmp_commande.cmd_prix_sup,
	 			pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal, pmp_commande.cmd_commentfour, pmp_commande.cmd_comment_du_four,
				pmp_utilisateur.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_commande.id AS id_cmd, jjj_users.name, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.tel_3,
				pmp_utilisateur.user_id, pmp_commande.cmd_status
				FROM pmp_regroupement
				LEFT JOIN pmp_commande
				ON pmp_regroupement.id = pmp_commande.groupe_cmd
				LEFT JOIN pmp_utilisateur
				ON pmp_commande.user_id = pmp_utilisateur.user_id
				LEFT JOIN jjj_users
				ON pmp_utilisateur.user_id = jjj_users.id
				WHERE pmp_regroupement.id = '$id_grp'
				AND pmp_commande.cmd_status = '25'
				/*AND pmp_commande.cmd_status != '55'
				AND pmp_commande.cmd_status != '52'*/
				ORDER BY pmp_commande.id";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getCommandesGroupementsRecap(&$co_pmp, $id_grp)
{
	$query = "  SELECT pmp_regroupement.id, pmp_commande.cmd_typefuel, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, pmp_commande.cmd_prix_ord, pmp_commande.cmd_prix_sup,
	 			pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal, pmp_commande.cmd_commentfour, pmp_commande.cmd_comment_du_four,
				pmp_utilisateur.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_commande.id AS id_cmd, jjj_users.name, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.tel_3,
				pmp_utilisateur.user_id, pmp_commande.cmd_status
				FROM pmp_regroupement
				LEFT JOIN pmp_commande
				ON pmp_regroupement.id = pmp_commande.groupe_cmd
				LEFT JOIN pmp_utilisateur
				ON pmp_commande.user_id = pmp_utilisateur.user_id
				LEFT JOIN jjj_users
				ON pmp_utilisateur.user_id = jjj_users.id
				WHERE pmp_regroupement.id = '$id_grp'
				AND pmp_commande.cmd_status != '50'
				AND pmp_commande.cmd_status != '55'
				AND pmp_commande.cmd_status != '99'
				-- AND pmp_commande.cmd_status != '52'
				ORDER BY pmp_commande.id";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getStatsQteCommandeRecap(&$co_pmp, $id_grp, $fuel)
{
	$query = "  SELECT SUM(pmp_commande.cmd_qte) AS cmd_qte, SUM(pmp_commande.cmd_qtelivre) AS cmd_qtelivre
				FROM pmp_regroupement
				LEFT JOIN pmp_commande
				ON pmp_regroupement.id = pmp_commande.groupe_cmd
				WHERE pmp_regroupement.id = '$id_grp'
				AND pmp_commande.cmd_status != '50'
				AND pmp_commande.cmd_status != '55'
				AND pmp_commande.cmd_status != '52'
				AND pmp_commande.cmd_status != '99'
				AND pmp_commande.cmd_typefuel = '$fuel' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getStatsQteCommande(&$co_pmp, $id_grp, $fuel)
{
	$query = "  SELECT SUM(pmp_commande.cmd_qte) AS cmd_qte, SUM(pmp_commande.cmd_qtelivre) AS cmd_qtelivre
				FROM pmp_regroupement
				LEFT JOIN pmp_commande
				ON pmp_regroupement.id = pmp_commande.groupe_cmd
				WHERE pmp_regroupement.id = '$id_grp'
				AND pmp_commande.cmd_status = '25'
				AND pmp_commande.cmd_typefuel = '$fuel' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}


//Détails d'une commande client
function getCommandeDetailsClients(&$co_pmp, $id)
{
	$query = "  SELECT pmp_commande.id AS num_cmd, pmp_commande.cmd_qte, pmp_commande.cmd_typefuel, pmp_commande.cmd_dt,pmp_commande.cmd_prix_ord,pmp_commande.cmd_prix_sup, pmp_commande.cmd_status, pmp_commande.cmd_comment, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.code_postal, pmp_utilisateur.ville, pmp_utilisateur.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.user_id,
				pmp_commande.cmd_commentfour, pmp_commande.cmd_qtelivre, pmp_fournisseur.id AS four_id, pmp_commande.groupe_cmd
				FROM pmp_commande
				LEFT JOIN pmp_utilisateur
				ON pmp_utilisateur.user_id = pmp_commande.user_id
				LEFT JOIN pmp_regroupement
				ON pmp_commande.groupe_cmd = pmp_regroupement.id
				LEFT JOIN pmp_fournisseur
				ON pmp_regroupement.id_four = pmp_fournisseur.id
				WHERE pmp_commande.id = '$id' ";
	$res = my_query($co_pmp, $query);
	$cmd_details = mysqli_fetch_array($res);
	return $cmd_details;
}

/* Modifier qté livrée tableau */
if(!empty($_POST["nb_commande"]))
{
	$nb_commande = $_POST['nb_commande'];
	for ($i=0; $i < $nb_commande; $i++)
	{
		$id = 'id_cmde_' . $i;
		$id_cmde = $_POST[$id];

		if (isset($id_cmde))
		{
			$l = 'cmd_livree_' . $id_cmde;
			$livree = isset($_POST[$l])? "1" : "0";
			if(isset($livree))
			{
				if($livree == 1)
				{
					$qte = 'qte_' . $id_cmde;
					$qte_livree = $_POST[$qte];
				}
				elseif ($livree == 0)
				{
					$qte = 'qte_livree_' . $id_cmde;
					$qte_livree = $_POST[$qte];
				}
			}
			else
			{
				$qte = 'qte_livree_' . $id_cmde;
				$qte_livree = $_POST[$qte];
			}

			$commande_details = getCommandeDetailsClients($co_pmp, $id_cmde);

			if ($qte_livree != $commande_details["cmd_qtelivre"])
			{
				$updateQte = " UPDATE pmp_commande SET cmd_qtelivre = '$qte_livree'
							   WHERE id = '$id_cmde' ";
				$res = my_query($co_pmp, $updateQte);
				header('Location: /admin-four/saisie_recap.php?id_crypte=' . $_GET["id_crypte"] .'&id_grp=' . $_GET["id_grp"] . '&return=recap#cmd_livree_'.$id_cmde);
				// TraceHisto($co_pmp, $id_cmde, 'Quantité Livrée', $qte_livree);
			}
		}
	}
	if(isset($res))
	{
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "La quantité livrée des commandes a bien été modifiée.";
		return $res;
	}
	else
	{
		return false;
	}
}

if(!empty($_POST["rendre_tous_actif"]))
{
	if (!empty($_POST["id_cmde"]))
	{
		$id_cmde = $_POST["id_cmde"];
		for($i = 0; $i < $_POST['nb_commande']; $i++)
		{
			$id = $id_cmde[$i];
			$livree = isset($_POST["tous_actif"]) ? "1" : "0";

			if(isset($livree))
			{
				if($livree == 1)
				{
					$qte = 'qte_' . $id;
					$qte_livree = $_POST[$qte];
				}
				elseif ($livree == 0)
				{
					$qte_livree = "0";
				}
			}

			$commande_details = getCommandeDetailsClients($co_pmp, $id);

			if ($qte_livree != $commande_details["cmd_qtelivre"])
			{
				$updateQte = " UPDATE pmp_commande SET cmd_qtelivre = '$qte_livree'
							   WHERE id = '$id' ";
				$res = my_query($co_pmp, $updateQte);
				// TraceHisto($co_pmp, $id_cmde, 'Quantité Livrée', $qte_livree);
			}
		}
		if(isset($res))
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "La quantité livrée des commandes a bien été modifiée.";
			return $res;
		}
		else
		{
			return false;
		}
	}
}

// Ajouter commentaire fournisseur commande
if(!$recharge)
{
	if(!empty($_POST["add_comment"]))
	{
		$commentaire = $_POST["commentaire_du_four"];
		$id_cmd = $_POST["id_cmd_comment"];
		$commentaire = mysqli_real_escape_string($co_pmp, $commentaire);
		$commentaire = str_replace(",", "", $commentaire);

		$query = "  UPDATE pmp_commande SET cmd_comment_du_four = '$commentaire'
					WHERE id = '$id_cmd' ";
		$res = my_query($co_pmp, $query);

		if(isset($res))
		{
			TraceHisto($co_pmp, $id_cmd, 'Admin Fournisseur', 'Commentaire fournisseur', $commentaire);
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le commentaire a bien été ajouté à la commande.";
			return $res;
		}
		else
		{
			return false;
		}
	}
}

if(!$recharge)
{
	if(!empty($_POST["fini_livrer"]))
	{
		if(isset($_GET["id_grp"]))
		{
			$id = $_GET["id_grp"];
			$query = "  UPDATE pmp_regroupement
						SET statut = '30'
						WHERE id = '$id'";
			$res = my_query($co_pmp, $query);
		}
		if(isset($res))
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le groupement est bien passé au statut livré.";
			return $res;
		}
		else
		{
			return false;
		}
	}
}

//Envoyer reacap POEMOP
function envoyerRecap(&$co_pmp)
{
	$grp = getGroupementDetails($co_pmp, $_GET["id_grp"]);

	$session_four = str_replace("/","",$_SESSION['four']);

	$fichier = fopen('export/export-recap-' . $session_four .'-' . $grp["date_grp"] . '.xls', 'w');
	fclose($fichier);

	$fichier = fopen('export/export-recap-' . $session_four .'-' . $grp["date_grp"] . '.xls', 'w+');
	fwrite($fichier, chr(239) . chr(187) . chr(191));
	$chaine = "";
	$col = "Nom;Prenom;CP;Ville;Adresse;Tel 1;Tel 2;Commentaire;Qté;Qté Livrée";
	fwrite($fichier,$col."\r\n");

	$res = getCommandesGroupementsRecap($co_pmp, $_GET["id_grp"]);
	while($export = mysqli_fetch_array($res))
	{
		$chaine = '"' . $export["name"] .'";"' . $export["prenom"] . '";"' . $export["code_postal"] . '";"' . $export["ville"] . '";"' . $export["adresse"] . '";"' . $export["tel_port"]
		. '";"' . $export["tel_fixe"] . '";"' . $export["cmd_comment_du_four"] . '";"' . $export["cmd_qte"] .  '";"' . $export["cmd_qtelivre"] . '"';

		fwrite($fichier,$chaine."\r\n");
	}

	fclose($fichier);

	header('Location: /admin-four/saisie_recap.php?id_crypte=' . $_GET["id_crypte"] .'&id_grp=' . $grp["id"] . '&return=recap&qte=saisie');
	header('Content-Encoding: UTF-8');
	header('Content-type: text/xls; charset=UTF-8');

	$piece_jointe = 'export-recap-' . $session_four .'-' . $grp["date_grp"] . '.xls';

	EnvoyerMailSaisieRecap("Quantité saisie sur groupement -> " . $grp['id'], "Quantité saisie sur groupement " . $grp['id'] . " - fournisseur " . $session_four, $piece_jointe);

	$id = $_GET["id_grp"];
	$query = "  UPDATE pmp_regroupement
				SET statut = '33'
				WHERE id = '$id'";
	$res = my_query($co_pmp, $query);

	if($res)
	{
		TraceHistoGrpt($co_pmp, $id, 'Admin Fournisseur', 'Statut grp', '33 - A facturer');
		TraceHistoGrpt($co_pmp, $id, 'Admin Fournisseur', 'Saisie Récap', 'Envoyé');
	}
}

// Exporter liste commande
function ExporterListeCmd(&$co_pmp, $res, $grp)
{
	$session_four = str_replace("/","",$_SESSION['four']);

	$fichier = fopen('export/export-commandes-' . $session_four .'-' . $grp["date_grp"] . '.xls', 'w');
	fclose($fichier);

	$fichier = fopen('export/export-commandes-' . $session_four .'-' . $grp["date_grp"] . '.xls', 'w+');
	fwrite($fichier, chr(239) . chr(187) . chr(191));
	$chaine = "";
	$col = "Nom;Prenom;CP;Ville;Adresse;Commentaire client;Tel 1;Tel 2;Commentaire;Prix Ord;Prix Sup;Qté;Qualité";
	fwrite($fichier,$col."\r\n");

	while($export = mysqli_fetch_array($res))
	{
		if ($export["cmd_typefuel"] == 1){ $type = 'Ordinaire';}
		if ($export["cmd_typefuel"] == 2){ $type = 'Supérieur';}
		if ($export["cmd_typefuel"] == 3){ $type = 'GNR';}

		//$date = date_format(new DateTime($cmd["cmd_dt"]), 'd/m/Y' );

		$chaine = '"' . $export["name"] .'";"' . $export["prenom"] . '";"' . $export["code_postal"] . '";"' . $export["ville"] . '";"' . $export["adresse"] . '";"' . $export["cmd_commentfour"]
		. '";"' . $export["tel_port"] . '";"' . $export["tel_fixe"] . '";"' . $export["cmd_comment_du_four"] . '";"' . $export["cmd_prix_ord"] . '";"' . $export["cmd_prix_sup"] . '";"' . $export["cmd_qte"]
		. '";"' . $type . '"';

		fwrite($fichier,$chaine."\r\n");
	}

	fclose($fichier);

	header('Location: /admin-four/export/export-commandes-' . $session_four .'-' . $grp["date_grp"] . '.xls');
	header('Content-Encoding: UTF-8');
	header('Content-type: text/xls; charset=UTF-8');
}

function ExporterListeCmdTermine(&$co_pmp, $res, $grp)
{
	$session_four = str_replace("/","",$_SESSION['four']);


	$fichier = fopen('export/export-commandes-' . $session_four .'-' . $grp["date_grp"] . '.xls', 'w');
	fclose($fichier);

	$fichier = fopen('export/export-commandes-' . $session_four .'-' . $grp["date_grp"] . '.xls', 'w+');
	fwrite($fichier, chr(239) . chr(187) . chr(191));
	$chaine = "";
	$col = "Nom;Prenom;CP;Ville;Adresse;Commentaire client;Tel 1;Tel 2;Commentaire;Prix Ord;Prix Sup;Qté;Qté Livrée;Qualité";
	fwrite($fichier,$col."\r\n");

	while($export = mysqli_fetch_array($res))
	{
		if ($export["cmd_typefuel"] == 1){ $type = 'Ordinaire';}
		if ($export["cmd_typefuel"] == 2){ $type = 'Supérieur';}
		if ($export["cmd_typefuel"] == 3){ $type = 'GNR';}

		// $date = date_format(new DateTime($cmd["cmd_dt"]), 'd/m/Y' );

		$chaine = '"' . $export["name"] .'";"' . $export["prenom"] . '";"' . $export["code_postal"] . '";"' . $export["ville"] . '";"' . $export["adresse"] . '";"' . $export["cmd_commentfour"]
		. '";"' . $export["tel_port"] . '";"' . $export["tel_fixe"] . '";"' . $export["cmd_comment_du_four"] . '";"' . $export["cmd_prix_ord"] . '";"' . $export["cmd_prix_sup"]
		. '";"' . $export["cmd_qte"] . '";"' . $export["cmd_qtelivre"] . '";"' . $type . '"';

		fwrite($fichier,$chaine."\r\n");
	}

	fclose($fichier);

	header('Location: /admin-four/export/export-commandes-' . $session_four .'-' . $grp["date_grp"] . '.xls');
	header('Content-Encoding: UTF-8');
	header('Content-type: text/xls; charset=UTF-8');
}
// Exporter liste commande
function ExporterListeRecap(&$co_pmp, $res, $grp)
{
	$session_four = str_replace("/","",$_SESSION['four']);

	$fichier = fopen('export/export-recap-' . $session_four .'-' . $grp["date_grp"] . '.xls', 'w');
	fclose($fichier);

	$fichier = fopen('export/export-recap-' . $session_four .'-' . $grp["date_grp"] . '.xls', 'w+');
	fwrite($fichier, chr(239) . chr(187) . chr(191));
	$chaine = "";
	$col = "Nom;Prenom;CP;Ville;Adresse;Tel 1;Tel 2;Commentaire;Qté;Qté Livrée";
	fwrite($fichier,$col."\r\n");

	while($export = mysqli_fetch_array($res))
	{
		$chaine = '"' . $export["name"] .'";"' . $export["prenom"] . '";"' . $export["code_postal"] . '";"' . $export["ville"] . '";"' . $export["adresse"] . '";"' . $export["tel_port"]
		. '";"' . $export["tel_fixe"] . '";"' . $export["cmd_comment_du_four"] . '";"' . $export["cmd_qte"] .  '";"' . $export["cmd_qtelivre"] . '"';

		fwrite($fichier,$chaine."\r\n");
	}

	fclose($fichier);

	header('Location: /admin-four/export/export-recap-' . $session_four .'-' . $grp["date_grp"] . '.xls');
	header('Content-Encoding: UTF-8');
	header('Content-type: text/xls; charset=UTF-8');
}

function getPlagesPrix(&$co_pmp, $id)
{
	$query = "  SELECT *
				FROM pmp_regrp_plages
				WHERE regrp_id = '$id' ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getCommissions(&$co_pmp)
{
	$four_id = $_SESSION["four_id"];
	$query = "  SELECT comord, comsup
				FROM pmp_fournisseur
				WHERE id = '$four_id' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

if(!$recharge)
{
	if(!empty($_POST["envoyer_mail_rappel"]))
	{
		if(isset($_SESSION["four_id"]))
		{
			$four_id = $_SESSION["four_id"];
			$query = "  SELECT tel_fixe, tel_port
						FROM pmp_fournisseur
						WHERE id = '$four_id' ";
			$res = my_query($co_pmp, $query);
			$res_four = mysqli_fetch_array($res);
		}

		if(isset($_POST["user_id"]))
		{
			$user_id = $_POST["user_id"];
			$query = "  SELECT email, name
						FROM jjj_users
						WHERE id = '$user_id' ";
			$res = my_query($co_pmp, $query);
			$res_user = mysqli_fetch_array($res);
		}

		if(isset($res_four["tel_fixe"]) && isset($res_user["email"]))
		{
			EnvoyerMailRappelClient($co_pmp, $res_four["tel_fixe"], $res_user["email"]);
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Une demande de rappel a bien été envoyé à " . $res_user["name"] . ".";
		}
	}
}


if(!empty($_POST["annuleeLivraison"]))
{
	$id = $_POST["id_cmd_livraison"];
	$commentaire = $_POST["commentaire_annulation"];
	$commentaire = mysqli_real_escape_string($co_pmp, $commentaire);
	$query = "  UPDATE pmp_commande SET cmd_comment_du_four = '$commentaire', cmd_qtelivre = '0', cmd_status = '52'
				WHERE id = '$id' ";
	$res = my_query($co_pmp, $query);

	if($res)
	{
		TraceHisto($co_pmp, $id, 'Admin Fournisseur', 'Quantité Livrée', '0');
		TraceHisto($co_pmp, $id, 'Admin Fournisseur', 'Status', "52 - Livraison Annulée");
		TraceHisto($co_pmp, $id, 'Admin Fournisseur', 'Raison Annulation', $commentaire);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "La livraison de cette commande à bien été annulée.";
		return $res;
	}
}
?>
