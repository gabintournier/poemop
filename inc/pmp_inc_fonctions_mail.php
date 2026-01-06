<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/* On vérifie l'environnement : dev ou prod ? */
$host = $_SERVER['HTTP_HOST'] ?? '';
define('IS_DEV', (strpos($host, 'dev') !== false) || (strpos($host, 'localhost') !== false));

// URLs dynamiques selon l'environnement
define('BASE_URL', IS_DEV ? 'https://dev.plus-on-est-moins-on-paie.fr' : 'https://plus-on-est-moins-on-paie.fr');

/*
function EnvoyerMail($sujet, $message)
function EnvoyerMailCommande(&$co_af, $id_crypte)
*/
// Laisser from vide pour envoyer from AF

function EnvoyerMailFromTo($sujet, $message, $from, $to)
{
    $isDev = false;
    if (php_sapi_name() === 'cli') {
        global $argv;
        if (isset($argv[1]) && $argv[1] === 'dev')
            $isDev = true;
    } else {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $isDev = (strpos($host, 'dev') !== false) || (strpos($host, 'localhost') !== false);
    }

    // Fonction interne pour remplacer les liens en DEV
    $remplacerLiensPourDev = function (string $content) use ($isDev): string {
        if (!$isDev)
            return $content;

        return preg_replace_callback(
            '#https?://([^/]+)(/[^"\s]*)?#i',
            function ($matches) {
                $host = $matches[1];
                $path = $matches[2] ?? '';

                if (strpos($host, 'dev.') === 0) {
                    return "https://{$host}{$path}";
                }

                if (strpos($host, 'plus-on-est-moins-on-paie.fr') !== false) {
                    return "https://dev.plus-on-est-moins-on-paie.fr{$path}";
                }

                return $matches[0];
            },
            $content
        );
    };

    $to_initial = array_filter(array_map('trim', explode(";", $to)));

    require_once __DIR__ . '/../newsletter/src/Exception.php';
    require_once __DIR__ . '/../newsletter/src/PHPMailer.php';
    require_once __DIR__ . '/../newsletter/src/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        $mail->setLanguage('fr', __DIR__ . '/../newsletter/src/');
        $mail->CharSet = "UTF-8";

        // Debug SMTP en DEV uniquement
        if (IS_DEV) {
            $mail->SMTPDebug = 2; // Verbose
            $mail->Debugoutput = 'error_log';
        }

        $dkimDomain = 'poemop.fr';
        $dkimSelector = 'default';
        $dkimPrivate = __DIR__ . '/../newsletter/dkim/dkim.private';
        $dkimPassphrase = '';

        $mail->IsSMTP();
        $mail->Host = 'mail.poemop.fr';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Username = 'info@poemop.fr';
        $mail->Password = 'i~#nuWVCp4p63fyn';
        $mail->SMTPAutoTLS = true;
        $mail->Hostname = 'poemop.fr';
        $mail->DKIM_domain = 'poemop.fr';
        $mail->DKIM_selector = 'default';
        $mail->DKIM_identity = 'info@poemop.fr';


        // Adresse de rebond (Return-Path) cohérente
        $mail->Sender = 'info@poemop.fr';

        $mail->setFrom("info@poemop.fr", 'POEMOP');
        $mail->addReplyTo($from ?: "info@poemop.fr", 'POEMOP');

        // =======================
        // VERIF UTILISATEURS DÉSACTIVÉS (DEV + PROD)
        // =======================
        global $co_pmp;
        $dest_finales = [];

        foreach ($to_initial as $dest) {
            $dest = trim($dest);
            if ($dest === '')
                continue;

            $email = mysqli_real_escape_string($co_pmp, $dest);
            $sql = "SELECT disabled_account FROM pmp_utilisateur WHERE email = '$email' LIMIT 1";
            $res = mysqli_query($co_pmp, $sql);

            if ($res && $row = mysqli_fetch_assoc($res)) {

                // 🧠 On autorise certains types de mails même si le compte est désactivé
                $isReactivationMail = stripos($sujet, 'réactivation') !== false
                    || stripos($sujet, 'reactivation') !== false;
                $isDesactivationMail = stripos($sujet, 'désactivation') !== false
                    || stripos($sujet, 'desactivation') !== false;
                $isSuppressionMail = stripos($sujet, 'suppression') !== false
                    || stripos($sujet, 'supprimé') !== false
                    || stripos($sujet, 'sera supprimé') !== false;

                if (
                    !empty($row['disabled_account'])
                    && $row['disabled_account'] == 1
                    && !$isReactivationMail
                    && !$isDesactivationMail
                    && !$isSuppressionMail
                ) {
                    $msg = "❌ Mail non envoyé à $dest (compte désactivé)";
                    error_log($msg);

                    $prefix = $isDev ? "[ENV. DEV] - " : "";
                    @mail(
                        "erreur@prixfioul.fr",
                        $prefix . "Mail bloqué (compte désactivé)",
                        "Un mail « $sujet » n'est pas parvenu (fct EnvoyerMailFromTo) à $dest car son compte est désactivé."
                    );

                    continue; // on bloque tous les autres mails
                }
            }

            $dest_finales[] = $dest;
        }

        if (empty($dest_finales)) {
            error_log("⚠️ Aucun mail envoyé : tous les destinataires sont désactivés ou vides.");
            return false;
        }

        // =======================
        // ENVIRONNEMENT DEV
        // =======================
        if ($isDev) {
            $sql = "SELECT maildev FROM site_settings WHERE `key` = 'POEMOP' LIMIT 1";
            $result = mysqli_query($co_pmp, $sql);
            $maildev = ($result && $row = mysqli_fetch_assoc($result)) ? $row['maildev'] : '';

            $fallbackUsed = false;
            if (empty($maildev)) {
                $maildev = "g.tournier@thevenin-ducrot.fr";
                $fallbackUsed = true;
            }

            $listeMailDev = array_filter(array_map('trim', preg_split('/[;,]/', $maildev)));
            $destinatairesStr = implode(", ", $dest_finales);

            $bandeau = '<div style="color:red;font-weight:bold;margin-bottom:15px;">
                Environnement de développement (DEV)<br>
                <span style="font-weight:normal;">En production, ce message aurait été envoyé à :</span><br>
                <span style="font-weight:normal;">' . htmlspecialchars($destinatairesStr) . '</span>
            </div>';

            foreach ($listeMailDev as $destDev) {
                $mail->clearAllRecipients();
                $mail->addAddress($destDev);
                $mail->addBCC('trace@prixfioul.fr');

                $mail->isHTML(true);
                $mail->Body = $bandeau . "<hr>" . $remplacerLiensPourDev($message);
                $mail->Subject = $fallbackUsed ? "ERREUR FALLBACK MAILDEV - $sujet" : "[ENV. DEV] - $sujet";
                $mail->AltBody = html2ascii(strip_tags($mail->Body));

                $mail->DKIM_domain = $dkimDomain;
                $mail->DKIM_private = $dkimPrivate;
                $mail->DKIM_selector = $dkimSelector;
                $mail->DKIM_passphrase = $dkimPassphrase;
                $mail->DKIM_identity = $mail->From;
                $mail->Encoding = "base64";

                $mail->addCustomHeader(
                    "List-Unsubscribe",
                    '<info@poemop.fr>, <https://plus-on-est-moins-on-paie.fr/?email=' . $destDev . '>'
                );

                $mail->send();
                error_log('MAIL SENT (DEV) Message-ID=' . $mail->getLastMessageID() . ' subject=' . $sujet . ' to=' . $destDev);
            }

            $mail->SmtpClose();
            return true;
        }

        // =======================
        // ENVIRONNEMENT PROD
        // =======================
        foreach ($dest_finales as $dest) {
            $mail->clearAllRecipients();
            $mail->addAddress($dest);
            $mail->addBCC('trace@prixfioul.fr');

            $mail->isHTML(true);
            $mail->Subject = $sujet;
            $mail->Body = $message;
            $mail->AltBody = html2ascii(strip_tags($mail->Body));

            $mail->DKIM_domain = $dkimDomain;
            $mail->DKIM_private = $dkimPrivate;
            $mail->DKIM_selector = $dkimSelector;
            $mail->DKIM_passphrase = $dkimPassphrase;
            $mail->DKIM_identity = $mail->From;
            $mail->Encoding = "base64";

            $mail->addCustomHeader(
                "List-Unsubscribe",
                '<info@poemop.fr>, <https://plus-on-est-moins-on-paie.fr/?email=' . $dest . '>'
            );

            $mail->send();
            error_log('MAIL SENT (PROD) Message-ID=' . $mail->getLastMessageID() . ' subject=' . $sujet . ' to=' . $dest);
        }

        $mail->SmtpClose();
        return true;

    } catch (Exception $e) {
        mail(
            "info@poemop.fr",
            "Erreur EnvoyerMailFromTo() " . $sujet,
            "Sujet: $sujet\nDestinataires: " . implode(", ", $to_initial) . "\nErreur: " . $mail->ErrorInfo
        );
        return false;
    }
}

