<?php session_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Simple Site</title>
    <link rel="stylesheet" type="text/css" href="<?php echo CSS_DIR; ?>camagru.css">
</head>

<body>
<div id="header">
    <h1>Hello <?php if (isset($_SESSION["user_email"])) { echo $_SESSION["user_email"]; } ?></h1>
    <ul class="nav global">
        <li><a href="#">Home</a></li>
        <li><a href="#">Articles</a></li>
        <li><a href="actions/logout.php">Logout</a></li>
    </ul>
</div>