<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";

// Expiration de session 30 min
$session_lifetime = 45 * 60;
if (isset($_SESSION['LAST_ACTIVITY']) && time() - $_SESSION['LAST_ACTIVITY'] > $session_lifetime) {
	session_unset();
	session_destroy();
	session_start();
}
$_SESSION['LAST_ACTIVITY'] = time();

// Détection de l'environnement via le nom de domaine
$host = $_SERVER['HTTP_HOST'];
$isDev = str_contains($host, 'dev') || str_contains($host, 'localhost'); // adapte selon ton NDD dev

$req = "SELECT dev_passwd FROM site_settings LIMIT 1";
$res = mysqli_query($co_pmp, $req);
$row = mysqli_fetch_assoc($res);
$DEV_PASSWORD = $row['dev_passwd'];

// Vérification de la soumission du mot de passe
if ($isDev && !isset($_SESSION['dev_authorized'])) {
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dev_pwd'])) {
		if ($_POST['dev_pwd'] === $DEV_PASSWORD) {
			$_SESSION['dev_authorized'] = true;
			header('Location: index.php');
			exit;
		} else {
			$error = "Mot de passe incorrect";
		}
	}

	// Formulaire DEV avec ton design
	?>
	<!DOCTYPE html>
	<html lang="fr">

	<head>
		<meta charset="UTF-8">
		<meta name="robots" content="noindex, nofollow">
		<title>Accès Dev</title>
		<style>
			body {
				margin: 0;
				height: 100vh;
				display: flex;
				justify-content: center;
				align-items: center;
				background-color: #1e1e1e;
				color: #fff;
				font-family: sans-serif;
			}

			form {
				display: flex;
				flex-direction: column;
				background: #1c1a1a;
				padding: 30px;
				border-radius: 10px;
				box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
				width: 350px;
			}

			h2 {
				text-align: center;
				margin-bottom: 20px;
				font-size: 18px;
				line-height: 1.4;
			}

			input[type="password"] {
				padding: 10px;
				margin-bottom: 20px;
				border: none;
				border-radius: 5px;
				background: #3c3c3c;
				color: #fff;
			}

			button {
				padding: 10px;
				border: none;
				border-radius: 5px;
				background: #4caf50;
				color: #fff;
				cursor: pointer;
				font-weight: bold;
			}

			button:hover {
				background: #45a049;
			}

			.error {
				color: #ff4c4c;
				margin-bottom: 10px;
				text-align: center;
			}
		</style>
	</head>

	<body>
		<form method="post">
			<img src="images/logo-plus-on-est-moins-on-paie.png" />
			<h2>Vous allez accéder à l'environnement de développement du site POEMOP.</h2>
			<?php if (!empty($error))
				echo "<div class='error'>$error</div>"; ?>
			<input type="password" name="dev_pwd" placeholder="Mot de passe" required>
			<button>Se connecter</button>
		</form>
	</body>

	</html>
	<?php
	exit;
}

// Variables pour l'index
$desc = 'Commandes groupées de fioul domestique partout en France. Poemop groupe gratuitement votre commande de fioul avec celles de vos voisins.';
$title = 'Commandes groupées de fioul domestique avec POEMOP';
ob_start();

