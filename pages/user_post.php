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

checkUserAuthentication();
$email = $_SESSION["user_email"];
$User = new User($dbh);
$relative_path = "../"; // Path to root;

// $img_path = $relative_path . POSTS_DIR_NAME . "/" . $post["img_file"];
// $class = "perfect-box";
// $style = "";

// if (file_exists($img_path)) {
//     $size = getimagesize($img_path);
//     $width = $size[0];
//     $height = $size[1];
//     $boxSize = 150;

//     // Change for html img to work.
//     $img_path = SITE_DIR . "/" . POSTS_DIR_NAME . "/" . $post["img_file"];

//     // Makes sure to fill up the box when image sizes are not
//     // a 1:1 ratio.
//     if ($height < $width) {
//         $class = "short-height";
//         $offset = -($width * $boxSize / $height - $boxSize) / 2;
//         $style = "left: " . $offset . "px;";
//     } else if ($width < $height) {
//         $class = "short-width";
//         $offset =  -($height * $boxSize / $width - $boxSize) / 2;
//         $style = "top: " . $offset . "px;";
//     }
// } else {
//     $img_path = IMG_DIR . "/" . "image-not-available.png";
// }
require_once TEMPLATES_PATH . "/header.php";
?>

<div class="container">
    <input type="hidden" id="user-login" value="avongdar">
    <input type="hidden" id="like-action" value="<?php echo ACTIONS_DIR ?>like.php">
    <div id="post-wrapper">
        <div id="post-box" class="back-shadow smooth-corners">
            <div class="photo-box-wrapper smooth-top-corners">
                <div class="photo-box">
                    <!-- <span class="vertical-helper"></span> -->
                    <!-- <img
                        src="<?php echo $img_path; ?>"
                        class="<?php echo $class; ?>"
                        style="<?php echo $style; ?>"
                    > -->
                    <img src="../resources/img/avongdar_20170519132145.png" alt="" class="">
                </div>
            </div>
            
            <div class="post-info">
                <span class="post-author"><a href="#" class="author-link">avongdar</a></span>
                <span class="post-time">January 24th, 2017 12:26 pm</span>
                <span id="num-likes-1" class="num-likes">1234</span>
                <i class="btn-like fa fa-heart-o" onclick="like(this)" post-id="1"></i>
            </div>
        </div>

        <div id="comments" class="back-shadow smooth-corners">
            <div id="write-comment" class="comment-box">
                <textarea id="comment-area" name="comment" placeholder="Write a comment..."></textarea>
                <div>
                    <div id="btn-comment" class="back-shadow smooth-corners" onclick="postComment()">Comment</div>    
                </div>
            </div>
            <div id="comment-box-1" class="comment-box">
                <p>
                    <span class="comment-author"><a href="#" class="author-link">avongdar</a></span>
                    <span class="comment-time">12:56 pm</span>
                </p>
                <p>
                    <span class="comment">It's so funny, I'm going to die!</span>
                </p>
                <i class="btn-delete fa fa-trash-o" onclick="deleteComment(this)" comment-id="1"></i>
            </div>

            <div id="comment-box-2" class="comment-box">
                <p>
                    <span class="comment-author"><a href="#" class="author-link">avongdar</a></span>
                    <span class="comment-time">12:55 pm</span>
                </p>
                <p>
                    <span class="comment">What are you doing!? This is a really long comment and I'm trying to see how it looks like, if there are multiple lines. Hope it turns out well. </span>
                </p>
                <i class="btn-delete fa fa-trash-o" onclick="deleteComment(this)" comment-id="2"></i>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo JS_DIR . "main.js" ?>"></script>
<script src="<?php echo JS_DIR . "comment.js" ?>"></script>

<?php require_once TEMPLATES_PATH . "/footer.php"; ?>