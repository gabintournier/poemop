<?php
include_once 'inc/dev_auth.php';
session_start();
$desc = 'Commandes groupées d\'électricité partout en France. Poemop groupe gratuitement votre commande de fioul avec celles de vos voisins.';
$title = 'Commandes groupées d\'électricité moins cher avec POEMOP';
ob_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";

include 'modules/menu_chaudiere.php';
?>
<div class="container-fluid">
	<div class="header">
		<div class="groupement-achats">
			<div class="row">
				<div class="col-sm-6 align-self-center">
					<h1>Commande groupée<br>de changement de chaudière</h1>
					<p>Pré-inscrivez-vous dès maintenant pour profiter en avant-première des remises négociées par notre équipe pour le prochain Achat groupé</p>
					<div class="block">
						<a href="creer_un_compte_poemop.php" class="btn btn-secondary">Rejoignez-nous !</a>
					</div>
				</div>
				<div class="col-sm-6">
					<img src="images/header-groupement-changement-chaudiere.svg" alt="Commande groupée d'électricité avec Poemop">
				</div>
			</div>
			<div class="inscription-elec text-center">
				<h2>C'est facile !</h2>
				<div class="ligne-center jaune"></div>
				<div class="row">
					<div class="col">
						<div class="bbox">
							<img src="images/inscription-gratuite-poemop.svg" alt="entièrement gratuit et transparent pour vous">
							<p>Je m'inscris gratuitement<br><span>et sans engagement</span></p>
						</div>
					</div>
					<div class="col">
						<div class="bbox">
							<img src="images/economies-groupement-poemop.svg" alt="Nous négocions les meilleurs prix d'électricté">
							<p>Mise en concurrence<br><span>nous négocions les meilleurs prix</span></p>
						</div>
					</div>
					<div class="col">
						<div class="bbox mail">
							<img src="images/mail-poemop.svg" alt="livraison tranquille et paiement direct">
							<p>Les meilleures offres<br><span>vous seront communiquées par mail</span></p>
						</div>
					</div>
					<div class="col">
						<div class="bbox">
							<img src="images/groupement-electricite-poemop.svg" alt="livraison tranquille et paiement direct">
							<p>Souscription<br><span>vous pouvez en bénéficier ou y renoncer.</span></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="section">
		<div class="row">
			<div class="col-sm-9">
				<div class="economies text-center">
					<img class="img" src="images/economies-groupement-poemop.svg" alt="Nous négocions les meilleurs prix d'électricté">
					<h2>Économies garanties</h2>
					<div class="ligne-center orange"></div>
					<p>Avec le fioul qui a énormement augmenté, le forfait MaPrimeRénov' a augmenté de 1000 € pour tous les propriétaires, tous niveaux de revenus confondus, qui souhaitent remplacer leur chaudière au fioul ou au gaz par une pompe à chaleur ou une chaudière biomasse. Cette aide est valable jusqu'à fin 2022.</p>
					<a href="#" class="btn btn-secondary">Tous les groupements</a>
				</div>
				<div class="block-titre text-center">
					<h2>Alors, n'hésitez plus et faites<br>le choix de l'économie !</h2>
				</div>
				<div class="text-center">
					<a href="creer_un_compte_poemop.php" class="btn btn-secondary">Rejoignez-nous !</a>
				</div>
				<hr class="separe" style="margin-top: 7%;">
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
<?php
	include 'modules/partenaires.php';
?>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
