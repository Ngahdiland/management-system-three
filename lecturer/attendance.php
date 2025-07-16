<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lecturer') {
    header('Location: ../login.php');
    exit();
}

require_once '../db.php';

$page_title = 'Attendance';
$lecturer_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Get today's date (or allow selection)
$date = $_GET['date'] ?? date('Y-m-d');

// Handle attendance marking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'], $_POST['course_id'], $_POST['status'])) {
    $student_id = intval($_POST['student_id']);
    $course_id = intval($_POST['course_id']);
    $status = $_POST['status'];
    $valid_status = ['present', 'absent', 'late', 'excused'];
    if (!in_array($status, $valid_status)) {
        $error = 'Invalid status.';
    } else {
        // Check if already marked
        $existing = fetchRow("SELECT id FROM attendance WHERE course_id = ? AND student_id = ? AND date = ?", [$course_id, $student_id, $date]);
        if ($existing) {
            executeQuery("UPDATE attendance SET status = ?, marked_by = ?, marked_at = NOW() WHERE id = ?", [$status, $lecturer_id, $existing['id']]);
            $success = 'Attendance updated.';
        } else {
            executeQuery("INSERT INTO attendance (course_id, student_id, date, status, marked_by) VALUES (?, ?, ?, ?, ?)", [$course_id, $student_id, $date, $status, $lecturer_id]);
            $success = 'Attendance marked.';
        }
    }
}

// Fetch students enrolled in lecturer's courses
$students = fetchAll(
    "SELECT u.id AS student_id, u.name AS student_name, c.id AS course_id, c.course_name,
        (SELECT status FROM attendance a WHERE a.course_id = c.id AND a.student_id = u.id AND a.date = ?) AS attendance_status
     FROM courses c
     JOIN enrollments e ON e.course_id = c.id
     JOIN users u ON e.student_id = u.id
     WHERE c.lecturer_id = ?
     ORDER BY c.course_name, u.name",
    [$date, $lecturer_id]
);

ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Attendance</h1>
            <p class="text-muted">Mark and review student attendance for your courses.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Mark Attendance (<?php echo htmlspecialchars($date); ?>)</h5>
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
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $s): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($s['student_name']); ?></td>
                                        <td><?php echo htmlspecialchars($s['course_name']); ?></td>
                                        <td>
                                            <?php
                                            $status = $s['attendance_status'] ?? 'Not Marked';
                                            $badge = [
                                                'present' => 'success',
                                                'absent' => 'danger',
                                                'late' => 'warning',
                                                'excused' => 'info',
                                                'Not Marked' => 'secondary'
                                            ];
                                            ?>
                                            <span class="badge bg-<?php echo $badge[$status] ?? 'secondary'; ?>">
                                                <?php echo ucfirst($status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form method="POST" style="display:inline-block; min-width:200px;">
                                                <input type="hidden" name="student_id" value="<?php echo $s['student_id']; ?>">
                                                <input type="hidden" name="course_id" value="<?php echo $s['course_id']; ?>">
                                                <select name="status" class="form-select form-select-sm d-inline w-auto" required>
                                                    <option value="present">Present</option>
                                                    <option value="absent">Absent</option>
                                                    <option value="late">Late</option>
                                                    <option value="excused">Excused</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">Mark</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($students)): ?>
                                    <tr><td colspan="4" class="text-center">No students found for your courses.</td></tr>
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