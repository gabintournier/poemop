<!DOCTYPE html>
<html lang="fr" dir="ltr" style="overflow-x: hidden;height: 100%;overflow-y: hidden;background: #0b2424;">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="/admin-four/bootstrap/5.2.3/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/fonts.css">
		<link rel="stylesheet" href="css/styles.css" type="text/css" />
		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/fontawesome.css"/>
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
		<title>Admin Fournisseur</title>
	</head>
	<body style="background: #0b2424;">
		<div class="connexion">
			<div class="logo-co">
				<img src="/images/logo-plus-on-est-moins-on-paie-blc.svg" alt="Appli POEMOP" style="width: 20%;display: block;margin: 0 auto;margin-top: 10%;">
			</div>
			<div class="row">
				<div class="col-sm-12 text-center">
<?php
					if(isset($_GET["id_crypte"]))
					{
?>
					<p class="col-form-label" style="margin-top: 2%;color: #f8f6f4;">Actualiser</p>
					<a class="refresh-co" href="index.php?id_crypte=<?= $_GET["id_crypte"]; ?> "><i class="fas fa-sync-alt"></i></a>
<?php
					}
					else
					{
?>
					<p class="col-form-label" style="margin-top: 2%;color: #f8f6f4;">Merci de contacter POEMOP pour vous connecter.</p>
<?php
					}
?>

				</div>
			</div>
		</div>
	</body>
</html>
