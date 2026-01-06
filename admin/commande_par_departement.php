<style media="screen">
.ligne-menu {width: 43%!important;}
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

$title = 'Commande par département';
$title_page = 'Commande par département';
ob_start();




include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_commandes.php";
unset($_SESSION['facture_saisie']);
if(!empty($_POST["charger_nb_client"]))
{
	$res_clients_25 = getNbClientDep25($co_pmp);
	$res_clients_50 = getNbClientDep50($co_pmp);
	$res_clients_75 = getNbClientDep75($co_pmp);
	$res_clients_99 = getNbClientDep99($co_pmp);
	$total25 = getTotalClientDep25($co_pmp);
	$total50 = getTotalClientDep50($co_pmp);
	$total75 = getTotalClientDep75($co_pmp);
	$total90 = getTotalClientDep90($co_pmp);
	$res_region = getNbClientRegion($co_pmp);
	$res_total = getNbClientTotal($co_pmp);
}
elseif(!empty($_POST["charger_qte_fioul"]))
{
	$res_clients_25 = getNbFioulDep25($co_pmp);
	$res_clients_50 = getNbFioulDep50($co_pmp);
	$res_clients_75 = getNbFioulDep75($co_pmp);
	$res_clients_99 = getNbFioulDep99($co_pmp);
	$total25 = getTotalFioulDep25($co_pmp);
	$total50 = getTotalFioulDep50($co_pmp);
	$total75 = getTotalFioulDep75($co_pmp);
	$total90 = getTotalFioulDep90($co_pmp);
	$res_region = getNbFioulRegion($co_pmp);
	$res_total = getNbFioulTotal($co_pmp);
}
?>
<div class="bloc">
	<div class="menu-bloc">
		<a href="liste_commandes.php">Liste</a>
		<a href="#" class="active">Calcul par département</a>
		<a href="commande_par_fournisseur.php">Calcul par fournisseur</a>
		<a href="statistiques_commande.php">Statistiques</a>
	</div>
	<form  method="post">
		<label class="label-title" style="margin: 0;">Commande par département</label>
		<div class="ligne"></div>
		<div class="row" style="margin-top: 1%;">
			<div class="col-sm-2">
				<input type="submit" name="charger_qte_fioul" value="Calcul Quantité Fioul" class="btn btn-primary" style="width:90%;">
			</div>
			<div class="col-sm-2">
				<input type="submit" name="charger_nb_client" value="Calcul Nombre Client" class="btn btn-warning" style="width:90%;">
			</div>
			<div class="col-sm-8 align-self-center">
				<div class="form-inline">
					<label for="region" class="col-sm-4 col-form-label" style="padding-left:0;">Region Parisienne (75, 77, 78, 91, 92, 93, 94, 95)</label>
					<div class="col-sm-3" style="padding:0">
						<input type="text" name="region" value="<?php if(!empty($_POST["charger_nb_client"])  || !empty($_POST["charger_qte_fioul"])) { echo $res_region["nb"]; } ?>" class="form-control" style="width:50%;">
					</div>
				</div>
			</div>
		</div>
		<hr>
		<label class="label-title" style="margin: 0;">Légende</label>
		<div class="ligne"></div>
		<div class="row" style="margin-top: 0.5%;">
			<div class="col-sm-4">
				<p style="margin-bottom: 0;font-size: 13px;font-family: 'Goldplay Alt Medium';">Quantité Fioul : Colonne Total Rouge si > à la capacité d'un camion (13 500 L)<br>Colonne Dep. bleue si une cde dans le dep est >= 3 000 L</p>
			</div>
			<div class="col-sm-4">
				<div class="col-sm-8 align-self-center">
					<div class="form-inline">
						<label for="total_global" class="col-sm-4 col-form-label" style="padding-left:0;">Total global</label>
						<div class="col-sm-3" style="padding:0">
							<input type="text" name="total_global" value="<?php if(!empty($_POST["charger_nb_client"])  || !empty($_POST["charger_qte_fioul"])) { echo $res_total["nb"]; } ?>" class="form-control" style="width:100%;">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-3">
				<div class="tableau" style="height:700px">
					<table class="table">
						<thead>
							<tr>
								<th style="width:5%;">Dep</th>
								<th class="text-center">Total</th>
							</tr>
						</thead>
						<tbody>