function EnvoyerMailErreur($sujet, $message)
{
    $dkimDomain = 'poemop.fr';
    $dkimSelector = 'default';
    $dkimPrivate = __DIR__ . '/../newsletter/dkim/dkim.private';
    $dkimPassphrase = '';

    // DKIM
    // # On genere un couple clé publique / clé privé en ligne de commande
    // # Attention ces fichiers doivent être accessible par le PHP !
    // cd /var/www/vhosts/achat-fioul.fr/httpdocs/newsletter/dkim
    // openssl genrsa -out dkim.private 1024
    // openssl rsa -in dkim.private -out dkim.public -pubout -outform PEM
    // ### ATTENTION cette clef publique n'est pas prise en compte (je ne sais pas comment faire) la clef publique prise en compte est celle de /etc/domainkeys et c'est celle la qu'il faut ajouter dans le DNS !
    // # On ajoute la clé publique dans le DNS
    // # --> TXT
    // # --> default._domainkey.achat-fioul.fr
    // # --> v=DKIM1;k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDbyK+u4ZSX0cFF5BpKDE1B3UvxjZ7Xwca7DepZ7n1p9ud9l2VcuEMTUUb/UMVgM4VFmmhgcTeJ9Tghfk6QV9e4VzNrzVd69X99Gc4ovivGSbAzC5rvVamcO7yL9R08DLyJtR/n1EIJsG8kwREsmH0ROeiKUfIYVZdsh1b3eschzQIDAQAB;
    // # Attention aux espaces et aux retour chariot et aux ;
    // # on vérifie que les DNS sont bien propagé avec www.mail-tester.com/spf-dkim-check
    // # on envoi un mail de test signé DKIM avec www.mail-tester.com/

    require_once __DIR__ . '/../newsletter/src/Exception.php';
    require_once __DIR__ . '/../newsletter/src/PHPMailer.php';

    $mail = new PHPMailer(true); // true = exceptions activées
    try {

        $mail->setLanguage('fr', __DIR__ . '/../newsletter/src/');
        $mail->CharSet = "UTF-8";

        // From
        $mail->setFrom("info@poemop.fr", 'Poemop');
        // Reply
        $mail->addReplyTo("info@poemop.fr", 'Poemop');
        // To
        $mail->addAddress('erreur@prixfioul.fr');
        // Suite
        $mail->isHTML(true);
        $mail->Subject = $sujet;
        $mail->Body = $message;
        $mail->AltBody = html2ascii($message);

        $mail->DKIM_domain = $dkimDomain;
        $mail->DKIM_private = $dkimPrivate; 	// la ou se trouve la clé publique générée plus haut
        $mail->DKIM_selector = $dkimSelector; 																				// la meme chose que sur le DNS
        $mail->DKIM_passphrase = $dkimPassphrase;
        $mail->DKIM_identity = $mail->From;
        $mail->Encoding = "base64";

        $mail->send();
    } catch (Exception $e) {
        mail("erreur@prixfioul.fr", "Erreur EnvoyerMailErreur() " . $sujet, "Fichier:/inc/pmp_inc_fonctions_mail.php\nSujet:" . $sujet . "\nDestinataire:erreur@prixfioul.fr\nErreur:" . $mail->ErrorInfo . "\nMessage:\n" . $message);
    }
}

