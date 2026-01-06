<?php
// Fonction de compatibilité PHP < 8 (au cas où)
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

// Détecte si on est en CLI avec argument 'dev'
$isDev = (php_sapi_name() === 'cli' && isset($argv[1]) && $argv[1] === 'dev')
    || (isset($_SERVER['HTTP_HOST']) && (
        str_contains($_SERVER['HTTP_HOST'], 'dev')
        || str_contains($_SERVER['HTTP_HOST'], 'localhost')
    )); // adapte selon ton NDD dev

if ($isDev) {
    $host_pmp      = 'localhost';
    $user_pmp      = 'devpmp';
    $database_pmp  = 'devpmp';
    $password_pmp  = 'Laen#t@4J3vw9cfJ';
} else {
    $host_pmp      = 'localhost';
    $user_pmp      = 'pomop-fuel';
    $database_pmp  = 'pomop-fuel';
    $password_pmp  = 'Pmp!664llt';
}

date_default_timezone_set('Europe/Paris');
