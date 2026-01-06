<?php
if(isset($_GET["details_cmd"]))
{
$cmd_details = getCommandeDetailsClients($co_pmp, $_GET["details_cmd"]);
$res_histo = getHistoDetailsCommande($co_pmp, $_GET["details_cmd"]);

$prix_sup = $cmd_details["cmd_prix_sup"] / 1000;
$prix_sup = number_format($prix_sup, 3, '.', '');

$prix_ord = $cmd_details["cmd_prix_ord"] / 1000;
$prix_ord = number_format($prix_ord, 3, '.', '');

if ($cmd_details["cmd_typefuel"] == 1){ $total = $prix_ord * $cmd_details["cmd_qtelivre"]; }
if ($cmd_details["cmd_typefuel"] == 2){ $total = $prix_sup * $cmd_details["cmd_qtelivre"];}


?>
<div class="modal fade" id="detailsCmd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" style="max-width: 65%;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Détails d'une commande</h5>
				<button type="button" class="btn-close fermer-modal" data-bs-dismiss="modal" aria-bs-label="Close"> <i class="fas fa-times"></i> </button>
			</div>
<?php
			if(isset($message))
			{
?>
			<div class="toast <?= $message_type; ?>" style="    margin: 20px 0 5px 15px;">
				<div class="message-icon <?= $message_type; ?>-icon">
					<i class="fas <?= $message_icone; ?>"></i>
				</div>
				<div class="message-content ">
					<div class="message-type">
						<?= $message_titre; ?>
					</div>
					<div class="message">
						<?= $message; ?>
					</div>
				</div>
				<div class="message-close">
					<i class="fas fa-times"></i>
				</div>
			</div>
<?php
			}
?>
			<div class="modal-body">
				<label class="label-title" style="margin: 0;">Commande</label>
				<div class="ligne" style="width: 2.3%"></div>
				<div class="row">
					<div class="col-sm-2">
						<div class="form-inline">
							<label for="n_cmd" class="col-sm-4 col-form-label" style="padding-left:0;padding-right: 0;">N° Cmd</label>
							<div class="col-sm-8">
								<input type="text" name="n_cmd" value="<?= $_GET["details_cmd"]; ?>" class="form-control" style="width: 100%;padding-right: 0;" disabled="disabled">
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-inline">
							<label for="four" class="col-sm-4 col-form-label" style="padding-left:0;">Fournisseur</label>
							<div class="col-sm-8">
								<input type="text" name="four" value="<?= $cmd_details["nom_four"]; ?>" class="form-control" style="width: 100%;" disabled="disabled">
							</div>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="form-inline">
							<label for="n_grp" class="col-sm-2 col-form-label" style="padding-left:0;">Groupement</label>
							<div class="col-sm-3">
								<input type="text" name="n_grp" value="<?= $cmd_details["groupe_cmd"]; ?>" class="form-control" style="width: 100%;" disabled="disabled">
							</div>
							<div class="col-sm-7">
								<input type="text" name="grp" value="<?= $cmd_details["libelle"]; ?>" class="form-control" style="width: 100%;" disabled="disabled">
							</div>
						</div>
					</div>
<?php
					if(isset($cmd_details["four_id"]))
					{
?>
					<div class="col-sm-2 align-self-center">
<?php
						if ($_GET["return"] == 'recherche')
						{
?>
						<a target="_blank" href="details_fournisseur.php?id_four=<?= $cmd_details["four_id"]; ?>&user_id=<?= $_GET["user_id"]; ?>&return=recherche_ancienne_commande" class="btn btn-primary" style="width:100%;">FICHE FOURNISSEUR</a>
<?php
						}
						elseif ($_GET["return"] == 'cmdes')
						{
?>
						<a target="_blank" href="details_fournisseur.php?id_four=<?= $cmd_details["four_id"]; ?>&user_id=<?= $_GET["user_id"]; ?>&return=cmdes" class="btn btn-primary" style="width:100%;">FICHE FOURNISSEUR</a>
<?php
						}
						else
						{
?>
						<a target="_blank" href="details_fournisseur.php?id_four=<?= $cmd_details["four_id"]; ?>" class="btn btn-primary" style="width:100%;">FICHE FOURNISSEUR</a>
<?php
						}
?>
					</div>
<?php
					}
?>

				</div>

				<div class="row" style="margin-top:1%;">
					<div class="col-sm-4" style="border-right: 1px solid #0b242436;">
						<div class="form-inline">
							<label for="qte" class="col-sm-5 col-form-label" style="padding-left:0;padding-right: 0;">Quantité commandée</label>
							<div class="col-sm-3">
								<input type="text" name="qte" value="<?= $cmd_details["cmd_qte"]; ?>" class="form-control" style="width: 100%;padding-right: 0;" disabled="disabled">
							</div>
							<div class="col-sm-2">
								<span>Litres</span>
							</div>
						</div>
						<div class="form-inline">
							<label for="qte" class="col-sm-5 col-form-label" style="padding-left:0;">Quantité livrée</label>
							<div class="col-sm-3">
								<input type="text" name="qte" value="<?= $cmd_details["cmd_qtelivre"]; ?>" class="form-control" style="width: 100%;" disabled="disabled">
							</div>
							<div class="col-sm-2">
								<span>Litres</span>
							</div>
						</div>
					</div>
					<div class="col-sm-4 align-self-center" style="border-right: 1px solid #0b242436;">
						<div class="form-inline">
							<label for="qte" class="col-sm-4 col-form-label" style="padding-left:0;">Prix au litre</label>
							<div class="col-sm-3">
								<input type="text" name="qte" value="<?= $prix_ord; ?>" class="form-control" style="width: 100%;" disabled="disabled">
							</div>
							<div class="col-sm-4">
								<span>Euro / Litre</span>
							</div>
						</div>
						<div class="form-inline">
							<label for="qte" class="col-sm-4 col-form-label" style="padding-left:0;">Prix au litre sup</label>
							<div class="col-sm-3">
								<input type="text" name="qte" value="<?= $prix_sup; ?>" class="form-control" style="width: 100%;" disabled="disabled">
							</div>
							<div class="col-sm-4">
								<span>Euro / Litre</span>
							</div>
						</div>
						<div class="form-inline">
							<label for="qte" class="col-sm-4 col-form-label" style="padding-left:0;">Total</label>
							<div class="col-sm-3">
								<input type="text" name="qte" value="<?= $total; ?>" class="form-control" style="width: 100%;" disabled="disabled">
							</div>
							<div class="col-sm-4">
								<span>Euros</span>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-inline" style="margin: 0 0 -2.5%;">
							<label class="col-sm-4 col-form-label" style="padding-left:0;">Type de fuel</label>
							<div class="col-sm-1" style="padding:0">
								<input type="radio" name="cmd_fioul" id="cmd_fioul_ord" class="switch value check form-control" value="Ordinaire" <?php if(isset($cmd_details['cmd_typefuel'])) { if($cmd_details['cmd_typefuel'] == 1){ echo "checked='checked'"; } } ?>>
							</div>
							<div class="col-sm-4">
								 <label for="cmd_fioul_ord" class="radio">Ordinaire</label>
							</div>
						</div>
						<div class="form-inline" style="margin: 0 0 -2.5%;">
							<label class="col-sm-4 col-form-label" style="padding-left:0; visibility:hidden">Type de fuel</label>
							<div class="col-sm-1" style="padding:0">
								<input type="radio" name="cmd_fioul" id="cmd_fioul_sup" class="switch value check form-control" value="Supérieur" <?php if(isset($cmd_details['cmd_typefuel'])) { if($cmd_details['cmd_typefuel'] == 2){ echo "checked='checked'"; } } ?>>
							</div>
							<div class="col-sm-4">
								 <label for="cmd_fioul_sup" class="radio">Supérieur</label>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2">
						<label for="date" class=" col-form-label" style="padding-left:0;">Date</label>
						<input type="text" name="date" value="<?= $cmd_details["cmd_dt"]; ?>" class="form-control" style="width: 100%;" disabled="disabled">
					</div>
					<div class="col-sm-2">
						<label for="status" class=" col-form-label" style="padding-left:0;">Statut</label>
						<select class="form-control input-custom" name="status">
							<option value="10" <?php if(isset($_GET["details_cmd"])) { if($cmd_details['cmd_status'] == '10'){ echo "selected='selected'"; } } ?>>Utilisateur</option>
							<option value="12" <?php if(isset($_GET["details_cmd"])) { if($cmd_details['cmd_status'] == '12'){ echo "selected='selected'"; } } ?>>Attachée</option>
							<option value="13" <?php if(isset($_GET["details_cmd"])) { if($cmd_details['cmd_status'] == '13'){ echo "selected='selected'"; } } ?>>Proposée</option>
							<option value="15" <?php if(isset($_GET["details_cmd"])) { if($cmd_details['cmd_status'] == '15'){ echo "selected='selected'"; } } ?>>Groupée</option>
							<option value="17" <?php if(isset($_GET["details_cmd"])) { if($cmd_details['cmd_status'] == '17'){ echo "selected='selected'"; } } ?>>Prix proposé</option>
							<option value="20" <?php if(isset($_GET["details_cmd"])) { if($cmd_details['cmd_status'] == '20'){ echo "selected='selected'"; } } ?>>Prix validé</option>
							<option value="25" <?php if(isset($_GET["details_cmd"])) { if($cmd_details['cmd_status'] == '25'){ echo "selected='selected'"; } } ?>>Livrable</option>
							<option value="30" <?php if(isset($_GET["details_cmd"])) { if($cmd_details['cmd_status'] == '30'){ echo "selected='selected'"; } } ?>>Livrée</option>
							<option value="40" <?php if(isset($_GET["details_cmd"])) { if($cmd_details['cmd_status'] == '40'){ echo "selected='selected'"; } } ?>>Terminée</option>
							<option value="50" <?php if(isset($_GET["details_cmd"])) { if($cmd_details['cmd_status'] == '50'){ echo "selected='selected'"; } } ?>>Annulée</option>
							<option value="52" <?php if(isset($_GET["details_cmd"])) { if($cmd_details['cmd_status'] == '52'){ echo "selected='selected'"; } } ?>>Annulée / Livraison</option>
							<option value="55" <?php if(isset($_GET["details_cmd"])) { if($cmd_details['cmd_status'] == '55'){ echo "selected='selected'"; } } ?>>Annulée / Prix</option>
							<option value="99" <?php if(isset($_GET["details_cmd"])) { if($cmd_details['cmd_status'] == '99'){ echo "selected='selected'"; } } ?>>Annulée / Compte désactivé</option>
						</select>
					</div>
				</div>
				<div class="row" style="margin-top:1%;">
					<div class="col-sm-12">
						<label for="com_client" class="col-sm-5 col-form-label" style="padding-left:0;">Commentaire client</label><br>
						<textarea name="com_client" rows="3" class="form-control" style="width: 100%;height: auto;" value=""><?= $cmd_details["cmd_comment"]; ?></textarea>
					</div>
					<div class="col-sm-12">
						<label for="com_client" class="col-sm-5 col-form-label" style="padding-left:0;">Livre d'or</label><br>
						<textarea name="com_client" rows="1" class="form-control" style="width: 100%;height: auto;" value="" disabled="disabled"><?php if(isset($avis["message"])) { echo $avis["message"]; } ?></textarea>
					</div>
				</div>
				<hr>
				<div class="tableau" style="height: 170px;">
					<table class="table">
						<thead>
							<tr>
								<th></th>
								<th>Date</th>
								<th>Qui</th>
								<th>Action</th>
								<th>Valeur</th>
							</tr>
						</thead>
						<tbody>
<?php
						while($histo = mysqli_fetch_array($res_histo))
						{
?>
							<tr>
								<td><i class="fas fa-arrow-right"></i></td>
								<td><?= $histo["his_date"]; ?></td>
								<td><?= $histo["his_intervenant"]; ?></td>
								<td><?= $histo["his_action"]; ?></td>
								<td><?= $histo["his_valeur"]; ?></td>
							</tr>
<?php
						}
?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary fermer-modal" data-bs-dismiss="modal">Fermer</button>
				<input type="submit" name="update_ancienne_cmd" class="btn btn-primary" value="OK">
			</div>
		</div>
	</div>
</div>
<?php
}
?>
