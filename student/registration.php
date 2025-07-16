<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

require_once '../db.php';

$page_title = 'Course Registration';
$success = '';
$error = '';

// Define available courses (should ideally come from DB)
$courseData = [
    'CS101' => ['name' => 'Introduction to Computer Science', 'credits' => 3],
    'MATH101' => ['name' => 'Calculus I', 'credits' => 4],
    'ENG101' => ['name' => 'English Composition', 'credits' => 3],
    'PHYS101' => ['name' => 'Physics I', 'credits' => 4],
    'CHEM101' => ['name' => 'General Chemistry', 'credits' => 4],
    'BIO101' => ['name' => 'Biology I', 'credits' => 4],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedCourses = $_POST['courses'] ?? [];
    $totalCredits = 0;
    foreach ($selectedCourses as $code) {
        if (isset($courseData[$code])) {
            $totalCredits += $courseData[$code]['credits'];
        }
    }
    if (empty($selectedCourses)) {
        $error = 'Please select at least one course.';
    } elseif ($totalCredits > 18) {
        $error = 'Course load exceeds maximum of 18 credits.';
    } else {
        $student_id = $_SESSION['user_id'];
        $semester = 'Fall 2024'; // You may want to fetch this from settings
        $academic_year = '2024-2025';
        $successCount = 0;
        foreach ($selectedCourses as $code) {
            // Check if already enrolled
            $course = fetchRow("SELECT id FROM courses WHERE course_code = ?", [$code]);
            if ($course) {
                $enrolled = fetchRow("SELECT id FROM enrollments WHERE student_id = ? AND course_id = ? AND semester = ? AND academic_year = ?", [$student_id, $course['id'], $semester, $academic_year]);
                if (!$enrolled) {
                    executeQuery("INSERT INTO enrollments (student_id, course_id, semester, academic_year) VALUES (?, ?, ?, ?)", [$student_id, $course['id'], $semester, $academic_year]);
                    $successCount++;
                }
            }
        }
        if ($successCount > 0) {
            $success = "Successfully registered for $successCount course(s).";
        } else {
            $error = 'You are already registered for the selected courses.';
        }
    }
}

ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Course Registration</h1>
            <p class="text-muted">Register for new courses for the semester.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Register for Courses</h5>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success"> <?php echo $success; ?> </div>
                    <?php elseif ($error): ?>
                        <div class="alert alert-danger"> <?php echo $error; ?> </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Select Courses</label>
                            <div class="row">
                                <?php foreach ($courseData as $code => $course): ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="courses[]" value="<?php echo $code; ?>" id="course_<?php echo $code; ?>">
                                            <label class="form-check-label" for="course_<?php echo $code; ?>">
                                                <?php echo $course['name']; ?> (<?php echo $code; ?>, <?php echo $course['credits']; ?> credits)
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$page_content = ob_get_clean();
include '../includes/layout.php';
?> 