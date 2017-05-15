<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('America/Los_Angeles');

require_once '../includes/lib/auth.php';
session_start();

try {
    if (
        signUp(
            $_POST["first"],
            $_POST["last"],
            $_POST["username"],
            $_POST["email"],
            $_POST["password"]
        )
    ) {
        echo "Signed up";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

?>