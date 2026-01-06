<?php
function getMailModele(&$co_pmp)
{
	$query = "  SELECT *
				FROM pmp_mail_auto_modele
			 	WHERE type = 1
				ORDER by ordre asc";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getDetailsMailModele(&$co_pmp, $id)
{
	$query = "  SELECT *
				FROM pmp_mail_auto_modele
			 	WHERE id = '$id'";
	$res = my_query($co_pmp, $query);
	$mail = mysqli_fetch_array($res);
	return $mail;
}

function getSmsDevice(&$co_pmp)
{
	$query = "  SELECT *
				FROM pmp_sms_device ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getMotsCles(&$co_pmp, $id)
{
	$query = "  SELECT *
				FROM pmp_mail_auto_modele_cle
			 	WHERE modele_id = '$id'";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getSmsType(&$co_pmp)
{
	$query = "  SELECT *
				FROM pmp_smstype ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getSmsMessage($co_pmp, $id)
{
	$query = "  SELECT *
				FROM pmp_smstype
				WHERE id = '$id' ";
	$res = my_query($co_pmp, $query);
	$message = mysqli_fetch_array($res);
	return $message;
}

function getNbMotsCles(&$co_pmp, $id)
{
	$query = "  SELECT count(*) AS mots_cle
				FROM pmp_mail_auto_modele_cle
				WHERE modele_id = '$id' ";
	$res = my_query($co_pmp, $query);
	$mots_cle = mysqli_fetch_array($res);
	return $mots_cle;
}

if(!empty($_POST["add_mail"]))
{
	$sujet_mail = mysqli_real_escape_string($co_pmp, $_POST["sujet"]);
	$nom_fichier = mysqli_real_escape_string($co_pmp, $_POST["fichier"]);
	$descriptif = mysqli_real_escape_string($co_pmp, $_POST["descriptif"]);

	$nom_fichier = str_replace(" ", "-", $nom_fichier);
	$descriptif = str_replace(" ", "-", $descriptif);

	if(!empty($sujet_mail) && !empty($nom_fichier) && !empty($descriptif))
	{
		$query = "  INSERT INTO pmp_mail_auto_modele (id, sujet, sujet_complet, dest, nom_fichier, description, type, priorite)
					VALUES ('', '$sujet_mail', '0', '0', '$nom_fichier', '$descriptif', '1', '2') ";
		$res = my_query($co_pmp, $query);
		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le mail a bien été ajouté.";
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
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Les champs 'Sujet du mail', 'Nom fichier' et 'Descriptif' sont obligatoires";
	}
}



if(!empty($_POST["up_mail"]))
{
	$sujet_mail = mysqli_real_escape_string($co_pmp, $_POST["sujet"]);
	$nom_fichier = mysqli_real_escape_string($co_pmp, $_POST["fichier"]);
	$descriptif = mysqli_real_escape_string($co_pmp, $_POST["descriptif"]);

	$nom_fichier = str_replace(" ", "-", $nom_fichier);
	$descriptif = str_replace(" ", "-", $descriptif);

	if(!empty($sujet_mail) && !empty($nom_fichier) && !empty($descriptif))
	{
		$id = $_POST["id_mail"];

		$query = "  UPDATE pmp_mail_auto_modele
					SET sujet = '$sujet_mail', nom_fichier = '$nom_fichier', description = '$descriptif'
					WHERE id = '$id'";
		$res = my_query($co_pmp, $query);
		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le mail a bien été modifié.";
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
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Les champs 'Sujet du mail', 'Nom fichier' et 'Descriptif' sont obligatoires";
	}
}

if(!empty($_POST["ajouter_mots_cles"]))
{
	$id = $_POST["id_mail"];
	$cle = mysqli_real_escape_string($co_pmp, $_POST["mots_cle"]);
	$descriptif_mc = mysqli_real_escape_string($co_pmp, $_POST["descriptif_mc"]);

	$cle = str_replace(" ", "_", $cle);
	$descriptif_mc = str_replace(" ", "_", $descriptif_mc);
	$cle = str_replace("[", "", $cle);
	$descriptif_mc = str_replace("[", "", $descriptif_mc);
	$cle = str_replace("]", "", $cle);
	$descriptif_mc = str_replace("]", "", $descriptif_mc);

	$cle = "[" . $cle . "]";
	$descriptif_mc = "[" . $descriptif_mc . "]";

	if($id != "")
	{
		if(!empty($cle) && !empty($descriptif_mc))
		{
			$query = "  INSERT INTO pmp_mail_auto_modele_cle (id, modele_id, cle, description)
						VALUES ('', '$id', '$cle', '$descriptif_mc') ";
			$res = my_query($co_pmp, $query);
			if($res)
			{
				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "Le mots-clés a bien été ajouté au mail modèle n°" . $id . ".";
			}
		}
		else
		{
			$message_type = "no";
			$message_icone = "fa-times";
			$message_titre = "Erreur";
			$message = "Les champs 'Mots-clés du mail' et 'Descriptif' sont obligatoires.";
		}
	}
	else
	{
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Pour ajouter des mots-clés il faut sélectionner un mail modèle.";
	}
}

function getListeSms(&$co_pmp)
{
	$date_min = date_format(new DateTime($_POST["date_ins_min"]), 'Y-m-d H:i:s' );
	$date_max = date_format(new DateTime($_POST["date_ins_max"]), 'Y-m-d 23:00:00' );

	$query = "  SELECT *
				FROM pmp_sms
				WHERE date_insertion BETWEEN '$date_min' AND '$date_max' ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getStatsSms(&$co_pmp, $etat)
{
	$date_min = date_format(new DateTime($_POST["date_ins_min"]), 'Y-m-d H:i:s' );
	$date_max = date_format(new DateTime($_POST["date_ins_max"]), 'Y-m-d 23:00:00' );

	$query = "  SELECT COUNT(*) as stats
				FROM pmp_sms
				WHERE date_insertion BETWEEN '$date_min' AND '$date_max'
				AND etat = '$etat' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getListeSmsNumero(&$co_pmp, $numero)
{
	$query = "  SELECT *
				FROM pmp_sms
				WHERE telephone = '$numero' ";
	$res = my_query($co_pmp, $query);
	return $res;
}

if(!empty($_POST["sauvegarder_sms"]))
{
	if(isset($_POST["nom_sms_type"]) && isset($_POST["message_sms_type"]))
	{
		$nom = mysqli_real_escape_string($co_pmp, $_POST["nom_sms_type"]);
		$message = mysqli_real_escape_string($co_pmp, $_POST["message_sms_type"]);

		$query = "  INSERT INTO pmp_smstype (id, nom, message)
					VALUES ('', '$nom', '$message') ";
		$res = my_query($co_pmp, $query);

		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le SMS type a bien été ajouté.";
		}
	}
	else
	{
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Les champs 'nom' et 'message' sont obligatoires.";
	}
}

if(!empty($_POST["supprimer_sms_type"]))
{
	$id = $_POST["sms_type"];
	$query = " DELETE FROM pmp_smstype WHERE id = '$id'";
	$res = my_query($co_pmp, $query);
	if($res)
	{
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le SMS type a bien été supprimé.";
	}
}

if(isset($_POST["sms_device"]))
{
	$id = $_POST["sms_device"];
	$query = "  UPDATE pmp_sms_device
				SET expediteur = '0' ";
	$res = my_query($co_pmp, $query);
	if($res)
	{
		$query = "  UPDATE pmp_sms_device
					SET expediteur = '1'
					WHERE id = '$id' ";
		$res = my_query($co_pmp, $query);
	}
}

if(!empty($_POST["annuler_envois"]))
{
	$query = "  UPDATE pmp_sms
				SET etat = 3
				WHERE etat = 0";
	$res = my_query($co_pmp, $query);
	if($res)
	{
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Les envois en cours ont bien été annulés.";
	}
}
?>
