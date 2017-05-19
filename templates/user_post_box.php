<?php 
/**
 * A templated for the user post on the home page.
 * This require the object $row to be present. $row should
 * contain information on the post, which is the author login,
 * post title, post image path, post datetime.
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
    echo "<h5> {$row['first']} </h5>";
?>
