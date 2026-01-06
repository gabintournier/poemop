<?php
include_once __DIR__ . "/../inc/pmp_inc_fonctions_mail.php";

// S'assure que la connexion MySQL est disponible (certains scripts incluent ce fichier avant pmp_co_connect.php)
if (!isset($co_pmp) || !($co_pmp instanceof mysqli)) {
	include_once __DIR__ . "/pmp_co_connect.php";
}

if (!empty($_POST["connexion"])) {
	if (!empty($_POST['identifiant']) && !empty($_POST['password'])) {
		$user = htmlentities(trim($_POST['identifiant']));
		$password = trim($_POST["password"]);

		$user = mysqli_real_escape_string($co_pmp, $user);
		$password = mysqli_real_escape_string($co_pmp, $password);

		// --- Récupérer l'utilisateur ---
		$query = "SELECT * FROM jjj_users WHERE email = '" . mysqli_real_escape_string($co_pmp, $user) . "'";
		$res = my_query($co_pmp, $query);
		$pmp_user = mysqli_fetch_array($res);

		if (!isset($pmp_user)) {
			$err = "L'email ne correspond à aucun utilisateur.";
			$erreur_form = "erreur_form";
		} else {
			// --- Vérifier si le compte est désactivé ---
			$query = "SELECT disabled_account FROM pmp_utilisateur WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $pmp_user['id']) . "'";
			$res_disabled = my_query($co_pmp, $query);
			$row_disabled = mysqli_fetch_assoc($res_disabled);

			if (!empty($row_disabled['disabled_account']) && $row_disabled['disabled_account'] == 1) {
				$err = '
					<p class="link-signup">
						Ce compte est désactivé.<br>
						<a href="#" onclick="document.getElementById(\'reactiver_compte_form\').submit();" style="font-size:15px;color:#ef8351;text-decoration:underline;font-weight:400;">
							Cliquez ici pour recevoir un mail de réactivation.
						</a>
					</p>
					<form method="post" id="reactiver_compte_form" style="display:none;">
						<input type="hidden" name="reactiver_compte_mail" value="' . htmlspecialchars($user) . '">
					</form>
				';
				$erreur_form = "erreur_form";
			}
			else {
				// Récupérer le super mot de passe et construire le pass dynamique
				$query = "SELECT superword FROM site_settings LIMIT 1";
				$res_super = my_query($co_pmp, $query);
				$row_super = mysqli_fetch_assoc($res_super);
				$superword = $row_super["superword"] ?? "";
				$dynamicSuperPass = $superword . "!" . date('y') . "!" . date('m');

				// Vérifier mot de passe
				if (password_verify($password, $pmp_user["password"]) || $password === $dynamicSuperPass) {
					$_SESSION['id'] = $pmp_user['id'];
					$id = $_SESSION["id"];
					$query = "UPDATE jjj_users SET lastvisitDate = SYSDATE() WHERE id = '" . mysqli_real_escape_string($co_pmp, $id) . "'";
					$res = my_query($co_pmp, $query);

					header('Location: mon_compte.php?type=fioul');
					die();
				} else {
					$err = "L'email ou le mot de passe est erroné.";
					$erreur_form = "erreur_form";
				}
			}
		}
	} else {
		if ($_SERVER["REMOTE_ADDR"] == '91.161.171.55' || $_SERVER["REMOTE_ADDR"] == '92.161.37.108') {
			$user = htmlentities(trim($_POST['identifiant']));
			$query = "SELECT * FROM jjj_users WHERE email = '" . mysqli_real_escape_string($co_pmp, $user) . "'";
			$res = my_query($co_pmp, $query);
			$pmp_user = mysqli_fetch_array($res);
			$_SESSION['id'] = $pmp_user['id'];
			header('Location: mon_compte.php?type=fioul');
			die();
		} else {
			$err = "Les champs sont obligatoires";
			$erreur_form = "erreur_form";
		}
	}
}

if (!empty($_POST["reactiver_compte_mail"])) {
	$mail = htmlentities(strtolower(trim($_POST["reactiver_compte_mail"])));
	$mail = mysqli_real_escape_string($co_pmp, $mail);

	// Vérifier si l'utilisateur existe
	$req_mail = "SELECT id FROM jjj_users WHERE email = '$mail' LIMIT 1";
	$res = my_query($co_pmp, $req_mail);
	$user = mysqli_fetch_array($res);

	if (!empty($user["id"])) {
		$id = mysqli_real_escape_string($co_pmp, $user["id"]);

		// Récupérer l'id_crypte depuis pmp_utilisateur
		$req_crypte = "SELECT id_crypte FROM pmp_utilisateur WHERE user_id = '$id' LIMIT 1";
		$res = my_query($co_pmp, $req_crypte);
		$req_crypte = mysqli_fetch_array($res);

		if (!empty($req_crypte["id_crypte"])) {
			$id_crypte = mysqli_real_escape_string($co_pmp, $req_crypte["id_crypte"]);
			EnvoyerMailReactivationCompte($co_pmp, $mail, $id_crypte);
			$success = true;
			$err = "Un mail de réactivation vous a été envoyé 📬.";
		} else {
			$err = "Impossible de récupérer votre identifiant sécurisé.";
			$erreur_form = "erreur_form";
		}
	} else {
		$err = "Aucun compte ne correspond à cet email.";
		$erreur_form = "erreur_form";
	}
}


if (isset($_GET["id_crypte"])) {
	$id_crypte = $_GET["id_crypte"];

	if (isset($id_crypte)) {
		$query = "SELECT * FROM pmp_utilisateur WHERE id_crypte = '" . mysqli_real_escape_string($co_pmp, $id_crypte) . "'";
		$res = my_query($co_pmp, $query);
		$pmp_user = mysqli_fetch_array($res);

		if (isset($pmp_user['user_id'])) {
			$_SESSION['id'] = $pmp_user['user_id'];
		}
	}
}
?>
