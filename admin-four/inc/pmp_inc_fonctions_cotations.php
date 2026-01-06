<?php
// En prod on utilise la config mail principale (pas la version _dev)
include_once "pmp_inc_fonctions_mail.php";

// function getZones(&$co_pmp)
// {
// 	$four_id = $_SESSION["four_id"];
//
// 	$query = "  SELECT pmp_fournisseur_zone.libelle, pmp_fournisseur_zone.id
// 				FROM pmp_fournisseur_zone
// 				LEFT JOIN pmp_zone_cp
// 				ON pmp_fournisseur_zone.id = pmp_zone_cp.zone_id
// 				LEFT JOIN pmp_utilisateur
// 				ON pmp_zone_cp.code_postal_id = pmp_utilisateur.code_postal_id
// 				LEFT JOIN pmp_commande
// 				ON pmp_utilisateur.user_id = pmp_commande.user_id
// 				WHERE pmp_fournisseur_zone.fournisseur_id = '$four_id'
// 				AND pmp_zone_cp.actif = '1'
// 				AND pmp_commande.cmd_status  BETWEEN '10' AND '15'
// 				AND pmp_commande.cmd_qte > '0'
// 				AND pmp_commande.groupe_cmd != ''
// 				GROUP BY pmp_fournisseur_zone.id ";
// 	$res = my_query($co_pmp, $query);
// 	return $res;
// }
function getMailContact(&$co_pmp, $id)
{
	$query = "  SELECT mail
				FROM pmp_fournisseur_contact
				WHERE id = '$id' ";
	$res = my_query($co_pmp, $query);
	$contact = mysqli_fetch_array($res);
	return $contact;
}

