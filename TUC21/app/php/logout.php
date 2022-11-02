<?php

session_start();
require_once("../db/connect.php");

if (isset($_COOKIE['g_csrf_token'])) {
    setcookie("g_csrf_token", null, -1);
    setcookie("g_state", null, -1);
}

session_destroy();
$conn = null;

header('Location: ../../index.php');
exit();
