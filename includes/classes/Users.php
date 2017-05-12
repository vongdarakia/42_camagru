<?php 
/**
 * Configuration data for the database.
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
class Users
{
	var $first;
	var $last;
	var $email;
	var $password;
	
	function __construct($vals)
	{
		echo $vals;
	}
}

$var = new Users("Someone");
?>