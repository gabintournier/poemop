<?php
include_once __DIR__ . "/pmp_inc_fonctions.php";
//Afficher l'utilisateur connecté
// $query = "  SELECT * FROM pmp_utilisateur
// 			WHERE user_id = '" . $_SESSION['id'] . "' ";
// $res = my_query($co_pmp, $query);
// $utilisateur = mysqli_fetch_array($res);

function getVilleCp(&$co_pmp, $cp)
{
	$query = "  SELECT * FROM pmp_code_postal
				WHERE code_postal = '" . mysqli_real_escape_string($co_pmp, $cp)  . "' ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function ChargeCommande(&$co_pmp, $user_id, $anulee)
{
	if($anulee)
	{
		$query = "	SELECT id, cmd_status, groupe_cmd, cmd_typefuel, cmd_qte, cmd_prix_ord, cmd_prix_sup, cmd_comment
				FROM pmp_commande
				WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'
				AND cmd_status = 55
				ORDER BY id DESC ";
	}
	else
	{
		$query = "	SELECT id, cmd_status, groupe_cmd, cmd_typefuel, cmd_qte, cmd_prix_ord, cmd_prix_sup, cmd_comment
				FROM pmp_commande
				WHERE user_id='" . mysqli_real_escape_string($co_pmp, $user_id)  . "'
				AND cmd_status < 40
				ORDER BY id DESC ";
	}
	$res = my_query($co_pmp, $query);
	return mysqli_fetch_array($res);
}

function ChargeCompteJoomla(&$co_pmp, $user_id)
{
	$query = "	SELECT *
				FROM jjj_users
				WHERE id='" . mysqli_real_escape_string($co_pmp, $user_id)  . "' ";
	$res = mysqli_query($co_pmp, $query);
	return mysqli_fetch_array($res);
}

function ChargeMonCompte(&$co_pmp, $user_id)
{
	$query = 	"SELECT * ";
	$query .= 	"FROM pmp_inscrit WHERE user_id='" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";

	$res = my_query($co_pmp, $query);
	return mysqli_fetch_array($res);
}

function ChargeCompteFioul(&$co_pmp, $user_id)
{
	$query = "	SELECT *
				FROM pmp_utilisateur
				WHERE user_id='" . mysqli_real_escape_string($co_pmp, $user_id)  . "' ";
	$res = mysqli_query($co_pmp, $query);
	return mysqli_fetch_array($res);
}

function ChargeCompteElectricite(&$co_pmp, $user_id)
{
	$query = 	"SELECT * ";
	$query .= 	"FROM pmp_electricite WHERE user_id='" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";

	$res = my_query($co_pmp, $query);
	return mysqli_fetch_array($res);
}

function ChargeCompteGaz(&$co_pmp, $user_id)
{
	$query = 	"SELECT * ";
	$query .= 	"FROM pmp_gaz WHERE user_id='" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";

	$res = my_query($co_pmp, $query);
	return mysqli_fetch_array($res);
}

function ChargeCompteArtisan(&$co_pmp, $user_id)
{
	$query = 	"SELECT * ";
	$query .= 	"FROM pmp_artisan WHERE user_id='" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";

	$res = my_query($co_pmp, $query);
	return $res;
}

if(!empty($_POST["valider_coordonnees"]))
{
	$id = $_SESSION["id"];
	$id = mysqli_real_escape_string($co_pmp, $id);

	$utilisateur = ChargeCompteFioul($co_pmp, $id);
	$jjj_users = ChargeCompteJoomla($co_pmp, $_SESSION['id']);

	if(strlen($_POST["nom"]) > 0)
	{
		if(!VerifierAlpha($_POST["nom"],3,150))
		{
			$message = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message_nom = "Erreur de saisie sur votre nom ! " . mysqli_real_escape_string($co_pmp, $_POST['nom']);
			$style_nom = "rouge_form";
			$nom = "";
		}
		else
		{
			$nom = formatNom($_POST['nom']);
		}
	}

	if(strlen($_POST["prenom"])>0)
	{
		if(!VerifierAlpha($_POST["prenom"],2,50))
		{
			$message = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message_prenom = "Erreur de saisie sur votre prénom ! " . mysqli_real_escape_string($co_pmp, $_POST['prenom']);
			$style_prenom = "rouge_form";
			$prenom = "";
		}
		else
		{
			$prenom = formatPrenom($_POST['prenom']);
		}
	}

	if(strlen($_POST["adresse"]) >0)
	{
		if(!VerifierAlphaNum($_POST['adresse'],0,254))
		{
			$message = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message_adresse = "Erreur de saisie sur votre adresse ! " . mysqli_real_escape_string($co_pmp, $_POST['adresse']);
			$style_adresse = "rouge_form";
			$adresse = "";
		}
		else
		{
			$adresse = formatAdresse($_POST['adresse']);
		}
	}

	if(strlen($_POST["code_postal"]) > 0)
	{
		if(!VerifierCPx($_POST['code_postal']))
		{
			$message = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message_cp = "Erreur de saisie sur votre code postal ! " . mysqli_real_escape_string($co_pmp, $_POST['code_postal']);
			$style_code_postal = "rouge_form";
			$cp = "";
		}
		else
		{
			$cp = $_POST['code_postal'];
		}
	}

	if(isset($_POST["tel1"]))
	{
		if(!VerifierTel($_POST['tel1']))
		{
			$message = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message_telp = "Erreur de saisie sur votre tel 1 : " . mysqli_real_escape_string($co_pmp, $_POST['tel1']) . " ! <br>Si vous avez un numéro étranger, commencez votre numéro par +";
			$style_tel1 = "rouge_form";
			$tel1 = "";
		}
		else
		{
			$tel1 = formatTel($_POST['tel1']);
		}
	}

	if(isset($_POST["tel2"]))
	{
		if(!VerifierTel($_POST['tel2']))
		{
			$message = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message_telf = "Erreur de saisie sur votre tel 2 : " . mysqli_real_escape_string($co_pmp, $_POST['tel2']) . " ! <br>Si vous avez un numéro étranger, commencez votre numéro par +";
			$style_tel2 = "rouge_form";
			$tel2 = "";
		}
		else
		{
			$tel2 = formatTel($_POST['tel2']);
		}
	}

	if(strlen($_POST['tel2'])>0 && strlen($_POST['tel1'])>0)
	{
		if($_POST['tel2'] == $_POST['tel1'])
		{
			$message = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message_telf = "Merci d'indiquer un numéro de téléphone différent pour ces deux champs";
			$style_tel2 = "rouge_form";
			$tel2 = "";
			$style_tel1 = "rouge_form";
			$tel1 = "";
		}
	}

	if(isset($_POST["tel3"]))
	{
		if(!VerifierTel($_POST['tel3']))
		{
			$message = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message_tel3 = "Erreur de saisie sur votre tel 3 : " . mysqli_real_escape_string($co_pmp, $_POST['tel3']) . " ! <br>Si vous avez un numéro étranger, commencez votre numéro par +";
			$style_tel3 = "rouge_form";
			$tel3 = "";
		}
		else
		{
			$tel3 = formatTel($_POST['tel3']);
		}
	}



	if(isset($_POST['com_user']))
	{
		if(!VerifierAlphaNum($_POST['com_user'],0,1000))
		{
			$message = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message_com = "Erreur de saisie sur votre fournisseur actuel";
			$style_com_user = "rouge_form";
			$com_user = "";
		}
		else
		{
			$com_user = $_POST['com_user'];
		}
	}
	else
	{
		$com_user = "";
	}

	if(isset($_POST['com2_user']))
	{
		if(!VerifierAlphaNum($_POST['com2_user'],0,1000))
		{
			$message = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message_com_u = "Erreur de saisie sur votre manière de nous avoir trouvé !";
			$style_com2_user = "rouge_form";
			$com2_user = "";
		}
		else
		{
			$com2_user = $_POST['com2_user'];
		}
	}
	else
	{
		$com2_user = "";
	}


	if(!isset($message))
	{
		$query = "  UPDATE jjj_users
					SET name = '" . mysqli_real_escape_string($co_pmp, $nom) . "'
					WHERE id = '" . mysqli_real_escape_string($co_pmp, $id)  . "' ";
		$res = my_query($co_pmp, $query);

		if($utilisateur['user_id'] != 0)
		{
			if($utilisateur['nom'] != $_POST['nom'])
			{
				TraceHistoClient($co_pmp, $id, 'Changement de Nom', mysqli_real_escape_string($co_pmp, $utilisateur['nom']) . " --> " . mysqli_real_escape_string($co_pmp, $_POST['nom']));
			}

			if($utilisateur['prenom'] != $_POST['prenom'])
			{
				TraceHistoClient($co_pmp, $id, 'Changement de Prenom', mysqli_real_escape_string($co_pmp, $utilisateur['prenom']) . " --> " . mysqli_real_escape_string($co_pmp, $_POST['prenom']));
			}
			if($utilisateur['adresse'] != $_POST['adresse'])
			{
				TraceHistoClient($co_pmp, $id, 'Changement Adresse', mysqli_real_escape_string($co_pmp, $utilisateur['adresse']) . " --> " . mysqli_real_escape_string($co_pmp, $_POST['adresse']));
			}
			if($utilisateur['tel_fixe'] != $_POST['tel1'])
			{
				TraceHistoClient($co_pmp, $id, 'Changement Tel 1', mysqli_real_escape_string($co_pmp, $utilisateur['tel_fixe']) . " --> " . mysqli_real_escape_string($co_pmp, $_POST['tel1']));
			}
			if($utilisateur['tel_port'] != $_POST['tel2'])
			{
				TraceHistoClient($co_pmp, $id, 'Changement Tel 2', mysqli_real_escape_string($co_pmp, $utilisateur['tel_fixe']) . " --> " . mysqli_real_escape_string($co_pmp, $_POST['tel2']));
			}
			if($utilisateur['tel_3'] != $_POST['tel3'])
			{
				TraceHistoClient($co_pmp, $id, 'Changement Tel 3', mysqli_real_escape_string($co_pmp, $utilisateur['tel_3']) . " --> " . mysqli_real_escape_string($co_pmp, $_POST['tel3']));
			}

			if($utilisateur['code_postal'] != $_POST['code_postal'])
			{
				$commande = ChargeCommande($co_pmp, $id, false);

				if($commande['groupe_cmd']!=0)
				{
					EnvoyerMail("Déménagement de " . $jjj_users['email'] . " (" . $utilisateur['code_postal'] . " --> " . $_POST['code_postal'] . ")", "Groupement : " . $commande['groupe_cmd']);
					$message_a = "Vous avez modifié votre code postal. Le tarif proposé n'est peut-être pas applicable sur votre commune. Nous allons vous tenir informé au plus vite.";
				}
				TraceHistoClient($co_pmp, $id, 'Changement de CP', $utilisateur['code_postal'] . " --> " . $_POST['code_postal']);

				// $_POST['code_postal_id'] = '';
				// $query = "SELECT id, code_postal, ville FROM pmp_code_postal WHERE code_postal='" . mysqli_real_escape_string($co_pmp, $_POST['code_postal']) . "'";
				// $res = my_query($co_pmp, $query);
				// $nb_ville = 0;
				// $id_ville = '';
				// while($tab_code_postal = mysqli_fetch_array($res))
				// {
				// 	$id_ville = $tab_code_postal{'id'};
				// 	$nb_ville++;
				// }
				// if($nb_ville == 1)
				// {
				// 	$_POST['code_postal_id'] = $id_ville;
				// }
			}

			if($utilisateur['ville'] == '')
			{
				$query = "SELECT ville FROM pmp_code_postal WHERE id='" . mysqli_real_escape_string($co_pmp, $_POST['code_postal_id_test']) . "'";
				$res = my_query($co_pmp, $query);
				$pmp_code_postal = mysqli_fetch_array($res);

				$query = "  UPDATE pmp_utilisateur
							SET
							ville='" . mysqli_real_escape_string($co_pmp, $pmp_code_postal['ville']) . "',
							code_postal_id = '" . mysqli_real_escape_string($co_pmp, $_POST['code_postal_id_test']) . "'
							WHERE user_id = '$id' ";
				$res = my_query($co_pmp, $query);

				TraceHistoClient($co_pmp, $id, 'Changement de Ville', $utilisateur['ville'] . " --> " . $pmp_code_postal['ville']);
			}

			if($utilisateur['code_postal_id'] != $_POST['code_postal_id_test'])
			{
				if(strlen($_POST['code_postal_id_test']) > 0)
				{
					$query = "SELECT ville FROM pmp_code_postal WHERE id='" . mysqli_real_escape_string($co_pmp, $_POST['code_postal_id_test']) . "'";
					$res = my_query($co_pmp, $query);
					$pmp_code_postal = mysqli_fetch_array($res);

					$query = "  UPDATE pmp_utilisateur
								SET
								ville='" . mysqli_real_escape_string($co_pmp, $pmp_code_postal['ville']) . "',
								code_postal_id = '" . mysqli_real_escape_string($co_pmp, $_POST['code_postal_id_test']) . "'
								WHERE user_id = '$id' ";
					$res = my_query($co_pmp, $query);

					TraceHistoClient($co_pmp, $id, 'Changement de Ville', $utilisateur['ville'] . " --> " . $pmp_code_postal['ville']);
				}

			}

			$query = "  UPDATE pmp_utilisateur
						SET nom = '" . mysqli_real_escape_string($co_pmp, $nom) . "',
						prenom = '" . mysqli_real_escape_string($co_pmp, $prenom) . "',
						adresse = '" . mysqli_real_escape_string($co_pmp, $adresse) . "',
						code_postal = '" . mysqli_real_escape_string($co_pmp, $cp) . "',
						tel_port = '" . mysqli_real_escape_string($co_pmp, $tel2) . "',
						tel_fixe = '" . mysqli_real_escape_string($co_pmp, $tel1) . "',
						tel_3 = '" . mysqli_real_escape_string($co_pmp, $tel3) . "',
						com_user='" . mysqli_real_escape_string($co_pmp, $com_user) . "',
						com2_user='" . mysqli_real_escape_string($co_pmp, $com2_user) . "',
						actif = '3'
						WHERE user_id = '$id' ";
			$res = my_query($co_pmp, $query);
			if($res)
			{
				if(!empty($jjj_users["name"]) && !empty($utilisateur["prenom"]) && !empty($utilisateur["adresse"]) && !empty($utilisateur["ville"]) && !empty($utilisateur["code_postal"]) && !empty($utilisateur["tel_port"]) && !empty($utilisateur["prenom"]) && !empty($jjj_users["email"]))
				{
					$message = "Succès";
					$message_type = "success";
					$message_icone = "fa-check";
					$message_m = "Vos coordonnées ont bien été mises à jour";
				}

				$utilisateur = ChargeCompteFioul($co_pmp, $id);
				return $res;
			}

		}
	}
}

if(!empty($_POST["valider_mdp"]))
{
	$id = $_SESSION["id"];
	$mdp = trim($_POST["mdp"]);
	$n_mdp = trim($_POST["n_mdp"]);
	$conf_mdp = trim($_POST["conf_mdp"]);

	$query = "  SELECT *
				FROM jjj_users
				WHERE id = '" . mysqli_real_escape_string($co_pmp, $id)  . "' ";
	$res = my_query($co_pmp, $query);
	$pmp_user = mysqli_fetch_array($res);

	if(password_verify($mdp , $pmp_user["password"]))
	{
		if (!empty($n_mdp))
		{
			if(strlen($n_mdp)<8)
			{
				$valid = false;
				$message = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message_mdp = "Le mot de passe est trop court, il doit être de 8 caractères";
				$style_mdp = "rouge_form";
			}
		}
		else
		{
			$valid = false;
			$message = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message_mdp = "Le champs mot de passe est obligatoire";
			$style_mdp = "rouge_form";
		}
		if (!empty($conf_mdp))
		{
			if ($conf_mdp !== $n_mdp)
			{
				$valid = false;
				$message = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message_mdp = "Les mots de passe ne sont pas identiques";
				$style_mdp = "rouge_form";
				$style_mdp2 = "rouge_form";
			}
		}
		else
		{
			$valid = false;
			$message = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message_mdp2 = "Le champs confirmation de mot de passe est obligatoire";
			$style_mdp2 = "rouge_form";
		}

		if (!isset($valid))
		{
			$n_mdp =  password_hash($n_mdp, PASSWORD_DEFAULT);
			$query = "  UPDATE jjj_users
						SET password = '" . mysqli_real_escape_string($co_pmp, $n_mdp)  . "'
						WHERE id = '$id' ";
			$res = my_query($co_pmp, $query);

			if(!$res)
			{
				return false;
			}
			else
			{
				if(isset($pmp_user[0]))
				{
					NouveauMotDePasse($co_pmp, $pmp_user["email"]);
				}
				$message = "Succès";
				$message_type = "success";
				$message_icone = "fa-check";
				$message_m = "Votre mot de passe a bien été modifié";
			}
		}
	}
	else
	{
		$message = "Erreur";
		$message_type = "no";
		$message_icone = "fa-times";
		$message_mdpa = "Le mot de passe actuel saisi n'est pas bon";
		$style_mdpa = "rouge_form";
	}
}

if(!empty($_POST['desabonnement']))
{
	$id = $_SESSION["id"];
	$query = "  UPDATE pmp_utilisateur
				SET bloquemail = '1', date_blocage = '" . mysqli_real_escape_string($co_pmp, $_POST['date_blocage']) . "', actif='3'
				WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $id) . "' ";
	$res = my_query($co_pmp, $query);
	if($res)
	{
	$message = "Succès";
	$message_type = "success";
	$message_icone = "fa-check";
	$message_m = "Votre demande de désabonnement est bien enregistrée";
	return $res;
	}
	else
	{
		return false;
	}
}

