<?php
include_once __DIR__ . "/../inc/pmp_inc_fonctions_mail.php";
$res_mail = getMailModeleThunder($co_pmp);

if (!empty($_POST["erg"])) {
    echo "ze";
}

if (isset($_GET["id_sel"])) {
    $email_cle = "";
    $dest_mail = $_GET["id_sel"];
    $email = explode(";", $dest_mail);
    foreach ($email as $email) {
        if (strlen($email) > 0) {
            $query = "SELECT email FROM jjj_users WHERE id = '$email'";
            $res = my_query($co_pmp, $query);
            $res = mysqli_fetch_array($res);
            $email_cle .= $res["email"] . ";";
        }
    }
}

if (!empty($_POST["generer_mail"])) {
    $dest_mail = $_POST["id_sel"];
    header('Location: /admin/recherche_client.php?mail_thunder=sel&id_sel=' . $dest_mail . '&mail=' . $_POST["mail_thunder"]);
    exit; // Ajoutez cette ligne pour arrêter l'exécution après la redirection
}

if (isset($_GET["mail"])) {
    $id_mail = $_GET["mail"];
    $mail_sel = getMailSelThunder($co_pmp, $id_mail);
    if ($id_mail == "57" || $id_mail == "59") {
        $date_groupement = '1';
    }
}

if (isset($_POST["envoyer_mail_thunder"])) {
    $id_mail = $_POST["mail"];
    $nom_fichier = $_POST["nom_fichier"];
    $type = $_POST["type"];
    if ($id_mail == "57" || $id_mail == "59") {
        if (isset($_POST["date_grp"])) {
            $dest_mail = $_POST["id_sel"];
            $id = explode(";", $dest_mail);
            $date_grp = $_POST["date_grp"];
            foreach ($id as $id) {
                if (strlen($id) > 0) {
                    $query = "SELECT id, email FROM jjj_users WHERE id = '$id'";
                    $res_email = my_query($co_pmp, $query);
                    $res_email = mysqli_fetch_array($res_email);
                    $emails = $res_email["email"];
                    $user_id = $res_email["id"];

                    $query = "SELECT id_crypte FROM pmp_utilisateur WHERE user_id = '$user_id'";
                    $res_crypte = my_query($co_pmp, $query);
                    $res_crypte = mysqli_fetch_array($res_crypte);
                    $id_crypte = $res_crypte["id_crypte"];

                    EnvoyerMailThunderDate($co_pmp, $id_crypte, $emails, $nom_fichier, $type, $date_grp);
                }
            }
        } else {
            $mail_sel = getMailSelThunder($co_pmp, $id_mail);
            $date_groupement = '1';
            $message_type = "no";
            $message_icone = "fa-times";
            $message_titre = "Erreur";
            $message = "Il faut renseigner la date prévue d'annonce du tarif";
        }
    } else {
        if (isset($_POST["nom_fichier"])) {
            $dest_mail = $_POST["id_sel"];
            $id = explode(";", $dest_mail);
            foreach ($id as $id) {
                if (strlen($id) > 0) {
                    $query = "SELECT id, email FROM jjj_users WHERE id = '$id'";
                    $res_email = my_query($co_pmp, $query);
                    $res_email = mysqli_fetch_array($res_email);
                    $emails = $res_email["email"];
                    $user_id = $res_email["id"];

                    $query = "SELECT id_crypte FROM pmp_utilisateur WHERE user_id = '$user_id'";
                    $res_crypte = my_query($co_pmp, $query);
                    $res_crypte = mysqli_fetch_array($res_crypte);
                    $id_crypte = $res_crypte["id_crypte"];

                    EnvoyerMailThunder($co_pmp, $id_crypte, $emails, $nom_fichier, $type);
                }
            }
        } else {
            $message_type = "no";
            $message_icone = "fa-times";
            $message_titre = "Erreur";
            $message = "Il faut cliquer sur GENERER MAIL avant de l'envoyer";
        }
    }
}
?>

<div class="modal fade" id="envoyerMailThunder" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Envoyer un mail modèle Thunder</h5>
                <button type="button" class="btn-close b-close-c" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body text-left">
<?php if (isset($message)) { ?>
                <div class="toast <?= $message_type; ?>" style="margin: 0px 0 18px;width: 518px;">
                    <div class="message-icon <?= $message_type; ?>-icon">
                        <i class="fas <?= $message_icone; ?>"></i>
                    </div>
                    <div class="message-content ">
                        <div class="message-type">
                            <?= $message_titre; ?>
                        </div>
                        <div class="message">
                            <?= $message; ?>
                        </div>
                    </div>
                    <div class="message-close">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
<?php } ?>
                <form method="post" action="">
                    <label class="label-title" style="margin: 0;">Choix du Mail</label>
                    <div class="ligne"></div>
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="form-inline">
                                <label for="sms_type" class="col-sm-6 col-form-label" style="padding-left:0;">Choisissez le type de MAIL à envoyer</label>
                                <div class="col-sm-6" style="padding:0">
                                    <select class="form-control" name="mail_thunder" style="width:100%">
<?php while ($mail = mysqli_fetch_array($res_mail)) { ?>
                                        <option value="<?php echo $mail["id"]; ?>" <?php if (isset($mail_sel["id"]) && $mail_sel["id"] == $mail["id"]) { echo 'selected="selected"'; } ?>><?php echo $mail["sujet"]; ?></option>
<?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 align-self-center">
                            <input type="submit" name="generer_mail" value="GÉNÉRER LE MAIL" class="btn btn-primary" style="width:100%;">
                        </div>
                    </div>
                    <hr>
                    <label class="label-title" style="margin: 0;">Mots clés</label>
                    <div class="ligne"></div>
                    <label for="sms_type" class="col-sm-6 col-form-label" style="padding-left:0;">Adresse mail</label>
                    <textarea name="name" class="form-control" rows="10" cols="80" style="height: 70px;"><?php if (isset($email_cle)) { echo $email_cle; } ?></textarea>
                    <hr>
                    <label for="nom_fichier" class="col-sm-6 col-form-label" style="padding-left:0;">Mail</label>
                    <input type="text" name="nom_fichier" class="form-control" value="<?php if (isset($mail_sel["nom_fichier"])) { echo $mail_sel["nom_fichier"]; } ?>">
<?php if (isset($date_groupement)) { ?>
                    <hr>
                    <label for="date_grp" class="col-sm-6 col-form-label" style="padding-left:0;">Date prévue d'annonce du tarif</label>
                    <input type="text" name="date_grp" class="form-control" value="">
<?php } ?>
                    <div class="modal-footer">
                        <input type="hidden" name="id_sel" value="<?php echo isset($_GET['id_sel']) ? $_GET['id_sel'] : ''; ?>">
                        <input type="hidden" name="mail" value="<?php echo isset($_GET['mail']) ? $_GET['mail'] : ''; ?>">
                        <input type="hidden" name="type" value="<?php echo isset($mail_sel["sujet"]) ? $mail_sel["sujet"] : ''; ?>">
                        <button type="button" class="btn btn-secondary b-close-c" data-bs-dismiss="modal">Fermer</button>
                        <a href="newsletter/modele/thunder/<?php echo isset($mail_sel["nom_fichier"]) ? $mail_sel["nom_fichier"] : ''; ?>.html" class="btn btn-warning">Visualiser</a>
                        <input type="submit" name="envoyer_mail_thunder" id="envoyer_mail_thunder" class="btn btn-primary" value="Envoyer">
                        <input type="submit" name="erg" value="zetg">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
