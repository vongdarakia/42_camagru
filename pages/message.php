<?php 
/**
 * Page to show user a specified message like email confirmations, or greetings and thankings.
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

if (!isset($_SESSION["message"]) || $_SESSION["message"] == "") {
    header("Location: ../index.php");
}
require_once TEMPLATES_PATH . "/header.php";
?>

<div id="container">
    <?php
        echo $_SESSION["message"];
        $_SESSION["message"] = "";
    ?>
</div>

<script src="<?php echo JS_DIR . "submit.js" ?>"></script>

<?php require_once TEMPLATES_PATH . "/footer.php"; ?>