if(isset($_POST['reabonnement']))
{
	$id = $_SESSION["id"];
	$query = "  UPDATE pmp_utilisateur SET bloquemail = '0', date_blocage = NULL, actif='3'
				WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $id) . "'";

	$res = my_query($co_pmp, $query);
	if($res)
	{
	$message = "Succès";
	$message_type = "success";
	$message_icone = "fa-check";
	$message_m = "Votre demande de réabonnement est bien enregistrée";
	return $res;
	}
	else
	{
		return false;
	}
}

if (!empty($_POST['envoyer_artisan']))
{
	$user_id = $_SESSION['id'];
	$jjj_users = ChargeCompteJoomla($co_pmp, $user_id);
	$compte_artisan = ChargeCompteArtisan($co_pmp, $user_id);
	$compte =  mysqli_fetch_array($compte_artisan);

	if(!VerifierAlpha($_POST['type'],3,150))
	{
		$message = "Erreur";
		$message_type = "no";
		$message_icone = "fa-times";
		$message_m = "Erreur de saisie sur le type d'activité ! " . htmlspecialchars($_POST['type']);
		$valid = false;
	}

	if(!VerifierAlpha($_POST['raison_social'],3,150))
	{
		$message = "Erreur";
		$message_type = "no";
		$message_icone = "fa-times";
		$message_m = "Erreur de saisie sur le nom ! " . htmlspecialchars($_POST['raison_social']);
		$valid = false;
	}

	if (!isset($valid)) {
		$nom =  htmlspecialchars($_POST['raison_social']);
		$type =  htmlspecialchars($_POST['type']);
		$nom = addslashes($_POST['raison_social']);
		$type = addslashes($_POST['type']);
		$query = "  INSERT INTO pmp_artisan (nom, type, user_id)
					VALUES ('$nom', '$type', '" . mysqli_real_escape_string($co_pmp, $user_id)  . "') ";
		$res = my_query($co_pmp, $query);
		if(!$res)
		{
			return false;
		}
		else
		{
			$message = "Succès";
			$message_type = "success";
			$message_icone = "fa-check";
			$message_m = "Vous venez d'ajouter un artisan";
		}
	}
}

