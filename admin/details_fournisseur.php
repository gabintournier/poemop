<style media="screen">
.menu > h1, .ligne-menu {margin-left:6%;}
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

$title = 'Fiche fournisseur';
$title_page = 'Fiche fournisseur';
$return = true;
if (isset($_GET["return"]))
{
	if ($_GET["return"]  == 'details')
	{
		$link = '/admin/gestion_client_commande.php?id_cmd=' . $_SESSION["id_cmd"] . '&return=cmdes';
	}
	elseif ($_GET["return"]  == 'recherche_ancienne_commande')
	{
		$link = '/admin/ancienne_commande.php?user_id=' . $_GET["user_id"] . '&return=recherche';
	}
	elseif ($_GET["return"]  == 'cmdes')
	{
		$link = '/admin/ancienne_commande.php?user_id=' . $_GET["user_id"] . '&id_cmd=' . $_SESSION["id_cmd"] . '&return=cmdes';
	}
	elseif ($_GET["return"]  == 'grp')
	{
		$link = '/admin/details_groupement.php?id_grp=' . $_GET["id_grp"] ;
	}
}
else
{
	$link = '/admin/liste_fournisseurs.php';
}

ob_start();




include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
unset($_SESSION['facture_saisie']);
$fournisseur_details = getFournisseurDetails($co_pmp, $_GET["id_four"]);
$res_avis = getLivreOrFournisseur($co_pmp, $_GET["id_four"]);

