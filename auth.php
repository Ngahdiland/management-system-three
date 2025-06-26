<?php
session_start();
require_once 'db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = trim($_POST['role'] ?? '');
    
    // Debug: Log the received data
    error_log("Auth attempt - Email: $email, Role: $role");
    
    // Validate input
    $errors = [];
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    }
    
    if (empty($role)) {
        $errors[] = 'Please select your role';
    } elseif (!in_array($role, ['student', 'lecturer', 'admin'])) {
        $errors[] = 'Invalid role selected';
    }
    
    // If no validation errors, proceed with authentication
    if (empty($errors)) {
        try {
            // Get user from database
            $sql = "SELECT id, name, email, password, role, department, student_id, status FROM users WHERE email = ? AND role = ? AND status = 'active'";
            $user = fetchRow($sql, [$email, $role]);
            
            // Debug: Log the query result
            error_log("User query result: " . ($user ? "User found" : "User not found"));
            
            if ($user && password_verify($password, $user['password'])) {
                // Authentication successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['department'] = $user['department'];
                $_SESSION['student_id'] = $user['student_id'];
                $_SESSION['login_time'] = time();
                
                // Debug: Log successful login
                error_log("Successful login: {$email} ({$role}) - User ID: {$user['id']}");
                
                // Debug: Log session data
                error_log("Session data set: " . print_r($_SESSION, true));
                
                // Redirect based on role
                $redirect_url = '';
                switch ($role) {
                    case 'student':
                        $redirect_url = 'student/dashboard.php';
                        break;
                    case 'lecturer':
                        $redirect_url = 'lecturer/dashboard.php';
                        break;
                    case 'admin':
                        $redirect_url = 'admin/dashboard.php';
                        break;
                    default:
                        $redirect_url = 'login.php';
                }
                
                // Debug: Log redirect URL
                error_log("Redirecting to: $redirect_url");
                
                // Check if file exists before redirecting
                if (file_exists($redirect_url)) {
                    header('Location: ' . $redirect_url);
                    exit();
                } else {
                    error_log("ERROR: Dashboard file not found: $redirect_url");
                    $errors[] = 'Dashboard file not found. Please contact administrator.';
                }
                
            } else {
                // Authentication failed
                if (!$user) {
                    error_log("User not found: $email with role $role");
                    $errors[] = 'User not found with the provided email and role';
                } else {
                    error_log("Password verification failed for user: $email");
                    $errors[] = 'Invalid password';
                }
            }
            
        } catch (Exception $e) {
            error_log("Authentication error: " . $e->getMessage());
            $errors[] = 'An error occurred during authentication. Please try again.';
        }
    }
    
    // If there are errors, redirect back to login with error message
    if (!empty($errors)) {
        $_SESSION['login_errors'] = $errors;
        $_SESSION['login_email'] = $email;
        $_SESSION['login_role'] = $role;
        error_log("Login errors: " . implode(', ', $errors));
        header('Location: login.php');
        exit();
    }
} else {
    // If not POST request, redirect to login
    error_log("Non-POST request to auth.php");
    header('Location: login.php');
    exit();
}
?> 