if(!empty($_POST["valider_email"]))
{
	$id = $_SESSION["id"];
	$mail = htmlspecialchars($_POST['n_mail']);

	if (!empty($mail))
	{
		$req_mail = "  SELECT * FROM pmp_utilisateur WHERE email = '" . mysqli_real_escape_string($co_pmp, $mail)  . "' ";
		$res = my_query($co_pmp, $req_mail);
		$req_mail = mysqli_fetch_array($res);

		if(isset($req_mail[0]))
		{
			if(strlen($req_mail[0])>0)
			{
				$message = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message_mail = "Un compte est déjà existant avec cet email";
				$valid = false;
				$style_mail = "rouge_form";
			}
		}

		$req_mail2 = "  SELECT * FROM jjj_users WHERE email = '" . mysqli_real_escape_string($co_pmp, $mail)  . "' ";
		$res = my_query($co_pmp, $req_mail2);
		$req_mail2 = mysqli_fetch_array($res);

		if(isset($req_mail2[0]))
		{
			if(strlen($req_mail2[0])>0)
			{
				$message = "Erreur";
				$message_type = "no";
				$message_icone = "fa-times";
				$message_mail = "Un compte est déjà existant avec cet email";
				$valid = false;
				$style_mail = "rouge_form";
			}
		}

		if(!VerifierMail($_POST['n_mail']))
		{
			$message = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message_mail = "Erreur de saisie sur votre email ! " . htmlspecialchars($_POST['n_email']);
			$valid = false;
			$style_mail = "rouge_form";
		}
	}
	else
	{
		$message = "Erreur";
		$message_type = "no";
		$message_icone = "fa-times";
		$message_mail = "Le champs email est obligatoire";
		$valid = false;
		$style_mail = "rouge_form";
	}

	if(!isset($valid))
	{
		$user = ChargeCompteFioul($co_pmp, $id);
		ModifierAdresseEmail($co_pmp, $_POST['n_mail'], $user["id_crypte"]);
		$message = "Succès";
		$message_type = "success";
		$message_icone = "fa-check";
		$message_m = "Vous allez recevoir un email sur cette nouvelle adresse pour valider la modification";
	}
}




