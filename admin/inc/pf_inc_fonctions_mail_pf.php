<?php
function getMailModelePF($co_pf)
{
	$query = "  SELECT *
				FROM pmp_mail_auto_modele ";
	$res = my_query($co_pf, $query);
	return $res;
}

function getMailModelePFid($co_pf, $id)
{
	$query = "  SELECT *
				FROM pmp_mail_auto_modele
				WHERE id = '$id'";
	$res = my_query($co_pf, $query);
	$mail = mysqli_fetch_array($res);
	return $mail;
}

function getMotsClesPF($co_pf, $id)
{
	$query = "  SELECT *
				FROM pmp_mail_auto_modele_cle
				WHERE modele_id = '$id'";
	$res = my_query($co_pf, $query);
	return $res;
}

function getNbMotsClesPF(&$co_pf, $id)
{
	$query = "  SELECT count(*) AS mots_cle
				FROM pmp_mail_auto_modele_cle
				WHERE modele_id = '$id' ";
	$res = my_query($co_pf, $query);
	$mots_cle = mysqli_fetch_array($res);
	return $mots_cle;
}

if(!empty($_POST["ajouter_mots_cles"]))
{
	if(isset($_POST["mots_cle"]) && isset($_POST["descriptif_mc"]))
	{
		$id = $_GET["mail_id"];
		$cle = mysqli_real_escape_string($co_pf, $_POST["mots_cle"]);
		$descriptif_mc = mysqli_real_escape_string($co_pf, $_POST["descriptif_mc"]);

		$cle = str_replace(" ", "_", $cle);
		$cle = strtoupper($cle);
		if($id != "")
		{
			$query = "  INSERT INTO pmp_mail_auto_modele_cle (id, modele_id, cle, description)
						VALUES ('', '$id', '$cle', '$descriptif_mc') ";
			$res = my_query($co_pf, $query);
			if($res)
			{
				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "Le mots-clés a bien été ajouté au mail modèle n°" . $id . ".";
			}
		}
	}
}

function getClientsFiltresPF($co_pf)
{
	$filtres = "";
	if(strlen($_SESSION['email_client']) > 0) { $mail = $_SESSION['email_client']; $filtres .= "AND mail = '$mail'"; }
	if(strlen($_SESSION['code_postal_client']) > 0) { $cp = $_SESSION['code_postal_client']; $filtres .= "AND cp = '$cp'"; }

	if(strlen($_SESSION["date_min_insc_client"]) > 0)
	{
		$date_min_insc_client = date_format(new DateTime($_SESSION["date_min_insc_client"]), 'Y-m-d H:i:s' );

		if(strlen($_SESSION["date_max_insc_client"]) > 0)
		{
			$date_max_insc_client = date_format(new DateTime($_SESSION["date_max_insc_client"]), 'Y-m-d H:i:s' );
		}
		else
		{
			$date_max_insc_client = date_format(new DateTime($_SESSION["date_min_insc_client"]), 'Y-m-d 23:00:00' );

		}

		$filtres .= "AND date_creation BETWEEN '$date_min_insc_client' AND '$date_max_insc_client'";
	}

	$query = "  SELECT *
				FROM pf_mailing
				WHERE id > 0
				$filtres
				";
	$res = my_query($co_pf, $query);
	return $res;
}

function getTotalClientsPF($co_pf)
{
	$query = "  SELECT COUNT(*) AS total
				FROM pf_mailing
				";
	$res = my_query($co_pf, $query);
	$total = mysqli_fetch_array($res);
	return $total;
}

if(!empty($_POST["modifier_mail_pf"]))
{
	if(isset($_POST["code_client"]) || isset($_POST["email_client_modifier"]) || isset($_POST["code_postal_client_modifier"]))
	{
		$id = $_POST["code_client"];
		$email = $_POST["email_client_modifier"];
		$cp = $_POST["code_postal_client_modifier"];

		$query = "  UPDATE pf_mailing
					SET mail = '$email', cp = '$cp'
					WHERE id = '$id'";
		$res = my_query($co_pf, $query);
		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le client a bien été modifié.";
		}
	}
	else
	{
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Aucun client n'a été sélectionné.";
	}
}

if(!empty($_POST["supprimer_mail_pf"]))
{
	if(isset($_POST["code_client_supp"]))
	{
		$id = $_POST["code_client_supp"];

		$query = "  DELETE FROM pf_mailing
					WHERE id = '$id'";
		$res = my_query($co_pf, $query);
		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le client a bien été supprimé.";
		}
	}
	else
	{
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Aucun client n'a été sélectionné.";
	}
}

function exporterListeClientsPF(&$co_pf, $res)
{
	$fichier = fopen('export/export-mail-pf.xls', 'w');
	fclose($fichier);

	$fichier = fopen('export/export-mail-pf.xls', 'w');
	fwrite($fichier, chr(239) . chr(187) . chr(191));
	$chaine = "";
	$col = "Code Postal;Mail;Reduc;Inf;Sup;Com";
	fwrite($fichier,$col."\r\n");

	while($export = mysqli_fetch_array($res))
	{
		if($export["alerte_reduc"] == 1) { $reduc = "X"; }
		if($export["alerte_com"] == 1) { $com = "X"; }

		$chaine = '"' . $export["cp"] .'";"' . $export["mail"] . '";"' . $reduc . '";"' . $export["alerte_inf"] . '";"' . $export["alerte_sup"] . '";"' . $com . '"';

		fwrite($fichier,$chaine."\r\n");
	}

	fclose($fichier);

	header('Location: /admin/export/export-mail-pf.xls');
	header('Content-Encoding: UTF-8');
	header('Content-type: text/xls; charset=UTF-8');
}

