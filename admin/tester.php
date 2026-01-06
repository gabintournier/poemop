<?php
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

error_reporting(E_ALL);
ini_set("display_errors", 1);

$title = 'Ajouter commandes au groupement';
$title_page = 'Ajouter commandes au groupement';
$return = true;



include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_commandes.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_groupements.php";

$grp = getGroupementDetails($co_pmp, $_GET["id_grp"]);
ob_start();
?>
<div class="bloc">
	<form  method="post">
		<label class="label-title" style="margin: 0;">Sélectionner une commande</label>
		<div class="ligne"></div>
		<label class="col-form-label" style="padding-left:0;">Pour le groupement <span style="color: #ef8351;"><?php if(isset($_GET["id_grp"])) { echo $grp["libelle"]; } ?></span> </label>
		<hr>
		<select class="js-example-basic-single form-control" name="fournisseur_ajax" style="width:100%;">
		<?php
		$res_four = getFournisseursListetest($co_pmp);
		while ($fournisseur = mysqli_fetch_array($res_four))
		{
?>
		<option value=""><?php echo $fournisseur["nom"]; ?></option>
<?php
		}
		?>
		</select>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
