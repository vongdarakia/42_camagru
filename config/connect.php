<?php 
/**
 * Connects to the database.
 *
 * PHP version 5.5.38
 *
 * @category  Config
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */

require_once 'database.php';

$dbh = null;

try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . "\n";
    exit(1);
}
?>