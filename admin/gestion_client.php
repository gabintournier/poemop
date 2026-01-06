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
session_start();
if (!isset($_SESSION['user'])) {
	header('Location: connexion.php');
	die();
}

$title = "Gestion d'un client";
$title_page = "Gestion d'un client";
$return = true;
ob_start();

include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_groupements.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_clients.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_commandes.php";

unset($_SESSION['facture_saisie']);

if (!empty($_POST["valide_update_client"]) || !empty($_POST["quitter_page"])) {
	$url = $_POST["new_url"];
	header('Location: ' . $url);
}

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
unset($_SESSION['facture_saisie']);
if (isset($_GET["user_id"]) && empty($_POST["supp_client"])) {
	if ($_GET["user_id"] != '') {
		$client = getInfosClient($co_pmp, $_GET["user_id"]);
		$res_histo = getHistoClient($co_pmp, $_GET["user_id"]);
		$pmp_compte = ChargeMonCompte($co_pmp, $_GET["user_id"]);
		$pmp_electricite = ChargeCompteElectricite($co_pmp, $_GET["user_id"]);
		$pmp_gaz = ChargeCompteGaz($co_pmp, $_GET["user_id"]);
		$pmp_commande = GetCommandeClient($co_pmp, $_GET["user_id"]);


		$date_insc = $client["registerDate"];
		$date_insc = date('Y-m-d', strtotime($date_insc));

		$date_co = $client["lastvisitDate"];
		$date_co = date('Y-m-d', strtotime($date_co));

		$date_blocage = $client["date_blocage"];
		$date_blocage = date('Y-m-d', strtotime($date_blocage));

		$date_j = date('Y-m-d');
		$res_four = getFournisseurSecteur($co_pmp, $client["code_postal"]);

		// Si le compte est désactivé → tous les champs deviennent non éditables
		$isDisabled = isset($client['disabled_account']) && $client['disabled_account'] == '1';
		$readonlyAttr = $isDisabled ? 'readonly disabled style="background-color:#f8f9fa;cursor:not-allowed;"' : '';
	}

	if (isset($_GET["id_cmd"])) {
		$cmd_details = getCommandeDetailsClients($co_pmp, $_GET["id_cmd"]);
	}


}

if (!empty($_POST["ajouter_client"])) {
	if (isset($pmp_commande["id"])) {
		if ($pmp_commande["cmd_status"] >= 30) {
			if ($pmp_commande["groupe_cmd"] > 0) {

			}
		}

	}
}

if (isset($_GET["return"])) {
	if ($_GET["return"] == 'cmdes') {
		$link = 'liste_commandes.php';
	} elseif ($_GET["return"] == 'avis') {
		$link = 'avis_clients.php?get=avis';
	} elseif ($_GET["return"] == 'grpt') {
		$link = 'details_groupement.php?id_grp=' . $cmd_details["groupe_cmd"];
	} elseif ($_GET["return"] == 'recherche') {
		$link = 'recherche_client.php';
	}
} else {
	$link = 'recherche_client.php';
}


if (isset($_POST['reactiver_compteADM']) && !empty($_POST['user_id'])) {
	$user_id = intval($_POST['user_id']);
	if (reactiverCompte($co_pmp, $user_id)) {
		$message_type = "success";
		$message_titre = "Succès";
		$message = "Le compte a bien été réactivé.";

		header("Location: " . $_SERVER['REQUEST_URI']);
		exit;
	} else {
		$message_type = "no";
		$message_titre = "Erreur";
		$message = "Une erreur est survenue lors de la réactivation.";

		header("Location: " . $_SERVER['REQUEST_URI']);
		exit;
	}
}


