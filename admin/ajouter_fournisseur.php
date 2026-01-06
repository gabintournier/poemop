<style media="screen">
.ligne-menu {width: 31%!important;}
.menu > h1, .ligne-menu {margin-left:6%;}
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

$title = 'Ajouter un fournisseur';
$title_page = 'Ajouter un fournisseur';
$return = true;
$link = '/admin/liste_fournisseurs.php';
ob_start();



include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
unset($_SESSION['facture_saisie']);
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
	<div class="form">
		<label class="label-title" style="margin: 0;">Formulaire</label>
		<div class="ligne" style="width: 2%;"></div>
		<form method="post">
			<div class="row" style="margin-top: 2%;">
				<div class="col-sm-3">
					<div class="form-inline">
						<label for="nom_four" class="col-sm-4 col-form-label" style="padding-left:0;">Raison social</label>
						<div class="col-sm-8" style="padding:0">
							<input type="text" name="nom_four" value="<?php if(isset($_POST["nom_four"])) { echo $_POST["nom_four"];} ?>" class="form-control" style="width:100%;">
						</div>
					</div>
				</div>
				<div class="col-sm-3 align-self-center">
					<div class="form-inline">
						<label for="etat_four" class="col-sm-2 col-form-label" style="padding-left:0;">Etat</label>
						<div class="col-sm-10" style="padding:0">
							<select class="form-control input-custom" name="etat_four" style="width:100%;">
								<option value="0" <?php if(isset($_POST["etat_four"])) { if($_POST["etat_four"] == 0) { echo "selected='selected'";} } ?>>Non contacté</option>
								<option value="1" <?php if(isset($_POST["etat_four"])) { if($_POST["etat_four"] == 1) { echo "selected='selected'";} } ?>>Partenaire</option>
								<option value="2" <?php if(isset($_POST["etat_four"])) { if($_POST["etat_four"] == 2) { echo "selected='selected'";} } ?>>Pas interessant</option>
								<option value="3" <?php if(isset($_POST["etat_four"])) { if($_POST["etat_four"] == 3) { echo "selected='selected'";} } ?>>Pas interessé</option>
								<option value="4" <?php if(isset($_POST["etat_four"])) { if($_POST["etat_four"] == 4) { echo "selected='selected'";} } ?>>A recontacter</option>
								<option value="5" <?php if(isset($_POST["etat_four"])) { if($_POST["etat_four"] == 5) { echo "selected='selected'";} } ?>>A recontacter com</option>
								<option value="6" <?php if(isset($_POST["etat_four"])) { if($_POST["etat_four"] == 6) { echo "selected='selected'";} } ?>>Autres que fioul</option>
								<option value="7" <?php if(isset($_POST["etat_four"])) { if($_POST["etat_four"] == 7) { echo "selected='selected'";} } ?>>Fin du partenariat</option>
								<option value="8" <?php if(isset($_POST["etat_four"])) { if($_POST["etat_four"] == 8) { echo "selected='selected'";} } ?>>Partenaire secondaire</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-inline">
						<label for="cp_four" class="col-sm-6 col-form-label" style="padding-left:0;">Code postal</label>
						<div class="col-sm-6" style="padding:0">
							<input type="text" name="cp_four" value="<?php if(isset($_POST["cp_four"])) { echo $_POST["cp_four"];} ?>" class="form-control" style="width:100%;">
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-inline">
						<label for="adresse_four" class="col-sm-2 col-form-label" style="padding-left:0;">Adresse</label>
						<div class="col-sm-10" style="padding:0">
							<input type="text" name="adresse_four" value="<?php if(isset($_POST["adresse_four"])) { echo $_POST["adresse_four"];} ?>" class="form-control" style="width:100%;">
						</div>
					</div>
				</div>
				<div class="col-sm-3" style="margin-top:0.5%;">
					<div class="form-inline">
						<label for="ville_four" class="col-sm-2 col-form-label" style="padding-left:0;">Ville</label>
						<div class="col-sm-10" style="padding:0">
							<input type="text" name="ville_four" value="<?php if(isset($_POST["ville_four"])) { echo $_POST["ville_four"];} ?>" class="form-control" style="width:100%;">
						</div>
					</div>
				</div>
				<div class="col-sm-3" style="margin-top:0.5%;">
					<div class="form-inline">
						<label for="fixe_four" class="col-sm-2 col-form-label" style="padding-left:0;">Fixe</label>
						<div class="col-sm-10" style="padding:0">
							<input type="text" name="fixe_four" value="<?php if(isset($_POST["fixe_four"])) { echo $_POST["fixe_four"];} ?>" class="form-control" style="width:100%;">
						</div>
					</div>
				</div>
				<div class="col-sm-3" style="margin-top:0.5%;">
					<div class="form-inline">
						<label for="mobile_four" class="col-sm-3 col-form-label" style="padding-left:0;">Mobile</label>
						<div class="col-sm-9" style="padding:0">
							<input type="text" name="mobile_four" value="<?php if(isset($_POST["mobile_four"])) { echo $_POST["mobile_four"];} ?>" class="form-control" style="width:100%;">
						</div>
					</div>
				</div>
				<div class="col-sm-3" style="margin-top:0.5%;">
					<div class="form-inline">
						<label for="fax_four" class="col-sm-2 col-form-label" style="padding-left:0;">Fax</label>
						<div class="col-sm-10" style="padding:0">
							<input type="text" name="fax_four" value="<?php if(isset($_POST["fax_four"])) { echo $_POST["fax_four"];} ?>" class="form-control" style="width:100%;">
						</div>
					</div>
				</div>
				<div class="col-sm-3" style="margin-top:0.5%;">
					<div class="form-inline">
						<label for="mail_four" class="col-sm-2 col-form-label" style="padding-left:0;">Mail</label>
						<div class="col-sm-10" style="padding:0">
							<input type="text" name="mail_four" value="<?php if(isset($_POST["mail_four"])) { echo $_POST["mail_four"];} ?>" class="form-control" style="width:100%;">
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-inline" style="margin-top:0.5%;">
						<label for="com_ord" class="col-sm-5 col-form-label" style="padding-left:0;">Commission ORD</label>
						<div class="col-sm-4" style="padding:0">
							<input type="text" name="com_ord" value="<?php if(isset($_POST["com_ord"])) { echo $_POST["com_ord"];} ?>" class="form-control text-right" style="width:100%;">
						</div>
						<span>HT</span>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-inline" style="margin-top:0.5%;">
						<label for="com_sup" class="col-sm-2 col-form-label" style="padding-left:0;">SUP</label>
						<div class="col-sm-4" style="padding:0">
							<input type="text" name="com_sup" value="<?php if(isset($_POST["com_sup"])) { echo $_POST["com_sup"];} ?>" class="form-control text-right" style="width:100%;">
						</div>
						<span>HT</span>
					</div>
				</div>
				<div class="col-sm-12">
					<label for="commentaire" class="col-form-label" style="padding-left:0;">Commentaire</label>
					<textarea name="commentaire" class="form-control" rows="5" cols="" style="height:auto;">
						<?php if(isset($_POST["commentaire"])) { echo $_POST["commentaire"];} ?>
					</textarea>
				</div>
			</div>
			<div class="text-right" style="margin-top:2%;">
				<input type="submit" name="add_fournisseur" class="btn btn-primary" value="Ajouter">
			</div>
		</form>
	</div>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
