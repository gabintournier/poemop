<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

<style media="screen">
.ligne-menu {width: 340px!important;}
</style>
<?php
session_start();

//*** Securisation du formulaire
// On detecte la recharge par F5 (par exemple) dans une meme session
$recharge = TRUE;
$RequestSignature = md5($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].print_r($_POST, true));
if($_SESSION['LastRequest'] != $RequestSignature)
{
	$_SESSION['LastRequest'] = $RequestSignature;
	$recharge = FALSE;
}
// On detecte le token du form et le token de la session sont identique
if(isset($_SESSION['token']) AND isset($_POST['token']) AND !empty($_SESSION['token']) AND !empty($_POST['token']))
{
    if ($_SESSION['token'] == $_POST['token'])
	{
		$recharge = FALSE;
    }
}

$title = "Nouvelles commandes";
$title_page = "Nouvelles commandes";
$return = true;


if (isset($_GET["return"]) == 'zone_cot')
{
	$link = 'liste_zones_cotation.php?id_crypte=' . $_GET["id_crypte"];
}

if (isset($_GET["refresh"])) {
    $host = $_SERVER['HTTP_HOST'];
    $isDev = str_contains($host, 'dev') || str_contains($host, 'localhost'); // Détecte dev ou localhost
    $baseUrl = $isDev ? "https://dev.plus-on-est-moins-on-paie.fr" : "https://plus-on-est-moins-on-paie.fr";

    $id_crypte = $_GET["id_crypte"];
    $id_zone   = $_GET["id_zone"];

    header('Location: ' . $baseUrl . '/admin-four/zone_cotations.php?id_crypte=' . $id_crypte . '&id_zone=' . $id_zone . '&return=zone_cot');
    exit; // Toujours mettre exit après un header location
}

ob_start();

// INC global
include_once "../inc/pmp_co_connect.php";
// Prépare l'environnement fournisseur avant de charger les fonctions métier (chemin absolu pour cibler la version admin)
include_once __DIR__ . "/inc/pmp_inc_fonctions_connexion.php";
// INC interne ADM Fournisseur
include_once "inc/pmp_inc_fonctions_cotations.php";

$dernier_grp = getDernierGroupement($co_pmp, $_GET["id_zone"]);
$plages_prix = getPlagesPrix($co_pmp, $_GET["id_zone"]);
$today = date("Y-m-d H:i:s");

if(isset($plages_prix["dateheure_cotation"]) && isset($dernier_grp["date_grp"]))
{

	if($plages_prix["dateheure_cotation"] < $dernier_grp["date_grp"])
	{
		$pas_de_prix = "ok";
	}
	elseif ($dernier_grp["date_grp"] < $today)
	{
		$nouvelle_cot = "ok";
	}
}

$cmdes_ord = getCommandesZoneFuel($co_pmp, $_GET["id_zone"], 1);
$cmdes_sup = getCommandesZoneFuel($co_pmp, $_GET["id_zone"], 2);
$qte_ord = getCommandesZoneStats($co_pmp, $_GET["id_zone"], 1);
$qte_sup = getCommandesZoneStats($co_pmp, $_GET["id_zone"], 2);

$com = getCommissions($co_pmp);

if(isset($qte_ord["nb"]) && isset($qte_sup["nb"]))
{
	$qte_total = $qte_ord["nb"] + $qte_sup["nb"];
}
elseif(isset($qte_ord["nb"]) && !isset($qte_sup["nb"]))
{
	$qte_total = $qte_ord["nb"] + 0;
}
elseif(!isset($qte_ord["nb"]) && isset($qte_sup["nb"]))
{
	$qte_total = 0 + $qte_sup["nb"];
}
else
{
	$qte_total = 0;
}

$cmdes = getCommandesZone($co_pmp, $_GET["id_zone"]);
if(!empty($_POST["exporter_cotations"]))
{
	ExporterListeCmdCotations($co_pmp, $cmdes);
}

