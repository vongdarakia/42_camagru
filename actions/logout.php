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
 * @license   Akia's Public License
 * @link      localhost:8080
 */

session_start();

if (isset($_SESSION["user_email"]) && !empty($_SESSION["user_email"])) {
    echo $_SESSION["user_email"] . " was logged out" . PHP_EOL;
    $_SESSION["user_email"] = "";
    $_SESSION["uid"] = "";
} else {
    echo "No one is logged in." . PHP_EOL;
}
?>