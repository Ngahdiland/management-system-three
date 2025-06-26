<?php
include '../includes/layout.php';
$page_title = 'Grades';
ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Grades</h1>
            <p class="text-muted">View your grades for assignments and exams.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>My Grades</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Assignment</th>
                                    <th>Grade</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Mathematics 101</td>
                                    <td>Midterm Exam</td>
                                    <td><span class="badge bg-success">A-</span></td>
                                    <td>2024-01-15</td>
                                </tr>
                                <tr>
                                    <td>Computer Science</td>
                                    <td>Programming Project</td>
                                    <td><span class="badge bg-primary">A</span></td>
                                    <td>2024-01-12</td>
                                </tr>
                                <tr>
                                    <td>English Literature</td>
                                    <td>Essay Assignment</td>
                                    <td><span class="badge bg-warning">B+</span></td>
                                    <td>2024-01-10</td>
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