function GestionCompteFioul(&$co_pmp, $inscrit, $user_id)
{
	// On charge le compte
	$pmp_utilisateur = ChargeCompteFioul($co_pmp, $user_id);

	// Si il a un compte fioul
	if(strlen($pmp_utilisateur['user_id']) >= 1)
	{
		// Si il s'inscrit ou se desinscrit
		if(strlen($inscrit) == 1)
		{
			$query = "	UPDATE pmp_utilisateur SET
				inscrit='" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
				WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
			$res = mysqli_query($co_pmp, $query);
		}
		// Sinon ($inscrit == "") on ne touche pas son compte
	}
	// Sinon (il n'à pas de compte fioul)
	else
	{
		// si ($inscrit == 0) il veux se désinscrire on ne fait rien // Pas possible

		// ... et qu'il veux s'inscrire
		if($inscrit == 1)
		{
			// On INSERT
			$query = "	INSERT INTO pmp_utilisateur (user_id, inscrit)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = mysqli_query($co_pmp, $query);
		}
		// Il s'est inscrit sur l'elec (ou le gaz)
		if($inscrit == "")
		{
			// On INSERT
			$query = "	INSERT INTO pmp_utilisateur (user_id, inscrit)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '0')";
			$res = mysqli_query($co_pmp, $query);
		}
	}

	return;
}

function GestionCompteElec(&$co_pmp, $inscrit, $user_id)
{
	// On charge le compte
	$pmp_electricite = ChargeCompteElectricite($co_pmp, $user_id);
	$jjj_users = ChargeCompteJoomla($co_pmp, $user_id);

	// Si il a un compte elec
	if(strlen($pmp_electricite['user_id']) >= 1)
	{
		// Si il s'inscrit ou se desinscrit
		if(strlen($inscrit) == 1)
		{
			$query = "	UPDATE pmp_electricite SET
				inscrit='" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
				WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
			$res = my_query($co_pmp, $query);
		}
		// Sinon ($inscrit == "") on ne touche pas son compte
	}
	// Sinon (il n'à pas de compte elec)
	else
	{
		// si ($inscrit == 0) il veux se désinscrire on ne fait rien // Pas possible

		// ... et qu'il veux s'inscrire
		if($inscrit == 1)
		{
			// On INSERT
			$query = "	INSERT INTO pmp_electricite (user_id, inscrit)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = my_query($co_pmp, $query);

			// INSERT le compte fioul (si il est vide)
			GestionCompteFioul($co_pmp, "", $user_id);
		}
		// Il s'est inscrit sur le gaz
		if($inscrit == "")
		{
			// On crée un compte elec juste pour stocker les info
			$query = "	INSERT INTO pmp_electricite (user_id, inscrit)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '0')";
			$res = my_query($co_pmp, $query);

			// INSERT le compte fioul (si il est vide)
			GestionCompteFioul($co_pmp, "", $user_id);
		}

	}

	return;
}

