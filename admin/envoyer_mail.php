<style media="screen">
.ligne-menu {width: 24%!important;}
</style>
<?php
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

$title = 'Envoyer un mail';
$title_page = 'Envoyer un mail';
ob_start();




include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_mail.php";
unset($_SESSION['facture_saisie']);
?>
<div class="bloc">
	<div class="menu-bloc">
		<a href="mail_type.php" >Mails Type</a>
		<a href="mail_modele.php">Mail Modèle</a>
		<a href="envoyer_mail.php" class="active">Envoyer Mail</a>
		<a href="envoyer_sms.php">Envoyer SMS</a>
		<a href="param_sms.php">Param SMS</a>
		<a href="gestion_client.php">Alerte PF</a>
	</div>
	<form method="post">
		<label class="label-title" style="margin: 0;">Choix du mail</label>
		<div class="ligne" style="width: 2%;"></div>
		<div class="form-inline">
			<label for="automatisation" class="col-sm-3 col-form-label" style="padding-left:0;max-width: 19%;">Choisissez le type de mail à envoyer</label>
			<div class="col-sm-3" style="padding:0">
				<select class="form-control" name="automatisation" style="width:100%">
					<option value=""></option>
				</select>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-sm-8">
				<label class="label-title" style="margin: 0;">Mail</label>
				<div class="ligne" style="width: 3%;"></div>
			</div>
			<div class="col-sm-4">
				<label for="priorite" class="col-sm-3 col-form-label" style="padding-left:0;">Priorité</label>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-inline">
					<label for="envoi_de" class="col-sm-3 col-form-label" style="padding-left:0;">Envoi de</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" id="envoi_de" name="envoi_de" class="form-control" value="info@poemop.fr" style="width:100%;">
					</div>
				</div>
				<div class="form-inline">
					<label for="repondre_a" class="col-sm-3 col-form-label" style="padding-left:0;">Répondre à</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" id="repondre_a" name="repondre_a" class="form-control" value="info@poemop.fr style="width:100%;">
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-inline">
					<label for="envoi_de_l" class="col-sm-4 col-form-label" style="padding-left:0;">Envoi de (libellé)</label>
					<div class="col-sm-6" style="padding:0">
						<input type="text" id="envoi_de_l" name="envoi_de_l" class="form-control" value="Achat Groupé Fioul" style="width:100%;">
					</div>
				</div>
				<div class="form-inline">
					<label for="repondre_a_l" class="col-sm-4 col-form-label" style="padding-left:0;">Répondre à (libellé)</label>
					<div class="col-sm-6" style="padding:0">
						<input type="text" id="repondre_a_l" name="repondre_a_l" class="form-control" value="Achat Groupé Fioul" style="width:100%;">
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<label for="priorite_1" class="col-form-label"><input type="radio" id="priorite_1" name="priorite" value="1"> Très lente (1 / J)</label><br>
				<label for="priorite_2" class="col-form-label"><input type="radio" id="priorite_2" name="priorite" value="2"> Lente (1 / H)</label>
			</div>
			<div class="col-sm-2">
				<label for="priorite_3" class="col-form-label"><input type="radio" id="priorite_3" name="priorite" value="1"> Normal (1 / 10 min)</label><br>
				<label for="priorite_4" class="col-form-label"><input type="radio" id="priorite_4" name="priorite" value="2"> Haute (1 / 10 min)</label>
			</div>
		</div>
		<label for="destinataire" class="col-form-label" style="padding-left:0;">Destinataire</label>
		<textarea name="destinataire" class="form-control" id="destinataire" rows="3" style="height:auto;"></textarea>
		<div class="form-inline" style="margin-top:0.7%">
			<label for="objet" class="col-sm-2 col-form-label" style="padding-left:0;max-width: 5%;">Objet</label>
			<div class="col-sm-8" style="padding:0">
				<input type="text" id="objet" name="objet" class="form-control" value="" style="width:100%;">
			</div>
			<div class="col-sm-2 elign-center" style="padding:0">
				<input type="submit" name="maj" value="MISE A JOUR DU MAIL TYPE" class="btn btn-warning" style="width:100%;margin-left: 35%;">
			</div>
		</div>
		<label for="mail" class="col-form-label" style="padding-left:0;">Mail</label>
		<textarea name="mail" class="form-control" id="mail" rows="10" style="height:auto;"></textarea>
		<div class="text-right" style="margin-top:1%;">
			<input type="submit" name="maj" value="INSÉRER MAIL EN BASE" class="btn btn-primary">
		</div>
		<hr>
		<label for="tel_port" class="col-form-label" style="padding-left:0;">Tel Port.</label>
		<textarea name="tel_port" class="form-control" id="tel_port" rows="2" style="height:auto;"></textarea>
		<label for="tel_fixe" class="col-form-label" style="padding-left:0;">Tel Fixe</label>
		<textarea name="tel_fixe" class="form-control" id="tel_fixe" rows="2" style="height:auto;"></textarea>
		<div class="row">
			<div class="col-sm-6 text-right" style="margin-top:1%;">
				<input type="submit" name="maj" value="Récupérer Mail de tous les clients" class="btn btn-secondary" style="width:50%;">
			</div>
			<div class="col-sm-6" style="margin-top:1%;">
				<input type="submit" name="maj" value="Récupérer Mail de tous les fournisseurs" class="btn btn-secondary" style="width:50%;">
			</div>
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
