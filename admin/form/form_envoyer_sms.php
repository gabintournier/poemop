<?php
include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/../inc/pmp_inc_fonctions_mail.php";
?>
<label class="label-title" style="margin: 0;">Choix du SMS</label>
<div class="ligne" style="width: 2%;"></div>
<div class="form-inline">
	<label for="automatisation" class="col-sm-3 col-form-label" style="padding-left:0;max-width: 19%;">Choisissez le type de SMS à envoyer</label>
	<div class="col-sm-3" style="padding:0">
		<select class="form-control" name="automatisation" style="width:100%">
			<option value=""></option>
		</select>
	</div>
</div>
<hr>
<label class="label-title" style="margin: 0;">SMS</label>
<div class="ligne" style="width: 2%;"></div>
<div class="row">
	<div class="col-sm-5">
		<div class="form-inline">
			<label for="numero" class="col-sm-4 col-form-label" style="padding-left:0;">Numéro destinataire</label>
			<div class="col-sm-8" style="padding:0">
				<input type="text" id="numero" name="numero" class="form-control" value="" style="width:100%;">
			</div>
		</div>
	</div>
	<div class="col-sm-4 align-self-center">
		<div class="form-inline">
			<input type="submit" name="sauvegarder" value="Sauvegarder SMS type sous le nom" class="col-sm-7 btn btn-secondary" style="width:100%;font-size: 14px;">
			<div class="col-sm-5" style="padding-right:0">
				<input type="text" id="" name="" class="form-control" value="" style="width:100%;">
			</div>
		</div>
	</div>
	<div class="col-sm-3 align-self-center">
		<input type="submit" name="supprimer" value="Supprimer SMS type" class="col-sm-7 btn btn-warning" style="width:100%;font-size: 14px;">
	</div>
</div>
<label for="message" class="col-form-label" style="padding-left:0;">Message</label>
<textarea name="message" class="form-control" id="message" rows="5" style="height:auto;"></textarea>
<div class="row" style="margin-top:0.7%;">
	<div class="col-sm-1">
		<label for="priorite" class="col-form-label" style="padding-left:0;">Priorité</label>
	</div>
	<div class="col-sm-1">
		<label for="priorite_1" class="col-form-label"><input type="radio" id="priorite_1" name="priorite" value="1">Lente</label><br>
	</div>
	<div class="col-sm-1">
		<label for="priorite_2" class="col-form-label"><input type="radio" id="priorite_2" name="priorite" value="1">Normal</label><br>
	</div>
	<div class="col-sm-1">
		<label for="priorite_3" class="col-form-label"><input type="radio" id="priorite_3" name="priorite" value="1">Haute</label><br>
	</div>
</div>
<div class="text-right">
	<input type="submit" name="envoyer" value="ENVOYER SMS" class="btn btn-primary">
</div>
