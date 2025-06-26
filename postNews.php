<?php
session_start();
require_once 'db.php';
require_once 'middleware.php';

// Ensure user is logged in and is admin
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('admin/news.php', 'Invalid request method', 'danger');
}

try {
    $adminId = getCurrentUserId();
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $targetRole = $_POST['target_role'] ?? 'all';
    $isPublished = isset($_POST['is_published']) ? 1 : 0;
    
    // Validate input
    $errors = [];
    
    if (empty($title)) {
        $errors[] = 'News title is required';
    } elseif (strlen($title) > 200) {
        $errors[] = 'Title is too long (maximum 200 characters)';
    }
    
    if (empty($content)) {
        $errors[] = 'News content is required';
    } elseif (strlen($content) > 5000) {
        $errors[] = 'Content is too long (maximum 5000 characters)';
    }
    
    if (!in_array($targetRole, ['all', 'students', 'lecturers', 'admin'])) {
        $errors[] = 'Invalid target role';
    }
    
    // If there are validation errors, redirect back with errors
    if (!empty($errors)) {
        $_SESSION['news_errors'] = $errors;
        $_SESSION['news_data'] = $_POST;
        redirectWithMessage('admin/news.php', 'Please correct the errors below', 'danger');
    }
    
    // Insert news into database
    $insertSql = "INSERT INTO news (title, content, target_role, author_id, is_published, published_at) 
                  VALUES (?, ?, ?, ?, ?, NOW())";
    
    executeQuery($insertSql, [
        $title, 
        $content, 
        $targetRole, 
        $adminId, 
        $isPublished
    ]);
    
    $newsId = getLastInsertId();
    
    // Log the news posting
    $publishStatus = $isPublished ? 'published' : 'draft';
    logActivity('news_posting', "Posted news: {$title} (target: {$targetRole}, status: {$publishStatus})");
    
    // Clear any stored news data
    unset($_SESSION['news_errors'], $_SESSION['news_data']);
    
    // Redirect with success message
    $message = "News '{$title}' posted successfully";
    if (!$isPublished) {
        $message .= " (saved as draft)";
    }
    redirectWithMessage('admin/news.php', $message, 'success');
    
} catch (Exception $e) {
    error_log("News posting error: " . $e->getMessage());
    redirectWithMessage('admin/news.php', 'News posting failed: ' . $e->getMessage(), 'danger');
}
?> 