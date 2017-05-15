<?php 
/**
 * Post page. User post their images. This is a test page for now. Probably won't use.
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

// Maybe use this.
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // …
// }

if (!(isset($_SESSION["user_email"]) && $_SESSION["user_email"] != "")) {
    header("Location: ../index.php");
}

require_once TEMPLATES_PATH . "/header.php";
?>

<div id="container">
    <h2>Post</h2>
    <?php  
    if (isset($_SESSION["error_msg"]) && $_SESSION["error_msg"] != "") {
        echo "Error: " . $_SESSION["error_msg"];
        $_SESSION["error_msg"] = "";
    }
    ?>
    <form action="<?php echo ACTIONS_DIR ?>post.php" method="post">
        <input type="email" name="email" value="<?php echo $_SESSION["user_email"]; ?>">
        <input type="text" name="title" placeholder="title">
        <input type="text" name="description" placeholder="description">
        <input type="text" name="img_name" placeholder="image name">
        <button>Post</button>
    </form>
</div>

<?php require_once TEMPLATES_PATH . "/footer.php"; ?>