<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include_once __DIR__ . "/../../inc/pmp_co_connect.php";

function compare($value, $array)
{
    for( $i = 0 ; $i < count($array) ; $i++ )
	{
        if ($value == $array[$i])
		{
            return $value;
        }
	}
}

$query = "SELECT DISTINCT mail_to
          FROM pmp_fournisseur_zone
          WHERE mail_to IS NOT NULL
          AND mail_to != ''";
$res = my_query($co_pmp, $query);

$mail_contact = "";
while ($mails = mysqli_fetch_array($res)) {
    $id_contacts = explode(";", $mails["mail_to"]); // <- liste des mails
    foreach ($id_contacts as $id_contact) {        // <- chaque mail dans la liste
        if ($id_contact != "") {
            if ($mail_contact != "") {
                $mail_contact .= ",";
            }
            $mail_contact .= "'" . $id_contact . "'";
        }
    }
}


$query = "  SELECT DISTINCT TRIM(LOWER(mail))
			FROM pmp_fournisseur_contact
			WHERE id IN (" . $mail_contact . ")
			ORDER BY 1";
$res = my_query($co_pmp, $query);
while ($contact = mysqli_fetch_array($res))
{
	$mail = $contact[0];
	$query = "  INSERT INTO pmp_mail_auto (id, user_id, modele_id, destinataires, etat, priorite, date_insertion, date_a_envoyer, date_action, chaine_cle)
	 			VALUES ('', NULL, '52', '$mail', 'Z', '0', NOW(), NOW(), '', NULL) ";
	$res2 = my_query($co_pmp, $query);
	if($res2)
	{
		echo $mail . " OK<br>";
	}
	else
	{
		echo $mail . " KO<br>";
	}

}

?>
