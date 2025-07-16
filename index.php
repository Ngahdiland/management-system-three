<?php
session_start();

// If user is already logged in, redirect to appropriate dashboard
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'student':
            header('Location: student/dashboard.php');
            exit();
        case 'lecturer':
            header('Location: lecturer/dashboard.php');
            exit();
        case 'admin':
            header('Location: admin/dashboard.php');
            exit();
    }
}

// If not logged in, redirect to login.php
header('Location: login.php');
exit();
?> 