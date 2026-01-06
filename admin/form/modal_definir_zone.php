<div class="modal fade" id="definirZone" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" style="max-width: 40%;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Zones pour le groupement en cours</h5>
				<button type="button" class="btn-close b-close" data-bs-dismiss="modal" aria-bs-label="Close"> <i class="fas fa-times"></i> </button>
			</div>
			<div class="modal-body" style="text-align: left;">
				<label class="label-title" style="margin: 0;">Zone à inclure</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-5">
						<div class="form-inline">
							<label for="fournisseur_ajax" class="col-sm-4 col-form-label" style="padding-left:0;">Fournisseur </label>
							<div class="col-sm-8" style="padding:0">
								<select class="form-control ajax" name="fournisseur_ajax" style="width:100%;">
									<option value=""></option>
<?php
									$res_four = getFournisseursListe($co_pmp);
									while ($four = mysqli_fetch_array($res_four))
									{
?>
									<option value="<?= $four["id"]; ?>" <?php if(isset($_POST["fournisseur_id"])) { if($_POST['fournisseur_id'] == $four["id"]){ echo "selected='selected'"; } } ?>><?= $four["nom"]; ?></option>
<?php
									}
?>
								</select>
							</div>
							<!-- <label for="fournisseur_zone_groupement" class="col-sm-4 col-form-label" style="padding-left:0;">Fournisseur </label>
							<div class="col-sm-8" style="padding:0">
								<select class="form-control fournisseur-zone" name="fournisseur_zone_groupement" style="width:100%;">
									<option value=""></option>
<?php
									$res_four = getFournisseursListe($co_pmp);
									while ($four = mysqli_fetch_array($res_four))
									{
?>
									<option value="<?= $four["id"]; ?>" <?php if(isset($_POST["fournisseur_id"])) { if($_POST['fournisseur_id'] == $four["id"]){ echo "selected='selected'"; } } ?>><?= $four["nom"]; ?></option>
<?php
									}
?>
								</select>
							</div>
							<input type="hidden" name="fournisseur_id" class="fournisseur_id" value="<?php if(isset($_POST["fournisseur_id"])) { echo $_POST["fournisseur_id"]; } ?>"> -->
						</div>
					</div>
					<div class="col-sm-5">
						<div class="form-inline">
							<label for="zone_groupement" class="col-sm-2 col-form-label" style="padding-left:0;">Zone</label>
							<div class="col-sm-8" style="padding:0">
								<select class="form-control code" name="zone_groupement" style="width:100%;">
<?php
								if(isset($res_zone))
								{
									while ($zone = mysqli_fetch_array($res_zone))
									{
?>
									<option value="<?= $zone["id"]; ?>"><?= $zone["libelle"]; ?></option>
<?php
									}
								}
?>
								</select>
							</div>
						</div>
					</div>
					<div class="add">
						<input type="submit" class="btn-go" name="definir_zone_grp" value="+" style="margin-right: 10px;border-radius: 8px;padding: 1px 4px;">
					</div>
					<div class="add">
						<input type="hidden" name="supp_grp_zone_id" class="supp_grp_zone_id" value="">
						<input type="submit" class="btn-go" name="supprimer_zone_grp" value="-" style="border-radius: 8px;padding: 1px 4px;">
					</div>
				</div>
				<div class="tableau" style="height: 200px;">
					<table class="table">
						<thead>
							<th>Fournisseur</th>
							<th>Zone</th>
						</thead>
						<tbody>
<?php
						if(isset($res_grp_zone))
						{
							while ($grp_zone = mysqli_fetch_array($res_grp_zone))
							{
?>
							<tr class="select grp_zone">
								<input type="hidden" name="grp_zone_id" value="<?= $grp_zone["id"] ?>">
								<td><?= $grp_zone["nom"] ?></td>
								<td><?= $grp_zone["libelle"] ?></td>
							</tr>
<?php
							}
						}
?>
						</tbody>
					</table>
				</div>
				<hr>
				<label class="label-title" style="margin: 0;">Zone à exclure</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-5">
						<div class="form-inline">
							<label for="" class="col-sm-4 col-form-label" style="padding-left:0;">Fournisseur</label>
							<div class="col-sm-8" style="padding:0">
								<select class="form-control fournisseur-zone2" name="fournisseur_zone_groupement_exclure" style="width:100%;">
									<option value=""></option>
<?php
									$res_four2 = getFournisseursListe($co_pmp);
									while ($four = mysqli_fetch_array($res_four2))
									{
?>
									<option value="<?= $four["id"]; ?>" <?php if(isset($_POST["fournisseur_id_2"])) { if($_POST['fournisseur_id_2'] == $four["id"]){ echo "selected='selected'"; } } ?>><?= $four["nom"]; ?></option>
<?php
									}
?>
								</select>
							</div>
							<input type="hidden" name="fournisseur_id_2" class="fournisseur_id_2" value="<?php if(isset($_POST["fournisseur_id_2"])) { echo $_POST["fournisseur_id_2"]; } ?>">
						</div>
					</div>
					<div class="col-sm-5">
						<div class="form-inline">
							<label for="" class="col-sm-2 col-form-label" style="padding-left:0;">Zone</label>
							<div class="col-sm-8" style="padding:0">
								<select class="form-control" name="zone_groupement_exclure" style="width:100%;">
<?php
								if(isset($res_zone2))
								{
									while ($zone = mysqli_fetch_array($res_zone2))
									{
?>
									<option value="<?= $zone["id"]; ?>"><?= $zone["libelle"]; ?></option>
<?php
									}
								}
?>
								</select>
							</div>
						</div>
					</div>
					<div class="add">
						<input type="submit" class="btn-go" name="definir_zone_grp_exclure" value="+" style="margin-right: 10px;border-radius: 8px;padding: 1px 4px;">
					</div>
					<div class="add">
						<input type="hidden" name="supp_grp_zone_id_exclus" class="supp_grp_zone_id_exclus" value="">
						<input type="submit" class="btn-go" name="supp_zone_grp_exclure" value="-" style="border-radius: 8px;padding: 1px 4px;">
					</div>
				</div>
				<div class="tableau" style="height: 200px;">
					<table class="table">
						<thead>
							<th>Fournisseur</th>
							<th>Zone</th>
						</thead>
						<tbody>
<?php
						if(isset($res_grp_zone_exlus))
						{
							while ($grp_zone = mysqli_fetch_array($res_grp_zone_exlus))
							{
?>
							<tr class="select grp_zone2">
								<input type="hidden" name="grp_zone_id" value="<?= $grp_zone["id"] ?>">
								<td><?= $grp_zone["nom"] ?></td>
								<td><?= $grp_zone["libelle"] ?></td>
							</tr>
<?php
							}
						}
?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary b-close" data-bs-dismiss="modal" aria-bs-label="Close">Fermer</button>
			</div>
		</div>
	</div>
</div>
