<?php
include_once "/var/www/vhosts/plus-on-est-moins-on-paie.fr/httpdocs/inc/pmp_co_connect.php";
include_once "/var/www/vhosts/plus-on-est-moins-on-paie.fr/httpdocs/inc/pmp_inc_fonctions.php";
include_once "/var/www/vhosts/plus-on-est-moins-on-paie.fr/httpdocs/inc/pmp_inc_fonctions_compte.php";
include_once "/var/www/vhosts/plus-on-est-moins-on-paie.fr/httpdocs/inc/pmp_inc_fonctions_mail.php"; // EnvoyerMailFromTo

// On balance X mails
	print "<h1>Mail auto</h1>";

	// On recupère le mail
	$query = "	SELECT id, user_id, modele_id, destinataires
				FROM pmp_mail_auto
				WHERE etat = 'A'
				AND date_a_envoyer < NOW()
				AND destinataires = 'test-on1jmpa07@srv1.mail-tester.com'
				ORDER BY priorite DESC, date_insertion
				";
	$res = my_query($co_pmp, $query);

	$pmp_mail_auto = mysqli_fetch_array($res);
	EnvoyerMailAuto($co_pmp, $pmp_mail_auto);
	print " => Mail auto envoyé a " . $pmp_mail_auto['destinataires'] . "<br>";


function EnvoyerMailAuto(&$co_pmp, $pmp_mail_auto)
{
	// Si on se base sur le user_id
	if(strlen($pmp_mail_auto['destinataires'])>0)
	{
		// On charge les données utilisateur
		$jjj_users = ChargeCompteJoomla($co_pmp, $pmp_mail_auto['destinataires']);
		$pmp_utilisateur = ChargeCompteFioul($co_pmp, $pmp_mail_auto['destinataires']);
		$pmp_electricite = ChargeCompteElectricite($co_pmp, $pmp_mail_auto['destinataires']);

		// On charge le modele
		$pmp_mail_auto_modele = ChargeMailModele($co_pmp, $pmp_mail_auto['modele_id']);
		$message_html = file_get_contents("/var/www/vhosts/plus-on-est-moins-on-paie.fr/httpdocs/newsletter/modele/MODELE_" . $pmp_mail_auto_modele['nom_fichier'] . ".html"); // OK appelé par le cron ET OK appelé en https://achat-fioul.fr/newsletter/envoyer_mail_auto

		// On change les data pour l'url
		$tel1 = NettoieTel($pmp_utilisateur['tel_fixe']);
		$tel2 = NettoieTel($pmp_utilisateur['tel_port']);
		$tel3 = NettoieTel($pmp_utilisateur['tel_3']);
		if(TelEstMobile($tel1))
			$mobile = $tel1;
		elseif(TelEstMobile($tel2))
			$mobile = $tel2;
		elseif(TelEstMobile($tel3))
			$mobile = $tel3;
		if(TelEstFixe($tel1))
			$fixe = $tel1;
		elseif(TelEstFixe($tel2))
			$fixe = $tel2;
		elseif(TelEstFixe($tel3))
			$fixe = $tel3;
		if($pmp_electricite['civilite'] == 1)
			$civilite="Monsieur";
		else if($pmp_electricite['civilite'] == 2)
			$civilite = "Madame";
		$message_html = str_replace("civilite_data", $civilite, $message_html);
		$message_html = str_replace("prenom_data", trim($pmp_utilisateur['prenom']), $message_html);
		$message_html = str_replace("nom_data", trim($jjj_users['name']), $message_html);
		$message_html = str_replace("email_data", trim($jjj_users['email']), $message_html);
		$message_html = str_replace("tel_mob_data", $mobile, $message_html);
		$message_html = str_replace("tel_fixe_data", $fixe, $message_html);
		$message_html = str_replace("pdl_data", trim($pmp_electricite['compteur_pdl']), $message_html);
		$message_html = str_replace("tracking_data", "mail_" . $pmp_mail_auto['modele_id'], $message_html);

		// On envoi
		EnvoyerMailFromTo($pmp_mail_auto_modele['sujet'] , $message_html, "", $pmp_mail_auto['destinataires']);

		// On marque le mail envoyé
		MarqueMailEnvoye($co_pmp, $pmp_mail_auto['id']);

		return true;
	}
	// Sinon si on se base le mail // TODO

	return false;
}
// Marque un seul mail
function MarqueMailEnvoye(&$co_pmp, $mail_id)
{
	// Utilisé par le php via le cron

	$mail_id = mysqli_real_escape_string($co_pmp, $mail_id);

	$query = "UPDATE pmp_mail_auto SET etat = 'E', date_action = NOW() WHERE id = '" . $mail_id . "'";
	my_query($co_pmp, $query);
}
// Marque un seul mail
function MarqueMailErreur(&$co_pmp, $mail_id)
{
	// Utilisé par le php via le cron

	$mail_id = mysqli_real_escape_string($co_pmp, $mail_id);

	$query = "UPDATE pmp_mail_auto SET etat = 'X', date_action = NOW() WHERE id = '" . $mail_id . "'";
	my_query($co_pmp, $query);
}
function ChargeMailModele(&$co_pmp, $modele_id)
{
	// Utilisé par le php via le cron

	$modele_id = mysqli_real_escape_string($co_pmp, $modele_id);

	$query = 	"SELECT sujet, sujet_complet, dest, nom_fichier ";
	$query .= 	"FROM pmp_mail_auto_modele WHERE id = '" . $modele_id . "'";
	$res = my_query($co_pmp, $query);
	return mysqli_fetch_array($res);
}
?>
