<style media="screen">
.ligne-menu {width: 35%!important;}
.bouton-ajouter {margin-left: 26%!important;}
.info-icon{width: 25px!important}
.info {width: 600px!important}
</style>
<?php
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

$title = 'Liste des regroupements';
$title_page = 'Liste des regroupements';

if(isset($_SESSION['facture_saisie'])) { unset($_SESSION['facture_saisie']); }

if (isset($_GET["return"]) == 'fournisseur')
{
	$return = true;
	$link = '/admin/details_fournisseur.php?id_four=' . $_GET["id_four"];
?>
<style media="screen">
.menu > h1, .ligne-menu {margin-left:6%;}
</style>
<?php
}

$button = true;
$link_button = '/admin/details_groupement.php';
$button_name = 'NOUVEAU GROUPEMENT';
$icon = '<i class="fas fa-plus"></i>';
ob_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_commandes.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_groupements.php";
include_once __DIR__ . "/inc/pmp_inc_groupements.php";

$res_four = getFournisseursListe($co_pmp);

if(!empty($_POST["charger_grp"]))
{
	unset($_SESSION["etat_four"]);
	unset($_SESSION["etat_four2"]);
	unset($_SESSION["resp"]);
	unset($_SESSION["fournisseur_id"]);
	unset($_SESSION["date_min"]);
	unset($_SESSION["date_max"]);
	unset($_SESSION["n_fact"]);
	unset($_SESSION["charger_calculer"]);
	unset($_SESSION["charger_fact_calculer"]);
	unset($_SESSION["charger_mois"]);

	$_SESSION["etat_four"] = $_POST["etat_four"];
	$_SESSION["etat_four2"] = $_POST["etat_four2"];
	$_SESSION["resp"] = $_POST["resp"];

	if(!empty($_POST["four_id"]))
	{
		$_SESSION["fournisseur_id"] = $_POST["four_id"];
	}
	else
	{
		unset($_SESSION["fournisseur_id"]);
	}
	if(!empty($_POST["date_min"]))
	{
		$_SESSION["date_min"] = $_POST["date_min"];
	}
	if(!empty($_POST["date_max"]))
	{
		$_SESSION["date_max"] = $_POST["date_max"];
	}

	$res = getFiltresGroupements($co_pmp);
	$_SESSION["charger_grp"] = $_POST["charger_grp"];
}
elseif (!empty($_POST["vider"]))
{
	unset($_SESSION["etat_four"]);
	unset($_SESSION["etat_four2"]);
	unset($_SESSION["resp"]);
	unset($_SESSION["fournisseur_id"]);
	unset($_SESSION["date_min"]);
	unset($_SESSION["date_max"]);
	unset($_SESSION["n_fact"]);
	unset($_SESSION["charger_calculer"]);
	unset($_SESSION["charger_fact_calculer"]);
	unset($_SESSION["charger_mois"]);
	unset($_SESSION["charger_grp"]);
	$res = getListeRegroupementsCréer($co_pmp);
	// if(!empty($_POST["exporter_grp"]))
	// {
	// 	ExporterListeGrpt($co_pmp, $res);
	// 	$res = getListeRegroupementsCréer($co_pmp);
	// }
}
elseif (!empty($_POST["charger_facture"]))
{
	unset($_SESSION["etat_four"]);
	unset($_SESSION["etat_four2"]);
	unset($_SESSION["resp"]);
	unset($_SESSION["fournisseur_id"]);
	unset($_SESSION["date_min"]);
	unset($_SESSION["date_max"]);
	unset($_SESSION["n_fact"]);
	unset($_SESSION["charger_calculer"]);
	unset($_SESSION["charger_fact_calculer"]);
	unset($_SESSION["charger_grp"]);
	unset($_SESSION["charger_mois"]);

	$terminer_grp = "ok";
	if(!empty($_POST["n_fact"]))
	{
		$_SESSION["n_fact"] = $_POST["n_fact"];
		$res = getFiltresGroupementsFacture($co_pmp, $_SESSION["n_fact"]);
		// if(!empty($_POST["exporter_grp"]))
		// {
		// 	ExporterListeGrpt($co_pmp, $res);
		// 	$res = getFiltresGroupementsFacture($co_pmp, $_SESSION["n_fact"]);
		// }
	}

	$_SESSION["charger_facture"] = $_POST["charger_facture"];

}
elseif(!empty($_POST["charger_calculer"]))
{
	unset($_SESSION["charger_mois"]);
	unset($_SESSION["charger_grp"]);

	$_SESSION["etat_four"] = $_POST["etat_four"];
	$_SESSION["etat_four2"] = $_POST["etat_four2"];
	$_SESSION["resp"] = $_POST["resp"];

	if(!empty($_POST["four_id"]))
	{
		$_SESSION["fournisseur_id"] = $_POST["four_id"];
	}

	if(!empty($_POST["date_min"]))
	{
		$_SESSION["date_min"] = $_POST["date_min"];
	}
	if(!empty($_POST["date_max"]))
	{
		$_SESSION["date_max"] = $_POST["date_max"];
	}

	$res = getFiltresGroupementsCalculer($co_pmp);
	$attachee = GetStatsGroupement($co_pmp, '12');
	$groupee = GetStatsGroupement($co_pmp, '15');
	$p_propose = GetStatsGroupement($co_pmp, '17');
	$p_valide = GetStatsGroupement($co_pmp, '20');
	$livrable = GetStatsGroupement($co_pmp, '25');
	$livree = GetStatsGroupement($co_pmp, '30');
	$term = GetStatsGroupement($co_pmp, '40');
	$annul = GetStatsGroupement($co_pmp, '52');
	$annulp = GetStatsGroupement($co_pmp, '55');
	$en_cours = $attachee["statut"] + $groupee["statut"] + $p_propose["statut"];
	$valide = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $term["statut"];

	if($valide == 0 && $en_cours == 0)
	{
		$projection = 0;
	}
	else
	{
		$projection = (($valide) / ($valide + $annulp["statut"]) * $en_cours) + $valide;
	}

	$_SESSION["charger_calculer"] = $_POST["charger_calculer"];
}
elseif (!empty($_POST["charger_fact_calculer"]))
{
	unset($_SESSION["etat_four"]);
	unset($_SESSION["etat_four2"]);
	unset($_SESSION["resp"]);
	unset($_SESSION["fournisseur_id"]);
	unset($_SESSION["date_min"]);
	unset($_SESSION["date_max"]);
	unset($_SESSION["n_fact"]);
	unset($_SESSION["charger_calculer"]);
	unset($_SESSION["charger_fact_calculer"]);
	unset($_SESSION["charger_grp"]);
	unset($_SESSION["charger_mois"]);
	unset($_SESSION["charger_facture"]);

	$_SESSION["n_fact"] = $_POST["n_fact"];

	$res = getFiltresGroupementsCalculerFacture($co_pmp, $_SESSION["n_fact"]);
	$attachee = GetStatsGroupementFact($co_pmp, '12', $_SESSION["n_fact"]);
	$groupee = GetStatsGroupementFact($co_pmp, '15', $_SESSION["n_fact"]);
	$p_propose = GetStatsGroupementFact($co_pmp, '17', $_SESSION["n_fact"]);
	$p_valide = GetStatsGroupementFact($co_pmp, '20', $_SESSION["n_fact"]);
	$livrable = GetStatsGroupementFact($co_pmp, '25', $_SESSION["n_fact"]);
	$livree = GetStatsGroupementFact($co_pmp, '30', $_SESSION["n_fact"]);
	$term = GetStatsGroupementFact($co_pmp, '40', $_SESSION["n_fact"]);
	$annul = GetStatsGroupementFact($co_pmp, '52', $_SESSION["n_fact"]);
	$annulp = GetStatsGroupementFact($co_pmp, '55', $_SESSION["n_fact"]);

	$en_cours = $attachee["statut"] + $groupee["statut"] + $p_propose["statut"];
	$valide = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $term["statut"];

	if($valide == 0 && $en_cours == 0)
	{
		$projection = 0;
	}
	else
	{
		$projection = (($valide) / ($valide + $annulp["statut"]) * $en_cours) + $valide;
	}

	$_SESSION["charger_fact_calculer"] = $_POST["charger_fact_calculer"];
}
elseif (!empty($_POST["charger_mois"]))
{
	unset($_SESSION["charger_calculer"]);
	unset($_SESSION["charger_fact_calculer"]);
	unset($_SESSION["charger_grp"]);

	$date = new DateTime();
    $dateDeb = $date -> format('Y-m-01');
    $dateFin = $date -> format('Y-m-t');

	$_SESSION["date_min"] = $dateDeb;
	$_SESSION["date_max"] = $dateFin;
	$_SESSION["etat_four"] = '10';
	$_SESSION["etat_four2"] = '40';

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
	$projection = (($valide) / ($valide + $annulp["statut"]) * $en_cours) + $valide;
	$_SESSION["charger_mois"] = $_POST["charger_mois"];
}

