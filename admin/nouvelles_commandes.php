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

error_reporting(E_ALL);
ini_set("display_errors", 1);

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
$res_cmd = getNouvellesCommandes($co_pmp);
$num_cmd = mysqli_num_rows($res_cmd);
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

	<form method="post">
		<label class="label-title" style="margin: 0;">Liste des commandes</label>
		<div class="ligne"></div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-inline" style="margin-top:0.5%;">
					<label for="vol_ord" class="col-sm-3 col-form-label" style="padding-left:0;max-width: 20%;">Commandes avec 1 seul groupement</label>
					<div class="col-sm-1" style="padding:0;">
						<input type="submit" name="affecter_auto" value="AFFECTER" class="btn btn-primary" style="width:100%;">
					</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-sm-12">

				<div class="tableau" style="height: 600px;">
						<table class="table" id="trie_table_affecter">
							<thead>
								<th><i class="fal fa-sort"></i></th>
								<th style="padding: 8px 10px;width: 65px;">Nb&nbsp;Litre</th>
								<th style="padding: 8px 10px;width: 40px;" class="text-center">Type</th>
								<th style="padding: 8px 10px;width: 65px;">Date</th>
								<th style="padding: 8px 10px;width: 60px;" class="text-center">CP</th>
								<th style="padding: 8px 10px;width: 250px;">Ville</th>
								<th style="padding: 8px 10px;width: 130px;">Nom</th>
								<th style="padding: 8px 10px;width: 130px;">Prénom</th>
								<th style="padding: 8px 10px;width: 130px;">Etat</th>
								<th></th>
								<th style="padding: 8px 10px;width: 70px;border-left: 1px solid #0b242436;" class="text-center">GRP</th>
								<th></th>
							</thead>
							<tbody>
<?php
							$i = 0;
							while($commande = mysqli_fetch_array($res_cmd))
							{
								if($commande["cmd_status"] == 10) { $status = " 10 - Utilisateur"; }
								if($commande["cmd_status"] == 12) { $status = " 12 - Attaché"; }

								if ($commande["cmd_typefuel"] == 1){ $type = 'O';}
								if ($commande["cmd_typefuel"] == 2){ $type = 'S';}
?>
								<tr>
									<td></td>
									<td><?= $commande["cmd_qte"]; ?></td>
									<td class="text-center"><?= $type; ?></td>
									<td><?= date_format(new DateTime($commande['cmd_dt']), 'd/m/Y' ); ?></td>
									<td class="text-center"><?= $commande["code_postal"]; ?></td>
									<td><?= $commande["ville"]; ?></td>
									<td><?= $commande["name"]; ?></td>
									<td><?= $commande["prenom"]; ?></td>
									<td><?= $status; ?></td>
									<td><?= $commande['id']; ?></td>
									<td style="color: #0b242494;border-left: 1px solid #0b242436;"><?= $commande["id_grp"]; ?> - <?= $commande["libelle"]; ?></td>
									<td>
										<form class="" method="post" style="margin: 0;">
											<input type="hidden" name="id_grp" value="<?= $commande["id_grp"]; ?>">
											<input type="hidden" name="cmd_id" value="<?= $commande['id']; ?>">
											<input type="submit" name="affecter_cmde" value="AFFECTER" class="btn btn-outline-primary">
										</form>
									</td>
								</tr>
<?php
							}
?>

							</tbody>
						</table>
				</div>
				<div class="text-center">
					<p style="font-size: 14px;color: #0b2424ab;"><?= $num_cmd; ?> lignes à traiter </p>
				</div>
			</div>
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
