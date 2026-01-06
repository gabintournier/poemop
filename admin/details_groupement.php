<link rel="stylesheet" type="text/css" href="css/datatables.min.css" />
<style media="screen">
	.ligne-menu {
		width: 35% !important;
	}

	.menu>h1,
	.ligne-menu {
		margin-left: 6%;
	}

	td {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}

	#trie_table_cmd.dataTable tbody th,
	#trie_table_cmd.dataTable tbody td {
		padding: 0% 0.75rem !important;
	}

	.info {
		width: 685px !important;
	}

	.info-icon {
		width: 25px !important;
	}
</style>
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
if (!isset($_SESSION['user'])) {
	header('Location: /admin/connexion.php');
	die();
}

if (isset($_GET["return"]) == 'fournisseur') {
	$link = '/admin/liste_regroupements.php?id_four=' . $_GET["id_four"] . '&return=fournisseur';
} else {
	$link = '/admin/liste_regroupements.php';
}

ob_start();

include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_commandes.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_groupements.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_zones.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_clients.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_mail_sms.php";

include __DIR__ . "/inc/pmp_inc_fournisseur.php";

if (isset($_GET["id_grp"]) && !empty($_GET["id_grp"])) {
	// Cas où on consulte un groupement existant
	$id_grp = intval($_GET["id_grp"]);
	$query = "SELECT id FROM pmp_regroupement WHERE id = '$id_grp' LIMIT 1";
	$res = my_query($co_pmp, $query);

	if (mysqli_num_rows($res) == 0) {
		// ID invalide → redirection
		header('Location: /admin/liste_regroupements.php?msg=no_id_grp');
		exit;
	}

	$return = true;
	if (isset($_GET["return"])) {
		if ($_GET["return"] == 'details') {
			$link = '/admin/gestion_client_commande.php?id_cmd=' . $_SESSION["id_cmd"] . '&return=cmdes';
		} elseif ($_GET["return"] == 'recherche_ancienne_commande') {
			$link = '/admin/ancienne_commande.php?user_id=' . $_GET["user_id"] . '&return=recherche';
		} elseif ($_GET["return"] == 'cmdes') {
			$link = '/admin/ancienne_commande.php?user_id=' . $_GET["user_id"] . '&id_cmd=' . $_SESSION["id_cmd"] . '&return=cmdes';
		} elseif ($_GET["return"] == 'grp') {
			$link = '/admin/details_groupement.php?id_grp=' . $_GET["id_grp"];
		}
	} else {
		$link = '/admin/liste_regroupements.php';
	}
	$title = 'Détail d\'un regroupement';
	$title_page = 'Détail d\'un regroupement';
} else if (isset($_GET['nouveau']) && $_GET['nouveau'] == 1) {
	// Cas où on veut créer un nouveau groupement
	$title = 'Nouveau groupement';
	$title_page = 'Nouveau groupement';
	$id_grp = null; // Pas encore créé
} else {
	// Pas d'id_grp ni param "nouveau" → on redirige
	header('Location: /admin/liste_regroupements.php?msg=no_id_grp');
	exit;
}

unset($_SESSION['n_dep']);
unset($_SESSION["principaux"]);
unset($_SESSION["non_contacte"]);
unset($_SESSION["partenaires"]);
unset($_SESSION["partenaires_sec"]);
unset($_SESSION["recontacter"]);
unset($_SESSION["recontacter_com"]);
unset($_SESSION["pas_interesse"]);
unset($_SESSION["pas_interessant"]);
unset($_SESSION["autre_fioul"]);
unset($_SESSION["partenanriat_fini"]);
unset($_SESSION["cp_livraison"]);
$res = getFournisseursListe($co_pmp);

$id_grp = isset($_GET['id_grp']) ? $_GET['id_grp'] : '';

// Nettoyage des comptes désactivés présents dans ce groupement (statuts <= 17)
if (!empty($id_grp)) {
	cleanDisabledForGroup($co_pmp, $id_grp);
}

if (!empty($_POST["update_grpt_sortie"]) && $message_type != "info") {
	header('Location: /admin/liste_regroupements.php');
}

if (isset($_POST["numfact"])) {
	$_SESSION["facture_saisie"] = $_POST["numfact"];
}

if (!empty($_POST["basculer_commande"])) {
	$id = $_GET["n_id_cmd"];
	$id_grp = $_GET["id_grp"];
	ajouterCommandegroupement($co_pmp, $id, $id_grp);
}

if (isset($_GET["id_grp"])) {
	$trie = getOrdreDeTrie($co_pmp, $_GET["id_grp"]);
	$arr = str_split($trie["options"], 2);

	$four = getGroupementFour($co_pmp, $_GET["id_grp"]);

	$grp = getGroupementDetails($co_pmp, $_GET["id_grp"]);

	if (isset($cacher_p) || isset($cacher)) {
		if ($cacher_p == 1 && $cacher == 1) {
			$cmdes = getCommandesGroupementsSansProposees($co_pmp, $_GET["id_grp"]);
		} elseif ($cacher_p == 0 && $cacher == 1) {
			$cmdes = getCommandesGroupementsCache($co_pmp, $_GET["id_grp"]);
		} elseif ($cacher == 0 && $cacher_p == 1) {
			$cmdes = getCommandesGroupements($co_pmp, $_GET["id_grp"]);
		} elseif ($cacher == 0 && $cacher_p == 0) {
			$cmdes = getCommandes($co_pmp, $_GET["id_grp"]);
		}
	} else {
		$cmdes = getCommandesGroupementsSansProposees($co_pmp, $_GET["id_grp"]);
	}


	// Total commande + actif
	$cmdes_actif = getCommandesGroupementsActif($co_pmp, $_GET["id_grp"]);
	$toutes_cmdes = getCommandesGroupementsCache($co_pmp, $_GET["id_grp"]);
	$num_cmdes = mysqli_num_rows($toutes_cmdes);
	$num_cmdes_actif = mysqli_num_rows($cmdes_actif);

	$plages = getPlagePrix($co_pmp, $_GET["id_grp"]);

	$res_histo = getHistoriqueGroupement($co_pmp, $_GET["id_grp"]);
	$utilisateurs = getNombreDeLitre($co_pmp, $_GET["id_grp"], '10');
	$attachees = getNombreDeLitre($co_pmp, $_GET["id_grp"], '12');
	$propose = getNombreDeLitre($co_pmp, $_GET["id_grp"], '13');
	$groupees = getNombreDeLitre($co_pmp, $_GET["id_grp"], '15');
	$ppropose = getNombreDeLitre($co_pmp, $_GET["id_grp"], '17');
	$pvalide = getNombreDeLitre($co_pmp, $_GET["id_grp"], '20');
	$livrable = getNombreDeLitre($co_pmp, $_GET["id_grp"], '25');
	$livrees = getNombreDeLitre($co_pmp, $_GET["id_grp"], '30');
	$terminees = getNombreDeLitreLivree($co_pmp, $_GET["id_grp"], '40');

	$annulees = getNombreDeLitre($co_pmp, $_GET["id_grp"], '55');

	$texte = $grp["planning"];
	$planning = array();
	$planning = preg_split("/[\\r\\t\\n]+/i", $texte);

	$res_grp_zone = getRegroupementZones($co_pmp, $_GET["id_grp"], 1);
	$res_grp_zone_exlus = getRegroupementZones($co_pmp, $_GET["id_grp"], 0);

	if (!empty($_POST["recup_prix"])) {
		$nTotal_l = getRecupEnCoursGroupement($co_pmp, $_GET["id_grp"]);

		if ($nTotal_l["en_cours"] > 0) {
			$temps_attente = getRecupEnCours($co_pmp);
			?>
			<div class="toast info" style="width: 580px;">
				<div class="message-icon info-icon">
					<i class="fas fa-exclamation"></i>
				</div>
				<div class="message-content ">
					<div class="message-type">
						Info
					</div>
					<div class="message">
						Une récupération des prix est en cours. Si elle n'est pas terminée dans <?= $temps_attente["en_cours"]; ?>
						minutes alors prévenir un administrateur
					</div>
				</div>
				<div class="message-close">
					<i class="fas fa-times"></i>
				</div>
			</div>
			<?php
		} else {
			$nTotal_l = getRecupGroupement($co_pmp, $_GET["id_grp"]);
			if ($nTotal_l["commande"] > 0) {
				?>
				<form method="post">
					<div class="toast info" style="width: 580px;">
						<div class="message-icon info-icon">
							<i class="fas fa-exclamation"></i>
						</div>
						<div class="message-content ">
							<div class="message-type">
								Info
							</div>
							<div class="message">
								Certaine récupération de prix n'ont pas aboutie, êtes-vous sur de vouloir la relancer ?
								<div class="row" style="padding-right: 30px;">
									<div class="col-sm-10 text-right">
										<button class="message-close btn btn-secondary det" type="button" name="button"
											style="color: #0b2424;width: 60px;height: 30px;">Non</button>
									</div>
									<div class="col-sm-2 text-right">
										<input type="submit" class="btn btn-outline-primary" name="relancer_recup" value="Oui"
											style="width: 60px;height: 30px;border-radius: 6px;background: white;">
									</div>
								</div>
							</div>
						</div>
						<div class="message-close">
							<i class="fas fa-times"></i>
						</div>
					</div>
				</form>
				<?php
			} else {
				$nTotal_l = getNombreCalcul($co_pmp, $_GET["id_grp"]);
				updateRecuperation($co_pmp, $_GET["id_grp"], $nTotal_l["nombre"]);
				$message_type = "success";
				$message_icone = "fa-check";
				$message_titre = "Succès";
				$message = "La récupération des prix a été lancée pour " . $nTotal_l["nombre"] . " commande(s) au statut Groupée !";
			}
		}
	}
	if (!empty($_POST["relancer_recup"])) {
		$nTotal_l = getNombreCalcul($co_pmp, $_GET["id_grp"]);
		updateRecuperation($co_pmp, $_GET["id_grp"], $nTotal_l["nombre"]);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "La récupération des prix a été lancée pour " . $nTotal_l["nombre"] . " commande(s) au statut Groupée !";

		header("Location: " . $_SERVER['REQUEST_URI']);
		exit;
	}
}

