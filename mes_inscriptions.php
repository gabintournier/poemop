<link rel="stylesheet" href="bootstrap/5.2.3/css/bootstrap.min.css" media="screen">

<?php
session_start();
include_once 'inc/dev_auth.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);


$desc = 'Commandes groupées de fioul domestique partout en France. Poemop groupe gratuitement votre commande de fioul avec celles de vos voisins.';
$title = 'Mes inscriptions POEMOP';
ob_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_compte.php";

if(isset($_GET["id_crypte"]))
{
	$id_crypte = $_GET["id_crypte"];
	$query = "  SELECT *
				FROM pmp_utilisateur
				WHERE id_crypte = '$id_crypte' ";
	$res = my_query($co_pmp, $query);
	$pmp_user = mysqli_fetch_array($res);

	if(isset($pmp_user['user_id']))
	{
		$_SESSION['id'] = $pmp_user['user_id'];
		$id_session = mysqli_real_escape_string($co_pmp, $_SESSION['id']);


	}
}

if(!isset($_SESSION['id']))
{
    header('Location: /');
	die();
}

if(isset($_SESSION['id']))
{

$user_id = $_SESSION['id'];

$jjj_users = ChargeCompteJoomla($co_pmp, $user_id);
$pmp_compte = ChargeMonCompte($co_pmp, $user_id);
$utilisateur = ChargeCompteFioul($co_pmp, $user_id);
$pmp_electricite = ChargeCompteElectricite($co_pmp, $user_id);
$pmp_gaz = ChargeCompteGaz($co_pmp, $user_id);
$pmp_artisan = ChargeCompteArtisan($co_pmp, $user_id);
$res_ville = getVilleCp($co_pmp, $utilisateur["code_postal"]);

include 'modules/menu.php';
?>
<div class="container-fluid">
	<div class="header inscriptions-groupement">
		<div class="row">
			<div class="col-sm-9 inscriptions">
				<h1>Mon compte Poemop</h1>
				<p>Je sélectionne les groupements pour lesquels je suis intéressé.</p>
				<hr class="separe">
<?php
				if(isset($_GET["desabo"]))
				{
?>
				<div class="informations-perso desinscription text-center" style="margin: 1% 0 1%;padding: 1%;">
				<h2>Désinscription</h2>
				<div class="ligne-center jaune"></div>
				<h3>Désabonnement de nos offres de prix</h3>
				<hr class="separe">
						<p class="center">Si vous souhaitez vous désabonner des offres fioul, merci de déselectionner l'onglet "fioul" ci-dessous.<br><br>
						Vous pouvez supprimer votre compte en cliquant <a href="https://plus-on-est-moins-on-paie.fr/mon_compte.php?type=fioul&desabo=ok">ici</a> .</p>
				</div>
<?php
				}
?>
<?php
if(isset($message)) // Affiche les message d'erreur ou du succès
{
?>
				<div class="toast <?= $message_type; ?>" style="margin: 1% 0 2% 25%!important;">
					<div class="message-icon <?= $message_type; ?>-icon">
						<i class="fas <?= $message_icone; ?>"></i>
					</div>
					<div class="message-content ">
						<div class="message-type" style="text-align:left;">
							<?= $message; ?>
						</div>
						<div class="message" style="text-align:left;">
<?php
						if($message != 'Erreur')
						{
							if(isset($message_m)) { echo "- " . $message_m . "<br>"; }
							if(isset($message_a)) { echo "- " . $message_a . "<br>"; }
						}
						else
						{
							if(isset($message_nom)) { echo "- " . $message_nom . "<br>"; }
							if(isset($message_cp)) { echo "- " . $message_cp . "<br>"; }
							if(isset($message_prenom)) { echo "- " . $message_prenom . "<br>"; }
							if(isset($message_adresse)) { echo "- " . $message_adresse . "<br>"; }
							if(isset($message_telp)) { echo "- " . $message_telp . "<br>"; }
							if(isset($message_telf)) { echo "- " . $message_telf . "<br>"; }
							if(isset($message_tel3)) { echo "- " . $message_tel3 . "<br>"; }
							if(isset($message_com_u)) { echo "- " . $message_com_u . "<br>"; }
							if(isset($message_com)) { echo "- " . $message_com . "<br>"; }
							if(isset($message_mdp)) { echo "- " . $message_mdp . "<br>"; }
							if(isset($message_mdp2)) { echo "- " . $message_mdp2 . "<br>"; }
							if(isset($message_mdpa)) { echo "- " . $message_mdpa . "<br>"; }
							if(isset($message_mail)) { echo "- " . $message_mail . "<br>"; }
							if(isset($message_commune)) { echo "- " . $message_commune . "<br>"; }
						}
?>
						</div>
					</div>
				</div>
<?php
}
?>
				<div class="diff-groupements">
					<div class="insc">
						<h2>Mes inscriptions</h2>
						<div class="ligne-center jaune"></div>
						<p>Pour valider votre abonnement, sélectionnez vos énergies ci-dessous et renseignez vos informations sur les différentes énergies.<br>Par défaut, l'adresse mail renseignée pour la création de votre compte sera utilisée pour toutes les énergies</p>
						<form method="post">
							<div class="row justify-content-md-center">
								<div class="col-sm-4 inputGroup input_1">
									<input class="check-energie" type="checkbox" name="abo_energie[]" value="1" id="energie_1" <?php if(isset($utilisateur[0])) { if($utilisateur['inscrit'] == 1) echo 'checked'; }?>>
									<label for="energie_1"><i class="fas fa-tint icon" aria-hidden="true"></i>Fioul</label>
									<p class="abo abo_1">non abonné</p>
									<div class="button-link infos_1">
										<span id="infos_1" class="show-infos btn btn-secondary" role="button" aria-pressed="true">Mes infos</span>
									</div>
									<div data-toggle="modal" data-target="#myModal_popup_1" class="popover-i" id="popup_1"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
								</div>
								<div class="col-sm-4 inputGroup input_2">
									<input class="check-energie" type="checkbox" name="abo_energie[]" value="2" id="energie_2" <?php if(isset($pmp_electricite[0])) { if($pmp_electricite['inscrit'] == 1) echo 'checked'; }?>>
									<label for="energie_2"><i class="fas fa-plug icon" aria-hidden="true"></i>Électricité</label>
									<p class="abo abo_2">non abonné</p>
									<div class="button-link infos_2">
										<span id="infos_2" class="show-infos btn btn-secondary" role="button" aria-pressed="true">Mes infos</span>
									</div>
									<div data-toggle="modal" data-target="#myModal_popup_2" class="popover-i" id="popup_2"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
								</div>
								<div class="col-sm-4 inputGroup input_3">
									<input class="check-energie" type="checkbox" name="abo_energie[]" value="3" id="energie_3" <?php if(isset($pmp_gaz[0])) { if($pmp_gaz['inscrit'] == 1) echo 'checked'; } ?>>
									<label for="energie_3"><i class="fas fa-fire icon" aria-hidden="true"></i>Gaz</label>
									<p class="abo abo_3">non abonné</p>
									<div class="button-link infos_3">
										<span id="infos_3" class="show-infos btn btn-secondary" role="button" aria-pressed="true">Mes infos</span>
									</div>
									<div data-toggle="modal" data-target="#myModal_popup_3" class="popover-i" id="popup_3"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
								</div>
							</div>
							<p>Poemop va se diversifier en proposant d'autres groupements d'achat. <br>Si ces produits vous intéressent, inscrivez-vous !<br>Nous vous contacterons lorsque nous aurons assez de personnes intéressées.</p>
							<div class="row justify-content-md-center">
								<div class="col-sm-4 inputGroup input_4">
									<input class="check-energie" type="checkbox" name="abo_energie[]" value="4" id="energie_4" <?php if(isset($pmp_compte[0])) { if($pmp_compte['artisan'] == 1) echo 'checked'; }?>>
									<label for="energie_4"><i class="fas fa-briefcase icon" aria-hidden="true"></i>Artisans</label>
									<p class="abo abo_4">non abonné</p>
									<div class="button-link infos_4">
										<span id="infos_4" class="show-infos btn btn-secondary" role="button" aria-pressed="true">Mes infos</span>
									</div>
									<div data-toggle="modal" data-target="#myModal_popup_4" class="popover-i" id="popup_4"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
								</div>
								<div class="col-sm-4 inputGroup input_5">
									<input class="check-energie" type="checkbox" name="abo_energie[]" value="5" id="energie_5" <?php  if(isset($pmp_compte[0])) { if($pmp_compte['produit_2'] == 1) echo 'checked'; }?>>
									<label for="energie_5"><i class="fas fa-credit-card icon" aria-hidden="true"></i>Compte bancaire</label>
									<p class="abo abo_5">non abonné</p>
									<div class="button-link infos_5">
										<span id="infos_5" class="show-infos btn btn-secondary" role="button" aria-pressed="true">Mes infos</span>
									</div>
									<div data-toggle="modal" data-target="#myModal_popup_5" class="popover-i" id="popup_5"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
								</div>
								<div class="col-sm-4 inputGroup input_6">
									<input class="check-energie" type="checkbox" name="abo_energie[]" value="6" id="energie_6" <?php  if(isset($pmp_compte[0])) { if($pmp_compte['produit_3'] == 1) echo 'checked'; }?>>
									<label for="energie_6"><i class="fas fa-file-invoice icon" aria-hidden="true"></i>Assurance</label>
									<p class="abo abo_6">non abonné</p>
									<div class="button-link infos_6">
										<span id="infos_6" class="show-infos btn btn-secondary" role="button" aria-pressed="true">Mes infos</span>
									</div>
									<div data-toggle="modal" data-target="#myModal_popup_6" class="popover-i" id="popup_6"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
								</div>

								<div class="col-sm-4 inputGroup input_7">
									<input class="check-energie" type="checkbox" name="abo_energie[]" value="7" id="energie_7" <?php  if(isset($pmp_compte[0])) { if($pmp_compte['produit_4'] == 1) echo 'checked'; }?>>
									<label for="energie_7"><i class="fas fa-phone-alt icon" aria-hidden="true"></i>Abo téléphonique</label>
									<p class="abo abo_7">non abonné</p>
									<div class="button-link infos_7">
										<span id="infos_7" class="show-infos btn btn-secondary" role="button" aria-pressed="true">Mes infos</span>
									</div>
									<div data-toggle="modal" data-target="#myModal_popup_7" class="popover-i" id="popup_7"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
								</div>
								<div class="col-sm-4 inputGroup input_8">
									<input class="check-energie" type="checkbox" name="abo_energie[]" value="8" id="energie_8" <?php  if(isset($pmp_compte[0])) { if($pmp_compte['produit_5'] == 1) echo 'checked'; }?>>
									<label for="energie_8"><i class="fas fa-wifi icon" aria-hidden="true"></i>Abo internet</label>
									<p class="abo abo_8">non abonné</p>
									<div class="button-link infos_8">
										<span id="infos_8" class="show-infos btn btn-secondary" role="button" aria-pressed="true">Mes infos</span>
									</div>
									<div data-toggle="modal" data-target="#myModal_popup_8" class="popover-i" id="popup_8"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
								</div>
								<div class="col-sm-4 inputGroup input_9">
									<input class="check-energie" type="checkbox" name="abo_energie[]" value="9" id="energie_9" <?php  if(isset($pmp_compte[0])) { if($pmp_compte['produit_6'] == 1) echo 'checked'; }?>>
									<label for="energie_9"><i class="fas fa-tree icon" aria-hidden="true"></i>Bois ou pellets</label>
									<p class="abo abo_9">non abonné</p>
									<div class="button-link infos_9">
										<span id="infos_9" class="show-infos btn btn-secondary" role="button" aria-pressed="true">Mes infos</span>
									</div>
									<div data-toggle="modal" data-target="#myModal_popup_9" class="popover-i" id="popup_9"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
								</div>

								<div class="col-sm-4 inputGroup input_10">
									<input class="check-energie" type="checkbox" name="abo_energie[]" value="10" id="energie_10" <?php  if(isset($pmp_compte[0])) { if($pmp_compte['produit_7'] == 1) echo 'checked'; }?>>
									<label for="energie_10"><i class="fas fa-tools icon" aria-hidden="true"></i>chaudière</label>
									<p class="abo abo_10">non abonné</p>
									<div class="button-link infos_10">
										<span id="infos_10" class="show-infos btn btn-secondary" role="button" aria-pressed="true">Mes infos</span>
									</div>
									<div data-toggle="modal" data-target="#myModal_popup_10" class="popover-i" id="popup_10"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
								</div>
								<div class="col-sm-4 inputGroup input_11">
									<input class="check-energie" type="checkbox" name="abo_energie[]" value="11" id="energie_11" <?php  if(isset($pmp_compte[0])) { if($pmp_compte['produit_8'] == 1) echo 'checked'; }?>>
									<label for="energie_11"><i class="fas fa-tv icon" aria-hidden="true"></i>Abo télé</label>
									<p class="abo abo_11">non abonné</p>
									<div class="button-link infos_11">
										<span id="infos_11" class="show-infos btn btn-secondary" role="button" aria-pressed="true">Mes infos</span>
									</div>
									<div data-toggle="modal" data-target="#myModal_popup_11" class="popover-i" id="popup_11"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
								</div>
								<div class="col-sm-4 inputGroup input_12">
									<input class="check-energie" type="checkbox" name="abo_energie[]" value="12" id="energie_12" <?php  if(isset($pmp_compte[0])) { if($pmp_compte['produit_9'] == 1) echo 'checked'; }?>>
									<label for="energie_12"><i class="fas fa-archive icon" aria-hidden="true"></i>Autres produits</label>
									<p class="abo abo_12">non abonné</p>
									<div class="button-link infos_12">
										<span id="infos_12" class="show-infos btn btn-secondary" role="button" aria-pressed="true">Mes infos</span>
									</div>
									<div data-toggle="modal" data-target="#myModal_popup_12" class="popover-i" id="popup_12"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
								</div>
							</div>
						</form>
					</div>
					<div class="formulaire-compte" id="mes_infos_1">
						<span class="return"><i class="fas fa-chevron-left"></i> Mes inscriptions</span>
						<h2>Mes informations Fioul</h2>
						<?php include 'form/mon_compte_fioul.php'; ?>
					</div>
					<div class="formulaire-compte" id="mes_infos_2">
						<span class="return"><i class="fas fa-chevron-left"></i> Mes inscriptions</span>
						<h2>Mes informations électricité</h2>
						<?php include 'form/mon_compte_elec.php'; ?>
					</div>
					<div class="formulaire-compte" id="mes_infos_3">
						<span class="return"><i class="fas fa-chevron-left"></i> Mes inscriptions</span>
						<h2>Mes informations Gaz</h2>
						<?php include 'form/mon_compte_gaz.php'; ?>
					</div>
					<div class="formulaire-compte" id="mes_infos_4">
						<span class="return"><i class="fas fa-chevron-left"></i> Mes inscriptions</span>
						<h2>Aidez nous à mettre en place ce réseau d'artisans de confiance</h2>
						<?php include 'form/mon_compte_artisans.php'; ?>
					</div>
					<div class="formulaire-compte" id="mes_infos_5">
						<span class="return"><i class="fas fa-chevron-left"></i> Mes inscriptions</span>
						<h2>Mes informations</h2>
					  	<?php include 'form/mon_compte_global.php'; ?>
					</div>
					<div class="formulaire-compte" id="mes_infos_6">
						<span class="return"><i class="fas fa-chevron-left"></i> Mes inscriptions</span>
						<h2>Mes informations</h2>
					  	<?php include 'form/mon_compte_global.php'; ?>
					</div>
					<div class="formulaire-compte" id="mes_infos_7">
						<span class="return"><i class="fas fa-chevron-left"></i> Mes inscriptions</span>
						<h2>Mes informations</h2>
					  	<?php include 'form/mon_compte_global.php'; ?>
					</div>
					<div class="formulaire-compte" id="mes_infos_8">
						<span class="return"><i class="fas fa-chevron-left"></i> Mes inscriptions</span>
						<h2>Mes informations</h2>
					  	<?php include 'form/mon_compte_global.php'; ?>
					</div>
					<div class="formulaire-compte" id="mes_infos_9">
						<span class="return"><i class="fas fa-chevron-left"></i> Mes inscriptions</span>
						<h2>Mes informations</h2>
					  	<?php include 'form/mon_compte_global.php'; ?>
					</div>
					<div class="formulaire-compte" id="mes_infos_10">
						<span class="return"><i class="fas fa-chevron-left"></i> Mes inscriptions</span>
						<h2>Mes informations</h2>
					  	<?php include 'form/mon_compte_global.php'; ?>
					</div>
					<div class="formulaire-compte" id="mes_infos_11">
						<span class="return"><i class="fas fa-chevron-left"></i> Mes inscriptions</span>
						<h2>Mes informations</h2>
					  	<?php include 'form/mon_compte_global.php'; ?>
					</div>
					<div class="formulaire-compte" id="mes_infos_12">
						<span class="return"><i class="fas fa-chevron-left"></i> Mes inscriptions</span>
						<h2>Mes informations</h2>
					  	<?php include 'form/mon_compte_global.php'; ?>
					</div>
				</div>

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

	<!-- modal -->

	<div class="modal fade" id="myModal" tabindex="-1" data-backdrop="static">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"></h4>
					<div class="close" data-dismiss="modal" aria-label="Close">
			        	<span aria-hidden="true">&times;</span>
			        </div>
				</div>
				<div class="modal-body check" id="body_popup_1">
					<p>Nous réalisons des commandes groupées de fioul depuis 2008. La fréquence des groupements est d'au moins une fois par mois par secteur géographique.</p>
					<h4>Le principe est simple :</h4>
					<div class="ligne-modal jaune"></div>
					<div id="activites">
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Pré-inscription gratuite</span> et sans engagement</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Nous négocions</span> pour vous et vous recevez l'offre par mail</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Vous validez</span> ou refusez cette offre sans avoir à vous justifie</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Le fournisseur vous contacte</span> par téléphone afin de convenir d'un rendez-vous</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Le paiement</span> se fait directement au livreur selon les modalités proposés dans le mail</p>
					</div>
					<p class="text-center" style="margin-top:2%"><span class="subtitle">Alors, n'hésitez plus et faites le choix de l'économie !</span></p>
				</div>

				<div class="modal-body check" id="body_popup_2">
					<p>Nous avons réalisé fin 2020 un 1er groupement national d'achat d'électricité qui a permis à plus de 670 familles de changer de fournisseur d'électricité et d'ainsi faire en moyenne 125 € d'économies par an !</p>
					<h4>Forts de cette grande réussite, nous lançons un nouvel achat groupé national d'électricité :</h4>
					<div class="ligne-modal jaune"></div>
					<div id="activites">
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Economies garanties !</span> 125 € en moyenne par an</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Sans engagement </span></p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Respect de l'environnement</span> avec les offres vertes</p>
					</div>
					<h4>Le principe est simple</h4>
					<div class="ligne-modal jaune"></div>
					<div id="activites">
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Pré-inscription </span> gratuite et sans engagement</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Nous négocions </span> pour vous et vous recevez l'offre par mail</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Vous validez </span> ou refusez cette offre sans avoir à vous justifier</p>
					</div>
					<h4>Et vous n'avez rien à faire de plus !</h4>
					<div class="ligne-modal jaune"></div>
					<div id="activites">
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Aucune démarche administrative</span>, on s'occupe de tout</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Aucune intervention ni coupure de courant</span></p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">POEMOP</span> restera à votre écoute</p>
					</div>
					<p class="text-center" style="margin-top:2%"><span class="subtitle">Alors, n'hésitez plus et faites le choix de l'économie !</span></p>
				</div>

				<div class="modal-body check" id="body_popup_3">
					<p>POEMOP diversifie ses activités et vous aide à réaliser des économies sur vos contrats de gaz !</p>
					<h4>Nous lançons un achat groupé national de gaz :</h4>
					<div class="ligne-modal jaune"></div>
					<div id="activites">
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Economies garanties !</span></p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Sans engagement</span></p>
					</div>
					<h4>Le principe est simple</h4>
					<div class="ligne-modal jaune"></div>
					<div id="activites">
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Pré-inscription </span> gratuite et sans engagement</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Nous négocions </span> pour vous et vous recevez l'offre par mail</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Vous validez </span> ou refusez cette offre sans avoir à vous justifier</p>
					</div>
					<h4>Et vous n'avez rien à faire de plus !</h4>
					<div class="ligne-modal jaune"></div>
					<div id="activites">
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Aucune démarche administrative</span>, on s'occupe de tout</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Aucune intervention ni coupure de courant</span></p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">POEMOP</span> restera à votre écoute</p>
					</div>
					<p class="text-center" style="margin-top:2%"><span class="subtitle">Alors, n'hésitez plus et faites le choix de l'économie !</span></p>
				</div>

				<div class="modal-body check" id="body_popup_4">
					<p>Ce n'est pas chose aisée aujourd'hui ! Sur quels critères en choisir un ? Comment évaluer sa compétence ? Autant de questions qui se posent et les réponses ne sont<br>pas toujours si évidentes.</p>
					<p>Aujourd'hui, nous souhaitons simplifier les démarches de chacun en mettant en place un réseau d'artisans de confiance afin de permettre à tous nos inscrits de trouver près de chez eux « l'artisan » qui lui faut, celui qui vous apportera une prestation de qualité, et au prix juste.</p>
					<h4>Dans quel but créer un réseau d'artisans de confiance ?</h4>
					<div class="ligne-modal jaune"></div>
					<div id="activites">
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Un gain de temps : </span> vous aurez à portée de main des artisans connus et reconnus !</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Une relation de confiance : </span> il s'agit de professionnels talentueux qui souhaitent vous satisfaire !</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">L'assurance de bénéficier </span> d'un travail sérieux et de qualité.</p>
					</div>
					<h4>Comment nous aider à mettre en place ce réseau d'artisans de confiance ?</h4>
					<div class="ligne-modal jaune"></div>
					<p>C'est très simple ! Nous sommes sûrs que vous avez dans vos contacts des professionnels auxquels vous avez fait appel et qui vous ont apporté satisfaction. Nous invitons chacun d'entre vous à nous soumettre un ou plusieurs artisans que vous pourriez recommander (plombier, peintre, électricien, menuisier, couvreur, chauffagiste, serrurier...) et qui pourrait permettre aux autres inscrits de trouver la bonne personne pour réaliser leurs travaux.</p>
				</div>

				<div class="modal-body check" id="body_popup_5">
					<p>Sélectionnez votre banque et indiquez vos besoins, POEMOP se charge d'étudier les banques pour vous permettre d'avoir un comparatif personnalisé (selon les tarifs mais également les services), pour vous permettre de choisir de manière pertinente la banque dont vous avez besoin.</p>
					<div id="activites" style="margin-top:2%;">
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Nous comparons </span> pour vous les offres des banques en fonction de votre profil</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Nous sommes indépendants </span> ce qui vous assure une totale transparence, nous vous proposons des contrats adaptés à vos besoins</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Vous validez </span> ou refusez cette offre sans avoir à vous justifier</p>
					</div>
					<p class="text-center" style="margin-top:2%"><span class="subtitle">Alors, n'hésitez plus et faites le choix de l'économie !</span></p>
				</div>

				<div class="modal-body check" id="body_popup_6">
					<p>Sélectionnez l'assurance que vous recherchez (auto, habitation, moto...) et inscrivez-vous à notre groupement. Poemop se charge de vous trouver l'offre la plus adaptée à votre profil.</p>
					<div id="activites" style="margin-top:2%;">
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Nous comparons </span> pour vous les assurances en fonction de votre profil</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Nous sommes indépendants </span> nous vous proposons des contrats adaptés à vos besoins</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Vous validez </span> ou refusez cette offre sans avoir à vous justifier</p>
					</div>
					<p class="text-center" style="margin-top:2%"><span class="subtitle">Alors, n'hésitez plus et faites le choix de l'économie !</span></p>
				</div>

				<div class="modal-body check" id="body_popup_7">
					<p>POEMOP diversifie ses activités et vous aide à réaliser des économies sur votre forfait téléphone, bénéficiez d'offres négociées pour obtenir des abonnements moins chers et réduire votre facture téléphonique, sans oublier la qualité de service !</p>
					<h4>Le principe est simple</h4>
					<div class="ligne-modal jaune"></div>
					<div id="activites">
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Pré-inscription </span> gratuite et sans engagement</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Nous négocions </span> pour vous et vous recevez l'offre par mail</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Vous validez </span> ou refusez cette offre sans avoir à vous justifier</p>
					</div>
					<p class="text-center" style="margin-top:2%"><span class="subtitle">Alors, n'hésitez plus et faites le choix de l'économie !</span></p>
				</div>

				<div class="modal-body check" id="body_popup_8">
					<p>POEMOP diversifie ses activités et vous aide à réaliser des économies sur votre abonnement internet : nous réalisons un achat groupé pour vous proposer un abonnement internet moins cher : profitez de l'offre négociée pour faire des économies sur vos factures !</p>
					<h4>Le principe est simple</h4>
					<div class="ligne-modal jaune"></div>
					<div id="activites">
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Pré-inscription </span> gratuite et sans engagement</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Nous négocions </span> pour vous et vous recevez l'offre par mail</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Vous validez </span> ou refusez cette offre sans avoir à vous justifier</p>
					</div>
					<p class="text-center" style="margin-top:2%"><span class="subtitle">Alors, n'hésitez plus et faites le choix de l'économie !</span></p>
				</div>

				<div class="modal-body check" id="body_popup_9">
					<p>Envie d'acheter du bois de chauffage au meilleur prix ? POEMOP diversifie ses activités et vous aide à réaliser des économies sur vos achats de bois ou de pellets ! </p>
					<p>Optez pour la commande groupée ! Cela vous permet d'acheter votre bois et vos pellets moins chers, en regroupant les commandes de plusieurs consommateurs habitant dans la même zone, de façon à faire baisser les coûts d'acheminement.</p>
					<h4>Le principe est simple</h4>
					<div class="ligne-modal jaune"></div>
					<div id="activites">
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Pré-inscription </span> gratuite et sans engagement</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Nous négocions </span> pour vous et vous recevez l'offre par mail</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Vous validez </span> ou refusez cette offre sans avoir à vous justifier</p>
					</div>
					<p>Plus il y aura d'inscrits, plus le prix sera attractif !</p>
					<p class="text-center" style="margin-top:2%"><span class="subtitle">Alors, n'hésitez plus et faites le choix de l'économie !</span></p>
				</div>
				<div class="modal-body check" id="body_popup_10">
					<h4>Vous souhaitez entretenir votre chaudière ?</h4>
					<div class="ligne-modal jaune"></div>
					<p>Ne cherchez plus ! Inscrivez-vous et nous trouvons pour vous le chauffagiste qui assurera l'entretien de votre chaudière.</p>
					<h4>C'est simple !</h4>
					<div class="ligne-modal jaune"></div>
					<p>Inscrivez-vous gratuitement, nous vous proposerons un devis pour un entretien de chaudière réalisé par un partenaire qualifié !
					Pas de démarche à réaliser de votre part, le chauffagiste prendra contact avec vous pour convenir d'un RDV et d'un délai d'intervention.</p>
					<p class="text-center" style="margin-top:2%"><span class="subtitle">Alors, n'hésitez plus et faites le choix de l'économie !</span></p>
				</div>

				<div class="modal-body check" id="body_popup_11">
					<p>POEMOP diversifie ses activités et vous aide à réaliser des économies sur vos abonnements télé</p>
					<h4>Comment bénéficier d'une offre TV attractive au meilleur prix ?</h4>
					<div class="ligne-modal jaune"></div>
					<p>La meilleure offre est celle qui vous permet de regarder tous vos programmes préférés au meilleur prix ! Réalisez des économies et profitez d'offres négociées avantageuses</p>
					<div id="activites">
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Pré-inscription </span> gratuite et sans engagement</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Nous négocions </span> pour vous et vous recevez l'offre par mail</p>
						<p><i class="fal fa-badge-check"></i> <span class="subtitle">Vous validez </span> ou refusez cette offre sans avoir à vous justifier</p>
					</div>
					<p class="text-center" style="margin-top:2%"><span class="subtitle">Alors, n'hésitez plus et faites le choix de l'économie !</span></p>
				</div>

				<div class="modal-body check" id="body_popup_12">
					<p>POEMOP diversifie ses activités et vous aide à réaliser des économies dans un domaine que vous aurez choisi !</p>
					<p>Dites nous seulement l'activité qui vous intéresse, et nous réaliserons des groupements en fonction de vos envies</p>
					<p class="text-center" style="margin-top:2%"><span class="subtitle">Alors, n'hésitez plus et faites le choix de l'économie !</span></p>
				</div>
				<div class="modal-body no-ckeck">
				</div>
			</div>
		</div>
	</div>
</div>



<?php
}
$content = ob_get_clean();
require('template.php');
?>
<script src="js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="bootstrap/5.2.3/js/bootstrap.min.js"></script>
<script src="js/javascript.util.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	if ( window.history.replaceState ) {
		window.history.replaceState( null, null, window.location.href );
	}
	$('.modal').attr('id', 'myModal');
	$(".formulaire-compte").hide();

	$("input[type=checkbox]").each(function(idx, elem) {
	   var id = $(this).val(); //On récupérer l'id de toutes les checkbox
	   // On affiche ou on cache "mes infos", "non abonnée" si la checkbox est true ou false
	   if ($('#energie_'+id).prop("checked") == true) {
		   $('.infos_'+id).show();
		   $('.abo_'+id).hide();
	   } else if ($('#energie_'+id).prop("checked") == false) {
		   $('.infos_'+id).hide();
		   $('.abo_'+id).show();
	   }
	});

	// Pour toute les energies
	$(".check-energie").click(function() {
		var id = $(this).val();
		 // On recupere l'id de la checkbox cliqué
		if ($(this).prop("checked") == true) {

			$.ajax({
				method: 'POST',
				url: 'inc/pmp_ajax_mes_inscriptions.php',
				data: {
					produit: id, //valeur de la checkbox cliqué
					inscrit: 1,
					user_id: <?php echo json_encode($user_id); ?> // Le user_id est récupéré au début dans les entetes de chaque fichier
				}
			})
			.done(function(data) {
				$('.abo_'+id).fadeOut(); // On enlève le text 'Non abonné' de la checbox sélectionné grace a la 'value' de la checkbox
				$('.infos_'+id).fadeIn(); // Puis on affiche le bouton
			});

		} else if (jQuery(this).prop("checked") == false) {
			$('#energie_'+id).prop('checked', true);	// Pour l'instant la checkbox est toujours checked
			$('.modal').attr('id', 'myModal');
			$('.modal-title').attr('id', 'title_modal');
			$('.no-ckeck').attr('id', 'body_modal');
			$('.check').hide();
			$('#myModal').modal('show');
			$('#title_modal').html('Voulez vous vraiment vous désabonner<br>du groupement ?'); //On crée le message de validation dans la modal
			$('#body_modal').html('<div class="modal-body-button"><button type="button" class="btn-modal btn btn-secondary oui_'+id+'" data-dismiss="modal">Oui</button><button type="button" class="btn-modal btn btn-secondary non_'+id+'" data-dismiss="modal">Non</button></div>');

			$('.oui_'+id).click(function(){ // Si on clique sur le bouton Oui de la modal validation
  				$.ajax({
  					method: 'POST',
  					url: 'inc/pmp_ajax_mes_inscriptions.php',
  					data: {
  						produit: id,
  						inscrit: 0,
  						user_id: <?php echo json_encode($user_id); ?>
  					}
  				})
  				.done(function(data) {
  					$('#energie_'+id).prop('checked', false);
  					$('.abo_'+id).fadeIn();
  					$('.infos_'+id).fadeOut();
					$('#myModal').modal('hide');
					// $('.modal-backdrop').fadeOut();
  				})
  			});
			$('.non_'+id).click(function(){
				$('#myModal').modal('hide');
			});
		}
	});

	$('.show-infos').click(function () {
		var id = $(this).attr('id');
		$('.insc').fadeOut();
		$('#mes_'+id).fadeIn();
	});

	$('.return').click(function () {
		$('.insc').fadeIn();
		$('.formulaire-compte').fadeOut();
	});

	// titre modal artisans
	$("#popup_4").click(function() {
		val = $(this).attr('id');
		$('#title_modal'+val).html('A la recherche d\'un bon artisan ? ');
		$('#body_'+val).show();
	})


	/* Modal informations */
	$(".popover-i").click(function() {
		val = $(this).attr('id');
		$('.modal').attr('id', 'myModal_'+val);
		$('.modal-title').attr('id', 'title_modal'+val);
		$('.no-check').hide();
		$('#body_modal').empty();
		$('.check').hide();
		$('#myModal_'+val).modal('show');
		$('#title_modal'+val).html('Achat groupé par POEMOP');
		$('#body_'+val).show();
	});

	//fermer la modal
	$('.close').click(function() {
		$('.modal').modal('hide')
	});


});

</script>
