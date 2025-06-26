<?php
session_start();

// Log the logout
if (isset($_SESSION['email']) && isset($_SESSION['role'])) {
    error_log("User logout: {$_SESSION['email']} ({$_SESSION['role']})");
}

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to bypass login page
header('Location: bypass_login.php');
exit();
?> 