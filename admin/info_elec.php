<div class="row">
	<div class="col-sm-2" style="max-width: 13%;">
		<div class="form-inline">
			<label for="code_elec" class="col-sm-4 col-form-label" style="padding-left:0;">Code</label>
			<div class="col-sm-8" style="padding:0">
				<input type="text" name="code_elec" class="form-control" style="width:100%;" value="" disabled="disabled">
			</div>
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-inline">
			<label for="nom" class="col-sm-3 col-form-label" style="padding-left:0;">Nom</label>
			<div class="col-sm-9" style="padding:0">
				<input type="text" name="nom" class="form-control" style="width:100%;" value="" disabled="disabled">
			</div>
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-inline">
			<label for="prenom" class="col-sm-4 col-form-label" style="padding-left:0;">Prénom</label>
			<div class="col-sm-8" style="padding:0;padding-left: 5%;">
				<input type="text" name="prenom" class="form-control" style="width:100%;" value="" disabled="disabled">
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-inline">
			<label for="internet" class="col-sm-5 col-form-label" style="padding-left:0;">Nom internet</label>
			<div class="col-sm-7" style="padding:0">
				<input type="text" name="internet" class="form-control" style="width:100%;" value="" disabled="disabled">
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-inline">
			<label for="etat" class="col-sm-2 col-form-label" style="padding-left:0;">Etat</label>
			<div class="col-sm-10" style="padding:0">
				<select class="form-control" name="etat"  style="width:100%;">
					<option value=""></option>
					<option value="1">Non relancé</option>
					<option value="2">Msg vocal</option>
					<option value="3">Relancé</option>
					<option value="4">Sans suite</option>
					<option value="5">En attente Mint</option>
					<option value="6">Professionel</option>
					<option value="7">ELD</option>
					<option value="8">Confirmé</option>
					<option value="9">Déjà Mint</option>
					<option value="10">Facturé</option>
					<option value="11">Confirmé Perdu</option>
				</select>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-2" style="max-width: 13%;">
		<div class="form-inline">
			<label for="code_postal" class="col-sm-4 col-form-label" style="padding-left:0;">CP</label>
			<div class="col-sm-8" style="padding:0">
				<input type="text" name="code_postal" class="form-control" style="width:100%;" value="" disabled="disabled">
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-inline">
			<label for="ville" class="col-sm-2 col-form-label" style="padding-left:0;">Ville</label>
			<div class="col-sm-10" style="padding:0">
				<input type="text" name="ville" class="form-control" style="width:100%;" value="" disabled="disabled">
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-inline">
			<label for="adresse" class="col-sm-2 col-form-label" style="padding-left:0;">Adresse</label>
			<div class="col-sm-10" style="padding:0">
				<input type="text" name="adresse" class="form-control" style="width:100%;" value="" disabled="disabled">
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-inline">
			<label for="email" class="col-sm-2 col-form-label" style="padding-left:0;">Mail</label>
			<div class="col-sm-10" style="padding:0">
				<input type="mail" name="email" class="form-control" style="width:100%;" value="" disabled="disabled">
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-3" style="max-width: 22%;">
		<div class="form-inline">
			<label for="tel_1" class="col-sm-3 col-form-label" style="padding-left:0;">Tel 1</label>
			<div class="col-sm-9" style="padding:0">
				<input type="text" name="tel_1" class="form-control" style="width:100%;" value="" disabled="disabled">
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-inline">
			<label for="tel_2" class="col-sm-3 col-form-label" style="padding-left:0;">Tel 2</label>
			<div class="col-sm-9" style="padding:0">
				<input type="text" name="tel_2" class="form-control" style="width:100%;" value="" disabled="disabled">
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-inline">
			<label for="tel_3" class="col-sm-3 col-form-label" style="padding-left:0;">Tel 3</label>
			<div class="col-sm-9" style="padding:0">
				<input type="text" name="tel_3" class="form-control" style="width:100%;" value="" disabled="disabled">
			</div>
		</div>
	</div>
	<div class="col-sm-2" style="max-width: 12%;">
		<label for="mail_ko" class="col-form-label">
			<input type="checkbox" name="mail_ko" id="mail_ko" class="switch value check">
			Mail KO
		</label>
	</div>
	<div class="col-sm-2" style="max-width: 12%;">
		<label for="stop_mail" class="col-form-label">
			<input type="checkbox" name="stop_mail" id="stop_mail" class="switch value check">
			Stop Mail
		</label>
	</div>

