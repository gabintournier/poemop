<style media="screen">
.ligne-menu {width: 26%!important;}
.menu > h1, .ligne-menu {margin-left:6%;}
.btn-outline-primary { background: #f7f7f7!important;padding: 3px 20px!important;border-radius: 6px!important;font-size: 14px!important;}
.btn-outline-defini { background: #f7f7f7!important;padding: 3px 20px!important;border-radius: 6px!important;border: 1px solid #ef8351!important;color: #ef8351!important;font-size: 14px!important; }
</style>
<?php
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

error_reporting(E_ALL);
ini_set("display_errors", 1);
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

$title = 'Zones fournisseur';
$title_page = 'Zones fournisseur';
$return = true;
$link = '/admin/liste_fournisseurs.php';
ob_start();

include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_commandes.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_zones.php";
$fournisseur_details = getFournisseurDetails($co_pmp, $_GET["id_four"]);
$res_zone = getListeZonesFournisseur($co_pmp, $_GET["id_four"]);
unset($_SESSION['facture_saisie']);
if(isset($_GET["cotation"]))
{
	$zone_id = $_GET["zone_id"];
	if($_GET["cotation"] == "0")
	{
		$query = "  UPDATE pmp_fournisseur_zone
					SET cotation = '1'
					WHERE id = '$zone_id' ";
		$res = my_query($co_pmp, $query);
		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "La cotation a bien été ouverte pour la zone " . $zone_id;
			header('Location: /admin/zones_fournisseur.php?id_four=' . $_GET["id_four"]);

		}
	}
	elseif($_GET["cotation"] == "1")
	{
		$query = "  UPDATE pmp_fournisseur_zone
					SET cotation = '0'
					WHERE id = '$zone_id' ";
		$res = my_query($co_pmp, $query);
		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "La cotation a bien été fermé pour la zone " . $zone_id;
			header('Location: /admin/zones_fournisseur.php?id_four=' . $_GET["id_four"]);
		}
	}
}

