<style media="screen">
.ligne-menu {width: 28%!important;}
</style>
<?php
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

$title = 'Liste des Mails Type';
$title_page = 'Liste des Mails Type';
ob_start();




include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_mail_sms.php";
unset($_SESSION['facture_saisie']);

$res_sms_device = getSmsDevice($co_pmp);

$date_jour = date("Y-m-d");
$date_hier = date('Y-m-d',strtotime('-1 day',strtotime($date_jour)));

if(!empty($_POST["charger_sms"]))
{
	$res_sms = getListeSms($co_pmp);
	$sms_a_envoye = getStatsSms($co_pmp, '0');
	$sms_envoye = getStatsSms($co_pmp, '1');
	$sms_annule = getStatsSms($co_pmp, '2');
	$sms_annule_p = getStatsSms($co_pmp, '3');
}
elseif (!empty($_POST["charger_num"]))
{
	$numero = $_POST["numero_sms"];
	$res_sms = getListeSmsNumero($co_pmp, $numero);
}

if(isset($message))
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
		<a href="envoyer_sms.php">Envoyer SMS</a>
		<a href="param_sms.php" class="active">Param SMS</a>
		<a href="alerte_pf.php">Alerte PF</a>
	</div>
	<form method="post"  id="FormID">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-inline">
					<label for="sms_device" class="col-sm-5 col-form-label" style="padding-left:0;">Téléphone à utiliser pour les envois de SMS</label>
					<div class="col-sm-4" style="padding:0">
						<select class="form-control" name="sms_device" style="width:100%" onchange="myFunction()">
<?php
						while($sms_device = mysqli_fetch_array($res_sms_device))
						{
?>
							<option value="<?= $sms_device["id"]; ?>" <?php if( $sms_device["expediteur"] == '1' ){ echo "selected='selected'"; } ?>><?= $sms_device["libelle"]; ?></option>
<?php
						}
?>
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-6 text-right">
				<button type="button" name="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#Confirmation" style="width:40%">ANNULER ENVOIS EN COURS</button>
				<div class="modal fade" id="Confirmation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-left" id="exampleModalLabel">Êtes-vous sûr de vouloir annuler les envois en cours</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i> </button>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
								<input type="submit" name="annuler_envois" class="btn btn-primary" value="Oui">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr>
		<label class="label-title" style="margin: 0;">Liste des SMS</label>
		<div class="ligne" style="width: 2%;"></div>
		<div class="row">
			<div class="col-sm-9">
				<div class="form-inline">
					<label for="date_ins_min" class="col-sm-2 col-form-label" style="padding-left:0;max-width: 15%;">Date insertion entre le</label>
					<div class="col-sm-1" style="padding:0;max-width: 12%;">
						<input type="date" id="date_ins_min" name="date_ins_min" class="form-control" value="<?php if(isset($_POST["date_ins_min"])) { echo $_POST["date_ins_min"]; } else { echo $date_hier; } ?>" style="width:100%;">
					</div>
					<div class="col-sm-1">
						<label for="date_ins_max" class="col-sm-2 col-form-label" style="max-width: 100%;">et le</label>
					</div>
					<div class="col-sm-1" style="padding:0;max-width: 12%;">
						<input type="date" id="date_ins_max" name="date_ins_max" class="form-control" value="<?php if(isset($_POST["date_ins_max"])) { echo $_POST["date_ins_max"]; } else { echo $date_jour; } ?>" style="width:100%;">
					</div>
					<div class="col-sm-5">
						<input type="submit" name="charger_sms" class="btn btn-primary" value="CHARGER">
					</div>
				</div>
			</div>
			<div class="col-sm-3 text-right">
				<div class="form-inline">
					<label for="numero_sms" class="col-sm-3 col-form-label" style="padding-left:0;max-width: 25%;">Numéro</label>
					<div class="col-sm-4" style="padding:0;">
						<input type="text" id="numero_sms" name="numero_sms" class="form-control" value="" style="width:100%;">
					</div>
					<div class="col-sm-5" style="padding-right:0">
						<input type="submit" name="charger_num" class="btn btn-primary" value="CHARGER" style="width:100%;">
					</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="tableau" style="height: 470px;">
			<table class="table" style="white-space: nowrap;">
				<thead>
					<tr>
						<th>N° Tel</th>
						<th>Etat</th>
						<th class="text-center">Priorité</th>
						<th>Date insert</th>
						<th>Date envoi</th>
						<th class="text-center">N° CMD</th>
						<th>SMS</th>
						<th>Expéditeur</th>
					</tr>
				</thead>
				<tbody>
<?php
				if(isset($res_sms))
				{
					while ($sms = mysqli_fetch_array($res_sms))
					{
						if($sms["etat"] == '0') { $etat = 'A envoyer'; }
						elseif($sms["etat"] == '1') { $etat = 'Envoyé'; }
						elseif($sms["etat"] == '2') { $etat = 'Annulé client'; }
						elseif($sms["etat"] == '3') { $etat = 'Annulé POEMOP'; }
?>
					<tr>
						<td><?= $sms["telephone"]; ?></td>
						<td class="text-center"><?= $etat; ?></td>
						<td><?= $sms["priorite"]; ?></td>
						<td><?= $sms["date_insertion"]; ?></td>
						<td><?= $sms["date_envoi"]; ?></td>
						<td class="text-center"><?= $sms["cmd_id"]; ?></td>
						<td><?= $sms["message"]; ?></td>
						<td><?= $sms["expediteur"]; ?></td>
					</tr>
<?php
					}
				}
?>
				</tbody>
			</table>
		</div>
		<div class="row">
			<div class="col-sm-2">
				<p style="font-size: 14px;color: #0b2424ab;">SMS à envoyé : <strong><?php if(isset($sms_a_envoye["stats"])) { echo $sms_a_envoye["stats"]; } ?></strong></p>
			</div>
			<div class="col-sm-2">
				<p style="font-size: 14px;color: #0b2424ab;">SMS envoyé(s) : <strong><?php if(isset($sms_envoye["stats"])) { echo $sms_envoye["stats"]; } ?></strong> </p>
			</div>
			<div class="col-sm-2">
				<p style="font-size: 14px;color: #0b2424ab;">SMS annulé client : <strong><?php if(isset($sms_annule["stats"])) { echo $sms_annule["stats"]; } ?></strong></p>
			</div>
			<div class="col-sm-2">
				<p style="font-size: 14px;color: #0b2424ab;">SMS annulé POEMOP : <strong><?php if(isset($sms_annule_p["stats"])) { echo $sms_annule_p["stats"]; } ?></strong></p>
			</div>
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script type="text/javascript">
function myFunction(val) {
	console.log("Entered Value is: " + val);
	var frm = document.getElementById ("FormID");

	frm.submit();
}
</script>
