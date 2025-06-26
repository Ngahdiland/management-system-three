<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

$page_title = 'Homework';
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Homework & Assignments</h1>
            <p class="text-muted">View and submit your homework assignments</p>
        </div>
    </div>

    <!-- Homework Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-primary">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">8</div>
                        <div class="text-muted">Total Assignments</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">5</div>
                        <div class="text-muted">Submitted</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">2</div>
                        <div class="text-muted">Pending</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="dashboard-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon text-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="number">1</div>
                        <div class="text-muted">Overdue</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Homework List -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Homework Assignments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Assignment</th>
                                    <th>Course</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Grade</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Calculus Problem Set 3</strong><br>
                                        <small class="text-muted">Derivatives and Applications</small>
                                    </td>
                                    <td>Mathematics 101</td>
                                    <td>
                                        <span class="text-success">Dec 15, 2024</span><br>
                                        <small class="text-muted">3 days left</small>
                                    </td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>-</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#submitModal" data-assignment="Calculus Problem Set 3">
                                            <i class="fas fa-upload me-1"></i>Submit
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Programming Project</strong><br>
                                        <small class="text-muted">Web Application Development</small>
                                    </td>
                                    <td>Computer Science</td>
                                    <td>
                                        <span class="text-danger">Dec 10, 2024</span><br>
                                        <small class="text-danger">Overdue</small>
                                    </td>
                                    <td><span class="badge bg-danger">Overdue</span></td>
                                    <td>-</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#submitModal" data-assignment="Programming Project">
                                            <i class="fas fa-upload me-1"></i>Submit
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Essay Assignment</strong><br>
                                        <small class="text-muted">Literary Analysis</small>
                                    </td>
                                    <td>English Literature</td>
                                    <td>
                                        <span class="text-success">Dec 20, 2024</span><br>
                                        <small class="text-muted">8 days left</small>
                                    </td>
                                    <td><span class="badge bg-success">Submitted</span></td>
                                    <td><span class="badge bg-success">A-</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewModal">
                                            <i class="fas fa-eye me-1"></i>View
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Physics Lab Report</strong><br>
                                        <small class="text-muted">Mechanics Experiment</small>
                                    </td>
                                    <td>Physics 101</td>
                                    <td>
                                        <span class="text-success">Dec 18, 2024</span><br>
                                        <small class="text-muted">6 days left</small>
                                    </td>
                                    <td><span class="badge bg-success">Submitted</span></td>
                                    <td><span class="badge bg-primary">B+</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewModal">
                                            <i class="fas fa-eye me-1"></i>View
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Chemistry Quiz</strong><br>
                                        <small class="text-muted">Chemical Bonding</small>
                                    </td>
                                    <td>Chemistry 101</td>
                                    <td>
                                        <span class="text-success">Dec 12, 2024</span><br>
                                        <small class="text-muted">Today</small>
                                    </td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>-</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#submitModal" data-assignment="Chemistry Quiz">
                                            <i class="fas fa-upload me-1"></i>Submit
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#submitModal">
                            <i class="fas fa-upload me-2"></i>Submit New Assignment
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>Download Template
                        </button>
                        <button class="btn btn-outline-info">
                            <i class="fas fa-calendar me-2"></i>View Calendar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Upcoming Deadlines</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Chemistry Quiz</h6>
                                <small class="text-muted">Chemical Bonding</small>
                            </div>
                            <span class="badge bg-warning">Today</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Calculus Problem Set 3</h6>
                                <small class="text-muted">Mathematics 101</small>
                            </div>
                            <span class="badge bg-info">3 days</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Physics Lab Report</h6>
                                <small class="text-muted">Physics 101</small>
                            </div>
                            <span class="badge bg-info">6 days</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Submit Assignment Modal -->
