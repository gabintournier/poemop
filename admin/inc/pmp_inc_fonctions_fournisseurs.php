<?php
// Afficher tous les fournisseurs
function getFournisseursListe(&$co_pmp)
{
  $query = "  SELECT  pmp_fournisseur.id, pmp_fournisseur.etat, pmp_fournisseur.nom, pmp_fournisseur.code_postal, pmp_fournisseur.ville, pmp_fournisseur.comord, pmp_fournisseur.comsup,
    concat(pmp_fournisseur.facturation, char(13), char(10), pmp_fournisseur.modalite, char(13), char(10), pmp_fournisseur.facilite) as info_four
  			  FROM pmp_fournisseur
			  WHERE pmp_fournisseur.etat = '1'
			  AND pmp_fournisseur.four_id = '0'
			  group by pmp_fournisseur.id
			  ORDER BY pmp_fournisseur.nom

			  ";
			  // SELECT MAX(pmp_regroupement.date_grp) AS date_grp , pmp_fournisseur.id, pmp_fournisseur.etat, pmp_fournisseur.nom, pmp_fournisseur.code_postal, pmp_fournisseur.ville, pmp_regroupement.statut, pmp_fournisseur.comord, pmp_fournisseur.comsup,
			  //   concat(pmp_fournisseur.facturation, char(13), char(10), pmp_fournisseur.modalite, char(13), char(10), pmp_fournisseur.facilite) as info_four
			  // 			  FROM pmp_fournisseur, pmp_regroupement
				// 		  WHERE pmp_fournisseur.id = pmp_regroupement.id_four
				// 		  AND pmp_fournisseur.etat = '1'
				// 		  AND pmp_fournisseur.four_id = '0'
				// 		  group by pmp_fournisseur.id
				// 		  ORDER BY pmp_fournisseur.nom
  $res = my_query($co_pmp, $query);
  return $res;
}

function getFournisseursListetest(&$co_pmp)
{
  $query = "  SELECT id, nom
  			  FROM pmp_fournisseur
			  WHERE etat = '1'
			  AND four_id = '0'
			  ORDER BY nom";
  $res = my_query($co_pmp, $query);
  return $res;
}


//Détails d'un fournisseur
function getFournisseurDetails(&$co_pmp, $id)
{
	$query = "  SELECT *
				FROM pmp_fournisseur
				WHERE id = '" . mysqli_real_escape_string($co_pmp, $id) . "' ";
	$res = my_query($co_pmp, $query);
	$fournisseur_details = mysqli_fetch_array($res);
	return $fournisseur_details;
}

//Afficher les zones en fonction d'un fournisseur
function getZoneFournisseurId(&$co_pmp, $id)
{
	$query = "  SELECT *
	            FROM pmp_fournisseur_zone
				WHERE fournisseur_id = '$id'";
	$res = my_query($co_pmp, $query);
    return $res;
}

function getZoneFournisseurLimitId(&$co_pmp, $id)
{
	$query = "  SELECT *
	            FROM pmp_fournisseur_zone
				WHERE fournisseur_id = '$id'
				LIMIT 1";
	$res = my_query($co_pmp, $query);
	$zone = mysqli_fetch_array($res);
	return $zone;
}

