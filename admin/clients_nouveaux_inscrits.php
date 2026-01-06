<style media="screen">
.ligne-menu {width: 25%!important;}
</style>
<?php
session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

$title = 'Nouveaux inscrits';
$title_page = 'Nouveaux inscrits';
ob_start();




include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_clients.php";
unset($_SESSION['facture_saisie']);
if(!empty($_POST["charger_recherche"]))
{
	$zone = $_POST["zone"];
	$coordonnees = $_POST["coordonnees"];
	$res = getNouveauxInscrits($co_pmp, $zone, $coordonnees);
}
?>
<div class="bloc">
	<div class="menu-bloc">
		<a href="#" class="active">Nouveaux inscrits</a>
		<a href="recherche_client.php">Chercher</a>
		<a href="fusionner_clients.php">Fusionner</a>
		<a href="gestion_client.php">Nouveau</a>
	</div>
	<form method="post">
		<div class="row">
			<div class="col-sm-7">
				<span class="titre-filtre">CHARGEMENT</span>
				<hr style="margin: 0;">
				<div class="row">
					<div class="col-sm-3">
						<label class="label-title" style="margin: 1% 0 0 0;">Coordonnées</label>
						<div class="ligne" style="width: 15%;"></div>
						<label for="nouvelle" class="col-form-label" style="padding-bottom: 0;">
							<input type="radio" name="coordonnees" id="coordonnees_incompletes" class="switch value check" value="incompletes" <?php echo ((!isset($_POST['coordonnees']) == "incompletes" && isset($_POST['charger_recherche']))?'':'checked="checked"'); ?>>
							Incomplètes
						</label><br>
						<label for="nouvelle" class="col-form-label">
							<input type="radio" name="coordonnees" id="coordonnees_completes" class="switch value check" value="completes" <?php if($_POST['coordonnees'] == "completes") { echo 'checked="checked"'; }  ?>>
							Complètes
						</label>
					</div>
					<div class="col-sm-3">
						<label class="label-title" style="margin: 1% 0 0 0;">Zone</label>
						<div class="ligne" style="width: 15%;"></div>
						<label for="nouvelle" class="col-form-label" style="padding-bottom: 0;">
							<input type="radio" name="zone" id="zone_desservie" class="switch value check" value="desservie" <?php echo ((!isset($_POST['zone']) == "desservie" && isset($_POST['charger_recherche']))?'':'checked="checked"'); ?>>
							Desservie
						</label><br>
						<label for="nouvelle" class="col-form-label">
							<input type="radio" name="zone" id="zone_non_desservie" class="switch value check" value="non_desservie" <?php if($_POST['zone'] == "non_desservie") { echo 'checked="checked"'; }  ?>>
							Non desservie
						</label>
					</div>
					<div class="col-sm-4">
						<label class="label-title" style="margin: 1% 0 0 0;">Recherche sur zone fournisseur</label>
						<div class="ligne" style="width: 10%;"></div>
						<div class="form-inline" style="margin: 2% 0 0 0;">
							<label for="n_four" class="col-sm-5 col-form-label" style="padding-left:0;">Fournisseur</label>
							<div class="col-sm-3" style="padding:0">
								<select class="form-control input-custom" name="etat_four" style="width: 204%;">
									<option value="1"></option>
									<option value="2"></option>
								</select>
							</div>
						</div>
						<div class="form-inline" style="margin: 2% 0 0 0;">
							<label for="n_four" class="col-sm-5 col-form-label" style="padding-left:0;">Zone</label>
							<div class="col-sm-3" style="padding:0">
								<select class="form-control input-custom" name="etat_four" style="width: 204%;">
									<option value="1"></option>
									<option value="2"></option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm align-self-center text-center">
						<label for="nouvelle" class="col-form-label" style="padding-bottom: 0;">
							<input type="checkbox" name="" id="" class="switch value check">
							Groupements
						</label><br>
						<input type="submit" name="charger_recherche" class="btn btn-primary" value="CHARGER" style="margin-top:9%;width:100%">
					</div>
				</div>
				<div class="tableau" style="height: 430px;">
					<table class="table">
						<thead>
						   <tr>
							   <th>Select</th>
							   <th>Code postal</th>
							   <th>Ville</th>
							   <th>Nom</th>
							   <th>N° Client</th>
							   <th>GRP</th>
							   <th>GRP possible</th>
						   </tr>
					   </thead>
					   <tbody>
