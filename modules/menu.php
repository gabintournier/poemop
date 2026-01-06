
<div class="menu" id="header-nav">
	<div class="navtop text-center">
    <?php
        $host = $_SERVER['HTTP_HOST'];
        $isDev = str_contains($host, 'dev') || str_contains($host, 'localhost'); // adapte selon ton NDD dev
        if ($isDev) {
            echo '<span style="color: #fff;">⚠️BDD : <b style="color:red;">'.$user_pmp.'</b> <br>⚠️ENVIRONNEMENT DE <b style="color:red;">DEV</b></span><br><br>';
        }
		else {
			echo "C'est gratuit et sans engagement";
		}
    ?>
	</div>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container-fluid">
	    	<a class="navbar-brand" href="index.php"  title="Revenir à l'accueil de Poemop">
				<img src="images/logo-plus-on-est-moins-on-paie.png" alt="Participez à des achats groupés avec Poemop" width="209" height="44">
			</a>

	    	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	      		<span class="navbar-toggler-icon"></span>
	    	</button>
	    	<div class="collapse navbar-collapse" id="navbarSupportedContent">
	      		<ul class="nav navbar-nav mx-auto order-0">
					<li class="nav-item">
	          			<a class="nav-link" aria-current="page" href="comment_ca_marche_fioul.php">Comment ça marche</a>
	        		</li>
					<li class="nav-item">
	          			<a class="nav-link" aria-current="page" href="commande_groupe_fioul_france.php">Commande groupée de fioul en France</a>
	        		</li>
					<li class="nav-item">
	          			<a class="nav-link" href="contacter_poemop.php">Contact</a>
	        		</li>
<?php
				if (!isset($_SESSION['id']))
				{
?>
					<li class="nav-item connexion mobile-responsive">
						<a class="" href="creer_un_compte_poemop.php?connexion=1">Connexion</a>
					</li>
<?php
				}
?>
	      		</ul>

<?php
				if (isset($_SESSION['id']))
				{
?>
				<div class="d-flex menu-compte">
					<a class="btn btn-primary" href="inc/pmp_inc_fonctions_deconnexion.php" title="Déconnexion poemop">Déconnexion</a>
				</div>
				<div class="mobile-menu-compte">
					<h2>Mon compte</h2>
					<a class="btn btn-secondary" href="inc/pmp_inc_fonctions_deconnexion.php" title="Déconnexion poemop" style="width: 100%;">Déconnexion</a>
					<hr class="separe">
					<ul class="nav-mobile">
						<li class="nav-item-mobile">
		          			<a href="ma_commande.php">Ma commande fioul</a>
		        		</li>
						<!--
						<li class="nav-item-mobile">
		          			<a href="mes_inscriptions.php">Mes inscriptions</a>
		        		</li>
						-->
						<li class="nav-item-mobile">
		          			<a href="mon_compte.php?type=fioul">Mon compte</a>
		        		</li>
						<li class="nav-item-mobile">
		          			<a href="parametres_compte.php">Mes paramètres</a>
		        		</li>
		      		</ul>
				</div>
<?php
				}
				else
				{
?>
				<div class="d-flex">
					<a class="btn btn-primary" href="creer_un_compte_poemop.php" title="Inscription poemop">Inscription</a>
				</div>
<?php
				}
?>

	    	</div>

		</div>
	</nav>
</div>
