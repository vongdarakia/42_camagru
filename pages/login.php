<?php 
/**
 * Home page to view people's posts.
 *
 * PHP version 5.5.38
 *
 * @category  Home
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   Akia's Public License
 * @link      localhost:8080
 */

require_once '../config/paths.php';

require_once(TEMPLATES_PATH . "/header.php");
?>

<div id="container">
    <form action="<?php echo ACTIONS_DIR ?>login.php" method="post">
        <input type="email" name="email">
        <input type="password" name="password">
        <button>Login</button>
    </form>
</div>

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>