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
    $dbh = new PDO($DB_DSN_HOST_ONLY, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $success = $dbh->exec("create database if not exists `{$DB_NAME}`")
    or die(print_r($dbh->errorInfo(), true));
    $dbh->query("use `{$DB_NAME}`");
}
catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . "\n";
    exit(1);
}

try {
    $dbh->exec("drop table if exists `comment`");
    $dbh->exec("drop table if exists `like`");
    $dbh->exec("drop table if exists `post`");
    $dbh->exec("drop table if exists `user`");

    $qry = "create table `user` (
        id int not null auto_increment primary key,
        first varchar(35) not null,
        last varchar(35) not null,
        username varchar(40) not null,
        email varchar(40) not null,
        password varchar(128) not null
    )";
    $dbh->exec($qry);

    $qry = "create table `post` (
        id int not null auto_increment primary key,
        author_id int not null,
        title varchar(60) not null,
        img_name varchar(75) not null,
        description varchar(1024) not null default '',
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
        comment varchar(1024) not null,
        foreign key (author_id)
            references `user`(id),
        foreign key (post_id)
            references `post`(id)
    )";
    $dbh->exec($qry);

    $dummyData = array(
        array(
            ":first" => "John",
            ":last" => "Doe",
            ":username" => "jdoe",
            ":email" => "jdoe@gmail.com",
            ":password" => hash('whirlpool', "john")
        ),
        array(
            ":first" => "Akia",
            ":last" => "Vongdara",
            ":username" => "avongdar",
            ":email" => "vongdarakia@gmail.com",
            ":password" => hash('whirlpool', "password")
        )
    );
    $sth = $dbh->prepare(
        'insert into `user` (first, last, username, email, password)
        values (:first, :last, :username, :email, :password)'
    );
    foreach ($dummyData as $value) {
        
        $sth->execute($value);
    }

    $sth = $dbh->prepare(
        'insert into `post` (title, img_name, author_id)
        values (:title, :img_name, :author_id)'
    );
    $sth->execute(array(
        ":title" => "1234567890abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz",
        ":img_name" => "1234567890abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz_20170515165608",
        ":author_id" => 2
    ));
    // $sth->execute(array(
    //     ":title" => "1234567890abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxy1z",
    //     ":img_name" => "1234567890abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz_201705151656081",
    //     ":author_id" => 2
    // ));
    // $sth->execute(array(
    //     ":title" => "1234567890abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxdyz",
    //     ":img_name" => "1234567890abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz_20170515165608d",
    //     ":author_id" => 2
    // ));
}
catch (PDOException $e) {
    echo 'Database setup failed: ' . $e->getMessage() . "\n";
    if (strpos($e->getMessage(), 'SQLSTATE[22001]') !== false) {
        print("String is too long");
    }
    exit(1);
}

echo "Setup complete!\n";

?>
