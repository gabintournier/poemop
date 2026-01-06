<?php
include_once 'inc/dev_auth.php';
session_start();
$desc = 'La presse rédige régulièrement des articles sur les commandes groupées réalisées par POEMOP. Car, nos actions vous font payer votre fioul moins cher de 50 euros en moyenne par commande.';
$title = 'Les commandes groupés POEMOP dans la presse';
ob_start();
include 'modules/menu.php';
?>
<div class="container-fluid">
	<div class="header">
		<div class="revue-de-presse">
			<div class="row">
				<div class="col align-self-center">
					<h1>Les commandes de fioul POEMOP<br>dans la presse !</h1>
					<div class="ligne jaune"></div>
					<p>Les médias s'intéressent à POEMOP. Vous trouverez dans cette rubrique divers articles<br>de presse, reportages à la télévision ou à la radio qui parlent de notre site.</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="bloc-actu">
						<img src="images/le_progres_01_2020.jpg" alt="Poemop dans le Progrès" style="width:100%;">
						<div class="titre-actu">
							Le progrès
							<div class="ligne vert"></div>
						</div>
						<p class="text">Le Progrès a réalisé un article sur les sites commandes groupées de fioul domestique. Le journaliste à suivit une de nos livraisons.</p>
						<p class="date">Janvier 2020</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu">
						<video poster="images/poemop_bfm_2018.jpg" controls="" preload="" class="bfm">
							<source src="video/poemop_bfm_2018.mp4" type="video/mp4">
							<source src="video/poemop_bfm_2018.webm" type="video/webm">
							<source src="video/poemop_bfm_2018.ogv" type="video/ogg">
							<p>Votre navigateur ne support pas les videos html5</p>
						</video>
						<div class="titre-actu">
							BFM-TV Grand Angle
							<div class="ligne vert"></div>
						</div>
						<p class="text">Nous avons été cité en exemple dans l'émission Grand Angle de Bruce Toussaint ayant pour sujet les éconnomies réalisées grace aux commandes groupées de fioul.</p>
						<p class="date">20 Novembre 2018</p>
						<hr class="separe">
					</div>
					<div class="bloc-actu" style="margin-top: 4.5%;">
						<img style="width:100%;" src="images/le-telegramme-logo-vector.png" alt="Le Télégramme à réalisé un article sur les commandes groupées de fioul">
						<div class="titre-actu">
							Le télégramme
							<div class="ligne vert"></div>
						</div>
						<p class="text">Le Télégramme à réalisé un article sur les commandes groupées de fioul, Stéphanie a été interwievé : <a href="https://www.letelegramme.fr/economie/fioul-la-commande-groupee-une-aubaine-08-07-2017-11588169.php" target="_blank">Fioul. La commande groupée, une aubaine ?</a></p>
						<p class="date">8 Juillet 2017</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu">
						<img style="width:100%;" src="images/dossier-familiale.png" alt="Dossier Familiale, POEMOP est mentionné pour ses commandes groupées de fioul">
						<div class="titre-actu">
							Dossier Familial
							<div class="ligne vert"></div>
						</div>
						<p class="text">A la rubrique consommation énergies du site dossierfamilial.com du 27 novembre 2016, POEMOP est mentionné pour ses commandes groupées de fioul : <a target="_blank" href="https://www.mercipourlinfo.fr/actualites/chauffage-au-fioul-faites-votre-prochaine-commande-avant-le-31-decembre-353075">Chauffage au fioul</a></p>
						<p class="date">27 Décembre 2016</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu">
						<img style="width:100%;" src="images/france3.png" alt="France 3 Centre - Val de Loire parle de Poemop">
						<div class="titre-actu">
							France3-region
							<div class="ligne vert"></div>
						</div>
						<p class="text">France 3 Centre -Val de Loire nous a contacté en août 2015 pour évoquer la baisse des prix du fioul. Notre responsable relation partenaires, Alexandre Louis, a été interrogé sur les gains apportés par notre groupement d'achat POEMOP et sur le prix du fioul selon les départements.
							<br>Vous pouvez lire cet article en cliquant sur le lien : <a href="https://france3-regions.francetvinfo.fr/centre-val-de-loire/la-baisse-des-prix-du-fioul-fait-le-bonheur-des-usagers-788597.html" target="_blank">La baisse des prix du fioul fait le bonheur des usagers</a></p>
						<p class="date">17 Aout 2015</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu">
						<img style="width:100%;" src="images/dossier-familiale.png" alt="9 conseils pour faire baisser sa facture de chauffage">
						<div class="titre-actu">
							Dossier Familial
							<div class="ligne vert"></div>
						</div>
						<p class="text">A la rubrique logement du site dossierfamilial.com du 25 novembre 2014, POEMOP faisait partie des 9 conseils pour faire baisser sa facture de chauffage.</p>
						<p class="date">Novembre 2014</p>
						<hr class="separe">
					</div>
					<div class="bloc-actu">
						<div class="titre-actu">
							TarifGaz.com
							<div class="ligne vert"></div>
						</div>
						<p class="text">A la rubrique choisir du site TarifGaz.com dans l'article "Tarif du gaz vs tarif du fioul : lequel est le moins cher ?", POEMOP est cité dans la rubrique : <a href="https://tarifgaz.com/faq-choisir/gaz-ou-fioul">Trouver moins cher pour le fioul</a> </p>
						<p class="date">04 Février 2014</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu">
						<img style="width:100%;" src="images/reponse_a_tout_2.jpg" alt="Réalisez des économies sur les coûts de livraison avec les achats groupées">
						<div class="titre-actu">
							Réponse à tout
							<div class="ligne vert"></div>
						</div>
						<p class="text">En mars 2014, c'est le site reponseatout.com qui parlait de POEMOP. L'administrateur de notre site, Thierry Martin, évoquait le gain réalisé sur les coûts de livraison lors d'une commande groupée.</p>
						<p class="date">Mars 2014</p>
						<hr class="separe">
					</div>
					<div class="bloc-actu">
						<audio controls="" preload="">
							<source src="video/rmc.mp3" type="audio/mp3">
							<source src="video/rmc.ogg" type="audio/ogg">
							<source src="video/rmc.aac" type="audio/aac">
							<p>Votre navigateur ne support pas les audios html5</p>
						</audio>
						<div class="titre-actu">
							RMC Émission de 7h à 8h de JJ Bourdin
							<div class="ligne vert"></div>
						</div>
						<p class="text">Gwenaël WINDRESTIN de RMC a fait un reportage sur notre société et a interrogé Elodie Durand, notre assistante, sur le gain apporté par le regroupement.<br>Vous pouvez écouter ce reportage en cliquant sur le bouton Play.</p>
						<p class="date">4 Novembre 2013</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu" >
						<video poster="images/poemop_france2_2013.jpg" controls="" preload="" class="bfm">
							<source src="video/poemop_france2_2013.mp4" type="video/mp4">
							<source src="video/poemop_france2_2013.webm" type="video/webm">
							<source src="video/poemop_france2_2013.ogv" type="video/ogg">
							<p>Votre navigateur ne support pas les videos html5</p>
						</video>
						<div class="titre-actu">
							France 2 journal de 13h00
							<div class="ligne vert"></div>
						</div>
						<p class="text">Elise Lucet de France 2 nous a contacté une seconde fois pour parler de notre site internet. Elodie Durand, notre assistante, a pu témoigner du gain réalisé lors d'achats groupés de fioul.<br>Vous pouvez regarder ce reportage en cliquant sur l'image.</p>
						<p class="date">29 Octobre 2013</p>
						<hr class="separe">
					</div>
					<div class="bloc-actu">
						<img style="width:100%;" src="images/notre_temps.jpg" alt="Magazine notre temps">
						<div class="titre-actu">
							Notre Temps
							<div class="ligne vert"></div>
						</div>
						<p class="text">Le magazine Notre temps, dans un article invitant ses lecteurs à comparer les prix, a mentionné POEMOP pour ses commandes groupées de fioul.</p>
						<p class="date">Aout 2012</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu">
						<img style="width:100%;" src="images/tv_grande_chaine.jpg" alt="Article paru dans le magazine TV Grandes Chaines du mois d'octobre 2012">
						<div class="titre-actu">
							TV Grandes chaines
							<div class="ligne vert"></div>
						</div>
						<p class="text">Voici ci-dessus un article paru dans le magazine TV Grandes Chaines du mois d'octobre 2012.<br>L'article portait sur l'allègement de sa facture d'énergie et a réservé un encart à POEMOP.</p>
						<p class="date">Octobre 2012</p>
						<hr class="separe">
					</div>
					<div class="bloc-actu">
						<img style="width:100%;" src="images/ca_interesse_1.jpg" alt="La magazine Ca m'intéresse parle de Poemop - 12 astuces pour acheter moins cher">
						<div class="titre-actu">
							Ca m'interesse
							<div class="ligne vert"></div>
						</div>
						<p class="text">Le magazine Ca m'intéresse s'est penché en mai 2012 sur les achats groupés. POEMOP a, une nouvelle fois, été mentionné.</p>
						<p class="date">Mai 2012</p>
						<hr class="separe">
					</div>
					<div class="bloc-actu">
						<div class="titre-actu">
							DossierFamilial.com
							<div class="ligne vert"></div>
						</div>
						<p class="text">Rubrique logement : <a href="https://www.mercipourlinfo.fr/conso/energie/facture-edf-engie-comment-payer-moins-cher-lelectricite-et-le-gaz-896873" target="_blank" >9 conseils pour baisser votre facture de chauffage</a> </p>
						<p class="date">15 Fevier 2012</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu">
						<audio controls="" preload="">
							<source src="video/france_inter_1.mp3" type="audio/mp3">
							<source src="video/france_inter_1.ogg" type="audio/ogg">
							<source src="video/france_inter_1.aac" type="audio/aac">
							<p>Votre navigateur ne support pas les audios html5</p>
						</audio>
						<div class="titre-actu">
							Europe 1 Émission de 5h à 7h d'Emmanuel MAUBERT
							<div class="ligne vert"></div>
						</div>
						<p class="text">Dans sa chronique sur la consommation, Isablelle QUENIN qualifiait notre site de "super intéressant". A réécouter en cliquant sur le bouton Play.</p>
						<p class="date">19 Janvier 2012</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu">
						<div class="titre-actu">
							France3.Fr
							<div class="ligne vert"></div>
						</div>
						<p class="text">POEMOP était cité dans l'article : Le prix du fuel domestique s'envole </p>
						<p class="date">5 Janvier 2012</p>
						<hr class="separe">
					</div>
					<div class="bloc-actu">
						<div class="titre-actu">
							Ledauphine.com
							<div class="ligne vert"></div>
						</div>
						<p class="text">La rédaction du site ledauphine.com a rélaisé un article sur notre site en septembre 2011. Pour le consulter, cliquez sur ce lien. <a href="https://www.ledauphine.com/isere-sud/2011/09/11/les-commandes-groupees-pour-alleger-la-facture" target="_blank">Les commandes groupées pour alléger la facture</a></p>
						<p class="date">12 Septembre 2011</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu">
						<div class="titre-actu">
							Maisonapart.com
							<div class="ligne vert"></div>
						</div>
						<p class="text">POEMOP a été cité sur la site maisonapart.com, dans un article portant sur la hausse des prix du fioul et les moyens de payer moins cher. Vous pouvez consulter cet article en cliquant sur le lien. <a href="https://www.maisonapart.com/edito/construire-renover/energie-chauffage-climatisation/prix-en-baisse-pour-le-fioul-domestique-en-cette-r-5919.php" target="_blank">Prix en baisse pour le fioul domestique en cette rentrée 2011</a> </p>
						<p class="date">5 Septembre 2011</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu">
						<div class="titre-actu">
							Lest-eclair.f
							<div class="ligne vert"></div>
						</div>
						<p class="text">Nous avons participé à la rédaction de l'article : "Fioul domestique : son prix varie chaque jour" </p>
						<p class="date">26 Aout 2011</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu">
						<audio controls="" preload="">
							<source src="video/france_inter_2.mp3" type="audio/mp3">
							<source src="video/france_inter_2.ogg" type="audio/ogg">
							<source src="video/france_inter_2.aac" type="audio/aac">
							<p>Votre navigateur ne support pas les audios html5</p>
						</audio>
						<div class="titre-actu">
							France Inter, Service Public
							<div class="ligne vert"></div>
						</div>
						<p class="text">Isabelle GIORDANO évoque un comparatif qui a été fait par l'UFC Que Choisir et qui passe au crible les sites d'achats groupés. POEMOP y est cité et son fonctionnement expliqué. Pour réécouter cet extrait, vous pouvez cliquer sur le bouton Play.</p>
						<p class="date">24 Mai 2011</p>
						<hr class="separe">
					</div>
					<div class="bloc-actu" id="france2">
						<video poster="images/poemop_france2_2010.jpg" controls="" preload="" class="bfm">
							<source src="video/poemop_france2_2010.mp4" type="video/mp4">
							<source src="video/poemop_france2_2010.webm" type="video/webm">
							<source src="video/poemop_france2_2010.ogv" type="video/ogg">
							<p>Votre navigateur ne support pas les videos html5</p>
						</video>
						<div class="titre-actu">
							France 2 journal de 13h00
							<div class="ligne vert"></div>
						</div>
						<p class="text">Elise Lucet nous a contactés une première fois pour réaliser un reportage sur le fonctionnement de notre site. Notre gérant, Laurent Gonin, a été interviewé et les journalistes ont suivi un fournisseur effectuant des livraisons via POEMOP. Retrouvez l'intégralité du reportage en cliquant sur le bouton Play.</p>
						<p class="date">7 Octobre 2010</p>
						<hr class="separe">
					</div>
					<div class="bloc-actu">
						<div class="titre-actu">
							Radin.com
							<div class="ligne vert"></div>
						</div>
						<p class="text">Dans la rubrique bons plans du site radin.com, POEMOP a été mentionné, ainsi que dans leur newletter.</p>
						<p class="date">8 Avril 2010</p>
						<hr class="separe">
					</div>
					<div class="bloc-actu">
						<div class="titre-actu">
							France Inter
							<div class="ligne vert"></div>
						</div>
						<p class="text">Le 9 septembre 2008, nous sommes intervenus lors d'un reportage sur les commandes groupées de fioul domestique, malheureusement nous n'avons pas cette bande son.</p>
						<p class="date">9 Septembre 2010</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu">
						<img style="width:100%;" src="images/60_millions.jpg" alt="60 millions de consommateur">
						<div class="titre-actu">
							60 millions de consommateur
							<div class="ligne vert"></div>
						</div>
						<p class="text">Le magazine 60 millions de consommateurs s'est penché en janvier 2011 sur la question de comment faire baisser sa facture d'énergie et a mentionné notre site. Retrouvez l'intégralité de l'article ci-dessous.</p>
						<p class="date">Janvier 2011</p>
						<hr class="separe">
					</div>
					<div class="bloc-actu">
						<img style="width:100%;" src="images/reponse_a_tout_1.jpg" alt="Réponse à TOUT">
						<div class="titre-actu">
							Réponse à TOUT !
							<div class="ligne vert"></div>
						</div>
						<p class="text">La magazine Réponse à tout a rédigé un article sur POEMOP en mars 2010 pour expliquer son fonctionnement. Vous pouvez lire l'article ci-dessous.</p>
						<p class="date">Mars 2010</p>
						<hr class="separe">
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
