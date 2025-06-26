<?php
include '../includes/layout.php';
$page_title = 'Analytics';
ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Analytics</h1>
            <p class="text-muted">View analytics and performance statistics for your courses.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Course Performance</h5>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Mathematics 101: <span class="text-success">Avg. Grade: B+</span></li>
                        <li>Physics 201: <span class="text-success">Avg. Grade: A-</span></li>
                        <li>Biology 101: <span class="text-warning">Avg. Grade: B</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Attendance Overview</h5>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Mathematics 101: <span class="text-success">92%</span></li>
                        <li>Physics 201: <span class="text-success">88%</span></li>
                        <li>Biology 101: <span class="text-warning">80%</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$page_content = ob_get_clean();
include '../includes/layout.php'; 