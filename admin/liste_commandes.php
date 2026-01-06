<style media="screen">
.ligne-menu {width: 320px!important;}
</style>
<link href="css/select2.min.css" rel="stylesheet" />
<?php
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

error_reporting(E_ALL);
ini_set("display_errors", 1);

$title = 'Liste des commandes';
$title_page = 'Liste des commandes';
ob_start();



include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_groupements.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_commandes.php";
unset($_SESSION['facture_saisie']);
$res_four = getFournisseursListe($co_pmp);
if (!empty($_SESSION["fournisseurs"]))
{
	$res_zone = getZoneFournisseurId($co_pmp, $_SESSION["fournisseurs"]);
	$zone_1 = getZoneFournisseurLimitId($co_pmp, $_SESSION["fournisseurs"]);

	if(isset($_POST["zone_fournisseur"])) { $_SESSION["zone_fournisseur"] = $_POST["zone_fournisseur"]; } else { $_SESSION["zone_fournisseur"] = $zone_1["id"]; }
}
if (!empty($_POST["fournisseurs"]))
{
	$_SESSION["n_dep_cmd"] = $_POST["n_dep"];
	$_SESSION["etat_1"] = $_POST["etat_1"];
	$_SESSION["etat_2"] = $_POST["etat_2"];
	$_SESSION["fournisseurs"] = $_POST["fournisseurs"];
	if (!empty($_SESSION["fournisseurs"]))
	{
		$res_zone = getZoneFournisseurId($co_pmp, $_SESSION["fournisseurs"]);
		$zone_1 = getZoneFournisseurLimitId($co_pmp, $_SESSION["fournisseurs"]);

		if(isset($_POST["zone_fournisseur"])) { $_SESSION["zone_fournisseur"] = $_POST["zone_fournisseur"]; } else { $_SESSION["zone_fournisseur"] = $zone_1["id"]; }
	}
}
if (!empty($_POST["charger_commandes"]))
{
	$_SESSION["n_dep_cmd"] = $_POST["n_dep"];
	$_SESSION["etat_1"] = $_POST["etat_1"];
	$_SESSION["etat_2"] = $_POST["etat_2"];
	$_SESSION["fournisseurs"] = $_POST["fournisseurs"];
  $_SESSION["date_min_co"] = $_POST["date_min_co"];
  $_SESSION["date_max_co"] = $_POST["date_max_co"];

	if (!empty($_SESSION["fournisseurs"]))
	{
		$res_zone = getZoneFournisseurId($co_pmp, $_SESSION["fournisseurs"]);
		$zone_1 = getZoneFournisseurLimitId($co_pmp, $_SESSION["fournisseurs"]);

		if(isset($_POST["zone_fournisseur"])) { $_SESSION["zone_fournisseur"] = $_POST["zone_fournisseur"]; } else { $_SESSION["zone_fournisseur"] = $zone_1["id"]; }
	}

	if (!empty($_POST["n_cmd"]))
	{
		$_SESSION["n_cmd"] = $_POST["n_cmd"];
		$n_cmd = $_POST["n_cmd"];
		header('Location: /admin/gestion_client_commande.php?id_cmd=' . $n_cmd .'&return=cmdes');
	}
	elseif (!empty($_SESSION["fournisseurs"]) && !empty($_SESSION["zone_fournisseur"]))
	{
		$res_cmd = getCommandesFournisseurZone($co_pmp, $_SESSION["zone_fournisseur"], $_SESSION["etat_1"], $_SESSION["etat_2"]);
	}
	else
	{
		$res_cmd = getFiltreCommandesActifs($co_pmp);
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
elseif (!empty($_SESSION["n_client"]) || !empty($_SESSION["cp_client"]) || !empty($_SESSION["nom_client"]) || !empty($_SESSION["p_client"]) || !empty($_SESSION["tel_client"]) || !empty($_SESSION["email_client"]) || !empty($_SESSION["date_min_co"]) || !empty($_SESSION["date_max_co"]))
{
	$res_cmd = getCommandeRapideActifs($co_pmp);

	if(!empty($_POST["exporter_cmd"]))
	{
		exporterListeCommandes($co_pmp, $res_cmd);
		$res_cmd = getCommandeRapideActifs($co_pmp);
	}
}
elseif (!empty($_SESSION["fournisseurs"]) && !empty($_SESSION["zone_fournisseur"]))
{
	$res_cmd = getCommandesFournisseurZone($co_pmp, $_SESSION["zone_fournisseur"], $_SESSION["etat_1"], $_SESSION["etat_2"]);
	if(!empty($_POST["exporter_cmd"]))
	{
		exporterListeCommandes($co_pmp, $res_cmd);
		$res_cmd = getCommandesFournisseurZone($co_pmp, $_SESSION["zone_fournisseur"], $_SESSION["etat_1"], $_SESSION["etat_2"]);
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

	$res_cmd = getCommandeRapideActifs($co_pmp);

	if(!empty($_POST["exporter_cmd"]))
	{
		exporterListeCommandes($co_pmp, $res_cmd);
		$res_cmd = getCommandeRapideActifs($co_pmp);
	}
}
elseif (!empty($_SESSION["n_dep_cmd"]) || !empty($_SESSION["etat_1"]) || !empty($_SESSION["etat_2"]))
{
	$res_cmd = getFiltreCommandesActifs($co_pmp);

	if(!empty($_POST["exporter_cmd"]))
	{
		exporterListeCommandes($co_pmp, $res_cmd);
		$res_cmd = getFiltreCommandesActifs($co_pmp);
	}
}

if (isset($message))
{
?>
<div class="toast <?= $message_type; ?>">
	<div class="message-icon  <?= $message_type; ?>-icon">
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
<div class="bloc">
	<div class="menu-bloc">
		<a href="#" class="active">Liste</a>
		<a href="commande_par_departement.php">Calcul par département</a>
		<a href="commande_par_fournisseur.php">Calcul par fournisseur</a>
		<a href="statistiques_commande.php">Statistiques</a>
	</div>
	<div class="filtre">
		<form method="post" id="FormID">
			<div class="row">
				<div class="col-sm-4">
					<label class="label-title" style="margin: 0;">Selection des commandes</label>
					<div class="ligne"></div>
					<div class="form-inline" style="margin: 2% 0 0 0;">
						<label for="n_dep" class="col-sm-7 col-form-label" style="padding-left:0;">N° département</label>
						<div class="col-sm-5" style="padding:0">
							<input type="text" name="n_dep" value="<?php if(!empty($_POST["vider_cmd"])) { echo ""; } elseif(isset($_POST["n_dep"]))  { echo $_POST["n_dep"]; } elseif(isset($_SESSION["n_dep_cmd"])) { echo $_SESSION["n_dep_cmd"]; } ?>" class="form-control" style="width:100%;">
						</div>
					</div>
          <div class="row">
            <div class="col-sm-6">
              <label for="date_min_co" class="col-sm-12 col-form-label" style="padding-left:0;">Date entre le</label>
              <input type="date" name="date_min_co" class="form-control" value="" style="width:100%;">
            </div>
            <div class="col-sm-6">
              <label for="date_max_co" class="col-sm-12 col-form-label" style="padding-left:0;">et le 'inclus'</label>
              <input type="date" name="date_max_co" class="form-control" value="" style="width:100%;">
            </div>
          </div>
				</div>
				<div class="col-sm-4">
					<label class="label-title" style="margin: 0;">Statut de la commande</label>
					<div class="ligne"></div>
					<div class="form-inline" style="margin: 2% 0 0 0;">
						<label for="etat_1" class="col-sm-5 col-form-label" style="padding-left:0;">Du statut</label>
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
								<option value="99" <?php if(isset($_SESSION["etat_1"])) { if($_SESSION['etat_1'] == '99'){ echo "selected='selected'"; } } ?>>Annulée / Compte désactivé</option>
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
								<option value="99" <?php if(isset($_SESSION["etat_2"])) { if($_SESSION['etat_2'] == '99'){ echo "selected='selected'"; } } ?>>Annulée / Compte désactivé</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-4" style="border-left: 1px solid #0b242436;">
					<label class="label-title" style="margin: 0;">Recherche sur zone fournisseur</label>
					<div class="ligne"></div>
					<div class="form-inline" style="margin: 2% 0 0 0;">
						<label for="n_four" class="col-sm-4 col-form-label" style="padding-left:0;">Fournisseur</label>
						<div class="col-sm-7" style="padding:0">
							<select class="js-example-basic-single form-control" onchange="myFunction(this.value)" name="fournisseurs" style="width:100%;">
								<option value="0"></option>
			<?php
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
						<label for="n_four" class="col-sm-4 col-form-label" style="padding-left:0;">Zone</label>
						<div class="col-sm-7" style="padding:0">
							<select class="form-control input-custom" name="zone_fournisseur" style="width:100%;">
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

			</div>
      <div class="row">
        <div class="col-sm-4">

        </div>
        <div class="col-sm-4">

        </div>
        <div class="text-right col-sm-4">
          <div class="form-inline" style="margin-top: 2%;">
            <div class="col-sm-6">
              <input type="submit" name="charger_commandes" value="CHARGER" class="btn btn-primary " style="min-width:70%;"><br>
            </div>
            <div class="col-sm-6">
              <input type="submit" name="vider_cmd" value="VIDER" class="btn btn-secondary col-sm-4" style="min-width:70%; margin-top:2%;margin-bottom: 4%;">
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="selGrp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 85%;">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Sélectionner un groupement</h5>
              <button type="button" class="btn-close b-close" data-bs-dismiss="modal" aria-bs-label="Close"> <i class="fa-solid fa-xmark"></i> </button>
            </div>
            <div class="modal-body">
              <?php include 'form/form_liste_groupements.php'; ?>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="n_id_grp" id="n_id_grp" value="">
              <button type="button" class="btn btn-secondary b-close" data-bs-dismiss="modal">Fermer</button>
              <input type="submit" name="ajouter_liste_grp" class="btn btn-primary valider_four" value="VALIDER">
            </div>
          </div>
        </div>
      </div>
			<hr>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-inline" style="margin: 2% 0 2% 0;">
            <label for="nb_litres" class="col-sm-4 col-form-label" style="padding-left:0;">Nombre de litres</label>
            <div class="col-sm-2" style="padding:0">
              <input type="text" name="nb_litres" id="nb_litres" class="form-control" value="">
            </div>
          </div>
        </div>
        <div class="col-sm-6 text-right">
          <input type="submit" name="toute_commande" class="btn btn-secondary" value="AFFICHER TOUTES LES COMMANDES" style="width:100%;">
        </div>
      </div>
			<div class="tableau" style="height:450px;">
				<table class="table" id="trie_table">
					<thead>
						<tr>
							<th><i class="fal fa-sort"></i></th>
							<th style="white-space: nowrap;padding: 8px 10px;"> Nb Litre</th>
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
              <th style="white-space: nowrap;padding: 8px 10px;">Fournisseur</th>
							<th style="white-space: nowrap;padding: 8px 10px;">Net</th>
						</tr>
					</thead>
					<tbody>
<?php
				$i = 0;
				if (isset($res_cmd))
				{
					while ($commande = mysqli_fetch_array($res_cmd))
					{
						if ($commande["cmd_typefuel"] == 1){ $type = 'O';}
						if ($commande["cmd_typefuel"] == 2){ $type = 'S';}
						if ($commande["cmd_typefuel"] == 3){ $type = 'GNR';}
?>
						<tr class="select commande">
							<input type="hidden" name="id_cmde[]" value="<?= $commande["num_cmd"]; ?>">
							<td></td>
							<td class="nb_l"><?= $commande["cmd_qte"]; ?></td>
							<td class="text-center"><?= $type; ?></td>
							<td><?= date_format(new DateTime($commande['cmd_dt']), 'd/m/Y' ); ?></td>
							<td><?= $commande["code_postal"]; ?></td>
							<td><?= $commande["ville"]; ?></td>
							<td><?= $commande["name"]; ?></td>
							<td><?= $commande["prenom"]; ?></td>
							<td><?= $commande["email"]; ?></td>
							<td><?= $commande["tel_fixe"]; ?></td>
							<td><?= $commande["tel_port"]; ?></td>
							<td><input type="hidden" name="user_id_<?php print $i++; ?>" value=" <?= $commande["user_id"]; ?>"> <?= $commande["user_id"]; ?></td>
							<td><?= $commande["num_cmd"]; ?></td>
							<td class="text-center"><?= $commande["cmd_status"]; ?></td>
              <td>
<?php
              if(isset($commande["nom_four"]))
              {
                echo $commande["nom_four"];
              }
?>
              </td>
							<td></td>
						</tr>
<?php
					}
				}
?>
					</tbody>
				</table>
				<input type="hidden" name="nb_commande" value="<?= $i; ?>">
			</div>
			<div class="row">
				<div class="col-sm-6">
					<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#rechercheRapide" name="button">RECHERCHE RAPIDE</button>
					<div class="modal fade" id="rechercheRapide" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" style="max-width: 25%;">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Recherche rapide d'une commande</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-bs-label="Close"> <i class="fa-solid fa-xmark"></i> </button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-sm-6">
											<label for="n_client" class="col-form-label">N° Client</label>
											<input type="text" name="n_client" value="" class="form-control" style="width:100%;">
										</div>
										<div class="col-sm-6">
											<label for="cp_client" class="col-form-label">CP</label>
											<input type="text" name="cp_client" value="" class="form-control" style="width:100%;">
										</div>
										<div class="col-sm-6">
											<label for="cp_client" class="col-form-label">Nom</label>
											<input type="text" name="nom_client" value="" class="form-control" style="width:100%;">
										</div>
										<div class="col-sm-6">
											<label for="cp_client" class="col-form-label">Prenom</label>
											<input type="text" name="p_client" value="" class="form-control" style="width:100%;">
										</div>
										<div class="col-sm-12">
											<label for="cp_client" class="col-form-label">Téléphone</label>
											<input type="text" name="tel_client" value="" class="form-control" style="width:100%;">
										</div>
										<div class="col-sm-12">
											<label for="cp_client" class="col-form-label">Email</label>
											<input type="email" name="email_client" value="" class="form-control" style="width:100%;">
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<input type="submit" name="recherche_rapide_commande" class="btn btn-primary valider_four" value="VALIDER">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 text-right">
					<input type="submit" name="exporter_cmd" value="EXPORTER" class="btn btn-secondary" style="width:209px;">
				</div>
			</div>
		</form>
	</div>

</div>

<?php
$content = ob_get_clean();
require('template.php');
?>
<script>
	   function myFunction(val) {
		  console.log("Entered Value is: " + val);
		  var frm = document.getElementById ("FormID");

      		frm.submit();
	   }
</script>
<script src="/admin/js/select2.min.js"></script>
<script src="/admin/js/script_commandes.js" charset="utf-8"></script>
