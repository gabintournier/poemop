<?php
include_once 'inc/dev_auth.php';
session_start();
$desc = 'Fonctionnement de POEMOP organisateur de commandes goupées de fioul domestique et d\'achats groupés.';
$title = 'Comment ça marche les commandes groupées de fioul POEMOP et d\'achats groupés';
ob_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";
?>

<?php
include 'modules/menu_fioul.php';
?>

<div class="container-fluid">
	<div class="header groupe">
		<div class="row">
			<div class="col align-self-center">
				<h1>Comment ça marche les commandes groupées de fioul ?</h1>
				<!-- <p>Vous devez tout d'abord créer un compte gratuitement. Nous vous avertirons quand un groupement sera lancé sur votre secteur.
				En fonction du nombre de personnes inscrites au groupement, nous négocions avec des fournisseurs locaux et nous vous proposons le tarif le plus intéressant. Vous pourrez ensuite accepter ou refuser la proposition.
				Si vous acceptez, le fournisseur vous contactera pour fixer le rendez-vous et vous le paierez directement à la livraison.</p> -->
				<div class="inscription-fioul text-center" style="padding:0!important">
					<div class="row">
						<div class="col align-self-center">
							<div class="bbox">
								<img src="images/inscription-gratuite-poemop.svg" alt="entièrement gratuit et transparent pour vous">
								<p>Je m'inscris gratuitement<br><span>et sans engagement</span></p>
							</div>
						</div>
						<div class="col">
							<div class="bbox">
								<img src="images/economies-groupement-poemop.svg" alt="traitement direct avec le fournisseur">
								<p>Je fais des économies<br><span>en participant au groupement</span></p>
							</div>
						</div>
						<div class="col align-self-center">
							<div class="bbox">
								<img src="images/livraison-poemop-violet.svg" alt="livraison tranquille et paiement direct" style="width: 25%;">
								<p>Je suis livré et je paie<br><span>directement le livreur</span></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col">
				<img src="images/header-groupement-fioul.svg" alt="Comment ça marche les commandes groupées de fioul POEMOP ?" style="width: 70%;display: block;margin: 0 auto;">
			</div>
		</div>
	</div>
	<div class="section">
		<div class="row">
			<div class="col-sm-9 col-mobile">
				<hr class="separe">
				<div class="bloc-questions">						
					<h2 style="text-align:center;">Foire Aux Questions</h2>		
					<div class="questions inscriptions text-center">
						<img src="images/inscription-gratuite-poemop.svg" alt="J'ai une question sur mon inscription">
						<h2>J'ai une question<br>sur mon inscription</h2>
						<div class="ligne-center jaune"></div>
					</div>

					<div class="accordion accordion-flush" id="accordionInscriptions">
	  					<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingOne">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
	        						<i class="far fa-bookmark"></i> Comment s'inscrire ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionInscriptions">
								<div class="accordion-body">
									Pour vous inscrire, cliquez sur le bouton <a href="creer_un_compte_poemop.php">créer un compte</a> situé à droite de la page d'accueil. Après avoir saisi votre adresse mail, un mot de passe de votre choix et votre code postal, vous recevrez un lien d'activation. Pensez à renseigner vos coordonnées complètes afin que nous puissions vous proposer des groupements.
									<div class="text-center">
										<a class="btn btn-secondary" href="creer_un_compte_poemop.php" title="Inscription poemop">Créer un compte</a>
									</div>
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingTwo">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
	        						<i class="far fa-bookmark"></i> Je me suis inscrit mais je n'ai pas reçu le mail d'activation de mon compte
	      						</button>
	    					</h3>
	    					<div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionInscriptions">
								<div class="accordion-body">
									Avez-vous pensé à consulter vos SPAM ou mails indésirables ? Si tel est le cas et que vous n'avez rien reçu, <a href="contacter_poemop.php">contactez-nous</a> (en nous précisant votre adresse mail et votre code postal).
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingThree">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
	        						<i class="far fa-bookmark"></i> Ma commune est-elle desservie par POEMOP ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionInscriptions">
								<div class="accordion-body">
									Pour savoir si un groupement est prévu dans votre commune, veuillez créer un compte en indiquant votre code postal. Nous vous alerterons ensuite par mail de la date du prochain groupement de commandes de fioul qui sera organisé près de chez vous.
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingFour">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
	        						<i class="far fa-bookmark"></i> Comment se désinscrire ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionInscriptions">
								<div class="accordion-body">
									Rendez-vous dans votre compte, à la rubrique <a href="contacter_poemop.php">mon compte</a> ou <a href="contacter_poemop.php">contactez-nous</a> (en nous indiquant le motif de votre désabonnement). Nous vous supprimerons de notre base de données et de notre site, conformément à la loi « Informatique et Libertés » N° 78-17 du 6 Janvier 1978, qui vous permet de bénéficier d’un droit d’accès, de rectification et d’opposition.
								</div>
	    					</div>
	  					</div>
					</div>
					<hr class="separe">
					<div class="questions inscriptions-group text-center">
						<img src="images/rejoignez-poemop.svg" alt="J'ai une question sur mon inscription">
						<h2>J'ai une question avant de m'inscrire<br>à un groupement</h2>
						<div class="ligne-center jaune"></div>
					</div>
					<div class="accordion accordion-flush" id="accordionInscriptionsGroup">
	  					<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingOne-group">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne-group" aria-expanded="false" aria-controls="flush-collapseOne-group">
	        						<i class="far fa-bookmark"></i> Quel est le prix du fioul sur ma commune ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseOne-group" class="accordion-collapse collapse" aria-labelledby="flush-headingOne-group" data-bs-parent="#accordionInscriptionsGroup">
								<div class="accordion-body">
									Il nous est impossible de vous donner à l’avance le prix du fioul que vous paierez en commandant sur notre site. Pour recevoir une proposition de tarif, vous devez en premier lieu être inscrit sur notre site et en second lieu vous inscrire au groupement lorsqu'il sera lancé sur votre secteur (sans frais et sans engagement).
									<div class="text-center" style="margin-top:10px;">
										<a class="btn btn-secondary" href="creer_un_compte_poemop.php" title="Inscription poemop">Créer un compte</a>
									</div>
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingTwo-group">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo-group" aria-expanded="false" aria-controls="flush-collapseTwo-group">
	        						<i class="far fa-bookmark"></i> Quand sera lancé le prochain groupement sur mon secteur ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseTwo-group" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo-group" data-bs-parent="#accordionInscriptionsGroup">
								<div class="accordion-body">
									Si vous n'avez pas encore reçu de mail vous invitant à la prochaine opération de groupement, c'est qu'il n'y en a pas encore sur votre commune.
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingThree-group">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree-group" aria-expanded="false" aria-controls="flush-collapseThree-group">
	        						<i class="far fa-bookmark"></i> Si je m'inscris à un groupement, suis-je engagé ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseThree-group" class="accordion-collapse collapse" aria-labelledby="flush-headingThree-group" data-bs-parent="#accordionInscriptionsGroup">
								<div class="accordion-body">
									L'inscription au groupement nous permet de négocier les tarifs en fonction du litrage réuni. Vous pourrez librement accepter ou refuser la proposition de tarif qui vous sera faite.
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingFour-group">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour-group" aria-expanded="false" aria-controls="flush-collapseFour-group">
	        						<i class="far fa-bookmark"></i> Y a-t-il un volume minimum imposé pour passer commande ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseFour-group" class="accordion-collapse collapse" aria-labelledby="flush-headingFour-group" data-bs-parent="#accordionInscriptionsGroup">
								<div class="accordion-body">
									"Afin de pouvoir participer au groupement de votre secteur, vous devez commander au minimum 500 litres de fioul. Vous aurez la possibilité de modifier le volume souhaité au moment de valider le tarif proposé. Vous devez vous rapprocher au mieux du volume livrable ; pour cela vous devez estimer le volume disponible dans votre cuve. Vous ne serez facturé que de la quantité livrée, cependant, des frais supplémentaires pourront être appliqués par le distributeur s'il constate un écart considérable entre la quantité commandée et ce qui rentre réellement dans la cuve (CGV du distributeur qui feront foi).
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingSix-group">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix-group" aria-expanded="false" aria-controls="flush-collapseSix-group">
	        						<i class="far fa-bookmark"></i> Dois-je commander du fioul de qualité ordinaire ou du fioul supérieur ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseSix-group" class="accordion-collapse collapse" aria-labelledby="flush-headingSix-group" data-bs-parent="#accordionInscriptionsGroup">
								<div class="accordion-body">
									Le fioul ordinaire est un fioul de bonne qualité qui répond aux réglementations en vigueur. Ce type de fioul est moins cher que le fioul de qualité supérieure car il ne contient pas d'additifs. Les additifs contenus dans le fioul supérieur permettent de garder une chaudière plus propre et d'obtenir un meilleur rendement. De plus, le fioul supérieur a une résistance au froid nettement plus élevée qui lui permet de rester fluide jusqu'à des températures de -20°C environ (contre -4°C pour du fioul ordinaire). Cela dépendra également de la disponibilité chez le distributeur qui sera sélectionné pour le groupement.
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingSeven-group">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven-group" aria-expanded="false" aria-controls="flush-collapseSeven-group">
	        						<i class="far fa-bookmark"></i> Je souhaite commander pour quelqu'un d'autre, comment faire ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseSeven-group" class="accordion-collapse collapse" aria-labelledby="flush-headingSeven-group" data-bs-parent="#accordionInscriptionsGroup">
								<div class="accordion-body">
									Si votre voisin dispose d'une adresse mail, le plus simple est qu'il crée un compte sur www.poemop.fr. S'il n'a pas d'adresse mail, nous pouvons passer sa commande manuellement. Pour cela, vous devrez nous envoyer par mail les coordonnées complètes de votre voisin (nom, prénom, adresse, numéro de téléphone) ainsi que les quantité et qualité souhaités.
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingEight-group">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEight-group" aria-expanded="false" aria-controls="flush-collapseEight-group">
	        						<i class="far fa-bookmark"></i> Pourquoi n'ai-je pas encore reçu la proposition de tarif ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseEight-group" class="accordion-collapse collapse" aria-labelledby="flush-headingEight-group" data-bs-parent="#accordionInscriptionsGroup">
								<div class="accordion-body">
									Nous donnons les dates des groupements à titre indicatif, elles sont susceptibles d'être modifiées. Nous négocions les tarifs avec les différents fournisseurs le matin. Une fois le meilleur prix sélectionné, la proposition du tarif vous est envoyée l'après-midi. Parfois, il se peut que les négociations prennent plus de temps, aussi la proposition de tarif peut exceptionnellement être envoyée le lendemain.</div>
	    					</div>
	  					</div>
					</div>
					<hr class="separe">

					<div class="questions inscriptions-tarifs text-center">
						<img src="images/economies-groupement-poemop.svg" alt="J'ai une question sur mon inscription">
						<h2>J'ai une question avant de répondre<br>à la proposition de tarif</h2>
						<div class="ligne-center jaune"></div>
					</div>
					<div class="accordion accordion-flush" id="accordionTarifs">
	  					<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingOne-tarifs">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne-tarifs" aria-expanded="false" aria-controls="flush-collapseOne-tarifs">
	        						<i class="far fa-bookmark"></i> Qui sera le fournisseur qui me livrera ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseOne-tarifs" class="accordion-collapse collapse" aria-labelledby="flush-headingOne-tarifs" data-bs-parent="#accordionTarifs">
								<div class="accordion-body">
									Nous ne sommes pas habilité à vous communiquer le nom du fournisseur avant validation de votre commande. Mais sachez que nous accordons une attention particulière à sélectionner des fournisseurs de qualité et proches de chez vous.
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingTwo-tarifs">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo-tarifs" aria-expanded="false" aria-controls="flush-collapseTwo-tarifs">
	        						<i class="far fa-bookmark"></i> Combien de temps ai-je pour valider la proposition de tarif ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseTwo-tarifs" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo-tarifs" data-bs-parent="#accordionTarifs">
								<div class="accordion-body">
									Vous avez 24h pour accepter ou refuser la proposition qui vous sera faite. Le prix du fioul changeant tous les jours, les fournisseurs ne peuvent maintenir les tarifs proposés plus longtemps.
								</div>
	    					</div>
	  					</div>
					</div>
					<hr class="separe">

					<div class="questions livraison text-center">
						<img src="images/livraison-poemop.svg" alt="J'ai une question sur mon inscription">
						<h2>J'ai une question<br>sur ma livraison</h2>
						<div class="ligne-center jaune"></div>
					</div>

					<div class="accordion accordion-flush" id="accordionTarifsLivraison">
	  					<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingOne-Livraison">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne-Livraison" aria-expanded="false" aria-controls="flush-collapseOne-Livraison">
	        						<i class="far fa-bookmark"></i> Quand serai-je livré ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseOne-Livraison" class="accordion-collapse collapse" aria-labelledby="flush-headingOne-Livraison" data-bs-parent="#accordionTarifsLivraison">
								<div class="accordion-body">
									Lorsque vous validez votre commande, vos coordonnées sont envoyées au fournisseur. Ce dernier vous rappelle au plus vite afin de fixer un rendez-vous pour la livraison. Nous ne gérons pas les plannings de livraison. Vous êtes livré généralement sous 1 à 5 jours en fonction de vos disponibilités et de celles du distributeur. Les délais peuvent être rallongés, notamment en cas de conjoncture difficile (guerres, tensions économiques, etc). Les dates de livraison sont données dans votre espace <a href="contacter_poemop.php">ma commande</a>. Au besoin, si votre commande est urgente, vous pouvez nous le signaler, nous transmettrons l'information au fournisseur.
									<div class="text-center">
										<a class="btn btn-secondary" href="creer_un_compte_poemop.php" title="Inscription poemop">Ma commande</a>
									</div>
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingTwo-Livraison">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo-Livraison" aria-expanded="false" aria-controls="flush-collapseTwo-Livraison">
	        						<i class="far fa-bookmark"></i> Comment demander un jour de livraison précis ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseTwo-Livraison" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo-Livraison" data-bs-parent="#accordionTarifsLivraison">
								<div class="accordion-body">
									POEMOP ne gère pas les plannings de livraison du fournisseur chargé de votre commande. Une fois que vous avez validé votre commande, c'est le fournisseur qui vous contactera au plus vite pour convenir d'un rendez-vous. Vous avez la possibilité de nous communiquer par mail vos disponibilités et nous les transmettrons au fournisseur pour l'en informer.
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingThree-Livraison">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree-Livraison" aria-expanded="false" aria-controls="flush-collapseThree-Livraison">
	        						<i class="far fa-bookmark"></i> Ma commande est urgente, comment faire ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseThree-Livraison" class="accordion-collapse collapse" aria-labelledby="flush-headingThree-Livraison" data-bs-parent="#accordionTarifsLivraison">
								<div class="accordion-body">
									Si votre commande est urgente, vous pouvez nous contacter lors de la validation de votre commande et nous préviendrons le fournisseur pour qu'il vous contacte au plus vite.
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingFour-Livraison">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour-Livraison" aria-expanded="false" aria-controls="flush-collapseFour-Livraison">
	        						<i class="far fa-bookmark"></i> Je ne suis pas encore livré et la date de livraison donnée sur le site est dépassée. Comment contacter le fournisseur ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseFour-Livraison" class="accordion-collapse collapse" aria-labelledby="flush-headingFour-Livraison" data-bs-parent="#accordionTarifsLivraison">
								<div class="accordion-body">
									Le numéro de téléphone du fournisseur vous a été donné dans le mail de confirmation de commande.
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingSix-Livraison">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix-Livraison" aria-expanded="false" aria-controls="flush-collapseSix-Livraison">
	        						<i class="far fa-bookmark"></i> Comment annuler ma commande ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseSix-Livraison" class="accordion-collapse collapse" aria-labelledby="flush-headingSix-Livraison" data-bs-parent="#accordionTarifsLivraison">
								<div class="accordion-body">
									<a href="contacter_poemop.php">contactez-nous</a> pour annuler votre commande avant que le distributeur n'ait pris rendez-vous avec vous.
								</div>
	    					</div>
	  					</div>
					</div>

					<hr class="separe">

					<div class="questions facture text-center">
						<img src="images/facture-poemop.svg" alt="J'ai une question sur ma facture">
						<h2>J'ai une question<br>sur ma facture</h2>
						<div class="ligne-center jaune"></div>
					</div>

					<div class="accordion accordion-flush" id="accordionFacture">
	  					<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingOne-Facture">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne-Facture" aria-expanded="false" aria-controls="flush-collapseOne-Facture">
	        						<i class="far fa-bookmark"></i> Combien serai-je facturé ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseOne-Facture" class="accordion-collapse collapse" aria-labelledby="flush-headingOne-Facture" data-bs-parent="#accordionFacture">
								<div class="accordion-body">
									Le prix donné dans la proposition de tarif s'entend TTC livré. Le service rendu par POEMOP est gratuit. Vous serez facturé par le distributeur du volume livré et non du volume commandé. Mais attention à bien pouvoir vous faire livrer la quantité minimum de votre plage de tarif.
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingTwo-Facture">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo-Facture" aria-expanded="false" aria-controls="flush-collapseTwo-Facture">
	        						<i class="far fa-bookmark"></i> Quand dois-je payer ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseTwo-Facture" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo-Facture" data-bs-parent="#accordionFacture">
								<div class="accordion-body">
									Vous devrez payer la facture du distributeur lors de la livraison (et les mois suivants si vous avez opté pour un paiement en plusieurs fois).
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingThree-Facture">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree-Facture" aria-expanded="false" aria-controls="flush-collapseThree-Facture">
	        						<i class="far fa-bookmark"></i> Comment payer ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseThree-Facture" class="accordion-collapse collapse" aria-labelledby="flush-headingThree-Facture" data-bs-parent="#accordionFacture">
								<div class="accordion-body">
									Les modalités de paiement diffèrent selon les fournisseurs. Les conditions appliquées seront celles du fournisseur retenu. Choisissez l'un des modes de paiement proposé par le distributeur.
								</div>
	    					</div>
	  					</div>
						<div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingFour-Facture">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour-Facture" aria-expanded="false" aria-controls="flush-collapseFour-Facture">
	        						<i class="far fa-bookmark"></i> Puis-je payer en plusieurs fois ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseFour-Facture" class="accordion-collapse collapse" aria-labelledby="flush-headingFour-Facture" data-bs-parent="#accordionFacture">
								<div class="accordion-body">
									Les conditions de règlement dépendent du fournisseur retenu. Certains fournisseurs proposent des facilités de paiement et d'autres demandent un paiement au comptant.
									Les différents moyens de paiement  proposés le jour de l'opération seront mentionnés dans le mail de la proposition du tarif, elles seront indiquées dans la rubrique "comment régler ma facture ?"
									<!-- Vous pouvez également choisir la facilité de paiement qui vous convient le mieux dans celles proposées par le fournisseur. Il est possible de bénéficier de paiements en 2 fois, 3 fois, 5 fois, 10 fois. Ces conditions dépendent du fournisseur chargé de votre livraison. -->
								</div>
	    					</div>
	  					</div>
						<!-- <div class="accordion-item">
	    					<h3 class="accordion-header" id="flush-headingSix-Facture">
	      						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix-Facture" aria-expanded="false" aria-controls="flush-collapseSix-Facture">
	        						<i class="far fa-bookmark"></i> Quand saurai-je si je peux payer en plusieurs fois ?
	      						</button>
	    					</h3>
	    					<div id="flush-collapseSix-Facture" class="accordion-collapse collapse" aria-labelledby="flush-headingSix-Facture" data-bs-parent="#accordionFacture">
								<div class="accordion-body">
									Les modalités de paiement vous seront communiquées dans le mail d'annonce du tarif. Vous pourrez donc prendre connaissance de ces modalités avant de passer votre commande.
								</div>
	    					</div>
	  					</div> -->
					</div>
				</div>
				<div class="text-right">
					<p>Si vous n'avez pas trouvé votre réponse ici, vous pouvez <a href="contacter_poemop.php">nous contacter</a> directement.</p>
				</div>
				<div class="block text-center faq">
					<a href="creer_un_compte_poemop.php" class="btn btn-secondary">Rejoignez-nous !</a>
				</div>
			</div>
			<div class="col-sm-3 col-mobile-none">
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
