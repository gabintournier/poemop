<?php
// 
include_once "pmp_inc_fonctions_mail.php";
//LIste de toutes les zones d'un fournisseurs
function getListeZonesFournisseur(&$co_pmp, $id_four)
{
	$query = "  SELECT *
				FROM pmp_fournisseur_zone
				WHERE fournisseur_id = '$id_four'
				ORDER BY id";
	$res = my_query($co_pmp, $query);
	return $res;
}

//Détails zones selectionné
function getZone(&$co_pmp, $id_zone)
{
	$query = "  SELECT id, fournisseur_id, libelle, droit_acces
				FROM pmp_fournisseur_zone
				WHERE id = '$id_zone' ";
	$res = my_query($co_pmp, $query);
	$details_zone = mysqli_fetch_array($res);
	return $details_zone;
}

// Affiche les zone dans le tableau avec le statut "actif" ou non
function getZoneCp(&$co_pmp, $id_zone)
{
	$query = "  SELECT pmp_zone_cp.zone_id, pmp_zone_cp.code_postal_id, pmp_zone_cp.actif, pmp_code_postal.code_postal, pmp_code_postal.ville, pmp_zone_cp.id as zone_cp_id
				FROM pmp_zone_cp, pmp_code_postal
				WHERE pmp_code_postal.id = pmp_zone_cp.code_postal_id
				AND pmp_zone_cp.zone_id = '" . mysqli_real_escape_string($co_pmp, $id_zone) . "'
				ORDER BY pmp_code_postal.code_postal ASC, pmp_zone_cp.id ";
	$res = my_query($co_pmp, $query);
	return $res;
}

// Affiche tout les departement dans le select
function getDepartement(&$co_pmp)
{
	$query = " 	SELECT id, libelle
				FROM pmp_departement
				ORDER BY id ASC ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function updateCotations(&$co_pmp, $zone_id, $cot)
{
	if($cot == '1')
	{
		$update = " UPDATE pmp_fournisseur_zone
					SET cotation = '0'
					WHERE id = '$zone_id' ";
		$res = my_query($co_pmp, $update);
		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "La cotation a bien été fermé pour la zone " . $zone_id;
		}
	}
	else
	{
		$update = " UPDATE pmp_fournisseur_zone
					SET cotation = '0'
					WHERE id = '$zone_id' ";
		$res = my_query($co_pmp, $update);
		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "La cotation a bien été ouverte pour la zone " . $zone_id;
		}
	}
}


//ajouter une zone
if(!empty($_POST["ajouter_zone"]))
{
	if(!empty($_POST["nom_zone"]))
	{
		$id_four = $_GET["id_four"];
		$nom = $_POST["nom_zone"];
		$option = $_POST["option_zone"];

		$query = "  INSERT INTO pmp_fournisseur_zone (id, fournisseur_id, libelle, droit_acces)
					VALUES ('', '$id_four', '$nom', '$option') ";
		$res = my_query($co_pmp, $query);
		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "La zone a été ajoutée avec succès";
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
		$message = "Le champs 'Libellé' est obligatoire";
	}

}



//Modifier une zone
if(!empty($_POST["modifier_zone"]))
{
	if(!empty($_POST["nom_zone"]))
	{
		$id = $_POST["id_zone_edit"];
		$nom = $_POST["nom_zone"];
		$option = $_POST["option_zone"];

		$query = "  UPDATE pmp_fournisseur_zone
		 			SET libelle = '$nom', droit_acces = '$option'
					WHERE id = '$id' ";
		$res = my_query($co_pmp, $query);
		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "La zone a été modifiée avec succès";
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
		$message = "Le champs 'Libellé' est obligatoire";
	}
}

if(!empty($_POST["supp_zone"]))
{
	if(!empty($_POST["supp_zone_id"]))
	{
		$id_zone = $_POST["supp_zone_id"];

		$query = " DELETE FROM pmp_zone_cp WHERE zone_id IN ( SELECT id FROM pmp_fournisseur_zone WHERE id = '$id_zone') ";
		$res = my_query($co_pmp, $query);
		$query = " DELETE FROM pmp_fournisseur_zone WHERE id = '$id_zone' ";
		$res = my_query($co_pmp, $query);

		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "La zone a été supprimée avec succès";
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
		$message = "Une erreur s'est produite";
	}
}

