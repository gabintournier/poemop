<?php
    session_start(); // demarrage de la session
    session_destroy(); // on d�truit la/les session(s), soit si vous utilisez une autre session, utilisez de pr�f�rence le unset()
    header('Location: /admin/connexion.php'); // On redirige
    die();
