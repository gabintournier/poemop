<style media="screen">
.bouton-ajouter {margin-left: 275px!important;z-index: 1040!important;}
</style>
<?php
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: connexion.php');
	die();
}

$title = 'Tableau de bord';
$title_page = 'Tableau de bord';

$button = true;
$link_button = '/admin/index.php?maj_pf=oui';
$button_name = 'MAJ PF';
ob_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);


include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions.php";
include_once __DIR__ . "/inc/pmp_inc_commandes_a_affecter.php";

unset($_SESSION['facture_saisie']);
$fournisseurs = getFournisseursListe($co_pmp);
$num_fournisseurs = mysqli_num_rows($fournisseurs);

$avis = getAvis0($co_pmp);
$num_avis = mysqli_num_rows($avis);

$cmdes = getTotalCommande($co_pmp);

$coord = getClientsCoord($co_pmp);

$grpts = getGroupements($co_pmp);
$num_grpts = mysqli_num_rows($grpts);

$client_tel = getClientsTel($co_pmp);
$client_net = getClientsNet($co_pmp);
$num_clients = $client_tel["client"] + $client_net["client"];

$clients_inactif = getClientsInactif($co_pmp);
$clients_actif = getClientsActif($co_pmp);

$res_cmd = getCommandesOrphelines($co_pmp);
$num_cmd = mysqli_num_rows($res_cmd);

$res_cmd_grp = getGroupementPossible($co_pmp);
$num_cmd_grp = mysqli_num_rows($res_cmd_grp);

$res_cmd_plus_grp = getPlusieursGroupementsPossibles($co_pmp);
$num_cmd_plus_grp = mysqli_num_rows($res_cmd_plus_grp);

$en_cours = getStatsEnCours($co_pmp);

$p_valide = getStatsValide($co_pmp, '20');
$livrable = getStatsValide($co_pmp, '25');
$livree = getStatsValide($co_pmp, '30');
$terminee = getStatsValide($co_pmp, '40');
$valide = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $terminee["statut"];

$annulee = getStatsAnnulee($co_pmp);
if(!$valide || !$annulee["statut"] || !$en_cours["statut"])
{
	$projection = "0";
}
else
{
	$projection = (($valide) / ($valide + $annulee["statut"]) * $en_cours["statut"]) + $valide;
}

if (!empty($_POST["recherche_zone_livraison"]))
{
	header('Location: liste_fournisseurs.php?cp=' . $_POST["cp_livraison"]);
}

if (!empty($_POST["recherche_commande"]))
{
	$_SESSION["n_client"] = $_POST["n_client"];
	$_SESSION["cp_client"] = $_POST["cp_client"];
	$_SESSION["nom_client"] = $_POST["nom_client"];
	$_SESSION["p_client"] = $_POST["p_client"];
	$_SESSION["tel_client"] = $_POST["tel_client"];
	$_SESSION["email_client"] = $_POST["email_client"];

	header('Location: liste_commandes.php');
}

if(!empty($_POST["recher_rapide_client"]))
{
	if(!empty($_POST["email"]))
	{
		$client = getClientRapide($co_pmp, $_POST["email"]);
		if(isset($client[0]))
		{
			header('Location: gestion_client.php?user_id=' . $client["id"] . '&return=accueil');
		}
		else
		{
			$message_type = "no";
			$message_icone = "fa-times";
			$message_titre = "Erreur";
			$message = "Aucun client trouvé avec cet email.";
		}
	}
	else
	{
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Le champs email est obligatoire.";
	}
}

if (isset($message))
{
?>
<div class="toast <?= $message_type; ?>" style="margin: -9.5% 0 10%!important;">
	<div class="message-icon <?= $message_type; ?>-icon">
		<i class="fas <?= $message_icone; ?>"></i>
	</div>
	<div class="message-content ">
		<div class="message-type">
			<?= $message_titre; ?>
		</div>
		<div class="message">
			<?= $message; ?>
		</div>
	</div>
	<div class="message-close">
		<i class="fas fa-times"></i>
	</div>
</div>
<?php
}
?>

