<?php
// Fonctions liées au compte admin (mise à jour mot de passe)

/**
 * Valide les règles de complexité du mot de passe admin.
 * Règles: min 8 caractères, au moins 1 majuscule, au moins 1 caractère spécial, ne contient pas l'identifiant.
 * @return array Liste des erreurs (vide si OK)
 */
function pmp_admin_validate_password_rules(string $password, string $username = ''): array {
    $errors = [];
    if (strlen($password) < 8) {
        $errors[] = 'Au moins 8 caractères.';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Au moins une lettre majuscule.';
    }
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = 'Au moins un caractère spécial (ex. ! @ # $ % ^ & *).';
    }
    if ($username !== '' && stripos($password, $username) !== false) {
        $errors[] = 'Le mot de passe ne doit pas contenir votre identifiant.';
    }
    return $errors;
}

/**
 * Met à jour le mot de passe admin si l'actuel est correct et que les règles sont respectées.
 * Retourne un tableau avec 'success' (bool), 'errors' (array), 'message', 'message_type', 'message_title', 'message_icon'.
 */
function pmp_admin_update_password(mysqli $co_pmp, string $user, string $current_password, string $new_password): array {
    $out = [
        'success' => false,
        'errors' => [],
        'message' => null,
        'message_type' => null,
        'message_title' => null,
        'message_icon' => null,
    ];

    $user_esc = mysqli_real_escape_string($co_pmp, $user);

    // Règles de complexité
    $out['errors'] = pmp_admin_validate_password_rules($new_password, $user);
    if (!empty($out['errors'])) {
        return $out;
    }

    // Charger le mot de passe stocké
    $res = my_query($co_pmp, "SELECT mdp_pmp FROM pmp_version WHERE user = '{$user_esc}' LIMIT 1");
    $row = $res ? mysqli_fetch_assoc($res) : null;
    if (!$row) {
        $out['errors'][] = 'Utilisateur introuvable.';
        return $out;
    }

    $stored = (string)$row['mdp_pmp'];
    $ok = false;
    if (preg_match('/^\$2y\$/', $stored)) {
        $ok = password_verify($current_password, $stored);
    } else {
        $ok = hash_equals($stored, $current_password);
    }
    if (!$ok) {
        $out['errors'][] = 'Mot de passe actuel incorrect.';
        return $out;
    }

    // Interdire la réutilisation
    if (hash_equals($current_password, $new_password)) {
        $out['errors'][] = 'Le nouveau mot de passe doit être différent de l’actuel.';
        return $out;
    }

    // Mise à jour
    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    $hash_sql = mysqli_real_escape_string($co_pmp, $hash);
    $upd = my_query($co_pmp, "UPDATE pmp_version SET mdp_pmp = '{$hash_sql}' WHERE user = '{$user_esc}'");
    if ($upd) {
        $out['success'] = true;
        $out['message_type'] = 'success';
        $out['message_title'] = 'Succès';
        $out['message_icon'] = 'fa-check';
        $out['message'] = 'Mot de passe mis à jour';
    } else {
        $out['errors'][] = 'Une erreur est survenue lors de la mise à jour.';
    }
    return $out;
}

