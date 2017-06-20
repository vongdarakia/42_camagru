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
    if ($user->passwordMatchesEmail($email, $pass)) {
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
 * Return if the username and password matches an account. This hashes the password
 * first before checking it.
 *
 * @param String $username Email we're trying to find.
 * @param String $pass     Password to check if it matches the given username.
 *
 * @return Boolean on if the password and username matches.
 */
function authUsername($username, $pass)
{
    global $dbh;
    $user = new User($dbh);
    if ($user->passwordMatchesUsername($username, $pass)) {
        $user->loadByUsername($username);
        if ($user->getVerified() == 1) {
            initSession(
                array(
                    "first" => $user->getFirstName(),
                    "last" => $user->getLastName(),
                    "username" => $user->getUsername(),
                    "email" => $user->getEmail()
                )
            );
            return true;
        } else {
            return "Needs verification";
        }
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
 * @param String $code     Verification code
 *
 * @return Boolean on if the password and email matches.
 */
function signUp($first, $last, $username, $email, $password, $code)
{
    global $dbh;
    $user = new User($dbh);

    if ($user->getUserByEmail($email)) {
        throw new Exception("Email already exists.", 1);
    }
    if ($user->getUserByUsername($username)) {
        throw new Exception("Username already exists.", 1);
    }

    $success = $user->add(
        array(
            "first" => $first,
            "last" => $last,
            "username" => $username,
            "email" => $email,
            "password" => $password,
            "verified" => 0
        )
    );

    if ($success) {
        $user->loadByEmail($email);
        $sth = $dbh->prepare(
            'insert into `email_confirmation` (author_id, code)
            values (:author_id, :code)'
        );
        $sth->execute(
            array(
                ":author_id" => $user->getId(),
                ":code" => $code
            )
        );
        return true;
    }
    return false;
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
 * @param String $errMsg Message to display about the error.
 *
 * @return void
 */
function checkUserAuthentication($errMsg="You must be logged in first.")
{
    if (isset($_SESSION['user_email']) && $_SESSION['user_email'] !== "") {
        return;
    }
    $_SESSION["err_msg"] = $errMsg;
    header("location: " . SITE_DIR . "/pages/login.php");
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
        echo '<h3 class="err-msg">';
        echo "Error: " . $_SESSION["err_msg"];
        echo '</h3>';
        $_SESSION["err_msg"] = "";
    }
}

/**
 * Sets the header status.
 *
 * @param String $status_code Status code to set to.
 *
 * @return void
 */
function headerStatus($status_code)
{
    static $status_codes = null;

    if ($status_codes === null) {
        $status_codes = array (
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            426 => 'Upgrade Required',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            509 => 'Bandwidth Limit Exceeded',
            510 => 'Not Extended'
        );
    }

    if ($status_codes[$status_code] !== null) {
        $status_string = $status_code . ' ' . $status_codes[$status_code];
        header(
            $_SERVER['SERVER_PROTOCOL']
            . ' ' . $status_string, true, $status_code
        );
    }
}

/**
 * Sends error message.
 *
 * @param String $err_msg  Error message to display.
 * @param String $err_code Status code.
 *
 * @return void
 */
function sendError($err_msg, $err_code)
{
    headerStatus($err_code);
    echo "Error: "  . $err_msg;
    exit(0);
}

/**
 * Sends data
 *
 * @param Mixed $data Data to send.
 *
 * @return void
 */
function sendData($data)
{
    headerStatus(200);
    echo $data;
    exit(0);
}

/**
 * Sets the session error message, and relocates to a url.
 *
 * @param String $err_msg Error message to display.
 * @param String $url     Url to relocate to.
 *
 * @return void
 */
function relocateError($err_msg, $url)
{
    $_SESSION["err_msg"] = $err_msg;
    header("Location: " . $url);
    exit(0);
}
?>