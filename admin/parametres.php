<?php
session_start();
if(!isset($_SESSION['user'])) {
    header('Location: /admin/connexion.php');
    die();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Forcer l'encodage UTF-8 côté HTTP
if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}

$title = 'Paramètres';
$title_page = 'Paramètres';
ob_start();

include_once __DIR__ . "/../inc/pmp_co_connect.php";
// Fonctions admin côté back office
include_once __DIR__ . "/inc/pmp_inc_fonctions_compte_admin.php";

$message = null; $message_type = null; $message_icone = null; $message_titre = null; $errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = (string)($_SESSION['user'] ?? '');
    $mdp_actuel = (string)($_POST['mdp_actuel'] ?? '');
    $mdp_nouveau = (string)($_POST['mdp_nouveau'] ?? '');
    $mdp_confirm = (string)($_POST['mdp_confirm'] ?? '');

    if ($mdp_nouveau !== $mdp_confirm) {
        $errors[] = 'Les mots de passes ne correspondent pas.';
    }

    if (empty($errors)) {
        $result = pmp_admin_update_password($co_pmp, $user, $mdp_actuel, $mdp_nouveau);
        $errors = $result['errors'];
        if ($result['success']) {
            $message_type = $result['message_type'];
            $message_titre = $result['message_title'];
            $message_icone = $result['message_icon'];
            $message = $result['message'];
        } else {
            $message_type = 'no';
            $message_titre = 'Erreur';
            $message_icone = 'fa-times';
        }
    } else {
        $message_type = 'no';
        $message_titre = 'Erreurs';
        $message_icone = 'fa-times';
    }
}
?>

<div class="param-panel">
    <div class="param-section-title">Paramètres du compte</div>
    <div class="param-section-sub">Mettez à jour votre mot de passe administrateur.</div>

    <?php
    if (($message || !empty($errors)) && !isset($message_modal)) {
        if (!empty($errors)) {
            $message_type = 'no';
            $message_titre = $message_titre ?: 'Erreurs';
            $message_icone = $message_icone ?: 'fal fa-times';
            // Construire une liste HTML des erreurs selon le pattern existant
            $list = '<ul style="margin:0; padding-left: 18px;">';
            foreach ($errors as $e) { $list .= '<li>'.htmlspecialchars($e).'</li>'; }
            $list .= '</ul>';
            $message = $list;
        } else {
            $message_type = $message_type ?: 'success';
            $message_titre = $message_titre ?: 'Succès';
            $message_icone = $message_icone ?: 'fa-check';
        }
        ?>
        <div class="toast <?= $message_type; ?>" style="margin: 10px 0;">
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

    <form method="post" class="param-card" aria-describedby="rulesHelp">
        <h5 class="param-card-title">Changer mon mot de passe</h5>

        <div class="form-group mb-3">
            <label class="form-label" for="mdp_actuel">Mot de passe actuel</label>
            <div class="input-group param-input-group">
                <input type="password" id="mdp_actuel" name="mdp_actuel" class="form-control" required autocomplete="current-password">
                <div class="input-group-append">
                    <button class="btn btn-secondary param-toggle-pass" type="button" data-target="mdp_actuel" aria-label="Afficher le mot de passe" aria-pressed="false"><i class="far fa-eye-slash"></i></button>
                </div>
            </div>
        </div>

        <?php
            $has_rules_error = false;
            if (!empty($_POST['changer_mdp']) && !empty($errors)) {
                foreach ($errors as $e) {
                    if (stripos($e, 'Au moins') !== false || stripos($e, 'identifiant') !== false) { $has_rules_error = true; break; }
                }
            }
        ?>
        <div class="form-group mb-3">
            <label class="form-label" for="mdp_nouveau">Nouveau mot de passe</label>
            <div class="input-group param-input-group">
                <input type="password" id="mdp_nouveau" name="mdp_nouveau" class="form-control <?= (!empty($_POST['changer_mdp']) && $has_rules_error) ? 'is-invalid' : '' ?>" required minlength="8"
                       pattern="^(?=.*[A-Z])(?=.*[^A-Za-z0-9]).{8,}$"
                       title="Au moins 8 caractères, avec au moins une majuscule et un caractère spécial"
                       aria-describedby="rulesHelp" autocomplete="new-password">
                <div class="input-group-append">
                    <button class="btn btn-secondary param-toggle-pass" type="button" data-target="mdp_nouveau" aria-label="Afficher le mot de passe" aria-pressed="false"><i class="far fa-eye-slash"></i></button>
                </div>
            </div>
        </div>

        <?php $mismatch = (!empty($_POST['changer_mdp']) && in_array('Les mots de passes ne correspondent pas.', $errors, true)); ?>
        <div class="form-group mb-3">
            <label class="form-label" for="mdp_confirm">Confirmer le nouveau mot de passe</label>
            <div class="input-group param-input-group">
                <input type="password" id="mdp_confirm" name="mdp_confirm" class="form-control <?= $mismatch ? 'is-invalid' : '' ?>" required minlength="8" autocomplete="new-password">
                <div class="input-group-append">
                    <button class="btn btn-secondary param-toggle-pass" type="button" data-target="mdp_confirm" aria-label="Afficher le mot de passe" aria-pressed="false"><i class="far fa-eye-slash"></i></button>
                </div>
            </div>
            <?php if ($mismatch): ?>
                <div class="invalid-feedback" id="confirmError" role="alert">Les mots de passes ne correspondent pas.</div>
            <?php endif; ?>
        </div>

        <div id="rulesHelp" class="param-rules" role="status" aria-live="polite" aria-atomic="true">
            Doit contenir :
            <ul class="param-rules-list" id="rulesList">
                <li id="ruleLength" class="invalid">Au moins 8 caractères</li>
                <li id="ruleUpper" class="invalid">Au moins une lettre majuscule</li>
                <li id="ruleSpecial" class="invalid">Au moins un caractère spécial (ex. ! @ # $ % ^ & *)</li>
            </ul>
        </div>

        <div class="param-actions">
            <button type="submit" name="changer_mdp" class="btn btn-primary">Enregistrer</button>
        </div>
    </form>
</div>

<script src="js/parametres.js"></script>

<?php
$content = ob_get_clean();
require('template.php');
?>
