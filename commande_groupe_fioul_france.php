<?php
include_once 'inc/dev_auth.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);

$desc = 'Commandes groupées de fioul domestique partout en France. Poemop groupe gratuitement votre commande de fioul avec celles de vos voisins.';
$title = 'Commandes groupées de fioul domestique partout en France';
ob_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_departement.php";

$res = getDepartements($co_pmp);
?>
<?php include 'modules/menu_fioul.php'; ?>
<div class="container-fluid">
	<div class="section">
		<div class="row">
			<div class="col-sm-9">
				<div class="header">
					<div class="groupement-achats">
						<div class="row">
							<div class="col-sm-6 align-self-center">
								<h1>Commandes groupées<br>de fioul en France !</h1>
								<p>Vous voulez faire des économies ?</p>
								<div class="block">
									<a href="creer_un_compte_poemop.php" class="btn btn-secondary">Rejoignez-nous !</a>
								</div>
							</div>
							<div class="col-sm-6">
								<img src="images/header-achat-groupes-poemop.svg" alt="Découvrez nos achats groupés" width="535" height="373" class="img-grpt">
							</div>
						</div>
					</div>
				</div>
				<div class="carte-france text-center">
					<h2>Zone<br>des groupements</h2>
					<div class="ligne-center orange"></div>
					<p>POEMOP vous aide à faire des économies sur votre facture de fioul sur une majeure partie de la France.<br>80 % du territoire français est actuellement couvert par nos services. Cliquez sur votre département pour savoir si un groupement<br>est en cours sur votre secteur. Vous pourrez également consulter les prix qui ont été négociés sur les précédents groupements.</p>
					
					<div id="maps" style="margin-top:5%;">
					  <div class="container">
						<div class="mapcontainer mapael" style="width:580px;">
						  <div class="map">
							<div class="navigmap">
							  <div class="map">
								<span> </span>
							  </div>

							  <div class='areaLegend'>
								<span> </span>
							  </div>
							</div>
						  </div>
						</div>
					  </div>
					</div>
					<div class="row">
<?php
						while($dep = mysqli_fetch_array($res))
						{
?>
						<div class="col-sm-6 text-left" style="text-align: left;">
							<a href="commande-groupee-de-fioul-domestique-<?= $dep["url"] ?>-departement.html" style="color: #ef8351;">Commandes groupées de fioul dans <?= $dep["titre"] ?> (<?= $dep["id"] ?>)</a><br>
						</div>
<?php
						}
?>

					</div>
				</div>
			</div>
			<div class="col-sm-3" style="margin-top: 5.9%;">
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
