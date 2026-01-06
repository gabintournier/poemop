<?php
if(isset($_GET["id_grp"]))
{
	$res_cmd_affecter = getCommandesAffecterGroupement($co_pmp, $_GET["id_grp"]);
}

?>
<div class="modal fade" id="commandesAffecter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" style="max-width: 60%;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Commandes à affecter</h5>
				<button type="button" class="btn-close b-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fas fa-times"></i> </button>
			</div>
			<div class="modal-body" style="text-align: left;">
				<label class="label-title" style="margin: 0;">Liste des commandes</label>
				<div class="ligne"></div>
				<label class="col-form-label" style="padding-left:0;display: block;margin-top: 2px;color: #0b2424a6;"><i class="far fa-arrow-from-left"></i> Liste des commandes à affeceter pour le groupement <?= $_GET["id_grp"]; ?></label>
				<hr>
				<div class="tableau">
					<table class="table">
						<thead>
							<th><i class="fal fa-sort"></i></th>
							<th style="padding: 8px 10px;">Select</th>
							<th style="padding: 8px 10px;width: 65px;">Nb&nbsp;Litre</th>
							<th style="padding: 8px 10px;width: 40px;" class="text-center">Type</th>
							<th style="padding: 8px 10px;width: 65px;">Date</th>
							<th style="padding: 8px 10px;width: 60px;" class="text-center">CP</th>
							<th style="padding: 8px 10px;width: 250px;">Ville</th>
							<th style="padding: 8px 10px;width: 130px;">Nom</th>
							<th style="padding: 8px 10px;width: 130px;">Prénom</th>
							<th style="padding: 8px 10px;width: 130px;">Etat</th>
						</thead>
						<tbody>
<?php
						$i = 0;
						while($commande = mysqli_fetch_array($res_cmd_affecter))
						{
							if($commande["cmd_status"] == 10) { $status = " 10 - Utilisateur"; }
							if($commande["cmd_status"] == 12) { $status = " 12 - Attaché"; }

							if ($commande["cmd_typefuel"] == 1){ $type = 'O';}
							if ($commande["cmd_typefuel"] == 2){ $type = 'S';}
?>
							<tr>
								<td></td>
								<input type="hidden" name="cmd_id[]" value="<?= $commande['id']; ?>">
								<input type="hidden" name="cmd_qte[]" value="<?= $commande["cmd_qte"]; ?>">
								<td><input type="checkbox" name="select_cmd_<?php print $i++; ?>[]" id="select_cmd"  checked="checked" class="switch value check" style="background: #ddddddd1;"></td>
								<td><?= $commande["cmd_qte"]; ?></td>
								<td class="text-center"><?= $type; ?></td>
								<td><?= date_format(new DateTime($commande['cmd_dt']), 'd/m/Y' ); ?></td>
								<td class="text-center"><?= $commande["code_postal"]; ?></td>
								<td><?= $commande["ville"]; ?></td>
								<td><?= $commande["name"]; ?></td>
								<td><?= $commande["prenom"]; ?></td>
								<td><?= $status; ?></td>
							</tr>
<?php
						}
?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="nb_cmd" value="<?php print $i; ?>">
				<button type="button" class="btn btn-secondary b-close" data-bs-dismiss="modal">Fermer</button>
				<input type="submit" name="valider_commandes_affecter" class="btn btn-primary" value="AFFECTER">
			</div>
		</div>
	</div>
</div>
