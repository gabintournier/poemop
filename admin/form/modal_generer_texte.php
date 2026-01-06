<?php
if(isset($_GET["generer_texte"]))
{
if(!empty($_POST["generer_texte_mail"]))
{
	$statut1 = $_POST["generer_statut_1"];
	$statut2 = $_POST["generer_statut_2"];
	if($statut2 == 0) { $statut2 = $statut1; }
	if(isset($_GET["id_grp"]))
	{
		$res_texte = getCommandesGroupements($co_pmp, $_GET["id_grp"]);
	}
	elseif (isset($_SESSION["fournisseur_ajax"]) && isset($_SESSION["zone"]))
	{
		$res_texte = getClientZoneStatus($co_pmp, $_SESSION["zone"], $statut1, $statut2);
	}
	else
	{
		$res_texte = getCommandesStatus($co_pmp, $statut1, $statut2);
	}

	$text = "";
	$text1 = "";
	$text2 = "";

	if(isset($_GET["id_grp"]))
	{
		$ord = getQuantiteVolumeStatusGrp($co_pmp, $_GET["id_grp"], 1, $statut1, $statut2);
		$sup = getQuantiteVolumeStatusGrp($co_pmp, $_GET["id_grp"], 2, $statut1, $statut2);
	}
	elseif (isset($_SESSION["fournisseur_ajax"]) && isset($_SESSION["zone"]))
	{
		$ord = getQuantiteVolumeStatusZone($co_pmp, $_SESSION["zone"], 1, $statut1, $statut2);
		$sup = getQuantiteVolumeStatusZone($co_pmp, $_SESSION["zone"], 2, $statut1, $statut2);
	}
	else
	{
		$ord = getQuantiteVolumeStatus($co_pmp, 1, $statut1, $statut2);
		$sup = getQuantiteVolumeStatus($co_pmp, 2, $statut1, $statut2);
	}

	if($_POST["generer"] == "code_postaux" && $_POST["trie_par"] == "ttype_fioul" || $_POST["generer"] == "corps_message" && $_POST["trie_par"] == "ttype_fioul")
	{
		$text1 .= "Supérieur : " . $sup["qte"] . " \n";
		$text1 .= "\n";
		$text2 .= "Ordinaire : " . $ord["qte"] . " \n";
		$text2 .= "\n";
	}
	elseif($_POST["generer"] == "code_postaux" && $_POST["trie_par"] == "tcode_postal" || $_POST["generer"] == "corps_message" && $_POST["trie_par"] == "tcode_postal")
	{
		$text .= "Supérieur : " . $sup["qte"] . " \n";
		$text .= "Ordinaire : " . $ord["qte"] . " \n";
		$text .= "\n";
		$text .= "\n";
	}


	while($cmdes = mysqli_fetch_array($res_texte))
	{
		if($cmdes["cmd_status"] >= $statut1 && $cmdes["cmd_status"] <= $statut2)
		{
			if($cmdes["tel_port"] == "") { $tel = $cmdes["tel_fixe"]; } else { $tel = $cmdes["tel_port"]; }
			if($cmdes["tel_fixe"] == "") { $tel = $cmdes["tel_port"]; } else { $tel = $cmdes["tel_fixe"]; }

			if($_POST["generer"] == "code_postaux" && $_POST["trie_par"] == "ttype_fioul")
			{
				if($cmdes["cmd_typefuel"] == 2)
				{
					$text1 .= $cmdes["cmd_qte"] . " Litres " .  $cmdes["code_postal"] . " " . $cmdes["ville"] . " " . $cmdes["cmd_commentfour"] . "\n";
				}
				if($cmdes["cmd_typefuel"] == 1)
				{
					$text2 .= $cmdes["cmd_qte"] . " Litres " .  $cmdes["code_postal"] . " " . $cmdes["ville"] . " " . $cmdes["cmd_commentfour"] . "\n";
				}
			}
			elseif($_POST["generer"] == "code_postaux" && $_POST["trie_par"] == "tcode_postal")
			{
				if($cmdes["cmd_typefuel"] == 2)
				{
					$text .= "(sup) " . $cmdes["cmd_qte"] . " Litres " . $cmdes["code_postal"] . " " . $cmdes["ville"] . " " . $cmdes["cmd_commentfour"] . " \n";

				}
				if($cmdes["cmd_typefuel"] == 1)
				{
					$text .= "(ord) " . $cmdes["cmd_qte"] . " Litres " . $cmdes["code_postal"] . " " . $cmdes["ville"] . " " . $cmdes["cmd_commentfour"] . " \n";
				}
			}
			elseif ($_POST["generer"] == "corps_message" && $_POST["trie_par"] == "ttype_fioul")
			{
				if($cmdes["cmd_typefuel"] == 2)
				{
					$text1 .= $cmdes["cmd_qte"] . " Litres " .  $cmdes["name"] . " " . $cmdes["prenom"] . " - " . $cmdes["adresse"] . " " . $cmdes["code_postal"] . " " . $cmdes["ville"] . " " . $cmdes["tel_port"] . " " . $cmdes["tel_fixe"] . " " . $cmdes["cmd_commentfour"] . "\n";
				}
				if($cmdes["cmd_typefuel"] == 1)
				{
					$text2 .= $cmdes["cmd_qte"] . " Litres " .  $cmdes["name"] . " " . $cmdes["prenom"] . " - " . $cmdes["adresse"] . " " . $cmdes["code_postal"] . " " . $cmdes["ville"] . " " . $cmdes["tel_port"] . " " . $cmdes["tel_fixe"] . " " . $cmdes["cmd_commentfour"] . "\n";
				}
			}
			elseif ($_POST["generer"] == "corps_message" && $_POST["trie_par"] == "tcode_postal")
			{
				if($cmdes["cmd_typefuel"] == 2)
				{
					$text .= "(sup) " . $cmdes["cmd_qte"] . " Litres " .  $cmdes["name"] . " " . $cmdes["prenom"] . " - " . $cmdes["adresse"] . " " . $cmdes["code_postal"] . " " . $cmdes["ville"] . " " . $cmdes["tel_port"] . " " . $cmdes["tel_fixe"] . " " . $cmdes["cmd_commentfour"] . "\n";
				}
				if($cmdes["cmd_typefuel"] == 1)
				{
					$text .= "(ord) " . $cmdes["cmd_qte"] . " Litres " .  $cmdes["name"] . " " . $cmdes["prenom"] . " - " . $cmdes["adresse"] . " " . $cmdes["code_postal"] . " " . $cmdes["ville"] . " " . $cmdes["tel_port"] . " " . $cmdes["tel_fixe"] . " " . $cmdes["cmd_commentfour"] . "\n";
				}
			}
		}
	}
	$text .= $text1 . " \n";
	$text .= $text2;

	$text = nl2br($text);
}
?>
<div class="modal fade" id="genererTexte" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" style="max-width: 45%;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Critère de génération du texte</h5>
				<button type="button" class="btn-close b-close-c" data-bs-dismiss="modal" aria-label="Close"> <i class="fas fa-times"></i> </button>
			</div>
			<div class="modal-body" style="text-align: left;">
				<label class="label-title" style="margin: 0;">Mail à envoyer</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-6">
						<label for="generer_statut_1" class="col-form-label" style="padding-left:0;">Aux utilisateur qui ont une commande à partir du statut</label>
						<select class="form-control" name="generer_statut_1">
							<option value="10" <?php if(isset($_POST["generer_statut_1"])) { if($_POST['generer_statut_1'] == '10'){ echo "selected='selected'"; } } ?>>Utilisateur</option>
							<option value="12" <?php if(isset($_POST["generer_statut_1"])) { if($_POST['generer_statut_1'] == '12'){ echo "selected='selected'"; } } ?>>Attachée</option>
							<option value="13" <?php if(isset($_POST["generer_statut_1"])) { if($_POST['generer_statut_1'] == '13'){ echo "selected='selected'"; } } ?>>Proposée</option>
							<option value="15" <?php if(isset($_POST["generer_statut_1"])) { if($_POST['generer_statut_1'] == '15'){ echo "selected='selected'"; } } ?>>Groupée</option>
							<option value="17" <?php if(isset($_POST["generer_statut_1"])) { if($_POST['generer_statut_1'] == '17'){ echo "selected='selected'"; } } ?>>Prix proposé</option>
							<option value="20" <?php if(isset($_POST["generer_statut_1"])) { if($_POST['generer_statut_1'] == '20'){ echo "selected='selected'"; } } ?>>Prix validé</option>
							<option value="25" <?php if(isset($_POST["generer_statut_1"])) { if($_POST['generer_statut_1'] == '25'){ echo "selected='selected'"; } } ?>>Livrable</option>
							<option value="30" <?php if(isset($_POST["generer_statut_1"])) { if($_POST['generer_statut_1'] == '30'){ echo "selected='selected'"; } } ?>>Livrée</option>
							<option value="40" <?php if(isset($_POST["generer_statut_1"])) { if($_POST['generer_statut_1'] == '40'){ echo "selected='selected'"; } } ?>>Terminée</option>
							<option value="50" <?php if(isset($_POST["generer_statut_1"])) { if($_POST['generer_statut_1'] == '50'){ echo "selected='selected'"; } } ?>>Annulée</option>
							<option value="52" <?php if(isset($_POST["generer_statut_1"])) { if($_POST['generer_statut_1'] == '52'){ echo "selected='selected'"; } } ?>>Annulée / Livraison</option>
							<option value="55" <?php if(isset($_POST["generer_statut_1"])) { if($_POST['generer_statut_1'] == '55'){ echo "selected='selected'"; } } ?>>Annulée / Prix</option>
						</select>
					</div>
					<div class="col-sm-6">
						<label for="generer_statut_2" class="col-sm-4 col-form-label" style="padding-left:0;">Jusqu'au statut </label>
						<select class="form-control" name="generer_statut_2">
							<option value="0"></option>
							<option value="10" <?php if(isset($_POST["generer_statut_2"])) { if($_POST['generer_statut_2'] == '10'){ echo "selected='selected'"; } } ?>>Utilisateur</option>
							<option value="12" <?php if(isset($_POST["generer_statut_2"])) { if($_POST['generer_statut_2'] == '12'){ echo "selected='selected'"; } } ?>>Attachée</option>
							<option value="13" <?php if(isset($_POST["generer_statut_2"])) { if($_POST['generer_statut_2'] == '13'){ echo "selected='selected'"; } } ?>>Proposée</option>
							<option value="15" <?php if(isset($_POST["generer_statut_2"])) { if($_POST['generer_statut_2'] == '15'){ echo "selected='selected'"; } } ?>>Groupée</option>
							<option value="17" <?php if(isset($_POST["generer_statut_2"])) { if($_POST['generer_statut_2'] == '17'){ echo "selected='selected'"; } } ?>>Prix proposé</option>
							<option value="20" <?php if(isset($_POST["generer_statut_2"])) { if($_POST['generer_statut_2'] == '20'){ echo "selected='selected'"; } } ?>>Prix validé</option>
							<option value="25" <?php if(isset($_POST["generer_statut_2"])) { if($_POST['generer_statut_2'] == '25'){ echo "selected='selected'"; } } ?>>Livrable</option>
							<option value="30" <?php if(isset($_POST["generer_statut_2"])) { if($_POST['generer_statut_2'] == '30'){ echo "selected='selected'"; } } ?>>Livrée</option>
							<option value="40" <?php if(isset($_POST["generer_statut_2"])) { if($_POST['generer_statut_2'] == '40'){ echo "selected='selected'"; } } ?>>Terminée</option>
							<option value="50" <?php if(isset($_POST["generer_statut_2"])) { if($_POST['generer_statut_2'] == '50'){ echo "selected='selected'"; } } ?>>Annulée</option>
							<option value="52" <?php if(isset($_POST["generer_statut_2"])) { if($_POST['generer_statut_2'] == '52'){ echo "selected='selected'"; } } ?>>Annulée / Livraison</option>
							<option value="55" <?php if(isset($_POST["generer_statut_2"])) { if($_POST['generer_statut_2'] == '55'){ echo "selected='selected'"; } } ?>>Annulée / Prix</option>
						</select>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-sm-4 align-self-end">
						<label for="generer" class="col-form-label" style="padding: 1.5%;">
							<input type="radio" name="generer" id="" class="switch value check" value="corps_message" <?php if(isset($_POST["generer"])) { if($_POST['generer'] == 'corps_message'){ echo "checked='checked'"; } } ?> style="width: 14px;">
							Générer Corps du Message
						</label><br>
						<label for="generer" class="col-form-label" style="padding: 1.5%;">
							<input type="radio" name="generer" id="" class="switch value check" value="code_postaux" <?php if(isset($_POST["generer"])) { if($_POST['generer'] == 'code_postaux'){ echo "checked='checked'"; } } else { echo "checked='checked'"; }  ?> style="width: 14px;">
							Générer Code postaux
						</label>

					</div>
					<div class="col-sm-4">
						<fieldset>
    						<legend >Trié par :</legend>
							<label for="trie_par" class="col-form-label" style="padding: 1.5%;">
								<input type="radio" name="trie_par" id="" value="ttype_fioul" class="switch value check" <?php if(isset($_POST["trie_par"])) { if($_POST['trie_par'] == 'ttype_fioul'){ echo "checked='checked'"; } } else { echo "checked='checked'"; }  ?> style="width: 14px;">
								Type de Fioul
							</label><br>
							<label for="trie_par" class="col-form-label" style="padding: 1.5%;">
								<input type="radio" name="trie_par" id="" value="tcode_postal" class="switch value check" <?php if(isset($_POST["trie_par"])) { if($_POST['trie_par'] == 'tcode_postal'){ echo "checked='checked'"; } } ?> style="width: 14px;">
								Code Postal
							</label>
						</fieldset>
					</div>
					<div class="col-sm-4 align-self-center text-right">
						<input type="submit" name="generer_texte_mail" class="btn btn-primary" value="Générer Texte">
					</div>
				</div>
				<hr>
				<textarea name="" id="textarea" class="form-control" rows="2" style="height: 350px;"><?php if(isset($text)) { echo str_replace("<br />","",$text); } ?></textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary b-close-c" data-bs-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<?php
}
?>