// Ajouter des département dans le tableau
if (!empty($_POST['add_dep']))
{
	$zone_id = $_GET['id_zone'];
	$code_departement_id = $_POST["dep_zone"];

	$query = "  SELECT *
				FROM pmp_zone_cp
				WHERE zone_id = '$zone_id'
				AND code_postal_id IN
				( SELECT id FROM pmp_code_postal WHERE code_departement = '$code_departement_id' ) ";
	$res = my_query($co_pmp, $query);
	$zone_cp = mysqli_fetch_array($res);

	if(isset($zone_cp[0]))
	{
		if(strlen($zone_cp[0])>0)
		{
			$message_type = "no";
			$message_icone = "fa-times";
			$message_titre = "Erreur";
			$message = "Le département existe déjà dans la zone.";
		}
	}
	else
	{
		$tous_actif = isset($_POST["tous_actif"]) ? "1" : "0";

		if($tous_actif == '1')
		{
			$ajouterDepartement = "	 INSERT INTO pmp_zone_cp (zone_id, code_postal_id, actif)
									 SELECT $zone_id, id, 1
									 FROM pmp_code_postal
									 WHERE code_departement = $code_departement_id ";
			$res = my_query($co_pmp, $ajouterDepartement);
		}
		else
		{
			$ajouterDepartement = "	 INSERT INTO pmp_zone_cp (zone_id, code_postal_id, actif)
									 SELECT $zone_id, id, 0
									 FROM pmp_code_postal
									 WHERE code_departement = $code_departement_id ";
			$res = my_query($co_pmp, $ajouterDepartement);
		}
		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le département " . $code_departement_id . " a été modifié avec succès";
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

//supprimer departement du TABLEAU
if (!empty($_POST['supp_dep']))
{
	$zone_id = $_GET['id_zone'];
	$code_departement_id = $_POST["dep_zone"];

	$supprimerDepartement = "   DELETE FROM pmp_zone_cp
								WHERE zone_id = $zone_id
								AND code_postal_id in
								( SELECT id FROM pmp_code_postal WHERE code_departement = '$code_departement_id' ) ";
	$res = my_query($co_pmp, $supprimerDepartement);
	if($res)
	{
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le département " . $code_departement_id . " a été supprimé avec succès";
	}
	else
	{
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Une erreur s'est produite";
	}
}

//Vider une zones
if(!empty($_POST["vider_zone"]))
{
	$id = $_GET["id_zone"];
	$query = "  DELETE FROM pmp_zone_cp
				WHERE zone_id = $id ";
	$res = my_query($co_pmp, $query);

	if($res)
	{
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "La zone a été vidée";
	}
	else
	{
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Une erreur s'est produite";
	}
}

// Ajouter des cp dans le tableau
if(!empty($_POST["add_cp"]))
{

	$cp = $_POST["cp_zone"];
	$id_zone = $_GET['id_zone'];

	$query = "  SELECT *
				FROM pmp_zone_cp
				WHERE zone_id = '$id_zone'
				AND code_postal_id IN
				( SELECT id FROM pmp_code_postal WHERE code_postal = '$cp' ) ";
	$res = my_query($co_pmp, $query);
	$zone_cp = mysqli_fetch_array($res);

	if(strlen($zone_cp[0])>0)
	{
		$query = "  UPDATE pmp_zone_cp
					SET actif = '1'
					WHERE zone_id = '$id_zone'
					AND code_postal_id IN
				   ( SELECT id FROM pmp_code_postal WHERE code_postal = '$cp' )";
		$res = my_query($co_pmp, $query);


		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le code postal " . $cp . " a été ajouté avec succès";
	}
	else
	{
		$query = "  SELECT id, code_postal
					FROM pmp_code_postal
					WHERE code_postal = '$cp' ";
		$res = my_query($co_pmp, $query);
		while ($code_postal = mysqli_fetch_array($res))
		{
			$id_cp = $code_postal["id"];
			$supprimerDoublon = "  DELETE FROM pmp_zone_cp
								   WHERE zone_id = '$id_zone'
								   AND code_postal_id = '$id_cp' ";
			mysqli_query($co_pmp, $supprimerDoublon);

			$query = "  INSERT INTO pmp_zone_cp(zone_id, code_postal_id, actif)
						VALUES ('$id_zone','$id_cp', 1) ";
			mysqli_query($co_pmp, $query);
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le code postal " . $cp . " a été ajouté avec succès";
		}
	}
}

// Supprimer des cp dans le tableau
if(!empty($_POST["supp_cp"]))
{
	$cp = $_POST["cp_zone"];
	$zone_id = $_GET['id_zone'];

	$query = "  DELETE FROM pmp_zone_cp
				WHERE zone_id = $zone_id
				AND code_postal_id in
				( SELECT id FROM pmp_code_postal WHERE code_postal = $cp ) ";
	$res = my_query($co_pmp, $query);
	if($res)
	{
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le code postal " . $cp . " a été supprimé avec succès";
	}
	else
	{
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Une erreur s'est produite";
	}
}

//Rendre actif ou non une zone fournisseur
if(isset($_POST['modifier_actif']))
{
	$nb_zone = $_POST['nb_zone'];
	for ($i=0; $i < $nb_zone; $i++)
	{
		$id = 'zone_cp_id_' . $i;
		if (isset($_POST[$id]))
		{
			$id_zone = $_POST[$id];
			$actif = 'zone_actif_' . $id_zone;
			$zone = isset($_POST[$actif]) ? "1" : "0";
			$updateZoneActif = " UPDATE pmp_zone_cp SET actif = '$zone'
								WHERE id = '$id_zone' ";
			$res = my_query($co_pmp, $updateZoneActif);
			if($res)
			{
				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "La zone a été modifiée avec succès";
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

//Rendre tous actif ou non une zone fournisseur
if(isset($_POST['rendre_tous_actif'])){
	if (!empty($_POST["zone_cp_id"])) {
		$id_zone = $_POST["zone_cp_id"];

		for($i=0;$i<$_POST['nb_zone'];$i++)
		{
			$id = $id_zone[$i];
			$zone = isset($_POST["tous_actif"]) ? "1" : "0";
			$updateZoneActif = " UPDATE pmp_zone_cp SET actif = '$zone'
								 WHERE id = '$id' ";
			$res = my_query($co_pmp, $updateZoneActif);
			if($res)
			{
				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "Toutes les zones ont été modifiées avec succès";
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

//Exporter les cp de la zone en fichier txt
if(!empty($_POST["exporter_zone"]))
{
	$zone = getZone($co_pmp, $_GET['id_zone']);
	$id = $_GET['id_zone'];

	$query = "  SELECT pmp_zone_cp.zone_id AS cp_zone_id, pmp_zone_cp.code_postal_id, pmp_code_postal.code_postal, pmp_code_postal.ville, pmp_zone_cp.actif
				FROM pmp_zone_cp, pmp_code_postal
				WHERE pmp_code_postal.id = pmp_zone_cp.code_postal_id
				AND pmp_zone_cp.zone_id = '$id' ";
	$res = my_query($co_pmp, $query);

	$fichier = fopen('export/export-zone-'.$zone['libelle'].'-'.$id.'.xls', 'w');
	fclose($fichier);

	$fichier = fopen('export/export-zone-'.$zone['libelle'].'-'.$id.'.xls', 'w+');
	$chaine = "";
	$col = "IdZone;IdCP;Code Postal;Commune;Actif";
	fwrite($fichier,$col."\r\n");

	while ($export = mysqli_fetch_array($res))
	{
		// echo $export["code_postal_id"];
		$chaine = $export["cp_zone_id"].";";
		$chaine .= $export["code_postal_id"].";";
		$chaine .= $export["code_postal"].";";
		$chaine .= $export["ville"].";";
		$chaine .= $export["actif"]."";

		fwrite($fichier,$chaine."\r\n");
	}
	fclose($fichier);
	$message_type = "success";
	$message_icone = "fa-check";
	$message_titre = "Succès";
	$message = "Le fichier a été exporté avec succès";

	header('Location: /admin/export/export-zone-'.$zone['libelle'].'-'.$id.'.xls');
}

// Importer des cp dans la zones
if (!empty($_POST["importer_zone"]))
{
	$id = $_GET['id_zone'];
	$doc = $_FILES["import"]["tmp_name"];
	$extensions = array('.xls', '');
	$extension = strrchr($_FILES['import']['tmp_name'], '.');
	if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
	{
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Vous devez uploader un fichier de type txt";
	}
	$lignes = file($doc);
	unset($lignes[0]);
	file_put_contents($doc, $lignes);

	if(!isset($type_msg))
	{
		foreach ($lignes as $ligne)
		{

			$ligne = str_replace(",", ';', $ligne);
			$ligne = explode(";",$ligne);
			if (is_numeric($ligne[0]) && is_numeric($ligne[1]) && is_numeric($ligne[2]) && strlen($ligne[2]) == 5 && preg_match("#^\D+$#",$ligne[3]))
			{
				if(move_uploaded_file($_FILES['import']['tmp_name'], $doc))
		   	 	{
					$supprimerZoneCp = "    DELETE FROM pmp_zone_cp
											WHERE zone_id = '$id'";
					$res = my_query($co_pmp, $supprimerZoneCp);
		   	 	}


				$query = "  INSERT INTO pmp_zone_cp(zone_id, code_postal_id, actif)
							VALUES('$id','$ligne[1]','$ligne[4]') ";
				mysqli_query($co_pmp, $query);
				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "Le fichier a été importé avec succès";
			}
			else
			{
				if(isset($ligne[0]))
				{
					$id = $_GET['id_zone'];
					$code = trim($ligne[0]);
					if(move_uploaded_file($_FILES['import']['tmp_name'], $doc))
			   	 	{
						$supprimerZoneCp = "    DELETE FROM pmp_zone_cp
												WHERE zone_id = '$id'";
						$res = my_query($co_pmp, $supprimerZoneCp);
			   	 	}

					$query = "  INSERT INTO pmp_zone_cp(zone_id, code_postal_id, actif)
								SELECT DISTINCT '$id', pmp_code_postal.id, '0'
								FROM pmp_code_postal
								WHERE pmp_code_postal.code_postal = '$code'
								AND NOT EXISTS (
              						SELECT 1
              						FROM pmp_zone_cp
              						WHERE zone_id = '$id'
              						AND code_postal_id = pmp_code_postal.id
          						) ";
					$res = my_query($co_pmp, $query);
					if($res)
					{
						$message_type = "success";
						$message_icone = "fa-check";
						$message_titre = "Succès";
						$message = "Le fichier a été importé avec succès";
					}
				}
			}
		}
	}
}

//Ajouter mail to à une zone
if(!empty($_POST["add_mail_to"]))
{
	if (!empty($_POST["contact_id"]))
	{
		$contact = $_POST["contact_id"];
		$id_zone = $_POST["mail_to_zone"];

		$id_select = "";
		for($i=0;$i<$_POST['nb_contact'];$i++)
		{
			$id = $contact[$i];
			$tmp = 'select_contact_' . $i;
			$select = isset($_POST[$tmp]) ? "1" : "0";
			if($select == "1")
			{
				$id_select .= $id . ";";

				$query = "  UPDATE pmp_fournisseur_zone
							SET mail_to = '$id_select'
							WHERE id = '$id_zone' ";
				$res = my_query($co_pmp, $query);
				if($res)
				{
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les mails ont bien été ajouté à la zone";
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
				$query = "  UPDATE pmp_fournisseur_zone
							SET mail_to = '$id_select'
							WHERE id = '$id_zone' ";
				$res = my_query($co_pmp, $query);
				if($res)
				{
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les mails ont bien été ajouté à la zone";
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

if(!empty($_POST["add_mail_to_zones"]))
{
	if (!empty($_POST["contact_id"]))
	{
		$contact = $_POST["contact_id"];
		$id_fournisseur = $_GET["id_four"];

		$id_select = "";
		for($i=0;$i<$_POST['nb_contact'];$i++)
		{
			$id = $contact[$i];
			$tmp = 'select_contact_' . $i;
			$select = isset($_POST[$tmp]) ? "1" : "0";
			if($select == "1")
			{
				$id_select .= $id . ";";

				$query = "  UPDATE pmp_fournisseur_zone
							SET mail_to = '$id_select'
							WHERE fournisseur_id = '$id_fournisseur' ";
				$res = my_query($co_pmp, $query);
				if($res)
				{
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les mails ont bien été ajouté à toutes les zones";
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
				$query = "  UPDATE pmp_fournisseur_zone
							SET mail_to = '$id_select'
							WHERE fournisseur_id = '$id_fournisseur' ";
				$res = my_query($co_pmp, $query);
				if($res)
				{
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les mails ont bien été ajouté à toutes les zones";
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

function getMailToZone(&$co_pmp, $id_contact)
{
	$query = "  SELECT mail_to, mail_cc, libelle
				FROM pmp_fournisseur_zone
				WHERE id = '$id_contact' ";
	$res = my_query($co_pmp, $query);
	$select_contact = mysqli_fetch_array($res);
	return $select_contact;
}

function getMailContact(&$co_pmp, $id)
{
	$query = "  SELECT mail
				FROM pmp_fournisseur_contact
				WHERE id = '$id' ";
	$res = my_query($co_pmp, $query);
	$contact = mysqli_fetch_array($res);
	return $contact;
}

// Ajouter Mail cc a une zone
if(!empty($_POST["add_mail_cc_zones"]))
{
	if (!empty($_POST["contact_id_cc"]))
	{
		$contact = $_POST["contact_id_cc"];
		$id_fournisseur = $_GET["id_four"];

		$id_select = "";
		for($i=0;$i<$_POST['nb_contact_cc'];$i++)
		{
			$id = $contact[$i];
			$tmp = 'select_contact_cc_' . $i;
			$select = isset($_POST[$tmp]) ? "1" : "0";
			if($select == "1")
			{
				$id_select .= $id . ";";

				$query = "  UPDATE pmp_fournisseur_zone
							SET mail_cc = '$id_select'
							WHERE fournisseur_id = '$id_fournisseur' ";
				$res = my_query($co_pmp, $query);
				if($res)
				{
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les mails en copie ont bien été ajouté à toutes les zones";
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
				$query = "  UPDATE pmp_fournisseur_zone
							SET mail_cc = '$id_select'
							WHERE fournisseur_id = '$id_fournisseur' ";
				$res = my_query($co_pmp, $query);
				if($res)
				{
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les mails en copie ont bien été ajouté à toutes les zones";
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

if(!empty($_POST["add_mail_cc"]))
{
	if (!empty($_POST["contact_id_cc"]))
	{
		$contact = $_POST["contact_id_cc"];
		$id_zone = $_POST["mail_to_zone_cc"];

		$id_select = "";
		for($i=0;$i<$_POST['nb_contact_cc'];$i++)
		{
			$id = $contact[$i];
			$tmp = 'select_contact_cc_' . $i;
			$select = isset($_POST[$tmp]) ? "1" : "0";
			if($select == "1")
			{
				$id_select .= $id . ";";

				$query = "  UPDATE pmp_fournisseur_zone
							SET mail_cc = '$id_select'
							WHERE id = '$id_zone' ";
				$res = my_query($co_pmp, $query);
				if($res)
				{
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les mails en copie ont bien été ajouté à la zone";
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
				$query = "  UPDATE pmp_fournisseur_zone
							SET mail_cc = '$id_select'
							WHERE id = '$id_zone' ";
				$res = my_query($co_pmp, $query);
				if($res)
				{
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les mails en copie ont bien été ajouté à la zone";
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

// Comparer une variable avec un tableau
function compare($value, $array)
{
    for( $i = 0 ; $i < count($array) ; $i++ )
	{
        if ($value == $array[$i])
		{
            return $value;
        }
	}
}

// Inserer Mail auto

function insererMailAuto(&$co_pmp, $modele, $dest, $etat, $date_a_envoyer, $chaine)
{
	$query = "  INSERT INTO pmp_mail_auto (id, user_id, modele_id, destinataires, etat, priorite, date_insertion, date_a_envoyer, date_action, chaine_cle)
	 			VALUES ('', NULL, '$modele', '$dest', '$etat', '2', NOW(), '$date_a_envoyer', NULL, '$chaine')";
	$res = my_query($co_pmp, $query);
	return $res;
}

//Envoyer mail demande cotations
if(!empty($_POST["envoyer_demande_cotations"]))
{
	if (!empty($_POST["cotation_zone_id"]))
	{
		$id_zone = $_POST["cotation_zone_id"];
		$id_four = $_GET["id_four"];
		$mail_contact = "";
		$mail_contact_cc = "";
		$four = getFournisseurDetails($co_pmp, $id_four);
		$date_mail = $_POST["date_mail"];

		$d = date("Y-m-d");
		$date = date('Y-m-d',strtotime('+1 days',strtotime($d)));
		$d_a = $date . " " . $_POST["heure_mail"];
		$d_a = date_format(new DateTime($d_a), 'Y-m-d H:i:s' );
		$jour = date("l", strtotime($d_a));

		if($jour == "Saturday")
		{
			$date_a_envoyer = date('Y-m-d H:i:s', strtotime('-30 minutes' ,strtotime($d_a)));
			$date_a_envoyer = date('Y-m-d H:i:s', strtotime('+2 days' ,strtotime($date_a_envoyer)));
		}
		else
		{
			$date_a_envoyer = date('Y-m-d H:i:s', strtotime('-30 minutes' ,strtotime($d_a)));
		}

		$chaine = "[date_mail]|" . $_POST["heure_mail"];



		for($i=0;$i<$_POST['nb_zone_cotations'];$i++)
		{
			$id = $id_zone[$i];
			$tmp = 'select_zone_mail_' . $i;
			$zone = isset($_POST[$tmp]) ? "1" : "0";

			$query = "  SELECT pmp_regroupement.id
						FROM pmp_regroupement, pmp_regrp_zone
						WHERE pmp_regrp_zone.regrp_id = pmp_regroupement.id
						AND pmp_regrp_zone.zone_id = '$id'
						AND pmp_regroupement.statut = '10' ";
			$res = my_query($co_pmp, $query);
			$trace_grp = mysqli_fetch_array($res);

			if($zone == "1")
			{
				$zone_mail = getMailToZone($co_pmp, $id);
				if(isset($zone_mail["mail_to"]))
				{
					$id_contacts = explode(";", $zone_mail["mail_to"]);
					foreach($id_contacts AS $id_contact)
					{
						$mail = getMailContact($co_pmp, $id_contact);
						if(isset($mail[0])>0)
						{
							$mail_contact .= $mail["mail"] .";";

							// insererMailAuto($co_pmp, '53', $mail["mail"], 'A', $date_a_envoyer, $chaine);
							//
							// if($trace_grp[0]>0)
							// {
							// 	TraceHistoGrpt($co_pmp, $trace_grp["id"], 'Relance : Demande de cotations', 'Fournisseur : ' . $four["nom"] . ' - Zone : ' . $zone_mail["libelle"] . '- Sera envoyé le : ' . $date_a_envoyer);
							// }
						}
					}
				}

				if(isset($zone_mail["mail_cc"]))
				{
					$id_contact_ccs = explode(";", $zone_mail["mail_cc"]);
					foreach($id_contact_ccs AS $id_contact_cc)
					{
						$mail_cc = getMailContact($co_pmp, $id_contact_cc);
						if(isset($mail_cc[0])>0)
						{
							$mail_contact_cc .= $mail_cc["mail"] .";";

							// insererMailAuto($co_pmp, '53', $mail_cc["mail"], 'A', $date_a_envoyer, $chaine);
							//
							// if($trace_grp[0]>0)
							// {
							// 	TraceHistoGrpt($co_pmp, $trace_grp["id"], 'Relance : Demande de cotations', 'Fournisseur : ' . $four["nom"] . ' - Zone : ' . $zone_mail["libelle"] . '- Sera envoyé le : ' . $date_a_envoyer);
							// }

						}
					}
				}

				if($mail_contact == '')
				{
					$message_type = "no";
					$message_icone = "fa-times";
					$message_titre = "Erreur";
					$message = "Aucun mail n'a été défini dans 'Mail To' ";
				}
				else
				{
					EnvoyerDemandeDeCotations($co_pmp, $four["id_crypte"], $id, $mail_contact, $zone_mail["libelle"], $mail_contact_cc, $_POST["date_mail"], $_POST["heure_mail"], $four["nom"]);




					if(isset($trace_grp[0]) && $trace_grp[0]>0)
					{
						TraceHistoGrpt($co_pmp, $trace_grp["id"], 'Demande de cotations', 'Fournisseur : ' . $four["nom"] . ' - Zone : ' . $zone_mail["libelle"]);
					}

					$mail_contact = "";
					$mail_contact_cc = "";

					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Les demandes de cotations ont bien été envoyées";
				}
			}
		}
	}
}

if(!empty($_POST["envoyer_mail_recap"]))
{
	if (!empty($_POST["recap_id"]))
	{
		$zone_id = $_POST["recap_id"];
		$id_four = $_GET["id_four"];
		$mail_contact = "";
		$mail_contact_cc = "";
		$four = getFournisseurDetails($co_pmp, $id_four);

		$zone_mail = getMailToZone($co_pmp, $zone_id);
		if(isset($zone_mail["mail_to"]))
		{
			$id_contacts = explode(";", $zone_mail["mail_to"]);
			foreach($id_contacts AS $id_contact)
			{
				$mail = getMailContact($co_pmp, $id_contact);
				if(isset($mail[0])>0)
				{
					$mail_contact .= $mail["mail"] .";";
				}
			}
		}

		if(isset($zone_mail["mail_cc"]))
		{
			$id_contact_ccs = explode(";", $zone_mail["mail_cc"]);
			foreach($id_contact_ccs AS $id_contact_cc)
			{
				$mail_cc = getMailContact($co_pmp, $id_contact_cc);
				if(isset($mail_cc[0])>0)
				{
					$mail_contact_cc .= $mail_cc["mail"] .";";
				}
			}
		}

		if($mail_contact == '')
		{
			$message_type = "no";
			$message_icone = "fa-times";
			$message_titre = "Erreur";
			$message = "Aucun mail n'a été défini dans 'Mail To' ";
		}
		else
		{
			$af = isset($_POST["recap_af"]) ? "1" : "0";
			if($af == 1)
			{
				// 
				include_once "af_co_connect.php";

				$query = "  SELECT id, id_four_pmp
							FROM pmp_fournisseur
							WHERE id_four_pmp = '$id_four' ";
				$res = my_query($co_af, $query);
				$four_af = mysqli_fetch_array($res);

				if(isset($four_af[0]) && $four_af[0]>0)
				{
					$id_four_af = $four_af["id"];
					$query = "  SELECT id
								FROM pmp_fournisseur_zone
								WHERE fournisseur_id = '$id_four_af' ";
					$res = my_query($co_af, $query);
					$zone_af = mysqli_fetch_array($res);

					$zone_af_id = $zone_af["id"];
					EnvoyerDemandeDeRecapAF($co_pmp, $four["id_crypte"], $zone_id, $mail_contact, $zone_mail["libelle"], $mail_contact_cc, $four["nom"], $id_four_af, $zone_af_id);
				}
				else
				{
					$message_type = "no";
					$message_icone = "fa-times";
					$message_titre = "Erreur";
					$message = "Ce fournisseur n'est pas présent dans l'amin ACHAT FIOUL";
				}
			}
			// else
			// {
			// 	EnvoyerDemandeDeRecap($co_pmp, $four["id_crypte"], $zone_id, $mail_contact, $zone_mail["libelle"], $mail_contact_cc, $four["nom"]);
			// 	$mail_contact = "";
			// 	$mail_contact_cc = "";
			//
			// 	$message_type = "success";
			// 	$message_icone = "fa-check";
			// 	$message_titre = "Succès";
			// 	$message = "Les demandes de récap a bien été envoyées";
			// }
		}
	}
}
