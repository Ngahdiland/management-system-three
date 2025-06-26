<?php
session_start();
require_once 'db.php';
require_once 'middleware.php';

// Ensure user is logged in and is a student
requireRole('student');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('student/registration.php', 'Invalid request method', 'danger');
}

try {
    $studentId = getCurrentUserId();
    $semester = trim($_POST['semester'] ?? '');
    $academicYear = trim($_POST['academic_year'] ?? '');
    $courses = $_POST['courses'] ?? [];
    $notes = trim($_POST['notes'] ?? '');
    
    // Validate input
    $errors = [];
    
    if (empty($semester)) {
        $errors[] = 'Semester is required';
    }
    
    if (empty($academicYear)) {
        $errors[] = 'Academic year is required';
    }
    
    if (empty($courses) || !is_array($courses)) {
        $errors[] = 'Please select at least one course';
    }
    
    // Check maximum course load
    $maxCourseLoad = 18; // Default value, should come from settings
    $totalCredits = 0;
    
    // Calculate total credits for selected courses
    foreach ($courses as $courseCode) {
        $courseSql = "SELECT credits FROM courses WHERE course_code = ? AND status = 'active'";
        $course = fetchRow($courseSql, [$courseCode]);
        
        if ($course) {
            $totalCredits += $course['credits'];
        } else {
            $errors[] = "Course {$courseCode} not found or inactive";
        }
    }
    
    if ($totalCredits > $maxCourseLoad) {
        $errors[] = "Total credits ({$totalCredits}) exceed maximum course load ({$maxCourseLoad})";
    }
    
    // Check for existing enrollments
    foreach ($courses as $courseCode) {
        $existingSql = "SELECT e.id FROM enrollments e 
                       JOIN courses c ON e.course_id = c.id 
                       WHERE e.student_id = ? AND c.course_code = ? 
                       AND e.semester = ? AND e.academic_year = ? 
                       AND e.status = 'enrolled'";
        
        $existing = fetchRow($existingSql, [$studentId, $courseCode, $semester, $academicYear]);
        
        if ($existing) {
            $errors[] = "You are already enrolled in {$courseCode} for {$semester} {$academicYear}";
        }
    }
    
    // If there are validation errors, redirect back with errors
    if (!empty($errors)) {
        $_SESSION['registration_errors'] = $errors;
        $_SESSION['registration_data'] = [
            'semester' => $semester,
            'academic_year' => $academicYear,
            'courses' => $courses,
            'notes' => $notes
        ];
        redirectWithMessage('student/registration.php', 'Please correct the errors below', 'danger');
    }
    
    // Begin transaction
    beginTransaction();
    
    $successCount = 0;
    $enrolledCourses = [];
    
    // Register for each course
    foreach ($courses as $courseCode) {
        try {
            // Get course details
            $courseSql = "SELECT id, course_name, credits FROM courses WHERE course_code = ? AND status = 'active'";
            $course = fetchRow($courseSql, [$courseCode]);
            
            if (!$course) {
                throw new Exception("Course {$courseCode} not found");
            }
            
            // Check if course has available seats
            $enrolledCountSql = "SELECT COUNT(*) as count FROM enrollments 
                                WHERE course_id = ? AND semester = ? AND academic_year = ? AND status = 'enrolled'";
            $enrolledCount = fetchRow($enrolledCountSql, [$course['id'], $semester, $academicYear]);
            
            $courseCapacitySql = "SELECT max_students FROM courses WHERE id = ?";
            $courseCapacity = fetchRow($courseCapacitySql, [$course['id']]);
            
            if ($enrolledCount['count'] >= $courseCapacity['max_students']) {
                throw new Exception("Course {$courseCode} is full");
            }
            
            // Insert enrollment
            $enrollmentSql = "INSERT INTO enrollments (student_id, course_id, semester, academic_year, enrollment_date) 
                             VALUES (?, ?, ?, ?, NOW())";
            executeQuery($enrollmentSql, [$studentId, $course['id'], $semester, $academicYear]);
            
            $successCount++;
            $enrolledCourses[] = $course['course_name'];
            
            // Log the enrollment
            logActivity('course_registration', "Enrolled in {$courseCode} ({$course['course_name']}) for {$semester} {$academicYear}");
            
        } catch (Exception $e) {
            // Rollback transaction on error
            rollbackTransaction();
            throw new Exception("Failed to register for {$courseCode}: " . $e->getMessage());
        }
    }
    
    // Commit transaction if all enrollments successful
    commitTransaction();
    
    // Prepare success message
    $message = "Successfully registered for {$successCount} course(s): " . implode(', ', $enrolledCourses);
    
    // Clear any stored registration data
    unset($_SESSION['registration_errors'], $_SESSION['registration_data']);
    
    // Redirect with success message
    redirectWithMessage('student/dashboard.php', $message, 'success');
    
} catch (Exception $e) {
    // Log error
    error_log("Course registration error: " . $e->getMessage());
    
    // Rollback transaction if still active
    try {
        rollbackTransaction();
    } catch (Exception $rollbackError) {
        error_log("Transaction rollback failed: " . $rollbackError->getMessage());
    }
    
    // Redirect with error message
    redirectWithMessage('student/registration.php', 'Registration failed: ' . $e->getMessage(), 'danger');
}
?> 