if (isset($_POST["fournisseur_id"])) {
	$res_zone = getListeZonesFournisseur($co_pmp, $_POST["fournisseur_id"]);
}

if (isset($_POST["fournisseur_id_2"])) {
	$res_zone2 = getListeZonesFournisseur($co_pmp, $_POST["fournisseur_id_2"]);
}

// if(isset($_GET["message"]) == "basculer")
// {
// 	$message_type = "success";
// 	$message_icone = "fa-check";
// 	$message_titre = "Succès";
// 	$message = "La commande a été modifiée.";
// }

if (isset($_GET["n_id_four"])) {
	$n_four = getNouveauFournisseur($co_pmp, $_GET["n_id_four"]);

	if ($_GET["n_id_four"] != $grp["id_four"]) {
		?>
		<div class="toast info" style="width: 540px;">
			<div class="message-icon info-icon">
				<i class="fas fa-exclamation"></i>
			</div>
			<div class="message-content ">
				<div class="message-type">
					Info
				</div>
				<div class="message">
					Attention, si vous validez, les infos fournisseur et commission du groupement vont être écrasées.<br>
					<a href="details_groupement.php?id_grp=<?= $_GET["id_grp"]; ?>" class="btn btn-outline-primary"
						style="padding: 1% 6% 1% 6%;border-radius: 5px;margin-top:5px;background: #ffffff;">ANNULER</a>
				</div>
			</div>
			<div class="message-close">
				<i class="fas fa-times"></i>
			</div>
		</div>
		<?php
	}
}

