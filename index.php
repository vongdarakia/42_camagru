<?php 
/**
 * Home page. Has people's public posts.
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

require_once 'config/paths.php';
require_once 'config/database.php';
require_once 'config/connect.php';
require_once 'includes/models/User.php';
require_once 'includes/models/Post.php';
require_once 'includes/lib/auth.php';
// require_once 'includes/models/Post.php';
// require_once 'includes/models/Like.php';
// require_once 'includes/models/Comment.php';


$page  = 1; // Page that we're trying to get.
$limit = 20; // How many post to show per page
$relative_path = "./"; // Path to root;

if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0) {
    $page = $_GET["page"];
} else if (isset($_GET["page"]) && !is_numeric($_GET["page"])) {
    echo "wtf are you trying to do?";
}

$email = "";
if (isset($_SESSION["user_email"]) && $_SESSION["user_email"] != "") {
    $email = $_SESSION["user_email"];
}

$Post  = new Post($dbh);
$query = "select DISTINCT
    u.id 'author_id',
    u.first 'author_fn',
    u.last 'author_ln',
    u.username 'author_login',
    u.email 'author_email',
    p.id 'post_id',
    p.title 'title',
    p.img_file 'img_file',
    p.creation_date 'post_creation_date',
    ifnull(l.liked, 0) 'liked',
    ifnull(c.count, 0) 'num_likes'
from `user` u
inner join `post` p on p.author_id = u.id
left join (
    select distinct 1 'liked', p.id 'post_id'
    from `like` l
    inner join `user` u on u.id = l.author_id 
    inner join `post` p on p.id = l.post_id
    where u.email = '".$email."'
) l on l.post_id = p.id
left join (
    select p.id, count(p.id) 'count' from `post` p
    inner join `like` l on l.post_id = p.id
    inner join `user` u on u.id = l.author_id
    group by p.id
) c on c.id = p.id
order by p.creation_date desc, p.id asc";

// Pagination info
$info     = $Post->getDataByPage($page, $limit, $query);
$maxPages = ceil($info->total / $info->limit);

// Limits the user to the max page if they try to exceed it.
if ($page > $maxPages) {
    $info = $Post->getDataByPage($maxPages, $limit, $query);
}



require_once TEMPLATES_PATH . "/header.php";
?>

<div class="container">
    <?php displayError(); ?>

    <div id="public-posts">
        <?php 
        foreach ($info->rows as $post) {
            include 'templates/user_post_box.php';
        }
        ?>
    </div>
    <?php require_once TEMPLATES_PATH . "/pagination.php"; ?>
    <input type="hidden" id="user-email" value="<?php echo $email; ?>">
    <input type="hidden" id="like-action" value="<?php echo ACTIONS_DIR ?>like.php">
</div>
<script src="<?php echo JS_DIR . "main.js" ?>"></script>

<?php require_once TEMPLATES_PATH . "/footer.php"; ?>