<?php
// plus-on-est-moins-on-paie.fr/newsletter/_envoyer_mail_auto.php

include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/../inc/pmp_inc_fonctions.php";
include_once __DIR__ . "/../inc/pmp_inc_fonctions_compte.php";
include_once __DIR__ . "/../inc/pmp_inc_fonctions_mail.php"; // EnvoyerMailFromTo

// -----------------------------------------------------------------------------
// PARAMÈTRES GLOBAUX DE SÉCURISATION
// -----------------------------------------------------------------------------

// Nombre MAX de mails traités par exécution du script (toutes catégories confondues)
define('MAX_MAILS_GLOBAL', 300);

// Fichier de log local pour tracer ce que fait le script
define('MAIL_AUTO_LOG_FILE', __DIR__ . '/mail_auto.log');

// Compteur global
$GLOBALS['MAILS_TRAITES_GLOBAL'] = 0;

// -----------------------------------------------------------------------------
// FONCTION DE LOG SIMPLE
// -----------------------------------------------------------------------------
function logMailAuto($message)
{
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    @file_put_contents(MAIL_AUTO_LOG_FILE, $line, FILE_APPEND);
}

// -----------------------------------------------------------------------------
// DÉTECTION ENVIRONNEMENT (DEV / PROD)
// -----------------------------------------------------------------------------
function isDevEnv()
{
    // Mode CLI : argument "dev" => DEV
    if (php_sapi_name() === 'cli') {
        global $argv;
        if (isset($argv[1]) && $argv[1] === 'dev') {
            return true;
        }
        return false;
    }

    // Mode HTTP : host contenant "dev" ou "localhost"
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (strpos($host, 'dev') !== false || strpos($host, 'localhost') !== false) {
        return true;
    }

    return false;
}

// -----------------------------------------------------------------------------
// MARQUER MAIL EN COURS DE TRAITEMENT
// -----------------------------------------------------------------------------
function MarqueMailEnCours(&$co_pmp, $mail_id)
{
    $mail_id = mysqli_real_escape_string($co_pmp, $mail_id);
    $query = "
        UPDATE pmp_mail_auto
        SET etat = 'P', date_action = NOW()
        WHERE id = '$mail_id'
          AND etat = 'A'
    ";
    my_query($co_pmp, $query);
}

// -----------------------------------------------------------------------------
// MARQUER MAIL ENVOYÉ
// -----------------------------------------------------------------------------
function MarqueMailEnvoye(&$co_pmp, $mail_id)
{
    $mail_id = mysqli_real_escape_string($co_pmp, $mail_id);
    $query = "
        UPDATE pmp_mail_auto
        SET etat = 'E', date_action = NOW()
        WHERE id = '$mail_id'
    ";
    my_query($co_pmp, $query);
}

// -----------------------------------------------------------------------------
// MARQUER MAIL ERREUR
// -----------------------------------------------------------------------------
function MarqueMailErreur(&$co_pmp, $mail_id)
{
    $mail_id = mysqli_real_escape_string($co_pmp, $mail_id);
    $query = "
        UPDATE pmp_mail_auto
        SET etat = 'X', date_action = NOW()
        WHERE id = '$mail_id'
    ";
    my_query($co_pmp, $query);
}

// -----------------------------------------------------------------------------
// CHARGER UN MODÈLE DE MAIL
// -----------------------------------------------------------------------------
function ChargeMailModele(&$co_pmp, $modele_id)
{
    $modele_id = mysqli_real_escape_string($co_pmp, $modele_id);
    $query = "
        SELECT sujet, sujet_complet, dest, nom_fichier, type
        FROM pmp_mail_auto_modele
        WHERE id = '$modele_id'
    ";
    $res = my_query($co_pmp, $query);
    return mysqli_fetch_array($res);
}

