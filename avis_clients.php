<?php
include_once 'inc/dev_auth.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);


include_once __DIR__ . "/inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_departement.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_avis.php";

$avis = getTotalMessage($co_pmp);
$nb_avis = mysqli_num_rows($avis);

$res_note = getTotalNoteMessage($co_pmp);
if($note = mysqli_fetch_array($res_note))
{
	$total_note = $note[0];
}

$res_nb = getNoteMessage($co_pmp);
if($nb = mysqli_fetch_array($res_nb))
{
	$nb_note = $nb[0];
}


if(($total_note > 0) && ($nb_note > 0))
{
	$note = number_format($total_note / $nb_note, 1);
}

$desc = 'Les clients POEMOP fioul nous ont attribué la note de ' . $note . '/5 sur un total de ' . $nb_avis . ' avis client.S';
$title = 'Les avis des clients POEMOP ayant commandé du fioul domestique groupé et moins cher.';
ob_start();

$res = getDepartements($co_pmp);



if(!isset($_GET["etoile"]))
{
	$res = getMeilleursMessages($co_pmp);
	$nb_etoile = 0;
}
else
{
	$nb_etoile = $_GET["etoile"];
	$res = getEtoilesMessages($co_pmp, $nb_etoile);
}

$res_nb_5 = getNbMessages($co_pmp, "5");
$nb_avis_5 = mysqli_num_rows($res_nb_5);

$res_nb_4 = getNbMessages($co_pmp, "4");
$nb_avis_4 = mysqli_num_rows($res_nb_4);

$res_nb_3 = getNbMessages($co_pmp, "3");
$nb_avis_3 = mysqli_num_rows($res_nb_3);

$res_nb_2 = getNbMessages($co_pmp, "2");
$nb_avis_2 = mysqli_num_rows($res_nb_2);

$res_nb_1 = getNbMessages($co_pmp, "1");
$nb_avis_1 = mysqli_num_rows($res_nb_1);

include 'modules/menu.php';
?>

<div class="container-fluid">
	<div class="header bloc-avis-clients">
		<div class="row">
			<div class="col-sm-9">
				<div class="text-center">
					<h1>Les avis clients de POEMOP</h1>
					<div class="ligne-center jaune"></div>
					<div class="navbar-groupements">
					    <div class="navbar-columns">
					        <div class="left-col">
					            <a href="avis_clients.php" class="navbar-link <?= ($nb_etoile>=1 && $nb_etoile<=5) ? '' : 'active' ?>">Tous les avis</a>
					            <a href="avis_clients.php?etoile=5" class="navbar-link <?= ($nb_etoile==5)?'active':'' ?>">
					                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
					                (<?= $nb_avis_5 ?>)
					            </a>
					            <a href="avis_clients.php?etoile=4" class="navbar-link <?= ($nb_etoile==4)?'active':'' ?>">
					                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
					                (<?= $nb_avis_4 ?>)
					            </a>
					        </div>
					
					        <div class="right-col">
					            <a href="avis_clients.php?etoile=3" class="navbar-link <?= ($nb_etoile==3)?'active':'' ?>">
					                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
					                (<?= $nb_avis_3 ?>)
					            </a>
					            <a href="avis_clients.php?etoile=2" class="navbar-link <?= ($nb_etoile==2)?'active':'' ?>">
					                <i class="fa fa-star"></i><i class="fa fa-star"></i>
					                (<?= $nb_avis_2 ?>)
					            </a>
					            <a href="avis_clients.php?etoile=1" class="navbar-link <?= ($nb_etoile==1)?'active':'' ?>">
					                <i class="fa fa-star"></i>
					                (<?= $nb_avis_1 ?>)
					            </a>
					        </div>
					    </div>
					</div>

					<hr class="separe">
				</div>
