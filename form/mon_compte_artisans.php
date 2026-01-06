<div class="ligne-center jaune"></div>
<p class="center">C'est très simple ! Nous sommes sûrs que vous avez dans vos contacts des professionnels auxquels vous avez fait appel et qui vous ont apporté satisfaction. Nous invitons chacun d'entre vous à nous soumettre un ou plusieurs artisans que vous vous pourriez recommander (plombier, peintre, électricien, menuisier, couvreur, chauffagiste, serrurier...) et qui pourrait permettre aux autres inscrits de trouver la bonne personne pour réaliser leurs travaux.</p>
<p class="center">* Champs obligatoire</p>
<hr class="separe">
<form class="adresse" method="post">
	<div class="row">
		<div class="col-sm-6">
			<label for="raison_social" class="col-form-label custom-label">
				Raison social *
			</label>
			<input type="text" class="form-control form-lg" name="raison_social" placeholder="Raison social" required="required">
		</div>

		<div class="col-sm-6">
			<label for="type" class="col-form-label custom-label">
				Type d'activité *
			</label>
			<input type="text" class="form-control form-lg" name="type" placeholder="Type d'activité" required="required">
		</div>
	</div>
	<div class="text-center">
		<input type="submit" class="btn btn-secondary btn-form" name="envoyer_artisan" value="Valider">
	</div>
</form>
<hr class="separe">
<div id="tableau_moncompte">
	<table class="table">
		<thead>
			<tr>
				<th scope="col">Raison social</th>
				<th scope="col">Type</th>
			</tr>
		</thead>
		<tbody>
<?php
		while ($artisan = mysqli_fetch_array($pmp_artisan))
		{
?>
			<tr>
				<td><?php print $artisan['nom']; ?></td>
				<td><?php print $artisan['type']; ?></td>
			</tr>
<?php
		}
?>
		</tbody>
	</table>
</div>
