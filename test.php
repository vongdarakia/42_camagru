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
 * @license   No License
 * @link      localhost:8080
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Los_Angeles');

require_once 'config/database.php';
require_once 'includes/models/User.php';
require_once 'includes/models/Post.php';
require_once 'includes/models/Like.php';
require_once 'includes/models/Comment.php';

try {
    // $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // $user = new User($dbh);

    // $go = strrchr("asdfpicjpg", ".");
    // echo $go;
    // echo ctype_alnum('a?bc');
    echo htmlentities("abc<a>");
    // $obj = $user->getDataByPage(10, 10000);

    // foreach ($obj->rows as $row) {
    //     echo $row["first"] . PHP_EOL;
    // }
    // $post = new Post($dbh);

    // $like = new Like($dbh);
    // $comment = new Comment($dbh);


    // if ($comment->add(array(
    //     "author_id" => 1,
    //     "post_id" => 3,
    //     "comment" => "Hello comment"
    // ))) {
    //     print("added a comment\n");
    // };

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
    //     "img_file" => "image_name",
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
    // $count = $user->add(array(
    //     "first" => "a",
    //     "last" => "jo",
    //     "username" => "hjo",
    //     "email" => "Mnad@gmail.com",
    //     "password" => "hope"
    // ));
    // echo $count;

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