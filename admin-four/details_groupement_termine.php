<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
<style media="screen">
.ligne-menu {width: 490px!important;}
</style>
<?php
session_start();

//*** Securisation du formulaire
// On detecte la recharge par F5 (par exemple) dans une meme session
$recharge = TRUE;
$RequestSignature = md5($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].print_r($_POST, true));
if($_SESSION['LastRequest'] != $RequestSignature)
{
	$_SESSION['LastRequest'] = $RequestSignature;
	$recharge = FALSE;
}
// On detecte le token du form et le token de la session sont identique
if(isset($_SESSION['token']) AND isset($_POST['token']) AND !empty($_SESSION['token']) AND !empty($_POST['token']))
{
    if ($_SESSION['token'] == $_POST['token'])
	{
		$recharge = FALSE;
    }
}

$title = "Détail d'un regroupement terminé";
$title_page = "Détail d'un regroupement terminé";
$return = true;
if (isset($_GET["return"]) == 'termines')
{
	$link = 'liste_regroupements_termine.php?id_crypte=' . $_GET["id_crypte"];
}

ob_start();

// INC global
include_once "../inc/pmp_co_connect.php";
// INC interne ADM Fournisseur
include_once "inc/pmp_inc_fonctions_groupements.php";

$cmdes = getCommandesGroupementsRecap($co_pmp, $_GET["id_grp"]);

$qteOrd = getStatsQteCommandeRecap($co_pmp, $_GET["id_grp"], 1);
$qteSup = getStatsQteCommandeRecap($co_pmp, $_GET["id_grp"], 2);

$totalQte = $qteOrd["cmd_qte"] + $qteSup["cmd_qte"];
$totalQteL = $qteOrd["cmd_qtelivre"] + $qteSup["cmd_qtelivre"];

$plage = getPlagesPrix($co_pmp, $_GET["id_grp"]);

$grp = getGroupementDetails($co_pmp, $_GET["id_grp"]);

$com = getCommissions($co_pmp);

