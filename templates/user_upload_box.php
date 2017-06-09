<?php 
/**
 * A templated for the user uploads on the post page.
 * This require the object $row and $relative_path to be present.
 *
 * $row Fields
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

$page = PAGES_DIR . 'user_post.php?post_id=' . $row['post_id'];
$img_path = $relative_path . POSTS_DIR_NAME . "/" . $row["img_file"];
$class = "perfect-box";
$style = "";

if (!isset($invisible)) {
    $invisible = "";
}
$box_class = "user-upload-box " . $invisible;

if (file_exists($img_path)) {
    $size = getimagesize($img_path);
    $width = $size[0];
    $height = $size[1];
    $boxSize = 150;

    // Change for html img to work.
    $img_path = SITE_DIR . "/" . POSTS_DIR_NAME . "/" . $row["img_file"];

    // Makes sure to fill up the box when image sizes are not
    // a 1:1 ratio.
    if ($height < $width) {
        $class = "short-height";
        $offset = -($width * $boxSize / $height - $boxSize) / 2;
        $style = "left: " . $offset . "px;";
    } else if ($width < $height) {
        $class = "short-width";
        $offset =  -($height * $boxSize / $width - $boxSize) / 2;
        $style = "top: " . $offset . "px;";
    }
}
else {
    $img_path = IMG_DIR . "/" . "image-not-available.png";
}
?>

<div class="<?php echo $box_class ?>" id="upload-box-<?php echo $row['post_id'] ?>">
    <div class="crop">
        <a href="<?php echo $page ?>" title="">
            <img
                src="<?php echo $img_path; ?>"
                class="<?php echo $class; ?>"
                style="<?php echo $style; ?>"
            >
        </a>
        <i class="btn-delete fa fa-trash-o" onclick="deletePost(<?php echo $row['post_id'] ?>)"></i>
    </div>
</div>
