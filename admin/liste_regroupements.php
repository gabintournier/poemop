<style media="screen">
	.ligne-menu {
		width: 35% !important;
	}

	.bouton-ajouter {
		margin-left: 26% !important;
	}

	.info-icon {
		width: 25px !important
	}

	.info {
		width: 600px !important
	}
</style>
<?php
session_start();
if (!function_exists('ensureEtatFourDefaults')) {
	function ensureEtatFourDefaults()
	{
		if (!isset($_SESSION["etat_four"])) {
			$_SESSION["etat_four"] = '10';
		}
		if (!isset($_SESSION["etat_four2"])) {
			$_SESSION["etat_four2"] = '10';
		}
	}
}
if (!isset($_SESSION['user'])) {
	header('Location: /admin/connexion.php');
	die();
}

ensureEtatFourDefaults();

$title = 'Liste des regroupements';
$title_page = 'Liste des regroupements';

if (isset($_SESSION['facture_saisie'])) {
	unset($_SESSION['facture_saisie']);
}

if (isset($_GET["return"]) == 'fournisseur') {
	$return = true;
	$link = '/admin/details_fournisseur.php?id_four=' . $_GET["id_four"];
	?>
	<style media="screen">
		.menu>h1,
		.ligne-menu {
			margin-left: 6%;
		}
	</style>
	<?php
}

$button = true;
$link_button = 'details_groupement.php?nouveau=1';
$button_name = 'NOUVEAU GROUPEMENT';
$icon = '<i class="fas fa-plus"></i>';
ob_start();

include_once "../inc/pmp_co_connect.php";
include_once "inc/pmp_inc_fonctions_commandes.php";
include_once "inc/pmp_inc_fonctions_fournisseurs.php";
include_once "inc/pmp_inc_fonctions_groupements.php";
include_once "inc/pmp_inc_groupements.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);


$res_four = getFournisseursListe($co_pmp);

