<style media="screen">
.ligne-menu {width: 41%!important;}
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

$title = 'Volume par zone fournisseur';
$title_page = 'Volume par zone fournisseur';
ob_start();




include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_commandes.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
unset($_SESSION['facture_saisie']);
$res_four = getFournisseursListe($co_pmp);

if(!empty($_POST["charger_vol"]))
{
	$id = $_POST["fournisseur"];
	$res = getVolumeFournisseursZone($co_pmp, $id);
	$style = "width: 5%;";
}
?>
<div class="bloc">
	<div class="menu-bloc">
		<a href="liste_commandes.php">Liste</a>
		<a href="commande_par_departement.php">Calcul par département</a>
		<a href="#" class="active">Calcul par fournisseur</a>
		<a href="statistiques_commande.php">Statistiques</a>
	</div>
	<label class="label-title" style="margin: 0;">Volume par zone de fournisseur</label>
	<div class="ligne"></div>
	<form method="post">
		<div class="row" style="margin-top: 0.5%;">
			<div class="col-sm-3">
				<div class="form-inline">
					<label for="fournisseur" class="col-sm-4 col-form-label" style="padding-left:0;">Fournisseur</label>
					<div class="col-sm-8" style="padding:0">
						<select class="form-control"  name="fournisseur" style="width:100%;">
<?php
							while ($fournisseurs = mysqli_fetch_array($res_four))
							{
?>
								<option value="<?= $fournisseurs["id"]; ?>" <?php if(isset($_POST["fournisseur"])) { if($_POST["fournisseur"] == $fournisseurs["id"]) { echo "selected='selected'"; } } ?>><?= $fournisseurs["nom"]; ?></option>
<?php
							}
?>
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-4" >
				<label for="four_partenaires" class="col-form-label">
					<input type="checkbox" name="four_partenaires" id="four_partenaires" class="switch value check" checked="checked">
					Charger que les zones fournisseurs partenaires
				</label>
			</div>
			<div class="col-sm-4 align-self-center">
				<input type="submit" name="charger_vol" value="CHARGER" class="btn btn-primary">
			</div>
		</div>
	</form>
	<hr>
	<div class="tableau tableau-stats" style="height:480px">
		<table class="table">
			<thead>
				<tr>
					<th style="<?php if(isset($style)) { echo $style; } ?>">fournisseur</th>
					<th>Zone</th>
					<th>Commandes utilisateur</th>
				</tr>
			</thead>
			<tbody>
<?php
			if(isset($res))
			{
				while($vol = mysqli_fetch_array($res))
				{
				$volume = getVolume($co_pmp, $vol["id"]);
?>
				<tr>
					<td><?= $vol["nom"]; ?></td>
					<td><?= $vol["libelle"]; ?></td>
					<td><?php if(isset($volume["cmd_qte"]) != NULL) { echo $volume["cmd_qte"]; } else { echo "0"; } ?></td>
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
<script src="js/select2.min.js"></script>
<script src="js/script_commandes.js" charset="utf-8"></script>
