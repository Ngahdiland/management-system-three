<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user has specific role
 */
function hasRole($role) {
    return isLoggedIn() && $_SESSION['role'] === $role;
}

/**
 * Check if user has any of the specified roles
 */
function hasAnyRole($roles) {
    if (!isLoggedIn()) {
        return false;
    }
    
    if (is_string($roles)) {
        return $_SESSION['role'] === $roles;
    }
    
    if (is_array($roles)) {
        return in_array($_SESSION['role'], $roles);
    }
    
    return false;
}

/**
 * Require authentication - redirect to login if not logged in
 */
function requireAuth() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ../login.php');
        exit();
    }
}

/**
 * Require specific role - redirect to login if not logged in or wrong role
 */
function requireRole($role) {
    requireAuth();
    
    if (!hasRole($role)) {
        $_SESSION['error'] = 'Access denied. You do not have permission to access this page.';
        header('Location: ../login.php');
        exit();
    }
}

/**
 * Require any of the specified roles
 */
function requireAnyRole($roles) {
    requireAuth();
    
    if (!hasAnyRole($roles)) {
        $_SESSION['error'] = 'Access denied. You do not have permission to access this page.';
        header('Location: ../login.php');
        exit();
    }
}

/**
 * Get current user ID
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user role
 */
function getCurrentUserRole() {
    return $_SESSION['role'] ?? null;
}

/**
 * Get current user name
 */
function getCurrentUserName() {
    return $_SESSION['name'] ?? null;
}

/**
 * Get current user email
 */
function getCurrentUserEmail() {
    return $_SESSION['email'] ?? null;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return hasRole('admin');
}

/**
 * Check if user is lecturer
 */
function isLecturer() {
    return hasRole('lecturer');
}

/**
 * Check if user is student
 */
function isStudent() {
    return hasRole('student');
}

/**
 * Get user's department
 */
function getUserDepartment() {
    return $_SESSION['department'] ?? null;
}

/**
 * Get student ID (for students only)
 */
function getStudentId() {
    return $_SESSION['student_id'] ?? null;
}

/**
 * Check if session has expired (optional security feature)
 */
function isSessionExpired($maxLifetime = 3600) { // Default 1 hour
    if (!isset($_SESSION['login_time'])) {
        return true;
    }
    
    return (time() - $_SESSION['login_time']) > $maxLifetime;
}

/**
 * Refresh session login time
 */
function refreshSession() {
    $_SESSION['login_time'] = time();
}

/**
 * Log user activity
 */
function logActivity($action, $details = '') {
    if (isLoggedIn()) {
        $userId = getCurrentUserId();
        $userEmail = getCurrentUserEmail();
        $userRole = getCurrentUserRole();
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        error_log("Activity: User {$userEmail} ({$userRole}) performed {$action} - {$details} - IP: {$ip} - UA: {$userAgent}");
    }
}

/**
 * CSRF token generation and validation
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitize output to prevent XSS
 */
function sanitizeOutput($data) {
    if (is_array($data)) {
        return array_map('sanitizeOutput', $data);
    }
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect with message
 */
function redirectWithMessage($url, $message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header('Location: ' . $url);
    exit();
}

/**
 * Get and clear flash messages
 */
function getFlashMessage() {
    $message = $_SESSION['message'] ?? null;
    $type = $_SESSION['message_type'] ?? 'info';
    
    if ($message) {
        unset($_SESSION['message'], $_SESSION['message_type']);
        return ['message' => $message, 'type' => $type];
    }
    
    return null;
}

/**
 * Display flash message if exists
 */
function displayFlashMessage() {
    $flash = getFlashMessage();
    if ($flash) {
        $type = $flash['type'];
        $message = $flash['message'];
        echo "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>";
        echo htmlspecialchars($message);
        echo "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>";
        echo "</div>";
    }
}
?> 