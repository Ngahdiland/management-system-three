<?php
// Database configuration
$host = 'localhost';
$dbname = 'lms_db';
$username = 'root';
$password = '';
$charset = 'utf8mb4';

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    // echo "Database connection successful!";
} catch (PDOException $e) {
    // Log error and display user-friendly message
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

// Function to get database connection
function getDB() {
    global $pdo;
    return $pdo;
}

// Function to execute prepared statements safely
function executeQuery($sql, $params = []) {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log("Query execution failed: " . $e->getMessage());
        throw new Exception("Database operation failed");
    }
}

// Function to fetch single row
function fetchRow($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetch();
}

// Function to fetch all rows
function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetchAll();
}

// Function to get last insert ID
function getLastInsertId() {
    $pdo = getDB();
    return $pdo->lastInsertId();
}

// Function to begin transaction
function beginTransaction() {
    $pdo = getDB();
    return $pdo->beginTransaction();
}

// Function to commit transaction
function commitTransaction() {
    $pdo = getDB();
    return $pdo->commit();
}

// Function to rollback transaction
function rollbackTransaction() {
    $pdo = getDB();
    return $pdo->rollback();
}
?> 