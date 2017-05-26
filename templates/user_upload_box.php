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

$img_path = $relative_path . POSTS_DIR_NAME . "/" . $row["img_file"];
if (!file_exists($img_path)) {
    return 0;
}

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
} else {
    $class = "perfect-box";
}
?>

<div class="user-upload-box">
    <div class="crop">
        <img
            src="<?php echo $img_path; ?>"
            class="<?php echo $class; ?>"
            style="<?php echo $style; ?>"
        >
    </div>
</div>