function EnvoyerMailUpdate($sujet, $message)
{
    require_once __DIR__ . '/../newsletter/src/Exception.php';
    require_once __DIR__ . '/../newsletter/src/PHPMailer.php';

    $mail = new PHPMailer(true); // true = exceptions activées
    try {

        $mail->setLanguage('fr', __DIR__ . '/../newsletter/src/');
        $mail->CharSet = "UTF-8";

        // From
        $mail->setFrom("info@" . $_SERVER['SERVER_NAME'], $_SERVER['SERVER_NAME']);
        // Reply
        $mail->addReplyTo("erreur@prixfioul.fr", $_SERVER['SERVER_NAME']);
        // To
        $mail->addAddress("erreur@prixfioul.fr");
        // Suite
        $mail->isHTML(true);
        $mail->Subject = $sujet;
        $mail->Body = $message;
        $mail->AltBody = html2ascii($message);

        $mail->DKIM_domain = $_SERVER['SERVER_NAME'];
        $mail->DKIM_private = __DIR__ . '/../newsletter/dkim/dkim.private';
        $mail->DKIM_selector = 'default';
        $mail->DKIM_passphrase = '';
        $mail->DKIM_identity = $mail->From;
        $mail->Encoding = "base64";

        $mail->send();
    } catch (Exception $e) {
        mail("erreur@prixfioul.fr", "Update Mail ", $sujet . " " . $to . " " . $message);
    }
}

