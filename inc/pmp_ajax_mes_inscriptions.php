<?php
    /* On recoit en POST
    Produit             :   Fioul, Electricite, Gaz
    Inscrit 	       	:   0 ou 1
    */

    // TODO : obligé remettre ici alors que c'est dans le fichier commun ?
	include_once __DIR__ . "/../inc/pmp_co_connect.php";
	include_once __DIR__ . "/../inc/pmp_inc_fonctions_compte.php";

	// EnvoyerMail("Trace", "bla"); // Impossible car pas la couche joomla en appel ajax

	if($_POST['produit'] == "1")
	{
		GestionCompteFioul($co_pmp, $_POST['inscrit'], $_POST['user_id']);
	}
	elseif ($_POST['produit'] == "2")
	{
		GestionCompteElec($co_pmp, $_POST['inscrit'], $_POST['user_id']);
	}
	elseif ($_POST['produit'] == "3")
	{
		GestionCompteGaz($co_pmp, $_POST['inscrit'], $_POST['user_id']);
	}
	else
	{
		GestionCompte($co_pmp, $_POST['inscrit'], $_POST['user_id']);
	}

    // On retourn OK (ou KO TODO)
	// $myArr = array("OK", $_POST['user_id']);
    // $myJSON = json_encode($myArr);
    // echo $myJSON;
?>
