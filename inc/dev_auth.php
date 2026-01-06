<?php
session_start();

// === EXPIRATION DE SESSION ===
$session_lifetime = 45 * 60; // 30 minutes
if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > $session_lifetime) {
        // La session a expiré
        session_unset();
        session_destroy();
        session_start(); // recommence une nouvelle session
    }
}
$_SESSION['LAST_ACTIVITY'] = time();

// === CHECK ENV DEV ===
$host = $_SERVER['HTTP_HOST'];
$isDev = str_contains($host, 'dev') || str_contains($host, 'localhost'); // adapte selon ton NDD dev

// Si on est en DEV et pas encore autorisé → redirection vers index.php
if ($isDev && !isset($_SESSION['dev_authorized'])) {
    header('Location: ../index.php');
    exit;
}
