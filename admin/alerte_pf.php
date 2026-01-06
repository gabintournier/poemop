<?php
session_start();
if (!isset($_SESSION['user'])) {
	header('Location: /admin/connexion.php');
	die();
}
error_reporting(E_ALL);
ini_set("display_errors", 1);
$title = 'Alerte Prix Fioul';
$title_page = 'Alerte Prix Fioul';
ob_start();




include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/../inc/pf_co_connect.php";
include_once __DIR__ . "/inc/pf_inc_fonctions_mail_pf.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
unset($_SESSION['facture_saisie']);
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$res_mail_pf = getMailModelePF($co_pf);
$total = getTotalClientsPF($co_pf);

if (isset($_POST["mail_modele"])) {
	$_SESSION["mail_modele"] = $_POST["mail_modele"];
	$res_mot = getMotsClesPF($co_pf, $_SESSION["mail_modele"]);
} elseif (isset($_SESSION["mail_modele"])) {
	$res_mot = getMotsClesPF($co_pf, $_SESSION["mail_modele"]);
}


if (!empty($_POST["chercher_client"])) {
	$_SESSION["email_client"] = $_POST["email_client"];
	$_SESSION["code_postal_client"] = $_POST["code_postal_client"];
	$_SESSION["date_min_insc_client"] = $_POST["date_min_insc_client"];
	$_SESSION["date_max_insc_client"] = $_POST["date_max_insc_client"];
	$res = getClientsFiltresPF($co_pf);
	$num_mail = mysqli_num_rows($res);

	if (!empty($_POST["exporter_recherche_client"])) {
		exporterListeClientsPF($co_pf, $res);
		$res = getClientsFiltresPF($co_pf);
		$num_mail = mysqli_num_rows($res);
	}

	if (!empty($_POST["envoyer_mail"])) {
		InsererMailAutoPF($co_pf, $res);
		$res = getClientsFiltresPF($co_pf);
		$num_mail = mysqli_num_rows($res);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Les mails ont bien été envoyés.";
	}
} elseif (!empty($_POST["vider_recherche"])) {
	unset($_SESSION['mail_modele']);
	unset($_SESSION['email_client']);
	unset($_SESSION['code_postal_client']);
	unset($_SESSION['date_min_insc_client']);
	unset($_SESSION['date_max_insc_client']);
	unset($_SESSION['fournisseur_recherche']);
	unset($_SESSION['zone_recherche']);
	header('Location: ' . $actual_link);
} elseif (!empty($_POST["chercher_client_fournisseur"])) {
	$_SESSION["fournisseur_recherche"] = $_POST["fournisseur_recherche"];
	$_SESSION["zone_recherche"] = $_POST["zone_recherche"];
	$res_zone = getZoneFournisseurId($co_pmp, $_SESSION["fournisseur_recherche"]);
	$res_cp = getListeCodePostal($co_pmp, $_SESSION["zone_recherche"]);
	$code_postal = "";
	while ($cp = mysqli_fetch_array($res_cp)) {
		$code_postal .= "'" . $cp["code_postal"] . "',";
	}
	$code_postal = substr($code_postal, 0, -1);
	$res = getMailFournisseurZone($co_pf, $code_postal);
	$num_mail = mysqli_num_rows($res);

	if (!empty($_POST["exporter_recherche_client"])) {
		exporterListeClientsPF($co_pf, $res);
		$res_cp = getListeCodePostal($co_pmp, $_SESSION["zone_recherche"]);
		$code_postal = "";
		while ($cp = mysqli_fetch_array($res_cp)) {
			$code_postal .= "'" . $cp["code_postal"] . "',";
		}
		$code_postal = substr($code_postal, 0, -1);
		$res = getMailFournisseurZone($co_pf, $code_postal);
		$num_mail = mysqli_num_rows($res);
	}

	if (!empty($_POST["envoyer_mail"])) {
		InsererMailAutoPF($co_pf, $res);
		$res_cp = getListeCodePostal($co_pmp, $_SESSION["zone_recherche"]);
		$code_postal = "";
		while ($cp = mysqli_fetch_array($res_cp)) {
			$code_postal .= "'" . $cp["code_postal"] . "',";
		}
		$code_postal = substr($code_postal, 0, -1);
		$res = getMailFournisseurZone($co_pf, $code_postal);
		$num_mail = mysqli_num_rows($res);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Les mails ont bien été envoyés.";
	}
} elseif (isset($_SESSION["email_client"]) || isset($_SESSION["code_postal_client"]) || isset($_SESSION["date_min_insc_client"]) || isset($_SESSION["date_max_insc_client"])) {
	$res = getClientsFiltresPF($co_pf);
	$num_mail = mysqli_num_rows($res);

	if (!empty($_POST["exporter_recherche_client"])) {
		exporterListeClientsPF($co_pf, $res);
		$res = getClientsFiltresPF($co_pf);
		$num_mail = mysqli_num_rows($res);
	}

	if (!empty($_POST["envoyer_mail"])) {
		InsererMailAutoPF($co_pf, $res);
		$res = getClientsFiltresPF($co_pf);
		$num_mail = mysqli_num_rows($res);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Les mails ont bien été envoyés.";
	}
} elseif (isset($_SESSION["zone_recherche"]) && isset($_SESSION["fournisseur_recherche"])) {
	$res_zone = getZoneFournisseurId($co_pmp, $_SESSION["fournisseur_recherche"]);
	$res_cp = getListeCodePostal($co_pmp, $_SESSION["zone_recherche"]);
	$code_postal = "";
	while ($cp = mysqli_fetch_array($res_cp)) {
		$code_postal .= "'" . $cp["code_postal"] . "',";
	}
	$code_postal = substr($code_postal, 0, -1);
	$res = getMailFournisseurZone($co_pf, $code_postal);
	$num_mail = mysqli_num_rows($res);

	if (!empty($_POST["exporter_recherche_client"])) {
		exporterListeClientsPF($co_pf, $res);
		$res_cp = getListeCodePostal($co_pmp, $_SESSION["zone_recherche"]);
		$code_postal = "";
		while ($cp = mysqli_fetch_array($res_cp)) {
			$code_postal .= "'" . $cp["code_postal"] . "',";
		}
		$code_postal = substr($code_postal, 0, -1);
		$res = getMailFournisseurZone($co_pf, $code_postal);
		$num_mail = mysqli_num_rows($res);
	}

	if (!empty($_POST["envoyer_mail"])) {
		InsererMailAutoPF($co_pf, $res);
		$res_cp = getListeCodePostal($co_pmp, $_SESSION["zone_recherche"]);
		$code_postal = "";
		while ($cp = mysqli_fetch_array($res_cp)) {
			$code_postal .= "'" . $cp["code_postal"] . "',";
		}
		$code_postal = substr($code_postal, 0, -1);
		$res = getMailFournisseurZone($co_pf, $code_postal);
		$num_mail = mysqli_num_rows($res);
		$message_type = "success";
		$message_icone = "fa-check";
		$message_titre = "Succès";
		$message = "Les mails ont bien été envoyés.";
	}
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
	<div class="menu-bloc">
		<a href="mail_modele.php">Mail Modèle</a>
		<a href="envoyer_sms.php">Envoyer SMS</a>
		<a href="param_sms.php">Param SMS</a>
		<a href="alerte_pf.php" class="active">Alerte PF</a>
	</div>
	<form method="post" id="FormID">
		<div class="row">
			<div class="col-sm-4">
				<div class="col-sm-12 text-right" style="padding: 0;">
					<input type="submit" name="vider_recherche" class="btn btn-warning" value="VIDER">
				</div>
				<label class="label-title" style="margin: 0;">Recherche client</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-8">
						<label for="email_client" class="col-form-label" style="padding-left:0;">Mail</label>
						<input type="text" name="email_client" class="form-control" style="width:100%;" value="<?php if (isset($_POST["email_client"])) {
							echo $_POST["email_client"];
						} elseif (isset($_SESSION["email_client"])) {
							echo $_SESSION["email_client"];
						} ?>">
					</div>
					<div class="col-sm-4">
						<label for="code_postal_client" class="col-form-label" style="padding-left:0;">Code
							Postal</label>
						<input type="text" name="code_postal_client" class="form-control" style="width:100%;" value="<?php if (isset($_POST["code_postal_client"])) {
							echo $_POST["code_postal_client"];
						} elseif (isset($_SESSION["code_postal_client"])) {
							echo $_SESSION["code_postal_client"];
						} ?>">
					</div>
					<div class="col-sm-12">
						<label class="col-form-label">Date inscription entre le</label>
						<div class="form-inline">
							<div class="col-sm-5" style="padding:0">
								<input type="date" name="date_min_insc_client" class="form-control text-right"
									style="width:100%;" value="<?php if (isset($_POST["date_min_insc_client"])) {
										echo $_POST["date_min_insc_client"];
									} elseif (isset($_SESSION["date_min_insc_client"])) {
										echo $_SESSION["date_min_insc_client"];
									} ?>">
							</div>
							<span style="font-size: 14px;margin: 0 25px;">et le</span>
							<div class="col-sm-5" style="padding:0">
								<input type="date" name="date_max_insc_client" class="form-control text-right"
									style="width:100%;" value="<?php if (isset($_POST["date_max_insc_client"])) {
										echo $_POST["date_max_insc_client"];
									} elseif (isset($_SESSION["date_max_insc_client"])) {
										echo $_SESSION["date_max_insc_client"];
									} ?>">
							</div>
						</div>
					</div>
					<div class="col-sm-12 text-right" style="margin-top:3%;">
						<input type="submit" name="chercher_client" class="btn btn-primary" value="CHERCHER">
					</div>
				</div>
				<hr>
				<label class="label-title" style="margin: 0;">Recherche sur zone fournisseur</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-8">
						<label for="fournisseur_recherche" class="col-form-label">Fournisseur</label>
						<select class="form-control input-custom" name="fournisseur_recherche" style="width:100%;">
							<option value="0"></option>
							<?php
							$res_four = getFournisseursListe($co_pmp);
							while ($fournisseur = mysqli_fetch_array($res_four)) {
								?>
								<option value="<?= $fournisseur["id"]; ?>" <?php if (isset($_SESSION["fournisseur_recherche"])) {
									  if ($_SESSION["fournisseur_recherche"] == $fournisseur["id"]) {
										  echo "selected='selected'";
									  }
								  } ?>><?= $fournisseur["nom"]; ?></option>
								<?php
							}
							?>
						</select>
					</div>
					<div class="col-sm-4">
						<label for="zone_recherche" class="col-form-label">zone</label>
						<select class="form-control input-custom code" name="zone_recherche" style="width:100%;">
							<?php
							if (isset($res_zone)) {
								while ($zone = mysqli_fetch_array($res_zone)) {
									?>
									<option value="<?= $zone["id"]; ?>" <?php if (isset($_SESSION["zone_recherche"])) {
										  if ($_SESSION["zone_recherche"] == $zone["id"]) {
											  echo "selected='selected'";
										  }
									  } ?>>
										<?= $zone["libelle"]; ?>
									</option>
									<?php
								}
							} elseif (isset($_SESSION["zone_recherche"])) {
								$res_zone = getZoneFournisseurId($co_pmp, $_SESSION["fournisseur_recherche"]);
								while ($zone = mysqli_fetch_array($res_zone)) {
									?>
									<option value="<?= $zone["id"]; ?>" <?php if ($_SESSION["zone_recherche"] == $zone["id"]) {
										  echo "selected='selected'";
									  } ?>><?= $zone["libelle"]; ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>
					<div class="col-sm-12 text-right" style="margin-top:3%;">
						<input type="submit" name="chercher_client_fournisseur" class="btn btn-primary"
							value="CHERCHER">
					</div>
				</div>
				<hr>
				<div class="form-inline" style="margin: 2% 0 0 0;">
					<label for="etat_four" class="col-sm-4 col-form-label" style="padding-left:0;">Total de mail
						chargé</label>
					<input type="text" class="form-control col-sm-4" name="" value="<?php if (isset($num_mail)) {
						echo $num_mail;
					} ?>">
				</div>
			</div>
			<div class="col-sm-8">
				<div class="row">
					<div class="col-sm-12 text-right">
						<input type="submit" name="exporter_recherche_client" class="btn btn-secondary"
							value="EXPORTER">
					</div>
				</div>
				<div class="tableau" style="height: 375px;">
					<table class="table">
						<thead>
							<tr>
								<th>C.Postal</th>
								<th>Mail</th>
								<th class="text-center">Reduc</th>
								<th class="text-center">Inf</th>
								<th class="text-center">Sup</th>
								<th class="text-center">Evo</th>
								<th class="text-center">Com</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if (isset($res)) {
								while ($clients = mysqli_fetch_array($res)) {
									?>
									<tr class="select select_client">
										<input type="hidden" name="" value="<?= $clients["id"]; ?>">
										<td><?= $clients["cp"]; ?></td>
										<td><?= $clients["mail"]; ?></td>
										<td class="text-center">
											<?php if ($clients["alerte_reduc"] == 1) {
												echo '<i class="fas fa-times"></i>';
											} ?>
										</td>
										<td class="text-center"><?= $clients["alerte_inf"]; ?></td>
										<td class="text-center"><?= $clients["alerte_sup"]; ?></td>
										<td></td>
										<td class="text-center">
											<?php if ($clients["alerte_com"] == 1) {
												echo '<i class="fas fa-times"></i>';
											} ?>
										</td>
									</tr>
									<?php
								}
							}
							?>
						</tbody>
					</table>
				</div>
				<div class="row">
					<div class="col-sm-9 text-right">
						<button type="button" data-bs-toggle="modal" class="btn btn-secondary"
							data-bs-target="#supprimerMail" name="button" style="width: 187px;">SUPPRIMER MAIL</button>
						<div class="modal fade" id="supprimerMail" tabindex="-1" aria-labelledby="exampleModalLabel"
							aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Êtes-vous sûr de vouloir
											supprimer ce mail ?</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-label="Close"> <i class="fal fa-times"></i> </button>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-sm-8 text-left">
												<label for="email_client" class="col-form-label"
													style="padding-left:0;">Mail</label>
												<input type="text" name="email_client_modifier" id="email_client_supp"
													class="form-control" style="width:100%;" value="">
											</div>

										</div>
									</div>
									<div class="modal-footer">
										<input type="hidden" name="code_client" id="code_client_supp" value="">
										<input type="submit" name="supprimer_mail_pf" class="btn btn-primary"
											value="Supprimer">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-3 text-right">
						<button type="button" data-bs-toggle="modal" class="btn btn-primary"
							data-bs-target="#modifierMail" name="button" style="width: 187px;">MODIFIER MAIL</button>
						<div class="modal fade" id="modifierMail" tabindex="-1" aria-labelledby="exampleModalLabel"
							aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Modification Mail PF</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"
											aria-label="Close"> <i class="fal fa-times"></i> </button>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-sm-8 text-left">
												<label for="email_client" class="col-form-label"
													style="padding-left:0;">Mail</label>
												<input type="text" name="email_client_modifier"
													id="email_client_modifier" class="form-control" style="width:100%;"
													value="">
											</div>
											<div class="col-sm-4 text-left">
												<label for="code_postal_client" class="col-form-label"
													style="padding-left:0;">Code Postal</label>
												<input type="text" name="code_postal_client_modifier"
													id="code_postal_client_modifier" class="form-control"
													style="width:100%;" value="">
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<input type="hidden" name="code_client" id="code_client" value="">
										<input type="submit" name="modifier_mail_pf" class="btn btn-primary"
											value="Modifier">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr>
		<label class="label-title" style="margin: 0;">Envoyer Mail Alerte PF à la liste des mails séléctionnés
			ci-dessus</label>
		<div class="ligne"></div>
		<div class="row">
			<div class="col-sm-7">
				<div class="row">
					<div class="col-sm-12 text-right">
						<?php
						if (isset($_SESSION["mail_modele"])) {
							?>
							<a href="alerte_pf.php?modal_param=oui&mail_id=<?= $_SESSION["mail_modele"]; ?>"
								class="btn btn-warning text-right">PARAMETRAGE MAIL MODELE PF</a>
							<?php
						} else {
							?>
							<a href="alerte_pf.php?modal_param=oui" class="btn btn-warning text-right">PARAMETRAGE MAIL
								MODELE PF</a>
							<?php
						}
						?>
						<div class="modal fade" id="paramMail" tabindex="-1" aria-labelledby="exampleModalLabel"
							aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" style="max-width: 85%;">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Parametrage mail modèle</h5>
										<button type="button" class="btn-close b-close" data-bs-dismiss="modal"
											aria-bs-label="Close"> <i class="fal fa-times"></i> </button>
									</div>
									<div class="modal-body text-left">
										<?php include 'form/modal_param_mail_modele_pf.php'; ?>
									</div>
									<div class="modal-footer text-right">
										<button type="button" class="btn btn-secondary b-close"
											data-bs-dismiss="modal">Fermer</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<label for="mail_modele" class="col-sm-4 col-form-label" style="padding-left:0;">Choix du mail à
					envoyer</label>
				<select class=" form-control" name="mail_modele" style="width:100%;" onchange="myFunction()">
					<option value=""></option>
					<?php
					while ($mail_pf = mysqli_fetch_array($res_mail_pf)) {
						?>
						<option value="<?= $mail_pf["id"]; ?>" <?php if (isset($_SESSION["mail_modele"])) {
							  if ($_SESSION["mail_modele"] == $mail_pf["id"]) {
								  echo "selected='selected'";
							  }
						  } ?>>
							<?= $mail_pf["sujet"]; ?>
						</option>
						<?php
					}
					?>
				</select>
				<div class="row">
					<div class="col-sm-12 text-right" style="margin-top: 20px;">
						<input type="submit" name="envoyer_mail" class="btn btn-primary text-right"
							value="ENVOYER MAIL">
					</div>
				</div>
			</div>
			<div class="col-sm-5">
				<div class="tableau" style="height: 195px;margin-top: 0;">
					<table class="table">
						<thead>
							<tr>
								<th>Mots-clés</th>
								<th>Valeur</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 0;
							if (isset($res_mot)) {
								while ($mot_cle = mysqli_fetch_array($res_mot)) {
									?>
									<tr>
										<input type="hidden" name="cle_id[]" value="<?= $mot_cle["id"]; ?>">
										<td style="padding: 0% 0.75rem;"><input type="hidden" name="mots_cle_<?= $i++; ?>"
												value="<?= $mot_cle["cle"]; ?>"><?= $mot_cle["cle"]; ?></td>
										<td style="padding: 0% 0.75rem;"> <input type="text" name="valeur_cle[]"
												class="form-control" value=""> </td>
									</tr>
									<?php
								}
							}
							?>
						</tbody>
					</table>
				</div>
				<input type="hidden" name="nb_mot_cle" value="<?= $i; ?>">
			</div>
		</div>
		<hr>
		<p style="font-size: 14px;color: #0b2424ab;">Information nombre total inscrits :
			<strong><?= $total["total"]; ?></strong>
		</p>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="/admin/js/script_mail.js" charset="utf-8"></script>
<script>
	function myFunction(val) {
		console.log("Entered Value is: " + val);
		var frm = document.getElementById("FormID");

		frm.submit();
	}
	$(document).ready(function () {
		$('select[name="fournisseur_recherche"]').change(function () {
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
	});
</script>