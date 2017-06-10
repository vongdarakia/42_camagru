<?php
/**
 * Logs in a user given an email and password.
 *
 * PHP version 5.5.38
 *
 * @category  Action
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Los_Angeles');

session_start();
require_once '../config/paths.php';
require_once '../includes/lib/auth.php';

if (isset($_POST["username"])
    && isset($_POST["password"])
    && authUsername($_POST["username"], $_POST["password"])
) {
    header("Location: " . SITE_DIR);
} else {
    clearSession();
    $_SESSION['err_msg'] = "Invalid login credentials";
    header("Location: ../pages/login.php");
}

// if (isset($_SESSION["user_email"]) && !empty($_SESSION["user_email"])) {
    
// } else {
    
// }
?>