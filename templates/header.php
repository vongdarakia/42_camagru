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
    <title>Camagru</title>
    <link
        href="https://fonts.googleapis.com/css?family=Pacifico|Lato:100"
        rel="stylesheet"
    >
    <link rel="stylesheet" type="text/css" href="<?php echo CSS_DIR; ?>camagru.css">
    
</head>
<body>
<div id="header">
    <div class="left-col">
        <a href="<?php echo SITE_DIR; ?>">
            <span class="white">Cam</span><span class="black">agru</span>
        </a>
    </div>
    <div class="right-col">
        <ul class="nav global">
            <?php
            if (isset($_SESSION["user_email"])
                && $_SESSION["user_email"] !== ""
            ) {
                echo '<li><a href="'.ACTIONS_DIR.'logout.php">Log Out</a></li>';
                echo '<li><a href="'.PAGES_DIR.'post.php">Account</a></li>';
            } else {
                echo '<li><a href="'.PAGES_DIR.'login.php">Log In</a></li>';
                echo '<li><a href="'.PAGES_DIR.'signup.php">Sign up</a></li>';
            }
            ?>
        </ul>
    </div>
</div>