// -----------------------------------------------------------------------------
// ENVOI D'UN MAIL AUTO (1 MAIL)
// -----------------------------------------------------------------------------
function EnvoyerMailAuto(&$co_pmp, $pmp_mail_auto)
{
    $pmp_mail_auto_modele = ChargeMailModele($co_pmp, $pmp_mail_auto['modele_id']);

    if ($pmp_mail_auto_modele['type'] == "1") {
        $pmp_mail_auto_modele['nom_fichier'] = "MODELE_" . $pmp_mail_auto_modele['nom_fichier'];
    }

    $modele_path = __DIR__ . "/modele/" . $pmp_mail_auto_modele['nom_fichier'] . ".html";
    if (!is_file($modele_path)) {
        logMailAuto("ERREUR: modèle introuvable pour mail_auto id={$pmp_mail_auto['id']} fichier={$modele_path}");
        MarqueMailErreur($co_pmp, $pmp_mail_auto['id']);
        return false;
    }

    $message_html = file_get_contents($modele_path);
    $mail_to = "";

    if (strlen($pmp_mail_auto['user_id']) > 0) {
        $jjj_users = ChargeCompteJoomla($co_pmp, $pmp_mail_auto['user_id']);
        $mail_to = $jjj_users['email'];

        $pmp_utilisateur = ChargeCompteFioul($co_pmp, $pmp_mail_auto['user_id']);

        if (strlen($pmp_utilisateur['id_crypte']) < 10) {
            $user_id = $pmp_mail_auto['user_id'];
            $id_crypte = password_hash($user_id, PASSWORD_DEFAULT);
            $user_id = mysqli_real_escape_string($co_pmp, $user_id);
            $id_crypte = mysqli_real_escape_string($co_pmp, $id_crypte);

            $query = "
                UPDATE pmp_utilisateur
                SET id_crypte = '$id_crypte'
                WHERE user_id = '$user_id'
            ";
            my_query($co_pmp, $query);
            $pmp_utilisateur['id_crypte'] = $id_crypte;
        }

        $message_html = str_replace("ID_CRYPTE", $pmp_utilisateur['id_crypte'], $message_html);
    } else {
        $mail_to = $pmp_mail_auto['destinataires'];
    }

    // Remplacement des variables de template via chaine_cle
    $cle_valeur = strtok($pmp_mail_auto['chaine_cle'], "\n");
    while ($cle_valeur !== false) {
        $parts = explode("|", $cle_valeur, 2);
        if (count($parts) == 2) {
            list($cle, $valeur) = $parts;
            $message_html = str_replace($cle, $valeur, $message_html);
        }
        $cle_valeur = strtok("\n");
    }

    logMailAuto("ENVOI: mail_auto id={$pmp_mail_auto['id']} vers {$mail_to}");

    if (EnvoyerMailFromTo($pmp_mail_auto_modele['sujet'], $message_html, "", $mail_to)) {
        MarqueMailEnvoye($co_pmp, $pmp_mail_auto['id']);
        return true;
    } else {
        MarqueMailErreur($co_pmp, $pmp_mail_auto['id']);
        return false;
    }
}

