<link rel="stylesheet" type="text/css" href="css/datatables.min.css"/>
<style media="screen">
.ligne-menu {width: 320px!important;}
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

$title = 'Liste des fournisseurs';
$title_page = 'Liste des fournisseurs';

//Bouton ajouter fournisseur
$button = true;
$link_button = '/admin/ajouter_fournisseur.php';
$button_name = 'AJOUTER FOURNISSEUR';
$icon = '<i class="fas fa-plus"></i>';
ob_start();




include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_fournisseurs.php";
unset($_SESSION['facture_saisie']);
include __DIR__ . "/inc/pmp_inc_fournisseur.php";

if(!empty($_GET["etat"]) == "supp")
{
	$message_type = "info";
	$message_icone = "fa-exclamation";
	$message_titre = "Info";
	$message = "Le fournisseur a été supprimé avec succès";
}
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
	<div class="filtre">
		<form method="post">
			<div class="row">
				<div class="col-sm-2">
					<label class="label-title" style="margin: 0;">Sélection des fournisseurs</label>
					<div class="ligne"></div>
					<div class="form-inline" style="margin: 2% 0 0 0;">
						<label for="n_four" class="col-sm-7 col-form-label" style="padding-left:0;">N° fournisseurs</label>
						<div class="col-sm-5" style="padding:0">
							<input type="text" name="n_four" value="<?php if(!empty($_POST["vider"])) { echo ""; } elseif(isset($_POST["n_four"]))  { echo $_POST["n_four"]; } elseif(isset($_SESSION["n_four"])) { echo $_SESSION["n_four"]; } ?>" class="form-control" style="width:80%;">
						</div>
					</div>
					<div class="form-inline" style="margin: 0 0 2% 0;">
						<label for="n_dep" class="col-sm-7 col-form-label" style="padding-left:0;">N° dep</label>
						<div class="col-sm-5" style="padding:0">
							<input type="text" name="n_dep" value="<?php if(!empty($_POST["vider"])) { echo ""; } elseif(isset($_POST["n_dep"]))  { echo $_POST["n_dep"]; } elseif(isset($_SESSION["n_dep"])) { echo $_SESSION["n_dep"]; } ?>" class="form-control" style="width:80%;">
						</div>
					</div>
					<label for="principaux" class="col-form-label">
			<?php
					if(isset($_SESSION["principaux"]))
					{
						if($_SESSION["principaux"] == 1)
						{
			?>
						<input type="checkbox" name="principaux" id="principaux" class="switch value check" value="1">
			<?php
						}
						elseif ($_SESSION["principaux"] == 0)
						{
			?>
						<input type="checkbox" name="principaux" id="principaux" class="switch value check" value="">
			<?php
						}
					}
					else
					{
			?>
						<input type="checkbox" name="principaux" id="principaux" class="switch value check"  <?php echo ((!isset($_POST['principaux']) && isset($_POST['charger_fournisseur']))?'':'checked="checked"'); ?>>
			<?php
					}
			?>
						Principaux
					</label>
				</div>
				<div class="col-sm-6">
					<label class="label-title" style="margin: 0;">Statuts fournisseurs</label>
					<div class="ligne"></div>
					<div class="row">
						<div class="col-sm-3" style="    margin-top: 1%;">
							<label for="non_contacte" class="col-form-label" style="padding: 1.5%;">
			<?php
						if(isset($_SESSION["non_contacte"]))
						{
							if($_SESSION["non_contacte"] == 1)
							{
			?>
							<input type="checkbox" name="non_contacte" id="non_contacte" class="switch value check" value="1">
			<?php
							}
							elseif ($_SESSION["non_contacte"] == 0)
							{
			?>
							<input type="checkbox" name="non_contacte" id="non_contacte" class="switch value check" value="">
			<?php
							}
						}
						else
						{
			?>
							<input type="checkbox" name="non_contacte" id="" class="switch value check" <?php if(!empty($_POST["vider"])) { echo ""; } elseif(isset($_POST["non_contacte"])) echo 'checked="checked"' ; ?>>
			<?php
						}
			?>
								Non contacté
							</label>
							<label for="partenaires" class="col-form-label" style="padding: 1.5%;">
			<?php
						if(isset($_SESSION["partenaires"]))
						{
							if($_SESSION["partenaires"] == 1)
							{
			?>
								<input type="checkbox" name="partenaires" id="partenaires" class="switch value check" value="1">
			<?php
							}
							elseif ($_SESSION["partenaires"] == 0)
							{
			?>
								<input type="checkbox" name="partenaires" id="partenaires" class="switch value check" value="">
			<?php
							}
						}
						else
						{
			?>
								<input type="checkbox" name="partenaires" id="" class="switch value check" <?php echo ((!isset($_POST['partenaires']) && isset($_POST['charger_fournisseur']))?'':'checked="checked"'); ?>>
			<?php
						}
			?>
								Partenaires
							</label>
							<label for="partenaires_sec" class="col-form-label" style="padding: 1.5%;">
			<?php
							if(isset($_SESSION["partenaires_sec"]))
							{
								if($_SESSION["partenaires_sec"] == 1)
								{
			?>
									<input type="checkbox" name="partenaires_sec" id="partenaires_sec" class="switch value check" value="1">
			<?php
								}
								elseif ($_SESSION["partenaires_sec"] == 0)
								{
			?>
									<input type="checkbox" name="partenaires_sec" id="partenaires_sec" class="switch value check" value="">
			<?php
								}
							}
							else
							{
			?>
									<input type="checkbox" name="partenaires_sec" id="" class="switch value check" <?php if(!empty($_POST["vider"])) { echo ""; } elseif(isset($_POST["partenaires_sec"])) echo 'checked="checked"' ; ?>>
			<?php
							}
			?>
								Partenaires sec
							</label>
						</div>
						<div class="col-sm-4" style="margin-top: 1%;">
							<label for="recontacter" class="col-form-label" style="padding: 1.5%;">
			<?php
							if(isset($_SESSION["recontacter"]))
							{
								if($_SESSION["recontacter"] == 1)
								{
			?>
								<input type="checkbox" name="recontacter" id="recontacter" class="switch value check" value="1">
			<?php
								}
								elseif ($_SESSION["recontacter"] == 0)
								{
			?>
								<input type="checkbox" name="recontacter" id="recontacter" class="switch value check" value="">
			<?php
								}
							}
							else
							{
			?>
								<input type="checkbox" name="recontacter" id="" class="switch value check" <?php if(!empty($_POST["vider"])) { echo ""; } elseif(isset($_POST["recontacter"])) echo 'checked="checked"' ; ?>>
			<?php
							}
			?>
								A recontacter
							</label>
							<label for="recontacter_com" class="col-form-label" style="padding: 1.5%;">
			<?php
							if(isset($_SESSION["recontacter_com"]))
							{
								if($_SESSION["recontacter_com"] == 1)
								{
			?>
								<input type="checkbox" name="recontacter_com" id="recontacter_com" class="switch value check" value="1">
			<?php
								}
								elseif ($_SESSION["recontacter_com"] == 0)
								{
			?>
								<input type="checkbox" name="recontacter_com" id="recontacter_com" class="switch value check" value="">
			<?php
								}
							}
							else
							{
			?>
								<input type="checkbox" name="recontacter_com" id="" class="switch value check" <?php if(!empty($_POST["vider"])) { echo ""; } elseif(isset($_POST["recontacter_com"])) echo 'checked="checked"' ; ?>>
			<?php
							}
			?>
								A recontact pr com
							</label>
							<label for="pas_interesse" class="col-form-label" style="padding: 1.5%;">
			<?php
							if(isset($_SESSION["pas_interesse"]))
							{
								if($_SESSION["pas_interesse"] == 1)
								{
			?>
								<input type="checkbox" name="pas_interesse" id="pas_interesse" class="switch value check" value="1">
			<?php
								}
								elseif ($_SESSION["pas_interesse"] == 0)
								{
			?>
								<input type="checkbox" name="pas_interesse" id="pas_interesse" class="switch value check" value="">
			<?php
								}
							}
							else
							{
			?>
								<input type="checkbox" name="pas_interesse" id="" class="switch value check" <?php if(!empty($_POST["vider"])) { echo ""; } elseif(isset($_POST["pas_interesse"])) echo 'checked="checked"' ; ?>>
			<?php
							}
			?>
								Pas interessé
							</label>
						</div>
						<div class="col-sm-3" style="    margin-top: 1%;">
							<label for="pas_interessant" class="col-form-label" style="padding: 1.5%;">
			<?php
							if(isset($_SESSION["pas_interessant"]))
							{
								if($_SESSION["pas_interessant"] == 1)
								{
			?>
								<input type="checkbox" name="pas_interessant" id="pas_interessant" class="switch value check" value="1">
			<?php
								}
								elseif ($_SESSION["pas_interessant"] == 0)
								{
			?>
								<input type="checkbox" name="pas_interessant" id="pas_interessant" class="switch value check" value="">
			<?php
								}
							}
							else
							{
			?>
								<input type="checkbox" name="pas_interessant" id="" class="switch value check" <?php if(!empty($_POST["vider"])) { echo ""; } elseif(isset($_POST["pas_interessant"])) echo 'checked="checked"' ; ?>>
			<?php
							}
			?>
								Pas interessant
							</label>
							<label for="autre_fioul" class="col-form-label" style="padding: 1.5%;">
			<?php
							if(isset($_SESSION["autre_fioul"]))
							{
								if($_SESSION["autre_fioul"] == 1)
								{
			?>
								<input type="checkbox" name="autre_fioul" id="autre_fioul" class="switch value check" value="1">
			<?php
								}
								elseif ($_SESSION["autre_fioul"] == 0)
								{
			?>
								<input type="checkbox" name="autre_fioul" id="autre_fioul" class="switch value check" value="">
			<?php
								}
							}
							else
							{
			?>
								<input type="checkbox" name="autre_fioul" id="" class="switch value check" <?php if(!empty($_POST["vider"])) { echo ""; } elseif(isset($_POST["autre_fioul"])) echo 'checked="checked"' ; ?>>
			<?php
							}
			?>
								Autre que fioul
							</label>
							<label for="partenanriat_fini" class="col-form-label" style="padding: 1.5%;">
			<?php
							if(isset($_SESSION["partenanriat_fini"]))
							{
								if($_SESSION["partenanriat_fini"] == 1)
								{
			?>
								<input type="checkbox" name="partenanriat_fini" id="partenanriat_fini" class="switch value check" value="1">
			<?php
								}
								elseif ($_SESSION["partenanriat_fini"] == 0)
								{
			?>
								<input type="checkbox" name="partenanriat_fini" id="partenanriat_fini" class="switch value check" value="">
			<?php
								}
							}
							else
							{
			?>
								<input type="checkbox" name="partenanriat_fini" id="" class="switch value check" <?php if(!empty($_POST["vider"])) { echo ""; } elseif(isset($_POST["partenanriat_fini"])) echo 'checked="checked"' ; ?>>
			<?php
							}
			?>
								Partenairiat fini
							</label>
						</div>
						<div class="col-sm-2 align-self-center text-center">
							<input type="submit" name="charger_fournisseur" value="CHARGER" class="btn btn-primary" style="width:100%;"><br>
							<input type="submit" name="vider" value="VIDER" class="btn btn-warning" style="width:100%; margin-top:5%;">
						</div>
					</div>
				</div>
				<div class="col" style="margin-left: 1%;border-left: 1px solid #0b242436;">
					<label class="label-title" style="margin: 0;">Recherche sur zone de livraison</label>
					<div class="ligne"></div>
					<div class="form-inline" style="margin: 0 0 2% 0;">
						<label for="cp_livraison" class="col-sm-3 col-form-label" style="padding-left:0;">Code postal</label>
						<div class="col-sm-5" style="padding:0">
							<input type="text" name="cp_livraison" value="<?php if(!empty($_POST["vider"])) { echo ""; } elseif(isset($_POST["cp_livraison"]))  { echo $_POST["cp_livraison"]; } elseif(isset($_SESSION["cp_livraison"])) { echo $_SESSION["cp_livraison"]; } elseif(isset($_GET["cp"])) { echo $_GET["cp"]; } ?>" class="form-control" style="width:55%;">
						</div>
						<input type="submit" name="recherche_zone_livraison" value="RECHERCHER" class="btn btn-primary" style="margin-left: -10%;">
					</div>
					<input type="submit" name="afficher_tous_fournisseurs" value="AFFICHER TOUS LES FOURNISSEURS" class="btn btn-secondary" style="margin-top: 3%;">
				</div>
			</div>
			<hr>
			<div class="tableau" style="height:500px;margin: 20px 0 10px;">
				<table class="table" id="trie_table_four">
					<thead>
						<tr>
							<th style="width: 10px;"><i class="fal fa-sort"></i></th>
							<th>Nom</th>
							<th class="text-center">Dernier grpt le</th>
							<th>Code postal</th>
							<th>Ville</th>
							<th>Etat</th>
			<?php
						if (!empty($_POST["recherche_zone_livraison"]) || !empty($_GET["cp"]) || (!empty($_SESSION["cp_livraison"])))
						{
			?>
							<th>Zone en recherche</th>
			<?php
						}
			?>
						</tr>
					</thead>
					<tbody>
			<?php
						while($fournisseur = mysqli_fetch_array($res))
						{
							if ($fournisseur["etat"] == '0'){ $etat = "Non contacté"; }
							if ($fournisseur["etat"] == '1'){ $etat = "Partenaire"; }
							if ($fournisseur["etat"] == '2'){ $etat = "Pas interessant"; }
							if ($fournisseur["etat"] == '3'){ $etat = "Pas interessé"; }
							if ($fournisseur["etat"] == '4'){ $etat = "A recontacter"; }
							if ($fournisseur["etat"] == '5'){ $etat = "A recontact pr com"; }
							if ($fournisseur["etat"] == '6'){ $etat = "Autre que fioul"; }
							if ($fournisseur["etat"] == '7'){ $etat = "Partenairiat fini"; }
							if ($fournisseur["etat"] == '8'){ $etat = "Partenaire sec"; }
							// $date = getDernierGroupement($co_pmp, $fournisseur['id']);
							$grp = getDateDernierGroupement($co_pmp, $fournisseur['id']);
			?>
							<tr class="select fournisseur">
								<input type="hidden" name="four_id" value="<?= $fournisseur['id']; ?>">
								<td></td>
								<td><?= $fournisseur['nom']; ?></td>
								<td class="text-center <?php if(isset($grp["statut"])) { if($grp["statut"] == '50'){ echo "rouge"; } }  ?>"><?= $grp["date_grp"] ?></td>
								<td><?= $fournisseur["code_postal"]; ?></td>
								<td><?= $fournisseur["ville"]; ?></td>
								<td><?= $etat; ?></td>
			<?php
								if (!empty($_POST["recherche_zone_livraison"]) || !empty($_GET["cp"]) || (!empty($_SESSION["cp_livraison"])))
								{
			?>
								<td><?php if(isset($fournisseur["libelle"])) { echo $fournisseur["libelle"]; } ?></td>
			<?php
								}
			?>
							</tr>
			<?php
						}
			?>

					</tbody>
				</table>
			</div>
			<div class="text-center">
				<p style="font-size: 14px;color: #0b2424ab;"><?= $num_res; ?> fournisseurs chargé(s)</p>
			</div>
			<div class="text-right">
				<input type="submit" name="exporter_fournisseur" value="EXPORTER" class="btn btn-secondary">
			</div>

		</form>
	</div>

</div>

<?php
$content = ob_get_clean();
require('template.php');
?>
<script type="text/javascript" src="/admin/js/datatables.min.js"></script>
<script src="/admin/js/date-eu.js" type="text/javascript"></script>
<script src="/admin/js/script_fournisseurs.js" charset="utf-8"></script>
