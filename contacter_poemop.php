

<?php

include_once 'inc/dev_auth.php';

session_start();
//*** Securisation du formulaire
// On detecte la recharge par F5 (par exemple) dans une meme session
$recharge = TRUE;
$RequestSignature = md5($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].print_r($_POST, true));
if($_SESSION['LastRequest'] != $RequestSignature)
{
	$_SESSION['LastRequest'] = $RequestSignature;
	$recharge = FALSE;
}
// On detecte le token du form et le token de la session sont identique
if(isset($_SESSION['token']) AND isset($_POST['token']) AND !empty($_SESSION['token']) AND !empty($_POST['token']))
{
    if ($_SESSION['token'] == $_POST['token'])
	{
		$recharge = FALSE;
    }
}

$desc = 'Contactez POEMOP pour vos commandes de fioul. Nous sommes disponible par email et par téléphone du lundi au vrendredi.';
$title = 'Comment contacter POEMOP ?';
ob_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

include_once __DIR__ . "/inc/recaptchalib.php";
include_once __DIR__ . "/inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions.php";


$siteKey = '6Lf36wgkAAAAAGUHUGdkLX7KdPoJYmsNufROzPQD';
$secret = '6Lf36wgkAAAAAAP7Qi4pZ7Qza9IUG00g6rmo5Hnw';

$reCaptcha = new ReCaptcha($secret);
if(!$recharge)
{
	if(isset($_POST["g-recaptcha-response"]))
	{
	    $resp = $reCaptcha->verifyResponse(
	        $_SERVER["REMOTE_ADDR"],
	        $_POST["g-recaptcha-response"]
	        );
	    if ($resp != null && $resp->success)
		{
			if(!empty($_POST["envoyer_message"]))
			{
				if(!empty($_POST['email_user']))
				{
					if(!VerifierMail($_POST['email_user']))
					{
						$message_info = "Erreur";
						$message_type = "no";
						$message_icone = "fa-times";
						$message = "Erreur de saisie sur votre adresse email : " . $_POST['email_user'] . ".";
						$valid = false;
						$erreur_mail = true;
					}
				}
				else
				{
					$message_info = "Erreur";
					$message_type = "no";
					$message_icone = "fa-times";
					$message = "L'email est obligatoire.";
					$valid = false;
					$erreur_mail = true;
				}
				if(!empty($_POST['cp_user']))
				{
					if(!VerifierCP($co_pmp, $_POST['cp_user']))
					{
						$message_info = "Erreur";
						$message_type = "no";
						$message_icone = "fa-times";
						$message = "Erreur de saisie sur votre code postal : " . $_POST['cp_user'] . ".";
						$valid = false;
						$erreur_cp = true;
					}
				}
				else
				{
					$message_info = "Erreur";
					$message_type = "no";
					$message_icone = "fa-times";
					$message = "Le code postal est obligatoire.";
					$valid = false;
					$erreur_cp = true;
				}
				if(!empty($_POST['message_user']))
				{
					if(!VerifierTaille($_POST['message_user'],20,2000))
					{
						$message_info = "Erreur";
						$message_type = "no";
						$message_icone = "fa-times";
						$message = "Le message doit faire entre 20 et 2 000 caractères.";
						$valid = false;
						$erreur_msg = true;
					}
					if(!VerifierTaille($_POST['message_user'],0,1000))
					{
						$message_info = "Erreur";
						$message_type = "no";
						$message_icone = "fa-times";
						$message = "Seul les caractères et la ponctuation simple est autorisée.";
						$valid = false;
						$erreur_msg = true;
					}
				}
				else
				{
					$message_info = "Erreur";
					$message_type = "no";
					$message_icone = "fa-times";
					$message = "Le message est obligatoire.";
					$valid = false;
					$erreur_msg = true;
				}
				$msg = $_POST['message_user'];
				if (!isset($valid))
				{
					EnvoyerMailMessageContact($co_pmp, $_POST['email_user'], $_POST['cp_user'], $msg);
					$message_info = "Succès";
					$message_type = "success";
					$message_icone = "fa-check";
					$message = "Votre mail à bien été envoyé à l'équipe POEMOP";
				}

			}
		}
	    else
		{
			$message_info = "Erreur";
			$message_type = "no";
			$message_icone = "fa-times";
			$message = "CAPTCHA incorrect";
			$valid = false;
			$erreur_msg = true;
		}
	}
}


include 'modules/menu.php';
?>

