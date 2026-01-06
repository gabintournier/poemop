<?php
// plus-on-est-moins-on-paie.fr/newsletter/_envoyer_mail_auto.php
// Version optimisée – traitement plus rapide, stable et sécurisé

include_once __DIR__ . "/../inc/pmp_co_connect.php";
include_once __DIR__ . "/../inc/pmp_inc_fonctions.php";
include_once __DIR__ . "/../inc/pmp_inc_fonctions_compte.php";
include_once __DIR__ . "/../inc/pmp_inc_fonctions_mail.php"; // EnvoyerMailFromTo

echo "<h1>Mail auto</h1>";

// ======================================
// 1️⃣ Providers normaux
// ======================================
echo "<h2>Providers normaux</h2>";
$query_normal = "
    SELECT id, user_id, modele_id, destinataires, chaine_cle, date_insertion
    FROM pmp_mail_auto
    WHERE etat = 'A'
      AND date_a_envoyer < NOW()
      AND LOWER(destinataires) NOT LIKE '%orange.fr'
      AND LOWER(destinataires) NOT LIKE '%orange.com'
      AND LOWER(destinataires) NOT LIKE '%wanadoo.fr'
      AND LOWER(destinataires) NOT LIKE '%wanadoo.com'
      AND LOWER(destinataires) NOT LIKE '%live.fr'
      AND LOWER(destinataires) NOT LIKE '%live.com'
      AND LOWER(destinataires) NOT LIKE '%free.fr%'
      AND LOWER(destinataires) NOT LIKE '%laposte.net'
      AND destinataires != ''
    ORDER BY priorite, date_insertion
    LIMIT 85
";
balanceXmails($co_pmp, $query_normal);


// ======================================
// 2️⃣ Providers “chiants” (taux d’erreur élevé)
// ======================================
echo "<h2>Providers chiants</h2>";
$providers = [
    'orange.fr',
    'orange.com',
    'wanadoo.fr',
    'wanadoo.com',
    'live.fr',
    'live.com',
    'free.fr',
    'laposte.net'
];

foreach ($providers as $provider) {
    $query = "
        SELECT id, user_id, modele_id, destinataires, chaine_cle, date_insertion
        FROM pmp_mail_auto
        WHERE etat = 'A'
          AND date_a_envoyer < NOW()
          AND LOWER(destinataires) LIKE '%$provider'
        ORDER BY priorite, date_insertion
        LIMIT 12
    ";
    balanceXmails($co_pmp, $query, $provider);
}

// ======================================
// FONCTIONS
// ======================================

function balanceXmails(&$co_pmp, $query, $provider = 'normal')
{
    // Détection de l'environnement (dev / prod)
    $isDev = false;
    if (php_sapi_name() === 'cli') {
        global $argv;
        if (isset($argv[1]) && $argv[1] === 'dev') $isDev = true;
    } else {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $isDev = (strpos($host, 'dev') !== false) || (strpos($host, 'localhost') !== false);
    }

    static $modele_cache = []; // cache des modèles HTML

    $res = my_query($co_pmp, $query);
    $count = 0;

    while ($pmp_mail_auto = mysqli_fetch_assoc($res)) {
        $count++;
        $id = $pmp_mail_auto['id'];
        $dest = trim($pmp_mail_auto['destinataires']);

        // --- Sécurité basique ---
        if (empty($dest)) continue;

        // --- Chargement modèle depuis cache ---
        $modele_id = $pmp_mail_auto['modele_id'];
        if (!isset($modele_cache[$modele_id])) {
            $pmp_mail_auto_modele = ChargeMailModele($co_pmp, $modele_id);
            $filename = ($pmp_mail_auto_modele['type'] == "1" ? "MODELE_" : "") . $pmp_mail_auto_modele['nom_fichier'] . ".html";
            $path = __DIR__ . "/modele/" . $filename;

            $modele_cache[$modele_id] = [
                'sujet' => $pmp_mail_auto_modele['sujet'],
                'html'  => file_exists($path) ? file_get_contents($path) : ''
            ];
        }

        $message_html = $modele_cache[$modele_id]['html'];
        $sujet = $modele_cache[$modele_id]['sujet'];

        // --- Insertion des valeurs ---
        $cle_valeur = strtok($pmp_mail_auto['chaine_cle'], "\n");
        while ($cle_valeur !== false) {
            [$cle, $valeur] = explode("|", $cle_valeur);
            $message_html = str_replace($cle, $valeur, $message_html);
            $cle_valeur = strtok("\n");
        }

        // --- Envoi du mail ---
        try {
            if (EnvoyerMailFromTo($sujet, $message_html, "", $dest)) {
                MarqueMailEnvoye($co_pmp, $id);
                echo "✅ Mail auto envoyé à $dest (modèle $modele_id, $provider)<br>";
            } else {
                MarqueMailErreur($co_pmp, $id);
                echo "❌ Mail auto NON envoyé à $dest (modèle $modele_id)<br>";
            }
        } catch (Throwable $e) {
            MarqueMailErreur($co_pmp, $id);
            error_log("Erreur mail auto ID $id : " . $e->getMessage());
        }

        // Micro pause entre chaque envoi (250 ms)
        usleep(250000);
    }

    echo "🧾 Total envoyés (ou traités) pour $provider : $count<br><br>";
}


// ======================================
// Fonctions utilitaires existantes
// ======================================
function MarqueMailEnvoye(&$co_pmp, $mail_id)
{
    $mail_id = mysqli_real_escape_string($co_pmp, $mail_id);
    $query = "UPDATE pmp_mail_auto SET etat = 'E', date_action = NOW() WHERE id = '$mail_id'";
    my_query($co_pmp, $query);
}

function MarqueMailErreur(&$co_pmp, $mail_id)
{
    $mail_id = mysqli_real_escape_string($co_pmp, $mail_id);
    $query = "UPDATE pmp_mail_auto SET etat = 'X', date_action = NOW() WHERE id = '$mail_id'";
    my_query($co_pmp, $query);
}

function ChargeMailModele(&$co_pmp, $modele_id)
{
    $modele_id = mysqli_real_escape_string($co_pmp, $modele_id);
    $query = "SELECT sujet, sujet_complet, dest, nom_fichier, type FROM pmp_mail_auto_modele WHERE id = '$modele_id'";
    $res = my_query($co_pmp, $query);
    return mysqli_fetch_array($res);
}
?>
