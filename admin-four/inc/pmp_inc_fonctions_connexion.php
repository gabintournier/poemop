<?php
function getFournisseur($co_pmp, $id_crypte)
{
	$query = "  SELECT *
				FROM pmp_fournisseur
				WHERE id_crypte = '$id_crypte' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}
if (!empty($_GET["id_crypte"]))
{
	$fournisseur = getFournisseur($co_pmp, $_GET["id_crypte"]);
	$_SESSION['four'] = $fournisseur["nom"];
	$_SESSION['id_crypte'] = $fournisseur["id_crypte"];
	$_SESSION["four_id"] = $fournisseur["id"];
}
if(!isset($_SESSION['id_crypte']))
{
    header('Location: /');
	die();
}
?>
