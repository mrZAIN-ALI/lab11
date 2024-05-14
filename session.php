<?php
// // Include database connection
// include_once "db.php";

// // Function to check if user is logged in
// function isLoggedIn() {
//     return isset($_COOKIE['user_id']);
// }

// // Function to login user
// function loginUser($user_id) {
//     // Set cookie to store user_id for 1 hour (3600 seconds)
//     setcookie('user_id', $user_id, time() + 3600, '/');
// }

// // Function to logout user
// function logoutUser() {
//     if (isset($_COOKIE['user_id'])) {
//         // Delete the cookie by setting its expiration time to the past
//         setcookie('user_id', '', time() - 3600, '/');
//     }
// }

// Include database connection
include_once "db.php";

// Start the session
session_start();

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to login user
function loginUser($user_id) {
    // Set session variable to store user_id
    $_SESSION['user_id'] = $user_id;
}

// Function to logout user
function logoutUser() {
    if (isset($_SESSION['user_id'])) {
        // Unset all of the session variables
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session
        session_destroy();
    }
}

?>