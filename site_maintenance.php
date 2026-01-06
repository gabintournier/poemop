<style media="screen">
	.footer{display:none;}
</style>
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

$desc = 'Commandes groupées de fioul domestique partout en France. Poemop groupe gratuitement votre commande de fioul avec celles de vos voisins.';
$title = 'Commandes groupées de fioul domestique avec POEMOP';
ob_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";

?>


<?php
function resol()
{
$resol='<script type="text/javascript">
                document.write(""+screen.width+"");
</script>';
return $resol;
}
$var_resol=resol();
?>
<div class="container-fluid">
	<div class="header">
		<div class="erreur-404 text-center">
			<h1>Site en maintenance</h1>
			<div class="ligne-center jaune"></div>
			<!-- <img src="/images/header-achat-groupes-poemop.svg" alt="Découvrez nos achats groupés" style="margin-bottom: 2%;width: 42%;"> -->
			<h2>Notre site est actuellement en maintenance. <br> Pour passer commande, merci de nous envoyer un mail à :</h2>
			<h2 style="color: #ef8351;">info@poemop.fr</h2>
			<img src="images/header-achat-groupes-poemop.svg" alt="Découvrez nos achats groupés" style="margin-bottom: 2%;width: 42%;">
		</div>
	</div>
</div>


<?php
$content = ob_get_clean();
require('template.php');
?>