function getZones(&$co_pmp)
{
	$four_id = $_SESSION["four_id"];

	$query = "  SELECT pmp_fournisseur_zone.libelle, pmp_fournisseur_zone.id
				FROM pmp_fournisseur_zone
				LEFT JOIN pmp_zone_cp
				ON pmp_fournisseur_zone.id = pmp_zone_cp.zone_id
				LEFT JOIN pmp_utilisateur
				ON pmp_zone_cp.code_postal_id = pmp_utilisateur.code_postal_id
				LEFT JOIN pmp_commande
				ON pmp_utilisateur.user_id = pmp_commande.user_id
				WHERE pmp_fournisseur_zone.fournisseur_id = '$four_id'
				AND pmp_zone_cp.actif = '1'
				AND pmp_fournisseur_zone.cotation = '1'
				-- AND pmp_commande.cmd_status  BETWEEN '10' AND '15'
				-- AND pmp_commande.cmd_qte > '0'
				-- AND pmp_commande.groupe_cmd != ''
				GROUP BY pmp_fournisseur_zone.id ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getCommissions(&$co_pmp)
{
	$four_id = $_SESSION["four_id"];
	$query = "  SELECT comord, comsup
				FROM pmp_fournisseur
				WHERE id = '$four_id' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}


function getPlagesPrix(&$co_pmp, $zone_id)
{
	$query = "  SELECT *
				FROM pmp_fournisseur_zone
				WHERE id = '$zone_id' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getDernierGroupement(&$co_pmp, $zone_id)
{
	$four_id = $_SESSION["four_id"];
	$query = "  SELECT pmp_regroupement.id, pmp_regroupement.date_grp
				FROM pmp_regroupement, pmp_regrp_zone
				WHERE pmp_regroupement.id = pmp_regrp_zone.regrp_id
				AND pmp_regrp_zone.zone_id = '$zone_id'
				ORDER BY pmp_regroupement.date_grp DESC LIMIT 1 ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getCommandesZoneFuel(&$co_pmp, $zone_id, $fuel)
{
	$four_id = $_SESSION["four_id"];

	$query = "  SELECT pmp_commande.cmd_qte, pmp_utilisateur.ville, pmp_utilisateur.code_postal
				FROM pmp_fournisseur_zone
				LEFT JOIN pmp_zone_cp
				ON pmp_fournisseur_zone.id = pmp_zone_cp.zone_id
				LEFT JOIN pmp_utilisateur
				ON pmp_zone_cp.code_postal_id = pmp_utilisateur.code_postal_id
				LEFT JOIN pmp_commande
				ON pmp_utilisateur.user_id = pmp_commande.user_id
				WHERE pmp_zone_cp.zone_id = '$zone_id'
				AND pmp_zone_cp.actif = '1'
				AND pmp_commande.cmd_status = '15'
				AND pmp_commande.cmd_typefuel = '$fuel'
				AND pmp_commande.cmd_qte > '0'
				AND pmp_commande.groupe_cmd != ''
				AND  pmp_fournisseur_zone.fournisseur_id = '$four_id'
				GROUP BY pmp_commande.id ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getCommandesZone(&$co_pmp, $zone_id)
{
	$four_id = $_SESSION["four_id"];

	$query = "  SELECT pmp_commande.cmd_qte, pmp_utilisateur.ville, pmp_utilisateur.code_postal, pmp_commande.cmd_typefuel
				FROM pmp_fournisseur_zone
				LEFT JOIN pmp_zone_cp
				ON pmp_fournisseur_zone.id = pmp_zone_cp.zone_id
				LEFT JOIN pmp_utilisateur
				ON pmp_zone_cp.code_postal_id = pmp_utilisateur.code_postal_id
				LEFT JOIN pmp_commande
				ON pmp_utilisateur.user_id = pmp_commande.user_id
				WHERE pmp_zone_cp.zone_id = '$zone_id'
				AND pmp_zone_cp.actif = '1'
				AND pmp_commande.cmd_status  = '15'
				AND pmp_commande.cmd_qte > '0'
				AND  pmp_fournisseur_zone.fournisseur_id = '$four_id'
				GROUP BY pmp_commande.id ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getCommandesZoneCotations(&$co_pmp, $zone_id)
{
	$four_id = $_SESSION["four_id"];
	$query = "  SELECT pmp_commande.id
				FROM pmp_fournisseur_zone
				LEFT JOIN pmp_zone_cp
				ON pmp_fournisseur_zone.id = pmp_zone_cp.zone_id
				LEFT JOIN pmp_utilisateur
				ON pmp_zone_cp.code_postal_id = pmp_utilisateur.code_postal_id
				LEFT JOIN pmp_commande
				ON pmp_utilisateur.user_id = pmp_commande.user_id
				WHERE pmp_zone_cp.zone_id = '$zone_id'
				AND pmp_zone_cp.actif = '1'
				AND pmp_commande.cmd_status  = '15'
				AND pmp_commande.cmd_qte > '0'
				AND pmp_fournisseur_zone.fournisseur_id = '$four_id'
				GROUP BY pmp_commande.id ";
				$res = my_query($co_pmp, $query);
				return $res;
}

function getCommandesZoneStats(&$co_pmp, $zone_id, $fuel)
{
	$four_id = $_SESSION["four_id"];
	$query = "  SELECT SUM(pmp_commande.cmd_qte) AS nb
				FROM pmp_fournisseur_zone
				LEFT JOIN pmp_zone_cp
				ON pmp_fournisseur_zone.id = pmp_zone_cp.zone_id
				LEFT JOIN pmp_utilisateur
				ON pmp_zone_cp.code_postal_id = pmp_utilisateur.code_postal_id
				LEFT JOIN pmp_commande
				ON pmp_utilisateur.user_id = pmp_commande.user_id
				WHERE pmp_zone_cp.zone_id = '$zone_id'
				AND pmp_zone_cp.actif = '1'
				AND pmp_commande.cmd_status = '15'
				AND pmp_commande.cmd_typefuel = '$fuel'
				AND pmp_commande.cmd_qte > '0'
				AND pmp_commande.groupe_cmd != ''
				AND  pmp_fournisseur_zone.fournisseur_id = '$four_id'
				 ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

if(!empty($_POST["valider_commentaire"]))
{
	if(isset($_POST["commentaire_cotations"]))
	{
		$commentaire_cotations = $_POST["commentaire_cotations"];
		$id = $_GET["id_zone"];
		$query = "  UPDATE pmp_fournisseur_zone
					SET message = '$commentaire_cotations'
					WHERE id = '$id' ";
		$res = my_query($co_pmp, $query);
		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "Votre commentaire sur cette zone a bien été enregistré.";
			return $res;
		}
		else
		{
			return false;
		}
	}
	else
	{
		$message_titre = "Erreur";
		$message_type = "no";
		$message_icone = "fa-times";
		$message = "Le champs commentaire est vide.";
	}
}

$host = $_SERVER['HTTP_HOST'];
$isDev = str_contains($host, 'dev') || str_contains($host, 'localhost'); // détecte l'environnement dev
$baseUrl = $isDev ? "https://dev.plus-on-est-moins-on-paie.fr" : "https://plus-on-est-moins-on-paie.fr";

if (isset($_GET["prix"])) {
    $id_zone = $_GET["id_zone"];
    $id_crypte = $_GET["id_crypte"] ?? '';

    // Mapping GET param -> colonne
    $prixMap = [
        "prix_500_ord"  => "ord500",
        "prix_500_sup"  => "sup500",
        "prix_1000_ord" => "ord1000",
        "prix_1000_sup" => "sup1000",
        "prix_2000_ord" => "ord2000",
        "prix_2000_sup" => "sup2000",
        "prix_3000_ord" => "ord3000",
        "prix_3000_sup" => "sup3000",
    ];

    $prixKey = $_GET["prix"];
    if (isset($prixMap[$prixKey])) {
        $col = $prixMap[$prixKey];
        $update = "UPDATE pmp_fournisseur_zone SET $col = NULL WHERE id = '$id_zone'";
        $res = my_query($co_pmp, $update);

        header('Location: ' . $baseUrl . '/admin-four/zone_cotations.php?id_crypte=' . $id_crypte . '&id_zone=' . $id_zone);
        exit;
    }
}

// Effacer tous les prix
if (!empty($_POST["effacer_prix"])) {
    $id_zone = $_GET["id_zone"];
    $id_crypte = $_GET["id_crypte"] ?? '';

    $update = "UPDATE pmp_fournisseur_zone
               SET ord500 = NULL, ord1000 = NULL, ord2000 = NULL, ord3000 = NULL,
                   sup500 = NULL, sup1000 = NULL, sup2000 = NULL, sup3000 = NULL,
                   dateheure_cotation = NULL
               WHERE id = '$id_zone'";
    $res = my_query($co_pmp, $update);

    if ($res) {
        header('Location: ' . $baseUrl . '/admin-four/zone_cotations.php?id_crypte=' . $id_crypte . '&id_zone=' . $id_zone);
        exit;
    }
}
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!$recharge && !empty($_POST["envoyer_tarif"])) {
    $volumes = [500, 1000, 2000, 3000];
    $types = ['ord', 'sup'];
    $prix = [];

    // --- Nettoyage des inputs ---
    foreach ($volumes as $v) {
        foreach ($types as $t) {
            $key = "prix_{$v}_{$t}";
            $val = trim(str_replace([' ', ','], ['.', '.'], $_POST[$key] ?? ''));
            $prix[$v][$t] = ($val === '') ? null : floatval($val);
        }
    }

    // --- Vérification : au moins un prix ---
    $hasPrix = false;
    foreach ($prix as $p) foreach ($p as $v) if (!empty($v)) $hasPrix = true;
    if (!$hasPrix) {
        $message_titre = "Erreur";
        $message_type = "no";
        $message_icone = "fa-times";
        $message = "Un prix minimum doit être saisi pour envoyer la proposition.";
    }

    // --- Vérification des plages autorisées ---
    foreach ($volumes as $v) {
        foreach ($types as $t) {
            if ($prix[$v][$t] && ($prix[$v][$t] < 0.5 || $prix[$v][$t] > 2)) {
                $message_titre = "Erreur";
                $message_type = "no";
                $message_icone = "fa-times";
                $message = "Le prix pour {$v}L ({$t}) doit être compris entre 0.500 et 2.000 euros.";
            }
        }
    }

    // --- Vérification : sup >= ord ---
    foreach ($volumes as $v) {
        if ($prix[$v]['ord'] && $prix[$v]['sup'] && $prix[$v]['sup'] < $prix[$v]['ord']) {
            $message_titre = "Erreur";
            $message_type = "no";
            $message_icone = "fa-times";
            $message = "Le prix supérieur ($v L) ne doit pas être inférieur au prix ordinaire.";
        }
    }

    // --- Vérifications croisées (progression des volumes) ---
    $combinaisons = [[500, 1000], [1000, 2000], [2000, 3000]];
    foreach ($combinaisons as [$small, $big]) {
        foreach ($types as $t) {
            if ($prix[$small][$t] && $prix[$big][$t] && $prix[$big][$t] > $prix[$small][$t]) {
                $message_titre = "Erreur";
                $message_type = "no";
                $message_icone = "fa-times";
                $message = "Le prix pour {$big} L ({$t}) ne doit pas être supérieur au prix pour {$small} L.";
            }
        }
    }

    // --- Envoi + enregistrement ---
    if (!isset($message)) {
        $commentaire = $_POST["commentaire_cotations"] ?? "";
        $id_zone = intval($_GET["id_zone"]);
        $date = mysqli_real_escape_string($co_pmp, date('Y-m-d H:i:s'));

        // Construction dynamique de la requête UPDATE
        $fields = [];
        foreach ($volumes as $v) {
            foreach ($types as $t) {
                $col = ($t === 'ord' ? 'ord' : 'sup') . $v;
                $val = is_null($prix[$v][$t]) ? "NULL" : $prix[$v][$t];
                $fields[] = "$col = $val";
            }
        }

        $update = "
            UPDATE pmp_fournisseur_zone
            SET " . implode(', ', $fields) . ",
                dateheure_cotation = '$date',
                message = '" . mysqli_real_escape_string($co_pmp, $commentaire) . "'
            WHERE id = '$id_zone'
        ";
        $res = my_query($co_pmp, $update);

        if ($res) {
            $query = "SELECT libelle, fournisseur_id, mail_to, mail_cc
                      FROM pmp_fournisseur_zone WHERE id = '$id_zone'";
            $res_zone = mysqli_fetch_array(my_query($co_pmp, $query));

            if ($res_zone) {
                $id_four = $res_zone["fournisseur_id"];
                $res_four = mysqli_fetch_array(my_query($co_pmp, "SELECT nom, email FROM pmp_fournisseur WHERE id = '$id_four'"));

                $zone = $res_zone["libelle"];
                $four = $res_four["nom"];
                $mail_to = $res_zone["mail_to"];

                if (!empty($mail_to)) {
                    foreach (explode(";", $mail_to) as $id_contact) {
                        $mail = getMailContact($co_pmp, $id_contact);
                        if (!empty($mail["mail"])) {
                            $dest = $mail["mail"];
                            my_query($co_pmp, "
                                UPDATE pmp_mail_auto
                                SET etat = 'E'
                                WHERE destinataires = '$dest' AND modele_id = '53' AND etat = 'A'
                            ");

                            // Envoi du mail
                            envoyerMailPropositionTarif(
                                $co_pmp,
                                $prix[500]['ord'], $prix[500]['sup'],
                                $prix[1000]['ord'], $prix[1000]['sup'],
                                $prix[2000]['ord'], $prix[2000]['sup'],
                                $prix[3000]['ord'], $prix[3000]['sup'],
                                $four, $zone, $commentaire, $dest
                            );
                        }
                    }
                }

                $message_type = "success";
                $message_icone = "fa-check";
                $message_titre = "Succès";
                $message = "Votre proposition de tarif a bien été envoyée à POEMOP.";
            }
        }
    }
}

function ExporterListeCmdCotations(&$co_pmp, $res)
{
	$fichier = fopen('export/export-commandes-' . $_SESSION['four'] .'-zone-' . $_GET["id_zone"] . '.xls', 'w');
	fclose($fichier);

	$fichier = fopen('export/export-commandes-' . $_SESSION['four'] .'-zone-' . $_GET["id_zone"] . '.xls', 'w+');
	fwrite($fichier, chr(239) . chr(187) . chr(191));
	$chaine = "";
	$col = "Ville;Code Postal;Quantité;Fuel";
	fwrite($fichier,$col."\r\n");

	while($export = mysqli_fetch_array($res))
	{
		if ($export["cmd_typefuel"] == 1){ $type = 'Ordinaire';}
		if ($export["cmd_typefuel"] == 2){ $type = 'Supérieur';}
		if ($export["cmd_typefuel"] == 3){ $type = 'GNR';}

		$chaine = '"' . $export["ville"] .'";"' . $export["code_postal"] . '";"' . $export["cmd_qte"] . '";"' . $type . '"';

		fwrite($fichier,$chaine."\r\n");
	}

	fclose($fichier);

	header('Location: /admin-four/export/export-commandes-' . $_SESSION['four'] .'-zone-' . $_GET["id_zone"] . '.xls');
	header('Content-Encoding: UTF-8');
	header('Content-type: text/xls; charset=UTF-8');
}
?>
