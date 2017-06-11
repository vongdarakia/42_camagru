<?php 
/**
 * Login page. If user's already signed in. Will take user to home page.
 *
 * PHP version 5.5.38
 *
 * @category  Page
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */

session_start();
require_once '../config/paths.php';

if (isset($_SESSION["user_email"]) && $_SESSION["user_email"] != "") {
    header("Location: " . SITE_DIR);
    exit(0);
}

require_once TEMPLATES_PATH . "/header.php";
?>

<div id="container">
    
    <h3 class="err-msg">
    <?php  
    if (isset($_SESSION["err_msg"]) && $_SESSION["err_msg"] != "") {
        echo "Error: " . $_SESSION["err_msg"];
        $_SESSION["err_msg"] = "";
    }
    ?>
    </h3>
    <form class="camagru-form" action="<?php echo ACTIONS_DIR ?>login.php" method="post">
        <h2 class="thin">Login</h2>
        <div>
            <input class="form-input" type="text" name="username" placeholder="Username" required>
            <input class="form-input" type="password" name="password" placeholder="Password" required>
            <div class="btn back-shadow smooth-corners" onclick="submit()">Login</div>
            <input type="submit" class="hidden hidden-submit">
        </div>
    </form>
</div>

<script src="<?php echo JS_DIR . "submit.js" ?>"></script>

<?php require_once TEMPLATES_PATH . "/footer.php"; ?>