<?php
include_once 'inc/dev_auth.php';

session_start();
$desc = 'Commandes groupées de gaz partout en France. Poemop groupe gratuitement votre commande de fioul avec celles de vos voisins.';
$title = 'Commandes groupées de gaz moins cher avec POEMOP';
ob_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";

include 'modules/menu_artisan.php';
?>
<div class="container-fluid">
	<div class="header">
		<div class="groupement-achats">
			<div class="row">
				<div class="col-sm-6 align-self-center">
					<h1>A la recherche<br>d'un bon artisan ?</h1>
					<p>Ce n'est pas chose aisée aujourd'hui ! Sur quels critères en choisir un ? Comment évaluer sa compétence ? Autant de questions qui se posent et les réponses ne sont pas toujours si évidentes</p>
					<div class="block">
						<a href="creer_un_compte_poemop.php" class="btn btn-secondary">Rejoignez-nous !</a>
					</div>
				</div>
				<div class="col-sm-6">
					<img src="images/header-artisans-poemop.svg" alt="Commande groupée de fioul domestique avec Poemop">
				</div>
			</div>
		</div>
	</div>
	<div class="section">
		<div class="row">
			<div class="col-sm-9">
				<div class="inscription-artisan text-center">
					<img class="img" src="images/artisan-de-confiance.svg" alt="Trouver simplement un artisan de confiance">
					<h2>Artisan de confiance</h2>
					<div class="ligne-center jaune"></div>
					<p>Aujourd'hui, nous souhaitons simplifier les démarches de chacun en mettant en place un réseau<br>d'artisans de confiance afin de permettre à tous nos inscrits de trouver près de chez eux « l'artisan » qui lui faut, celui qui vous apportera<br>une prestation de qualité, et au prix juste.</p>
				</div>
				<div class="reseau text-center">
					<h2>Dans quel but créer<br>un réseau d'artisans de confiance ?</h2>
					<div class="ligne-center orange"></div>
					<div class="row">
						<div class="col">
							<div class="bbox">
								<img class="img2" src="images/check.svg" alt="Les avantages à créer un réseau d'artisans de confiance">
								<p>Un gain de temps<br><span>vous aurez à portée de main<br>des artisans connus et reconnus !</span></p>
							</div>
						</div>
						<div class="col">
							<div class="bbox">
								<img class="img2" src="images/check.svg" alt="Les avantages à créer un réseau d'artisans de confiance">
								<p>Une relation de confiance<br><span>il d'agit de porfessionnels talentueux<br>qui souhaitent vous satisfaire !</span></p>
							</div>
						</div>
						<div class="col">
							<div class="bbox">
								<img class="img2" src="images/check.svg" alt="Les avantages à créer un réseau d'artisans de confiance">
								<p>l'assurance de bénéficier<br><span>d'un travail sérieux<br>et de qualité !</span></p>
							</div>
						</div>
					</div>
				</div>
				<div class="achats-groupes text-center">
					<img class="img" src="images/rejoindre-poemop-blc.svg" alt="Aidez-nous à mettre en place un réseau d'artisans de confiance">
					<h2>Comment nous aider à mettre en place<br>ce réseau d'artisans de confiance ?</h2>
					<div class="ligne-center jaune"></div>
					<p>C'est très simple ! Nous sommes sûrs que vous avez dans vos contacts des professionnels auxquels vous avez fait appel et qui vous ont apporté satisfaction. Nous invitons chacun d'entre vous à nous soumettre un ou plusieurs artisans que vous pourriez recommander (plombier, peintre, électricien, menuisier, couvreur, chauffagiste, serrurier...) et qui pourrait permettre aux autres inscrits de trouver la bonne personne pour réaliser leurs travaux.</p>
					<div class="block text-center poemop-artisan">
						<a href="mon_compte.php?type=artisan.php" class="btn btn-secondary">Je connais un artisan !</a>
					</div>
				</div>
				<hr class="separe">
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
