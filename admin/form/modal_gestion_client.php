<label for="client_traite" class="col-form-label">
	<input type="checkbox" name="client_traite" id="client_traite" class="switch value check">
	Client Traité
</label>
<div class="row">
	<div class="col-sm-2 align-self-end" style="max-width: 12%;">
		<div class="form-inline">
			<label for="code_client" class="col-sm-4 col-form-label" style="padding-left:0;">Code</label>
			<div class="col-sm-8" style="padding:0">
				<input type="text" name="code_client" class="form-control" style="width:100%;" value="" disabled="disabled">
			</div>
		</div>
	</div>
	<div class="col-sm-2 align-self-end">
		<div class="form-inline">
			<label for="nom" class="col-sm-3 col-form-label" style="padding-left:0;">Nom</label>
			<div class="col-sm-9" style="padding:0">
				<input type="text" name="nom" class="form-control" style="width:100%;" value="">
			</div>
		</div>
	</div>
	<div class="col-sm-2 align-self-end">
		<div class="form-inline">
			<label for="prenom" class="col-sm-4 col-form-label" style="padding-left:0;">Prénom</label>
			<div class="col-sm-8" style="padding:0">
				<input type="text" name="prenom" class="form-control" style="width:100%;" value="">
			</div>
		</div>
	</div>
	<div class="col-sm-2 align-self-end">
		<div class="form-inline">
			<label for="internet" class="col-sm-4 col-form-label" style="padding-left:0;">Internet</label>
			<div class="col-sm-8" style="padding:0">
				<input type="text" name="internet" class="form-control" style="width:100%;" value="" disabled="disabled">
			</div>
		</div>
	</div>
	<div class="col-sm-2 align-self-end" style="max-width: 12%;">
		<select class="form-control" name="statut_client" style="width:100%;font-size: 12px;">
			<option value="0">0 - Inactif</option>
			<option value="1">1 - A relancer</option>
			<option value="2">2 - Actif</option>
			<option value="3">3 - Actif Site</option>
			<option value="4">4 - Ancien actif</option>
			<option value="5">5 - Inactif relancé</option>
		</select>
	</div>
	<div class="col-sm-2" style="max-width: 13%;border-left: 1px solid #0b242436;">
		<label for="date_insc" class="col-form-label" style="padding-left:0;">Date inscription</label>
		<input type="date" name="date_insc" class="form-control" style="width:100%;" value="" disabled="disabled">
	</div>
	<div class="col-sm-2" style="max-width: 13%;">
		<label for="date_co" class="col-form-label" style="padding-left:0;">Date dernière co</label>
		<input type="date" name="date_co" class="form-control" style="width:100%;" value="" disabled="disabled">
	</div>
</div>
<div class="row" style="margin-top: 0.5%;">
	<div class="col-sm-4 align-self-end">
		<div class="form-inline">
			<label for="adresse" class="col-sm-2 col-form-label" style="padding-left:0;">Adresse</label>
			<div class="col-sm-10" style="padding:0">
				<input type="text" name="adresse" class="form-control" style="width:100%;" >
			</div>
		</div>
	</div>
	<div class="col-sm-2 align-self-end">
		<div class="form-inline">
			<label for="cp" class="col-sm-6 col-form-label" style="padding-left:0;">Code Postal</label>
			<div class="col-sm-6" style="padding:0">
				<input type="text" name="cp" class="form-control" style="width:100%;" >
			</div>
		</div>
	</div>
	<div class="col-sm-3 align-self-end">
		<div class="form-inline">
			<label for="ville" class="col-sm-2 col-form-label" style="padding-left:0;">Ville</label>
			<div class="col-sm-10" style="padding:0">
				<select class="form-control ville" name="ville" style="width:100%;font-size: 12px;">
<?php
				if(isset($res_ville))
				{
					while ($ville = mysqli_fetch_array($res_ville))
					{
?>
					<option value="<?= $ville["id"]; ?>"><?= $ville["ville"]; ?></option>
<?php
					}
				}