if (isset($message)) {
	?>
	<div class="toast <?= $message_type; ?>" style="width: 525px;">
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
	<form method="post" id="FormID">
		<div class="row">
			<div class="col-sm-7" style="border-right: 1px solid #0b242436;">
				<label class="label-title" style="margin: 0;">Infos générales</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-3">
						<div class="form-inline">
							<label for="n_grpt" class="col-sm-5 col-form-label" style="padding-left:0;">N° Grp</label>
							<div class="col-sm-7" style="padding:0">
								<input type="text" name="n_grpt" value="<?php if (isset($_GET["id_grp"])) {
									echo $grp["id"];
								} ?>" class="form-control" style="width:100%;" disabled="disabled">
							</div>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="form-inline">
							<label for="libelle_grpt" class="col-sm-3 col-form-label"
								style="padding-left:0;">Libellé</label>
							<div class="col-sm-9" style="padding:0">
								<input type="text" name="libelle_grpt" value="<?php if (isset($_GET["id_grp"])) {
									echo $grp["libelle"];
								} ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-inline">
							<label for="date_grpt" class="col-sm-3 col-form-label" style="padding-left:0;">Date</label>
							<div class="col-sm-9" style="padding:0">
								<input type="date" name="date_grpt" value="<?php if (isset($_GET["id_grp"])) {
									echo $grp["date_grp"];
								} ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-inline">
							<label for="n_four" class="col-sm-5 col-form-label" style="padding-left:0;">N° Four</label>
							<div class="col-sm-7" style="padding:0">
								<input type="text" name="n_four" id="n_four" value="<?php if (isset($n_four["id"])) {
									echo $n_four["id"];
								} elseif (isset($_GET["id_grp"])) {
									echo $grp["id_four"];
								} ?>" class="form-control" style="width:100%;" disabled="disabled">
							</div>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="form-inline">
							<label for="four_grpt" class="col-sm-3 col-form-label"
								style="padding-left:0;">Fournisseur</label>
							<div class="col-sm-9" style="padding:0">
								<input id="four_grpt" type="text" name="four_grpt" value="<?php if (isset($n_four["nom"])) {
									echo $n_four["nom"];
								} elseif (isset($four["nom"])) {
									echo $four["nom"];
								} ?>" class="form-control" style="width:100%;" disabled="disabled">
								<input type="hidden" name="add_id_four" id="add_id_four" value="">
							</div>
						</div>
					</div>
					<div class="col-sm-2">
						<?php
						if (isset($_GET["id_grp"])) {
							?>
							<a href="details_groupement.php?id_grp=<?= $_GET["id_grp"]; ?>&popup=oui"
								class="btn btn-secondary" style="width:100%">SÉL</a>
							<?php
						} else {
							?>
							<button type="button" name="button" data-bs-toggle="modal" class="btn btn-secondary"
								data-bs-target="#selFour" style="width:100%">SÉL</button>
							<?php
						}
						?>
						<div class="modal fade" id="selFour" tabindex="-1" aria-labelledby="exampleModalLabel"
							aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" style="max-width: 85%;">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Sélectionner un fournisseur</h5>
										<button type="button" class="btn-close b-close" data-bs-dismiss="modal"
											aria-label="Close"> <i class="fas fa-times"></i> </button>
									</div>
									<div class="modal-body">
										<?php include 'form/form_liste_fournisseur.php'; ?>
										<input type="hidden" name="n_id_four" id="n_id_four" value="">

									</div>
									<div class="modal-footer">
										<?php
										if (isset($_GET["id_grp"])) {
											?>
											<a class="btn btn-primary valider_four" style="color:#fff;">VALIDER</a>

											<?php
										} else {
											?>
											<button type="button" class="btn btn-primary valider_four"
												data-bs-dismiss="modal" aria-label="Close"> VALIDER </button>
											<?php
										}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-2">
						<a href="details_fournisseur.php?id_four=<?php if (isset($n_four["id"])) {
							echo $n_four["id"];
						} else {
							echo $grp["id_four"];
						} ?>&id_grp=<?php if (isset($_GET["id_grp"])) {
							 echo $_GET["id_grp"];
						 } ?>&return=grp" class="btn btn-secondary" style="width:100%">FICHE</a>
					</div>
					<div class="col-sm-12">
						<label for="com_grpt" class="col-form-label">Commentaire</label>
						<textarea name="com_grpt" class="form-control" rows="2" style="height:60%;"><?php if (isset($_GET["id_grp"]) && isset($grp["commentaire"])) {
							echo $grp["commentaire"];
						} ?></textarea>
					</div>
					<div class="col-sm-6 mt-1">
						<div class="form-inline">
							<label for="statut_grpt" class="col-sm-5 col-form-label ps-0">Statut groupement</label>
							<div class="col-sm-7 p-0">
								<?php
								$statuts = [
									5 => 'Groupement prévu',
									10 => 'Groupement créé',
									15 => 'Groupement envoyé',
									30 => 'Groupement livré',
									33 => 'Groupement à facturer',
									37 => 'Groupement facturé',
									50 => 'Groupement annulé'
								];

								$selectedStatut = isset($_GET['id_grp']) ? ($grp['statut'] ?? null) : null;
								?>
								<select class="form-control" name="statut_grpt" id="statut_grpt">
									<?php foreach ($statuts as $value => $label): ?>
										<option value="<?= $value ?>" <?= ($value == $selectedStatut) ? 'selected' : '' ?>>
											<?= $value ?> - <?= htmlspecialchars($label) ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>

					<div class="col-sm-12">
						<hr class="separe" style="margin:1% 0 1%;">
						<label class="label-title" style="margin: 0;">Facturation</label>
						<div class="ligne"></div>
					</div>
					<div class="col-sm-5"></div>
					<div class="col-sm-3 text-center" style="margin-left:-7%;">
						<input type="submit" class="btn btn-secondary" name="recup_qte"
							style="font-size:13px;width:85%;" value="Récup qté tableau">
					</div>
					<div class="col-sm-2 text-center">
						<input type="submit" name="calc_fact" class="btn btn-secondary" value="Calc Fact"
							style="font-size:13px;width:85%;">
					</div>
					<div class="col-sm-2 text-right" style="margin-left: 6%;">
						<button type="button" class="btn btn-secondary" name="button" data-bs-toggle="modal"
							class="btn btn-secondary" data-bs-target="#fact"
							style="font-size:13px;width:85%;">Facturation</button>
						<div class="modal fade" id="fact" tabindex="-1" aria-labelledby="exampleModalLabel"
							aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" style="max-width: 35%;">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Information Facturation
											Groupement</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-bs-label="Close"> <i class="fas fa-times"></i> </button>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-sm-4">
												<div class="form-inline">
													<label for="n_grpt_fact" class="col-sm-4 col-form-label"
														style="padding-left:0;">N° Grp</label>
													<div class="col-sm-8" style="padding:0">
														<input type="text" name="n_grpt_fact" value="<?php if (isset($_GET["id_grp"])) {
															echo $grp["id"];
														} ?>" class="form-control" style="width:100%;" disabled="disabled">
													</div>
												</div>
											</div>
											<div class="col-sm-8">
												<div class="form-inline">
													<label for="libelle_grpt_fact" class="col-sm-2 col-form-label"
														style="padding-left:0;">Libellé</label>
													<div class="col-sm-10" style="padding:0">
														<input type="text" name="libelle_grpt_fact" value="<?php if (isset($_GET["id_grp"])) {
															echo $grp["libelle"];
														} ?>" class="form-control" style="width:100%;">
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-inline" style="margin-top:0.5%;">
													<label for="com_ord" class="col-sm-7 col-form-label"
														style="padding-left:0; font-size:13px">Quantité Ordinaire
														Totale</label>
													<div class="col-sm-4" style="padding:0">
														<input type="text" name="com_ord" value="<?php if (isset($_GET["id_grp"])) {
															if ($grp["volord"] != '') {
																echo $grp["volord"];
															} else {
																echo "0";
															}
														} ?>" class="form-control" style="width:100%;">
													</div>
													<label for="com_ord" class="col-sm-1 col-form-label"
														style="padding-left:0; font-size:13px;justify-content: right!important;">L</label>
												</div>
												<div class="form-inline" style="margin-top:0.5%;">
													<label for="com_sup" class="col-sm-7 col-form-label"
														style="padding-left:0; font-size:13px">Quantité Supérieur
														Totale</label>
													<div class="col-sm-4" style="padding:0">
														<input type="text" name="com_sup" value="<?php if (isset($_GET["id_grp"])) {
															if ($grp["volsup"] != '') {
																echo $grp["volsup"];
															} else {
																echo "0";
															}
														} ?>" class="form-control" style="width:100%;">
													</div>
													<label for="com_ord" class="col-sm-1 col-form-label"
														style="padding-left:0; font-size:13px;justify-content: right!important;">L</label>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-inline" style="margin-top:0.5%;">
													<label for="com_ord" class="col-sm-7 col-form-label"
														style="padding-left:0; font-size:13px">Commission
														Ordinaire</label>
													<div class="col-sm-3" style="padding:0">
														<input type="text" name="com_ord" value="<?php if (isset($_GET["id_grp"])) {
															echo number_format($grp["mtcomordht"], 2, ',', ' ');
														} ?>" class="form-control" style="width:100%;">
													</div>
													<label for="com_ord" class="col-sm-2 col-form-label"
														style="padding-left:0; font-size:13px;justify-content: right!important;">HT</label>
												</div>
												<div class="form-inline" style="margin-top:0.5%;">
													<label for="com_sup" class="col-sm-7 col-form-label"
														style="padding-left:0; font-size:13px">Commission
														Supérieur</label>
													<div class="col-sm-3" style="padding:0">
														<input type="text" name="com_sup" value="<?php if (isset($_GET["id_grp"])) {
															echo number_format($grp["mtcomsupht"], 2, ',', ' ');
														} ?>" class="form-control" style="width:100%;">
													</div>
													<label for="com_ord" class="col-sm-2 col-form-label"
														style="padding-left:0; font-size:13px;justify-content: right!important;">HT</label>
												</div>
											</div>
											<div class="col-sm-12 text-left">
												<label for="mail-copie" class="col-sm-7 col-form-label"
													style="padding-left:0; font-size:13px">Destinataire Mail en
													copie</label>
												<input type="text" name="mail-copie" value="<?php if (isset($four["fact_email"])) {
													echo $four["fact_email"];
												} ?>" class="form-control" style="width:100%;">
											</div>
										</div>


									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
											aria-bs-label="Close">Fermer</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-inline" style="margin-top:0.5%;">
							<label for="com_ord" class="col-sm-7 col-form-label"
								style="padding-left:0; font-size:12px">Com ord (€/m3)</label>
							<div class="col-sm-5" style="padding:0">
								<input type="text" name="com_ord" value="<?php if (isset($_GET["id_grp"])) {
									echo number_format((float) ($grp["mtcomordht"] ?? 0), 2, ',', ' ');
								} ?>" class="form-control comordsel" style="width:100%;">
							</div>
						</div>
						<div class="form-inline" style="margin-top:0.5%;">
							<label for="com_sup" class="col-sm-7 col-form-label"
								style="padding-left:0; font-size:12px">Com sup (€/m3)</label>
							<div class="col-sm-5" style="padding:0">
								<input type="text" name="com_sup" value="<?php if (isset($_GET["id_grp"])) {
									echo number_format((float) ($grp["mtcomsupht"] ?? 0), 2, ',', ' ');
								} ?>" class="form-control comsupsel" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-2" style="padding:0">
						<div class="form-inline" style="margin-top:0.5%;">
							<label for="com_ord_ttc" class="col-sm-2 col-form-label" style="padding-left:0;">HT</label>
							<div class="col-sm-6" style="padding:0">
								<input type="text" name="com_ord_ttc" value="<?php if (isset($_GET["id_grp"])) {
									echo number_format((float) ($grp["mtcomord"] ?? 0), 2, ',', ' ');
								} ?>" class="form-control" style="width:100%;">
							</div>
						</div>
						<div class="form-inline" style="margin-top:0.5%;">
							<label for="com_sup_ttc" class="col-sm-2 col-form-label" style="padding-left:0;">HT</label>
							<div class="col-sm-6" style="padding:0">
								<input type="text" name="com_sup_ttc" value="<?php if (isset($_GET["id_grp"])) {
									echo number_format((float) ($grp["mtcomsup"] ?? 0), 2, ',', ' ');
								} ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-3" style="margin-left: -6%;">
						<div class="form-inline" style="margin-top:0.5%;">
							<label for="vol_ord" class="col-sm-6 col-form-label"
								style="padding-left:0; font-size:12px">TTC Vol Ord</label>
							<div class="col-sm-5" style="padding:0">
								<input type="text" name="vol_ord" value="<?php if (isset($grp["volord"])) {
									echo $grp["volord"];
								} else {
									echo "0";
								} ?>" class="form-control" style="width:100%;">
							</div>
						</div>
						<div class="form-inline" style="margin-top:0.5%;">
							<label for="vol_sup" class="col-sm-6 col-form-label"
								style="padding-left:0; font-size:12px">TTC Vol Sup</label>
							<div class="col-sm-5" style="padding:0">
								<input type="text" name="vol_sup" value="<?php if (isset($grp["volsup"])) {
									echo $grp["volsup"];
								} else {
									echo "0";
								} ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-3" style="margin-left: -4%;">
						<div class="form-inline" style="margin-top:0.5%;">
							<label for="mtant_ht" class="col-sm-3 col-form-label" style="padding-left:0;">Mtant</label>
							<div class="col-sm-6" style="padding:0">
								<input type="text" name="mtant_ht" value="<?php if (isset($grp["mtfactht"])) {
									echo $grp["mtfactht"];
								} else {
									echo "0,00";
								} ?>" class="form-control" style="width:100%;">
							</div>
						</div>
						<div class="form-inline" style="margin-top:0.5%;">
							<label for="mtant_ttc" class="col-sm-3 col-form-label" style="padding-left:0;">Mtant</label>
							<div class="col-sm-6" style="padding:0">
								<input type="text" name="mtant_ttc" value="<?php if (isset($grp["mtfact"])) {
									echo $grp["mtfact"];
								} else {
									echo "0,00";
								} ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-1" style="margin-left: -7%;">
						<label for="mtant_ht" class="col-form-label" style="padding-left:0;">HT</label>
						<label for="mtant_ttc" class="col-form-label" style="padding-left:0;">TTC</label>
					</div>
					<div class="col-sm-2 text-center">
						<label for="numfact" class="col-form-label" style="padding-left:0;">N° Facture</label>
						<input onchange="myFunction(this.value)" type="text" name="numfact" value="<?php if (isset($_GET["id_grp"])) {
							if (isset($_SESSION["facture_saisie"])) {
								echo $_SESSION["facture_saisie"];
							} else {
								echo $grp["numfact"];
							}
						} ?>" class="form-control" style="width:100%;">
					</div>
				</div>
			</div>
			<div class="col-sm-3" style="border-right: 1px solid #0b242436;">
				<label class="label-title" style="margin: 0;">Mise à jour des commandes du groupement</label>
				<div class="ligne"></div>
				<div class="form-inline">
					<label for="statut_cmd" class="col-sm-5 col-form-label" style="padding-left:0;">Statut
						commandes</label>
					<div class="col-sm-7" style="padding:0">
						<select class="form-control" name="statut_cmd" style="width:100%;font-size: 12px;">
							<option value="10" <?php if (isset($_POST["statut_cmd"])) {
								if ($_POST['statut_cmd'] == '10') {
									echo "selected='selected'";
								}
							} ?>>10 - Commande utilisateur</option>
							<option value="12" <?php if (isset($_POST["statut_cmd"])) {
								if ($_POST['statut_cmd'] == '12') {
									echo "selected='selected'";
								}
							} ?>>12 - Commande attachée</option>
							<option value="13" <?php if (isset($_POST["statut_cmd"])) {
								if ($_POST['statut_cmd'] == '13') {
									echo "selected='selected'";
								}
							} ?>>13 - Commande proposée</option>
							<option value="15" <?php if (isset($_POST["statut_cmd"])) {
								if ($_POST['statut_cmd'] == '15') {
									echo "selected='selected'";
								}
							} ?>>15 - Commande Groupée</option>
							<option value="17" <?php if (isset($_POST["statut_cmd"])) {
								if ($_POST['statut_cmd'] == '17') {
									echo "selected='selected'";
								}
							} ?>>17 - Prix proposé</option>
							<option value="20" <?php if (isset($_POST["statut_cmd"])) {
								if ($_POST['statut_cmd'] == '20') {
									echo "selected='selected'";
								}
							} ?>>20 - Prix validé</option>
							<option value="25" <?php if (isset($_POST["statut_cmd"])) {
								if ($_POST['statut_cmd'] == '25') {
									echo "selected='selected'";
								}
							} ?>>25 - Commande livrable</option>
							<option value="30" <?php if (isset($_POST["statut_cmd"])) {
								if ($_POST['statut_cmd'] == '30') {
									echo "selected='selected'";
								}
							} ?>>30 - Commande livrée</option>
							<option value="40" <?php if (isset($_POST["statut_cmd"])) {
								if ($_POST['statut_cmd'] == '40') {
									echo "selected='selected'";
								}
							} ?>>40 - Commande terminée</option>
							<option value="50" <?php if (isset($_POST["statut_cmd"])) {
								if ($_POST['statut_cmd'] == '50') {
									echo "selected='selected'";
								}
							} ?>>50 - Commande annulée</option>
							<option value="52" <?php if (isset($_POST["statut_cmd"])) {
								if ($_POST['statut_cmd'] == '52') {
									echo "selected='selected'";
								}
							} ?>>52 - Commande annulée/livraison</option>
							<option value="55" <?php if (isset($_POST["statut_cmd"])) {
								if ($_POST['statut_cmd'] == '55') {
									echo "selected='selected'";
								}
							} ?>>55 - Commande annulée/prix</option>
							<option value="99" <?php if (isset($_POST["statut_cmd"])) {
								if ($_POST['statut_cmd'] == '99') {
									echo "selected='selected'";
								}
							} ?>>99 - Commande annulée/compte désactivé</option>

						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-8">
						<div class="tableau" style="height: 200px;margin-top: 10px;">
							<table class="table">
								<thead>
									<tr>
										<th>Qté</th>
										<th>Prix O</th>
										<th>Prix S</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (isset($plages)) {
										$i = 0;
										while ($plage = mysqli_fetch_array($plages)) {
											$prix_ord = $plage["prix_ord"] / 1000;
											$prix_ord = number_format($prix_ord, 3, '.', '');
											$prix_sup = $plage["prix_sup"] / 1000;
											$prix_sup = number_format($prix_sup, 3, '.', '');
											if ($prix_ord == '0.000') {
												$prix_ord = '';
											}
											if ($prix_sup == '0.000') {
												$prix_sup = '';
											}

											?>
											<tr class="select ligne_plages">
												<input type="hidden" name="plage_id[]" value="<?= $plage["id"]; ?>">
												<td style="padding:0;"><input type="text"
														name="plage_volume_<?php print $i++; ?>" id="<?= $plage["id"]; ?>"
														class="form-control input_volume" value="<?= $plage["volume"]; ?> ">
												</td>
												<td style="padding:0;" class="select_prix_ord">
													<input type="hidden" name="plage_ord_id" class="plage_ord_id"
														value="<?= $plage["id"]; ?>">
													<input type="text" name="plage_prix_ord_<?= $plage["id"]; ?>"
														id="<?= $plage["id"]; ?>"
														class="form-control prix_ord plage_prix_ord_<?= $plage["id"]; ?>" value="<?php if (isset($plage["volume"])) {
															  echo $prix_ord;
														  } ?>">
												</td>
												<td style="padding:0;" class="select_prix_sup">
													<input type="hidden" name="plage_sup_id" class="plage_sup_id"
														value="<?= $plage["id"]; ?>">
													<input type="text" name="plage_prix_sup_<?= $plage["id"]; ?>"
														id="<?= $plage["id"]; ?>"
														class="form-control prix_sup plage_prix_sup_<?= $plage["id"]; ?>" value="<?php if (isset($plage["volume"])) {
															  echo $prix_sup;
														  } ?>">
												</td>
											</tr>
											<?php
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-sm-4 align-self-center">
						<div class="form-inline">
							<input type="hidden" name="id_select_plages_prix" class="id_select_plages_prix" value="">
							<input type="submit" name="supprimer_plages_prix" class="col-sm-5 btn btn-secondary"
								value="-" style="margin: 0 2% 0 0;">
							<input type="submit" name="ajouter_plages_prix" class="col-sm-5 btn btn-secondary" value="+"
								style="margin: 0 0 0 2%;">
						</div>
						<div class="text-center">
							<input type="hidden" name="nb_plages" value="<?php print $i++; ?>">
							<input type="hidden" name="nb_commande_plages" class="nb_commande_plages" value="">
							<input type="submit" name="appliquer_plages_prix" class="btn btn-secondary"
								value="APPLIQUER" style="margin: 6% 0 0 -9px">
						</div>
					</div>
					<div class="col-sm-12">
						<div class="form-inline">
							<label for="n_statut_cmd" class="col-form-label" style="padding-left:0;">* Statut à
								appliquer aux commandes</label>
							<select class="form-control col-sm-7" name="n_statut_cmd"
								style="width:100%; font-size:12px">
								<option value="10" <?php if (isset($_POST["n_statut_cmd"])) {
									if ($_POST['n_statut_cmd'] == '10') {
										echo "selected='selected'";
									}
								} ?>>10 - Commande
									utilisateur</option>
								<option value="12" <?php if (isset($_POST["n_statut_cmd"])) {
									if ($_POST['n_statut_cmd'] == '12') {
										echo "selected='selected'";
									}
								} ?>>12 - Commande
									attachée</option>
								<option value="13" <?php if (isset($_POST["n_statut_cmd"])) {
									if ($_POST['n_statut_cmd'] == '13') {
										echo "selected='selected'";
									}
								} ?>>13 - Commande
									proposée</option>
								<option value="15" <?php if (isset($_POST["n_statut_cmd"])) {
									if ($_POST['n_statut_cmd'] == '15') {
										echo "selected='selected'";
									}
								} ?>>15 - Commande
									Groupée</option>
								<option value="17" <?php if (isset($_POST["n_statut_cmd"])) {
									if ($_POST['n_statut_cmd'] == '17') {
										echo "selected='selected'";
									}
								} ?>>17 - Prix
									proposé</option>
								<option value="20" <?php if (isset($_POST["n_statut_cmd"])) {
									if ($_POST['n_statut_cmd'] == '20') {
										echo "selected='selected'";
									}
								} ?>>20 - Prix
									validé</option>
								<option value="25" <?php if (isset($_POST["n_statut_cmd"])) {
									if ($_POST['n_statut_cmd'] == '25') {
										echo "selected='selected'";
									}
								} ?>>25 - Commande
									livrable</option>
								<option value="30" <?php if (isset($_POST["n_statut_cmd"])) {
									if ($_POST['n_statut_cmd'] == '30') {
										echo "selected='selected'";
									}
								} ?>>30 - Commande
									livrée</option>
								<option value="40" <?php if (isset($_POST["n_statut_cmd"])) {
									if ($_POST['n_statut_cmd'] == '40') {
										echo "selected='selected'";
									}
								} ?>>40 - Commande
									terminée</option>
								<option value="50" <?php if (isset($_POST["n_statut_cmd"])) {
									if ($_POST['n_statut_cmd'] == '50') {
										echo "selected='selected'";
									}
								} ?>>50 - Commande
									annulée</option>
								<option value="52" <?php if (isset($_POST["n_statut_cmd"])) {
									if ($_POST['n_statut_cmd'] == '52') {
										echo "selected='selected'";
									}
								} ?>>52 - Commande
									annulée/livraison</option>
								<option value="55" <?php if (isset($_POST["n_statut_cmd"])) {
									if ($_POST['n_statut_cmd'] == '55') {
										echo "selected='selected'";
									}
								} ?>>55 - Commande
									annulée/prix</option>
								<option value="99" <?php if (isset($_POST["n_statut_cmd"])) {
									if ($_POST['n_statut_cmd'] == '99') {
										echo "selected='selected'";
									}
								} ?>>99 - Commande
									annulée/compte désactivé</option>
							</select>
							<input type="submit" name="applique_n_statut" class="col-sm-4 btn btn-secondary"
								value="APPLIQUER" style="margin-left: 6%;">
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-2 text-center">
				<button type="button" class="btn btn-secondary" name="button" style="width:100%">GOOGLE EARTH</button>
				<a href="details_groupement.php?id_grp=<?= $id_grp ?>&definir_zone=oui" class="btn btn-secondary"
					style="width:100%; margin-top:2%;">DÉFINIR ZONE</a>
				<?php include 'form/modal_definir_zone.php'; ?>
				<input type="submit" name="charger_client" class="btn btn-secondary" value="CHARGER CLIENT"
					style="width:100%; margin-top:2%;">

				<?php
				if (isset($_GET["id_grp"])) {
					?>
					<button type="button" name="button" class="btn btn-secondary" style="width:100%; margin-top:2%;"
						data-bs-target="#commandesAffecter" data-bs-toggle="modal">COMMANDES A AFFECTER</button>
					<?php include 'form/modal_commandes_a_affecter.php'; ?>

					<?php
				}
				?>
				<div class="form-inline justify-content-center" style="margin-top:4%">
					<label for="resp_grpt" class="col-sm-2 col-form-label" style="padding-left:0;">Resp</label>
					<div class="col-sm-5" style="padding:0">
						<select class="form-control" name="resp_grpt">
							<?php
							if (isset($_GET["id_grp"])) {
								?>
								<option value="" <?php if (isset($grp["responsable"]) && $grp["responsable"] == '')
									echo "selected"; ?>></option>
								<option value="STE" <?php if (isset($grp["responsable"]) && $grp["responsable"] == 'STE')
									echo "selected"; ?>>STE</option>
								<option value="MAG" <?php if (isset($grp["responsable"]) && $grp["responsable"] == 'MAG')
									echo "selected"; ?>>MAG</option>

								<?php
							} else {
								?>
								<option value=""></option>
								<option value="STE">STE</option>
								<option value="MAG">MAG</option>
								<?php
							}
							?>

						</select>
					</div>
				</div>
				<?php
				if (isset($utilisateurs["total"]) && $utilisateurs["total"] > 0) {
					echo '<label for="n_statut_cmd" class="col-form-label" style="padding-left:0;margin-top: 40px;color: #ef8351;">Attention Cde Utilisateur</label>';
				}
				?>
			</div>
		</div>
		<hr class="separe" style="margin:1% 0 1%;">
		<div class="row">
			<div class="col-sm-10">
				<label class="label-title" for="trie_table_cmd" style="margin: 0;">Liste des commandes</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-10">
						<div class="tableau" style="margin: 1% 0;height: 535px;">
							<table class="table" id="trie_table_cmd" style="table-layout: fixed;">
								<thead>
									<tr style="white-space: nowrap;">
										<th><i class="fal fa-sort"></i></th>
										<th style="width: 150px;padding-left: 0;">Nom</th>
										<th style="width:50px;padding-left: 0;">Prénom</th>
										<th style="width:60px;">Adresse</th>
										<th class="text-center" style="width:35px;">CP</th>
										<th style="width:70px;">Ville</th>
										<th class="text-center" style="width:40px;">Qté</th>
										<th class="text-center" style="width:40px;">Livré</th>
										<th class="text-center" style="width:40px;">Prix O</th>
										<th class="text-center" style="width:40px;">Prix S</th>
										<th class="text-center" style="width:30px;">Fuel</th>
										<th class="text-center" style="width:90px;">Statut</th>
										<th class="text-center" style="width:40px;">PMP</th>
										<th class="text-center" style="width:40px;">AF</th>
										<th class="text-center" style="width:40px;">FMC</th>
										<th class="text-center" style="width:40px;">FR</th>
										<th class="text-center" style="width:40px;">FM</th>
										<th class="text-center" style="width:130px;">Mail</th>
										<th class="text-center" style="width:230px;">Commentaire Fournisseur</th>
										<th class="text-center" style="width:120px;">Statut Client</th>
										<th class="text-center" style="width:130px;">Tel 1</th>
										<th class="text-center" style="width:130px;">Tel 2</th>
										<th class="text-center" style="width:60px;">Date</th>

									</tr>
								</thead>
								<tbody>
									<?php
									$i = 0;
									if (isset($cmdes)) {
										while ($cmd = mysqli_fetch_array($cmdes)) {
											if ($cmd["cmd_typefuel"] == 1) {
												$type = 'O';
											}
											if ($cmd["cmd_typefuel"] == 2) {
												$type = 'S';
											}
											if ($cmd["cmd_typefuel"] == 3) {
												$type = 'GNR';
											}

											if ($cmd["cmd_status"] == 0 || is_null($cmd["cmd_status"])) {
												$status = " 0 - Pas de commande";
											}
											if ($cmd["cmd_status"] == 10) {
												$status = " 10 - Utilisateur";
											}
											if ($cmd["cmd_status"] == 12) {
												$status = " 12 - Attaché";
											}
											if ($cmd["cmd_status"] == 13) {
												$status = " 13 - Proposé";
											}
											if ($cmd["cmd_status"] == 15) {
												$status = " 15 - Groupée";
											}
											if ($cmd["cmd_status"] == 17) {
												$status = " 17 - P. Proposé";
											}
											if ($cmd["cmd_status"] == 20) {
												$status = " 20 - Validé";
											}
											if ($cmd["cmd_status"] == 25) {
												$status = " 25 - Livrable";
											}
											if ($cmd["cmd_status"] == 30) {
												$status = " 30 - Livré";
											}
											if ($cmd["cmd_status"] == 40) {
												$status = " 40 - Terminée";
											}
											if ($cmd["cmd_status"] == 50) {
												$status = " 50 - Annulée";
											}
											if ($cmd["cmd_status"] == 52) {
												$status = " 52 - Annulée/livraison";
											}
											if ($cmd["cmd_status"] == 55) {
												$status = " 55 - Annulée/prix";
											}
											if ($cmd["cmd_status"] == 99) {
												$status = " 99 - Annulée/compte désactivé";
											}

											$prix_sup = $cmd["cmd_prix_sup"] / 1000;
											$prix_sup = number_format($prix_sup, 3, '.', '');

											$prix_ord = $cmd["cmd_prix_ord"] / 1000;
											$prix_ord = number_format($prix_ord, 3, '.', '');
											?>
											<tr class="select commande">
												<input type="hidden" name="id_cmde_<?php print $i++; ?>"
													value="<?= $cmd["id_cmd"]; ?>">
												<td></td>
												<td style="padding: 0% 0.75rem 0 0!important;"><?= $cmd["name"]; ?></td>
												<td style="padding: 0% 0.75rem 0 0!important;"><?= $cmd["prenom"]; ?></td>
												<td style="padding: 0% 0.75rem;"><?= $cmd["adresse"]; ?></td>
												<td style="padding: 0% 0.75rem;" class="text-center"><?= $cmd["code_postal"]; ?>
												</td>
												<td style="padding: 0% 0.75rem;"><?= $cmd["ville"]; ?></td>
												<td style="padding: 0% 0.75rem;"
													class="text-center qte_<?= $cmd["cmd_status"]; ?>"><?= $cmd["cmd_qte"]; ?>
												</td>
												<td style="padding: 0% 0.75rem;" class="text-center"> <input type="text"
														name="qte_livree_<?= $cmd['id_cmd']; ?>" class="form-control"
														value="<?= $cmd["cmd_qtelivre"]; ?>"
														style="width: 80px;text-align:center"> </td>
												<td style="padding: 0% 0.75rem;" class="text-center"><?= $prix_ord; ?></td>
												<td style="padding: 0% 0.75rem;" class="text-center"><?= $prix_sup; ?></td>
												<td style="padding: 0% 0.75rem;" class="text-center"><?= $type; ?></td>
												<td style="padding: 0% 0.75rem;"
													class="text-center statut_<?= $cmd["cmd_status"]; ?>"><?= $status; ?></td>
												<td style="padding: 0% 0.75rem;" class="text-center"><?= $cmd["cmd_prixpmp"]; ?>
												</td>
												<td style="padding: 0% 0.75rem;" class="text-center"><?= $cmd["cmd_prixaf"]; ?>
												</td>
												<td style="padding: 0% 0.75rem;" class="text-center"><?= $cmd["cmd_prixfmc"]; ?>
												</td>
												<td style="padding: 0% 0.75rem;" class="text-center"><?= $cmd["cmd_prixfr"]; ?>
												</td>
												<td style="padding: 0% 0.75rem;" class="text-center"><?= $cmd["cmd_prixfm"]; ?>
												</td>
												<td style="padding: 0% 0.75rem;" class="text-center"><?= $cmd["email"]; ?></td>
												<td style="padding: 0% 0.75rem;"><?= $cmd["cmd_commentfour"]; ?></td>
												<td style="padding: 0% 0.75rem;"></td>
												<td style="padding: 0% 0.75rem;" class="text-center"><?= $cmd["tel_fixe"]; ?>
												</td>
												<td style="padding: 0% 0.75rem;" class="text-center"><?= $cmd["tel_port"]; ?>
												</td>
												<td style="padding: 0% 0.75rem;" class="text-center">
													<?= date_format(new DateTime($cmd["cmd_dt"]), 'd/m/Y'); ?>
												</td>
											</tr>
											<?php
										}
									}
									?>
								</tbody>
								<input type="hidden" name="nb_commande" class="nb_commande" value="<?php print $i; ?>">
								<input type="hidden" name="nb_commande_i" class="nb_commande"
									value="<?php print $i; ?>">
							</table>
						</div>
					</div>
					<div class="col-sm-2 text-center align-self-center" style="border-right: 1px solid #0b242436;">
						<label for="cacher_annules" class="col-form-label" style="padding-bottom: 0;text-align:left">
							<input type="checkbox" name="cacher_annules" id="cacher_annules" class="switch value check"
								<?= (!isset($_POST['cacher_annules']) && isset($_POST['update_grpt'])) ? '' : 'checked="checked"'; ?>>Cacher annulées
						</label>
						<label for="cacher_propose" class="col-form-label" style="padding-bottom: 0;text-align:left">
							<input type="checkbox" name="cacher_propose" id="cacher_propose" class="switch value check"
								<?= (!isset($_POST['cacher_propose']) && isset($_POST['update_grpt'])) ? '' : 'checked="checked"'; ?>>Cacher proposées
						</label>

						<a href="plus_commande_groupement.php?id_grp=<?= $id_grp; ?>" class="btn btn-secondary"
							style="width:90%; margin-top:4%;">+ COMMANDE</a>
						<a href="details_groupement.php?id_grp=<?= $id_grp; ?>&utilisateur=oui"
							class="btn btn-secondary" style="width:90%; margin-top:4%;">+ UTILISATEUR</a>
						<?php include 'form/modal_recherche_client.php'; ?>

						<button type="button" data-bs-target="#supprimerCmdes" data-bs-toggle="modal"
							class="btn btn-secondary" style="width:90%; margin-top:4%;">- SUPPRIMER</button>
						<button type="button" data-bs-target="#supprimerCmdesTout" data-bs-toggle="modal"
							class="btn btn-secondary" style="width:90%; margin-top:4%;">- SUPPRIMER TOUT</button>

						<label for="ajout_n_client" class="col-form-label" style="padding-left:0;">Ajout N°
							Client</label>
						<input type="text" name="ajout_n_client" class="form-control" value="">

						<input type="submit" name="recup_prix" class="btn btn-secondary" value="RECUP PRIX"
							style="width:90%; margin-top:4%;">
						<button type="button" data-bs-target="#exportXls" data-bs-toggle="modal"
							class="btn btn-secondary" style="width:90%; margin-top:4%;">EXPORT XLS</button>
						<?php include 'form/modal_export_xls.php'; ?>

						<a href="details_groupement.php?id_grp=<?= $id_grp; ?>&generer_texte=oui"
							class="btn btn-secondary" style="width:90%; margin-top:4%;">GENERER TEXTE</a>
						<?php include 'form/modal_generer_texte.php'; ?>

						<a href="details_groupement.php?id_grp=<?= $id_grp; ?>&popup_sms=oui" class="btn btn-secondary"
							style="width:90%; margin-top:4%;">ENVOYER SMS</a>
						<?php include 'form/modal_envoyer_sms.php'; ?>

						<a href="details_groupement.php?id_grp=<?= $id_grp; ?>&popup_pmp=oui" class="btn btn-secondary"
							style="width:90%; margin-top:4%;">MAIL PMP</a>
						<?php include 'form/modal_envoyer_mail.php'; ?>

						<button type="button" data-bs-target="#cmdesTermine" data-bs-toggle="modal"
							class="btn btn-warning" style="width:90%; margin-top:4%;">CMD TERMINÉES</button>
						<div class="modal fade" id="cmdesTermine" tabindex="-1" aria-labelledby="exampleModalLabel"
							aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Terminer les commandes du
											groupement</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-label="Close"><i class="fas fa-times"></i></button>
									</div>
									<div class="modal-body text-left">
										Etes vous sur de vouloir passer les commandes livrables et livrées à terminées ?
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary"
											data-bs-dismiss="modal">Non</button>
										<input type="submit" name="commandes_termines" class="btn btn-primary"
											value="Oui">
									</div>
								</div>
							</div>
						</div>

						<button type="button" data-bs-target="#validerQte" data-bs-toggle="modal"
							class="btn btn-primary" style="width:90%; margin-top:4%;">VALIDER TABLEAU</button>
						<div class="modal fade" id="validerQte" tabindex="-1" aria-labelledby="exampleModalLabel"
							aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Valider les Quantitées</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-label="Close"><i class="fas fa-times"></i></button>
									</div>
									<div class="modal-body text-left">
										Enregistrer la quantité commandée dans la quantité livrée pour les commandes non
										modifiées ?
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary"
											data-bs-dismiss="modal">Fermer</button>
										<input type="submit" name="valider_tableau_qte_0" class="btn btn-warning"
											value="Non">
										<input type="submit" name="valider_tableau" class="btn btn-primary" value="Oui">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-2">
				<label class="label-title" style="margin: 0;">Nombre de litre total</label>
				<div class="ligne"></div>
				<div class="form-inline">
					<label for="nb_total_cde" class="col-form-label col-sm-6" style="padding-left:0;">Nb Total Cde
						:</label>
					<div class="col-sm-6">
						<span style="font-size: 14px;">(<?php if (isset($num_cmdes)) {
							echo $num_cmdes;
						} else {
							echo "0";
						} ?>
							/ <?php if (isset($num_cmdes_actif)) {
								echo $num_cmdes_actif;
							} else {
								echo "0";
							} ?>)</span>
						<br><span style="font-size: 14px;">Total / Actif</span>

					</div>
				</div>

				<div class="form-inline">
					<label for="utilisateur" class="col-form-label col-sm-5" style="padding-left:0;">Utilisateur
						:</label>
					<div class="col-sm-2 text-right">
						<span style="font-size: 13px;    padding-left: 0;">(<?php if (isset($_GET["id_grp"])) {
							echo $utilisateurs["total"];
						} ?>)</span>
					</div>
					<div class="col-sm-5" style="padding-right: 0;">
						<input type="text" class="form-control" name="qte_utilisateur" id="qte_utilisateur"
							style="font-size: 12px; width: 100%;" value="<?php if (isset($_GET["id_grp"])) {
								echo $utilisateurs["sum_qte"];
							} ?>" style="width:100%;">
					</div>
				</div>
				<div class="form-inline">
					<label for="attachee" class="col-form-label col-sm-5" style="padding-left:0;">Attachées :</label>
					<div class="col-sm-2 text-right">
						<span style="font-size: 13px;    padding-left: 0;">(<?php if (isset($_GET["id_grp"])) {
							echo $attachees["total"];
						} ?>)</span>
					</div>
					<div class="col-sm-5" style="padding-right: 0;">
						<input type="text" class="form-control" name="attachee" style="font-size: 12px; width: 100%;"
							value="<?php if (isset($_GET["id_grp"])) {
								echo $attachees["sum_qte"];
							} ?>" style="width:100%;">
					</div>
				</div>
				<div class="form-inline">
					<label for="attachee" class="col-form-label col-sm-5" style="padding-left:0;">Proposées :</label>
					<div class="col-sm-2 text-right">
						<span style="font-size: 13px;    padding-left: 0;">(<?php if (isset($_GET["id_grp"])) {
							echo $propose["total"];
						} ?>)</span>
					</div>
					<div class="col-sm-5" style="padding-right: 0;">
						<input type="text" class="form-control" name="attachee" style="font-size: 12px; width: 100%;"
							value="<?php if (isset($_GET["id_grp"])) {
								echo $propose["sum_qte"];
							} ?>" style="width:100%;">
					</div>
				</div>
				<div class="form-inline">
					<label for="groupees" class="col-form-label col-sm-5" style="padding-left:0;">Groupées :</label>
					<div class="col-sm-2 text-right">
						<span style="font-size: 13px;    padding-left: 0;">(<?php if (isset($_GET["id_grp"])) {
							echo $groupees["total"];
						} ?>)</span>
					</div>
					<div class="col-sm-5" style="padding-right: 0;">
						<input type="text" class="form-control" name="groupees" style="font-size: 12px; width: 100%;"
							value="<?php if (isset($_GET["id_grp"])) {
								echo $groupees["sum_qte"];
							} ?>" style="width:100%;">
					</div>
				</div>
				<div class="form-inline">
					<label for="p_propose" class="col-form-label col-sm-5" style="padding-left:0;">P. Proposé :</label>
					<div class="col-sm-2 text-right">
						<span style="font-size: 13px;    padding-left: 0;">(<?php if (isset($_GET["id_grp"])) {
							echo $ppropose["total"];
						} ?>)</span>
					</div>
					<div class="col-sm-5" style="padding-right: 0;">
						<input type="text" class="form-control" name="p_propose" style="font-size: 12px; width: 100%;"
							value="<?php if (isset($_GET["id_grp"])) {
								echo $ppropose["sum_qte"];
							} ?>" style="width:100%;">
					</div>
				</div>
				<div class="form-inline">
					<label for="p_valide" class="col-form-label col-sm-5" style="padding-left:0;">P. Validé :</label>
					<div class="col-sm-2 text-right">
						<span style="font-size: 13px;    padding-left: 0;">(<?php if (isset($_GET["id_grp"])) {
							echo $pvalide["total"];
						} ?>)</span>
					</div>
					<div class="col-sm-5" style="padding-right: 0;">
						<input type="text" class="form-control" name="p_valide" style="font-size: 12px; width: 100%;"
							value="<?php if (isset($_GET["id_grp"])) {
								echo $pvalide["sum_qte"];
							} ?>" style="width:100%;">
					</div>
				</div>
				<div class="form-inline">
					<label for="livrable" class="col-form-label col-sm-5" style="padding-left:0;">Livrable :</label>
					<div class="col-sm-2 text-right">
						<span style="font-size: 13px;    padding-left: 0;">(<?php if (isset($_GET["id_grp"])) {
							echo $livrable["total"];
						} ?>)</span>
					</div>
					<div class="col-sm-5" style="padding-right: 0;">
						<input type="text" class="form-control" name="livrable" style="font-size: 12px; width: 100%;"
							value="<?php if (isset($_GET["id_grp"])) {
								echo $livrable["sum_qte"];
							} ?>" style="width:100%;">
					</div>
				</div>
				<div class="form-inline">
					<label for="livrees" class="col-form-label col-sm-5" style="padding-left:0;">Livrées :</label>
					<div class="col-sm-2 text-right">
						<span style="font-size: 13px;    padding-left: 0;">(<?php if (isset($_GET["id_grp"])) {
							echo $livrees["total"];
						} ?>)</span>
					</div>
					<div class="col-sm-5" style="padding-right: 0;">
						<input type="text" class="form-control" name="livrees" style="font-size: 12px; width: 100%;"
							value="<?php if (isset($_GET["id_grp"])) {
								echo $livrees["sum_qte"];
							} ?>" style="width:100%;">
					</div>
				</div>
				<div class="form-inline">
					<label for="termines" class="col-form-label col-sm-5" style="padding-left:0;">Terminées :</label>
					<div class="col-sm-2 text-right">
						<span style="font-size: 13px;    padding-left: 0;">(<?php if (isset($_GET["id_grp"])) {
							echo $terminees["total"];
						} ?>)</span>
					</div>
					<div class="col-sm-5" style="padding-right: 0;">
						<input type="text" class="form-control" name="termines" style="font-size: 12px; width: 100%;"
							value="<?php if (isset($_GET["id_grp"])) {
								echo $terminees["sum_qte"];
							} ?>" style="width:100%;">
					</div>
				</div>
				<div class="form-inline">
					<label for="annulees" class="col-form-label col-sm-5" style="padding-left:0;">Annulees :</label>
					<div class="col-sm-2 text-right">
						<span style="font-size: 13px;    padding-left: 0;">(<?php if (isset($_GET["id_grp"])) {
							echo $annulees["total"];
						} ?>)</span>
					</div>
					<div class="col-sm-5" style="padding-right: 0;">
						<input type="text" class="form-control" name="annulees" style="font-size: 12px; width: 100%;"
							value="<?php if (isset($_GET["id_grp"])) {
								echo $annulees["sum_qte"];
							} ?>" style="width:100%;">
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-5" style="border-right: 1px solid #0b242436;">
				<label class="label-title" style="margin: 0;">Information déroulement groupement</label>
				<div class="ligne"></div>
				<div class="form-inline">
					<label for="inscription" class="col-sm-3 col-form-label" style="padding-left:0;">Inscription</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="inscription" class="form-control" style="width:100%;" value="<?php if (isset($planning["0"])) {
							echo $planning["0"];
						} ?>">
						<input type="hidden" name="inscription_hidden" value="<?php if (isset($planning["0"])) {
							echo $planning["0"];
						} ?>">
					</div>
				</div>
				<div class="form-inline">
					<label for="annonce_prix" class="col-sm-3 col-form-label" style="padding-left:0;">Annonce
						prix</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="annonce_prix" class="form-control" style="width:100%;" value="<?php if (isset($planning["1"])) {
							echo $planning["1"];
						} ?>">
						<input type="hidden" name="annonce_prix_hidden" class="form-control" style="width:100%;" value="<?php if (isset($planning["1"])) {
							echo $planning["1"];
						} ?>">
					</div>
				</div>
				<div class="form-inline">
					<label for="validation" class="col-sm-3 col-form-label" style="padding-left:0;">Validation</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="validation" class="form-control" style="width:100%;" value="<?php if (isset($planning["2"])) {
							echo $planning["2"];
						} ?>">
						<input type="hidden" name="validation_hidden" class="form-control" style="width:100%;" value="<?php if (isset($planning["2"])) {
							echo $planning["2"];
						} ?>">
					</div>
				</div>
				<div class="form-inline">
					<label for="livraison" class="col-sm-3 col-form-label" style="padding-left:0;">Livraison</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="livraison" class="form-control" style="width:100%;" value="<?php if (isset($planning["3"])) {
							echo $planning["3"];
						} ?>">
						<input type="hidden" name="livraison_hidden" class="form-control" style="width:100%;" value="<?php if (isset($planning["3"])) {
							echo $planning["3"];
						} ?>">
					</div>
				</div>
				<div class="form-inline">
					<label for="prochain_grp" class="col-sm-3 col-form-label" style="padding-left:0;">Prochain
						Grp</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="prochain_grp" class="form-control" style="width:100%;" value="<?php if (isset($planning["4"])) {
							echo $planning["4"];
						} ?>">
						<input type="hidden" name="prochain_grp_hidden" class="form-control" style="width:100%;" value="<?php if (isset($planning["4"])) {
							echo $planning["4"];
						} ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2" style="border-right: 1px solid #0b242436;">
				<label class="label-title" style="margin: 0;">Ordre de tri</label>
				<div class="ligne"></div>
				<select class="form-control" name="ordre_tri_1" style="margin-top: 4%;">
					<option value="00" <?php if (isset($arr[0])) {
						if ($arr[0] == "00") {
							echo "selected='selected'";
						}
					} ?>></option>
					<option value="01" <?php if (isset($arr[0])) {
						if ($arr[0] == "01") {
							echo "selected='selected'";
						}
					} ?>>Nom</option>
					<option value="02" <?php if (isset($arr[0])) {
						if ($arr[0] == "02") {
							echo "selected='selected'";
						}
					} ?>>Code postal</option>
					<option value="03" <?php if (isset($arr[0])) {
						if ($arr[0] == "03") {
							echo "selected='selected'";
						}
					} ?>>Ville</option>
					<option value="04" <?php if (isset($arr[0])) {
						if ($arr[0] == "04") {
							echo "selected='selected'";
						}
					} ?>>Statut</option>
					<option value="05" <?php if (isset($arr[0])) {
						if ($arr[0] == "05") {
							echo "selected='selected'";
						}
					} ?>>Quantité</option>
					<option value="06" <?php if (isset($arr[0])) {
						if ($arr[0] == "06") {
							echo "selected='selected'";
						}
					} ?>>Mail</option>
					<option value="07" <?php if (isset($arr[0])) {
						if ($arr[0] == "07") {
							echo "selected='selected'";
						}
					} ?>>Prix FM</option>
					<option value="08" <?php if (isset($arr[0])) {
						if ($arr[0] == "08") {
							echo "selected='selected'";
						}
					} ?>>Prix FR</option>
				</select>
				<select class="form-control" name="ordre_tri_2" style="margin-top: 4%;">
					<option value="00" <?php if (isset($arr[1])) {
						if ($arr[1] == "00") {
							echo "selected='selected'";
						}
					} ?>></option>
					<option value="01" <?php if (isset($arr[1])) {
						if ($arr[1] == "01") {
							echo "selected='selected'";
						}
					} ?>>Nom</option>
					<option value="02" <?php if (isset($arr[1])) {
						if ($arr[1] == "02") {
							echo "selected='selected'";
						}
					} ?>>Code postal</option>
					<option value="03" <?php if (isset($arr[1])) {
						if ($arr[1] == "03") {
							echo "selected='selected'";
						}
					} ?>>Ville</option>
					<option value="04" <?php if (isset($arr[1])) {
						if ($arr[1] == "04") {
							echo "selected='selected'";
						}
					} ?>>Statut</option>
					<option value="05" <?php if (isset($arr[1])) {
						if ($arr[1] == "05") {
							echo "selected='selected'";
						}
					} ?>>Quantité</option>
					<option value="06" <?php if (isset($arr[1])) {
						if ($arr[1] == "06") {
							echo "selected='selected'";
						}
					} ?>>Mail</option>
					<option value="07" <?php if (isset($arr[1])) {
						if ($arr[1] == "07") {
							echo "selected='selected'";
						}
					} ?>>Prix FM</option>
					<option value="08" <?php if (isset($arr[1])) {
						if ($arr[1] == "08") {
							echo "selected='selected'";
						}
					} ?>>Prix FR</option>
				</select>
				<!-- <select class="form-control" name="ordre_tri_3" style="margin-top: 4%;">
					<option value="0"></option>
					<option value="1">Nom</option>
					<option value="2">Code postal</option>
					<option value="3">Ville</option>
					<option value="4">Statut</option>
					<option value="5">Quantité</option>
					<option value="6">Mail</option>
					<option value="7">Prix FM</option>
					<option value="8">Prix FR</option>
				</select> -->
				<div class="text-center">
					<input type="submit" name="ordre_tri" class="btn btn-secondary" value="TRIER"
						style="width:50%; margin-top:4%;">
				</div>
			</div>
			<div class="col-sm-5">
				<label class="label-title" style="margin: 0;">Information fournisseur</label>
				<div class="ligne"></div>
				<textarea name="info_four" class="form-control info_four" rows="2" style="height:50%; margin-top:1%;">
