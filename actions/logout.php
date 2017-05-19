<?php
/**
 * Logs out the current user.
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

require_once '../includes/lib/auth.php';
session_start();

if (isset($_SESSION["user_email"]) && !empty($_SESSION["user_email"])) {
    // echo $_SESSION["user_email"] . " was logged out" . PHP_EOL;
    clearSession();

} else {
    // echo "No one is logged in." . PHP_EOL;
}
// header("Location: ../pages/login.php");
header("Location: ../index.php");
?>