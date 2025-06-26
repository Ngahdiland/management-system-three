<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lecturer') {
    header('Location: ../login.php');
    exit();
}

$page_title = 'Lecturer Dashboard';
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Welcome, Professor <?php echo $_SESSION['name']; ?>!</h1>
            <p class="text-muted">Manage your courses and track student progress</p>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row">
        <!-- Course Count Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-primary">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">4</div>
                        <div class="text-muted">Active Courses</div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="courses.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>View Courses
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Students Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-success">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">127</div>
                        <div class="text-muted">Total Students</div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="students.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-user-graduate me-1"></i>View Students
                    </a>
                </div>
            </div>
        </div>

        <!-- Grading Status Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-warning">
                            <i class="fas fa-edit"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">23</div>
                        <div class="text-muted">Pending Grades</div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="grading.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-check me-1"></i>Grade Now
                    </a>
                </div>
            </div>
        </div>

        <!-- Analytics Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-info">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">85%</div>
                        <div class="text-muted">Avg. Performance</div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="analytics.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-chart-line me-1"></i>View Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Overview and Recent Activity -->
    <div class="row">
        <!-- Course Overview -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Course Overview</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Students</th>
                                    <th>Avg. Grade</th>
                                    <th>Attendance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Mathematics 101</strong><br>
                                        <small class="text-muted">Mon, Wed 9:00 AM</small>
                                    </td>
                                    <td>35</td>
                                    <td><span class="badge bg-success">B+</span></td>
                                    <td>92%</td>
                                    <td>
                                        <a href="course_details.php?id=1" class="btn btn-sm btn-outline-primary">Manage</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Advanced Calculus</strong><br>
                                        <small class="text-muted">Tue, Thu 11:00 AM</small>
                                    </td>
                                    <td>28</td>
                                    <td><span class="badge bg-primary">A-</span></td>
                                    <td>88%</td>
                                    <td>
                                        <a href="course_details.php?id=2" class="btn btn-sm btn-outline-primary">Manage</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Linear Algebra</strong><br>
                                        <small class="text-muted">Fri 2:00 PM</small>
                                    </td>
                                    <td>42</td>
                                    <td><span class="badge bg-warning">B</span></td>
                                    <td>85%</td>
                                    <td>
                                        <a href="course_details.php?id=3" class="btn btn-sm btn-outline-primary">Manage</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Statistics</strong><br>
                                        <small class="text-muted">Mon, Wed 3:00 PM</small>
                                    </td>
                                    <td>22</td>
                                    <td><span class="badge bg-info">A</span></td>
                                    <td>90%</td>
                                    <td>
                                        <a href="course_details.php?id=4" class="btn btn-sm btn-outline-primary">Manage</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">New Assignment Submitted</h6>
                                <small class="text-muted">2 min ago</small>
                            </div>
                            <p class="mb-1">John Doe - Mathematics 101</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Attendance Marked</h6>
                                <small class="text-muted">1 hour ago</small>
                            </div>
                            <p class="mb-1">Advanced Calculus - 28/30 present</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Grade Posted</h6>
                                <small class="text-muted">3 hours ago</small>
                            </div>
                            <p class="mb-1">Linear Algebra Midterm Results</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">New Message</h6>
                                <small class="text-muted">5 hours ago</small>
                            </div>
                            <p class="mb-1">From: Sarah Johnson</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="attendance.php" class="btn btn-outline-primary w-100">
                                <i class="fas fa-clipboard-check mb-2"></i><br>
                                Mark Attendance
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="grading.php" class="btn btn-outline-success w-100">
                                <i class="fas fa-edit mb-2"></i><br>
                                Grade Assignments
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="homework.php" class="btn btn-outline-info w-100">
                                <i class="fas fa-tasks mb-2"></i><br>
                                Post Homework
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="analytics.php" class="btn btn-outline-warning w-100">
                                <i class="fas fa-chart-bar mb-2"></i><br>
                                View Analytics
                            </a>
                        </div>
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