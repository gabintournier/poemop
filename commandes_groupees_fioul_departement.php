<?php
include_once 'inc/dev_auth.php';
session_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_departement.php";

$desc = 'POEMOP organise des commandes goupées de fioul domestique régulièrement ' . $departement . '. Notre prix du fioul est moins cher de 50 euros en moyenne par commande de fioul ' . $departement;
$title = 'Commandes groupées de fioul domestiques dans ' . $departement;
ob_start();

$res = getDepartements($co_pmp);
$pmp_conso = getConsommateurDep($co_pmp, $_GET["dep"]);
$num_conso = mysqli_num_rows($pmp_conso);
$res_group = getDerniersGroupements($co_pmp, $_GET["dep"]);
echo $_GET["dep"];
?>
<?php include 'modules/menu.php'; ?>
<div class="container-fluid">
	<div class="header">
		<div class="groupement-fioul">
			<div class="row">
				<div class="col-sm-6 align-self-center">
					<h1>Commandes groupées de fioul<br>domestique livrées <?= $departement; ?></h1>
					<!-- <p><?= $num_conso; ?> consommateurs de fioul se sont inscrits sur POEMOP dans l'Ain.<br>
					Ils commandent du fioul moins cher grâce aux groupements de fioul POEMOP.</p> -->
					<div class="block">
						<a href="creer_un_compte_poemop.php" class="btn btn-secondary">Rejoignez-nous !</a>
					</div>
				</div>
				<div class="col-sm-6">
					<img src="images/header-groupement-fioul.svg" alt="Commande groupée de fioul domestique avec Poemop">
				</div>
			</div>
		</div>
	</div>
	<div class="section">
		<div class="row">
			<div class="col-sm-9">
				<div class="achats-groupes text-center">
					<h2>Groupement<br>de fioul domestique <?= $departement; ?></h2>
					<div class="ligne-center jaune"></div>
					<p>
						<strong class="blc"><?= $num_conso; ?> consommateurs de fioul se sont inscrits sur POEMOP</strong> <?= $departement; ?><br>
						Ils commandent du fioul moins cher grâce aux groupements de fioul POEMOP.
					</p>
					<p>Nous organisons régulièrement des groupements de commandes de fioul sur votre département. Vous trouverez ci-dessous, à titre indicatif, les tarifs et les dates des derniers groupements sur votre secteur. N'oubliez pas que les cours du fioul évoluent chaque jour et que si vous souhaitez faire un comparatif, il vous faut le faire à la même date.</p>
					<p>N'attendez plus pour bénéficier du meilleur tarif pour la commande de votre fioul domestique, inscrivez-vous gratuitement et sans engagement, et rejoignez la grande communauté Plus on est moins on paie !</p>
					<a href="creer_un_compte_poemop.php" class="btn btn-secondary">Rejoignez-nous !</a>
				</div>
<?php       // Pour tous les gpe en cours sur ce dep
			$i = 0;
			while($group = mysqli_fetch_array($res_group))
			{
				$i++;
				if ($i == 1) // Pour le premier gpe
				{
?>
				<div class="titre-bbox text-center">
					<img class="img" src="images/rejoindre-poemop.svg" alt="Rejoignez Poemop et faites des économies">
					<h2>Achats groupés de fioul<br>moins cher par POEMOP <?= $departement; ?></h2>
					<div class="ligne-center orange"></div>
				</div>
<?php
				} // Si on a quelque chose a afficher
				if(strlen($group["planning"]) > 0)
				{
					$annee = substr($group['date_grp'],0,4);
					$mois = substr($group['date_grp'],5,2);
					if($mois == "01") { $mois = "janvier"; }
					if($mois == "02") { $mois = "février"; }
					if($mois == "03") { $mois = "mars"; }
					if($mois == "04") { $mois = "avril"; }
					if($mois == "05") { $mois = "mai"; }
					if($mois == "06") { $mois = "juin"; }
					if($mois == "07") { $mois = "juillet"; }
					if($mois == "08") { $mois = "aout"; }
					if($mois == "09") { $mois = "septembre"; }
					if($mois == "10") { $mois = "octobre"; }
					if($mois == "11") { $mois = "novembre"; }
					if($mois == "12") { $mois = "décembre"; }

					if($group['statut'] == "5") { $msg = ' Groupement prévu en '; }
					else if($group['statut'] == "10") { $msg = ' Groupement en cours de rassemblement en '; }
					else if($group['statut'] == "15") { $msg = ' Groupement en cours de livraison en '; }
					else if($group['statut'] == "20") { $msg = ' Groupement livré récemment en '; }
					else  { $msg = ' Groupement terminé en '; }
?>
				<div class="box groupement-dep">
					<h3 class="top"><?= $i . ' - ' . $msg . ' ' . $mois . ' ' . $annee; ?></h3>
					<hr class="separe">
<?php
					if($plages = ChargePlages($co_pmp, $group["id"], 1000))
					{
						if(($plages['cmd_prix_ord']!=NULL) || ($plages['cmd_prix_sup']!=NULL))
						{
?>
					<p>
						<?php if($plages['prix_ord']!=0) { print 'Prix du litre d\'ordinaire : <strong>' . sprintf("%0.3f",$plages['prix_ord']/1000) . '&euro;* TTC/L</strong><br />'; } ?>
						<?php if($plages['prix_sup']!=0) { print 'Prix du litre supérieur : <strong>' . sprintf("%0.3f",$plages['prix_sup']/1000) . '&euro;* TTC/L</strong><br />'; } ?>
					</p>
					<hr class="separe">
<?php
						}
					}
?>
					<p><?= nl2br($group['planning']); ?></p>
					<div class="number" style="color:#0f393a">
						# <?= $group["id"]; ?>
					</div>
				</div>
<?php
				}

			}
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