<?php
						if(!empty($_POST["charger_nb_client"]) || !empty($_POST["charger_qte_fioul"]))
						{
							while ($res = mysqli_fetch_array($res_clients_25))
							{
?>
							<tr>
								<td style="padding: 1% 0 1% 0.75rem;" class="<?php if(isset($res["cmd_qte"])) { if($res["cmd_qte"] >= 3000) { echo "bleu"; } }  ?>"><?= $res["dep"]; ?></td>
								<td style="padding: 1% 0.75rem 1% 0.75rem;" class="text-center <?php if($res["nb"] > 13500) { echo "rouge"; } ?>"><?= $res["nb"]; ?></td>
							</tr>
<?php
							}
						}
?>
						</tbody>
					</table>
				</div>
				<input type="text" name="total_col_1" value="<?php if(isset($total25["total"])) { echo $total25["total"]; } ?>" class="form-control" style="width:30%;display: block;margin: 0 auto;">
			</div>
			<div class="col-sm-3">
				<div class="tableau" style="height:700px">
					<table class="table">
						<thead>
							<tr>
								<th style="width:5%;">Dep</th>
								<th class="text-center">Total</th>
							</tr>
						</thead>
						<tbody>
<?php
						if(!empty($_POST["charger_nb_client"]) || !empty($_POST["charger_qte_fioul"]))
						{
							while ($res = mysqli_fetch_array($res_clients_50))
							{
?>
							<tr>
								<td style="padding: 1% 0 1% 0.75rem;" class="<?php if(isset($res["cmd_qte"])) { if($res["cmd_qte"] >= 3000) { echo "bleu"; } }  ?>"><?= $res["dep"]; ?></td>
								<td style="padding: 1% 0.75rem 1% 0.75rem;" class="text-center <?php if($res["nb"] > 13500) { echo "rouge"; } ?>"><?= $res["nb"]; ?></td>
							</tr>
<?php
							}
						}
?>
						</tbody>
					</table>
				</div>
				<input type="text" name="total_col_1" value="<?php if(isset($total50["total"])) { echo $total50["total"]; } ?>" class="form-control" style="width:30%;display: block;margin: 0 auto;">
			</div>
			<div class="col-sm-3">
				<div class="tableau" style="height:700px">
					<table class="table">
						<thead>
							<tr>
								<th style="width:5%;">Dep</th>
								<th class="text-center">Total</th>
							</tr>
						</thead>
						<tbody>
<?php
						if(!empty($_POST["charger_nb_client"]) || !empty($_POST["charger_qte_fioul"]))
						{
							while ($res = mysqli_fetch_array($res_clients_75))
							{
								if(!empty($_POST["charger_qte_fioul"])) { if($res["nb"] > 1000) { $rouge = "rouge"; } }
?>
							<tr>
								<td style="padding: 1% 0 1% 0.75rem;" class="<?php if(isset($res["cmd_qte"])) { if($res["cmd_qte"] >= 3000) { echo "bleu"; }  } ?>"><?= $res["dep"]; ?></td>
								<td style="padding: 1% 0.75rem 1% 0.75rem;" class="text-center <?php if($res["nb"] > 13500) { echo "rouge"; } ?>"><?= $res["nb"]; ?></td>
							</tr>
<?php
							}
						}
?>
						</tbody>
					</table>
				</div>
				<input type="text" name="total_col_1" value="<?php if(isset($total75["total"])) { echo $total75["total"]; } ?>" class="form-control" style="width:30%;display: block;margin: 0 auto;">
			</div>
			<div class="col-sm-3">
				<div class="tableau" style="height:700px">
					<table class="table">
						<thead>
							<tr>
								<th style="width:5%;">Dep</th>
								<th class="text-center">Total</th>
							</tr>
						</thead>
						<tbody>
<?php
						if(!empty($_POST["charger_nb_client"]) || !empty($_POST["charger_qte_fioul"]))
						{
							while ($res = mysqli_fetch_array($res_clients_99))
							{
?>
							<tr>
								<td style="padding: 1% 0 1% 0.75rem;" class="<?php if(isset($res["cmd_qte"])) { if($res["cmd_qte"] >= 3000) { echo "bleu"; } }  ?>"><?= $res["dep"]; ?></td>
								<td style="padding: 1% 0.75rem 1% 0.75rem;" class="text-center <?php if($res["nb"] > 13500) { echo "rouge"; } ?>"><?= $res["nb"]; ?></td>
							</tr>
<?php
							}
						}
?>
						</tbody>
					</table>
				</div>
				<input type="text" name="total_col_1" value="<?php if(isset($total90["total"])) { echo $total90["total"]; } ?>" class="form-control" style="width:30%;display: block;margin: 0 auto;">
			</div>
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="/admin/js/select2.min.js"></script>
<script src="/admin/js/script_commandes.js" charset="utf-8"></script>
