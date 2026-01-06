<?php
include_once __DIR__ . "/../inc/pmp_inc_fonctions_avis.php";
$res = getDerniersMessages($co_pmp);
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
?>

<div class="module avis-clients">
	<div class="row">
		<div class="col top-mod align-self-center">
			<p>Avis clients</p>
		</div>
		<div class="col top-mod text-right text-end">
			<img loading="lazy" src="images/avis-clients-poemop.svg" alt="Avis clients poemop" style="width: 30%;">
		</div>
	</div>
	<hr class="separe marge">
	<div class="bloc-avis text-center">
		<img loading="lazy" src="images/avis-clients-poemop.svg" alt="12 997 messages poemop" style="width: 16%;">
		<p class="titre-bloc">Total de messages</p>
		<div class="prix prix-vert"><?= number_format($nb_avis, 0, ',', ' '); ?></div>
	</div>
	<hr class="separe width">
	<div class="bloc-avis text-center">
		<img loading="lazy" src="images/moyenne-poemop.svg" alt="4.8/5 de moyenne poemop" style="width: 16%;">
		<p class="titre-bloc">Note moyenne</p>
		<div class="prix prix-vert"><?= $note . "/5"; ?></div>
	</div>
<?php
$i = 1;
	while ($message = mysqli_fetch_array($res))
	{
?>
	<div class="bloc-activites">
		<p class="client"><?= $message["signature"]; ?></p>
		<p class="date">Le <?= substr($message["date"],8,2) . "-" . substr($message["date"],5,2) . "-" . substr($message["date"],0,4); ?></p>
		<div class="star nb<?= $i++ ?>">
			<i class="fa fa-star" aria-hidden="true"></i>
			<i class="fa fa-star" aria-hidden="true"></i>
			<i class="fa fa-star" aria-hidden="true"></i>
			<i class="fa fa-star" aria-hidden="true"></i>
			<i class="fa fa-star" aria-hidden="true"></i>
		</div>
		<hr class="separe">
		<p class="avis"><?= $message["message"]; ?></p>
	</div>
<?php
	}
?>
	<div class="text-center" style="margin-top:5%;">
		<a href="avis_clients.php" class="btn btn-secondary"  title="Avis commande fioul domestique poemop">Voir tous les avis</a>
	</div>

</div>
