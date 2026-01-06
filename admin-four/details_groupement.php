<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
<style media="screen">
table.dataTable tbody th, table.dataTable tbody td {
padding: 0.4rem 0.75rem!important;
}
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

$title = "Détail d'un regroupement";
$title_page = "Détail d'un regroupement";
$return = true;
if (isset($_GET["return"]) == 'grp')
{
	$link = 'liste_regroupements.php?id_crypte=' . $_GET["id_crypte"];
}
// $title_page = 'Tableau de bord';

ob_start();


// INC global
include_once "../inc/pmp_co_connect.php";
// INC interne ADM Fournisseur
include_once "inc/pmp_inc_fonctions_groupements.php";


$cmdes = getCommandesGroupements($co_pmp, $_GET["id_grp"]);
// $cmdes_qte = getCommandesGroupementsQte($co_pmp, $_GET["id_grp"]);

$qteOrd = getStatsQteCommande($co_pmp, $_GET["id_grp"], 1);
$qteSup = getStatsQteCommande($co_pmp, $_GET["id_grp"], 2);

$totalQte = $qteOrd["cmd_qte"] + $qteSup["cmd_qte"];
$totalQteL = $qteOrd["cmd_qtelivre"] + $qteSup["cmd_qtelivre"];

if(!empty($_POST["exporter_grp"]))
{
	$grp = getGroupementDetails($co_pmp, $_GET["id_grp"]);
	ExporterListeCmd($co_pmp, $cmdes, $grp);
}

$plage = getPlagesPrix($co_pmp, $_GET["id_grp"]);

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
	<form method="post">
		<div class="row">
			<div class="col-sm-4" style="border-right: 1px solid #0b242436;">
				<label class="label-title" style="margin: 0;">Volume total</label>
				<div class="ligne"></div>
				<div class="row" style="margin-top: 10%;">
					<div class="col-sm-12">
						<div class="form-inline">
							<label for="vol_ord" class="col-sm-4 col-form-label" style="padding: 0;padding-left:0;">Qté Ord Commandée</label>
							<div class="col-sm-8" style="padding:0">
								<input type="text" name="vol_ord" value="<?php if(isset($qteOrd["cmd_qte"])) { echo $qteOrd["cmd_qte"]; } else { echo "0"; } ?>" class="form-control" style="padding: 0;width:100%;background-color: #e0e1df00!important;" disabled="disabled">
							</div>
						</div>
						<div class="form-inline" style="margin-top: 5%;">
							<label for="vol_sup" class="col-sm-4 col-form-label" style="padding: 0;padding-left:0;">Qté Sup Commandée</label>
							<div class="col-sm-8" style="padding:0">
								<input type="text" name="vol_sup" value="<?php if(isset($qteSup["cmd_qte"])) { echo $qteSup["cmd_qte"]; } else { echo "0"; } ?>" class="form-control" style="padding: 0;width:100%;background-color: #e0e1df00!important;" disabled="disabled">
							</div>
						</div>
						<div class="form-inline" style="margin-top: 5%;">
							<label for="vol_sup" class="col-sm-4 col-form-label" style="padding: 0;padding-left:0;">Total Commandé</label>
							<div class="col-sm-8" style="padding:0">
								<input type="text" name="vol_sup" value="<?php if(isset($totalQte)) { echo $totalQte; } else { echo "0"; } ?>" class="form-control" style="padding: 0;width:100%;background-color: #e0e1df00!important;" disabled="disabled">
							</div>
						</div>
					</div>

				</div>
			</div>
			<div class="col-sm-8">
				<label class="label-title" style="margin: 0;">Plages prix</label>
				<div class="ligne"></div>
				<div class="tableau" style="height: 170px;max-width: 280px;">
					<table class="table">
						<thead>
							<tr>
								<th style="border-bottom: 2px solid #ef83514a!important;">Qté</th>
								<th style="border-bottom: 2px solid #ef83514a!important;" class="text-center">Prix O</th>
								<th style="border-bottom: 2px solid #ef83514a!important;" class="text-center">Prix S</th>
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
		<label class="label-title" style="margin: 0;"> Liste des commandes</label>
		<div class="ligne"></div>
		<div class="row" style="margin-top: 0.5%;">
			<div class="col-sm-4">
				<div class="form-inline">
					<p class="col-form-label col-sm-3">Légende :</p>
					<div class="col-sm-1 text-center" style="padding:0">
						<i class="fal fa-envelope-open-text"></i>
					</div>
					<div class="col-sm" style="padding: 0;">
						<p class="col-form-label" style="font-size: 13px;color: #0b2424b8;">Envoi un mail au client avec votre numéro</p>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-inline">
					<p class="col-form-label col-sm-1" style="padding-right: 7%;"><i class="fal fa-info-circle"></i></p>
					<div class="col-sm" style="padding: 0;">
						<p class="col-form-label" style="font-size: 13px;color: #0b2424b8;">Affiche le commentaire du client</p>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-inline">
					<p class="col-form-label col-sm-1" style="padding-right: 7%;"><i class="fal fa-comment-alt-edit"></i></p>
					<div class="col-sm" style="padding: 0;">
						<p class="col-form-label" style="font-size: 13px;color: #0b2424b8;">Laisser un commentaire sur une commande</p>
					</div>
				</div>
			</div>
			<div class="col-sm-2 align-self-end text-end">
				<input type="submit" class="btn btn-secondary" name="exporter_grp" value="EXPORTER" style="width: 175px;">
			</div>
		</div>
		<div class="tableau" style="height: auto;margin-top: 10px;">
			<table class="table" id="trie_table_cmd">
				<thead>
					<th>Rappel</th>
					<th>Nom</th>
					<th>Prenom</th>
					<th>CP</th>
					<th>Ville</th>
					<th>Adresse</th>
					<th class="text-center">Com Client</th>
					<th>Tel 1</th>
					<th>Tel 2</th>
					<th>Commentaire</th>
					<th class="text-center" style="width:50px!important;">Prix ord</th>
					<th class="text-center" style="width:50px!important;">Prix sup</th>
					<th class="text-center" style="width:40px!important;">Qté</th>
					<th class="text-center" style="width:50px!important;">Fuel</th>
					<th class="text-center" style="width:50px!important;">statut</th>
				</thead>
				<tbody>
