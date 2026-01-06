<style media="screen">
.ligne-menu {width: 46%!important;}
.menu > h1, .ligne-menu {margin-left:6%;}
</style>
<link href="css/select2.min.css" rel="stylesheet" />
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
if(!isset($_SESSION['user']))
{
    header('Location: /admin/connexion.php');
	die();
}

$title = "Envoyer un mail modèle Thunder";
$title_page = "Envoyer un mail modèle Thunder";
$return = true;
$link = 'recherche_client.php';

ob_start();

include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_clients.php";
include_once __DIR__ . "/inc/pmp_inc_fonctions_mail.php";
$res_mail = getMailModeleThunder($co_pmp);

if (isset($_GET["id_sel"]))
 {
    $email_cle = "";
    $dest_mail = $_GET["id_sel"];
    $emails = explode(";", $dest_mail);
    foreach ($emails as $email) {
        if (strlen($email) > 0) {
            $query = "SELECT email FROM jjj_users WHERE id = '$email'";
            $res = my_query($co_pmp, $query);
            $res = mysqli_fetch_array($res);
            $email_cle .= $res["email"] . ";";
        }
    }
}

if (!empty($_POST["generer_mail"]))
{
    $dest_mail = $_GET["id_sel"];
    header('Location: /admin/mail_thunder.php?id_sel=' . $dest_mail . '&mail=' . $_POST["mail_thunder"]);
    exit;
}

if (isset($_GET["mail"]))
{
    $id_mail = $_GET["mail"];
    $mail_sel = getMailSelThunder($co_pmp, $id_mail);
    if ($id_mail == "57" || $id_mail == "59" || $id_mail == "54")
	{
        $date_groupement = '1';
    }
}

if (isset($_POST["envoyer_mail_thunder"])) {
    $id_mail = $_POST["mail"];
    $nom_fichier = $_POST["nom_fichier"];
    $type = $_POST["type"];

    if ($id_mail == "57" || $id_mail == "59" || $id_mail == "54")
	{
        if (isset($_POST["date_grp"]))
		{
            $dest_mail = $_POST["id_sel"];
            $ids = explode(";", $dest_mail);
            $date_grp = $_POST["date_grp"];
            foreach ($ids as $id)
			{
                if (strlen($id) > 0)
				{
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
					TraceHistoClientAdmin($co_pmp, $user_id, 'Mail Thunder envoyé', $type);

					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Le mail a bien été envoyé.";
                }
            }
        }
		else
		{
            $mail_sel = getMailSelThunder($co_pmp, $id_mail);
            $date_groupement = '1';
            $message_type = "no";
            $message_icone = "fa-times";
            $message_titre = "Erreur";
            $message = "Il faut renseigner la date prévue d'annonce du tarif";
        }
    }
	else
	{
        if (isset($_POST["nom_fichier"]))
		{
            $dest_mail = $_POST["id_sel"];
            $ids = explode(";", $dest_mail);
            foreach ($ids as $id)
			{
                if (strlen($id) > 0)
				{
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
					TraceHistoClientAdmin($co_pmp, $user_id, 'Mail Thunder envoyé', $type);

					$message_type = "success";
					$message_icone = "fa-check";
					$message_titre = "Succès";
					$message = "Le mail a bien été envoyé.";
                }
            }
        }
		else
		{
            $message_type = "no";
            $message_icone = "fa-times";
            $message_titre = "Erreur";
            $message = "Merci de cliquer sur le bouton GENERER LE MAIL avant de l'envoyer.";
        }
    }
}


if(isset($message))
{
?>
<div class="toast <?= $message_type; ?>">
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
<?php
}
?>
<div class="bloc">
	<form method="post">
		<label class="label-title" style="margin: 0;">Choix du Mail</label>
		<div class="ligne"></div>
		<div class="row">
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-9">
						<div class="form-inline">
							<label for="sms_type" class="col-sm-6 col-form-label" style="padding-left:0;">Choisissez le type de MAIL à envoyer</label>
							<div class="col-sm-6" >
								<select class="form-control" name="mail_thunder" style="width:100%">
									<?php
									while ($mail = mysqli_fetch_array($res_mail)) {
									?>
									<option value="<?php echo isset($mail["id"]) ? $mail["id"] : ''; ?>" <?php if(isset($_GET["mail"])) { if($_GET["mail"] == $mail["id"]) { echo "selected='selected'"; } } ?>><?php echo isset($mail["sujet"]) ? $mail["sujet"] : ''; ?></option>
									<?php
									}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-3 align-self-center">
						<input type="submit" name="generer_mail" value="GÉNÉRER LE MAIL" class="btn btn-primary" style="width:100%;">
					</div>
				</div>
			</div>
			<div class="col-sm-6" style="border-left: 1px solid #0b242436;">
				<div class="form-inline">
					<label for="nom_fichier" class="col-sm-1 col-form-label" style="padding-left:0;">Mail</label>
					<div class="col-sm-11" style="padding:0">
						<input type="text" name="nom_fichier" class="form-control" value="<?php if (isset($mail_sel["nom_fichier"])) { echo $mail_sel["nom_fichier"]; } ?>" style="width:100%!important">
					</div>
				</div>
			</div>
		</div>
		<hr>
		<label class="label-title" style="margin: 0;">Mots clés</label>
		<div class="ligne"></div>
		<label for="sms_type" class="col-sm-6 col-form-label" style="padding-left:0;">Adresse mail</label>
		<textarea name="name" class="form-control" rows="10" cols="80" style="height: 70px;"><?php if (isset($email_cle)) { echo $email_cle; } ?></textarea>
		<?php if (isset($date_groupement)) { ?>
		<hr>
		<label for="date_grp" class="col-sm-6 col-form-label" style="padding-left:0;">Date prévue d'annonce du tarif</label>
		<input type="text" name="date_grp" class="form-control" value="">
		<?php } ?>
		<div class="row" style="margin-top:20px">
			<input type="hidden" name="id_sel" value="<?php echo isset($_GET['id_sel']) ? $_GET['id_sel'] : ''; ?>">
			<input type="hidden" name="mail" value="<?php echo isset($_GET['mail']) ? $_GET['mail'] : ''; ?>">
			<input type="hidden" name="type" value="<?php echo isset($mail_sel["sujet"]) ? $mail_sel["sujet"] : ''; ?>">
			<div class="col-sm-10 text-right">
				<a href="newsletter/modele/thunder/MODELE_<?php echo isset($mail_sel["nom_fichier"]) ? $mail_sel["nom_fichier"] : ''; ?>.html" class="btn btn-warning" target="_blank" style="width: 187px;">VISUALISER</a>
			</div>
			<div class="col-sm-2 text-right">
				<input type="submit" name="envoyer_mail_thunder" id="envoyer_mail_thunder" class="btn btn-primary" value="ENVOYER" style="width: 187px;">
			</div>
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
