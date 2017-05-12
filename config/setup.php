<?php 
/**
 * Sets up the database with tables that will be used by the web application.
 *
 * Resources:
 *      Recommended name length
 *      http://stackoverflow.com/questions/30485/what-is-a-reasonable-length-limit-on-person-name-fields
 *
 *      Recommended email length
 *      http://stackoverflow.com/questions/1297272/how-long-should-sql-email-fields-be
 *
 *      Recommended password length based on encryption
 *      http://stackoverflow.com/questions/247304/what-data-type-to-use-for-hashed-password-field-and-what-length
 *
 *      Using Foreign Keys
 *      https://dev.mysql.com/doc/refman/5.6/en/create-table-foreign-keys.html
 *
 * PHP version 5.5.38
 *
 * @category  Config
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   Akia's Public License
 * @link      localhost:8080
 */

require 'config/database.php';

try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . "\n";
}

try {
    $dbh->exec("drop table if exists `comment`");
    $dbh->exec("drop table if exists `like`");
    $dbh->exec("drop table if exists `post`");
    $dbh->exec("drop table if exists `user`");

    $qry = "create table `user` (
        id int not null auto_increment primary key,
        first varchar(35),
        last varchar(35),
        email varchar(40),
        password varchar(128)
    )";
    $dbh->exec($qry);

    $qry = "create table `post` (
        id int not null auto_increment primary key,
        img_path varchar(128),
        author_id int not null,
        foreign key (author_id)
            references `user`(id)
    )";
    $dbh->exec($qry);

    $qry = "create table `like` (
        id int not null auto_increment primary key,
        post_id int not null,
        author_id int not null,
        foreign key (author_id)
            references `user`(id),
        foreign key (post_id)
            references `post`(id)
    )";
    $dbh->exec($qry);

    $qry = "create table `comment` (
        id int not null auto_increment primary key,
        post_id int not null,
        author_id int not null,
        comment varchar(1024),
        foreign key (author_id)
            references `user`(id),
        foreign key (post_id)
            references `post`(id)
    )";
    $dbh->exec($qry);
}
catch (PDOException $e) {
    echo 'Database setup failed: ' . $e->getMessage() . "\n";
}

echo "Setup complete!\n";

?>