function EnvoyerMail($sujet, $message)
{
    EnvoyerMailFromTo($sujet, $message, "", "info@poemop.fr");
}

function EnvoyerMailActivationCompte(&$co_pmp, $email, $id_crypte)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/activation_compte.html");
    $url = BASE_URL . "/creer_un_compte_poemop.php?id_crypte=" . $id_crypte;
    $message = str_replace("COMMANDE_URL", $url, $message);
    $sujet = "Activation de votre compte POEMOP";
    EnvoyerMailFromTo($sujet, $message, "", $email);
}

function EnvoyerMailReactivationCompte(&$co_pmp, $email, $id_crypte)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/reactiver_compte.html");
    $url = BASE_URL . "/inc/pmp_inc_fonctions_compte.php?action=reactiver_compte&id_crypte=" . urlencode($id_crypte);
    $message = str_replace("ID_CRYPTE", $id_crypte, $message);
    $message = str_replace("COMPTE_REACTIVATION_URL", $url, $message);

    $sujet = "Réactivation de votre compte POEMOP";
    EnvoyerMailFromTo($sujet, $message, "", $email);
}

function EnvoyerMailDesactivationCompte(&$co_pmp, string $email, string $id_crypte, string $disabled_date)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/confirmation_desactivation.html");

    // 🔢 Calcul de la date de suppression (3 ans après désactivation)
    $delete_date = date('d/m/Y', strtotime($disabled_date . ' +3 years'));

    // Remplacements dans le template
    $message = str_replace(
        ["ADRESSE_EMAIL", "DATE_DESACTIVATION", "DATE_SUPPRESSION", "ID_CRYPTE"],
        [
            htmlspecialchars($email),
            date('d/m/Y', strtotime($disabled_date)),
            $delete_date,
            htmlspecialchars($id_crypte)
        ],
        $message
    );

    $sujet = "Confirmation de désactivation de votre compte POEMOP";
    EnvoyerMailFromTo($sujet, $message, "", $email);
}

function EnvoyerMailRappelSuppression(&$co_pmp, string $email, string $id_crypte, string $disabled_date)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/notification_suppression_6mois.html");

    // Calcul de la date de suppression (3 ans après la désactivation)
    $delete_date = date('d/m/Y', strtotime($disabled_date . ' +3 years'));

    // Remplacements dynamiques
    $message = str_replace(
        ["ADRESSE_EMAIL", "DATE_DESACTIVATION", "DATE_SUPPRESSION", "ID_CRYPTE"],
        [
            htmlspecialchars($email),
            date('d/m/Y', strtotime($disabled_date)),
            $delete_date,
            htmlspecialchars($id_crypte)
        ],
        $message
    );

    $sujet = "Votre compte POEMOP sera supprimé dans 6 mois";
    EnvoyerMailFromTo($sujet, $message, "", $email);
}

