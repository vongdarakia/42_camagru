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
require_once '../config/paths.php';
require_once '../config/connect.php';
require_once '../includes/lib/auth.php';
require_once '../includes/models/User.php';

session_start();
$email = $_SESSION["user_email"];
$user = new User($dbh);
$query = "select
    u.first 'author_fn',
    u.last 'author_ln',
    u.username 'author_login',
    u.email 'author_email',
    p.title 'title',
    p.img_file 'img_file',
    p.creation_date 'post_creation_date'
from `user` u inner join `post` p on p.author_id = u.id
where u.email = '".$email."'
order by p.creation_date desc";
$info = $user->getDataByPage(1, 10, $query);
$relative_path = "../"; // Path to root;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Photos</title>
    <link
        href="https://fonts.googleapis.com/css?family=Pacifico|Lato:100"
        rel="stylesheet"
    >
    <link rel="stylesheet" href="<?php echo CSS_DIR; ?>font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo CSS_DIR; ?>camagru.css">
</head>
<body id="photos-body">
    <div id="photos-header">
        <h3>Recent Photos</h3>
        <hr class="style14">
    </div>
    <div id="photos">
        <?php 
        foreach ($info->rows as $row) {
            include '../templates/user_upload_box.php';
        }
        ?>
    </div>
</body>
</html>