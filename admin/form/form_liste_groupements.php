
<?php
$res_four = getFournisseursListe($co_pmp);
if(!empty($_POST["charger_grp"]))
{
	$_SESSION["etat_four"] = $_POST["etat_four"];
	$_SESSION["etat_four2"] = $_POST["etat_four2"];
	$_SESSION["resp"] = $_POST["resp"];

	if(!empty($_POST["four_id"]))
	{
		$_SESSION["four_id"] = $_POST["four_id"];
	}
	if(!empty($_POST["date_min"]) && !empty($_POST["date_max"]))
	{
		$_SESSION["date_min"] = $_POST["date_min"];
		$_SESSION["date_max"] = $_POST["date_max"];
	}

	$res = getFiltresGroupements($co_pmp);
	if(!empty($_POST["exporter_grp"]))
	{
		ExporterListeGrpt($co_pmp, $res);
		$res = getFiltresGroupements($co_pmp);
	}
}
elseif(!empty($_POST["charger_calculer"]))
{
	$_SESSION["etat_four"] = $_POST["etat_four"];
	$_SESSION["etat_four2"] = $_POST["etat_four2"];
	$_SESSION["resp"] = $_POST["resp"];

	if(!empty($_POST["four_id"]))
	{
		$_SESSION["four_id"] = $_POST["four_id"];
	}
	if(!empty($_POST["date_min"]) && !empty($_POST["date_max"]))
	{
		$_SESSION["date_min"] = $_POST["date_min"];
		$_SESSION["date_max"] = $_POST["date_max"];
	}

	$res = getFiltresGroupementsCalculer($co_pmp);
	$attachee = GetStatsGroupement($co_pmp, '12');
	$groupee = GetStatsGroupement($co_pmp, '15');
	$p_propose = GetStatsGroupement($co_pmp, '17');
	$p_valide = GetStatsGroupement($co_pmp, '20');
	$livrable = GetStatsGroupement($co_pmp, '25');
	$livree = GetStatsGroupement($co_pmp, '30');
	$terminee = GetStatsGroupement($co_pmp, '40');
	$annul = GetStatsGroupement($co_pmp, '50');
	$annulp = GetStatsGroupement($co_pmp, '55');
	$en_cours = $attachee["statut"] + $groupee["statut"] + $p_propose["statut"];
	$valide = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $terminee["statut"];
	$_SESSION["charger_calculer"] = $_POST["charger_calculer"];
}
// elseif (!empty($_SESSION["charger_calculer"]))
// {
// 	$res = getFiltresGroupementsCalculer($co_pmp);
// 	if(!empty($_POST["exporter_grp"]))
// 	{
// 		$pourc_annulp = "1";
// 		$pourc_annul = "0";
// 		ExporterListeGrptStats($co_pmp, $res, $pourc_annulp, $pourc_annul);
// 		$res = getFiltresGroupementsCalculer($co_pmp);
// 	}
// }
elseif (!empty($_POST["charger_mois"]))
{
	$date = new DateTime();
    $dateDeb = $date -> format('Y-m-01');
    $dateFin = $date -> format('Y-m-t');

	$_SESSION["date_min"] = $dateDeb;
	$_SESSION["date_max"] = $dateFin;

	$res = GetMoisEnCours($co_pmp);
	$attachee = GetStatsGroupement($co_pmp, '12');
	$groupee = GetStatsGroupement($co_pmp, '15');
	$p_propose = GetStatsGroupement($co_pmp, '17');
	$p_valide = GetStatsGroupement($co_pmp, '20');
	$livrable = GetStatsGroupement($co_pmp, '25');
	$livree = GetStatsGroupement($co_pmp, '30');
	$terminee = GetStatsGroupement($co_pmp, '40');
	$annul = GetStatsGroupement($co_pmp, '50');
	$annulp = GetStatsGroupement($co_pmp, '55');
	$en_cours = $attachee["statut"] + $groupee["statut"] + $p_propose["statut"];
	$valide = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $terminee["statut"];
	$_SESSION["charger_mois"] = $_POST["charger_mois"];
}
elseif (!empty($_SESSION["charger_mois"]))
{
	$res = GetMoisEnCours($co_pmp);
	if(!empty($_POST["exporter_grp"]))
	{
		$pourc_annulp = "1";
		$pourc_annul = "0";
		ExporterListeGrptStats($co_pmp, $res);
		$res = GetMoisEnCours($co_pmp);
	}
}
elseif (!empty($_POST["charger_facture"]))
{
	if(!empty($_POST["n_fact"]))
	{
		$_SESSION["n_fact"] = $_POST["n_fact"];
		$res = getFiltresGroupementsFacture($co_pmp,$_SESSION["n_fact"]);
		if(!empty($_POST["exporter_grp"]))
		{
			ExporterListeGrpt($co_pmp, $res);
			$res = getFiltresGroupementsFacture($co_pmp,$_SESSION["n_fact"]);
		}
	}

}
elseif (!empty($_POST["vider"]))
{
	unset($_SESSION["etat_four"]);
	unset($_SESSION["etat_four2"]);
	unset($_SESSION["resp"]);
	unset($_SESSION["four_id"]);
	unset($_SESSION["date_min"]);
	unset($_SESSION["date_max"]);
	unset($_SESSION["n_fact"]);
	$res = getListeRegroupementsCréer($co_pmp);
	if(!empty($_POST["exporter_grp"]))
	{
		ExporterListeGrpt($co_pmp, $res);
		$res = getListeRegroupementsCréer($co_pmp);
	}
}
elseif (!empty($_SESSION["etat_four"]) || !empty($_SESSION["etat_four2"]) || !empty($_SESSION["resp"]) || !empty($_SESSION["four_id"]) || !empty($_POST["date_min"]) && !empty($_POST["date_max"]))
{
	$res = getFiltresGroupements($co_pmp);
	if(!empty($_POST["exporter_grp"]))
	{
		ExporterListeGrpt($co_pmp, $res);
		$res = getFiltresGroupements($co_pmp);
	}
}
elseif (!empty($_SESSION["n_fact"]))
{
	$res = getFiltresGroupementsFacture($co_pmp, $_SESSION["n_fact"]);
	if(!empty($_POST["exporter_grp"]))
	{
		ExporterListeGrpt($co_pmp, $res);
		$res = getFiltresGroupementsFacture($co_pmp, $_SESSION["n_fact"]);
	}
}
elseif (isset($_GET["id_four"]))
{
	$res = getListeRegroupementsFournisseur($co_pmp, $_GET["id_four"]);
}
else
{
	$res = getListeRegroupementsCréer($co_pmp);
	if(!empty($_POST["exporter_grp"]))
	{
		ExporterListeGrpt($co_pmp, $res);
		$res = getListeRegroupementsCréer($co_pmp);
	}
}

