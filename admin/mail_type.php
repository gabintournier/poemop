<style media="screen">
.ligne-menu {width: 28%!important;}
</style>
<?php
session_start();
if(!isset($_SESSION['user']))
{
    header('Location:/admin/connexion.php');
	die();
}

$title = 'Liste des Mails Type';
$title_page = 'Liste des Mails Type';
ob_start();




include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_mail.php";
unset($_SESSION['facture_saisie']);
$res_mail = getMailType($co_pmp);
?>
<div class="bloc">
	<div class="menu-bloc">
		<a href="#" class="active">Mails Type</a>
		<a href="mail_modele.php">Mail Modèle</a>
		<a href="envoyer_mail.php">Envoyer Mail</a>
		<a href="envoyer_sms.php">Envoyer SMS</a>
		<a href="param_sms.php">Param SMS</a>
		<a href="gestion_client.php">Alerte PF</a>
	</div>
	<form method="post">
		<div class="row">
			<div class="col-sm-3">
				<div class="form-inline">
					<label for="nom" class="col-sm-2 col-form-label" style="padding-left:0;">Nom</label>
					<div class="col-sm-10" style="padding:0">
						<input type="text" name="nom" class="form-control" style="width:100%;">
					</div>
				</div>
			</div>
			<div class="col-sm-7">
				<div class="form-inline">
					<label for="nom" class="col-sm-1 col-form-label" style="padding-left:0;">Objet</label>
					<div class="col-sm-11" style="padding:0">
						<input type="text" name="nom" class="form-control" style="width:100%;">
					</div>
				</div>
			</div>
			<div class="col-sm-1 text-right">
				<input type="submit" name="add_mail" value="-" class="btn btn-go" style="padding: 0.175rem 0.75rem;background: white;width:38%;height:90%;">
			</div>
			<div class="col-sm-1">
				<input type="submit" name="supp_mail" value="+" class="btn btn-go" style="padding: 0.175rem 0.75rem;background: white;width:38%;height:90%;">
			</div>
			<div class="col-sm-12">
				<label for="mail" class="col-sm-1 col-form-label" style="padding-left:0;">Mail</label>
				<textarea name="mail" class="form-control" rows="3"style="height:auto;"></textarea>
			</div>
			<div class="col-sm-12" style="margin-top: 0.8%;">
				<div class="form-inline">
					<label for="automatisation" class="col-sm-3 col-form-label" style="padding-left:0;">Fonctionnalité correspondante pour automatisation</label>
					<div class="col-sm-3" style="padding:0">
						<select class="form-control" name="automatisation" style="width:100%">
							<option value=""></option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="tableau">
			<table class="table">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Code</th>
						<th>Objet</th>
						<th>Mail</th>
					</tr>
				</thead>
				<tbody>
<?php
				while($mails = mysqli_fetch_array($res_mail))
				{
?>
					<tr class="select">
						<td><?= $mails["nom"]; ?></td>
						<td><?= $mails["codemail"]; ?></td>
						<td><?= $mails["objet"]; ?></td>
						<td><?= $mails["mail"]; ?></td>
					</tr>
<?php
				}
?>
				</tbody>
			</table>
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
