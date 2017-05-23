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
        move_uploaded_file($_FILES["file"]["tmp_name"], POSTS_DIR . $imgName);
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
    file_put_contents(POSTS_DIR . $imgName, $data);
    return true;
}

session_start();

require_once '../config/paths.php';
require_once '../includes/lib/auth.php';
require_once '../includes/lib/features.php';

checkUserAuthentication();

// $okayImageTypes = [
//     "image/jpeg",
//     "image/png",
//     "image/gif",
//     "image/pjpeg"
// ];

try {
    if (!isset($_POST["submit"]) || $_POST["submit"] != "Post") {
        $_SESSION["err_msg"] = "Submission error";
        header("Location: ../pages/post.php");
    }

    // Sets image name without an extension. That will be set in the
    // saveFileFromUpload and saveFileFromCapture functions.
    $imgName = str_replace(" ", "_", strtolower(trim($_SESSION["user_login"])));
    $imgName = $imgName . "_" . date('YmdHis');
    
    // Sets title image name unless title was specified.
    $title = $imgName;
    if (isset($_POST["title"]) && $_POST["title"] !== "") {
        $title = $_POST["title"];
    }

    // Gives the image name an extension based on the type of imaged being posted.
    if (isset($_FILES["file"]) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $imgName = $imgName . strtolower(strrchr($_FILES["file"]["name"], "."));
    } else if (isset($_POST["camImg"]) && $_POST["camImg"] != "") {
        $imgName = $imgName . ".png";
    } else {
        $_SESSION["err_msg"] = "No image source was given to upload.";
        header("Location: ../pages/post.php");
        return;
    }

    if (!is_writable(POSTS_DIR)) {
        $_SESSION["err_msg"] = "Can't write to posts folder.";
        header("Location: ../pages/post.php");
        return;
    }

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
        } else {
            saveFileFromCapture($imgName, $_POST["camImg"]);
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