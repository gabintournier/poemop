<?php

include_once __DIR__ . "/../inc/pmp_inc_fonctions_mail.php";

if (!empty($_POST["inscription_poemop"]))
{
	$cp = mysqli_real_escape_string($co_pmp, $_POST["cp_user"]);
	$mail = htmlentities(strtolower(trim($_POST["mail"])));
	$mail = mysqli_real_escape_string($co_pmp, $mail);

	//Verifier si le code postal existe
	if (!empty($cp))
	{
		$req_cp = "  SELECT * FROM pmp_code_postal WHERE code_postal = '$cp' ";
		$res = my_query($co_pmp, $req_cp);
		$req_cp = mysqli_fetch_array($res);

		if(isset($req_cp[0]))
		{
			if(strlen($req_cp[0])>0)
			{
				$cp_id = $req_cp["id"];
			}
			else
			{
				$valid = false;
				$err = "Le code postal saisi n'existe pas";
				$erreur_cp = "erreur_form";
			}
		}
		else
		{
			$valid = false;
			$err = "Le code postal saisi n'existe pas";
			$erreur_cp = "erreur_form";
		}
	}
	//Verification si le mail est déjà présent dans la bdd
	if (!empty($mail))
	{
		$req_mail = "  SELECT * FROM pmp_utilisateur WHERE email = '$mail' ";
		$res = my_query($co_pmp, $req_mail);
		$req_mail = mysqli_fetch_array($res);

		if(isset($req_mail[0]))
		{
			if(strlen($req_mail[0])>0)
			{
				$valid = false;
				$err = "Un compte est déjà existant avec cet email";
				$erreur_mail = "erreur_form";
			}
		}

		$req_mail2 = "  SELECT * FROM jjj_users WHERE email = '$mail' ";
		$res = my_query($co_pmp, $req_mail2);
		$req_mail2 = mysqli_fetch_array($res);

		if(isset($req_mail2[0]))
		{
			if(strlen($req_mail2[0])>0)
			{
				$valid = false;
				$err = "Un compte est déjà existant avec cet email ";
				$erreur_mail = "erreur_form";
			}
		}

		if(!VerifierMail($mail))
		{
			$err = "Erreur de saisie sur votre email ! " . $mail;
			$valid = false;
			$erreur_mail = "erreur_form";
		}
	}
	else
	{
		$valid = false;
		$err = "Le champs email est obligatoire";
		$erreur_mail = "erreur_form";
	}

	if (!isset($valid))
	{
		$success = true;
		$date_creation_compte = date('Y-m-d H:i:s');
		$date_creation_compte = mysqli_real_escape_string($co_pmp, $date_creation_compte);

		$query = "  INSERT INTO jjj_users (email, registerDate)
					VALUES ('$mail', '$date_creation_compte') ";
		$res = my_query($co_pmp, $query);

		if(!$res)
		{
			return false;
		}
		else
		{

			$last_id = mysqli_insert_id($co_pmp);
			$id_crypte = password_hash($last_id, PASSWORD_DEFAULT);

			$last_id = mysqli_real_escape_string($co_pmp, $last_id);
			$id_crypte = mysqli_real_escape_string($co_pmp, $id_crypte);
			$cp_id = mysqli_real_escape_string($co_pmp, $cp_id);

			$query = "  INSERT INTO pmp_utilisateur (user_id, id_crypte, code_postal, code_postal_id, email, date_creation)
			 			VALUES ('$last_id', '$id_crypte', '$cp', '$cp_id', '$mail', '$date_creation_compte')";
			$res = my_query($co_pmp, $query);
			if(!$res)
			{
				return false;
			}
			else
			{
				EnvoyerMailActivationCompte($co_pmp, $mail, $id_crypte);
			}
			return $res;

		}
	}
}

