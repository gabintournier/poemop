<?php
    /* On recoit en POST
    Produit             :   Fioul, Electricite, Gaz
    Inscrit 	       	:   0 ou 1
    */

    // TODO : obligé remettre ici alors que c'est dans le fichier commun ?
	include_once __DIR__ . "/../inc/pmp_co_connect.php";
	include_once __DIR__ . "/../inc/pmp_inc_fonctions_compte.php";

	// EnvoyerMail("Trace", "bla"); // Impossible car pas la couche joomla en appel ajax

	$res_ville = getVilleCp($co_pmp, $_POST["code_postal"]);
	while($ville = mysqli_fetch_array($res_ville))
	{

?>
		<option value="<?php echo $ville["id"]; ?>"><?php echo $ville["ville"]; ?></option>
<?php
	}

    // On retourn OK (ou KO TODO)
	// $myArr = array("OK", $_POST['user_id']);
    // $myJSON = json_encode($myArr);
    // echo $myJSON;
?>
