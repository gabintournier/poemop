<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// INC global
include_once "../inc/pmp_co_connect.php";
// INC interne ADM Fournisseur
include_once "inc/pmp_inc_fonctions_connexion.php";
?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="bootstrap/5.2.3/css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/fonts.css">
		<link rel="stylesheet" href="css/styles.css" type="text/css" />
		<link rel="stylesheet" href="css/datatables.min.css"  type="text/css" />
		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/fontawesome.css"/>
		<link rel="apple-touch-icon" sizes="57x57" href="../favicon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="../favicon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="../favicon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="../favicon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="../favicon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="../favicon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="../favicon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="../favicon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="../favicon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="../favicon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="../favicon/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		
		<script src="js/jquery-ui.min.js"></script>
		<script src="../js/jquery-3.6.0.min.js"></script>
		<title><?= $title; ?></title>
	</head>
	<body>
		<div class="row">
			<div class="col-sm-2" style="max-width: 15%;">
				<div class="content-menu">
					<div class="logo">
						<img src="../images/logo-plus-on-est-moins-on-paie-blc.svg" alt="Appli POEMOP" style="width: 65%;">
						<p>Admin Fournisseur</p>
					</div>
					<hr class="separe-menu">
					<div class="sidenav" id="dashboard">
						<a href="index.php?id_crypte=<?= $_SESSION["id_crypte"]; ?>"><i class="fad fa-chart-bar"></i> Tableau de bord</a>
					</div>
					<div class="sidenav" id="cotations">
						<a href="liste_zones_cotation.php?id_crypte=<?= $_SESSION["id_crypte"]; ?>"><i class="fal fa-barcode-read"></i> Mes cotations</a>
					</div>
					<div class="sidenav" id="groupements">
						<a href="liste_regroupements.php?id_crypte=<?= $_SESSION["id_crypte"]; ?>"><i class="fal fa-truck"></i> Mes regroupements</a>
					</div>
					<div class="sidenav" id="recap">
						<a href="liste_groupements_recap.php?id_crypte=<?= $_SESSION["id_crypte"]; ?>"><i class="fal fa-list-ol"></i> Mes récaps</a>
					</div>
					<div class="sidenav" id="termines">
						<a href="liste_regroupements_termine.php?id_crypte=<?= $_SESSION["id_crypte"]; ?>"><i class="fal fa-box-check"></i> Groupements terminés</a>
					</div>
				</div>
			</div>
			<div class="col-sm-10" style="max-width: 85%;width: 85%;padding:0">
				<div class="menu-haut">
					<div class="row">
<?php
					if(isset($return) == true)
					{
?>
						<div class="col-sm-1 align-self-center return-bloc">
							<a href="<?php if(isset($link)) { echo $link; }  ?>" class="return"><i class="fas fa-arrow-left"></i></a>
						</div>

						<div class="col-sm-6 align-self-center menu">
							<h1>
								<?= $title_page; ?>
							</h1>
							<div class="ligne-menu"></div>
						</div>

<?php
					}
					else
					{
?>
						<div class="col-sm-7 align-self-center menu">
							<h1>
								<?= $title_page; ?>
							</h1>
							<div class="ligne-menu"></div>
						</div>
<?php
					}
?>


						<div class="col-sm-1 align-self-center text-right">
							<span class="date-stat"><i class="fal fa-calendar-alt"></i> <?= date("d M y"); ?></span>
						</div>
						<div class="col-sm-1 align-self-center text-right">
							<span class="refresh"><i class="fas fa-sync-alt"></i></span>
						</div>
						<div class="col-sm-3 align-self-center text-right">
							<div class="profil">
								<p class="user"><?= $_SESSION['four']; ?> <img src="images/user.png" alt=""></p>

							</div>
						</div>
					</div>
				</div>
				<div class="content">
					<?= $content; ?>
				</div>

			</div>
		</div>

		<!-- Interne ADMIN FOUR -->
		<script src="js/javascript.util.min.js"></script>
		<script src="js/menu.js" charset="utf-8"></script>
		<script src="js/datatables.min.js"></script>
		<script src="js/date-eu.js" type="text/javascript"></script>
		<script src="bootstrap/5.2.3/js/bootstrap.min.js"></script>

		<!-- Externe ADMIN FOUR -->
		<script src="../js/popper.min.js"></script>
	</body>
</html>
