<?php
include_once __DIR__ . "/../inc/pmp_inc_fonctions_connexion.php";
include_once __DIR__ . "/../inc/pmp_inc_fonctions_compte.php";
if (isset($_SESSION['id'])) {
	$utilisateur = ChargeCompteFioul($co_pmp, $_SESSION['id'])
		?>
	<div class="module co">
		<div class="row">
			<div class="col top-mod align-self-center">
				<p>Mon compte</p>
			</div>
			<div class="col top-mod text-right text-end">
				<img loading="lazy" src="images/inscription-gratuite-poemop-blc.svg"
					alt="Inscrivez vous et faites des économies">
			</div>
		</div>
		<hr class="marge">
		<form method="post" class="compte-co">
			<p class="hello">Bonjour
				<?php if (isset($utilisateur["prenom"])) {
					echo htmlspecialchars($utilisateur["prenom"]);
				} ?> !
			</p>
			<a class="btn btn-secondary lg" href="inc/pmp_inc_fonctions_deconnexion.php"
				title="Déconnexion poemop">Déconnexion</a>
		</form>
		<hr class="separe">
		<div class="flex-links">
			<img src="images/ma_commande_fioul.svg" alt="entièrement gratuit et transparent pour vous">
			<a href="ma_commande.php">Ma commande fioul</a>
		</div>
		<hr class="separe">
		<!--
	<div class="flex-links">
		<img src="images/mes_inscriptions.svg" alt="entièrement gratuit et transparent pour vous">
		<a href="mes_inscriptions.php">Mes inscriptions</a>
	</div>
	<hr class="separe">
-->
		<div class="flex-links">
			<img src="images/mon_compte.svg" alt="entièrement gratuit et transparent pour vous">
			<a href="mon_compte.php?type=fioul">Mon compte</a>
		</div>
		<hr class="separe">
		<div class="flex-links">
			<img src="images/groupement-abonnement-telephone2.svg" alt="Paramètres du compte">
			<a href="parametres_compte.php">Mes paramètres</a>
		</div>
	</div>
	<?php
} else {
	?>
	<div class="module co">
		<div class="row">
			<div class="col top-mod align-self-center">
				<p>Connexion</p>
			</div>
			<div class="col top-mod text-right text-end">
				<img src="images/inscription-gratuite-poemop-blc.svg" alt="Inscrivez vous et faites des économies">
			</div>
		</div>
		<hr class="marge">
		<?php           // Si y a une erreur à l'envoi du formulaire on affiche le message d'erreur
			if (!empty($err)) {
				?>
			<div class="toast no" style="margin: 1% 0 2% 0!important;width: 100%!important;">
				<!-- <div class="message-icon no-icon">
			<i class="fas fa-times"></i>
		</div> -->
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
			// Toast via GET
			if (!empty($_GET['toast']) && !empty($_GET['msg'])) {
				$toast_type = htmlspecialchars($_GET['toast']); // success / no / info...
				$toast_title = htmlspecialchars($_GET['title'] ?? 'Information');
				$toast_msg = htmlspecialchars($_GET['msg']);
				?>
			<div class="toast <?= $toast_type ?>" style="margin:1% 0 2% 0!important;width:100%!important;">
				<div class="message-content">
					<div class="message-type">
						<?= $toast_title ?>
					</div>
					<div class="message">
						<?= $toast_msg ?>
					</div>
				</div>
			</div>
			<?php
			}
			?>
		<form method="post">
			<div class="mb-3 row">
				<label for="identifiant" class="col-sm-1 col-form-label custom-label">
					<img src="images/identifiant.svg" alt="entièrement gratuit et transparent pour vous" width="20"
						height="23">
				</label>
				<div class="col-sm-11">
					<input type="text" class="form-control custom-input" name="identifiant" id="identifiant"
						placeholder="Identifiant" style="height: 36px;">
				</div>
			</div>
			<div class="mb-3 row">
				<label for="mdp_co" class="col-sm-1 col-form-label custom-label">
					<img src="images/identifiant.svg" alt="entièrement gratuit et transparent pour vous" width="20"
						height="23">
				</label>
				<div class="col-sm-11">
					<input type="password" class="form-control custom-input" name="password" id="mdp_co"
						placeholder="Mot de passe" style="background-color: #f0f8ff00 !important;height: 36px;">
				</div>
			</div>
			<input class="btn btn-secondary lg" type="submit" name="connexion" value="Connexion">
		</form>
		<a href="creer_un_compte_poemop.php" title="Inscription poemop" class="btn btn-primary lg">Je m'inscris</a>
		<div class="link-co">
			<a href="creer_un_compte_poemop.php?reset=1">Mot de passe oublié ?</a>
		</div>
	</div>
	<script>
		const togglePassword = document.querySelector('#togglePassword');
		const password = document.querySelector('#mdp_co');

		if (togglePassword && password) {
			togglePassword.addEventListener('click', function () {
				const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
				password.setAttribute('type', type);
				this.classList.toggle('fa-eye-slash');
			});
		}
	</script>
	<?php
}
?>