// -----------------------------------------------------------------------------
// FONCTION PRINCIPALE : BALANCE X MAILS SUR UNE REQUÊTE DONNÉE
// -----------------------------------------------------------------------------
function balanceXmails(&$co_pmp, $query, $nb)
{
    $isDev = isDevEnv();

    // Si on a déjà atteint le plafond global, on ne fait plus rien
    if ($GLOBALS['MAILS_TRAITES_GLOBAL'] >= MAX_MAILS_GLOBAL) {
        print " => Plafond global de " . MAX_MAILS_GLOBAL . " mails atteint, on stoppe.<br>";
        logMailAuto("STOP: plafond global atteint (" . MAX_MAILS_GLOBAL . " mails)");
        return;
    }

    // On ajuste la limite locale en fonction de ce qu'il reste possible d'envoyer
    $reste_possible = MAX_MAILS_GLOBAL - $GLOBALS['MAILS_TRAITES_GLOBAL'];
    $nb = min($nb, $reste_possible);

    if ($nb <= 0) {
        print " => Aucun mail à envoyer (limite globale atteinte).<br>";
        return;
    }

    $query_limit = $query . " LIMIT " . intval($nb);
    $res = my_query($co_pmp, $query_limit);

    $mails = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $mails[] = $row;
    }

    if (empty($mails)) {
        print " => Aucun mail à envoyer<br>";
        return;
    }

    logMailAuto("INFO: " . count($mails) . " mails chargés pour traitement (limite locale $nb)");

    foreach ($mails as $pmp_mail_auto) {
        if ($GLOBALS['MAILS_TRAITES_GLOBAL'] >= MAX_MAILS_GLOBAL) {
            print " => Plafond global atteint en cours de boucle, on stoppe.<br>";
            logMailAuto("STOP: plafond global atteint en cours de boucle");
            return;
        }

        // On marque le mail comme "en cours" pour éviter les doubles traitements
        MarqueMailEnCours($co_pmp, $pmp_mail_auto['id']);

        // Mode DEV : regroupement de mails par modèle + date_insertion
        if ($isDev) {
            $modele_id = mysqli_real_escape_string($co_pmp, $pmp_mail_auto['modele_id']);
            $date_insertion = mysqli_real_escape_string($co_pmp, $pmp_mail_auto['date_insertion']);

            $q_group = "
                SELECT id, user_id, modele_id, destinataires, chaine_cle, date_insertion
                FROM pmp_mail_auto
                WHERE etat = 'P'
                  AND modele_id = '$modele_id'
                  AND date_insertion = '$date_insertion'
                  AND date_a_envoyer < NOW()
            ";
            $res_group = my_query($co_pmp, $q_group);

            $rows = [];
            $dest_list = [];

            while ($r = mysqli_fetch_assoc($res_group)) {
                if (!empty($r['destinataires'])) {
                    $parts = array_filter(array_map('trim', explode(';', $r['destinataires'])));
                    foreach ($parts as $p) {
                        if (!in_array($p, $dest_list)) {
                            $dest_list[] = $p;
                        }
                    }
                }
                $rows[] = $r;
            }

            if (count($rows) > 1) {
                $base = $rows[0];
                $base['destinataires'] = implode(';', $dest_list);

                if (EnvoyerMailAuto($co_pmp, $base)) {
                    foreach ($rows as $r_sent) {
                        MarqueMailEnvoye($co_pmp, $r_sent['id']);
                    }
                    $GLOBALS['MAILS_TRAITES_GLOBAL'] += count($rows);
                    print " => Mail auto GROUPÉ envoyé (modele_id {$base['modele_id']}) à : "
                        . implode(', ', $dest_list) . "<br><br>";
                    logMailAuto("OK: mail groupé modele_id={$base['modele_id']} vers " . implode(', ', $dest_list));
                } else {
                    foreach ($rows as $r_err) {
                        MarqueMailErreur($co_pmp, $r_err['id']);
                    }
                    $GLOBALS['MAILS_TRAITES_GLOBAL'] += count($rows);
                    print " => Mail auto GROUPÉ NON envoyé (erreur)<br><br>";
                    logMailAuto("ERREUR: mail groupé modele_id={$base['modele_id']} non envoyé");
                }

                continue;
            }
        }

        // MODE NORMAL (PROD)
        $pmp_mail_auto_modele = ChargeMailModele($co_pmp, $pmp_mail_auto['modele_id']);

        if ($pmp_mail_auto_modele['type'] == "1") {

            // Si destinataire direct (email dans le champ destinataires)
            if (!empty($pmp_mail_auto['destinataires'])) {
                $user_id = GetUserId($co_pmp, $pmp_mail_auto['destinataires']);

                if (!empty($user_id)) {
                    $pmp_mail_auto['user_id'] = $user_id;
                } else {
                    // Destinataire invalide => mail d'erreur + marquage X
                    EnvoyerMailFromTo(
                        "Erreur balanceXmails()",
                        "fichier:/newsletter/_envoyer_mail_auto.php\npmp_mail_auto{destinataires}:"
                        . $pmp_mail_auto['destinataires'] . "\n",
                        "",
                        "erreur@prixfioul.fr"
                    );
                    MarqueMailErreur($co_pmp, $pmp_mail_auto['id']);
                    $GLOBALS['MAILS_TRAITES_GLOBAL']++;
                    logMailAuto("ERREUR: destinataire invalide pour mail_auto id={$pmp_mail_auto['id']}");
                    continue;
                }
            }

            // Envoi final si user_id défini
            if (!empty($pmp_mail_auto['user_id'])) {
                if (EnvoyerMailAuto($co_pmp, $pmp_mail_auto)) {
                    print " => Mail auto envoyé au user_id {$pmp_mail_auto['user_id']}<br><br>";
                    logMailAuto("OK: mail_auto id={$pmp_mail_auto['id']} user_id={$pmp_mail_auto['user_id']}");
                } else {
                    print " => Mail auto NON envoyé (ERR) user_id {$pmp_mail_auto['user_id']}<br><br>";
                    logMailAuto("ERREUR: envoi mail_auto id={$pmp_mail_auto['id']} user_id={$pmp_mail_auto['user_id']}");
                }
                $GLOBALS['MAILS_TRAITES_GLOBAL']++;
            } else {
                print " => Mail auto NON envoyé car pas de user_id<br>";
                logMailAuto("WARN: pas de user_id pour mail_auto id={$pmp_mail_auto['id']}");
                MarqueMailErreur($co_pmp, $pmp_mail_auto['id']);
                $GLOBALS['MAILS_TRAITES_GLOBAL']++;
            }

        } else {
            // Mail sans user_id (type != 1)
            if (!empty($pmp_mail_auto['destinataires'])) {
                if (EnvoyerMailAuto($co_pmp, $pmp_mail_auto)) {
                    print " => Mail auto envoyé à {$pmp_mail_auto['destinataires']}<br><br>";
                    logMailAuto("OK: mail_auto id={$pmp_mail_auto['id']} dest={$pmp_mail_auto['destinataires']}");
                } else {
                    print " => Mail auto NON envoyé à {$pmp_mail_auto['destinataires']}<br><br>";
                    logMailAuto("ERREUR: envoi mail_auto id={$pmp_mail_auto['id']} dest={$pmp_mail_auto['destinataires']}");
                }
                $GLOBALS['MAILS_TRAITES_GLOBAL']++;
            } else {
                logMailAuto("WARN: mail_auto id={$pmp_mail_auto['id']} sans destinataires");
                MarqueMailErreur($co_pmp, $pmp_mail_auto['id']);
                $GLOBALS['MAILS_TRAITES_GLOBAL']++;
            }
        }
    }
}

