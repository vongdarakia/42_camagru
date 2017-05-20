<?php 
/**
 * Functions for authentification.
 *
 * PHP version 5.5.38
 *
 * @category  Functions
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */

require_once CONFIG_PATH .'/connect.php';
require_once MODELS_PATH .'/User.php';

/**
 * Return if the email and password matches an account. This hashes the password
 * first before checking it.
 *
 * @param String $email Email we're trying to find.
 * @param String $pass  Password to check if it matches the given email.
 *
 * @return Boolean on if the password and email matches.
 */
function auth($email, $pass)
{
    global $dbh;
    $user = new User($dbh);
    if ($user->passwordMatchesLogin($email, $pass)) {
        $user->loadByEmail($email);
        initSession(
            array(
                "first" => $user->getFirstName(),
                "last" => $user->getLastName(),
                "username" => $user->getUsername(),
                "email" => $user->getEmail()
            )
        );
        return true;
    }
    return false;
}

/**
 * Return if the email and password matches an account. This hashes the password
 * first before checking it.
 *
 * @param String $first    First name
 * @param String $last     Last name
 * @param String $username Username
 * @param String $email    Email
 * @param String $password Password
 *
 * @return Boolean on if the password and email matches.
 */
function signUp($first, $last, $username, $email, $password)
{
    global $dbh;
    $user = new User($dbh);

    if ($user->getUserByEmail($email)) {
        throw new Exception("Email alright exists.", 1);
    }
    if ($user->getUserByUsername($username)) {
        throw new Exception("Username alright exists.", 1);
    }
    return $user->add(
        array(
            "first" => $first,
            "last" => $last,
            "username" => $username,
            "email" => $email,
            "password" => $password
        )
    );
}

/**
 * Sets the session info when user logs in.
 *
 * @param Array $info Dictionary of the user info (first, last, username, email)
 *
 * @return void
 */
function initSession($info)
{
    $_SESSION['user_first'] = $info['first'];
    $_SESSION['user_last'] = $info['last'];
    $_SESSION['user_login'] = $info['username'];
    $_SESSION['user_email'] = $info['email'];
}

/**
 * Clears the session info when user logs out.
 *
 * @return void
 */
function clearSession()
{
    $_SESSION['user_first'] = "";
    $_SESSION['user_last'] = "";
    $_SESSION['user_login'] = "";
    $_SESSION['user_email'] = "";
}

/**
 * Checks session to see if user is logged in. If not,
 * send the user to a login page.
 *
 * @return void
 */
function checkUserAuthentication($errMsg="You must be logged in first.")
{
    if(isset($_SESSION['user_email']) && $_SESSION['user_email'] !== "") {
        return;
    }
    $_SESSION["err_msg"] = $errMsg;
    header("location: /camagru/pages/login.php");
    exit(0);
}

/**
 * Displays error message if it exists for the session, then clears it.
 * So, it should only display once.
 *
 * @return void
 */
function displayError()
{
    if (isset($_SESSION["err_msg"]) && $_SESSION["err_msg"] != "") {
        echo "Error: " . $_SESSION["err_msg"];
        $_SESSION["err_msg"] = "";
    }
}
?>