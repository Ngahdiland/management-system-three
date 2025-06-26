<?php
session_start();
require_once 'db.php';
require_once 'middleware.php';

// Ensure user is logged in and is a student
requireRole('student');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('student/homework.php', 'Invalid request method', 'danger');
}

try {
    $studentId = getCurrentUserId();
    $homeworkId = $_POST['homework_id'] ?? null;
    $notes = trim($_POST['notes'] ?? '');
    
    // Validate homework ID
    if (!$homeworkId) {
        throw new Exception('Homework ID is required');
    }
    
    // Check if homework exists and is active
    $homeworkSql = "SELECT h.*, c.course_name FROM homework h 
                    JOIN courses c ON h.course_id = c.id 
                    WHERE h.id = ? AND h.due_date > NOW()";
    $homework = fetchRow($homeworkSql, [$homeworkId]);
    
    if (!$homework) {
        throw new Exception('Homework not found or deadline has passed');
    }
    
    // Check if student is enrolled in the course
    $enrollmentSql = "SELECT e.id FROM enrollments e 
                      JOIN courses c ON e.course_id = c.id 
                      WHERE e.student_id = ? AND c.id = ? AND e.status = 'enrolled'";
    $enrollment = fetchRow($enrollmentSql, [$studentId, $homework['course_id']]);
    
    if (!$enrollment) {
        throw new Exception('You are not enrolled in this course');
    }
    
    // Check if already submitted
    $existingSql = "SELECT id FROM submissions WHERE homework_id = ? AND student_id = ?";
    $existing = fetchRow($existingSql, [$homeworkId, $studentId]);
    
    if ($existing) {
        throw new Exception('You have already submitted this homework');
    }
    
    // Handle file upload
    if (!isset($_FILES['assignment_file']) || $_FILES['assignment_file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Please select a file to upload');
    }
    
    $file = $_FILES['assignment_file'];
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];
    
    // Validate file
    $allowedTypes = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    if (!in_array($fileExtension, $allowedTypes)) {
        throw new Exception('Invalid file type. Allowed types: ' . implode(', ', $allowedTypes));
    }
    
    // Check file size (10MB limit)
    $maxFileSize = 10 * 1024 * 1024; // 10MB
    if ($fileSize > $maxFileSize) {
        throw new Exception('File size exceeds 10MB limit');
    }
    
    // Create upload directory if it doesn't exist
    $uploadDir = 'uploads/homework/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Generate unique filename
    $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExtension;
    $filePath = $uploadDir . $uniqueFileName;
    
    // Move uploaded file
    if (!move_uploaded_file($fileTmpName, $filePath)) {
        throw new Exception('Failed to upload file');
    }
    
    // Determine submission status (on time or late)
    $submissionStatus = 'submitted';
    if (strtotime($homework['due_date']) < time()) {
        $submissionStatus = 'late';
    }
    
    // Insert submission into database
    $insertSql = "INSERT INTO submissions (homework_id, student_id, file_path, file_name, file_size, submission_date, status, feedback) 
                  VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";
    
    executeQuery($insertSql, [
        $homeworkId, 
        $studentId, 
        $filePath, 
        $fileName, 
        $fileSize, 
        $submissionStatus, 
        $notes
    ]);
    
    $submissionId = getLastInsertId();
    
    // Log the submission
    logActivity('homework_submission', "Submitted homework: {$homework['title']} for {$homework['course_name']}");
    
    // Prepare success message
    $statusMessage = $submissionStatus === 'late' ? ' (Late submission)' : '';
    $message = "Homework submitted successfully{$statusMessage}";
    
    // Redirect with success message
    redirectWithMessage('student/homework.php', $message, 'success');
    
} catch (Exception $e) {
    error_log("Homework upload error: " . $e->getMessage());
    
    // Clean up uploaded file if it exists
    if (isset($filePath) && file_exists($filePath)) {
        unlink($filePath);
    }
    
    redirectWithMessage('student/homework.php', 'Upload failed: ' . $e->getMessage(), 'danger');
}
?> 