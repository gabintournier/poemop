<?php
if (!empty($_POST["afficher_clients"]))
{
	// $_SESSION["afficher_clients"] = $_POST["afficher_clients"];
	$res = getClientsListe($co_pmp);

	if(!empty($_POST["exporter_clients"]))
	{
		exporterListeClients($co_pmp, $res);
		$res = getClientsListe($co_pmp);
	}
}
// elseif (!empty($_SESSION["afficher_clients"]))
// {
// 	$res = getClientsListe($co_pmp);
//
// 	if(!empty($_POST["exporter_clients"]))
// 	{
// 		exporterListeClients($co_pmp, $res);
// 		$res = getClientsListe($co_pmp);
// 	}
// }
if(!empty($_POST["chercher_client"]))
{
	// $_SESSION["nom_client"] = $_POST["nom_client"];
	$_SESSION["cp_client"] = $_POST["cp_client"];
	// $_SESSION["mail_client"] = $_POST["mail_client"];
	$res = getClientsFiltres($co_pmp);
}
elseif (!empty($_GET["email"]))
{
	$_SESSION["mail_client"] = $_GET["email"];
	$res = getClientsFiltres($co_pmp);
}
?>
