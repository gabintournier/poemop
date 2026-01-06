<style media="screen">
.ligne-menu {width: 30%!important;}
.menu > h1, .ligne-menu {margin-left:6%;}
</style>
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

$title = 'Définition de la zone';
$title_page = 'Définition de la zone';
$return = true;

ob_start();




include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_zones.php";
unset($_SESSION['facture_saisie']);
$zone = getZone($co_pmp, $_GET['id_zone']);
$fournisseur = getFournisseurDetails($co_pmp,  $zone["fournisseur_id"]);

$zones_cp = getZoneCp($co_pmp, $_GET['id_zone']);
$departements = getDepartement($co_pmp);


$link = '/admin/zones_fournisseur.php?id_four=' . $zone["fournisseur_id"];


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
	<form method="post" enctype="multipart/form-data">
		<div class="row">
			<div class="col-sm-12">
				<label class="label-title" style="margin: 0;">Infos générales</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-3">
						<div class="form-inline">
							<label for="nom_four" class="col-sm-4 col-form-label" style="padding-left:0;">Fournisseur :</label>
							<div class="col-sm-8" style="padding:0">
								<label for="nom_four" class="col-form-label label-input"><?= $fournisseur["nom"]; ?></label>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-inline">
							<label for="cp_four" class="col-sm-2 col-form-label" style="padding-left:0;">Zone :</label>
							<div class="col-sm-10" style="padding:0">
								<label for="cp_four" class="col-form-label label-input"><?= $zone["libelle"]; ?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr>
		<label class="label-title" style="margin: 0;">Ajouter / Supprimer</label>
		<div class="ligne"></div>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-inline">
					<label for="dep_zone" class="col-sm-4 col-form-label" style="padding-left:0;">Le dep dans la zone :</label>
					<div class="col-sm-6" style="padding:0">
						<select class="form-control input-custom" name="dep_zone" style="width:100%;">
<?php
						while ($departement = mysqli_fetch_array($departements))
						{
?>
							<option value="<?= $departement['id']; ?>"><?= $departement['id']; ?> - <?= $departement['libelle']; ?></option>
<?php
						}
?>
						</select>
					</div>
					<div class="submit-dep col-sm-1">
						<div class="add">
							<input type="submit" class="btn-go" name="add_dep" value="+">
						</div>
					</div>
					<div class="submit-dep col-sm-1">
						<div class="supp">
							<input type="submit" class="btn-go" name="supp_dep" value="-">
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3" style="margin-left: 1%;border-left: 1px solid #0b242436;">
				<div class="form-inline">
					<label for="cp_zone" class="col-sm-6 col-form-label" style="padding-left:0;">Le cp dans la zone :</label>
					<div class="col-sm-3" style="padding:0">
						<input type="text" name="cp_zone" value="" class="form-control" style="width:100%;">
					</div>
					<div class="submit-dep col-sm-1" style="margin-right: 3%;">
						<div class="add">
							<input type="submit" class="btn-go" name="add_cp" value="+">
						</div>
					</div>
					<div class="submit-dep col-sm-1">
						<div class="supp">
							<input type="submit" class="btn-go" name="supp_cp" value="-">
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3" style="border-left: 1px solid #0b242436;">
				<div class="form-inline">
					<label for="dep_zone" class="col-sm-5 col-form-label" style="padding-left:0;">Activer tous les CP :</label>
					<div class="col-sm-2 select-tous_actif" style="padding:0">
						<input type="checkbox" name="tous_actif" value="" class="switch value">
					</div>
					<div class=" col-sm-4 " style="margin-right: 3%;">
						<input type="submit" name="rendre_tous_actif" value="VALIDER" class="btn btn-primary" style="width:100%;">
					</div>
				</div>
			</div>
		</div>
		<hr>
		<label class="label-title" style="margin: 0;">Importer / Exporter</label>
		<div class="ligne"></div>
		<div class="row">
			<div class="col-sm-6">
				<div class="row" style="margin-top:2%;">
					<div class="col-sm-4">
						<input class="form-control" name="import" type="file" value="" style="height: auto;">
					</div>
					<div class="col-sm-3">
						<input type="submit" name="importer_zone" value="IMPORTER" class="btn btn-secondary" style="width:100%;">
					</div>
					<div class="col-sm-3">
						<input type="submit" name="exporter_zone" value="EXPORTER" class="btn btn-secondary" style="width:100%;">
					</div>
				</div>
			</div>
			<div class="col-sm-6 text-right">
				<button type="button" data-bs-toggle="modal" data-bs-target="#vider" class="btn btn-warning">VIDER</button>
			</div>
			<div class="modal fade" id="vider" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document" style="width:480px;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Vider une zone</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fal fa-times"></i> </button>
						</div>
						<div class="modal-body">
							Êtes-vous sûr de vouloir vider cette zone ?
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Non</button>
							<input class="btn btn-primary" name="vider_zone" id="vider_zone" type="submit" value="Vider la zone" >
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tableau" style="height:505px">
			<table class="table" id="zone_table">
				<thead>
					<tr>
						<th>Code postal</th>
						<th>Commune</th>
						<th>Actif</th>
						<th style="width:10%"></th>
					</tr>
				</thead>
				<tbody>
<?php
				$i = 0;
				while($cp_zone = mysqli_fetch_array($zones_cp))
				{
?>
					<tr class="select">
						<input type="hidden" name="zone_cp_id_<?php print $i++; ?>" value="<?= $cp_zone['zone_cp_id']; ?>">
						<td><?= $cp_zone['code_postal']; ?></td>
						<td><?= $cp_zone['ville']; ?></td>
						<td>
							<input type="checkbox" name="zone_actif_<?= $cp_zone['zone_cp_id']; ?>" value="<?= $cp_zone['actif']; ?>" class="switch value s-tableau">
						</td>
						<td></td>
					</tr>
<?php
				}
?>
				</tbody>
			</table>
		</div>
		<input type="hidden" name="nb_zone" value="<?php print $i; ?>">
		<div class="text-right">
			<input type="submit" name="modifier_actif" value="VALIDER" class="btn btn-primary" style="padding:1%">
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="/admin/js/script_zones.js" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".s-tableau").click(function() {
		var id = $(this).val();
		console.log(id);
	});
});

</script>
