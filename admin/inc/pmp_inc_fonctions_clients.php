<?php
include_once __DIR__ . "/../../inc/pmp_inc_fonctions_mail.php";
include_once __DIR__ . "/../../inc/pmp_inc_fonctions.php";
//Afficher tous les clients sur la page chercher
function getClientsListe(&$co_pmp)
{
	$query = "  SELECT pmp_utilisateur.id,jjj_users.name, pmp_utilisateur.user_id, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal, jjj_users.email, pmp_utilisateur.internet, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.com_user, pmp_utilisateur.disabled_account
				FROM pmp_utilisateur, jjj_users
				WHERE pmp_utilisateur.user_id = jjj_users.id
				AND pmp_utilisateur.user_id > 1000010
				ORDER BY pmp_utilisateur.code_postal ASC, jjj_users.name ASC
				";

	$res = my_query($co_pmp, $query);
	return $res;
}
function getClientsFiltresActifs(&$co_pmp)
{
	$filtres = "";
	$nouveaux_inscrits = $_SESSION["nouveaux_inscrits"];
	if ($nouveaux_inscrits == '1') {
		$traite = "pmp_utilisateur.traite = '0' AND inscrit = '1'";
	} else {
		$traite = "pmp_utilisateur.traite >= '0'";
	}

	if (strlen($_SESSION['mail_client']) > 0) {
		$mail = $_SESSION['mail_client'];
		$filtres .= " AND jjj_users.email = '$mail'";
	}
	if (strlen($_SESSION['code_client']) > 0) {
		$user_id = $_SESSION['code_client'];
		$filtres .= " AND pmp_utilisateur.user_id = '$user_id'";
	}
	if (strlen($_SESSION['cp_client']) > 0) {
		$cp = $_SESSION['cp_client'];
		$filtres .= " AND pmp_utilisateur.code_postal = '$cp'";
	}
	if (strlen($_SESSION['nom_client']) > 0) {
		$name = strtoupper($_SESSION['nom_client']);
		$filtres .= " AND jjj_users.name = '$name'";
	}
	if (strlen($_SESSION["ville_client"]) > 0) {
		$res_ville = str_replace(" ", "-", $_SESSION["ville_client"]);
		$ville = strtoupper($res_ville);
		$ville2 = $_SESSION["ville_client"];
		$filtres .= " AND (pmp_utilisateur.ville = '$ville2' OR pmp_utilisateur.ville = '$ville')";
	}
	if (strlen($_SESSION["tel_client"]) > 0) {
		$res = $_SESSION["tel_client"];
		$tel = substr($res, 0, 2) . '.' . substr($res, 2, 2) . '.' . substr($res, 4, 2) . '.' . substr($res, 6, 2) . '.' . substr($res, 8, 2);
		$filtres .= " AND (pmp_utilisateur.tel_fixe='$tel' OR pmp_utilisateur.tel_port='$tel')";
	}
	if (strlen($_SESSION["date_min_insc"]) > 0) {
		$date_min_insc = date_format(new DateTime($_SESSION["date_min_insc"]), 'Y-m-d H:i:s');
		if (strlen($_SESSION["date_max_insc"]) > 0) {
			$date_max_insc = date_format(new DateTime($_SESSION["date_max_insc"]), 'Y-m-d H:i:s');
		} else {
			$date_max_insc = date_format(new DateTime($_SESSION["date_min_insc"]), 'Y-m-d 23:00:00');
		}
		$filtres .= " AND jjj_users.registerDate BETWEEN '$date_min_insc' AND '$date_max_insc'";
	}
	if (strlen($_SESSION["date_min_co"]) > 0) {
		$date_min_co = date_format(new DateTime($_SESSION["date_min_co"]), 'Y-m-d H:i:s');
		if (strlen($_SESSION["date_max_co"]) > 0) {
			$date_max_co = date_format(new DateTime($_SESSION["date_max_co"]), 'Y-m-d H:i:s');
		} else {
			$date_max_co = date_format(new DateTime($_SESSION["date_min_co"]), 'Y-m-d 23:00:00');
		}
		$filtres .= " AND jjj_users.lastvisitDate BETWEEN '$date_min_co' AND '$date_max_co'";
	}
	if (!empty($_SESSION['groupementEnCours'])) {
		$id_grp = intval($_SESSION['groupementEnCours']);
		$filtres .= " AND pmp_utilisateur.user_id NOT IN (
			SELECT user_id FROM pmp_commande WHERE groupe_cmd = '$id_grp'
		)";
	}

	$query = "
		SELECT 
			pmp_utilisateur.id,
			jjj_users.name, 
			pmp_utilisateur.traite, 
			pmp_utilisateur.user_id, 
			pmp_utilisateur.nom, 
			pmp_utilisateur.prenom, 
			pmp_utilisateur.adresse, 
			pmp_utilisateur.ville, 
			pmp_utilisateur.code_postal, 
			jjj_users.email, 
			pmp_utilisateur.internet, 
			pmp_utilisateur.tel_fixe, 
			pmp_utilisateur.tel_port, 
			pmp_utilisateur.com_user
		FROM pmp_utilisateur
		LEFT JOIN jjj_users ON pmp_utilisateur.user_id = jjj_users.id
		WHERE $traite
		  AND COALESCE(pmp_utilisateur.disabled_account, 0) = 0
		  $filtres
		GROUP BY pmp_utilisateur.user_id
		ORDER BY pmp_utilisateur.code_postal ASC, jjj_users.name ASC
	";

	$res = my_query($co_pmp, $query);
	return $res;
}

