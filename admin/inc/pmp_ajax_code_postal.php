<?php


include_once __DIR__ . "/../../inc/pmp_co_connect.php";
include_once "pmp_inc_fonctions_clients.php";

$res_ville = getVilleCP($co_pmp, $_POST["code_postal"]);
while($ville = mysqli_fetch_array($res_ville))
{
?>
	<option value="<?php echo $ville["id"]; ?>"><?php echo $ville["ville"]; ?></option>
<?php
}
?>