if (!empty($_POST["charger_grp"])) {
	unset($_SESSION["etat_four"]);
	unset($_SESSION["etat_four2"]);
	unset($_SESSION["resp"]);
	unset($_SESSION["fournisseur_id"]);
	unset($_SESSION["date_min"]);
	unset($_SESSION["date_max"]);
	unset($_SESSION["n_fact"]);
	unset($_SESSION["charger_calculer"]);
	unset($_SESSION["charger_mois"]);

	$_SESSION["etat_four"] = $_POST["etat_four"];
	$_SESSION["etat_four2"] = $_POST["etat_four2"];
	$_SESSION["resp"] = $_POST["resp"];

	if (!empty($_POST["four_id"])) {
		$_SESSION["fournisseur_id"] = $_POST["four_id"];
	} else {
		unset($_SESSION["fournisseur_id"]);
	}
	if (!empty($_POST["date_min"])) {
		$_SESSION["date_min"] = $_POST["date_min"];
	}
	if (!empty($_POST["date_max"])) {
		$_SESSION["date_max"] = $_POST["date_max"];
	}

	$res = getFiltresGroupements($co_pmp);
	$_SESSION["charger_grp"] = $_POST["charger_grp"];
} elseif (!empty($_POST["vider"])) {
	unset($_SESSION["etat_four"]);
	unset($_SESSION["etat_four2"]);
	unset($_SESSION["resp"]);
	unset($_SESSION["fournisseur_id"]);
	unset($_SESSION["date_min"]);
	unset($_SESSION["date_max"]);
	unset($_SESSION["n_fact"]);
	unset($_SESSION["charger_calculer"]);
	unset($_SESSION["charger_mois"]);
	unset($_SESSION["charger_grp"]);
	ensureEtatFourDefaults();
	$res = getListeRegroupementsCréer($co_pmp);
	// if(!empty($_POST["exporter_grp"]))
	// {
	// 	ExporterListeGrpt($co_pmp, $res);
	// 	$res = getListeRegroupementsCréer($co_pmp);
	// }
} elseif (!empty($_POST["charger_facture"])) {
	unset($_SESSION["etat_four"]);
	unset($_SESSION["etat_four2"]);
	unset($_SESSION["resp"]);
	unset($_SESSION["fournisseur_id"]);
	unset($_SESSION["date_min"]);
	unset($_SESSION["date_max"]);
	unset($_SESSION["n_fact"]);
	unset($_SESSION["charger_calculer"]);
	unset($_SESSION["charger_grp"]);
	unset($_SESSION["charger_mois"]);
	ensureEtatFourDefaults();

	$terminer_grp = "ok";
	if (!empty($_POST["n_fact"])) {
		$_SESSION["n_fact"] = $_POST["n_fact"];
		$res = getFiltresGroupementsFacture($co_pmp, $_SESSION["n_fact"]);
		// if(!empty($_POST["exporter_grp"]))
		// {
		// 	ExporterListeGrpt($co_pmp, $res);
		// 	$res = getFiltresGroupementsFacture($co_pmp, $_SESSION["n_fact"]);
		// }
	}

} elseif (!empty($_POST["charger_calculer"])) {
	unset($_SESSION["charger_mois"]);
	unset($_SESSION["charger_grp"]);

	$_SESSION["etat_four"] = $_POST["etat_four"];
	$_SESSION["etat_four2"] = $_POST["etat_four2"];
	$_SESSION["resp"] = $_POST["resp"];

	if (!empty($_POST["four_id"])) {
		$_SESSION["fournisseur_id"] = $_POST["four_id"];
	}

	if (!empty($_POST["date_min"])) {
		$_SESSION["date_min"] = $_POST["date_min"];
	}
	if (!empty($_POST["date_max"])) {
		$_SESSION["date_max"] = $_POST["date_max"];
	}

	$res = getFiltresGroupementsCalculer($co_pmp);
	$attachee = GetStatsGroupement($co_pmp, '12');
	$groupee = GetStatsGroupement($co_pmp, '15');
	$p_propose = GetStatsGroupement($co_pmp, '17');
	$p_valide = GetStatsGroupement($co_pmp, '20');
	$livrable = GetStatsGroupement($co_pmp, '25');
	$livree = GetStatsGroupement($co_pmp, '30');
	$term = GetStatsGroupement($co_pmp, '37');
	$annul = GetStatsGroupement($co_pmp, '52');
	$annulp = GetStatsGroupement($co_pmp, '55');
	$en_cours = $attachee["statut"] + $groupee["statut"] + $p_propose["statut"];
	$valide = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $term["statut"];
	$annul = (float) ($annulp["statut"] ?? 0);

	$denominateur = $valide + $annul;

	if ($denominateur == 0) {
		$projection = 0; // Pas de division possible
	} else {
		$projection = ($valide / $denominateur) * $en_cours + $valide;
	}

	$_SESSION["charger_calculer"] = $_POST["charger_calculer"];
} elseif (!empty($_POST["charger_mois"])) {
	unset($_SESSION["charger_calculer"]);
	unset($_SESSION["charger_grp"]);

	$date = new DateTime();
	$dateDeb = $date->format('Y-m-01');
	$dateFin = $date->format('Y-m-t');

	$_SESSION["date_min"] = $dateDeb;
	$_SESSION["date_max"] = $dateFin;
	$_SESSION["etat_four"] = '10';
	$_SESSION["etat_four2"] = '37';

	$res = GetMoisEnCours($co_pmp);
	$attachee = GetStatsGroupement($co_pmp, '12');
	$groupee = GetStatsGroupement($co_pmp, '15');
	$p_propose = GetStatsGroupement($co_pmp, '17');
	$p_valide = GetStatsGroupement($co_pmp, '20');
	$livrable = GetStatsGroupement($co_pmp, '25');
	$livree = GetStatsGroupement($co_pmp, '30');
	$term = GetStatsGroupement($co_pmp, '37');
	$annul = GetStatsGroupement($co_pmp, '50');
	$annulp = GetStatsGroupement($co_pmp, '55');
	$en_cours = $attachee["statut"] + $groupee["statut"] + $p_propose["statut"];
	$valide = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $term["statut"];
	$projection = (($valide) / ($valide + $annulp["statut"]) * $en_cours) + $valide;
	$_SESSION["charger_mois"] = $_POST["charger_mois"];
}

