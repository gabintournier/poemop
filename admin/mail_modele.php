<style media="screen">
.ligne-menu {width: 330px!important;}
.btn-outline-primary { background: #f7f7f7!important;padding: 5px 20px!important;border-radius: 6px!important;font-size: 14px!important;}
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

$title = 'Liste des Mails Modèle';
$title_page = 'Liste des Mails Modèle';
ob_start();




include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_mail_sms.php";
unset($_SESSION['facture_saisie']);
$mails = getMailModele($co_pmp);

if (isset($message))
{
?>
<div class="toast <?= $message_type; ?>">
	<div class="message-icon  <?= $message_type; ?>-icon">
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
		<a href="mail_modele.php"  class="active">Mail Modèle</a>
		<a href="envoyer_sms.php">Envoyer SMS</a>
		<a href="param_sms.php">Param SMS</a>
		<a href="alerte_pf.php">Alerte PF</a>
	</div>


	<form method="post">
		<div class="row">
			<div class="col-sm-6" style="border-right: 1px solid #0b242436;">
				<label class="label-title" style="margin: 0;">Modèle</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-6" >
						<label for="sujet" class="col-form-label" style="padding-left:0;">Sujet du mail</label>
						<input type="text" name="sujet" id="sujet_mail" class="form-control" style="width:100%;" value="<?php if(isset($_POST["sujet"])) { echo $_POST["sujet"]; } ?>">
					</div>
					<div class="col-sm-6">
						<label for="fichier" class="col-form-label" style="padding-left:0;">Nom fichier</label>
						<input type="text" name="fichier" id="nom_fichier" class="form-control" style="width:100%;" value="<?php if(isset($_POST["fichier"])) { echo $_POST["fichier"]; } ?>">
					</div>
				</div>
				<label for="descriptif" class="col-form-label" style="padding-left:0;">Descriptif</label>
				<textarea name="descriptif" class="form-control" id="descriptif" rows="8" style="height:auto;"> <?php if(isset($_POST["descriptif"])) { echo $_POST["descriptif"]; } ?></textarea>
				<div class="text-right">
					<input type="hidden" name="id_mail" id="id_mail" value="<?php if(isset($_POST["id_mail"])) { echo $_POST["id_mail"]; } ?>">
					<input type="submit" name="add_mail" value="VALIDER" class="btn btn-primary" style="margin-top:15px;">
					<input type="submit" name="up_mail" value="MODIFIER" class="btn btn-warning" style="margin-top:15px;">
				</div>
			</div>
			<div class="col-sm-6">
				<label class="label-title" style="margin: 0;">Mots-clés</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-4">
						<label for="mots_cle" class="col-form-label" style="padding-left:0;">Mots-clés du mail</label>
						<input type="text" name="mots_cle" class="form-control" style="width:100%;">
					</div>
					<div class="col-sm-6">
						<label for="descriptif_mc" class="col-form-label" style="padding-left:0;">Descriptif</label>
						<input type="text" name="descriptif_mc" class="form-control" style="width:100%;">
					</div>
					<div class="col-sm-2 align-self-end">
						<input type="submit" name="ajouter_mots_cles" value="+" class="btn btn-go" style="padding: 0.175rem 0.75rem;background: white;">
					</div>
				</div>
				<div class="tableau" style="height: 200px;margin-bottom: 0;">
					<table class="table">
						<thead>
							<tr>
								<th>Mots-Clés</th>
								<th>Descriptif</th>
							</tr>
						</thead>
						<tbody>
<?php
						if(!empty($_POST["afficher_mots_cles"]) || !empty($_POST["ajouter_mots_cles"]))
						{
							$id = $_POST["id_mail"];
							$mot_cle = getMotsCles($co_pmp, $id);
							while ($mc = mysqli_fetch_array($mot_cle))
							{
?>
							<tr>
								<td><?= $mc["cle"]; ?></td>
								<td><?= $mc["description"]; ?></td>
							</tr>
<?php
							}
						}
?>

						</tbody>
					</table>
				</div>
				<div class="text-right">
					<input type="hidden" name="nb_mots_cles" id="nb_mots_cles" value="">
					<input type="submit" name="afficher_mots_cles" value="AFFICHER MOTS CLES" class="btn btn-outline-primary" style="margin-top: 15px;">
				</div>
			</div>
		</div>

		<hr>
		<div class="tableau">
			<table class="table" style="white-space: nowrap;">
				<thead>
					<tr>
						<th>N°</th>
						<th>Sujet</th>
						<th class="text-center">Destinataire</th>
						<th>Fichier</th>
						<th class="text-center">Nb Mot-Clé</th>
						<th>Déscriptif</th>
					</tr>
				</thead>
				<tbody>
<?php
				while($mail = mysqli_fetch_array($mails))
				{
					$mots_cle = getNbMotsCles($co_pmp, $mail["id"]);
?>
					<tr class="select">
						<td><?= $mail["id"]; ?></td>
						<td><?= $mail["sujet"]; ?></td>
						<td class="text-center">Client</td>
						<td><?= $mail["nom_fichier"]; ?></td>
						<td class="text-center"><?= $mots_cle["mots_cle"]; ?></td>
						<td><?= $mail["description"]; ?></td>
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
<script src="/admin/js/script_mail.js" charset="utf-8"></script>
