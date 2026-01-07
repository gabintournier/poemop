<?php

session_start();
include_once 'inc/dev_auth.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
//*** Securisation du formulaire
// On detecte la recharge par F5 (par exemple) dans une meme session
$recharge = TRUE;
$RequestSignature = md5($_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'] . print_r($_POST, true));
if ($_SESSION['LastRequest'] != $RequestSignature) {
	$_SESSION['LastRequest'] = $RequestSignature;
	$recharge = FALSE;
}
// On detecte le token du form et le token de la session sont identique
if (isset($_SESSION['token']) AND isset($_POST['token']) AND !empty($_SESSION['token']) AND !empty($_POST['token'])) {
	if ($_SESSION['token'] == $_POST['token']) {
		$recharge = FALSE;
	}
}

$desc = 'Commandes groupées de fioul domestique partout en France. Poemop groupe gratuitement votre commande de fioul avec celles de vos voisins.';
$title = 'Commande de fioul moins cher avec POEMOP';
ob_start();

include_once __DIR__ . "/inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_compte.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_commande.php";

if (isset($_GET["id_crypte"])) {
	$id_crypte = $_GET["id_crypte"];
	$query = "  SELECT *
				FROM pmp_utilisateur
				WHERE id_crypte = '$id_crypte' ";
	$res = my_query($co_pmp, $query);
	$pmp_user = mysqli_fetch_array($res);

	if (isset($pmp_user['user_id'])) {
		$_SESSION['id'] = $pmp_user['user_id'];
		$id_session = mysqli_real_escape_string($co_pmp, $_SESSION['id']);


	}
}
if (isset($_SESSION['id'])) {
	$res_cmd = getCommandeUtilisateur($co_pmp, $_SESSION['id']);

	if (!$res_cmd) {
		$res_cmd = [
			"cmd_status" => null,
			"cmd_typefuel" => 0,
			"cmd_qte" => 1000,
			"cmd_prix_ord" => 0,
		];
	}
	$res_annulee = getCommandeUtilisateurAnnuleePrix($co_pmp, $_SESSION['id']);

	if (isset($res_cmd[0])) {
		$status_calcule = CalculeStatus($res_cmd);
		$regroupement = ChargeGroupement($co_pmp, $res_cmd);

		if (isset($regroupement["id_four"]) && $regroupement["id_four"] > 0) {
			$info_four = ModalitesFournisseur($co_pmp, $regroupement["id_four"]);
		}

		if (isset($res_cmd['cmd_qte'])) {
			$qte = $res_cmd['cmd_qte']; // prends la valeur exacte de la DB
		} else {
			error_log('[WARN] cmd_qte absent pour l\'utilisateur ' . $_SESSION['id']);
			$qte = 0; // ou null si tu veux, juste pour éviter une erreur PHP
		}


		// Vérification fournisseur spécifique
		if (isset($regroupement['id_four']) && (int) $regroupement['id_four'] === 2049) {
			$types_combustibles = "Merci de bien prendre connaissance des produits proposés par le fournisseur";
		}
	}

	$pmp_utilisateur = ChargeCompteFioul($co_pmp, $_SESSION['id']);
	$jjj_users = ChargeCompteJoomla($co_pmp, $_SESSION['id']);
	$res_commandes = chargeAnciennesCommandes($co_pmp, $_SESSION['id']);
	$num_commandes = mysqli_num_rows($res_commandes);
	$tabRaisonsRefu = ChargeRaisonsRefu();
	echo $_SESSION['id'];

	if (!isset($_SESSION['id'])) {
		header('Location: /');
		die();
	}

	if (isset($res_cmd["cmd_typefuel"])) {
		if ($res_cmd["cmd_typefuel"] == "1") {
			$fuel = "ordinaire";
		} elseif ($res_cmd["cmd_typefuel"] == "2") {
			$fuel = "supérieur";
		}
	}

	if (isset($res_cmd["cmd_status"])) {
		$statut = $res_cmd["cmd_status"];
	} else {
		$statut = '1';
	}

	if (empty($jjj_users["name"]) || empty($pmp_utilisateur["prenom"]) || empty($pmp_utilisateur["adresse"]) || empty($pmp_utilisateur["ville"]) || empty($pmp_utilisateur["code_postal"]) || empty($pmp_utilisateur["tel_fixe"]) || empty($jjj_users["email"])) {
		$coord_ko = "grey";
		if (empty($pmp_utilisateur["tel_fixe"])) {
			$telephone_ko = "numéro de téléphone";
		}
		if (empty($pmp_utilisateur["adresse"])) {
			$adresse_ko = "adresse";
		}
		if (empty($jjj_users["name"])) {
			$nom_ko = "nom";
		}
		if (empty($pmp_utilisateur["prenom"])) {
			$prenom_ko = "prénom";
		}
		if (empty($pmp_utilisateur["ville"])) {
			$ville_ko = "ville";
		}
		if (empty($pmp_utilisateur["code_postal"])) {
			$cp_ko = "code postal";
		}
		if (empty($jjj_users["email"])) {
			$email_ko = "email";
		}
	}

	include 'modules/menu_fioul.php';

	?>
	<div class="container-fluid">
		<div class="header commandes">
			<div class="row">
				<div class="col-sm-9 text-center">
					<h1>Ma commande de fioul domestique</h1>
					<div class="ligne-center jaune"></div>
					<a href="comment_ca_marche_fioul.php" class="text-right"
						title="Groupement de fioul domestique POEMOP"><i class="far fa-lightbulb"></i> Comment ça marche
						?</a>
					<?php
					if ($pmp_utilisateur['inscrit'] != 1) {
						?>
						<div class="toast info">
							<div class="message-icon info-icon">
								<i class="fas fa-info"></i>
							</div>
							<div class="message-content ">
								<div class="message-type" style="text-align:left;">
									Avertissement
								</div>
								<div class="message" style="text-align:left;">
									Vous n'êtes pas inscrit aux groupements de fioul. Rendez-vous sur vos inscriptions pour
									cocher la case 'FIOUL'
								</div>
							</div>
						</div>
						<?php
					} else {
						if (isset($res_cmd["cmd_status"])) {

							if ($res_cmd["cmd_status"] == '15' || $res_cmd["cmd_status"] == '17') {
								if ($res_cmd['cmd_prix_ord'] > 0) {
									if (isset($_GET["refuser_tarif"])) {

										?>
										<div class="toast info" style="border-left: 3px solid #dc3545 !important;">
											<div class="message-icon info-icon">
												<i class="fas fa-info" style="padding: 22% 39%!important;background:#dc3545 !important;"></i>
											</div>
											<div class="message-content ">
												<div class="message-type" style="text-align:left;">
													Afin de valider votre refus de tarif,
												</div>
												<div class="message" style="text-align:left;">
													veuillez remplir le formulaire ci-dessous.<br>
												</div>
											</div>
										</div>
									<?php } else { ?>
										<div class="toast info">
											<div class="message-icon info-icon">
												<i class="fas fa-info" style="padding: 22% 39%!important;"></i>
											</div>
											<div class="message-content ">
												<div class="message-type" style="text-align:left;">
													Veuillez accepter ou refuser le tarif ci-dessous.
												</div>
												<div class="message" style="text-align:left;">
													Votre commande est groupée, mais vous n'avez pas validé le tarif.<br>
												</div>
											</div>
										</div>
										<?php
									}
								}
							}
						}
						if (isset($message)) // Affiche les message d'erreur ou du succès
						{
							?>
							<div class="toast <?= $message_type; ?>">
								<div class="message-icon <?= $message_type; ?>-icon">
									<i class="fas <?= $message_icone; ?>"></i>
								</div>
								<div class="message-content ">
									<div class="message-type" style="text-align:left;">
										<?= $message_info; ?>
									</div>
									<div class="message" style="text-align:left;">
										<?= $message; ?>
									</div>
								</div>
							</div>
							<?php
						}
						if (isset($_GET["refuser_tarif"])) {
							?>
							<div class="refus_tarif" style="display:block!important;text-align: left;">
								<a class="return" href="ma_commande.php" style="color: #347261!important"><i
										class="fas fa-chevron-left"></i> Ma commande</a>
								<div class="text-center">
									<h2>Indiquez nous votre raison de refus du tarif</h2>
									<div class="ligne-center orange"></div>
									<p>Sélectionnez votre raison</p>
									<form method="post">
										<select name="raison_refus_selectionne" class="form-control"
											style="width: 60%;margin: 0 auto;" required>
											<option value="0" selected></option>
											<option value="1" <?php if (isset($_POST['raison_refus_selectionne'])) {
												if ($_POST['raison_refus_selectionne'] == 1) {
													echo "selected";
												}
											} ?>>
												<?php print htmlspecialchars($tabRaisonsRefu[1]); ?>
											</option>
											<option value="2" <?php if (isset($_POST['raison_refus_selectionne'])) {
												if ($_POST['raison_refus_selectionne'] == 2) {
													echo "selected";
												}
											} ?>>
												<?php print htmlspecialchars($tabRaisonsRefu[2]); ?>
											</option>
											<option value="3" <?php if (isset($_POST['raison_refus_selectionne'])) {
												if ($_POST['raison_refus_selectionne'] == 3) {
													echo "selected";
												}
											} ?>>
												<?php print htmlspecialchars($tabRaisonsRefu[3]); ?>
											</option>
											<option value="4" <?php if (isset($_POST['raison_refus_selectionne'])) {
												if ($_POST['raison_refus_selectionne'] == 4) {
													echo "selected";
												}
											} ?>>
												<?php print htmlspecialchars($tabRaisonsRefu[4]); ?>
											</option>
											<option value="5" <?php if (isset($_POST['raison_refus_selectionne'])) {
												if ($_POST['raison_refus_selectionne'] == 5) {
													echo "selected";
												}
											} ?>>
												<?php print htmlspecialchars($tabRaisonsRefu[5]); ?>
											</option>
											<option value="6" <?php if (isset($_POST['raison_refus_selectionne'])) {
												if ($_POST['raison_refus_selectionne'] == 6) {
													echo "selected";
												}
											} ?>>
												<?php print htmlspecialchars($tabRaisonsRefu[6]); ?>
											</option>
											<option value="7" <?php if (isset($_POST['raison_refus_selectionne'])) {
												if ($_POST['raison_refus_selectionne'] == 7) {
													echo "selected";
												}
											} ?>>
												<?php print htmlspecialchars($tabRaisonsRefu[7]); ?>
											</option>
										</select>
										<p class="refusTxt">Ajoutez un commentaire</p>
										<textarea class="form-control" type="text" name="raison_refus"
											style="width: 60%;margin: 0 auto;"></textarea>
										<input type="submit" name="valider_refus" class="btn btn-secondary" value="Envoyer"
											style="margin-top:2%;">
									</form>
								</div>
							</div>
							<?php
						} else {
							?>
							<div class="statut-commande">
								<?php
								if (isset($res_cmd[0])) {
									if ($res_cmd["cmd_status"] == '0' || $res_cmd["cmd_status"] == '13' || $res_cmd["cmd_status"] == '11') {
										$statut = '1';
									} elseif ($res_cmd["cmd_status"] == '12') {
										$statut = '10';
									}
								}
								?>
								<img src="images/statut-<?= $statut; ?>-commande-poemop.png" class="statut-desktop"
									alt="Pas de commande - Vous n'avez pas de commande en cours">
								<img src="images/statut-<?= $statut; ?>-commande-poemop-mobile-n.png" class="statut-mobile"
									alt="Pas de commande - Vous n'avez pas de commande en cours">
								<?php               // Si une commande existe avec le status 10
											if (isset($res_cmd[0]) && $res_cmd["cmd_status"] == '10' || isset($res_cmd[0]) && $res_cmd["cmd_status"] == '12') {
												?>
									<div class="infos-commandes statut_<?= $statut; ?>">
										<p><img src="images/statut_<?= $statut; ?>.svg"
												alt="Pas de commande - Vous n'avez pas de commande en cours"> Vous avez demandé <span
												class="infos_cmds"><?= htmlspecialchars($qte); ?>L</span> de fioul <span
												class="infos_cmds"><?= htmlspecialchars($fuel); ?></span></p>
									</div>
									<?php
											} elseif (isset($res_cmd[0]) && $res_cmd["cmd_status"] == '15') {
												?>
									<div class="infos-commandes statut_<?= $statut; ?>">
										<p><img src="images/statut_<?= $statut; ?>.svg"
												alt="Pas de commande - Vous n'avez pas de commande en cours"> Vous êtes inscrit au
											groupement pour <span class="infos_cmds"><?= htmlspecialchars($qte); ?>L</span> de fioul
											<span class="infos_cmds"><?= htmlspecialchars($fuel); ?></span>
										</p>
										<p style="    margin-top: 25px;color: #ef8351;">L'offre de prix vous sera envoyée par mail.
											Retrouvez toutes les dates du groupement ci-dessous.</p>
									</div>
									<?php
											} elseif (isset($res_cmd[0]) && $res_cmd["cmd_status"] == '17') {
												?>
									<div class="infos-commandes statut_<?= $statut; ?>">
										<p><img src="images/statut_<?= $statut; ?>.svg"
												alt="Pas de commande - Vous n'avez pas de commande en cours"> Vous êtes inscrit au
											groupement pour <span class="infos_cmds"><?= htmlspecialchars($qte); ?>L</span> de fioul
											<span class="infos_cmds"><?= htmlspecialchars($fuel); ?></span>
										</p>
									</div>
									<?php
											} elseif (isset($res_cmd[0]) && $res_cmd["cmd_status"] == '20' || isset($res_cmd[0]) && $res_cmd["cmd_status"] == '30') {
												?>
									<div class="infos-commandes statut_<?= $statut; ?>">
										<p><img src="images/statut_<?= $statut; ?>.svg"
												alt="Pas de commande - Vous n'avez pas de commande en cours"> Vous avez commandé <span
												class="infos_cmds"><?= htmlspecialchars($qte); ?>L</span> de fioul <span
												class="infos_cmds"><?= htmlspecialchars($fuel); ?></span></p>
									</div>
									<?php
									AfficheInfosCommande($res_cmd);
									?>
									<h2 style="margin-top:2%;">Modifier ma commande</h2>
									<div class='ligne-center orange'></div>
									<p>Il est désormais impossible de modifier votre commande</p>
									<?php
									if (isset($res_cmd["cmd_comment"])) {
										$cmd_comment = htmlspecialchars($res_cmd["cmd_comment"]);
										?>
										<div class="proposee" style="width: 100%">
											<p class="titre-bloc">Commentaire commande</p>
											<hr class="separe">
											<p style="line-break: anywhere;"><?= nl2br($cmd_comment); ?></p>
										</div>
										<?php
									}
									if ($res_cmd["cmd_status"] == '30') {
										$cmd_id = htmlspecialchars($res_cmd["id"]);
										$avis = resAvisCmd($co_pmp, $cmd_id);
										?>
										<div class="proposee" style="width: 100%">
											<?php

											?>
											<p class="titre-bloc">Avis commande</p>
											<hr class="separe">
											<div class="rating">
												<input type="radio" id="star5" name="rating" value="5" <?php if ($avis["note"] == 5) {
													echo 'checked="checked"';
												} ?> />
												<label class="full" for="star5"></label>

												<input type="radio" id="star4" name="rating" value="4" <?php if ($avis["note"] == 4) {
													echo 'checked="checked"';
												} ?> />
												<label class="full" for="star4"></label>

												<input type="radio" id="star3" name="rating" value="3" <?php if ($avis["note"] == 3) {
													echo 'checked="checked"';
												} ?> />
												<label class="full" for="star3"></label>

												<input type="radio" id="star2" name="rating" value="2" <?php if ($avis["note"] == 2) {
													echo 'checked="checked"';
												} ?> />
												<label class="full" for="star2"></label>

												<input type="radio" id="star1" name="rating" value="1" <?php if ($avis["note"] == 1) {
													echo 'checked="checked"';
												} ?> />
												<label class="full" for="star1"></label>
											</div>
											<?php
											if (isset($avis["message"])) {
												$message = htmlspecialchars($avis["message"]);
												?>
												<p><?= $message; ?></p>
												<?php
											}
											?>
											<?php

											?>
										</div>
										<?php
									}
									?>
									<?php
											} elseif (isset($res_cmd[0]) && $res_cmd["cmd_status"] == '25') {
												?>
									<div class="infos-commandes statut_<?= $statut; ?>">
										<p><img src="images/statut_<?= $statut; ?>.svg"
												alt="Pas de commande - Vous n'avez pas de commande en cours"> Vous avez commandé <span
												class="infos_cmds"><?= htmlspecialchars($res_cmd["cmd_qte"]); ?>L</span> de fioul <span
												class="infos_cmds"><?= htmlspecialchars($fuel); ?></span></p>
									</div>
									<?php
									AfficheInfosCommande($res_cmd);
									?>
									<h2 style="margin-top:2%;">Modifier ma commande</h2>
									<div class='ligne-center orange'></div>
									<p>Il est désormais impossible de modifier votre commande</p>
									<?php
									if (isset($res_cmd["cmd_comment"])) {
										?>
										<div class="proposee" style="width: 100%">
											<p class="titre-bloc">Commentaire commande</p>
											<hr class="separe">
											<p><?= htmlspecialchars($res_cmd["cmd_comment"]); ?></p>
										</div>
										<?php
									}
									?>
									<div class="livree_avis_client" style="margin-top: 2%;">
										<form method="post">
											<fieldset>
												<legend>Ma commande a été livrée ?</legend>
												<hr class="separe">
												<p>Si vous avez été livré de votre commande Poemop, vous pouvez, dans le cadre
													ci-dessous, nous indiquer si vous êtes satisfait<br>
													de votre commande. Dites nous ce que vous avez pensé du fournisseur, du livreur
													ainsi que de la prestation POEMOP.<br>N'hésitez pas à nous faire part de vos
													remarques et à noter votre commande.
													Si vous n'avez pas été livré<br>ou si vous avez une réclamation, merci de nous
													adresser un mail à <a title="nous écrire"
														href="mailto:info@poemop.fr">info@poemop.fr</a></p>
											</fieldset>
											<div class="rating">
												<input type="radio" id="star5" name="rating" value="5" checked="checked" />
												<label class="full" for="star5"></label>

												<input type="radio" id="star4" name="rating" value="4" />
												<label class="full" for="star4"></label>

												<input type="radio" id="star3" name="rating" value="3" />
												<label class="full" for="star3"></label>

												<input type="radio" id="star2" name="rating" value="2" />
												<label class="full" for="star2"></label>

												<input type="radio" id="star1" name="rating" value="1" />
												<label class="full" for="star1"></label>
											</div>
											<input type="hidden" name="signature"
												value="<?php print ($pmp_utilisateur['prenom'] . ' ' . substr($pmp_utilisateur["nom"], 0, 1)); ?>" />
											<textarea name="livre_or" rows="4" cols="20" class="form-control"
												style="width: 65%;margin: 0 auto;margin-bottom: 2%;"></textarea>
											<input type="submit" name="signale_livree" class="btn btn-primary"
												value="Signaler la livraison">
										</form>
									</div>

									<?php
											} else {
												?>
									<div class="infos-commandes etat1">
										<p> <span><img src="images/pas-de-commande-poemop.svg"
													alt="Pas de commande - Vous n'avez pas de commande en cours"></span> Vous n'avez pas
											de commande en cours</p>
									</div>
									<?php
									if (isset($res_annulee[0])) {
										$groupement_en_cours = ChargeGroupementCree($co_pmp, $res_annulee);
										if (isset($groupement_en_cours[0]) && $res_annulee["cmd_comment"] == "Je suis absent aux dates de livraison" || isset($groupement_en_cours[0]) && $res_annulee["cmd_comment"] == "Ma commande est urgente") {
											?>
											<form method="post">
												<div class="encart"
													style="background: white;padding: 2%;width: 80%;margin: 0 auto;margin-top: 2%;border-radius: 13px;border: 1px solid #347261;">
													<p style="font-family: 'Goldplay Alt SemiBold';">Vous avez refusé le tarif pour la raison
														suivante "<?= $res_annulee["cmd_comment"]; ?>"</p>
													<p>Vous avez encore la possibilité d'accepter le tarif en cliquant sur le bouton "réactiver
														ma commande".</p>
													<?php
													if ($res_annulee["cmd_comment"] == "Ma commande est urgente") {
														?>
														<p>Merci de nous préciser qu'il s'agit d'une commande urgente avant d'accepter le tarif afin
															que nous puissions le transmettre au fournisseur<br>
															ainsi votre commande sera traitée en priorité.</p>
														<?php
													} else {
														?>
														<p>Merci de nous transmettre vos disponibilités avant d'accepter le tarif afin que nous
															puissions les transmettre au fournisseur.<br>
															La commande sera automatiquement annulée si vous ne parvenez pas à fixer un rendez-vous.
														</p>
														<?php
													}
													?>
													<input type="hidden" name="id_cmd_a" value="<?= $res_annulee["id"]; ?>">
													<input type="submit" class="btn btn-primary" name="reactiver_commande"
														value="RÉACTIVER MA COMMANDE" style="width: 100%;padding: 5px 0px !important;">
												</div>
											</form>
											<?php
										}
									}
									?>
									<?php
									if (isset($res_cmd[0]) && $res_cmd["cmd_status"] == '13') {
										?>
										<div class="grpt-en-cours">
											Un groupement est en cours sur votre secteur
										</div>
										<?php
									}
											}
											if ($res_cmd !== null) {
												if ($res_cmd['cmd_prix_ord'] > 0 && $res_cmd['cmd_prix_ord'] != NULL) {
													if (isset($res_cmd[0]) && $res_cmd["cmd_status"] == '17') {
														if (isset($types_combustibles)) {
															?>
												<div style="background: #fff;display: block;margin: 2% auto 0;padding: 1%;border-radius: 15px;">
													<p style="font-family: 'Goldplay Alt SemiBold'; margin-bottom: 13px;">
														<?= $types_combustibles; ?>
													</p>
													<p style="margin-bottom:0;"> <span style="font-family: 'Goldplay Alt SemiBold';">FIOUL
															ORDINAIRE</span> : Energie Liquide Désoufrée</p>
													<p><span style="font-family: 'Goldplay Alt SemiBold';">FIOUL SUPÉRIEUR</span> : Carat Végétal
													</p>
												</div>
												<?php
														}
														AfficheInfosCommande($res_cmd);
														?>
											<!-- <div class="action_tarif">
						<a href="/ma_commande.php#<?php if (isset($coord_ko)) {
							echo "coord-ko";
						} else {
							echo "show_valider";
						} ?>" class="btn btn-primary <?php if (isset($coord_ko)) {
							 echo $coord_ko;
						 } ?>">Accepter le tarif <i class="fa-solid fa-angle-down"></i></a>
					</div> -->
											<?php
													}

													if ($res_cmd["cmd_status"] == '12' || $res_cmd["cmd_status"] == '13' || $res_cmd["cmd_status"] == '15') {
														if (isset($types_combustibles)) {
															?>
												<div style="background: #fff;display: block;margin: 2% auto 0;padding: 1%;border-radius: 15px;">
													<p style="font-family: 'Goldplay Alt SemiBold'; margin-bottom: 13px;">
														<?= $types_combustibles; ?>
													</p>
													<p style="margin-bottom:0;"> <span style="font-family: 'Goldplay Alt SemiBold';">FIOUL
															ORDINAIRE</span> : Energie Liquide Désoufrée</p>
													<p><span style="font-family: 'Goldplay Alt SemiBold';">FIOUL SUPÉRIEUR</span> : Carat Végétal
													</p>
												</div>

												<?php
														}
														AfficheInfosCommande($res_cmd);
														?>
											<!-- <div class="action_tarif">
						<a href="/ma_commande.php#<?php if (isset($coord_ko)) {
							echo "coord-ko";
						} else {
							echo "show_valider";
						} ?> " class="btn btn-primary <?php if (isset($coord_ko)) {
							  echo $coord_ko;
						  } ?> ">Accepter le tarif <i class="fa-solid fa-angle-down"></i></a>
					</div> -->
											<?php
													}
												}
											}

											?>
								<div class="coord-ko" style="display:none;">
									<div class="toast no" style="margin: 1% 0 2% 25%!important;">
										<div class="message-icon no-icon">
											<i class="fas fal fa-times"></i>
										</div>
										<div class="message-content ">
											<div class="message-type" style="text-align:left;">
												Vos coordonnées sont incomplètes
											</div>
											<div class="message" style="text-align:left;">
												Merci de renseigner vos coordonnées pour accepter le tarif.<br>
												Il manque votre :<br>
												<?php if (isset($telephone_ko)) {
													echo "- " . $telephone_ko . "<br>";
												} ?>
												<?php if (isset($adresse_ko)) {
													echo "- " . $adresse_ko . "<br>";
												} ?>
												<?php if (isset($nom_ko)) {
													echo "- " . $nom_ko . "<br>";
												} ?>
												<?php if (isset($prenom_ko)) {
													echo "- " . $prenom_ko . "<br>";
												} ?>
												<?php if (isset($ville_ko)) {
													echo "- " . $ville_ko . "<br>";
												} ?>
												<?php if (isset($cp_ko)) {
													echo "- " . $cp_ko . "<br>";
												} ?>
												<?php if (isset($email_ko)) {
													echo "- " . $email_ko . "<br>";
												} ?>
												<a href="ma_commande.php#modifier_coordonnees" class="btn btn-secondary"
													style="margin-top:2%;">Modifier coordonnées <i
														class="fal fa-arrow-circle-down"></i></a>
											</div>
										</div>
									</div>
								</div>

								<?php
								// Si les informations de l'utilisateur sont complètes on affiche la possibilité de faire une commande
								// if(!empty($jjj_users["name"]) && !empty($pmp_utilisateur["prenom"]) && !empty($pmp_utilisateur["adresse"]) && !empty($pmp_utilisateur["ville"]) && !empty($pmp_utilisateur["code_postal"]) && !empty($pmp_utilisateur["tel_fixe"]) || !empty($pmp_utilisateur["tel_fixe"]) && !empty($pmp_utilisateur["prenom"]) && !empty($jjj_users["email"]) && $res_cmd["cmd_status"] != '20' && $res_cmd["cmd_status"] != '25' && $res_cmd["cmd_status"] != '30')
								// {
								?>
								<form method="post" id="FormID">
									<?php                   // Si une commande existe déjà on affiche le formulaire
												if ($res_cmd["cmd_status"] != 25 && $res_cmd["cmd_status"] != 20 && $res_cmd["cmd_status"] != 30) {
													?>
										<h2 style="margin-top:2%;">
											<?php if ($res_cmd["cmd_status"] == '13') {
												echo "Joindre ma commande";
											} else {
												echo "Modifier ma commande";
											} ?>
										</h2>
										<div class='ligne-center orange'></div>

										<div class="row" style="margin-top:2%;">
											<div class="col-sm">

											</div>
											<div class="col-sm align-self-center">
												<div class="box-commande">
													<input <?php if (isset($res_cmd["cmd_status"])) {
														if ($res_cmd["cmd_status"] != '12' && $res_cmd["cmd_status"] != '13') {
															echo 'onchange="myFunction(this.value)"';
														}
													} ?> type="radio" name="qualite" id="qualite_o" value="1" class="radiobox" <?php if (isset($res_cmd["cmd_typefuel"])) {
														  if ($res_cmd["cmd_typefuel"] == "1") {
															  echo "checked='checked'";
														  }
														  if ($res_cmd["cmd_typefuel"] == "0") {
															  echo "checked='checked'";
														  }
													  } else {
														  echo "checked='checked'";
													  } ?>>
													<label for="qualite_o" class="for-radiobox">Fioul ordinaire</label>
												</div>
											</div>
											<div class="col-sm align-self-center">
												<div class="box-commande">
													<input <?php if (isset($res_cmd["cmd_status"])) {
														if ($res_cmd["cmd_status"] != '12' && $res_cmd["cmd_status"] != '13') {
															echo 'onchange="myFunction(this.value)"';
														}
													} ?> type="radio" name="qualite" id="qualite_s" value="2" class="radiobox" <?php if (isset($res_cmd["cmd_typefuel"])) {
														  if ($res_cmd["cmd_typefuel"] == "2") {
															  echo "checked='checked'";
														  }
													  } ?>>
													<label for="qualite_s" class="for-radiobox">Fioul supérieur</label>
												</div>
											</div>
											<div class="col-sm align-self-center">
												<div class="box-commande form-inline">
													<label for="quantite" class="col-sm-5" style="margin-right:20px;">Quantité</label>
													<div class="col-sm-7" style="padding:0">
														<input <?php if (isset($res_cmd["cmd_status"])) {
															if ($res_cmd["cmd_status"] != '12' && $res_cmd["cmd_status"] != '13') {
																echo 'onchange="myFunction(this.value)"';
															}
														} ?> type="number" name="quantite"
															value="<?php if (isset($res_cmd["cmd_qte"])) {
																if ($res_cmd["cmd_qte"] == '0') {
																	echo "1000";
																} else {
																	echo $res_cmd["cmd_qte"];
																}
															} elseif (isset($_POST["quantite"])) {
																echo $_POST["quantite"];
															} else {
																echo "1000";
															} ?>" class="form-control">
													</div>
												</div>
											</div>
											<div class="col-sm-3">

											</div>

										</div>

									<?php           	 // Si une commande existe déjà on affiche la possibilité de pouvoir la modifier
												}
												if (isset($res_cmd[0])) {
													if ($res_cmd["cmd_status"] == '12' || $res_cmd["cmd_status"] == '13') {
														if (empty($jjj_users["name"]) || empty($pmp_utilisateur["prenom"]) || empty($pmp_utilisateur["adresse"]) || empty($pmp_utilisateur["ville"]) || empty($pmp_utilisateur["code_postal"]) || empty($pmp_utilisateur["tel_fixe"]) || empty($jjj_users["email"])) {
															?>
												<div class="toast info">
													<div class="message-icon info-icon">
														<i class="fas fa-info"></i>
													</div>
													<div class="message-content ">
														<div class="message-type" style="text-align:left;">
															Avertissement
														</div>
														<div class="message" style="text-align:left;">
															Merci de renseigner vos nom, prénom, adresse complète et numéro de téléphone en
															cliquant sur le bouton "Modifier mes coordonnées", pour participer à un groupement
														</div>
													</div>
												</div>
												<?php
														} else {
															?>
												<input type="submit" name="joindre_commande" class="btn btn-primary" value="Joindre ma commande"
													style="margin-top:2%;">
												<?php
														}
													}
													// elseif($res_cmd["cmd_status"] != '25' && $res_cmd["cmd_status"] != '20' && $res_cmd["cmd_status"] != '30')
													// {
													?>
										<!-- <input type="submit" name="mod_commande" class="btn btn-primary" value="Appliquer les modifications" style="margin-top:2%;"> -->
										<?php
										// }
										?>

										<?php
										if ($res_cmd["cmd_status"] == '17' || $res_cmd["cmd_status"] == '12' || $res_cmd["cmd_status"] == '15' || $res_cmd["cmd_status"] == '13') {
											if (empty($jjj_users["name"]) || empty($pmp_utilisateur["prenom"]) || empty($pmp_utilisateur["adresse"]) || empty($pmp_utilisateur["ville"]) || empty($pmp_utilisateur["code_postal"]) || empty($pmp_utilisateur["tel_fixe"]) || empty($jjj_users["email"])) {
												?>

												<?php
											} else {
												if ($res_cmd['cmd_prix_ord'] > 0 || $res_cmd['cmd_prix_sup'] > 0) {
													?>
													<div class="cmd_comment" id="show_valider">
														<p class="titre-bloc">Conditions de livraison de votre commande</p>
														<hr class="separe">
														<p class="text-center">Merci de nous donner dans cet encart les informations utiles pour
															votre livraison (commande urgente, petit camion,...).<br>
															Si vous souhaitez <a href="contacter_poemop.php">nous contacter</a>, envoyez-nous un
															mail à l'adresse <a href="mailto:info@poemop.fr">info@poemop.fr</a></p>
														<textarea name="cmd_comment" class="form-control" rows="8" cols="80"
															style="width:70%;margin:0 auto;"></textarea>

													</div>
													<div class="action_tarif">
														<input type="submit" name="accepter_tarif" class="btn btn-primary"
															value="Accepter le tarif">
														<a href="ma_commande.php?refuser_tarif=ok" class="btn btn-secondary"> Refuser le tarif</a>
													</div>
													<?php
												}
											}
										}
												} // Sinon on affiche le bouton creer une commande
												else {
													?>
										<?php               // Si les informations de l'utilisateur sont incomplètes on affiche le message d'avertissement
														if (empty($jjj_users["name"]) || empty($pmp_utilisateur["prenom"]) || empty($pmp_utilisateur["adresse"]) || empty($pmp_utilisateur["ville"]) || empty($pmp_utilisateur["code_postal"]) || empty($pmp_utilisateur["tel_fixe"]) || empty($jjj_users["email"])) {
															?>
											<div class="toast info">
												<div class="message-icon info-icon">
													<i class="fas fa-info"></i>
												</div>
												<div class="message-content ">
													<div class="message-type" style="text-align:left;">
														Avertissement
													</div>
													<div class="message" style="text-align:left;">
														Merci de renseigner vos nom, prénom, adresse complète et numéro de téléphone en
														cliquant sur le bouton "Modifier mes coordonnées", pour participer à un groupement
													</div>
												</div>
											</div>
											<?php
														} else {
															?>
											<input type="submit" name="creer_commande" class="btn btn-primary" value="Créer ma commande"
												style="margin-top:2%;">
											<?php
														}
												}
												?>

								</form>
								<?php
						}
						?>
						</div>
						<?php
						if (isset($res_cmd[0])) {
							if ($res_cmd['cmd_status'] == 10 || $res_cmd['cmd_status'] == 12 || $res_cmd['cmd_status'] == 13 || $res_cmd['cmd_status'] == 15) {
								?>
								<form method="post">
									<input type="submit" name="supp_commande" class="btn btn-secondary" value="Supprimer ma commande"
										style="margin:2%;">
								</form>
								<?php
							}
						}
						?>

						<div class="coordonnees">
							<h2>Mes coordonnées</h2>
							<div class="ligne-center <?php if (isset($res_cmd[0])) {
								echo "jaune";
							} else {
								echo "orange";
							} ?>">
							</div>

							<div class="rappel" id="modifier_coordonnees">
								<div class="details">
									<p> <span>Nom :</span> <?php if (isset($jjj_users["name"])) {
										echo $jjj_users["name"];
									} ?>
									</p>
									<p> <span>Prenom :</span>
										<?php if (isset($pmp_utilisateur["prenom"])) {
											echo $pmp_utilisateur["prenom"];
										} ?>
									</p>
									<p> <span>Adresse :</span>
										<?php if (isset($pmp_utilisateur["adresse"])) {
											echo $pmp_utilisateur["adresse"];
										} ?>
									</p>
									<p> <span>Ville :</span>
										<?php if (isset($pmp_utilisateur["ville"])) {
											echo $pmp_utilisateur["ville"];
										} ?>
									</p>
									<p> <span>Cp :</span>
										<?php if (isset($pmp_utilisateur["code_postal"])) {
											echo $pmp_utilisateur["code_postal"];
										} ?>
									</p>
									<p> <span>Téléphone :</span>
										<?php if (isset($pmp_utilisateur["tel_fixe"])) {
											echo $pmp_utilisateur["tel_fixe"];
										} ?>
									</p>
									<p> <span>Email :</span>
										<?php if (isset($jjj_users["email"])) {
											echo $jjj_users["email"];
										} ?>
									</p>
								</div>
							</div>
							<div class="block mon-compte">
								<a href="mon_compte.php?type=fioul" class="btn btn-secondary">Modifier mes coordonnées</a>
							</div>
							<?php               // Si les informations de l'utilisateur sont incomplètes on affiche le message d'avertissement
									if (empty($pmp_utilisateur["adresse"]) || empty($pmp_utilisateur["ville"]) || empty($pmp_utilisateur["code_postal"]) || empty($pmp_utilisateur["tel_fixe"]) || empty($jjj_users["email"])) {
										?>
								<div class="precisions pre1">
									<p>Votre inscription sur le site POEMOP a bien été prise en compte. Seulement nous avons besoin
										de vos coordonnées complètes pour vous proposer des groupements sur votre secteur et ainsi
										vous faire faire des économies sur votre facture de fioul. Merci de renseigner vos nom,
										prénom, adresse complète et numéro de téléphone</p>
									<p>Vous pouvez également nous <a href="/contacter_poemop.php"
											title="Contacter Poemop">transmettre ces éléments par mail ou par téléphone</a>, nous
										les complèterons pour vous.</p>
								</div>
								<?php
									} elseif (isset($res_cmd[0])) {
										?>

								<?php
								AfficheInfosGroupement($co_pmp, $regroupement, $status_calcule);
								if ($res_cmd['cmd_status'] == 10) {
									?>
									<div class="precisions pre1" style="margin-top:2%;">
										<p>Votre intention de commande a bien été prise en compte, mais vous n'êtes pas encore inscrit à
											un groupement. Nous allons essayer de réunir d'autres personnes sur votre secteur. N'hésitez
											pas également à en parler autour de vous.</p>
										<p>Nous vous préviendrons par mail lorsqu'un groupement sera lancé. Afin de vous assurer la
											bonne réception de nos mails avec nos meilleurs tarifs, nous vous recommandons fortement de
											nous ajouter à votre carnet d'adresses dans votre boîte mail.</p>
										<p>Vous devrez confirmer votre participation au groupement. Nous vous rappelons que le service
											rendu par POEMOP est gratuit et que la participation à un groupement ne vous engage en rien.
										</p>
										<p>Après négociations avec différents fournisseurs locaux, vous recevrez par mail la proposition
											de tarif. Vous pourrez alors librement l'accepter ou la refuser. Vous pourrez modifier la
											quantité et la qualité que vous souhaitez commander au moment de la validation.</p>
										<p>Le tarif qui vous sera proposé sera TTC livré et sera maintenu jusqu'à la livraison.</p>
									</div>
									<!-- <form method="post">
						<input type="submit" name="supp_commande" class="btn btn-secondary" value="Supprimer ma commande" style="margin:2%;">
					</form> -->
									<?php
								}
								if ((($res_cmd['cmd_status'] == 12) && ($status_calcule == 12)) || (($res_cmd['cmd_status'] == 13) && ($status_calcule == 13))) {
									?>
									<div class="precisions pre2" style="margin:2% 0 2%;">
										<p>
											<b>ATTENTION !</b><br>
											Pour recevoir l'offre de prix, vous devez patienter le temps que nous négocions avec les
											fournisseurs<br>
											En cliquant sur "Supprimer ma commande", vous sortirez du groupement et <u>vous ne recevrez
												pas l'offre de prix</u>.
										</p>

										<!-- <form method="post">
							<input type="submit" name="supp_commande" class="btn btn-secondary" value="Supprimer ma commande">
						</form> -->
									</div>
									<?php
								}
								if (($res_cmd['cmd_status'] == 15) && ($status_calcule == 15)) {
									?>
									<div class="precisions pre2" style="margin:2% 0 2%;">
										<p>
											<b>ATTENTION !</b><br>
											Pour recevoir l'offre de prix, vous devez patienter le temps que nous négocions avec les
											fournisseurs<br>
											En cliquant sur "Supprimer ma commande", vous sortirez du groupement et <u>vous ne recevrez
												pas l'offre de prix</u>.<br>

										</p>

										<!-- <form method="post">
							<input type="submit" name="supp_commande" class="btn btn-secondary" value="Supprimer ma commande" style="margin-bottom:2%;"><br>
							<input type="submit" name="prochain_groupement" class="btn btn-primary" value="Attendre le prochain groupement">
						</form> -->
									</div>
									<?php
								}
								?>
								<?php
									} else {
										?>
								<div class="precisions pre1">
									<p>Vous n'avez saisi aucune intention de commande. Si vous êtes intéressé par une commande de
										fioul, merci de saisir les qualité et quantité désirées afin que nous puissions initier un
										groupement ou le cas échéant, vous inscrire au groupement en cours sur votre secteur. Ces
										éléments seront modifiables au moment de la validation de commande. Merci de vous rapprocher
										au mieux du volume livrable ; pour cela vous devez estimer le volume disponible dans votre
										cuve.</p>
									<p>Les inscrits au groupement reçoivent une proposition de tarif qu'ils peuvent librement
										accepter ou refuser. Nous vous rappelons que le service rendu par POEMOP est gratuit et que
										le paiement se fait à la livraison (aucun paiement en ligne).</p>
								</div>
								<?php
									}
									?>
						</div>
						<?php
						// }
						if ($num_commandes > 0) {
							?>
							<div style="margin-top:2%;">
								<h2>Mes anciennes commandes</h2>
								<div class="ligne-center orange"></div>
								<div class="tableau-responsive">
									<table class="table table-custom">
										<thead>
											<tr>
												<th>Date</th>
												<th>Commande</th>
												<th>Prix</th>
												<th style="width: 15%;">Statut</th>
											</tr>
										</thead>
										<tbody>
											<?php
											while ($commandes = mysqli_fetch_array($res_commandes)) {
												$prixLitre = "-";
												$cmd_details = "-";

												if (($commandes['cmd_typefuel'] == '1') && ($commandes['cmd_prix_ord'] != "") && ($commandes['cmd_prix_ord'] != 0)) {
													$prixLitre = number_format($commandes['cmd_prix_ord'] / 1000, 3, ",", " ");
													$cmd_details = $commandes['cmd_qtelivre'] . " Litres de fioul ordinaire";
												} elseif (($commandes['cmd_typefuel'] == '2') && ($commandes['cmd_prix_sup'] != "") && ($commandes['cmd_prix_sup'] != 0)) {
													$prixLitre = number_format($commandes['cmd_prix_sup'] / 1000, 3, ",", " ");
													$cmd_details = $commandes['cmd_qtelivre'] . " Litres de fioul supérieur";
												}
												?>
												<tr>
													<td><?= date_format(new DateTime($commandes['cmd_dt']), 'd/m/Y'); ?></td>
													<td><?= $cmd_details; ?></td>
													<td><?= $prixLitre . "€/L"; ?></td>
													<td><img src="images/statut_30.svg" alt="commande livrée et Terminée"
															style="width: 33%;"></td>
												</tr>
												<?php
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
							<?php
						}
					}
					?>
				</div>
				<div class="col-sm-3 resp">
					<?php
					include 'modules/connexion.php';
					include 'modules/activites.php';
					// include 'modules/avis_clients.php';
					?>
				</div>
			</div>
		</div>
	</div>

	<?php
} else {
	include 'modules/menu_fioul.php';
	?>
	<div class="container-fluid">
		<div class="header commandes">
			<div class="row">
				<div class="col-sm-9 text-center">
					<h1>Ma commande de fioul domestique</h1>
					<div class="ligne-center jaune"></div>
					<a href="comment_ca_marche_fioul.php" class="text-right"
						title="Groupement de fioul domestique POEMOP"><i class="far fa-lightbulb"></i> Comment ça marche
						?</a>
					<div class="toast info">
						<div class="message-icon info-icon">
							<i class="fas fa-info" style="padding: 22% 39%!important;"></i>
						</div>
						<div class="message-content ">
							<div class="message-type" style="text-align:left;">
								Avertissement
							</div>
							<div class="message" style="text-align:left;">
								Vous n'êtes pas connecté. Veuillez vous connecter pour accéder à cette page.
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-3 resp">
					<?php
					include 'modules/connexion.php';
					include 'modules/activites.php';
					// include 'modules/avis_clients.php';
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
$content = ob_get_clean();
require('template.php');
?>
<script>
	function myFunction(val) {
		console.log("Entered Value is: " + val);
		var frm = document.getElementById("FormID");

		frm.submit();
	}
	// $(document).ready(function() {
	// 	$('input[name="qualite"]').change(function(){
	// 		if($(this).val()) {
	// 			qualite =  $(this).val();
	// 			$.ajax({
	// 				method: 'POST',
	// 				url: 'inc/pmp_ajax_mod_commande.php',
	// 				dataType : 'html',
	// 				data: {
	// 					qualite: qualite, //valeur de la checkbox cliqué
	// 				},
	// 			});
	// 		}
	// 	});
	// });
</script>