function getClientsFiltres(&$co_pmp)
{
	$filtres = "";
	$nouveaux_inscrits = $_SESSION["nouveaux_inscrits"];
	if ($nouveaux_inscrits == '1') {
		$traite = "pmp_utilisateur.traite = '0' AND inscrit = '1'";
	} else {
		$traite = "pmp_utilisateur.traite >= '0'";
	}


	if (strlen($_SESSION['mail_client']) > 0) {
		$mail = $_SESSION['mail_client'];
		$filtres .= " AND jjj_users.email = '$mail'";
	}
	if (strlen($_SESSION['code_client']) > 0) {
		$user_id = $_SESSION['code_client'];
		$filtres .= " AND pmp_utilisateur.user_id = '$user_id'";
	}
	if (strlen($_SESSION['cp_client']) > 0) {
		$cp = $_SESSION['cp_client'];
		$filtres .= " AND pmp_utilisateur.code_postal = '$cp'";
	}
	if (strlen($_SESSION['nom_client']) > 0) {
		$name = strtoupper($_SESSION['nom_client']);
		$filtres .= " AND jjj_users.name = '$name'";
	}
	if (strlen($_SESSION["ville_client"]) > 0) {
		$res_ville = str_replace(" ", "-", $_SESSION["ville_client"]);
		$ville = strtoupper($res_ville);
		$ville2 = $_SESSION["ville_client"];
		$filtres .= " AND (pmp_utilisateur.ville = '$ville2' OR pmp_utilisateur.ville = '$ville')";
	}
	if (strlen($_SESSION["tel_client"]) > 0) {
		$res = $_SESSION["tel_client"];
		$tel = substr($res, 0, 2) . '.' . substr($res, 2, 2) . '.' . substr($res, 4, 2) . '.' . substr($res, 6, 2) . '.' . substr($res, 8, 2);
		$filtres .= " AND (pmp_utilisateur.tel_fixe='$tel' OR pmp_utilisateur.tel_port='$tel')";
	}
	if (strlen($_SESSION["date_min_insc"]) > 0) {
		$date_min_insc = date_format(new DateTime($_SESSION["date_min_insc"]), 'Y-m-d H:i:s');
		if (strlen($_SESSION["date_max_insc"]) > 0) {
			$date_max_insc = date_format(new DateTime($_SESSION["date_max_insc"]), 'Y-m-d H:i:s');
		} else {
			$date_max_insc = date_format(new DateTime($_SESSION["date_min_insc"]), 'Y-m-d 23:00:00');
		}
		$filtres .= " AND jjj_users.registerDate BETWEEN '$date_min_insc' AND '$date_max_insc'";
	}
	if (strlen($_SESSION["date_min_co"]) > 0) {
		$date_min_co = date_format(new DateTime($_SESSION["date_min_co"]), 'Y-m-d H:i:s');
		if (strlen($_SESSION["date_max_co"]) > 0) {
			$date_max_co = date_format(new DateTime($_SESSION["date_max_co"]), 'Y-m-d H:i:s');
		} else {
			$date_max_co = date_format(new DateTime($_SESSION["date_min_co"]), 'Y-m-d 23:00:00');
		}
		$filtres .= " AND jjj_users.lastvisitDate BETWEEN '$date_min_co' AND '$date_max_co'";
	}
	if (!empty($_SESSION['groupementEnCours'])) {
		$id_grp = intval($_SESSION['groupementEnCours']);
		$filtres .= " AND pmp_utilisateur.user_id NOT IN (
			SELECT user_id FROM pmp_commande WHERE groupe_cmd = '$id_grp'
		)";
	}
	if (isset($_SESSION['masquer_desactives']) && $_SESSION['masquer_desactives'] == '1') {
		$filtres .= " AND (pmp_utilisateur.disabled_account = 0 OR pmp_utilisateur.disabled_account IS NULL)";
	}

	$query = "
		SELECT 
			pmp_utilisateur.id,
			jjj_users.name, 
			pmp_utilisateur.traite, 
			pmp_utilisateur.user_id, 
			pmp_utilisateur.nom, 
			pmp_utilisateur.prenom, 
			pmp_utilisateur.adresse, 
			pmp_utilisateur.ville, 
			pmp_utilisateur.code_postal, 
			jjj_users.email, 
			pmp_utilisateur.internet, 
			pmp_utilisateur.tel_fixe, 
			pmp_utilisateur.tel_port, 
			pmp_utilisateur.com_user,
			pmp_utilisateur.disabled_account
		FROM pmp_utilisateur
		LEFT JOIN jjj_users ON pmp_utilisateur.user_id = jjj_users.id
		WHERE $traite
		$filtres
		GROUP BY pmp_utilisateur.user_id
		ORDER BY pmp_utilisateur.code_postal ASC, jjj_users.name ASC
	";

	$res = my_query($co_pmp, $query);
	return $res;
}