<div class="row justify-content-md-center">
	<div class="col-sm padding">
		<div class="cards-stat fournisseurs-hover" style="height: 275px;">
			<a href="liste_fournisseurs.php">
				<div class="icon-stat fournisseurs-content">
					<img class="img" src="../images/poemop.svg" alt="fournisseurs" style="height:40px;">
				</div>
				<div class="title-stat">
					Gestion des fournisseurs
				</div>
				<div class="item">
					<div class="number nb-four"><?= $num_fournisseurs; ?></div>
					<div class="type">Fournisseurs</div>
				</div>
				<div class="row">
					<div class="col-sm text">
						Tous les<br>fournisseurs
					</div>
					<div class="col-sm align-self-center">
						<div class="text-right">
							<a href="liste_fournisseurs.php" class="btn btn-go opacity-hover fournisseurs-show"><i class="fas fa-chevron-right"></i></a>
						</div>
					</div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-sm padding">
		<div class="cards-stat commandes-hover" style="height: 275px;">
			<a href="liste_commandes.php">
				<div class="icon-stat fournisseurs-content">
					<img src="images/livraison-poemop-a.svg" alt="Avis" style="height: 40px;">
				</div>
				<div class="title-stat">
					Gestion des commandes
				</div>
				<div class="item">
					<div class="number nb-com"><?= number_format($cmdes["cmd"],0,',',' '); ?></div>
					<div class="type">Commandes passées</div>
				</div>
				<div class="row">
					<div class="col-sm text">
						Toutes les<br>commandes
					</div>
					<div class="col-sm align-self-center">
						<div class="text-right">
							<a href="liste_commandes.php" class="btn btn-go opacity-hover fournisseurs-show"><i class="fas fa-chevron-right"></i></a>
						</div>
					</div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-sm padding">
		<div class="cards-stat avis-hover" style="height: 275px;">
			<a href="avis_clients.php">
				<div class="icon-stat fournisseurs-content">
					<img src="../images/moyenne-poemop.svg" alt="Avis" style="height: 40px;">
				</div>
				<div class="title-stat">
					Gestion des avis clients
				</div>
				<div class="item">
					<div class="number nb-avis"><?= $num_avis; ?></div>
					<div class="type">Avis à traiter</div>
				</div>
				<div class="row">
					<div class="col-sm text">
						Tous les<br>avis
					</div>
					<div class="col-sm align-self-center">
						<div class="text-right">
							<a href="avis_clients.php" class="btn btn-go opacity-hover fournisseurs-show"><i class="fas fa-chevron-right"></i></a>
						</div>
					</div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-sm">
		<div class="cards-stat groupements-hover" style="height: 275px;">
			<a href="liste_regroupements.php">
				<div class="icon-stat fournisseurs-content">
					<img src="images/commandes.svg" alt="Avis" style="height: 40px;">
				</div>
				<div class="title-stat">
					Gestion des regroupements
				</div>
				<div class="item">
					<div class="number nb-group"><?= number_format($num_grpts,0,',',' '); ?></div>
					<div class="type">Groupements</div>
				</div>
				<div class="row">
					<div class="col-sm text">
						Tous les<br>groupements
					</div>
					<div class="col-sm align-self-center">
						<div class="text-right">
							<a href="liste_regroupements.php" class="btn btn-go opacity-hover fournisseurs-show"><i class="fas fa-chevron-right"></i></a>
						</div>
					</div>
				</div>
			</a>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-4 padding">
		<div class="bloc-dash min-bloc" style="    height: 125px;">
			<div class="title-stat" style="margin: 1% 0 0 0;">Statistiques commandes</div>
			<div class="ligne"></div>
			<div class="row text-center" style="margin-top: 2%;">
				<div class="col-sm-4 margin" style="border-right: 1px solid rgba(0,0,0,.1);">
					<div class="type" style="margin:0;">En cours</div>
					<div class="number nb-four">
					    <?= is_numeric($en_cours["statut"]) ? number_format($en_cours["statut"], 0, ',', ' ') : '0'; ?>
					</div>

				</div>
				<div class="col-sm-4 margin" style="border-right: 1px solid rgba(0,0,0,.1);">
		   			<div class="type" style="margin:0;">
		   			    <?php 
		   			        $txt = (isset($valide) && $valide > 1) ? "Validées" : "Validée"; 
		   			        echo $txt; 
		   			    ?>
		   			</div>
		   			<div class="number nb-com">
		   			    <?php if(isset($valide)) { echo number_format($valide,0,',',' '); } else { echo "0"; } ?>
		   			</div>
				</div>
				<div class="col-sm-4 margin">
				    <div class="type" style="margin:0;">
				        <?= ($projection > 1 ? "Projections" : "Projection"); ?>
				    </div>
				    <div class="number nb-avis">
				        <?= number_format($projection,0,',',' '); ?>
				    </div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-5 stats-client-dash padding">
		<div class="bloc-dash  min-bloc" style="height: 125px;">
			<div class="title-stat" style="margin: 1% 0 0 0;">Statistiques clients</div>
			<div class="ligne"></div>
			<div class="row text-center" style="margin-top: 2%;">
				<div class="col-sm-3 margin" style="border-right: 1px solid rgba(0,0,0,.1);">
					<div class="type" style="margin:0;">Total</div>
					<div class="stat"><?= number_format($num_clients,0,',',' '); ?></div>
				</div>
				<div class="col-sm-3 margin" style="border-right: 1px solid rgba(0,0,0,.1);">
					<div class="type" style="margin:0;">Coord.</div>
					<div class="stat"><?= number_format($coord["coord"],0,',',' '); ?></div>
				</div>
				<div class="col-sm-3 margin" style="border-right: 1px solid rgba(0,0,0,.1);">
					<div class="type" style="margin:0;">Actif</div>
					<div class="stat"><?= number_format($clients_actif["actif"],0,',',' '); ?></div>
				</div>
				<div class="col-sm-3 margin">
					<div class="type" style="margin:0;">Inactif</div>
					<div class="stat"><?= number_format($clients_inactif["inactif"],0,',',' '); ?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="bloc-dash min-bloc color" style="height: 125px;">
			<div class="title-stat" style="margin: 1% 0 0 0; color: #f8f6f4;">Recherche d'un client</div>
			<div class="ligne"></div>
			<form style="margin-top: 2%;" method="post">
				<div class="form-inline">
					<label for="email" class="col-form-label search-mail col-sm-2 " style="color:#f8f6f4;padding:0">Email</label>
					<input type="text" name="email" value="" class="search-form form-control col-sm-10">
					<input type="submit" name="recher_rapide_client" value="RECHERCHER" class="btn btn-success" style="min-width:100%; margin-top:4%;">
				</div>

			</form>
		</div>
	</div>
	<div class="col-sm-5 col-sm-5-res">
		<div class="row">
			<div class="col-sm-6 padding">
				<form method="post">
					<div class="bloc-dash min color">
						<div class="title-stat" style="margin-bottom: 0;color:#f8f6f4">Recherche fournisseurs<br>sur zone de livraison</div>
						<div class="ligne"></div>
						<img src="../images/header-groupement-fioul.svg" alt="Commande groupée de fioul domestique avec Poemop" style="margin-top: 9%;">
						<div class="text-center" style="margin-top:5%;">
							<label for="cp_livraison" class="col-form-label" style="color:#f8f6f4">Code postal</label>
							<input type="text" name="cp_livraison" value="" class="form-control" style="width:80%;display: block;margin: 0 auto;">
							<input type="submit" name="recherche_zone_livraison" value="RECHERCHER" class="btn btn-primary" style="min-width:80%; margin-top:8%;">
						</div>
					</div>
				</form>
			</div>
			<div class="col-sm-6 padding">
				<div class="bloc-dash min">
					<div class="title-stat" style="margin-bottom: 0;">Recherche rapide<br>d'une commande</div>
					<div class="ligne"></div>
					<form method="post">
						<div class="row">
							<div class="col-sm-6">
								<label for="n_client" class="col-form-label">N° Client</label>
								<input type="text" name="n_client" value="" class="form-control" style="width:100%;">
							</div>
							<div class="col-sm-6">
								<label for="cp_client" class="col-form-label">CP</label>
								<input type="text" name="cp_client" value="" class="form-control" style="width:100%;">
							</div>
							<div class="col-sm-6">
								<label for="cp_client" class="col-form-label">Nom</label>
								<input type="text" name="nom_client" value="" class="form-control" style="width:100%;">
							</div>
							<div class="col-sm-6">
								<label for="cp_client" class="col-form-label">Prenom</label>
								<input type="text" name="p_client" value="" class="form-control" style="width:100%;">
							</div>
							<div class="col-sm-12">
								<label for="cp_client" class="col-form-label">Téléphone</label>
								<input type="text" name="tel_client" value="" class="form-control" style="width:100%;">
							</div>
							<div class="col-sm-12">
								<label for="cp_client" class="col-form-label">Email</label>
								<input type="email" name="email_client" value="" class="form-control" style="width:100%;">
							</div>
						</div>
						<div class="text-center" style="margin-top:5%">
							<input type="submit" name="recherche_commande" value="RECHERCHER" class="btn btn-primary" style="min-width:100%;">
						</div>
					</form>
				</div>
			</div>
	</div>
