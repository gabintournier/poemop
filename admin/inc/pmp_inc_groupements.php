<?php
// $res_four = getFournisseursListe($co_pmp);
// if(!empty($_POST["charger_grp"]))
// {
// 	$_SESSION["etat_four"] = $_POST["etat_four"];
// 	$_SESSION["etat_four2"] = $_POST["etat_four2"];
// 	$_SESSION["resp"] = $_POST["resp"];
//
// 	if(!empty($_POST["four_id"]))
// 	{
// 		$_SESSION["four_id"] = $_POST["four_id"];
// 	}
// 	if(!empty($_POST["date_min"]) && !empty($_POST["date_max"]))
// 	{
// 		$_SESSION["date_min"] = $_POST["date_min"];
// 		$_SESSION["date_max"] = $_POST["date_max"];
// 	}
//
// 	$res = getFiltresGroupements($co_pmp);
// 	if(!empty($_POST["exporter_grp"]))
// 	{
// 		ExporterListeGrpt($co_pmp, $res);
// 		$res = getFiltresGroupements($co_pmp);
// 	}
// }
// elseif(!empty($_POST["charger_calculer"]))
// {
// 	$_SESSION["etat_four"] = $_POST["etat_four"];
// 	$_SESSION["etat_four2"] = $_POST["etat_four2"];
// 	$_SESSION["resp"] = $_POST["resp"];
//
// 	if(!empty($_POST["four_id"]))
// 	{
// 		$_SESSION["four_id"] = $_POST["four_id"];
// 	}
// 	if(!empty($_POST["date_min"]) && !empty($_POST["date_max"]))
// 	{
// 		$_SESSION["date_min"] = $_POST["date_min"];
// 		$_SESSION["date_max"] = $_POST["date_max"];
// 	}
//
// 	$res = getFiltresGroupementsCalculer($co_pmp);
// 	$attachee = GetStatsGroupement($co_pmp, '12');
// 	$groupee = GetStatsGroupement($co_pmp, '15');
// 	$p_propose = GetStatsGroupement($co_pmp, '17');
// 	$p_valide = GetStatsGroupement($co_pmp, '20');
// 	$livrable = GetStatsGroupement($co_pmp, '25');
// 	$livree = GetStatsGroupement($co_pmp, '30');
// 	$terminee = GetStatsGroupement($co_pmp, '40');
// 	$annul = GetStatsGroupement($co_pmp, '50');
// 	$annulp = GetStatsGroupement($co_pmp, '55');
// 	$en_cours = $attachee["statut"] + $groupee["statut"] + $p_propose["statut"];
// 	$valide = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $terminee["statut"];
// 	$projection = (($valide) / ($valide + $annulp["statut"]) * $en_cours) + $valide;
// 	$_SESSION["charger_calculer"] = $_POST["charger_calculer"];
// }
//
// elseif (!empty($_POST["charger_mois"]))
// {
// 	$date = new DateTime();
//     $dateDeb = $date -> format('Y-m-01');
//     $dateFin = $date -> format('Y-m-t');
//
// 	$_SESSION["date_min"] = $dateDeb;
// 	$_SESSION["date_max"] = $dateFin;
// 	$_SESSION["etat_four"] = '10';
// 	$_SESSION["etat_four2"] = '40';
//
// 	$res = GetMoisEnCours($co_pmp);
// 	$attachee = GetStatsGroupement($co_pmp, '12');
// 	$groupee = GetStatsGroupement($co_pmp, '15');
// 	$p_propose = GetStatsGroupement($co_pmp, '17');
// 	$p_valide = GetStatsGroupement($co_pmp, '20');
// 	$livrable = GetStatsGroupement($co_pmp, '25');
// 	$livree = GetStatsGroupement($co_pmp, '30');
// 	$terminee = GetStatsGroupement($co_pmp, '40');
// 	$annul = GetStatsGroupement($co_pmp, '50');
// 	$annulp = GetStatsGroupement($co_pmp, '55');
// 	$en_cours = $attachee["statut"] + $groupee["statut"] + $p_propose["statut"];
// 	$valide = $p_valide["statut"] + $livrable["statut"] + $livree["statut"] + $terminee["statut"];
// 	$projection = (($valide) / ($valide + $annulp["statut"]) * $en_cours) + $valide;
// 	$_SESSION["charger_mois"] = $_POST["charger_mois"];
// }
// elseif (!empty($_SESSION["charger_mois"]))
// {
// 	$res = GetMoisEnCours($co_pmp);
// 	if(!empty($_POST["exporter_grp"]))
// 	{
// 		$pourc_annulp = "1";
// 		$pourc_annul = "0";
// 		ExporterListeGrptStats($co_pmp, $res, $pourc_annulp, $pourc_annul);
// 		$res = GetMoisEnCours($co_pmp);
// 	}
// }
// elseif (!empty($_POST["charger_facture"]))
// {
// 	if(!empty($_POST["n_fact"]))
// 	{
// 		$_SESSION["n_fact"] = $_POST["n_fact"];
// 		$res = getFiltresGroupementsFacture($co_pmp, $_SESSION["n_fact"]);
// 		if(!empty($_POST["exporter_grp"]))
// 		{
// 			ExporterListeGrpt($co_pmp, $res);
// 			$res = getFiltresGroupementsFacture($co_pmp, $_SESSION["n_fact"]);
// 		}
// 	}
//
// }
// elseif (!empty($_POST["vider"]))
// {
// 	unset($_SESSION["etat_four"]);
// 	unset($_SESSION["etat_four2"]);
// 	unset($_SESSION["resp"]);
// 	unset($_SESSION["four_id"]);
// 	unset($_SESSION["date_min"]);
// 	unset($_SESSION["date_max"]);
// 	unset($_SESSION["n_fact"]);
// 	$res = getListeRegroupementsCréer($co_pmp);
// 	if(!empty($_POST["exporter_grp"]))
// 	{
// 		ExporterListeGrpt($co_pmp, $res);
// 		$res = getListeRegroupementsCréer($co_pmp);
// 	}
// }
// elseif (!empty($_SESSION["etat_four"]) || !empty($_SESSION["etat_four2"]) || !empty($_SESSION["resp"]) || !empty($_SESSION["four_id"]) || !empty($_POST["date_min"]) && !empty($_POST["date_max"]))
// {
// 	$res = getFiltresGroupements($co_pmp);
// 	if(!empty($_POST["exporter_grp"]))
// 	{
// 		ExporterListeGrpt($co_pmp, $res);
// 		$res = getFiltresGroupements($co_pmp);
// 	}
// }
// elseif (!empty($_SESSION["n_fact"]))
// {
// 	$res = getFiltresGroupementsFacture($co_pmp, $_SESSION["n_fact"]);
// 	if(!empty($_POST["exporter_grp"]))
// 	{
// 		ExporterListeGrpt($co_pmp, $res);
// 		$res = getFiltresGroupementsFacture($co_pmp, $_SESSION["n_fact"]);
// 	}
// }
// elseif (isset($_GET["id_four"]))
// {
// 	$res = getListeRegroupementsFournisseur($co_pmp, $_GET["id_four"]);
// }
// else
// {
// 	$res = getListeRegroupementsCréer($co_pmp);
// 	if(!empty($_POST["exporter_grp"]))
// 	{
// 		ExporterListeGrpt($co_pmp, $res);
// 		$res = getListeRegroupementsCréer($co_pmp);
// 	}
// }
?>
