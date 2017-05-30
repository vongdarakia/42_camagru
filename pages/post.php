<?php 
/**
 * Post page. User post their images.
 *
 * Resources:
 *      Flipping image: Googled "getmedia html5 mirror"
 *      https://www.christianheilmann.com/2013/07/19/
 *      flipping-the-image-when-accessing-the-laptop-camera-with-getusermedia/
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
require_once '../config/connect.php';
require_once '../includes/lib/auth.php';
require_once '../includes/models/User.php';


// Maybe use this.
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // …
// }
checkUserAuthentication();
$email = $_SESSION["user_email"];
$user = new User($dbh);
$query = "select
    u.first 'author_fn',
    u.last 'author_ln',
    u.username 'author_login',
    u.email 'author_email',
    p.title 'title',
    p.img_file 'img_file',
    p.creation_date 'post_creation_date'
from `user` u inner join `post` p on p.author_id = u.id
where u.email = '".$email."'
order by p.creation_date desc";
$info = $user->getDataByPage(1, 10, $query);
$relative_path = "../"; // Path to root;


require_once TEMPLATES_PATH . "/header.php";
?>

<div class="container" id="post-container">
    <?php  
    if (isset($_SESSION["err_msg"]) && $_SESSION["err_msg"] != "") {
        echo "Error: " . $_SESSION["err_msg"];
        $_SESSION["err_msg"] = "";
    }
    ?>
    <div class="main">
        <div class="wrapper">
            <input
                type="hidden" 
                value="<?php echo IMG_DIR ?>"
                id="img-dir"
            >
            <input
                type="hidden" 
                value="<?php echo POSTS_DIR ?>"
                id="post-dir"
            >
            <form id="form-sticker">
            <?php 
            $stickers = ["patrick-gasp.png", "doge.png", "mustache-glasses.png"];
            for ($i=0; $i < 3; $i++) {
                echo '<label class="radio-inline">
                <input type="radio" name="opt-sticker" onclick="changeSticker(this)" value="'.$i.'">
                    <div class="sticker-box">
                        <img src="'. IMG_DIR . $stickers[$i] .'">
                    </div>
                </label>';
            }
            ?>
               <!--  <label class="radio-inline">
                    <input type="radio" name="opt-sticker" onclick="changeSticker(this)" value="1">
                    <div class="sticker-box">
                        <img id="img-patrick" src="<?php echo IMG_DIR ?>patrick-gasp.png">
                    </div>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="opt-sticker" onclick="changeSticker(this)" value="2">
                    <div class="sticker-box">
                        <img id="img-doge" src="<?php echo IMG_DIR ?>doge.png">
                    </div>
                </label>
                <label class="radio-inline">
                    <input checked type="radio" name="opt-sticker" onclick="changeSticker(this)" value="0">
                    <div class="sticker-box">
                        <img id="img-mustache" src="<?php echo IMG_DIR ?>mustache-glasses.png">
                    </div>
                </label> -->
            </form>
            <div id="booth">

                <div id="preview-wrapper" class="hidden">
                    <img src="" id="preview-cam-img">
                    <img src="" id="preview-sticker-img">
                </div>

                <div id="video-wrapper">
                    <img src="" id="sticker-img">
                    <img src="" id="camera-img">
                    <video id="camera" width="400" height="300"></video>
                </div>
                
                <a href="#" id="btn-capture">Take Photo</a>

                <div id="captured-wrapper" class="">
                    <img src="" id="captured-cam-img">
                    <img src="" id="captured-sticker-img">
                    <p>Preview</p>
                </div>

                <!-- These are used for converting images to base64 -->
                <canvas id="camera-canvas" width="400" height="300"></canvas>
                <canvas id="sticker-canvas" width="400" height="300"></canvas>
            </div>
            <form action="<?php echo ACTIONS_DIR ?>post.php"
                method="post" id="form-post"
                enctype="multipart/form-data">
                <input type="email" name="email" id="email" value="<?php echo $email; ?>">
                <input type="text" name="title" id="title" placeholder="title">
                <input type="text" name="description" id="description" placeholder="description">
                <input type="file" name="file" id="file" onchange="fileChange(this)">
                <input type="hidden" name="camImg" value="" id="cam-photo">
                <input type="hidden" name="stickerImg" value="" id="sticker-photo">
                <!-- <input type="button" name="btnSubmit" onClick="fileUpload(this.form,'../actions/post.php'); return false;" value="Post"> -->
            </form>
        </div>
    </div>
    <div class="side">
        <div id="side-photos" class="wrapper">
            <div id="photos-header">
                <h3>Recent Photos</h3>
                <hr class="style14">
            </div>
            <div id="photos">
                <?php 
                foreach ($info->rows as $row) {
                    include '../templates/user_upload_box.php';
                }
                ?>
            </div>
        </div>
    </div>
    <div id="upload"></div>
    
</div>

<script src="<?php echo JS_DIR . "main.js" ?>"></script>
<script src="<?php echo JS_DIR . "post.js" ?>">
    
</script>

<?php require_once TEMPLATES_PATH . "/footer.php"; ?>