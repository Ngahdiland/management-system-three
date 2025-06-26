<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

$page_title = 'Student Dashboard';
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Welcome back, <?php echo $_SESSION['name']; ?>!</h1>
            <p class="text-muted">Here's what's happening with your academic journey</p>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row">
        <!-- Registered Courses Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-primary">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">5</div>
                        <div class="text-muted">Registered Courses</div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="courses.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>View Courses
                    </a>
                </div>
            </div>
        </div>

        <!-- GPA Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-success">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">3.8</div>
                        <div class="text-muted">Current GPA</div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="grades.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-chart-bar me-1"></i>View Grades
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-warning">
                            <i class="fas fa-comments"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">3</div>
                        <div class="text-muted">New Messages</div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="messages.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-envelope me-1"></i>Open Chat
                    </a>
                </div>
            </div>
        </div>

        <!-- News Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-info">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">2</div>
                        <div class="text-muted">New Announcements</div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="news.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-bullhorn me-1"></i>Read News
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Upcoming Events -->
    <div class="row">
        <!-- Recent Grades -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Recent Grades</h5>
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

        <!-- Upcoming Events -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Upcoming Events</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Final Exams</h6>
                                <small class="text-muted">Mathematics 101</small>
                            </div>
                            <span class="badge bg-danger">Tomorrow</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Project Deadline</h6>
                                <small class="text-muted">Computer Science</small>
                            </div>
                            <span class="badge bg-warning">3 days</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Class Presentation</h6>
                                <small class="text-muted">English Literature</small>
                            </div>
                            <span class="badge bg-info">1 week</span>
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
                            <a href="registration.php" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-plus mb-2"></i><br>
                                Register for Courses
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="homework.php" class="btn btn-outline-success w-100">
                                <i class="fas fa-tasks mb-2"></i><br>
                                Submit Homework
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="timetable.php" class="btn btn-outline-info w-100">
                                <i class="fas fa-calendar-alt mb-2"></i><br>
                                View Timetable
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="messages.php" class="btn btn-outline-warning w-100">
                                <i class="fas fa-comments mb-2"></i><br>
                                Contact Lecturer
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