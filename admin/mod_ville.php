<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);




include_once __DIR__ . "/../inc/pmp_co_connect.php";

$query = "SELECT * FROM pmp_utilisateur WHERE ville = '' AND code_postal_id != 0";
$res = my_query($co_pmp, $query);


foreach ($res as $res)
{
	$cp_id = $res["code_postal_id"];
	$user_id = $res["user_id"];
	$query = "SELECT ville FROM pmp_code_postal WHERE id='$cp_id'";
	$res = my_query($co_pmp, $query);
	$pmp_code_postal = mysqli_fetch_array($res);

	$ville = $pmp_code_postal["ville"];

	$query = "  UPDATE pmp_utilisateur
				SET
				ville='$ville',
				WHERE user_id = '$user_id' ";
	$res = my_query($co_pmp, $query);
}


?>
