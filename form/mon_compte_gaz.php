<?php
$res_ville = getVilleCp($co_pmp, $utilisateur["code_postal"]);
?>
<div class="ligne-center jaune"></div>
<p class="center">* Champs important pour l'inscription</p>
<h3>Mon profil</h3>
<hr class="separe">
<form class="adresse" method="post">
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
			while($ville = mysqli_fetch_array($res_ville))
			{
?>
				<option value="<?= $ville["id"]; ?>" <?php if($ville["id"] == $utilisateur["code_postal_id"]) { echo "selected='selected'"; } ?>><?= $ville["ville"]; ?></option>
<?php
			}
?>
			</select>
		</div>
	</div>
	<div class="text-center">
		<input type="submit" class="btn btn-secondary btn-form" name="valider_coordonnees" value="Valider">
	</div>

	<h3>Contact</h3>
	<hr class="separe">
	<p>Indiquez plutôt vos numéros de téléphone portable car nous sommes susceptibles de vous contacter par SMS</p>

	<div class="row">
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
			<input  disabled="disabled" type="email" class="form-control form-lg" name="mail" placeholder="Adresse email" value="<?php if(isset($utilisateur["email"])) { echo $utilisateur["email"]; } ?>">
		</div>
	</div>
	<div class="text-center">
		<input type="submit" class="btn btn-secondary btn-form" name="valider_coordonnees" value="Valider">
	</div>

	<h3>Mon habitation</h3>
	<hr class="separe">
	<div class="row">
		<div class="col-sm-12">
			<label for="habitation_type" class="col-form-label custom-label">
				Type de logement
			</label>
			<select class="form-control custom-input form-lg white" name="habitation_type" >
				<option value="1">Appartement</option>
				<option value="2">Maison</option>
			</select>
		</div>
		<div class="col-sm-6">
			<label for="habitation_surface" class="col-form-label custom-label">
				Surface approximative (en m2) *
			</label>
			<input type="text" class="form-control form-lg" name="habitation_surface" placeholder="Surface" >
		</div>
		<div class="col-sm-6">
			<label for="habitation_occupant" class="col-form-label custom-label">
				Nombre d'occupants
			</label>
			<input type="text" class="form-control form-lg" name="habitation_occupant" placeholder="Nombre d'occupants">
		</div>
		<div class="col-sm-12">
			<label for="habitation_chauffage" class="col-form-label custom-label">
				Chauffage *
			</label>
			<select class="form-control custom-input form-lg white" name="habitation_chauffage" >
				<option value="0"></option>
				<option value="1">Collectif</option>
				<option value="2">Fioul</option>
				<option value="3">Électricité</option>
				<option value="4">Gaz naturel</option>
				<option value="5">Propane en citerne</option>
				<option value="6">Poêle / Chaudière à bois</option>
				<option value="7">Poêle / Chaudière à granulé</option>
				<option value="8">Chéminée</option>
				<option value="9">Pompe à chaleur</option>
				<option value="10">Autre</option>
			</select>
		</div>
		<div class="col-sm-12">
			<label for="habitation_eau" class="col-form-label custom-label">
				Eau *
			</label>
			<select class="form-control custom-input form-lg white" name="habitation_eau" >
				<option value="0"></option>
				<option value="1">Collectif</option>
				<option value="2">Fioul</option>
				<option value="3">Électricité</option>
				<option value="4">Gaz naturel</option>
				<option value="5">Propane en citerne</option>
				<option value="6">Chauffe-eau solaire</option>
				<option value="7">Autre</option>
			</select>
		</div>
		<div class="col-sm-12">
			<label for="habitation_cuisson" class="col-form-label custom-label">
				Cuisson
			</label>
			<select class="form-control custom-input form-lg white" name="habitation_cuisson" >
				<option value="0"></option>
				<option value="1">Électricité</option>
				<option value="2">Gaz naturel</option>
				<option value="3">Bouteille de gaz</option>
				<option value="4">Autre</option>
			</select>
		</div>
	</div>
	<div class="text-center">
		<input type="submit" class="btn btn-secondary btn-form" name="valider_gaz" value="Valider">
	</div>

	<h3>Mon compteur gaz</h3>
	<hr class="separe">
	<div class="row">
		<div class="col-sm-6">
			<label for="compteur_pce" class="col-form-label custom-label">
				Mon point de comptage et d'estimation PCE (indiqué sur ma facture) *
			</label>
			<input type="text" class="form-control form-lg" name="compteur_pce" placeholder="Compteur PCE">
		</div>

		<div class="col-sm-6">
			<label for="compteur_consommation" class="col-form-label custom-label">
				Ma consommation annuelle (en kWh/an) *
			</label>
			<input type="text" class="form-control form-lg" name="compteur_consommation" placeholder="Consommation">
		</div>

		<div class="col-sm-6">
			<label for="compteur_fournisseur" class="col-form-label custom-label">
				Mon fournisseur actuel
			</label>
			<input type="text" class="form-control form-lg" name="compteur_fournisseur" placeholder="Mon fournisseur">
		</div>
		<div class="col-sm-6">
			<label for="compteur_demenagement" class="col-form-label custom-label">
				Je déménage *
			</label>
			<select class="form-control custom-input form-lg white" name="compteur_demenagement">
				<option value="1">Oui</option>
				<option value="2">Non</option>
			</select>
		</div>
	</div>
	<div class="text-center">
		<input type="submit" class="btn btn-secondary btn-form" name="valider_gaz" value="Valider">
	</div>
</form>