if(!empty($_POST["reset_password"]))
{

	if (isset($_POST["mail"]))
	{
		$mail = htmlentities(strtolower(trim($_POST["mail"])));
		$mail = mysqli_real_escape_string($co_pmp, $mail);
		$req_mail = "  SELECT * FROM jjj_users WHERE email = '$mail' ";
		$res = my_query($co_pmp, $req_mail);
		$req_mail = mysqli_fetch_array($res);

		if(isset($req_mail[0]))
		{
			if(strlen($req_mail[0])>0)
			{
				$id = $req_mail["id"];
				$id = mysqli_real_escape_string($co_pmp, $id);
				$req_crypte = "  SELECT * FROM pmp_utilisateur WHERE user_id = '$id' ";
				$res = my_query($co_pmp, $req_crypte);
				$req_crypte = mysqli_fetch_array($res);

				if(isset($req_crypte["id_crypte"]))
				{
					$id_crypte = $req_crypte["id_crypte"];
					$id_crypte = mysqli_real_escape_string($co_pmp, $id_crypte);
					ReinitialiserMotDePasse($co_pmp, $mail, $id_crypte);
					$success = true;
				}

			}
			else
			{
				$valid = false;
				$err = "Aucun compte ne correspond à cet email.";
				$erreur_mail = "erreur_form";
			}
		}
		else
		{
			$valid = false;
			$err = "Aucun compte ne correspond à cet email.";
			$erreur_mail = "erreur_form";
		}

	}
	else
	{
		$valid = false;
		$err = "Le champs email est obligatoire";
		$erreur_mail = "erreur_form";
	}
}

if(!empty($_POST["activer_compte"]))
{
	$mdp = trim($_POST["password_user"]);
	$confmdp = trim($_POST["confirm_password"]);

	$mdp = mysqli_real_escape_string($co_pmp, $mdp);
	$confmdp = mysqli_real_escape_string($co_pmp, $confmdp);

	//Verification du mdp = plus long que 8 caractères
	if (!empty($mdp))
	{
		if(strlen($mdp)<8)
		{
			$valid = false;
			$err = "Le mot de passe est trop court, il doit être de 8 caractères";
			$erreur_mdp = "erreur_form";
		}
	}
	else
	{
		$valid = false;
		$err = "Le champs mot de passe est obligatoire";
		$erreur_mdp = "erreur_form";
	}
	if (!empty($confmdp))
	{
		if ($confmdp !== $mdp)
		{
			$valid = false;
			$err = "Les mots de passe ne sont pas identiques";
			$erreur_mdp = "erreur_form";
		}
	}
	else
	{
		$valid = false;
		$err = "Le champs mot de passe est obligatoire";
		$erreur_mdp = "erreur_form";
	}

	if (!isset($valid))
	{
		$lastvisitDate = date('Y-m-d H:i:s');
		$id_crypte = $_GET["id_crypte"];
		$mdp =  password_hash($mdp, PASSWORD_DEFAULT);

		$id_crypte = mysqli_real_escape_string($co_pmp, $id_crypte);

		$req_user = "  SELECT * FROM pmp_utilisateur WHERE id_crypte = '$id_crypte' ";
		$res = my_query($co_pmp, $req_user);
		$req_user = mysqli_fetch_array($res);

		$lastvisitDate = mysqli_real_escape_string($co_pmp, $lastvisitDate);

		if(isset($req_user["user_id"]))
		{
			$user_id = $req_user["user_id"];

			$user_id = mysqli_real_escape_string($co_pmp, $user_id);
			$lastvisitDate = mysqli_real_escape_string($co_pmp, $lastvisitDate);

			$query = "  UPDATE jjj_users
						SET password = '$mdp', sendEmail = '1', lastvisitDate = '$lastvisitDate'
						WHERE id = '$user_id' ";
			$res = my_query($co_pmp, $query);

			$query = "  INSERT INTO pmp_inscrit (user_id)
						VALUES ('$user_id') ";
			$res = my_query($co_pmp, $query);

			if(!$res)
			{
				return false;
			}
			else
			{
				$_SESSION['id'] = $req_user['user_id'];
				header('Location: /mon_compte.php?type=fioul');
	            die();
			}
		}
	}
}

