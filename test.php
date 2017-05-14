<?php 
/**
 * Redirects the page to the public index page.
 *
 * PHP version 5.5.38
 *
 * @category  Main
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   Akia's Public License
 * @link      localhost:8080
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Los_Angeles');

require_once 'config/database.php';
require_once 'includes/classes/User.php';
require_once 'includes/classes/Post.php';
require_once 'includes/classes/Like.php';
require_once 'includes/classes/Comment.php';

try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user = new User($dbh, array(
            "id"           => 12,
            "first"        => 13));

    $post = new Post($dbh, array(
            "id"           => 1,
            "author_id"        => 1));

    $like = new Like($dbh);
    $comment = new Comment($dbh);


    if ($comment->add(array(
        "author_id" => 1,
        "post_id" => 3,
        "comment" => "Hello comment"
    ))) {
        print("added a comment\n");
    };

 //    if ($like->add(array(
 //        "author_id" => 1,
 //        "post_id" => 3
 //    ))) {
 //     print("added a like\n");
    // };
    // $user->getById(1);
    // if ($user->loadById(1))
    // {
    //     echo "loaded " . $user->getFirstName() . "\n";
    // }
    // print("posting\n");
    // $post->add(array(
    //     "author_id" => 1,
    //     "title" => "Title",
    //     "img_name" => "image_name",
    //     "description" => "Some comment about the post"
    // ));
    // print("posted\n");
    // if ($post->loadById(2))
    // {
    //     echo "loaded " . $post->getAuthorId() . "\n";
    // }
    // else {
    //  echo "couldn't load\n";
    // }

    // print("removing: " . $post->getId() . "\n");
    // print("deleted: " . $post->removeById(2) . "\n");

    // if ($user->remove())
    // {
    //     echo "user removed";
    // }
    // else {
    //     echo "failed to remove";
    // }
    // $user->setFirstName("Kia");
    // if ($user->save())
    // {
    //     echo "user saved";
    // }
    // else {
    //     echo "failed to save";
    // }
    // $user->add(array(
    //     "first" => "Ho",
    //     "last" => "jo",
    //     "email" => "Mnad@gmail.com",
    //     "password" => hash("whirlpool", "hope")
    // ));

    // if ($user->validEmail("z@us.42.fr")) {
    //     echo "valid email\n";
    // }
    // else {
    //     echo "invalid email\n";
    // }
    // $sth = $dbh->prepare("select * from `user` where id = 5");
    // $sth->execute();

    // $result = $sth->setFetchMode(PDO::FETCH_ASSOC);
    // $obj = $sth->fetchObject();
    // foreach($result as $row) {

    // }

    // if ($obj) {
    //     echo "found";
    // }
    // else {
    //     echo "not found";
    // }
    


}
catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . "\n";
}
catch (Exception $e) {
    echo $e->getMessage() . "\n";
}


?>