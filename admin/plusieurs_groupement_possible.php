<style media="screen">
.ligne-menu {width: 330px!important;}
.btn-outline-primary { background: #f7f7f7!important;padding: 3px 20px!important;border-radius: 6px!important;font-size: 14px!important;}
</style>
<?php
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

$recharge = TRUE;
$RequestSignature = md5($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].print_r($_POST, true));
if($_SESSION['LastRequest'] != $RequestSignature)
{
	$_SESSION['LastRequest'] = $RequestSignature;
	$recharge = FALSE;
}
// On detecte le token du form et le token de la session sont identique
if(isset($_SESSION['token']) AND isset($_POST['token']) AND !empty($_SESSION['token']) AND !empty($_POST['token']))
{
    if ($_SESSION['token'] == $_POST['token'])
	{
		$recharge = FALSE;
    }
}

$title = 'Commandes à affecter';
$title_page = 'Commandes à affecter';
ob_start();

include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_nouvelles_commandes.php";
unset($_SESSION['facture_saisie']);
$res_cmd = getCommandesOrphelines($co_pmp);

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
		<a href="commandes_orphelines.php">Commandes Orphelines</a>
		<a  href="groupement_possible.php">Groupement possible</a>
		<a href="#" class="active">Plusieurs groupements possible</a>
	</div>
	<form method="post">
		<label class="label-title" style="margin: 0;">Volume commandes</label>
		<div class="ligne"></div>
		<div class="row">
			<div class="col-sm-2" style="max-width: 11%;">
				<div class="form-inline" style="margin-top:0.5%;">
					<label for="vol_ord" class="col-sm-6 col-form-label" style="padding-left:0;">Vol Ord</label>
					<div class="col-sm-6" style="padding:0;">
						<input type="text" name="vol_ord" value="1200" class="form-control" style="width:100%;">
					</div>
				</div>
			</div>
			<div class="col-sm-2" style="max-width: 11%;">
				<div class="form-inline" style="margin-top:0.5%;">
					<label for="vol_ord" class="col-sm-6 col-form-label" style="padding-left:0;">Vol Sup</label>
					<div class="col-sm-6" style="padding:0;">
						<input type="text" name="vol_ord" value="1200" class="form-control" style="width:100%;">
					</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-sm-12">

				<div class="tableau" style="height: 600px;margin-top: 0;">
						<table class="table" id="trie_table_affecter">
							<thead>
								<th style="width: 4px;"><i class="fal fa-sort"></i></th>
								<th style="padding: 8px 10px;width: 65px;">Select</th>
								<th style="padding: 8px 10px;width: 65px;">Nb&nbsp;Litre</th>
								<th style="padding: 8px 10px;width: 40px;" class="text-center">Type</th>
								<th style="padding: 8px 10px;width: 65px;">Date</th>
								<th style="padding: 8px 10px;width: 60px;" class="text-center">CP</th>
								<th style="padding: 8px 10px;width: 250px;">Ville</th>
								<th style="padding: 8px 10px;width: 130px;">Nom</th>
								<th style="padding: 8px 10px;width: 130px;">Prénom</th>
								<th style="padding: 8px 10px;width: 130px;">Etat</th>
								<th style="padding: 8px 10px;width: 130px; border-left: 1px solid #0b242436;">Groupement</th>
							</thead>
							<tbody>

							</tbody>
						</table>
				</div>
			</div>
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
