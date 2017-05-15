<?php 
/**
 * Signs up the user given some credentials. Will log you in after and 
 * take you to the home page if successful, otherwise back to the 
 * signup page with an error message.
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

require_once '../includes/lib/features.php';

session_start();

try {
    if (post(
        $_POST["email"],
        $_POST["title"],
        $_POST["img_name"],
        $_POST["description"]
    )
    ) {
        // echo "Posted";
        $_SESSION["error_msg"] = "Posted";
        header("Location: ../pages/post.php");
    }
} catch (Exception $e) {
    $_SESSION["error_msg"] = $e->getMessage();
    header("Location: ../pages/post.php");
    // echo $e->getMessage();
}

?>