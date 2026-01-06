<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

ini_set('error_log', '/tmp/php_error.log');
error_reporting(E_ALL);

function EnvoyerMailFromToPieceJointe($sujet, $message, $from, $to, $piece_jointe)
{
    $isDev = str_contains($_SERVER['HTTP_HOST'] ?? '', 'dev') || str_contains($_SERVER['HTTP_HOST'] ?? '', 'localhost');
    $to_initial = $to; // garder les destinataires d’origine

    // DKIM
    require_once __DIR__ . '/../../newsletter/src/Exception.php';
    require_once __DIR__ . '/../../newsletter/src/PHPMailer.php';
    require_once __DIR__ . '/../../newsletter/src/SMTP.php';

    $mail = new PHPMailer(true); // true = exceptions activées
    try {
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'error_log';
        $dkimDomain = 'poemop.fr';
        $dkimSelector = 'default';
        $dkimPrivate = __DIR__ . '/../../newsletter/dkim/dkim.private';
        $dkimPassphrase = '';
        $mail->setLanguage('fr', __DIR__ . '/../../newsletter/src/');
        $mail->CharSet = "UTF-8";
        // SMTP authentifié
        $mail->isSMTP();
        $mail->Host = 'mail.poemop.fr';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Username = 'info@poemop.fr';
        $mail->Password = 'i~#nuWVCp4p63fyn';
        $mail->SMTPAutoTLS = true;
        $mail->Hostname = 'poemop.fr';
        $mail->Helo = 'poemop.fr';

        // Gestion DEV
        if ($isDev) {
            global $co_pmp; // connexion MySQL existante si nécessaire
            $sql = "SELECT maildev FROM site_settings WHERE `key` = 'POEMOP' LIMIT 1";
            $result = mysqli_query($co_pmp, $sql);
            if ($result && $row = mysqli_fetch_assoc($result)) {
                $maildev = $row['maildev'];
                if ($maildev) {
                    $to = $maildev; // redirige tous les mails vers l’email DEV
                }
            }

            $bandeau = '
                <div style="color:red;font-weight:bold;margin-bottom:15px;">
                    Environnement de développement (DEV)<br>
                    <span style="font-weight:normal;">En production, ce mail aurait été envoyé à :</span><br>
                    <span style="font-weight:normal;">' . htmlspecialchars($to_initial) . '</span>
                </div>';
            $message = $bandeau . "<hr>" . (strlen(trim($message)) ? $message : '<i>(Pas de contenu transmis, erreur chargement mail source.)</i>');
            $sujet = "[ENV. DEV] - " . $sujet;
        }

        // From
        $mail->setFrom("info@poemop.fr", 'Poemop');
        if (strlen($from) == 0)
            $mail->addReplyTo("info@poemop.fr", 'Poemop');
        else
            $mail->addReplyTo($from);

        // To
        $tok = strtok($to, ";");
        while ($tok !== false) {
            $mail->addAddress($tok);
            $tok = strtok(";");
        }

        // Bcc
        $mail->addBCC('trace@prixfioul.fr');


        // Suite
        $mail->isHTML(true);
        $mail->Subject = $sujet;
        $mail->Body = $message;
        $mail->AltBody = html2ascii($message);
        $mail->addAttachment(__DIR__ . '/../export/' . $piece_jointe);

        $mail->DKIM_domain = $dkimDomain;
        $mail->DKIM_private = $dkimPrivate;
        $mail->DKIM_selector = $dkimSelector;
        $mail->DKIM_passphrase = $dkimPassphrase;
        $mail->DKIM_identity = $mail->From;
        $mail->Encoding = "base64";

        $mail->send();
    } catch (Exception $e) {
        error_log('SMTP error (PJ): ' . $mail->ErrorInfo);
        mail("erreur@prixfioul.fr", "plus-on-est-moins-on-paie.Fr : Erreur PJ Mail ", $sujet . " " . $to . " " . $message);
        mail("info@poemop.fr", "plus-on-est-moins-on-paie.Fr : Erreur PJ Mail ", $sujet . " " . $to . " " . $message);
    }
}

