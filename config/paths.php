<?php 
/**
 * Path variables for directories.
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

    defined("SITE_ROOT")
    or define ('SITE_ROOT', realpath(dirname(__DIR__)));

    defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", dirname(__DIR__) . '/templates');

    defined("CONFIG_PATH")
    or define("CONFIG_PATH", dirname(__DIR__) . '/config');

    defined("CSS_DIR")
    or define("CSS_DIR",  '/camagru/resources/css/');

    defined("ACTIONS_DIR")
    or define("ACTIONS_DIR", '/camagru/actions/');

    defined("PAGES_DIR")
    or define("PAGES_DIR", '/camagru/pages/');

    defined("POSTS_DIR_NAME")
    or define("POSTS_DIR_NAME", 'posts');

    defined("POSTS_DIR")
    or define("POSTS_DIR", SITE_ROOT . '/' . POSTS_DIR_NAME . '/');

    
    // echo substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])) . PHP_EOL;
    // echo realpath(dirname(__DIR__)) . $PHP_EOL;
    // echo $_SERVER['DOCUMENT_ROOT'];

    // echo SITE_ROOT;
    ini_set('display_errors', 1);
    error_reporting(E_ALL|E_STRICT);
    date_default_timezone_set('America/Los_Angeles');
?>