?>
				</select>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-2 align-self-end">
		<div class="form-inline">
			<label for="tel_1" class="col-sm-3 col-form-label" style="padding-left:0;">Tel 1</label>
			<div class="col-sm-9" style="padding:0">
				<input type="text" name="tel_1" class="form-control" style="width:100%;">
			</div>
		</div>
	</div>
	<div class="col-sm-2 align-self-end">
		<div class="form-inline">
			<label for="tel_2" class="col-sm-3 col-form-label" style="padding-left:0;">Tel 2</label>
			<div class="col-sm-9" style="padding:0">
				<input type="text" name="tel_2" class="form-control" style="width:100%;">
			</div>
		</div>
	</div>
	<div class="col-sm-2 align-self-end">
		<div class="form-inline">
			<label for="tel_3" class="col-sm-3 col-form-label" style="padding-left:0;">Tel 3</label>
			<div class="col-sm-9" style="padding:0">
				<input type="text" name="tel_3" class="form-control" style="width:108%;">
			</div>
		</div>
	</div>
</div>
<div class="row" style="margin-top: -2.4%;">
	<div class="col-sm-3 align-self-end">
		<div class="form-inline">
			<label for="mail" class="col-sm-2 col-form-label" style="padding-left:0;">Email</label>
			<div class="col-sm-10" style="padding:0">
				<input type="mail" name="mail" class="form-control" style="width:100%;" >
			</div>
		</div>
	</div>
	<div class="col-sm-3 align-self-end">
		<div class="form-inline">
			<label for="ville_h" class="col-sm-4 col-form-label" style="padding-left:0;max-width: 28%;"  >Ville (Histo)</label>
			<div class="col-sm-8" style="padding:0">
				<input type="text" name="ville_h" class="form-control" disabled="disabled" style="width:100%;">
			</div>
		</div>
	</div>
	<div class="col-sm-2">
		<label for="coord" class="col-form-label" style="padding-left:0;">Coordonnées géographique</label>
		<div class="form-inline">
			<label for="lat" class="col-sm-2 col-form-label" style="padding-left:0;">Lat</label>
			<div class="col-sm-10" style="padding:0">
				<input type="text" name="lat" class="form-control" style="width:100%;" disabled="disabled">
			</div>
		</div>
	</div>
	<div class="col-sm-2 align-self-end">
		<div class="form-inline">
			<label for="long" class="col-sm-3 col-form-label" style="padding-left:0;">Long</label>
			<div class="col-sm-9" style="padding:0">
				<input type="text" name="long" class="form-control" style="width:100%;" disabled="disabled">
			</div>
		</div>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-sm-6">
		<label for="four_com" class="col-form-label" style="padding-left:0;">Fournisseur actuel ou commentaires</label>
		<textarea name="four_com" rows="3" class="form-control" style="width: 100%;height: auto;" ></textarea>
	</div>
	<div class="col-sm-6">
		<label for="four_def" class="col-form-label" style="padding-left:0;">Fournisseur définit sur secteur</label>
		<textarea name="four_def" rows="3" class="form-control" style="width: 100%;height: auto;" value="" disabled="disabled">
		</textarea>
	</div>
</div>
<div class="row" style="margin-top: 0.5%;">
	<div class="col-sm-4">
		<label for="cm_crm" class="col-form-label" style="padding-left:0;">Commentaire CRM (préciser date + trigramme)</label>
	</div>
	<div class="col-sm-8">
		<div class="form-inline">
			<label for="bloquer_mail" class="col-sm-4 col-form-label">
				<input type="checkbox" name="bloquer_mail" id="bloquemail" class="switch value check" >
				Bloquer les mails au clients jusqu'au
			</label>
			<div class="col-sm-2" style="padding:0">
				<input type="date" name="date_bloque" class="form-control" style="width:100%;" >
			</div>
			<div class="col-sm-6" style="padding:0">
				<label for="date_bloque" class="col-form-label" style="padding-left:0;margin-left: 3%;">(Si date non renseignée, mail toujours bloqué)</label>
			</div>
		</div>
	</div>
</div>
<textarea name="cm_crm" rows="3" class="form-control" style="width: 100%;height: auto;"></textarea>
