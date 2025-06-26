<?php
include '../includes/layout.php';
$page_title = 'Timetable';
ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Timetable</h1>
            <p class="text-muted">View and manage the academic timetable.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Academic Timetable</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>8:00-10:00</th>
                                    <th>10:00-12:00</th>
                                    <th>12:00-2:00</th>
                                    <th>2:00-4:00</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Monday</td>
                                    <td>Mathematics 101</td>
                                    <td>Physics 201</td>
                                    <td></td>
                                    <td>Chemistry 101</td>
                                </tr>
                                <tr>
                                    <td>Tuesday</td>
                                    <td>Computer Science 101</td>
                                    <td></td>
                                    <td>English 101</td>
                                    <td>Mathematics 101</td>
                                </tr>
                                <tr>
                                    <td>Wednesday</td>
                                    <td>Biology 101</td>
                                    <td>Mathematics 101</td>
                                    <td></td>
                                    <td>Physics 201</td>
                                </tr>
                                <tr>
                                    <td>Thursday</td>
                                    <td>Chemistry 101</td>
                                    <td>English 101</td>
                                    <td>Computer Science 101</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Friday</td>
                                    <td>Physics 201</td>
                                    <td>Biology 101</td>
                                    <td>Mathematics 101</td>
                                    <td>English 101</td>
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