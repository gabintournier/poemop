<?php
if(isset($_GET["id_grp"]))
{
	$id_grp = $_GET["id_grp"];
	$user = getChargerClientCommande($co_pmp, $id_grp);
}


if(!empty($_POST["basculer_groupement"]))
{
	if (!empty($_POST["cmde_id_grp"]))
	{
		$id_cmd = $_POST["cmde_id_grp"];
		for($i=0;$i<$_POST['nb_cmd'];$i++)
		{
			$tmp = 'basculer_commande_' . $i;
			$id = $id_cmd[$i];
			$id_ancien_grp = 'id_grp_' . $id;
			$id_ancien_grp = $_POST[$id_ancien_grp];
			$basculer = isset($_POST[$tmp]) ? "1" : "0";
			if($basculer == '1')
			{
				$query = " UPDATE pmp_commande SET groupe_cmd = '$id_grp'
									 WHERE id = '$id' ";
				$res = my_query($co_pmp, $query);
				if($res)
				{
					TraceHisto($co_pmp, $id, 'Basculer Groupement', $id_ancien_grp . " --> " . $id_grp);
					header('Location: /admin/details_groupement.php?id_grp=' . $id_grp .'&charger_client=ok');

				}
			}
		}
	}
}

if(isset($_GET["charger_client"]) && $_GET["charger_client"] == 'ok')
{
	$message_type = "success";
	$message_icone = "fa-check";
	$message_titre = "Succès";
	$message = "Les commandes ont bien été basculées sur ce groupement.";
}
?>
<div class="modal fade" id="ChargerClient" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" style="max-width: 45%;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Commandes déjà dans un groupement</h5>
				<button type="button" class="btn-close b-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fas fa-times"></i> </button>
			</div>
			<div class="modal-body" style="text-align: left;">
<?php
			if (isset($message))
			{
?>
				<div class="toast <?= $message_type; ?>" style="margin: 3px 0 7px;width: 600px;">
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
				<div class="form-inline">
					<label for="dep_zone" class="col-sm-4 col-form-label" style="padding-left:0;">Sélectionner toutes les commandes :</label>
					<div class="col-sm-2 select-tous_basculer" style="padding:0">
						<input type="checkbox" name="tous_basculer" value="" class="switch value">
					</div>
				</div>
				<div class="tableau">
					<table class="table" id="trie_table_cmd_charger">
						<thead>
							<tr style="white-space: nowrap;">
								<th><i class="fal fa-sort"></i></th>
								<th class="text-center">Select</th>
								<th class="text-center">N° GRP</th>
								<th>Nom</th>
								<th>Prénom</th>
								<th class="text-center">CP</th>
								<th class="text-center">Date</th>
								<th class="text-center">Qté</th>
								<th class="text-center">Statut</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$i = 0;
							foreach ($user as $u) // Correction : $u au lieu de $user
							{
							    if($u["groupe_cmd"] > 0)
							    {
							?>
							    <tr>
							        <input type="hidden" name="cmde_id_grp[]" value="<?= $u["id"]; ?>">
							        <input type="hidden" name="id_grp_<?= $u["id"]; ?>" value="<?= $u["groupe_cmd"]; ?>">
							        <td></td>
							        <td class="text-center"><input type="checkbox" name="basculer_commande_<?php print $i++; ?>[]" value="" class="switch value"></td>
							        <td class="text-center"><?= $u["groupe_cmd"]; ?></td>
							        <td><?= $u["name"]; ?></td>
							        <td><?= $u["prenom"]; ?></td>
							        <td class="text-center"><?= $u["code_postal"]; ?></td>
							        <td class="text-center"><?= $u["cmd_dt"]; ?></td>
							        <td class="text-center"><?= $u["cmd_qte"]; ?></td>
							        <td class="text-center"><?= $u["cmd_status"]; ?></td>
							    </tr>
							<?php
							    }
							 }
						?>
						</tbody>
					</table>
				</div>
				<input type="hidden" name="nb_cmd" value="<?php print $i; ?>">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary b-close" data-bs-dismiss="modal">Fermer</button>
				<input type="submit" name="basculer_groupement" class="btn btn-primary" value="Basculer">
			</div>
		</div>
	</div>
</div>
