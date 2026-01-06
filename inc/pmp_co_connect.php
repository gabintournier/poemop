<?php
/*
function AjouterChamp(&$co_af, $champ, $escape, $premier)
function my_query(&$co_af, $query)
*/

include_once __DIR__ . "/pmp_co_configuration.php";
include_once __DIR__ . "/pmp_inc_fonctions_mail.php";

$co_pmp = mysqli_connect($host_pmp, $user_pmp, $password_pmp, $database_pmp);

/* Vérification de la connexion */
if (mysqli_connect_errno()) {
    printf("Échec de la connexion : %s\n", mysqli_connect_error());
    exit();
}

/* Modification du jeu de résultats en utf8 */
if (!mysqli_set_charset($co_pmp, "utf8")) {
    printf("Erreur lors du chargement du jeu de caractères utf8 : %s\n", mysqli_error($co_pmp));
    exit();
}

function my_query(&$co_pmp, $query)
{
    $res = mysqli_query($co_pmp, $query);
    if (!$res) {
        $message  = 'Requête invalide : ' . mysqli_error($co_pmp) . "\n";
        $message .= 'Requête complète : ' . $query;
        EnvoyerMailErreur("Erreur sur le site POEMOP", $message);
    }
    return $res;
}

function my_query_admin(&$co_pmp, $query)
{
    $res = mysqli_query($co_pmp, $query);
    if ($res) {
        $message = 'Requête complète : ' . $query;
        EnvoyerMailUpdate("Update depuis l'admin POEMOP", $message);
    }
    return $res;
}
?>
