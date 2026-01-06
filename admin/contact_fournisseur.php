<style media="screen">
.ligne-menu {width: 26%!important;}
.menu > h1, .ligne-menu {margin-left:6%;}
</style>
<?php
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

$title = 'Contact fournisseur';
$title_page = 'Contact fournisseur';
$return = true;
$link = '/admin/liste_fournisseurs.php';
ob_start();




include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
unset($_SESSION['facture_saisie']);
$res = getContactsFournisseurs($co_pmp, $_GET["id_four"]);


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
		<a href="details_fournisseur.php?id_four=<?= $_GET["id_four"]; ?>">Fournisseur</a>
		<a href="#" class="active">Contact</a>
		<a href="zones_fournisseur.php?id_four=<?= $_GET["id_four"]; ?>">Zone</a>
	</div>
	<form method="post">
		<label class="label-title add" style="margin: 0;">Ajouter contact</label>
		<label class="label-title upd" style="margin: 0; display:none">Modifier contact</label>
		<div class="ligne"></div>
		<div class="row" style="margin-top: 1%;">
			<div class="col-sm-2" style="max-width: 16%;">
				<input type="text" class="form-control" name="nom_contact" id="nom_contact" value="" placeholder="Nom *">
			</div>
			<div class="col-sm-2" style="max-width: 16%;">
				<input type="text" class="form-control" name="prenom_contact" id="prenom_contact" value="" placeholder="Prenom *">
			</div>
			<div class="col-sm-2" style="max-width: 16%;">
				<input type="tel" class="form-control" name="tel_contact" id="tel_contact" value="" placeholder="Tel *">
			</div>
			<div class="col-sm-2" style="max-width: 16%;">
				<input type="text" class="form-control" name="mail_contact" id="mail_contact" value="" placeholder="Mail *">
			</div>
			<div class="col-sm-2" style="max-width: 16%;">
				<input type="text" class="form-control" name="fonction_contact" id="fonction_contact" value="" placeholder="Fonction">
			</div>
			<div class="col-sm-2" style="max-width: 16%;">
				<input type="text" class="form-control" name="com_contact" id="com_contact" value="" placeholder="Commentaire">
			</div>
			<div class="col-sm-1 align-self-center" style="max-width: 2.8%;">
				<span class="vider vider-form"><i class="fa-solid fa-xmark"></i></span>
			</div>
			<div class="col-sm-12 text-right">
				<input type="hidden" name="id_contact" id="id_contact" value="">
				<input type="submit" name="ajouter_contact" class="btn btn-primary" value="AJOUTER" style="margin-top:1%;">
				<input type="submit" name="modifier_contact" class="btn btn-warning" value="MODIFIER" style="margin-top:1%;display:none">
			</div>
		</div>
	</form>
	<hr>
	<div class="tableau" style="height:450px">
		<table class="table" id="trie_table">
			<thead>
				<tr>
					<th>Nom</th>
					<th>Prenom</th>
					<th>Tel</th>
					<th>Mail</th>
					<th>Fonction</th>
					<th>Commentaire</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
<?php
				while ($contact = mysqli_fetch_array($res))
				{
?>
				<tr class="select contact">
					<input type="hidden" name="contact_id" value="<?= $contact["id"]; ?>">
					<td><?= $contact['nom']; ?></td>
					<td><?= $contact['prenom']; ?></td>
					<td><?= $contact['tel']; ?></td>
					<td><?= $contact['mail']; ?></td>
					<td><?= $contact['fonction']; ?></td>
					<td><?= $contact['commentaire']; ?></td>
					<td>
						<span class="delete" data-bs-toggle="modal" data-bs-target="#supprimerContact_<?= $contact["id"]; ?>"><i class="fa-regular fa-trash-can" style="padding:20%;"></i></span>
						<form method="post">
							<div class="modal fade" id="supprimerContact_<?= $contact["id"]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							 	<div class="modal-dialog modal-dialog-centered">
							    	<div class="modal-content">
							      		<div class="modal-header">
							        		<h5 class="modal-title" id="exampleModalLabel">Supprimer ce contact ?</h5>
							        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i> </button>
							      		</div>
							      		<div class="modal-body">
											<?php print $contact['nom'] . " - " . $contact['prenom'] . " - " . $contact['fonction']; ?>
							      		</div>
							      		<div class="modal-footer">
											<input type="hidden" name="supp_contact_id" value="<?php print $contact['id']; ?>">
							        		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
											<input type="submit" name="supp_contact" class="btn btn-primary" value="Supprimer">
							      		</div>
							    	</div>
							 	</div>
							</div>
						</form>
					</td>
				</tr>
<?php
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
<script src="/admin/js/script_fournisseurs.js" charset="utf-8"></script>
