<style media="screen">
.ligne-menu {width: 155px!important;}
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
$title = 'Livre d\'or';
$title_page = 'Livre d\'or';
ob_start();
unset($_SESSION['facture_saisie']);



include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_avis.php";

if (!empty($_POST["rechercher_avis"]))
{
	$res_avis = getAvis($co_pmp);
	$message = 'no';
}
elseif (!empty($_POST["valider_statut"]))
{
	$res_avis = getAvis($co_pmp);

}
elseif (!empty($_POST["en_attente"]))
{
	$res_avis = getAvis($co_pmp);

}
elseif (!empty($_GET["get"]))
{
	$res_avis = getAvisReturn($co_pmp);
}

$date = date('Y-m-d',strtotime(date('Y-01-01')));

if (isset($message))
{
if ($message != 'no')
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
}
?>
<div class="bloc">
	<form method="post">
		<div class="filtre">
			<label class="label-title" style="margin: 0;">Recherche</label>
			<div class="ligne"></div>
			<div class="row">
				<div class="col-sm-3 col-sm-3-res">
					<div class="form-inline" style="margin-top:1%;">
						<label for="date_min" class="col-sm-3 col-form-label col-date-min" style="padding-left:0;">Depuis le</label>
						<div class="col-sm-6" style="padding:0">
							<input type="date" class="form-control input-custom" id="date_min" name="date_min" value="<?php if(isset($_POST["date_min"])) { echo $_POST["date_min"]; } else { echo $date; } ?>" style="width:100%;">
						</div>
					</div>
				</div>
				<div class="col-sm-2 col-sm-2-res" style="margin-left: -5%;">
					<div class="form-inline" style="margin-top:1%;">
						<label for="statut_avis" class="col-sm-7 col-form-label" style="padding-left:0;">Ayant le statut</label>
						<div class="col-sm-4" style="padding:0">
							<select class="form-control input-custom" name="statut_avis">
									<option value="0" <?php if(isset($_POST['statut_avis']) && $_POST['statut_avis'] == "0") echo "selected"; ?>>Non validé</option>
									<option value="1" <?php if(isset($_POST['statut_avis']) && $_POST['statut_avis'] == "1") echo "selected"; ?>>Validé</option>
									<option value="2" <?php if(isset($_POST['statut_avis']) && $_POST['statut_avis'] == "2") echo "selected"; ?>>Censuré</option>
									<option value="3" <?php if(isset($_POST['statut_avis']) && $_POST['statut_avis'] == "3") echo "selected"; ?>>En attente</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-2 text-center align-self-center">
					<input type="submit" name="rechercher_avis" value="CHARGER" class="btn btn-primary">
				</div>
			</div>
		</div>
		<hr>
		<div class="message">
			<label class="label-title" style="margin: 0;">Message</label>
			<div class="ligne"></div>
			<div class="row">
				<div class="col-sm-3 col-sm-3-res">
					<div class="form-inline" style="margin-top:1%;">
						<label for="signature" class="col-sm-3 col-form-label"  style="padding-left:0;">Signature</label>
						<div class="col-sm-5" style="padding:0">
							<input class="form-control disable" id="signature" type="text" name="signature" value="">
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-inline" style="margin-top:1%;">
						<label for="date_clients" class="col-sm-2 col-form-label" style="padding-left:0;">Date</label>
						<div class="col-sm-6" style="padding:0">
							<input type="date" class="form-control input-custom" id="date_clients" name="date_clients" value="" style="width:100%;" disabled="disabled">
						</div>
					</div>
				</div>
				<div class="col-sm-12" style="margin-top:1%;">
					<label for="message">Message</label>
					<textarea name="message" id="message" class="form-control disable" rows="3" cols="" style="height:auto;"></textarea>
					<label for="censurer_message" class="col-form-label">
						<input type="checkbox" name="censurer_message" id="censurer_message" class="switch value check">
						Censurer
					</label>
					<br>
					<label for="reponse">Réponse</label>
					<textarea name="reponse" id="reponse" class="form-control disable" rows="3" cols="" style="height:auto;"></textarea>
				</div>
				<div class="col-sm-2" style="margin-top:1%;">
					<input type="hidden" name="user_id" id="user_id" value="">
					<input type="hidden" name="id_cmde" id="id_cmde" value="">
					<input type="submit" name="fiche_client" value="FICHE CLIENT" class="btn btn-warning">
				</div>
				<div class="col-sm-8 text-right" style="margin-top:1%;">

					<input type="submit" name="en_attente" value="METTRE EN ATTENTE" class="btn btn-secondary">
				</div>
				<div class="col-sm-2 text-right" style="margin-top:1%;">
					<input type="submit" name="valider_statut" value="VALIDER" class="btn btn-primary" style="width:100%">
				</div>
			</div>
		</div>
		<hr>
	</form>
