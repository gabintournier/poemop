
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
	<nav class="navbar navbar-expand-lg navbar-light bg-light" >
		<div class="container-fluid">
	    	<a class="navbar-brand" href="/"  title="Revenir à l'accueil de Poemop">
				<img src="images/logo-plus-on-est-moins-on-paie.png" alt="Participez à des achats groupés avec Poemop" width="209" height="44">

			</a>
			<div class="connexion-mobile">
				<div class="d-flex">
					<img src="images/connexion-poemop.svg" alt="entièrement gratuit et transparent pour vous">
					<a href="mon_compte.php" title="Inscription poemop">Mon compte</a>
				</div>
			</div>
	    	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	      		<span class="navbar-toggler-icon"></span>
	    	</button>
	    	<div class="collapse navbar-collapse" id="navbarSupportedContent">
	      		<ul class="nav navbar-nav mx-auto order-0">
	        		<li class="nav-item">
	          			<a class="nav-link" aria-current="page" href="comment_ca_marche_gaz.php">Comment ça marche : Artisans</a>
	        		</li>
					<li class="nav-item">
	          			<a class="nav-link" href="groupements_achats_poemop.php">Groupements</a>
	        		</li>
					<!-- <li class="nav-item">
	          			<a class="nav-link" href="/actualites_poemop.php">Actualités</a>
	        		</li> -->
					<li class="nav-item">
	          			<a class="nav-link" href="contacter_poemop.php">Contact</a>
	        		</li>
	      		</ul>
<?php
				if (isset($_SESSION['id']))
				{
?>
				<img src="images/connexion-poemop.svg" class="mon-compte" alt="entièrement gratuit et transparent pour vous"><a class="nav-link compte" href="mes_inscriptions.php">Mes inscriptions</a>
				<div class="d-flex">
					<a class="btn btn-primary" href="inc/pmp_inc_fonctions_deconnexion.php" title="Déconnexion poemop">Déconnexion</a>
				</div>
<?php
				}
				else
				{
?>
				<div class="d-flex">
					<a class="btn btn-primary" href="creer_un_compte_poemop.php" title="Inscription poemop">Inscription / Connexion</a>
				</div>
<?php
				}
?>
	    	</div>

		</div>
	</nav>
</div>
