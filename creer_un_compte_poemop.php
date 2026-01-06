<?php
include_once 'inc/dev_auth.php';
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

if (empty($err) && !empty($_SESSION['err'])) {
	$err = $_SESSION['err'];
	unset($_SESSION['err']);
}

if (empty($erreur_form) && !empty($_SESSION['erreur_form'])) {
	$erreur_form = $_SESSION['erreur_form'];
	unset($_SESSION['erreur_form']);
}


$desc = 'Commandes groupées de fioul domestique partout en France. Poemop groupe gratuitement votre commande de fioul avec celles de vos voisins.';
$title = 'Créer un compte sur plus-on-est-moins-on-paie.fr';
ob_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_inscription.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_connexion.php";
if (!isset($_GET["id_crypte"])) {
	include 'modules/menu.php';
}

?>
<style media="screen">
	html,
	.background,
	.content {
		background: #0f393a;
	}

	.co>a {
		display: none;
	}

	.col-sm-3 {
		height: 550px;
	}
</style>

<div class="group">
	<div class="row">
		<div class="col-sm-3 display"></div>
		<div class="col-sm-6 center-bloc-grid">
			<?php   // Si un id_crypte est présent en GET on affiche le formulaire pour créer son mot de passe
			if (isset($_GET["id_crypte"])) {
				if (isset($_GET["m_email"])) {
					?>
					<div class="module registration text-center" style="margin-bottom:13%;">
						<h2>Votre adresse email a bien été modifiée</h2>
						<div class="text-center" style="margin-top:5%;">
							<a href="creer_un_compte_poemop.php?connexion=1" class="btn btn-primary" style="width: 80%;">JE ME
								CONNECTE</a>
						</div>
					</div>
					<?php
				} else {
					?>
					<div class="module registration" style="margin-bottom:13%;">
						<div class="row">
							<div class="col-sm-7 top-mod align-self-center">
								<?php
								if (isset($_GET["reinitialiser"])) {
									?>
									<h2>Réinitialiser mon mot de passe</h2>
									<?php
								} else {
									?>
									<h1>J'active mon compte !</h1>
									<?php
								}
								?>
							</div>
							<div class="col top-mod text-end">
								<img src="images/rejoindre-poemop.svg" alt="Inscrivez vous et faites des économies">
							</div>
						</div>
						<hr class="separe">
						<?php           // Si y a une erreur à l'envoi du formulaire on affiche le message d'erreur
								if (!empty($err)) {
									?>
							<div class="toast no" style="margin: 1% 0 2% 0!important;width: 100%!important;">
								<div class="message-icon no-icon">
									<i class="fas fa-times"></i>
								</div>
								<div class="message-content ">
									<div class="message-type">
										Erreur
									</div>
									<div class="message">
										<?= $err; ?>
									</div>
								</div>
							</div>
							<?php
								}
								?>
						<form method="post">
							<div class="form-inline justify-content-center" style="margin-top:3%;">
								<label for="password_user" class=" col-sm-4 col-form-label custom-label">Mot de passe *</label>
								<div class="col-sm-6">
									<input type="password"
										class="form-control form-lg <?php if (isset($erreur_mdp)) {
											echo htmlspecialchars($erreur_mdp);
										} ?>"
										id="password_user" name="password_user" placeholder="Mot de passe" required="required">
								</div>
							</div>
							<div class="form-inline justify-content-center" style="margin-top:3%;">
								<label for="confirm_password" class="col-sm-4 col-form-label custom-label">Confirmer le mot de
									passe *</label>
								<div class="col-sm-6">
									<input type="password"
										class="form-control form-lg <?php if (isset($erreur_mdp)) {
											echo htmlspecialchars($erreur_mdp);
										} ?>"
										id="confirm_password" name="confirm_password" placeholder="Mot de passe"
										required="required">
								</div>
							</div>
							<div class="text-right" style="margin-top:5%;">
								<?php
								if (isset($_GET["reinitialiser"])) {
									?>
									<input type="submit" name="change_password" class="btn btn-primary"
										value="Modifier mot de passe">
									<?php
								} else {
									?>
									<input type="submit" name="activer_compte" class="btn btn-primary" value="Activer mon compte">
									<?php
								}
								?>
							</div>
						</form>
					</div>
					<?php
				}
			} // Si l'pmp_utilisateurclique sur "mmot de passe oublié" on affiche le formulaire
			elseif (isset($_GET["reset"])) {
				?>
				<div class="module registration">
					<div class="row">
						<div class="col-sm-12 top-mod align-self-center">
							<h1>Réinitialiser<br>mon mot de passe</h1>
						</div>
					</div>
					<hr class="separe">
					<?php
					if (!empty($err)) {
						?>
						<div class="toast no" style="margin: 1% 0 2% 0!important;width: 100%!important;">
							<div class="message-icon no-icon">
								<i class="fas fa-times"></i>
							</div>
							<div class="message-content ">
								<div class="message-type">
									Erreur
								</div>
								<div class="message">
									<?= $err; ?>
								</div>
							</div>
						</div>
						<?php
					}
					if (isset($success)) {
						?>
						<div class="block-titre block-success text-center">
							<h2>Votre demande a bien été prise en compte</h2>
						</div>
						<p class="text-center">Vous allez recevoir un mail pour réinitialiser votre mot de passe. </p>
						<?php
					} else {
						?>
						<p class="text-center">Veuillez saisir votre adresse e-mail ci-dessous. Nous vous enverrons les
							instructions pour créer un nouveau mot de passe.</p>
						<form method="post">
							<div class="form-inline justify-content-center" style="margin-top:3%;">
								<label for="mail" class="col-sm-2 col-form-label custom-label">Email *</label>
								<div class="col-sm-6">
									<input type="email"
										class="form-control form-lg <?php if (isset($erreur_mail)) {
											echo htmlspecialchars($erreur_mail);
										} ?>"
										id="mail" name="mail" placeholder="Email" required="required"
										value="<?php if (isset($mail)) {
											echo htmlspecialchars($mail);
										} ?>">
								</div>
							</div>
							<div class="text-right" style="margin-top:5%;">
								<input type="submit" name="reset_password" class="btn btn-primary" value="Réinitialiser">
							</div>
						</form>
						<?php
					}
					?>
					<div class="flex">
						<p class="link-login"><a href="creer_un_compte_poemop.php?connexion=1">Me connecter</a></p>
						<p style="padding: 0 2% 0 2%;"> | </p>
						<p class="link-login"><a href="creer_un_compte_poemop.php">Créer mon compte</a></p>
					</div>
				</div>
				<?php
			} else {
				if (empty($_GET["connexion"])) {
					?>
					<div class="module registration" id="registration-bloc" style="margin-bottom:13%;">
						<div class="row">
							<div class="col-sm-7 top-mod align-self-center">
								<h1>Rejoignez-nous !</h1>
							</div>
							<div class="col top-mod text-end">
								<img src="images/rejoindre-poemop.svg" alt="Inscrivez vous et faites des économies">
							</div>
						</div>
						<hr class="separe">
						<?php
						if (!empty($err)) {
							?>
							<div class="toast no" style="margin: 1% 0 2% 0!important;width: 100%!important;">
								<div class="message-icon no-icon">
									<i class="fas fa-times"></i>
								</div>
								<div class="message-content ">
									<div class="message-type">
										Erreur
									</div>
									<div class="message">
										<?= $err; ?>
									</div>
								</div>
							</div>
							<?php
						}
						if (isset($success)) {
							?>
							<div class="block-titre block-success text-center">
								<h2>Merci pour votre inscription<br>et bienvenue sur Poemop !</h2>
							</div>
							<p class="text-center">Vous allez recevoir un mail pour activer votre compte. Attention, ce message peu
								arriver dans les spams.</p>

							<?php
						} else {
							?>
							<form method="post">
								<div class="form-inline justify-content-center" style="margin-top:3%;">
									<label for="cp_user" class=" col-sm-4 col-form-label custom-label">Code postal *</label>
									<div class="col-sm-6">
										<input type="text"
											class="form-control form-lg <?php if (isset($erreur_cp)) {
												echo htmlspecialchars($erreur_cp);
											} ?>"
											id="cp_user" name="cp_user" placeholder="Code postal" required="required"
											value="<?php if (isset($cp)) {
												echo htmlspecialchars($cp);
											} ?>">
									</div>
								</div>
								<div class="form-inline justify-content-center" style="margin-top:3%;">
									<label for="mail" class="col-sm-4 col-form-label custom-label">Email *</label>
									<div class="col-sm-6">
										<input type="email"
											class="form-control form-lg <?php if (isset($erreur_mail)) {
												echo htmlspecialchars($erreur_mail);
											} ?>"
											id="mail" name="mail" placeholder="Email" required="required"
											value="<?php if (isset($mail)) {
												echo htmlspecialchars($mail);
											} ?>">
									</div>
								</div>
								<!-- <div class="form-inline justify-content-center" style="margin:3% 0 6%;">
						<label for="confirm_mail" class="col-sm-4 col-form-label custom-label">Confirmer email *</label>
						<div class="col-sm-6">
							<input type="email" class="form-control form-lg <?php if (isset($erreur_mail)) {
								echo $erreur_mail;
							} ?>" id="confirm_mail" name="confirm_mail" placeholder="Email" required="required" value="<?php if (isset($confmail)) {
								 echo $confmail;
							 } ?>">
						</div>
					</div> -->
								<div class="row" style="margin-top: 10%;">
									<div class="col-sm-6 align-self-center">
										<p class="link-login">Vous avez déjà un compte ? <a
												href="creer_un_compte_poemop.php?connexion=1">Connectez-vous</a></p>
									</div>
									<div class="col-sm-6 text-right">
										<input type="submit" name="inscription_poemop" class="btn btn-primary" value="Je m'inscris">
									</div>
								</div>
							</form>
							<?php
						}
						?>
					</div>
					<?php
				}
				if (!empty($erreur_form) || isset($_GET["connexion"])) {
					?>
					<div class="module registration" id="login-bloc">
						<div class="row">
							<div class="col-sm-7 top-mod align-self-center">
								<h1>Se connecter&nbsp;!</h1>
							</div>
							<div class="col top-mod text-end">
								<img src="images/poemop.svg" alt="Inscrivez vous et faites des économies">
							</div>
						</div>

						<hr class="separe">
						<?php
						if (!empty($err)) {
							?>
							<div class="toast no connecte" style="margin: 1% 0 2% 0!important;width: 100%!important;">
								<div class="message-icon no-icon">
									<i class="fas fa-times"></i>
								</div>
								<div class="message-content ">
									<div class="message-type">
										Erreur
									</div>
									<div class="message">
										<?= $err; ?>
									</div>
								</div>
							</div>
							<?php
						}
						?>
						<form method="post">
							<div class="form-inline justify-content-center" style="margin-top:3%;">
								<label for="identifiant" class="col-sm-4 col-form-label custom-label">Email *</label>
								<div class="col-sm-6">
									<input type="email"
										class="form-control form-lg <?php if (isset($erreur_form)) {
											echo htmlspecialchars($erreur_form);
										} ?>"
										id="identifiant" name="identifiant" placeholder="Email" required="required"
										value="<?php if (isset($mail)) {
											echo htmlspecialchars($mail);
										} ?>">
								</div>
							</div>
							<div class="form-inline justify-content-center" style="margin:3% 0 0;">
								<label for="password_connexion" class="col-sm-4 col-form-label custom-label">Mot de passe
									*</label>
								<div class="col-sm-6 position-relative" style="padding-right:0;">
									<input type="password"
										class="form-control form-lg <?php if (isset($erreur_form)) {
											echo htmlspecialchars($erreur_form);
										} ?>"
										id="password_connexion" name="password" placeholder="Mot de passe" required="required"
										value="<?php if (isset($confmail)) {
											echo htmlspecialchars($confmail);
										} ?>"
										style="padding-right: 44px;">
								</div>
							</div>
							<div class="form-inline justify-content-center" style="margin:3% 0 6%;">
								<div class="col-sm-10 text-right">
									<a class="mdp_oublie" href="creer_un_compte_poemop.php?reset=1">Mot de passe oublié</a>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-6 align-self-center">
									<p class="link-signup">Vous n'avez pas de compte ? <a
											href="creer_un_compte_poemop.php">Inscrivez-vous</a></p>
								</div>
								<div class="col-sm-6 text-right">
									<input type="submit" name="connexion" class="btn btn-primary" value="Je me connecte">
								</div>
							</div>
						</form>
					</div>
					<?php
				}
				?>

				<?php
			}
			?>
		</div>
		<div class="col-sm-3 display"></div>
		<hr>
	</div>
</div>




<?php
$content = ob_get_clean();
require('template.php');
?>

<script>
	$(function () {
		$("input[type='password']").each(function (index, element) {
			$(this).attr("autocomplete", "off");
			$(this).val("");
		});
	});
</script>

<script>
	const togglePasswordLogin = document.querySelector('#togglePasswordLogin');
	const passwordLogin = document.querySelector('#password_connexion');

	if (togglePasswordLogin && passwordLogin) {
		togglePasswordLogin.addEventListener('click', function () {
			const type = passwordLogin.getAttribute('type') === 'password' ? 'text' : 'password';
			passwordLogin.setAttribute('type', type);
			this.classList.toggle('fa-eye-slash');
		});
	}
</script>