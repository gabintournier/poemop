<?php
//Afficher les avis en fonction de la date et du statut
function getAvis(&$co_pmp)
{
	$date_min = date_format(new DateTime($_POST["date_min"]), 'Y-m-d H:i:s' );
	$date = date(format: "Y-m-d");
	$date_a = date('Y-m-d',strtotime('+1 days',strtotime($date)));
	$date_max = date_format(new DateTime($date_a), 'Y-m-d H:i:s' );
	$statut = $_POST["statut_avis"];
	$query = "  SELECT *
				FROM pmp_livre_or
				WHERE date BETWEEN '$date_min' AND '$date_max'
				AND valide = '$statut'
				ORDER BY date DESC";
	$res = my_query($co_pmp, $query);
	return $res;
}
//Afficher les avis après le retour de la page commande client
function getAvisReturn(&$co_pmp)
{
	$date_min = date('2020-01-01',strtotime('2020-01-01'));
	$date = date_format(new DateTime($date_min), 'Y-m-d H:i:s' );
	$date_a = date('Y-m-d',strtotime('+1 days',strtotime($date)));
	$date_max = date_format(new DateTime($date_a), 'Y-m-d H:i:s' );

	$query = "  SELECT *
				FROM pmp_livre_or
				WHERE valide = '0' ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getFournisseurAvis(&$co_pmp, $id)
{
	$query = "  SELECT pmp_fournisseur.nom
				FROM pmp_fournisseur, pmp_commande, pmp_regroupement
				WHERE pmp_commande.groupe_cmd = pmp_regroupement.id
				AND pmp_regroupement.id_four = pmp_fournisseur.id
				AND pmp_commande.id = '$id' ";
	$res = my_query($co_pmp, $query);
	$res = mysqli_fetch_array($res);
	return $res;
}

//Valider avis
if (!empty($_POST["valider_statut"]))
{

	if(isset($_POST["message"]) && isset($_POST["signature"]) && isset($_SESSION["user"]))
	{
		$message = $_POST["message"];
		$signature = $_POST["signature"];
		$reponse = $_POST["reponse"];
		$message = addslashes($message);
		$reponse = addslashes($reponse);
		$n_commande = $_POST["id_cmde"];
		$user = $_SESSION['user'];

		if (isset($_POST['censurer_message']))
		{
			$valide = "2";
			$msg = "censuré";
		}
		else
		{
			$valide = "1";
			$msg = "validé";
		}

		$updateAvis = "  UPDATE pmp_livre_or
		 				 SET message = '$message', valide = '$valide', reponse  = '$reponse', signature = '$signature', intervenant = '$user', date_reponse = NOW()
						 WHERE commande_id = '$n_commande' ";
		$res = my_query($co_pmp, $updateAvis);
		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "L'avis client a été " . $msg . " avec succès";
		}
		else
		{
			return false;
		}
	}
	else
	{
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Il n'y a aucun avis à traiter";
	}

}

if(!empty($_POST["en_attente"]))
{
	if(isset($_POST["message"]))
	{
		$n_commande = $_POST["id_cmde"];
		$user = $_SESSION['user'];

		$updateAvis = "  UPDATE pmp_livre_or
		 				 SET valide = '3', intervenant = '$user', date_reponse = SYSDATE()
						 WHERE commande_id = '$n_commande' ";
		$res = my_query($co_pmp, $updateAvis);
		if($res)
		{
			$message_type = "success";
			$message_icone = "fa-check";
			$message_titre = "Succès";
			$message = "L'avis client a été mis en attente avec succès";
		}
		else
		{
			return false;
		}
	}
	else
	{
		$message_type = "no";
		$message_icone = "fa-times";
		$message_titre = "Erreur";
		$message = "Il n'y a aucun avis à mettre en attente";
	}
}

if(!empty($_POST["fiche_client"]))
{
	$user_id = $_POST["user_id"];
	$id_cmde = $_POST["id_cmde"];
	header('Location: /admin/gestion_client.php?user_id=' . $user_id . '&id_cmd=' . $id_cmde . '&return=avis');
}

?>
