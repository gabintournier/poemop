<?php
include_once 'inc/dev_auth.php';
session_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";

$desc = 'Données personnelles de vos commandes de fioul moins cher POEMOP.';
$title = 'Données personnelles de vos commandes de fioul moins cher';
ob_start();

include 'modules/menu.php';
?>
<div class="container-fluid">
	<div class="header mentions">
		<div class="row">
			<div class="col-sm-9">
				<div class="text-center">
					<h1>Données personnelles de vos achats de fioul</h1>
					<div class="ligne-center jaune"></div>
					<p>Plus-on-est-moins-on-paie.fr s'engage à traiter vos données personnelles de manière loyale et licite, dans le respect des dispositions de la Loi n°78-17 du 6 janvier 1978 modifiée relative à l’informatique, aux fichiers et aux libertés et du Règlement (UE) n° 2016/679 du 27 avril 2016 relatif à la protection des personnes physiques à l'égard du traitement des données à caractère personnel et à la libre circulation de ces données, dit « Règlement Général sur la Protection des Données » (ou « RGPD »).</p>
					<p>La présente politique des données personnelles a pour objet de vous informer des conditions dans lesquelles plus-on-est-moins-on-paie.fr traite vos données personnelles, tout en veillant à leur protection.</p>
				</div>
				<h2>Protection de vos données à caractère personnel</h2>
				<hr class="separe">
				<p>plus-on-est-moins-on-paie.fr peut être amenée à collecter des données personnelles de ses clients, notamment en cas de navigation sur notre site internet, de création de compte, d'inscription aux Newsletters, de devis, de commande, d'avis laissé sur le site, de réponse à un sondage ou enquête de satisfaction, d'envoi d'email ou via le formulaire de contact.</p>
				<br>
				<h2>Types de données collectées</h2>
				<hr class="separe">
				<p>Les données personnelles collectées sont : nom, prénom, civilité, adresse postale, n° de téléphone, adresse email, mot de passe.</p>
				<p>Les données de navigation sont : adresse IP et type de navigateur.</p>
				<p>Les données liées aux commandes sont : type de fioul, nombre de litres, prix au litre, date, avis.</p>
				<br>
				<h2>Finalités de la collecte et du traitement des données</h2>
				<hr class="separe">
				<p>Les informations recueillies font l’objet d’un traitement informatique et sont destinées à la gestion de vos commandes, à la réalisation d’études statistiques et d’un suivi qualité du service de plus-on-est-moins-on-paie.fr, à l’envoi d’alertes (afin de vous tenir informé(e) par e-mail d’une promotion ou d’une actualité vous intéressant), à l’envoi de newsletter et d’informations, et au suivi de la relation client.</p>
				<br>
				<p>Dans un souci d’efficacité, les équipes de POEMOP peuvent, à votre demande expresse, vous accompagner dans le processus de commande et d’inscription à nos groupements.</p>
				<br>
				<h2>Destinataires des données</h2>
				<hr class="separe">
				<p>Les données collectées sur notre site sont exclusivement destinées à l’usage propre de plus-on-est-moins-on-paie.fr. Elles peuvent être transmises aux personnes agissant sous l’autorité et sur les instructions de plus-on-est-moins-on-paie.fr auxquelles plus-on-est-moins-on-paie.fr fait appel, notamment dans le cadre de l'exécution des services et des commandes (notamment pour la livraison).</p>
				<p>Aucune des données personnelles collectées à partir du site n’est communiquée ou cédée à des tiers à des fins commerciales et ne sont jamais transférées en dehors de l’Espace Économique Européen.</p>
				<br>
				<h2>Conservation des données</h2>
				<hr class="separe">
				<p>Vos données à caractère personnel ainsi collectées sont conservées pendant une durée qui n’excède pas la durée nécessaire aux finalités pour lesquelles elles ont été collectées et pour permettre à plus-on-est-moins-on-paie.fr de respecter strictement et uniquement ses obligations légales.</p>
				<br>
				<h2>Vos droits</h2>
				<hr class="separe">
				<p>Conformément à la loi « Informatique et Libertés » du 6 janvier 1978 et au Règlement européen sur la protection des données du 27 avril 2016, vous disposez d'un droit d'accès, d’interrogation, de rectification, d’effacement, de limitation des données à caractère personnel qui vous concernent.</p>
				<p>Vous disposez également d’un droit d’opposition au traitement de vos données à caractère personnel pour des motifs légitimes, ainsi que d’un droit d’opposition à ce que vos données à caractère personnel soient utilisées à des fins de prospection commerciale.</p>
				<p>Vous disposez en outre d’un droit de retrait du consentement, pour les traitements fondés exclusivement sur votre consentement.</p>
				<p>Vous pouvez demander la communication de vos données personnelles. Le responsable du traitement se réserve toutefois le droit de refuser toute demande qui serait considérée comme abusive.</p>
				<p>Vous disposez également de la possibilité d’introduire une réclamation auprès de la CNIL, autorité de contrôle compétente pour la protection des données.</p>
				<p>L'exercice de ce droit peut être effectué des manières suivantes :</p>
				<ul>
					<li>Par email grâce à notre formulaire de <a href="contacter_poemop.php">contact</a> de plus-on-est-moins-on-paie.fr : </li>
					<li>Par courrier à l'adresse suivante : POMEOP, 2 rue de l'Ardoise, Port Edouard Herriot, 69007 Lyon</li>
				</ul>
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
