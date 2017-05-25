<?php 
/**
 * Signs up the user given some credentials. Will log you in after and 
 * take you to the home page if successful, otherwise back to the 
 * signup page with an error message.
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

session_start();
require_once '../config/paths.php';
require_once '../includes/lib/auth.php';

try {
    if (signUp(
        $_POST["first"],
        $_POST["last"],
        $_POST["username"],
        $_POST["email"],
        $_POST["password"]
    )
    ) {
        initSession($_POST);
        header("Location: " . SITE_DIR);
    }
} catch (Exception $e) {
    $_SESSION["err_msg"] = $e->getMessage();
    header("Location: ../pages/signup.php");
}

?>