?>
<div class="row">
	<div class="col-sm-2">
		<label class="label-title" style="margin: 0;">Statut du regroupement</label>
		<div class="ligne" style="width: 12%;"></div>
		<div class="form-inline" style="margin: 2% 0 0 0;">
			<label for="etat_four" class="col-sm-8 col-form-label" style="padding-left:0;">A partir du statut</label>
			<div class="col-sm-4" style="padding:0">
				<select class="form-control input-custom" name="etat_four">
					<option value="10" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '10'){ echo "selected='selected'"; } } ?>>10 - Créé</option>
					<option value="5" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '5'){ echo "selected='selected'"; } } ?>>5 - Prévu</option>
					<option value="15" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '15'){ echo "selected='selected'"; } } ?>>15 - Envoyé</option>
					<option value="30" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '30'){ echo "selected='selected'"; } } ?>>30 - Livré</option>
					<option value="33" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '33'){ echo "selected='selected'"; } } ?>>33 - A facturer</option>
					<option value="37" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '37'){ echo "selected='selected'"; } } ?>>37 - Facturé</option>
					<option value="40" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '40'){ echo "selected='selected'"; } } ?>>40 - Terminé</option>
					<option value="50" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '50'){ echo "selected='selected'"; } } ?>>50 - Annulé</option>
				</select>
			</div>
		</div>
		<div class="form-inline" style="margin: 2% 0 2% 0;">
			<label for="etat_four2" class="col-sm-8 col-form-label" style="padding-left:0;">Jusqu'au statut</label>
			<div class="col-sm-4" style="padding:0">
				<select class="form-control input-custom" name="etat_four2">
					<option value="10" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '10'){ echo "selected='selected'"; } } ?>>10 - Créé</option>
					<option value="5" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '5'){ echo "selected='selected'"; } } ?>>5 - Prévu</option>
					<option value="15" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '15'){ echo "selected='selected'"; } } ?>>15 - Envoyé</option>
					<option value="30" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '30'){ echo "selected='selected'"; } } ?>>30 - Livré</option>
					<option value="33" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '33'){ echo "selected='selected'"; } } ?>>33 - A facturer</option>
					<option value="37" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '37'){ echo "selected='selected'"; } } ?>>37 - Facturé</option>
