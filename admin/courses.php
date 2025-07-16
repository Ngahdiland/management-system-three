<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

require_once '../db.php';

$page_title = 'Courses';
$success = '';
$error = '';

// Fetch lecturers for dropdown
$lecturers = fetchAll("SELECT id, name FROM users WHERE role = 'lecturer' AND status = 'active'", []);

// Handle Add Course
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $course_code = trim($_POST['course_code'] ?? '');
    $course_name = trim($_POST['course_name'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $lecturer_id = intval($_POST['lecturer_id'] ?? 0);
    $status = trim($_POST['status'] ?? 'active');
    if (!$course_code || !$course_name || !$department || !$lecturer_id) {
        $error = 'All fields are required.';
    } else {
        // Check for duplicate code
        $exists = fetchRow("SELECT id FROM courses WHERE course_code = ?", [$course_code]);
        if ($exists) {
            $error = 'A course with this code already exists.';
        } else {
            executeQuery("INSERT INTO courses (course_code, course_name, department, lecturer_id, status) VALUES (?, ?, ?, ?, ?)", [
                $course_code, $course_name, $department, $lecturer_id, $status
            ]);
            $success = 'Course added successfully!';
        }
    }
}

// Handle Edit Course
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = intval($_POST['id']);
    $course_code = trim($_POST['course_code'] ?? '');
    $course_name = trim($_POST['course_name'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $lecturer_id = intval($_POST['lecturer_id'] ?? 0);
    $status = trim($_POST['status'] ?? 'active');
    if (!$course_code || !$course_name || !$department || !$lecturer_id) {
        $error = 'All fields are required.';
    } else {
        executeQuery("UPDATE courses SET course_code = ?, course_name = ?, department = ?, lecturer_id = ?, status = ? WHERE id = ?", [
            $course_code, $course_name, $department, $lecturer_id, $status, $id
        ]);
        $success = 'Course updated successfully!';
    }
}

// Handle Delete Course
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['id']);
    executeQuery("DELETE FROM courses WHERE id = ?", [$id]);
    $success = 'Course deleted successfully!';
}

// Fetch all courses
$courses = fetchAll("SELECT c.*, u.name AS lecturer_name FROM courses c LEFT JOIN users u ON c.lecturer_id = u.id ORDER BY c.id ASC", []);

ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Courses</h1>
            <p class="text-muted">Manage all courses in the system.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>All Courses</h5>
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
                                    <th>Course ID</th>
                                    <th>Title</th>
                                    <th>Department</th>
                                    <th>Lecturer</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $c): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($c['course_code']); ?></td>
                                        <td><?php echo htmlspecialchars($c['course_name']); ?></td>
                                        <td><?php echo htmlspecialchars($c['department']); ?></td>
                                        <td><?php echo htmlspecialchars($c['lecturer_name']); ?></td>
                                        <td><span class="badge bg-<?php echo $c['status'] === 'active' ? 'success' : 'secondary'; ?>"><?php echo ucfirst($c['status']); ?></span></td>
                                        <td>
                                            <!-- Edit Form -->
                                            <form method="POST" style="display:inline-block; width:180px;">
                                                <input type="hidden" name="action" value="edit">
                                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                                <input type="text" name="course_code" value="<?php echo htmlspecialchars($c['course_code']); ?>" class="form-control form-control-sm mb-1" required>
                                                <input type="text" name="course_name" value="<?php echo htmlspecialchars($c['course_name']); ?>" class="form-control form-control-sm mb-1" required>
                                                <input type="text" name="department" value="<?php echo htmlspecialchars($c['department']); ?>" class="form-control form-control-sm mb-1" required>
                                                <select name="lecturer_id" class="form-select form-select-sm mb-1" required>
                                                    <option value="">Select Lecturer</option>
                                                    <?php foreach ($lecturers as $l): ?>
                                                        <option value="<?php echo $l['id']; ?>" <?php if ($c['lecturer_id'] == $l['id']) echo 'selected'; ?>><?php echo htmlspecialchars($l['name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <select name="status" class="form-select form-select-sm mb-1" required>
                                                    <option value="active" <?php if ($c['status'] === 'active') echo 'selected'; ?>>Active</option>
                                                    <option value="inactive" <?php if ($c['status'] === 'inactive') echo 'selected'; ?>>Inactive</option>
                                                    <option value="completed" <?php if ($c['status'] === 'completed') echo 'selected'; ?>>Completed</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                            </form>
                                            <!-- Delete Form -->
                                            <form method="POST" style="display:inline-block;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this course?');">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Add Course Form -->
                    <form method="POST" class="mt-4">
                        <input type="hidden" name="action" value="add">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-2">
                                <input type="text" name="course_code" class="form-control" placeholder="Course Code" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="course_name" class="form-control" placeholder="Title" required>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="department" class="form-control" placeholder="Department" required>
                            </div>
                            <div class="col-md-3">
                                <select name="lecturer_id" class="form-select" required>
                                    <option value="">Select Lecturer</option>
                                    <?php foreach ($lecturers as $l): ?>
                                        <option value="<?php echo $l['id']; ?>"><?php echo htmlspecialchars($l['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <select name="status" class="form-select" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-success">Add</button>
                            </div>
                        </div>
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