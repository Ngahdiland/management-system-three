<?php
session_start();

// Function to set session data for a role
function setRoleSession($role) {
    $_SESSION['user_id'] = $role === 'admin' ? 1 : ($role === 'lecturer' ? 2 : 3);
    $_SESSION['name'] = $role === 'admin' ? 'Admin User' : ($role === 'lecturer' ? 'Prof. John Smith' : 'John Doe');
    $_SESSION['email'] = $role === 'admin' ? 'admin@lms.edu' : ($role === 'lecturer' ? 'john.smith@lms.edu' : 'john.doe@student.lms.edu');
    $_SESSION['role'] = $role;
    $_SESSION['department'] = $role === 'admin' ? 'Administration' : ($role === 'lecturer' ? 'Mathematics' : 'Computer Science');
    $_SESSION['student_id'] = $role === 'student' ? 'STU001' : null;
    $_SESSION['login_time'] = time();
}

// Handle role selection
if (isset($_POST['role'])) {
    $role = $_POST['role'];
    setRoleSession($role);
    
    // Redirect to appropriate dashboard
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
        header('Location: ' . $redirect_url);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS - Quick Access</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .header {
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2.5em;
        }
        .header p {
            color: #666;
            font-size: 1.1em;
        }
        .role-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }
        .role-btn {
            padding: 20px;
            border: none;
            border-radius: 10px;
            font-size: 1.2em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .role-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .admin-btn {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
        }
        .lecturer-btn {
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
            color: white;
        }
        .student-btn {
            background: linear-gradient(45deg, #45b7d1, #96c93d);
            color: white;
        }
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #007bff;
        }
        .info-box h3 {
            margin-top: 0;
            color: #333;
        }
        .info-box p {
            margin: 5px 0;
            color: #666;
        }
        .icon {
            font-size: 1.5em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 LMS Quick Access</h1>
            <p>Select your role to continue</p>
        </div>
        
        <form method="POST" class="role-buttons">
            <button type="submit" name="role" value="admin" class="role-btn admin-btn">
                <span class="icon">👨‍💼</span>
                Continue as Administrator
            </button>
            
            <button type="submit" name="role" value="lecturer" class="role-btn lecturer-btn">
                <span class="icon">👨‍🏫</span>
                Continue as Lecturer
            </button>
            
            <button type="submit" name="role" value="student" class="role-btn student-btn">
                <span class="icon">👨‍🎓</span>
                Continue as Student
            </button>
        </form>
        
        <div class="info-box">
            <h3>📋 Quick Access Mode</h3>
            <p><strong>Admin:</strong> Full system access, user management, settings</p>
            <p><strong>Lecturer:</strong> Course management, grading, announcements</p>
            <p><strong>Student:</strong> View courses, submit assignments, check grades</p>
            <p><em>This bypasses authentication for development purposes.</em></p>
        </div>
    </div>
</body>
</html> 