function GestionCompteGaz(&$co_pmp, $inscrit, $user_id)
{
	// On charge le compte
	$pmp_gaz = ChargeCompteGaz($co_pmp, $user_id);
	$pmp_electricite = ChargeCompteElectricite($co_pmp, $user_id);
	$jjj_users = ChargeCompteJoomla($co_pmp, $user_id);

	// Si il a un compte gaz
	if(strlen($pmp_gaz['user_id']) >= 1)
	{
		$query = "	UPDATE pmp_gaz SET
			inscrit='" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
			WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
		$res = my_query($co_pmp, $query);
	}
	// Sinon (il n'à pas de compte fioul)
	else
	{
		// ... et qu'il veux se désinscrire on ne fait rien // Pas possible

		// ... et qu'il veux s'inscrire
		if($inscrit == 1)
		{
			// On INSERT le compte gaz
			$query = "	INSERT INTO pmp_gaz (user_id, inscrit)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = my_query($co_pmp, $query);

			// INSERT le compte elec (si il est vide)
			GestionCompteElec($co_pmp, "", $user_id);
		}
	}

	return;
}

function GestionCompte(&$co_pmp, $inscrit, $user_id)
{
	// On charge le compte
	$pmp_compte = ChargeMonCompte($co_pmp, $user_id);
	$jjj_users = ChargeCompteJoomla($co_pmp, $user_id);

	if ($_POST['produit'] == 4) {
		if(strlen($pmp_compte['user_id']) >= 1)
		{
			// Si il s'inscrit ou se desinscrit
			if(strlen($inscrit) == 1)
			{
				$query = "	UPDATE pmp_inscrit SET
					artisan = '" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
					WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
				$res = my_query($co_pmp, $query);
			}
		}
		else
		{
			$query = "	INSERT INTO pmp_inscrit (user_id, artisan)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = my_query($co_pmp, $query);
		}
	}

	if ($_POST['produit'] == 5) {
		if(strlen($pmp_compte['user_id']) >= 1)
		{
			// Si il s'inscrit ou se desinscrit
			if(strlen($inscrit) == 1)
			{
				$query = "	UPDATE pmp_inscrit SET
					produit_2 = '" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
					WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
				$res = my_query($co_pmp, $query);
			}
		}
		else
		{
			$query = "	INSERT INTO pmp_inscrit (user_id, produit_2)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = my_query($co_pmp, $query);
		}
	}

	if ($_POST['produit'] == 6) {
		if(strlen($pmp_compte['user_id']) >= 1)
		{
			// Si il s'inscrit ou se desinscrit
			if(strlen($inscrit) == 1)
			{
				$query = "	UPDATE pmp_inscrit SET
					produit_3 = '" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
					WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
				$res = my_query($co_pmp, $query);
			}
		}
		else
		{
			$query = "	INSERT INTO pmp_inscrit (user_id, produit_3)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = my_query($co_pmp, $query);
		}
	}

	if ($_POST['produit'] == 7) {
		if(strlen($pmp_compte['user_id']) >= 1)
		{
			// Si il s'inscrit ou se desinscrit
			if(strlen($inscrit) == 1)
			{
				$query = "	UPDATE pmp_inscrit SET
					produit_4 = '" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
					WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
				$res = my_query($co_pmp, $query);
			}
		}
		else
		{
			$query = "	INSERT INTO pmp_inscrit (user_id, produit_4)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = my_query($co_pmp, $query);
		}
	}

	if ($_POST['produit'] == 8) {
		if(strlen($pmp_compte['user_id']) >= 1)
		{
			// Si il s'inscrit ou se desinscrit
			if(strlen($inscrit) == 1)
			{
				$query = "	UPDATE pmp_inscrit SET
					produit_5 = '" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
					WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
				$res = my_query($co_pmp, $query);
			}
		}
		else
		{
			$query = "	INSERT INTO pmp_inscrit (user_id, produit_5)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = my_query($co_pmp, $query);
		}
	}

	if ($_POST['produit'] == 9) {
		if(strlen($pmp_compte['user_id']) >= 1)
		{
			// Si il s'inscrit ou se desinscrit
			if(strlen($inscrit) == 1)
			{
				$query = "	UPDATE pmp_inscrit SET
					produit_6 = '" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
					WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
				$res = my_query($co_pmp, $query);
			}
		}
		else
		{
			$query = "	INSERT INTO pmp_inscrit (user_id, produit_6)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = my_query($co_pmp, $query);
		}
	}

	if ($_POST['produit'] == 10) {
		if(strlen($pmp_compte['user_id']) >= 1)
		{
			// Si il s'inscrit ou se desinscrit
			if(strlen($inscrit) == 1)
			{
				$query = "	UPDATE pmp_inscrit SET
					produit_7 = '" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
					WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
				$res = my_query($co_pmp, $query);
			}
		}
		else
		{
			$query = "	INSERT INTO pmp_inscrit (user_id, produit_7)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = my_query($co_pmp, $query);
		}
	}

	if ($_POST['produit'] == 11) {
		if(strlen($pmp_compte['user_id']) >= 1)
		{
			// Si il s'inscrit ou se desinscrit
			if(strlen($inscrit) == 1)
			{
				$query = "	UPDATE pmp_inscrit SET
					produit_8 = '" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
					WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
				$res = my_query($co_pmp, $query);
			}
		}
		else
		{
			$query = "	INSERT INTO pmp_inscrit (user_id, produit_8)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = my_query($co_pmp, $query);
		}
	}

	if ($_POST['produit'] == 12) {
		if(strlen($pmp_compte['user_id']) >= 1)
		{
			// Si il s'inscrit ou se desinscrit
			if(strlen($inscrit) == 1)
			{
				$query = "	UPDATE pmp_inscrit SET
					produit_9 = '" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
					WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
				$res = my_query($co_pmp, $query);
			}
		}
		else
		{
			$query = "	INSERT INTO pmp_inscrit (user_id, produit_9)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = my_query($co_pmp, $query);
		}
	}

	if ($_POST['produit'] == 13) {
		if(strlen($pmp_compte['user_id']) >= 1)
		{
			// Si il s'inscrit ou se desinscrit
			if(strlen($inscrit) == 1)
			{
				$query = "	UPDATE pmp_inscrit SET
					produit_10 = '" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
					WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
				$res = my_query($co_pmp, $query);
			}
		}
		else
		{
			$query = "	INSERT INTO pmp_inscrit (user_id, produit_10)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = my_query($co_pmp, $query);
		}
	}

	if ($_POST['produit'] == 14) {
		if(strlen($pmp_compte['user_id']) >= 1)
		{
			// Si il s'inscrit ou se desinscrit
			if(strlen($inscrit) == 1)
			{
				$query = "	UPDATE pmp_inscrit SET
					produit_11 = '" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
					WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
				$res = my_query($co_pmp, $query);
			}
		}
		else
		{
			$query = "	INSERT INTO pmp_inscrit (user_id, produit_11)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = my_query($co_pmp, $query);
		}
	}

	if ($_POST['produit'] == 15) {
		if(strlen($pmp_compte['user_id']) >= 1)
		{
			// Si il s'inscrit ou se desinscrit
			if(strlen($inscrit) == 1)
			{
				$query = "	UPDATE pmp_inscrit SET
					produit_12 = '" . mysqli_real_escape_string($co_pmp, $inscrit)  . "'
					WHERE user_id = '" . mysqli_real_escape_string($co_pmp, $user_id)  . "'";
				$res = my_query($co_pmp, $query);
			}
		}
		else
		{
			$query = "	INSERT INTO pmp_inscrit (user_id, produit_12)
						VALUES ('" . mysqli_real_escape_string($co_pmp, $user_id)  . "', '1')";
			$res = my_query($co_pmp, $query);
		}
	}

}

