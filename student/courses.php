<?php
include '../includes/layout.php';
$page_title = 'My Courses';
ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">My Courses</h1>
            <p class="text-muted">View all your registered courses.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Registered Courses</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course ID</th>
                                    <th>Title</th>
                                    <th>Lecturer</th>
                                    <th>Schedule</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>CS101</td>
                                    <td>Introduction to Computer Science</td>
                                    <td>Dr. Jane Doe</td>
                                    <td>Mon, Wed 9:00-10:30</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>MTH201</td>
                                    <td>Calculus II</td>
                                    <td>Prof. John Smith</td>
                                    <td>Tue, Thu 11:00-12:30</td>
                                    <td><span class="badge bg-success">Active</span></td>
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