if (isset($message))
{
?>
<div class="toast <?= $message_type; ?>">
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
<div class="bloc">
	<div class="menu-bloc">
		<a href="details_fournisseur.php?id_four=<?= $_GET["id_four"]; ?>">Fournisseur</a>
		<a href="contact_fournisseur.php?id_four=<?= $_GET["id_four"]; ?>">Contact</a>
		<a href="#" class="active">Zone</a>
	</div>
	<form method="post">
		<div class="row">
			<div class="col-sm-5" style="border-right:1px solid #0b242436">
				<label class="label-title" style="margin: 0;">Infos générales</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-7">
						<div class="form-inline">
							<label for="nom_four" class="col-sm-4 col-form-label" style="padding-left:0;">Rasion social :</label>
							<div class="col-sm-8" style="padding:0">
								<label for="nom_four" class="col-form-label label-input"><?= $fournisseur_details["nom"]; ?></label>
							</div>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="form-inline">
							<label for="cp_four" class="col-sm-6 col-form-label" style="padding-left:0;">Code postal :</label>
							<div class="col-sm-6" style="padding:0">
								<label for="cp_four" class="col-form-label label-input"><?= $fournisseur_details["code_postal"]; ?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<input type="hidden" name="id_zone_edit" id="id_zone_edit" value="">
				<label class="label-title" style="margin: 0;">Zone</label>
				<div class="ligne" style="width: 6%;"></div>
				<div class="row">
					<div class="col-sm-5">
						<div class="form-inline">
							<label for="nom_zone" class="col-sm-4 col-form-label" style="padding-left:0;">Libellé :</label>
							<div class="col-sm-8" style="padding:0">
								<input type="text" name="nom_zone" id="nom_zone" value="<?php if(!empty($_POST["ajouter_zone"]) || !empty($_POST["modifier_zone"])) { echo ""; }  elseif(isset($_POST["nom_zone"])) { echo $_POST["nom_zone"]; } ?>" class="form-control controle" style="width:100%;">
								<span class="remove remove-nom"><i class="fa-regular fa-circle-xmark"></i></span>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-inline">
							<label for="option_zone" class="col-sm-5 col-form-label" style="padding-left:0;">Options :</label>
							<div class="col-sm-7" style="padding:0">
								<input type="text" name="option_zone" id="option_zone" value="<?php if(!empty($_POST["ajouter_zone"]) || !empty($_POST["modifier_zone"])) { echo ""; }  elseif(isset($_POST["option_zone"])) { echo $_POST["option_zone"]; } ?>" class="form-control controle" style="width:100%;">
								<span class="remove remove-option"><i class="fa-regular fa-circle-xmark"></i></span>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<input type="submit" name="ajouter_zone" class="btn btn-primary ajouter" value="AJOUTER" style="width: 90%;">
						<input type="submit" name="modifier_zone" class="btn btn-warning modifier" value="MODIFIER" style="width: 90%;display:none">
					</div>
				</div>
			</div>
		</div>
		<hr>
		<p style="margin-bottom: 0;font-family: 'Goldplay Alt Medium';color: #0b242494 ;font-size: 15px;"><i class="far fa-arrow-from-top" style="padding: 0 18px 0;"></i> Envoyer une demande de cotation</p>
		<div class="tableau tableau-zone" style="height:500px;margin-top:8px">
			<table class="table">
				<thead>
					<tr>
						<th>Select</th>
						<th style="width:5%">ID</th>
						<th>Libellé</th>
						<th>Options</th>
						<th class="text-center">Mail To</th>
						<th class="text-center">Mail CC</th>
<?php
						if(isset($fournisseur_details["id_crypte"]))
						{
?>
						<th class="text-center" style="width: 10%;border-left: 1px solid #0b242436;">Cotations</th>
						<th class="text-center" style="width: 10%;border-right: 1px solid #0b242436;"></th>
						<th class="text-center" style="width: 13%;">Admin Fournisseur</th>
						<th class="text-center" style="width: 13%;">RÉCAP</th>
<?php
						}
?>

						<th style="width:5%"></th>
						<th style="width:5%"></th>
					</tr>
				</thead>
				<tbody>
<?php
				$a = 0;
				while($zones = mysqli_fetch_array($res_zone))
				{
					$mail = getMailToZone($co_pmp, $zones['id']);
					if(isset($mail["mail_to"])) { if($mail["mail_to"] == "") { $class_btn = "outline-primary"; } else { $class_btn = "outline-defini"; } } else { $class_btn = "outline-primary"; }
					if(isset($mail["mail_cc"])) { if($mail["mail_cc"] == "") { $class_btn_cc = "outline-primary"; } else { $class_btn_cc = "outline-defini"; } } else { $class_btn_cc = "outline-primary"; }
?>
					<tr class="select select_<?= $zones['id']; ?>">
						<input type="hidden" name="cotation_zone_id[]" value="<?= $zones['id']; ?>">
						<td><input type="checkbox" name="select_zone_mail_<?= $a++; ?>" id="select_zone_mail" class="switch value check"></td>
						<td class="zone"><input type="hidden" name="zone_id" value="<?= $zones['id']; ?>"><?= $zones["id"]; ?></td>
						<td class="zone"><input type="hidden" name="zone_id" value="<?= $zones['id']; ?>"><?= $zones['libelle']; ?></td>
						<td class="zone"><input type="hidden" name="zone_id" value="<?= $zones['id']; ?>"><?= $zones['droit_acces']; ?></td>
						<td class="text-center">
							<button type="button" data-bs-toggle="modal" data-bs-target="#addMailTo<?= $zones["id"]; ?>" class="btn btn-<?= $class_btn; ?>" name="button">DÉFINIR</button>
							<form method="post">
								<div class="modal fade" id="addMailTo<?= $zones["id"]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" style="max-width: 85%;">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        		<h5 class="modal-title" id="exampleModalLabel">Définir des mails sur la zone <?= $zones['libelle']; ?></h5>
								        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i></button>
								      		</div>
								      		<div class="modal-body text-left">
												<?php include 'form/form_contact_fournisseur.php'; ?>
								      		</div>
								      		<div class="modal-footer">
												<input type="hidden" name="nb_contact" value="<?php print $i++; ?>">
												<input type="hidden" name="mail_to_zone" value="<?php print $zones['id']; ?>">

								        		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
												<input type="submit" name="add_mail_to_zones" class="btn btn-warning" value="Ajouter à toutes les zones">
												<input type="submit" name="add_mail_to" id="add_mail_to" class="btn btn-primary" value="Ajouter">
								      		</div>
								    	</div>
								 	</div>
								</div>
							</form>
						</td>
						<td class="text-center">
							<button type="button" data-bs-toggle="modal" data-bs-target="#addMailCc<?= $zones["id"]; ?>" class="btn btn-<?= $class_btn_cc; ?>" name="button">DÉFINIR</button>
							<form method="post">
								<div class="modal fade" id="addMailCc<?= $zones["id"]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" style="max-width: 85%;">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        		<h5 class="modal-title" id="exampleModalLabel">Définir des mails en copie sur la zone <?= $zones['libelle']; ?></h5>
								        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i></button>
								      		</div>
								      		<div class="modal-body text-left">
												<?php include 'form/form_contact_fournisseur2.php'; ?>
								      		</div>
								      		<div class="modal-footer">
												<input type="hidden" name="nb_contact_cc" value="<?php print $i++; ?>">
												<input type="hidden" name="mail_to_zone_cc" value="<?php print $zones['id']; ?>">
								        		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
												<input type="submit" name="add_mail_cc_zones" class="btn btn-warning" value="Ajouter à toutes les zones">
												<input type="submit" name="add_mail_cc" id="add_mail_cc" class="btn btn-primary" value="Ajouter">
								      		</div>
								    	</div>
								 	</div>
								</div>
							</form>
						</td>
<?php
						if(isset($fournisseur_details["id_crypte"]))
						{
							if($zones['cotation'] == '0') { $cotation = "Fermé"; $gerer_cotations = "OUVRIR"; } else { $cotation = "Ouvert"; $gerer_cotations = "FERMER"; }

?>
						<td class="zone text-center" style="border-left: 1px solid #0b242436;"><input type="hidden" name="zone_id" value="<?= $zones['id']; ?>"><?= $cotation; ?></td>
						<td class="text-center" style="border-right: 1px solid #0b242436;"><a href="zones_fournisseur.php?id_four=<?= $_GET["id_four"]; ?>&zone_id=<?= $zones["id"]; ?>&cotation=<?= $zones['cotation']; ?>" class="btn btn-outline-primary" style="background: #f7f7f7;padding: 3px 20px;border-radius: 6px;"><?= $gerer_cotations; ?></a></td>
						<?php
							$host = $_SERVER['HTTP_HOST'];
							$isDev = str_contains($host, 'dev') || str_contains($host, 'localhost');

							$baseUrl = $isDev
							    ? "https://dev.plus-on-est-moins-on-paie.fr"
							    : "https://plus-on-est-moins-on-paie.fr";
							?>
						<td class="text-center"><a href="<?= $baseUrl ?>/admin-four/zone_cotations.php?id_crypte=<?= $fournisseur_details["id_crypte"]; ?>&id_zone=<?= $zones['id']; ?>&return=zone_cot"target="_blank"class="btn btn-secondary">COTATIONS</a></td>
						<td class="text-center">
							<button type="button" data-bs-toggle="modal" data-bs-target="#mailRecap<?= $zones['id']; ?>" class="btn btn-outline-primary" name="button">ENVOYER MAIL</button>
							<form method="post">
								<div class="modal fade" id="mailRecap<?= $zones["id"]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" style="max-width: 603px;">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        		<h5 class="modal-title" id="exampleModalLabel">Envoyer un mail de demande de récap à <?= $zones['libelle']; ?></h5>
								        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i></button>
								      		</div>
								      		<div class="modal-body text-left">
												<label for="nom_four" class="col-sm-4 col-form-label" style="padding-left:0;">Récapitulatifs des commandes ACHAT FIOUL :<input style="margin-left: 15px;" type="checkbox" name="recap_af" id="recap_af" class="switch value check"></label>

								      		</div>
								      		<div class="modal-footer">
												<input type="hidden" name="recap_id" value="<?php print $zones['id']; ?>">
								        		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
												<input type="submit" name="envoyer_mail_recap" id="envoyer_mail_recap" class="btn btn-primary" value="Envoyer">
								      		</div>
								    	</div>
								 	</div>
								</div>
							</form>
						</td>
<?php
						}
?>
						<td class="text-center edit">
							<input type="hidden" name="" value="<?= $zones['id']; ?>">
							<input type="hidden" name="" value="<?= $zones['libelle']; ?>">
							<input type="hidden" name="" value="<?= $zones['droit_acces']; ?>">
							<span class="delete edit"><input type="hidden" name="zone_id" value="<?= $zones['id']; ?>"><i class="fa-solid fa-pencil" style="padding: 11% 13%;"></i></span>
						</td>
						<td class="text-center supp">
							<span class="delete supp" data-bs-toggle="modal" data-bs-target="#supprimerZone_<?= $zones["id"]; ?>"><i class="fa-regular fa-trash-can" style="padding: 11% 14%;"></i></span>
							<form method="post">
								<div class="modal fade" id="supprimerZone_<?= $zones["id"]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        		<h5 class="modal-title" id="exampleModalLabel">Supprimer cette zone ?</h5>
								        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i></button>
								      		</div>
								      		<div class="modal-body text-left">
												<?php print $fournisseur_details["nom"] . " - " . $fournisseur_details["code_postal"] . " - " . $zones['libelle']; ?>
								      		</div>
								      		<div class="modal-footer">
												<input type="hidden" name="supp_zone_id" value="<?php print $zones['id']; ?>">
								        		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
												<input type="submit" name="supp_zone" id="supp_zone" class="btn btn-primary" value="Supprimer">
								      		</div>
								    	</div>
								 	</div>
								</div>
							</form>
						</td>
					</tr>
<?php
				}
?>
					<tr>
						<input type="hidden" name="nb_zone_cotations" value="<?= $a; ?>">
						<td> <button type="button" name="button" data-bs-toggle="modal" data-bs-target="#EnvoyerDemandeCotation" class="btn btn-outline-primary" style="padding: 8px!important;border: 1px solid #ef8351;"><i style="font-size: 20px;color: #ef8351c4;" class="fal fa-envelope-open-text"></i></button> </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<div class="row">
				<div class="modal fade" id="EnvoyerDemandeCotation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" style="max-width: 40%;">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Envoyer une demande de cotation</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i></button>
							</div>
							<div class="modal-body text-left">
								<label for="nom_four" class="col-form-label label-input" style="color:#0b2424;">Réponse avant :</label>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-inline">
											<label for="date_mail" class="col-sm-4 col-form-label" style="padding-left:0;">DATE_MAIL :</label>
											<div class="col-sm-8" style="padding:0">
												<input type="text" name="date_mail" class="form-control" value="demain matin" style="width:100%">
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-inline">
											<label for="heure_mail" class="col-sm-4 col-form-label" style="padding-left:0;">HEURE_MAIL :</label>
											<div class="col-sm-8" style="padding:0">
												<input type="time" name="heure_mail" class="form-control" value="10:30"  style="width:100%">
											</div>
										</div>
									</div>
									<div class="col-sm-12 text-right">
										<a href="newsletter/modele/MODELE_demande_cotation.html" target="_blank" class="btn btn-outline-primary" style="margin-top: 10px;">VISUALISER MAIL</a>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
								<input type="submit" name="envoyer_demande_cotations" id="envoyer_demande_cotations" class="btn btn-primary" value="Envoyer">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="/admin/js/script_fournisseurs.js" charset="utf-8"></script>
<script src="/admin/js/script_zones.js" charset="utf-8"></script>
