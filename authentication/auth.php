<?php
require_once __DIR__ . "/../config.php";
error_reporting(E_ALL);
ini_set("display_errors", 1);

//check whether user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: authentication/login.php"); //fetch login.php
    exit;
}

//session timeout: 30 minutes
$timeout = 1800;

//check inactivity
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();

    header("Location: authentication/login.php?timeout=1"); //fetch login.php
    exit();
}

//update last activity
$_SESSION['last_activity'] = time();

?>