<?php
// Initialisation
$res_client = false;
$res_zone = false;

// Ton code de session et POST reste identique
if(!empty($_POST["chercher_client_2"]))
{
    $_SESSION["nom_client"] = $_POST["nom_client_2"];
    $_SESSION["mail_client"] = $_POST["mail_client_2"];
    $_SESSION["code_client"] = $_POST["code_client_2"];
    $_SESSION["cp_client"] = $_POST["cp_client_2"];
    $_SESSION["tel_client"] = $_POST["tel_client_2"];
    $_SESSION["ville_client"] = $_POST["ville_client_2"];
    $_SESSION["date_min_insc"] = $_POST["date_min_insc_2"];
    $_SESSION["date_max_insc"] = $_POST["date_max_insc_2"];
    $_SESSION["date_min_co"] = $_POST["date_min_co_2"];
    $_SESSION["date_max_co"] = $_POST["date_max_co_2"];
    $_SESSION["nouveaux_inscrits"] = isset($_POST["nouveaux_inscrits_2"])? "1" : "0";
    $res_client = getClientsFiltres($co_pmp);
}
elseif (!empty($_POST["vider_2"]))
{
    unset($_SESSION['nom_client']);
    unset($_SESSION['mail_client']);
    unset($_SESSION['code_client']);
    unset($_SESSION['cp_client']);
    unset($_SESSION['tel_client']);
    unset($_SESSION['ville_client']);
    unset($_SESSION['date_min_insc']);
    unset($_SESSION['date_max_insc']);
    unset($_SESSION['date_min_co']);
    unset($_SESSION['date_max_co']);
    unset($_SESSION['nouveaux_inscrits']);
    unset($_SESSION['fournisseur_ajax']);
    unset($_SESSION['zone']);
    unset($_SESSION['afficher_clients']);
    $res_client = false;
}
elseif (!empty($_POST["chercher_client_four_2"]))
{
    $_SESSION["fournisseur_ajax"] = $_POST["fournisseur_ajax_2"];
    $_SESSION["zone"] = $_POST["zone_2"];
    $res_zone = getZoneFournisseurId($co_pmp, $_SESSION["fournisseur_ajax"]);
    $res_client = getClientFournisseurZone($co_pmp, $_SESSION["zone"]);
}
elseif (!empty($_POST["afficher_clients_2"]))
{
    $_SESSION["afficher_clients"] = $_POST["afficher_clients_2"];
    $res_client = getClientsListe($co_pmp);
}
elseif (isset($_SESSION["afficher_clients"]))
{
    $res_client = getClientsListe($co_pmp);
}
elseif (isset($_SESSION["fournisseur_ajax"]))
{
    $res_zone = getZoneFournisseurId($co_pmp, $_SESSION["fournisseur_ajax"]);
    $res_client = getClientFournisseurZone($co_pmp, $_SESSION["zone"]);
}
elseif (isset($_SESSION["nom_client"]) || isset($_SESSION["mail_client"]) || isset($_SESSION["code_client"]) || isset($_SESSION["cp_client"]) || isset($_SESSION["tel_client"]) || isset($_SESSION["ville_client"]))
{
    $res_client = getClientsFiltres($co_pmp);
}

// Ensuite ton HTML et formulaire ici...

?>

<table class="table" id="trie_table_client2">
    <thead>
        <tr>
            <th>C.Postal</th>
            <th>Ville</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Mail</th>
            <th>Tel</th>
            <th>Port</th>
            <th>Fournisseur</th>
            <th>N° Client</th>
        </tr>
    </thead>
    <tbody>
<?php
if($res_client && mysqli_num_rows($res_client) > 0)
{
    while ($clients = mysqli_fetch_array($res_client))
    {
?>
        <tr class="select clients">
            <input type="hidden" name="id_client" value="<?= $clients["user_id"]; ?>">
            <td><?= $clients["code_postal"]; ?></td>
            <td><?= $clients["ville"]; ?></td>
            <td><?= $clients["name"]; ?></td>
            <td><?= $clients["prenom"]; ?></td>
            <td><?= $clients["email"]; ?></td>
            <td><?= $clients["tel_fixe"]; ?></td>
            <td><?= $clients["tel_port"]; ?></td>
            <td><?= $clients["com_user"]; ?></td>
            <td><?= $clients["user_id"]; ?></td>
        </tr>
<?php
    }
}
else
{
    echo '<tr><td colspan="9" style="text-align:center;">Aucun client trouvé</td></tr>';
}
?>
    </tbody>
</table>
