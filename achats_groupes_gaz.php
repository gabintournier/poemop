<?php
include_once 'inc/dev_auth.php';
session_start();
$desc = 'Commandes groupées de gaz partout en France. Poemop groupe gratuitement votre commande de fioul avec celles de vos voisins.';
$title = 'Commandes groupées de gaz moins cher avec POEMOP';
ob_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";

include 'modules/menu_gaz.php';
?>
<div class="container-fluid">
	<div class="header">
		<div class="groupement-fioul">
			<div class="row">
				<div class="col-sm-6 align-self-center">
					<h1>Nous lançons un achat groupé national de gaz</h1>
					<p>POEMOP diversifie ses activités et vous aide à réaliser<br>des économies sur vos contrats de gaz !</p>
					<div class="block">
						<a href="creer_un_compte_poemop.php" class="btn btn-secondary">Rejoignez-nous !</a>
					</div>
				</div>
				<div class="col-sm-6">
					<img src="images/header-gaz-poemop.svg" alt="Commande groupée de fioul domestique avec Poemop">
				</div>
			</div>
			<div class="inscription-fioul text-center">
				<h2>Le principe est simple</h2>
				<div class="ligne-center orange"></div>
				<div class="row">
					<div class="col">
						<div class="bbox">
							<img src="images/inscription-gratuite-poemop.svg" alt="entièrement gratuit et transparent pour vous">
							<p>Pré-inscription gratuite<br><span>et sans engagement</span></p>
						</div>
					</div>
					<div class="col">
						<div class="bbox">
							<img src="images/economies-groupement-poemop.svg" alt="traitement direct avec le fournisseur">
							<p>Nous négocions pour vous<br><span>et vous recevez l'offre par mail</span></p>
						</div>
					</div>
					<div class="col">
						<div class="bbox">
							<img src="images/livraison-poemop.svg" alt="livraison tranquille et paiement direct">
							<p>Vous validez ou refusez<br><span>sans avoir à vous justifier</span></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="section">
		<div class="row">
			<div class="col-sm-9">
				<div class="block-titre text-center">
					<h2>Alors, n'hésitez plus et faites<br>le choix de l'économie !</h2>
				</div>
<?php
				// include 'modules/actu.php';
?>
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
