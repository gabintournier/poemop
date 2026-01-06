<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

<style media="screen">
.ligne-menu {width: 25% !important;}
</style>
<?php
session_start();
$title = 'Liste des zones';
$title_page = 'Liste des zones';
// $title_page = 'Tableau de bord';

//*** Securisation du formulaire
// On detecte la recharge par F5 (par exemple) dans une meme session
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

ob_start();

// INC global
include_once "../inc/pmp_co_connect.php";
// Prépare l'environnement fournisseur avant de charger les fonctions métier (chemin absolu pour cibler la version admin)
include_once __DIR__ . "/inc/pmp_inc_fonctions_connexion.php";
// INC interne ADM Fournisseur
include_once "inc/pmp_inc_fonctions_cotations.php";

if(isset($_GET["id_crypte"]))
{
	$res = getZones($co_pmp);
}

?>
<div class="bloc">
	<form method="post">
		<label class="label-title" style="margin: 0;">Liste des zones</label>
		<div class="ligne"></div>
		<!-- <div class="row">
			<div class="col-sm-12">
				<div class="form-inline" style="margin-top:0.5%;">
					<label for="com_ord" class="col-sm-2 col-form-label" style="padding-left:0;">Total des commandes :</label>
						<div class="col-sm-10" style="padding:0">
							<input type="text" name="com_ord" value="<?php if(isset($cmdes["nb"])) { echo $cmdes["nb"]; } else { echo "0"; } ?>" class="form-control" style="width:100%;background-color: #e0e1df00!important;" disabled="disabled">
						</div>
				</div>
			</div>
		</div> -->
		<div class="tableau" style="height: 680px;">
			<table class="table" id="trie_table_grp">
				<thead>
					<th style="border-bottom: 2px solid #ef83514a!important;" >Zone</th>
					<th style="border-bottom: 2px solid #ef83514a!important;" >Nb commandes</th>
				</thead>
				<tbody>
<?php
				while ($zones = mysqli_fetch_array($res))
				{
					$cmdes = getCommandesZoneCotations($co_pmp, $zones["id"]);
					$num_cmdes = mysqli_num_rows($cmdes);
?>
					<tr class="select zones">
						<input type="hidden" name="n_grp" value="<?= $zones["id"]; ?>">
						<td><?= $zones["libelle"]; ?></td>
						<td><?= $num_cmdes; ?></td>
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
<script src="js/script_groupements.js" charset="utf-8"></script>
