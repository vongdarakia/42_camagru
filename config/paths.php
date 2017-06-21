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
    or define('SITE_ROOT', realpath(dirname(__DIR__)));

    defined("SITE_DIR")
    or define('SITE_DIR', "");

    defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", dirname(__DIR__) . '/templates');

    defined("CONFIG_PATH")
    or define("CONFIG_PATH", dirname(__DIR__) . '/config');

    defined("MODELS_PATH")
    or define("MODELS_PATH", dirname(__DIR__) . '/includes/models');

    defined("CSS_DIR")
    or define("CSS_DIR",  SITE_DIR . '/resources/css/');

    defined("JS_DIR")
    or define("JS_DIR",  SITE_DIR . '/resources/js/');

    defined("IMG_DIR")
    or define("IMG_DIR",  SITE_DIR . '/resources/img/');

    defined("TEMPLATES_DIR")
    or define("TEMPLATES_DIR",  SITE_DIR . '/templates/');

    defined("ACTIONS_DIR")
    or define("ACTIONS_DIR", SITE_DIR . '/actions/');

    defined("PAGES_DIR")
    or define("PAGES_DIR", SITE_DIR . '/pages/');

    defined("POSTS_DIR_NAME")
    or define("POSTS_DIR_NAME", 'posts');

    defined("POSTS_DIR")
    or define("POSTS_DIR", SITE_DIR . '/' . POSTS_DIR_NAME . '/');

    defined("POSTS_PATH")
    or define("POSTS_PATH", SITE_ROOT . '/' . POSTS_DIR_NAME . '/');

    defined("WEBSITE_URL")
    or define("WEBSITE_URL", 'http://localhost:8080');

    defined("WEBSITE_HOME_URL")
    or define("WEBSITE_HOME_URL", WEBSITE_URL . SITE_DIR);

    ini_set('display_errors', 1);
    error_reporting(E_ALL|E_STRICT);
    date_default_timezone_set('America/Los_Angeles');
?>
