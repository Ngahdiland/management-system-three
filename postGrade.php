<?php
session_start();
require_once 'db.php';
require_once 'middleware.php';

// Ensure user is logged in and is a lecturer
requireRole('lecturer');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('lecturer/grading.php', 'Invalid request method', 'danger');
}

try {
    $lecturerId = getCurrentUserId();
    $studentId = $_POST['student_id'] ?? null;
    $courseId = $_POST['course_id'] ?? null;
    $assignmentName = trim($_POST['assignment_name'] ?? '');
    $assignmentType = $_POST['assignment_type'] ?? '';
    $gradePoints = $_POST['grade_points'] ?? null;
    $maxPoints = $_POST['max_points'] ?? 100;
    $weight = $_POST['weight'] ?? 1.00;
    $comments = trim($_POST['comments'] ?? '');
    
    // Validate input
    $errors = [];
    
    if (!$studentId) {
        $errors[] = 'Student ID is required';
    }
    
    if (!$courseId) {
        $errors[] = 'Course ID is required';
    }
    
    if (empty($assignmentName)) {
        $errors[] = 'Assignment name is required';
    }
    
    if (!in_array($assignmentType, ['homework', 'quiz', 'exam', 'project', 'participation'])) {
        $errors[] = 'Invalid assignment type';
    }
    
    if ($gradePoints === null || $gradePoints === '') {
        $errors[] = 'Grade points are required';
    }
    
    if (!is_numeric($gradePoints) || $gradePoints < 0) {
        $errors[] = 'Grade points must be a positive number';
    }
    
    if (!is_numeric($maxPoints) || $maxPoints <= 0) {
        $errors[] = 'Maximum points must be a positive number';
    }
    
    if ($gradePoints > $maxPoints) {
        $errors[] = 'Grade points cannot exceed maximum points';
    }
    
    if (!is_numeric($weight) || $weight < 0 || $weight > 1) {
        $errors[] = 'Weight must be between 0 and 1';
    }
    
    // If there are validation errors, redirect back with errors
    if (!empty($errors)) {
        $_SESSION['grading_errors'] = $errors;
        $_SESSION['grading_data'] = $_POST;
        redirectWithMessage('lecturer/grading.php', 'Please correct the errors below', 'danger');
    }
    
    // Verify lecturer is assigned to the course
    $courseSql = "SELECT id, course_name FROM courses WHERE id = ? AND lecturer_id = ? AND status = 'active'";
    $course = fetchRow($courseSql, [$courseId, $lecturerId]);
    
    if (!$course) {
        throw new Exception('You are not assigned to this course or course is inactive');
    }
    
    // Verify student is enrolled in the course
    $enrollmentSql = "SELECT id FROM enrollments WHERE student_id = ? AND course_id = ? AND status = 'enrolled'";
    $enrollment = fetchRow($enrollmentSql, [$studentId, $courseId]);
    
    if (!$enrollment) {
        throw new Exception('Student is not enrolled in this course');
    }
    
    // Calculate percentage and grade letter
    $percentage = ($gradePoints / $maxPoints) * 100;
    $gradeLetter = calculateGradeLetter($percentage);
    
    // Check if grade already exists for this assignment
    $existingSql = "SELECT id FROM grades WHERE student_id = ? AND course_id = ? AND assignment_name = ?";
    $existing = fetchRow($existingSql, [$studentId, $courseId, $assignmentName]);
    
    if ($existing) {
        // Update existing grade
        $updateSql = "UPDATE grades SET 
                      grade_points = ?, 
                      grade_letter = ?, 
                      max_points = ?, 
                      weight = ?, 
                      comments = ?, 
                      graded_by = ?, 
                      graded_at = NOW() 
                      WHERE id = ?";
        
        executeQuery($updateSql, [
            $gradePoints, 
            $gradeLetter, 
            $maxPoints, 
            $weight, 
            $comments, 
            $lecturerId, 
            $existing['id']
        ]);
        
        $action = 'updated';
    } else {
        // Insert new grade
        $insertSql = "INSERT INTO grades (student_id, course_id, assignment_name, assignment_type, grade_points, grade_letter, max_points, weight, comments, graded_by) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        executeQuery($insertSql, [
            $studentId, 
            $courseId, 
            $assignmentName, 
            $assignmentType, 
            $gradePoints, 
            $gradeLetter, 
            $maxPoints, 
            $weight, 
            $comments, 
            $lecturerId
        ]);
        
        $action = 'posted';
    }
    
    // Update course enrollment grade if this is a major assignment
    if (in_array($assignmentType, ['exam', 'project']) && $weight >= 0.3) {
        updateCourseGrade($studentId, $courseId);
    }
    
    // Log the grading activity
    logActivity('grade_submission', "{$action} grade for {$assignmentName} - Student: {$studentId}, Course: {$course['course_name']}");
    
    // Clear any stored grading data
    unset($_SESSION['grading_errors'], $_SESSION['grading_data']);
    
    // Redirect with success message
    $message = "Grade {$action} successfully: {$gradePoints}/{$maxPoints} ({$gradeLetter})";
    redirectWithMessage('lecturer/grading.php', $message, 'success');
    
} catch (Exception $e) {
    error_log("Grade submission error: " . $e->getMessage());
    redirectWithMessage('lecturer/grading.php', 'Grade submission failed: ' . $e->getMessage(), 'danger');
}

/**
 * Calculate grade letter based on percentage
 */
function calculateGradeLetter($percentage) {
    if ($percentage >= 93) return 'A';
    if ($percentage >= 90) return 'A-';
    if ($percentage >= 87) return 'B+';
    if ($percentage >= 83) return 'B';
    if ($percentage >= 80) return 'B-';
    if ($percentage >= 77) return 'C+';
    if ($percentage >= 73) return 'C';
    if ($percentage >= 70) return 'C-';
    if ($percentage >= 67) return 'D+';
    if ($percentage >= 63) return 'D';
    if ($percentage >= 60) return 'D-';
    return 'F';
}

/**
 * Update overall course grade for student
 */
function updateCourseGrade($studentId, $courseId) {
    try {
        // Calculate weighted average of all grades for this student in this course
        $gradesSql = "SELECT 
                        SUM(grade_points * weight) as total_weighted_points,
                        SUM(max_points * weight) as total_weighted_max
                      FROM grades 
                      WHERE student_id = ? AND course_id = ?";
        
        $gradeData = fetchRow($gradesSql, [$studentId, $courseId]);
        
        if ($gradeData && $gradeData['total_weighted_max'] > 0) {
            $overallPercentage = ($gradeData['total_weighted_points'] / $gradeData['total_weighted_max']) * 100;
            $overallGradeLetter = calculateGradeLetter($overallPercentage);
            
            // Update enrollment record
            $updateSql = "UPDATE enrollments SET grade_letter = ?, grade_points = ? 
                          WHERE student_id = ? AND course_id = ?";
            executeQuery($updateSql, [$overallGradeLetter, $overallPercentage, $studentId, $courseId]);
        }
    } catch (Exception $e) {
        error_log("Error updating course grade: " . $e->getMessage());
    }
}
?> 