</div>
<hr>
<div class="row">
	<div class="col-sm-6">
		<label class="label-title" style="margin: 0;">Habitation</label>
		<div class="ligne" style="width: 4.5%;"></div>
		<div class="row" style="margin-top: 1%;">
			<div class="col-sm-6">
				<div class="form-inline">
					<input type="text" name="habitation" class="form-control col-sm-7" style="width:100%;" value="" disabled="disabled">
					<div class="col-sm-3" style="padding:0">
						<input type="text" name="habitation_m" class="form-control" style="width:100%;margin-left: 20%;" value="" disabled="disabled">
					</div>
					<div class="col-sm-2" style="padding-left: 10%;">
						<label for="habitation_m" class="col-sm-3 col-form-label" style="padding-left:0;">m²</label>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-inline">
					<label for="occupant" class="col-sm-4 col-form-label" style="padding-left:0;">Occupant</label>
					<div class="col-sm-8" style="padding:0">
						<input type="text" name="occupant" class="form-control" style="width:100%;" value="" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-inline">
					<label for="presence" class="col-sm-4 col-form-label" style="padding-left:0;">Presence</label>
					<div class="col-sm-8" style="padding:0">
						<input type="text" name="presence" class="form-control" style="width:100%;" value="" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-inline">
					<label for="chauffage" class="col-sm-4 col-form-label" style="padding-left:0;">Chauffage</label>
					<div class="col-sm-8" style="padding:0">
						<input type="text" name="chauffage" class="form-control" style="width:100%;" value="" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-inline">
					<label for="eau" class="col-sm-2 col-form-label" style="padding-left:0;">Eau</label>
					<div class="col-sm-10" style="padding:0">
						<input type="text" name="eau" class="form-control" style="width:100%;" value="" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-inline">
					<label for="cuisson" class="col-sm-4 col-form-label" style="padding-left:0;">Cuisson</label>
					<div class="col-sm-8" style="padding:0">
						<input type="text" name="cuisson" class="form-control" style="width:100%;" value="" disabled="disabled">
					</div>
				</div>
			</div>
		</div>
		<hr>
		<label class="label-title" style="margin: 0;">Compteur</label>
		<div class="ligne" style="width: 4.5%;"></div>
		<div class="row">
			<div class="col-sm-3">
				<div class="form-inline">
					<label for="presence" class="col-sm-8 col-form-label" style="padding-left:0;">Puissance</label>
					<div class="col-sm-4" style="padding:0">
						<input type="text" name="presence" class="form-control" style="width:100%;" value="" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-inline">
					<label for="conso" class="col-sm-5 col-form-label" style="padding-left:0;">Conso</label>
					<div class="col-sm-7" style="padding:0">
						<input type="text" name="conso" class="form-control" style="width:100%;" value="" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-inline">
					<label for="fournisseur" class="col-sm-4 col-form-label" style="padding-left:0;">Fournisseur</label>
					<div class="col-sm-8" style="padding:0">
						<input type="text" name="fournisseur" class="form-control" style="width:100%;" value="" disabled="disabled">
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-inline">
					<label for="pdl" class="col-sm-2 col-form-label" style="padding-left:0;">PDL</label>
					<div class="col-sm-10" style="padding:0">
						<input type="text" name="pdl" class="form-control" style="width:100%;" value="" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-inline">
					<label for="linky" class="col-sm-5 col-form-label" style="padding-left:0;">Linky</label>
					<div class="col-sm-7" style="padding:0">
						<input type="text" name="linky" class="form-control" style="width:100%;" value="" disabled="disabled">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<textarea rows="13" class="form-control" style="width: 100%;height: auto;" value=""></textarea>
	</div>
</div>
<hr>
<div class="tableau" style="height: 200px;">
	<table class="table">
		<thead>
			<tr>
				<th>Ref</th>
				<th>Statut</th>
				<th>Date Mint</th>
				<th>Date Import</th>
				<th>Tel</th>
				<th>PDL</th>
			</tr>
		</thead>
	</table>
</div>
