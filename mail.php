<?php
include_once 'inc/dev_auth.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);

include_once __DIR__ . "/inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions.php";

if(!empty($_POST["mail"]))
{
	EnvoyerMailLouis($co_pmp);
}
?>
<form method="post">
	<input type="submit" name="mail" value="envoyer">
</form>
