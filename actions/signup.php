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
require_once '../includes/models/User.php';

try {
    $_SESSION["first"] = isset($_POST["first"]) ? $_POST["first"] : "";
    $_SESSION["last"] = isset($_POST["last"]) ? $_POST["last"] : "";
    $_SESSION["username"] = isset($_POST["username"]) ? $_POST["username"] : "";
    $_SESSION["email"] = isset($_POST["email"]) ? $_POST["email"] : "";
    
    if (!isset($_POST['first'])
        || !isset($_POST['last'])
        || !isset($_POST['username'])
        || !isset($_POST['email'])
        || !isset($_POST['password'])
        || !isset($_POST['password2'])
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

    $code = hash('ripemd128', urldecode($_POST["email"]) . date('YmdHis'));
    if (signUp(
        urldecode($_POST["first"]),
        urldecode($_POST["last"]),
        urldecode($_POST["username"]),
        urldecode($_POST["email"]),
        urldecode($_POST["password"]),
        $code
    )
    ) {

        $_SESSION["first"] = "";
        $_SESSION["last"] = "";
        $_SESSION["username"] = "";
        $_SESSION["email"] = "";
        $_SESSION["password"] = "";
        // initSession($_POST);


        $User = new User($dbh);
        $User->loadByEmail($_SESSION["email"]);

        $url = WEBSITE_URL . ACTIONS_DIR . "verify_email.php?code=" . $code;
        $sub = "Camagru Sign-up Confirmation";
        $msg = "Thank you for signing up for Camagru, "
            . $_POST['first'] . " " . $_POST['last']
            . "!<br><br>Your username is " . $_POST['username']
            . ".<br><br>Verify your email here: " . $url;

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($_SESSION['user_email'], $sub, $msg, $headers);
        // header("Location: " . SITE_DIR);
        $_SESSION['message'] = '<h2 class="thin"> Thank you for signing up for Camagru, '. urldecode($_POST["first"])
        .'!</h2> <p class="thin">A confirmation email has been sent to '
        . urldecode($_POST["email"]) .'</p>'
        .'<p class="thin">Please verify your email before logging in.</p>';
        header("Location: ../pages/message.php");
    } else {
        $_SESSION["message"] = '<h3 class="err-msg">Something happened. Please contact Akia Vongdara (vongdarakia@gmail.com) for support.</h3>';
        header("Location: ../pages/message.php");
        exit(0);
    }
} catch (Exception $e) {
    $_SESSION["err_msg"] = $e->getMessage();
    header("Location: ../pages/signup.php");
}
?>