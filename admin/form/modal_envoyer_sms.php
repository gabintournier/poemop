<?php
if(isset($_GET["popup_sms"]))
{
$res_sms = getSmsType($co_pmp);

if(!empty($_POST["charger_tel"]))
{
	$message = getSmsMessage($co_pmp, $_POST["choix_sms"]);

	$statut1 = $_POST["sms_statut_1"];
	$statut2 = $_POST["sms_statut_2"];
	if($statut2 == 0) { $statut2 = $statut1; }

	$tel = "";
	$mail = "";
	$res_tel = getCommandesGroupements($co_pmp, $_GET["id_grp"]);
	while($cmdes = mysqli_fetch_array($res_tel))
	{
		if($cmdes["cmd_status"] >= $statut1 && $cmdes["cmd_status"] <= $statut2)
		{
			$tel_fixe = $cmdes["tel_fixe"];
			$tel_port = $cmdes["tel_port"];
			$tel1 = str_replace(".","",$tel_fixe);
			$tel2 = str_replace(".","",$tel_port);
			$code_syntaxe = '#^0[6-7]([-. ]?[0-9]{2}){4}$#';
			if(preg_match($code_syntaxe, $tel1))
			{
			 	$tel .= $tel1 .";";
			}
			elseif(preg_match($code_syntaxe, $tel2))
			{
					$tel .= $tel2 .";";
			}
			else
			{
				$tel .= "";
				$mail .= $cmdes["email"].";";
			}
		}
	}
}

if(!empty($_POST["envoyer_sms"]))
{
	if($_POST["tel_sms"] != "" && $_POST["message"] != "")
	{
		$statut1 = $_POST["sms_statut_1"];
		$statut2 = $_POST["sms_statut_2"];
		if($statut2 == 0) { $statut2 = $statut1; }

		$message = mysqli_real_escape_string($co_pmp, $_POST["message"]);
		$tel = explode(";", $_POST["tel_sms"]);
		if(isset($_POST["priorite"])) { $priorite = $_POST["priorite"]; } else { $priorite = "2"; }
		$expediteur = "0770121596";
		foreach($tel as $numTel)
		{
		   $tel_p = wordwrap($numTel, 2, '.', TRUE);
		   if($tel_p != '')
		   {
		       $query = " SELECT user_id FROM pmp_utilisateur 
		                  WHERE tel_fixe = '$tel_p' 
		                     OR tel_port = '$tel_p' 
		                     OR tel_3 = '$tel_p' ";
		       $res = my_query($co_pmp, $query);
		       $user_id = mysqli_fetch_array($res);

		       if($user_id["user_id"] > 0 && $user_id["user_id"] != '63')
		       {
		           $user_id = $user_id["user_id"];
		           $cmd = getCommandeUtilisateur($co_pmp, $user_id);
		           $cmd = $cmd["id"];

		           $query = "INSERT INTO pmp_sms 
		                        (id, telephone, message, etat, priorite, date_insertion, date_envoi, expediteur, cmd_id)
		                     VALUES ('', '$numTel', '$message', '0', '$priorite', NOW(), NULL, '$expediteur', '$cmd')";

		           $res = my_query($co_pmp, $query);

		           if($res)
		           {
		               TraceHisto($co_pmp, $cmd, 'SMS envoyé', $_POST["message"]);
		               $succes_type = "success";
		               $succes_icone = "fa-check";
		               $succes_titre = "Succès";
		               $succes = "Les SMS ont bien été envoyés.";
		           }
		       }
		   }
		}
		// TraceHistoGrpt($co_pmp, $_GET['id_grp'], 'SMS ' . $_POST["message"]  , 'Statut ' . $statut1 . ' -> ' . $statut2);
	}

}
?>
<div class="modal fade" id="selSms" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" style="max-width: 45%;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Choix SMS</h5>
				<button type="button" class="btn-close b-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fas fa-times"></i> </button>
			</div>
			<div class="modal-body" style="text-align: left;">
<?php
				if(isset($succes))
				{
?>
				<div class="toast <?= $succes_type; ?>" style="margin: 4px 0 17px">
					<div class="message-icon  <?= $succes_type; ?>-icon">
						<i class="fas <?= $succes_icone; ?>"></i>
					</div>
					<div class="message-content ">
						<div class="message-type">
							<?= $succes_titre; ?>
						</div>
						<div class="message">
							<?= $succes; ?>
						</div>
					</div>
					<div class="message-close">
						<i class="fas fa-times"></i>
					</div>
				</div>
<?php
				}
?>
				<label class="label-title" style="margin: 0;">Critère d'envoi SMS</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-8">
						<fieldset>
    						<legend>Choix SMS</legend>
							<div class="form-inline" style="margin: 2% 0 0 0;">
								<label for="choix_sms" class="col-sm-3 col-form-label" style="padding-left:0;">SMS à envoyer</label>
								<div class="col-sm-9" style="padding:0">
									<select class="form-control input-custom" name="choix_sms" style="width:100%;">
										<option value="0"></option>
<?php
										while($sms_type = mysqli_fetch_array($res_sms))
										{
?>
										<option value="<?= $sms_type["id"]; ?>" <?php if(isset($_POST["choix_sms"])) { if($_POST['choix_sms'] == $sms_type["id"]){ echo "selected='selected'"; } } ?>><?= $sms_type["nom"]; ?></option>
<?php
										}
?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label for="sms_statut_1" class="col-form-label" style="padding-left:0;">A partir du statut</label>
									<select class="form-control" name="sms_statut_1">
										<option value="10" <?php if(isset($_POST["sms_statut_1"])) { if($_POST['sms_statut_1'] == '10'){ echo "selected='selected'"; } } ?>>Utilisateur</option>
										<option value="12" <?php if(isset($_POST["sms_statut_1"])) { if($_POST['sms_statut_1'] == '12'){ echo "selected='selected'"; } } ?>>Attachée</option>
										<option value="13" <?php if(isset($_POST["sms_statut_1"])) { if($_POST['sms_statut_1'] == '13'){ echo "selected='selected'"; } } ?>>Proposée</option>
										<option value="15" <?php if(isset($_POST["sms_statut_1"])) { if($_POST['sms_statut_1'] == '15'){ echo "selected='selected'"; } } ?>>Groupée</option>
										<option value="17" <?php if(isset($_POST["sms_statut_1"])) { if($_POST['sms_statut_1'] == '17'){ echo "selected='selected'"; } } ?>>Prix proposé</option>
										<option value="20" <?php if(isset($_POST["sms_statut_1"])) { if($_POST['sms_statut_1'] == '20'){ echo "selected='selected'"; } } ?>>Prix validé</option>
										<option value="25" <?php if(isset($_POST["sms_statut_1"])) { if($_POST['sms_statut_1'] == '25'){ echo "selected='selected'"; } } ?>>Livrable</option>
										<option value="30" <?php if(isset($_POST["sms_statut_1"])) { if($_POST['sms_statut_1'] == '30'){ echo "selected='selected'"; } } ?>>Livrée</option>
										<option value="40" <?php if(isset($_POST["sms_statut_1"])) { if($_POST['sms_statut_1'] == '40'){ echo "selected='selected'"; } } ?>>Terminée</option>
										<option value="50" <?php if(isset($_POST["sms_statut_1"])) { if($_POST['sms_statut_1'] == '50'){ echo "selected='selected'"; } } ?>>Annulée</option>
										<option value="52" <?php if(isset($_POST["sms_statut_1"])) { if($_POST['sms_statut_1'] == '52'){ echo "selected='selected'"; } } ?>>Annulée / Livraison</option>
										<option value="55" <?php if(isset($_POST["sms_statut_1"])) { if($_POST['sms_statut_1'] == '55'){ echo "selected='selected'"; } } ?>>Annulée / Prix</option>
									</select>
								</div>
								<div class="col-sm-6">
									<label for="sms_statut_2" class="col-form-label" style="padding-left:0;">Jusqu'au statut </label>
									<select class="form-control" name="sms_statut_2">
										<option value="0"></option>
										<option value="10" <?php if(isset($_POST["sms_statut_2"])) { if($_POST['sms_statut_2'] == '10'){ echo "selected='selected'"; } } ?>>Utilisateur</option>
										<option value="12" <?php if(isset($_POST["sms_statut_2"])) { if($_POST['sms_statut_2'] == '12'){ echo "selected='selected'"; } } ?>>Attachée</option>
										<option value="13" <?php if(isset($_POST["sms_statut_2"])) { if($_POST['sms_statut_2'] == '13'){ echo "selected='selected'"; } } ?>>Proposée</option>
										<option value="15" <?php if(isset($_POST["sms_statut_2"])) { if($_POST['sms_statut_2'] == '15'){ echo "selected='selected'"; } } ?>>Groupée</option>
										<option value="17" <?php if(isset($_POST["sms_statut_2"])) { if($_POST['sms_statut_2'] == '17'){ echo "selected='selected'"; } } ?>>Prix proposé</option>
										<option value="20" <?php if(isset($_POST["sms_statut_2"])) { if($_POST['sms_statut_2'] == '20'){ echo "selected='selected'"; } } ?>>Prix validé</option>
										<option value="25" <?php if(isset($_POST["sms_statut_2"])) { if($_POST['sms_statut_2'] == '25'){ echo "selected='selected'"; } } ?>>Livrable</option>
										<option value="30" <?php if(isset($_POST["sms_statut_2"])) { if($_POST['sms_statut_2'] == '30'){ echo "selected='selected'"; } } ?>>Livrée</option>
										<option value="40" <?php if(isset($_POST["sms_statut_2"])) { if($_POST['sms_statut_2'] == '40'){ echo "selected='selected'"; } } ?>>Terminée</option>
										<option value="50" <?php if(isset($_POST["sms_statut_2"])) { if($_POST['sms_statut_2'] == '50'){ echo "selected='selected'"; } } ?>>Annulée</option>
										<option value="52" <?php if(isset($_POST["sms_statut_2"])) { if($_POST['sms_statut_2'] == '52'){ echo "selected='selected'"; } } ?>>Annulée / Livraison</option>
										<option value="55" <?php if(isset($_POST["sms_statut_2"])) { if($_POST['sms_statut_2'] == '55'){ echo "selected='selected'"; } } ?>>Annulée / Prix</option>
									</select>
								</div>
								<div class="col-sm-12 text-right" style="margin-top: 10px;">
									<input type="submit" name="charger_tel" class="btn btn-warning" value="CHARGER">
								</div>
							</div>
						</fieldset>
					</div>
					<div class="col-sm-4">
						<label for="sans_mobile" class="col-form-label" style="padding-left:0;margin-top:0">Client Sans Mobile </label>
						<textarea name="sans_mobile" id="sans_mobile" class="form-control" rows="2" style="height: 158px;"><?php if(isset($mail)) { echo $mail; } ?></textarea>
					</div>
				</div>
				<hr>
				<label class="label-title" style="margin: 0;">Envoyer SMS</label>
				<div class="ligne"></div>
				<fieldset>
					<legend>SMS</legend>
					<div class="row">
						<div class="col-sm-10">
							<label for="tel_sms" class="col-form-label" style="padding-left:0;margin-top:0">Numéro de téléphone</label>
							<textarea name="tel_sms" id="tel_sms" class="form-control" rows="2" style="height: 100px;"><?php if(isset($tel)) { echo $tel; } ?></textarea>
						</div>
						<div class="col-sm-2 align-self-end">
							<label for="priorite" class="col-form-label" style="padding-left:0;margin-top:0">Priorité</label><br>
							<label for="priorite" class="col-form-label" style="padding: 1.5%;">
								<input type="radio" name="priorite" id="priorite" class="switch value check" value="haute" style="width: 14px;">
								Haute
							</label><br>
							<label for="priorite" class="col-form-label" style="padding: 1.5%;">
								<input checked="checked" type="radio" name="priorite" id="priorite" class="switch value check" value="normal"  style="width: 14px;">
								Normale
							</label><br>
							<label for="priorite" class="col-form-label" style="padding: 1.5%;">
								<input type="radio" name="priorite" id="priorite" class="switch value check" value="lente" style="width: 14px;">
								Lente
							</label><br>
						</div>
					</div>
					<label for="message" class="col-form-label" style="padding-left:0;margin-top:0">Message</label>
					<textarea name="message" id="message" class="form-control" rows="2" style="height: 120px;"><?php if(isset($message["message"])) { echo $message["message"]; } ?></textarea>
					<div class="row">
						<div class="col-sm-12 text-center" style="margin-top:10px">
							<input type="submit" name="envoyer_sms" value="Envoyer SMS" class="btn btn-primary">
						</div>
					</div>
				</fieldset>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary b-close" data-bs-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<?php
}
?>
