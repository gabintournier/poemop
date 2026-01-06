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
			<div class="col-sm-3">
				<a href="#" class="btn btn-secondary">Création Client</a>
			</div>
			<div class="col-sm-6 text-right">
				<input type="submit" name="exporter_clients" class="btn btn-primary" value="EXPORTER">
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
				<label for="nom_client" class="col-form-label">Nom / Prénom</label>
				<input type="text" name="nom_client" class="form-control" style="width:100%;" value="<?php if(isset($_POST["nom_client"])) { echo $_POST["nom_client"]; } elseif (isset($_SESSION["nom_client"])) { echo $_SESSION["nom_client"]; } ?>">
			</div>
			<div class="col-sm-4">
				<label for="tel_client" class="col-form-label">Téléphone</label>
				<input type="text" name="tel_client" class="form-control" style="width:100%;" value="<?php if(isset($_POST["tel_client"])) { echo $_POST["tel_client"]; } elseif (isset($_SESSION["tel_client"])) { echo $_SESSION["tel_client"]; } ?>">
			</div>
			<div class="col-sm-4">
				<label for="mail_client" class="col-form-label">Mail</label>
				<input type="text" name="mail_client" class="form-control" style="width:100%;" value="<?php if(isset($_POST["mail_client"])) { echo $_POST["mail_client"]; } elseif (isset($_SESSION["mail_client"])) { echo $_SESSION["mail_client"]; } ?>">
			</div>
			<div class="col-sm-3">
				<label for="code_client" class="col-form-label">Code</label>
				<input type="text" name="code_client" class="form-control" style="width:100%;" value="<?php if(isset($_POST["code_client"])) { echo $_POST["code_client"]; } elseif (isset($_SESSION["code_client"])) { echo $_SESSION["code_client"]; } ?>">
			</div>
			<div class="col-sm-6">
				<label for="ville_client" class="col-form-label">Ville</label>
				<input type="text" name="ville_client" class="form-control" style="width:100%;" value="<?php if(isset($_POST["ville_client"])) { echo $_POST["ville_client"]; } elseif (isset($_SESSION["ville_client"])) { echo $_SESSION["ville_client"]; } ?>">
			</div>
			<div class="col-sm-3">
				<label for="cp_client" class="col-form-label">CP</label>
				<input type="text" name="cp_client" class="form-control" style="width:100%;" value="<?php if(isset($_POST["cp_client"])) { echo $_POST["cp_client"]; } elseif (isset($_SESSION["cp_client"])) { echo $_SESSION["cp_client"]; } ?>">
			</div>
			<div class="col-sm-12">
				<label class="col-form-label">Date inscription entre le</label>
				<div class="form-inline">
					<div class="col-sm-5" style="padding:0">
						<input type="date" name="date_min_insc" class="form-control text-right" style="width:100%;" value="<?php if(isset($_POST["date_min_insc"])) { echo $_POST["date_min_insc"]; } elseif (isset($_SESSION["date_min_insc"])) { echo $_SESSION["date_min_insc"]; } ?>">
					</div>
					<span style="font-size: 14px;margin: 0 4%;">et le</span>
					<div class="col-sm-5" style="padding:0">
						<input type="date" name="date_max_insc" class="form-control text-right" style="width:100%;" value="<?php if(isset($_POST["date_max_insc"])) { echo $_POST["date_max_insc"]; } elseif (isset($_SESSION["date_max_insc"])) { echo $_SESSION["date_max_insc"]; } ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<label class="col-form-label">Date dernière co entre le</label>
				<div class="form-inline">
					<div class="col-sm-5" style="padding:0">
						<input type="date" name="date_min_co" class="form-control text-right" style="width:100%;" value="<?php if(isset($_POST["date_min_co"])) { echo $_POST["date_min_co"]; } elseif (isset($_SESSION["date_min_co"])) { echo $_SESSION["date_min_co"]; } ?>">
					</div>
					<span style="font-size: 14px;margin: 0 4%;">et le</span>
					<div class="col-sm-5" style="padding:0">
						<input type="date" name="date_max_co" class="form-control text-right" style="width:100%;" value="<?php if(isset($_POST["date_max_co"])) { echo $_POST["date_max_co"]; } elseif (isset($_SESSION["date_max_co"])) { echo $_SESSION["date_max_co"]; } ?>">
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
			<div class="col-sm-8 text-right" style="margin-top:3%;">
				<input type="submit" name="generer_texte" class="btn btn-secondary" value="GENERER TEXTE">
			</div>
			<div class="col-sm-4 text-right" style="margin-top:3%;">
				<input type="submit" name="chercher_client_four" class="btn btn-primary" value="CHERCHER" style="width: 90%;">
			</div>
