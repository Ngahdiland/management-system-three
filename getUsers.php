<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$current_id = $_SESSION['user_id'];
$sql = "SELECT id, name, role FROM users WHERE id != ? AND status = 'active' ORDER BY role, name";
$users = fetchAll($sql, [$current_id]);
echo json_encode($users); 