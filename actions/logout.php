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


require_once '../config/paths.php';
require_once '../includes/lib/auth.php';

session_start();
clearSession();
header("Location: " . WEBSITE_HOME_URL);
?>