</div>
<!-- <div class="col-sm-4">
	<div class="bloc-dash" style="padding: 10px 20px;">
		<div class="row">
			<div class="col-sm-6">
				<div class="title-stat" style="margin-bottom: 0;">Commandes à affecter</div>
				<div class="ligne"></div>
				<div class="item" style="margin-top: 20px;">
					<div class="number nb-mail"><?php if(isset($num_cmd)) {echo number_format($num_cmd,0,',',' '); } else { echo "Travaux"; } ?></div>
					<div class="type">Commandes<br>à affecter</div>
				</div>
				<a href="nouvelles_commandes.php" class="btn btn-warning">TRAITER COMMANDES</a>
			</div>
			<div class="col-sm-6 align-self-center" style="padding-left: 0;">
				<img src="/images/header-achat-groupes-poemop.svg" alt="Inscrivez vous et faites des économies" style="width: 100%;display: block;margin: 0 auto;">
			</div>
		</div>

	</div>
</div> -->
<div class="col-sm-7 col-sm-7-res">
	<div class="bloc-dash">
		<div class="afficher-td">
			<div class="title-stat" style="margin-bottom: 0;">Commandes à affecter</div>
			<div class="ligne"></div>
			<div class="row">
				<div class="col-sm-4 text-center">
					<div class="item">
						<div class="number nb-mail"><?php if(isset($num_cmd)) {echo number_format($num_cmd,0,',',' '); }?></div>
						<div class="type">Commandes<br>orphelines</div>
					</div>
				</div>
				<div class="col-sm-4 text-center">
					<div class="item">
						<div class="number nb-mail"><?php if(isset($num_cmd_grp)) {echo number_format($num_cmd_grp,0,',',' '); }?></div>
						<div class="type">Commandes<br>avec 1 grp</div>
					</div>
				</div>
				<div class="col-sm-4 text-center">
					<div class="item">
						<div class="number nb-mail"><?php if(isset($num_cmd_plus_grp)) {echo number_format($num_cmd_plus_grp,0,',',' '); }?></div>
						<div class="type">Commandes<br>avec + grp</div>
					</div>
				</div>
			</div>
				<img src="images/header-achat-groupes-poemop.svg" alt="Inscrivez vous et faites des économies" style="width: 245px;display: block;margin: 0 auto;margin-top: 15px;margin-bottom: 22px;">
			<a href="commandes_a_affecter.php" class="btn btn-warning" style="display: block;">TRAITER COMMANDES</a>
		</div>
		<div class="row pas-afficher-td">
			<div class="col-sm-7">
				<div class="title-stat" style="margin-bottom: 0;">Commandes à affecter</div>
				<div class="ligne"></div>
				<div class="row" style="margin-top: 5%;">
					<div class="col-sm">
						<div class="item">
							<div class="number nb-mail"><?php if(isset($num_cmd)) {echo number_format($num_cmd,0,',',' '); }?></div>
							<div class="type">Commandes<br>orphelines</div>
						</div>
					</div>
					<div class="col-sm">
						<div class="item">
							<div class="number nb-mail"><?php if(isset($num_cmd_grp)) {echo number_format($num_cmd_grp,0,',',' '); }?></div>
							<div class="type">Commandes<br>avec 1 grp</div>
						</div>
					</div>
					<div class="col-sm">
						<div class="item">
							<div class="number nb-mail"><?php if(isset($num_cmd_plus_grp)) {echo number_format($num_cmd_plus_grp,0,',',' '); }?></div>
							<div class="type">Commandes<br>avec plusieurs grp</div>
						</div>
					</div>
					<div class="col-sm-12" style="margin-top: 7%;">
						<a href="commandes_a_affecter.php" class="btn btn-warning">TRAITER COMMANDES</a>
					</div>
				</div>
			</div>
			<div class="col-sm-5 align-self-center">
				<img src="images/header-achat-groupes-poemop.svg" alt="Inscrivez vous et faites des économies" style="width: 90%;display: block;margin: 0 auto;">
			</div>
			
		</div>
	</div>
	<form method="post">
		<?php include 'form/modal_maj_pf.php'; ?>
	</form>
	<!-- <div class="text-center">
		<input type="submit" name="" value="EXTRACTION ZONE" class="btn btn-warning" style="width:33%;margin-top: 5%;"><br>
	</div> -->
</div>
<?php
$content = ob_get_clean();
require 'template.php';
?>
<?php
$host = $_SERVER['HTTP_HOST'];
$isDev = str_contains($host, 'dev') || str_contains($host, 'localhost'); 

$baseUrl = $isDev 
    ? "https://dev.plus-on-est-moins-on-paie.fr" 
    : "https://plus-on-est-moins-on-paie.fr";
?>

<script type="text/javascript">
$(document).ready(function() {

   var width = $(window).width();
   if(width >= 1280 && width <= 1290){
       $('.col-sm-5-res').removeClass('col-sm-5').addClass('col-sm-7');
	   $('.col-sm-7-res').removeClass('col-sm-7').addClass('col-sm-5');
   }
	
   var loc = window.location.href;
   if (loc === "<?= $baseUrl ?>/admin/index.php?maj_pf=oui") {
       $('#MajPF').modal('show');
   }

   $(".b-close").click(function() {
	   var url = "<?= $baseUrl ?>/admin/index.php";
	   window.location.replace(url);
   });
});
</script>