function getClientFournisseurZone(&$co_pmp, $id)
{
	$stmt = $co_pmp->prepare(" SELECT u.id,
                                      u.user_id,
                                      u.traite,
                                      u.nom,
                                      u.prenom,
                                      u.adresse,
                                      u.ville,
                                      u.code_postal,
                                      j.name,
                                      j.email,
                                      u.internet,
                                      u.tel_fixe,
                                      u.tel_port,
                                      u.com_user
                               FROM pmp_utilisateur u
                               INNER JOIN jjj_users j
                                   ON j.id = u.user_id
                               INNER JOIN pmp_code_postal cp
                                   ON u.code_postal = cp.code_postal
                               INNER JOIN pmp_zone_cp zc
                                   ON cp.id = zc.code_postal_id
                               WHERE u.inscrit = 1
                                 AND zc.zone_id = ?
                                 AND zc.actif = 1
                               GROUP BY u.user_id
                               ORDER BY u.code_postal ASC, j.name ASC
                             ");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$res = $stmt->get_result();

	return $res; // <-- IMPORTANT !
}

//Afficher les infos du client
function getInfosClient(&$co_pmp, $id)
{
	$query = "  SELECT pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.user_id, jjj_users.username, pmp_utilisateur.actif, jjj_users.registerDate, jjj_users.lastvisitDate, pmp_utilisateur.adresse, pmp_utilisateur.code_postal, pmp_utilisateur.ville, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_utilisateur.tel_3, pmp_utilisateur.email, pmp_utilisateur.lat, pmp_utilisateur.lng,
				pmp_utilisateur.com_crm,pmp_utilisateur.inscrit, pmp_utilisateur.com_user, pmp_utilisateur.disabled_account, pmp_utilisateur.disabled_date, pmp_utilisateur.traite, pmp_utilisateur.code_postal_id AS cp_id, pmp_utilisateur.bloquemail, pmp_utilisateur.date_blocage, jjj_users.password, jjj_users.email AS joomla_email, jjj_users.name, pmp_utilisateur.com_op, pmp_utilisateur.raison_desinscription, pmp_utilisateur.commentaire_desinscription
				FROM pmp_utilisateur, jjj_users
				WHERE pmp_utilisateur.user_id = jjj_users.id
				AND pmp_utilisateur.user_id = '$id' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function ChargeMonCompte(&$co_pmp, $user_id)
{
	$query = "SELECT * ";
	$query .= "FROM pmp_inscrit WHERE user_id='" . mysqli_real_escape_string($co_pmp, $user_id) . "'";

	$res = my_query($co_pmp, $query);
	return mysqli_fetch_array($res);
}

function ChargeCompteElectricite(&$co_pmp, $user_id)
{
	$query = "SELECT * ";
	$query .= "FROM pmp_electricite WHERE user_id='" . $user_id . "'";

	$res = my_query($co_pmp, $query);
	return mysqli_fetch_array($res);
}

function ChargeCompteGaz(&$co_pmp, $user_id)
{
	$query = "SELECT * ";
	$query .= "FROM pmp_gaz WHERE user_id='" . $user_id . "'";

	$res = my_query($co_pmp, $query);
	return mysqli_fetch_array($res);
}

//afficher les villes en fonction du code postal
function getVilleCP(&$co_pmp, $cp)
{
	$query = "  SELECT *
				FROM pmp_code_postal
				WHERE code_postal = '$cp' ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getVilleCPId(&$co_pmp, $id)
{
	$query = "  SELECT ville
				FROM pmp_code_postal
				WHERE id = '$id' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

//Afficher l'historique client
function getHistoClient(&$co_pmp, $user_id)
{
	$query = "  SELECT *
				FROM pmp_utilisateur_histo
				WHERE user_id = '$user_id'
				ORDER BY hisu_date DESC";
	$res = my_query($co_pmp, $query);
	return $res;
}

function TraceHistoClientAdmin(&$co_pmp, $user_id, $hisu_action, $hisu_valeur)
{
	// On a soit un status, soit une quantite soit une qualite

	$user = $_SESSION['user'];
	$hisu_valeur = htmlspecialchars($hisu_valeur);
	$hisu_valeur = mysqli_real_escape_string($co_pmp, $hisu_valeur);

	$query = "	INSERT INTO pmp_utilisateur_histo (user_id, hisu_date, hisu_intervenant, hisu_action, hisu_valeur )
				VALUES ('" . $user_id . "',SYSDATE(),'" . $user . "','" . $hisu_action . "','" . $hisu_valeur . "')";
	$res = my_query($co_pmp, $query);
}

//Update fiche client
if (!empty($_POST["update_client"]) || !empty($_POST["valide_update_client"])) {
	$id = $_GET["user_id"];
	$nom = $_POST["nom"];
	$prenom = $_POST["prenom"];
	$actif = $_POST["statut_client"];
	$adresse = $_POST["adresse"];
	$cp = $_POST["cp"];
	$cp_id = $_POST["ville"];
	$email = $_POST["mail"];
	$traite = isset($_POST["client_traite"]) ? "1" : "0";
	$tel_fixe = $_POST["tel_1"];
	$tel_port = $_POST["tel_2"];
	$tel_3 = $_POST["tel_3"];
	$com_user = htmlspecialchars($_POST['four_com']);
	$com_crm = htmlspecialchars($_POST['cm_crm']);
	$bloquer_mail = isset($_POST["bloquer_mail"]) ? "1" : "0";
	$date_b = $_POST["date_bloque"];
	$date_bloque = date_format(new DateTime($date_b), 'Y-m-d H:i:s');
	$com_op = htmlspecialchars($_POST['com_permanant']);

	$res_ville = getVilleCPId($co_pmp, $cp_id);
	$ville = $res_ville["ville"];

	$user = getInfosClient($co_pmp, $id);

	if ($traite != $user["traite"]) {
		$query = "  UPDATE pmp_utilisateur
					SET traite = '$traite' WHERE user_id = '$id' ";
		$res = my_query($co_pmp, $query);
		TraceHistoClientAdmin($co_pmp, $id, 'Client traité', $traite);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été modifié avec succès";
	}

	if ($nom != $user["name"]) {
		$query = "  UPDATE pmp_utilisateur
					SET nom = '$nom' WHERE user_id = '$id' ";
		$res = my_query($co_pmp, $query);

		$query = "  UPDATE jjj_users
					SET name = '$nom' WHERE id = '$id' ";
		$res = my_query($co_pmp, $query);

		TraceHistoClientAdmin($co_pmp, $id, 'Changement de Nom', $user["nom"] . " --> " . $nom);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été modifié avec succès";
	}

	if ($actif != $user["actif"]) {
		if ($user["actif"] == 0) {
			$textu_actif = "Inactif";
		}
		if ($user["actif"] == 1) {
			$textu_actif = "A relancer";
		}
		if ($user["actif"] == 2) {
			$textu_actif = "Inactif";
		}
		if ($user["actif"] == 3) {
			$textu_actif = "Inactif site";
		}
		if ($user["actif"] == 4) {
			$textu_actif = "Ancien actif";
		}
		if ($user["actif"] == 5) {
			$textu_actif = "Inactif relancé";
		}

		if ($actif == 0) {
			$text_actif = "Inactif";
		}
		if ($actif == 1) {
			$text_actif = "A relancer";
		}
		if ($actif == 2) {
			$text_actif = "Inactif";
		}
		if ($actif == 3) {
			$text_actif = "Inactif site";
		}
		if ($actif == 4) {
			$text_actif = "Ancien actif";
		}
		if ($actif == 5) {
			$text_actif = "Inactif relancé";
		}

		$query = "  UPDATE pmp_utilisateur
					SET actif = '$actif' WHERE user_id = '$id' ";
		$res = my_query($co_pmp, $query);
		TraceHistoClientAdmin($co_pmp, $id, 'Actif', $textu_actif . " --> " . $text_actif);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été modifié avec succès";
	}

	if ($prenom != $user["prenom"]) {
		$query = "  UPDATE pmp_utilisateur
					SET prenom = '$prenom' WHERE user_id = '$id' ";
		$res = my_query($co_pmp, $query);
		TraceHistoClientAdmin($co_pmp, $id, 'Changement de Prenom', $user["prenom"] . " --> " . $prenom);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été modifié avec succès";
	}

	if ($adresse != $user["adresse"]) {
		$query = "  UPDATE pmp_utilisateur
					SET adresse = '$adresse' WHERE user_id = '$id' ";
		$res = my_query($co_pmp, $query);
		TraceHistoClientAdmin($co_pmp, $id, 'Changement Adresse', $user["adresse"] . " --> " . $adresse);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été modifié avec succès";
	}

	if ($email != $user["joomla_email"]) {
		$query = "  UPDATE jjj_users
					SET email = '$email' WHERE id = '$id' ";
		$res = my_query($co_pmp, $query);

		TraceHistoClientAdmin($co_pmp, $id, 'Changement Email', $user["joomla_email"] . " --> " . $email);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été modifié avec succès";
	}

	if ($email != $user["email"]) {
		$query = "  UPDATE pmp_utilisateur
					SET email = '$email' WHERE user_id = '$id' ";
		$res = my_query($co_pmp, $query);
	}


	if ($cp != $user["code_postal"]) {
		$query = "  UPDATE pmp_utilisateur
					SET code_postal = '$cp' WHERE user_id = '$id' ";
		$res = my_query($co_pmp, $query);
		TraceHistoClientAdmin($co_pmp, $id, 'Changement de CP', $user["code_postal"] . " --> " . $cp);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été modifié avec succès";
	}

	if ($tel_fixe != $user["tel_fixe"]) {
		$query = "  UPDATE pmp_utilisateur
					SET tel_fixe = '$tel_fixe' WHERE user_id = '$id' ";
		$res = my_query($co_pmp, $query);
		TraceHistoClientAdmin($co_pmp, $id, 'Changement Tel 1', $user["tel_fixe"] . " --> " . $tel_fixe);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été modifié avec succès";
	}

	if ($tel_port != $user["tel_port"]) {
		$query = "  UPDATE pmp_utilisateur
					SET tel_port = '$tel_port' WHERE user_id = '$id' ";
		$res = my_query($co_pmp, $query);
		TraceHistoClientAdmin($co_pmp, $id, 'Changement Tel 2', $user["tel_port"] . " --> " . $tel_port);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été modifié avec succès";
	}

	if ($tel_3 != $user["tel_3"]) {
		$query = "  UPDATE pmp_utilisateur
					SET tel_3 = '$tel_3' WHERE user_id = '$id' ";
		$res = my_query($co_pmp, $query);
		TraceHistoClientAdmin($co_pmp, $id, 'Changement Tel 3', $user["tel_3"] . " --> " . $tel_3);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été modifié avec succès";
	}

	if ($ville != $user["ville"]) {
		$query = "  UPDATE pmp_utilisateur
					SET ville = '$ville', code_postal_id = '$cp_id' WHERE user_id = '$id' ";
		$res = my_query($co_pmp, $query);
		TraceHistoClientAdmin($co_pmp, $id, 'Changement de Ville', $user["ville"] . " --> " . $ville);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été modifié avec succès";
	}

	if ($com_user != $user["com_user"]) {
		$query = "  UPDATE pmp_utilisateur
					SET com_user = '$com_user' WHERE user_id = '$id' ";
		$res = my_query($co_pmp, $query);
		TraceHistoClientAdmin($co_pmp, $id, 'Commentaires', $com_user);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été modifié avec succès";
	}

	if ($com_crm != $user["com_crm"]) {
		$query = "  UPDATE pmp_utilisateur
					SET com_crm = '$com_crm' WHERE user_id = '$id' ";
		$res = my_query($co_pmp, $query);
		TraceHistoClientAdmin($co_pmp, $id, 'Commentaires CRM', $com_crm);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été modifié avec succès";
	}

	if ($com_op != $user["com_op"]) {
		$query = "  UPDATE pmp_utilisateur
					SET com_op = '$com_op' WHERE user_id = '$id' ";
		$res = my_query($co_pmp, $query);
		TraceHistoClientAdmin($co_pmp, $id, 'Commentaires destiné au fournisseur', $com_op);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été modifié avec succès";
	}
}

if (!empty($_POST["nouveau_client"])) {
	$actif = $_POST["statut_client"] ?? '';
	$cp = $_POST["cp"] ?? '';
	$cp_id = $_POST["ville"] ?? '';
	$mail = $_POST["mail"] ?? '';
	$traite = isset($_POST["client_traite"]) ? "1" : "0";
	$com_user = mysqli_real_escape_string($co_pmp, $_POST["four_com"]);
	$com_crm = mysqli_real_escape_string($co_pmp, $_POST["cm_crm"]);
	$bloquer_mail = isset($_POST["bloquer_mail"]) ? "1" : "0";
	$date_b = $_POST["date_bloque"];
	$date_bloque = date_format(new DateTime($date_b), 'Y-m-d H:i:s');

	$res_ville = getVilleCPId($co_pmp, $cp_id);
	$ville = $res_ville["ville"] ?? '';

	$date_creation_compte = date('Y-m-d H:i:s');
	$date_creation_compte = mysqli_real_escape_string($co_pmp, $date_creation_compte);

	$nom = formatNom($_POST["nom"] ?? '');
	$prenom = formatPrenom($_POST["prenom"] ?? '');
	$adresse = formatAdresse($_POST['adresse'] ?? '');
	$tel_port = formatTel($_POST['tel_1'] ?? '');
	$tel_fixe = formatTel($_POST['tel_2'] ?? '');
	$tel_3 = formatTel($_POST['tel_3'] ?? '');

	if (strlen($nom) > 0) {
		$query = "  INSERT INTO jjj_users (name, email, registerDate)
					VALUES ('$nom', '$mail', '$date_creation_compte') ";
		$res = my_query($co_pmp, $query);
		$last_id = mysqli_insert_id($co_pmp);
		$id_crypte = password_hash($last_id, PASSWORD_DEFAULT);

		$last_id = mysqli_real_escape_string($co_pmp, $last_id);
		$id_crypte = mysqli_real_escape_string($co_pmp, $id_crypte);

		$query = "  INSERT INTO pmp_utilisateur (user_id, nom, prenom, adresse, ville, code_postal, code_postal_id, tel_fixe, tel_port, tel_3, email, date_creation, com_user, com_crm, actif, inscrit, id_crypte)
					VALUES ('$last_id', '$nom', '$prenom', '$adresse', '$ville', '$cp', '$cp_id', '$tel_fixe', '$tel_port', '$tel_3', '$mail', '$date_creation_compte', '$com_user', '$com_crm', '$actif', '1',
					'$id_crypte')";
		$res = my_query($co_pmp, $query);
		if ($res) {
			TraceHistoClientAdmin($co_pmp, $last_id, 'Création client ADMIN', $last_id);

			if (isset($_GET["popup_client"])) {
				header('Location: /admin/recherche_client.php?popup_client=oui&succes=ok');
			} else {
				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "Le client a été ajouté avec succès";
			}
		}
	} else {
		header('Location: /admin/recherche_client.php?popup_client=oui&erreur=ok');
	}
}

//Supprmer client
if (!empty($_POST["supp_client"])) {
	$user_id = $_POST["user_id"];
	if (isset($user_id)) {
		//Transferer ses commandes terminées (statut 40) sur un utilisateur "spécial"
		$query = " UPDATE pmp_commande SET user_id = '1000000' WHERE user_id = '$user_id' AND cmd_status = 40 ";
		$res = my_query($co_pmp, $query);
		//Supprimer les autres commandes
		$query = " DELETE FROM pmp_commande WHERE user_id = '$user_id' AND cmd_status > 40 ";
		$res = my_query($co_pmp, $query);
		$query = " DELETE FROM pmp_utilisateur WHERE user_id = '$user_id' ";
		$res = my_query($co_pmp, $query);
		$query = " DELETE FROM jjj_users WHERE id = '$user_id' ";
		$res = my_query($co_pmp, $query);

		if ($res) {
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Le client a été supprimé avec succès";
			return $res;
		} else {
			return false;
		}
	}
}

//Fournisseur definit sur secteur
function getFournisseurSecteur(&$co_pmp, $cp)
{
	$query = "  SELECT distinct(pmp_fournisseur.nom), pmp_fournisseur_zone.libelle
				FROM pmp_fournisseur, pmp_fournisseur_zone, pmp_zone_cp, pmp_code_postal
				WHERE pmp_fournisseur.id = pmp_fournisseur_zone.fournisseur_id
				AND pmp_fournisseur_zone.id = pmp_zone_cp.zone_id
				AND pmp_code_postal.id = pmp_zone_cp.code_postal_id
				AND pmp_code_postal.code_postal = '$cp'
				AND pmp_zone_cp.actif = 1
				AND pmp_fournisseur.etat = 1 ";
	$res = my_query($co_pmp, $query);
	return $res;
}

// Exporter liste clients
function exporterListeClients(&$co_pmp, $res)
{
	$date = date("Y-m-d");
	$fichier = fopen('export/export-clients' . $date . '.xls', 'w');
	fclose($fichier);

	$fichier = fopen('export/export-clients' . $date . '.xls', 'w');
	fwrite($fichier, chr(239) . chr(187) . chr(191));
	$chaine = "";
	$col = "Code Postal;Ville;Nom;Prenom;Mail;Tel;Port;Fournisseur;Code Client";
	fwrite($fichier, $col . "\r\n");

	while ($export = mysqli_fetch_array($res)) {
		$chaine = '"' . $export["code_postal"] . '";"' . $export["ville"] . '";"' . $export["nom"] . '";"' . $export["prenom"] . '";"' . $export["email"] . '";"' . $export["tel_fixe"] .
			'";"' . $export["tel_port"] . '";"' . $export["tel_port"] . '";"' . $export["com_user"] . '";"' . $export["id"] . '"';

		fwrite($fichier, $chaine . "\r\n");
	}

	fclose($fichier);

	header('Location: /admin/export/export-clients' . $date . '.xls');
	header('Content-Encoding: UTF-8');
	header('Content-type: text/xls; charset=UTF-8');
}

function getNouveauxInscrits(&$co_pmp, $zone, $coordonnees)
{
	if ($coordonnees == 'incompletes') {
		if ($zone == 'desservie') {
			$query = "  SELECT pmp_utilisateur.id, pmp_utilisateur.user_id, pmp_utilisateur.nom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal, pmp_utilisateur.code_postal_id
						FROM pmp_utilisateur, jjj_users
						WHERE jjj_users.id = pmp_utilisateur.user_id
						AND pmp_utilisateur.internet = 1 AND pmp_utilisateur.traite = 0 AND pmp_utilisateur.inscrit = 1  and pmp_utilisateur.code_postal_id is null
						and exists (select 1 from pmp_zone_cp, pmp_fournisseur_zone, pmp_fournisseur, pmp_code_postal
                		where pmp_code_postal.code_postal = pmp_utilisateur.code_postal
                		and pmp_code_postal.id = pmp_zone_cp.code_postal_id
                		and pmp_zone_cp.actif = 1
                		and pmp_zone_cp.zone_id = pmp_fournisseur_zone.id
                		and pmp_fournisseur_zone.fournisseur_id = pmp_fournisseur.id
                		and pmp_fournisseur.etat = 1)
						ORDER BY code_postal, ville, 3 ";
			$res = my_query($co_pmp, $query);
			return $res;
		} elseif ($zone == 'non_desservie') {
			$query = "  SELECT pmp_utilisateur.id, pmp_utilisateur.user_id, pmp_utilisateur.nom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal, pmp_utilisateur.code_postal_id
						FROM pmp_utilisateur, jjj_users
						WHERE jjj_users.id = pmp_utilisateur.user_id
						AND pmp_utilisateur.internet = 1 AND pmp_utilisateur.traite = 0 AND pmp_utilisateur.inscrit = 1  and pmp_utilisateur.code_postal_id is null
						and not exists (select 1 from pmp_zone_cp, pmp_fournisseur_zone, pmp_fournisseur, pmp_code_postal
		                where pmp_code_postal.code_postal = pmp_utilisateur.code_postal
		                and pmp_code_postal.id = pmp_zone_cp.code_postal_id
		                and pmp_zone_cp.actif = 1
		                and pmp_zone_cp.zone_id = pmp_fournisseur_zone.id
		                and pmp_fournisseur_zone.fournisseur_id = pmp_fournisseur.id
		                and pmp_fournisseur.etat = 1)
						ORDER BY code_postal, ville, 3 ";
			$res = my_query($co_pmp, $query);
			return $res;
		}
	} elseif ($coordonnees == 'completes') {
		if ($zone == 'desservie') {
			$query = "  SELECT pmp_utilisateur.id, pmp_utilisateur.user_id, pmp_utilisateur.nom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal, pmp_utilisateur.code_postal_id
						FROM pmp_utilisateur, jjj_users
						WHERE jjj_users.id = pmp_utilisateur.user_id
						AND pmp_utilisateur.internet = 1 AND pmp_utilisateur.traite = 0 AND pmp_utilisateur.inscrit = 1  and pmp_utilisateur.code_postal_id is not null
						and exists (select 1 from pmp_zone_cp, pmp_fournisseur_zone, pmp_fournisseur
		                where pmp_zone_cp.code_postal_id = pmp_utilisateur.code_postal_id
		                and pmp_zone_cp.actif = 1
		                and pmp_zone_cp.zone_id = pmp_fournisseur_zone.id
		                and pmp_fournisseur_zone.fournisseur_id = pmp_fournisseur.id
		                and pmp_fournisseur.etat = 1)
						ORDER BY code_postal, ville, 3 ";
			$res = my_query($co_pmp, $query);
			return $res;
		} elseif ($zone == 'non_desservie') {
			$query = "  SELECT pmp_utilisateur.id, pmp_utilisateur.user_id, pmp_utilisateur.nom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal, pmp_utilisateur.code_postal_id
						FROM pmp_utilisateur, jjj_users
						WHERE jjj_users.id = pmp_utilisateur.user_id
						AND pmp_utilisateur.internet = 1 AND pmp_utilisateur.traite = 0 AND pmp_utilisateur.inscrit = 1  and pmp_utilisateur.code_postal_id is not null
						and not exists (select 1 from pmp_zone_cp, pmp_fournisseur_zone, pmp_fournisseur
		                where pmp_zone_cp.code_postal_id = pmp_utilisateur.code_postal_id
		                and pmp_zone_cp.actif = 1
		                and pmp_zone_cp.zone_id = pmp_fournisseur_zone.id
		                and pmp_fournisseur_zone.fournisseur_id = pmp_fournisseur.id
		                and pmp_fournisseur.etat = 1)
						ORDER BY code_postal, ville, 3 ";
			$res = my_query($co_pmp, $query);
			return $res;
		}
	}
}

//Envoyer mail d'activation de compte à un client
if (!empty($_POST["envoyer_mail_activation"])) {
	$id = $_GET["user_id"];
	$query = "  SELECT pmp_utilisateur.id_crypte, jjj_users.email
				FROM pmp_utilisateur, jjj_users
				WHERE jjj_users.id = pmp_utilisateur.user_id
				AND pmp_utilisateur.user_id = '$id' ";
	$res = my_query($co_pmp, $query);
	$res_user = mysqli_fetch_array($res);

	if (strlen($res_user[0]) > 0) {
		EnvoyerMailActivationCompte($co_pmp, $res_user["email"], $res_user["id_crypte"]);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "L'email d'activation a été envoyé";
	}
}

//Si une commande est en cours pour le client rendre le menu 'commande' cliquable
function GetCommandeClient(&$co_pmp, $id)
{
	$query = "  SELECT *
				FROM pmp_commande
				WHERE user_id = '$id'
				-- AND cmd_status < 40
				ORDER BY cmd_dt DESC ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function TraceHistoCmd(&$co_pmp, $id, $param1, $param2)
{
	$user = $_SESSION['user'];
	$param2 = htmlspecialchars($param2);
	$query = "  INSERT INTO pmp_commande_histo (cmd_id, his_intervenant, his_date, his_action, his_valeur)
				VALUES ('$id', '$user', NOW(), '$param1', '$param2') ";
	$res = my_query($co_pmp, $query);
}

function CreationCommandeAvecGroupement(&$co_pmp, $id_grp)
{
	$user_id = $_GET["user_id"];
	$query = "    SELECT *
				FROM pmp_commande
				WHERE user_id = '$user_id'
				AND cmd_status < 40 ";
	$res = my_query($co_pmp, $query);
	$cmd = mysqli_fetch_array($res);

	if (isset($cmd["id"])) {
		if ($cmd["cmd_status"] >= 30) {
			$query = "  INSERT INTO pmp_commande (user_id, groupe_cmd, cmd_dt, cmd_typefuel, cmd_qte, cmd_status )
						VALUES ('" . $user_id . "', '" . $id_grp . "',  SYSDATE(), '1', '0', '13')";
			$res = my_query($co_pmp, $query);
			if ($res) {
				$last_id = mysqli_insert_id($co_pmp);
				TraceHistoCmd($co_pmp, $last_id, 'Statut', '13 - Groupement ' . $id_grp);
				return $res;
			}
		} elseif ($cmd["cmd_status"] < 30) {
			if ($cmd["groupe_cmd"] == 0) {
				$query = "  UPDATE pmp_commande
							SET groupe_cmd = '$id_grp'
							WHERE user_id = '$user_id'
							AND cmd_status < 30 ";
				$res = my_query($co_pmp, $query);
				if ($res) {
					$last_id = mysqli_insert_id($co_pmp);
					TraceHistoCmd($co_pmp, $cmd["id"], 'Statut', '13 - Groupement ' . $id_grp);
					return $res;
				}
			}
		}
	} else {
		$query = "  INSERT INTO pmp_commande (user_id, groupe_cmd, cmd_dt, cmd_typefuel, cmd_qte, cmd_status )
					VALUES ('" . $user_id . "', '" . $id_grp . "',  SYSDATE(), '1', '0', '13')";
		$res = my_query($co_pmp, $query);
		if ($res) {
			$last_id = mysqli_insert_id($co_pmp);
			TraceHistoCmd($co_pmp, $last_id, 'Statut', '13 - Groupement ' . $id_grp);
			return $res;
		}
	}


}

//Ajouter client grp
if (!empty($_POST["ajouter_client_grp"])) {
	$id = $_POST["id_grp2"];
	$user_id = $_GET["user_id"];

	$query = "    SELECT *
				FROM pmp_commande
				WHERE user_id = '$user_id'
				AND cmd_status < 40
				ORDER BY cmd_dt DESC LIMIT 1 ";
	$res = my_query($co_pmp, $query);
	$cmd = mysqli_fetch_array($res);

	$id_cmd = $cmd["id"];

	$query = "  UPDATE pmp_commande
				SET groupe_cmd = '$id'
				WHERE id = '$id_cmd'
				 ";
	$res = my_query($co_pmp, $query);

	if ($res) {
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Le client a été ajouté au groupement " . $id;
		TraceHistoCmd($co_pmp, $cmd["id"], 'Changement de groupement', $id);
		$message_modal = "ok";
		return $res;
	}

	// $id = $_POST["id_grp"];
	// $user_id = $_GET["user_id"];
	// $query = "    SELECT *
	// 			FROM pmp_commande
	// 			WHERE user_id = '$user_id'
	// 			AND cmd_status < 40 ";
	// $res = my_query($co_pmp, $query);
	// $cmd = mysqli_fetch_array($res);
	// if(isset($cmd["id"]))
	// {
	// 	if($cmd["cmd_status"] == 10)
	// 	{
	// $query = "  UPDATE pmp_commande
	// 			SET groupe_cmd = '$id'
	// 			WHERE user_id = '$user_id'
	// 			AND cmd_status = 10 ";
	// $res = my_query($co_pmp, $query);
	// 		if($res)
	// 		{
	// 			$message_type = "success";
	// 			$message_icone = "fa-check";
	// 			$message_titre = "Succès";
	// 			$message = "Le client a été ajouté au groupement " . $id;
	// 			TraceHistoCmd($co_pmp, $cmd["id"], 'Ajout Groupement', $id);
	// 			$message_modal = "ok";
	// 			return $res;
	// 		}
	// 	}
	// 	elseif ($cmd["cmd_status"] == 15)
	// 	{
	// 		$query = "  UPDATE pmp_commande
	// 					SET groupe_cmd = '$id'
	// 					WHERE user_id = '$user_id'
	// 					AND cmd_status = 15 ";
	// 		$res = my_query($co_pmp, $query);
	// 		if($res)
	// 		{
	// 			$message_type = "success";
	// 			$message_icone = "fa-check";
	// 			$message_titre = "Succès";
	// 			$message = "Le client a été ajouté au groupement " . $id;
	// 			TraceHistoCmd($co_pmp, $cmd["id"], 'Ajout Groupement', $id);
	// 			$message_modal = "ok";
	// 			return $res;
	// 		}
	// 	}
	// 	elseif ($cmd["cmd_status"] == 13)
	// 	{
	// 		$message_type = "info";
	// 		$message_icone = "fa-exclamation";
	// 		$message_titre = "Info";
	// 		$message = "Le client est déjà sur un groupement";
	// 		$message_modal = "ok";
	// 	}
	// 	else
	// 	{
	// 		$message_type = "info";
	// 		$message_icone = "fa-exclamation";
	// 		$message_titre = "Info";
	// 		$message = "Le client a déjà une commande en cours";
	// 		$message_modal = "ok";
	// 	}
	// }
	// else
	// {
	// 	$query = "  INSERT INTO pmp_commande (user_id, groupe_cmd, cmd_dt, cmd_typefuel, cmd_qte, cmd_status )
	// 				VALUES ('" . $user_id . "', '" . $id . "',  SYSDATE(), '1', '0', '13')";
	// 	$res = my_query($co_pmp, $query);
	// 	if($res)
	// 	{
	// 		$last_id = mysqli_insert_id($co_pmp);
	// 		$message_type = "success";
	// 		$message_icone = "fa-check";
	// 		$message_titre = "Succès";
	// 		$message = "Le client a été ajouté au groupement " . $id;
	// 		$message_modal = "ok";
	// 		TraceHistoCmd($co_pmp, $last_id, 'Ajout Groupement', $id);
	// 		return $res;
	// 	}
	// }
}

if (!empty($_POST["mod_mdp"])) {
	$mdp = trim($_POST["password_user"]);
	$confmdp = trim($_POST["confirm_password"]);

	if (!empty($mdp)) {
		if (strlen($mdp) < 7) {
			$message_type = "no";
			$message_icone = "fa-times";
			$message_titre = "Erreur";
			$message = "Le mot de passe est trop court, il doit être de 7 caractères";
			$valid = false;
		}
	} else {
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Le champs mot de passe est obligatoire";
		$valid = false;
	}

	if (!empty($confmdp)) {
		if ($confmdp !== $mdp) {
			$message_type = "no";
			$message_icone = "fa-times";
			$message_titre = "Erreur";
			$message = "Les mots de passe ne sont pas identiques";
			$valid = false;
		}
	} else {
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Le champs mot de passe est obligatoire";
		$valid = false;
	}

	if (!isset($valid)) {
		$mdpH = password_hash($mdp, PASSWORD_DEFAULT);
		if (isset($_GET["user_id"])) {
			$user_id = $_GET["user_id"];
			$query = "  UPDATE jjj_users
						SET password = '$mdpH'
						WHERE id = '$user_id' ";
			$res = my_query($co_pmp, $query);
			if ($res) {
				$req_email = "  SELECT email
							FROM jjj_users
							WHERE id = '$user_id' ";
				$res = my_query($co_pmp, $req_email);
				$req_email = mysqli_fetch_array($res);
				if (isset($req_email[0])) {
					EnvoyerMailMotDePasseModAdmin($co_pmp, $mdp, $req_email["email"]);
				}


				TraceHistoClientAdmin($co_pmp, $user_id, 'Mot de passe modifié', $mdpH);
				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "Le mot de passe a bien été modifié ";
				return $res;
			}
		} else {
			$message_type = "no";
			$message_icone = "fa-times";
			$message_titre = "Erreur";
			$message = "Aucun client à modifier";
		}
	}
}

if (!empty($_POST["fusionner_clients"])) {
	if (strlen($_GET["user_id_1"]) > 0 && strlen($_GET["user_id_2"]) > 0) {
		$user_id_1 = $_GET["user_id_1"];
		$user_id_2 = $_GET["user_id_2"];

		//maj les commandes en liant les commandes du client 2 vers le client 1
		$maj_cmd = "  UPDATE pmp_commande
                	SET user_id = '$user_id_1'
                	WHERE user_id = '$user_id_2'
                	AND cmd_qte != 0
                	AND (cmd_status = 40 or cmd_comment is not null) ";
		$res = my_query($co_pmp, $maj_cmd);

		//J'efface les autres commandes (autre que statut 40)
		$del_cmd = "  	DELETE FROM pmp_commande
            			WHERE user_id = '$user_id_2' ";
		$res = my_query($co_pmp, $del_cmd);

		//Puis supprimer client 2
		$del_u = "  	DELETE FROM pmp_utilisateur
            			WHERE user_id = '$user_id_2' ";
		$res = my_query($co_pmp, $del_u);

		$del_j = "  	DELETE FROM jjj_users
            			WHERE id  = '$user_id_2' ";
		$res = my_query($co_pmp, $del_j);

		if ($res) {
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Les clients ont été fusionnés.";
			return $res;
		}
	}
}

function getCommandesStatus($co_pmp, $statut1, $statut2)
{
	$query = "  SELECT pmp_commande.cmd_dt, pmp_commande.cmd_typefuel, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, pmp_commande.cmd_prix_ord, pmp_commande.cmd_prix_sup, pmp_commande.cmd_status, pmp_commande.cmd_prixpmp,
				pmp_commande.cmd_prixaf, pmp_commande.cmd_prixfmc, pmp_commande.cmd_prixfr, pmp_commande.cmd_prixfm, pmp_commande.cmd_commentfour, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal,
				jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_commande.id AS id_cmd, jjj_users.name
				FROM pmp_commande, pmp_utilisateur, jjj_users
				WHERE pmp_commande.user_id = pmp_utilisateur.user_id
				AND pmp_utilisateur.user_id = jjj_users.id
				AND pmp_commande.cmd_status BETWEEN '$statut1' and '$statut2'
				ORDER BY pmp_commande.id";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getClientZoneStatus(&$co_pmp, $id, $statut1, $statut2)
{
	$query = "  SELECT pmp_commande.cmd_dt, pmp_commande.cmd_typefuel, pmp_commande.cmd_qte, pmp_commande.cmd_qtelivre, pmp_commande.cmd_prix_ord, pmp_commande.cmd_prix_sup, pmp_commande.cmd_status, pmp_commande.cmd_prixpmp,
				pmp_commande.cmd_prixaf, pmp_commande.cmd_prixfmc, pmp_commande.cmd_prixfr, pmp_commande.cmd_prixfm, pmp_commande.cmd_commentfour, pmp_utilisateur.nom, pmp_utilisateur.prenom, pmp_utilisateur.adresse, pmp_utilisateur.ville, pmp_utilisateur.code_postal,
				jjj_users.email, pmp_utilisateur.tel_fixe, pmp_utilisateur.tel_port, pmp_commande.id AS id_cmd, jjj_users.name
				FROM pmp_utilisateur, jjj_users, pmp_zone_cp, pmp_code_postal, pmp_commande
				WHERE jjj_users.id = pmp_utilisateur.user_id
				AND pmp_commande.user_id = pmp_utilisateur.user_id
				AND pmp_utilisateur.inscrit = 1
			    AND pmp_zone_cp.zone_id = '$id'
			    AND pmp_zone_cp.code_postal_id = pmp_code_postal.id
			    AND pmp_utilisateur.code_postal = pmp_code_postal.code_postal
				AND pmp_commande.cmd_status BETWEEN '$statut1' and '$statut2'
			    AND  pmp_zone_cp.actif = 1
				GROUP BY pmp_utilisateur.user_id
				ORDER BY pmp_commande.id
			   ";

	$res = my_query($co_pmp, $query);
	return $res;
}


function getQuantiteVolumeStatus(&$co_pmp, $fuel, $statut1, $statut2)
{
	$query = "  SELECT SUM(cmd_qte) AS qte
				FROM pmp_commande
				WHERE cmd_typefuel = '$fuel'
				AND cmd_status BETWEEN '$statut1' and '$statut2' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getQuantiteVolumeStatusZone(&$co_pmp, $id, $fuel, $statut1, $statut2)
{
	$query = "  SELECT SUM(pmp_commande.cmd_qte) AS qte
				FROM pmp_utilisateur, pmp_zone_cp, pmp_code_postal, pmp_commande
				WHERE pmp_zone_cp.code_postal_id = pmp_code_postal.id
			    AND pmp_utilisateur.code_postal = pmp_code_postal.code_postal
				AND pmp_commande.user_id = pmp_utilisateur.user_id
			    AND pmp_zone_cp.zone_id = '$id'
			    AND  pmp_zone_cp.actif = 1
				AND pmp_commande.cmd_typefuel = '$fuel'
				AND pmp_commande.cmd_status BETWEEN '$statut1' and '$statut2'
				GROUP BY pmp_utilisateur.user_id
			   ";

	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getMailModeleThunder(&$co_pmp)
{
	$query = "  SELECT *
	 			FROM pmp_mail_auto_modele
				WHERE type = 2 ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getMailSelThunder(&$co_pmp, $id)
{
	$query = "  SELECT *
	 			FROM pmp_mail_auto_modele
				WHERE id = '$id' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

if (!empty($_POST["traiter_client"])) {
	$nb_client_mail = $_POST['nb_client_mail'];
	for ($i = 0; $i < $nb_client_mail; $i++) {
		$id = 'id_client_' . $i;
		if (isset($_POST[$id])) {
			$id_client = $_POST[$id];
			$actif = 'select_traite_' . $id_client;
			$traite = isset($_POST[$actif]) ? "1" : "0";
			if ($traite == "1") {
				$query = "  UPDATE pmp_utilisateur
							SET traite = '1' WHERE user_id = '$id_client' ";
				$res = my_query($co_pmp, $query);
				TraceHistoClientAdmin($co_pmp, $id_client, 'Client traité', $traite);
				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "Le client a été modifié avec succès";
			}
		}
	}
}
function reactiverCompte(&$co_pmp, int $user_id): bool
{
	$user_id = mysqli_real_escape_string($co_pmp, $user_id);

	// 🔹 Réactivation dans pmp_utilisateur
	$query1 = "
        UPDATE pmp_utilisateur
        SET 
            disabled_account = 0,
            disabled_date = NULL,
            rappel_suppression_envoye = 0  -- 🔄 reset du flag RGPD
        WHERE user_id = '$user_id'
    ";
	$ok1 = my_query($co_pmp, $query1);

	// 🔹 Réactivation aussi côté Joomla
	$query2 = "
        UPDATE jjj_users
        SET block = 0
        WHERE id = '$user_id'
    ";
	$ok2 = my_query($co_pmp, $query2);

	// ✅ Si tout s’est bien passé, on envoie le mail + on log l’action
	if ($ok1 && $ok2) {
		// 📧 Récupère email + nom complet
		$queryMail = "
            SELECT j.email, u.prenom, u.nom
            FROM jjj_users j
            LEFT JOIN pmp_utilisateur u ON j.id = u.user_id
            WHERE j.id = '$user_id'
            LIMIT 1
        ";
		$resMail = my_query($co_pmp, $queryMail);

		if ($resMail && $row = mysqli_fetch_assoc($resMail)) {
			$email = $row['email'] ?? '';
			$prenom = $row['prenom'] ?? '';
			$nom = $row['nom'] ?? '';

			// 🗂️ Historique admin
			if (function_exists('TraceHistoClientAdmin')) {
				$texteHisto = "Le compte {$email} a été réactivé manuellement depuis l'administration.";
				TraceHistoClientAdmin($co_pmp, $user_id, 'Réactivation du compte', $texteHisto);
			}

			// 📧 Envoi du mail de confirmation
			if (!empty($email)) {
				if (function_exists('EnvoyerMailCompteReactiveAdmin')) {
					EnvoyerMailCompteReactiveAdmin($co_pmp, $email);
				} else {
					error_log("⚠️ Fonction EnvoyerMailCompteReactiveAdmin() introuvable lors de la réactivation du compte $user_id");
				}
			}
		}
	}

	return $ok1 && $ok2;
}