//Ajouter un fournisseur
if(!empty($_POST["add_fournisseur"]))
{
	$nom = $_POST["nom_four"];
	$etat = $_POST["etat_four"];
	$adresse = $_POST["adresse_four"];
	$code_postal = $_POST["cp_four"];
	$ville = $_POST["ville_four"];
	$fixe = $_POST["fixe_four"];
	$mobile = $_POST["mobile_four"];
	$fax = $_POST["fax_four"];
	$mail = $_POST["mail_four"];
	$com_ord = $_POST["com_ord"];
	$com_sup = $_POST["com_sup"];
	$commentaire = $_POST["commentaire"];
	$commentaire = addslashes($commentaire);
	$nom = mysqli_real_escape_string($co_pmp, $nom);
	$adresse = mysqli_real_escape_string($co_pmp, $adresse);
	$ville = mysqli_real_escape_string($co_pmp, $ville);


	if(!empty($nom) && !empty($etat) && !empty($adresse) && !empty($code_postal) && !empty($ville) && !empty($mail))
	{
		$query = "  INSERT INTO pmp_fournisseur (id,four_id, nom, adresse, ville, code_postal, tel_fixe, tel_port, fax, email, etat, commentaire, affiche, comord, comsup)
					VALUES ('', '0', '$nom', '$adresse', '$ville', '$code_postal', '$fixe', '$mobile', '$fax', '$mail', '$etat', '$commentaire', '1', '$com_ord', '$com_sup') ";
		$res = my_query($co_pmp, $query);
		if($res)
		{
			$last_id = mysqli_insert_id($co_pmp);
			$id_crypte = password_hash($last_id, PASSWORD_DEFAULT);
			$query = "  UPDATE pmp_fournisseur
						SET id_crypte = '$id_crypte'
						WHERE id = '$last_id' ";
			$res = my_query($co_pmp, $query);
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le fournisseur a été ajouté avec succès";
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
		$message = "Les champs 'Raison social', 'Etat', 'Adresse', 'Code postal', 'Ville' et 'Email' sont obligatoires";
	}
}

//Supprimer un fournisseur
if(!empty($_POST["supp_fournisseur"]))
{
	$id = $_GET["id_four"];
	$supprimerFournisseur = "DELETE FROM pmp_zone_cp where zone_id IN ( SELECT id FROM pmp_fournisseur_zone WHERE fournisseur_id = '$id' )";
	$reponse = my_query($co_pmp, $supprimerFournisseur);
	$supprimerFournisseur = "DELETE FROM pmp_fournisseur_zone WHERE fournisseur_id = '$id'";
	$reponse = my_query($co_pmp, $supprimerFournisseur);
	$supprimerFournisseur = "DELETE FROM pmp_fournisseur_contact WHERE four_id = '$id'";
	$reponse = my_query($co_pmp, $supprimerFournisseur);
	$supprimerFournisseur = "DELETE FROM pmp_fournisseur WHERE id = '$id'";
	$reponse = my_query($co_pmp, $supprimerFournisseur);

	header('Location: /admin/liste_fournisseurs.php?etat=supp');
}

//Modifier un fournisseur
if(!empty($_POST["update_fournisseur"]))
{
	$id = $_GET["id_four"];
	$four = getFournisseurDetails($co_pmp, $id);
	if(isset($_POST["nom_four"])) { $nom = $_POST["nom_four"]; } else { $nom = $four["nom"]; }
	if(isset($_POST["etat_four"])) { $etat = $_POST["etat_four"]; } else { $etat = $four["etat"]; }
	if(isset($_POST["adresse_four"])) { $adresse = $_POST["adresse_four"]; } else { $adresse = $four["adresse"]; }
	if(isset($_POST["cp_four"])) { $code_postal = $_POST["cp_four"]; } else { $code_postal = $four["code_postal"]; }
	if(isset($_POST["ville_four"])) { $ville = $_POST["ville_four"]; } else { $ville = $four["ville"]; }
	if(isset($_POST["fixe_four"])) { $fixe = $_POST["fixe_four"]; } else { $fixe = $four["tel_fixe"]; }
	if(isset($_POST["port_four"])) { $port = $_POST["port_four"]; } else { $port = $four["tel_port"]; }
	if(isset($_POST["fax_four"])) { $fax = $_POST["fax_four"]; } else { $fax = $four["fax"]; }
	if(isset($_POST["mail_four"])) { $mail = $_POST["mail_four"]; } else { $mail = $four["email"]; }
	if(isset($_POST["url_four"])) { $url = $_POST["url_four"]; } else { $url = $four["url"]; }
	if(isset($_POST["lt_four"])) { $lat = $_POST["lt_four"]; } else { $lat = $four["lat"]; }
	if(isset($_POST["lg_four"])) { $lng = $_POST["lg_four"]; } else { $lng = $four["lng"]; }
	if(isset($_POST["fact_nom"])) { $fact_nom = $_POST["fact_nom"]; } else { $fact_nom = $four["fact_nom"]; }
	if(isset($_POST["fact_adr"])) { $fact_adr = $_POST["fact_adr"]; } else { $fact_adr = $four["fact_adr"]; }
	if(isset($_POST["fact_cp"])) { $fact_cp = $_POST["fact_cp"]; } else { $fact_cp = $four["fact_cp"]; }
	if(isset($_POST["fact_ville"])) { $fact_ville = $_POST["fact_ville"]; } else { $fact_ville = $four["fact_ville"]; }
	if(isset($_POST["fact_mail"])) { $fact_mail = $_POST["fact_mail"]; } else { $fact_mail = $four["fact_email"]; }
	if(isset($_POST["com_ord"])) { $com_ord = $_POST["com_ord"]; } else { $com_ord = $four["comord"]; }
	if(isset($_POST["com_sup"])) { $com_sup = $_POST["com_sup"]; } else { $com_sup = $four["comsup"]; }
	if(isset($_POST["commentaire"])) {$commentaire = $_POST["commentaire"];} else { $commentaire = $four["commentaire"]; }
	if(isset($_POST["facilite"])) {$facilite = $_POST["facilite"];} else { $facilite = $four["facilite"]; }
	if(isset($_POST["modalite"])) {$modalite = $_POST["modalite"];} else { $modalite = $four["modalite"]; }
	$grp_email = $_POST["grp_email"]; //ingrid.deceglie@eslc.fr,agnieszka.dudzik@eslc.fr



  $modalite = mysqli_real_escape_string($co_pmp, $modalite);
  $facilite = mysqli_real_escape_string($co_pmp, $facilite);
  $commentaire = mysqli_real_escape_string($co_pmp, $commentaire);
	$ville = mysqli_real_escape_string($co_pmp, $ville);
	$nom = mysqli_real_escape_string($co_pmp, $nom);
	$adresse = mysqli_real_escape_string($co_pmp, $adresse);
	$fact_ville = mysqli_real_escape_string($co_pmp, $fact_ville);
	$fact_adr = mysqli_real_escape_string($co_pmp, $fact_adr);
  $fact_nom = mysqli_real_escape_string($co_pmp, $fact_nom);

	$updateFournisseur = "  UPDATE pmp_fournisseur
							SET nom = '$nom', adresse = '$adresse', ville = '$ville', etat ='$etat', code_postal = '$code_postal', tel_fixe = '$fixe', tel_port = '$port', fax = '$fax', email = '$mail', lat = '$lat', lng = '$lng', url = '$url',
							grp_email = '$grp_email', fact_nom = '$fact_nom', fact_adr = '$fact_adr', fact_cp = '$fact_cp', fact_ville = '$fact_ville', fact_email = '$fact_mail', comord = '$com_ord', comsup = '$com_sup', commentaire = '$commentaire', facilite = '$facilite', modalite = '$modalite'

							WHERE id = '$id' ";
	$res = my_query($co_pmp, $updateFournisseur);
	if($res)
	{
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le fournisseur a été modifié avec succès";
		return $res;
	}

}

//Filtre fournisseurs
function getFiltreFournisseurs(&$co_pmp)
{
	$non_contacte = $_SESSION["non_contacte"];
	$partenaires = $_SESSION["partenaires"];
	$partenaires_sec = $_SESSION["partenaires_sec"];
	$recontacter = $_SESSION["recontacter"];
	$recontacter_com = $_SESSION["recontacter_com"];
	$pas_interesse = $_SESSION["pas_interesse"];
	$pas_interessant = $_SESSION["pas_interessant"];
	$autre_fioul = $_SESSION["autre_fioul"];
	$partenanriat_fini = $_SESSION["partenanriat_fini"];
	$principaux = $_SESSION["principaux"];

	$n_four = $_SESSION['n_four'];

	$filtres = "";
	if($non_contacte == 1) { $filtres .= "0 "; }
	if($partenaires == 1) { $filtres .= "1 "; }
	if($pas_interessant == 1) { $filtres .= "2 "; }
	if($pas_interesse == 1) { $filtres .= "3 "; }
	if($recontacter == 1) { $filtres .= "4 "; }
	if($recontacter_com == 1) { $filtres .= "5 "; }
	if($autre_fioul == 1) { $filtres .= "6 "; }
	if($partenanriat_fini == 1) { $filtres .= "7 "; }
	if($partenaires_sec == 1) { $filtres .= "8 "; }

	if(strlen($filtres) > 1)
	{
		$filtres = str_replace(' ', ',', $filtres);
		$filtres = substr($filtres,0,-1);
	}
	else {
		$filtres = str_replace(' ', '', $filtres);
	}

	// if ($principaux == 1)
	// {
	// 	$query = "  SELECT pmp_fournisseur.id, pmp_fournisseur.etat, pmp_fournisseur.nom, pmp_fournisseur.code_postal, pmp_fournisseur.ville, concat(pmp_fournisseur.facturation, char(13), char(10), pmp_fournisseur.modalite, char(13), char(10), pmp_fournisseur.facilite) as info_four
	// 				FROM pmp_fournisseur
	// 				WHERE pmp_fournisseur.etat IN ($filtres)
	// 				AND pmp_fournisseur.four_id = '0'
	// 				ORDER BY pmp_fournisseur.nom ";
	// 	$res = my_query($co_pmp, $query);
	// 	return $res;
	// }


	if ($principaux == 1)
	{
		if(isset($n_four) == $_SESSION['n_four'])
		{
			$query = "  SELECT pmp_fournisseur.id, pmp_fournisseur.etat, pmp_fournisseur.nom, pmp_fournisseur.code_postal, pmp_fournisseur.ville, concat(pmp_fournisseur.facturation, char(13), char(10), pmp_fournisseur.modalite, char(13), char(10), pmp_fournisseur.facilite) as info_four
						FROM pmp_fournisseur
						WHERE pmp_fournisseur.etat IN ($filtres)
						AND pmp_fournisseur.id = '$n_four'
						group by pmp_fournisseur.id
						ORDER BY pmp_fournisseur.nom ";
			$res = my_query($co_pmp, $query);
			return $res;
		}
		else
		{
			$query = "  SELECT pmp_fournisseur.id, pmp_fournisseur.etat, pmp_fournisseur.nom, pmp_fournisseur.code_postal, pmp_fournisseur.ville, concat(pmp_fournisseur.facturation, char(13), char(10), pmp_fournisseur.modalite, char(13), char(10), pmp_fournisseur.facilite) as info_four
						FROM pmp_fournisseur
						WHERE pmp_fournisseur.etat IN ($filtres)
						AND pmp_fournisseur.four_id = '0'
						group by pmp_fournisseur.id
						ORDER BY pmp_fournisseur.nom ";
			$res = my_query($co_pmp, $query);
			return $res;
		}
	}
	else
	{
		if(isset($n_four) == $_SESSION['n_four'])
		{
			$n_four = $_SESSION['n_four'];
			if ($non_contacte == 1)
			{
				$query = "  SELECT pmp_fournisseur.id, pmp_fournisseur.etat, pmp_fournisseur.nom, pmp_fournisseur.code_postal, pmp_fournisseur.ville,  concat(pmp_fournisseur.facturation, char(13), char(10), pmp_fournisseur.modalite, char(13), char(10), pmp_fournisseur.facilite) as info_four
							FROM pmp_fournisseur
							WHERE pmp_fournisseur.etat IN ('$non_contacte', '$partenaires',  '$partenaires_sec',  '$recontacter',  '$recontacter_com',  '$pas_interesse',  '$pas_interessant',  '$autre_fioul',  '$partenanriat_fini')
							AND pmp_fournisseur.id = '$n_four'
							AND pmp_fournisseur.four_id > '0'
							group by pmp_fournisseur.id
							ORDER BY pmp_fournisseur.nom ";
				$res = my_query($co_pmp, $query);
				return $res;
			}
			else
			{
				$query = "  SELECT pmp_fournisseur.id, pmp_fournisseur.etat, pmp_fournisseur.nom, pmp_fournisseur.code_postal, pmp_fournisseur.ville, concat(pmp_fournisseur.facturation, char(13), char(10), pmp_fournisseur.modalite, char(13), char(10), pmp_fournisseur.facilite) as info_four
							FROM pmp_fournisseur
							WHERE pmp_fournisseur.etat IN ('$partenaires',  '$partenaires_sec',  '$recontacter',  '$recontacter_com',  '$pas_interesse',  '$pas_interessant',  '$autre_fioul',  '$partenanriat_fini')
							AND pmp_fournisseur.id = '$n_four'
							AND pmp_fournisseur.four_id > '0'
							AND pmp_fournisseur.etat != '0'
							group by pmp_fournisseur.id
							ORDER BY pmp_fournisseur.nom ";
				$res = my_query($co_pmp, $query);
				return $res;
			}
		}
		else
		{
			if ($non_contacte == 1)
			{
				$query = "  SELECT pmp_fournisseur.id, pmp_fournisseur.etat, pmp_fournisseur.nom, pmp_fournisseur.code_postal, pmp_fournisseur.ville,  concat(pmp_fournisseur.facturation, char(13), char(10), pmp_fournisseur.modalite, char(13), char(10), pmp_fournisseur.facilite) as info_four
							FROM pmp_fournisseur
							WHERE pmp_fournisseur.etat IN ('$non_contacte', '$partenaires',  '$partenaires_sec',  '$recontacter',  '$recontacter_com',  '$pas_interesse',  '$pas_interessant',  '$autre_fioul',  '$partenanriat_fini')
							AND pmp_fournisseur.four_id > '0'
							group by pmp_fournisseur.id
							ORDER BY pmp_fournisseur.nom ";
				$res = my_query($co_pmp, $query);
				return $res;
			}
			else
			{
				$query = "  SELECT pmp_fournisseur.id, pmp_fournisseur.etat, pmp_fournisseur.nom, pmp_fournisseur.code_postal, pmp_fournisseur.ville,  concat(pmp_fournisseur.facturation, char(13), char(10), pmp_fournisseur.modalite, char(13), char(10), pmp_fournisseur.facilite) as info_four
							FROM pmp_fournisseur
							WHERE pmp_fournisseur.etat IN ('$partenaires',  '$partenaires_sec',  '$recontacter',  '$recontacter_com',  '$pas_interesse',  '$pas_interessant',  '$autre_fioul',  '$partenanriat_fini')
							AND pmp_fournisseur.four_id > '0'
							AND pmp_fournisseur.etat != '0'
							group by pmp_fournisseur.id
							ORDER BY pmp_fournisseur.nom ";
				$res = my_query($co_pmp, $query);
				return $res;
			}
		}
	}
}

function getFiltreFournisseursDep(&$co_pmp)
{
	$non_contacte = $_SESSION["non_contacte"];
	$partenaires = $_SESSION["partenaires"];
	$partenaires_sec = $_SESSION["partenaires_sec"];
	$recontacter = $_SESSION["recontacter"];
	$recontacter_com = $_SESSION["recontacter_com"];
	$pas_interesse = $_SESSION["pas_interesse"];
	$pas_interessant = $_SESSION["pas_interessant"];
	$autre_fioul = $_SESSION["autre_fioul"];
	$partenanriat_fini = $_SESSION["partenanriat_fini"];
	$principaux = $_SESSION["principaux"];

	$dep = $_SESSION["n_dep"];
	$filtres = "";
	if($non_contacte == 1) { $filtres .= "0 "; }
	if($partenaires == 1) { $filtres .= "1 "; }
	if($pas_interessant == 1) { $filtres .= "2 "; }
	if($pas_interesse == 1) { $filtres .= "3 "; }
	if($recontacter == 1) { $filtres .= "4 "; }
	if($recontacter_com == 1) { $filtres .= "5 "; }
	if($autre_fioul == 1) { $filtres .= "6 "; }
	if($partenanriat_fini == 1) { $filtres .= "7 "; }
	if($partenaires_sec == 1) { $filtres .= "8 "; }

	if(strlen($filtres) > 1)
	{
		$filtres = str_replace(' ', ',', $filtres);
		$filtres = substr($filtres,0,-1);
	}
	else {
		$filtres = str_replace(' ', '', $filtres);
	}

	if ($principaux == 1)
	{
		$query = "  SELECT pmp_fournisseur.id, pmp_fournisseur.etat, pmp_fournisseur.nom, pmp_fournisseur.code_postal, pmp_fournisseur.ville
					FROM pmp_fournisseur
					WHERE pmp_fournisseur.code_postal LIKE '$dep%'
					AND pmp_fournisseur.etat IN ($filtres)
					AND pmp_fournisseur.four_id = '0'
					ORDER BY pmp_fournisseur.nom ";
		$res = my_query($co_pmp, $query);
		return $res;
	}
	else
	{
		$query = "  SELECT pmp_fournisseur.id, pmp_fournisseur.etat, pmp_fournisseur.nom, pmp_fournisseur.code_postal, pmp_fournisseur.ville, pmp_regroupement.statut
					FROM pmp_fournisseur, pmp_regroupement
					WHERE pmp_fournisseur.id = pmp_regroupement.id_four
					AND pmp_fournisseur.code_postal LIKE '$dep%'
					AND pmp_fournisseur.etat IN ($filtres)
					AND pmp_fournisseur.four_id > '0'
					group by pmp_fournisseur.id
					ORDER BY pmp_fournisseur.nom ";
		$res = my_query($co_pmp, $query);
		return $res;
	}


}

function getFiltreFournisseursId(&$co_pmp)
{
	$id = $_POST["n_four"];
	$query = "  SELECT *
				FROM pmp_fournisseur
				WHERE id = '$id'
				ORDER BY nom ";
	$res = my_query($co_pmp, $query);
	return $res;
}
//Recherche sur zone de livraison
function getFournisseursCP(&$co_pmp, $cp)
{
	$non_contacte = $_SESSION["non_contacte"];
	$partenaires = $_SESSION["partenaires"];
	$partenaires_sec = $_SESSION["partenaires_sec"];
	$recontacter = $_SESSION["recontacter"];
	$recontacter_com = $_SESSION["recontacter_com"];
	$pas_interesse = $_SESSION["pas_interesse"];
	$pas_interessant = $_SESSION["pas_interessant"];
	$autre_fioul = $_SESSION["autre_fioul"];
	$partenanriat_fini = $_SESSION["partenanriat_fini"];
	$principaux = $_SESSION["principaux"];

	$filtres = "";
	if($non_contacte == 1) { $filtres .= "0 "; }
	if($partenaires == 1) { $filtres .= "1 "; }
	if($pas_interessant == 1) { $filtres .= "2 "; }
	if($pas_interesse == 1) { $filtres .= "3 "; }
	if($recontacter == 1) { $filtres .= "4 "; }
	if($recontacter_com == 1) { $filtres .= "5 "; }
	if($autre_fioul == 1) { $filtres .= "6 "; }
	if($partenanriat_fini == 1) { $filtres .= "7 "; }
	if($partenaires_sec == 1) { $filtres .= "8 "; }

	if(strlen($filtres) > 1)
	{
		$filtres = str_replace(' ', ',', $filtres);
		$filtres = substr($filtres,0,-1);
	}
	else {
		$filtres = str_replace(' ', '', $filtres);
	}

	if ($principaux == 1)
	{
		$query = "  SELECT  pmp_fournisseur.id, pmp_fournisseur.etat, pmp_fournisseur.nom, pmp_fournisseur.code_postal,
					pmp_fournisseur.ville, pmp_fournisseur_zone.libelle
					FROM pmp_fournisseur, pmp_fournisseur_zone, pmp_zone_cp, pmp_code_postal, pmp_regroupement
					WHERE pmp_fournisseur.id = pmp_fournisseur_zone.fournisseur_id
					AND pmp_fournisseur_zone.id = pmp_zone_cp.zone_id
					AND pmp_zone_cp.code_postal_id = pmp_code_postal.id
					AND pmp_fournisseur.id = pmp_regroupement.id_four
					AND pmp_code_postal.code_postal = '$cp'
					AND pmp_fournisseur.etat IN ($filtres)
					AND pmp_fournisseur.four_id = '0'
					AND pmp_zone_cp.actif = '1'
					GROUP BY pmp_fournisseur_zone.id ";
		$res = my_query($co_pmp, $query);
		return $res;
	}
	else
	{
		$query = "  SELECT pmp_fournisseur.id, pmp_fournisseur.etat, pmp_fournisseur.nom, pmp_fournisseur.code_postal,
					pmp_fournisseur.ville, pmp_fournisseur_zone.libelle
					FROM pmp_fournisseur, pmp_fournisseur_zone, pmp_zone_cp, pmp_code_postal, pmp_regroupement
					WHERE pmp_fournisseur.id = pmp_fournisseur_zone.fournisseur_id
					AND pmp_fournisseur_zone.id = pmp_zone_cp.zone_id
					AND pmp_zone_cp.code_postal_id = pmp_code_postal.id
					AND pmp_fournisseur.id = pmp_regroupement.id_four
					AND pmp_code_postal.code_postal = '$cp'
					AND pmp_fournisseur.etat IN ($filtres)
					AND pmp_fournisseur.four_id > '0'
					AND pmp_zone_cp.actif = '1'
					GROUP BY pmp_fournisseur_zone.id ";
		$res = my_query($co_pmp, $query);
		return $res;
	}


}

function getFournisseursGetCP(&$co_pmp, $cp)
{
	$query = "  SELECT  pmp_fournisseur.id, pmp_fournisseur.etat, pmp_fournisseur.nom, pmp_fournisseur.code_postal,
				pmp_fournisseur.ville, pmp_fournisseur_zone.libelle
				FROM pmp_fournisseur, pmp_fournisseur_zone, pmp_zone_cp, pmp_code_postal, pmp_regroupement
				WHERE pmp_fournisseur.id = pmp_fournisseur_zone.fournisseur_id
				AND pmp_fournisseur_zone.id = pmp_zone_cp.zone_id
				AND pmp_zone_cp.code_postal_id = pmp_code_postal.id
				AND pmp_fournisseur.id = pmp_regroupement.id_four
				AND pmp_code_postal.code_postal = '$cp'
				AND pmp_fournisseur.etat = 1
				AND pmp_fournisseur.four_id = '0'
				AND pmp_zone_cp.actif = '1'
				GROUP BY pmp_fournisseur_zone.id ";
	$res = my_query($co_pmp, $query);
	return $res;
}

//Afficher tout les fournisseurs
function getFournisseurs(&$co_pmp)
{
	$query = "  SELECT  pmp_regroupement.statut, pmp_fournisseur.*
				FROM pmp_fournisseur, pmp_regroupement
				WHERE pmp_fournisseur.id = pmp_regroupement.id_four
				group by pmp_fournisseur.id ";
	$res = my_query($co_pmp, $query);
	return $res;
}

//Afficher les contacts d'un fournisseur
function getContactsFournisseurs(&$co_pmp, $id_four)
{
	$query = "  SELECT *
				FROM pmp_fournisseur_contact
				WHERE four_id = '$id_four' ";
	$res = my_query($co_pmp, $query);
	return $res;
}

//Ajouter un contact
if(!empty($_POST["ajouter_contact"]))
{
	if (!empty($_POST["nom_contact"]) || !empty($_POST["prenom_contact"]) || !empty($_POST["tel_contact"]) || !empty($_POST["mail_contact"]))
	{
		$id_four = $_GET["id_four"];
		$nom = $_POST["nom_contact"];
		$prenom = $_POST["prenom_contact"];
		$tel = $_POST["tel_contact"];
		$mail = $_POST["mail_contact"];
		$fonction = $_POST["fonction_contact"];
		$commentaire = $_POST["com_contact"];

		$ajouterContact = " INSERT INTO pmp_fournisseur_contact(id, four_id, nom, prenom, tel, mail, fonction, commentaire)
							VALUES ('', '$id_four', '$nom', '$prenom', '$tel', '$mail', '$fonction', '$commentaire') ";

		mysqli_query($co_pmp, $ajouterContact);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Un nouveau contact a été ajouté à ce fournisseur";
	}
	else
	{
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Vous devez remplir tous les champs obligatoires";
	}
}

//Modifer un contact
if(!empty($_POST["modifier_contact"]))
{
	if (!empty($_POST["nom_contact"]) || !empty($_POST["prenom_contact"]) || !empty($_POST["tel_contact"]) || !empty($_POST["mail_contact"]))
	{
		$id = $_POST["id_contact"];
		$nom = $_POST["nom_contact"];
		$prenom = $_POST["prenom_contact"];
		$tel = $_POST["tel_contact"];
		$mail = $_POST["mail_contact"];
		$fonction = $_POST["fonction_contact"];
		$commentaire = $_POST["com_contact"];

		$updateFournisseurContact = "   UPDATE pmp_fournisseur_contact SET nom = '$nom', prenom = '$prenom', tel = '$tel', mail = '$mail', fonction = '$fonction', commentaire = '$commentaire'
										WHERE id= '$id' ";
		$res = my_query($co_pmp, $updateFournisseurContact);

		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le contact a été modifié";
	}
	else
	{
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Vous devez remplir tous les champs obligatoires";
	}
}

//Supprimer un contact
if(!empty($_POST["supp_contact"]))
{
	$id = $_POST["supp_contact_id"];
	$supprimerFournisseurContact = "  DELETE FROM pmp_fournisseur_contact
									  WHERE id = '$id' ";
	$res = my_query($co_pmp, $supprimerFournisseurContact);

	if($res)
	{
		$message_type = "info";
		$message_icone = "fa-exclamation";
		$message_titre = "Info";
		$message = "Le contact a été supprimé";
	}
	else
	{
		$message_type = "info";
		$message_icone = "fa-exclamation";
		$message_titre = "Info";
		$message = "NO";
	}
}

//Exporter la liste des fournisseurs en XSL
function exporterListeFournisseurs($co_pmp, $res)
{
	$date = date("Y-m-d");
	$fichier = fopen('export/export-fournisseur' . $date .'.xls', 'w');
	fclose($fichier);

	$fichier = fopen('export/export-fournisseur' . $date .'.xls', 'w+');
	fwrite($fichier, chr(239) . chr(187) . chr(191));
	$chaine = "";
	$col = "Nom;Dernier Grpt le;Code Postal;Ville;Etat;";
	fwrite($fichier,$col."\r\n");

	while ($export = mysqli_fetch_array($res))
	{
		if ($export["etat"] == '0'){ $etat = "Non contacté"; }
		if ($export["etat"] == '1'){ $etat = "Partenaire"; }
		if ($export["etat"] == '2'){ $etat = "Pas interessant"; }
		if ($export["etat"] == '3'){ $etat = "Pas interessé"; }
		if ($export["etat"] == '4'){ $etat = "A recontacter"; }
		if ($export["etat"] == '5'){ $etat = "A recontact pr com"; }
		if ($export["etat"] == '6'){ $etat = "Autre que fioul"; }
		if ($export["etat"] == '7'){ $etat = "Partenairiat fini"; }
		if ($export["etat"] == '8'){ $etat = "Partenaire sec"; }

		$chaine = '"' . $export["nom"] . '";" - ";"' . $export["code_postal"] . '";"' . $export["ville"] . '";"' . $etat . '"';

		fwrite($fichier,$chaine."\r\n");
	}

	fclose($fichier);

	header('Location: /admin/export/export-fournisseur' . $date . '.xls');
	header('Content-Encoding: UTF-8');
	header('Content-type: text/xls; charset=UTF-8');
}

//Afficher tous les avis clients d'un fournisseur
function getLivreOrFournisseur(&$co_pmp, $id)
{
	$query = "  SELECT pmp_commande.user_id, pmp_regroupement.id, pmp_regroupement.date_grp, pmp_livre_or.note, pmp_livre_or.message
	 			FROM pmp_commande, pmp_regroupement, pmp_livre_or, pmp_fournisseur
				WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
				AND pmp_livre_or.commande_id = pmp_commande.id
				AND pmp_fournisseur.id = pmp_regroupement.id_four
				AND pmp_fournisseur.id = '$id'
				AND pmp_livre_or.valide = 1
				ORDER BY date_grp DESC
				";
	$res = my_query($co_pmp, $query);
	return $res;
}

//Afficher le dernier groupement
function getDernierGroupement(&$co_pmp, $id)
{
	$query = "  SELECT date_grp, statut
				FROM pmp_regroupement
				WHERE id = ( SELECT MAX(id) FROM pmp_regroupement WHERE id_four = '$id' ) ";
	$res = my_query($co_pmp, $query);
	$date = mysqli_fetch_array($res);
	return $date;
}

function getDateDernierGroupement(&$co_pmp, $id)
{
	$query = "  SELECT MAX(date_grp) AS date_grp , statut
					FROM pmp_regroupement
					WHERE id_four = '$id' ";
	$res = my_query($co_pmp, $query);
	if($res)
	{
		$date = mysqli_fetch_array($res);
		return $date;
	}

}
?>
