<div class="ligne-center jaune"></div>
<?php
if(isset($utilisateur["code_postal"]))
{
	$res_ville = getVilleCp($co_pmp, $utilisateur["code_postal"]);
}
?>
<p class="center">* Champs obligatoires pour passer commande</p>
<h3>Adresse de livraison</h3>
<hr class="separe">
<form method="post" class="adresse">
	<div class="row">
		<div class="col-sm-6">
			<label for="nom" class="col-form-label custom-label">
				Nom *
			</label>
			<input type="text" class="form-control form-lg" name="nom" placeholder="Nom" value="<?php if(isset($utilisateur["nom"])) { echo $utilisateur["nom"]; } ?>">
		</div>
		<div class="col-sm-6">
			<label for="prenom" class="col-form-label custom-label">
				Prénom *
			</label>
			<input type="text" class="form-control form-lg" name="prenom" placeholder="Prénom" value="<?php if(isset($utilisateur["prenom"])) { echo $utilisateur["prenom"]; } ?>">
		</div>
		<div class="col-sm-6">
			<label for="adresse" class="col-form-label custom-label">
				Adresse *
			</label>
			<input type="text" class="form-control form-lg" name="adresse" placeholder="Adresse" required="required" value="<?php if(isset($utilisateur["adresse"])) { echo $utilisateur["adresse"]; } ?>">
		</div>
		<div class="col-sm-6">
			<label for="code_postal" class="col-form-label custom-label">
				Code postal *
			</label>
			<input type="text" class="form-control form-lg" name="code_postal" placeholder="Code postal" value="<?php if(isset($utilisateur["code_postal"])) { echo $utilisateur["code_postal"]; } ?>">
		</div>
		<div class="col-sm-6">
			<label for="code_postal_id" class="col-form-label custom-label">
				Commune *
			</label>
			<select class="form-control form-lg" name="code_postal_id" placeholder="code_postal_id">
				<?php
							$nb_ville = 0;
							if($utilisateur["code_postal_id"] == '')
							{
			?>
								<option value="">Sélectionnez</option>
			<?php
							}
							while($ville = mysqli_fetch_array($res_ville))
							{
			?>
								<option value="<?= $ville["id"]; ?>" <?php if($ville["id"] == $utilisateur["code_postal_id"]) { echo "selected='selected'"; } ?>><?= $ville["ville"]; ?></option>
			<?php
							}
			?>
			</select>
		</div>
		<div class="col-sm-6">
			<label for="tel1" class="col-form-label custom-label">
				Téléphone portable *
			</label>
			<input type="text" class="form-control form-lg" name="tel1" placeholder="Téléphone portable" value="<?php if(isset($utilisateur["tel_port"])) { echo $utilisateur["tel_port"]; } ?>">
		</div>
		<div class="col-sm-6">
			<label for="tel2" class="col-form-label custom-label">
				Téléphone 2
			</label>
			<input type="text" class="form-control form-lg" name="tel2" placeholder="Téléphone 2" value="<?php if(isset($utilisateur["tel_fixe"])) { echo $utilisateur["tel_fixe"]; } ?>">
		</div>
		<div class="col-sm-6">
			<label for="tel3" class="col-form-label custom-label">
				Téléphone 3
			</label>
			<input type="text" class="form-control form-lg" name="tel3" placeholder="Téléphone 3" value="<?php if(isset($utilisateur["tel_3"])) { echo $utilisateur["tel_3"]; } ?>">
		</div>
		<div class="col-sm-6">
			<label for="mail" class="col-form-label custom-label">
				Adresse email *
			</label>
			<input type="email" class="form-control form-lg" name="mail" placeholder="Adresse email" value="<?php if(isset($utilisateur["email"])) { echo $utilisateur["email"]; } ?>">
		</div>
	</div>
	<div class="text-center">
		<input type="submit" class="btn btn-secondary btn-form" name="valider_coordonnees" value="Valider">
	</div>
</form>
