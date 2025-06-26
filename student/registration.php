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
            <p class="text-muted">Register for new courses for the semester.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Register for Courses</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="selectCourse" class="form-label">Select Course</label>
                            <select class="form-select" id="selectCourse">
                                <option>Mathematics 101</option>
                                <option>Physics 201</option>
                                <option>Computer Science 101</option>
                                <option>Chemistry 101</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
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