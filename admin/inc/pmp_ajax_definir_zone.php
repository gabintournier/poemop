<?php
    /* On recoit en POST
    Produit             :   Fioul, Electricite, Gaz
    Inscrit 	       	:   0 ou 1
    */
    // TODO : obligé remettre ici alors que c'est dans le fichier commun ?

	include_once __DIR__ . "/../../inc/pmp_co_connect.php";
	include_once "pmp_inc_fonctions_zones.php";
	// EnvoyerMail("Trace", "bla"); // Impossible car pas la couche joomla en appel ajax

	$res_zone = getListeZonesFournisseur($co_pmp, $_POST["four_id"]);
	while($zone = mysqli_fetch_array($res_zone))
	{

?>
		<option value="<?php echo $zone["id"]; ?>"><?php echo $zone["libelle"]; ?></option>
<?php
	}

    // On retourn OK (ou KO TODO)
	// $myArr = array("OK", $_POST['user_id']);
    // $myJSON = json_encode($myArr);
    // echo $myJSON;
?>