<div class="container-fluid">
	<div class="header">
		<div class="groupement-fioul">
			<div class="row">
				<div class="col align-self-center">
					<h1>Contactez-nous !</h1>
					<p>Une question sur votre commande de fioul ou sur nos achats groupés ?<br>Contactez-nous !</p>
				</div>
				<div class="col">
					<img src="images/header-contacter-poemop.svg" alt="Inscrivez vous et faites des économies" style="width: 70%;display: block;margin: 0 auto;">
				</div>
			</div>
		</div>
	</div>

	<div class="section">
		<div class="row">
			<div class="col-sm-9">
				<hr class="separe">
				<div class="bloc-contact text-center">
					<h2>Comment nous contacter ?</h2>
					<div class="ligne-center jaune"></div>
					<p>Nous avons préparé une FAQ claire et précise répondant aux questions les plus fréquentes.<br>Assurez-vous de l'avoir consultée avant de nous contacter.<br>La réponse à votre question s'y trouve peut-être déjà !</p>
					<div class="text-center">
						<a href="comment_ca_marche_fioul.php" class="btn btn-secondary">FAQ</a>
					</div>
				</div>
				<div class="poemop contact-form">
					<div class="row">
						<div class="col-sm-8">
							<p style="font-size: 20px;">Envoyez-nous un message</p>
							<hr class="separe">
<?php
							if(isset($message)) // Affiche les message d'erreur ou du succès
							{
?>
							<div class="toast <?= $message_type; ?>" style="margin: 1% 0 2% 0%!important;width:100%!important">
								<div class="message-icon <?= $message_type; ?>-icon">
									<i class="fas <?= $message_icone; ?>"></i>
								</div>
								<div class="message-content ">
									<div class="message-type" style="text-align:left;">
										<?= $message_info; ?>
									</div>
									<div class="message" style="text-align:left;">
										<?= $message; ?>
									</div>
								</div>
							</div>
<?php
							}

							if(isset($message_type) == "no" || !isset($message))
							{
?>
							<form method="post">
								<div class="row">
									<div class="col-sm-6">
										<label for="email_user" class="col-form-label custom-label">Email *</label>
										<input type="email" class="form-control form-lg <?php if(isset($erreur_mail)) { echo "erreur_form"; } ?>" id="email_user" name="email_user"  placeholder="Email" value="<?php if(isset($_POST["email_user"])) { echo $_POST["email_user"]; } ?>">
									</div>
									<div class="col-sm-6">
										<label for="cp_user" class="col-form-label custom-label">Code postal *</label>
										<input type="text" class="form-control form-lg <?php if(isset($erreur_cp)) { echo "erreur_form"; } ?>" id="cp_user" name="cp_user" placeholder="Code postal" required="required" value="<?php if(isset($_POST["cp_user"])) { echo $_POST["cp_user"]; } ?>">
									</div>
									<div class="col-sm-12" style="margin-top: 15px;">
										<label for="message_user" class="col-form-label custom-label">Votre message *</label>
										<textarea name="message_user" id="message_user" rows="8" cols="80" class="form-control form-lg <?php if(isset($erreur_msg)) { echo "erreur_form"; } ?>" required="required" style="height:150px;" ><?php if(isset($_POST["message_user"])) { echo $_POST["message_user"]; } ?></textarea>
									</div>
								</div>
								<label for="valider_form" class="col-form-label" style="padding-bottom: 0;margin-top:15px;">
									<input type="checkbox" name="valider_form" id="valider_form" class="switch value check" required="required" value="ok">
									J'ai bien lu les <a href="comment_ca_marche_fioul.php" style="color: #ef8351;">questions fréquentes</a> et n'y ai pas trouvé ma réponse.
								</label>
								<div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>" style="margin-top: 15px;"></div>
								<div class="text-right" style="margin-top: 5%;">
									<input type="submit" name="envoyer_message" class="btn btn-primary" value="ENVOYER">
								</div>
							</form>
<?php
							}
?>
						</div>
						<div class="col-sm-4">
							<p style="font-size: 20px; margin-top:5%">Contactez-nous par SMS</p>
							<div class="prix small prix-vert"> 07.70.12.15.96</div>
							<hr class="separe">
							<p style="font-size: 20px; margin-top:5%">Contactez-nous par email</p>
							<div class="prix small prix-vert">info@poemop.fr</div>
							<p></p>
							<div class="horaire">
								<p style="font-size: 20px;">Nos opératrices sont disponibles</p>
								<hr class="separe">
								<div class="row">
									<div class="col-sm-6">
										<div class="jour">
											Lundi<br>
											Mardi<br>
											Mercredi<br>
											Jeudi<br><br>
											Vendredi
										</div>
									</div>
									<div class="col-sm-6">
										<div class="jour">
											8h30 - 16h00<br>
											8h30 - 16h00<br>
											8h30 - 16h00<br>
											8h30 - 11h30<br>14h00 - 16h00<br>
											8h30 - 11h30<br>14h00 - 16h00
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
<?php
				include 'modules/connexion.php';
				include 'modules/activites.php';
				include 'modules/avis_clients.php';
?>
			</div>
		</div>
	</div>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
