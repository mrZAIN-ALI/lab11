<?php
// Include session management file
include_once "session.php";

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Destroy the user_id cookie
if (isset($_COOKIE['user_id'])) {
    // Delete the cookie by setting its expiration time to the past
    setcookie('user_id', '', time() - 3600, '/');
}

// Redirect to login page
header("Location: login.php");
exit;
?>
