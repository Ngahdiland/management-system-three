<?php
// Database setup script for LMS
echo "<h2>LMS Database Setup</h2>";

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$charset = 'utf8mb4';

try {
    // Connect without database first
    $dsn = "mysql:host=$host;charset=$charset";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "✅ Connected to MySQL server<br>";
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS lms_db");
    echo "✅ Database 'lms_db' created/verified<br>";
    
    // Connect to the specific database
    $pdo->exec("USE lms_db");
    echo "✅ Connected to lms_db database<br>";
    
    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('student', 'lecturer', 'admin') NOT NULL,
            department VARCHAR(100),
            student_id VARCHAR(20),
            phone VARCHAR(20),
            address TEXT,
            status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "✅ Users table created/verified<br>";
    
    // Create courses table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS courses (
            id INT PRIMARY KEY AUTO_INCREMENT,
            course_code VARCHAR(20) UNIQUE NOT NULL,
            course_name VARCHAR(200) NOT NULL,
            description TEXT,
            credits INT NOT NULL,
            department VARCHAR(100),
            lecturer_id INT,
            semester VARCHAR(20),
            academic_year VARCHAR(20),
            max_students INT DEFAULT 50,
            status ENUM('active', 'inactive', 'completed') DEFAULT 'active',
            schedule TEXT,
            room VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (lecturer_id) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "✅ Courses table created/verified<br>";
    
    // Create enrollments table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS enrollments (
            id INT PRIMARY KEY AUTO_INCREMENT,
            student_id INT NOT NULL,
            course_id INT NOT NULL,
            semester VARCHAR(20) NOT NULL,
            academic_year VARCHAR(20) NOT NULL,
            enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status ENUM('enrolled', 'dropped', 'completed') DEFAULT 'enrolled',
            grade_letter VARCHAR(2),
            grade_points DECIMAL(3,2),
            FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
            UNIQUE KEY unique_enrollment (student_id, course_id, semester, academic_year)
        )
    ");
    echo "✅ Enrollments table created/verified<br>";
    
    // Create other essential tables
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS grades (
            id INT PRIMARY KEY AUTO_INCREMENT,
            student_id INT NOT NULL,
            course_id INT NOT NULL,
            assignment_name VARCHAR(200) NOT NULL,
            assignment_type ENUM('homework', 'quiz', 'exam', 'project', 'participation') NOT NULL,
            grade_points DECIMAL(5,2),
            grade_letter VARCHAR(2),
            max_points DECIMAL(5,2) DEFAULT 100,
            weight DECIMAL(3,2) DEFAULT 1.00,
            comments TEXT,
            graded_by INT,
            graded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
            FOREIGN KEY (graded_by) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "✅ Grades table created/verified<br>";
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS homework (
            id INT PRIMARY KEY AUTO_INCREMENT,
            course_id INT NOT NULL,
            title VARCHAR(200) NOT NULL,
            description TEXT,
            due_date DATETIME NOT NULL,
            max_points DECIMAL(5,2) DEFAULT 100,
            weight DECIMAL(3,2) DEFAULT 1.00,
            file_attachment VARCHAR(255),
            created_by INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "✅ Homework table created/verified<br>";
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS submissions (
            id INT PRIMARY KEY AUTO_INCREMENT,
            homework_id INT NOT NULL,
            student_id INT NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            file_name VARCHAR(255) NOT NULL,
            file_size INT,
            submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status ENUM('submitted', 'late', 'graded') DEFAULT 'submitted',
            grade_points DECIMAL(5,2),
            grade_letter VARCHAR(2),
            feedback TEXT,
            graded_by INT,
            graded_at TIMESTAMP NULL,
            FOREIGN KEY (homework_id) REFERENCES homework(id) ON DELETE CASCADE,
            FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (graded_by) REFERENCES users(id) ON DELETE SET NULL,
            UNIQUE KEY unique_submission (homework_id, student_id)
        )
    ");
    echo "✅ Submissions table created/verified<br>";
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS messages (
            id INT PRIMARY KEY AUTO_INCREMENT,
            sender_id INT NOT NULL,
            receiver_id INT NOT NULL,
            subject VARCHAR(200),
            message TEXT NOT NULL,
            is_read BOOLEAN DEFAULT FALSE,
            sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "✅ Messages table created/verified<br>";
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS news (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(200) NOT NULL,
            content TEXT NOT NULL,
            target_role ENUM('all', 'students', 'lecturers', 'admin') DEFAULT 'all',
            author_id INT NOT NULL,
            is_published BOOLEAN DEFAULT TRUE,
            published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "✅ News table created/verified<br>";
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS attendance (
            id INT PRIMARY KEY AUTO_INCREMENT,
            course_id INT NOT NULL,
            student_id INT NOT NULL,
            date DATE NOT NULL,
            status ENUM('present', 'absent', 'late', 'excused') DEFAULT 'present',
            marked_by INT NOT NULL,
            marked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            notes TEXT,
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
            FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (marked_by) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE KEY unique_attendance (course_id, student_id, date)
        )
    ");
    echo "✅ Attendance table created/verified<br>";
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS settings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT,
            description TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "✅ Settings table created/verified<br>";
    
    // Check if sample data already exists
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $userCount = $stmt->fetch()['count'];
    
    if ($userCount == 0) {
        echo "<h3>Inserting Sample Data...</h3>";
        
        // Insert sample admin user
        $adminPassword = password_hash('password', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO users (name, email, password, role, department) VALUES ('Admin User', 'admin@lms.edu', '$adminPassword', 'admin', 'Administration')");
        echo "✅ Admin user created<br>";
        
        // Insert sample lecturer
        $pdo->exec("INSERT INTO users (name, email, password, role, department) VALUES ('Prof. John Smith', 'john.smith@lms.edu', '$adminPassword', 'lecturer', 'Mathematics')");
        echo "✅ Lecturer user created<br>";
        
        // Insert sample student
        $pdo->exec("INSERT INTO users (name, email, password, role, department, student_id) VALUES ('John Doe', 'john.doe@student.lms.edu', '$adminPassword', 'student', 'Computer Science', 'STU001')");
        echo "✅ Student user created<br>";
        
        // Insert sample courses
        $pdo->exec("INSERT INTO courses (course_code, course_name, description, credits, department, lecturer_id, semester, academic_year) VALUES ('CS101', 'Introduction to Computer Science', 'Basic concepts of computer science and programming', 3, 'Computer Science', 2, 'Fall 2024', '2024-2025')");
        $pdo->exec("INSERT INTO courses (course_code, course_name, description, credits, department, lecturer_id, semester, academic_year) VALUES ('MATH101', 'Calculus I', 'Introduction to differential and integral calculus', 4, 'Mathematics', 2, 'Fall 2024', '2024-2025')");
        $pdo->exec("INSERT INTO courses (course_code, course_name, description, credits, department, lecturer_id, semester, academic_year) VALUES ('ENG101', 'English Composition', 'College-level writing and composition', 3, 'English', 2, 'Fall 2024', '2024-2025')");
        echo "✅ Sample courses created<br>";
        
        // Insert sample enrollments
        $pdo->exec("INSERT INTO enrollments (student_id, course_id, semester, academic_year) VALUES (3, 1, 'Fall 2024', '2024-2025')");
        $pdo->exec("INSERT INTO enrollments (student_id, course_id, semester, academic_year) VALUES (3, 2, 'Fall 2024', '2024-2025')");
        $pdo->exec("INSERT INTO enrollments (student_id, course_id, semester, academic_year) VALUES (3, 3, 'Fall 2024', '2024-2025')");
        echo "✅ Sample enrollments created<br>";
        
        // Insert sample homework
        $pdo->exec("INSERT INTO homework (course_id, title, description, due_date, max_points, created_by) VALUES (1, 'Programming Assignment 1', 'Create a simple calculator program', '2024-12-15 23:59:00', 100, 2)");
        $pdo->exec("INSERT INTO homework (course_id, title, description, due_date, max_points, created_by) VALUES (2, 'Calculus Problem Set 3', 'Derivatives and Applications', '2024-12-15 23:59:00', 100, 2)");
        $pdo->exec("INSERT INTO homework (course_id, title, description, due_date, max_points, created_by) VALUES (3, 'Essay Assignment', 'Literary Analysis Essay', '2024-12-20 23:59:00', 100, 2)");
        echo "✅ Sample homework created<br>";
        
        // Insert sample news
        $pdo->exec("INSERT INTO news (title, content, target_role, author_id) VALUES ('Academic Calendar Updated', 'The academic calendar for the 2024-2025 academic year has been updated with important dates and deadlines.', 'all', 1)");
        $pdo->exec("INSERT INTO news (title, content, target_role, author_id) VALUES ('Course Registration Opens', 'Course registration for the Spring 2025 semester will open on December 1st, 2024.', 'students', 1)");
        $pdo->exec("INSERT INTO news (title, content, target_role, author_id) VALUES ('Faculty Development Workshop', 'A workshop on \"Modern Teaching Methods\" will be held on November 15th.', 'lecturers', 1)");
        echo "✅ Sample news created<br>";
        
        // Insert default settings
        $pdo->exec("INSERT INTO settings (setting_key, setting_value, description) VALUES ('current_semester', 'Fall 2024', 'Current academic semester')");
        $pdo->exec("INSERT INTO settings (setting_key, setting_value, description) VALUES ('current_academic_year', '2024-2025', 'Current academic year')");
        $pdo->exec("INSERT INTO settings (setting_key, setting_value, description) VALUES ('system_name', 'Learning Management System', 'Name of the LMS system')");
        echo "✅ Default settings created<br>";
        
    } else {
        echo "✅ Sample data already exists ($userCount users found)<br>";
    }
    
    echo "<hr>";
    echo "<h3>✅ Database Setup Complete!</h3>";
    echo "<p><strong>Test Credentials:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> admin@lms.edu / password</li>";
    echo "<li><strong>Lecturer:</strong> john.smith@lms.edu / password</li>";
    echo "<li><strong>Student:</strong> john.doe@student.lms.edu / password</li>";
    echo "</ul>";
    echo "<p><a href='login.php' class='btn btn-primary'>Go to Login Page</a></p>";
    
} catch (PDOException $e) {
    echo "❌ Database setup failed: " . $e->getMessage() . "<br>";
    echo "<p>Make sure:</p>";
    echo "<ul>";
    echo "<li>MySQL server is running</li>";
    echo "<li>Username and password are correct</li>";
    echo "<li>You have permission to create databases</li>";
    echo "</ul>";
}
?> 