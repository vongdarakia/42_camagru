<?php 
/**
 * Login page.
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

if ($_SESSION["user_email"]) {
    header("Location: ../index.php");
}

require_once TEMPLATES_PATH . "/header.php";
?>

<div id="container">
    <h2>Login</h2>
    <?php  
        if (isset($_SESSION["error_msg"]) && $_SESSION["error_msg"] != "") {
            echo "Error: " . $_SESSION["error_msg"];
            $_SESSION["error_msg"] = "";
        }
    ?>
    <form action="<?php echo ACTIONS_DIR ?>login.php" method="post">
        <input type="email" name="email">
        <input type="password" name="password">
        <button>Login</button>
    </form>
</div>

<?php require_once TEMPLATES_PATH . "/footer.php"; ?>