if (isset($message))
{
?>
<div class="toast <?= $message_type; ?>">
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
<div class="bloc" <?php if($plages_prix["cotation"] == '0') { echo 'style="height: 500px;"'; } ?>>
<?php
if($plages_prix["cotation"] == '0')
{
?>
<div class="toast info" style="margin: 0;">
	<div class="message-icon  info-icon">
		<i class="fa-solid fa-circle-exclamation"></i>
	</div>
	<div class="message-content ">
		<div class="message-type">
			Info
			<div class="ligne"></div>
		</div>
		<div class="message" style="margin-top: 13px;">
			Aucune cotation en attente pour cette zone
		</div>
	</div>
</div>
<?php
}
else
{
?>
	<form method="post" id="FormID">
		<label class="label-title" style="margin: 0;">Infos générales</label>
		<div class="ligne"></div>
		<label class="col-form-label">Voici une liste de personnes concernées par une nouvelle commande globale. Si ces commandes vous intéressent, je vous invite à nous proposer votre meilleur tarif pour récupérer ce marché (incluant notre commission de <?= $com["comord"]; ?> € HT par m3 livré). </label>
		<div class="row">
			<div class="col-sm-3" >
				<div class="form-inline">
					<label for="com_ord" class="col-sm-7 col-form-label" style="padding-left:0;">Commission ord (€/m3) :</label>
					<div class="col-sm-5" style="padding:0;border-right: 1px solid #0b242436;">
						<input type="text" name="com_ord" value="<?= $com["comord"]; ?>,00 € HT" class="form-control" style="width:100%;background-color: #e0e1df00!important;" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-inline">
					<label for="com_sup" class="col-sm-7 col-form-label" style="padding-left:0;">Commission sup (€/m3) :</label>
					<div class="col-sm-5" style="padding:0">
						<input type="text" name="com_sup" value="<?= $com["comsup"]; ?>,00 € HT" class="form-control" style="width:100%;background-color: #e0e1df00!important;" disabled="disabled">
					</div>
				</div>
			</div>
		</div>
		<hr>
		<label class="label-title" style="margin: 0;">Proposition de tarif</label>
		<div class="ligne"></div>
		<p class="col-form-label">Prix au litre TTC livré (Exemple : 1.215)</p>
		<div class="row">
			<div class="col-sm-12">
				<div class="tableau" style="height: 205px;max-width: 350px;margin: 0 0 0 0;">
					<table class="table">
						<thead>
							<tr>
								<th style="border-bottom: 2px solid #ef83514a!important;">Qté</th>
								<th style="border-bottom: 2px solid #ef83514a!important;width: 137px;" class="text-center">Prix O</th>
								<th style="border-bottom: 2px solid #ef83514a!important;width: 137px;" class="text-center">Prix S</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="font-weight: 900;">500</td>
<?php
							if($plages_prix["ord500"] != NULL && !isset($pas_de_prix))
							{
?>
								<td class="text-center checkbox_ord500"><input type="hidden" name="prix_500_ord" value="<?= $plages_prix["ord500"]; ?>"><?= $plages_prix["ord500"]; ?>
									<a href="zone_cotations.php?id_crypte=<?= $_GET["id_crypte"]; ?>&id_zone=<?= $_GET["id_zone"]; ?>&prix=prix_500_ord" style="color: #8f9b9b;cursor: pointer;font-size: 14px;margin-left: 7%;"><i class="far fa-times-circle"></i></a>
								</td>
								<td style="display:none;" class="text-center saisie_ord500"><input type="text" class="form-control text-center" name="prix_500_ord" value="<?= $plages_prix["ord500"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>

<?php
							}
							else
							{
								if(isset($_POST["prix_500_ord"]) && $_POST["prix_500_ord"] > 0)
								{
?>
								<td class="text-center saisie_ord500"><input type="text" class="form-control text-center" name="prix_500_ord" value="<?= $_POST["prix_500_ord"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
								else
								{
?>
								<td class="text-center checkbox_ord500">
									<span class="edit" style="color: #8f9b9b;cursor: pointer;font-size: 14px;"><label class="col-form-label" style="margin-right: 7%;font-size: 13px;padding: 0;">Saisir prix</label><input type="hidden" name="prix_500_ord" value="ord500"><i class="far fa-edit"></i></span>
								</td>
								<td style="display:none;" class="text-center saisie_ord500"><input type="text" class="form-control text-center form_ord500" name="prix_500_ord" value="" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
							}

							if($plages_prix["sup500"] != NULL && !isset($pas_de_prix))
							{
?>
								<td class="text-center checkbox_sup500"><input type="hidden" name="prix_500_sup" value="<?= $plages_prix["sup500"]; ?>"><?= $plages_prix["sup500"]; ?>
									<a href="zone_cotations.php?id_crypte=<?= $_GET["id_crypte"]; ?>&id_zone=<?= $_GET["id_zone"]; ?>&prix=prix_500_sup" style="color: #8f9b9b;cursor: pointer;font-size: 14px;margin-left: 7%;"><i class="far fa-times-circle"></i></a>
								</td>
								<td style="display:none;" class="text-center saisie_sup500"><input type="text" class="form-control text-center" name="prix_500_sup" value="<?= $plages_prix["sup500"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
							}
							else
							{
								if(isset($_POST["prix_500_sup"]) && $_POST["prix_500_sup"] > 0)
								{
?>
								<td class="text-center saisie_sup500"><input type="text" class="form-control text-center" name="prix_500_sup" value="<?= $_POST["prix_500_sup"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
								else
								{
?>
								<td class="text-center checkbox_sup500">
									<span class="edit" style="color: #8f9b9b;cursor: pointer;font-size: 14px;"><label class="col-form-label" style="margin-right: 7%;font-size: 13px;padding: 0;">Saisir prix</label><input type="hidden" name="prix_500_sup" value="sup500"><i class="far fa-edit"></i></span>
								</td>
								<td style="display:none;" class="text-center saisie_sup500"><input type="text" class="form-control text-center form_sup500" name="prix_500_sup" value="" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
							}
?>
							</tr>
							<tr>
								<td style="font-weight: 900;">1 000</td>
<?php
							if($plages_prix["ord1000"] != NULL && !isset($pas_de_prix))
							{
?>
								<td class="text-center checkbox_ord1000"><input type="hidden" name="prix_1000_ord" value="<?= $plages_prix["ord1000"]; ?>"><?= $plages_prix["ord1000"]; ?>
									<a href="zone_cotations.php?id_crypte=<?= $_GET["id_crypte"]; ?>&id_zone=<?= $_GET["id_zone"]; ?>&prix=prix_1000_ord" style="color: #8f9b9b;cursor: pointer;font-size: 14px;margin-left: 7%;"><i class="far fa-times-circle"></i></a>
								</td>
								<td style="display:none;" class="text-center saisie_ord1000"><input type="text" class="form-control text-center" name="prix_1000_ord" value="<?= $plages_prix["ord1000"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
							}
							else
							{
								if(isset($_POST["prix_1000_ord"]) && $_POST["prix_1000_ord"] > 0)
								{
?>
								<td class="text-center saisie_ord1000"><input type="text" class="form-control text-center" name="prix_1000_ord" value="<?= $_POST["prix_1000_ord"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
								else
								{
?>
								<td class="text-center checkbox_ord1000">
									<span class="edit" style="color: #8f9b9b;cursor: pointer;font-size: 14px;"><label class="col-form-label" style="margin-right: 7%;font-size: 13px;padding: 0;">Saisir prix</label><input type="hidden" name="prix_1000_ord" value="ord1000"><i class="far fa-edit"></i></span>
								</td>
								<td style="display:none;" class="text-center saisie_ord1000"><input type="text" class="form-control text-center form_ord1000" name="prix_1000_ord" value="" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
							}

							if($plages_prix["sup1000"] != NULL && !isset($pas_de_prix))
							{
?>
								<td class="text-center checkbox_sup1000"><input type="hidden" name="prix_1000_sup" value="<?= $plages_prix["sup1000"]; ?>"><?= $plages_prix["sup1000"]; ?>
									<a href="zone_cotations.php?id_crypte=<?= $_GET["id_crypte"]; ?>&id_zone=<?= $_GET["id_zone"]; ?>&prix=prix_1000_sup" style="color: #8f9b9b;cursor: pointer;font-size: 14px;margin-left: 7%;"><i class="far fa-times-circle"></i></a>
								</td>
								<td style="display:none;" class="text-center saisie_sup1000"><input type="text" class="form-control text-center" name="prix_1000_sup" value="<?= $plages_prix["sup1000"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
							}
							else
							{
								if(isset($_POST["prix_1000_sup"]) && $_POST["prix_1000_sup"] > 0)
								{
?>
								<td class="text-center saisie_sup1000"><input type="text" class="form-control text-center" name="prix_1000_sup" value="<?= $_POST["prix_1000_sup"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
								else
								{
?>
								<td class="text-center checkbox_sup1000">
									<span class="edit" style="color: #8f9b9b;cursor: pointer;font-size: 14px;"><label class="col-form-label" style="margin-right: 7%;font-size: 13px;padding: 0;">Saisir prix</label><input type="hidden" name="prix_1000_sup" value="sup1000"><i class="far fa-edit"></i></span>
								</td>
								<td style="display:none;" class="text-center saisie_sup1000"><input type="text" class="form-control text-center form_sup1000" name="prix_1000_sup" value="" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
							}
?>							</tr>
							<tr>
								<td style="font-weight: 900;">2 000</td>
<?php
							if($plages_prix["ord2000"] != NULL && !isset($pas_de_prix))
							{
?>
								<td class="text-center checkbox_ord2000"><input type="hidden" name="prix_2000_ord" value="<?= $plages_prix["ord2000"]; ?>"><?= $plages_prix["ord2000"]; ?>
									<a href="zone_cotations.php?id_crypte=<?= $_GET["id_crypte"]; ?>&id_zone=<?= $_GET["id_zone"]; ?>&prix=prix_2000_ord" style="color: #8f9b9b;cursor: pointer;font-size: 14px;margin-left: 7%;"><i class="far fa-times-circle"></i></a>
								</td>
								<td style="display:none;" class="text-center saisie_ord2000"><input type="text" class="form-control text-center" name="prix_2000_ord" value="<?= $plages_prix["ord2000"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
							}
							else
							{
								if(isset($_POST["prix_2000_ord"]) && $_POST["prix_2000_ord"] > 0)
								{
?>
								<td class="text-center saisie_ord2000"><input type="text" class="form-control text-center" name="prix_2000_ord" value="<?= $_POST["prix_2000_ord"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
								else
								{
?>
								<td class="text-center checkbox_ord2000">
									<span class="edit" style="color: #8f9b9b;cursor: pointer;font-size: 14px;"><label class="col-form-label" style="margin-right: 7%;font-size: 13px;padding: 0;">Saisir prix</label><input type="hidden" name="prix_2000_ord" value="ord2000"><i class="far fa-edit"></i></span>
								</td>
								<td style="display:none;" class="text-center saisie_ord2000"><input type="text" class="form-control text-center form_ord2000" name="prix_2000_ord" value="" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
							}

							if($plages_prix["sup2000"] != NULL && !isset($pas_de_prix))
							{
?>
								<td class="text-center checkbox_sup2000"><input type="hidden" name="prix_2000_sup" value="<?= $plages_prix["sup2000"]; ?>"><?= $plages_prix["sup2000"]; ?>
									<a href="zone_cotations.php?id_crypte=<?= $_GET["id_crypte"]; ?>&id_zone=<?= $_GET["id_zone"]; ?>&prix=prix_2000_sup" style="color: #8f9b9b;cursor: pointer;font-size: 14px;margin-left: 7%;"><i class="far fa-times-circle"></i></a>
								</td>
								<td style="display:none;" class="text-center saisie_sup2000"><input type="text" class="form-control text-center" name="prix_2000_sup" value="<?= $plages_prix["sup2000"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
							}
							else
							{
								if(isset($_POST["prix_2000_sup"]) && $_POST["prix_2000_sup"] > 0)
								{
?>
								<td class="text-center saisie_sup2000"><input type="text" class="form-control text-center" name="prix_2000_sup" value="<?= $_POST["prix_2000_sup"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
								else
								{
?>
								<td class="text-center checkbox_sup2000">
									<span class="edit" style="color: #8f9b9b;cursor: pointer;font-size: 14px;"><label class="col-form-label" style="margin-right: 7%;font-size: 13px;padding: 0;">Saisir prix</label><input type="hidden" name="prix_2000_sup" value="sup2000"><i class="far fa-edit"></i></span>
								</td>
								<td style="display:none;" class="text-center saisie_sup2000"><input type="text" class="form-control text-center form_sup2000" name="prix_2000_sup" value="" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
							}
?>
							</tr>
							<tr>
								<td style="font-weight: 900;">3 000</td>
<?php
							if($plages_prix["ord3000"] != NULL && !isset($pas_de_prix))
							{
?>
								<td class="text-center checkbox_ord3000"><input type="hidden" name="prix_3000_ord" value="<?= $plages_prix["ord3000"]; ?>"><?= $plages_prix["ord3000"]; ?>
									<a href="zone_cotations.php?id_crypte=<?= $_GET["id_crypte"]; ?>&id_zone=<?= $_GET["id_zone"]; ?>&prix=prix_3000_ord" style="color: #8f9b9b;cursor: pointer;font-size: 14px;margin-left: 7%;"><i class="far fa-times-circle"></i></a>
								</td>
								<td style="display:none;" class="text-center saisie_ord3000"><input type="text" class="form-control text-center" name="prix_3000_ord" value="<?= $plages_prix["ord3000"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
							}
							else
							{
								if(isset($_POST["prix_3000_ord"]) && $_POST["prix_3000_ord"] > 0)
								{
?>
								<td class="text-center saisie_ord3000"><input type="text" class="form-control text-center" name="prix_3000_ord" value="<?= $_POST["prix_3000_ord"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
								else
								{
?>
								<td class="text-center checkbox_ord3000">
									<span class="edit" style="color: #8f9b9b;cursor: pointer;font-size: 14px;"><label class="col-form-label" style="margin-right: 7%;font-size: 13px;padding: 0;">Saisir prix</label><input type="hidden" name="prix_3000_ord" value="ord3000"><i class="far fa-edit"></i></span>
								</td>
								<td style="display:none;" class="text-center saisie_ord3000"><input type="text" class="form-control text-center form_ord3000" name="prix_3000_ord" value="" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
							}
?>
<?php
							if($plages_prix["sup3000"] != NULL && !isset($pas_de_prix))
							{
?>
								<td class="text-center checkbox_sup3000"><input type="hidden" name="prix_3000_sup" value="<?= $plages_prix["sup3000"]; ?>"><?= $plages_prix["sup3000"]; ?>
									<a href="zone_cotations.php?id_crypte=<?= $_GET["id_crypte"]; ?>&id_zone=<?= $_GET["id_zone"]; ?>&prix=prix_3000_sup" style="color: #8f9b9b;cursor: pointer;font-size: 14px;margin-left: 7%;"><i class="far fa-times-circle"></i></a>
								</td>
								<td style="display:none;" class="text-center saisie_sup3000"><input type="text" class="form-control text-center" name="prix_3000_sup" value="<?= $plages_prix["sup3000"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
							}
							else
							{
								if(isset($_POST["prix_3000_sup"]) && $_POST["prix_3000_sup"] > 0 && empty($_POST["effacer_prix"]))
								{
?>
								<td class="text-center saisie_sup3000"><input type="text" class="form-control text-center" name="prix_3000_sup" value="<?= $_POST["prix_3000_sup"]; ?>" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
								else
								{
?>
								<td class="text-center checkbox_sup3000">
									<span class="edit" style="color: #8f9b9b;cursor: pointer;font-size: 14px;"><label class="col-form-label" style="margin-right: 7%;font-size: 13px;padding: 0;">Saisir prix</label><input type="hidden" name="prix_3000_sup" value="sup3000"><i class="far fa-edit"></i></span>
								</td>
								<td style="display:none;" class="text-center saisie_sup3000"><input type="text" class="form-control text-center form_sup3000" name="prix_3000_sup" value="" style="padding: 0 !important;background-color: #253b3b0d!important;">  </td>
<?php
								}
							}
?>
							</tr>
						</tbody>
					</table>
				</div>
<?php
				// if($plages_prix["dateheure_cotation"] != NULL && !isset($pas_de_prix) && !isset($nouvelle_cot))
				if($plages_prix["dateheure_cotation"] != NULL)
				{
					$date = date_create($plages_prix["dateheure_cotation"]);
       				$dates = date_format($date, 'H:i');
					$date1 = date($plages_prix["dateheure_cotation"]); // Date du jour
					setlocale(LC_TIME, "fr_FR");
?>
					<label class="col-form-label" style="font-weight: 900;"> Vous nous avez envoyé une cotation le  <?= (new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(new DateTime($date1)); ?> à <?= $dates; ?></label>					
					<button type="button" class="btn btn-warning" name="button" data-bs-toggle="modal" data-bs-target="#EffacerPrix">EFFACER TOUS LES PRIX</button>
					<div class="modal fade" id="EffacerPrix" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" style="max-width: 40%;">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Etes-vous sûr de vouloir effacer tous les prix ?</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i></button>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
									<input type="submit" name="effacer_prix" id="effacer_prix" class="btn btn-primary" value="Oui">
								</div>
							</div>
						</div>
					</div>
<?php
				}
?>

			</div>
			<div class="col-sm-12">
				<label class="col-form-label">Commentaire</label><br>
<?php
				if(!isset($nouvelle_cot))
				{
?>
				<textarea name="commentaire_cotations" class="form-control" rows="5" cols="80"><?php if(isset($_POST["commentaire_cotations"])) { echo $_POST["commentaire_cotations"]; } elseif(isset($plages_prix["message"])) { echo $plages_prix["message"]; }  ?></textarea>
<?php
				}
				else
				{
?>
				<textarea name="commentaire_cotations" class="form-control" rows="5" cols="80"></textarea>
<?php
				}
?>
			</div>
			<div class="col-sm-12 align-self-end text-center">
				<p class="col-form-label">Merci de remplir le tableau avec votre proposition de tarif et de l'envoyer à POEMOP.</p>
				<input type="submit" name="envoyer_tarif" value="ENVOYER TARIF POEMOP" class="btn btn-primary">
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-sm-2">
				<label class="label-title" style="margin: 0;">Liste des commandes</label>
				<div class="ligne"></div>
			</div>
			<div class="col-sm-6">
				<div class="form-inline">
					<label for="vol_ord" class="col-sm-1 col-form-label" style="padding-left: 0;padding-right: 0;padding-top: 0;font-weight: 600;">Total</label>
					<div class="col-sm-4" style="padding:0">
						<input type="text" name="vol_ord" value="<?= $qte_total; ?>" class="form-control" style="padding-left: 0;font-weight: 500;padding-right: 0;padding-top: 0;width:100%;background-color: #e0e1df00!important;" disabled="disabled">
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-inline">
					<label for="vol_ord" class="col-sm-2 col-form-label" style="padding-left:0;">Qté Ordinaire :</label>
					<div class="col-sm-4" style="padding:0">
						<input type="text" name="vol_ord" value="<?php if(isset($qte_ord["nb"])) { echo $qte_ord["nb"]; } else { echo "0"; } ?>" class="form-control" style="width:100%; background-color: #e0e1df00!important;" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-inline">
					<label for="vol_ord" class="col-sm-5 col-form-label" style="padding-left:0;">Qté Supérieur :</label>
					<div class="col-sm-7" style="padding:0">
						<input type="text" name="vol_ord" value="<?php if(isset($qte_sup["nb"])) { echo $qte_sup["nb"]; } else { echo "0"; } ?>" class="form-control" style="width:100%;background-color: #e0e1df00!important;" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-sm-3 text-end">
				<input type="submit" name="exporter_cotations" value="EXPORTER" class="btn btn-secondary" style="width:215px;">

			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">


				<div class="tableau" style="height: auto;margin-top: 1%;">
					<table class="table" id="trie_ord">
						<thead>
							<tr>
								<th>Ville <i class="fal fa-arrow-to-bottom" style="margin-left:2%"></i></th>
								<th>Code postal <i class="fal fa-arrow-to-bottom" style="margin-left:2%"></i></th>
								<th>Quantité <i class="fal fa-arrow-to-bottom" style="margin-left:2%"></i></th>
							</tr>
						</thead>
						<tbody>
<?php
						while($cmde = mysqli_fetch_array($cmdes_ord))
						{
?>
							<tr>
								<td><?= $cmde["ville"]; ?></td>
								<td><?= $cmde["code_postal"]; ?></td>
								<td><?= $cmde["cmd_qte"]; ?></td>
							</tr>
<?php
						}
?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-sm-6">

				<div class="tableau" style="height: auto;margin-top: 1%;">
					<table class="table" id="trie_sup">
						<thead>
							<tr>
								<th>Ville <i class="fal fa-arrow-to-bottom" style="margin-left:2%"></i></th>
								<th>Code postal <i class="fal fa-arrow-to-bottom" style="margin-left:2%"></i></th>
								<th>Quantité <i class="fal fa-arrow-to-bottom" style="margin-left:2%"></i></th>
							</tr>
						</thead>
						<tbody>
<?php
						while($cmde = mysqli_fetch_array($cmdes_sup))
						{
?>
							<tr>
								<td><?= $cmde["ville"]; ?></td>
								<td><?= $cmde["code_postal"]; ?></td>
								<td><?= $cmde["cmd_qte"]; ?></td>
							</tr>
<?php
						}
?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</form>
<?php
}
?>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="js/script_groupements.js" charset="utf-8"></script>
