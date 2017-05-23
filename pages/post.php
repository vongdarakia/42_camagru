<?php 
/**
 * Post page. User post their images.
 *
 * PHP version 5.5.38
 *
 * @category  Page
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */

session_start();
require_once '../config/paths.php';
require_once '../includes/lib/auth.php';


// Maybe use this.
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // …
// }
checkUserAuthentication();
$email = $_SESSION["user_email"];

require_once TEMPLATES_PATH . "/header.php";
?>

<div id="container">
    <h2>Post</h2>
    <?php  
    if (isset($_SESSION["err_msg"]) && $_SESSION["err_msg"] != "") {
        echo "Error: " . $_SESSION["err_msg"];
        $_SESSION["err_msg"] = "";
    }
    ?>
    <input
        type="hidden" 
        value="<?php echo IMG_DIR ?>"
        id="img-dir"
    >
    <form action="<?php echo ACTIONS_DIR ?>post.php"
        method="post"
        enctype="multipart/form-data">
        <div id="booth">
            <div class="video-wrapper">
                <img src="" id="sticker-photo">
                <video id="camera" width="400" height="300"></video>
            </div>
            
            <a href="#" id="btn-capture">Take Photo</a>

            <canvas id="canvas" width="400" height="300"></canvas>
            <img src="" id="cam-photo">

            <canvas id="canvas2" width="400" height="300"></canvas>
            
        </div>
        <input type="email" name="email" value="<?php echo $email; ?>">
        <input type="text" name="title" placeholder="title">
        <input type="text" name="description" placeholder="description">
        <input type="file" name="file" id="file">
        <input type="hidden" name="camImg" value="" id="cam-img">
        <input type="hidden" name="stickerImg" value="" id="sticker-img">
        <input type="submit" name="submit" value="Post">
        <!-- <button>Post</button> -->
    </form>
</div>

<script src="<?php echo JS_DIR . "post.js" ?>">
    
</script>

<?php require_once TEMPLATES_PATH . "/footer.php"; ?>