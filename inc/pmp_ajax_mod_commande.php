<?php
include_once  __DIR__ . "/../inc/pmp_co_connect.php";
include_once  __DIR__ . "/../inc/pmp_inc_fonctions_commande.php";

if(isset($_POST["quantite"]))
{
	$res = modifierQualite($co_pmp, $_POST["quantite"]);
	return $res;
}
?>
