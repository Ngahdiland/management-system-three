<?php
include '../includes/layout.php';
$page_title = 'Courses';
ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">My Courses</h1>
            <p class="text-muted">View and manage your courses.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Courses Taught</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course ID</th>
                                    <th>Title</th>
                                    <th>Department</th>
                                    <th>Enrolled Students</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>MTH201</td>
                                    <td>Calculus II</td>
                                    <td>Mathematics</td>
                                    <td>28</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">View</button>
                                        <button class="btn btn-sm btn-secondary">Edit</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PHY101</td>
                                    <td>Physics I</td>
                                    <td>Physics</td>
                                    <td>32</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">View</button>
                                        <button class="btn btn-sm btn-secondary">Edit</button>
                                    </td>
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