<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Submit Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="submitForm">
                    <div class="mb-3">
                        <label class="form-label">Assignment</label>
                        <input type="text" class="form-control" id="assignmentTitle" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="assignmentFile" class="form-label">Upload File</label>
                        <div class="upload-area" id="uploadArea">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <h5>Drag and drop files here</h5>
                            <p class="text-muted">or</p>
                            <input type="file" class="form-control" id="assignmentFile" accept=".pdf,.doc,.docx,.txt,.zip,.rar" style="display: none;">
                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('assignmentFile').click()">
                                Choose File
                            </button>
                            <p class="text-muted mt-2">Maximum file size: 10MB</p>
                        </div>
                        <div id="fileInfo" class="mt-3" style="display: none;">
                            <div class="alert alert-info">
                                <i class="fas fa-file me-2"></i>
                                <span id="fileName"></span>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeFile()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="assignmentNotes" class="form-label">Additional Notes (Optional)</label>
                        <textarea class="form-control" id="assignmentNotes" rows="3" placeholder="Any additional notes or comments..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmSubmit" required>
                            <label class="form-check-label" for="confirmSubmit">
                                I confirm that this is my original work and I have not plagiarized any content.
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitBtn" onclick="submitAssignment()">
                    <i class="fas fa-upload me-2"></i>Submit Assignment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Assignment Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assignment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Assignment Information</h6>
                        <p><strong>Title:</strong> Essay Assignment</p>
                        <p><strong>Course:</strong> English Literature</p>
                        <p><strong>Submitted:</strong> Dec 8, 2024</p>
                        <p><strong>Grade:</strong> <span class="badge bg-success">A-</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Feedback</h6>
                        <p>Excellent analysis of the literary themes. Your argument is well-structured and supported with relevant textual evidence. Minor improvements needed in citation format.</p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-download me-2"></i>Download Submission
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 40px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #667eea;
    background: #f8f9ff;
}

.upload-area.dragover {
    border-color: #667eea;
    background: #e3f2fd;
}

.progress {
    height: 8px;
    border-radius: 4px;
}
</style>

<script>
// File upload handling
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('assignmentFile');
const fileInfo = document.getElementById('fileInfo');
const fileName = document.getElementById('fileName');

// Drag and drop functionality
uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        handleFile(files[0]);
    }
});

fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        handleFile(e.target.files[0]);
    }
});

function handleFile(file) {
    if (file.size > 10 * 1024 * 1024) { // 10MB limit
        alert('File size exceeds 10MB limit');
        return;
    }
    
    fileName.textContent = file.name;
    fileInfo.style.display = 'block';
    uploadArea.style.display = 'none';
}

function removeFile() {
    fileInput.value = '';
    fileInfo.style.display = 'none';
    uploadArea.style.display = 'block';
}

// Modal assignment title
document.querySelectorAll('[data-bs-target="#submitModal"]').forEach(button => {
    button.addEventListener('click', function() {
        const assignment = this.dataset.assignment;
        document.getElementById('assignmentTitle').value = assignment;
    });
});

// Submit assignment
function submitAssignment() {
    const form = document.getElementById('submitForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    if (!fileInput.files[0]) {
        alert('Please select a file to upload');
        return;
    }
    
    // Simulate upload progress
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
    
    // Create progress bar
    const progressBar = document.createElement('div');
    progressBar.className = 'progress mt-3';
    progressBar.innerHTML = '<div class="progress-bar" style="width: 0%"></div>';
    form.appendChild(progressBar);
    
    let progress = 0;
    const interval = setInterval(() => {
        progress += 10;
        progressBar.querySelector('.progress-bar').style.width = progress + '%';
        
        if (progress >= 100) {
            clearInterval(interval);
            setTimeout(() => {
                alert('Assignment submitted successfully!');
                bootstrap.Modal.getInstance(document.getElementById('submitModal')).hide();
                location.reload();
            }, 500);
        }
    }, 200);
}
</script>

<?php
$page_content = ob_get_clean();
include '../includes/layout.php';
?> 