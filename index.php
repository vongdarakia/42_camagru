<?php 
/**
 * Redirects the page to the public index page.
 *
 * PHP version 5.5.38
 *
 * @category  Main
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   Akia's Public License
 * @link      localhost:8080
 */

require('config/database.php');

$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);

?>