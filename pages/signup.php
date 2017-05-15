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
 * @license   Akia's Public License
 * @link      localhost:8080
 */

session_start();
require_once '../config/paths.php';
require_once TEMPLATES_PATH . "/header.php";
?>

<div id="container">
    <h2>Sign up</h2>
    <?php  
        if (isset($_SESSION["error_msg"]) && $_SESSION["error_msg"] != "") {
            echo "Error: " . $_SESSION["error_msg"];
            $_SESSION["error_msg"] = "";
        }
    ?>
    <form action="<?php echo ACTIONS_DIR ?>signup.php" method="post">
        <input type="text" name="first">
        <input type="text" name="last">
        <input type="text" name="username">
        <input type="email" name="email">
        <input type="password" name="password">
        <button>Sign Up</button>
    </form>
</div>

<?php require_once TEMPLATES_PATH . "/footer.php"; ?>