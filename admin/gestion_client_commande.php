<style media="screen">
	.ligne-menu {
		width: 26% !important;
	}

	.menu>h1,
	.ligne-menu {
		margin-left: 6%;
	}
</style>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user'])) {
	header('Location: connexion.php');
	die();
}

$title = 'Gestion d\'un client';
$title_page = 'Gestion d\'un client';
$return = true;

ob_start();

include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_groupements.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_commandes.php";

unset($_SESSION['facture_saisie']);
$_SESSION["id_cmd"] = $_GET["id_cmd"];

if (!empty($_POST["valide_modifier_commande"]) || !empty($_POST["quitter_page"])) {
	$url = $_POST["new_url"];
	header('Location: ' . $url);
}

if (isset($_GET["id_cmd"])) {
	$cmd_details = getCommandeDetailsClients($co_pmp, $_GET["id_cmd"]);
	$res = getCommentaireHisto($co_pmp, $_GET["id_cmd"]);
}

if ($_GET["return"] == 'cmdes') {
	$link = 'liste_commandes.php';
} elseif ($_GET["return"] == 'avis') {
	$link = 'avis_clients.php?get=avis';
} elseif ($_GET["return"] == 'grpt') {
	$link = 'details_groupement.php?id_grp=' . $cmd_details["groupe_cmd"];
} elseif ($_GET["return"] == 'recherche') {
	$link = 'recherche_client.php';
}

