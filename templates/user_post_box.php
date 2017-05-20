<?php 
/**
 * A templated for the user post on the home page.
 * This require the object $row to be present.
 *
 * Fields
 *      author_fn
 *      author_ln
 *      author_login
 *      author_email
 *      title
 *      img_file
 *      post_creation_date
 *
 * PHP version 5.5.38
 *
 * @category  Template
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */
    echo "<h5> {$row['author_fn']} </h5>";
    $img = POSTS_DIR_NAME . "/" . $row["img_file"];
?>

<div class="user-post-box">
    <div class="crop">
        <img src="<?php echo $img; ?>">
    </div>
    <!-- <img src="<?php echo $img; ?>"> -->
   <!--  <div style="width: 100px; height: 100px; background-image: url(<?php echo $img; ?>); background-repeat: no-repeat;"></div> -->
</div>