<?php
if (!empty($_POST["statut_avis"]))
{
	if($_POST["statut_avis"] == 0) { $avis = "non validé"; }
	if($_POST["statut_avis"] == 1) { $avis = "validé"; }
	if($_POST["statut_avis"] == 2) { $avis = "censuré"; }
	if($_POST["statut_avis"] == 3) { $avis = "en attente"; }
?>
	<span style="font-family: 'Goldplay Alt SemiBold';font-size: 14px;color: #0b2424;">Avis <?= $avis; ?></span>
<?php
}
?>
	<div class="tableau">
		<table class="table">
			<thead>
				<tr>
 				   <th  style="width: 5%;">N° Cde</th>
 				   <th class="text-center" style="width: 5%;">Signature</th>
 				   <th class="text-center" style="width: 5%;">Date</th>
 				   <th  class="text-center" style="width: 5%;">Note</th>
 				   <th>Message</th>
 				   <th>Reponse</th>
				   <th class="text-center">Valide</th>
				   <th >Four</th>
<?php
				   if(isset($_POST['statut_avis']) && $_POST['statut_avis'] >= "1")
				   {
?>
				   <th scope="col">Qui</th>
				   <th scope="col">Traiter le</th>
<?php
				   }
?>
 			   </tr>
		   </thead>
		   <tbody>
			<?php
				if (isset($res_avis))
				{
				    while ($avis = mysqli_fetch_array($res_avis))
				    {
				        $timestamp = strtotime($avis['date']);
				        $newDate = date("d-m-Y", $timestamp);
					
				        // On force message et reponse à être des chaînes pour strip_tags
				        $txt = strip_tags($avis['message'] ?? '');
				        $txt_r = strip_tags($avis['reponse'] ?? '');
					
				        $lg_max = 50; // nombre de caractères
					
				        // Troncature message
				        if (strlen($txt) > $lg_max)
				        {
				            $txt = substr($txt, 0, $lg_max);
				            $last_space = strrpos($txt, " ");
				            $txt = substr($txt, 0, $last_space) . "...";
				        }
				        else
				        {
				            $txt = $avis['message'] ?? '';
				        }
					
				        // Troncature réponse
				        if (strlen($txt_r) > $lg_max)
				        {
				            $txt_r = substr($txt_r, 0, $lg_max);
				            $last_space = strrpos($txt_r, " "); // correction ici, c'était $txt au lieu de $txt_r
				            $txt_r = substr($txt_r, 0, $last_space) . "...";
				        }
				        else
				        {
				            $txt_r = $avis['reponse'] ?? '';
				        }
					
				        $str = str_replace('"', "", $avis['message'] ?? '');
				?>
				        <tr class="select avis">
				<?php
				            $four = getFournisseurAvis($co_pmp, $avis['commande_id']);
				?>
				            <input type="hidden" name="message_avis" value="<?= htmlspecialchars($str); ?>">
				            <input type="hidden" name="reponse_avis" value="<?= htmlspecialchars($avis['reponse'] ?? ''); ?>">
				            <input type="hidden" name="id_avis" value="<?= $avis['id']; ?>">
				            <input type="hidden" name="user" value="<?= $avis['user_id']; ?>">
				            <td class="commande_id">
				                <input type="hidden" value="<?= $avis['commande_id']; ?>"><?= $avis['commande_id']; ?>
				            </td>
				            <td class="text-center"><?= htmlspecialchars($avis['signature']); ?></td>
				            <td class="text-center"><?= $avis['date']; ?></td>
				            <td class="text-center"><?= $avis['note']; ?></td>
				            <td><?= htmlspecialchars($txt); ?></td>
				            <td><?= htmlspecialchars($txt_r); ?></td>
				            <td class="text-center"><?= htmlspecialchars($avis['valide']); ?></td>
				            <td><?= isset($four['nom']) ? htmlspecialchars($four['nom']) : ''; ?></td>
				<?php
				            if (isset($_POST['statut_avis']) && $_POST['statut_avis'] >= "1")
				            {
				?>
				                <td><?= htmlspecialchars($avis['intervenant']); ?></td>
				                <td class="text-center">
				                    <?= ($avis['date_reponse'] > '0000-00-00 00:00:00') ? $avis['date_reponse'] : ''; ?>
				                </td>
				<?php
				            }
				?>
				        </tr>
				<?php
				    }
				}
				?>
		   </tbody>
		</table>
	</div>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="/admin/js/script_clients.js" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function() {
	var width = $(window).width();
	if(width >= 1280 && width <= 1290)
	{
		$('.col-sm-3-res').removeClass('col-sm-3').addClass('col-sm-4');
		$('.col-date-min').removeClass('col-sm-3').addClass('col-sm-4');
		$('.col-sm-2-res').removeClass('col-sm-2').addClass('col-sm-3');
	}
});	
</script>	