<?php
						if(isset($res))
						{
							while($client = mysqli_fetch_array($res))
							{
?>
							<tr>
								<td></td>
								<td><?= $client["code_postal"]; ?></td>
								<td><?= $client["ville"]; ?></td>
								<td><?= $client["nom"]; ?></td>
								<td><?= $client["user_id"]; ?></td>
								<td></td>
								<td></td>
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
						<label for="nouvelle" class="col-form-label" style="padding-bottom: 0;">
							<input type="checkbox" name="" id="" class="switch value check">
							Cocher / Décocher
						</label>
					</div>
					<div class="col-sm-3">
						<input type="submit" class="btn btn-primary" name="traiter" value="TRAITER" style="width: 100%;">
					</div>
					<div class="col-sm-3">
						<input type="submit" class="btn btn-warning" name="ajouter_client_grp" value="AJOUTER CLIENT GRP" style="width: 100%;">
					</div>
				</div>
			</div>
			<div class="col-sm-5">
				<div class="tableau" style="height: auto;">
					<table class="table">
						<thead>
						   <tr>
							   <th>N° GRPT</th>
							   <th>Groupement</th>
							   <th>Statut</th>
							   <th>NB Clients</th>
						   </tr>
					   </thead>
					   <tbody>
						   <tr class="select">
							   <td></td>
							   <td></td>
							   <td></td>
							   <td></td>
						   </tr>
						   <tr class="select avis">
							   <td></td>
							   <td></td>
							   <td></td>
							   <td></td>
						   </tr>
						   <tr class="select">
							   <td></td>
							   <td></td>
							   <td></td>
							   <td></td>
						   </tr>
						   <tr class="select avis">
							   <td></td>
							   <td></td>
							   <td></td>
							   <td></td>
						   </tr>
						   <tr class="select">
							   <td></td>
							   <td></td>
							   <td></td>
							   <td></td>
						   </tr>
						   <tr class="select avis">
							   <td></td>
							   <td></td>
							   <td></td>
							   <td></td>
						   </tr>
					   </tbody>
					</table>
				</div>
				<label class="label-title" style="margin:0;">Génération mail</label>
				<div class="ligne" style="width: 5%;"></div>
				<label for="n_four" class="col-form-label" style="padding-left:0;">Choix du mail à envoyer</label>
				<div class="row">
					<div class="col-sm-9">
						<div style="padding:0">
							<select class="form-control input-custom" name="etat_four" style="width: 100%;">
								<option value="1"></option>
								<option value="2"></option>
							</select>
						</div>
					</div>
					<div class="col-sm align-self-center text-end">
						<input type="submit" name="" value="GÉNÉRER" class="btn btn-secondary" style="width:100%;">
					</div>
				</div>
				<hr>
				<label class="label-title" style="margin:0;">Mail</label>
				<div class="ligne" style="width: 5%;"></div>
				<label for="dest" class="col-form-label" style="padding-left:0;">Destinataire</label>
				<select name="dest" class="form-control input-custom" id="dest" multiple style="height: 12%!important;">
  				</select>
				<label for="objet" class="col-form-label" style="padding-left:0;">Objet</label>
				<input type="text" name="objet" class="form-control input-custom" value="">
				<div class="row" style="margin-top:2%;">
					<div class="col-sm-4 text-end">
						<select class="form-control input-custom" name="etat_four" style="width: 100%;">
							<option value="1">Haute</option>
							<option value="2">Normal</option>
							<option value="2" selected="selected">Lente</option>
						</select>
					</div>
					<div class="col-sm-8">
						<input type="submit" class="btn btn-primary" name="envoyer_mail" value="ENVOYEZ MAIL" style="width: 53%;">
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
