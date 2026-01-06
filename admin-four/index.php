<style media="screen">
.ligne-menu {width: 248px!important;}
</style>
<?php
session_start();
$title = 'Tableau de bord';
$title_page = 'Tableau de bord';

ob_start();

// INC global
include_once "../inc/pmp_co_connect.php";
// INC interne ADM Fournisseur
include_once "inc/pmp_inc_fonctions.php";

// include_once __DIR__ . "/inc/pmp_inc_fonctions_groupements.php";

if(isset($_SESSION["four_id"]))
{
	$res_zones = getNbZones($co_pmp);
	$zone = mysqli_num_rows($res_zones);
	// $res_grp_10 = getNbGroupements($co_pmp, 10);
	// $res_grp_15 = getNbGroupements($co_pmp, 15);
	$res_recap = getNbGroupements($co_pmp, 30);
	$res_trm = getNbGroupementsTermines($co_pmp);

	// $res_grp = $res_grp_10["nb"] + $res_grp_15["nb"];
	$res_grp = getNbGroupements($co_pmp, 15);
	//$res_cotations = getCommandesCotations($co_pmp);
	//$cotations = mysqli_num_rows($res_cotations);

	$cotations = getNbCotations($co_pmp);
}
else
{
	header('Location: test.php?id_crypte=' . $_GET["id_crypte"]);
}

?>
<div class="row justify-content-md-center">
	<div class="col-sm">
		<div class="cards-stat fournisseurs-hover" style="height: 275px;">
			<a href="liste_zones_cotation.php?id_crypte=<?= $_GET["id_crypte"]; ?>">
				<div class="icon-stat fournisseurs-content">
					<img src="images/cotation-poemop.svg" style="width: 13%;">
				</div>
				<div class="title-stat">
					Gestion des cotations
				</div>
				<div class="item">
					<div class="number nb-four"><?php if(isset($cotations["nb"])) { echo $cotations["nb"];} else { echo "0"; } ?></div>
					<div class="type">zones à traiter</div>
				</div>
			</a>
			<div class="row">
				<div class="col-sm text">
					Toutes les<br>zones
				</div>
				<div class="col-sm align-self-center">
					<div class="text-end">
						<a href="liste_zones_cotation.php?id_crypte=<?= $_GET["id_crypte"]; ?>" class="btn btn-go opacity-hover fournisseurs-show"><i class="fas fa-chevron-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm">
		<div class="cards-stat commandes-hover" style="height: 275px;">
			<a href="liste_regroupements.php?id_crypte=<?= $_GET["id_crypte"]; ?>">
				<div class="icon-stat fournisseurs-content">
					<img src="../admin/images/commandes.svg" style="width: 20%;margin-top:2%">
				</div>
				<div class="title-stat">
					Gestion des regroupements
				</div>
				<div class="item">
					<div class="number nb-com"><?php if(isset($res_grp["nb"])) { echo $res_grp["nb"]; } else { echo "0"; }  ?></div>
					<div class="type">groupements en cours</div>
				</div>

			</a>
			<div class="row">
				<div class="col-sm text">
					Tous les<br>groupements
				</div>
				<div class="col-sm align-self-center">
					<div class="text-end">
						<a href="liste_regroupements.php?id_crypte=<?= $_GET["id_crypte"]; ?>" class="btn btn-go opacity-hover fournisseurs-show"><i class="fas fa-chevron-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm">
		<div class="cards-stat avis-hover" style="height: 275px;">
			<a href="liste_groupements_recap.php?id_crypte=<?= $_GET["id_crypte"]; ?>">
				<div class="icon-stat fournisseurs-content">
					<img class="img" src="../images/poemop.svg" alt="fournisseurs" style="width:14%;">
				</div>
				<div class="title-stat">
					Gestion des récap
				</div>
				<div class="item">
					<div class="number nb-avis"><?php if(isset($res_recap["nb"])) { echo $res_recap["nb"]; } else { echo "0"; } ?></div>
					<div class="type">récap à traiter</div>
				</div>
			</a>
			<div class="row">
				<div class="col-sm text">
					Tous les<br>groupements
				</div>
				<div class="col-sm align-self-center">
					<div class="text-end">
						<a href="liste_groupements_recap.php?id_crypte=<?= $_GET["id_crypte"]; ?>" class="btn btn-go opacity-hover fournisseurs-show"><i class="fas fa-chevron-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm">
		<div class="cards-stat groupements-hover" style="height: 275px;">
			<a href="liste_regroupements.php?id_crypte=<?= $_GET["id_crypte"]; ?>">
				<div class="icon-stat fournisseurs-content">
					<img src="../admin/images/livraison-poemop-a.svg" alt="Avis" style="width: 16%;">
				</div>
				<div class="title-stat">
					Gestion des regroupements
				</div>
				<div class="item">
					<div class="number nb-group"><?php if(isset($res_trm["nb"])) { echo $res_trm["nb"]; } else { echo "0"; } ?></div>
					<div class="type">groupements terminés</div>
				</div>
			</a>
			<div class="row">
				<div class="col-sm text">
					Tous les<br>groupements
				</div>
				<div class="col-sm align-self-center">
					<div class="text-end">
						<a href="liste_regroupements_termine.php?id_crypte=<?= $_GET["id_crypte"]; ?>" class="btn btn-go opacity-hover fournisseurs-show"><i class="fas fa-chevron-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
