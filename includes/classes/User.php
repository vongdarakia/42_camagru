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
class User
{
	var $first;
	var $last;
	var $email;
	var $password;
	
	function __construct($vals)
	{
		echo $vals;
	}

	public function update($fields) {

	}

	public function remove() {

	}

	public static function getUserById($id) {

	}

	public static function getUsersByField($field, $val) {

	}

	public static function addUser($fields) {

	}

	public static function updateUserById($id) {

	}
}

$var = new User("Someone");
?>