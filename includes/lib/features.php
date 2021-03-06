<?php 
/**
 * Functions for posting, liking and commenting.
 *
 * PHP version 5.5.38
 *
 * @category  Functions
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */

require_once '../config/connect.php';
require_once '../includes/models/User.php';
require_once '../includes/models/Post.php';

/**
 * Posts an image by a user.
 *
 * @param String $email       Email of the author.
 * @param String $title       Title of post.
 * @param String $img_file    File name of image.
 * @param String $description Description of the post.
 *
 * @return Boolean if post was successful. Otherwise id.
 */
function post($email, $title, $img_file, $description="")
{
    global $dbh;
    $user = new User($dbh);
    $post = new Post($dbh);
    $user->loadByEmail($email);
    if ($user->getId() > 0) {
        return $post->add(
            array(
                "author_id" => $user->getId(),
                "title" => $title,
                "img_file" => $img_file,
                "description" => $description
            )
        );
    }
    return false;
}

/**
 * Get a formated date.
 *
 * @param String $date Date of item from database.
 *
 * @return String formated date.
 */
function commentDate($date)
{
    $date = strtotime($date);
    $date = date('F j, Y g:i a', $date);
    return $date;
}
?>