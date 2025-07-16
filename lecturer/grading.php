<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lecturer') {
    header('Location: ../login.php');
    exit();
}

require_once '../db.php';

$page_title = 'Grading';
$lecturer_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle grade submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submission_id'])) {
    $submission_id = intval($_POST['submission_id']);
    $grade_points = floatval($_POST['grade_points']);
    $grade_letter = trim($_POST['grade_letter']);
    $feedback = trim($_POST['feedback']);
    // Get submission info
    $submission = fetchRow("SELECT * FROM submissions WHERE id = ?", [$submission_id]);
    if ($submission) {
        // Get homework info
        $homework = fetchRow("SELECT * FROM homework WHERE id = ?", [$submission['homework_id']]);
        if ($homework) {
            // Insert grade
            executeQuery("INSERT INTO grades (student_id, course_id, assignment_name, assignment_type, grade_points, grade_letter, comments, graded_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [
                $submission['student_id'], $homework['course_id'], $homework['title'], 'homework', $grade_points, $grade_letter, $feedback, $lecturer_id
            ]);
            // Update submission status
            executeQuery("UPDATE submissions SET status = 'graded', grade_points = ?, grade_letter = ?, feedback = ?, graded_by = ?, graded_at = NOW() WHERE id = ?", [
                $grade_points, $grade_letter, $feedback, $lecturer_id, $submission_id
            ]);
            $success = 'Grade submitted successfully!';
        } else {
            $error = 'Homework not found.';
        }
    } else {
        $error = 'Submission not found.';
    }
}

// Fetch pending submissions for lecturer's courses
$pending = fetchAll(
    "SELECT s.id AS submission_id, u.name AS student_name, c.course_name, h.title AS assignment, s.submission_date, s.file_name, s.file_path
     FROM submissions s
     JOIN homework h ON s.homework_id = h.id
     JOIN courses c ON h.course_id = c.id
     JOIN users u ON s.student_id = u.id
     WHERE c.lecturer_id = ? AND s.status = 'submitted'
     ORDER BY s.submission_date DESC",
    [$lecturer_id]
);

ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Grading</h1>
            <p class="text-muted">Grade student assignments and exams.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Pending Grades</h5>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success"> <?php echo $success; ?> </div>
                    <?php elseif ($error): ?>
                        <div class="alert alert-danger"> <?php echo $error; ?> </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Assignment</th>
                                    <th>Submission Date</th>
                                    <th>File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pending as $p): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($p['student_name']); ?></td>
                                        <td><?php echo htmlspecialchars($p['course_name']); ?></td>
                                        <td><?php echo htmlspecialchars($p['assignment']); ?></td>
                                        <td><?php echo htmlspecialchars($p['submission_date']); ?></td>
                                        <td><a href="<?php echo str_replace('..', '..', $p['file_path']); ?>" target="_blank"><?php echo htmlspecialchars($p['file_name']); ?></a></td>
                                        <td>
                                            <form method="POST" style="min-width:200px;">
                                                <input type="hidden" name="submission_id" value="<?php echo $p['submission_id']; ?>">
                                                <div class="input-group input-group-sm mb-1">
                                                    <input type="number" step="0.01" min="0" max="100" name="grade_points" class="form-control" placeholder="Points" required>
                                                    <input type="text" name="grade_letter" class="form-control" placeholder="Letter" maxlength="2" required>
                                                </div>
                                                <input type="text" name="feedback" class="form-control form-control-sm mb-1" placeholder="Feedback (optional)">
                                                <button type="submit" class="btn btn-sm btn-primary">Submit Grade</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($pending)): ?>
                                    <tr><td colspan="6" class="text-center">No pending submissions to grade.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$page_content = ob_get_clean();
include '../includes/layout.php';
?> 