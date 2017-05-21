<?php 
/**
 * Home page to view people's posts.
 *
 * Resources:
 *      Best Pagination Practices
 *      https://uxplanet.org/pagination-best-practices-76fbd3f5a78d
 *
 * PHP version 5.5.38
 *
 * @category  Home
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */


ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('America/Los_Angeles');

session_start();

if (!isset($_SESSION["user_email"])) {
    header("location: pages/login.php");
}

require_once 'config/paths.php';
require_once 'config/database.php';
require_once 'config/connect.php';
require_once 'includes/models/User.php';
require_once 'includes/models/Post.php';
require_once 'includes/lib/auth.php';
// require_once 'includes/models/Post.php';
// require_once 'includes/models/Like.php';
// require_once 'includes/models/Comment.php';

try {
    // $user = new User($dbh, array(
    //         "id"           => 12,
    //         "first"        => 13));

    // $post = new Post($dbh, array(
    //         "id"           => 1,
    //         "author_id"        => 1));

    // $like = new Like($dbh);
    // $comment = new Comment($dbh);

}
catch (Exception $e) {
    echo $e->getMessage() . "\n";
}

require_once TEMPLATES_PATH . "/header.php";
?>

<div id="container">
    <!-- <h2>Home</h2> -->
    <?php displayError(); ?>

    <div id="public-posts">
        <?php 
            // echo "<h3>Hello " . $_SESSION["user_first"] . " " . $_SESSION["user_last"] . ", " 
            // . $_SESSION["user_login"] . " - " . $_SESSION["user_email"] . "</h3>";


            // $user = new User($dbh);
            $post = new Post($dbh);
            $query = "select
                u.first 'author_fn',
                u.last 'author_ln',
                u.username 'author_login',
                u.email 'author_email',
                p.title 'title',
                p.img_file 'img_file',
                p.creation_date 'post_creation_date'
            from `user` u inner join `post` p on p.author_id = u.id";

            $info = $post->getDataByPage(1, 10, $query);
            foreach ($info->rows as $row) {
                include 'templates/user_post_box.php';
            }
        ?>
        <?php require_once TEMPLATES_PATH . "/pagination.php"; ?>
    </div>
    
</div>


<?php require_once TEMPLATES_PATH . "/footer.php"; ?>