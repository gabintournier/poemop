
<?php
include_once 'inc/dev_auth.php';
session_start();
$desc = 'Suivez l\'actualités Poemop. Poemop groupe gratuitement vos achats avec ceux de vos voisins.';
$title = 'Actualités POEMOP';
ob_start();
include 'modules/menu.php';
?>

<div class="container-fluid">
	<div class="header">
		<div class="actu">
			<h1>Suivez l'actualités<br>avec Poemop !</h1>
			<div class="ligne jaune"></div>
			<div class="row">
				<div class="col-sm-6">
					<div class="bloc-actu">
						<img src="images/actu1.png" alt="">
						<div class="titre-actu">
							Lorem Ipsum is simply dummy text<br>of the printing and typesetting
							<div class="ligne vert"></div>
						</div>
						<p class="text">Lorem Ipsum is simply dummy text of the printing and typesetting, Lorem Ipsum is simply dummy text of the printing and typesetting</p>
						<p class="date">24 aout 2021</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu">
						<img src="images/actu2.png" alt="">
						<div class="titre-actu">
							Lorem Ipsum is simply dummy text<br>of the printing and typesetting
							<div class="ligne vert"></div>
						</div>
						<p class="text">Lorem Ipsum is simply dummy text of the printing and typesetting, Lorem Ipsum is simply dummy text of the printing and typesetting</p>
						<p class="date">24 aout 2021</p>
						<hr class="separe">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="bloc-actu">
						<img src="images/actu3.png" alt="">
						<div class="titre-actu">
							Lorem Ipsum is simply dummy text<br>of the printing and typesetting
							<div class="ligne vert"></div>
						</div>
						<p class="text">Lorem Ipsum is simply dummy text of the printing and typesetting, Lorem Ipsum is simply dummy text of the printing and typesetting</p>
						<p class="date">24 aout 2021</p>
						<hr class="separe">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
