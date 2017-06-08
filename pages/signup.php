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

if (isset($_SESSION["user_email"]) && $_SESSION["user_email"] != "") {
    header("Location: " . SITE_DIR);
    exit(0);
}

require_once TEMPLATES_PATH . "/header.php";
?>

<div id="container">
    <h2>Sign up</h2>
    <?php  
    if (isset($_SESSION["err_msg"]) && $_SESSION["err_msg"] != "") {
        echo "Error: " . $_SESSION["err_msg"];
        $_SESSION["err_msg"] = "";
    }
    ?>
    <form action="<?php echo ACTIONS_DIR ?>signup.php" method="post">
        <input type="text" name="first" placeholder="first"
            value="<?php echo $_SESSION['first'] ?>">
        <input type="text" name="last" placeholder="last"
            value="<?php echo $_SESSION['last'] ?>">
        <input type="text" name="username" placeholder="username"
            value="<?php echo $_SESSION['username'] ?>">
        <input type="email" name="email" placeholder="email"
            value="<?php echo $_SESSION['email'] ?>">
        <input type="password" name="password" placeholder="password">
        <button>Sign Up</button>
    </form>
</div>

<?php
require_once TEMPLATES_PATH . "/footer.php";
$_SESSION["first"] = "";
$_SESSION["last"] = "";
$_SESSION["username"] = "";
$_SESSION["email"] = "";
$_SESSION["password"] = "";
?>