// -----------------------------------------------------------------------------
// LANCEMENT DU SCRIPT
// -----------------------------------------------------------------------------

print "<h1>Mail auto</h1>";
logMailAuto("=== DÉBUT EXÉCUTION _envoyer_mail_auto.php ===");

// Provider normaux
print "<h2>Provider normaux</h2>";

$query_normaux = "
    SELECT id, user_id, modele_id, destinataires, chaine_cle, date_insertion
    FROM pmp_mail_auto
    WHERE etat = 'A'
      AND date_a_envoyer < NOW()
      AND LOWER(destinataires) NOT LIKE '%orange.fr'
      AND LOWER(destinataires) NOT LIKE '%orange.com'
      AND LOWER(destinataires) NOT LIKE '%wanadoo.fr'
      AND LOWER(destinataires) NOT LIKE '%wanadoo.com'
      AND LOWER(destinataires) NOT LIKE '%live.fr'
      AND LOWER(destinataires) NOT LIKE '%live.com'
      AND LOWER(destinataires) NOT LIKE '%free.fr%'
      AND LOWER(destinataires) NOT LIKE '%laposte.net'
      AND destinataires != ''
    ORDER BY priorite, date_insertion
";
balanceXmails($co_pmp, $query_normaux, 45);

// Providers chiants
print "<h2>Provider chiants</h2>";

$providers = [
    'orange.fr',
    'orange.com',
    'wanadoo.fr',
    'wanadoo.com',
    'live.fr',
    'live.com',
    'free.fr',
    'laposte.net'
];

foreach ($providers as $provider) {
    if ($GLOBALS['MAILS_TRAITES_GLOBAL'] >= MAX_MAILS_GLOBAL) {
        print " => Plafond global atteint, on ne traite plus d'autres providers.<br>";
        break;
    }

    $query_provider = "
        SELECT id, user_id, modele_id, destinataires, chaine_cle, date_insertion
        FROM pmp_mail_auto
        WHERE etat = 'A'
          AND date_a_envoyer < NOW()
          AND LOWER(destinataires) LIKE '%$provider'
        ORDER BY priorite, date_insertion
    ";
    balanceXmails($co_pmp, $query_provider, 10);
}

logMailAuto("=== FIN EXÉCUTION _envoyer_mail_auto.php : mails traités = " . $GLOBALS['MAILS_TRAITES_GLOBAL'] . " ===");
