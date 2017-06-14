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
 *      What data type to use for hashed passwords
 *      https://stackoverflow.com/questions/614476/storing-sha1-hash-values-in-mysql
 *
 * PHP version 5.5.38
 *
 * @category  Config
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */

require 'database.php';

/**
 * Drops all tables from the database. The order is crucial. Need to drop
 * tables that has foreign keys first because if you delete the foriegn key
 * table like "user", then the tables "comment", "like" and "post" will be
 * faulty since they all require "author_id" to work.
 *
 * @param String $dbh PDO Database handler.
 *
 * @return void
 */
function dropTables($dbh)
{
    $dbh->exec("drop table if exists `email_confirmation`");
    $dbh->exec("drop table if exists `comment`");
    $dbh->exec("drop table if exists `like`");
    $dbh->exec("drop table if exists `post`");
    $dbh->exec("drop table if exists `user`");
}

/**
 * Creates all tables to be used for Camagru.
 *
 * @param String $dbh PDO Database handler.
 *
 * @return void
 */
function createTables($dbh)
{
    $qry = "create table `user` (
        id              int not null auto_increment primary key,
        first           varchar(35) not null,
        last            varchar(35) not null,
        username        varchar(40) not null unique,
        email           varchar(40) not null unique,
        password        char(128) not null,
        verified        int not null default 0,
        creation_date   datetime default current_timestamp,
        update_date     datetime on update current_timestamp
    )";
    $dbh->exec($qry);

    $qry = "create table `post` (
        id              int not null auto_increment primary key,
        author_id       int not null,
        title           varchar(60) not null,
        img_file        varchar(80) not null,
        description     varchar(1024) not null default '',
        creation_date   datetime default current_timestamp,
        update_date     datetime on update current_timestamp,
        foreign key (author_id)
            references `user`(id)
    )";
    $dbh->exec($qry);

    $qry = "create table `like` (
        id              int not null auto_increment primary key,
        post_id         int not null,
        author_id       int not null,
        creation_date   datetime default current_timestamp
    )";
    $dbh->exec($qry);

    $qry = "create table `comment` (
        id              int not null auto_increment primary key,
        post_id         int not null,
        author_id       int not null,
        comment         varchar(1024) not null,
        creation_date   datetime default current_timestamp,
        update_date     datetime on update current_timestamp
    )";
    $dbh->exec($qry);

    $qry = "create table `email_confirmation` (
        id              int not null auto_increment primary key,
        author_id       int not null,
        code            char(32) not null unique,
        creation_date   datetime default current_timestamp
    )";
    $dbh->exec($qry);
}

/**
 * Inserts dummy data for users.
 *
 * @param String $dbh PDO Database handler.
 *
 * @return void
 */
function insertDummyUsers($dbh)
{
    $dummyData = array(
        array(
            ":first" => "John",
            ":last" => "Doe",
            ":username" => "jdoe",
            ":email" => "jdoe@gmail.com",
            ":password" => hash('whirlpool', "john"),
            ":verified" => 1
        ),
        array(
            ":first" => "Akia",
            ":last" => "Vongdara",
            ":username" => "avongdar",
            ":email" => "vongdarakia@gmail.com",
            ":password" => hash('whirlpool', "password"),
            ":verified" => 1
        )
    );
    $sth = $dbh->prepare(
        'insert into `user` (first, last, username, email, password, verified)
        values (:first, :last, :username, :email, :password, :verified)'
    );
    foreach ($dummyData as $value) {
        $sth->execute($value);
    }
    $password = hash('whirlpool', "password");
    for ($i=0; $i < 100; $i++) { 
        $sth->execute(
            array(
                ":first" => "Akia" . $i,
                ":last" => "Vongdara" . $i,
                ":username" => "avongdar" . $i,
                ":email" => "vongdarakia".$i."@gmail.com",
                ":password" => $password,
                ":verified" => 1
            )
        );
    }
}

/**
 * Inserts dummy data for posts.
 *
 * @param String $dbh PDO Database handler.
 *
 * @return void
 */
function insertDummyPosts($dbh)
{
    $sth = $dbh->prepare(
        'insert into `post` (title, img_file, author_id)
        values (:title, :img_file, :author_id)'
    );

    $sth->execute(
        array(
            ":title" => "chilling",
            ":img_file" => "chilling.gif",
            ":author_id" => 2
        )
    );
    for ($i=1; $i <= 4; $i++) { 
        $sth->execute(
            array(
                ":title" => "dummy". $i,
                ":img_file" => "dummy" . $i . ".jpg",
                ":author_id" => 2
            )
        );
    }
    for ($j=1; $j <= 2; $j++) { 
        $sth->execute(
            array(
                ":title" => "dummy". ($i + $j),
                ":img_file" => "dummy" . ($i + $j) . ".jpg",
                ":author_id" => 2 + $j
            )
        );
    }
}

/**
 * Inserts dummy data for email confirmations.
 *
 * @param String $dbh PDO Database handler.
 *
 * @return void
 */
function insertDummyEmailConfirmations($dbh)
{
    $sth = $dbh->prepare(
        'insert into `email_confirmation` (author_id, code)
        values (:author_id, :code)'
    );

    $code = hash('ripemd128', 'okay');
    echo $code;
    $sth->execute(
        array(
            ":author_id" => 2,
            ":code" => $code
        )
    );
}

require 'paths.php';

// Creates a database if it doesn't exist, then connects to it.
try {
    $dbh = new PDO($DB_DSN_HOST_ONLY, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $success = $dbh->exec("create database if not exists `{$DB_NAME}`")
    or die(print_r($dbh->errorInfo(), true));
    $dbh->query("use `{$DB_NAME}`");
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . "\n";
    exit(1);
}

// Sets up the database.
try {
    dropTables($dbh);
    createTables($dbh);
    insertDummyUsers($dbh);
    insertDummyPosts($dbh);
    insertDummyEmailConfirmations($dbh);
    if (!file_exists('../'.POSTS_DIR_NAME)) {
        mkdir('../'.POSTS_DIR_NAME);
    }
} catch (PDOException $e) {
    echo 'Database setup failed: ' . $e->getMessage() . "\n";
    if (strpos($e->getMessage(), 'SQLSTATE[22001]') !== false) {
        print("String is too long");
    }
    exit(1);
}

echo "Setup complete!\n";

?>