<?php
					if(!empty($_POST["charger_mois"]))
					{
?>
					<option value="40" <?php if(isset($_POST["charger_mois"])) { echo "selected='selected'"; } ?>>40 - Terminé</option>
<?php
					}
					elseif (isset($_GET["id_four"]))
					{
?>
					<option value="40" <?php if(isset($_GET["id_four"])) { echo "selected='selected'"; } ?>>40 - Terminé</option>
<?php
					}
					else
					{
?>
					<option value="40" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '40'){ echo "selected='selected'"; } } ?>>40 - Terminé</option>
<?php
					}
?>
					<option value="50" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '50'){ echo "selected='selected'"; } } ?>>50 - Annulé</option>
				</select>
			</div>
		</div>
	</div>
	<div class="col-sm-3" style="margin-left:3%;">
		<div class="row">
			<div class="col-sm-6">
				<label for="date_min" class="col-sm-12 col-form-label" style="padding-left:0;">Date entre le</label>
				<input type="date" name="date_min" class="form-control" value="<?php if(isset($_SESSION["date_min"])) { echo $_SESSION["date_min"]; } elseif(isset($dateDeb)) { echo $dateDeb; } ?>" style="width:100%;">
			</div>
			<div class="col-sm-6">
				<label for="date_max" class="col-sm-12 col-form-label" style="padding-left:0;">et le 'inclus'</label>
				<input type="date" name="date_max" class="form-control" value="<?php if(isset($_SESSION["date_max"])) { echo $_SESSION["date_max"]; } elseif(isset($dateFin)) { echo $dateFin; } ?>" style="width:100%;">
			</div>
		</div>
		<div class="row" style="margin-top: 3%;">
			<div class="col-sm-8">
				<div class="form-inline" style="margin: 2% 0 0 0;">
					<label for="n_four" class="col-sm-5 col-form-label" style="padding-left:0;">Fournisseur</label>
					<div class="col-sm-7" style="padding:0">
						<select class=" form-control" name="four_id" style="width:100%;">
							<option value=""></option>
<?php
						while($fournisseur = mysqli_fetch_array($res_four))
						{
							if(isset($_GET["id_four"]))
							{
?>
								<option value="<?= $fournisseur["id"]; ?>" <?php if(isset($_GET["id_four"])) { if($_GET["id_four"] == $fournisseur["id"]){ echo "selected='selected'"; } } ?>><?= $fournisseur["nom"]; ?></option>
<?php
							}
							else
							{
?>
								<option value="<?= $fournisseur["id"]; ?>" <?php if(isset($_SESSION["four_id"])) { if($_SESSION['four_id'] == $fournisseur["id"]){ echo "selected='selected'"; } } ?>><?= $fournisseur["nom"]; ?></option>
<?php
							}
						}
