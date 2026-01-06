<?php
if(isset($_GET["utilisateur"]))
{
if(!empty($_POST["chercher_client"]))
{
	$_SESSION["nom_client"] = $_POST["nom_client"];
	$_SESSION["mail_client"] = $_POST["mail_client"];
	$_SESSION["code_client"] = $_POST["code_client"];
	$_SESSION["cp_client"] = $_POST["cp_client"];
	$_SESSION["tel_client"] = $_POST["tel_client"];
	$_SESSION["ville_client"] = $_POST["ville_client"];
	$_SESSION["date_min_insc"] = $_POST["date_min_insc"];
	$_SESSION["date_max_insc"] = $_POST["date_max_insc"];
	$_SESSION["date_min_co"] = $_POST["date_min_co"];
	$_SESSION["date_max_co"] = $_POST["date_max_co"];
	$_SESSION["nouveaux_inscrits"] = isset($_POST["nouveaux_inscrits"])? "1" : "0";
	$_SESSION["groupementEnCours"] = $_GET['id_grp'];
	$res = getClientsFiltresActifs($co_pmp);
	header('Location: ' . $actual_link);
}
elseif (!empty($_POST["vider"]))
{
	unset($_SESSION['nom_client']);
	unset($_SESSION['mail_client']);
	unset($_SESSION['code_client']);
	unset($_SESSION['cp_client']);
	unset($_SESSION['tel_client']);
	unset($_SESSION['ville_client']);
	unset($_SESSION['date_min_insc']);
	unset($_SESSION['date_max_insc']);
	unset($_SESSION['date_min_co']);
	unset($_SESSION['date_max_co']);
	unset($_SESSION['nouveaux_inscrits']);
	unset($_SESSION['fournisseur_ajax']);
	unset($_SESSION['zone']);
	unset($_SESSION['afficher_clients']);
	header('Location: ' . $actual_link);
}
elseif (!empty($_POST["chercher_client_four"]))
{
	$_SESSION["fournisseur_ajax"] = $_POST["fournisseur_ajax"];
	$_SESSION["zone"] = $_POST["zone"];
	$res_zone = getZoneFournisseurId($co_pmp, $_SESSION["fournisseur_ajax"]);
	// $zone_1 = getZoneFournisseurLimitId($co_pmp, $_SESSION["fournisseur_ajax"]);
	// if(isset($_POST["zone"])) { $_SESSION["zone"] = $_POST["zone"]; } else { $_SESSION["zone"] = $zone_1["id"]; }
	$res = getClientFournisseurZone($co_pmp, $_SESSION["zone"]);
	header('Location: ' . $actual_link);
}
elseif (!empty($_POST["afficher_clients"]))
{
	$_SESSION["afficher_clients"] = $_POST["afficher_clients"];
	$res = getClientsListe($co_pmp);
}
elseif (isset($_SESSION["afficher_clients"]))
{
	$res = getClientsListe($co_pmp);
	if(!empty($_POST["exporter_clients"]))
	{
		exporterListeClients($co_pmp, $res);
		$res = getClientsListe($co_pmp);

	}
}
elseif (isset($_SESSION["fournisseur_ajax"]))
{
	$res_zone = getZoneFournisseurId($co_pmp, $_SESSION["fournisseur_ajax"]);
	$res = getClientFournisseurZone($co_pmp, $_SESSION["zone"]);
}
elseif (isset($_SESSION["nom_client"]) || isset($_SESSION["mail_client"]) || isset($_SESSION["code_client"]) || isset($_SESSION["cp_client"]) || isset($_SESSION["tel_client"]) || isset($_SESSION["ville_client"]))
{
	$res = getClientsFiltresActifs($co_pmp);
	if(!empty($_POST["test"]))
	{
		ajouterListeGroupement($co_pmp, $res, $_GET["id_grp"]);
		$res = getClientsFiltresActifs($co_pmp);
	}

	if(!empty($_POST["basculer_utilisateur"]))
	{
		basculerListeUtilisateur($co_pmp, $res, $_GET["id_grp"]);
	}
}


?>
<div class="modal fade" id="rechercheClient" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" style="max-width: 85%;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Recherche d'un client par ... </h5>
				<button type="button" class="btn-close b-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fas fa-times"></i> </button>
			</div>
			<?php
			if (isset($message_modal))
			{
			?>
			<div class="toast <?= $message_type; ?>" style="margin: 18px 17px -2px;text-align: left;width: 625px;">
				<div class="message-icon  <?= $message_type; ?>-icon">
					<i class="fas <?= $message_icone; ?>"></i>
				</div>
				<div class="message-content ">
					<div class="message-type">
						<?= $message_titre; ?>
					</div>
					<div class="message">
						<?= $message_modal; ?>
					</div>
				</div>
				<div class="message-close">
					<i class="fas fa-times"></i>
				</div>
			</div>
			<?php
			}

			if(isset($_GET["message"]) == 'grpt')
			{
?>
			<div class="toast success" style="margin: 18px 17px -2px;text-align: left;width: 807px;border-left: 3px solid #dbc047;">
				<div class="message-content " style="margin-left: 20px;">
					<div class="message-type">
						Attention
					</div>
					<div class="message">
						Certaines commandes sont déjà présentes dans un groupement. Voulez-vous les basculer ?
						<div class="row">
							<div class="col-sm-2">
								<input type="submit" name="basculer_utilisateur" value="BASCULER" class="btn btn-warning" style="margin-top: 11px;padding-left: 25px;padding-right: 25px;">
							</div>
						</div>
					</div>
				</div>
				<div class="message-close">
					<i class="fas fa-times"></i>
				</div>
			</div>
<?php
			}
			?>
			<div class="modal-body" style="text-align: left;">
				<div class="row">
					<div class="col-sm-3">
						<label for="nouveaux_inscrits" class="col-form-label">
<?php
						if(isset($_SESSION["nouveaux_inscrits"]))
						{
							if($_SESSION["nouveaux_inscrits"] == 1)
							{
?>
							<input type="checkbox" name="nouveaux_inscrits" id="nouveaux_inscrits" class="switch value check" value="1">
<?php
						}
							elseif ($_SESSION["nouveaux_inscrits"] == 0)
							{
?>
							<input type="checkbox" name="nouveaux_inscrits" id="nouveaux_inscrits" class="switch value check" value="0">
<?php
							}
						}
						else
						{
?>
							<input type="checkbox" name="nouveaux_inscrits" id="nouveaux_inscrits" class="switch value check" value="0">
<?php
						}
?>
							Uniquement nouveaux inscrits
						</label>
					</div>
					<div class="col-sm-1">
						<input type="submit" name="vider" value="VIDER" class="btn btn-warning" style="width:100%;">
					</div>
					<div class="col-sm-8">
						<div class="row">
							<div class="col-sm-3">
								<input type="submit" name="afficher_clients" class="btn btn-warning" value="Afficher tous les clients">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-4">
						<label class="label-title" style="margin: 0;">Recherche client</label>
						<div class="ligne" style="width: 5.5%;"></div>
						<div class="row">
							<div class="col-sm-4">
								<label for="nom_client" class="col-form-label">Nom</label>
								<input type="text" name="nom_client" class="form-control" style="width:100%;" value="<?php if(isset($_SESSION["nom_client"])) { echo $_SESSION["nom_client"]; } ?>">
							</div>
							<div class="col-sm-4">
								<label for="tel_client" class="col-form-label">Téléphone</label>
								<input type="text" name="tel_client" class="form-control" style="width:100%;" value="<?php if(isset($_SESSION["tel_client"])) { echo $_SESSION["tel_client"]; } ?>">
							</div>
							<div class="col-sm-4">
								<label for="mail_client" class="col-form-label">Mail</label>
								<input type="text" name="mail_client" class="form-control" style="width:100%;" value="<?php if(isset($_SESSION["mail_client"])) { echo $_SESSION["mail_client"]; } ?>">
							</div>
							<div class="col-sm-3">
								<label for="code_client" class="col-form-label">Code</label>
								<input type="text" name="code_client" class="form-control" style="width:100%;" value="<?php if(isset($_SESSION["code_client"])) { echo $_SESSION["code_client"]; } ?>">
							</div>
							<div class="col-sm-6">
								<label for="ville_client" class="col-form-label">Ville</label>
								<input type="text" name="ville_client" class="form-control" style="width:100%;" value="<?php if(isset($_SESSION["ville_client"])) { echo $_SESSION["ville_client"]; } ?>">
							</div>
							<div class="col-sm-3">
								<label for="cp_client" class="col-form-label">CP</label>
								<input type="text" name="cp_client" class="form-control" style="width:100%;" value="<?php if(isset($_SESSION["cp_client"])) { echo $_SESSION["cp_client"]; } ?>">
							</div>
							<div class="col-sm-12">
								<label class="col-form-label">Date inscription entre le</label>
								<div class="form-inline">
									<div class="col-sm-5" style="padding:0">
										<input type="date" name="date_min_insc" class="form-control text-right" style="width:100%;" value="<?php if(isset($_SESSION["date_min_insc"])) { echo $_SESSION["date_min_insc"]; } ?>">
									</div>
									<span style="font-size: 14px;margin: 0 4%;">et le</span>
									<div class="col-sm-5" style="padding:0">
										<input type="date" name="date_max_insc" class="form-control text-right" style="width:100%;" value="<?php if(isset($_SESSION["date_max_insc"])) { echo $_SESSION["date_max_insc"]; } ?>">
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<label class="col-form-label">Date dernière co entre le</label>
								<div class="form-inline">
									<div class="col-sm-5" style="padding:0">
										<input type="date" name="date_min_co" class="form-control text-right" style="width:100%;" value="<?php if(isset($_SESSION["date_min_co"])) { echo $_SESSION["date_min_co"]; } ?>">
									</div>
									<span style="font-size: 14px;margin: 0 4%;">et le</span>
									<div class="col-sm-5" style="padding:0">
										<input type="date" name="date_max_co" class="form-control text-right" style="width:100%;" value="<?php if(isset($_SESSION["date_max_co"])) { echo $_SESSION["date_max_co"]; } ?>">
									</div>
								</div>
							</div>
							<div class="col-sm-12 text-right" style="margin-top:3%;">
								<input type="submit" name="chercher_client" class="btn btn-primary" value="CHERCHER">
							</div>
						</div>
						<hr>
						<label class="label-title" style="margin: 0;">Recherche sur zone fournisseur</label>
						<div class="ligne" style="width: 5.5%;"></div>
						<div class="row">
							<div class="col-sm-6">
								<label for="fournisseur_ajax" class="col-form-label">Fournisseur</label>
								<select class="form-control input-custom" name="fournisseur_ajax" style="width:100%;">
									<option value="0"></option>
<?php
									$res_four = getFournisseursListe($co_pmp);
									while($fournisseur = mysqli_fetch_array($res_four))
									{
?>
									<option value="<?= $fournisseur["id"]; ?>" <?php if(isset($_SESSION["fournisseur_ajax"])) { if($_SESSION["fournisseur_ajax"] == $fournisseur["id"]) { echo "selected='selected'"; } } ?>><?= $fournisseur["nom"]; ?></option>
<?php
									}
?>
								</select>
								</select>
							</div>
							<div class="col-sm-6">
								<label for="zone" class="col-form-label">zone</label>
								<select class="form-control input-custom code" name="zone" style="width:100%;">
<?php
								if(isset($res_zone))
								{
									while($zone = mysqli_fetch_array($res_zone))
									{
?>
										<option value="<?= $zone["id"]; ?>" <?php if(isset($_SESSION["zone"])) { if($_SESSION["zone"] == $zone["id"]) { echo "selected='selected'"; } } ?>><?= $zone["libelle"]; ?></option>
<?php
									}
								}
								elseif (isset($_SESSION["zone"]))
								{
									$res_zone = getZoneFournisseurId($co_pmp, $_SESSION["fournisseur_ajax"]);
									while($zone = mysqli_fetch_array($res_zone))
									{
?>
										<option value="<?= $zone["id"]; ?>" <?php if($_SESSION["zone"] == $zone["id"]) { echo "selected='selected'"; } ?>><?= $zone["libelle"]; ?></option>
<?php
									}
								}
?>
								</select>
							</div>
							<div class="col-sm-12 text-right" style="margin-top:3%;">
								<input type="submit" name="chercher_client_four" class="btn btn-primary" value="CHERCHER">
							</div>
							<div class="col-sm-6">
								<label for="Utilisateur" class="col-form-label">Utilisateur</label>
								<input type="text" name="Utilisateur" class="form-control" style="width:100%;" value="<?php if(isset($utilisateur)) { echo $utilisateur; } ?>">
							</div>
							<div class="col-sm-6">
								<label for="attach_grp" class="col-form-label">Attach + Grp</label>
								<input type="text" name="attach_grp" class="form-control" style="width:100%;" value="<?php if(isset($attachees_groupees)) { echo $attachees_groupees; } ?>">
							</div>
						</div>
						<hr>
						<!-- <div class="text-center">
							<input type="submit" name="creer_grp" class="btn btn-secondary" value="Créer regroupement avec la liste" style="width: 345px;margin-bottom:2%;">
						</div> -->

					</div>
					<div class="col-sm-8">
						<div class="tableau" style="margin: 0;height: 740px;margin-top: 1%;">
							<table class="table" id="trie_table_client">
								<thead>
									<tr>
										<th>C.Postal</th>
										<th>Ville</th>
										<th>Nom</th>
										<th>Prénom</th>
										<th>Mail</th>
										<th>Tel</th>
										<th>Port</th>
										<th>Fournisseur</th>
										<th>N° Client</th>
									</tr>
								</thead>
								<tbody>
<?php
								$i = 0;
								if(isset($res))
								{
									while ($clients = mysqli_fetch_array($res))
									{
?>
									<tr class="select gestion_clients">
										<input type="hidden" name="id_client_<?= $i++;  ?>" value="<?= $clients["user_id"]; ?>">
										<td><?= $clients["code_postal"]; ?></td>
										<td><?= $clients["ville"]; ?></td>
										<td><?= $clients["name"]; ?></td>
										<td><?= $clients["prenom"]; ?></td>
										<td><?= $clients["email"]; ?></td>
										<td><?= $clients["tel_fixe"]; ?></td>
										<td><?= $clients["tel_port"]; ?></td>
										<td><?= $clients["com_user"]; ?></td>
										<td><?= $clients["user_id"]; ?></td>

									</tr>
<?php
									}
								}
?>
								</tbody>
							</table>
						</div>

					</div>
				</div>

			</div>
			<div class="modal-footer">
				<input type="hidden" name="nb_client" value="<?= $i; ?>">
				<button type="button" class="btn btn-secondary b-close" data-bs-dismiss="modal">Fermer</button>
				<input type="hidden" name="user_id_client" id="user_id_client" value="">
				<input type="submit" name="test" class="btn btn-secondary" value="Ajouter la liste au regroupement" style="width: 345px;">

				<input type="submit" name="creer_commande_groupement" class="btn btn-primary" value="Valider">
			</div>
		</div>
	</div>
</div>
<?php
}
?>
