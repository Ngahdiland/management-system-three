<?php
session_start();
require_once 'db.php';
require_once 'middleware.php';

// Ensure user is logged in
requireAuth();

// Set JSON content type
header('Content-Type: application/json');

try {
    $userId = getCurrentUserId();
    $receiverId = $_GET['receiver_id'] ?? null;
    $limit = min((int)($_GET['limit'] ?? 50), 100); // Max 100 messages
    $offset = (int)($_GET['offset'] ?? 0);
    
    if (!$receiverId) {
        throw new Exception('Receiver ID is required');
    }
    
    // Validate receiver exists
    $receiverSql = "SELECT id, name, role FROM users WHERE id = ? AND status = 'active'";
    $receiver = fetchRow($receiverSql, [$receiverId]);
    
    if (!$receiver) {
        throw new Exception('Receiver not found');
    }
    
    // Get messages between the two users
    $messagesSql = "SELECT m.*, 
                           s.name as sender_name, 
                           s.role as sender_role,
                           r.name as receiver_name,
                           r.role as receiver_role
                    FROM messages m
                    JOIN users s ON m.sender_id = s.id
                    JOIN users r ON m.receiver_id = r.id
                    WHERE (m.sender_id = ? AND m.receiver_id = ?) 
                       OR (m.sender_id = ? AND m.receiver_id = ?)
                    ORDER BY m.sent_at DESC
                    LIMIT ? OFFSET ?";
    
    $messages = fetchAll($messagesSql, [$userId, $receiverId, $receiverId, $userId, $limit, $offset]);
    
    // Mark messages as read
    $markReadSql = "UPDATE messages SET is_read = TRUE 
                    WHERE receiver_id = ? AND sender_id = ? AND is_read = FALSE";
    executeQuery($markReadSql, [$userId, $receiverId]);
    
    // Format messages for response
    $formattedMessages = [];
    foreach ($messages as $message) {
        $formattedMessages[] = [
            'id' => $message['id'],
            'sender_id' => $message['sender_id'],
            'receiver_id' => $message['receiver_id'],
            'subject' => $message['subject'],
            'message' => $message['message'],
            'is_read' => (bool)$message['is_read'],
            'sent_at' => $message['sent_at'],
            'sender_name' => $message['sender_name'],
            'sender_role' => $message['sender_role'],
            'receiver_name' => $message['receiver_name'],
            'receiver_role' => $message['receiver_role'],
            'is_own_message' => $message['sender_id'] == $userId
        ];
    }
    
    // Get unread count
    $unreadSql = "SELECT COUNT(*) as count FROM messages 
                  WHERE receiver_id = ? AND is_read = FALSE";
    $unreadCount = fetchRow($unreadSql, [$userId]);
    
    // Response data
    $response = [
        'success' => true,
        'messages' => array_reverse($formattedMessages), // Reverse to show oldest first
        'receiver' => [
            'id' => $receiver['id'],
            'name' => $receiver['name'],
            'role' => $receiver['role']
        ],
        'unread_count' => $unreadCount['count'],
        'total_messages' => count($formattedMessages)
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    error_log("Get messages error: " . $e->getMessage());
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 