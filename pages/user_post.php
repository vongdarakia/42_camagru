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
require_once '../includes/models/Post.php';

function commentDate($date)
{
    $date = strtotime($date);
    $date = date('F j, Y g:i a',$date);
    return $date;
}

if (!isset($_GET["post_id"])) {
    header("Location: " . SITE_DIR);
}

$email = "";
if (isset($_SESSION["user_email"]) && $_SESSION["user_email"] != "") {
    $email = $_SESSION["user_email"];
}

$relative_path = "../"; // Path to root;
$Post = new Post($dbh);
$post = $Post->getById($_GET["post_id"]);
$user_liked_post = false;
if ($email != "") {
    $user_liked_post = $Post->didUserLike($email, $_GET["post_id"]);    
}


if (!$post) {
    echo "This post doesn't exist.";
    exit;
}

$comments = $Post->getComments($_GET["post_id"]);

if ($comments == false || $comments->rowCount() == 0) {
    $comments = [];
}

$author = $_SESSION['user_login'];

$post_time = commentDate($post->creation_date);

$num_likes = $Post->getNumLikes($_GET['post_id']);
$post_id = $_GET['post_id'];

$logged_in = isset($_SESSION["user_login"]) && $_SESSION["user_login"] != "";

// echo $num_likes;
require_once TEMPLATES_PATH . "/header.php";
?>

<div class="container">
    <input type="hidden" id="user-login" value="<?php echo $author; ?>">
    <input type="hidden" id="like-action" value="<?php echo ACTIONS_DIR ?>like.php">
    <input type="hidden" id="comment-action" value="<?php echo ACTIONS_DIR ?>comment.php">
    <input type="hidden" id="delete-comment-action" value="<?php echo ACTIONS_DIR ?>delete_comment.php">
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
                    <img src="<?php echo POSTS_DIR . $post->img_file ?>" alt="" class="">
                </div>
            </div>
            
            <div class="post-info">
                <span class="post-author"><a href="#" class="author-link"><?php echo $author; ?></a></span>
                <span class="post-time"><?php echo $post_time ?></span>
                <span id="num-likes-<?php echo $post_id ?>" class="num-likes"><?php echo $num_likes; ?></span>
                <!-- <i class="btn-like fa fa-heart-o" onclick="like(this)" post-id="<?php echo $post_id ?>"></i> -->

                <?php 
                if ($email != "") {
                    if ($user_liked_post) {
                        echo '<i class="btn-liked fa fa-heart" onclick="like(this)" post-id="' .$post_id . '"></i>';
                    } else {
                        echo '<i class="btn-like fa fa-heart-o" onclick="like(this)" post-id="' .$post_id . '"></i>';
                    }
                } else {
                    echo '<i class="btn-like fa fa-heart-o" onclick="like(this)" post-id="' .$post_id . '"></i>';
                }
                ?>
            </div>
        </div>

        <div id="comments" class="back-shadow smooth-corners">
            <div id="write-comment" class="comment-box">
                <textarea id="comment-area" name="comment" placeholder="Write a comment..." post-id="<?php echo $post_id ?>"></textarea>
                <div>
                    <div id="btn-comment" class="back-shadow smooth-corners" onclick="postComment()">Comment</div>    
                </div>
            </div>

            <?php
            foreach ($comments as $comment) {
                $time = commentDate($comment['creation_date']);
                echo '
                <div id="comment-box-'.$comment["id"].'" class="comment-box">
                    <p>
                        <span class="comment-author"><a href="#" class="author-link">'.$comment['author_login'].'</a></span>
                        <span class="comment-time">'.$time.'</span>
                    </p>
                    <p>
                        <span class="comment">'.$comment["comment"].'</span>
                    </p>';
                if ($logged_in && $_SESSION["user_login"] == $comment['author_login']) {
                    echo '<i class="btn-delete fa fa-trash-o" onclick="deleteComment(this)" comment-id="'.$comment["id"].'"></i>';
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>

<script src="<?php echo JS_DIR . "main.js" ?>"></script>
<script src="<?php echo JS_DIR . "comment.js" ?>"></script>

<?php require_once TEMPLATES_PATH . "/footer.php"; ?>