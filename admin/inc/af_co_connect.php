<?php
/*
function AjouterChamp(&$co_af, $champ, $escape, $premier)
function my_query(&$co_af, $query)
*/
include_once "inc/af_co_configuration.php";

$co_af = mysqli_connect($host_af,$user_af,$password_af,$database_af);

/* Vérification de la connexion */
if (mysqli_connect_errno()) {
	printf("Échec de la connexion : %s\n", mysqli_connect_error());
	exit();
}

//	mysqli_select_db($co_af, $database); // Inutile car on selectionne deja une base a la connexion

/* Modification du jeu de résultats en utf8 */
if (!mysqli_set_charset($co_af, "utf8")) {
	printf("Erreur lors du chargement du jeu de caractères utf8 : %s\n", mysqli_error($co_af));
	exit();
}

?>