include 'modules/menu.php';
function resol()
{
	$resol = '<script type="text/javascript">
                    document.write(""+screen.width+"");
    </script>';
	return $resol;
}
$var_resol = resol();
?>
<div class="container-fluid">
	<div class="section">
		<div class="row">
			<div class="col-sm-9">
				<?php
				if ($var_resol >= 800) {
					?>
					<div class="compte-mobile">
						<?php
						include 'modules/connexion.php';
						?>
					</div>
					<?php
				}
				?>


				<!-- <div class="text-center" style="margin-top:105px;background: #f8f6f4;padding: 20px;border-radius: 25px">
					<h2>Information importante !</h2>
					<div class="ligne-center orange"></div>
					<p><strong>Les groupements sont actuellement perturbés par l'afflux massif de commandes chez les fournisseurs. Il se peut que certains groupements soient reportés.</strong> <br></p>
					<p>Merci de faire preuve de patience.</p>
					<p>Si vous ne trouvez pas les réponses à vos questions sur votre compte, privilégiez les demandes de renseignements par mail à <strong>info@poemop.fr</strong></p>
				</div> -->
				<div class="header achat_grp" style="    margin-top: 95px;">
					<div class="groupement-achats">
						<div class="row">
							<div class="col-sm-6 align-self-center">
								<h1>Commandes groupées <br>de fioul avec Poemop !</h1>
								<p>Vous voulez faire des économies ?</p>
								<div class="block">
									<a href="creer_un_compte_poemop.php" class="btn btn-primary">Rejoignez-nous !</a>
								</div>
							</div>
							<div class="col-sm-6">
								<img src="images/header-achat-groupes-poemop.svg" alt="Commandes groupées de fioul"
									width="535" height="373" class="img-grpt">
							</div>
						</div>
						<div class="groupements text-center">
							<h2>Je choisis un groupement</h2>
							<div class="ligne-center jaune"></div>
							<div class="row">
								<div class="col">
									<a href="achats_groupes_fioul.php" class="links-groupements">
										<div class="bbox fioul-img">
											<img src="images/achat-groupe-fioul.svg"
												alt="Commandes groupées de fioul avec Poemop">
											<p>Achat groupé<br><span>de fioul</span></p>
										</div>
									</a>
								</div>
								<div class="col">
									<div class="bbox" style="background:#c3bcb68c;">
										<img src="images/changement-de-chaudiere-no.svg"
											alt="Participez aux achats groupés d'électricité avec Poemop">
										<p>Changement <br><span>de chaudière</span></p>
									</div>
									<!-- <a href="/achats_groupes_changement_de_chaudiere.php" class="links-groupements">
										<div class="bbox" style="background:#c3bcb68c;">
											<img src="/images/changement-de-chaudiere-no.svg" alt="Participez aux achats groupés d'électricité avec Poemop">
											<p>Changement <br><span>de chaudière</span></p>
										</div>
									</a> -->
								</div>
								<div class="col">
									<div class="bbox" style="background:#c3bcb68c;">
										<img src="images/groupement-gaz.svg"
											alt="Réaliser des économies sur vos contrats de gaz avec Poemop">
										<p>Achat groupé<br><span>de gaz</span></p>
									</div>
									<!-- <a href="/achats_groupes_gaz.php" class="links-groupements">
										<div class="bbox" style="background:#c3bcb68c;">
											<img src="/images/groupement-gaz.svg" alt="Réaliser des économies sur vos contrats de gaz avec Poemop">
											<p>Achat groupé<br><span>de gaz</span></p>
										</div>
									</a> -->
								</div>
								<div class="col">
									<div class="bbox" style="background:#c3bcb68c;">
										<img src="images/groupement-abonnement-telephone.svg"
											alt="bénéficiez d'offres négociées pour obtenir des abonnements moins chers">
										<p>Trouver un<br><span>bon artisan</span></p>
									</div>
									<!-- <a href="/achats_groupes_artisan.php">
										<div class="bbox" style="background:#c3bcb68c;">
											<img src="/images/groupement-abonnement-telephone.svg" alt="bénéficiez d'offres négociées pour obtenir des abonnements moins chers">
											<p>Trouver un<br><span>bon artisan</span></p>
										</div>
									</a> -->
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="poemop-f" style="padding: 2% 0 0 0;">
					<div class="titre-bbox text-center">
						<img class="img" src="images/rejoindre-poemop.svg"
							alt="Commandes groupées de fioul - Rejoignez Poemop et faites des économies" width="65"
							height="80">
						<h2>Rejoindre Poemop<br>c’est simple !</h2>
						<div class="ligne-center orange"></div>
					</div>
					<div class="row">
						<div class="col">
							<div class="bbox2 inscription">
								<p class="top">Je m'inscris<br>gratuitement</p>
								<hr>
								<p class="text-center">Je crée mon compte gratuitement et simplement. Je renseigne bien
									mes coordonnées afin que l'on puisse m'avertir si un groupement est initié sur mon
									secteur.</p>
								<div class="number">
									01
								</div>
							</div>
						</div>
						<div class="col">
							<div class="bbox2 participe-groupement">
								<p class="top">Je participe<br>à un groupement</p>
								<hr>
								<p class="text-center">Je sélectionne les groupements pour lesquels je suis intéressé.
									Nous négocions pour vous et vous recevez l'offre par email. Plus il y aura
									d'inscrits plus l'offre sera intéressante. </p>
								<div class="number">
									02
								</div>
							</div>
						</div>
						<div class="col">
							<div class="bbox2 offre-groupement">
								<p class="top">Je profite<br>de l'offre</p>
								<hr>
								<p class="text-center">Je reçois l'offre par email, ou je suis directement contacté par
									un partenaire. Je refuse ou je profite de l'offre sans avoir à me justifier. Je fais
									des économies !</p>
								<div class="number">
									03
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="achats-groupes text-center">
					<img loading="lazy" class="img" src="images/economies-groupement-poemop-blc.svg"
						alt="Commandes groupées de fioul" width="60" height="73">
					<h2>Faites des économies en participant<br>à des achats groupés !</h2>
					<div class="ligne-center jaune"></div>
					<p>Inscrivez-vous en 5 min.</p>
					<a href="mes_inscriptions.php" class="btn btn-primary">Rejoignez nous !</a>
				</div>
				<div class="poemop text-center">
					<img loading="lazy" class="img" src="images/poemop.svg"
						alt="Commandes groupées de fioul - Qui sommes nous - Poemop" width="60" height="70">
					<h2>Qui est Poemop ?</h2>
					<div class="ligne-center orange"></div>
					<p>Créé en 2008 par trois particuliers chauffés au fioul, POEMOP est né d’une idée simple :
						<strong>se regrouper pour faire baisser le prix du fioul domestique.</strong>
					</p>
					<p>Face à la hausse constante du coût de l’énergie, nos fondateurs ont imaginé une solution
						collaborative permettant à tous les consommateurs de profiter d’un <strong>tarif plus
							avantageux</strong> grâce à la commande groupée de fioul.</p>
					<p>En <span id="year"></span>, alors que le prix du fioul reste particulièrement instable en raison
						de la conjoncture économique et géopolitique, notre mission est plus que jamais d’actualité :
						<strong>négocier pour vous le meilleur prix du fioul, au plus près de chez vous, partout en
							France.</strong>
					</p>
					<h3>Notre service est entièrement gratuit et sans engagement.</h3>
					<p>POEMOP, c’est avant tout une communauté solidaire de consommateurs, rassemblés autour d’un
						objectif commun : <strong>payer le juste prix du fioul</strong> tout en simplifiant leurs
						démarches.</p>
					<p>Rejoignez dès aujourd’hui le mouvement et découvrez <strong>comment faire des économies sur votre
							fioul domestique</strong> en quelques clics.</p>
					<div class="block text-center poemop-trait">
						<a href="creer_un_compte_poemop.php" class="btn btn-primary">Rejoignez-nous !</a>
					</div>
				</div>
				<div class="presse text-center">
					<h2>Une renommée nationale<br>depuis plus de 15 ans</h2>
					<div class="ligne-center jaune"></div>
					<p>Les médias parlent de nous ! Retrouvez ci-dessous le reportage diffusé dans le journal de 13h de
						France 2.<br>Vous pouvez également consultez l'ensemble de notre revue de presse.</p>
					<a href="revue_de_presse.php#france2">
						<img loading="lazy" src="images/video_acceuil.png" alt="Video reportage de France 2" width="552"
							height="310" class="image-f2">
					</a>
					<div class="text-center" style="margin-top:2%;">
						<a href="revue_de_presse.php" class="btn btn-secondary">Revue de presse</a>
					</div>

				</div>
				<?php
				// include 'modules/actu.php';
				?>
			</div>
			<div class="col-sm-3" style="margin-top: 5.9%;">
				<?php
				include 'modules/connexion.php';
				include 'modules/activites.php';
				include 'modules/avis_clients.php';
				?>
			</div>
		</div>
	</div>
</div>

<link rel="stylesheet" href="slick-carousel/slick-theme.css" media="screen">
<?php
include 'modules/partenaires.php';
?>
<script>
	document.getElementById("year").textContent = new Date().getFullYear();
</script>

<?php
$content = ob_get_clean();
require('template.php');
?>