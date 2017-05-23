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
 * Checks if the base 64 data is an image.
 *
 * @param String $base64 Base 64 data of an image.
 *
 * @return Boolean Success
 */
function checkBase64Image($base64)
{
    $img = imagecreatefromstring(base64_decode($base64));
    if (!$img) {
        return false;
    }

    imagepng($img, 'tmp.png');
    $info = getimagesize('tmp.png');

    unlink('tmp.png');

    if ($info[0] > 0 && $info[1] > 0 && $info['mime']
        && ($info[2] == IMG_JPG || $info[2] == IMG_PNG 
        || $info[2] == IMAGETYPE_PNG)
    ) {
        return true;
    }
    return false;
}


/**
 * PNG ALPHA CHANNEL SUPPORT for imagecopymerge()
 * by Sina Salek (From PHP Documentation comments)
 *
 * Resources:
 *      http://php.net/manual/en/function.imagecopymerge.php
 *
 * @param Resource $dst_im Destination image link resource.
 * @param Resource $src_im Source image link resource.
 * @param Int      $dst_x  x-coordinate of destination point.
 * @param Int      $dst_y  y-coordinate of destination point.
 * @param Int      $src_x  x-coordinate of source point.
 * @param Int      $src_y  y-coordinate of source point.
 * @param Int      $src_w  Source width.
 * @param Int      $src_h  Source height.
 * @param Float    $pct    The two images will be merged according
 *                         to pct which can range from 0 to 100.
 *
 * @return Boolean Success
 */
function imageCopyMergeAlpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y,
    $src_w, $src_h, $pct
) { 
    // creating a cut resource 
    $cut = imagecreatetruecolor($src_w, $src_h); 

    // copying relevant section from background to the cut resource 
    imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h); 
    
    // copying relevant section from watermark to the cut resource 
    imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h); 
    
    // insert cut resource to destination image 
    return imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
}

/**
 * Merges two images together.
 *
 * @param String  $img_name Image file name.
 * @param String  $bg       Base 64 data image string or File
 * @param String  $stkr     Base 64 data image string that will overlay
 * @param Boolean $is_file  If background is file or base 64 string
 *
 * @return Boolean Success
 */
function superimposeImages($img_name, $bg, $stkr, $is_file)
{
    $bg64    = false;
    $bg_img  = false;
    $pattern = '#^data:image/\w+;base64,#i';
    $type = IMG_PNG;

    if ($is_file) {
        
        $size = getimagesize($_FILES["file"]["tmp_name"]);
        $type = $size[2];

        if ($type === IMG_JPG) {
            $bg_img = imageCreateFromJpeg($_FILES["file"]["tmp_name"]);
        } else {
            $bg_img = imageCreateFromPng($_FILES["file"]["tmp_name"]);
        }
    } else {
        $bg_img_str = base64_decode(preg_replace($pattern, '', $bg));
        $bg_img = imagecreatefromstring($bg_img_str);
    }
    $stkr_img_str = base64_decode(preg_replace($pattern, '', $stkr));
    $stkr_img = imagecreatefromstring($stkr_img_str);

    $src_w = imagesx($stkr_img);
    $src_h = imagesy($stkr_img);
    imageCopyMergeAlpha($bg_img, $stkr_img, 0, 0, 0, 0, $src_w, $src_h, 100);
    if ($type === IMG_JPG) {
        return imagejpeg($bg_img, POSTS_DIR . $img_name);
    }
    return imagepng($bg_img, POSTS_DIR . $img_name);
}

/**
 * Saves a file from a file upload.
 *
 * @param String $img_name Image file name.
 *
 * @return Boolean Success
 */
function saveFileFromUpload($img_name)
{
    // superimposeGif($img_name, $_POST["stickerImg"]);
    superimposeImages($img_name, null, $_POST["stickerImg"], true);
    // move_uploaded_file($_FILES["file"]["tmp_name"], POSTS_DIR . $img_name);
    return false;
}

/**
 * Saves a file from a base 64 image that was retrieved from a webcam capture.
 *
 * @param String $img_name Image file name.
 *
 * @return Boolean Success
 */
function saveFileFromCapture($img_name)
{
    $cam_data = $_POST["camImg"];
    $stker_data = $_POST["stickerImg"];
    $new_img = superimposeImages($img_name, $cam_data, $stker_data, false);
    // file_put_contents(POSTS_DIR . $img_name, $new_img);
    return true;
}

session_start();

require_once '../config/paths.php';
require_once '../includes/lib/auth.php';
require_once '../includes/lib/features.php';

checkUserAuthentication();

$pattern = '#^data:image/\w+;base64,#i';

try {
    if (!isset($_POST["submit"]) || $_POST["submit"] != "Post") {
        $_SESSION["err_msg"] = "Submission error";
        header("Location: ../pages/post.php");
    }

    // Check is post directory is writable first before posting anything.
    if (!is_writable(POSTS_DIR)) {
        $_SESSION["err_msg"] = "Can't write to posts folder.";
        header("Location: ../pages/post.php");
        return;
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
        // Checks if image is valid
        $validImg = getimagesize($_FILES["file"]["tmp_name"]);
        if ($validImg === false
            || !($validImg[2] == IMG_JPG || $validImg[2] == IMG_PNG)
        ) {
            $_SESSION["err_msg"] = "File is not an image. Must be png or jpg.";
            header("Location: ../pages/post.php");
            return;
        }
        $imgName = $imgName . strtolower(strrchr($_FILES["file"]["name"], "."));
    } else if (isset($_POST["camImg"]) && $_POST["camImg"] != "") {
        // Uses png if we're using web cam image.
        // Checks if base 64 image is valid
        $cam64 = preg_replace($pattern, '', $_POST["camImg"]);
        if (!checkBase64Image($cam64)) {
            $_SESSION["err_msg"] = "Invalid image. Must be based 64."
                . " Please contact vongdarakia@gmail.com";
            // header("Location: ../pages/post.php");
            return false;
        }
        $imgName = $imgName . ".png";
    } else {
        $_SESSION["err_msg"] = "No image source was given to upload.";
        header("Location: ../pages/post.php");
        return;
    }

    // Check for sticker image
    if (!(isset($_POST["stickerImg"]) && $_POST["stickerImg"] != "")) {
        $_SESSION["err_msg"] = "Sticker image was not found";
        header("Location: ../pages/post.php");
        return;
    }

    // Checks if image is valid
    $stkr64 = preg_replace($pattern, '', $_POST["stickerImg"]);
    if (!checkBase64Image($stkr64)) {
        $_SESSION["err_msg"] = "Sticker is not an image";
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
            saveFileFromCapture($imgName);
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