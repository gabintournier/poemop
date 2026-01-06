<?php
// ==========================================
// RGPD Maintenance (désactivation & suppression)
// ==========================================
date_default_timezone_set('Europe/Paris');

$mode = $argv[1] ?? 'prod';
$isDev = ($mode === 'dev');

// Connexions BDD
$db_user = $isDev ? 'devpmp' : 'pomop-fuel';
$db_pass = $isDev ? 'Laen#t@4J3vw9cfJ' : 'Pmp!664llt';
$db_name = $isDev ? 'devpmp' : 'pomop-fuel';

// Chargement des fonctions mail + co_pmp (mysqli)
include_once __DIR__ . "/../pmp_co_connect.php";
include_once __DIR__ . "/../pmp_inc_fonctions_mail.php";

// Dossier log
$log_dir = __DIR__ . "/logs";
if (!is_dir($log_dir)) mkdir($log_dir, 0775, true);
$log_file = $log_dir . "/rgpd_" . date('Y-m-d') . "_$mode.log";

file_put_contents($log_file, "=== RGPD ($mode) - " . date('Y-m-d H:i:s') . " ===\n", FILE_APPEND);

try {
    // Connexion PDO (pour les requêtes lourdes)
    $pdo = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // =======================================
    // 1️⃣ Comptes désactivés depuis 2 ans 6 mois → rappel mail
    // =======================================
    $query_reminder = "
        SELECT 
            u.user_id, j.email, u.id_crypte, u.disabled_date
        FROM pmp_utilisateur u
        JOIN jjj_users j ON j.id = u.user_id
        WHERE u.disabled_account = 1
          AND u.disabled_date IS NOT NULL
          AND (TO_DAYS(NOW()) - TO_DAYS(u.disabled_date)) BETWEEN 912 AND 915
          AND (u.rappel_suppression_envoye IS NULL OR u.rappel_suppression_envoye = 0);
    ";

    $users = $pdo->query($query_reminder)->fetchAll(PDO::FETCH_ASSOC);

    if ($users) {
        foreach ($users as $u) {
            $email = trim($u['email']);
            $id_crypte = $u['id_crypte'];
            $disabled_date = $u['disabled_date'];

            if (!empty($email)) {
                file_put_contents($log_file, "📧 Envoi du rappel à $email\n", FILE_APPEND);
                try {
                    // ⚙️ Utilise la connexion mysqli (co_pmp)
                    global $co_pmp;
                    EnvoyerMailRappelSuppression($co_pmp, $email, $id_crypte, $disabled_date);

                    // Flag comme "rappel envoyé"
                    $update = $pdo->prepare("
                        UPDATE pmp_utilisateur 
                        SET rappel_suppression_envoye = 1 
                        WHERE user_id = ?
                    ");
                    $update->execute([$u['user_id']]);
                } catch (Throwable $e) {
                    file_put_contents($log_file, "❌ Erreur envoi mail à $email : " . $e->getMessage() . "\n", FILE_APPEND);
                }
            }
        }
    } else {
        file_put_contents($log_file, "✅ Aucun compte à notifier aujourd’hui.\n", FILE_APPEND);
    }

    // =======================================
    // 2️⃣ Suppression après 3 ans
    // =======================================
    $users_to_delete = $pdo->query("
        SELECT user_id FROM pmp_utilisateur
        WHERE disabled_account = 1
          AND (TO_DAYS(NOW()) - TO_DAYS(disabled_date)) >= 1095
    ")->fetchAll(PDO::FETCH_COLUMN);

    if ($users_to_delete) {
        foreach ($users_to_delete as $user_id) {
            $user_id = intval($user_id);

            $pdo->exec("DELETE FROM pmp_commande WHERE user_id = $user_id");
            $pdo->exec("DELETE FROM jjj_users WHERE id = $user_id");
            $pdo->exec("DELETE FROM pmp_utilisateur WHERE user_id = $user_id");

            file_put_contents($log_file, "🗑️ Suppression complète de user_id=$user_id\n", FILE_APPEND);
        }
    } else {
        file_put_contents($log_file, "✅ Aucun compte à supprimer aujourd’hui.\n", FILE_APPEND);
    }

} catch (Throwable $e) {
    file_put_contents($log_file, "❌ Erreur globale : " . $e->getMessage() . "\n", FILE_APPEND);
}

file_put_contents($log_file, "=== Fin RGPD ($mode) ===\n\n", FILE_APPEND);
