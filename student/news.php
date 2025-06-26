<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

$page_title = 'News Feed';
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">News Feed</h1>
            <p class="text-muted">Read the latest news and announcements.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Latest News</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <h6>Semester Begins <span class="badge bg-success">New</span></h6>
                            <small class="text-muted">2024-02-01</small>
                            <p>Welcome to the new semester! Classes start on February 1st.</p>
                        </li>
                        <li class="list-group-item">
                            <h6>System Maintenance</h6>
                            <small class="text-muted">2024-01-20</small>
                            <p>The LMS will be down for maintenance on January 20th from 2am to 4am.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
include '../includes/layout.php';
?> 