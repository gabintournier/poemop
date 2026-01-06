<?php
$res_mail = null;
if (isset($_GET["popup_pmp"])) {
    $res_mail = getMailModele($co_pmp);

    // On charge toujours le mail sélectionné pour garder le sujet cohérent
    if (!empty($_POST["choix_mail_m"])) {
        $mail = getDetailsMailModele($co_pmp, $_POST["choix_mail_m"]);
    }

        if (!empty($_POST["charger_mail"])) {
            $res_mc = getMotsCles($co_pmp, $_POST["choix_mail_m"]);
            $mail = getDetailsMailModele($co_pmp, $_POST["choix_mail_m"]);

            $statut1 = isset($_POST["mail_statut_1"]) ? intval($_POST["mail_statut_1"]) : 10;
            $statut2 = isset($_POST["mail_statut_2"]) ? intval($_POST["mail_statut_2"]) : 0;
            if ($statut2 === 0) {
                $statut2 = $statut1;
            }
            $statut_min = min($statut1, $statut2);
            $statut_max = max($statut1, $statut2);

        $mail_client = "";
        $prix = "";
        $volume = "";
        $res_cmd = getCommandesGroupementsEnvoisMail($co_pmp, $_GET["id_grp"]);

        while ($cmdes = mysqli_fetch_array($res_cmd)) {
            if ($cmdes["cmd_status"] >= $statut_min && $cmdes["cmd_status"] <= $statut_max) {
                $mail_client .= $cmdes["email"] . ";";
            }
        }

        $res_plg = getPlagePrix($co_pmp, $_GET["id_grp"]);
        while ($plages = mysqli_fetch_array($res_plg)) {
            $prix .= $plages["prix_ord"] . ";";
            $prix .= $plages["prix_sup"] . ";";
            $volume .= $plages["volume"] . ";";
        }
    }

    if (!empty($_POST["envoyer_mail"])) {
        if (isset($_POST["mail_id"])) {
            $statut1 = isset($_POST["mail_statut_1"]) ? intval($_POST["mail_statut_1"]) : 10;
            $statut2 = isset($_POST["mail_statut_2"]) ? intval($_POST["mail_statut_2"]) : 0;
            if ($statut2 === 0) {
                $statut2 = $statut1;
            }
            $statut_min = min($statut1, $statut2);
            $statut_max = max($statut1, $statut2);

            $mail_id = $_POST["mail_id"];
            $dest_mail = $_POST["dest_mail"];
            $chaine = "";

            $d = date("Y-m-d");
            $date = date_format(new DateTime($d), 'Y-m-d H:i:s');

            for ($i = 0; $i < $_POST['nb_mot_cle']; $i++) {
			    $valeur_mots_cles = $_POST["valeur_mots_cle"];
			    $tmp = 'mots_cle_' . $i;
			    $mots_cles = $_POST[$tmp];
			    $valeur = $valeur_mots_cles[$i];
						
			    // Pas d'escape ici : on escape tout à la fin une seule fois
			    $chaine .= $mots_cles . "|" . $valeur . "\n";
			}
			
			if (isset($_POST["info_fournisseur"])) {
			    $chaine .= "[info_fournisseur]|" . $_POST["info_fournisseur"];
			}
			
			// Échappement global à la fin seulement
			$chaine = mysqli_real_escape_string($co_pmp, $chaine);

            $objetMailFinal = $_POST["objet_mail"] ?? ($mail["sujet"] ?? '');

            $res_cmd = getCommandesGroupementsEnvoisMail($co_pmp, $_GET["id_grp"]);
			while ($cmdes = mysqli_fetch_array($res_cmd)) {
			    if ($cmdes["cmd_status"] >= $statut_min && $cmdes["cmd_status"] <= $statut_max) {
			        TraceHisto($co_pmp, $cmdes["id_cmd"], 'Mail envoyé', $objetMailFinal);
			    }
			}

            TraceHistoGrpt($co_pmp, $_GET['id_grp'], 'Mail ' . $objetMailFinal, 'Statut ' . $statut_min . ' -> ' . $statut_max);

            $email = explode(";", $dest_mail);
            foreach ($email as $listedMails) {
                if (strlen($listedMails) > 0) {
                    $listedMails = trim($listedMails);
                    $listedMailsSafe = mysqli_real_escape_string($co_pmp, $listedMails);

                    $checkSql = "
                        SELECT disabled_account 
                        FROM pmp_utilisateur 
                        WHERE email = '$listedMailsSafe' 
                        LIMIT 1
                    ";
                    $checkRes = mysqli_query($co_pmp, $checkSql);
                    if ($checkRes && $checkRow = mysqli_fetch_assoc($checkRes)) {
                        if (!empty($checkRow['disabled_account']) && $checkRow['disabled_account'] == 1) {
                            error_log("⚠️ Insertion mail ignorée (compte désactivé) : $listedMails");

                            $prefix = (strpos($_SERVER['HTTP_HOST'] ?? '', 'dev') !== false) ? "[ENV. DEV] - " : "";
                            @mail(
                                "erreur@prixfioul.fr",
                                $prefix . "Insertion mail bloquée (compte désactivé)",
                                "Un mail n'a pas été inséré dans pmp_mail_auto car le compte est désactivé.\n\nAdresse : $listedMails\nMail : " . $objetMailFinal
                            );
                            continue;
                        }
                    }

                    $query = "INSERT INTO pmp_mail_auto (
                        id, user_id, modele_id, destinataires, etat, priorite, date_insertion, date_a_envoyer, date_action, chaine_cle
                    ) VALUES (
                        '', NULL, '$mail_id', '$listedMailsSafe', 'A', '2', NOW(), NOW(), NULL, '$chaine'
                    )";
                    $res = my_query($co_pmp, $query);

                    if ($res) {
                        $message_type = "success";
                        $message_icone = "fa-check";
                        $message_titre = "Succès";
                        $message = "Les mails ont bien été envoyés.";
                    } else {
                        $message_type = "danger";
                        $message_icone = "fa-times";
                        $message_titre = "Erreur";
                        $message = "Erreur SQL : $query";
                    }
                }
            }
        }
    }
