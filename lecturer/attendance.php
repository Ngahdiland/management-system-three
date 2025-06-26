<?php
include '../includes/layout.php';
$page_title = 'Attendance';
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
                    <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Mark Attendance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>Mathematics 101</td>
                                    <td>2024-02-12</td>
                                    <td><span class="badge bg-danger">Absent</span></td>
                                    <td><button class="btn btn-sm btn-success">Mark Present</button></td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>Physics 201</td>
                                    <td>2024-02-12</td>
                                    <td><span class="badge bg-success">Present</span></td>
                                    <td><button class="btn btn-sm btn-danger">Mark Absent</button></td>
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