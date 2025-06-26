<?php
session_start();
require_once 'db.php';

// Clear any existing session data
session_destroy();
session_start();

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = trim($_POST['role'] ?? '');
    
    // Simple validation
    if (empty($email) || empty($password) || empty($role)) {
        $message = 'All fields are required';
        $message_type = 'error';
    } else {
        try {
            // Get user from database
            $sql = "SELECT * FROM users WHERE email = ? AND role = ? AND status = 'active'";
            $user = fetchRow($sql, [$email, $role]);
            
            if ($user && password_verify($password, $user['password'])) {
                // Success - create session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['department'] = $user['department'];
                $_SESSION['student_id'] = $user['student_id'];
                $_SESSION['login_time'] = time();
                
                $message = "Login successful! Redirecting...";
                $message_type = 'success';
                
                // Redirect after a short delay
                $redirect_url = '';
                switch ($role) {
                    case 'student':
                        $redirect_url = 'student/dashboard.php';
                        break;
                    case 'lecturer':
                        $redirect_url = 'lecturer/dashboard.php';
                        break;
                    case 'admin':
                        $redirect_url = 'admin/dashboard.php';
                        break;
                }
                
                if ($redirect_url) {
                    echo "<script>setTimeout(function() { window.location.href = '$redirect_url'; }, 2000);</script>";
                }
                
            } else {
                $message = 'Invalid email, password, or role';
                $message_type = 'error';
            }
            
        } catch (Exception $e) {
            $message = 'Database error: ' . $e->getMessage();
            $message_type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS - Working Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .credentials {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LMS Login</h1>
            <p>Working Login Page</p>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="admin" <?php echo ($_POST['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrator</option>
                    <option value="lecturer" <?php echo ($_POST['role'] ?? '') === 'lecturer' ? 'selected' : ''; ?>>Lecturer</option>
                    <option value="student" <?php echo ($_POST['role'] ?? '') === 'student' ? 'selected' : ''; ?>>Student</option>
                </select>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="credentials">
            <h4>Test Credentials:</h4>
            <p><strong>Admin:</strong><br>
            Email: admin@lms.edu<br>
            Password: password<br>
            Role: Administrator</p>
            
            <p><strong>Lecturer:</strong><br>
            Email: john.smith@lms.edu<br>
            Password: password<br>
            Role: Lecturer</p>
            
            <p><strong>Student:</strong><br>
            Email: john.doe@student.lms.edu<br>
            Password: password<br>
            Role: Student</p>
        </div>
    </div>
</body>
</html> 