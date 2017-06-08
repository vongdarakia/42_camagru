<?php
/**
 * User comment on a post.
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

if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] == ""
    || !isset($_POST["comment"]) || !isset($_POST["post_id"])
) {
    sendError("You must be logged in to comment.", 200);
    exit(0);
}

headerStatus(200);
$Comment = new Comment($dbh);
$User = new User($dbh);

try {
    $User->loadByEmail($_SESSION["user_email"]);
    $post_id = urldecode($_POST["post_id"]);
    $comment = htmlentities(urldecode($_POST["comment"]));

    if (trim($comment) == "") {
        sendError("Can't comment with empty string.", 200);
    }
    
    $comment = trim($comment);
    $uid = $User->getId();
    $num_rows = $Comment->add(
        array('post_id' => $post_id, 'author_id' => $uid, 'comment' => $comment)
    );
    if ($num_rows) {
        $last = $dbh->lastInsertId();
        $comment = $Comment->getById($last);
        $comment->author_login = $_SESSION['user_login'];
        $url = WEBSITE_URL . PAGES_DIR . "user_post.php?post_id=" . $post_id;
        $sub = "Camagru: " . $comment->author_login . " commented on your post!";
        $msg = $comment->author_login . " said,<br><br>\"" . $comment->comment
            . "\"<br><br>Log in to see more: <a href=\"".$url."\">Camagru</a>";

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($_SESSION['user_email'], $sub, $msg, $headers);
        echo json_encode($comment);
    } else {
        sendError("Couldn't add comment", 200);
    }
} catch (Exception $e) {
    sendError("Server error. Probably invalid post id.", 200);
}
?>