elseif(isset($_SESSION["charger_calculer"]))
{
	$res = getFiltresGroupementsCalculer($co_pmp);
	$attachee = GetStatsGroupement($co_pmp, '12');
	$groupee = GetStatsGroupement($co_pmp, '15');
	$p_propose = GetStatsGroupement($co_pmp, '17');
	$p_valide = GetStatsGroupement($co_pmp, '20');
	$livrable = GetStatsGroupement($co_pmp, '25');
	$livree = GetStatsGroupement($co_pmp, '30');
	$term = GetStatsGroupement($co_pmp, '40');
	$annul = GetStatsGroupement($co_pmp, '52');
	$annulp = GetStatsGroupement($co_pmp, '55');
	$en_cours = $attachee["statut"] + $groupee["statut"] + $p_propose["statut"];
	$valide = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $term["statut"];

	if($valide == 0 && $en_cours == 0)
	{
		$projection = 0;
	}
	else
	{
		$projection = (($valide) / ($valide + $annulp["statut"]) * $en_cours) + $valide;
	}

	if(!empty($_POST["exporter_grp"]))
	{
		ExporterListeGrptStats($co_pmp, $res);
	}
}
elseif (isset($_SESSION["charger_mois"]))
{
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
	$projection = (($valide) / ($valide + $annulp["statut"]) * $en_cours) + $valide;

	if(!empty($_POST["exporter_grp"]))
	{
		ExporterListeGrptStats($co_pmp, $res);
	}
}
elseif (isset($_SESSION["charger_grp"]))
{
	$res = getFiltresGroupements($co_pmp);
	if(!empty($_POST["exporter_grp"]))
	{
		ExporterListeGrpt($co_pmp, $res);
	}
}
elseif (isset($_SESSION["charger_fact_calculer"]))
{
	$res = getFiltresGroupementsCalculerFacture($co_pmp, $_SESSION["n_fact"]);
	$attachee = GetStatsGroupementFact($co_pmp, '12', $_SESSION["n_fact"]);
	$groupee = GetStatsGroupementFact($co_pmp, '15', $_SESSION["n_fact"]);
	$p_propose = GetStatsGroupementFact($co_pmp, '17', $_SESSION["n_fact"]);
	$p_valide = GetStatsGroupementFact($co_pmp, '20', $_SESSION["n_fact"]);
	$livrable = GetStatsGroupementFact($co_pmp, '25', $_SESSION["n_fact"]);
	$livree = GetStatsGroupementFact($co_pmp, '30', $_SESSION["n_fact"]);
	$term = GetStatsGroupementFact($co_pmp, '40', $_SESSION["n_fact"]);
	$annul = GetStatsGroupementFact($co_pmp, '52', $_SESSION["n_fact"]);
	$annulp = GetStatsGroupementFact($co_pmp, '55', $_SESSION["n_fact"]);

	$en_cours = $attachee["statut"] + $groupee["statut"] + $p_propose["statut"];
	$valide = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $term["statut"];

	if($valide == 0 && $en_cours == 0)
	{
		$projection = 0;
	}
	else
	{
		$projection = (($valide) / ($valide + $annulp["statut"]) * $en_cours) + $valide;
	}
}
elseif (!empty($_SESSION["charger_facture"]))
{
	$terminer_grp = "ok";
	$res = getFiltresGroupementsFacture($co_pmp, $_SESSION["n_fact"]);
	// if(!empty($_POST["exporter_grp"]))
	// {
	// 	ExporterListeGrpt($co_pmp, $res);
	// 	$res = getFiltresGroupementsFacture($co_pmp, $_SESSION["n_fact"]);
	// }
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

if(isset($_GET["grp"]))
{
	$message_type = "info";
	$message_icone = "fa-exclamation";
	$message_titre = "Info";
	$message = "Le groupement a été supprimé avec succès";
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
	<div class="filtre">
		<form method="post">
			<div class="row">
				<div class="col-sm-2">
					<label class="label-title" style="margin: 0;">Statut du regroupement</label>
					<div class="ligne"></div>
					<div class="form-inline" style="margin: 5px 0 0 0;">
						<label for="etat_four" class="col-sm-8 col-form-label" style="padding-left:0;">A partir du statut</label>
						<div class="col-sm-4" style="padding:0">
							<select class="form-control input-custom" name="etat_four">
								<option value="5" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '5'){ echo "selected='selected'"; } } ?>>5 - Prévu</option>
								<option value="10" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '10'){ echo "selected='selected'"; } } else { echo "selected='selected'"; } ?>>10 - Créé</option>
								<option value="15" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '15'){ echo "selected='selected'"; } } ?>>15 - Envoyé</option>
								<option value="30" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '30'){ echo "selected='selected'"; } } ?>>30 - Livré</option>
								<option value="33" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '33'){ echo "selected='selected'"; } } ?>>33 - A facturer</option>
								<option value="37" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '37'){ echo "selected='selected'"; } } ?>>37 - Facturé</option>
								<option value="37" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '40'){ echo "selected='selected'"; } } ?>>40 - Terminé</option>
								<option value="50" <?php if(isset($_SESSION["etat_four"])) { if($_SESSION['etat_four'] == '50'){ echo "selected='selected'"; } } ?>>50 - Annulé</option>
							</select>
						</div>
					</div>
					<div class="form-inline" style="margin: 5px 0 5px 0;">
						<label for="etat_four2" class="col-sm-8 col-form-label" style="padding-left:0;">Jusqu'au statut</label>
						<div class="col-sm-4" style="padding:0">
							<select class="form-control input-custom" name="etat_four2">
								<option value="5" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '5'){ echo "selected='selected'"; } } ?>>5 - Prévu</option>
								<option value="10" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '10'){ echo "selected='selected'"; } } else { echo "selected='selected'"; } ?>>10 - Créé</option>
								<option value="15" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '15'){ echo "selected='selected'"; } } ?>>15 - Envoyé</option>
								<option value="30" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '30'){ echo "selected='selected'"; } } ?>>30 - Livré</option>
								<option value="33" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '33'){ echo "selected='selected'"; } } ?>>33 - A facturer</option>
								<option value="37" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '37'){ echo "selected='selected'"; } } ?>>37 - Facturé</option>
