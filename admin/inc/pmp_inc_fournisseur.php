<?php

if (!empty($_POST["charger_fournisseur"]))
{
	$_SESSION["n_four"] = $_POST["n_four"];
	$_SESSION["n_dep"] = $_POST["n_dep"];

	$principaux = isset($_POST["principaux"])? "1" : "0";
	$non_contacte = isset($_POST["non_contacte"])? "1" : "0";
	$partenaires = isset($_POST["partenaires"])? "1" : "0";
	$partenaires_sec = isset($_POST["partenaires_sec"])? "1" : "0";
	$recontacter = isset($_POST["recontacter"])? "1" : "0";
	$recontacter_com = isset($_POST["recontacter_com"])? "1" : "0";
	$pas_interesse = isset($_POST["pas_interesse"])? "1" : "0";
	$pas_interessant = isset($_POST["pas_interessant"])? "1" : "0";
	$autre_fioul = isset($_POST["autre_fioul"])? "1" : "0";
	$partenanriat_fini = isset($_POST["partenanriat_fini"])? "1" : "0";

	$_SESSION["principaux"] = $principaux;
	$_SESSION["non_contacte"] = $non_contacte;
	$_SESSION["partenaires"] = $partenaires;
	$_SESSION["partenaires_sec"] = $partenaires_sec;
	$_SESSION["recontacter"] = $recontacter;
	$_SESSION["recontacter_com"] = $recontacter_com;
	$_SESSION["pas_interesse"] = $pas_interesse;
	$_SESSION["pas_interessant"] = $pas_interessant;
	$_SESSION["autre_fioul"] = $autre_fioul;
	$_SESSION["partenanriat_fini"] = $partenanriat_fini;

	if (!empty($_POST["n_dep"]))
	{
		$res = getFiltreFournisseursDep($co_pmp);
		$num_res = mysqli_num_rows($res);

	}
	else
	{
		$res = getFiltreFournisseurs($co_pmp);
		$num_res = mysqli_num_rows($res);
	}
}
elseif (!empty($_POST["vider"]))
{
	unset($_SESSION['n_four']);
	unset($_SESSION['n_dep']);
	unset($_SESSION["principaux"]);
	unset($_SESSION["non_contacte"]);
	unset($_SESSION["partenaires"]);
	unset($_SESSION["partenaires_sec"]);
	unset($_SESSION["recontacter"]);
	unset($_SESSION["recontacter_com"]);
	unset($_SESSION["pas_interesse"]);
	unset($_SESSION["pas_interessant"]);
	unset($_SESSION["autre_fioul"]);
	unset($_SESSION["partenanriat_fini"]);
	unset($_SESSION["cp_livraison"]);
	$res = getFournisseursListe($co_pmp);
	$num_res = mysqli_num_rows($res);
	// header('/admin/liste_fournisseurs.php');
}
elseif (!empty($_POST["afficher_tous_fournisseurs"]))
{
	unset($_SESSION['n_four']);
	unset($_SESSION['n_dep']);
	unset($_SESSION["principaux"]);
	unset($_SESSION["non_contacte"]);
	unset($_SESSION["partenaires"]);
	unset($_SESSION["partenaires_sec"]);
	unset($_SESSION["recontacter"]);
	unset($_SESSION["recontacter_com"]);
	unset($_SESSION["pas_interesse"]);
	unset($_SESSION["pas_interessant"]);
	unset($_SESSION["autre_fioul"]);
	unset($_SESSION["partenanriat_fini"]);
	unset($_SESSION["cp_livraison"]);
	$res = getFournisseurs($co_pmp);
	$num_res = mysqli_num_rows($res);

	if(!empty($_POST["exporter_fournisseur"]))
	{
		exporterListeFournisseurs($co_pmp, $res);
		$res = getFournisseurs($co_pmp);
	}
}
elseif (!empty($_POST["recherche_zone_livraison"]))
{
	unset($_SESSION['n_four']);
	unset($_SESSION['n_dep']);

	$principaux = isset($_POST["principaux"])? "1" : "0";
	$non_contacte = isset($_POST["non_contacte"])? "1" : "0";
	$partenaires = isset($_POST["partenaires"])? "1" : "0";
	$partenaires_sec = isset($_POST["partenaires_sec"])? "1" : "0";
	$recontacter = isset($_POST["recontacter"])? "1" : "0";
	$recontacter_com = isset($_POST["recontacter_com"])? "1" : "0";
	$pas_interesse = isset($_POST["pas_interesse"])? "1" : "0";
	$pas_interessant = isset($_POST["pas_interessant"])? "1" : "0";
	$autre_fioul = isset($_POST["autre_fioul"])? "1" : "0";
	$partenanriat_fini = isset($_POST["partenanriat_fini"])? "1" : "0";

	$_SESSION["principaux"] = $principaux;
	$_SESSION["non_contacte"] = $non_contacte;
	$_SESSION["partenaires"] = $partenaires;
	$_SESSION["partenaires_sec"] = $partenaires_sec;
	$_SESSION["recontacter"] = $recontacter;
	$_SESSION["recontacter_com"] = $recontacter_com;
	$_SESSION["pas_interesse"] = $pas_interesse;
	$_SESSION["pas_interessant"] = $pas_interessant;
	$_SESSION["autre_fioul"] = $autre_fioul;
	$_SESSION["partenanriat_fini"] = $partenanriat_fini;

	$_SESSION["cp_livraison"] = $_POST["cp_livraison"];
	$res = getFournisseursCP($co_pmp, $_SESSION["cp_livraison"]);
	$num_res = mysqli_num_rows($res);
}
elseif (!empty($_SESSION["n_dep"]))
{
	$res = getFiltreFournisseursDep($co_pmp);
	$num_res = mysqli_num_rows($res);
	if(!empty($_POST["exporter_fournisseur"]))
	{
		exporterListeFournisseurs($co_pmp, $res);
		$res = getFiltreFournisseursDep($co_pmp);
	}
}
elseif (!empty($_SESSION["n_four"]) || !empty($_SESSION["partenaires_sec"]) || !empty($_SESSION["recontacter"]) || !empty($_SESSION["recontacter_com"]) || !empty($_SESSION["pas_interesse"]) || !empty($_SESSION["pas_interessant"]) || !empty($_SESSION["autre_fioul"]) || !empty($_SESSION["partenanriat_fini"]) || !empty($_SESSION["non_contacte"]) )
{
	$res = getFiltreFournisseurs($co_pmp);
	$num_res = mysqli_num_rows($res);

	if(!empty($_POST["exporter_fournisseur"]))
	{
		exporterListeFournisseurs($co_pmp, $res);
		$res = getFiltreFournisseurs($co_pmp);
	}
}
elseif (!empty($_GET["cp"]))
{
	$res = getFournisseursGetCP($co_pmp, $_GET["cp"]);
	$num_res = mysqli_num_rows($res);
}
elseif (!empty($_SESSION["cp_livraison"]))
{
	$res = getFournisseursCP($co_pmp, $_SESSION["cp_livraison"]);
	$num_res = mysqli_num_rows($res);

	if(!empty($_POST["exporter_fournisseur"]))
	{
		exporterListeFournisseurs($co_pmp, $res);
		$res = getFournisseursCP($co_pmp, $_SESSION["cp_livraison"]);
	}
}

else
{
	$res = getFournisseursListe($co_pmp);
	$num_res = mysqli_num_rows($res);

	if(!empty($_POST["exporter_fournisseur"]))
	{
		exporterListeFournisseurs($co_pmp, $res);
		$res = getFournisseursListe($co_pmp);
	}
}
 ?>
