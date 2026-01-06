<div class="ligne-center jaune"></div>
<?php

if(isset($utilisateur["code_postal"]))
{
	$res_ville = getVilleCp($co_pmp, $utilisateur["code_postal"]);
}
else
{
	$res_ville = getVilleCp($co_pmp, $_POST["code_postal"]);
}

if(isset($_GET["mod_email"]))
{
?>
<form class="change-email" method="post">
	<a href="mon_compte.php?type=fioul" class="return" style="color: #0f393a;"><i class="fas fa-chevron-left"></i> Mes informations</a>
	<h3 style="margin-top: 2%;">Modifier mon email</h3>
	<hr class="separe">
	<div class="col-sm-6">
		<label for="mail" class="col-form-label custom-label">
			Nouvelle adresse email *
		</label>
		<input type="email" class="form-control form-lg <?php if(isset($style_mail)) { echo $style_mail;} ?>" name="n_mail" placeholder="Adresse email" value="">
	</div>
	<div class="text-center">
		<input type="submit" class="btn btn-secondary btn-form" name="valider_email" value="Valider">
	</div>
</form>
<?php
}
elseif(isset($_GET["mod_mdp"]))
{
?>
<form method="post" >
	<a href="mon_compte.php?type=fioul" class="return" style="color: #0f393a;"><i class="fas fa-chevron-left"></i> Mes informations</a>
	<h3 style="margin-top: 2%;">Modifier mon mot de passe</h3>
	<hr class="separe">
	<div class="row">
		<div class="col-sm-6">
			<label for="mdp" class="col-form-label custom-label">
				Mot de passe actuel
			</label>
			<input type="password" class="form-control form-lg <?php if(isset($style_mdpa)) { echo $style_mdpa;} ?>" name="mdp" placeholder="Mot de passe actuel" value="">
		</div>
	</div>
	<div class="row" style="margin-top:1%;">
		<div class="col-sm-6">
			<label for="n_mdp" class="col-form-label custom-label">
				Nouveau mot de passe
			</label>
			<input type="password" class="form-control form-lg <?php if(isset($style_mdp)) { echo $style_mdp;} ?>" name="n_mdp" placeholder="Nouveau mot de passe" value="">
		</div>
		<div class="col-sm-6">
			<label for="conf_mdp" class="col-form-label custom-label">
				Confirmation mot de passe
			</label>
			<input type="password" class="form-control form-lg <?php if(isset($style_mdp2)) { echo $style_mdp2;} ?>" name="conf_mdp" placeholder="Confirmation mot de passe" value="">
		</div>
	</div>

	<div class="text-center">
		<input type="submit" class="btn btn-secondary btn-form" name="valider_mdp" value="Valider">
	</div>
</form>
<?php
}
else
{
?>
<div class="information">

<?php
	if(!empty($jjj_users["name"]) && !empty($utilisateur["prenom"]) && !empty($utilisateur["adresse"]) && !empty($utilisateur["ville"]) && !empty($utilisateur["code_postal"]) && !empty($utilisateur["tel_port"]) || !empty($utilisateur["tel_fixe"]) && !empty($utilisateur["prenom"]) && !empty($jjj_users["email"]))
	{
?>
		<p class="center">* Champs obligatoires pour passer commande </p>
<?php
	}
	else
	{
?>
		<p class="center">* Champs obligatoires pour passer commande</p>

		<div class="toast info web-toast" style="margin: 1% 0 2% 10%!important; width:80%!important;">
			<!-- <div class="message-icon info-icon">
				<i class="fas fa-info" style="padding: 22% 35% !important;font-size: 15px!important;"></i>
			</div> -->
			<div class="message-content ">
				<div class="message-type" style="text-align:left;">
					Avertissement
				</div>
				<div class="message" style="text-align:left;">
					Vous devez finir de renseigner vos coordonnées avant de passer une commande de fioul<br>
				</div>
			</div>
		</div>

		<div class="toast info mobile-toast">
			<!-- <div class="message-icon info-icon">
				<i class="fas fa-info" style="padding: 22% 35% !important;font-size: 15px!important;"></i>
			</div> -->
			<div class="message-content ">
				<div class="message-type" style="text-align:left;">
					Avertissement
				</div>
				<div class="message" style="text-align:left;">
					Vous devez finir de renseigner vos coordonnées avant de passer une commande de fioul<br>
				</div>
			</div>
		</div>
<?php
	}
?>
	<h3>Adresse de livraison</h3>
	<hr class="separe">
	<form method="post" class="adresse" id="FormID">
		<div class="row">
			<div class="col-sm-6">
				<label for="nom" class="col-form-label custom-label">
					Nom *
				</label>
				<input type="text" class="form-control form-lg <?php if(isset($style_nom)) { echo $style_nom;} ?>" name="nom" placeholder="Nom" value="<?php if(isset($message)) { if($message == "Erreur") { echo htmlspecialchars($_POST["nom"]); } else { echo htmlspecialchars($jjj_users["name"]); } } else { echo htmlspecialchars($jjj_users["name"]); } ?>">
			</div>
			<div class="col-sm-6">
				<label for="prenom" class="col-form-label custom-label">
					Prénom *
				</label>
				<input type="text" class="form-control form-lg <?php if(isset($style_prenom)) { echo $style_prenom;} ?>" name="prenom" placeholder="Prénom" value="<?php if(isset($message)) { if($message == "Erreur") { echo htmlspecialchars($_POST["prenom"]); } else { echo htmlspecialchars($utilisateur["prenom"]); } } else { echo htmlspecialchars($utilisateur["prenom"]); } ?>">
			</div>
			<div class="col-sm-6">
				<label for="adresse" class="col-form-label custom-label">
					Adresse *
				</label>
				<input type="text" class="form-control form-lg <?php if(isset($style_adresse)) { echo $style_adresse;} ?>" name="adresse" placeholder="Adresse" value="<?php if(isset($message)) { if($message == "Erreur") { echo htmlspecialchars($_POST["adresse"]); } else { echo htmlspecialchars($utilisateur["adresse"]); } } else { echo htmlspecialchars($utilisateur["adresse"]); } ?>">
			</div>
			<div class="col-sm-6">
				<label for="code_postal" class="col-form-label custom-label">
					Code postal *
				</label>
				<input type="text" class="form-control form-lg <?php if(isset($style_code_postal)) { echo $style_code_postal;} ?>" name="code_postal" placeholder="Code postal" value="<?php if(isset($message)) { if($message == "Erreur") { echo $_POST["code_postal"]; } else { echo $utilisateur["code_postal"]; } } else { echo $utilisateur["code_postal"]; } ?>">
			</div>
			<div class="col-sm-6">
				<label for="code_postal_id" class="col-form-label custom-label">
					Commune *
				</label>
				<select class="form-control form-lg code" name="code_postal_id_test" placeholder="code_postal_id_test">
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
					Téléphone 1 *
				</label>
				<input type="text" class="form-control form-lg <?php if(isset($style_tel1)) { echo $style_tel1;} ?>" name="tel1" placeholder="Téléphone 1" value="<?php if(isset($message)) { if($message == "Erreur") { echo htmlspecialchars($_POST["tel1"]); } else { echo htmlspecialchars($utilisateur["tel_fixe"]); } } else { echo htmlspecialchars($utilisateur["tel_fixe"]); } ?>">
			</div>
			<div class="col-sm-6">
				<label for="tel2" class="col-form-label custom-label">
					Téléphone 2
				</label>
<?php
				if(isset($utilisateur["tel_fixe"]) && $utilisateur["tel_fixe"] != "")
				{
?>
				<input type="text" class="form-control form-lg <?php if(isset($style_tel2)) { echo $style_tel2;} ?>" name="tel2" placeholder="Téléphone 2" value="<?php if(isset($message)) { if($message == "Erreur") { echo htmlspecialchars($_POST["tel2"]); } else { echo htmlspecialchars($utilisateur["tel_port"]); } } else { echo htmlspecialchars($utilisateur["tel_port"]); } ?>">
<?php
				}
				else
				{
?>
				<input type="text" class="form-control form-lg <?php if(isset($style_tel2)) { echo $style_tel2;} ?>" name="tel2" placeholder="Téléphone 2" value="<?php if(isset($message)) { if($message == "Erreur") { echo htmlspecialchars($_POST["tel2"]); } else { echo htmlspecialchars($utilisateur["tel_port"]); } } else { echo htmlspecialchars($utilisateur["tel_port"]); } ?>"  disabled="disabled">
<?php
				}
?>
			</div>
			<div class="col-sm-6">
				<label for="tel3" class="col-form-label custom-label">
					Téléphone 3
				</label>
<?php
				if(isset($utilisateur["tel_fixe"]) && $utilisateur["tel_fixe"] != "")
				{
?>
				<input type="text" class="form-control form-lg <?php if(isset($style_tel3)) { echo $style_tel3;} ?>" name="tel3" placeholder="Téléphone 3" value="<?php if(isset($message)) { if($message == "Erreur") { echo htmlspecialchars($_POST["tel3"]); } else { echo htmlspecialchars($utilisateur["tel_3"]); } } else { echo htmlspecialchars($utilisateur["tel_3"]); } ?>">
<?php
				}
				else
				{
?>
				<input type="text" class="form-control form-lg <?php if(isset($style_tel3)) { echo $style_tel3;} ?>" name="tel3" placeholder="Téléphone 3" value="<?php if(isset($message)) { if($message == "Erreur") { echo htmlspecialchars($_POST["tel3"]); } else { echo htmlspecialchars($utilisateur["tel_3"]); } } else { echo htmlspecialchars($utilisateur["tel_3"]); } ?>" disabled="disabled">
<?php
				}
?>
			</div>
			<div class="col-sm-6">
				<label for="mail" class="col-form-label custom-label">
					Adresse email *
				</label>
				<input type="email" class="form-control form-lg " name="mail" placeholder="Adresse email" value="<?php if(isset($_POST["mail"])) { echo htmlspecialchars($_POST["mail"]); } elseif(isset($jjj_users["email"])) { echo htmlspecialchars($jjj_users["email"]); }?>" disabled="disabled">
			</div>
		</div>
		<div class="row" style="margin-top:2%;">
			<div class="col-sm-6 text-center">
				<a href="mon_compte.php?type=fioul&mod_mdp=1" class="btn btn-primary  responsive-btn" style="width: 60%;font-size: 15px;"> Modifier mon mot de passe</a>

			</div>
			<div class="col-sm-6 text-center">
				<a href="mon_compte.php?type=fioul&mod_email=1" class="btn btn-primary show-email responsive-btn" style="width: 60%;font-size: 15px;"> Modifier mon email</a>

			</div>
		</div>
		<div class="text-center">
			<input type="submit" class="btn btn-secondary btn-form" name="valider_coordonnees" value="Valider">
		</div>

		<h3>Autres informations</h3>
		<hr class="separe">

		<div class="row">
			<div class="col-sm-6">
				<label for="com_user" class="col-form-label custom-label">
					Fournisseur actuel
				</label>
				<input type="text" class="form-control form-lg <?php if(isset($style_com_user)) { echo $style_com_user;} ?>" name="com_user" placeholder="Fournisseur actuel" value="<?php if(isset($message)) { if($message == "Erreur") { echo htmlspecialchars($_POST["com_user"]); } else { echo htmlspecialchars($utilisateur["com_user"]); } } else { echo htmlspecialchars($utilisateur["com_user"]); } ?>">
			</div>
			<div class="col-sm-6">
				<label for="com2_user" class="col-form-label custom-label">
					Comment nous avez vous trouvé
				</label>
				<input type="text" class="form-control form-lg <?php if(isset($style_com2_user)) { echo $style_com2_user;} ?>" name="com2_user" placeholder="Comment nous avez vous trouvé" value="<?php if(isset($message)) { if($message == "Erreur") { echo htmlspecialchars($_POST["com2_user"]); } else { echo htmlspecialchars($utilisateur["com2_user"]); } } else { echo htmlspecialchars($utilisateur["com2_user"]); } ?>">
			</div>
		</div>
		<div class="text-center">
			<input type="submit" class="btn btn-secondary btn-form" name="valider_coordonnees" value="Valider">
		</div>

	</form>
</div>
<?php
}
?>