<?php
								if(!empty($_POST["charger_mois"]))
								{
?>
								<option value="37" <?php if(isset($_POST["charger_mois"])) { echo "selected='selected'"; } ?>>40 - Terminé</option>
<?php
								}
								elseif (isset($_GET["id_four"]))
								{
?>
								<option value="37" <?php if(isset($_GET["id_four"])) { echo "selected='selected'"; } ?>>40 - Terminé</option>
<?php
								}
								else
								{
?>
								<option value="37" <?php if(isset($_SESSION["etat_four2"])) { if($_SESSION['etat_four2'] == '40'){ echo "selected='selected'"; } } ?>>40 - Terminé</option>
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
							<div class="form-inline" style="margin: 5px 0 0 0;">
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
											<option value="<?= $fournisseur["id"]; ?>" <?php if(isset($_SESSION["fournisseur_id"])) { if($_SESSION['fournisseur_id'] == $fournisseur["id"]){ echo "selected='selected'"; } } ?>><?= $fournisseur["nom"]; ?></option>
<?php
										}
									}
?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-inline" style="margin: 5px 0 0 0;">
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
					<div class="ligne"></div>
					<div class="form-inline" style="margin: 5px 0 0 0;">
						<label for="n_four" class="col-sm-5 col-form-label" style="padding-left:0;">N° Facture</label>
						<div class="col-sm-7" style="padding:0">
							<input type="text" class="form-control" name="n_fact" value="<?php if(isset($_SESSION["n_fact"])) { echo $_SESSION["n_fact"]; } ?>" style="width:100%;">
						</div>
					</div>
					<div class="form-inline">
						<input type="submit" name="charger_facture" value="CHARGER" class="btn btn-primary" style="margin-top:5px; width: 50%;"><br>
						<button type="button" data-bs-toggle="modal" data-bs-target="#AppliquerFacture" name="button" class="btn btn-secondary" style="margin-top:5px; margin-left:2%;">APPLIQUER</button>
						<div class="modal fade" id="AppliquerFacture" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Appliquer ce numéro de facture à la liste des groupements ?</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i> </button>
									</div>
									<div class="modal-body">
										<?php if(isset($_SESSION["n_fact"])) { echo $_SESSION["n_fact"]; } ?>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
										<input type="submit" name="appliquer_facture" class="btn btn-primary" value="Appliquer">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="row" style="height: 112px;">
						<div class="col-sm-12 align-self-end" style="height: 62px;">
							<input type="submit" name="charger_fact_calculer" value="CHARGER ET CALCULER" class="btn btn-secondary" style="min-width:100%; margin-top: 29px;">
						</div>
						<div class="col-sm-4 text-right align-self-end" style=padding:0>
							<button type="button" data-bs-toggle="modal" data-bs-target="#AppliquerTerminer" name="button" class="btn btn-outline-primary" <?php if(isset($terminer_grp)) { if($terminer_grp == "ok") { echo ""; } else { echo "disabled='disabled'"; }  } else { echo "disabled='disabled'"; } ?> style="    margin-left: 16px;padding-top: 3px;padding-bottom: 4px;margin-bottom: 10px;background: white;border-radius:7px">TERMINER</button>
						</div>
						<div class="modal fade" id="AppliquerTerminer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Appliquer le statut à la liste des groupements ?</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i> </button>
									</div>
									<div class="modal-body">
										Status : 40 - Terminé
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
										<input type="submit" name="appliquer_terminer" class="btn btn-primary" value="Appliquer">
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-8 text-right align-self-end">
							<input type="submit" name="exporter_grp" value="EXPORTER" class="btn btn-secondary" style="width: 70%;margin-bottom: 10px;">
						</div>
					</div>
				</div>

			</div>
			<hr>
			<label class="label-title" style="margin: 0;">Mise à jour des groupements</label>
			<div class="ligne"></div>
			<div class="row">
				<div class="col-sm-4">
					<div class="form-inline" style="margin:5px 0 0 0;">
						<label for="nouveau_statut" class="col-sm-8 col-form-label" style="padding-left:0;">Passer les groupements suivants au statut</label>
						<div class="col-sm-4" style="padding:0">
							<select class="form-control input-custom" name="nouveau_statut">
								<option value="5">5 - Prévu</option>
								<option value="10">10 - Créé</option>
								<option value="15">15 - Envoyé</option>
								<option value="30">30 - Livré</option>
								<option value="33">33 - A facturer</option>
								<option value="37">37 - Facturé</option>
								<option value="37">40 - Terminé</option>
								<option value="50">50 - Annulé</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-3" style="margin:5px 0 0 0;padding: 0;">
					<button type="button" name="button" data-bs-toggle="modal" class="btn btn-primary" data-bs-target="#ValidationStatut" style="width:35%;">VALIDER</button>
					<div class="modal fade" id="ValidationStatut" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel"> Modifier le statut des groupements </h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i> </button>
								</div>
								<div class="modal-body">
									Êtes-vous sur de passer la liste des groupements au statut<br><strong class="statutSel"></strong> ?
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
									<input type="submit" name="modifier_statut_grp" class="btn btn-primary" value="Oui">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<hr>
			<div class="tableau" style="height: 570px;">
				<table class="table" id="trie_table_grp">
					<thead>
						<tr>
							<th style="width: 5px;"><i class="fal fa-sort"></i></th>
							<th>N°</th>
							<th class="text-center">Etat</th>
							<th>Libelle</th>
							<th>Date</th>
							<th class="text-center">Resp</th>
