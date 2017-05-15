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
 * @license   Akia's Public License
 * @link      localhost:8080
 */

require_once '../config/connect.php';
require_once '../includes/models/User.php';

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
?>