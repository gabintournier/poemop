<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

ini_set('error_log', '/tmp/php_error.log');
error_reporting(E_ALL);

function remplacerLiensPourDev(string $content): string
{
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $isDev = str_contains($host, 'dev') || str_contains($host, 'localhost');

    if (!$isDev) {
        return $content;
    }

    return preg_replace_callback(
        '#https?://([^/]+)(/[^"\\s]*)?#i',
        function ($matches) {
            $host = $matches[1];
            $path = $matches[2] ?? '';

            if (str_starts_with($host, 'dev.')) {
                return "https://{$host}{$path}";
            }

            if (str_contains($host, 'plus-on-est-moins-on-paie.fr')) {
                return "https://dev.plus-on-est-moins-on-paie.fr{$path}";
            }

            return "https://{$host}{$path}";
        },
        $content
    );
}

function EnvoyerMailFromToCC($sujet, $message, $from, $to, $cc)
{
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $isDev = str_contains($host, 'dev') || str_contains($host, 'localhost');
    $to_initial = $to;

    require_once __DIR__ . '/../../newsletter/src/Exception.php';
    require_once __DIR__ . '/../../newsletter/src/PHPMailer.php';
    require_once __DIR__ . '/../../newsletter/src/SMTP.php';

    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'error_log';
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

        // From
        $mail->setFrom("info@poemop.fr", 'Poemop');
        if (strlen($from) == 0) {
            $mail->addReplyTo("info@poemop.fr", 'Poemop');
        } else {
            $mail->addReplyTo($from);
        }

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
            $message = $bandeau . "<hr>" . remplacerLiensPourDev($message);
            $sujet = "[ENV. DEV] - " . $sujet;
        }

        // To
        $tok = strtok($to, ";");
        while ($tok !== false) {
            $mail->addAddress(trim($tok));
            $tok = strtok(";");
        }

        // CC
        if (!empty($cc)) {
            $cck = strtok($cc, ";");
            while ($cck !== false) {
                $mail->addCC(trim($cck));
                $cck = strtok(";");
            }
        }

        // Bcc
        $mail->addBCC('trace@prixfioul.fr');
        $mail->addBCC('info@poemop.fr');

        // Suite
        $mail->isHTML(true);
        $mail->Subject = $sujet;
        $mail->Body = $message;
        $mail->AltBody = html2ascii($message);

        // DKIM
        $mail->DKIM_domain = 'poemop.fr';
        $mail->DKIM_private = __DIR__ . '/../../newsletter/dkim/dkim.private';
        $mail->DKIM_selector = 'default';
        $mail->DKIM_passphrase = '';
        $mail->DKIM_identity = $mail->From;
        $mail->Encoding = "base64";

        $mail->send();
    } catch (Exception $e) {
        mail("info@poemop.fr", "plus-on-est-moins-on-paie.fr : Erreur Mail ", $sujet . " " . $to . " " . $message);
    }
}

