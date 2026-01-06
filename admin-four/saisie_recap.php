<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

<style media="screen">
.ligne-menu {width: 260px!important;}
table.dataTable tbody th, table.dataTable tbody td {
padding: 0% 0.75rem!important;
}
.btn-outline-primary { background: #f7f7f7!important;padding: 3px 20px!important;border-radius: 6px!important;font-size: 14px!important;}
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

$title = 'Détails des récap';
$title_page = 'Détails des récap';
$return = true;


if (isset($_GET["return"]) == 'recap')
{
	$link = 'liste_groupements_recap.php?id_crypte=' . $_GET["id_crypte"];
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

$grp = getGroupementDetails($co_pmp, $_GET["id_grp"]);

if(!empty($_POST["exporter_recap"]))
{
	ExporterListeRecap($co_pmp, $cmdes, $grp);
}

if(!empty($_POST["valider_recap"]))
{
	$id_cmde = $_POST["id_cmde"];
	for($i = 0; $i < $_POST['nb_commande']; $i++)
	{
		$id = $id_cmde[$i];
		$qte = 'qte_livree_' . $id;
		$qte_livree = $_POST[$qte];

		$com = "commentaire_du_four_" . $id;
		$com_four = $_POST[$com];

		if($qte_livree == "0")
		{
			if($com_four == "")
			{
				$erreur = "ok";
			}
		}
	}
	if (isset($erreur))
	{
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Pour toute saisie de quantité à 0, merci de nous indiquer en commentaire le motif d'annulation.";
	}
	else
	{
		envoyerRecap($co_pmp);
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
if(isset($_GET["qte"]))
{
?>
<div class="toast success">
	<div class="message-icon  success-icon">
		<i class="fas fa-check"></i>
	</div>
	<div class="message-content ">
		<div class="message-type">
			Succès
		</div>
		<div class="message">
			Le mail a bien été envoyé à POEMOP avec le récap des quantités livrées.
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
<?php
	if($grp["statut"] >= 37)
	{
?>
<div class="toast info" style="margin: 0;">
	<div class="message-icon  info-icon">
		<i class="fa-solid fa-circle-exclamation"></i>
	</div>
	<div class="message-content ">
		<div class="message-type">
			Info
			<div class="ligne"></div>
		</div>
		<div class="message" style="margin-top: 13px;">
			Ce groupement a été facturé, il est donc impossible de modifier les quantités livrées.
		</div>
	</div>
</div>
<?php
	}
	else
	{
?>
	<form method="post" id="FormID">
		<label class="label-title" style="margin: 0;">Volume total</label>
		<div class="ligne"></div>
		<div class="row">
			<div class="col-sm-3">
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
					<label for="vol_ord" class="col-sm-8 col-form-label" style="padding-left:0;">Total Commandé</label>
					<div class="col-sm-4" style="padding:0">
						<input type="text" name="vol_ord" value="<?php if(isset($totalQte)) { echo $totalQte; } else { echo "0"; } ?>" class="form-control" style="width:100%;background-color: #e0e1df00!important;" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-3">
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
		<hr>
		<div class="row">
			<div class="col-sm-6">
				<label class="label-title" style="margin: 0;"> Liste des commandes</label>
				<div class="ligne"></div>
			</div>
			<div class="col-sm-6 align-self-end text-end">
				<input type="submit" class="btn btn-secondary" name="exporter_recap" value="EXPORTER" style="width: 175px;">
			</div>
		</div>

		<!-- <div class="form-inline">
			<label for="tous_actif" class="col-sm-3 col-form-label" style="padding-left:0;max-width: 20%;">Toutes les commandes ont été livrées :</label>
			<div class="col-sm-1 select-tous_actif" style="padding:0;max-width: 6%;">
				<input type="checkbox" name="tous_actif" value="0" class="switch value">
			</div>
			<div class=" col-sm-2 " style="margin-right: 3%;">
				<input type="submit" name="rendre_tous_actif" value="VALIDER" class="btn btn-primary" style="width:60%;">
			</div>
		</div> -->
		<p style="font-family: 'Goldplay Medium' !important;margin: 1% 0 0;font-size: 14px;"><i class="fal fa-question-circle"></i> Remplit automatiquement la quantité livrée avec la quantité commandée</p>
		<div class="tableau" style="height: 475px;margin-top: 10px;">
			<table class="table" id="trie_table_cmd_qte">
				<thead>
					<th>Nom</th>
					<th>Prenom</th>
					<th>CP</th>
					<th>Ville</th>
					<th>Adresse</th>
					<!-- <th>Tel 1</th> -->
					<th>Téléphone</th>
					<th>Commentaire</th>
					<th>Livraison Annulée</th>
					<th class="text-center" style="width:40px!important;">Qté</th>
					<th class="text-center" style="width:65px!important;">Qté Livrée</th>
					<th class="text-center">Livré <i data-toggle="tooltip" data-placement="top" title="Remplis automatiquement la quantité livrée avec la quantité commandée" class="fal fa-question-circle"></i></th>
				</thead>
				<tbody>
<?php
				$i = 0;
				while($cmd = mysqli_fetch_array($cmdes))
				{
					if(strlen($cmd["tel_fixe"])>0) { $tel = $cmd["tel_fixe"]; }
					else { $tel = $cmd["tel_port"]; }
?>

					<tr class="select commande">
						<input type="hidden" name="id_cmde[]" class="id_cmde[]" value="<?= $cmd["id_cmd"]; ?>">
						<input type="hidden" name="id_cmde_<?php print $i++; ?>" value="<?= $cmd["id_cmd"]; ?>">
						<td><?= $cmd["name"]; ?></td>
						<td><?= $cmd["prenom"]; ?></td>
						<td><?= $cmd["code_postal"]; ?></td>
						<td><?= $cmd["ville"]; ?></td>
						<td><?= $cmd["adresse"]; ?></td>
						<!-- <td><?= $cmd["tel_port"]; ?></td> -->
						<td><?= $tel; ?></td>
						<td class="text-truncate">
							<input type="hidden" name="commentaire_du_four_<?= $cmd["id_cmd"]; ?>" value="<?php if(isset($cmd["cmd_comment_du_four"])) { echo $cmd["cmd_comment_du_four"]; } ?>">
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
						<td>
<?php
						if($cmd["cmd_status"] == '52') { echo "Livraison annulée"; }
						else
						{
?>
							<button type="button" data-bs-toggle="modal" data-bs-target="#annuleeLivraison_<?= $cmd["id_cmd"]; ?>" class="btn btn-outline-primary" name="button" style="padding: 1px 20px!important;border-radius: 6px!important;">ANNULER</button>
							<form method="post">
								<div class="modal fade" id="annuleeLivraison_<?= $cmd["id_cmd"]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" style="max-width: 40%;">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Voulez-vous annuler la livraison de cette commande</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i></button>
											</div>
											<div class="modal-body text-left">
												<label class="col-form-label" style="padding-left:0;"><?= $cmd["name"]; ?> - <?= $cmd["prenom"]; ?></label>
												<p>Merci de nous donner le motif d'annulation de cette commande</p>
												<textarea name="commentaire_annulation" class="form-control" rows="5" cols="62" style="height:auto;" value="" required ></textarea>
											</div>
											<div class="modal-footer">
												<input type="hidden" name="id_cmd_livraison" value="<?php print $cmd['id_cmd']; ?>">
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
												<input type="submit" name="annuleeLivraison" id="annuleeLivraison" class="btn btn-primary" value="Oui">
											</div>
										</div>
									</div>
								</div>
							</form>
<?php
						}
?>
						</td>
						<td class="text-center"><input type="hidden" class="qte_<?= $cmd["id_cmd"]; ?> form-control" name="qte_<?= $cmd["id_cmd"]; ?>" value="<?= $cmd["cmd_qte"]; ?>" style="text-align:center"><?= $cmd["cmd_qte"]; ?></td>
						<td class="text-center">
							<input <?php if($cmd["cmd_status"] == '52') { echo 'disabled="disabled" style="background-color: #253b3b00!important;border-radius: 0;text-align:center"'; } else { echo 'style="background-color: #253b3b0d!important;border-radius: 0;text-align:center"'; } ?> onchange="myFunction(this.value)" type="text" class="qte_livree_<?= $cmd["id_cmd"]; ?> form-control" name="qte_livree_<?= $cmd["id_cmd"]; ?>" value="<?= $cmd["cmd_qtelivre"]; ?>" style="background-color: #253b3b0d!important;border-radius: 0;text-align:center">
						</td>
						<td class="text-center">
							<label for="cmd_livree_<?= $cmd['id_cmd']; ?>" class="col-form-label" style="padding-bottom: 0;width: 15%;">
								<input <?php if($cmd["cmd_status"] == '52') { echo 'disabled="disabled"'; } ?> type="checkbox" onchange="myFunction(this.value)" name="cmd_livree_<?= $cmd['id_cmd']; ?>" id="cmd_livree_<?= $cmd['id_cmd']; ?>" class="switch value check" value="<?= $cmd["id_cmd"]; ?>" style="margin-right: 6%;">
							</label>
						</td>
					</tr>
<?php
				}
?>
				</tbody>

			</table>
			<input type="hidden" name="nb_commande" class="nb_commande" value="<?php print $i; ?>">
		</div>
		<noscript><input type="submit" value="Changer" /></noscript>
		<!-- <div class="row">
			<div class="col-sm-10 text-end">
				<input type="submit" name="valider_tableau" class="btn btn-primary" value="VALIDÉ QTÉ" style="width: 189px;">
			</div>
			<div class="col-sm-2 text-end">
				<input type="submit" name="envoyer_mail_poemop" class="btn btn-warning" value="MAIL POEMOP" style="width: 189px;">
			</div>
		</div> -->
		<div class=" text-end">
			<button type="button" name="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#test">J'AI TERMINÉ LA SAISIE DE MON RÉCAP</button>
		</div>
		<div class="modal fade" id="test" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" style="max-width: 40%;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">J'ai terminé la saisie de mon récap</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i></button>
					</div>
					<div class="modal-body text-left">
						<p class="col-form-label">Un mail sera envoyé à POEMOP et le groupement passera dans la liste des groupements terminés.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
						<input type="submit" name="valider_recap" value="Envoyer" class="btn btn-primary">
					</div>
				</div>
			</div>
		</div>
	</form>
<?php
	}
?>
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
<script src="js/script_groupements.js" charset="utf-8"></script>
