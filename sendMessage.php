<?php
session_start();
require_once 'db.php';
require_once 'middleware.php';

// Ensure user is logged in
requireAuth();

// Set JSON content type
header('Content-Type: application/json');

try {
    $senderId = getCurrentUserId();
    $receiverId = $_POST['receiver_id'] ?? null;
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validate input
    if (!$receiverId) {
        throw new Exception('Receiver ID is required');
    }
    
    if (empty($message)) {
        throw new Exception('Message content is required');
    }
    
    if (strlen($message) > 1000) {
        throw new Exception('Message is too long (maximum 1000 characters)');
    }
    
    // Validate receiver exists and is active
    $receiverSql = "SELECT id, name, role FROM users WHERE id = ? AND status = 'active'";
    $receiver = fetchRow($receiverSql, [$receiverId]);
    
    if (!$receiver) {
        throw new Exception('Receiver not found or inactive');
    }
    
    // Prevent sending message to self
    if ($senderId == $receiverId) {
        throw new Exception('Cannot send message to yourself');
    }
    
    // Check if sender is blocked (optional feature)
    // This could be implemented with a separate blocked_users table
    
    // Insert message into database
    $insertSql = "INSERT INTO messages (sender_id, receiver_id, subject, message, sent_at) 
                  VALUES (?, ?, ?, ?, NOW())";
    
    executeQuery($insertSql, [$senderId, $receiverId, $subject, $message]);
    
    $messageId = getLastInsertId();
    
    // Get the inserted message with sender/receiver details
    $messageSql = "SELECT m.*, 
                          s.name as sender_name, 
                          s.role as sender_role,
                          r.name as receiver_name,
                          r.role as receiver_role
                   FROM messages m
                   JOIN users s ON m.sender_id = s.id
                   JOIN users r ON m.receiver_id = r.id
                   WHERE m.id = ?";
    
    $sentMessage = fetchRow($messageSql, [$messageId]);
    
    // Log the message sending
    logActivity('send_message', "Sent message to {$receiver['name']} ({$receiver['role']})");
    
    // Format response
    $response = [
        'success' => true,
        'message' => [
            'id' => $sentMessage['id'],
            'sender_id' => $sentMessage['sender_id'],
            'receiver_id' => $sentMessage['receiver_id'],
            'subject' => $sentMessage['subject'],
            'message' => $sentMessage['message'],
            'is_read' => (bool)$sentMessage['is_read'],
            'sent_at' => $sentMessage['sent_at'],
            'sender_name' => $sentMessage['sender_name'],
            'sender_role' => $sentMessage['sender_role'],
            'receiver_name' => $sentMessage['receiver_name'],
            'receiver_role' => $sentMessage['receiver_role'],
            'is_own_message' => true
        ],
        'receiver' => [
            'id' => $receiver['id'],
            'name' => $receiver['name'],
            'role' => $receiver['role']
        ]
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    error_log("Send message error: " . $e->getMessage());
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
    exit;
}
$file = __DIR__ . '/messages.json';
$messages = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$messages[] = $data;
file_put_contents($file, json_encode($messages, JSON_PRETTY_PRINT));
echo json_encode(['status' => 'success']);
?> 