<div class="modal fade" id="exportXls" tabindex="-1" aria-labelledby="exportXlsLabel" aria-hidden="true"
	data-group-id="<?= isset($id_grp) ? (int) $id_grp : ''; ?>"
	data-group-label="<?= htmlspecialchars($grp['libelle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
	<div class="modal-dialog modal-dialog-centered" style="max-width: 45%;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exportXlsLabel">Critère d'export Excel des informations du groupement</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<i class="fas fa-times"></i>
				</button>
			</div>
			<div class="modal-body" style="text-align: left;">
				<label class="label-title" style="margin: 0;">Statuts des commandes à exporter</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-6">
						<label for="statut_1_export" class="col-sm-4 col-form-label" style="padding-left:0;">À partir du statut </label>
						<select class="form-control" name="statut_1_export">
							<option value="10">Utilisateur</option>
							<option value="12">Attachée</option>
							<option value="13">Proposée</option>
							<option value="15">Groupée</option>
							<option value="17">Prix proposé</option>
							<option value="20">Prix validé</option>
							<option value="25">Livrable</option>
							<option value="30">Livrée</option>
							<option value="40">Terminée</option>
							<option value="50">Annulée</option>
							<option value="52">Annulée / Livraison</option>
							<option value="55">Annulée / Prix</option>
						</select>
					</div>
					<div class="col-sm-6">
						<label for="statut_2_export" class="col-sm-4 col-form-label" style="padding-left:0;">Jusqu'au statut </label>
						<select class="form-control" name="statut_2_export">
							<option value="0"></option>
							<option value="10">Utilisateur</option>
							<option value="12">Attachée</option>
							<option value="13">Proposée</option>
							<option value="15">Groupée</option>
							<option value="17">Prix proposé</option>
							<option value="20">Prix validé</option>
							<option value="25">Livrable</option>
							<option value="30">Livrée</option>
							<option value="40">Terminée</option>
							<option value="50">Annulée</option>
							<option value="52">Annulée / Livraison</option>
							<option value="55">Annulée / Prix</option>
						</select>
					</div>
				</div>
				<hr>
				<label class="label-title" style="margin: 0;">Colonnes exportées</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-2" style="margin-top: 1%;">
						<label for="nom_prenom" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="nom_prenom" class="switch value check" checked="checked" style="width: 14px;">
							Nom Prénom
						</label><br>
						<label for="type_fioul" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="type_fioul" class="switch value check" checked="checked" style="width: 14px;">
							Type Fioul
						</label>
					</div>
					<div class="col-sm-2" style="margin-top: 1%;">
						<label for="adresse" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="adresse" class="switch value check" checked="checked" style="width: 14px;">
							Adresse
						</label><br>
						<label for="quantite" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="quantite" class="switch value check" checked="checked" style="width: 14px;">
							Quantité
						</label>
					</div>
					<div class="col-sm-2" style="margin-top: 1%;">
						<label for="cp_ville" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="cp_ville" class="switch value check" checked="checked" style="width: 14px;">
							CP Ville
						</label><br>
						<label for="les_prix" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="les_prix" class="switch value check" checked="checked" style="width: 14px;">
							Les prix
						</label>
					</div>
					<div class="col-sm-2" style="margin-top: 1%;">
						<label for="tel" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="tel" class="switch value check" checked="checked" style="width: 14px;">
							Les Tél.
						</label><br>
						<label for="commentaire_cmd" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="commentaire_cmd" class="switch value check" checked="checked" style="width: 14px;">
							Commentaire
						</label>
					</div>
					<div class="col-sm-2" style="margin-top: 1%;">
						<label for="date" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="date" class="switch value check" style="width: 14px;">
							Date
						</label><br>
						<label for="quantite_livree" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="quantite_livree" class="switch value check" style="width: 14px;">
							Qté Livrée
						</label>
					</div>
					<div class="col-sm-2" style="margin-top: 1%;">
						<label for="mail" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="mail" class="switch value check" style="width: 14px;">
							Mail
						</label><br>
						<label for="statut_exp" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="statut_exp" class="switch value check" style="width: 14px;">
							Statut
						</label>
					</div>
					<div class="col-sm-3">
						<label for="aspiration_prix" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="aspiration_prix" class="switch value check" style="width: 14px;">
							Les Prix AF FM FR
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
				<button type="button" id="triggerExportXls" class="btn btn-primary">Exporter XLS</button>
			</div>
		</div>
	</div>
</div>