function EnvoyerMailFromToThunder($sujet, $message, $from, $to)
{
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $isDev = str_contains($host, 'dev') || str_contains($host, 'localhost');
    $to_initial = $to;

    require_once __DIR__ . '/../../newsletter/src/Exception.php';
    require_once __DIR__ . '/../../newsletter/src/PHPMailer.php';
    require_once __DIR__ . '/../../newsletter/src/SMTP.php';

    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'error_log';
        $mail->setLanguage('fr', __DIR__ . '/../../newsletter/src/');
        $mail->CharSet = "UTF-8";

        // Sécurité TLS
        $mail->isSMTP();
        $mail->Host = 'mail.poemop.fr';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Username = 'info@poemop.fr';
        $mail->Password = 'i~#nuWVCp4p63fyn';

        // Alignement TLS
        $mail->SMTPAutoTLS = true;
        $mail->Hostname = 'poemop.fr';
        $mail->Helo = 'poemop.fr';

        // DKIM
        $mail->DKIM_domain = 'poemop.fr';
        $mail->DKIM_selector = 'default';
        $mail->DKIM_identity = 'info@poemop.fr';

        // From
        $mail->setFrom("info@poemop.fr", 'POEMOP');
        if (strlen($from) == 0)
            $mail->addReplyTo("info@poemop.fr", 'POEMOP');
        else
            $mail->addReplyTo($from);

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
            $message = $bandeau . "<hr>" . remplacerLiensPourDev($message);
            $sujet = "[ENV. DEV - MAIL THUNDER] - " . $sujet;
        }

        // To
        $tok = strtok($to, ";");
        while ($tok !== false) {
            $mail->addAddress(trim($tok));
            $tok = strtok(";");
        }

        // Bcc
        $mail->addBCC('trace@prixfioul.fr');

        // Suite
        $mail->isHTML(true);
        $mail->Subject = $sujet;
        $mail->Body = $message;
        $mail->AltBody = html2ascii($message);

        // DKIM
        $mail->DKIM_domain = 'poemop.fr';
        $mail->DKIM_private = __DIR__ . '/../../newsletter/dkim/dkim.private';
        $mail->DKIM_selector = 'default';
        $mail->DKIM_passphrase = '';
        $mail->DKIM_identity = $mail->From;
        $mail->Encoding = "base64";
        $mail->addCustomHeader("List-Unsubscribe", '<info@poemop.fr>, <https://plus-on-est-moins-on-paie.fr/?email=' . $to . '>');

        $mail->send();
        $mail->SmtpClose();
    } catch (Exception $e) {
        mail("info@poemop.fr", "Erreur EnvoyerMailFromTo() " . $sujet, "Sujet:" . $sujet . "\nDestinataire:" . $to . "\nErreur:" . $mail->ErrorInfo . "\nMessage:\n" . $message);
        return false;
    }
    return true;
}

function EnvoyerDemandeDeCotations(&$co_pmp, $id_crypte, $id_zone, $mails, $zone, $cc, $date, $heure, $four)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/modele/MODELE_demande_cotation.html");
    $message = str_replace("ID_CRYPTE", $id_crypte, $message);
    $message = str_replace("ID_ZONE", $id_zone, $message);
    $message = str_replace("DATE_MAIL", $date, $message);
    $message = str_replace("HEURE_MAIL", $heure, $message);
    $sujet = "Demande de cotations pour le fournisseur " . $four . " et la zone : " . $zone;
    EnvoyerMailFromToCC($sujet, $message, "", $mails, $cc);
}

function EnvoyerDemandeDeRecap(&$co_pmp, $id_crypte, $id_zone, $mails, $zone, $cc, $four)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/modele/MODELE_saisie_recap_poemop.html");
    $message = str_replace("ID_CRYPTE", $id_crypte, $message);
    $sujet = "Récapitulatif commandes groupées POEMOP - " . $four;
    EnvoyerMailFromToCC($sujet, $message, "", $mails, $cc);
}

function EnvoyerDemandeDeRecapAF(&$co_pmp, $id_crypte, $id_zone, $mails, $zone, $cc, $four, $id_four_af, $zone_af_id)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/modele/MODELE_saisie_recap_poemop_af.html");
    $message = str_replace("ID_CRYPTE", $id_crypte, $message);
    $message = str_replace("ID_FOUR", $id_four_af, $message);
    $message = str_replace("ZONE_ID", $zone_af_id, $message);
    $sujet = "Récapitulatif commandes groupées POEMOP et ACHAT FIOUL - " . $four;
    EnvoyerMailFromToCC($sujet, $message, "", $mails, $cc);
}

function EnvoyerMailThunder(&$co_pmp, $id_crypte, $mails, $nom_fichier, $type)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/modele/thunder/MODELE_" . $nom_fichier . ".html");
    $message = str_replace("ID_CRYPTE", $id_crypte, $message);
    $sujet = "POEMOP - " . $type;
    EnvoyerMailFromToThunder($sujet, $message, "", $mails);
}

function EnvoyerMailThunderDate(&$co_pmp, $id_crypte, $mails, $nom_fichier, $type, $date_grp)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/modele/thunder/MODELE_" . $nom_fichier . ".html");
    $message = str_replace("ID_CRYPTE", $id_crypte, $message);
    $message = str_replace("DATE_GRP", $date_grp, $message);
    $sujet = "POEMOP - " . $type;
    EnvoyerMailFromToThunder($sujet, $message, "", $mails);
}
?>