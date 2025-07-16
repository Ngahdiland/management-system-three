<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

require_once '../db.php';

$page_title = 'Homework';
$student_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['homework_id'])) {
    $homework_id = intval($_POST['homework_id']);
    if (!isset($_FILES['assignment_file']) || $_FILES['assignment_file']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please select a file to upload.';
    } else {
        $file = $_FILES['assignment_file'];
        $allowed = ['pdf', 'doc', 'docx', 'zip', 'txt'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $error = 'Invalid file type. Allowed: pdf, doc, docx, zip, txt.';
        } elseif ($file['size'] > 10 * 1024 * 1024) {
            $error = 'File size exceeds 10MB limit.';
        } else {
            // Check if already submitted
            $existing = fetchRow("SELECT id FROM submissions WHERE homework_id = ? AND student_id = ?", [$homework_id, $student_id]);
            if ($existing) {
                $error = 'You have already submitted this assignment.';
            } else {
                $upload_dir = '../uploads/homework/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $filename = uniqid('hw_') . '_' . basename($file['name']);
                $filepath = $upload_dir . $filename;
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    executeQuery("INSERT INTO submissions (homework_id, student_id, file_path, file_name, file_size, status) VALUES (?, ?, ?, ?, ?, 'submitted')", [
                        $homework_id, $student_id, $filepath, $file['name'], $file['size']
                    ]);
                    $success = 'Assignment uploaded successfully!';
                } else {
                    $error = 'Failed to upload file.';
                }
            }
        }
    }
}

// Fetch homework assignments for student's courses
$assignments = fetchAll(
    "SELECT h.id, h.title, h.due_date, c.course_name,
        (SELECT id FROM submissions s WHERE s.homework_id = h.id AND s.student_id = ?) AS submitted
     FROM homework h
     JOIN courses c ON h.course_id = c.id
     JOIN enrollments e ON e.course_id = c.id
     WHERE e.student_id = ?
     ORDER BY h.due_date DESC",
    [$student_id, $student_id]
);

ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Homework</h1>
            <p class="text-muted">View and upload your homework assignments.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Assignments</h5>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success"> <?php echo $success; ?> </div>
                    <?php elseif ($error): ?>
                        <div class="alert alert-danger"> <?php echo $error; ?> </div>
                    <?php endif; ?>
                    <div class="table-responsive mb-3">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Assignment</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignments as $a): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($a['course_name']); ?></td>
                                        <td><?php echo htmlspecialchars($a['title']); ?></td>
                                        <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($a['due_date']))); ?></td>
                                        <td>
                                            <?php if ($a['submitted']): ?>
                                                <span class="badge bg-success">Submitted</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!$a['submitted']): ?>
                                                <form method="POST" enctype="multipart/form-data" style="display:inline-block; min-width:180px;">
                                                    <input type="hidden" name="homework_id" value="<?php echo $a['id']; ?>">
                                                    <input type="file" name="assignment_file" required class="form-control form-control-sm mb-1">
                                                    <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                                                </form>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-secondary" disabled>Uploaded</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($assignments)): ?>
                                    <tr><td colspan="5" class="text-center">No assignments found.</td></tr>
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