if (isset($message) && !isset($message_modal)) {
	?>
	<div class="toast <?= $message_type; ?>">
		<div class="message-icon <?= $message_type; ?>-icon">
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
				<a href="#" class="active">Client</a>
				<a href="gestion_client_commande.php?id_cmd=<?= $_GET["id_cmd"]; ?>&return=cmdes">Commande</a>
				<a href="ancienne_commande.php?user_id=<?= $_GET["user_id"]; ?>&id_cmd=<?= $_GET["id_cmd"]; ?>&return=cmdes">Anciennes
					commandes</a>
			</div>
			<?php
		} elseif ($_GET["return"] == 'grpt') {
			?>
			<div class="menu-bloc">
				<a href="#" class="active">Client</a>
				<a href="gestion_client_commande.php?id_cmd=<?= $_GET["id_cmd"]; ?>&return=grpt">Commande</a>
				<a href="ancienne_commande.php?user_id=<?= $_GET["user_id"]; ?>&id_cmd=<?= $_GET["id_cmd"]; ?>&return=grpt">Anciennes
					commandes</a>
			</div>
			<?php
		} elseif ($_GET["return"] == 'avis') {
			?>
			<div class="menu-bloc">
				<a href="avis_clients.php"><i class="fas fa-arrow-left" style="font-size: 14px;"></i></a>
				<a href="#" class="active">Client</a>
				<a href="gestion_client_commande.php?id_cmd=<?= $_GET["id_cmd"]; ?>&return=avis">Commande</a>
				<a href="ancienne_commande.php?user_id=<?= $_GET["user_id"]; ?>&id_cmd=<?= $_GET["id_cmd"]; ?>&return=avis">Anciennes
					commandes</a>
			</div>
			<?php
		}
	} else {
		?>
		<?php if (!empty($client['disabled_account']) && $client['disabled_account'] == 1):
			// Calcul de la date d’effacement (3 ans après désactivation)
			$delete_date = '';
			if (!empty($client['disabled_date'])) {
				$date_obj = new DateTime($client['disabled_date']);
				$date_obj->modify('+3 years');
				$delete_date = $date_obj->format('d/m/Y');
			}
			?>
			<div class="row">
				<div class="col-sm-12">
					<div class="alert alert-danger compte-desactive shadow-sm" role="alert"
						style="position: relative; overflow: hidden;">
						<div style="font-weight: 600; font-size: 1.1rem;">
							⚠️ Ce compte est désactivé.
						</div>
						<div style="margin-top: 8px;">
							Sans action, les données de ce compte seront effacées le
							<strong><?= $delete_date; ?></strong>.
						</div>
						<form method="post" style="display:inline;">
							<input type="hidden" name="user_id" value="<?= htmlspecialchars($client['user_id']); ?>">
							<button type="submit" name="reactiver_compteADM" class="btn-reactiver-compte">
								🔓 Réactiver ce compte
							</button>
						</form>
					</div>
				</div>
			</div>
		<?php endif; ?>


		<br>

		<div class="menu-bloc">
			<?php
			if (isset($_GET["return"])) {
				if ($_GET["return"] == 'accueil') {
					?>
					<a href="index.php"><i class="fas fa-arrow-left" style="font-size: 14px;"></i></a>

					<?php
				}
			} else {
				?>
				<a href="clients_nouveaux_inscrits.php"><i class="fas fa-arrow-left" style="font-size: 14px;"></i></a>
				<?php
			}
			?>
			<a href="#" class="active">Client</a>
			<?php
			if (isset($pmp_commande["id"])) {
				?>
				<a href="gestion_client_commande.php?id_cmd=<?= $pmp_commande["id"] ?>&return=recherche">Commande</a>
				<?php
			} else {
				?>
				<a href="" style="color: #0b242469;cursor: default;">Commande</a>
				<?php
			}
			?>

			<a href="ancienne_commande.php?user_id=<?= $_GET["user_id"]; ?>&return=recherche">Anciennes commandes</a>

		</div>
		<?php
	}
	?>
	<form method="post">
		<input type="hidden" class="new_url" name="new_url" value="">
		<div class="row">
			<div class="col-sm-5">
				<label for="client_traite" class="col-form-label">
					<input type="checkbox" name="client_traite" id="client_traite" class="switch value check" <?php if (isset($client['traite'])) {
						if ($client['traite'] == '1') {
							echo "checked='checked'";
						}
					} ?>>
					Client Traité
				</label>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-8">
				<label for="bloquemail" class="col-form-label d-flex align-items-center">
					<input type="checkbox" name="bloquemail" id="bloquemail" class="switch value check"
						<?= (isset($client['bloquemail']) && $client['bloquemail'] == '0') ? "checked='checked'" : "" ?>
						disabled style="margin-right: 10px; cursor: not-allowed !important;">
					<span>
						<?php
						// Si le compte est désactivé
						if (!empty($client['disabled_account']) && $client['disabled_account'] == 1) {
							echo "<strong>Compte désactivé</strong>";
						}
						// Sinon, affiche le statut de réception des mails
						elseif (isset($client['bloquemail']) && $client['bloquemail'] == '0') {
							echo "Reçoit les notifications de groupements";
						} else {
							echo "<strong>Ne reçoit pas les notifications de groupements</strong>";
							if (isset($date_blocage) && $date_blocage > $date_j) {
								echo " (jusqu’au " . date('d/m/Y', strtotime($date_blocage)) . ")";
							}
						}
						?>
					</span>
				</label>
			</div>
		</div>

		<?php
		// On n'affiche la raison / commentaire que si bloquemail = 1 (ne reçoit pas les notifications)
		if (isset($client['bloquemail']) && $client['bloquemail'] == '1') {

			// Tableau pour traduire les valeurs en texte lisible
			$raisons = [
				'trop_mail' => "Je reçois trop d’emails",
				'plus_concerne' => "Je ne suis plus concerné",
				'prix' => "Les prix ne sont pas assez avantageux",
				'commande' => "Je n'ai pas besoin de commander pour l'instant",
				'autre' => "Autre raison"
			];

			// Valeurs récupérées depuis la BDD
			$raison_val = $client['raison_desinscription'] ?? '';
			$raison_label = $raisons[$raison_val] ?? '';
			$commentaire = $client['commentaire_desinscription'] ?? '';
			?>

			<div class="row">
				<?php if ($raison_label): ?>
					<div class="col-sm-6">
						<label for="raison_desinscription" class="col-form-label">Raison de désinscription aux notifications de
							groupements</label>
						<input type="text" id="raison_desinscription" class="form-control"
							value="<?= htmlspecialchars($raison_label) ?>" readonly>
					</div>
				<?php endif; ?>

				<?php if ($commentaire): ?>
					<div class="col-sm-6">
						<label for="commentaire_desinscription" class="col-form-label">Commentaire de désinscription aux
							notifications de groupements</label>
						<input type="text" id="raison_desinscription" class="form-control"
							value="<?= htmlspecialchars(string: $commentaire) ?>" readonly>
					</div>
				<?php endif; ?>
			</div>
		<?php } ?>


		<div class="row">
			<div class="col-sm-2 align-self-end" style="max-width: 12%;">
				<div class="form-inline">
					<label for="code_client" class="col-sm-4 col-form-label" style="padding-left:0;">Code</label>
					<div class="col-sm-8" style="padding:0">
						<input type="text" name="code_client" class="form-control" style="width:100%;" value="<?php if (isset($_GET["user_id"])) {
							echo $_GET["user_id"];
						} ?>" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-2 align-self-end">
				<div class="form-inline">
					<label for="nom" class="col-sm-3 col-form-label" style="padding-left:0;">Nom</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="nom" class="form-control" style="width:100%;" value="<?php if (isset($client["name"])) {
							echo $client["name"];
						} ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2 align-self-end">
				<div class="form-inline">
					<label for="prenom" class="col-sm-4 col-form-label" style="padding-left:0;">Prénom</label>
					<div class="col-sm-8" style="padding:0">
						<input type="text" name="prenom" class="form-control" style="width:100%;" value="<?php if (isset($client["prenom"])) {
							echo $client["prenom"];
						} ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2 align-self-end">
				<div class="form-inline">
					<label for="internet" class="col-sm-4 col-form-label" style="padding-left:0;">Internet</label>
					<div class="col-sm-8" style="padding:0">
						<input type="text" name="internet" class="form-control" style="width:100%;" value="<?php if (isset($client["username"])) {
							echo $client["username"];
						} ?>" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-2 align-self-end" style="max-width: 12%;">
				<select class="form-control" name="statut_client" style="width:100%;font-size: 12px;">
					<option value="0" <?php if (isset($client["actif"])) {
						if ($client['actif'] == '0') {
							echo "selected='selected'";
						}
					} ?>>0 - Inactif</option>
					<option value="1" <?php if (isset($client["actif"])) {
						if ($client['actif'] == '1') {
							echo "selected='selected'";
						}
					} ?>>1 - A relancer</option>
					<option value="2" <?php if (isset($client["actif"])) {
						if ($client['actif'] == '2') {
							echo "selected='selected'";
						}
					} ?>>2 - Actif</option>
					<option value="3" <?php if (isset($client["actif"])) {
						if ($client['actif'] == '3') {
							echo "selected='selected'";
						}
					} ?>>3 - Actif Site</option>
					<option value="4" <?php if (isset($client["actif"])) {
						if ($client['actif'] == '4') {
							echo "selected='selected'";
						}
					} ?>>4 - Ancien actif</option>
					<option value="5" <?php if (isset($client["actif"])) {
						if ($client['actif'] == '5') {
							echo "selected='selected'";
						}
					} ?>>5 - Inactif relancé</option>
				</select>
			</div>
			<div class="col-sm-2" style="max-width: 13%;border-left: 1px solid #0b242436;">
				<label for="date_insc" class="col-form-label" style="padding-left:0;">Date inscription</label>
				<input type="date" name="date_insc" class="form-control" style="width:100%;" value="<?php if (isset($date_insc)) {
					echo $date_insc;
				} ?>" disabled="disabled">
			</div>
			<div class="col-sm-2" style="max-width: 13%;">
				<label for="date_co" class="col-form-label" style="padding-left:0;">Date dernière co</label>
				<input type="date" name="date_co" class="form-control" style="width:100%;" value="<?php if (isset($date_co)) {
					echo $date_co;
				} ?>" disabled="disabled">
			</div>
		</div>
		<div class="row" style="margin-top: 0.5%;">
			<div class="col-sm-4 align-self-end">
				<div class="form-inline">
					<label for="adresse" class="col-sm-2 col-form-label" style="padding-left:0;">Adresse</label>
					<div class="col-sm-10" style="padding:0">
						<input type="text" name="adresse" class="form-control" style="width:100%;" value="<?php if (isset($client["adresse"])) {
							echo $client["adresse"];
						} ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2 align-self-end">
				<div class="form-inline">
					<label for="cp" class="col-sm-6 col-form-label" style="padding-left:0;">Code Postal</label>
					<div class="col-sm-6" style="padding:0">
						<input type="text" name="cp" class="form-control" style="width:100%;" value="<?php if (isset($client["code_postal"])) {
							echo $client["code_postal"];
						} ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-3 align-self-end">
				<div class="form-inline">
					<label for="ville" class="col-sm-2 col-form-label" style="padding-left:0;">Ville</label>
					<div class="col-sm-10" style="padding:0">
						<select class="form-control ville" name="ville" style="width:100%;font-size: 12px;">
							<?php
							// Si aucune ville n'est renseignée ou si le cp_id vaut 0, on affiche une option vide par défaut
							if (empty($client["ville"]) || (isset($client["cp_id"]) && $client["cp_id"] == 0)) {
								?>
								<option value="" selected="selected"></option>
								<?php
							}

							// Liste des villes correspondant au code postal
							$villes = getVilleCP($co_pmp, $client["code_postal"]);
							while ($ville = mysqli_fetch_array($villes)) {
								?>
								<option value="<?= $ville["id"]; ?>" <?php
								  // ✅ Ne sélectionne la ville que si elle est effectivement renseignée côté client
							  	if (!empty($client["ville"]) && $ville["id"] == $client["cp_id"]) {
									  echo 'selected="selected"';
								  }
								  ?>>
									<?= htmlspecialchars($ville["ville"]); ?>
								</option>
								<?php
							}
							?>
						</select>
					</div>
				</div>
			</div>

			<div class="col-sm-3" style="padding-left: 0;">
				<button type="button" name="button" class="btn btn-secondary" data-bs-toggle="modal"
					data-bs-target="#ModMotdepasse" style="width:100%">MODIFIER MOT DE PASSE</button>
				<div class="modal fade" id="ModMotdepasse" tabindex="-1" aria-labelledby="exampleModalLabel"
					aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Modifier le mot de passe</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i
										class="fa-solid fa-xmark"></i> </button>
							</div>
							<div class="modal-body">

								<div class="form-inline">
									<label for="long" class="col-sm-5 col-form-label" style="padding-left:0;">Mot de
										passe</label>
									<div class="col-sm-7" style="padding:0">
										<input type="password" name="password_user" class="form-control"
											style="width:100%;" value="">
									</div>
								</div>
								<div class="form-inline">
									<label for="long" class="col-sm-5 col-form-label" style="padding-left:0;">Confirmer
										mot de passe</label>
									<div class="col-sm-7" style="padding:0">
										<input type="password" name="confirm_password" class="form-control"
											style="width:100%;" value="">
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">

									</div>
									<div class="col-sm-6">

									</div>
								</div>
							</div>
							<div class="modal-footer">
								<input type="hidden" name="user_id" value="<?php print $client['user_id']; ?>">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
								<input type="submit" name="mod_mdp" class="btn btn-primary" value="Modifier">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-2 align-self-end">
				<div class="form-inline">
					<label for="tel_1" class="col-sm-3 col-form-label" style="padding-left:0;">Tel 1</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="tel_1" class="form-control" style="width:100%;" value="<?php if (isset($client["tel_fixe"])) {
							echo $client["tel_fixe"];
						} ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2 align-self-end">
				<div class="form-inline">
					<label for="tel_2" class="col-sm-3 col-form-label" style="padding-left:0;">Tel 2</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="tel_2" class="form-control" style="width:100%;" value="<?php if (isset($client["tel_port"])) {
							echo $client["tel_port"];
						} ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2 align-self-end">
				<div class="form-inline">
					<label for="tel_3" class="col-sm-3 col-form-label" style="padding-left:0;">Tel 3</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="tel_3" class="form-control" style="width:108%;" value="<?php if (isset($client["tel_3"])) {
							echo $client["tel_3"];
						} ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="margin-top: -2.4%;">
			<div class="col-sm-3 align-self-end">
				<div class="form-inline">
					<label for="mail" class="col-sm-2 col-form-label" style="padding-left:0;">Email</label>
					<div class="col-sm-10" style="padding:0">
						<input type="mail" name="mail" class="form-control" style="width:100%;" value="<?php if (isset($client["joomla_email"])) {
							echo $client["joomla_email"];
						} ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-3 align-self-end">
				<div class="form-inline">
					<label for="ville_h" class="col-sm-4 col-form-label" style="padding-left:0;max-width: 28%;">Ville
						(Histo)</label>
					<div class="col-sm-8" style="padding:0">
						<input type="text" name="ville_h" class="form-control" style="width:100%;" value="<?php if (isset($client["ville"])) {
							echo $client["ville"];
						} ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<label for="coord" class="col-form-label" style="padding-left:0;">Coordonnées géographique</label>
				<div class="form-inline">
					<label for="lat" class="col-sm-2 col-form-label" style="padding-left:0;">Lat</label>
					<div class="col-sm-10" style="padding:0">
						<input type="text" name="lat" class="form-control" style="width:100%;" value="<?php if (isset($client["lat"])) {
							echo $client["lat"];
						} ?>" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-2 align-self-end">
				<div class="form-inline">
					<label for="long" class="col-sm-3 col-form-label" style="padding-left:0;">Long</label>
					<div class="col-sm-9" style="padding:0">
						<input type="text" name="long" class="form-control" style="width:100%;" value="<?php if (isset($client["lng"])) {
							echo $client["lng"];
						} ?>" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-2 align-self-end text-right">
				<?php
				if (empty($_POST["supp_client"]) && isset($client["password"])) {
					if ($client["password"] == '') {
						?>
						<input class="btn btn-secondary" type="submit" name="envoyer_mail_activation" value="MAIL ACTIVATION"
							style="width:72%;margin-bottom:4%;">

						<?php
					}
				}
				?>
				<button type="button" name="button" data-bs-toggle="modal"
					class="btn btn-warning <?= ($client['bloquemail'] == '1' || $client['disabled_account'] == '1') ? 'disabled' : '' ?>"
					data-bs-target="#InscriptionClient"
					style="width:72%; <?= ($client['bloquemail'] == '1' || $client['disabled_account'] == '1') ? 'opacity:.2;cursor:not-allowed;pointer-events:none;' : '' ?>"
					<?= ($client['bloquemail'] == '1' || $client['disabled_account'] == '1') ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
					INSCRIPTIONS
				</button>

			</div>
		</div>
		<label for="com_permanant" class="col-form-label">Commentaire destiné au fournisseur (travaux)</label>
		<textarea class="form-control" name="com_permanant" rows="8" cols="80"><?php if (isset($client["com_op"])) {
			echo $client["com_op"];
		} ?></textarea>

		<hr>

		<div class="row">
			<div class="col-sm-6">
				<label for="four_com" class="col-form-label" style="padding-left:0;">Fournisseur actuel ou
					commentaires</label>
				<textarea name="four_com" rows="3" class="form-control" style="width: 100%;height: auto;" value="<?php if (isset($client["com_user"])) {
					echo $client["com_user"];
				} ?>"><?php if (isset($client["com_user"])) {
					 echo $client["com_user"];
				 } ?></textarea>
			</div>
			<div class="col-sm-6">
				<label for="four_def" class="col-form-label" style="padding-left:0;">Fournisseur définit sur
					secteur</label>
				<textarea name="four_def" rows="3" class="form-control" style="width: 100%;height: auto;" value=""
					disabled="disabled">
