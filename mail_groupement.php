<?php
include_once 'inc/dev_auth.php';
include_once 'inc/pmp_co_connect.php';
session_start();

if (isset($_GET["id_crypte"]) && !isset($_SESSION['id'])) {
    $id_crypte = $_GET["id_crypte"];

    $query = "
        SELECT user_id, disabled_account
        FROM pmp_utilisateur
        WHERE id_crypte = '" . mysqli_real_escape_string($co_pmp, $id_crypte) . "'
        LIMIT 1
    ";
    $res = my_query($co_pmp, $query);
    $pmp_user = mysqli_fetch_assoc($res);

    if (!empty($pmp_user['user_id'])) {

        // 🔒 Vérifier si le compte est désactivé
        if (!empty($pmp_user['disabled_account']) && $pmp_user['disabled_account'] == 1) {
            $_SESSION['err'] = '<p class="link-signup">Ce compte est désactivé. Pour le réactiver, veuillez <a href="contacter_poemop.php" style="color:#ef8351;text-decoration:underline;">nous contacter</a>.</p>';
            $_SESSION['erreur_form'] = "erreur_form";

            // 👉 Redirige simplement vers la page de connexion (le toast affichera $err)
            header('Location: creer_un_compte_poemop.php?connexion=1');
            exit;
        }

        // ✅ Compte actif → on poursuit normalement
        $_SESSION['id'] = $pmp_user['user_id'];
        $_SESSION['user'] = true; // compatibilité avec menu.php
    }
}

include_once 'inc/pmp_inc_fonctions.php';
include_once 'inc/pmp_inc_fonctions_compte.php';
include_once 'inc/mail_groupement_functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$desc = 'Gérez votre inscription aux offres de prix de POEMOP. Vous pouvez vous désinscrire ou vous réinscrire facilement.';
$title = 'Gestion des notifications des offres de prix POEMOP';

ob_start();

// === Détermination de l'action et de l'utilisateur ===
$action = $_POST['actionNotifGroupement'] ?? $_GET['actionNotifGroupement'] ?? null;
$user_id = $_SESSION['id'] ?? null;

// === Vérification action / utilisateur ===
$actionValide = in_array($action, ['inscription', 'desinscription', 'temporaire']);
if (!$user_id || !$actionValide) {
    header("Location: mon_compte.php?type=fioul");
    exit;
}

// === Gestion désabonnement temporaire ===
$message_toast = null;
if ($action === 'temporaire' && isset($_POST['desabonnement']) && !empty($_POST['date_blocage'])) {
    $date_blocage = $_POST['date_blocage'];
    $message_toast = desabonnementTemporaire($co_pmp, $user_id, $date_blocage);
    $_SESSION['temp_desabonne'] = true;

    // Après désabonnement temporaire, on reste sur la page désinscription
    $action = 'desinscription';
}

// === Gestion feedback ===
// === Gestion feedback ===
$feedback_sent = false;
if (isset($_POST['envoyer_feedback'])) {
    $raison = $_POST['raison'] ?? '';
    $commentaire = $_POST['commentaire'] ?? '';
    $ok = EnregistrerDesinscription($co_pmp, $user_id, $raison, $commentaire);

    // Préparer le toast pour affichage
    $message_toast = $ok
        ? [
            'info' => 'Notification',
            'type' => 'success',
            'icone' => 'fa-check',
            'message' => 'Merci pour vos remarques. Nous les avons bien prises en compte.'
        ]
        : [
            'info' => 'Erreur',
            'type' => 'no',
            'icone' => 'fa-times',
            'message' => "Une erreur est survenue, veuillez réessayer."
        ];

    $feedback_sent = true;

    // mettre à jour les données affichées avec ce que l'utilisateur vient de saisir
    $desinscription_data['raison_desinscription'] = $raison;
    $desinscription_data['commentaire_desinscription'] = $commentaire;
}


// === Traitement autres actions si pas de feedback et pas temporaire ===
if (!$feedback_sent) {
    $message_toast ??= handleMailGroupementActions($co_pmp, $user_id, $action);
}

// === Récupération des données utilisateur pour formulaires ===
$desinscription_data = GetDesinscriptionData($co_pmp, $user_id);
$date_blocage = $desinscription_data['date_blocage'] ?? null;

// === Calcul flags affichage ===
$tempDesabonne = !empty($_SESSION['temp_desabonne']);
unset($_SESSION['temp_desabonne']);
$showTempDesabo = !$date_blocage && !$tempDesabonne;
$showFeedback = true;

include 'modules/menu.php';
?>