?>
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-inline" style="margin: 2% 0 0 0;">
					<label for="n_four" class="col-sm-5 col-form-label" style="padding-left:0;">Resp</label>
					<div class="col-sm-7" style="padding:0">
						<select class="form-control" name="resp" style="width:100%;padding:0">
							<option value="0" <?php if(isset($_SESSION["resp"])) { if($_SESSION['resp'] == '0'){ echo "selected='selected'"; } } ?>></option>
							<option value="STE" <?php if(isset($_SESSION["resp"])) { if($_SESSION['resp'] == 'STE'){ echo "selected='selected'"; } } ?>>STE</option>
							<option value="MAG" <?php if(isset($_SESSION["resp"])) { if($_SESSION['resp'] == 'MAG'){ echo "selected='selected'"; } } ?>>MAG</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-2 align-self-center">
		<input type="submit" name="charger_grp" value="CHARGER" class="btn btn-primary" style="min-width:100%;"><br>
		<input type="submit" name="charger_calculer" value="CHARGER ET CALCULER" class="btn btn-secondary" style="min-width:100%; margin-top:2%;">
		<input type="submit" name="vider" value="VIDER" class="btn btn-warning" style="min-width:100%; margin-top:2%;">
	</div>
	<div class="col-sm-2" style="border-left: 1px solid #0b242436;">
		<label class="label-title" style="margin: 0;">Facturation</label>
		<div class="ligne" style="width: 12%;"></div>
		<div class="form-inline" style="margin: 2% 0 0 0;">
			<label for="n_four" class="col-sm-5 col-form-label" style="padding-left:0;">N° Facture</label>
			<div class="col-sm-7" style="padding:0">
				<input type="text" class="form-control" name="n_fact" value="<?php if(isset($_SESSION["n_fact"])) { echo $_SESSION["n_fact"]; } ?>" style="width:100%;">
			</div>
		</div>
		<div class="form-inline">
			<input type="submit" name="charger_facture" value="CHARGER" class="btn btn-primary" style="margin-top:2%; width: 50%;"><br>
			<input type="submit" name="appliquer_facture" value="APPLIQUER" class="btn btn-secondary" style="margin-top:2%; margin-left:2%;">
		</div>
	</div>
	<div class="col-sm-2 text-right align-self-end">
		<input type="submit" name="exporter_grp" value="EXPORTER" class="btn btn-secondary" style="width: 70%;margin-bottom: 4%;">
	</div>
</div>
<hr>
<div class="tableau" style="height: 570px;">
	<table class="table" id="trie_table_grp2">
		<thead>
			<tr>
				<th>N°</th>
				<th>Etat</th>
				<th>Libelle</th>
				<th>Date</th>
				<th>Resp</th>
<?php
				if(!empty($_POST["charger_calculer"]) || !empty($_POST["charger_mois"]))
				{
?>
				<th>Attaché</th>
				<th>Groupé</th>
				<th>Prix P</th>
				<th>Prix V</th>
				<th>Livrable</th>
				<th>Livrée</th>
				<th>Terminée</th>
				<th>Ann P</th>
				<th>% Ann P</th>
				<th>Ann.</th>
				<th>% Ann.</th>
<?php
				}
?>
			</tr>
		</thead>
		<tbody>
