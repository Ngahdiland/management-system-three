<?php
include '../includes/layout.php';
$page_title = 'Grading';
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
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Assignment</th>
                                    <th>Submission Date</th>
                                    <th>Current Grade</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>Mathematics 101</td>
                                    <td>Homework 1</td>
                                    <td>2024-02-10</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td><button class="btn btn-sm btn-primary">Grade</button></td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>Physics 201</td>
                                    <td>Lab Report</td>
                                    <td>2024-02-09</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td><button class="btn btn-sm btn-primary">Grade</button></td>
                                </tr>
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