if (isset($message))
{
?>
<div class="toast <?= $message_type; ?>">
	<div class="message-icon success-icon">
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
		<a href="#" class="active">Fournisseur</a>
		<a href="contact_fournisseur.php?id_four=<?= $_GET["id_four"]; ?>">Contact</a>
		<a href="zones_fournisseur.php?id_four=<?= $_GET["id_four"]; ?>">Zone</a>
	</div>

	<form method="post">
		<div class="row">
			<div class="col-sm-6" style="border-right: 1px solid #0b242436;">
				<div class="row">
					<div class="col-sm-6">
						<label class="label-title" style="margin: 0;">Infos générales</label>
						<div class="ligne"></div>
					</div>
					<div class="col-sm-6 text-right">
						<a href="/admin-four/index.php?id_crypte=<?= $fournisseur_details["id_crypte"]; ?>" target="_blank" class="btn btn-primary">ADMIN FOURNISSEUR</a>
					</div>
				</div>

				<div class="row" style="margin-top: 2%;">
					<div class="col-sm-3">
						<div class="form-inline">
							<label for="n_four" class="col-sm-3 col-form-label" style="padding-left:0;">N°</label>
							<div class="col-sm-8" style="padding:0">
								<input type="text" name="n_four" value="<?= $_GET["id_four"]; ?>" class="form-control" style="width:100%;" disabled="disabled">
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-inline">
							<label for="nom_four" class="col-sm-3 col-form-label" style="padding-left:0;">Nom</label>
							<div class="col-sm-9" style="padding:0">
								<input type="text" name="nom_four" value="<?= $fournisseur_details["nom"]; ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-5 align-self-center">
						<div class="form-inline">
							<label for="etat_four" class="col-sm-3 col-form-label" style="padding-left:0;">Etat</label>
							<div class="col-sm-9" style="padding:0">
								<select class="form-control input-custom" name="etat_four" style="width:100%;">
									<option value="0" <?php if($fournisseur_details['etat'] == 0){ echo "selected='selected'"; } ?>>Non contacté</option>
									<option value="1" <?php if($fournisseur_details['etat'] == 1){ echo "selected='selected'"; } ?>>Partenaire</option>
									<option value="2" <?php if($fournisseur_details['etat'] == 2){ echo "selected='selected'"; } ?>>Pas interessant</option>
									<option value="3" <?php if($fournisseur_details['etat'] == 3){ echo "selected='selected'"; } ?>>Pas interessé</option>
									<option value="4" <?php if($fournisseur_details['etat'] == 4){ echo "selected='selected'"; } ?>>A recontacter</option>
									<option value="5" <?php if($fournisseur_details['etat'] == 5){ echo "selected='selected'"; } ?>>A recontacter com</option>
									<option value="6" <?php if($fournisseur_details['etat'] == 6){ echo "selected='selected'"; } ?>>Autres que fioul</option>
									<option value="7" <?php if($fournisseur_details['etat'] == 7){ echo "selected='selected'"; } ?>>Fin du partenariat</option>
									<option value="8" <?php if($fournisseur_details['etat'] == 8){ echo "selected='selected'"; } ?>>Partenaire secondaire</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-7" style="margin-top:0.5%;">
						<div class="form-inline">
							<label for="adresse_four" class="col-sm-2 col-form-label" style="padding-left:0;">Adresse</label>
							<div class="col-sm-10" style="padding:0">
								<input type="text" name="adresse_four" value="<?= $fournisseur_details["adresse"]; ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-5" style="margin-top:0.5%;">
						<input type="submit" name="" value="G. EARTH" class="btn btn-secondary" style="min-width:40%; margin-top:0.5%;">
					</div>
					<div class="col-sm-4" style="margin-top:0.5%;">
						<div class="form-inline">
							<label for="cp_four" class="col-sm-6 col-form-label" style="padding-left:0;">Code postal</label>
							<div class="col-sm-6" style="padding:0">
								<input type="text" name="cp_four" value="<?= $fournisseur_details["code_postal"]; ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-8" style="margin-top:0.5%;">
						<div class="form-inline">
							<label for="ville_four" class="col-sm-1 col-form-label" style="padding-left:0;">Ville</label>
							<div class="col-sm-11" style="padding:0">
								<input type="text" name="ville_four" value="<?= $fournisseur_details["ville"]; ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-4" style="margin-top:0.5%;">
						<div class="form-inline">
							<label for="fixe_four" class="col-sm-3 col-form-label" style="padding-left:0;">Fixe</label>
							<div class="col-sm-9" style="padding:0">
								<input type="tel" name="fixe_four" value="<?= $fournisseur_details["tel_fixe"]; ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-4" style="margin-top:0.5%;">
						<div class="form-inline">
							<label for="port_four" class="col-sm-4 col-form-label" style="padding-left:0;">Mobile</label>
							<div class="col-sm-8" style="padding:0">
								<input type="tel" name="port_four" value="<?= $fournisseur_details["tel_port"]; ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-4" style="margin-top:0.5%;">
						<div class="form-inline">
							<label for="fax_four" class="col-sm-3 col-form-label" style="padding-left:0;">Fax</label>
							<div class="col-sm-9" style="padding:0">
								<input type="tel" name="fax_four" value="<?= $fournisseur_details["fax"]; ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-8" style="margin-top:0.5%;">
						<div class="form-inline">
							<label for="mail_four" class="col-sm-1 col-form-label" style="padding-left:0;">Mail</label>
							<div class="col-sm-11" style="padding:0">
								<input type="text" name="mail_four" value="<?= $fournisseur_details["email"]; ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-4" style="margin-top:0.5%;">
						<div class="form-inline">
							<label for="lt_four" class="col-sm-2 col-form-label" style="padding-left:0;">Lt.</label>
							<div class="col-sm-10" style="padding:0">
								<input type="text" name="lt_four" value="<?= $fournisseur_details["lat"]; ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-8" style="margin-top:0.5%;">
						<div class="form-inline">
							<label for="url_four" class="col-sm-1 col-form-label" style="padding-left:0;">Url</label>
							<div class="col-sm-11" style="padding:0">
								<input type="text" name="url_four" value="<?= $fournisseur_details["url"]; ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-4" style="margin-top:0.5%;">
						<div class="form-inline">
							<label for="lg_four" class="col-sm-2 col-form-label" style="padding-left:0;">Lg.</label>
							<div class="col-sm-10" style="padding:0">
								<input type="text" name="lg_four" value="<?= $fournisseur_details["lng"]; ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<label class="label-title" style="margin: 0;">Infos facturation</label>
				<div class="ligne"></div>
				<div class="form-inline" style="margin-top: 2%;">
					<label for="fact_nom" class="col-sm-1 col-form-label" style="padding-left:0;">Nom</label>
					<div class="col-sm-11" style="padding:0">
						<input type="text" name="fact_nom" value="<?= $fournisseur_details["fact_nom"]; ?>" class="form-control" style="width:100%;">
					</div>
				</div>
				<div class="form-inline" style="margin-top:0.5%;">
					<label for="fact_adr" class="col-sm-1 col-form-label" style="padding-left:0;">Adresse</label>
					<div class="col-sm-11" style="padding:0">
						<input type="text" name="fact_adr" value="<?= $fournisseur_details["fact_adr"]; ?>" class="form-control" style="width:100%;">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-4" style="margin-top:0.5%;">
						<div class="form-inline">
							<label for="fact_cp" class="col-sm-6 col-form-label" style="padding-left:0;">Code postal</label>
							<div class="col-sm-6" style="padding:0">
								<input type="text" name="fact_cp" value="<?= $fournisseur_details["fact_cp"]; ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
					<div class="col-sm-8" style="margin-top:0.5%;">
						<div class="form-inline">
							<label for="fact_ville" class="col-sm-1 col-form-label" style="padding-left:0;">Ville</label>
							<div class="col-sm-11" style="padding:0">
								<input type="text" name="fact_ville" value="<?= $fournisseur_details["fact_ville"]; ?>" class="form-control" style="width:100%;">
							</div>
						</div>
					</div>
				</div>
				<div class="form-inline" style="margin-top:0.5%;">
					<label for="fact_mail" class="col-sm-1 col-form-label" style="padding-left:0;">Mail</label>
					<div class="col-sm-11" style="padding:0">
						<input type="text" name="fact_mail" value="<?= $fournisseur_details["fact_email"]; ?>" class="form-control" style="width:100%;">
					</div>
				</div>
				<div class="form-inline" style="margin-top:0.5%;">
					<label for="grp_mail" class="col-sm-4 col-form-label" style="padding-left:0;">Mail pour copie facturation</label>
					<div class="col-sm-8" style="padding:0">
						<input type="text" name="grp_email" value="<?= $fournisseur_details["grp_email"]; ?>" class="form-control" style="width:100%;">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-5">
						<div class="form-inline" style="margin-top:0.5%;">
							<label for="com_ord" class="col-sm-6 col-form-label" style="padding-left:0;">Commission ORD</label>
							<div class="col-sm-4" style="padding:0">
								<input type="text" name="com_ord" value="<?= $fournisseur_details["comord"]; ?>" class="form-control text-right" style="width:100%;">
							</div>
							<span>HT</span>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="form-inline" style="margin-top:0.5%;">
							<label for="com_sup" class="col-sm-2 col-form-label" style="padding-left:0;">SUP</label>
							<div class="col-sm-4" style="padding:0">
								<input type="text" name="com_sup" value="<?= $fournisseur_details["comsup"]; ?>" class="form-control text-right" style="width:100%;">
							</div>
							<span>HT</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
			    <label class="label-title" style="margin: 2% 0 0;">Spécificités</label>
			    <div class="ligne"></div>
			    <textarea name="name" class="form-control" rows="5" cols="62" style="height:auto;margin-top:2%;"><?= htmlspecialchars(trim(preg_replace('/\s+/', ' ', str_replace(["\r", "\n"], '', strip_tags($fournisseur_details["name"] ?? ''))))) ?></textarea>
			</div>
			
			<div class="col-sm-4">
			    <label class="label-title" style="margin: 2% 0 0;">Modalités de règlement</label>
			    <div class="ligne"></div>
			    <textarea name="modalite" class="form-control" rows="5" cols="62" style="height:auto;margin-top:2%;"><?= htmlspecialchars(trim(preg_replace('/\s+/', ' ', str_replace(["\r", "\n"], '', strip_tags($fournisseur_details["modalite"] ?? ''))))) ?></textarea>
			</div>
			
			<div class="col-sm-4">
			    <label class="label-title" style="margin: 2% 0 0;">Facilités de paiement</label>
			    <div class="ligne"></div>
			    <textarea name="facilite" class="form-control" rows="5" cols="62" style="height:auto;margin-top:2%;"><?= htmlspecialchars(trim(preg_replace('/\s+/', ' ', str_replace(["\r", "\n"], '', strip_tags($fournisseur_details["facilite"] ?? ''))))) ?></textarea>
			</div>
			
			<div class="col-sm-12" style="margin-bottom:1%;">
			    <label class="label-title" for="commentaire" style="margin: 1% 0 0;">Commentaire</label>
			    <div class="ligne"></div>
			    <textarea name="commentaire" class="form-control" rows="5" style="height:auto;margin-top:1%;"><?= htmlspecialchars(trim(preg_replace('/\s+/', ' ', str_replace(["\r", "\n"], '', strip_tags($fournisseur_details["commentaire"] ?? ''))))) ?></textarea>
			</div>

			<div class="col-sm-3">
				<button type="button" name="button" data-bs-toggle="modal" class="btn btn-warning" data-bs-target="#supprimerFour"><i class="fa-regular fa-trash-can"></i> SUPPRIMER</button>
			</div>
			<div class="col-sm-3 text-right">
				<a href="liste_regroupements.php?id_four=<?= $_GET["id_four"] ?>&return=fournisseur" class="btn btn-secondary" style="min-width:40%; margin-top:0.5%;">GRPT EN COURS</a>
			</div>
			<div class="col-sm-3 text-left">
				<button type="button" name="button" data-bs-toggle="modal" class="btn btn-secondary" data-bs-target="#livredOr" style="min-width:40%; margin-top:0.5%;"><i class="far fa-heart" style="font-size: 12px;"></i> LIVRE D'OR</button>
			</div>
			<div class="col-sm-3 text-right">
				<input type="submit" name="update_fournisseur" value="OK" class="btn btn-primary" style="min-width:40%; margin-top:0.5%;">
			</div>

			<div class="modal fade" id="supprimerFour" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Supprimer ce fournisseur ?</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fal fa-times"></i> </button>
						</div>
						<div class="modal-body">
							<?= $fournisseur_details["nom"]; ?>
						</div>
						<div class="modal-footer">
							<input type="hidden" name="id" value="<?= $_GET["id_four"] ?>">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
							<input type="submit" name="supp_fournisseur" class="btn btn-primary" value="Supprimer">
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="livredOr" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" style="max-width: 70%;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Livre d'Or pour le fournisseur <?= $fournisseur_details["nom"]; ?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fal fa-times"></i> </button>
						</div>
						<div class="modal-body">
							<div class="tableau" style="height: 600px;">
								<table class="table">
									<thead>
										<tr>
											<th>Client</th>
											<th>GRPT</th>
											<th>Date</th>
											<th>Note</th>
											<th>Message</th>
										</tr>
									</thead>
									<tbody>
<?php
									while ($avis = mysqli_fetch_array($res_avis))
									{
?>
									<tr>
										<td><?= $avis["user_id"]; ?></td>
										<td><?= $avis["id"]; ?></td>
										<td><?= $avis["date_grp"]; ?></td>
										<td><?= $avis["note"]; ?></td>
										<td><?= $avis["message"]; ?></td>
									</tr>
<?php
									}
?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
						</div>
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
<script src="/admin/js/script_fournisseurs.js" charset="utf-8"></script>