if(isset($_POST["supp_compte"]))
{
	$id = $_SESSION["id"];
	$id = mysqli_real_escape_string($co_pmp, $id);

	$supp_compte = "DELETE FROM jjj_users where id  = '$id' ";
	$reponse = my_query($co_pmp, $supp_compte);
	$supp_compte = "DELETE FROM pmp_utilisateur WHERE user_id = '$id'";
	$reponse = my_query($co_pmp, $supp_compte);
	$supp_compte = "DELETE FROM pmp_commande WHERE user_id = '$id'";
	$reponse = my_query($co_pmp, $supp_compte);

	session_start(); // demarrage de la session
	session_destroy(); // on détruit la/les session(s), soit si vous utilisez une autre session, utilisez de préférence le unset()
	header('Location: /'); // On redirige
	die();
}

if (isset($_POST["desac_compte"])) {
    session_start();

    $id = $_SESSION["id"];
    $id = mysqli_real_escape_string($co_pmp, $id);

    // Blocage de la désactivation si une commande est au statut 20 (Prix validé) ou 25 (Livrable)
    $res_block = my_query($co_pmp, "
        SELECT cmd_status
        FROM pmp_commande
        WHERE user_id = '$id'
          AND cmd_status IN (20, 25)
          AND cmd_status NOT IN (50, 52, 55, 99)
        ORDER BY cmd_status DESC
        LIMIT 1
    ");
    if ($res_block && mysqli_num_rows($res_block) > 0) {
        $row_block = mysqli_fetch_assoc($res_block);
        $st_block = intval($row_block['cmd_status']);
        header('Location: /parametres_compte.php?desac_block=1&statut=' . $st_block);
        exit;
    }

    // 🕒 Date actuelle
    $disabled_date = date('Y-m-d H:i:s');

    // ⚙️ Récupération email + id_crypte
    $query_user = "
        SELECT j.email, u.id_crypte, u.prenom, u.nom
        FROM jjj_users j
        JOIN pmp_utilisateur u ON j.id = u.user_id
        WHERE j.id = '$id'
        LIMIT 1
    ";
    $res_user = my_query($co_pmp, $query_user);
    $user = mysqli_fetch_assoc($res_user);

    $user_email = $user['email'] ?? '';
    $id_crypte = $user['id_crypte'] ?? '';
    $prenom = $user['prenom'] ?? '';
    $nom = $user['nom'] ?? '';

    // ⚙️ Désactivation du compte
    my_query($co_pmp, "
        UPDATE pmp_utilisateur 
        SET disabled_account = 1, disabled_date = '$disabled_date' 
        WHERE user_id = '$id'
    ");

    // Historiser l'ancien groupement avant détachement des commandes actives
    $__trace_detach = [];
    $__res_old = my_query($co_pmp, "
        SELECT id, groupe_cmd
        FROM pmp_commande
        WHERE user_id = '$id'
          AND cmd_status <= 17
          AND cmd_status NOT IN (50, 52, 55, 99)
          AND groupe_cmd <> 0
    ");
    while ($__r = mysqli_fetch_array($__res_old)) {
        $__cid = intval($__r['id']);
        $__old = intval($__r['groupe_cmd']);
        $__val = mysqli_real_escape_string($co_pmp, $__old . ' (commande détachée)');
        $__trace_detach[] = "($__cid, 'site', NOW(), 'Ancien groupement', '$__val')";
    }
    if (!empty($__trace_detach)) {
        my_query($co_pmp, "
            INSERT INTO pmp_commande_histo (cmd_id, his_intervenant, his_date, his_action, his_valeur)
            VALUES " . implode(',', $__trace_detach)
        );
    }

	// ⚙️ Blocage des commandes actives jusqu'à "Prix proposé" inclus
	my_query($co_pmp, "
	    UPDATE pmp_commande 
	    SET cmd_status = 99, groupe_cmd = 0
	    WHERE user_id = '$id' 
	      AND cmd_status <= 17 
	      AND cmd_status NOT IN (50, 52, 55, 99)
	");

    // ⚙️ Désactivation Joomla
    my_query($co_pmp, "
        UPDATE jjj_users 
        SET block = 1 
        WHERE id = '$id'
    ");

	// 🧾 Historisation des commandes désactivées
	if (function_exists('TraceHistoCmd')) {
	    // Récupère les commandes modifiées (pour tracer individuellement)
	    $res_cmds = my_query($co_pmp, "
	        SELECT id, cmd_status 
	        FROM pmp_commande 
	        WHERE user_id = '$id' 
	          AND cmd_status = 99
	    ");

	    while ($cmd = mysqli_fetch_assoc($res_cmds)) {
	        $cmd_id = $cmd['id'];
	        $old_status = $cmd['cmd_status'];
	        $commentaire = '99 - Commande annulée (compte désactivé)';
	        TraceHistoCmd($co_pmp, $cmd_id, 'Statut', $commentaire);
	    }
	}

    // 🗂️ Enregistrement dans l’historique client
    // Filet de sécurité: historisation batch et purge des prix pour commandes à 99
    my_query($co_pmp, "
        UPDATE pmp_commande
        SET cmd_prix_ord = NULL, cmd_prix_sup = NULL
        WHERE user_id = '$id'
          AND cmd_status = 99
          AND (cmd_prix_ord IS NOT NULL OR cmd_prix_sup IS NOT NULL)
    ");
    $__ids_hist = [];
    $__res_hist = my_query($co_pmp, "
        SELECT id FROM pmp_commande
        WHERE user_id = '$id'
          AND cmd_status = 99
    ");
    while ($__r = mysqli_fetch_array($__res_hist)) { $__ids_hist[] = intval($__r['id']); }
    if (!empty($__ids_hist)) {
        $__vals = [];
        foreach ($__ids_hist as $__cid) {
            $__vals[] = "($__cid, 'site', NOW(), 'Statut', '99 - Annulée / Compte désactivé')";
        }
        my_query($co_pmp, "
            INSERT INTO pmp_commande_histo (cmd_id, his_intervenant, his_date, his_action, his_valeur)
            VALUES " . implode(',', $__vals)
        );
    }

    if (function_exists('TraceHistoClient')) {
        $texteHisto = "Le compte de {$user_email} a été désactivé";
        TraceHistoClient($co_pmp, $id, "Désactivation du compte", $texteHisto);
    }

    // 📧 Envoi du mail de confirmation
    if (!empty($user_email) && !empty($id_crypte)) {
        include_once __DIR__ . "/pmp_inc_fonctions_mail.php";
        EnvoyerMailDesactivationCompte($co_pmp, $user_email, $id_crypte, $disabled_date);
    }

    // 🔥 Supprimer la session
    session_unset();
    session_destroy();

    // ✅ Redirection vers index avec message GET
    header('Location: /index.php?toast=success&title=Compte%20désactivé&msg=Votre%20compte%20a%20bien%20été%20désactivé.%20Vous%20pourrez%20le%20réactiver%20en%20vous%20reconnectant.');
    exit;
}



function dateUS2Texte($dateus)
{
	if(strlen($dateus)>0)
	{
		$jour = $dateus[8].$dateus[9];
		$mois = $dateus[5].$dateus[6];
		$an   = $dateus[0].$dateus[1].$dateus[2].$dateus[3];
		if($mois == "01")
			$mois = "Janvier";
		if($mois == "02")
			$mois = "Février";
		if($mois == "03")
			$mois = "Mars";
		if($mois == "04")
			$mois = "Avril";
		if($mois == "05")
			$mois = "Mai";
		if($mois == "06")
			$mois = "Juin";
		if($mois == "07")
			$mois = "Juillet";
		if($mois == "08")
			$mois = "Aout";
		if($mois == "09")
			$mois = "Septembre";
		if($mois == "10")
			$mois = "Octobre";
		if($mois == "11")
			$mois = "Novembre";
		if($mois == "12")
			$mois = "Décembre";
	}
	return $jour . " " . $mois . " " . $an;
}

// --- FONCTIONS GESTION NOTIFS MAILS --- //// --- FONCTIONS GESTION NOTIFS MAILS --- //
function handleMailGroupementActions($co_pmp, $user_id, $action): ?array {
    
	if (!$user_id) return null;

    if (!empty($_POST['desabonnement'])) {
        $result = desabonnementTemporaire($co_pmp, $user_id, $_POST['date_blocage'] ?? '');
        $_SESSION['message']       = $result['message'];
        $_SESSION['message_type']  = $result['type'];
        $_SESSION['message_icone'] = $result['icone'];
        $_SESSION['message_m']     = null;
        $_SESSION['temp_desabonne']= true;

        header("Location: mail_groupement.php?actionNotifGroupement=desinscription");
        exit;
    }

    if (in_array($action, ['inscription', 'desinscription'])) {
        return majEtatNotifsMails($co_pmp, $user_id, $action === 'desinscription');
    }

    if (!empty($_POST['reabonnement'])) {
        return reabonnement($co_pmp, $user_id);
    }

    return null;
}

function majEtatNotifsMails($co_pmp, int $user_id, bool $desinscrire): array {
    // On récupère l'état actuel
    $res = mysqli_query($co_pmp, "SELECT bloquemail FROM pmp_utilisateur WHERE user_id = '$user_id'");
    $ancien = $res && mysqli_num_rows($res) ? mysqli_fetch_assoc($res)['bloquemail'] : null;

    $new_state = $desinscrire ? 1 : 0;
    $sql = "UPDATE pmp_utilisateur SET bloquemail = ?, date_blocage = NULL WHERE user_id = ?";
    $stmt = $co_pmp->prepare($sql);
    $ok = $stmt->execute([$new_state, $user_id]);

    if ($ok) {
        // Historisation
        if ($ancien != $new_state) {
            $action = $desinscrire
                ? "Notifications groupements"
                : "Notifications groupements";
            TraceHistoClient(
                $co_pmp,
                $user_id,
                $action,
                ($ancien == 1 ? "Désactivées" : "Activées") . " → " . ($new_state == 1 ? "Désactivées" : "Activées")
            );
        }
    }

    return $ok
        ? [
            'info' => 'Notification',
            'type' => 'success',
            'icone' => 'fa-check',
            'message' => $desinscrire
                ? 'Vous avez été désinscrit avec succès de nos notifications de groupements.'
                : 'Vous êtes de nouveau inscrit à nos notifications de groupements.'
        ]
        : [
            'info' => 'Erreur',
            'type' => 'no',
            'icone' => 'fa-times',
            'message' => 'Une erreur est survenue, veuillez réessayer.'
        ];
}

function desabonnementTemporaire($co_pmp, int $user_id, string $date_blocage): array {
    if (!$date_blocage) {
        return [
            'info' => 'Erreur',
            'type' => 'no',
            'icone' => 'fa-times',
            'message' => 'Date de blocage manquante.'
        ];
    }

    // Mise à jour de l'utilisateur
    $stmt = $co_pmp->prepare("UPDATE pmp_utilisateur SET bloquemail = '1', date_blocage = ?, actif = '3' WHERE user_id = ?");
    $stmt->bind_param("si", $date_blocage, $user_id);
    $ok = $stmt->execute();

    if ($ok) {
        // Récupérer la dernière ligne de l’historique
      $stmtH = $co_pmp->prepare("
	    SELECT id, hisu_valeur 
	    FROM pmp_utilisateur_histo 
	    WHERE user_id = ? AND hisu_action IN ('Désinscription temporaire','Notifications groupements')
	    ORDER BY hisu_date DESC 
	    LIMIT 1
	");

        $stmtH->bind_param("i", $user_id);
        $stmtH->execute();
        $result = $stmtH->get_result(); // -> mysqli_result
        $lastHisto = $result->fetch_assoc(); // ok maintenant

		$texteHisto = "Désactivées jusqu’au " . dateUS2Texte($date_blocage);

        if ($lastHisto) {
            // Update de la ligne existante
            $stmtU = $co_pmp->prepare("
                UPDATE pmp_utilisateur_histo 
                SET hisu_action = 'Notifications groupements', hisu_valeur = ? 
                WHERE id = ?
            ");
            $stmtU->bind_param("si", $texteHisto, $lastHisto['id']);
            $stmtU->execute();
        } else {
            // Sinon créer une nouvelle ligne
            TraceHistoClient($co_pmp, $user_id, "Notifications groupements", $texteHisto);
        }
    }

    return $ok
        ? [
            'info' => 'Succès',
            'type' => 'success',
            'icone' => 'fa-check',
            'message' => "Les notifications de groupement ont été désactivées jusqu'au " . dateUS2Texte($date_blocage)
        ]
        : [
            'info' => 'Erreur',
            'type' => 'no',
            'icone' => 'fa-times',
            'message' => 'Une erreur est survenue lors de la demande de Notifications groupements.'
        ];
}



function reabonnement($co_pmp, int $user_id): array {
    // On récupère l’ancien état avant modif
    $res = mysqli_query($co_pmp, "SELECT bloquemail, date_blocage FROM pmp_utilisateur WHERE user_id = '$user_id'");
    $ancien = $res && mysqli_num_rows($res) ? mysqli_fetch_assoc($res) : null;

    $query = "UPDATE pmp_utilisateur SET bloquemail = '0', date_blocage = NULL, actif = '3' WHERE user_id = ?";
    $stmt = $co_pmp->prepare($query);
    $ok = $stmt->execute([$user_id]);

    if ($ok) {
        TraceHistoClient(
            $co_pmp,
            $user_id,
            "Notifications groupements",
            "Ancien état : " . json_encode($ancien) . " → Réabonné"
        );
    }

    return $ok
        ? [
            'info' => 'Succès',
            'type' => 'success',
            'icone' => 'fa-check',
            'message' => 'Votre réabonnement est bien enregistré.'
        ]
        : [
            'info' => 'Erreur',
            'type' => 'no',
            'icone' => 'fa-times',
            'message' => 'Une erreur est survenue lors de votre réabonnement.'
        ];
}


function EnregistrerDesinscription($co_pmp, $user_id, $raison, $commentaire) {
    $raison = mysqli_real_escape_string($co_pmp, trim($raison));
    $commentaire = mysqli_real_escape_string($co_pmp, trim($commentaire));

    $query = "
        UPDATE pmp_utilisateur
        SET raison_desinscription = '$raison',
            commentaire_desinscription = '$commentaire'
        WHERE user_id = '$user_id'
    ";
    $ok = mysqli_query($co_pmp, $query);

    if ($ok) {
        // Historique simple : juste ce que l'utilisateur a choisi
        TraceHistoClient(
            $co_pmp,
            $user_id,
            "Feedback désinscription",
            "Raison : $raison | Commentaire : $commentaire"
        );
    }

    return $ok;
}

/* Partie désinscription via MAIL */ 
function getUserIdByCrypte($co_pmp, $id_crypte) {
    $id_crypte = mysqli_real_escape_string($co_pmp, $id_crypte);
    $query = "SELECT user_id FROM pmp_utilisateur WHERE id_crypte = '$id_crypte' LIMIT 1";
    $res = my_query($co_pmp, $query);
    if ($row = mysqli_fetch_assoc($res)) {
        return $row['id'];
    }
    return null;
}

function GetDesinscriptionData($co_pmp, $user_id) {
    $query = "SELECT date_blocage, raison_desinscription, commentaire_desinscription FROM pmp_utilisateur WHERE user_id = '$user_id' LIMIT 1";
    $res = mysqli_query($co_pmp, $query);
    if ($res && mysqli_num_rows($res) > 0) {
        return mysqli_fetch_assoc($res);
    }
    return ['raison_desinscription' => '', 'commentaire_desinscription' => ''];
}
// ==========================================
// Réactivation de compte via lien mail
// ==========================================
if (
    isset($_GET['action']) 
    && $_GET['action'] === 'reactiver_compte'
    && !empty($_GET['id_crypte'])
) {
    include_once __DIR__ . "/pmp_co_connect.php";

    $id_crypte = mysqli_real_escape_string($co_pmp, $_GET['id_crypte']);

    // 🔍 Vérifie si le compte existe
    $query = "
        SELECT u.user_id, u.disabled_account, j.email
        FROM pmp_utilisateur u
        LEFT JOIN jjj_users j ON j.id = u.user_id
        WHERE u.id_crypte = '$id_crypte'
        LIMIT 1
    ";
    $res = my_query($co_pmp, $query);
    $user = mysqli_fetch_assoc($res);

    // 🟥 Cas 1 : lien invalide ou compte introuvable
    if (!$user) {
        header("Location: /index.php?toast=no&title=Erreur&msg=Lien%20invalide%20ou%20compte%20introuvable.");
        exit;
    }

    $user_id = intval($user['user_id']);
    $user_email = $user['email'] ?? '';

    // ==========================================
    // ✅ Réactivation du compte
    // ==========================================
    my_query($co_pmp, "
        UPDATE pmp_utilisateur
        SET 
            disabled_account = 0, 
            disabled_date = NULL,
            rappel_suppression_envoye = 0  -- 🔄 reset du flag RGPD
        WHERE user_id = '$user_id'
    ");

    my_query($co_pmp, "
        UPDATE jjj_users
        SET block = 0
        WHERE id = '$user_id'
    ");

    // 🗂️ Historique
    $texteHisto = "Le compte de {$user_email} a été (ré)activé via le lien de réactivation envoyé par mail.";
    TraceHistoClient($co_pmp, $user_id, "Réactivation du compte", $texteHisto);

    // ✅ Redirection propre
    header("Location: /index.php?toast=success&title=Succès&msg=Votre%20compte%20est%20actif%20🎉%20Vous%20pouvez%20désormais%20vous%20reconnecter.");
    exit;
}
