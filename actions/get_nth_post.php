<?php 
/**
 * User posts their image to the server. The image's name is
 * generated here.
 *
 * Resources:
 *      Superimposing images on the server
 *      https://stackoverflow.com/questions/6547784/superimpose-images-with-php
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
require_once '../config/connect.php';
require_once '../includes/lib/auth.php';
require_once '../includes/models/User.php';
require_once '../includes/models/Post.php';

checkUserAuthentication();

$Post = new Post($dbh);

try {
    if (isset($_POST["nth"])) {
        $email = $_SESSION["user_email"];
        $nth = (int)$_POST["nth"];
        $query = "
        select p.id 'post_id', p.img_file
        from `post` p
        inner join `user` u on u.id = p.author_id
        where
            u.email = '" . $email . "'
        order by p.creation_date desc, p.id asc";
        if ($nth > 0) {
            echo json_encode($Post->getNthData($nth, $query));
            exit(0);
        }
    }
} catch(Exception $e) {
    sendError($e->getMessage());
    // sendError("Invalid data.", 200);
    exit(0);
}
sendError("Invalid data.", 200);
?>