?>
<div class="modal fade" id="selPmp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" style="max-width: 60%;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Choix Mail</h5>
				<button type="button" class="btn-close b-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fas fa-times"></i> </button>
			</div>
			<?php
			if (isset($message))
			{
			?>
			<div class="toast <?= $message_type; ?>" style="margin: 18px 17px -2px;text-align: left;">
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
			<div class="modal-body" style="text-align: left;">
				<label class="label-title" style="margin: 0;">Critère d'envoi du Mail</label>
				<div class="ligne"></div>
				<fieldset>
					<legend>Génération Mail</legend>
					<div class="row">
						<div class="col-sm-5">
							<label for="choix_mail_m" class="col-form-label" style="padding-left:0;">Choix du mail à envoyer</label>
							<select class="form-control input-custom" name="choix_mail_m" style="width:100%;">
								<option value=""></option>
<?php
								while($mail_modele = mysqli_fetch_array($res_mail))
								{
?>
								<option value="<?= $mail_modele["id"]; ?>" <?php if(isset($_POST["choix_mail_m"])) { if($_POST['choix_mail_m'] == $mail_modele["id"]){ echo "selected='selected'"; } } ?>><?= $mail_modele["nom_fichier"]; ?></option>
<?php
								}
?>
							</select>
							<label class="col-form-label text-center" style="padding-left:0;display: block;margin-top: 15px;color: #0b2424a6;">Choix du statut des commandes concernées</label>
							<label for="mail_statut_1" class="col-form-label" style="padding-left:0;">À partir du statut</label>
							<select class="form-control" name="mail_statut_1">
								<option value="10" <?php if(isset($_POST["mail_statut_1"])) { if($_POST['mail_statut_1'] == '10'){ echo "selected='selected'"; } } ?>>Utilisateur</option>
								<option value="12" <?php if(isset($_POST["mail_statut_1"])) { if($_POST['mail_statut_1'] == '12'){ echo "selected='selected'"; } } ?>>Attachée</option>
								<option value="13" <?php if(isset($_POST["mail_statut_1"])) { if($_POST['mail_statut_1'] == '13'){ echo "selected='selected'"; } } ?>>Proposée</option>
								<option value="15" <?php if(isset($_POST["mail_statut_1"])) { if($_POST['mail_statut_1'] == '15'){ echo "selected='selected'"; } } ?>>Groupée</option>
								<option value="17" <?php if(isset($_POST["mail_statut_1"])) { if($_POST['mail_statut_1'] == '17'){ echo "selected='selected'"; } } ?>>Prix proposé</option>
								<option value="20" <?php if(isset($_POST["mail_statut_1"])) { if($_POST['mail_statut_1'] == '20'){ echo "selected='selected'"; } } ?>>Prix validé</option>
								<option value="25" <?php if(isset($_POST["mail_statut_1"])) { if($_POST['mail_statut_1'] == '25'){ echo "selected='selected'"; } } ?>>Livrable</option>
								<option value="30" <?php if(isset($_POST["mail_statut_1"])) { if($_POST['mail_statut_1'] == '30'){ echo "selected='selected'"; } } ?>>Livrée</option>
								<option value="40" <?php if(isset($_POST["mail_statut_1"])) { if($_POST['mail_statut_1'] == '40'){ echo "selected='selected'"; } } ?>>Terminée</option>
								<option value="50" <?php if(isset($_POST["mail_statut_1"])) { if($_POST['mail_statut_1'] == '50'){ echo "selected='selected'"; } } ?>>Annulée</option>
								<option value="52" <?php if(isset($_POST["mail_statut_1"])) { if($_POST['mail_statut_1'] == '52'){ echo "selected='selected'"; } } ?>>Annulée / Livraison</option>
								<option value="55" <?php if(isset($_POST["mail_statut_1"])) { if($_POST['mail_statut_1'] == '55'){ echo "selected='selected'"; } } ?>>Annulée / Prix</option>
							</select>
							<label for="mail_statut_2" class="col-form-label" style="padding-left:0;">Jusqu'au statut </label>
							<select class="form-control" name="mail_statut_2">
								<option value="0"></option>
								<option value="10" <?php if(isset($_POST["mail_statut_2"])) { if($_POST['mail_statut_2'] == '10'){ echo "selected='selected'"; } } ?>>Utilisateur</option>
								<option value="12" <?php if(isset($_POST["mail_statut_2"])) { if($_POST['mail_statut_2'] == '12'){ echo "selected='selected'"; } } ?>>Attachée</option>
								<option value="13" <?php if(isset($_POST["mail_statut_2"])) { if($_POST['mail_statut_2'] == '13'){ echo "selected='selected'"; } } ?>>Proposée</option>
								<option value="15" <?php if(isset($_POST["mail_statut_2"])) { if($_POST['mail_statut_2'] == '15'){ echo "selected='selected'"; } } ?>>Groupée</option>
								<option value="17" <?php if(isset($_POST["mail_statut_2"])) { if($_POST['mail_statut_2'] == '17'){ echo "selected='selected'"; } } ?>>Prix proposé</option>
								<option value="20" <?php if(isset($_POST["mail_statut_2"])) { if($_POST['mail_statut_2'] == '20'){ echo "selected='selected'"; } } ?>>Prix validé</option>
								<option value="25" <?php if(isset($_POST["mail_statut_2"])) { if($_POST['mail_statut_2'] == '25'){ echo "selected='selected'"; } } ?>>Livrable</option>
								<option value="30" <?php if(isset($_POST["mail_statut_2"])) { if($_POST['mail_statut_2'] == '30'){ echo "selected='selected'"; } } ?>>Livrée</option>
								<option value="40" <?php if(isset($_POST["mail_statut_2"])) { if($_POST['mail_statut_2'] == '40'){ echo "selected='selected'"; } } ?>>Terminée</option>
								<option value="50" <?php if(isset($_POST["mail_statut_2"])) { if($_POST['mail_statut_2'] == '50'){ echo "selected='selected'"; } } ?>>Annulée</option>
								<option value="52" <?php if(isset($_POST["mail_statut_2"])) { if($_POST['mail_statut_2'] == '52'){ echo "selected='selected'"; } } ?>>Annulée / Livraison</option>
								<option value="55" <?php if(isset($_POST["mail_statut_2"])) { if($_POST['mail_statut_2'] == '55'){ echo "selected='selected'"; } } ?>>Annulée / Prix</option>
							</select>
							<div class="row">
								<div class="col-sm-12 text-align-end text-right">
									<input type="submit" name="charger_mail" class="btn btn-warning" value="GÉNÉRER" style="margin-top:33px;">
								</div>
							</div>
						</div>
						<div class="col-sm-7">
							<div class="tableau" style="margin: 20px 0 5px; height: 300px;">
								<table class="table">
									<thead>
										<th style="width:160px;">Mots-Clés</th>
										<th>Valeur</th>
									</thead>
									<tbody>
<?php
									$i = 0;
									if(!empty($_POST["charger_mail"]) && isset($res_mc))
									{
										$plages_prix = explode(";",$prix);
										$plages_volume = explode(";",$volume);
										while($mots_cles = mysqli_fetch_array($res_mc))
										{
										if($mots_cles["cle"] != "[info_fournisseur]")
										{
											if($mots_cles["cle"] == "[planning_inscription]" && isset($planning["0"])) { $valeur = $planning["0"]; }
											elseif($mots_cles["cle"] == "[planning_prix]" && isset($planning["1"])) { $valeur = $planning["1"]; }
											elseif($mots_cles["cle"] == "[planning_validation]" && isset($planning["2"])) { $valeur = $planning["2"]; }
											elseif($mots_cles["cle"] == "[planning_livraison]" && isset($planning["3"])) { $valeur = $planning["3"]; }
											elseif($mots_cles["cle"] == "[planning_prochain]" && isset($planning["4"])) { $valeur = $planning["4"]; }

											elseif($mots_cles["cle"] == "[prix_ord1]" && isset($plages_prix[0])) { 
											    $valeur = is_numeric($plages_prix[0]) ? $plages_prix[0] / 1000 : 0; 
											    $valeur = number_format($valeur, 3, '.', ''); 
											}
											elseif($mots_cles["cle"] == "[prix_ord2]" && isset($plages_prix[2])) { 
											    $valeur = is_numeric($plages_prix[2]) ? $plages_prix[2] / 1000 : 0; 
											    $valeur = number_format($valeur, 3, '.', ''); 
											}
											elseif($mots_cles["cle"] == "[prix_ord3]" && isset($plages_prix[4])) { 
											    $valeur = is_numeric($plages_prix[4]) ? $plages_prix[4] / 1000 : 0; 
											    $valeur = number_format($valeur, 3, '.', ''); 
											}
											elseif($mots_cles["cle"] == "[prix_ord4]" && isset($plages_prix[6])) { 
											    $valeur = is_numeric($plages_prix[6]) ? $plages_prix[6] / 1000 : 0; 
											    $valeur = number_format($valeur, 3, '.', ''); 
											}

											elseif($mots_cles["cle"] == "[prix_sup1]" && isset($plages_prix[1])) { 
											    $valeur = is_numeric($plages_prix[1]) ? $plages_prix[1] / 1000 : 0; 
											    $valeur = number_format($valeur, 3, '.', ''); 
											}
											elseif($mots_cles["cle"] == "[prix_sup2]" && isset($plages_prix[3])) { 
											    $valeur = is_numeric($plages_prix[3]) ? $plages_prix[3] / 1000 : 0; 
											    $valeur = number_format($valeur, 3, '.', ''); 
											}
											elseif($mots_cles["cle"] == "[prix_sup3]" && isset($plages_prix[5])) { 
											    $valeur = is_numeric($plages_prix[5]) ? $plages_prix[5] / 1000 : 0; 
											    $valeur = number_format($valeur, 3, '.', ''); 
											}
											elseif($mots_cles["cle"] == "[prix_sup4]" && isset($plages_prix[7])) { 
											    $valeur = is_numeric($plages_prix[7]) ? $plages_prix[7] / 1000 : 0; 
											    $valeur = number_format($valeur, 3, '.', ''); 
											}

											elseif($mots_cles["cle"] == "[volume1]" && isset($plages_volume[0])) { $valeur = $plages_volume[0]; }
											elseif($mots_cles["cle"] == "[volume2]" && isset($plages_volume[1])) { $valeur = $plages_volume[1]; }
											elseif($mots_cles["cle"] == "[volume3]" && isset($plages_volume[2])) { $valeur = $plages_volume[2]; }
											elseif($mots_cles["cle"] == "[volume4]" && isset($plages_volume[3])) { $valeur = $plages_volume[3]; }
											elseif($mots_cles["cle"] == "[tel_four]") { $four = getGroupementFour($co_pmp, $_GET["id_grp"]); $valeur = $four["tel_fixe"]; }
											else { $valeur = ""; }

?>
										<tr>
											<td style="padding: 8px 15px;"> <input type="hidden" name="mots_cle_<?= $i++; ?>" value="<?= $mots_cles["cle"]; ?>"> <?= $mots_cles["cle"]; ?></td>
											<td style="padding: 8px 15px;"> <input type="text" name="valeur_mots_cle[]" value="<?= $valeur; ?>" class="form-control" style="width:100%"> </td>
										</tr>
<?php


										}
										}
									}
?>
									</tbody>
								</table>
							</div>
							<input type="hidden" name="nb_mot_cle" value="<?= $i; ?>">
							<label for="info_fournisseur" class="col-form-label" style="padding-left:0;">Info fournisseur</label>
							<?php $chaine=preg_replace("#\n|\t|\r#","",$grp["infofour"]); ?>
							<textarea name="info_fournisseur" id="info_fournisseur" class="form-control" rows="2" style="height: 70px;"><?php if(!empty($_POST["charger_mail"]) && isset($grp["infofour"])){ echo $chaine; } ?></textarea>
						</div>
					</div>
				</fieldset>
				<hr>
				<label class="label-title" style="margin: 0;">Envoyer Mail</label>
				<div class="ligne"></div>
				<fieldset>
					<legend>Mail</legend>
					<div class="row">
						<div class="col-sm-10">
							<label for="dest_mail" class="col-form-label" style="padding-left:0;margin-top:0">Destinataires</label>
							<textarea name="dest_mail" id="dest_mail" class="form-control" rows="2" style="height: 50px;"><?php if(isset($mail_client)) { echo $mail_client; } ?></textarea>
							<label for="objet_mail" class="col-form-label" style="padding-left:0;margin-top:0">Objet</label>
							<?php
							$objet_affiche = $_POST["objet_mail"] ?? ($mail["sujet"] ?? '');
							?>
							<input type="text" name="objet_mail" value="<?= htmlspecialchars($objet_affiche); ?>" class="form-control" style="width:100%;">

						</div>
						<div class="col-sm-2 align-self-end">
							<label for="priorite" class="col-form-label" style="padding-left:0;margin-top:0">Priorité</label><br>
							<label for="priorite" class="col-form-label" style="padding: 1.5%;">
								<input type="radio" name="priorite" id="" class="switch value check" value="haute" style="width: 14px;">
								Haute
							</label><br>
							<label for="priorite" class="col-form-label" style="padding: 1.5%;">
								<input type="radio" name="priorite" id="" class="switch value check" value="normal" checked="checked" style="width: 14px;">
								Normale
							</label><br>
							<label for="priorite" class="col-form-label" style="padding: 1.5%;">
								<input type="radio" name="priorite" id="" class="switch value check" value="lente" style="width: 14px;">
								Lente
							</label><br>
						</div>
						<div class="col-sm-10 text-right">
							<a href="../newsletter/modele/MODELE_<?php if(isset($mail["nom_fichier"])) { echo $mail["nom_fichier"]; } ?>.html" target="_blank" class="btn btn-outline-primary" style="margin-top: 20px;background: #f7f7f7!important;padding: 3px 20px!important;border-radius: 6px!important;font-size: 14px!important;" ><i style="margin-right: 10px;" class="far fa-external-link"></i> VISUALISER</a>
						</div>
						<div class="col-sm-2 text-right">
							<input type="hidden" name="mail_id" value="<?php if(isset($_POST["choix_mail_m"])) { echo $_POST["choix_mail_m"]; } ?>">
							<input type="submit" class="btn btn-primary" name="envoyer_mail" value="ENVOYER" style="margin-top: 20px;width: 148px;">
						</div>
					</div>
				</fieldset>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary b-close" data-bs-dismiss="modal">Fermer</button>
			</div>
<?php
			if (isset($message))
			{
?>
			<div class="toast <?= $message_type; ?>" style="margin: 18px 17px -2px;text-align: left;">
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
		</div>
	</div>
</div>
<?php
    $mailDump = [];
    if ($res_mail instanceof mysqli_result) {
        mysqli_data_seek($res_mail, 0);
        while ($entry = mysqli_fetch_assoc($res_mail)) {
            $mailDump[$entry['id']] = $entry['sujet'];
        }
    }
?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const selectMail = document.querySelector('select[name="choix_mail_m"]');
    const objetInput = document.querySelector('input[name="objet_mail"]');
    const selectData = <?= json_encode($mailDump, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

    if (selectMail && objetInput) {
        selectMail.addEventListener('change', () => {
            objetInput.value = selectData[selectMail.value] || '';
        });
    }
});
</script>
<?php
}
?>