if(!empty($_POST["exporter_cmd_termine"]))
{
	ExporterListeCmdTermine($co_pmp, $cmdes, $grp);
}
?>
<div class="bloc">
	<form method="post">
		<div class="row">
			<div class="col-sm-3" style="border-right: 1px solid #0b242436;">
				<label class="label-title" style="margin: 0;">Infos générales</label>
				<div class="ligne"></div>
				<div class="form-inline" style="margin-top:0.5%;">
					<label for="com_ord" class="col-sm-8 col-form-label" style="padding-left:0;">Commission ord (€/m3) :</label>
					<div class="col-sm-4" style="padding:0">
						<input type="text" name="com_ord" value="<?= $com["comord"]; ?>,00 € HT" class="form-control" style="width:100%;background-color: #e0e1df00!important;" disabled="disabled">
					</div>
				</div>
				<div class="form-inline">
					<label for="com_sup" class="col-sm-8 col-form-label" style="padding-left:0;">Commission sup (€/m3) :</label>
					<div class="col-sm-4" style="padding:0">
						<input type="text" name="com_sup" value="<?= $com["comsup"]; ?>,00 € HT" class="form-control" style="width:100%;background-color: #e0e1df00!important;" disabled="disabled">
					</div>
				</div>
				<div class="form-inline">
					<label for="com_sup" class="col-sm-8 col-form-label" style="padding-left:0;">N° Facture</label>
					<div class="col-sm-4" style="padding:0">
						<input type="text" name="com_sup" value="<?= $grp["numfact"]; ?>" class="form-control" style="width:100%;background-color: #e0e1df00!important;" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-6" style="border-right: 1px solid #0b242436;">
				<label class="label-title" style="margin: 0;">Volume total</label>
				<div class="ligne"></div>
				<div class="row" style="margin-top:0.5%;">
					<div class="col-sm-6">
						<div class="form-inline">
							<label for="vol_ord" class="col-sm-8 col-form-label" style="padding-left:0;">Qté Ord Commandée</label>
							<div class="col-sm-4" style="padding:0">
								<input type="text" name="vol_ord" value="<?php if(isset($qteOrd["cmd_qte"])) { echo $qteOrd["cmd_qte"]; } else { echo "0"; } ?>" class="form-control" style="width:100%;background-color: #e0e1df00!important;" disabled="disabled">
							</div>
						</div>
						<div class="form-inline">
							<label for="vol_sup" class="col-sm-8 col-form-label" style="padding-left:0;">Qté Sup Commandée</label>
							<div class="col-sm-4" style="padding:0">
								<input type="text" name="vol_sup" value="<?php if(isset($qteSup["cmd_qte"])) { echo $qteSup["cmd_qte"]; } else { echo "0"; } ?>" class="form-control" style="width:100%;background-color: #e0e1df00!important;" disabled="disabled">
							</div>
						</div>
						<div class="form-inline">
							<label for="vol_sup" class="col-sm-8 col-form-label" style="padding-left:0;">Total Commandé</label>
							<div class="col-sm-4" style="padding:0">
								<input type="text" name="vol_sup" value="<?php if(isset($totalQte)) { echo $totalQte; } else { echo "0"; } ?>" class="form-control" style="width:100%;background-color: #e0e1df00!important;" disabled="disabled">
							</div>
						</div>
					</div>
					<div class="col-sm-6 align-self-center">
						<div class="form-inline">
							<label for="vol_ord_livre" class="col-sm-8 col-form-label" style="padding-left:0;">Qté Ord Livrée</label>
							<div class="col-sm-4" style="padding:0">
								<input type="text" name="vol_ord_livre" value="<?php if(isset($qteOrd["cmd_qtelivre"])) { echo $qteOrd["cmd_qtelivre"]; } else { echo "0"; } ?>" class="form-control" style="width:100%;background-color: #e0e1df00!important;" disabled="disabled">
							</div>
						</div>
						<div class="form-inline">
							<label for="vol_sup_livre" class="col-sm-8 col-form-label" style="padding-left:0;">Qté Sup Livrée</label>
							<div class="col-sm-4" style="padding:0">
								<input type="text" name="vol_sup_livre" value="<?php if(isset($qteSup["cmd_qtelivre"])) { echo $qteSup["cmd_qtelivre"]; } else { echo "0"; } ?>" class="form-control" style="width:100%;background-color: #e0e1df00!important;" disabled="disabled">
							</div>
						</div>
						<div class="form-inline">
							<label for="vol_sup_livre" class="col-sm-8 col-form-label" style="padding-left:0;">Total Livré</label>
							<div class="col-sm-4" style="padding:0">
								<input type="text" name="vol_sup_livre" value="<?php if(isset($totalQteL)) { echo $totalQteL; } else { echo "0"; } ?>" class="form-control" style="width:100%;background-color: #e0e1df00!important;" disabled="disabled">
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="col-sm-3">
				<label class="label-title" style="margin: 0;">Tarif groupement</label>
				<div class="ligne"></div>
				<div class="tableau" style="height: auto;margin: 10px 0 0px;">
					<table class="table">
						<thead>
							<tr>
								<th style="border-bottom: 2px solid #ef83514a!important;">Qté</th>
								<th style="border-bottom: 2px solid #ef83514a!important;">Prix O</th>
								<th style="border-bottom: 2px solid #ef83514a!important;">Prix S</th>
							</tr>
						</thead>
						<tbody>
<?php
						while($plages = mysqli_fetch_array($plage))
						{
?>
							<tr>
								<td><?= $plages["volume"]; ?></td>
								<td><?= $plages["prix_ord"]; ?></td>
								<td><?= $plages["prix_sup"]; ?></td>
							</tr>
<?php
						}
