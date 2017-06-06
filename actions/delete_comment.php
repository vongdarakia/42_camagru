<?php
/**
 * User deletes a comment.
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
require_once '../includes/models/Comment.php';

checkUserAuthentication();

$Comment = new Comment($dbh);
$User = new User($dbh);

$User->loadByEmail($_SESSION['user_email']);





try {
    if (isset($_POST["comment_id"])) {
        $comment_id = urldecode($_POST["comment_id"]);
        $comment = $Comment->getById($comment_id);

        if ($User->getId() != $comment->author_id) {
            sendError("Can't delete another user's comment.", 200);
        }
        
        if ($Comment->removeById($comment_id) == 1) {
            sendData("OK");
        } else {
            sendError("Failed to delete.", 200);
        }
    }
} catch (Exception $e) {
    sendError("Bad input.", 200);
}
?>