<style media="screen">
.ligne-menu {width: 23%!important;}
</style>
<?php
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

$title = 'Envoyer un SMS';
$title_page = 'Envoyer un SMS';
ob_start();

include_once __DIR__. "/../inc/pmp_co_connect.php";
include_once __DIR__. "/inc/pmp_inc_fonctions_mail_sms.php";

$res_sms = getSmsType($co_pmp);
unset($_SESSION['facture_saisie']);

if(isset($_POST["sms_type"]))
{
	$id = $_POST["sms_type"];
	$res = getSmsMessage($co_pmp, $id);
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
		<a href="mail_modele.php">Mail Modèle</a>
		<a href="envoyer_sms.php" class="active">Envoyer SMS</a>
		<a href="param_sms.php">Param SMS</a>
		<a href="alerte_pf.php">Alerte PF</a>
	</div>
	<label class="label-title" style="margin: 0;">Choix du SMS</label>
	<div class="ligne" style="width: 2%;"></div>
	<form method="post" id="FormID">
	<div class="row">
		<div class="col-sm-5">
			<div class="form-inline">
				<label for="sms_type" class="col-sm-6 col-form-label" style="padding-left:0;">Choisissez le type de SMS à envoyer</label>
				<div class="col-sm-6" style="padding:0">
					<select class="form-control" name="sms_type" style="width:100%" onchange="myFunction()">
<?php
					while($sms = mysqli_fetch_array($res_sms))
					{
?>
						<option value="<?= $sms["id"]; ?>" <?php if(isset($_POST["sms_type"])) { if( $sms["id"] == $_POST["sms_type"]){ echo "selected='selected'"; } } ?>>  <?= $sms["nom"]; ?></option>
<?php
					}
?>
					</select>
				</div>
			</div>
		</div>
		<div class="col-sm-5 align-self-center">
			<div class="form-inline">
				<input type="submit" name="sauvegarder_sms" value="Sauvegarder SMS type sous le nom" class="col-sm-6 btn btn-primary" style="width:100%;font-size: 14px;">
				<div class="col-sm-6" style="padding-right:0">
					<input type="text" id="nom_sms_type" name="nom_sms_type" class="form-control" value="<?php if(isset($res)) { echo $res["nom"]; } ?>" style="width:100%;">
				</div>
			</div>
		</div>
		<div class="col-sm-2 align-self-center">
			<input type="submit" name="supprimer_sms_type" value="Supprimer SMS type" class="btn btn-warning" style="width:100%;font-size: 14px;">
		</div>
	</div>
	<hr>
	<label class="label-title" style="margin: 0;">SMS</label>
	<div class="ligne" style="width: 2%;"></div>
	<label for="message_sms_type" class="col-form-label" style="padding-left:0;">Message</label>
	<textarea name="message_sms_type" class="form-control" id="message_sms_type" rows="5" style="height:auto;"><?php if(isset($res)) { echo $res["message"]; } ?></textarea>
	<!-- <div class="row" style="margin-top:0.7%;">
		<div class="col-sm-1">
			<label for="priorite" class="col-form-label" style="padding-left:0;">Priorité</label>
		</div>
		<div class="col-sm-1">
			<label for="priorite_1" class="col-form-label"><input type="radio" id="priorite_1" name="priorite" value="1">Lente</label><br>
		</div>
		<div class="col-sm-1">
			<label for="priorite_2" class="col-form-label"><input type="radio" id="priorite_2" name="priorite" value="1">Normal</label><br>
		</div>
		<div class="col-sm-1">
			<label for="priorite_3" class="col-form-label"><input type="radio" id="priorite_3" name="priorite" value="1">Haute</label><br>
		</div>
	</div> -->
	</form>
</div>
<?php
$content = ob_get_clean();
require_once "template.php";
?>
<script type="text/javascript">
function myFunction(val) {
	console.log("Entered Value is: " + val);
	var frm = document.getElementById ("FormID");

	frm.submit();
}
</script>