<?php
							if(isset($_SESSION["charger_calculer"]) || !empty($_POST["charger_mois"]) || !empty($_SESSION["charger_fact_calculer"]))
							{
?>
							<th>Attaché</th>
							<th>Groupé</th>
							<th>Prix&nbspP</th>
							<th>Prix&nbspV</th>
							<th>Livrable</th>
							<th>Livrée</th>
							<th>Terminée</th>
							<th>Ann&nbspP</th>
							<th>%&nbspAnn&nbspP</th>
							<th>Ann.</th>
							<th>%&nbspAnn.</th>
<?php
							}
?>
						</tr>
					</thead>
					<tbody>
<?php
					$i = 0;
					$id_grp = "";
					if(isset($_SESSION["charger_calculer"]) || !empty($_POST["charger_mois"]) || !empty($_SESSION["charger_fact_calculer"]))
					{
						while ($regroupement = mysqli_fetch_array($res))
						{
							$total_grp_ap = $regroupement["p_valide"] + $regroupement["livrable"] + $regroupement["livree"] + $regroupement["terminee"] + $regroupement["annulp"];
							$total_grp_a = $regroupement["p_valide"] + $regroupement["livrable"] + $regroupement["livree"] + $regroupement["terminee"] + $regroupement["annul"];


							$pannulp_grp = cacul_pourcentage($regroupement["annulp"],$total_grp_ap,'100');
							$pannul_grp = cacul_pourcentage($regroupement["annul"],$total_grp_a,'100');

							if($pannulp_grp == 0) { $pourc_annulp_grp = ""; } else { $pourc_annulp_grp = $pannulp_grp; }
							if($pannul_grp == 0) { $pourc_annul_grp = ""; } else { $pourc_annul_grp = $pannul_grp; }

							$date = $regroupement["date_grp"];
							$date = date_create($date);
							$date_grp = date_format($date,"d/m/Y");

							$id_grp .= $regroupement["groupe_cmd"] . ";";

							if($regroupement["statut"] <= 33) { $terminee = $regroupement["terminee"]; } else { $terminee = $regroupement["terminee_livree"]; }
?>
						<tr class="select regroupements">
							<input type="hidden" name="id_grp[]" class="id_grp[]<?php print $i++; ?>" value="<?= $regroupement["groupe_cmd"]; ?>">
							<input type="hidden" name="n_grp" value="<?= $regroupement["groupe_cmd"]; ?>">
							<td></td>
							<td><?= $regroupement["groupe_cmd"]; ?></td>
							<td class="text-center"><?= $regroupement["statut"]; ?></td>
							<td class="text-center"><?= $regroupement["libelle"]; ?></td>
							<td class="text-center"><?= $date_grp; ?></td>
							<td class="text-center"><?= $regroupement["responsable"]; ?></td>
							<td class="text-center attachee"> <?= $regroupement["attachee"]; ?></td>
							<td class="text-center"><?= $regroupement["groupee"]; ?></td>
							<td class="text-center"><?= $regroupement["p_propose"]; ?></td>
							<td class="text-center"><?= $regroupement["p_valide"]; ?></td>
							<td class="text-center"><?= $regroupement["livrable"]; ?></td>
							<td class="text-center"><?= $regroupement["livree"]; ?></td>
							<td class="text-center"><?= $regroupement["terminee"]; ?></td>
							<td class="text-center"><?= $regroupement["annulp"]; ?></td>
							<td class="text-center"><?= $pourc_annulp_grp; ?></td>
							<td class="text-center"><?= $regroupement["annul"]; ?></td>
							<td class="text-center"><?= $pourc_annul_grp; ?></td>
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

							$id_grp .= $regroupement["id"] . ";";
?>
						<tr class="select regroupements">
							<input type="hidden" name="id_grp[]" class="id_grp[]<?php print $i++; ?>" value="<?= $regroupement["id"]; ?>">
							<input type="hidden" name="n_grp" value="<?= $regroupement["id"]; ?>">
							<td></td>
							<td><?= $regroupement["id"]; ?></td>
							<td class="text-center"><?= $regroupement["statut"]; ?></td>
							<td><?= $regroupement["libelle"]; ?></td>
							<td><?= $date_grp; ?></td>
							<td class="text-center"><?= $regroupement["responsable"]; ?></td>
						</tr>
<?php
						}
					}