<?php
if (isset($res_four)) {
	while ($four = mysqli_fetch_array($res_four)) {
		echo $four["nom"] . " - " . $four["libelle"] . "\n";
	}
}
?>
				</textarea>
			</div>
		</div>
		<div class="row" style="margin-top: 0.5%;">
			<div class="col-sm-4">
				<label for="cm_crm" class="col-form-label" style="padding-left:0;">Commentaire CRM (préciser date +
					trigramme)</label>
			</div>
		</div>
		<textarea name="cm_crm" rows="3" class="form-control" style="width: 100%;height: auto;" value="<?php if (isset($client["com_crm"])) {
			echo $client["com_crm"];
		} ?>"><?php if (isset($client["com_crm"])) {
			 echo $client["com_crm"];
		 } ?></textarea>
		<hr>
		<div class="tableau" style="height: 230px;">
			<table class="table">
				<thead>
					<tr>
						<th></th>
						<th>Date</th>
						<th>Qui</th>
						<th>Action</th>
						<th>Valeur</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (isset($res_histo)) {
						while ($histo = mysqli_fetch_array($res_histo)) {
							// Vérifie si l'action contient "mot de passe" ou "mdp"
							$valeur_affichee = $histo["hisu_valeur"];
							if (preg_match('/mot de passe|mdp/i', $histo["hisu_action"])) {
								$valeur_affichee = '[Masqué pour des raisons de confidentialité]';
							}
							?>
							<tr>
								<td><i class="fas fa-arrow-right"></i></td>
								<td><?= $histo["hisu_date"]; ?></td>
								<td><?= $histo["hisu_intervenant"]; ?></td>
								<td><?= $histo["hisu_action"]; ?></td>
								<td><?= $valeur_affichee; ?></td>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>
		</div>
		<div class="row">
			<div class="col-sm-3">
				<button style="width:212px" type="button" name="button" data-bs-toggle="modal" class="btn btn-warning"
					data-bs-target="#supprimerClient"><i class="fa-regular fa-trash-can" style="margin-right: 5%;"></i>
					SUPPRIMER</button>
			</div>

			<div class="col-sm-3 text-center">
				<a href="<?= $actual_link . "&popup=oui" ?>"
					class="btn btn-secondary <?= ($client['disabled_account'] == '1') ? 'disabled' : '' ?>"
					<?= ($client['disabled_account'] == '1') ? 'aria-disabled="true" tabindex="-1" style="pointer-events:none;opacity:.2;cursor:not-allowed;"' : '' ?>>
					AJOUTER À UN GRPT
				</a>
			</div>

			<div class="col-sm-3 text-right">
				<?php
				if (isset($_GET["user_id"])) {
					?>
					<input type="submit" name="update_client" value="ENREGISTRER"
						class="btn btn-primary <?= ($client['disabled_account'] == '1') ? 'disabled' : '' ?>"
						style="min-width:40%; margin-top:0.5%; <?= ($client['disabled_account'] == '1') ? 'opacity:.2;cursor:not-allowed;pointer-events:none;' : '' ?>"
						<?= ($client['disabled_account'] == '1') ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
					<?php
				} else {
					?>
					<input type="submit" name="nouveau_client" value="ENREGISTRER" class="btn btn-primary"
						style="min-width:40%; margin-top:0.5%;">
					<?php
				}
				?>
			</div>

		</div>
		<?php
		if (isset($_GET["user_id"])) {
			?>
			<div class="modal fade" id="supprimerClient" tabindex="-1" aria-labelledby="exampleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Supprimer ce client ?</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i
									class="fa-solid fa-xmark"></i> </button>
						</div>
						<div class="modal-body">
							<?php
							if (isset($client["nom"]) && isset($client["prenom"])) {
								echo $client["nom"] . " " . $client["prenom"];
							}
							?>
						</div>
						<div class="modal-footer">
							<input type="hidden" name="user_id" value="<?php if (isset($client["user_id"])) {
								echo $client['user_id'];
							} ?>">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
							<input type="submit" name="supp_client" class="btn btn-primary" value="Supprimer">
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="InscriptionClient" tabindex="-1" aria-labelledby="exampleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" style="max-width: 50%;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Inscriptions du client</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i
									class="fa-solid fa-xmark"></i> </button>
						</div>
						<div class="modal-body">
							<?php include 'form/form_inscriptions_client.php'; ?>
						</div>
						<div class="modal-footer">
							<input type="hidden" name="user_id" value="<?php if (isset($client["user_id"])) {
								echo $client['user_id'];
							} ?>">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="AjouterGrpt" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Ajouter le client à un groupement</h5>
							<button type="button" class="btn-close fermer-modal" data-bs-dismiss="modal" aria-label="Close">
								<i class="fa-solid fa-xmark"></i> </button>
						</div>
						<div class="modal-body">
							<?php
							if (isset($message_modal)) {
								?>
								<div class="toast <?= $message_type; ?>" style="margin: 0 0 1%">
									<div class="message-icon <?= $message_type; ?>-icon">
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
							if (!empty($_POST["ajouter_client"])) {
								if (isset($pmp_commande["id"])) {
									if ($pmp_commande["cmd_status"] < 30) {
										if ($pmp_commande["groupe_cmd"] > 0) {
											?>
											<p>La commande de <?= $client['name'] . " " . $client['prenom']; ?> est déjà dans le groupement
												<?= $pmp_commande['groupe_cmd']; ?> et a le statut <?= $pmp_commande['cmd_status']; ?>,
												êtes-vous sûr de vouloir la basculer sur ce groupement ?
											</p>
											<div class="modal-footer">
												<input type="hidden" name="id_grp2" id="id_grp2" value="<?= $_POST["id_grp"]; ?>">
												<button type="button" class="btn btn-secondary fermer-modal"
													data-bs-dismiss="modal">Non</button>
												<input type="submit" name="ajouter_client_grp" class="btn btn-primary" value="Valider">
											</div>
											<?php
										} else {
											CreationCommandeAvecGroupement($co_pmp, $_POST["id_grp"]);
											?>
											<div class="toast success" style="margin: 0 0 1%">
												<div class="message-icon success-icon">
													<i class="fas fa-check"></i>
												</div>
												<div class="message-content ">
													<div class="message-type">
														Succès
													</div>
													<div class="message">
														Le client a été ajouté au groupement <?= $_POST["id_grp"] ?>
													</div>
												</div>
												<div class="message-close">
													<i class="fas fa-times"></i>
												</div>
											</div>
											<?php
											include 'form/form_liste_groupements.php';
										}
									} elseif ($pmp_commande["cmd_status"] >= 30) {
										CreationCommandeAvecGroupement($co_pmp, $_POST["id_grp"]);
										?>
										<div class="toast success" style="margin: 0 0 1%">
											<div class="message-icon success-icon">
												<i class="fas fa-check"></i>
											</div>
											<div class="message-content ">
												<div class="message-type">
													Succès
												</div>
												<div class="message">
													Le client a été ajouté au groupement <?= $_POST["id_grp"] ?>
												</div>
											</div>
											<div class="message-close">
												<i class="fas fa-times"></i>
											</div>
										</div>
										<?php
										include 'form/form_liste_groupements.php';
									}

								} else {
									CreationCommandeAvecGroupement($co_pmp, $_POST["id_grp"]);
									?>
									<div class="toast success" style="margin: 0 0 1%">
										<div class="message-icon success-icon">
											<i class="fas fa-check"></i>
										</div>
										<div class="message-content ">
											<div class="message-type">
												Succès
											</div>
											<div class="message">
												Le client a été ajouté au groupement <?= $_POST["id_grp"] ?>
											</div>
										</div>
										<div class="message-close">
											<i class="fas fa-times"></i>
										</div>
									</div>
									<?php
									include 'form/form_liste_groupements.php';
								}
							} else {
								include 'form/form_liste_groupements.php';
								?>
								<div class="modal-footer">
									<input type="hidden" name="id_grp" id="id_grp" value="">
									<input type="submit" name="ajouter_client" class="btn btn-primary" value="VALIDER">
								</div>

								<?php
							}
							?>


						</div>

					</div>
				</div>
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
							<input type="submit" class="btn btn-primary valider_form" name="valide_update_client"
								value="Oui / Sortie">
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="InfoElec" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" style="max-width: 60%;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Info Client</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i
									class="fa-solid fa-xmark"></i> </button>
						</div>
						<div class="modal-body">
							<?php include 'info_elec.php'; ?>
						</div>
						<div class="modal-footer">

							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
							<input type="submit" name="compte_fioul" class="btn btn-primary" value="Compte Fioul">
						</div>
					</div>
				</div>
			</div>

			<?php
		}
		?>
		<?php if ($isDisabled): ?>
			<script>
				document.addEventListener('DOMContentLoaded', () => {
					document.querySelectorAll('input, textarea, select').forEach(el => {
						// On garde les boutons intacts
						if (!['button', 'submit', 'hidden'].includes(el.type)) {
							el.disabled = true;
							el.style.backgroundColor = '#f8f9fa';
							el.style.cursor = 'not-allowed';
						}
					});
				});
			</script>
		<?php endif; ?>

	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="js/script_clients.js" charset="utf-8"></script>
<?php
if (isset($_GET["popup"])) {
	?>
	<?php
	$host = $_SERVER['HTTP_HOST'];
	$isDev = str_contains($host, 'dev') || str_contains($host, 'localhost');

	$baseUrl = $isDev
		? "https://dev.plus-on-est-moins-on-paie.fr"
		: "https://plus-on-est-moins-on-paie.fr";
	?>

	<script type="text/javascript">
		$(document).ready(function () {
			let popup = '<?php echo isset($_GET["popup"]) ? $_GET["popup"] : ""; ?>';

			if (popup === 'oui') {
				$('#AjouterGrpt').modal('show');

				$(".fermer-modal").click(function () {
					var params = new URLSearchParams(window.location.search);
					params.delete('popup');
					var newloc = params.toString();
					window.location.href = "<?= $baseUrl ?>/admin/gestion_client.php?" + newloc;
				});
			}
		});
	</script>

	<?php
}
?>
<script type="text/javascript">
	$(document).ready(function () {
		function myfun() {
			console.log('ok');
		}

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

		$('input[name="cp"]').blur(function () {
			if ($(this).val()) {
				code_postal = $(this).val();
				console.log(code_postal);
				$.ajax({
					method: 'POST',
					url: 'inc/pmp_ajax_code_postal.php',
					dataType: 'html',
					data: {
						code_postal: code_postal, //valeur de la checkbox cliqué
					},
					success: function (reponse) {
						$(".ville").empty();
						$('.ville').append('<option value="">' + reponse + '</option>');
					},
				});
			}
		});
	});
</script>
<?php
if (isset($_GET["user_id"])) {
	?>
	<script type="text/javascript">

		$(".check-energie").click(function () {
			var id = $(this).val();

			if ($(this).prop("checked") == true) {
				$.ajax({
					method: 'POST',
					url: 'inc/pmp_ajax_mes_inscriptions.php',
					data: {
						produit: id, //valeur de la checkbox cliqué
						inscrit: 1,
						user_id: <?php echo json_encode($_GET["user_id"]); ?> // Le user_id est récupéré au début dans les entetes de chaque fichier
					}
				})
					.done(function (data) {
						$('.abo_' + id).fadeOut(); // On enlève le text 'Non abonné' de la checbox sélectionné grace a la 'value' de la checkbox
						$('.infos_' + id).delay(500).fadeIn(); // Puis on affiche le bouton
					});
			}
			else if (jQuery(this).prop("checked") == false) {
				$.ajax({
					method: 'POST',
					url: 'inc/pmp_ajax_mes_inscriptions.php',
					data: {
						produit: id,
						inscrit: 0,
						user_id: <?php echo json_encode($_GET["user_id"]); ?>
					}
				})
					.done(function (data) {
						$('#energie_' + id).prop('checked', false);
						$('.abo_' + id).delay(500).fadeIn();
						$('.infos_' + id).fadeOut();
						$('#myModal').modal('hide');
						// $('.modal-backdrop').fadeOut();
					})
			}
		});

		document.addEventListener('DOMContentLoaded', () => {
			const toast = document.querySelector('.toast.success');
			if (toast) {
				setTimeout(() => {
					toast.classList.add('auto-hide-success');
				}, 4000);
			}
		});
	</script>
	<?php
}
?>