<?php
function supprimeAccent($str)
{
    $url = $str;
    $url = preg_replace('#Ç#', 'C', $url);
    $url = preg_replace('#ç#', 'c', $url);
    $url = preg_replace('#è|é|ê|ë#', 'e', $url);
    $url = preg_replace('#È|É|Ê|Ë#', 'E', $url);
    $url = preg_replace('#à|á|â|ã|ä|å#', 'a', $url);
    $url = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $url);
    $url = preg_replace('#ì|í|î|ï#', 'i', $url);
    $url = preg_replace('#Ì|Í|Î|Ï#', 'I', $url);
    $url = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $url);
    $url = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $url);
    $url = preg_replace('#ù|ú|û|ü#', 'u', $url);
    $url = preg_replace('#Ù|Ú|Û|Ü#', 'U', $url);
    $url = preg_replace('#ý|ÿ#', 'y', $url);
    $url = preg_replace('#Ý#', 'Y', $url);

    return ($url);
}
function formatNom($str)
{
	// Tout en majuscule
	return supprimeAccent(mb_strtoupper($str,'UTF-8'));
}
function formatPrenom($str)
{
	// Premier en majuscule (sans accent), le reste en minuscule (avec accent)
	return supprimeAccent(mb_substr(mb_strtoupper($str,'UTF-8'),0,1,'UTF-8')) . mb_substr(mb_strtolower($str,'UTF-8'),1,NULL,'UTF-8');
}
function formatAdresse($str)
{
	return formatPrenom($str);
}

function VerifierCPx($msg)
{
	if(preg_match("`^[0-9]{5}$`",$msg))
		return true;
	return false;
}

