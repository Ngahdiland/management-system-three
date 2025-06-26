<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

$page_title = 'Homework';
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
                                <tr>
                                    <td>Mathematics 101</td>
                                    <td>Homework 1</td>
                                    <td>2024-02-15</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td><button class="btn btn-sm btn-primary">Upload</button></td>
                                </tr>
                                <tr>
                                    <td>Physics 201</td>
                                    <td>Lab Report</td>
                                    <td>2024-02-18</td>
                                    <td><span class="badge bg-success">Submitted</span></td>
                                    <td><button class="btn btn-sm btn-secondary" disabled>Uploaded</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <form>
                        <div class="mb-3">
                            <label for="uploadAssignment" class="form-label">Upload Assignment</label>
                            <input class="form-control" type="file" id="uploadAssignment">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
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