if (isset($_SESSION["charger_calculer"])) {
	$res = getFiltresGroupementsCalculer($co_pmp);
	$attachee = GetStatsGroupement($co_pmp, '12');
	$groupee = GetStatsGroupement($co_pmp, '15');
	$p_propose = GetStatsGroupement($co_pmp, statut: '17');
	$p_valide = GetStatsGroupement($co_pmp, '20');
	$livrable = GetStatsGroupement($co_pmp, '25');
	$livree = GetStatsGroupement($co_pmp, '30');
	$term = GetStatsGroupement($co_pmp, '37');
	$annul = GetStatsGroupement($co_pmp, '52');
	$annulp = GetStatsGroupement($co_pmp, statut: '55');
	$en_cours = $attachee["statut"] + $groupee["statut"] + $p_propose["statut"];
	$valide = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $term["statut"];
	$annul = (float) ($annulp["statut"] ?? 0);

	$denominateur = $valide + $annul;

	if ($denominateur == 0) {
		$projection = 0; // sécurité anti division par zéro
	} else {
		$projection = ($valide / $denominateur) * $en_cours + $valide;
	}

	if (!empty($_POST["exporter_grp"])) {
		ExporterListeGrptStats($co_pmp, $res);
	}
} elseif (isset($_SESSION["charger_mois"])) {
	$res = GetMoisEnCours($co_pmp);
	$attachee = GetStatsGroupement($co_pmp, '12');
	$groupee = GetStatsGroupement($co_pmp, '15');
	$p_propose = GetStatsGroupement($co_pmp, '17');
	$p_valide = GetStatsGroupement($co_pmp, '20');
	$livrable = GetStatsGroupement($co_pmp, '25');
	$livree = GetStatsGroupement($co_pmp, '30');
	$term = GetStatsGroupement($co_pmp, '40');
	$annul = GetStatsGroupement($co_pmp, '50');
	$annulp = GetStatsGroupement($co_pmp, '55');
	$en_cours = $attachee["statut"] + $groupee["statut"] + $p_propose["statut"];
	$valide = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $term["statut"];
	$projection = (($valide) / ($valide + $annulp["statut"]) * $en_cours) + $valide;

	if (!empty($_POST["exporter_grp"])) {
		ExporterListeGrptStats($co_pmp, $res);
	}
} elseif (isset($_SESSION["charger_grp"])) {
	$res = getFiltresGroupements($co_pmp);
	if (!empty($_POST["exporter_grp"])) {
		ExporterListeGrpt($co_pmp, $res);
	}
} elseif (!empty($_SESSION["n_fact"])) {
	$terminer_grp = "ok";
	$res = getFiltresGroupementsFacture($co_pmp, $_SESSION["n_fact"]);
	// if(!empty($_POST["exporter_grp"]))
	// {
	// 	ExporterListeGrpt($co_pmp, $res);
	// 	$res = getFiltresGroupementsFacture($co_pmp, $_SESSION["n_fact"]);
	// }
} elseif (isset($_GET["id_four"])) {
	$res = getListeRegroupementsFournisseur($co_pmp, $_GET["id_four"]);
} else {
	$res = getListeRegroupementsCréer($co_pmp);
	if (!empty($_POST["exporter_grp"])) {
		ExporterListeGrpt($co_pmp, $res);
		$res = getListeRegroupementsCréer($co_pmp);
	}
}

if (isset($_GET["grp"])) {
	$message_type = "info";
	$message_icone = "fa-exclamation";
	$message_titre = "Info";
	$message = "Le groupement a été supprimé avec succès";
}

if (isset($_GET["msg"]) && $_GET["msg"] == "no_id_grp") {
	$message_type = "Info";
	$message_icone = "fa-exclamation";
	$message_titre = "Attention";
	$message = "Aucun groupement sélectionné.<br>Le groupement n'existe pas ou plus.";
}

