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
 * @license   Akia's Public License
 * @link      localhost:8080
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Los_Angeles');

require_once '../includes/lib/auth.php';
session_start();

if (auth($_POST["email"], $_POST["password"])) {
    $_SESSION["user_email"] = $_POST["email"];
} else {
    $_SESSION["user_email"] = "";
}

if (isset($_SESSION["user_email"]) && !empty($_SESSION["user_email"])) {
    header("Location: ../index.php");
} else {
    header("Location: ../pages/login.php");
}
?>