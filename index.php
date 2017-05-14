<?php 
/**
 * Home page to view people's posts.
 *
 * PHP version 5.5.38
 *
 * @category  Home
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   Akia's Public License
 * @link      localhost:8080
 */


ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('America/Los_Angeles');

require_once 'config/database.php';
require_once 'config/paths.php';
require_once 'config/connect.php';
require_once 'includes/models/User.php';
require_once 'includes/models/Post.php';
require_once 'includes/models/Like.php';
require_once 'includes/models/Comment.php';

try {
    $user = new User($dbh, array(
            "id"           => 12,
            "first"        => 13));

    $post = new Post($dbh, array(
            "id"           => 1,
            "author_id"        => 1));

    $like = new Like($dbh);
    $comment = new Comment($dbh);

}
catch (Exception $e) {
    echo $e->getMessage() . "\n";
}

require_once(TEMPLATES_PATH . "/header.php");
require_once(TEMPLATES_PATH . "/footer.php");
?>

