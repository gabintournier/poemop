<?php
//Nombre total de message dans live d'or
function getTotalMessage($co_pmp)
{
	$query = "  SELECT *
				FROM pmp_livre_or
				WHERE note IS NOT NULL AND valide = 1";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getTotalNoteMessage($co_pmp)
{
	$query = "  SELECT SUM(note)
				FROM pmp_livre_or
				WHERE note IS NOT NULL AND valide = 1";
	$res = my_query($co_pmp, $query);
	return $res;
}

function getNoteMessage($co_pmp)
{
	$query = "  SELECT COUNT(note)
				FROM pmp_livre_or
				WHERE note IS NOT NULL";
	$res = my_query($co_pmp, $query);
	return $res;
}
//Afficher les 3 derniers messages
function getDerniersMessages($co_pmp)
{
	$query = "  SELECT date, message, note, signature
				FROM pmp_livre_or
				WHERE valide = 1
				AND note = 5 AND message IS NOT NULL
				AND message != ''
				ORDER BY date DESC LIMIT 3";
	$res = my_query($co_pmp, $query);
	return $res;
}
//Afficher par defaut les meilleurs avis sur la page avis
function getMeilleursMessages($co_pmp)
{
	$query = "  SELECT date, message, reponse, signature, note
				FROM pmp_livre_or
				WHERE valide = '1'
				AND note = '5'
				AND LENGTH(message) > 50
				ORDER BY date DESC LIMIT 20 ";
	$res = my_query($co_pmp, $query);
	return $res;
}
//Afficher les avis en fonction des étoiles sur la page avis
function getEtoilesMessages($co_pmp, $etoiles)
{
	if(isset($_GET['debut']))
	{
		$debut = $_GET['debut'];
	}
	else
	{
		$debut = 0;
	}
	$query = "  SELECT date, message, reponse, signature, note
				FROM pmp_livre_or
				WHERE valide = '1'
				AND note = '" . mysqli_real_escape_string($co_pmp, $etoiles) . "'
				ORDER BY date DESC LIMIT $debut,20 ";
	$res = my_query($co_pmp, $query);
	return $res;
}
//Afficher le nombre de message
function getNbMessages($co_pmp, $etoiles)
{
	$query = "  SELECT *
				FROM pmp_livre_or
				WHERE note = '" . mysqli_real_escape_string($co_pmp, $etoiles) . "'
				AND valide = '1' ";
	$res = my_query($co_pmp, $query);
	return $res;
}
