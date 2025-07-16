<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

require_once '../db.php';

$page_title = 'News & Announcements';
$admin_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle Add News
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = isset($_POST['is_published']) ? 1 : 0;
    if (!$title || !$content) {
        $error = 'Title and content are required.';
    } else {
        executeQuery("INSERT INTO news (title, content, author_id, is_published) VALUES (?, ?, ?, ?)", [
            $title, $content, $admin_id, $status
        ]);
        $success = 'News added successfully!';
    }
}

// Handle Edit News
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = intval($_POST['id']);
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = isset($_POST['is_published']) ? 1 : 0;
    if (!$title || !$content) {
        $error = 'Title and content are required.';
    } else {
        executeQuery("UPDATE news SET title = ?, content = ?, is_published = ? WHERE id = ?", [
            $title, $content, $status, $id
        ]);
        $success = 'News updated successfully!';
    }
}

// Handle Delete News
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['id']);
    executeQuery("DELETE FROM news WHERE id = ?", [$id]);
    $success = 'News deleted successfully!';
}

// Fetch all news
$news = fetchAll("SELECT * FROM news ORDER BY published_at DESC, id DESC", []);

ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">News & Announcements</h1>
            <p class="text-muted">Manage news and announcements for the system.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-newspaper me-2"></i>All News</h5>
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
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($news as $n): ?>
                                    <tr>
                                        <td><?php echo $n['id']; ?></td>
                                        <td><?php echo htmlspecialchars($n['title']); ?></td>
                                        <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($n['published_at']))); ?></td>
                                        <td><span class="badge bg-<?php echo $n['is_published'] ? 'success' : 'warning'; ?>"><?php echo $n['is_published'] ? 'Published' : 'Draft'; ?></span></td>
                                        <td>
                                            <!-- Edit Form -->
                                            <form method="POST" style="display:inline-block; width:200px;">
                                                <input type="hidden" name="action" value="edit">
                                                <input type="hidden" name="id" value="<?php echo $n['id']; ?>">
                                                <input type="text" name="title" value="<?php echo htmlspecialchars($n['title']); ?>" class="form-control form-control-sm mb-1" required>
                                                <textarea name="content" class="form-control form-control-sm mb-1" required><?php echo htmlspecialchars($n['content']); ?></textarea>
                                                <div class="form-check mb-1">
                                                    <input class="form-check-input" type="checkbox" name="is_published" id="pub_<?php echo $n['id']; ?>" <?php if ($n['is_published']) echo 'checked'; ?>>
                                                    <label class="form-check-label" for="pub_<?php echo $n['id']; ?>">Published</label>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                            </form>
                                            <!-- Delete Form -->
                                            <form method="POST" style="display:inline-block;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $n['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this news item?');">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Add News Form -->
                    <form method="POST" class="mt-4">
                        <input type="hidden" name="action" value="add">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <input type="text" name="title" class="form-control" placeholder="Title" required>
                            </div>
                            <div class="col-md-6">
                                <textarea name="content" class="form-control" placeholder="Content" required></textarea>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_published" id="add_pub">
                                    <label class="form-check-label" for="add_pub">Published</label>
                                </div>
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