<?php
if (isset($_GET["dep"]))
{
	//Afficher titre de la page departement
	$dep = $_GET["dep"];
	$query = "  SELECT titre
				FROM pmp_departement
				WHERE id = '$dep' ";
	$res = my_query($co_pmp, $query);
	$pmp_departement = mysqli_fetch_array($res);
$departement = $pmp_departement['titre'];
}

//Nombre de consammateur departement
function getConsommateurDep(&$co_pmp, $dep)
{
	$query = "  SELECT id
				FROM pmp_utilisateur
				WHERE code_postal LIKE '$dep%' ";
	$res = my_query($co_pmp, $query);
	return $res;
}

//Afficher les departements dans le select sur le menu de droite
function getDepartements(&$co_pmp)
{
	$query = "  SELECT *
				FROM pmp_departement
				ORDER BY id ASC ";
	$res = my_query($co_pmp, $query);
	return $res;
}

//Afficher les 10 derniers groupements
function getDerniersGroupements(&$co_pmp, $dep)
{
	$query = "  SELECT pmp_regroupement.statut, pmp_regroupement.planning, pmp_regroupement.date_grp, pmp_regroupement.id
				FROM pmp_regrp_dep, pmp_regroupement
				WHERE pmp_regrp_dep.departement = '$dep'
				AND pmp_regroupement.statut >= 5
				AND pmp_regroupement.id = pmp_regrp_dep.regrp_id
				ORDER BY pmp_regroupement.date_grp DESC, pmp_regroupement.id DESC LIMIT 0,10 ";
	$res = my_query($co_pmp, $query);
	return $res;
}

function ChargePlages(&$co_pmp, $regroupement, $quantite)
{
	$query = "	SELECT pmp_regrp_plages.volume, pmp_regrp_plages.prix_ord, pmp_regrp_plages.prix_sup, pmp_commande.cmd_prix_ord, pmp_commande.cmd_prix_sup
				FROM pmp_regrp_plages
				LEFT JOIN pmp_commande
				ON pmp_regrp_plages.regrp_id = pmp_commande.groupe_cmd
				WHERE pmp_regrp_plages.regrp_id='" . $regroupement . "'
				AND pmp_regrp_plages.volume = '$quantite'
				AND pmp_commande.cmd_status != '50'
				AND pmp_commande.cmd_status != '99'
				LIMIT 1 ";
	$res = mysqli_query($co_pmp, $query);

	if($res)
	{
		return mysqli_fetch_array($res);
	}
	return false;
}

//Redirection du select sur la page commandes groupees fioul departement
if (!empty($_POST["go_cp"]))
{
	$dep = $_POST["dep"];
	header('Location: /commande-groupee-de-fioul-domestique-' . $dep . '-departement.html');
}
