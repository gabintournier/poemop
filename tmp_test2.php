<?php
session_start();
$_SESSION['user'] = 'test';
session_write_close();
$_POST["action"] = "start";
$_POST["id_grp"] = 14861;
include 'admin/ajax_charger_client.php';
