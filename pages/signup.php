<?php 
/**
 * Sign up page.
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
require_once '../includes/lib/auth.php';

if (isset($_SESSION["user_email"]) && $_SESSION["user_email"] != "") {
    header("Location: " . WEBSITE_HOME_URL);
    exit(0);
}

if (!isset($_SESSION['first'])) {
    $_SESSION["first"] = "";
}

if (!isset($_SESSION['last'])) {
    $_SESSION["last"] = "";
}
if (!isset($_SESSION['username'])) {
    $_SESSION["username"] = "";
}

if (!isset($_SESSION['email'])) {
    $_SESSION["email"] = "";
}

require_once TEMPLATES_PATH . "/header.php";
?>

<div id="container">
    <?php displayError(); ?>
    <form class="camagru-form" action="<?php echo ACTIONS_DIR ?>signup.php" method="post">
        <h2 class="thin">Sign Up</h2>
        <div>
            <input class="form-input" type="text" name="first" placeholder="first" required
                value="<?php echo $_SESSION['first'] ?>">
            <input class="form-input" type="text" name="last" placeholder="last" required
                value="<?php echo $_SESSION['last'] ?>">
            <input class="form-input" type="text" name="username" placeholder="username" required
                value="<?php echo $_SESSION['username'] ?>">
            <input class="form-input" type="email" name="email" placeholder="email" required
                value="<?php echo $_SESSION['email'] ?>">
            <input class="form-input" type="password" name="password" placeholder="password" id="pass1" required>
            <input class="form-input" type="password" name="password2" placeholder="password again" id="pass2" required>
            <input type="submit" class="hidden hidden-submit">
            <div class="btn back-shadow smooth-corners" onclick="submit()">Sign Up</div>
        </div>
    </form>
</div>

<script src="<?php echo JS_DIR . "submit.js" ?>"></script>
<?php
require_once TEMPLATES_PATH . "/footer.php";
$_SESSION["first"] = "";
$_SESSION["last"] = "";
$_SESSION["username"] = "";
$_SESSION["email"] = "";
?>