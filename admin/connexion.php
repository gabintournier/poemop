<style media="screen">
	body {
		background-color: #0b2424!important;
	}
</style>

<?php


include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_connexion.php";
?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/fonts.css">
	<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<link rel="stylesheet" href="/fontawesome-6.3.0/css/all.min.css"/>
	<link rel="stylesheet" href="/fontawesome-6.3.0/css/fontawesome.min.css"/>
	<link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<title>Connexion admin POEMOP</title>
</head>
<body>
	<div class="connexion">
		<div class="logo-co">
			<img src="/images/logo-plus-on-est-moins-on-paie-blc.svg" alt="Appli POEMOP" style="width: 20%;">
		</div>
<?php
if (isset($message))
{
?>
		<div class="toast <?= $message_type; ?>" style="margin: 1% 0 0 34%;">
			<div class="message-icon <?= $message_type; ?>-icon">
				<i class="fas <?= $message_icone; ?>"></i>
			</div>
			<div class="message-content">
				<div class="message-type">
					<?= $message_titre; ?>
				</div>
				<div class="message">
					<?= $message; ?>
				</div>
			</div>
			<div class="message-close">
				<i class="fas fa-times"></i>
			</div>
		</div>
<?php
}
?>
		<div class="bloc-co">
			<div class="row">
				<div class="col align-self-end">
					<h1>Se connecter</h1>
				</div>
				<div class="col text-right">
					<img class="img" src="/images/poemop.svg" alt="fournisseurs" style="width:22%;">
				</div>
			</div>
			<hr>
			<form method="post" class="form-log">
				<div class="form-inline input-log">
					<label for="user" class="col-sm-1 col-form-label label-i-custom">
						<i class="far fa-user"></i>
					</label>
					<div class="col-sm-7">
						<input type="text" name="user" value="" class="form-control custom" placeholder="Identifiant" required="" style="height: 37px;">
					</div>
				</div>
				<div class="form-inline input-log position-relative">
					<label for="mdp" class="col-sm-1 col-form-label label-i-custom">
						<i class="fas fa-unlock-alt"></i>
					</label>
					<div class="col-sm-7 position-relative">
						<input type="password" id="mdp" name="mdp" class="form-control custom" placeholder="Mot de passe" required="" style="height: 37px;">
						<i class="far fa-eye" id="togglePassword" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer;"></i>
					</div>
				</div>
				<div class="row" style="margin-top:10%;">
					<div class="col-sm-6 align-self-center retour-site">
						<a href="/"><i class="fas fa-arrow-left"></i> Retour site</a>
					</div>
					<div class="col-sm-6 text-right">
						<input type="submit" class="btn btn-primary" name="connexion" value="CONNEXION">
					</div>
				</div>
			</form>
		</div>
	</div>

	<script src="/admin/js/jquery-3.6.0.min.js"></script>
	<script src="/admin/js/jquery-ui.min.js"></script>
	<script src="/js/popper.min.js"></script>
	<script src="/bootstrap/5.0.1/js/bootstrap.min.js"></script>
	<script src="/admin/js/javascript.util.min.js" integrity="sha512-oHBLR38hkpOtf4dW75gdfO7VhEKg2fsitvHZYHZjObc4BPKou2PGenyxA5ZJ8CCqWytBx5wpiSqwVEBy84b7tw==" crossorigin="anonymous"></script>
	<script src="js/menu.js" charset="utf-8"></script>
	<script src="/admin/js/datatables.min.js"></script>
	<script src="/admin/js/date-eu.js" type="text/javascript"></script>

	<script>
		const togglePassword = document.querySelector('#togglePassword');
		const password = document.querySelector('#mdp');

		togglePassword.addEventListener('click', function () {
			const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
			password.setAttribute('type', type);
			this.classList.toggle('fa-eye-slash');
		});
	</script>
</body>
</html>
