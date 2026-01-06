<?php
include_once 'inc/dev_auth.php';
session_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";

$desc = 'Cookies de vos commandes de fioul moins cher POEMOP.';
$title = 'Cookies de vos commandes de fioul moins cher';
ob_start();

include 'modules/menu.php';
?>
<div class="container-fluid">
	<div class="header mentions">
		<div class="row">
			<div class="col-sm-9">
				<div class="text-center">
					<h1>Cookies de vos achats de fioul</h1>
					<div class="ligne-center jaune"></div>
					<p>Le site plus-on-est-moins-on-paie.fr utilise des cookies susceptibles d’enregistrer des informations relatives à votre navigation, sous réserve des choix que vous auriez exprimés à leur sujet et que vous pouvez modifier à tout moment.</p>
					<p>Cette page est destinée à vous informer sur la nature de ces cookies, leurs finalités et les moyens dont vous disposez pour vous y opposer.</p>
				</div>
				<h2>Qu’est-ce qu’un cookie ?</h2>
				<hr class="separe">
				<p>Un « cookie », également appelé témoin de connexion, est un fichier texte de petite taille, déposé par le serveur d’un site Internet sur le disque dur du terminal que vous utilisez pour consulter un site Internet, c’est-à-dire votre ordinateur, votre tablette ou votre smartphone.</p>
				<br>
				<h2>A quoi ça sert ?</h2>
				<hr class="separe">
				<p>Ces cookies permettent de stocker des informations sur votre terminal ou d’accéder à des informations qui y sont déjà stockées.</p>
				<p>En recueillant et en conservant des informations sur votre navigation, les cookies nous permettent de mieux comprendre le comportement des utilisateurs de notre site et nous aident ainsi améliorer la navigation et personnaliser nos services, tout en vous proposant la meilleure expérience possible sur le site.</p>
				<p>Les cookies ne stockent aucune donnée personnelle sensible vous concernant, et ne nous permettent pas de vous identifier, mais reconnaissent simplement l'appareil que vous êtes en train d'utiliser pour nous permettre de comprendre la façon avec laquelle vous naviguez sur le site et ainsi améliorer sa qualité et y faciliter votre navigation.</p>
				<p>En tant qu’émetteur de cookies, nous sommes seuls susceptibles de lire ou modifier les informations qui sont contenues sur les cookies que nous déposons sur votre terminal lors de votre navigation sur le site.</p>
				<br>
				<h2>Quels sont les cookies utilisés sur le site et pour quelles finalités ?</h2>
				<hr class="separe">
				<p>Pour vous rendre la navigation sur le site toujours plus agréable et facile, mais également pour mieux vous connaître, nous utilisons plusieurs types de cookies susceptibles d’être enregistrés sur votre terminal au cours de votre navigation sur notre site.</p>
				<p>Des cookies peuvent également être émis par des tiers, notamment des réseaux sociaux via l’utilisation de boutons de partage.</p>
				<p>De manière générale, les cookies peuvent être distingués en deux catégories :</p>
				<ul>
					<li>Les cookies ayant pour finalité exclusive de permettre ou faciliter la communication par voie électronique ou étant strictement nécessaires à la fourniture d'un service de communication en ligne à la demande expresse de l'utilisateur : ces cookies peuvent être déposés sans votre consentement préalable.</li>
					<li>Les autres cookies dont les finalités peuvent être variées : statistiques, mesures d’audience, partage de contenu vers les réseaux sociaux, etc. : ces cookies sont soumis à votre consentement avant d’être déposés sur votre terminal.</li>
				</ul>
				<br>
				<h2>Les cookies techniques fonctionnels indispensables</h2>
				<hr class="separe">
				<p>Les finalités de chacun des types de cookies utilisés sur le site sont décrites ci-dessous.</p>
				<p>Nous utilisons des cookies techniques qui sont indispensables à votre navigation sur le site, à son bon fonctionnement, à l’accès aux différents produits et services qui y sont proposés. Ils servent également à fluidifier votre navigation sur le site et permettre l’utilisation optimale de ses différentes fonctionnalités.</p>
				<p>Dans la mesure où ces cookies sont strictement nécessaires, ils peuvent être déposés sans votre consentement préalable.</p>
				<p>Ces cookies sont strictement nécessaires à l’utilisation du site. Si vous décidez de les désactiver, nous ne pouvons pas vous assurer un accès au site, une navigation normale et le fonctionnement correct des services qui y sont proposés.</p>
				<p>Ils nous permettent de vous proposer des services indispensables comme :</p>
				<ul>
					<li>L’accès à vos espaces personnels, tels que votre compte client ou utilisateur, et aux contenus qui vous y sont réservés sur la base des informations transmises lors de la création de votre compte.</li>
					<li>Le bon fonctionnement de votre panier d’achat, en mémorisant les produits ou services commandés jusqu’à la page de confirmation de votre commande.</li>
					<li>L’information sur la présence de cookies lors de votre première navigation sur le site, par l’affichage d’un bandeau.</li>
					<li>Adapter et optimiser la présentation du site aux préférences d'affichage de votre terminal (langue utilisée, résolution d'affichage, système d'exploitation utilisé, etc.) selon le type de terminal que vous utilisez et les logiciels de visualisation ou de lecture qu’il comporte.</li>
					<li>Vous identifier lorsque vous vous connectez sur le site, en mémorisant vos identifiants et mots de passe, afin que vous n'ayez pas à saisir ces informations manuellement à chaque visite.</li>
					<li>Faciliter vos recherches en conservant vos recherches automatiques et en mémorisant vos préférences.</li>
					<li>Améliorer votre expérience utilisateur en préremplissant certains champs des formulaires avec des informations relatives à des formulaires que vous auriez déjà renseignés sur le site.</li>
				</ul>
				<br>
				<h2>Les cookies de performance</h2>
				<hr class="separe">
				<p>Ces cookies techniques fonctionnels ne sont pas indispensables au bon fonctionnement du site mais ont pour objectif principal de faciliter votre navigation et la fourniture de services améliorés.</p>
				<p>Nous utilisons des cookies de performance pour analyser l’utilisation qui est faite du site afin d’en optimiser le fonctionnement et la performance et d’améliorer votre expérience de navigation.</p>
				<p>Ces cookies sont utilisés pour :</p>
	   			<ul>
	   				<li>Établir des statistiques de fréquentation et d’utilisation du site.</li>
					<li>Recenser le nombre de visiteurs.</li>
					<li>Analyser le nombre de pages visitées, la fréquence, la durée et la récurrence des visites.</li>
					<li>Enregistrer la façon dont vous utilisez le site.</li>
	   			</ul>
				<p>Les données statistiques obtenues ne sont pas cédées à des tiers ni utilisées à d’autres fins.</p>
				<p>Les cookies de performance que nous utilisons sont ceux proposés par GOOGLE ANALYTICS.</p>
				<br>
				<h2>Les cookies émis par des tiers</h2>
				<hr class="separe">
				<p>Le site est susceptible d’intégrer des contenus appartenant à des tiers, par exemple des modules permettant de visionner des vidéos, de diffuser de la publicité, ou de mesurer des audiences, etc.</p>
				<p>Les fournisseurs tiers de ces services peuvent déposer des cookies lorsque vous visitez le site et ainsi être informés de votre navigation sur le site.</p>
				<p>Nous vous invitons à consulter les politiques de protection des données personnelles propres à chacun de ces tiers afin de prendre connaissance des finalités d’utilisation, notamment publicitaires, des informations qu’ils peuvent recueillir grâce à ces fonctionnalités.</p>
				<br>
				<h2>Combien de temps les cookies sont-ils conservés ?</h2>
				<hr class="separe">
				<p>La durée de conservation des cookies déposés sur le site varie selon leur type.</p>
				<p>Conformément aux recommandations de la CNIL et à la réglementation relative à la protection des données personnelles, les cookies nécessitant le consentement de l’internaute doivent avoir une durée de vie limitée à 13 mois après leur premier dépôt sur son terminal, ne pouvant pas être prolongée lors de nouvelles visites sur le site.</p>
				<br>
				<h2>Comment accepter ou refuser le dépôt de cookies ?</h2>
				<hr class="separe">
				<p>Vous pouvez à tout moment exprimer ou modifier vos souhaits en matière de cookies, et notamment retirer votre consentement.</p>
				<br>
				<h2>Exercer vos choix selon le navigateur utilisé</h2>
				<hr class="separe">
				<p>Pour cela, vous devez configurer votre logiciel de navigation de manière à ce que des cookies soient enregistrés dans votre terminal ou, au contraire, qu'ils soient rejetés, soit systématiquement, soit selon leur émetteur.</p>
				<p>Vous pouvez également configurer votre logiciel de navigation de manière à ce que l'acceptation ou le refus des cookies vous soient proposés ponctuellement, avant qu'un cookie soit susceptible d'être enregistré dans votre terminal.</p>
				<p>Si vous refusez le dépôt des cookies techniques, il est possible que vous ne puissiez pas bénéficier de certaines fonctionnalités du site.</p>
	    		<p>Si vous avez décidé de ne pas donner votre consentement quant à l'utilisation des cookies qui l'exigent ou si vous l'avez révoqué, nous déclinons toute responsabilité pour les conséquences liées au comportement dégradé de notre site, ou aux différentes erreurs résultant de l’impossibilité d’utiliser les cookies nécessaires à son fonctionnement.</p>
				<p>Pour la gestion des cookies, la configuration de chaque navigateur est différente. Vous trouverez ci-après les liens ou démarches vous permettant d’accéder aux informations détaillées sur le paramétrage des cookies :</p>
				<ul>
					<li>Internet Explorer : support.microsoft.com/fr-fr/help/17442/windows-internet-explorer-delete-manage-cookies#ie=ie-11 </li>
					<li>Google Chrome : support.google.com/chrome/answer/95647?hl=fr&hlrm=en </li>
					<li>Mozilla Firefox :  support.mozilla.org/fr/kb/autoriser-bloquer-cookies-preferences-sites?redirectlocale=fr&redirectslug=activer-desactiver-cookies-preferences </li>
					<li>Safari : cliquez sur le bouton Paramètres, puis Préférences. Dans la fenêtre qui s’affiche, choisissez Confidentialité/Sécurité et cliquez sur Afficher les Cookies. Sélectionnez les Cookies que vous souhaitez désactiver puis clic sur Effacer ou sur Tout effacer. </li>
				</ul>
				<br>
				<h2>Comment détruire les fichiers “cookies” déjà installes sur votre ordinateur ? </h2>
				<hr class="separe">
				<ul>
					<li>Allez sur votre poste de travail.</li>
					<li>Sélectionnez dans C:\ le dossier Windows.</li>
					<li>Ouvrez le dossier “Temporary Internet Files”.</li>
					<li>Sélectionnez tous les fichiers (CTRL A).</li>
					<li>Choisissez l’option “supprimer”.</li>
				</ul>
				<p>Vous trouverez également des informations supplémentaires sur le site de la CNIL : www.cnil.fr/fr/cookies-les-outils-pour-les-maitriser </p>
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