function EnvoyerMailFrom($sujet, $message, $from, $to)
{
    $isDev = str_contains($_SERVER['HTTP_HOST'] ?? '', 'dev') || str_contains($_SERVER['HTTP_HOST'] ?? '', 'localhost');
    $to_initial = $to;

    require_once __DIR__ . '/../../newsletter/src/Exception.php';
    require_once __DIR__ . '/../../newsletter/src/PHPMailer.php';
    require_once __DIR__ . '/../../newsletter/src/SMTP.php';

    $mail = new PHPMailer(true); // true = exceptions activées
    try {
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'error_log';
        $dkimDomain = 'poemop.fr';
        $dkimSelector = 'default';
        $dkimPrivate = __DIR__ . '/../../newsletter/dkim/dkim.private';
        $dkimPassphrase = '';

        $mail->setLanguage('fr', __DIR__ . '/../../newsletter/src/');
        $mail->CharSet = "UTF-8";
        // SMTP authentifié
        $mail->isSMTP();
        $mail->Host = 'mail.poemop.fr';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Username = 'info@poemop.fr';
        $mail->Password = 'i~#nuWVCp4p63fyn';
        $mail->SMTPAutoTLS = true;
        $mail->Hostname = 'poemop.fr';
        $mail->Helo = 'poemop.fr';

        // Gestion DEV
        if ($isDev) {
            global $co_pmp;
            $sql = "SELECT maildev FROM site_settings WHERE `key` = 'POEMOP' LIMIT 1";
            $result = mysqli_query($co_pmp, $sql);
            if ($result && $row = mysqli_fetch_assoc($result)) {
                $maildev = $row['maildev'];
                if ($maildev) {
                    $to = $maildev;
                }
            }

            $bandeau = '
                <div style="color:red;font-weight:bold;margin-bottom:15px;">
                    Environnement de développement (DEV)<br>
                    <span style="font-weight:normal;">En production, ce mail aurait été envoyé à :</span><br>
                    <span style="font-weight:normal;">' . htmlspecialchars($to_initial) . '</span>
                </div>';
            $message = $bandeau . "<hr>" . (strlen(trim($message)) ? $message : '<i>(Pas de contenu transmis, erreur chargement mail source.)</i>');
            $sujet = "[ENV. DEV] - " . $sujet;
        }

        // From
        $mail->setFrom("info@poemop.fr", 'Poemop');
        if (strlen($from) == 0)
            $mail->addReplyTo("info@poemop.fr", 'Poemop');
        else
            $mail->addReplyTo($from);

        // To
        $tok = strtok($to, ";");
        while ($tok !== false) {
            $mail->addAddress($tok);
            $tok = strtok(";");
        }

        // Bcc
        $mail->addBCC('trace@prixfioul.fr');

        // Suite
        $mail->isHTML(true);
        $mail->Subject = $sujet;
        $mail->Body = $message;
        $mail->AltBody = html2ascii($message);

        $mail->DKIM_domain = $dkimDomain;
        $mail->DKIM_private = $dkimPrivate;
        $mail->DKIM_selector = $dkimSelector;
        $mail->DKIM_passphrase = $dkimPassphrase;
        $mail->DKIM_identity = $mail->From;
        $mail->Encoding = "base64";

        $mail->send();
    } catch (Exception $e) {
        error_log('SMTP error: ' . $mail->ErrorInfo);
        mail("erreur@prixfioul.fr", "plus-on-est-moins-on-paie.Fr : Erreur Mail ", $sujet . " " . $to . " " . $message);
        mail("info@poemop.fr", "plus-on-est-moins-on-paie.Fr : Erreur Mail ", $sujet . " " . $to . " " . $message);
    }
}

// Autres fonctions inchangées
function EnvoyerMailSaisieRecap($sujet, $message, $piece_jointe)
{
    EnvoyerMailFromToPieceJointe($sujet, $message, "", "info@poemop.fr", $piece_jointe);
}

function EnvoyerMailRappelClient(&$co_pmp, $tel_fixe, $email_client)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/modele/MODELE_relance_client.html");
    $message = str_replace("tel_four", $tel_fixe, $message);
    $sujet = "Suite à votre commande sur notre site POEMOP";
    EnvoyerMailFrom($sujet, $message, "", "amelie@prixfioul.fr");
}

function envoyerMailPropositionTarif(&$co_pmp, $prix1, $prix2, $prix3, $prix4, $prix5, $prix6, $prix7, $prix8, $fournisseur, $zone, $commentaire, $mails)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/modele/MODELE_proposition_tarifs.html");
    $message = str_replace("NOM_FOURNISSEUR", $fournisseur, $message);
    $message = str_replace("NOM_ZONE", $zone, $message);
    $prix = [$prix1, $prix2, $prix3, $prix4, $prix5, $prix6, $prix7, $prix8];
    for ($i = 0; $i < 8; $i++) {
        if ($prix[$i] == "NULL")
            $prix[$i] = " ";
    }
    $replacements = ["PRIX_500_ORD", "PRIX_500_SUP", "PRIX_1000_ORD", "PRIX_1000_SUP", "PRIX_2000_ORD", "PRIX_2000_SUP", "PRIX_3000_ORD", "PRIX_3000_SUP"];
    for ($i = 0; $i < 8; $i++) {
        $message = str_replace($replacements[$i], $prix[$i], $message);
    }
    $message = str_replace("COMMENTAIRE", $commentaire, $message);
    $sujet = "Proposition de tarif pour le fournisseur " . $fournisseur . " zone : " . $zone;
    EnvoyerMailFrom($sujet, $message, $mails, "info@poemop.fr");
}

function envoyerMailPropositionTarif2(&$co_pmp, $prix1, $prix2, $prix3, $prix4, $prix5, $prix6, $prix7, $prix8, $fournisseur, $zone, $commentaire, $mail_contact)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/modele/MODELE_proposition_tarifs.html");
    $message = str_replace("NOM_FOURNISSEUR", $fournisseur, $message);
    $message = str_replace("NOM_ZONE", $zone, $message);
    $prix = [$prix1, $prix2, $prix3, $prix4, $prix5, $prix6, $prix7, $prix8];
    for ($i = 0; $i < 8; $i++) {
        if ($prix[$i] == "NULL")
            $prix[$i] = " ";
    }
    $replacements = ["PRIX_500_ORD", "PRIX_500_SUP", "PRIX_1000_ORD", "PRIX_1000_SUP", "PRIX_2000_ORD", "PRIX_2000_SUP", "PRIX_3000_ORD", "PRIX_3000_SUP"];
    for ($i = 0; $i < 8; $i++) {
        $message = str_replace($replacements[$i], $prix[$i], $message);
    }
    $message = str_replace("COMMENTAIRE", $commentaire, $message);
    $sujet = "Proposition de tarif pour le fournisseur " . $fournisseur . " zone : " . $zone;
    EnvoyerMailFrom($sujet, $message, $mail_contact, "info@poemop.fr");
}
?>