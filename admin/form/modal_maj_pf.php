<?php
if(isset($_GET["maj_pf"]))
{
	include_once __DIR__ . "/../../inc/pf_co_connect.php";
	include_once __DIR__ . "/../inc/pf_inc_fonctions_mail_pf.php";

	$res_maj = getMajPF($co_pf);
?>
<div class="modal fade" id="MajPF" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" style="max-width: 45%;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Saisie des prix pour les mises à jour PF</h5>
				<button type="button" class="btn-close b-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fas fa-times"></i> </button>
			</div>
			<div class="modal-body" style="text-align: left;">
				<div class="row">
					<div class="col-sm-10">
						<div class="tableau" style="height: 250px;">
							<table class="table">
								<thead>
									<tr>
										<th>Information</th>
										<th class="text-center">Valeur</th>
										<th>Date</th>
									</tr>
								</thead>
								<tbody>
<?php
								$i = 0;
								while($maj = mysqli_fetch_array($res_maj))
								{
?>
									<tr>
										<input type="hidden" name="code_<?php print $i++; ?>" value="<?= $maj["code"]; ?>">
										<td><?= $maj["libelle"]; ?></td>
										<td class="text-center" style="width: 140px;padding: 0;"><input type="text" class="form-control" name="maj_valeur_<?= $maj['code']; ?>" value="<?= $maj["valeur"]; ?>" style="text-align:center"> </td>
										<td><?= $maj["date"]; ?></td>
									</tr>
<?php
								}
?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-sm-2" style="padding-left: 0;margin-top: 20px;">
						<a href="https://www.zonebourse.com/cours/matiere-premiere/BRENT-OIL-4948/" class="btn btn-secondary" target="_blank" style="width:100%;">BRENT</a>
						<a href="https://www.boursorama.com/bourse/devises/taux-de-change-dollar-euro-USD-EUR/" class="btn btn-secondary" target="_blank" style="width:100%;margin: 10px 0;">USD / EUR</a>
						<a href="https://www.boursorama.com/bourse/matieres-premieres/cours/8xWBS/" class="btn btn-secondary" target="_blank" style="width:100%">WTI</a>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="nb_maj" class="nb_maj" value="<?php print $i; ?>">
				<button type="button" class="btn btn-secondary b-close" data-bs-dismiss="modal">Fermer</button>
				<input type="submit" name="maj_pf" class="btn btn-primary" value="Mettre à jour">
			</div>
		</div>
	</div>
</div>
<?php
}
?>
