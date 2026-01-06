<style media="screen">
.ligne-menu {width: 330px!important;}
.btn-outline-primary { background: #f7f7f7!important;padding: 3px 20px!important;border-radius: 6px!important;font-size: 14px!important;}
</style>
<?php
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

error_reporting(E_ALL);
ini_set("display_errors", 1);

$title = 'Commandes à affecter';
$title_page = 'Commandes à affecter';
ob_start();




include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_commandes_a_affecter.php";



if(!empty($_POST["commandes_orphelines"]))
{
	$_SESSION["commandes_orphelines"] = $_POST["commandes_orphelines"];
	$res_cmd = getCommandesOrphelines($co_pmp);
	$num_cmd = mysqli_num_rows($res_cmd);

	$vol_ord = getCommandesOrphelinesVol($co_pmp, 1);
	$vol_sup = getCommandesOrphelinesVol($co_pmp, 2);
}
elseif (!empty($_POST["charger_commandes"]))
{
	unset($_SESSION["commandes_orphelines"]);

	if(!empty($_POST["groupement_possible"]) && empty($_POST["plusieurs_groupement_possible"]))
	{
		$_SESSION["groupement_possible"] = $_POST["groupement_possible"];
		unset($_SESSION["plusieurs_groupement_possible"]);
		$res_cmd = getGroupementPossible($co_pmp);
		$num_cmd = mysqli_num_rows($res_cmd);
	}
	elseif (!empty($_POST["plusieurs_groupement_possible"]) && empty($_POST["groupement_possible"]))
	{
		$_SESSION["plusieurs_groupement_possible"] = $_POST["plusieurs_groupement_possible"];
		unset($_SESSION["groupement_possible"]);
		$res_cmd = getPlusieursGroupementsPossibles($co_pmp);
		$num_cmd = mysqli_num_rows($res_cmd);
	}
	elseif(!empty($_POST["groupement_possible"]) && !empty($_POST["plusieurs_groupement_possible"]))
	{
		$_SESSION["groupement_possible"] = $_POST["groupement_possible"];
		$_SESSION["plusieurs_groupement_possible"] = $_POST["plusieurs_groupement_possible"];
		$res_cmd = getCommandesGroupements($co_pmp);
		$num_cmd = mysqli_num_rows($res_cmd);
	}
}
elseif (!empty($_POST["vider"]))
{
	unset($_SESSION["commandes_orphelines"]);
	unset($_SESSION["groupement_possible"]);

	$res_cmd = getCommandesGroupements($co_pmp);
	$num_cmd = mysqli_num_rows($res_cmd);
}
else
{
	$res_cmd = getCommandesGroupements($co_pmp);
	$num_cmd = mysqli_num_rows($res_cmd);
}

unset($_SESSION['facture_saisie']);

error_reporting(E_ALL);
ini_set("display_errors", 1);



if (isset($message))
{
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
	<form method="post">
		<div class="row">
			<div class="col-sm-4" style="border-right: 1px solid #0b242436;">
				<label class="label-title" style="margin: 0;">Sélection des commandes</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-8" style="    margin-top: 1%;">
						<label for="groupement_possible" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="groupement_possible" id="" class="switch value check" <?php echo ((!isset($_POST['groupement_possible']) && isset($_POST['charger_commandes']))?'':'checked="checked"'); ?>>
							1 seul groupement possible
						</label>
						<label for="plusieurs_groupement_possible" class="col-form-label" style="padding: 1.5%;">
							<input type="checkbox" name="plusieurs_groupement_possible" id="" class="switch value check" <?php echo ((!isset($_POST['plusieurs_groupement_possible']) && isset($_POST['charger_commandes']))?'':'checked="checked"'); ?>>
							Plusieurs groupements possible
						</label>
					</div>
					<div class="col-sm-4 align-self-center text-center" >
						<input type="submit" name="charger_commandes" value="CHARGER" class="btn btn-primary" style="width:100%;"><br>
						<input type="submit" name="vider" value="VIDER" class="btn btn-warning" style="width:100%; margin-top:5%;">
					</div>
				</div>
				<input type="submit" name="commandes_orphelines" value="COMMANDES ORPHELINES" class="btn btn-outline-primary">
			</div>
			<div class="col-sm-4" style="border-right: 1px solid #0b242436;">
				<label class="label-title" style="margin: 0;">Affectation automatique</label>
				<div class="ligne"></div>
				<div class="row">
					<div class="col-sm-7" style="margin-top: 1%;">
						<label for="vol_ord" class="col-form-label" style="padding-left:0;">Affecter automatiquement les commandes avec un seul groupement possible </label>
					</div>
					<div class="col-sm-5 align-self-center text-center" >
						<input type="submit" name="affecter_auto" value="AFFECTER" class="btn btn-primary" style="width:77%;"><br>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<label class="label-title" style="margin: 0;">Volumes des commandes</label>
				<div class="ligne"></div>
				<div class="form-inline" style="margin-top:0.5%;">
					<label for="vol_ord" class="col-sm-2 col-form-label" style="padding-left:0;">Vol Ord</label>
					<div class="col-sm-3" style="padding:0;">
						<input type="text" name="vol_ord" value="<?php if(isset($vol_ord["vol"])) { echo $vol_ord["vol"]; } ?>" class="form-control vol_ord" style="width:100%;">
					</div>
				</div>
				<div class="form-inline" style="margin-top:0.5%;">
					<label for="vol_ord" class="col-sm-2 col-form-label" style="padding-left:0;">Vol Sup</label>
					<div class="col-sm-3" style="padding:0;">
						<input type="text" name="vol_ord" value="<?php if(isset($vol_sup["vol"])) { echo $vol_sup["vol"]; } ?>" class="form-control vol_sup" style="width:100%;">
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-2" style="max-width: 11%;">

			</div>
			<div class="col-sm-2" style="max-width: 11%;">

			</div>
		</div>
		<hr>
		<div class="form-inline">
			<label for="dep_zone" class="col-sm-2 col-form-label" style="padding-left:0;max-width: 13%;">Tous cocher / decocher :</label>
			<div class="col-sm-2 select-tous_cocher" style="padding:0">
				<input type="checkbox" name="tous_cocher" value="" class="switch value">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">

				<div class="tableau" style="height: 600px;margin-top: 0;">
						<table class="table" id="trie_table_affecter">
<?php
							if(isset($_SESSION["commandes_orphelines"]))
							{
?>
							<thead>
								<th style="width: 4px;"><i class="fal fa-sort"></i></th>
								<th style="padding: 8px 10px;width: 35px;">Nb&nbsp;Litre</th>
								<th style="padding: 8px 10px;width: 40px;" class="text-center">Type</th>
								<th style="padding: 8px 10px;width: 65px;">Date</th>
								<th style="padding: 8px 10px;width: 60px;" class="text-center">CP</th>
								<th style="padding: 8px 10px;width: 250px;">Ville</th>
								<th style="padding: 8px 10px;width: 130px;">Nom</th>
								<th style="padding: 8px 10px;width: 130px;">Prénom</th>
								<th style="padding: 8px 10px;width: 80px;">Etat</th>
							</thead>
							<tbody>
<?php
							while($commande = mysqli_fetch_array($res_cmd))
							{
								if($commande["cmd_status"] == 10) { $status = " 10 - Utilisateur"; }
								if($commande["cmd_status"] == 12) { $status = " 12 - Attaché"; }

								if ($commande["cmd_typefuel"] == 1){ $type = 'O';}
								if ($commande["cmd_typefuel"] == 2){ $type = 'S';}
?>
								<tr>
									<td></td>
									<td><?= $commande["cmd_qte"]; ?></td>
									<td class="text-center"><?= $type; ?></td>
									<td><?= date_format(new DateTime($commande['cmd_dt']), 'd/m/Y' ); ?></td>
									<td class="text-center"><?= $commande["code_postal"]; ?></td>
									<td><?= $commande["ville"]; ?></td>
									<td><?= $commande["name"]; ?></td>
									<td><?= $commande["prenom"]; ?></td>
									<td ><?= $status; ?></td>
								</tr>
<?php
							}
?>
							</tbody>
<?php
							}
							else
							{
?>
							<thead>
								<th style="width: 4px;"><i class="fal fa-sort"></i></th>
								<th style="padding: 8px 10px;width:45px;">Select</th>
								<th style="padding: 8px 10px;width: 35px;">Nb&nbsp;Litre</th>
								<th style="padding: 8px 10px;width: 40px;" class="text-center">Type</th>
								<th style="padding: 8px 10px;width: 65px;">Date</th>
								<th style="padding: 8px 10px;width: 60px;" class="text-center">CP</th>
								<th style="padding: 8px 10px;width: 250px;">Ville</th>
								<th style="padding: 8px 10px;width: 130px;">Nom</th>
								<th style="padding: 8px 10px;width: 130px;">Prénom</th>
								<th style="padding: 8px 10px;width: 80px;">Etat</th>
								<th style="padding: 8px 10px;width: 270px;border-left: 1px solid #0b242436;">Groupement</th>

							</thead>
							<tbody>
<?php
							$i = 0;
							while($commande = mysqli_fetch_array($res_cmd))
							{
								if($commande["cmd_status"] == 10) { $status = " 10 - Utilisateur"; }
								if($commande["cmd_status"] == 12) { $status = " 12 - Attaché"; }

								if ($commande["cmd_typefuel"] == 1){ $type = 'O'; $class = "nb_o";}
								if ($commande["cmd_typefuel"] == 2){ $type = 'S'; $class = "nb_s";}

								$grp = getGroupementCree($co_pmp, $commande["code_postal_id"]);
								$num_grps = mysqli_num_rows($grp);
?>
								<tr>
									<td></td>
									<input type="hidden" name="cmd_id[]" value="<?= $commande['id']; ?>">
									<input type="hidden" name="cmd_qte[]" value="<?= $commande["cmd_qte"]; ?>">
									<input type="hidden" name="user_id[]" value="<?= $commande["user_id"]; ?>">
									<td><input type="checkbox" name="select_grp_<?php print $i++; ?>[]" id="select_grp" class="switch value check" style="background: #ddddddd1;"></td>
									<td class="<?= $class; ?>"><?= $commande["cmd_qte"]; ?></td>
									<td class="text-center"><?= $type; ?></td>
									<td><?= date_format(new DateTime($commande['cmd_dt']), 'd/m/Y' ); ?></td>
									<td class="text-center"><?= $commande["code_postal"]; ?></td>
									<td><?= $commande["ville"]; ?></td>
									<td><?= $commande["name"]; ?></td>
									<td><?= $commande["prenom"]; ?></td>
									<td ><?= $status; ?></td>
<?php
									if($num_grps == 1)
									{
?>
									<input type="hidden" name="groupements[]" value="<?= $commande["id_grp"]; ?>">
									<td style="border-left: 1px solid #0b242436;"><?= $commande["id_grp"]; ?> - <?= $commande["libelle"]; ?></td>
<?php
									}
									else
									{
?>
									<td style="border-left: 1px solid #0b242436;">
										<select class="form-control input-custom" name="groupements[]" style="background-color: #ffffff;">
											<option value=""><?= $num_grps; ?> groupements</option>

<?php
									while($rgp = mysqli_fetch_array($grp))
									{
?>
											<option value="<?= $rgp["id"] ?>"><?= $rgp["libelle"] ?></option>
<?php
									}
?>
										</select>
									</td>
<?php
									}
?>
								</tr>
<?php

							}
?>
							</tbody>
<?php
							}
?>
						</table>
				</div>
				<div class="row">
					<div class="col-sm-4"></div>
					<div class="col-sm-4 text-center">
						<p style="font-size: 14px;color: #0b2424ab;"><?= $num_cmd; ?> commandes</p>
					</div>
					<div class="col-sm-4 text-right">
						<input type="hidden" name="nb_cmd" value="<?php print $i; ?>">
						<input type="submit" name="affecter_commandes" value="AFFECTER" class="btn btn-primary" style="width:130px;">
					</div>
				</div>
				<div class="text-center">
				</div>
			</div>
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="/admin/js/script_zones.js" charset="utf-8"></script>
