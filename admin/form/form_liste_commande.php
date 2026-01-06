<?php
if(isset($_GET["popup_c"]))
{

if (!empty($_SESSION["fournisseurs"]))
{
	$res_zone = getZoneFournisseurId($co_pmp, $_SESSION["fournisseurs"]);
	$zone_1 = getZoneFournisseurLimitId($co_pmp, $_SESSION["fournisseurs"]);

	if(isset($_POST["zone_fournisseur"])) { $_SESSION["zone_fournisseur"] = $_POST["zone_fournisseur"]; } else { $_SESSION["zone_fournisseur"] = $zone_1["id"]; }
}
if (!empty($_POST["charger_commandes"]))
{
	$_SESSION["n_dep_cmd"] = $_POST["n_dep"];
	$_SESSION["etat_1"] = $_POST["etat_1"];
	$_SESSION["etat_2"] = $_POST["etat_2"];
	$_SESSION["fournisseurs"] = $_POST["fournisseur_ajax"];


	if (!empty($_SESSION["fournisseurs"]))
	{
		$res_zone = getZoneFournisseurId($co_pmp, $_SESSION["fournisseurs"]);
		$zone_1 = getZoneFournisseurLimitId($co_pmp, $_SESSION["fournisseurs"]);

		if(isset($_POST["zone_fournisseur"])) { $_SESSION["zone_fournisseur"] = $_POST["zone_fournisseur"]; } else { $_SESSION["zone_fournisseur"] = $zone_1["id"]; }
	}

	if (!empty($_POST["n_cmd"]))
	{
		$_SESSION["n_cmd"] = $_POST["n_cmd"];
		if(isset($_GET["id_grp"]))
		{
			$res_cmd = getFiltreCommandes($co_pmp);
		}
		else
		{
			$n_cmd = $_POST["n_cmd"];
			header('Location: /admin/gestion_client_commande.php?id_cmd=' . $n_cmd .'&return=cmdes');
		}
	}
	elseif (!empty($_SESSION["fournisseurs"]) && !empty($_SESSION["zone_fournisseur"]))
	{
		$res_cmd = getCommandesFournisseurZone($co_pmp, $_SESSION["zone_fournisseur"], $_SESSION["etat_1"], $_SESSION["etat_2"]);
	}
	else
	{
		$res_cmd = getFiltreCommandes($co_pmp);
	}
}

elseif (!empty($_POST["vider_cmd"]))
{
	unset($_SESSION["n_cmd"]);
	unset($_SESSION["n_dep_cmd"]);
	unset($_SESSION["etat_1"]);
	unset($_SESSION["etat_2"]);
	unset($_SESSION["fournisseurs"]);
	unset($_SESSION["zone_fournisseur"]);
	unset($_SESSION["n_client"]);
	unset($_SESSION["cp_client"]);
	unset($_SESSION["nom_client"]);
	unset($_SESSION["p_client"]);
	unset($_SESSION["tel_client"]);
	unset($_SESSION["email_client"]);
}
elseif (!empty($_SESSION["n_client"]) || !empty($_SESSION["cp_client"]) || !empty($_SESSION["nom_client"]) || !empty($_SESSION["p_client"]) || !empty($_SESSION["tel_client"]) || !empty($_SESSION["email_client"]))
{
	$res_cmd = getCommandeRapide($co_pmp);

	if(!empty($_POST["exporter_cmd"]))
	{
		exporterListeCommandes($co_pmp, $res_cmd);
		$res_cmd = getCommandeRapide($co_pmp);
	}
}
elseif (!empty($_SESSION["fournisseurs"]) && !empty($_SESSION["zone_fournisseur"]))
{
	$res_cmd = getCommandesFournisseurZone($co_pmp, $_SESSION["zone_fournisseur"], $_SESSION["etat_1"], $_SESSION["etat_1"]);
	if(!empty($_POST["exporter_cmd"]))
	{
		exporterListeCommandes($co_pmp, $res_cmd);
		$res_cmd = getCommandesFournisseurZone($co_pmp, $_SESSION["zone_fournisseur"], $_SESSION["etat_1"], $_SESSION["etat_1"]);
	}
}
elseif (!empty($_POST["recherche_rapide_commande"]))
{
	$_SESSION["n_client"] = $_POST["n_client"];
	$_SESSION["cp_client"] = $_POST["cp_client"];
	$_SESSION["nom_client"] = $_POST["nom_client"];
	$_SESSION["p_client"] = $_POST["p_client"];
	$_SESSION["tel_client"] = $_POST["tel_client"];
	$_SESSION["email_client"] = $_POST["email_client"];

	$res_cmd = getCommandeRapide($co_pmp);

	if(!empty($_POST["exporter_cmd"]))
	{
		exporterListeCommandes($co_pmp, $res_cmd);
		$res_cmd = getCommandeRapide($co_pmp);
	}
}
elseif (!empty($_SESSION["n_dep_cmd"]) || !empty($_SESSION["etat_1"]) || !empty($_SESSION["etat_2"]))
{
	$res_cmd = getFiltreCommandes($co_pmp);

	if(!empty($_POST["exporter_cmd"]))
	{
		exporterListeCommandes($co_pmp, $res_cmd);
		$res_cmd = getFiltreCommandes($co_pmp);
	}
}

if(!empty($_POST["ajouter_liste"]))
{
	$id_ko = "";
	$id_ok = "";
	$id_grp = $_GET["id_grp"];
	$id = $_POST["ids_cmd"];
	$id_cmd = explode(";",$id);
	foreach ($id_cmd as $id_cmd)
	{
		$cmd = getCommandeDetailsClients($co_pmp, $id_cmd);
		if(isset($cmd))
		{
			if($cmd["groupe_cmd"] == 0)
			{
				$id_ok .= $id_cmd . ";";

				$query = "  UPDATE pmp_commande
							SET groupe_cmd = '$id_grp'
							WHERE id = '$id_cmd'
							AND groupe_cmd = '0'  ";
				$res = my_query($co_pmp, $query);
				if($res)
				{
					TraceHisto($co_pmp, $id_cmd, 'Ajout Groupement', $id_grp);
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message_mod = "Les commandes sans groupements ont bien été ajoutées.";
				}
			}
			else
			{
				$id_ko .= $id_cmd . ";";
			}
		}
	}
}

if(!empty($_POST["basculer_commande_groupement"]))
{
	$id_grp = $_GET["id_grp"];
	$nb = $_POST['nb_cmd_basculer'];
	for ($i=0; $i < $nb; $i++)
	{
		$id = 'cmde_id_grp_' . $i;
		if (isset($_POST[$id]))
		{
			$id_cmd = $_POST[$id];
			$id_ancien_grp = 'id_grp_' . $id_cmd;
			$id_ancien_grp = $_POST[$id_ancien_grp];
			$actif = 'basculer_commande_' . $id_cmd;
			$basculer = isset($_POST[$actif]) ? "1" : "0";
			if($basculer == '1')
			{
				$query = " UPDATE pmp_commande SET groupe_cmd = '$id_grp'
									 WHERE id = '$id_cmd' ";
				$res = my_query($co_pmp, $query);
				if($res)
				{
					TraceHisto($co_pmp, $id, 'Basculer Groupement', $id_ancien_grp . " --> " . $id_grp);
					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message_mod = "Les commandes ont bien été ajoutées.";
				}
			}
		}
	}
}
?>

<?php
if(isset($message_mod))
{
?>
<div class="toast <?= $message_type; ?>" style="margin: 10px 0 15px;">
	<div class="message-icon  <?= $message_type; ?>-icon">
		<i class="fas <?= $message_icone; ?>"></i>
	</div>
	<div class="message-content ">
		<div class="message-type">
			<?= $message_titre; ?>
		</div>
		<div class="message">
			<?= $message_mod; ?>
		</div>
	</div>
	<div class="message-close">
		<i class="fas fa-times"></i>
	</div>
</div>
<?php
}
?>

<?php
if(isset($id_ko) && strlen($id_ko) > 0)
{
?>
<label class="label-title" style="margin: 0;">Commandes déjà dans un groupement</label>
<div class="ligne"></div>
<div class="form-inline">
	<label for="dep_zone" class="col-sm-2 col-form-label" style="padding-left:0;">Sélectionner toutes les commandes :</label>
	<div class="col-sm-2 select-tous_basculer" style="padding:0">
		<input type="checkbox" name="tous_basculer" value="" class="switch value">
	</div>
</div>
<div class="tableau">
	<table class="table" id="trie_table_cmd_charger">
		<thead>
			<tr style="white-space: nowrap;">
				<th><i class="fal fa-sort"></i></th>
				<th class="text-center">Select</th>
				<th class="text-center">N° GRP</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Email</th>
				<th class="text-center">CP</th>
				<th class="text-center">Date</th>
				<th class="text-center">Qté</th>
				<th class="text-center">Statut</th>
			</tr>
		</thead>
		<tbody>
<?php
		$i = 0;
		$id_cmds = explode(";",$id_ko);
		foreach ($id_cmds as $id_cmd)
		{    
			if (empty($id_cmd)) continue;
			$cmd = getCommandeDetailsClients($co_pmp, $id_cmd);
			if(isset($cmd))
			{
?>
			<tr>
				<input type="hidden" name="cmde_id_grp_<?php print $i++; ?>" value="<?= $cmd["num_cmd"]; ?>">
				<input type="hidden" name="id_grp_<?= $cmd["num_cmd"]; ?>" value="<?= $cmd["groupe_cmd"]; ?>">
				<td></td>
				<td class="text-center"><input type="checkbox" name="basculer_commande_<?= $cmd["num_cmd"]; ?>" value="" class="switch value"></td>
				<td class="text-center"><?= $cmd["groupe_cmd"]; ?></td>
				<td><?= $cmd["name"]; ?></td>
				<td><?= $cmd["prenom"]; ?></td>
				<td><?= $cmd["email"]; ?></td>
				<td class="text-center"><?= $cmd["code_postal"]; ?></td>
				<td class="text-center"><?= $cmd["cmd_dt"]; ?></td>
				<td class="text-center"><?= $cmd["cmd_qte"]; ?></td>
				<td class="text-center"><?= $cmd["cmd_status"]; ?></td>
			</tr>
<?php
			}
		}
?>
		</tbody>
	</table>
</div>
<input type="hidden" name="nb_cmd_basculer" value="<?php print $i; ?>">
<?php
}
else
{
?>
<div class="row">
	<div class="col-sm-2">
		<label class="label-title" style="margin: 0;">Selection des commandes</label>
		<div class="ligne"></div>
		<div class="form-inline" style="margin: 2% 0 0 0;">
			<label for="n_dep" class="col-sm-7 col-form-label" style="padding-left:0;">N° département</label>
			<div class="col-sm-5" style="padding:0">
				<input type="text" name="n_dep" value="<?php if(!empty($_POST["vider_cmd"])) { echo ""; } elseif(isset($_POST["n_dep"]))  { echo $_POST["n_dep"]; } elseif(isset($_SESSION["n_dep_cmd"])) { echo $_SESSION["n_dep_cmd"]; } ?>" class="form-control" style="width:100%;">
			</div>
		</div>
		<div class="form-inline" style="margin: 0 0 2% 0;">
			<label for="n_cmd" class="col-sm-7 col-form-label" style="padding-left:0;">N° commande</label>
			<div class="col-sm-5" style="padding:0">
				<input type="text" name="n_cmd" value="<?php if(!empty($_POST["vider_cmd"])) { echo ""; } elseif(isset($_POST["n_cmd"]))  { echo $_POST["n_cmd"]; } elseif(isset($_SESSION["n_cmd"])) { echo $_SESSION["n_cmd"]; } ?>" class="form-control" style="width:100%;">
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<label class="label-title" style="margin: 0;">Statut de la commande</label>
		<div class="ligne"></div>
		<div class="form-inline" style="margin: 2% 0 0 0;">
			<label for="etat_1" class="col-sm-5 col-form-label" style="padding-left:0;">A partir du statut</label>
			<div class="col-sm-3" style="padding:0">
				<select class="form-control input-custom" name="etat_1">
					<option value="10" <?php if(isset($_SESSION["etat_1"])) { if($_SESSION['etat_1'] == '10'){ echo "selected='selected'"; } } ?>>Utilisateur</option>
					<option value="12" <?php if(isset($_SESSION["etat_1"])) { if($_SESSION['etat_1'] == '12'){ echo "selected='selected'"; } } ?>>Attachée</option>
					<option value="13" <?php if(isset($_SESSION["etat_1"])) { if($_SESSION['etat_1'] == '13'){ echo "selected='selected'"; } } ?>>Proposée</option>
					<option value="15" <?php if(isset($_SESSION["etat_1"])) { if($_SESSION['etat_1'] == '15'){ echo "selected='selected'"; } } ?>>Groupée</option>
					<option value="17" <?php if(isset($_SESSION["etat_1"])) { if($_SESSION['etat_1'] == '17'){ echo "selected='selected'"; } } ?>>Prix proposé</option>
					<option value="20" <?php if(isset($_SESSION["etat_1"])) { if($_SESSION['etat_1'] == '20'){ echo "selected='selected'"; } } ?>>Prix validé</option>
					<option value="25" <?php if(isset($_SESSION["etat_1"])) { if($_SESSION['etat_1'] == '25'){ echo "selected='selected'"; } } ?>>Livrable</option>
					<option value="30" <?php if(isset($_SESSION["etat_1"])) { if($_SESSION['etat_1'] == '30'){ echo "selected='selected'"; } } ?>>Livrée</option>
					<option value="40" <?php if(isset($_SESSION["etat_1"])) { if($_SESSION['etat_1'] == '40'){ echo "selected='selected'"; } } ?>>Terminée</option>
					<option value="50" <?php if(isset($_SESSION["etat_1"])) { if($_SESSION['etat_1'] == '50'){ echo "selected='selected'"; } } ?>>Annulée</option>
					<option value="52" <?php if(isset($_SESSION["etat_1"])) { if($_SESSION['etat_1'] == '52'){ echo "selected='selected'"; } } ?>>Annulée / Livraison</option>
					<option value="55" <?php if(isset($_SESSION["etat_1"])) { if($_SESSION['etat_1'] == '55'){ echo "selected='selected'"; } } ?>>Annulée / Prix</option>
				</select>
			</div>
		</div>
		<div class="form-inline" style="margin: 2% 0 2% 0;">
			<label for="etat_2" class="col-sm-5 col-form-label" style="padding-left:0;">Jusqu'au statut</label>
			<div class="col-sm-3" style="padding:0">
				<select class="form-control input-custom" name="etat_2">
					<option value="0" <?php if(isset($_SESSION["etat_2"])) { if($_SESSION['etat_2'] == '0'){ echo "selected='selected'"; } } ?>></option>
					<option value="10" <?php if(isset($_SESSION["etat_2"])) { if($_SESSION['etat_2'] == '10'){ echo "selected='selected'"; } } ?>>Utilisateur</option>
					<option value="12" <?php if(isset($_SESSION["etat_2"])) { if($_SESSION['etat_2'] == '12'){ echo "selected='selected'"; } } ?>>Attachée</option>
					<option value="13" <?php if(isset($_SESSION["etat_2"])) { if($_SESSION['etat_2'] == '13'){ echo "selected='selected'"; } } ?>>Proposée</option>
					<option value="15" <?php if(isset($_SESSION["etat_2"])) { if($_SESSION['etat_2'] == '15'){ echo "selected='selected'"; } } ?>>Groupée</option>
					<option value="17" <?php if(isset($_SESSION["etat_2"])) { if($_SESSION['etat_2'] == '17'){ echo "selected='selected'"; } } ?>>Prix proposé</option>
					<option value="20" <?php if(isset($_SESSION["etat_2"])) { if($_SESSION['etat_2'] == '20'){ echo "selected='selected'"; } } ?>>Prix validé</option>
					<option value="25" <?php if(isset($_SESSION["etat_2"])) { if($_SESSION['etat_2'] == '25'){ echo "selected='selected'"; } } ?>>Livrable</option>
					<option value="30" <?php if(isset($_SESSION["etat_2"])) { if($_SESSION['etat_2'] == '30'){ echo "selected='selected'"; } } ?>>Livrée</option>
					<option value="40" <?php if(isset($_SESSION["etat_2"])) { if($_SESSION['etat_2'] == '40'){ echo "selected='selected'"; } } ?>>Terminée</option>
					<option value="50" <?php if(isset($_SESSION["etat_2"])) { if($_SESSION['etat_2'] == '50'){ echo "selected='selected'"; } } ?>>Annulée</option>
					<option value="52" <?php if(isset($_SESSION["etat_2"])) { if($_SESSION['etat_2'] == '52'){ echo "selected='selected'"; } } ?>>Annulée / Livraison</option>
					<option value="55" <?php if(isset($_SESSION["etat_2"])) { if($_SESSION['etat_2'] == '55'){ echo "selected='selected'"; } } ?>>Annulée / Prix</option>
				</select>
			</div>
		</div>
	</div>
	<div class="col-sm-3" style="margin-left: -1%;">
		<label class="label-title" style="margin: 0;">Recherche sur zone fournisseur</label>
		<div class="ligne"></div>
		<div class="form-inline" style="margin: 2% 0 0 0;">
			<label for="fournisseur_ajax" class="col-sm-4 col-form-label" style="padding-left:0;">Fournisseur</label>
			<div class="col-sm-7" style="padding:0">
				<select class="js-example-basic-single form-control" name="fournisseur_ajax" style="width:100%;">
					<option value="0"></option>
<?php
				$res_four = getFournisseursListe($co_pmp);
				while($fournisseur = mysqli_fetch_array($res_four))
				{
?>
					<option value="<?= $fournisseur["id"]; ?>" <?php if(isset($_SESSION["fournisseurs"])) { if($_SESSION["fournisseurs"] == $fournisseur["id"]) { echo "selected='selected'"; } } ?>><?= $fournisseur["nom"]; ?></option>
<?php
				}
?>
				</select>
			</div>
		</div>
		<div class="form-inline" style="margin: 2% 0 2% 0;">
			<label for="zone_fournisseur" class="col-sm-4 col-form-label" style="padding-left:0;">Zone</label>
			<div class="col-sm-7" style="padding:0">
				<select class="form-control input-custom code" name="zone_fournisseur" style="width:100%;">
<?php
			if(isset($res_zone) && isset($_SESSION["zone_fournisseur"]))
			{
				while($zone = mysqli_fetch_array($res_zone))
				{
?>
				<option value="<?= $zone["id"]; ?>" <?php if(isset($_SESSION["zone_fournisseur"])) { if($_SESSION["zone_fournisseur"] == $zone["id"]) { echo "selected='selected'"; } } ?>><?= $zone["libelle"]; ?></option>
<?php
				}
			}
?>
				</select>
			</div>
		</div>
	</div>
	<div class="col-sm-2 align-self-center" style="margin-left:-3%;">
		<div class="btn-form">
			<input type="submit" name="charger_commandes" value="CHARGER" class="btn btn-primary" style="min-width:55%;"><br>
			<input type="submit" name="vider_cmd" value="VIDER" class="btn btn-secondary" style="min-width:55%; margin-top:2%;margin-bottom: 4%;">
		</div>
	</div>
	<div class="col-sm-2 align-self-center" style="border-left: 1px solid #0b242436;margin-left:-3%;">
		<input type="submit" name="toute_commande" class="btn btn-secondary" value="AFFICHER TOUTES LES COMMANDES" style="width:132%;">
		<!-- <a href="liste_commandes.php?popup=oui" class="btn btn-primary"  style="width: 132%;margin-top:2%;"></a> -->
		<input type="submit" name="ajouter_liste" value="AJOUTER LISTE GROUPEMENT" class="btn btn-primary"  style="width: 132%;margin-top:2%;">
		<div class="modal fade" id="selGrp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" style="max-width: 85%;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Sélectionner un groupement</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-bs-label="Close"> <i class="fa-solid fa-xmark"></i> </button>
					</div>
					<div class="modal-body">
						<?php include 'form/form_liste_groupements.php'; ?>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="n_id_grp" id="n_id_grp" value="">
						<input type="submit" name="ajouter_liste_grp" class="btn btn-primary valider_four" value="VALIDER">
					</div>
				</div>
			</div>
		</div>

		<div class="form-inline" style="margin: 2% 0 2% 0;">
			<label for="nb_litres" class="col-sm-7 col-form-label" style="padding-left:0;">Nombre de litres</label>
			<div class="col-sm-3" style="padding:0">
				<input type="text" name="nb_litres" id="nb_litres" class="form-control" value="" style="width:143%;">
			</div>
		</div>
		<div class="text-end">
			<input type="submit" name="exporter_cmd" value="EXPORTER" class="btn btn-warning">
		</div>
	</div>
</div>
<hr>
<div class="tableau" style="height:450px;">
	<table class="table commande_add_grp" id="trie_table2">
		<thead>
			<tr>
				<th style="white-space: nowrap;padding: 8px 10px;">Nb Litre</th>
				<th style="white-space: nowrap;padding: 8px 10px;">Type Fuel</th>
				<th style="white-space: nowrap;padding: 8px 10px;">Date</th>
				<th style="white-space: nowrap;padding: 8px 10px;">CP</th>
				<th style="white-space: nowrap;padding: 8px 10px;">Ville</th>
				<th style="white-space: nowrap;padding: 8px 10px;">Nom</th>
				<th style="white-space: nowrap;padding: 8px 10px;">Prénom</th>
				<th style="white-space: nowrap;padding: 8px 10px;">Mail</th>
				<th style="white-space: nowrap;padding: 8px 10px;">Tel</th>
				<th style="white-space: nowrap;padding: 8px 10px;">Portable</th>
				<th style="white-space: nowrap;padding: 8px 10px;">N° Client</th>
				<th style="white-space: nowrap;padding: 8px 10px;">N° CMD</th>
				<th style="white-space: nowrap;padding: 8px 10px;">Etat CMD</th>
			</tr>
		</thead>
		<tbody>
<?php
$o = 0;
$id = "";
if (isset($res_cmd))
{
		while ($commande = mysqli_fetch_array($res_cmd))
		{
			if ($commande["cmd_typefuel"] == 1){ $type = 'O';}
			if ($commande["cmd_typefuel"] == 2){ $type = 'S';}
			if ($commande["cmd_typefuel"] == 3){ $type = 'GNR';}
			$id .= $commande["num_cmd"] . ";";
?>
			<tr class="select commande">
				<input type="hidden" name="id_cmde[]" value="<?= $commande["num_cmd"]; ?>">
				<td class="nb_l"><?= $commande["cmd_qte"]; ?></td>
				<td class="text-center"><?= $type; ?></td>
				<td><?= date_format(new DateTime($commande['cmd_dt']), 'd/m/Y' ); ?></td>
				<td><?= $commande["code_postal"]; ?></td>
				<td><?= $commande["ville"]; ?></td>
				<td><?= $commande["nom"]; ?></td>
				<td><?= $commande["prenom"]; ?></td>
				<td><?= $commande["email"]; ?></td>
				<td><?= $commande["tel_fixe"]; ?></td>
				<td><?= $commande["tel_port"]; ?></td>
				<td><input type="hidden" name="id_client_<?php print $o++; ?>" value=" <?= $commande["user_id"]; ?>"> <?= $commande["user_id"]; ?></td>
				<td><?= $commande["num_cmd"]; ?></td>
				<td class="text-center"><?= $commande["cmd_status"]; ?></td>
			</tr>
<?php
		}
}
elseif (isset($commande_rapide) == TRUE)
{
	$res = getCommandeRapide($co_pmp, $_GET['n_client'], $_GET['cp_client'], $_GET["nom_client"], $_GET["p_client"], $_GET["tel_client"], $_GET["email_client"]);
	while ($commande = mysqli_fetch_array($res))
	{
		if ($commande["cmd_typefuel"] == 1){ $type = 'O';}
		if ($commande["cmd_typefuel"] == 2){ $type = 'S';}
		if ($commande["cmd_typefuel"] == 3){ $type = 'GNR';}
		$id .= $commande["num_cmd"] . ";";
?>
			<tr class="select commande">
				<input type="hidden" name="id_cmde" value="<?= $commande["num_cmd"]; ?>">
				<td class="nb_l"><?= $commande["cmd_qte"]; ?></td>
				<td class="text-center"><?= $type; ?></td>
				<td><?= date_format(new DateTime($commande['cmd_dt']), 'd/m/Y' ); ?></td>
				<td><?= $commande["code_postal"]; ?></td>
				<td><?= $commande["ville"]; ?></td>
				<td><?= $commande["nom"]; ?></td>
				<td><?= $commande["prenom"]; ?></td>
				<td><?= $commande["email"]; ?></td>
				<td><?= $commande["tel_fixe"]; ?></td>
				<td><?= $commande["tel_port"]; ?></td>
				<td><?= $commande["user_id"]; ?></td>
				<td><?= $commande["num_cmd"]; ?></td>
				<td class="text-center"><?= $commande["cmd_status"]; ?></td>
				<td></td>
			</tr>
<?php
	}
}
?>

		</tbody>
	</table>
	<input type="hidden" name="nb_commande" value="<?= $o; ?>">
	<input type="hidden" name="ids_cmd" value="<?= $id; ?>">

</div>
<?php
}

}
?>