<?php
				// $i = 0;
				while($cmd = mysqli_fetch_array($cmdes))
				{
					if ($cmd["cmd_typefuel"] == 1){ $type = 'Ordinaire';}
					if ($cmd["cmd_typefuel"] == 2){ $type = 'Supérieur';}
					if ($cmd["cmd_typefuel"] == 3){ $type = 'GNR';}
?>
					<tr class="select commande">
						<input type="hidden" name="id_cmde_<?php print $i++; ?>" value="<?= $cmd["id_cmd"]; ?>">
						<td>
							<button type="button" name="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#relanceClient<?= $cmd['user_id']; ?>" style="font-size: 17px;width: 100%;"><i class="fal fa-envelope-open-text"></i></button>
							<form method="post">
								<div class="modal fade" id="relanceClient<?= $cmd["user_id"]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" style="max-width: 40%;">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Envoyer un mail au client pour qu'il vous rappelle ?</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i></button>
											</div>
											<div class="modal-body text-left">
												<label class="col-form-label" style="padding-left:0;"><?= $cmd["name"]; ?> - <?= $cmd["prenom"]; ?></label>
											</div>
											<div class="modal-footer">
												<input type="hidden" name="user_id" value="<?php print $cmd['user_id']; ?>">
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
												<input type="submit" name="envoyer_mail_rappel" id="envoyer_mail_rappel" class="btn btn-primary" value="Envoyer">
											</div>
										</div>
									</div>
								</div>
							</form>
						</td>
						<td><?= $cmd["name"]; ?></td>
						<td><?= $cmd["prenom"]; ?></td>
						<td><?= $cmd["code_postal"]; ?></td>
						<td><?= $cmd["ville"]; ?></td>
						<td><?= $cmd["adresse"]; ?></td>
						<td class="text-center text-truncate"  style="white-space: inherit;">
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
						<td class="text-truncate">
							<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editCommentFour_<?= $cmd['id_cmd']; ?>"><i class="fal fa-comment-alt-edit"></i></button>
							<?= $cmd["cmd_comment_du_four"]; ?>
							<form method="post">
								<div class="modal fade" id="editCommentFour_<?= $cmd["id_cmd"]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" style="max-width: 40%;">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Laisser un commentaire pour cette commande ?</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i></button>
											</div>
											<div class="modal-body text-left">
												<label class="col-form-label" style="padding-left:0;"><?= $cmd["name"]; ?> - <?= $cmd["prenom"]; ?></label>
												<textarea name="commentaire_du_four" class="form-control" rows="5" cols="62" style="height:auto;" value="<?php if(isset($cmd["cmd_comment_du_four"])) { echo $cmd["cmd_comment_du_four"]; } ?>" ><?php if(isset($cmd["cmd_comment_du_four"])) { echo $cmd["cmd_comment_du_four"]; } ?></textarea>
											</div>
											<div class="modal-footer">
												<input type="hidden" name="id_cmd_comment" value="<?php print $cmd['id_cmd']; ?>">
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
												<input type="submit" name="add_comment" id="add_comment" class="btn btn-primary" value="Ajouter">
											</div>
										</div>
									</div>
								</div>
							</form>

						</td>
						<td class="text-center"><?= $cmd["cmd_prix_ord"]; ?></td>
						<td class="text-center"><?= $cmd["cmd_prix_sup"]; ?></td>
						<td class="text-center"><input type="hidden" name="qte" class="form-control" value="<?= $cmd["cmd_qte"]; ?>" style="text-align:center"><?= $cmd["cmd_qte"]; ?></td>
						<td class="text-center"><?= $type; ?></td>
						<td class="text-center"><?= $cmd["cmd_status"]; ?></td>
					</tr>
<?php
				}
?>
				</tbody>

			</table>
		</div>
		<div class="text-end">
			<input type="submit" name="fini_livrer" value="J'AI FINI DE LIVRER" class="btn btn-primary">
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<link rel="stylesheet" href="../bootstrap/4.0.0/js/bootstrap.min.js" media="screen" defer>
<script src="js/script_groupements.js" charset="utf-8"></script>
<script type="text/javascript">

</script>
