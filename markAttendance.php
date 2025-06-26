<?php
session_start();
require_once 'db.php';
require_once 'middleware.php';

// Ensure user is logged in and is a lecturer
requireRole('lecturer');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('lecturer/attendance.php', 'Invalid request method', 'danger');
}

try {
    $lecturerId = getCurrentUserId();
    $courseId = $_POST['course_id'] ?? null;
    $date = $_POST['date'] ?? null;
    $attendanceData = $_POST['attendance'] ?? [];
    $notes = trim($_POST['notes'] ?? '');
    
    // Validate input
    $errors = [];
    
    if (!$courseId) {
        $errors[] = 'Course ID is required';
    }
    
    if (!$date) {
        $errors[] = 'Date is required';
    } elseif (!strtotime($date)) {
        $errors[] = 'Invalid date format';
    }
    
    if (empty($attendanceData) || !is_array($attendanceData)) {
        $errors[] = 'No attendance data provided';
    }
    
    // If there are validation errors, redirect back with errors
    if (!empty($errors)) {
        $_SESSION['attendance_errors'] = $errors;
        $_SESSION['attendance_data'] = $_POST;
        redirectWithMessage('lecturer/attendance.php', 'Please correct the errors below', 'danger');
    }
    
    // Verify lecturer is assigned to the course
    $courseSql = "SELECT id, course_name FROM courses WHERE id = ? AND lecturer_id = ? AND status = 'active'";
    $course = fetchRow($courseSql, [$courseId, $lecturerId]);
    
    if (!$course) {
        throw new Exception('You are not assigned to this course or course is inactive');
    }
    
    // Check if attendance for this date already exists
    $existingSql = "SELECT COUNT(*) as count FROM attendance WHERE course_id = ? AND date = ?";
    $existing = fetchRow($existingSql, [$courseId, $date]);
    
    if ($existing['count'] > 0) {
        throw new Exception('Attendance for this date has already been marked');
    }
    
    // Begin transaction
    beginTransaction();
    
    $successCount = 0;
    $validStatuses = ['present', 'absent', 'late', 'excused'];
    
    // Process attendance for each student
    foreach ($attendanceData as $studentId => $status) {
        try {
            // Validate student is enrolled in the course
            $enrollmentSql = "SELECT e.id, u.name FROM enrollments e 
                             JOIN users u ON e.student_id = u.id 
                             WHERE e.student_id = ? AND e.course_id = ? AND e.status = 'enrolled'";
            $enrollment = fetchRow($enrollmentSql, [$studentId, $courseId]);
            
            if (!$enrollment) {
                throw new Exception("Student {$studentId} is not enrolled in this course");
            }
            
            // Validate attendance status
            if (!in_array($status, $validStatuses)) {
                throw new Exception("Invalid attendance status for student {$enrollment['name']}");
            }
            
            // Insert attendance record
            $insertSql = "INSERT INTO attendance (course_id, student_id, date, status, marked_by, marked_at, notes) 
                          VALUES (?, ?, ?, ?, ?, NOW(), ?)";
            
            executeQuery($insertSql, [
                $courseId, 
                $studentId, 
                $date, 
                $status, 
                $lecturerId, 
                $notes
            ]);
            
            $successCount++;
            
        } catch (Exception $e) {
            // Rollback transaction on error
            rollbackTransaction();
            throw new Exception("Failed to mark attendance for student {$studentId}: " . $e->getMessage());
        }
    }
    
    // Commit transaction if all attendance records successful
    commitTransaction();
    
    // Log the attendance marking
    logActivity('attendance_marking', "Marked attendance for {$course['course_name']} on {$date} - {$successCount} students");
    
    // Clear any stored attendance data
    unset($_SESSION['attendance_errors'], $_SESSION['attendance_data']);
    
    // Redirect with success message
    $message = "Attendance marked successfully for {$successCount} students on " . date('M j, Y', strtotime($date));
    redirectWithMessage('lecturer/attendance.php', $message, 'success');
    
} catch (Exception $e) {
    error_log("Attendance marking error: " . $e->getMessage());
    
    // Rollback transaction if still active
    try {
        rollbackTransaction();
    } catch (Exception $rollbackError) {
        error_log("Transaction rollback failed: " . $rollbackError->getMessage());
    }
    
    redirectWithMessage('lecturer/attendance.php', 'Attendance marking failed: ' . $e->getMessage(), 'danger');
}
?> 