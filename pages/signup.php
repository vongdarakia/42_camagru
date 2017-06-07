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
        <input type="text" name="first" placeholder="first">
        <input type="text" name="last" placeholder="last">
        <input type="text" name="username" placeholder="username">
        <input type="email" name="email" placeholder="email">
        <input type="password" name="password" placeholder="password">
        <button>Sign Up</button>
    </form>
</div>

<?php require_once TEMPLATES_PATH . "/footer.php"; ?>