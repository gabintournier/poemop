<style media="screen">
.ligne-menu {width: 32%!important;}
</style>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

$title = "Fusionner un client";
$title_page = "Fusionner un client";
ob_start();

include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_groupements.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_commandes.php";
include_once __DIR__ . "/inc/pmp_inc_groupements.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_clients.php";

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
unset($_SESSION['facture_saisie']);
$res = getClientsListe($co_pmp);

if(!empty($_POST["charger_client_1"]))
{
	$user_id = $_POST["n_id_client1"];
	header('Location: /admin/fusionner_clients.php?user_id_1='.$user_id);
}

if(!empty($_POST["charger_client_2"]))
{
	$user_id = $_GET["user_id_1"];
	$user_id_2 = $_POST["n_id_client2"];

	header('Location: /admin/fusionner_clients.php?user_id_1='.$user_id.'&user_id_2='.$user_id_2);
}

if(isset($_GET["user_id_1"]))
{
	$user_id = $_GET["user_id_1"];
	$client1 = getInfosClient($co_pmp, $user_id);
}

if(isset($_GET["user_id_2"]))
{
	$user_id_2 = $_GET["user_id_2"];
	$client2 = getInfosClient($co_pmp, $user_id_2);
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
		<a href="clients_nouveaux_inscrits.php">Nouveaux inscrits</a>
		<a href="recherche_client.php">Chercher</a>
		<a href="fusionner_clients.php" class="active">Fusionner</a>
		<a href="gestion_client.php">Nouveau</a>
	</div>
	<form method="post">
		<p style="font-size: 13px;font-family: 'Goldplay Alt Medium';">La fusion va garder seulement le Client 1. S'arrurer de reporter sur le client 1, les données du client 2 à sauvegarder. Seules les commandes du client 2 ayant un statut valide (terminé ou en cours de livraison) seront basculées sur le client 1.</p>
		<label class="label-title" style="margin: 0;">Client 1 à conserver</label>
		<div class="ligne" style="width: 2%;"></div>
		<div class="row">
			<div class="col-sm-2">
				<div class="form-inline">
					<label for="nom_1" class="col-sm-3 col-form-label" style="padding-left:0;">Nom</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="nom_1" class="form-control" style="width:100%;" value="<?php if(isset($client1["name"])) { echo $client1["name"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-inline">
					<label for="prenom_1" class="col-sm-4 col-form-label" style="padding-left:0;">Prénom</label>
					<div class="col-sm-8" style="padding:0">
						<input type="text" name="prenom_1" class="form-control" style="width:100%;" value="<?php if(isset($client1["prenom"])) { echo $client1["prenom"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-inline">
					<label for="email_1" class="col-sm-3 col-form-label" style="padding-left:0;">Email</label>
					<div class="col-sm-9" style="padding:0">
						<input type="email" name="email_1" class="form-control" style="width:100%;" value="<?php if(isset($client1["joomla_email"])) { echo $client1["joomla_email"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2" style="max-width: 13%;">
				<div class="form-inline">
					<label for="code_clien_1" class="col-sm-4 col-form-label" style="padding-left:0;">Code</label>
					<div class="col-sm-8" style="padding:0">
						<input type="text" name="code_client_1" class="form-control" style="width:100%;" value="<?php if(isset($client1["user_id"])) { echo $client1["user_id"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-4" style="max-width: 22%;">
				<div class="form-inline">
					<label for="adresse_1" class="col-sm-3 col-form-label" style="padding-left:0;">Adresse</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="adresse_1" class="form-control" style="width:100%;" value="<?php if(isset($client1["adresse"])) { echo $client1["adresse"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2 text-right" style="max-width: 13%;">
				<a href="<?= $actual_link; ?><?php if(isset($_GET["user_id_1"]) || isset($_GET["user_id_2"])) { echo "&"; } else { echo "?"; } ?>popup1=oui" class="btn btn-warning" style="width:100%;">Charger client 1</a>

				<div class="modal fade" id="ChargerClient1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" style="max-width: 85%;">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Sélectionner un client</h5>
								<button type="button" class="btn-close <?php if(isset($_GET["user_id_1"])) { echo "b-close-c-user1"; } else { echo "b-close-c"; } ?>" data-bs-dismiss="modal" aria-bs-label="Close"> <i class="fal fa-times"></i> </button>
							</div>
							<div class="modal-body text-left">
								<?php include 'form/form_recherche_clients.php'; ?>
							</div>
							<div class="modal-footer text-right">
								<input type="hidden" name="n_id_client1" id="n_id_client1" value="">
								<input type="submit" name="charger_client_1" class="btn btn-primary valider_client" style="color:#fff;"  value="VALIDER">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="margin-top: 0.4%;">
			<div class="col-sm-2">
				<div class="form-inline">
					<label for="code_postal_1" class="col-sm-6 col-form-label" style="padding-left:0;">Code postal</label>
					<div class="col-sm-6" style="padding:0">
						<input type="text" name="code_postal_1" class="form-control" style="width:100%;" value="<?php if(isset($client1["code_postal"])) { echo $client1["code_postal"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-inline">
					<label for="ville_1" class="col-sm-3 col-form-label" style="padding-left:0;">Ville</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="ville_1" class="form-control" style="width:100%;" value="<?php if(isset($client1["ville"])) { echo $client1["ville"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-inline">
					<label for="tel_1_1" class="col-sm-2 col-form-label" style="padding-left:0;">Tel 1 / 2</label>
					<div class="col-sm-5" style="padding:0">
						<input type="text" name="tel_2_1" class="form-control" style="width:100%;" value="<?php if(isset($client1["tel_port"])) { echo $client1["tel_port"]; } ?>">
					</div>
					<div class="col-sm-5" style="padding-right: 0;">
						<input type="text" name="code_client" class="form-control" style="width:100%;" value="<?php if(isset($client1["tel_fixe"])) { echo $client1["tel_fixe"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-inline">
					<label for="tel_3_1" class="col-sm-2 col-form-label" style="padding-left:0;">Tel 3</label>
					<div class="col-sm-6" style="padding:0">
						<input type="text" name="tel_3_1" class="form-control" style="width:100%;" value="<?php if(isset($client1["tel_3"])) { echo $client1["tel_3"]; } ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="margin-top: 0.4%;">
			<div class="col-sm-12">
				<label for="com_client_1" class=" col-form-label" style="padding-left:0;">Commentaire client</label>
				<textarea name="com_client_1" class="form-control" rows="3" cols="" style="height:auto;"><?php if(isset($client1["com_user"])) { echo $client1["com_user"]; } ?></textarea>
			</div>
		</div>
		<div class="row" style="margin-top: 0.4%;">
			<div class="col-sm-12">
				<label for="com_crm_1" class=" col-form-label" style="padding-left:0;">Commentaire CRM</label>
				<textarea name="com_crm_1" class="form-control" rows="3" cols="" style="height:auto;"><?php if(isset($client1["com_crm"])) { echo $client1["com_crm"]; } ?></textarea>
			</div>
		</div>
		<hr>
		<label class="label-title" style="margin: 0;">Client 2 à supprimer</label>
		<div class="ligne" style="width: 2%;"></div>
		<div class="row">
			<div class="col-sm-2">
				<div class="form-inline">
					<label for="nom_2" class="col-sm-3 col-form-label" style="padding-left:0;">Nom</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="nom_2" class="form-control" style="width:100%;" value="<?php if(isset($client2["name"])) { echo $client2["name"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-inline">
					<label for="prenom_2" class="col-sm-4 col-form-label" style="padding-left:0;">Prénom</label>
					<div class="col-sm-8" style="padding:0">
						<input type="text" name="prenom_2" class="form-control" style="width:100%;" value="<?php if(isset($client2["prenom"])) { echo $client2["prenom"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-inline">
					<label for="email_2" class="col-sm-3 col-form-label" style="padding-left:0;">Email</label>
					<div class="col-sm-9" style="padding:0">
						<input type="email" name="email_2" class="form-control" style="width:100%;" value="<?php if(isset($client2["joomla_email"])) { echo $client2["joomla_email"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2" style="max-width: 13%;">
				<div class="form-inline">
					<label for="code_clien_2" class="col-sm-4 col-form-label" style="padding-left:0;">Code</label>
					<div class="col-sm-8" style="padding:0">
						<input type="text" name="code_client_2" class="form-control" style="width:100%;" value="<?php if(isset($client2["user_id"])) { echo $client2["user_id"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-4" style="max-width: 22%;">
				<div class="form-inline">
					<label for="adresse_2" class="col-sm-3 col-form-label" style="padding-left:0;">Adresse</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="adresse_2" class="form-control" style="width:100%;" value="<?php if(isset($client2["adresse"])) { echo $client2["adresse"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2 text-right" style="max-width: 13%;">
<?php
				if(isset($_GET["user_id_1"]))
				{
?>
				<a href="<?= $actual_link; ?><?php if(isset($_GET["user_id_1"]) || isset($_GET["user_id_2"])) { echo "&"; } else { echo "?"; } ?>popup2=oui" class="btn btn-warning" style="width:100%;">Charger client 2</a>
<?php
				}
				else
				{
?>
				<a href="fusionner_clients.php?popup2=oui" class="btn btn-warning" style="width:100%;">Charger client 2</a>
<?php
				}
?>

				<div class="modal fade" id="ChargerClient2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" style="max-width: 85%;">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Sélectionner un client</h5>
								<button type="button" class="btn-close <?php if(isset($_GET["user_id_1"])) { echo "b-close-c-user1"; } else { echo "b-close-c"; } ?>" data-bs-dismiss="modal" aria-bs-label="Close"> <i class="fal fa-times"></i> </button>
							</div>
							<div class="modal-body text-left">
								<?php include 'form/form_recherche_clients2.php'; ?>
							</div>
							<div class="modal-footer text-right">
								<input type="hidden" name="n_id_client2" id="n_id_client2" value="">
								<input type="submit" name="charger_client_2" class="btn btn-primary valider_client" style="color:#fff;"  value="VALIDER">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="margin-top: 0.4%;">
			<div class="col-sm-2">
				<div class="form-inline">
					<label for="code_postal_2" class="col-sm-6 col-form-label" style="padding-left:0;">Code postal</label>
					<div class="col-sm-6" style="padding:0">
						<input type="text" name="code_postal_2" class="form-control" style="width:100%;" value="<?php if(isset($client2["code_postal"])) { echo $client2["code_postal"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-inline">
					<label for="ville_2" class="col-sm-3 col-form-label" style="padding-left:0;">Ville</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="ville_2" class="form-control" style="width:100%;" value="<?php if(isset($client2["ville"])) { echo $client2["ville"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-inline">
					<label for="tel_1_2" class="col-sm-2 col-form-label" style="padding-left:0;">Tel 1 / 2</label>
					<div class="col-sm-5" style="padding:0">
						<input type="text" name="tel_1_2" class="form-control" style="width:100%;" value="<?php if(isset($client2["tel_port"])) { echo $client2["tel_port"]; } ?>">
					</div>
					<div class="col-sm-5" style="padding-right: 0;">
						<input type="text" name="tel_2_2" class="form-control" style="width:100%;" value="<?php if(isset($client2["tel_fixe"])) { echo $client2["tel_fixe"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-inline">
					<label for="tel_3_2" class="col-sm-2 col-form-label" style="padding-left:0;">Tel 3</label>
					<div class="col-sm-6" style="padding:0">
						<input type="text" name="tel_3_2" class="form-control" style="width:100%;" value="<?php if(isset($client2["tel_3"])) { echo $client2["tel_3"]; } ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="margin-top: 0.4%;">
			<div class="col-sm-12">
				<label for="com_client_2" class=" col-form-label" style="padding-left:0;">Commentaire client</label>
				<textarea name="com_client_2" class="form-control" rows="3" cols="" style="height:auto;"><?php if(isset($client2["com_user"])) { echo $client2["com_user"]; } ?></textarea>
			</div>
		</div>
		<div class="row" style="margin-top: 0.4%;">
			<div class="col-sm-12">
				<label for="com_crm_2" class=" col-form-label" style="padding-left:0;">Commentaire client</label>
				<textarea name="com_crm_2" class="form-control" rows="3" cols="" style="height:auto;"><?php if(isset($client2["com_crm"])) { echo $client2["com_crm"]; } ?></textarea>
			</div>
		</div>
		<div class="row" style="margin-top: 1%;">
			<div class="col-sm-11 text-right" style="max-width: 87%;">
				<a href="fusionner_clients.php" class="btn btn-secondary">VIDER</a>
			</div>
			<div class="col-sm-2" style="max-width: 13%;">
				<button type="button" name="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Confirmation" style="width:100%">FUSIONNER</button>
				<div class="modal fade" id="Confirmation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Êtes-vous sûr de vouloir fusionner ces deux clients</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i> </button>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
								<input type="submit" name="fusionner_clients" class="btn btn-primary" value="Oui">
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
include "template.php";
?>

<script src="js/script_clients.js" charset="utf-8"></script>
