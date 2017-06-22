<?php 
/**
 * A templated for the header. Has all the style sheets and javascripts.
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link
        href="https://fonts.googleapis.com/css?family=Pacifico|Lato:100"
        rel="stylesheet"
    >
    <link rel="stylesheet" type="text/css" href="<?php echo CSS_DIR; ?>camagru.css">
    <?php 
    if (isset($css_files)) {
        foreach ($css_files as $css) {
            echo '<link rel="stylesheet" type="text/css" href="'.CSS_DIR.$css.'">';
        }
    }
    ?>
</head>
<body>
<div id="header">
    <div class="container">
        <div class="left-col">
            <a href="<?php echo WEBSITE_HOME_URL; ?>">
                <span class="white">Cam</span><span class="black">agru</span>
            </a>
        </div>
        <div class="right-col">
            <ul class="nav global">
                <?php
                echo '<li><a href="'.PAGES_DIR.'post.php">Take Picture!</a></li>';
                if (isset($_SESSION["user_email"])
                    && $_SESSION["user_email"] !== ""
                ) {
                    echo '<li><a href="'.PAGES_DIR.'user.php?user='.$_SESSION['user_login'].'">Account</a></li>';
                    echo '<li><a href="'.ACTIONS_DIR.'logout.php">Log Out</a></li>';
                } else {
                    echo '<li><a href="'.PAGES_DIR.'login.php">Log In</a></li>';
                    echo '<li><a href="'.PAGES_DIR.'signup.php">Sign up</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
    <input
        type="hidden" 
        value="<?php echo IMG_DIR ?>"
        id="img-dir"
    >
    <input
        type="hidden" 
        value="<?php echo POSTS_DIR ?>"
        id="post-dir"
    >
    <input
        type="hidden" 
        value="<?php echo ACTIONS_DIR ?>"
        id="action-dir"
    >
    <input
        type="hidden" 
        value="<?php echo PAGES_DIR ?>"
        id="pages-dir"
    >
</div>
