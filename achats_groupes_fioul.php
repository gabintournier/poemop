<?php
include_once 'inc/dev_auth.php';
session_start();

$desc = 'Commandes groupées de fioul domestique partout en France. Poemop groupe gratuitement votre commande de fioul avec celles de vos voisins.';
$title = 'Commandes groupées de fioul moins cher avec POEMOP';
ob_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions.php";

// Moyenne POEMOP
$query = "	SELECT AVG(pmp_commande.cmd_prix_ord)/1000
			FROM pmp_commande, pmp_utilisateur
			WHERE pmp_commande.cmd_status IN (17,20,25)
			AND pmp_commande.cmd_prix_ord != 0
			AND pmp_commande.user_id = pmp_utilisateur.user_id
			AND pmp_commande.cmd_typefuel = 1
			AND pmp_commande.cmd_qte >= 1000
			AND cmd_dt BETWEEN date(now() - INTERVAL 30 day) AND now()
			ORDER BY 1 DESC
			LIMIT 1
			";
$res= mysqli_query($co_pmp, $query);
if($pmp_commande = mysqli_fetch_array($res))
{
	if($pmp_commande[0] == 0)
		$pmp_commande[0] = 0.000;
	$prix_pmp_moy = floor($pmp_commande[0] * 1000);
}

// Min POEMOP
$query = "	SELECT pmp_commande.cmd_prix_ord, pmp_utilisateur.code_postal
			FROM pmp_commande, pmp_utilisateur
			WHERE pmp_commande.cmd_status IN (17,20,25)
			AND pmp_commande.cmd_prix_ord != 0
			AND pmp_commande.user_id = pmp_utilisateur.user_id
			AND pmp_commande.cmd_typefuel = 1
			AND pmp_commande.cmd_qte >= 1000
			AND cmd_dt BETWEEN date(now() - INTERVAL 30 day) AND now()
			ORDER BY 1
			LIMIT 1
			";
$res= mysqli_query($co_pmp, $query);
if($pmp_commande = mysqli_fetch_array($res))
{
	$prix_pmp_min = floor($pmp_commande[0]);
	$dep_pmp_min = substr($pmp_commande[1],0,2);
}
else
{
	$prix_pmp_min = 0.000;
	$dep_pmp_min = 00;
}

// Moyenne PF
$nom_fichier = "maj/data0.js";
$fichier = fopen($nom_fichier, 'r');
$nb_ligne = 0;
while($ligne = fgets($fichier))
	$nb_ligne++;
fseek($fichier,0);
for ($i=0; $i<$nb_ligne-1; $i++)
	$ligne = fgets($fichier);
$prix_pf = substr($ligne,13,1);
if($prix_pf == "]")
	$prix_pf = substr($ligne,9,4);
else
	$prix_pf = substr($ligne,9,3);
?>
<?php include 'modules/menu_fioul.php'; ?>
<div class="container-fluid">
	<div class="section">
		<div class="row">
			<div class="col-sm-9">
				<div class="header">
					<div class="groupement-fioul">
						<div class="row">
							<div class="col-sm-6 align-self-center">
								<h1>Commande groupée<br>de fioul domestique</h1>
								<p>Vous voulez faire des économies ???</p>
								<div class="block">
									<a href="creer_un_compte_poemop.php" class="btn btn-primary">Rejoignez-nous !</a>
								</div>
							</div>
							<div class="col-sm-6">
								<img src="images/header-groupement-fioul.svg" alt="Commande groupée de fioul domestique avec Poemop">
							</div>
						</div>
						<div class="inscription-fioul text-center">
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
										<img src="images/economies-groupement-poemop.svg" alt="traitement direct avec le fournisseur">
										<p>Je fais des économies<br><span>en participant au groupement</span></p>
									</div>
								</div>
								<div class="col">
									<div class="bbox">
										<img src="images/livraison-poemop.svg" alt="livraison tranquille et paiement direct">
										<p>Je suis livré et je paie<br><span>directement le livreur</span></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="fioul text-center">
					<div class="row">
						<div class="col" style="padding-right: 3%!important;">
							<div class="bbox">
								<p class="bbox-titre-1">Tendance de prix Poemop<br><span>Pour 1000 L ord.</span></p>
								<hr class="separe">
								<div class="prix prix-orange">
									<?php print $prix_pmp_moy; ?><span>&euro;</span>
								</div>
							</div>
						</div>
						<div class="col" style="padding-right: 3%!important;">
							<div class="bbox">
								<p class="bbox-titre-2">Meilleur prix Poemop<br><span><?php print "departement $dep_pmp_min"; ?></span></p>
								<hr class="separe">
								<div class="prix prix-bleu">
									<?php print "$prix_pmp_min"; ?><span>&euro;</span>
								</div>
							</div>
						</div>
						<div class="col">
							<div class="bbox">
								<p class="bbox-titre-3">Moyenne nationale<br><span>Source prixfioul.fr</span></p>
								<hr class="separe">
								<div class="prix prix-vert">
									<?php print "$prix_pf"; ?><span>&euro;</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="achats-groupes text-center">
					<img class="img" src="images/economies-groupement-poemop-blc.svg" alt="traitement direct avec le fournisseur">
					<h2>Payez votre fioul<br>moins cher</h2>
					<div class="ligne-center jaune"></div>


					<p>
						Nous réalisons des <strong style="color:#fff;font-weight: 500;">commandes groupées de fioul depuis 2008</strong>.<br>Ces regroupements permettent de négocier de meilleurs tarifs.<br>
						<br>
						<strong style="color:#fff;font-weight: 500;">Payez votre fioul moins cher, c'est gratuit</strong> et <strong style="color:#fff;font-weight: 500;">sans engagement</strong>.<br>
						<br>
						Pour en profiter il suffit de vous inscrire, rejoignez le mouvement<br><strong style="color:#fff;font-weight: 500;">POEMOP</strong> Plus On Est Moins On Paie !
					</p>
				</div>
				<div class="block-titre text-center">
					<h2>Alors, n'hésitez plus et faites<br>le choix de l'économie !</h2>
				</div>
				<div class="text-center">
					<a href="creer_un_compte_poemop.php" class="btn btn-primary">Rejoignez-nous !</a>
				</div>
				<div class="carte-france text-center">
					<h2>Zone<br>des groupements</h2>
					<div class="ligne-center orange"></div>
					<p>POEMOP vous aide à faire des économies sur votre facture de fioul sur une majeure partie de la France.<br>80 % de la France est actuellement couvert par nos services. Cliquez sur votre département pour savoir si un groupement<br>est en cours sur votre secteur. Vous pourrez également consulter les prix qui ont été négociés sur les précédents groupements.</p>

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
				</div>
			</div>
			<div class="col-sm-3" style="margin-top: 5.9%;">
<?php
				include '/modules/connexion.php';
				include '/modules/activites.php';
				include '/modules/avis_clients.php';
?>
			</div>
		</div>
	</div>
</div>


<?php
$content = ob_get_clean();
require('template.php');
?>
