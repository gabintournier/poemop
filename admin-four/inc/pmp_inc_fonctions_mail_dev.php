<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

ini_set('error_log', '/tmp/php_error.log');
error_reporting(E_ALL);

function EnvoyerMailFromToPieceJointe($sujet, $message, $from, $to, $piece_jointe)
{
    // DKIM (doc historique conservée)
    // # On genere un couple clé publique / clé privée en ligne de commande
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
    // # on vérifie que les DNS sont bien propagés avec https://www.mail-tester.com/spf-dkim-check
    // # on envoi un mail de test signé DKIM avec https://www.mail-tester.com/

    require_once __DIR__ . '/../../newsletter/src/Exception.php';
    require_once __DIR__ . '/../../newsletter/src/PHPMailer.php';
    require_once __DIR__ . '/../../newsletter/src/SMTP.php';

    $mail = new PHPMailer(true); // true = exceptions activées
    try {
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'error_log';

        $mail->setLanguage('fr', __DIR__ . '/../../newsletter/src/');
        $mail->CharSet = "UTF-8";
        // SMTP authentifié (aligné sur prod)
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
        // Reply
        if(strlen($from)==0)
            $mail->addReplyTo("info@poemop.fr", 'Poemop');
        else
            $mail->addReplyTo($from);
        // To
        $tok = strtok($to, ";");
        while ($tok !== false)
        {
            $mail->addAddress($tok);
            // echo "-" . $tok . "-";
            $tok = strtok(";");
        }
        // Bcc
        $mail->addBCC('info@poemop.fr');
        $mail->addBCC('trace@prixfioul.fr');
        // Suite
        $mail->isHTML(true);
        $mail->Subject = $sujet;
        $mail->Body    = $message;
        $mail->AltBody = html2ascii($message);
        $mail->addAttachment(__DIR__ . '/../export/'.$piece_jointe);
        $mail->DKIM_domain = 'poemop.fr';
        $mail->DKIM_private = __DIR__ . '/../../newsletter/dkim/dkim.private'; 	// la ou se trouve la clé publique générée plus haut
        $mail->DKIM_selector = 'default'; 				// la meme chose que sur le DNS
        $mail->DKIM_passphrase = '';
        $mail->DKIM_identity = $mail->From;
        $mail->Encoding = "base64";

        $mail->send();

        // echo 'Message envoyé';
    }
    catch (Exception $e)
    {
        // echo "Le message ne peut pas être envoyé. Erreurs: {$mail->ErrorInfo}";
        mail("info@poemop.fr", "plus-on-est-moins-on-paie.Fr : Erreur Mail ", $sujet . " " . $to . " " . $message);
    }
}

function EnvoyerMailFrom($sujet, $message, $from, $to)
{
    // DKIM
    // # On genere un couple clé publique / clé privée en ligne de commande
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
    // # on vérifie que les DNS sont bien propagés avec https://www.mail-tester.com/spf-dkim-check
    // # on envoi un mail de test signé DKIM avec https://www.mail-tester.com/

    require_once __DIR__ . '/../../newsletter/src/Exception.php';
    require_once __DIR__ . '/../../newsletter/src/PHPMailer.php';
    require_once __DIR__ . '/../../newsletter/src/SMTP.php';

    $mail = new PHPMailer(true); // true = exceptions activées
    try {
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'error_log';

        $mail->setLanguage('fr', __DIR__ . '/../../newsletter/src/');
        $mail->CharSet = "UTF-8";
        // SMTP authentifié (aligné sur prod)
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
        // Reply
        if(strlen($from)==0)
            $mail->addReplyTo("info@poemop.fr", 'Poemop');
        else
            $mail->addReplyTo($from);
        // To
        $tok = strtok($to, ";");
        while ($tok !== false)
        {
            $mail->addAddress($tok);
            // echo "-" . $tok . "-";
            $tok = strtok(";");
        }
        // Bcc
        $mail->addBCC('info@poemop.fr');
        $mail->addBCC('trace@prixfioul.fr');
        // Suite
        $mail->isHTML(true);
        $mail->Subject = $sujet;
        $mail->Body    = $message;
        $mail->AltBody = html2ascii($message);
        $mail->DKIM_domain = 'poemop.fr';
        $mail->DKIM_private = __DIR__ . '/../../newsletter/dkim/dkim.private'; 	// la ou se trouve la clé publique générée plus haut
        $mail->DKIM_selector = 'default'; 				// la meme chose que sur le DNS
        $mail->DKIM_passphrase = '';
        $mail->DKIM_identity = $mail->From;
        $mail->Encoding = "base64";

        $mail->send();

        // echo 'Message envoyé';
    }
    catch (Exception $e)
    {
        // echo "Le message ne peut pas être envoyé. Erreurs: {$mail->ErrorInfo}";
        mail("info@poemop.fr", "plus-on-est-moins-on-paie.Fr : Erreur Mail ", $sujet . " " . $to . " " . $message);
    }
}