<?php
				if(!isset($_GET["etoile"]))
				{
?>
					<h2>Derniers avis</h2>
<?php
				}
				else if($_GET['etoile'] == "1")
				{
?>
					<h2>Derniers avis <?= htmlspecialchars($_GET['etoile']); ?> étoiles <?php echo "(" . $nb_avis_1 . ")"; ?></h2>
<?php
				}
				else
				{
?>
					<h2>Derniers avis <?= htmlspecialchars($_GET['etoile']); ?> étoiles <?php if($_GET['etoile'] == "5") { echo "(" . $nb_avis_5 . ")"; } if($_GET['etoile'] == "4") { echo "(" . $nb_avis_4 . ")"; } if($_GET['etoile'] == "3") { echo "(" . $nb_avis_3 . ")"; } if($_GET['etoile'] == "2") { echo "(" . $nb_avis_2 . ")"; } ?></h2>
<?php
				}
				while($message = mysqli_fetch_array($res))
				{
?>
				<div class="commentaire">
					<p class="client"><?= $message["signature"]; ?></p>
					<p class="date">Le <?= substr($message["date"],8,2) . "-" . substr($message["date"],5,2) . "-" . substr($message["date"],0,4); ?></p>
<?php
					if(!isset($_GET["etoile"]) || $_GET["etoile"] == 5)
					{
?>
					<div class="star nb1">
						<i class="fa fa-star" aria-hidden="true"></i>
						<i class="fa fa-star" aria-hidden="true"></i>
						<i class="fa fa-star" aria-hidden="true"></i>
						<i class="fa fa-star" aria-hidden="true"></i>
						<i class="fa fa-star" aria-hidden="true"></i>
					</div>
<?php
					}
					else if($_GET["etoile"] == 4)
					{
?>
					<div class="star nb1">
						<i class="fa fa-star" aria-hidden="true"></i>
						<i class="fa fa-star" aria-hidden="true"></i>
						<i class="fa fa-star" aria-hidden="true"></i>
						<i class="fa fa-star" aria-hidden="true"></i>
						<i class="far fa-star" aria-hidden="true"></i>
					</div>
<?php
					}
					else if($_GET["etoile"] == 3)
					{
?>
					<div class="star nb1">
						<i class="fa fa-star" aria-hidden="true"></i>
						<i class="fa fa-star" aria-hidden="true"></i>
						<i class="fa fa-star" aria-hidden="true"></i>
						<i class="far fa-star" aria-hidden="true"></i>
						<i class="far fa-star" aria-hidden="true"></i>
					</div>
<?php
					}
					else if($_GET["etoile"] == 2)
					{
?>
					<div class="star nb1">
						<i class="fa fa-star" aria-hidden="true"></i>
						<i class="fa fa-star" aria-hidden="true"></i>
						<i class="far fa-star" aria-hidden="true"></i>
						<i class="far fa-star" aria-hidden="true"></i>
						<i class="far fa-star" aria-hidden="true"></i>
					</div>
<?php
					}
					else if($_GET["etoile"] == 1)
					{
?>
					<div class="star nb1">
						<i class="fa fa-star" aria-hidden="true"></i>
						<i class="far fa-star" aria-hidden="true"></i>
						<i class="far fa-star" aria-hidden="true"></i>
						<i class="far fa-star" aria-hidden="true"></i>
						<i class="far fa-star" aria-hidden="true"></i>
					</div>
<?php
					}
					if(isset($message["message"]))
					{
?>
					<hr class="separe">
					<p class="avis"><?= $message["message"]; ?></p>
<?php
					}
?>
<?php
					if(isset($message["message"]))
					{
?>
					<div class="reponse">
					    <?= $message["reponse"]; ?>
					    <div class="signRep">L'équipe Poemop</div>
					</div>

<?php
					}
?>
				</div>
<?php
				}
				//page suivante
				if(isset($_GET['etoile']))
				{
				// si il y a des message avant
				if($_GET['etoile'] == 5) { $nb_avis = $nb_avis_5; }
				else if($_GET['etoile'] == 4) { $nb_avis = $nb_avis_4; }
				else if($_GET['etoile'] == 3) { $nb_avis = $nb_avis_3; }
				else if($_GET['etoile'] == 2) { $nb_avis = $nb_avis_2; }
				else if($_GET['etoile'] == 1) { $nb_avis = $nb_avis_1; }
?>

				<div class="row">
					<div class="col-sm-6">
<?php
					if(isset($_GET['debut']))
					{
						$debut = $_GET['debut'] - 20;
						if($debut >= 0)
						{
?>
						<a style="background: #34726130;padding: 2%;border-radius: 6px;color: #0f393a;" href="avis_clients.php?etoile=<?= htmlspecialchars($_GET['etoile']) ?>&debut=<?= htmlspecialchars($debut) ?>" title="Les avis clients"><i class="fa-solid fa-arrow-left-long" style="font-size: 24px;margin-right: 4%;position: relative;top: 12%;"></i> Page précédente</a>
<?php
						}
					}
?>
					</div>
					<div class="col-sm-6 text-right">
<?php
					if(!isset($_GET['debut'])) { $_GET['debut'] = '0'; }
					$debut = $_GET['debut'] + 20;

					if($debut < $nb_avis)
					{
?>
						<a style="background: #34726130;padding: 2%;border-radius: 6px;color: #0f393a;" href="avis_clients.php?etoile=<?= htmlspecialchars($_GET['etoile']) ?>&debut=<?= htmlspecialchars($debut) ?>" title="Les avis clients" rel="nofollow"> Page suivante <i class="fa-solid fa-arrow-right-long" style="font-size: 24px;margin-left: 4%;position: relative;top: 12%;"></i></a>
<?php
					}
?>
					</div>
				</div>
<?php
				}
?>
				<div class="block text-center faq">
					<a href="creer_un_compte_poemop.php" class="btn btn-secondary">Rejoignez-nous !</a>
				</div>
			</div>
			<div class="col-sm-3">
<?php
				include 'modules/connexion.php';
				include 'modules/activites.php';
?>
			</div>
		</div>
	</div>
</div>

<?php
$content = ob_get_clean();
require('template.php');
?>
