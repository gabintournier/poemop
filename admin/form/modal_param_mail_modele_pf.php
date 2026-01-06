<?php
$res_mail = getMailModelePF($co_pf);

if(isset($_GET["mail_id"]))
{
	$_SESSION["mail_modele"] = $_GET["mail_id"];
	$mail = getMailModelePFid($co_pf, $_SESSION["mail_modele"]);
	$res_mot_cle = getMotsClesPF($co_pf, $_SESSION["mail_modele"]);
}

if (isset($message))
{
?>
<div class="toast <?= $message_type; ?>" style="margin: 10px 0 14px;width: 490px;">
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
<div class="row">
	<div class="col-sm-6" style="border-right: 1px solid #0b242436;">
		<label class="label-title" style="margin: 0;">Modèle</label>
		<div class="ligne"></div>
		<div class="row">
			<div class="col-sm-8" >
				<label for="sujet" class="col-form-label" style="padding-left:0;">Sujet du mail</label>
				<input type="text" name="sujet" id="sujet_mail" class="form-control" style="width:100%;" value="<?php if(isset($mail["sujet"])) { echo $mail["sujet"]; } ?>">
			</div>
			<div class="col-sm-4">
				<label for="fichier" class="col-form-label" style="padding-left:0;">Nom fichier</label>
				<input type="text" name="fichier" id="nom_fichier" class="form-control" style="width:100%;" value="<?php if(isset($mail["nom_fichier"])) { echo $mail["nom_fichier"]; } ?>">
			</div>
		</div>
		<label for="descriptif" class="col-form-label" style="padding-left:0;">Descriptif</label>
		<textarea name="descriptif" class="form-control" id="descriptif" rows="8" style="height:auto;"><?php if(isset($mail["description"])) { echo $mail["description"]; } ?></textarea>
	</div>
	<div class="col-sm-6">
		<label class="label-title" style="margin: 0;">Mots-clés</label>
		<div class="ligne"></div>
		<div class="row">
			<div class="col-sm-4">
				<label for="mots_cle" class="col-form-label" style="padding-left:0;">Mots-clés du mail</label>
				<input type="text" name="mots_cle" class="form-control" style="width:100%;">
			</div>
			<div class="col-sm-6">
				<label for="descriptif_mc" class="col-form-label" style="padding-left:0;">Descriptif</label>
				<input type="text" name="descriptif_mc" class="form-control" style="width:100%;">
			</div>
			<div class="col-sm-2 align-self-end">
				<input type="submit" name="ajouter_mots_cles" value="+" class="btn btn-go" style="padding: 0.175rem 0.75rem;background: white;">
			</div>
		</div>
		<div class="tableau" style="height: 200px;margin-bottom: 0;">
			<table class="table">
				<thead>
					<tr>
						<th>Mots-Clés</th>
						<th>Descriptif</th>
					</tr>
				</thead>
				<tbody>
<?php
				if(isset($res_mot_cle))
				{
					while ($mot_cle = mysqli_fetch_array($res_mot_cle))
					{
?>
					<tr>
						<td><?= $mot_cle["cle"]; ?></td>
						<td><?= $mot_cle["description"]; ?></td>
					</tr>
<?php
					}
				}
?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<hr>
<div class="tableau">
	<table class="table" style="white-space: nowrap;">
		<thead>
			<tr>
				<th>N°</th>
				<th>Sujet</th>
				<th class="text-center">Destinataire</th>
				<th>Fichier</th>
				<th class="text-center">Nb Mot-Clé</th>
				<th>Déscriptif</th>
			</tr>
		</thead>
		<tbody>
<?php
		while($mail = mysqli_fetch_array($res_mail))
		{
			$mail_pf = getNbMotsClesPF($co_pf, $mail["id"]);
?>
			<tr class="select_mail <?php if($mail["id"] == $_SESSION["mail_modele"]) { echo "selected"; } ?>">
				<td><?= $mail["id"]; ?></td>
				<td><?= $mail["sujet"]; ?></td>
				<td>Client</td>
				<td><?= $mail["nom_fichier"]; ?></td>
				<td><?= $mail_pf["mots_cle"]; ?></td>
				<td><?= $mail["description"]; ?></td>
			</tr>
<?php
		}
?>
		</tbody>
	</table>
</div>
