<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$page_title = 'Admin Dashboard';
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
            <p class="text-muted">System overview and management</p>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row">
        <!-- Total Students Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-primary">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">1,247</div>
                        <div class="text-muted">Total Students</div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="users.php?role=student" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>View Students
                    </a>
                </div>
            </div>
        </div>

        <!-- Active Courses Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-success">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">89</div>
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

        <!-- Attendance Rate Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-warning">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">87%</div>
                        <div class="text-muted">Avg. Attendance</div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="attendance.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-chart-bar me-1"></i>View Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-info">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">1,389</div>
                        <div class="text-muted">Total Users</div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="users.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-cog me-1"></i>Manage Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- System Statistics and Recent Activity -->
    <div class="row">
        <!-- System Statistics -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>System Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Students</th>
                                    <th>Lecturers</th>
                                    <th>Courses</th>
                                    <th>Avg. GPA</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Computer Science</strong></td>
                                    <td>245</td>
                                    <td>12</td>
                                    <td>18</td>
                                    <td>3.4</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Mathematics</strong></td>
                                    <td>189</td>
                                    <td>8</td>
                                    <td>15</td>
                                    <td>3.2</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Physics</strong></td>
                                    <td>156</td>
                                    <td>10</td>
                                    <td>12</td>
                                    <td>3.1</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Chemistry</strong></td>
                                    <td>203</td>
                                    <td>9</td>
                                    <td>14</td>
                                    <td>3.3</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Biology</strong></td>
                                    <td>178</td>
                                    <td>7</td>
                                    <td>11</td>
                                    <td>3.5</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Engineering</strong></td>
                                    <td>276</td>
                                    <td>15</td>
                                    <td>19</td>
                                    <td>3.0</td>
                                    <td><span class="badge bg-warning">Maintenance</span></td>
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
                                <h6 class="mb-1">New User Registration</h6>
                                <small class="text-muted">5 min ago</small>
                            </div>
                            <p class="mb-1">Student: John Smith</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Course Created</h6>
                                <small class="text-muted">1 hour ago</small>
                            </div>
                            <p class="mb-1">Advanced Machine Learning</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">System Backup</h6>
                                <small class="text-muted">3 hours ago</small>
                            </div>
                            <p class="mb-1">Database backup completed</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Grade Report Generated</h6>
                                <small class="text-muted">5 hours ago</small>
                            </div>
                            <p class="mb-1">Midterm grades for CS101</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">News Posted</h6>
                                <small class="text-muted">1 day ago</small>
                            </div>
                            <p class="mb-1">Academic calendar updated</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions and System Health -->
    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="users.php" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-plus mb-2"></i><br>
                                Add New User
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="courses.php" class="btn btn-outline-success w-100">
                                <i class="fas fa-book mb-2"></i><br>
                                Create Course
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="news.php" class="btn btn-outline-info w-100">
                                <i class="fas fa-newspaper mb-2"></i><br>
                                Post Announcement
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="reports.php" class="btn btn-outline-warning w-100">
                                <i class="fas fa-chart-line mb-2"></i><br>
                                Generate Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-heartbeat me-2"></i>System Health</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Database Performance</span>
                            <span>95%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 95%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Server Uptime</span>
                            <span>99.9%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" style="width: 99.9%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Storage Usage</span>
                            <span>67%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: 67%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Active Sessions</span>
                            <span>234</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: 78%"></div>
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