if (isset($message)) {
	?>
	<div class="toast <?= $message_type; ?>">
		<div class="message-icon success-icon">
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
	<?php
	if (isset($_GET["id_cmd"])) {
		if ($_GET["return"] == 'cmdes') {
			?>
			<div class="menu-bloc">
				<a
					href="gestion_client.php?user_id=<?= $cmd_details["user_id"]; ?>&id_cmd=<?= $_GET["id_cmd"]; ?>&return=cmdes">Client</a>
				<a href="#" class="active">Commande</a>
				<a
					href="ancienne_commande.php?user_id=<?= $cmd_details["user_id"]; ?>&id_cmd=<?= $_GET["id_cmd"]; ?>&return=cmdes">Anciennes
					commandes</a>
			</div>
			<?php
		} elseif ($_GET["return"] == 'grpt') {
			?>
			<div class="menu-bloc">
				<a
					href="gestion_client.php?user_id=<?= $cmd_details["user_id"]; ?>&id_cmd=<?= $_GET["id_cmd"]; ?>&return=grpt">Client</a>
				<a href="#" class="active">Commande</a>
				<a
					href="ancienne_commande.php?user_id=<?= $cmd_details["user_id"]; ?>&id_cmd=<?= $_GET["id_cmd"]; ?>&return=grpt">Ancienne
					commande</a>
			</div>
			<?php
		} elseif ($_GET["return"] == 'recherche') {
			?>
			<div class="menu-bloc">
				<a href="gestion_client.php?user_id=<?= $cmd_details["user_id"]; ?>&return=recherche">Client</a>
				<a href="#" class="active">Commande</a>
				<a
					href="ancienne_commande.php?user_id=<?= $cmd_details["user_id"]; ?>&id_cmd=<?= $_GET["id_cmd"]; ?>&return=recherche">Ancienne
					commande</a>
			</div>
			<?php
		} elseif ($_GET["return"] == 'avis') {
			?>
			<div class="menu-bloc">
				<a
					href="gestion_client.php?user_id=<?= $cmd_details["user_id"]; ?>&id_cmd=<?= $_GET["id_cmd"]; ?>&return=avis">Client</a>
				<a href="#" class="active">Commande</a>
				<a
					href="ancienne_commande.php?user_id=<?= $cmd_details["user_id"]; ?>&id_cmd=<?= $_GET["id_cmd"]; ?>&return=avis">Ancienne
					commande</a>
			</div>
			<?php
		}
	} else {
		?>
		<div class="menu-bloc">
			<a href="clients_nouveaux_inscrits.php"><i class="fas fa-arrow-left" style="font-size: 14px;"></i></a>
			<a href="gestion_client.php">Client</a>
			<a href="#" class="active">Commande</a>
			<a href="#">Anciennes commandes</a>
		</div>
		<?php
	}
	?>
	<form method="post">
		<input type="hidden" class="new_url" name="new_url" value="">
		<div class="row" style="margin-left: 0%;">
			<div class="col-sm-2" style="padding:0;margin: 0 2% 0 0;">
				<label class="label-title" style="margin: 0;">Commande</label>
				<div class="ligne"></div>
				<div class="form-inline" style="margin: 0 0 -2.5%;">
					<label class="col-sm-6 col-form-label" style="padding-left:0;">Type de fuel</label>
					<div class="col-sm-2" style="padding:0">
						<input type="radio" name="cmd_fioul" id="cmd_fioul_ord" class="switch value check form-control"
							value="Ordinaire" <?php if (isset($_GET["id_cmd"]) && isset($cmd_details['cmd_typefuel'])) {
								if ($cmd_details['cmd_typefuel'] == 1) {
									echo "checked='checked'";
								}
							} ?>>
					</div>
					<div class="col-sm-4">
						<label for="cmd_fioul_ord" class="radio">Ordinaire</label>
					</div>
				</div>
				<div class="form-inline" style="margin: 0 0 -2.5%;">
					<label class="col-sm-6 col-form-label" style="padding-left:0; visibility:hidden">Type de
						fuel</label>
					<div class="col-sm-2" style="padding:0">
						<input type="radio" name="cmd_fioul" id="cmd_fioul_sup" class="switch value check form-control"
							value="Supérieur" <?php if (isset($_GET["id_cmd"]) && isset($cmd_details['cmd_typefuel'])) {
								if ($cmd_details['cmd_typefuel'] == 2) {
									echo "checked='checked'";
								}
							} ?>>
					</div>
					<div class="col-sm-4">
						<label for="cmd_fioul_sup" class="radio">Supérieur</label>
					</div>
				</div>
				<div class="form-inline">
					<label class="col-sm-6 col-form-label" style="padding-left:0; visibility:hidden">Type de
						fuel</label>
					<div class="col-sm-2" style="padding:0">
						<input type="radio" name="cmd_fioul" id="cmd_fioul_np" class="switch value check form-control"
							value="GNR" <?php if (isset($_GET["id_cmd"]) && isset($cmd_details['cmd_typefuel'])) {
								if ($cmd_details['cmd_typefuel'] != 2 && $cmd_details['cmd_typefuel'] != 1) {
									echo "checked='checked'";
								}
							} ?>>
					</div>
					<div class="col-sm-4" style="padding:0">
						<label for="cmd_fioul_np" class="radio">Autre</label>
					</div>
				</div>
				<div class="form-inline" style="margin-top: 5%;">
					<label class="col-sm-6 col-form-label" for="cmd_qt" style="padding-left:0;">Quantité L</label>
					<div class="col-sm-4" style="padding:0">
						<input type="number" name="cmd_qt" value="<?php if (isset($_GET["id_cmd"]) && isset($cmd_details["cmd_qte"])) {
							echo $cmd_details["cmd_qte"];
						} ?>" class="form-control" style="width:100%;">
					</div>
				</div>
			</div>
			<?php
			$prix_ord = $cmd_details["cmd_prix_ord"] / 1000;
			$prix_ord = number_format($prix_ord, 3, '.', '');
			$prix_sup = $cmd_details["cmd_prix_sup"] / 1000;
			$prix_sup = number_format($prix_sup, 3, '.', '');
			?>
			<div class="col-sm-3" style="border-left:1px solid #0b242436;padding: 0 0 0 2%;">
				<label class="label-title" style="margin: 0;">Livraison</label>
				<div class="ligne"></div>
				<div class="form-inline">
					<label for="prix_ord" class="col-sm-4 col-form-label" style="padding-left:0;">Prix Ordinaire</label>
					<div class="col-sm-3" style="padding:0">
						<input type="text" name="prix_ord" value="<?php if (isset($_GET["id_cmd"])) {
							echo $prix_ord;
						} else {
							echo "0";
						} ?>" class="form-control text-right" style="width:100%;">
					</div>
					<span>Euros / Litre</span>
				</div>
				<div class="form-inline">
					<label for="prix_sup" class="col-sm-4 col-form-label" style="padding-left:0;">Prix Supérieur</label>
					<div class="col-sm-3" style="padding:0">
						<input type="text" name="prix_sup" value="<?php if (isset($_GET["id_cmd"])) {
							echo $prix_sup;
						} else {
							echo "0";
						} ?>" class="form-control text-right" style="width:100%;">
					</div>
					<span>Euros / Litre</span>
				</div>
				<?php
				if (strlen($cmd_details["cmd_prix_sup"] ?? '') > 0 && strlen($cmd_details["cmd_prix_ord"] ?? '') > 0) {
					if ($cmd_details['cmd_typefuel'] == 1) {
						$prix_total = $prix_ord * $cmd_details["cmd_qte"];
					}
					if ($cmd_details['cmd_typefuel'] == 2) {
						$prix_total = $prix_sup * $cmd_details["cmd_qte"];
					}
				}
				?>
				<div class="form-inline">
					<label for="prix_T" class="col-sm-4 col-form-label" style="padding-left:0;">Prix Total</label>
					<div class="col-sm-3" style="padding:0">
						<input type="text" name="prix_T" value="<?php if (isset($prix_total)) {
							echo $prix_total;
						} ?>" class="form-control text-right" style="width:100%;">
					</div>
					<span>Euros</span>
				</div>
				<div class="form-inline">
					<label for="qte_livree" class="col-sm-4 col-form-label" style="padding-left:0;">Quantité
						livrée</label>
					<div class="col-sm-3" style="padding:0">
						<input type="text" name="qte_livree" value="<?php if (isset($_GET["id_cmd"])) {
							echo $cmd_details['cmd_qtelivre'];
						} ?>" class="form-control text-right" style="width:100%;">
					</div>
					<span>Litres</span>
				</div>
			</div>
			<div class="col-sm-6" style="border-left:1px solid #0b242436;padding: 0 0 0 2%;">
				<div class="form-inline" style="margin: 2% 0 0 0;">
					<label for="etat_1" class="col-sm-3 col-form-label" style="padding-left:0;">Statut de la
						commande</label>
					<div class="col-sm-3" style="padding:0">
						<select class="form-control input-custom" name="etat_1">
							<option value="10" <?php if (isset($_GET["id_cmd"])) {
								if ($cmd_details['cmd_status'] == '10') {
									echo "selected='selected'";
								}
							} ?>>Utilisateur</option>
							<option value="12" <?php if (isset($_GET["id_cmd"])) {
								if ($cmd_details['cmd_status'] == '12') {
									echo "selected='selected'";
								}
							} ?>>Attachée</option>
							<option value="13" <?php if (isset($_GET["id_cmd"])) {
								if ($cmd_details['cmd_status'] == '13') {
									echo "selected='selected'";
								}
							} ?>>Proposée</option>
							<option value="15" <?php if (isset($_GET["id_cmd"])) {
								if ($cmd_details['cmd_status'] == '15') {
									echo "selected='selected'";
								}
							} ?>>Groupée</option>
							<option value="17" <?php if (isset($_GET["id_cmd"])) {
								if ($cmd_details['cmd_status'] == '17') {
									echo "selected='selected'";
								}
							} ?>>Prix proposé</option>
							<option value="20" <?php if (isset($_GET["id_cmd"])) {
								if ($cmd_details['cmd_status'] == '20') {
									echo "selected='selected'";
								}
							} ?>>Prix validé</option>
							<option value="25" <?php if (isset($_GET["id_cmd"])) {
								if ($cmd_details['cmd_status'] == '25') {
									echo "selected='selected'";
								}
							} ?>>Livrable</option>
							<option value="30" <?php if (isset($_GET["id_cmd"])) {
								if ($cmd_details['cmd_status'] == '30') {
									echo "selected='selected'";
								}
							} ?>>Livrée</option>
							<option value="40" <?php if (isset($_GET["id_cmd"])) {
								if ($cmd_details['cmd_status'] == '40') {
									echo "selected='selected'";
								}
							} ?>>Terminée</option>
							<option value="50" <?php if (isset($_GET["id_cmd"])) {
								if ($cmd_details['cmd_status'] == '50') {
									echo "selected='selected'";
								}
							} ?>>Annulée</option>
							<option value="52" <?php if (isset($_GET["id_cmd"])) {
								if ($cmd_details['cmd_status'] == '52') {
									echo "selected='selected'";
								}
							} ?>>Annulée / Livraison</option>
							<option value="55" <?php if (isset($_GET["id_cmd"])) {
								if ($cmd_details['cmd_status'] == '55') {
									echo "selected='selected'";
								}
							} ?>>Annulée / Prix</option>
							<option value="99" <?php if (isset($_GET["id_cmd"])) {
								if ($cmd_details['cmd_status'] == '99') {
									echo "selected='selected'";
								}
							} ?>>Annulée / Compte désactivé</option>
						</select>
					</div>
					<?php
					if (isset($cmd_details['four_id'])) {
						$four_id = $cmd_details['four_id'];
						$four = " SELECT nom FROM pmp_fournisseur
									WHERE id = '$four_id' ";
						$four = my_query($co_pmp, $four);
						$four = mysqli_fetch_array($four);
						?>
						<div class="col-sm-3">
							<input type="text" name="four" value="<?= $four["nom"]; ?>" class="form-control"
								style="width:100%;">
						</div>
						<div class="col-sm-3">
							<a href="details_fournisseur.php?id_four=<?= $four_id; ?>&return=details"
								class="btn btn-primary">FICHE FOURNISSEUR</a>
						</div>
						<?php
					}
					?>

				</div>
				<label for="com_client" class="col-form-label" style="padding-left:0;">Commentaire client</label>
				<textarea name="com_client" rows="3" class="form-control" style="width: 111%;height: auto;" value="<?php if (isset($_GET["id_cmd"])) {
					echo $cmd_details['cmd_comment'];
				} ?>"><?php if (isset($_GET["id_cmd"])) {
					 echo $cmd_details['cmd_comment'];
				 } ?></textarea>
			</div>
			<!-- <div class="col-sm-2" style="border-left:1px solid #0b242436;padding: 3% 0 0 2%;margin-left: 4%;">
				<input type="submit" name="elec" class="btn btn-secondary"value="INFO ELEC" style="width:100%; margin-bottom:2%;"><br>
				<input type="submit" name="sms" class="btn btn-warning" value="ENVOYER SMS" style="width:100%">
			</div> -->
		</div>
		<label for="com_four" class="col-form-label" style="padding-left:0;">Commentaire destiné au fournisseur</label>
		<textarea name="com_four" rows="3" class="form-control" style="width: 100%;height: 10%;" value="<?php if (isset($_GET["id_cmd"])) {
			echo $cmd_details['cmd_commentfour'];
		} ?>"><?php if (isset($_GET["id_cmd"])) {
			 echo $cmd_details['cmd_commentfour'];
		 } ?></textarea>
		<label for="comment_du_four" class="col-form-label" style="padding-left:0;">Commentaire du fournisseur</label>
		<textarea name="comment_du_four" rows="1" class="form-control" style="width: 100%;height: auto;" value=""
			disabled="disabled"><?php if (isset($cmd_details['cmd_comment_du_four'])) {
				echo $cmd_details['cmd_comment_du_four'];
			} ?></textarea>
		<hr>
		<label for="com_histo" class="col-form-label" style="padding-left:0;">Commentaire pour historique</label>
		<textarea name="com_histo" rows="1" class="form-control" style="width: 100%;height: auto;" value=""></textarea>
		<div class="tableau" style="height:280px">
			<table class="table">
				<thead>
					<tr>
						<th style="width: 3%;"></th>
						<th>Date</th>
						<th>Qui</th>
						<th>Action</th>
						<th>Valeur</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (isset($_GET["id_cmd"])) {

						while ($histo = mysqli_fetch_array($res)) {
							$valeur = str_replace(array('\r', '\n', '\\'), ' ', $histo["his_valeur"]);
							if ($valeur != ' ') {
								?>
								<tr class="select">
									<td><i class="fas fa-arrow-right"></i></td>
									<td><?= $histo["his_date"]; ?></td>
									<td><?= $histo["his_intervenant"]; ?></td>
									<td><?= $histo["his_action"]; ?></td>
									<td><?= $valeur; ?></td>
								</tr>
								<?php
							}
						}
					}
					?>
				</tbody>
			</table>
		</div>
		<div class="text-right">
			<input type="submit" name="modifier_commande" class="btn btn-primary" value="OK">
		</div>
		<div class="modal fade" id="validateForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Attention</h5>
						<button type="button" class="btn-close fermer-modal" data-bs-dismiss="modal" aria-label="Close">
							<i class="fa-solid fa-xmark"></i> </button>
					</div>
					<div class="modal-body">
						<p>Des modifications ont été apportées sur cette page, voulez-vous les enregistrer ?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary fermer-modal"
							data-bs-dismiss="modal">Fermer</button>
						<input type="submit" class="btn btn-warning quitter_page" name="quitter_page"
							value="Non / Sortie">
						<input type="submit" class="btn btn-primary valider_form" name="valide_modifier_commande"
							value="Oui / Sortie">
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="js/script_commandes.js" charset="utf-8"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$("input").on("input", function () {
			$(document).on('click', 'a', function (e) {
				e.preventDefault();
				e.stopPropagation();
				url = jQuery(this).attr('href');
				$('#validateForm').modal('show');
				$(".valider_form").click(function () {
					$(".new_url").val(url);
				});
				$(".quitter_page").click(function () {
					$(".new_url").val(url);
				});
			});
		});

		$("select").on("input", function () {
			$(document).on('click', 'a', function (e) {
				e.preventDefault();
				e.stopPropagation();
				url = jQuery(this).attr('href');
				$('#validateForm').modal('show');
				$(".valider_form").click(function () {
					$(".new_url").val(url);
				});
				$(".quitter_page").click(function () {
					$(".new_url").val(url);
				});
			});
		});

		$("textarea").on("input", function () {
			$(document).on('click', 'a', function (e) {
				e.preventDefault();
				e.stopPropagation();
				url = jQuery(this).attr('href');
				$('#validateForm').modal('show');
				$(".valider_form").click(function () {
					$(".new_url").val(url);
				});
				$(".quitter_page").click(function () {
					$(".new_url").val(url);
				});
			});
		});
	});
</script>