<?php
session_start();
if (!empty($_POST["connexion"])) {
    if (!empty($_POST['user']) && !empty($_POST['mdp'])) {
        $user = trim($_POST['user']);
        $password = (string)$_POST['mdp'];

        $user_sql = mysqli_real_escape_string($co_pmp, $user);
        $query = "SELECT * FROM pmp_version WHERE user = '$user_sql' LIMIT 1";
        $res = my_query($co_pmp, $query);
        $pmp_user = mysqli_fetch_array($res);

        $ok = false;
        if (!empty($pmp_user['user'])) {
            $stored = (string)$pmp_user['mdp_pmp'];
            // Si déjà hashé (bcrypt), on vérifie via password_verify
            if (preg_match('/^\$2y\$/', $stored)) {
                $ok = password_verify($password, $stored);
            } else {
                // Ancien mot de passe en clair: on compare puis on migre en hashé
                if (hash_equals($stored, $password)) {
                    $ok = true;
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $hash_sql = mysqli_real_escape_string($co_pmp, $hash);
                    my_query($co_pmp, "UPDATE pmp_version SET mdp_pmp = '$hash_sql' WHERE user = '$user_sql'");
                }
            }
        }

        if ($ok) {
            $_SESSION['user'] = $pmp_user['user'];
            header('Location: index.php');
            die();
        } else {
            $message_type = "no";
            $message_icone = "fal fa-times";
            $message_titre = "Erreur";
            $message = "L'identifiant ou le mot de passe n'est pas bon";
        }
    }
}

?>
