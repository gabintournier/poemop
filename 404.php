<?php
$desc = 'Commandes groupées avec Poemop. Poemop groupe gratuitement vos achats avec ceux de vos voisins.';
$title = 'Page introuvable';
ob_start();
$FORCE_BASE_ROOT = true;
include 'modules/menu.php';
?>
<style media="screen">
	html, body {
		background: #f8f6f4 !important;
	}
</style>
<div class="container-fluid">
	<div class="header">
		<div class="erreur-404 text-center">
			<h1>Oups !!!!</h1>
			<div class="ligne-center jaune"></div>
			<p>Erreur 404</p>
			<img src="/images/header-achat-groupes-poemop.svg" alt="DǸcouvrez nos achats groupǸs" style="margin-bottom: 2%;width: 42%;">
			<h2>La page que vous recherchez est introuvable</h2>
			<div class="block erreur">
				<a href="/" class="btn btn-secondary">Retour à l'accueil !</a>
			</div>
		</div>
	</div>
</div>
<?php
$content = ob_get_clean();
require('template.php');

// phpinfo();

// Log des erreurs 404. Si besoin, prǸvoir MAJ pour les stocker en BDD et Dashboard d'analyse.
$fichier = fopen('404.txt', 'a');

// DǸtection environnement
$host = $_SERVER['HTTP_HOST'] ?? '';
$env = (stripos($host, 'dev.') === 0) ? '[DEV]' : '[PROD]';

// DonnǸes minimales utiles
$uri = $_SERVER["REQUEST_URI"] ?? '';
$referer = $_SERVER["HTTP_REFERER"] ?? '-';
$date = date('Y-m-d H:i:s');

// �%criture dans le fichier
fwrite($fichier, "$env [$date] $uri | Ref: $referer" . PHP_EOL);
fclose($fichier);

?>