?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-sm-6">
				<label class="label-title" style="margin: 0;"> Liste des commandes</label>
				<div class="ligne"></div>
			</div>
			<div class="col-sm-6 text-end">
				<input type="submit" name="exporter_cmd_termine" value="EXPORTER" class="btn btn-secondary">
			</div>
		</div>

		<div class="tableau" style="height: 475px;">
			<table class="table" id="cmdes_termines">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Prenom</th>
						<th>CP</th>
						<th>Ville</th>
						<th>Adresse</th>
						<th>Com Client</th>
						<th>Tel 1</th>
						<th>Tel 2</th>
						<th>Commentaire</th>
						<th>Qté</th>
						<th>Qté Livrée</th>
						<th>Prix Ord</th>
						<th>Prix Sup</th>
						<th>Qualité</th>
					</tr>
				</thead>
				<tbody>
<?php
				while($cmd = mysqli_fetch_array($cmdes))
				{
					if ($cmd["cmd_typefuel"] == 1){ $type = 'Ordinaire';}
					if ($cmd["cmd_typefuel"] == 2){ $type = 'Supérieur';}
					if ($cmd["cmd_typefuel"] == 3){ $type = 'GNR';}
?>
					<tr class="select commande">
						<td><?= $cmd["name"]; ?></td>
						<td><?= $cmd["prenom"]; ?></td>
						<td><?= $cmd["code_postal"]; ?></td>
						<td><?= $cmd["ville"]; ?></td>
						<td><?= $cmd["adresse"]; ?></td>
						<td class="text-center" style="white-space: inherit;">
<?php
						if(isset($cmd["cmd_commentfour"]) && $cmd["cmd_commentfour"] != "")
						{
?>
						<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#comment_<?= $cmd['id_cmd']; ?>"><i class="fal fa-info-circle"></i></button>
						<div class="modal fade" id="comment_<?= $cmd["id_cmd"]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" style="max-width: 40%;">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Commentaire du client</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i></button>
									</div>
									<div class="modal-body text-left">
										<label class="col-form-label" style="padding-left:0;"><?= $cmd["name"]; ?> - <?= $cmd["prenom"]; ?></label>
										<hr>
										<p><?= $cmd["cmd_commentfour"]; ?></p>

									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
									</div>
								</div>
							</div>
						</div>
<?php
						}
?>
						</td>
						<td><?= $cmd["tel_port"]; ?></td>
						<td><?= $cmd["tel_fixe"]; ?></td>
						<td class="text-center" style="white-space: inherit;">
<?php
						if(isset($cmd["cmd_comment_du_four"]))
						{
?>
						<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#comment_four_<?= $cmd['id_cmd']; ?>"><i class="fal fa-info-circle"></i></button>
						<div class="modal fade" id="comment_four_<?= $cmd["id_cmd"]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" style="max-width: 40%;">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Votre commentaire</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i></button>
									</div>
									<div class="modal-body text-left">
										<label class="col-form-label" style="padding-left:0;"><?= $cmd["name"]; ?> - <?= $cmd["prenom"]; ?></label>
										<hr>
										<p><?= $cmd["cmd_comment_du_four"]; ?></p>

									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
									</div>
								</div>
							</div>
						</div>
<?php
						}
?>
						</td>
						<td class="text-center"><?= $cmd["cmd_qte"]; ?></td>
						<td class="text-center"><?= $cmd["cmd_qtelivre"]; ?></td>
						<td class="text-center"><?= $cmd["cmd_prix_ord"]; ?></td>
						<td class="text-center"><?= $cmd["cmd_prix_sup"]; ?></td>
						<td class="text-center"><?= $type; ?></td>
					</tr>
<?php
				}
?>
				</tbody>
			</table>
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<link rel="stylesheet" href="/bootstrap/4.0.0/js/bootstrap.min.js" media="screen" defer>
<script src="js/script_groupements.js" charset="utf-8"></script>