function InsererMailAutoPF(&$co_pf, $res_mail)
{
	if(isset($_SESSION["mail_modele"]))
	{
		$modele_id = $_SESSION["mail_modele"];
		$chaine = "";

		$d = date("Y-m-d");
		$date = date_format(new DateTime($d), 'Y-m-d H:i:s' );

		if($_POST["nb_mot_cle"] != 0)
		{
			for($i=0;$i<$_POST['nb_mot_cle'];$i++)
			{
				$valeur_mots_cles = $_POST["valeur_cle"];
				$valeur = $valeur_mots_cles[$i];
				if(strlen($valeur) > 0)
				{
					$tmp = 'mots_cle_' . $i;
					$mots_cles = $_POST[$tmp];
					$mots_cles = mysqli_real_escape_string($co_pf, $mots_cles);
					$valeur = mysqli_real_escape_string($co_pf, $valeur);

					$chaine .= $mots_cles . "=" . $valeur . "&";

				}
				else
				{
					$chaine .= NULL;
					break;
				}
			}
		}
		else
		{
			$chaine .= NULL;
		}

		if($chaine != NULL)
		{
			$chaine = substr($chaine, 0, -1);
			while($mail = mysqli_fetch_array($res_mail))
			{
				$mail_client = $mail["mail"];
				$query = "  INSERT INTO pmp_mail_auto (id, cmd_id, modele_id, destinataires, etat, priorite, date_insertion, date_a_envoyer, date_action, chaine_cle)
							VALUES ('', NULL, '$modele_id', '$mail_client', 'A', '2', NOW(), Sysdate(), NULL, '$chaine') ";
				$res = my_query($co_pf, $query);
				if($res)
				{
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les mails ont bien été envoyés.";
				}
			}
		}
		else
		{
			while($mail = mysqli_fetch_array($res_mail))
			{
				$mail_client = $mail["mail"];
				$query = "  INSERT INTO pmp_mail_auto (id, cmd_id, modele_id, destinataires, etat, priorite, date_insertion, date_a_envoyer, date_action, chaine_cle)
							VALUES ('', NULL, '$modele_id', '$mail_client', 'A', '2', NOW(), Sysdate(), NULL, NULL) ";
				$res = my_query($co_pf, $query);

			}
		}

	}
}

function getListeCodePostal($co_pmp, $zone_id)
{
	$query = "  SELECT pmp_code_postal.code_postal
				FROM pmp_zone_cp, pmp_code_postal
				WHERE pmp_zone_cp.zone_id = '$zone_id'
				AND    pmp_zone_cp.code_postal_id = pmp_code_postal.id
			    AND    pmp_zone_cp.actif = 1
				ORDER BY  pmp_code_postal.code_postal ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getMailFournisseurZone($co_pf, $code_postal)
{
	$query = "  SELECT distinct(pf_mailing.id), cp, mail, alerte_reduc, alerte_inf, alerte_sup, alerte_com
    			FROM pf_mailing
        		WHERE cp in ($code_postal)
    			ORDER BY cp ";
	$res = my_query($co_pf, $query);
	return $res;
}

function getMajPF($co_pf)
{
	$query = "  SELECT code, libelle, valeur, date
				FROM pf_donnees_maj
				WHERE code in ('CTAUX', 'CBRE', 'CWTI', 'C92')
				ORDER BY date";
	$res = my_query($co_pf, $query);
	return $res;
}

function getDetailsMajPF($co_pf, $code)
{
	$query = "  SELECT *
				FROM pf_donnees_maj
				WHERE code = '$code'
				";
	$res = my_query($co_pf, $query);
	$valeur = mysqli_fetch_array($res);
	return $valeur;
}

if(!empty($_POST["maj_pf"]))
{
	if(!empty($_POST["nb_maj"]))
	{
		$nb_maj = $_POST['nb_maj'];
		for ($i=0; $i < $nb_maj; $i++)
		{
			$code = 'code_' . $i;
			$code = $_POST[$code];
			if (isset($code))
			{
				$valeur = 'maj_valeur_' . $code;
				$maj_valeur = $_POST[$valeur];
				$maj_details = getDetailsMajPF($co_pf, $code);
				$date = date("Y-m-d");

				if($code != "C92")
				{
					$query = "  UPDATE pf_donnees_maj SET valeur = '$maj_valeur', date = '$date'
								WHERE code = '$code' ";
					$res = my_query($co_pf, $query);
				}
				else
				{
					if ($maj_valeur != $maj_details["valeur"])
					{
						$query = "  UPDATE pf_donnees_maj SET valeur = '$maj_valeur', date = '$date'
									WHERE code = '$code' ";
						$res = my_query($co_pf, $query);
					}
				}

			}
		}
		header('Location: /admin/index.php');
	}
}
?>