// Le nombre total de litre livré
function getTotalLitreLivre(&$co_pmp)
{
	/* ATTENTION cette requete explose les temps du serveur !
	$query = "  SELECT SUM(cmd_qtelivre)
				FROM pmp_commande ";
	*/

	$query = "  SELECT valeur_int
				FROM pmp_parametre
				WHERE parametre = 'total_livre'";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}
// Le nombre de personne groupé en cours
function getNbFoyerGroupe(&$co_pmp)
{
	/* ATTENTION cette requete explose les temps du serveur !
	$query = "  SELECT COUNT(*)
				FROM pmp_commande
				WHERE groupe_cmd > 0
				AND cmd_status IN (12,15,17,20,25,30) ";
	*/
	$query = "  SELECT valeur_int
				FROM pmp_parametre
				WHERE parametre = 'personne_groupe'";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

function getInscriptionRecente(&$co_pmp)
{
	$query = "	SELECT code_postal, date_creation
				FROM pmp_utilisateur
				WHERE CHAR_LENGTH(code_postal) = 5
				ORDER BY date_creation DESC
				LIMIT 4 ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function VerifierMail($msg)
{
	if(strlen($msg)>100)
		return false;
	// $atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';
	$atom   = '[-a-z0-9_]';   						// caractères autorisés avant l'arobase
	$domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; 	// caractères autorisés après l'arobase (nom de domaine)
	$regex = '/^' . $atom . '+' .   				// Une ou plusieurs fois les caractères autorisés avant l'arobase
	'(\.' . $atom . '+)*' .        					// Suivis par zéro point ou plus
													// séparés par des caractères autorisés avant l'arobase
	'@' .                      					    // Suivis d'un arobase
	'(' . $domain . '{1,63}\.)+' .  				// Suivis par 1 à 63 caractères autorisés pour le nom de domaine
													// séparés par des points
	$domain . '{2,63}$/i';          				// Suivi de 2 à 63 caractères autorisés pour le nom de domaine
	if (preg_match($regex, $msg))
		return true;
	return false;
}

function GetUserId(&$co_pmp, $mail)
{
	$query = "	SELECT id
				FROM jjj_users
				WHERE email='" . mysqli_real_escape_string($co_pmp, $mail)  . "' ";
	$res = my_query($co_pmp, $query);
	$jjj_users = mysqli_fetch_array($res);
	return $jjj_users["id"];
}

function VerifierCP($co_pmp, $msg)
{
	$req_cp = "  SELECT * FROM pmp_code_postal WHERE code_postal = '$msg' ";
	$res = my_query($co_pmp, $req_cp);
	$req_cp = mysqli_fetch_array($res);
	if(isset($req_cp[0]))
	{
		if(strlen($req_cp[0])>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

}

function TraceHistoClient(&$co_pmp, $user_id, $hisu_action, $hisu_valeur)
{
	// On a soit un status, soit une quantite soit une qualite

	$cmd_id = mysqli_real_escape_string($co_pmp, $user_id);
	$hisu_action = mysqli_real_escape_string($co_pmp, $hisu_action);
	$hisu_valeur = mysqli_real_escape_string($co_pmp, $hisu_valeur);

	$query = "	INSERT INTO pmp_utilisateur_histo (user_id, hisu_date, hisu_intervenant, hisu_action, hisu_valeur )
				VALUES ('" . $user_id . "',SYSDATE(),'site','" . $hisu_action . "','" . $hisu_valeur . "')";
	$res = my_query($co_pmp, $query);
}

function VerifierAlpha($msg, $taille_min, $taille_max)
{
	if(strlen($msg)<$taille_min)
		return false;
	if(strlen($msg)>$taille_max)
		return false;
	if(preg_match("`^[-a-zA-ZàáâôèéêëÇçîïùúûü_ \'\"().,;:°!?\r\n&/+*%$<>#€£@#]*$`",$msg)) // #€# les caractères spéciaux (unicode) doivent etre entre 2 dieses
		return true;
	return false;
}

function VerifierTaille($msg, $taille_min, $taille_max)
{
	if(strlen($msg)<$taille_min)
	{
		return false;
	}

	if(strlen($msg)>$taille_max)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function VerifierAlphaNum($msg, $taille_min, $taille_max)
{
	if(strlen($msg)<$taille_min)
		return false;
	if(strlen($msg)>$taille_max)
		return false;
	if(preg_match("`^[-a-zA-Z0-9àáâôèéêëÇçîïùúûü_ \'\"().,;:°!?\r\n&/+*%$<>#€£@#]*$`",$msg)) // #€# les caractères spéciaux (unicode) doivent etre entre 2 dieses
		return true;
	return false;
}

function VerifierQte($msg)
{
	if(preg_match("`^[-a-zA-Z0-9àáâôèéêëÇçîïùúûü_ \'\"().,;:°!?\r\n&/+*%$<>#€£@#]*$`",$msg)) // #€# les caractères spéciaux (unicode) doivent etre entre 2 dieses
		return true;
	return false;
}

function VerifierTel($msg)
{
	if(strlen($msg)>=25)
		return false;
	if(strlen($msg)==0)
		return true;
	if(preg_match("`^0[12345679]{1}[0-9]{8}$`",$msg))
		return true;
	if(preg_match("`^0[12345679]{1}.[0-9]{2}.[0-9]{2}.[0-9]{2}.[0-9]{2}$`",$msg))
		return true;
	if(preg_match("`^0[12345679]{1}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}$`",$msg))
		return true;
	if(preg_match("`^0[12345679]{1}_[0-9]{2}_[0-9]{2}_[0-9]{2}_[0-9]{2}$`",$msg))
		return true;
	if(preg_match("`^0[12345679]{1} [0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}$`",$msg))
		return true;
	if(preg_match("`^\+[0123456789]+$`",$msg))
		return true;
	return false;
}

function formatTel($str)
{
	if(strlen($str)<8)
		return "";
	$res = $str;
	$res = str_replace("-",".",$res);
	$res = str_replace("_",".",$res);
	$res = str_replace(" ",".",$res);

	// Si c'est un numéro qui commence par +33
	if(substr($res,0,3) == "+33")
	{
		// On remplace +33 par 0
		$res = "0" . substr($res,3);
	}

	// Si c'est pas un numéro qui commence avec des + ET qu'il n'y a pas de point
	if( (substr($res,0,1) != '+') && (strpos($res,".") === FALSE) )
	{
		// On ajoute les points
		$res = substr($res,0,2) . '.' . substr($res,2,2) . '.' . substr($res,4,2) . '.' . substr($res,6,2) . '.' . substr($res,8,2);
	}
	return $res;
}

function NettoieTel($tel)
{
	$tel = preg_replace('/\s/', '', $tel);
	$tel = preg_replace('/\./', '', $tel);
	$tel = preg_replace('/\,/', '', $tel);
	return $tel;
}
function TelEstMobile($tel)
{
	if($tel[1] == '6')
		return true;
	if($tel[1] == '7')
		return true;
	return false;
}
function TelEstFixe($tel)
{
	if($tel[1] == '1')
		return true;
	if($tel[1] == '2')
		return true;
	if($tel[1] == '3')
		return true;
	if($tel[1] == '4')
		return true;
	if($tel[1] == '5')
		return true;
	return false;
}

function getMoyennePoemop(&$co_pmp)
{
	$query = "	SELECT AVG(pmp_commande.cmd_prix_ord)/1000
				FROM pmp_commande, pmp_utilisateur
				WHERE pmp_commande.cmd_status IN (17,20,25)
				AND pmp_commande.cmd_prix_ord != 0
				AND pmp_commande.user_id = pmp_utilisateur.user_id
				AND pmp_commande.cmd_typefuel = 1
				AND pmp_commande.cmd_qte >= 1000
				AND cmd_dt BETWEEN date(now() - INTERVAL 30 day) AND now()
				ORDER BY 1 DESC LIMIT 1	";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}