if(isset($_GET["email"]))
{
	$email = $_GET["email"];
	$lastvisitDate = date('Y-m-d H:i:s');
	$id_crypte = $_GET["id_crypte"];

	$email = mysqli_real_escape_string($co_pmp, $email);
	$lastvisitDate = mysqli_real_escape_string($co_pmp, $lastvisitDate);
	$id_crypte = mysqli_real_escape_string($co_pmp, $id_crypte);

	$req_user = "  SELECT * FROM pmp_utilisateur WHERE id_crypte = '$id_crypte' ";
	$res = my_query($co_pmp, $req_user);
	$req_user = mysqli_fetch_array($res);

	if(isset($req_user["user_id"]))
	{
		$user_id = $req_user["user_id"];

		$user_id = mysqli_real_escape_string($co_pmp, $user_id);

		$query = "  UPDATE jjj_users
					SET email = '$email'
					WHERE id = '$user_id' ";
		$res = my_query($co_pmp, $query);

		$query = "  UPDATE pmp_utilisateur
					SET email = '$email'
					WHERE user_id = '$user_id' ";
		$res = my_query($co_pmp, $query);

		if(!$res)
		{
			return false;
		}
		else
		{
			ConfirmationModificationAdresseEmail($co_pmp, $email);
		}
	}

}

if(!empty($_POST["change_password"]))
{
	$mdp = trim($_POST["password_user"]);
	$confmdp = trim($_POST["confirm_password"]);

	$mdp = mysqli_real_escape_string($co_pmp, $mdp);
	$confmdp = mysqli_real_escape_string($co_pmp, $confmdp);

	if (!empty($mdp))
	{
		if(strlen($mdp)<8)
		{
			$valid = false;
			$err = "Le mot de passe est trop court, il doit être de 8 caractères";
			$erreur_mdp = "erreur_form";
		}
	}
	else
	{
		$valid = false;
		$err = "Le champs mot de passe est obligatoire";
		$erreur_mdp = "erreur_form";
	}

	if (!empty($confmdp))
	{
		if ($confmdp !== $mdp)
		{
			$valid = false;
			$err = "Les mots de passe ne sont pas identiques";
			$erreur_mdp = "erreur_form";
		}
	}
	else
	{
		$valid = false;
		$err = "Le champs mot de passe est obligatoire";
		$erreur_mdp = "erreur_form";
	}

	if (!isset($valid))
	{
		$lastvisitDate = date('Y-m-d H:i:s');
		$id_crypte = $_GET["id_crypte"];
		$new_mdp =  password_hash($mdp, PASSWORD_DEFAULT);

		$lastvisitDate = mysqli_real_escape_string($co_pmp, $lastvisitDate);
		$id_crypte = mysqli_real_escape_string($co_pmp, $id_crypte);
		$new_mdp = mysqli_real_escape_string($co_pmp, $new_mdp);

		$req_user = "  SELECT * FROM pmp_utilisateur WHERE id_crypte = '$id_crypte' ";
		$res_user = my_query($co_pmp, $req_user);
		$req_user = mysqli_fetch_array($res_user);
		if(isset($req_user["user_id"]))
		{
			$user_id = $req_user["user_id"];

			$user_id = mysqli_real_escape_string($co_pmp, $user_id);

			$query = "  UPDATE jjj_users
						SET password = '$new_mdp', lastvisitDate = '$lastvisitDate'
						WHERE id = '$user_id' ";
			$res = my_query($co_pmp, $query);

			$req_email = "  SELECT * FROM jjj_users WHERE id = '$user_id' ";
			$res_email = my_query($co_pmp, $req_email);
			$req_email = mysqli_fetch_array($res_email);

			$email = mysqli_real_escape_string($co_pmp, $req_email["email"]);

			NouveauMotDePasse($co_pmp, $email);
			header('Location: /mon_compte.php?type=fioul');
		}
	}
}
?>
