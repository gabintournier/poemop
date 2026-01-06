<?php
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

$title = 'Admin POEMOP';
$title_page = 'Admin POEMOP';
ob_start();
unset($_SESSION['facture_saisie']);
$content = ob_get_clean();
require('template.php');
?>