function EnvoyerMailMotDePasseModAdmin(&$co_pmp, $mdp, $email)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/nouveau_mot_de_passe_admin.html");
    $message = str_replace("MOT_DE_PASSE", $mdp, $message);
    $sujet = "Votre mot de passe a été modifié";
    EnvoyerMailFromTo($sujet, $message, "", $email);
}

function EnvoyerMailLouis(&$co_pmp)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/nouveau_mot_de_passe_admin.html");
    $sujet = "Mail POEMOP";
    EnvoyerMailFromTo($sujet, $message, "", "test-zahmwhfe5@srv1.mail-tester.com");
}

function ReinitialiserMotDePasse(&$co_pmp, $email, $id_crypte)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/reinitialiser_mot_de_passe.html");
    $url = BASE_URL . "/creer_un_compte_poemop.php?id_crypte=" . $id_crypte . "&reinitialiser=1";
    $message = str_replace("COMMANDE_URL", $url, $message);
    $sujet = "Réinitialiser votre mot de passe POEMOP";
    EnvoyerMailFromTo($sujet, $message, "", $email);
}

function ModifierAdresseEmail(&$co_pmp, $email, $id_crypte)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/nouvelle_adresse_email.html");
    $url = BASE_URL . "/creer_un_compte_poemop.php?id_crypte=" . $id_crypte . "&email=" . $email . "&m_email=2";
    $message = str_replace("COMMANDE_URL", $url, $message);
    $message = str_replace("N_EMAIL", $email, $message);
    $sujet = "Modifier votre adresse email POEMOP";
    EnvoyerMailFromTo($sujet, $message, "", $email);
}

function EnvoyerMailRefusTarif(&$co_pmp, $grp_id, $user_email, $raison_refus_sel, $raison_refus)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/refus_tarif.html");
    $message = str_replace("N_GROUPEMENT", $grp_id, $message);
    $message = str_replace("USER_EMAIL", $user_email, $message);
    $message = str_replace("RAISON_REFUS_SEL", $raison_refus_sel, $message);
    $message = str_replace("RAISON_REFUS", $raison_refus, $message);
    $sujet = "Tarif refusé - groupement " . $grp_id . " de " . $user_email;
    EnvoyerMailFromTo($sujet, $message, "", "info@poemop.fr");
}

function NouveauMotDePasse(&$co_pmp, $email)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/nouveau_mot_de_passe.html");
    $sujet = "Votre mot de passe a été modifié";
    EnvoyerMailFromTo($sujet, $message, "", $email);
}

function ConfirmationModificationAdresseEmail(&$co_pmp, $email)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/confirmation_email.html");
    $sujet = "Votre adresse email a été modifiée";
    EnvoyerMailFromTo($sujet, $message, "", $email);
}

function EnvoyerMailMessageContact(&$co_pmp, $email, $cp, $msg)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/message_contact.html");
    $message = str_replace("CONTACT_MAIL", $email, $message);
    $message = str_replace("CONTACT_CP", $cp, $message);
    $message = str_replace("CONTACT_MESSAGE", $msg, $message);
    $sujet = "Nouveau message de : " . $email . " - (" . $cp . ")";
    EnvoyerMailFromTo($sujet, $message, "$email", "info@poemop.fr");
}
function EnvoyerMailCompteReactiveAdmin(&$co_pmp, string $email)
{
    $message = file_get_contents(__DIR__ . "/../newsletter/compte_reactive_admin.html");
    $sujet = "Votre compte POEMOP a été réactivé";
    EnvoyerMailFromTo($sujet, $message, "", $email);
}

function html2ascii($s)
{
    // convert links
    $s = preg_replace('/]*\)"?[^>]*>(.*?)/i', '$2 ($1)', $s);

    // convert p, br and hr tags
    $s = preg_replace('@]*>@i', "\n", $s);
    $s = preg_replace('@]*>@i', "\n\n", $s);
    $s = preg_replace('@]*>(.*)@i', "\n" . '$1' . "\n", $s);

    // convert bold and italic tags
    $s = preg_replace('@]*>(.*?)@i', '*$1*', $s);
    $s = preg_replace('@]*>(.*?)@i', '*$1*', $s);
    $s = preg_replace('@]*>(.*?)@i', '_$1_', $s);
    $s = preg_replace('@]*>(.*?)@i', '_$1_', $s);

    return $s;
}
