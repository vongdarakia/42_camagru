<?php
/**
 * User action to delete their post.
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
$User = new User($dbh);

try {
    if (isset($_POST["post_id"])) {
        $post_id = urldecode($_POST["post_id"]);
        $Post->loadById($post_id);
        $User->loadByEmail($_SESSION["user_email"]);

        $path = POSTS_PATH . $Post->getImgFile();
        
        if ($Post->getId() > 0 &&
            $Post->getAuthorId() == $User->getId() &&
            $Post->removeById($post_id) == 1
        ) {
            if (file_exists($path)) {
                unlink($path);    
            }
            sendData("OK");
        } else {
            sendData("Error: Failed to delete.");
        }
    }
} catch (Exception $e) {
    sendError($e->getMessage(), 200);
}
?>