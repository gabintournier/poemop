<div class="modal fade" id="Histo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" style="max-width: 65%;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Historique du groupement</h5>
				<button type="button" class="btn-close b-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fas fa-times"></i> </button>
			</div>
			<div class="modal-body">
				<label for="com_histo" class="col-form-label col-sm-5" style="padding-left:0;">Commentaire pour historique</label>
				<input type="text" name="com_histo" class="form-control" style="width:100%;">
				<div class="tableau">
					<table class="table">
						<thead>
							<tr>
								<th></th>
								<th>Date</th>
								<th>Qui</th>
								<th>Action</th>
								<th>Commentaire</th>
							</tr>
						</thead>
						<tbody>
<?php
						if(isset($res_histo))
						{
							while ($histo = mysqli_fetch_array($res_histo))
							{
								$valeur = str_replace( array('\r', '\n', '\\'), ' ', $histo["hisg_valeur"]);
								if($valeur != ' ')
								{
?>
							<tr>
								<td><i class="fas fa-arrow-right"></i></td>
								<td><?= $histo["hisg_date"]; ?></td>
								<td><?= $histo["hisg_intervenant"]; ?></td>
								<td><?= $histo["hisg_action"]; ?></td>
								<td><?= $valeur; ?></td>
							</tr>
<?php
								}
							}
						}
?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary b-close" data-bs-dismiss="modal">Fermer</button>
				<input type="submit" name="add_histo_ok" value="OK" class="btn btn-primary">
				<input type="submit" name="add_histo" class="btn btn-primary b-close" value="OK / Sortie">
			</div>
		</div>
	</div>
</div>