?>
					</tbody>
				</table>
<?php $ids_grp_clean = rtrim($id_grp, ';'); ?>
				<input type="hidden" name="nb_groupement" value="<?php print $i; ?>">
				<input type="hidden" name="ids_grp" value="<?= $ids_grp_clean; ?>">
			</div>
<?php
			if(isset($_SESSION["charger_calculer"]) || !empty($_POST["charger_mois"]) || !empty($_SESSION["charger_fact_calculer"]))
			{
				$total_ap = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $term["statut"] + $annulp["statut"];
				$total_a = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $term["statut"] + $annul["statut"];

				$pannulp = cacul_pourcentage($annulp["statut"],$total_ap,'100');
				$pannul = cacul_pourcentage($annul["statut"],$total_a,'100');
				if($pannulp == 0) { $pourc_annulp = ""; } else { $pourc_annulp = $pannulp; }
				if($pannul == 0) { $pourc_annul = ""; } else { $pourc_annul = $pannul; }

?>

			<div class="tableau" style="height: auto;">
				<table class="table" style="margin-bottom: 0;">
					<thead>
						<th style="width: 6%;border-bottom: none;"></th>
						<th style="width: 4%;padding: 0;border-bottom: none;"></th>
						<th style="width: 5%;border-bottom: none;"></th>
						<th style="width: 4%;padding: 0;border-bottom: none;"></th>
						<th style="width: 6%;border-bottom: none;"></th>
						<th style="width: 4%;padding: 0;border-bottom: none;"></th>
						<th style="width: 8.3%;border-bottom: none;"> </th>
						<th style="width:89.84px;padding: 0;border-bottom: none;" class="text-center">Attaché</th>
						<th style="width:87.34px;padding: 0;border-bottom: none;" class="text-center">Groupé</th>
						<th style="width:74.38px;padding: 0;border-bottom: none;" class="text-center">Prix P</th>
						<th style="width:74.80px;padding: 0;border-bottom: none;" class="text-center">Prix V</th>
						<th style="width:91.80px;padding: 0;border-bottom: none;" class="text-center">Livrable</th>
						<th style="width:78.05px;padding: 0;border-bottom: none;" class="text-center">Livrée</th>
						<th style="width:100.47px;padding: 0;border-bottom: none;" class="text-center">Terminé</th>
						<th style="width:75.39px;padding: 0;border-bottom: none;" class="text-center">Ann P</th>
						<th style="width:90.36px;padding: 0;border-bottom: none;" class="text-center"> % Ann P</th>
						<th style="width:67.06px;padding: 0;border-bottom: none;" class="text-center">Ann.</th>
						<th style="width:82.27px;padding: 0;border-bottom: none;" class="text-center">% Ann</th>
					</thead>
					<thead>
						<th style="width: 6%;border-bottom: none;">En cours</th>
						<th style="width: 4%;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $en_cours; ?>" class="form-control input-custom" ></th>
						<th style="width: 5%;border-bottom: none;">Validé</th>
						<th style="width: 4%;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $valide; ?>" class="form-control input-custom"></th>
						<th style="width: 6%;border-bottom: none;">Projection</th>
						<th style="width: 4%;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= number_format($projection,0,',',' '); ?>" class="form-control input-custom" ></th>
						<th style="width: 8.3%;border-bottom: none;">Totaux m3</th>
						<th style="width:89.84px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $attachee["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
						<th style="width:87.34px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $groupee["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
						<th style="width:74.38px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $p_propose["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
						<th style="width:74.80px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $p_valide["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
						<th style="width:91.80px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $livrable["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
						<th style="width:78.05px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $livree["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
						<th style="width:100.47px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $term["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
						<th style="width:75.39px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $annulp["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
						<th style="width:90.36px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $pourc_annulp; ?>" class="form-control input-custom" style="text-align:center"></th>
						<th style="width:67.06px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $annul["statut"]; ?>" class="form-control input-custom" style="text-align:center"></th>
						<th style="width:82.27px;padding: 0;border-bottom: none;"><input type="text" name="" value="<?= $pourc_annul; ?>" class="form-control input-custom" style="text-align:center"></th>
					</thead>
				</table>
			</div>
<?php
			}
?>

			<div class="row">

				<div class="col-sm-2" style="max-width: 13%;">
					<input type="submit" name="charger_mois" value="CHARGER MOIS" class="btn btn-warning" style="width: 100%;margin-bottom: 2%;">
				</div>

			</div>
		</form>
	</div>

</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="/admin/js/select2.min.js"></script>
<script src="/admin/js/script_commandes.js" charset="utf-8"></script>