if (isset($message)) {
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
						<label for="etat_four" class="col-sm-8 col-form-label" style="padding-left:0;">A partir du
							statut</label>
						<div class="col-sm-4" style="padding:0">
							<select class="form-control input-custom" name="etat_four">
								<option value="5" <?php if (isset($_SESSION["etat_four"])) {
									if ($_SESSION['etat_four'] == '5') {
										echo "selected='selected'";
									}
								} ?>>5 - Prévu
								</option>
								<option value="10" <?php if (isset($_SESSION["etat_four"])) {
									if ($_SESSION['etat_four'] == '10') {
										echo "selected='selected'";
									}
								} else {
									echo "selected='selected'";
								} ?>>10 - Créé</option>
								<option value="15" <?php if (isset($_SESSION["etat_four"])) {
									if ($_SESSION['etat_four'] == '15') {
										echo "selected='selected'";
									}
								} ?>>15 - Envoyé
								</option>
								<option value="30" <?php if (isset($_SESSION["etat_four"])) {
									if ($_SESSION['etat_four'] == '30') {
										echo "selected='selected'";
									}
								} ?>>30 - Livré
								</option>
								<option value="33" <?php if (isset($_SESSION["etat_four"])) {
									if ($_SESSION['etat_four'] == '33') {
										echo "selected='selected'";
									}
								} ?>>33 - A facturer
								</option>
								<option value="37" <?php if (isset($_SESSION["etat_four"])) {
									if ($_SESSION['etat_four'] == '37') {
										echo "selected='selected'";
									}
								} ?>>37 - Facturé
								</option>
								<option value="50" <?php if (isset($_SESSION["etat_four"])) {
									if ($_SESSION['etat_four'] == '50') {
										echo "selected='selected'";
									}
								} ?>>50 - Annulé
								</option>
							</select>
						</div>
					</div>
					<div class="form-inline" style="margin: 5px 0 5px 0;">
						<label for="etat_four2" class="col-sm-8 col-form-label" style="padding-left:0;">Jusqu'au
							statut</label>
						<div class="col-sm-4" style="padding:0">
							<select class="form-control input-custom" name="etat_four2">
								<option value="5" <?php if (isset($_SESSION["etat_four2"])) {
									if ($_SESSION['etat_four2'] == '5') {
										echo "selected='selected'";
									}
								} ?>>5 - Prévu
								</option>
								<option value="10" <?php if (isset($_SESSION["etat_four2"])) {
									if ($_SESSION['etat_four2'] == '10') {
										echo "selected='selected'";
									}
								} else {
									echo "selected='selected'";
								} ?>>10 - Créé</option>
								<option value="15" <?php if (isset($_SESSION["etat_four2"])) {
									if ($_SESSION['etat_four2'] == '15') {
										echo "selected='selected'";
									}
								} ?>>15 - Envoyé
								</option>
								<option value="30" <?php if (isset($_SESSION["etat_four2"])) {
									if ($_SESSION['etat_four2'] == '30') {
										echo "selected='selected'";
									}
								} ?>>30 - Livré
								</option>
								<option value="33" <?php if (isset($_SESSION["etat_four2"])) {
									if ($_SESSION['etat_four2'] == '33') {
										echo "selected='selected'";
									}
								} ?>>33 - A
									facturer</option>
								<option value="37" <?php if (isset($_SESSION["etat_four2"])) {
									if ($_SESSION['etat_four2'] == '37') {
										echo "selected='selected'";
									}
								} ?>>37 - Facturé
								</option>
								<?php
								if (!empty($_POST["charger_mois"])) {
									?>
									<?php
								} elseif (isset($_GET["id_four"])) {
									?>
									<?php
								} else {
									?>
									<?php
								}
								?>
								<option value="50" <?php if (isset($_SESSION["etat_four2"])) {
									if ($_SESSION['etat_four2'] == '50') {
										echo "selected='selected'";
									}
								} ?>>50 - Annulé
								</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-3" style="margin-left:3%;">
					<div class="row">
						<div class="col-sm-6">
							<label for="date_min" class="col-sm-12 col-form-label" style="padding-left:0;">Date entre
								le</label>
							<input type="date" name="date_min" class="form-control" value="<?php if (isset($_SESSION["date_min"])) {
								echo $_SESSION["date_min"];
							} elseif (isset($dateDeb)) {
								echo $dateDeb;
							} ?>" style="width:100%;">
						</div>
						<div class="col-sm-6">
							<label for="date_max" class="col-sm-12 col-form-label" style="padding-left:0;">et le
								'inclus'</label>
							<input type="date" name="date_max" class="form-control" value="<?php if (isset($_SESSION["date_max"])) {
								echo $_SESSION["date_max"];
							} elseif (isset($dateFin)) {
								echo $dateFin;
							} ?>" style="width:100%;">
						</div>
					</div>
					<div class="row" style="margin-top: 3%;">
						<div class="col-sm-8">
							<div class="form-inline" style="margin: 5px 0 0 0;">
								<label for="n_four" class="col-sm-5 col-form-label"
									style="padding-left:0;">Fournisseur</label>
								<div class="col-sm-7" style="padding:0">
									<select class=" form-control" name="four_id" style="width:100%;">
										<option value=""></option>
										<?php
										while ($fournisseur = mysqli_fetch_array($res_four)) {
											if (isset($_GET["id_four"])) {
												?>
												<option value="<?= $fournisseur["id"]; ?>" <?php if (isset($_GET["id_four"])) {
													  if ($_GET["id_four"] == $fournisseur["id"]) {
														  echo "selected='selected'";
													  }
												  } ?>><?= $fournisseur["nom"]; ?></option>
												<?php
											} else {
												?>
												<option value="<?= $fournisseur["id"]; ?>" <?php if (isset($_SESSION["fournisseur_id"])) {
													  if ($_SESSION['fournisseur_id'] == $fournisseur["id"]) {
														  echo "selected='selected'";
													  }
												  } ?>><?= $fournisseur["nom"]; ?></option>
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
										<option value="0" <?php if (isset($_SESSION["resp"])) {
											if ($_SESSION['resp'] == '0') {
												echo "selected='selected'";
											}
										} ?>></option>
										<option value="STE" <?php if (isset($_SESSION["resp"])) {
											if ($_SESSION['resp'] == 'STE') {
												echo "selected='selected'";
											}
										} ?>>STE
										</option>
										<option value="MAG" <?php if (isset($_SESSION["resp"])) {
											if ($_SESSION['resp'] == 'MAG') {
												echo "selected='selected'";
											}
										} ?>>MAG
										</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-2 align-self-center">
					<input type="submit" name="charger_grp" value="CHARGER" class="btn btn-primary"
						style="min-width:100%;"><br>
					<input type="submit" name="charger_calculer" value="CHARGER ET CALCULER" class="btn btn-secondary"
						style="min-width:100%; margin-top:2%;">
					<input type="submit" name="vider" value="VIDER" class="btn btn-warning"
						style="min-width:100%; margin-top:2%;">
				</div>
				<div class="col-sm-2" style="border-left: 1px solid #0b242436;">
					<label class="label-title" style="margin: 0;">Facturation</label>
					<div class="ligne"></div>
					<div class="form-inline" style="margin: 5px 0 0 0;">
						<label for="n_four" class="col-sm-5 col-form-label" style="padding-left:0;">N° Facture</label>
						<div class="col-sm-7" style="padding:0">
							<input type="text" class="form-control" name="n_fact" value="<?php if (isset($_SESSION["n_fact"])) {
								echo $_SESSION["n_fact"];
							} ?>" style="width:100%;">
						</div>
					</div>
					<div class="form-inline">
						<input type="submit" name="charger_facture" value="CHARGER" class="btn btn-primary"
							style="margin-top:5px; width: 50%;"><br>
						<button type="submit" name="appliquer_facture" class="btn btn-secondary"
							style="margin-top:5px; margin-left:2%;" data-confirm-modal="true"
							data-confirm-title="Appliquer ce numéro de facture ?"
							data-confirm-message="Appliquer ce numéro à la liste des groupements chargés ?"
							data-confirm-detail="<?php if (isset($_SESSION['n_fact'])) {
								echo htmlspecialchars($_SESSION['n_fact']);
							} ?>">APPLIQUER</button>
					</div>
				</div>
				<div class="col-sm-1 text-right align-self-end">
					<button type="submit" name="appliquer_terminer" class="btn btn-outline-primary" <?php if (isset($terminer_grp)) {
						if ($terminer_grp == "ok") {
							echo "";
						} else {
							echo "disabled=\"disabled\"";
						}
					} else {
						echo "disabled=\"disabled\"";
					} ?>
						style="margin-bottom: 10px;background: white;border-radius:7px" data-confirm-modal="true"
						data-confirm-title="Appliquer le statut 37 ?"
						data-confirm-message="Passer la liste des groupements au statut 37 - Facture ?"
						data-confirm-detail="Status : 37 - Facture">TERMINER</button>
				</div>
				<div class="col-sm-2 text-right align-self-end" style="max-width: 13%;">
					<input type="submit" name="exporter_grp" value="EXPORTER" class="btn btn-secondary"
						style="width: 70%;margin-bottom: 10px;">
				</div>
			</div>
			<hr>
			<label class="label-title" style="margin: 0;">Mise à jour des groupements</label>
			<div class="ligne"></div>
			<div class="row">
				<div class="col-sm-4">
					<div class="form-inline" style="margin:5px 0 0 0;">
						<label for="nouveau_statut" class="col-sm-8 col-form-label" style="padding-left:0;">Passer les
							groupements suivants au statut</label>
						<div class="col-sm-4" style="padding:0">
							<select class="form-control input-custom" name="nouveau_statut">
								<option value="5">5 - Prévu</option>
								<option value="10">10 - Créé</option>
								<option value="15">15 - Envoyé</option>
								<option value="30">30 - Livré</option>
								<option value="33">33 - A facturer</option>
								<option value="37">37 - Facturé</option>
								<option value="50">50 - Annulé</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-3" style="margin:5px 0 0 0;padding: 0;">
					<button type="button" name="button" data-bs-toggle="modal" class="btn btn-primary"
						data-bs-target="#ValidationStatut" style="width:35%;">VALIDER</button>
					<div class="modal fade" id="ValidationStatut" tabindex="-1" aria-labelledby="exampleModalLabel"
						aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel"> Modifier le statut des groupements
									</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
										<i class="fa-solid fa-xmark"></i> </button>
								</div>
								<div class="modal-body">
									Êtes-vous sur de passer la liste des groupements au statut<br><strong
										class="statutSel"></strong> ?
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
							if (isset($_SESSION["charger_calculer"]) || !empty($_POST["charger_mois"])) {
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
								<th>Ann Livr</th>
								<th>%&nbspAnn&nbspLivr</th>
								<?php
							}
							?>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 0;
						$id_grp = "";
						if (isset($_SESSION["charger_calculer"]) || !empty($_POST["charger_mois"])) {
							while ($regroupement = mysqli_fetch_array($res)) {
								$total_grp_ap = $regroupement["p_valide"] + $regroupement["livrable"] + $regroupement["livree"] + $regroupement["terminee"] + $regroupement["annulp"];
								$total_grp_a = $regroupement["p_valide"] + $regroupement["livrable"] + $regroupement["livree"] + $regroupement["terminee"] + $regroupement["annul"];


								$pannulp_grp = cacul_pourcentage($regroupement["annulp"], $total_grp_ap, '100');
								$pannul_grp = cacul_pourcentage($regroupement["annul"], $total_grp_a, '100');

								if ($pannulp_grp == 0) {
									$pourc_annulp_grp = "";
								} else {
									$pourc_annulp_grp = $pannulp_grp;
								}
								if ($pannul_grp == 0) {
									$pourc_annul_grp = "";
								} else {
									$pourc_annul_grp = $pannul_grp;
								}

								$date = $regroupement["date_grp"];
								$date = date_create($date);
								$date_grp = date_format($date, "d/m/Y");

								$id_grp .= $regroupement["groupe_cmd"] . ";";

								if ($regroupement["statut"] <= 33) {
									$terminee = $regroupement["terminee"];
								} else {
									$terminee = $regroupement["terminee_livree"];
								}
								?>
								<tr class="select regroupements">
									<input type="hidden" name="id_grp[]" class="id_grp[]<?php print $i++; ?>"
										value="<?= $regroupement["groupe_cmd"]; ?>">
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
						} else {
							while ($regroupement = mysqli_fetch_array($res)) {
								$date = $regroupement["date_grp"];
								$date = date_create($date);
								$date_grp = date_format($date, "d/m/Y");

								$id_grp .= $regroupement["id"] . ";";
								?>
								<tr class="select regroupements">
									<input type="hidden" name="id_grp[]" class="id_grp[]<?php print $i++; ?>"
										value="<?= $regroupement["id"]; ?>">
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
			if (isset($_SESSION["charger_calculer"]) || !empty($_POST["charger_mois"])) {

				// 🔒 Sécurisation PHP 8 : on force les variables à être des tableaux avec une clé "statut"
				$vars = [&$p_valide, &$livrable, &$livree, &$term, &$annulp, &$annul];
				foreach ($vars as &$v) {
					if (!is_array($v)) {
						$v = ["statut" => $v ?? 0];
					}
				}
				unset($v); // sécurité
			
				// Calculs
				$total_ap = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $term["statut"] + $annulp["statut"];
				$total_a = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $term["statut"] + $annul["statut"];

				$pannulp = cacul_pourcentage($annulp["statut"], $total_ap, '100');
				$pannul = cacul_pourcentage($annul["statut"], $total_a, '100');

				$pourc_annulp = ($pannulp == 0) ? "" : $pannulp;
				$pourc_annul = ($pannul == 0) ? "" : $pannul;
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
							<th style="width:90.36px;padding: 0;border-bottom: none;" class="text-center">% Ann P</th>
							<th style="width:67.06px;padding: 0;border-bottom: none;" class="text-center">Ann Livr</th>
							<th style="width:82.27px;padding: 0;border-bottom: none;" class="text-center">% Ann Livr</th>
						</thead>
						<thead>
							<th style="width: 6%;border-bottom: none;">En cours</th>
							<th style="width: 4%;padding: 0;border-bottom: none;">
								<input type="text" value="<?= $en_cours; ?>" class="form-control input-custom">
							</th>
							<th style="width: 5%;border-bottom: none;">Validé</th>
							<th style="width: 4%;padding: 0;border-bottom: none;">
								<input type="text" value="<?= $valide; ?>" class="form-control input-custom">
							</th>
							<th style="width: 6%;border-bottom: none;">Projection</th>
							<th style="width: 4%;padding: 0;border-bottom: none;">
								<input type="text" value="<?= number_format($projection, 0, ',', ' '); ?>"
									class="form-control input-custom">
							</th>
							<th style="width: 8.3%;border-bottom: none;">Totaux m3</th>
							<th style="width:89.84px;padding: 0;border-bottom: none;">
								<input type="text" value="<?= $attachee["statut"]; ?>"
									class="form-control input-custom text-center">
							</th>
							<th style="width:87.34px;padding: 0;border-bottom: none;">
								<input type="text" value="<?= $groupee["statut"]; ?>"
									class="form-control input-custom text-center">
							</th>
							<th style="width:74.38px;padding: 0;border-bottom: none;">
								<input type="text" value="<?= $p_propose["statut"]; ?>"
									class="form-control input-custom text-center">
							</th>
							<th style="width:74.80px;padding: 0;border-bottom: none;">
								<input type="text" value="<?= $p_valide["statut"]; ?>"
									class="form-control input-custom text-center">
							</th>
							<th style="width:91.80px;padding: 0;border-bottom: none;">
								<input type="text" value="<?= $livrable["statut"]; ?>"
									class="form-control input-custom text-center">
							</th>
							<th style="width:78.05px;padding: 0;border-bottom: none;">
								<input type="text" value="<?= $livree["statut"]; ?>"
									class="form-control input-custom text-center">
							</th>
							<th style="width:100.47px;padding: 0;border-bottom: none;">
								<input type="text" value="<?= $term["statut"]; ?>"
									class="form-control input-custom text-center">
							</th>
							<th style="width:75.39px;padding: 0;border-bottom: none;">
								<input type="text" value="<?= $annulp["statut"]; ?>"
									class="form-control input-custom text-center">
							</th>
							<th style="width:90.36px;padding: 0;border-bottom: none;">
								<input type="text" value="<?= $pourc_annulp; ?>"
									class="form-control input-custom text-center">
							</th>
							<th style="width:67.06px;padding: 0;border-bottom: none;">
								<input type="text" value="<?= $annul["statut"]; ?>"
									class="form-control input-custom text-center">
							</th>
							<th style="width:82.27px;padding: 0;border-bottom: none;">
								<input type="text" value="<?= $pourc_annul; ?>"
									class="form-control input-custom text-center">
							</th>
						</thead>
					</table>
				</div>
				<?php
			}
			?>

			<!--
			<div class="row">

				<div class="col-sm-2" style="max-width: 13%;">
					<input type="submit" name="charger_mois" value="CHARGER MOIS" class="btn btn-warning" style="width: 100%;margin-bottom: 2%;">
				</div>

			</div>
			-->
		</form>
	</div>

</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="js/select2.min.js"></script>
<script src="js/script_commandes.js" charset="utf-8"></script>