<?= htmlspecialchars(trim(preg_replace('/\s+/', ' ', str_replace(["\\r", "\\n", "\r", "\n"], ' ', strip_tags($grp["infofour"] ?? ''))))) ?>
</textarea>
				<div class="form-inline" style="margin-top:4%;">
					<div class="col-sm-3 text-center">
						<button style="width: 140px;" type="button" name="button" data-bs-toggle="modal"
							class="btn btn-warning" data-bs-target="#supprimerGroupe"><i
								class="fa-regular fa-trash-can"></i> SUPPRIMER</button>
					</div>
					<?php
					if (isset($_GET["id_grp"])) {
						?>
						<div class="modal fade" id="supprimerGroupe" tabindex="-1" aria-labelledby="exampleModalLabel"
							aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Supprimer ce groupement ?</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
											<i class="fas fa-times"></i> </button>
									</div>
									<div class="modal-body">
										<?= $grp["libelle"]; ?>
									</div>
									<div class="modal-footer">
										<input type="hidden" name="id" value="<?= $_GET["id_grp"] ?>">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
										<input type="submit" name="supp_groupe" class="btn btn-primary" value="Supprimer">
									</div>
								</div>
							</div>
						</div>
						<?php
					}
					?>
					<div class="col-sm-3 text-center">
						<a href="details_groupement.php?id_grp=<?= $id_grp; ?>&historique=oui" class="btn btn-secondary"
							style="width:140px;">HISTORIQUE</a>
						<?php include 'form/modal_historique.php'; ?>
					</div>
					<div class="col-sm-3 text-center">
						<input type="submit" name="update_grpt" class="btn btn-primary" value="METTRE À JOUR"
							style="width: 140px;">
					</div>
					<div class="col-sm-3 text-center">
						<input type="submit" name="update_grpt_sortie" class="btn btn-primary" value="OK / SORTIE"
							style="width: 140px;">
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="supprimerCmdes" tabindex="-1" aria-labelledby="exampleModalLabel"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Voulez-vous vraiment supprimer cette commande du
							groupement ?</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i
								class="fas fa-times"></i> </button>
					</div>
					<div class="modal-body">
						<label class="col-form-label cmdes_supp" style="padding-left:0;"></label>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="id_cmdes_supp" id="id_cmdes_supp" value="">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
						<input type="submit" name="supp_cmdes_grps" class="btn btn-primary" value="Supprimer">
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="supprimerCmdesTout" tabindex="-1" aria-labelledby="exampleModalLabel"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Voulez-vous vraiment supprimer toutes les
							commandes du groupement ?</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i
								class="fas fa-times"></i> </button>
					</div>
					<div class="modal-body">
						<label class="col-form-label cmdes_supp" style="padding-left:0;"></label>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
						<input type="submit" name="supprimer_tout" class="btn btn-primary" value="Supprimer">
					</div>
				</div>
			</div>
		</div>



		<?php
		if (isset($_GET["id_grp"])) {
			?>
			<div class="modal fade" id="selCom" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" style="max-width: 85%;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Sélectionner une commande</h5>
							<button type="button" class="btn-close b-close" data-bs-dismiss="modal" aria-label="Close"> <i
									class="fas fa-times"></i> </button>
						</div>
						<div class="modal-body">
							<?php
							if (isset($_GET["n_id_cmd"])) {
								$id = $_GET["n_id_cmd"];
								$id_grp = $_GET["id_grp"];

								$query = "    SELECT *
											FROM pmp_commande
											WHERE id = '$id' ";
								$res = my_query($co_pmp, $query);
								$cmd = mysqli_fetch_array($res);

								if ($cmd["groupe_cmd"] != 0) {
									?>
									<p>La commande sélectionnée est déjà dans le groupement <?= $cmd['groupe_cmd']; ?> et a le
										statut <?= $cmd['cmd_status']; ?>, êtes-vous sûr de vouloir la basculer sur ce groupement ?
									</p>
									<?php
								} else {
									ajouterCommandegroupement($co_pmp, $id, $id_grp);
								}
							} else {
								include 'form/form_liste_commande.php';
							}
							?>
						</div>
						<div class="modal-footer">
							<input type="hidden" name="n_id_cmd" id="n_id_cmd" value="">
							<button type="button" class="btn btn-secondary b-close" data-bs-dismiss="modal"
								aria-label="Close"> FERMER </button>
							<?php
							if (isset($id_ko) && strlen($id_ko) > 0) {
								?>
								<input type="submit" name="basculer_commande_groupement" class="btn btn-primary"
									value="BASCULER">
								<?php
							} elseif (isset($_GET["n_id_cmd"])) {
								?>
								<input type="submit" name="basculer_commande" class="btn btn-primary" value="BASCULER">
								<?php
							} else {
								?>
								<button type="button" class="btn btn-primary valider_cmd" name="valider_cmd">VALIDER</button>

								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>

			<?php

		}
		?>
		<!-- contrôle sur la présence de commande dans un groupement avant de l'annuler -->
		<?php include 'form/modal_verification_commande.php'; ?>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>

<script src="/admin/js/datatables.min.js" type="text/javascript"></script>
<script src="/admin/js/date-eu.js" type="text/javascript"></script>
<script src="/admin/js/script_regroupements.js" type="text/javascript" charset="utf-8"></script>
<!-- <script src="/admin/js/script_fournisseurs.js" charset="utf-8"></script> -->
<script>
	const idGrp = <?php echo intval($_GET['id_grp'] ?? 0); ?>;
	window.chargerClientIdGrp = idGrp; // utilisé par le script global
	if (typeof window.dismissChargerClientNotification === 'function') {
		window.dismissChargerClientNotification(idGrp);
	}
	if (typeof window.getChargerClientJobStatus === 'function' && typeof window.clearChargerClientJobState === 'function') {
		const st = window.getChargerClientJobStatus();
		if (st && st.status === 'done' && st.groupId === idGrp) {
			window.clearChargerClientJobState();
		}
	}

	function myFunction(val) {
		console.log("Entered Value is: " + val);
		var frm = document.getElementById("FormID");

		frm.submit();
	}

	$(document).ready(function () {
		$('select[name="fournisseur_ajax"]').change(function () {
			if ($(this).val()) {
				four_id = $(this).val();
				$.ajax({
					method: 'POST',
					url: 'inc/pmp_ajax_definir_zone.php',
					dataType: 'html',
					data: {
						four_id: four_id, //valeur de la checkbox cliqué
					},
					success: function (reponse) {

						$(".code").empty();
						$('.code').append('<option value="">' + reponse + '</option>');
					},
				});
			}
		});

		$('.input_volume').on('input', function (e) {
			if ($(this).val()) {
				var volume = $(this).val();
				var id = $(this).attr('id');
				$.ajax({
					method: 'POST',
					url: 'inc/pmp_ajax_definir_plages_prix.php',
					dataType: 'html',
					data: {
						id: id,
						volume: volume,
					},
				});
			}

		});

		$('.prix_ord').change('input', function (e) {
			if ($(this).val()) {
				var prix_ord = $(this).val();
				var id = $(this).attr('id');
				$.ajax({
					method: 'POST',
					url: 'inc/pmp_ajax_definir_plages_prix.php',
					dataType: 'html',
					data: {
						id: id,
						prix_ord: prix_ord,
					},
					success: function (reponse) {
						reponse = reponse / 1000;

						var res = reponse.toFixed(3);
						$(".plage_prix_ord_" + id).val("");
						$(".plage_prix_ord_" + id).val(res);
					},
				});

			}

		});

		$('.prix_sup').change('input', function (e) {
			if ($(this).val()) {
				var prix_sup = $(this).val();
				var id = $(this).attr('id');
				$.ajax({
					method: 'POST',
					url: 'inc/pmp_ajax_definir_plages_prix.php',
					dataType: 'html',
					data: {
						id: id,
						prix_sup: prix_sup,
					},
					success: function (reponse) {
						reponse = reponse / 1000;

						var res = reponse.toFixed(3);
						$(".plage_prix_sup_" + id).val("");
						$(".plage_prix_sup_" + id).val(res);
					},
				});

			}

		});
	});

		// Bouton "CHARGER CLIENT" -> job global (toast persistent + reprise)
	document.addEventListener('DOMContentLoaded', () => {
		const btn = document.querySelector('input[name="charger_client"]');
		if (btn) {
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				if (typeof window.startChargerClientJob === 'function') {
					window.startChargerClientJob();
				} else {
					console.error('startChargerClientJob manquant');
				}
			});
		}
	});
</script>