// function EnvoyerMail($sujet, $message)
// {
// 	EnvoyerMailFromTo($sujet, $message, "", "info@poemop.fr");
// }

function EnvoyerMailSaisieRecap($sujet, $message, $piece_jointe)
{
    EnvoyerMailFromToPieceJointe($sujet, $message, "", "info@poemop.fr", $piece_jointe);
}

function EnvoyerMailRappelClient(&$co_pmp, $tel_fixe, $email_client)
{
    $message = file_get_contents(__DIR__ . "/../../newsletter/modele/MODELE_relance_client.html");
    $message = str_replace("tel_four", $tel_fixe, $message);
    $sujet = "Suite à votre commande sur notre site POEMOP";
    EnvoyerMailFrom($sujet, $message, "", "amelie@prixfioul.fr");
}

function envoyerMailPropositionTarif(&$co_pmp, $prix1, $prix2, $prix3, $prix4, $prix5, $prix6, $prix7, $prix8, $fournisseur, $zone, $commentaire, $mails)
{
    $message = file_get_contents(__DIR__ . "/../../newsletter/modele/MODELE_proposition_tarifs.html");
    $message = str_replace("NOM_FOURNISSEUR", $fournisseur, $message);
    $message = str_replace("NOM_ZONE", $zone, $message);
    if($prix1 == "NULL") { $prix1 = " "; }
    if($prix2 == "NULL") { $prix2 = " "; }
    if($prix3 == "NULL") { $prix3 = " "; }
    if($prix4 == "NULL") { $prix4 = " "; }
    if($prix5 == "NULL") { $prix5 = " "; }
    if($prix6 == "NULL") { $prix6 = " "; }
    if($prix7 == "NULL") { $prix7 = " "; }
    if($prix8 == "NULL") { $prix8 = " "; }
    $message = str_replace("PRIX_500_ORD", $prix1, $message);
    $message = str_replace("PRIX_500_SUP", $prix2, $message);
    $message = str_replace("PRIX_1000_ORD", $prix3, $message);
    $message = str_replace("PRIX_1000_SUP", $prix4, $message);
    $message = str_replace("PRIX_2000_ORD", $prix5, $message);
    $message = str_replace("PRIX_2000_SUP", $prix6, $message);
    $message = str_replace("PRIX_3000_ORD", $prix7, $message);
    $message = str_replace("PRIX_3000_SUP", $prix8, $message);
    $message = str_replace("COMMENTAIRE", $commentaire, $message);
    $sujet = "Proposition de tarif pour le fournisseur " . $fournisseur. " zone : " . $zone;
    EnvoyerMailFrom($sujet, $message, $mails, "info@poemop.fr");
}

function envoyerMailPropositionTarif2(&$co_pmp, $prix1, $prix2, $prix3, $prix4, $prix5, $prix6, $prix7, $prix8, $fournisseur, $zone, $commentaire, $mail_contact)
{
    $message = file_get_contents(__DIR__ . "/../../newsletter/modele/MODELE_proposition_tarifs.html");
    $message = str_replace("NOM_FOURNISSEUR", $fournisseur, $message);
    $message = str_replace("NOM_ZONE", $zone, $message);
    if($prix1 == "NULL") { $prix1 = " "; }
    if($prix2 == "NULL") { $prix2 = " "; }
    if($prix3 == "NULL") { $prix3 = " "; }
    if($prix4 == "NULL") { $prix4 = " "; }
    if($prix5 == "NULL") { $prix5 = " "; }
    if($prix6 == "NULL") { $prix6 = " "; }
    if($prix7 == "NULL") { $prix7 = " "; }
    if($prix8 == "NULL") { $prix8 = " "; }
    $message = str_replace("PRIX_500_ORD", $prix1, $message);
    $message = str_replace("PRIX_500_SUP", $prix2, $message);
    $message = str_replace("PRIX_1000_ORD", $prix3, $message);
    $message = str_replace("PRIX_1000_SUP", $prix4, $message);
    $message = str_replace("PRIX_2000_ORD", $prix5, $message);
    $message = str_replace("PRIX_2000_SUP", $prix6, $message);
    $message = str_replace("PRIX_3000_ORD", $prix7, $message);
    $message = str_replace("PRIX_3000_SUP", $prix8, $message);
    $message = str_replace("COMMENTAIRE", $commentaire, $message);
    $sujet = "Proposition de tarif pour le fournisseur " . $fournisseur . " zone : " . $zone;
    EnvoyerMailFrom($sujet, $message, $mail_contact, "info@poemop.fr");
}
?>