<?php
			if (isset($_SESSION["zone"]))
			{
				$zone = $_SESSION["zone"];
				$vol_u = getCommandesFournisseurZoneStatus($co_pmp, $zone, '10');
				$vol_u2 = getCommandesFournisseurZoneStatus2($co_pmp, $zone, '10');
				$utilisateur = $vol_u["vol"] + $vol_u2["vol"];

				$vol_a = getCommandesFournisseurZoneStatus($co_pmp, $zone, '12');
				$vol_a2 = getCommandesFournisseurZoneStatus2($co_pmp, $zone, '12');
				$attachees = $vol_a["vol"] + $vol_a2["vol"];

				$vol_g = getCommandesFournisseurZoneStatus($co_pmp, $zone, '15');
				$vol_g2 = getCommandesFournisseurZoneStatus2($co_pmp, $zone, '15');
				$groupees = $vol_g["vol"] + $vol_g2["vol"];

				$attachees_groupees = $attachees + $groupees;
			}
?>
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
		<div class="text-center">
			<input type="submit" name="creer_grp" class="btn btn-secondary" value="Créer regroupement avec la liste" style="width: 345px;margin-bottom:2%;">
		</div>
		<div class="text-center">
			<a href="<?= $actual_link . "?popup_grp=oui" ?>" class="btn btn-secondary" style="width: 345px;">Ajouter la liste à un regroupement</a>
			<div class="modal fade" id="AjouterGrpt" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" style="max-width: 90%;text-align: left;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Ajouter la liste à un groupement</h5>
							<button type="button" class="btn-close fermer-modal" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i> </button>
						</div>
						<div class="modal-body">
<?php
						if (isset($_GET["message"]))
						{
?>
							<div class="toast success" style="margin: 0 0 1%;width: 530px;">
								<div class="message-icon success-icon">
									<i class="fas fa-check"></i>
								</div>
								<div class="message-content ">
									<div class="message-type">
										Succès
									</div>
									<div class="message">
										Les clients sans commande en cours ont été ajoutés au groupement au statuts 13 - Proposé.
									</div>
								</div>
								<div class="message-close">
									<i class="fas fa-times"></i>
								</div>
							</div>
<?php
						}
?>
							<?php include 'form/form_liste_groupements.php'; ?>
							<input type="hidden" name="id_grp" id="id_grp" value="">
						</div>
						<div class="modal-footer">
							<input type="submit" name="ajouter_liste_groupement" class="btn btn-primary" value="Ajouter la liste au regroupement" style="width: 345px;">
						</div>
					</div>
				</div>
			</div>
		</div>
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
					$res = getClientsFiltres($co_pmp);
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

					if(!empty($_POST["exporter_clients"]))
					{
						exporterListeClients($co_pmp, $res);
						$res = getClientFournisseurZone($co_pmp, $_SESSION["zone"]);

					}
				}
				elseif (isset($_SESSION["nom_client"]) || isset($_SESSION["mail_client"]) || isset($_SESSION["code_client"]) || isset($_SESSION["cp_client"]) || isset($_SESSION["tel_client"]) || isset($_SESSION["ville_client"]))
				{
					$res = getClientsFiltres($co_pmp);
					if(!empty($_POST["ajouter_liste_groupement"]))
					{
						ajouterListeGroupement($co_pmp, $res, $_POST["id_grp"]);
						$res = getClientsFiltres($co_pmp);
					}

					if(!empty($_POST["exporter_clients"]))
					{
						exporterListeClients($co_pmp, $res);
						$res = getClientsFiltres($co_pmp);

					}
				}



				while ($clients = mysqli_fetch_array($res))
				{

?>
					<tr class="select clients" id="test">
						<input type="hidden" name="id_client" value="<?= $clients["user_id"]; ?>">
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

?>
				</tbody>
			</table>
		</div>
	</div>
</div>
