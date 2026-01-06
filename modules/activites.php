<?php
include_once __DIR__ . "/../inc/pmp_inc_fonctions.php";
include_once __DIR__ . "/../inc/pmp_inc_fonctions_departement.php";
$commande = getTotalLitreLivre($co_pmp);
$nb_litre_livre = number_format($commande[0],0,',',' ');
$total_economie = number_format($commande[0]*64/1000,0,',',' ');

$res_users = getInscriptionRecente($co_pmp);

$foyer = getNbFoyerGroupe($co_pmp);
$nb_foyer_groupe = $foyer[0];

$res = getDepartements($co_pmp);
?>
<div class="module activites">
	<div class="row">
		<div class="col top-mod align-self-center">
			<p>Activités</p>
		</div>
		<div class="col top-mod text-right text-end">
			<img loading="lazy" src="images/activites-poemop.svg" alt="POEMOP vous aide à faire des économies sur vos factures">
		</div>
	</div>
	<hr class="separe marge">
	<div class="bloc-activites text-center">
		<p class="titre-bloc">Economies</p>
		<hr class="separe">
		<div class="prix prix-orange"><?= $total_economie; ?><span>€</span></div>
		<p>économisés par nos utilisateurs</p>
	</div>
	<div class="bloc-activites text-center">
		<p class="titre-bloc">Inscriptions récentes</p>
		<hr class="separe">
<?php
	while($utilisateur = mysqli_fetch_array($res_users))
	{
		$date_aujourdhui = date("d/m");
		$date_hier = date("d/m", strtotime("-1 day"));
		$date_avanthier = date("d/m", strtotime("-2 days"));
		$date_inscription = substr($utilisateur['date_creation'],8,2) . "/" . substr($utilisateur['date_creation'],5,2);
		if($date_inscription == $date_aujourdhui)
		{
			if(!isset($affiche_aujourdhui))
			{
?>
			<p>Aujourd'hui</p>
<?php
			}
			$affiche_aujourdhui = true;
		}
		else if($date_inscription == $date_hier)
		{
			if(!isset($affiche_hier))
			{
?>
			<p>Hier</p>
<?php
			}
			$affiche_hier = true;
		}
		else if($date_inscription == $date_avanthier)
		{
			if(!isset($affiche_avanthier))
			{
?>
			<p>Avant-hier</p>
<?php
			}
			$affiche_avanthier = true;
		}
		else
		{
			if(!isset($affiche_avant))
			{
?>
			<p>Avant</p>
<?php
			}
			$affiche_avant = true;
		}
		$heure = substr($utilisateur['date_creation'],11,2);
		$minute = substr($utilisateur['date_creation'],14,2);
		if($heure == 0)
		{
			$heure = "06";
		}
?>
		<div class="prix small prix-violet" style="margin-left: -21%;"><?= $utilisateur['code_postal']; ?><span style="margin-left:0.3%;">  à <?= $heure . 'h' . $minute; ?></span></div>
<?php
	}
?>
		<!-- <p>Aujourd'hui</p>
		<div class="prix small prix-violet"><?= $total_economie; ?><span> à 10h44</span></div> -->
	</div>
	<div class="bloc-activites text-center">
		<p class="titre-bloc">Groupements</p>
		<hr class="separe">
		<div class="prix prix-bleu"><?= $nb_litre_livre; ?><span>L</span></div>
		<p>ont déjà été livrés</p>
	</div>
	<div class="bloc-activites text-center">
		<p class="titre-bloc">En cours de livraison</p>
		<hr class="separe">
		<div class="prix prix-vert"><?= $nb_foyer_groupe; ?><span> consommateurs</span></div>
		<p>sont dans un groupement</p>
	</div>
	<div class="text-center dep">
		<p> En savoir plus dans votre département</p>
		<form method="post">
			<div class="mb-3 row">
				<div class="col-sm-9">
					<select class="form-control form-lg" id="dep" name="dep" style="width: 290px;">
<?php
					while($dep = mysqli_fetch_array($res))
					{
						$n_dep = htmlentities($dep["id"]);
						$nom_dep = htmlentities($dep["libelle"]);
						$url = htmlentities($dep["url"]);
?>
						<option value="<?= $url; ?>"><?php if($n_dep < '10') { echo '0'; } echo $n_dep . ' ' . $nom_dep; ?></option>
<?php
					}
?>
					</select>
	    		</div>
				<div class="col-sm-3 text-right">
						<input type="submit" name="go_cp" class="btn btn-secondary" value="Go" style="padding: .45rem .95rem !important;">
				</div>
			</div>
		</form>
	</div>
</div>