<div class="container-fluid">
    <div class="header">
        <div class="groupement-fioul">
            <div class="row">
                <div class="col align-self-center">
                    <h1>Gérer votre inscription</h1>
                    <p>Choisissez si vous souhaitez recevoir nos mails sur les propositions de groupement et les offres de prix.</p>
                </div>
                <div class="col">
                    <img src="images/header-contacter-poemop.svg" alt="Contact POEMOP" style="width: 70%;display: block;margin: 0 auto;">
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="row">
            <div class="col-sm-9">
                <div class="bloc-contact text-center">
                    <h2>Préférences de notifications aux mails de groupements</h2>
                    <div class="ligne-center jaune"></div>
                </div>

                <?php if ($message_toast): ?>
                    <div class="toast <?= htmlspecialchars($message_toast['type'] ?? ''); ?>" style="margin-right: 50% !important;width:50%!important">
                        <div class="message-icon <?= htmlspecialchars($message_toast['type'] ?? ''); ?>-icon">
                            <i class="fas <?= htmlspecialchars($message_toast['icone'] ?? ''); ?>"></i>
                        </div>
                        <div class="message-content">
                            <div class="message-type"><?= htmlspecialchars($message_toast['info'] ?? ''); ?></div>
                            <div class="message"><?= htmlspecialchars($message_toast['message'] ?? ''); ?></div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($action === 'desinscription'): ?>
                    <?php if ($showTempDesabo): ?>
                        <hr class="separe" style="margin:30px 0;">
                        <div class="informations-perso desinscription text-center">
                            <h3>Désabonnement temporaire</h3>
                            <p>Souhaitez-vous que ce désabonnement soit temporaire ?</p>
                            <form method="post">
                                <input type="hidden" name="actionNotifGroupement" value="temporaire">
                                <select class="form-control custom-input form-lg" name="date_blocage" style="max-width:400px;margin:0 auto;">
                                    <?php
                                    for ($i = 2; $i <= 12; $i += 2) {
                                        $date_tmp_mk = mktime(0, 0, 0, date("m") + $i, date("d"), date("Y"));
                                        $date_tmp_us = date('Y-m-d', $date_tmp_mk);
                                        echo '<option value="' . htmlspecialchars($date_tmp_us) . '" ' . ($i == 4 ? "selected" : "") . '>';
                                        echo $i . ' mois (jusqu\'au ' . dateUS2Texte($date_tmp_us) . ')</option>';
                                    }
                                    ?>
                                </select>
                                <div class="text-center" style="margin-top:15px;">
                                    <input type="submit" class="btn btn-secondary" name="desabonnement" value="Je me désabonne temporairement">
                                </div>
                            </form>
                            <p style="margin-top:10px">Pour un désabonnement définitif, vous n'avez rien à faire de plus.</p>
                        </div>
                    <?php endif; ?>

                    <?php if ($showFeedback): ?>
                        <hr class="separe" style="margin:30px 0;">
                        <div class="bloc-contact text-center">
                            <h2>Vous souhaitez nous en dire plus ?</h2>
                            <div class="ligne-center jaune"></div>
                        </div>
                        <form method="post" class="mailNotifGroupement-form" style="background:#fafafa;padding:20px;border-radius:10px;">
                            <input type="hidden" name="actionNotifGroupement" value="desinscription">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="raison" class="col-form-label custom-label">Raison de votre désinscription (facultatif)</label>
                                    <select class="form-control form-lg" id="raison" name="raison">
                                        <option value="">-- Sélectionnez une raison --</option>
                                        <option value="trop_mail" <?= ($desinscription_data['raison_desinscription'] ?? '') === 'trop_mail' ? 'selected' : '' ?>>Je reçois trop d’emails</option>
                                        <option value="plus_concerne" <?= ($desinscription_data['raison_desinscription'] ?? '') === 'plus_concerne' ? 'selected' : '' ?>>Je ne suis plus concerné</option>
                                        <option value="prix" <?= ($desinscription_data['raison_desinscription'] ?? '') === 'prix' ? 'selected' : '' ?>>Les prix ne sont pas assez avantageux</option>
                                        <option value="commande" <?= ($desinscription_data['raison_desinscription'] ?? '') === 'commande' ? 'selected' : '' ?>>Je n'ai pas besoin de commander pour l'instant</option>
                                        <option value="autre" <?= ($desinscription_data['raison_desinscription'] ?? '') === 'autre' ? 'selected' : '' ?>>Autre raison</option>
                                    </select>
                                </div>
                                <div class="col-sm-12" style="margin-top: 15px;">
                                    <label for="commentaire" class="col-form-label custom-label">Commentaire (facultatif)</label>
                                    <textarea name="commentaire" id="commentaire" rows="5" class="form-control form-lg" placeholder="Expliquez-nous brièvement..." style="height:120px;"><?= htmlspecialchars($desinscription_data['commentaire_desinscription'] ?? '') ?></textarea>
                                </div>
                            </div>
                            <div class="text-right" style="margin-top: 5%;"><input type="submit" name="envoyer_feedback" class="btn btn-primary" value="ENVOYER"></div>
                        </form>
                    <?php endif; ?>

                    <div class="text-center" style="margin-bottom:15px;">
                        <a href="index.php" class="btn btn-secondary">Revenir à l'accueil</a>
                    </div>

                    <hr class="separe" style="margin:30px 0;">
                    <div class="text-center" style="margin-top:20px;">
                        <p style="color:red;font-weight:bold;">
                            Vous vous êtes désinscrit par erreur ?<br>
                            <a href="mail_groupement.php?actionNotifGroupement=inscription" style="color:#ef8351;text-decoration:underline;">
                                Cliquez ici pour vous réinscrire.
                            </a>
                        </p>
                    </div>
                <?php elseif ($action === 'inscription'): ?>
                    <div class="text-center" style="margin-top:30px;">
                        <div class="alert alert-success" role="alert" style="padding:25px; border-radius:12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                            <h3 style="margin-bottom:15px;">Félicitations !</h3>
                            <p style="font-size:1.1rem; margin-bottom:10px;">
                                Vous êtes maintenant réinscrit à nos emails ! 🎉<br>
                                Vous recevrez de nouveau nos offres groupées et nos propositions de tarifs.
                            </p>
                        </div>
                        <a href="index.php" class="btn btn-secondary" style="margin-top: 20px">Revenir à l'accueil</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-sm-3">
                <?php
                include 'modules/connexion.php';
                include 'modules/activites.php';
                include 'modules/avis_clients.php';
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require('template.php');
?>
