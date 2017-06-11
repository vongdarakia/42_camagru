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
    $_SESSION["first"] = isset($_POST["first"]) ? $_POST["first"] : "";
    $_SESSION["last"] = isset($_POST["last"]) ? $_POST["last"] : "";
    $_SESSION["username"] = isset($_POST["username"]) ? $_POST["username"] : "";
    $_SESSION["email"] = isset($_POST["email"]) ? $_POST["email"] : "";
    
    if (!isset($_POST['first']) ||
        !isset($_POST['last']) ||
        !isset($_POST['username']) ||
        !isset($_POST['email']) ||
        !isset($_POST['password']) ||
        !isset($_POST['password2'])
    ) {
        $_SESSION["err_msg"] = 'All fields needs to be filled in.';
        header("Location: ../pages/signup.php");
        exit(0);
    }
    
    if (strcmp($_POST['password'], $_POST['password2']) != 0) {
        $_SESSION["err_msg"] = 'Passwords do not match.';
        header("Location: ../pages/signup.php");
        exit(0);
    }
    if (signUp(
        $_POST["first"],
        $_POST["last"],
        $_POST["username"],
        $_POST["email"],
        $_POST["password"]
    )
    ) {
        $_SESSION["first"] = "";
        $_SESSION["last"] = "";
        $_SESSION["username"] = "";
        $_SESSION["email"] = "";
        $_SESSION["password"] = "";
        initSession($_POST);
        header("Location: " . SITE_DIR);
    }
} catch (Exception $e) {
    $_SESSION["err_msg"] = $e->getMessage();
    header("Location: ../pages/signup.php");
}
?>