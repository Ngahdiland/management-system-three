<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

$page_title = 'Course Registration';
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Course Registration</h1>
            <p class="text-muted">Register for courses for the upcoming semester</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Course Registration Form</h5>
                </div>
                <div class="card-body">
                    <form action="../registerCourse.php" method="POST" id="registrationForm">
                        <input type="hidden" name="student_id" value="<?php echo $_SESSION['user_id']; ?>">
                        
                        <!-- Semester Selection -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="semester" class="form-label">Semester</label>
                                <select class="form-select" id="semester" name="semester" required>
                                    <option value="">Select Semester</option>
                                    <option value="Fall 2024">Fall 2024</option>
                                    <option value="Spring 2025">Spring 2025</option>
                                    <option value="Summer 2025">Summer 2025</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="academic_year" class="form-label">Academic Year</label>
                                <select class="form-select" id="academic_year" name="academic_year" required>
                                    <option value="">Select Year</option>
                                    <option value="2024-2025">2024-2025</option>
                                    <option value="2025-2026">2025-2026</option>
                                </select>
                            </div>
                        </div>

                        <!-- Course Selection -->
                        <div class="mb-3">
                            <label class="form-label">Available Courses</label>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="courses[]" value="CS101" id="cs101">
                                        <label class="form-check-label" for="cs101">
                                            <strong>CS101 - Introduction to Computer Science</strong><br>
                                            <small class="text-muted">Credits: 3 | Schedule: Mon, Wed 9:00 AM</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="courses[]" value="MATH101" id="math101">
                                        <label class="form-check-label" for="math101">
                                            <strong>MATH101 - Calculus I</strong><br>
                                            <small class="text-muted">Credits: 4 | Schedule: Tue, Thu 10:00 AM</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="courses[]" value="ENG101" id="eng101">
                                        <label class="form-check-label" for="eng101">
                                            <strong>ENG101 - English Composition</strong><br>
                                            <small class="text-muted">Credits: 3 | Schedule: Mon, Wed 2:00 PM</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="courses[]" value="PHYS101" id="phys101">
                                        <label class="form-check-label" for="phys101">
                                            <strong>PHYS101 - Physics I</strong><br>
                                            <small class="text-muted">Credits: 4 | Schedule: Tue, Thu 1:00 PM</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="courses[]" value="CHEM101" id="chem101">
                                        <label class="form-check-label" for="chem101">
                                            <strong>CHEM101 - General Chemistry</strong><br>
                                            <small class="text-muted">Credits: 4 | Schedule: Fri 9:00 AM</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="courses[]" value="BIO101" id="bio101">
                                        <label class="form-check-label" for="bio101">
                                            <strong>BIO101 - Biology I</strong><br>
                                            <small class="text-muted">Credits: 4 | Schedule: Mon, Wed 11:00 AM</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any special requests or notes..."></textarea>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a> of course registration
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Register for Courses
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-undo me-2"></i>Reset Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Registration Summary -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Registration Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Selected Courses: <span id="selectedCount">0</span></h6>
                        <div id="selectedCourses"></div>
                    </div>
                    <div class="mb-3">
                        <h6>Total Credits: <span id="totalCredits">0</span></h6>
                    </div>
                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Note:</strong> Maximum course load is 18 credits per semester.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Registration Guidelines -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Registration Guidelines</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Maximum 18 credits per semester
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Prerequisites must be completed
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            No schedule conflicts allowed
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Registration deadline: 2 weeks before semester
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Course Registration Terms</h6>
                <ol>
                    <li>Students must meet all prerequisites for selected courses.</li>
                    <li>Course registration is binding once submitted.</li>
                    <li>Maximum course load is 18 credits per semester.</li>
                    <li>Schedule conflicts will result in automatic course removal.</li>
                    <li>Tuition fees must be paid before registration is confirmed.</li>
                    <li>Course withdrawal deadlines apply as per academic calendar.</li>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Course selection tracking
const courseCheckboxes = document.querySelectorAll('input[name="courses[]"]');
const selectedCount = document.getElementById('selectedCount');
const selectedCourses = document.getElementById('selectedCourses');
const totalCredits = document.getElementById('totalCredits');

const courseData = {
    'CS101': { name: 'Introduction to Computer Science', credits: 3 },
    'MATH101': { name: 'Calculus I', credits: 4 },
    'ENG101': { name: 'English Composition', credits: 3 },
    'PHYS101': { name: 'Physics I', credits: 4 },
    'CHEM101': { name: 'General Chemistry', credits: 4 },
    'BIO101': { name: 'Biology I', credits: 4 }
};

function updateSummary() {
    const selected = Array.from(courseCheckboxes).filter(cb => cb.checked);
    selectedCount.textContent = selected.length;
    
    let credits = 0;
    let courseList = '';
    
    selected.forEach(checkbox => {
        const course = courseData[checkbox.value];
        credits += course.credits;
        courseList += `<div class="badge bg-primary me-1 mb-1">${course.name}</div>`;
    });
    
    totalCredits.textContent = credits;
    selectedCourses.innerHTML = courseList;
    
    // Check if over credit limit
    if (credits > 18) {
        totalCredits.style.color = 'red';
        totalCredits.innerHTML += ' <small class="text-danger">(Over limit!)</small>';
    } else {
        totalCredits.style.color = '';
    }
}

courseCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateSummary);
});

// Form validation
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    const selected = Array.from(courseCheckboxes).filter(cb => cb.checked);
    const credits = selected.reduce((sum, cb) => sum + courseData[cb.value].credits, 0);
    
    if (selected.length === 0) {
        e.preventDefault();
        alert('Please select at least one course.');
        return;
    }
    
    if (credits > 18) {
        e.preventDefault();
        alert('Course load exceeds maximum of 18 credits.');
        return;
    }
});
</script>

<?php
$page_content = ob_get_clean();
include '../includes/layout.php';
?> 