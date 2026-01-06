<?php

// Ne pas utiliser $_SERVER["DOCUMENT_ROOT"] car ce fichier est aussi appelé par le cron
include_once  "pf_co_configuration.php";
// include_once  "/var/www/vhosts/prixfioul.fr/httpdocs/inc/pf_inc_fonctions_mail.php"; // Ne pas mettre ici, ca fait planter le cron qui envoi des mails

$co_pf = mysqli_connect($host_pf,$user_pf,$password_pf,$database_pf);

/* Vérification de la connexion */
if (mysqli_connect_errno()) {
	printf("Échec de la connexion : %s\n", mysqli_connect_error());
	exit();
}

//	mysqli_select_db($co_pf, $database); // Inutile car on selectionne deja une base a la connexion

/* Modification du jeu de résultats en utf8 */
if (!mysqli_set_charset($co_pf, "utf8")) {
	printf("Erreur lors du chargement du jeu de caractères utf8 : %s\n", mysqli_error($co_pf));
	exit();
}

// function my_query(&$co_pf, $query)
// {
// 	$res = mysqli_query($co_pf, $query);
// 	if(!$res)
// 	{
// 		$message  = 'Requête invalide : ' . mysqli_error($co_pf) . "\n";
// 		$message .= 'Requête complète : ' . $query;
// 		EnvoyerMailErreur("Erreur my_query()", "Fichier:/inc/pf_co_connect.php\n" . $message);
// 	}
// 	return $res;
// }
?>
