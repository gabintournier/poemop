<?php
include_once 'inc/dev_auth.php';
session_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";

$desc = 'Conditions générales de vente de fioul de moins cher POEMOP.';
$title = 'Conditions générales de vente de fioul moins cher';
ob_start();

include 'modules/menu.php';
?>
<div class="container-fluid">
	<div class="header mentions">
		<div class="row">
			<div class="col-sm-9">
				<div class="text-center">
					<h1>Conditions générales d’utilisation</h1>
					<div class="ligne-center jaune"></div>
					<p>Les présentes conditions générales d'utilisation ont pour objet de définir les termes et conditions auxquelles le site internet plus-on-est-moins-on-paie.fr offre la possibilité à l'utilisateur de réaliser des devis et des commandes de fioul.</p>
				</div>
				<h2>Définitions</h2>
				<hr class="separe">
				<p>« Distributeur de fioul » ou « Fournisseur de fioul » : société assurant la vente et la distribution de produits pétroliers.</p>
				<br>
				<h2>Acceptation des conditions générales d’utilisation</h2>
				<hr class="separe">
				<p>Le service est proposé à l’utilisateur sous réserve de son acceptation inconditionnelle des présentes conditions générales d'utilisation. L’utilisateur déclare et reconnaît, avoir lu, compris et accepté sans réserve les présentes conditions générales d'utilisation.</p>
				<p>Le site internet plus-on-est-moins-on-paie.fr se réserve le droit de modifier en tout ou partie et à tout moment les présentes conditions générales d’utilisation. Il appartient en conséquence à l’utilisateur de se référer régulièrement à la dernière version des conditions générales d’utilisation disponible en permanence sur le site internet plus-on-est-moins-on-paie.fr. L’utilisateur est tenu d’accepter cette dernière version à chaque nouvelle utilisation du service.</p>
				<p>En cas de non-respect des présentes conditions générales d’utilisation, le site internet plus-on-est-moins-on-paie.fr se réserve le droit, sans aucune indemnité ni préavis, de suspendre l’accès de l’utilisateur et de lui refuser pour l'avenir l'accès à tout ou partie du service, sans préjudice des différentes actions délictuelles ou contractuelles de droit commun qui pourraient lui être ouvertes.</p>
				<br>
				<h2>Demande de devis par l’utilisateur</h2>
				<hr class="separe">
				<p>La demande de devis est gratuite, anonyme et sans engagement, elle a une durée limitée dans le temps.</p>
				<p>L’utilisateur s’engage à demander des devis pour des besoins concrets et réels, dans le but d’obtenir une livraison potentielle.</p>
				<p>L’utilisateur s’interdit de demander des devis fantaisistes ou ayant simplement pour but de consulter les prix. Des contrôles peuvent être effectués par le site internet plus-on-est-moins-on-paie.fr.</p>
				<p>Toute utilisation abusive ou fantaisiste pourra faire l’objet de poursuites judiciaires.</p>
				<br>
				<h2>Commande de produits par l’utilisateur</h2>
				<hr class="separe">
				<p>L'utilisateur s'engage à fournir des informations exactes et complètes et à actualiser le cas échéant les informations fournies dans les plus brefs délais. L'utilisateur pourra procéder à la modification des informations concernant sa commande par mail. Au cas où l'une de ces informations s'avérerait fausse, incomplète ou obsolète, le site internet plus-on-est-moins-on-paie.fr se réserve le droit, sans aucune indemnité et sans préavis, de suspendre ou d’annuler sa commande. Le site internet plus-on-est-moins-on-paie.fr ne pourra être tenu responsable de toute perte ou dommage survenus en raison du manquement aux obligations incombant à l'utilisateur.</p>
				<p>L'utilisateur doit saisir les informations indispensables au traitement de sa commande notamment ses coordonnées ainsi que les renseignements supplémentaires demandés.</p>
				<p>La validation définitive de la commande ne peut se faire que par l'intermédiaire du site plus-on-est-moins-on-paie.fr</p>
				<p>Les systèmes d'enregistrement des commandes mis en œuvre par le site internet plus-on-est-moins-on-paie.fr sont expressément admis comme valant preuve de la nature, du contenu, du prix et de la date de la commande par l’utilisateur.</p>
				<p>Dès validation de la commande, le site internet plus-on-est-moins-on-paie.fr confirme à l’utilisateur la commande et son montant total. Cette confirmation est effectuée par courrier électronique à l'adresse communiquée par l’utilisateur. Il est également stipulé, quelque soit le montant de la commande, que le contrat de vente n'est finalisé qu'à compter de la confirmation de la commande par le distributeur de fioul sélectionné. Dans l'hypothèse où l'un des produits de la commande ne serait plus disponible à la vente, le site internet annulera plus-on-est-moins-on-paie.fr ou reportera la commande.</p>
				<p>Le contrat de vente est passé entre l’acheteur et le distributeur de fioul. Le rôle du site internet plus-on-est-moins-on-paie.fr se limite à la mise en relation des deux parties. La réalisation de ce contrat (rendez-vous, livraison, paiement...) ne rentre pas dans le cadre de la prestation fournie pas le site internet plus-on-est-moins-on-paie.fr.</p>
				<p>Conformément à la loi du 6 janvier 1988 et à la directive n° 97.7 du 20 mai 1997, l’utilisateur dispose d'un délai de rétractation de sept jours francs à compter de la réception de la marchandise pour retourner tout produit au fournisseur pour échange ou remboursement sans pénalités. Ce droit ne peut s'appliquer aux produits pétroliers.</p>
				<p>L’utilisateur s'interdit de revendre les produits. Toute commande passée par un utilisateur est pour son usage personnel ou professionnel, ou pour l'usage personnel ou professionnel de la personne au nom de laquelle la livraison doit être effectuée.</p>
				<br>
				<h2>Modalités de paiement</h2>
				<hr class="separe">
				<p>Le paiement des produits commandés par l’utilisateur par l'intermédiaire du site internet plus-on-est-moins-on-paie.fr s'effectue à la livraison des marchandises auprès de la société effectuant cette livraison. Si plusieurs modes de paiement sont disponibles, les modalités doivent être clairement définis lors de la prise du rendez-vous. Le paiement des produits commandés par l'utilisateur est effectué en Euros.</p>
				<br>
				<h2>Remise de produits</h2>
				<hr class="separe">
				<p>L’utilisateur doit être présent au lieu, date et, le cas échéant, créneau horaire défini avec le distributeur de fioul afin de réceptionner sa commande.</p>
				<p>Lors de la remise, l’utilisateur doit être en mesure de prouver son identité et de fournir la référence de sa commande figurant sur la confirmation envoyée par le site internet plus-on-est-moins-on-paie.fr. L’utilisateur s'engage à signer le bon d'émargement présenté par la personne chargée de la remise. Par la signature du bon d'émargement, l’utilisateur reconnaît avoir reçu les produits objets de sa commande. Sur le bon d'émargement, l’utilisateur peut porter toute mention qui lui semble nécessaire. Dans l'hypothèse où l’utilisateur ne serait pas en mesure de recevoir personnellement les produits qu'il a commandés, il pourra donner procuration à un tiers de réceptionner la livraison. Cette personne pourra alors signer le bon d'émargement et faire toute remarque utile sur ce dernier.</p>
				<p>La livraison sera réalisée selon les informations fournies lors de la validation de la commande et après accord du distributeur. Dans l'hypothèse où le distributeur de fioul se trouve dans l'impossibilité de respecter les délais prévus, l’utilisateur en est informé par tout moyen tel que courrier électronique, appel ou téléphonique ou SMS.</p>

				<br>
				<h2>Responsabilité et garantie</h2>
				<hr class="separe">
				<p>L’utilisateur demeure seul juge du contenu approprié et adapté des produits commandés à ses besoins et à sa consommation. Eu égard à la nature des produits proposés par le site internet plus-on-est-moins-on-paie.fr, l’utilisateur renonce à se prévaloir d’une éventuelle obligation de renseignement ou de conseil qui pèserait sur le site internet plus-on-est-moins-on-paie.fr à son égard ; de la même façon, il renonce au bénéfice d’une éventuelle garantie de conformité quant à l’usage attendu des produits qu’il commande par l’intermédiaire du site internet plus-on-est-moins-on-paie.fr.</p>
				<p>L’utilisateur est informé qu’il ne pourra pas rechercher la responsabilité du site internet plus-on-est-moins-on-paie.fr au titre de la responsabilité du fait des produits défectueux. L’utilisateur ne pourra rechercher la responsabilité de la société ayant effectué la livraison au titre de la responsabilité du fait des produits défectueux que, conformément aux dispositions de l’article 1386-9 du Code civil, s’il justifie d’un défaut affectant le produit que lui aurait vendu la société ayant effectué la livraison, l’existence d’un préjudice spécifique, et d’un lieu de causalité entre ce défaut et ce préjudice.</p>
				<p>L’utilisateur accepte de dégager le site internet plus-on-est-moins-on-paie.fr, la SAS Plus-on-est-moins-on-paie, ses dirigeants, agents, préposés, prestataires ou employés, de toute responsabilité pour tous les préjudices résultant de l'utilisation du site plus-on-est-moins-on-paie.fr, à ce titre, et sans limiter la portée des autres dispositions des présentes conditions générales d’utilisation. Le site internet plus-on-est-moins-on-paie.fr ne peut notamment être considéré, hors le cas de sa faute directe, comme responsable des dommages résultants : de la perte, de l'altération ou de l'accès frauduleux à des données ; de la transmission accidentelle par le biais du service ou par voie de courrier électronique de virus ou d'autres éléments nuisibles ; de l'attitude, de la conduite ou du comportement d'un tiers ou d'un autre utilisateur ; de la non-conclusion d'une vente ou d'un achat.</p>
				<p>Les réparations dues par le site internet plus-on-est-moins-on-paie.fr en cas de défaillance qui résulteraient d'une faute établie à son encontre correspondront au seul préjudice direct. Ces dommages indirects sont ceux qui ne résultent pas directement et exclusivement de la défaillance des prestations du site internet plus-on-est-moins-on-paie.fr, et notamment le défaut de conformité des produits livrés.</p>
				<p>Le site internet plus-on-est-moins-on-paie.fr ne garantit pas que le service proposé soit continu, sans interruption provisoire, sans suspension ou sans erreur. Le site internet plus-on-est-moins-on-paie.fr se réserve le droit de suspendre ou de retirer un bien ou un service, pour des raisons techniques ou en cas de violation des stipulations contractuelles et/ou des dispositions légales. Le site internet plus-on-est-moins-on-paie.fr ne peut engager sa responsabilité pour les dommages consécutifs à de tels faits.</p>
				<p>Le site internet plus-on-est-moins-on-paie.fr s'efforce à tout moment de fournir des informations correctes et précises. Il n'est assumé aucune responsabilité pour d'éventuels manques ou erreurs. Si vous constatez des erreurs ou des omissions, veuillez vous adresser au site internet plus-on-est-moins-on-paie.fr</p>
				<br>
				<h2>Force majeure</h2>
				<hr class="separe">
				<p>Tous les événements, de quelque nature qu'ils soient, échappant à la volonté du fournisseur et/ou du site internet plus-on-est-moins-on-paie.fr, irrésistibles, imprévisibles et tendant à retarder ou à empêcher l'exécution de la commande constituent, de convention expresse, une cause de suspension et/ou d'extinction des obligations du fournisseur et/ou du site internet plus-on-est-moins-on-paie.fr envers l’utilisateur, sans indemnité au profit du l’utilisateur, qui ne pourra prétendre, le cas échéant, qu’au remboursement de sa commande, si cette dernière était prépayée.</p>
				<p>Sans limiter la portée des autres dispositions des présentes conditions générales d'utilisation, la responsabilité du site internet plus-on-est-moins-on-paie.fr, qu'elle soit délictuelle ou contractuelle, ne peut être engagée pour des faits dus à un cas fortuit ou au fait d'un tiers ou de la victime du dommage. L'utilisateur reconnaît que constituent notamment un cas fortuit les pannes et les problèmes d'ordre technique concernant le matériel, les programmes et logiciels informatiques ou le réseau Internet, ces problèmes ne se limitant pas aux interruptions, suspensions ou fermetures du service. Il reconnaît, par conséquent, que le site internet plus-on-est-moins-on-paie.fr ne peut être tenu pour responsable des dommages liés à ces problèmes.</p>
				<br>
				<h2>Modification et interruption de service</h2>
				<hr class="separe">
				<p>Le site internet plus-on-est-moins-on-paie.fr se réserve le droit, à tout moment, de modifier ou d’interrompre de manière temporaire tout ou partie du service proposé pour des raisons techniques ou de mise en conformité de l’utilisateur avec les stipulations contractuelles ou les dispositions légales, et ce sans avoir à en informer préalablement l’utilisateur. L’utilisateur ne peut engager la responsabilité du site internet plus-on-est-moins-on-paie.fr à raison des modifications et interruptions effectuées.</p>
				<br>
				<h2>Résiliation pour faute de l’utilisateur</h2>
				<hr class="separe">
				<p>En cas de violations ou d'agissements par l’utilisateur contraires aux présentes conditions générales d’utilisation, le site internet plus-on-est-moins-on-paie.fr est en droit à tout moment, avec effet immédiat et sans mise en demeure préalable de supprimer ou d’interdire tout accès au site ou aux services. Enfin, l’utilisateur reconnaît que le site internet plus-on-est-moins-on-paie.fr ne pourra être tenu pour responsable à son encontre ou à l'encontre de tiers pour toute résiliation de son accès au service motivée par la violation des présentes Conditions Générales d'Utilisation et/ou de dispositions légales.</p>
				<br>
				<h2>Intégralité des engagements</h2>
				<hr class="separe">
				<p>Les présentes conditions générales d'utilisation constituent la totalité de l'accord passé entre le site internet plus-on-est-moins-on-paie.fr et l’utilisateur pour ce qui concerne l'utilisation du service. Elles se substituent à tout accord éventuellement intervenu antérieurement entre le site internet plus-on-est-moins-on-paie.fr et l’utilisateur.</p>
				<br>
				<h2>Divers</h2>
				<hr class="separe">
				<p>Le défaut d’exercice par le site internet plus-on-est-moins-on-paie.fr des droits qui lui sont reconnus par les termes des présentes conditions générales d'utilisation ne constitue pas une renonciation à faire valoir ses droits. Si une ou plusieurs dispositions des présentes conditions générales d’utilisation sont déclarées nulles ou caduques par application d’une loi ou d’une décision judiciaire ou administrative, les autres dispositions conservent force obligatoire.</p>
				<br>
				<h2>Loi applicable</h2>
				<hr class="separe">
				<p>Les présentes conditions générales d'utilisation sont soumises au droit français. Vous reconnaissez explicitement les dispositions précédentes par l'utilisation de ce site web.</p>
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
