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
 * @license   No License
 * @link      localhost:8080
 */

require_once '../config/paths.php';
require_once '../includes/lib/features.php';

session_start();

/**
 * Saves a file from a file upload.
 *
 * @param String $imgName Image file name without extension.
 *
 * @return Boolean Success
 */
function saveFileFromUpload($imgName)
{
    $validImg = getimagesize($_FILES["file"]["tmp_name"]);
    if ($validImg !== false) {
        $ext = strtolower(strrchr($_FILES["file"]["name"], "."));
        $targetFile = POSTS_DIR . $imgName . $ext;
        $_SESSION["err_msg"] = "File is good: " . $validImg["mime"];
        move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile);
        return true;
    } else {
        $_SESSION["err_msg"] = "File is not an image";
    }
    return false;
}

/**
 * Saves a file from a base 64 image that was retrieved from a webcam capture.
 *
 * @param String $imgName Image file name without extension.
 * @param String $data    Base 64 image string.
 *
 * @return Boolean Success
 */
function saveFileFromCapture($imgName, $data)
{
    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
    if (!$data) {
        $_SESSION["err_msg"] = "Invalid image. Must be based 64."
            . " Please contact vongdarakia@gmail.com";
        return false;
    }
    $targetFile = POSTS_DIR . $imgName . ".png";
    file_put_contents($targetFile, $data);
    return true;
}

$okayImageTypes = [
    "image/jpeg",
    "image/png",
    "image/gif",
    "image/pjpeg"
];

try {
    if (isset($_POST["submit"])) {
        $_SESSION["err_msg"] = "Submission error";
        header("Location: ../pages/post.php");
    }
    // Sets title
    $title = "";
    if (!isset($_POST["title"]) || $_POST["title"] == "") {
        $title = $_SESSION["user_login"];
    } else {
        $title = $_POST["title"];
    }

    // Sets image name without an extension. That will be set in the
    // saveFileFromUpload and saveFileFromCapture functions.
    $imgName = str_replace(" ", "_", strtolower(trim($title)));
    $imgName = $imgName . "_" . date('YmdHis');
    
    // Records the post to the database, and if successful, saves the image.
    if (post(
        $_POST["email"],
        $title,
        $imgName,
        $_POST["description"]
    )
    ) {
        if (isset($_FILES["file"]) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            saveFileFromUpload($imgName);
        } else if (isset($_POST["base64img"]) && $_POST["base64img"] != "") {
            saveFileFromCapture($imgName, $_POST["base64img"]);
        } else {
            $_SESSION["err_msg"] = "No image source was given to upload.";    
        }
    } else {
        $_SESSION["err_msg"] = "Failed to save post.";
    }
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'SQLSTATE[22001]') !== false) {
        $_SESSION["err_msg"] = "Title must be less than or equal to 60 characters.";
    } else {
        $_SESSION["err_msg"] = $e->getMessage();
    }
}
header("Location: ../pages/post.php");

?>