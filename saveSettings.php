<?php
session_start();
require_once 'db.php';
require_once 'middleware.php';

// Ensure user is logged in and is admin
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('admin/settings.php', 'Invalid request method', 'danger');
}

try {
    $adminId = getCurrentUserId();
    
    // Get form data
    $currentSemester = trim($_POST['current_semester'] ?? '');
    $currentAcademicYear = trim($_POST['current_academic_year'] ?? '');
    $maxCourseLoad = (int)($_POST['max_course_load'] ?? 18);
    $gradingScaleA = (int)($_POST['grading_scale_a'] ?? 90);
    $gradingScaleB = (int)($_POST['grading_scale_b'] ?? 80);
    $gradingScaleC = (int)($_POST['grading_scale_c'] ?? 70);
    $gradingScaleD = (int)($_POST['grading_scale_d'] ?? 60);
    $systemName = trim($_POST['system_name'] ?? '');
    $systemEmail = trim($_POST['system_email'] ?? '');
    $fileUploadLimit = (int)($_POST['file_upload_limit'] ?? 10485760);
    
    // Feature toggles
    $enableRegistration = isset($_POST['enable_registration']) ? 1 : 0;
    $enableMessaging = isset($_POST['enable_messaging']) ? 1 : 0;
    $enableAttendance = isset($_POST['enable_attendance']) ? 1 : 0;
    $enableHomework = isset($_POST['enable_homework']) ? 1 : 0;
    $enableNews = isset($_POST['enable_news']) ? 1 : 0;
    
    // Validate input
    $errors = [];
    
    if (empty($currentSemester)) {
        $errors[] = 'Current semester is required';
    }
    
    if (empty($currentAcademicYear)) {
        $errors[] = 'Current academic year is required';
    }
    
    if ($maxCourseLoad < 1 || $maxCourseLoad > 30) {
        $errors[] = 'Maximum course load must be between 1 and 30 credits';
    }
    
    if ($gradingScaleA < 0 || $gradingScaleA > 100) {
        $errors[] = 'Grading scale A must be between 0 and 100';
    }
    
    if ($gradingScaleB < 0 || $gradingScaleB > 100) {
        $errors[] = 'Grading scale B must be between 0 and 100';
    }
    
    if ($gradingScaleC < 0 || $gradingScaleC > 100) {
        $errors[] = 'Grading scale C must be between 0 and 100';
    }
    
    if ($gradingScaleD < 0 || $gradingScaleD > 100) {
        $errors[] = 'Grading scale D must be between 0 and 100';
    }
    
    // Validate grading scale order
    if ($gradingScaleA <= $gradingScaleB || $gradingScaleB <= $gradingScaleC || $gradingScaleC <= $gradingScaleD) {
        $errors[] = 'Grading scales must be in descending order (A > B > C > D)';
    }
    
    if (empty($systemName)) {
        $errors[] = 'System name is required';
    }
    
    if (empty($systemEmail)) {
        $errors[] = 'System email is required';
    } elseif (!filter_var($systemEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid system email format';
    }
    
    if ($fileUploadLimit < 1024 * 1024 || $fileUploadLimit > 100 * 1024 * 1024) {
        $errors[] = 'File upload limit must be between 1MB and 100MB';
    }
    
    // If there are validation errors, redirect back with errors
    if (!empty($errors)) {
        $_SESSION['settings_errors'] = $errors;
        $_SESSION['settings_data'] = $_POST;
        redirectWithMessage('admin/settings.php', 'Please correct the errors below', 'danger');
    }
    
    // Begin transaction
    beginTransaction();
    
    // Define settings to update
    $settings = [
        'current_semester' => $currentSemester,
        'current_academic_year' => $currentAcademicYear,
        'max_course_load' => $maxCourseLoad,
        'grading_scale_a' => $gradingScaleA,
        'grading_scale_b' => $gradingScaleB,
        'grading_scale_c' => $gradingScaleC,
        'grading_scale_d' => $gradingScaleD,
        'system_name' => $systemName,
        'system_email' => $systemEmail,
        'file_upload_limit' => $fileUploadLimit,
        'enable_registration' => $enableRegistration,
        'enable_messaging' => $enableMessaging,
        'enable_attendance' => $enableAttendance,
        'enable_homework' => $enableHomework,
        'enable_news' => $enableNews
    ];
    
    // Update each setting
    foreach ($settings as $key => $value) {
        $updateSql = "INSERT INTO settings (setting_key, setting_value, updated_at) 
                      VALUES (?, ?, NOW()) 
                      ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = NOW()";
        
        executeQuery($updateSql, [$key, $value, $value]);
    }
    
    // Commit transaction
    commitTransaction();
    
    // Log the settings update
    logActivity('settings_update', "Updated system settings");
    
    // Clear any stored settings data
    unset($_SESSION['settings_errors'], $_SESSION['settings_data']);
    
    // Redirect with success message
    redirectWithMessage('admin/settings.php', 'Settings updated successfully', 'success');
    
} catch (Exception $e) {
    error_log("Settings update error: " . $e->getMessage());
    
    // Rollback transaction if still active
    try {
        rollbackTransaction();
    } catch (Exception $rollbackError) {
        error_log("Transaction rollback failed: " . $rollbackError->getMessage());
    }
    
    redirectWithMessage('admin/settings.php', 'Settings update failed: ' . $e->getMessage(), 'danger');
}
?> 