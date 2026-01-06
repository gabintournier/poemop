<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function EnvoyerMailFromToCC($sujet, $message, $from, $to, $cc)
{
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
	// # on vérifie que les DNS sont bien propagé avec https://www.mail-tester.com/spf-dkim-check
	// # on envoi un mail de test signé DKIM avec https://www.mail-tester.com/

	require_once '../newsletter/src/Exception.php';
	require_once '../newsletter/src/PHPMailer.php';

	$mail = new PHPMailer(true); // true = exceptions activées
	try {

		$mail->setLanguage('fr', '../newsletter/src/');
		$mail->CharSet = "UTF-8";

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

		//CC

		if (isset($cc) && $cc != "" )
		{
			$cck = strtok($cc, ";");
			while ($cck !== false)
			{
				$mail->addCC($cck);
				$cck = strtok(";");
			}
        	                               // $cc est en copie des emails
    	}
		// Bcc
		$mail->addBCC('info@poemop.fr');
		// Suite
		$mail->isHTML(true);
		$mail->Subject = $sujet;
		$mail->Body    = $message;
		$mail->AltBody = html2ascii($message);
		$mail->DKIM_domain = 'plus-on-est-moins-on-paie.fr';
		$mail->DKIM_private = '../newsletter/dkim/dkim.private'; 	// la ou se trouve la clé publique générée plus haut
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
		mail("info@poemop.fr", "plus-on-est-moins-on-paie.fr : Erreur Mail ", $sujet . " " . $to . " " . $message);
	}
}

function EnvoyerDemandeDeCotations(&$co_pmp, $id_crypte, $id_zone, $mails, $zone, $cc, $date, $heure)
{
	$message = file_get_contents("../newsletter/modele/MODELE_demande_cotation.html");
	$message = str_replace("ID_CRYPTE", $id_crypte, $message);
	$message = str_replace("ID_ZONE", $id_zone, $message);
	$message = str_replace("DATE_MAIL", $date, $message);
	$message = str_replace("HEURE_MAIL", $heure, $message);
	$sujet = "Demande de cotations pour la zone : " . $zone;
	EnvoyerMailFromToCC($sujet, $message, "", $mails, $cc);
}
?>
