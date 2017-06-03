<?php
/**
 * User action to like a post.
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
require_once '../includes/models/Like.php';

checkUserAuthentication();

header_status(200);
$Like = new Like($dbh);
$User = new User($dbh);

try {
    if (isset($_POST["post_id"])) {
        $User->loadByEmail($_SESSION["user_email"]);
        $post_id = urldecode($_POST["post_id"]);
        $is_liking = urldecode($_POST["is_liking"]);
        $uid = $User->getId();

        if ($User->getId() > 0) {
            if ($is_liking == "true") {
                $res = $Like->exists($post_id, $uid);
                if (!$res) {
                    $num_rows = $Like->add(
                        array('post_id' => $post_id, 'author_id' => $uid)
                    );
                    if ($num_rows) {
                        sendData($num_rows);
                    } else {
                        sendError("Can't add duplicate", 200);
                    }
                } else {
                    // Return id
                    sendData($res[0]);
                }
            } else {
                sendData($Like->removeByPostAndAuthor($post_id, $uid));
                exit(0);
            }
        } else {
            sendError("Can't find user", 200);
        }
    }
} catch (Exception $e) {
    sendError($e->getMessage(), 200);
}
?>