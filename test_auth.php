<?php
session_start();
require_once 'db.php';

echo "<h2>LMS Authentication Debug</h2>";

// Test 1: Database Connection
echo "<h3>1. Database Connection Test</h3>";
try {
    $pdo = getDB();
    echo "✅ Database connection successful<br>";
    
    // Test if tables exist
    $tables = ['users', 'courses', 'enrollments'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Table '$table' exists<br>";
        } else {
            echo "❌ Table '$table' does not exist<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
    exit;
}

// Test 2: Check if users exist
echo "<h3>2. User Data Test</h3>";
try {
    $users = fetchAll("SELECT id, name, email, role, status FROM users");
    echo "Found " . count($users) . " users in database:<br>";
    
    if (count($users) > 0) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['name'] . "</td>";
            echo "<td>" . $user['email'] . "</td>";
            echo "<td>" . $user['role'] . "</td>";
            echo "<td>" . $user['status'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ No users found in database<br>";
    }
} catch (Exception $e) {
    echo "❌ Error fetching users: " . $e->getMessage() . "<br>";
}

// Test 3: Password Verification Test
echo "<h3>3. Password Verification Test</h3>";
$test_password = 'password'; // This is the password used in the sample data
$test_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

if (password_verify($test_password, $test_hash)) {
    echo "✅ Password verification working correctly<br>";
} else {
    echo "❌ Password verification failed<br>";
}

// Test 4: Authentication Logic Test
echo "<h3>4. Authentication Logic Test</h3>";
$test_email = 'admin@lms.edu';
$test_role = 'admin';

try {
    $sql = "SELECT id, name, email, password, role, department, student_id, status FROM users WHERE email = ? AND role = ? AND status = 'active'";
    $user = fetchRow($sql, [$test_email, $test_role]);
    
    if ($user) {
        echo "✅ User found: " . $user['name'] . " (" . $user['email'] . ")<br>";
        
        if (password_verify($test_password, $user['password'])) {
            echo "✅ Password verification successful<br>";
            
            // Simulate session creation
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['department'] = $user['department'];
            $_SESSION['student_id'] = $user['student_id'];
            $_SESSION['login_time'] = time();
            
            echo "✅ Session variables set:<br>";
            echo "<ul>";
            foreach ($_SESSION as $key => $value) {
                echo "<li>$key: $value</li>";
            }
            echo "</ul>";
            
            echo "<h4>Test Credentials:</h4>";
            echo "<strong>Email:</strong> admin@lms.edu<br>";
            echo "<strong>Password:</strong> password<br>";
            echo "<strong>Role:</strong> admin<br>";
            
        } else {
            echo "❌ Password verification failed for user<br>";
        }
    } else {
        echo "❌ User not found with email: $test_email and role: $test_role<br>";
    }
} catch (Exception $e) {
    echo "❌ Error in authentication logic: " . $e->getMessage() . "<br>";
}

// Test 5: File Path Test
echo "<h3>5. File Path Test</h3>";
$dashboard_paths = [
    'admin/dashboard.php',
    'lecturer/dashboard.php', 
    'student/dashboard.php'
];

foreach ($dashboard_paths as $path) {
    if (file_exists($path)) {
        echo "✅ $path exists<br>";
    } else {
        echo "❌ $path does not exist<br>";
    }
}

// Test 6: Session Test
echo "<h3>6. Session Test</h3>";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "✅ Sessions are working<br>";
    echo "Session ID: " . session_id() . "<br>";
} else {
    echo "❌ Sessions are not working<br>";
}

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>If database connection fails, make sure MySQL is running and the database 'lms_db' exists</li>";
echo "<li>If no users found, run the database.sql script to create tables and sample data</li>";
echo "<li>If password verification fails, check if the password hash is correct</li>";
echo "<li>If dashboard files don't exist, check the file structure</li>";
echo "</ol>";

echo "<p><strong>To create the database and sample data, run:</strong></p>";
echo "<code>mysql -u root -p < database.sql</code>";

echo "<p><strong>Or if you're using XAMPP/WAMP, import database.sql through phpMyAdmin</strong></p>";
?> 