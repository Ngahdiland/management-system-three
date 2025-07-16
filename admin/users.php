<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

require_once '../db.php';

$page_title = 'User Management';
$success = '';
$error = '';

// Handle Add User
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $status = trim($_POST['status'] ?? 'active');
    $password = $_POST['password'] ?? '';
    if (!$name || !$email || !$role || !$password) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        // Check for duplicate email
        $exists = fetchRow("SELECT id FROM users WHERE email = ?", [$email]);
        if ($exists) {
            $error = 'A user with this email already exists.';
        } else {
            executeQuery("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)", [
                $name, $email, $password, $role, $status
            ]);
            $success = 'User added successfully!';
        }
    }
}

// Handle Edit User
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = intval($_POST['id']);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $status = trim($_POST['status'] ?? 'active');
    if (!$name || !$email || !$role) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        executeQuery("UPDATE users SET name = ?, email = ?, role = ?, status = ? WHERE id = ?", [
            $name, $email, $role, $status, $id
        ]);
        $success = 'User updated successfully!';
    }
}

// Handle Delete User
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['id']);
    executeQuery("DELETE FROM users WHERE id = ?", [$id]);
    $success = 'User deleted successfully!';
}

// Fetch all users
$users = fetchAll("SELECT * FROM users ORDER BY id ASC", []);

ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">User Management</h1>
            <p class="text-muted">View, add, edit, or remove users from the system.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>All Users</h5>
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
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $u): ?>
                                    <tr>
                                        <td><?php echo $u['id']; ?></td>
                                        <td><?php echo htmlspecialchars($u['name']); ?></td>
                                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                                        <td><?php echo ucfirst($u['role']); ?></td>
                                        <td><span class="badge bg-<?php echo $u['status'] === 'active' ? 'success' : 'secondary'; ?>"><?php echo ucfirst($u['status']); ?></span></td>
                                        <td>
                                            <!-- Edit Form -->
                                            <form method="POST" style="display:inline-block; width:120px;">
                                                <input type="hidden" name="action" value="edit">
                                                <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                                                <input type="text" name="name" value="<?php echo htmlspecialchars($u['name']); ?>" class="form-control form-control-sm mb-1" required>
                                                <input type="email" name="email" value="<?php echo htmlspecialchars($u['email']); ?>" class="form-control form-control-sm mb-1" required>
                                                <select name="role" class="form-select form-select-sm mb-1" required>
                                                    <option value="student" <?php if ($u['role'] === 'student') echo 'selected'; ?>>Student</option>
                                                    <option value="lecturer" <?php if ($u['role'] === 'lecturer') echo 'selected'; ?>>Lecturer</option>
                                                    <option value="admin" <?php if ($u['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                                                </select>
                                                <select name="status" class="form-select form-select-sm mb-1" required>
                                                    <option value="active" <?php if ($u['status'] === 'active') echo 'selected'; ?>>Active</option>
                                                    <option value="inactive" <?php if ($u['status'] === 'inactive') echo 'selected'; ?>>Inactive</option>
                                                    <option value="suspended" <?php if ($u['status'] === 'suspended') echo 'selected'; ?>>Suspended</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                            </form>
                                            <!-- Delete Form -->
                                            <form method="POST" style="display:inline-block;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?');">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Add User Form -->
                    <form method="POST" class="mt-4">
                        <input type="hidden" name="action" value="add">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-2">
                                <input type="text" name="name" class="form-control" placeholder="Name" required>
                            </div>
                            <div class="col-md-3">
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="col-md-2">
                                <select name="role" class="form-select" required>
                                    <option value="student">Student</option>
                                    <option value="lecturer">Lecturer</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="password" class="form-control" placeholder="Password" required>
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