<?php
		if(!empty($_POST["charger_calculer"]) || !empty($_POST["charger_mois"]))
		{
			while ($regroupement = mysqli_fetch_array($res))
			{
				$date = $regroupement["date_grp"];
				$date = date_create($date);
				$date_grp = date_format($date,"d/m/Y");
?>
			<tr class="select groupement_commande">
				<input type="hidden" name="n_grp" value="<?= $regroupement["groupe_cmd"]; ?>">
				<td><?= $regroupement["groupe_cmd"]; ?></td>
				<td class="text-center"><?= $regroupement["statut"]; ?></td>
				<td class="text-center"><?= $regroupement["libelle"]; ?></td>
				<td class="text-center"><?= $date_grp; ?></td>
				<td class="text-center"><?= $regroupement["responsable"]; ?></td>
				<td class="text-center"><?= $regroupement["attachee"]; ?></td>
				<td class="text-center"><?= $regroupement["groupee"]; ?></td>
				<td class="text-center"><?= $regroupement["p_propose"]; ?></td>
				<td class="text-center"><?= $regroupement["p_valide"]; ?></td>
				<td class="text-center"><?= $regroupement["livrable"]; ?></td>
				<td class="text-center"><?= $regroupement["livree"]; ?></td>
				<td class="text-center"><?= $regroupement["terminee"]; ?></td>
				<td class="text-center"><?= $regroupement["annulp"]; ?></td>
				<td class="text-center"></td>
				<td class="text-center"><?= $regroupement["annul"]; ?></td>
				<td class="text-center"></td>
			</tr>
<?php
			}
		}
		else
		{
			while ($regroupement = mysqli_fetch_array($res))
			{
				$date = $regroupement["date_grp"];
				$date = date_create($date);
				$date_grp = date_format($date,"d/m/Y");
?>
			<tr class="select groupement_commande">
				<input type="hidden" name="n_grp" value="<?= $regroupement["id"]; ?>">
				<td><?= $regroupement["id"]; ?></td>
				<td><?= $regroupement["statut"]; ?></td>
				<td><?= $regroupement["libelle"]; ?></td>
				<td><?= $date_grp; ?></td>
				<td><?= $regroupement["responsable"]; ?></td>
			</tr>
<?php
			}
		}
?>
		</tbody>
	</table>
</div>
<?php
if(!empty($_POST["charger_calculer"]) || !empty($_POST["charger_mois"]))
{
?>
<div class="tableau" style="height: auto;">
	<table class="table" style="margin-bottom: 0;">
		<thead>
			<th style="width: 6%;border-bottom: none;">En cours</th>
			<th style="width: 4%;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $en_cours; ?>" class="form-control input-custom" ></th>
			<th style="width: 5%;border-bottom: none;">Validé</th>
			<th style="width: 4%;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $valide; ?>" class="form-control input-custom"></th>
			<th style="width: 6%;border-bottom: none;">Projection</th>
			<th style="width: 4%;padding: 0;border-bottom: none;"><input type="text" name="" value="0" class="form-control input-custom" ></th>
			<th style="width: 8.3%;border-bottom: none;">Totaux m3</th>
			<th style="width:89.84px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $attachee["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
			<th style="width:87.34px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $groupee["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
			<th style="width:74.38px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $p_propose["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
			<th style="width:74.80px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $p_valide["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
			<th style="width:91.80px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $livrable["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
			<th style="width:78.05px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $livree["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
			<th style="width:100.47px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $terminee["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
			<th style="width:75.39px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $annulp["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
			<th style="width:90.36px;padding: 0;border-bottom: none;"><input type="text" name="" value="0" class="form-control input-custom" style="text-align:center"></th>
			<th style="width:67.06px;padding: 0;border-bottom: none;"><input type="text" name="" value="0" class="form-control input-custom" style="text-align:center"></th>
			<th style="width:82.27px;padding: 0;border-bottom: none;"><input type="text" name="" value="0" class="form-control input-custom" style="text-align:center"></th>
		</thead>
	</table>
</div>
<?php
}
?>

<!-- <div class="row">
	<div class="col-sm-2" style="max-width: 14%;">
		<input type="submit" name="nouveau_grp" value="NOUVEAU" class="btn btn-primary" style="width: 100%;margin-bottom: 2%;">
	</div>
	<div class="col-sm-2" style="max-width: 14%;">
		<input type="submit" name="charger_mois" value="CHARGER MOIS" class="btn btn-warning" style="width: 100%;margin-bottom: 2%;">
	</div>
	<div class="col-sm-2" style="max-width: 14%;">
		<input type="submit" name="dupliquer" value="DUPLIQUER" class="btn btn-secondary" style="width: 100%;margin-bottom: 2%;">
	</div>
	<div class="col-sm-7 text-right" style="max-width: 58%;">
		<input type="submit" name="ajouter_client_grp" value="AJOUTER" class="btn btn-primary fermer-modal" style="width: 239px;margin-bottom: 2%;">
	</div>
</div> -->

<!--  -->
<script src="/admin/js/select2.min.js"></script>
<!-- <script src="/admin/js/script_commandes.js" charset="utf-8"></script> -->
