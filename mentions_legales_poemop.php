<?php
include_once 'inc/dev_auth.php';
session_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";

$desc = 'Mentions légales de vos commandes de fioul moins cher POEMOP.';
$title = 'Mentions légales de vos commandes de fioul moins cher';
ob_start();

include 'modules/menu.php';
?>
<div class="container-fluid">
	<div class="header mentions">
		<div class="row">
			<div class="col-sm-9">
				<div class="text-center">
					<h1>Mentions légales</h1>
					<div class="ligne-center jaune"></div>
				</div>
				<h2>La société</h2>
				<hr class="separe">
				<p>Le site web plus-on-est-moins-on-paie.fr est la propriété de la SAS Plus On est Moins On Paie. Immatriculée au RCS de Lyon sous le numéro : 503.534.372</p>
				<p> <strong>code APE : </strong>4791A<br>
					<strong>Capital : </strong>1000 €<br>
					<strong>TVA intracommunautaire : </strong>FR12503534372<br>
					<strong>Siège social : </strong>2 rue de l'Ardoise, Port Edouard Herriot, 69007 Lyon<br>
					<strong>Responsable de rédaction : </strong>Anna MANZONI<br>
					<strong>Mail : </strong><a href="mailto:contact@poemop.fr">contact@poemop.fr</a>
				</p>
				<br>
				<h2>Hébergement</h2>
				<hr class="separe">
				<p>Le site web plus-on-est-moins-on-paie.fr est hébergé par NUXIT :</p>
				<p> <strong>Société : </strong>Nuxit – Magic Online<br>
					<strong>Site internet : </strong>www.nuxit.com<br>
					<strong>Adresse : </strong>130-134 Avenue du Président Wilson 93512 Montreuil Cedex<br>
					<strong>Téléphone : </strong>+33 (0)4 86 57 6000<br>
				</p>
				<br>
				<h2>Propriété intellectuelle</h2>
				<hr class="separe">
				<p>L'ensemble du contenu du site plus-on-est-moins-on-paie.fr est la propriété de la SAS Plus On est Moins On Paie. Tous les droits d'utilisation lui sont réservés. L’utilisateur reconnaît et accepte que le contenu du site plus-on-est-moins-on-paie.fr (notamment les textes, logiciels, architecture, photographies, illustrations, logos, marques, etc.) est protégé par les droits de propriété intellectuelle. Dans ces conditions, l’utilisateur ne peut en aucun cas utiliser le contenu du site plus-on-est-moins-on-paie.fr sans l'accord préalable et écrit de celle-ci.</p>
				<p>Aucune responsabilité ne peut être retenue à l'encontre de la SAS Plus On est Moins On Paie quant au contenu des sites référencés inclus dans l'index, ni pour toute erreur ou omission contenue sur lesdits sites Internet, ni pour tout contenu non objectif, licite ou illicite diffusé sur lesdits sites Internet.</p>
				<p>L’utilisateur reconnaît qu'il doit faire preuve de discernement et supporter tous les risques afférents à l'utilisation qu'il fait du contenu et, notamment, lorsqu'il se fie à l'opportunité, l'utilité ou le caractère complet de ce contenu, qu'il soit créé par la SAS Plus On est Moins On Paie ou par des tiers accessibles à partir du site plus-on-est-moins-on-paie.fr, par des liens et notamment par ses services de recherche.</p>
				<br>
				<h2>Liens hypertextes</h2>
				<hr class="separe">
				<p>Le site plus-on-est-moins-on-paie.fr se réserve le droit de créer des liens hypertextes dirigeant vers des sites Internet tiers ou d'autres sources Internet. Des tiers peuvent créer des liens hypertextes dirigeants vers le site plus-on-est-moins-on-paie.fr. Dans la mesure où la SAS Plus On est Moins On Paie ne peut contrôler ces sites et ces sources externes, l’utilisateur reconnaît que la SAS Plus On est Moins On Paie ne peut être tenue pour responsable de la mise à disposition de ces sites et sources externes. La SAS Plus On est Moins On Paie ne peut, par conséquent, être tenue pour responsable quant aux contenus, publicités, produits, services ou tout autre matériel disponibles sur ou à partir de ces sites ou sources externes. En outre, l’utilisateur reconnaît que la SAS Plus On est Moins On Paie ne peut être tenue pour responsable de tous dommages ou pertes avérés ou allégués, consécutifs ou en relation avec l'utilisation ou avec le fait d'avoir fait confiance au contenu, à des biens ou des services disponibles sur ou à partir de ces sites ou sources externes.</p>
				<br>
				<h2>Responsabilité</h2>
				<hr class="separe">
				<p>L’utilisateur accepte de dégager la SAS Plus On est Moins On Paie, ses dirigeants, agents, préposés, prestataires ou employés, de toute responsabilité pour tous les préjudices résultant de l'utilisation du site plus-on-est-moins-on-paie.fr, à ce titre, et sans limiter la portée des autres dispositions des présentes conditions générales d’utilisation. La SAS Plus On est Moins On Paie ne peut notamment être considérée, hors le cas de sa faute directe, comme responsable des dommages résultants : de la perte, de l'altération ou de l'accès frauduleux à des données ; de la transmission accidentelle par le biais du service ou par voie de courrier électronique de virus ou d'autres éléments nuisibles ; de l'attitude, de la conduite ou du comportement d'un tiers ou d'un autre utilisateur.</p>
				<p>Les réparations dues par la SAS Plus On est Moins On Paie en cas de défaillance qui résulteraient d'une faute établie à son encontre correspondront au seul préjudice direct. Ces dommages indirects sont ceux qui ne résultent pas directement et exclusivement de la défaillance des prestations de la SAS Plus On est Moins On Paie.</p>
				<p>La SAS Plus On est Moins On Paie ne garantit pas que le service proposé soit continu, sans interruption provisoire, sans suspension ou sans erreur. La SAS Plus On est Moins On Paie se réserve le droit de suspendre ou de retirer un bien ou un service, pour des raisons techniques ou en cas de violation des stipulations contractuelles et/ou des dispositions légales. La SAS Plus On est Moins On Paie ne peut engager sa responsabilité pour les dommages consécutifs à de tels
				faits.</p>
				<p>La SAS Plus On est Moins On Paie s'efforce à tout moment de fournir des informations correctes et précises. Il n'est assumé aucune responsabilité pour d'éventuels manques ou erreurs. Si vous constatez des erreurs ou des omissions, veuillez-vous adresser à La SAS Plus On est Moins On Paie.</p>
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
