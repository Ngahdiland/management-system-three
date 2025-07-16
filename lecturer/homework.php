<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lecturer') {
    header('Location: ../login.php');
    exit();
}

require_once '../db.php';

$page_title = 'Post Homework';
$lecturer_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Fetch lecturer's courses
$courses = fetchAll("SELECT id, course_name FROM courses WHERE lecturer_id = ? AND status = 'active'", [$lecturer_id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = intval($_POST['course_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $due_date = trim($_POST['due_date'] ?? '');
    $file_path = null;
    $file_name = null;
    if (!$course_id || !$title || !$due_date) {
        $error = 'Please fill in all required fields.';
    } elseif (!strtotime($due_date)) {
        $error = 'Invalid due date.';
    } else {
        // Handle file upload
        if (!empty($_FILES['attachment']['name'])) {
            $file = $_FILES['attachment'];
            $allowed = ['pdf', 'doc', 'docx', 'zip', 'txt'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = 'Invalid file type. Allowed: pdf, doc, docx, zip, txt.';
            } elseif ($file['size'] > 10 * 1024 * 1024) {
                $error = 'File size exceeds 10MB limit.';
            } else {
                $upload_dir = '../uploads/homework/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $filename = uniqid('hw_attach_') . '_' . basename($file['name']);
                $filepath = $upload_dir . $filename;
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    $file_path = $filepath;
                    $file_name = $file['name'];
                } else {
                    $error = 'Failed to upload attachment.';
                }
            }
        }
        if (!$error) {
            executeQuery(
                "INSERT INTO homework (course_id, title, description, due_date, file_attachment, created_by) VALUES (?, ?, ?, ?, ?, ?)",
                [$course_id, $title, $description, $due_date, $file_path, $lecturer_id]
            );
            $success = 'Homework posted successfully!';
        }
    }
}

ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Post Homework</h1>
            <p class="text-muted">Create and assign homework to your courses.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>New Homework Assignment</h5>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success"> <?php echo $success; ?> </div>
                    <?php elseif ($error): ?>
                        <div class="alert alert-danger"> <?php echo $error; ?> </div>
                    <?php endif; ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="course_id" class="form-label">Course</label>
                            <select class="form-select" id="course_id" name="course_id" required>
                                <option value="">Select Course</option>
                                <?php foreach ($courses as $c): ?>
                                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['course_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="datetime-local" class="form-control" id="due_date" name="due_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Attachment (optional)</label>
                            <input class="form-control" type="file" id="attachment" name="attachment">
                        </div>
                        <button type="submit" class="btn btn-primary">Post Homework</button>
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