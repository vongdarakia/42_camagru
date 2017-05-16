<?php 
/**
 * User posts their image to the server. The image's name is generated
 * here.
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
    $img_name = str_replace(" ", "_", strtolower(trim($_POST["title"])));
    $img_name = $img_name . "_" . date('YmdHis');
    if (post(
        $_POST["email"],
        $_POST["title"],
        $img_name,
        $_POST["description"]
    )
    ) {
        $_SESSION["error_msg"] = "";
    }
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'SQLSTATE[22001]') !== false) {
        $_SESSION["error_msg"] = "Title must be less than or equal to 60 characters.";
    } else {
        $_SESSION["error_msg"] = $e->getMessage();
    }
}
header("Location: ../pages/post.php");

?>