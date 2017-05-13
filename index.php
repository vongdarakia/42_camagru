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

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Los_Angeles');

require 'config/database.php';
require 'includes/classes/User.php';

try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user = new User($dbh, array(
            "id"           => 12,
            "first"        => 13));
    // $user->getUserById(1);
    if ($user->loadById(2))
    {
        echo "loaded " . $user->getFirstName() . "\n";
    }

    // if ($user->remove())
    // {
    //     echo "user removed";
    // }
    // else {
    //     echo "failed to remove";
    // }
    // $user->setFirstName("Kia");
    // if ($user->save())
    // {
    //     echo "user saved";
    // }
    // else {
    //     echo "failed to save";
    // }
    // $user->addUser(array(
    //     "first" => "",
    //     "last" => "",
    //     "email" => "",
    //     "password" => ""
    // ));

    if ($user->validEmail("z@us.42.fr")) {
        echo "valid email\n";
    }
    else {
        echo "invalid email\n";
    }
    // $sth = $dbh->prepare("select * from `user` where id = 5");
    // $sth->execute();

    // $result = $sth->setFetchMode(PDO::FETCH_ASSOC);
    // $obj = $sth->fetchObject();
    // foreach($result as $row) {

    // }

    // if ($obj) {
    //     echo "found";
    // }
    // else {
    //     echo "not found";
    // }
    


}
catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . "\n";
}
catch (Exception $e) {
    echo $e->getMessage() . "\n";
}


?>