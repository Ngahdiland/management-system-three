-- Learning Management System Database Schema

-- Create database
CREATE DATABASE IF NOT EXISTS lms_db;
USE lms_db;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'lecturer', 'admin') NOT NULL,
    department VARCHAR(100),
    student_id VARCHAR(20) UNIQUE,
    phone VARCHAR(20),
    address TEXT,
    profile_image VARCHAR(255),
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Courses table
CREATE TABLE courses (
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
);

-- Enrollments table
CREATE TABLE enrollments (
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
);

-- Grades table
CREATE TABLE grades (
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
);

-- Homework table
CREATE TABLE homework (
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
);

-- Submissions table
CREATE TABLE submissions (
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
);

-- Messages table
CREATE TABLE messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- News table
CREATE TABLE news (
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
);

-- Attendance table
CREATE TABLE attendance (
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
);

-- Settings table
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, description) VALUES
('current_semester', 'Fall 2024', 'Current academic semester'),
('current_academic_year', '2024-2025', 'Current academic year'),
('max_course_load', '18', 'Maximum credits a student can take per semester'),
('grading_scale_a', '90', 'Minimum percentage for A grade'),
('grading_scale_b', '80', 'Minimum percentage for B grade'),
('grading_scale_c', '70', 'Minimum percentage for C grade'),
('grading_scale_d', '60', 'Minimum percentage for D grade'),
('system_name', 'Learning Management System', 'Name of the LMS system'),
('system_email', 'admin@lms.edu', 'System email address'),
('file_upload_limit', '10485760', 'Maximum file upload size in bytes (10MB)');

-- Insert sample admin user
INSERT INTO users (name, email, password, role, department) VALUES
('Admin User', 'admin@lms.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Administration');

-- Insert sample lecturer
INSERT INTO users (name, email, password, role, department) VALUES
('Prof. John Smith', 'john.smith@lms.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lecturer', 'Mathematics');

-- Insert sample student
INSERT INTO users (name, email, password, role, department, student_id) VALUES
('John Doe', 'john.doe@student.lms.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'Computer Science', 'STU001');

-- Insert sample courses
INSERT INTO courses (course_code, course_name, description, credits, department, lecturer_id, semester, academic_year) VALUES
('CS101', 'Introduction to Computer Science', 'Basic concepts of computer science and programming', 3, 'Computer Science', 2, 'Fall 2024', '2024-2025'),
('MATH101', 'Calculus I', 'Introduction to differential and integral calculus', 4, 'Mathematics', 2, 'Fall 2024', '2024-2025'),
('ENG101', 'English Composition', 'College-level writing and composition', 3, 'English', 2, 'Fall 2024', '2024-2025');

-- Insert sample enrollment
INSERT INTO enrollments (student_id, course_id, semester, academic_year) VALUES
(3, 1, 'Fall 2024', '2024-2025'),
(3, 2, 'Fall 2024', '2024-2025'),
(3, 3, 'Fall 2024', '2024-2025');

-- Insert sample homework
INSERT INTO homework (course_id, title, description, due_date, max_points, created_by) VALUES
(1, 'Programming Assignment 1', 'Create a simple calculator program', '2024-12-15 23:59:00', 100, 2),
(2, 'Calculus Problem Set 3', 'Derivatives and Applications', '2024-12-15 23:59:00', 100, 2),
(3, 'Essay Assignment', 'Literary Analysis Essay', '2024-12-20 23:59:00', 100, 2);

-- Insert sample news
INSERT INTO news (title, content, target_role, author_id) VALUES
('Academic Calendar Updated', 'The academic calendar for the 2024-2025 academic year has been updated with important dates and deadlines.', 'all', 1),
('Course Registration Opens', 'Course registration for the Spring 2025 semester will open on December 1st, 2024.', 'students', 1),
('Faculty Development Workshop', 'A workshop on "Modern Teaching Methods" will be held on November 15th.', 'lecturers', 1);

-- Create indexes for better performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_courses_code ON courses(course_code);
CREATE INDEX idx_enrollments_student ON enrollments(student_id);
CREATE INDEX idx_enrollments_course ON enrollments(course_id);
CREATE INDEX idx_grades_student ON grades(student_id);
CREATE INDEX idx_grades_course ON grades(course_id);
CREATE INDEX idx_messages_sender ON messages(sender_id);
CREATE INDEX idx_messages_receiver ON messages(receiver_id);
CREATE INDEX idx_attendance_course_date ON attendance(course_id, date);
CREATE INDEX idx_attendance_student ON attendance(student_id); 