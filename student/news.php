<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

$page_title = 'News & Announcements';
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">News & Announcements</h1>
            <p class="text-muted">Stay updated with the latest news and announcements</p>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="filter" id="all" value="all" checked>
                                <label class="btn btn-outline-primary" for="all">
                                    <i class="fas fa-globe me-1"></i>All News
                                </label>
                                
                                <input type="radio" class="btn-check" name="filter" id="students" value="students">
                                <label class="btn btn-outline-success" for="students">
                                    <i class="fas fa-user-graduate me-1"></i>Students
                                </label>
                                
                                <input type="radio" class="btn-check" name="filter" id="lecturers" value="lecturers">
                                <label class="btn btn-outline-purple" for="lecturers">
                                    <i class="fas fa-chalkboard-teacher me-1"></i>Lecturers
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search news..." id="searchNews">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- News Feed -->
    <div class="row" id="newsContainer">
        <!-- All News -->
        <div class="col-lg-4 col-md-6 mb-4 news-item" data-category="all">
            <div class="card news-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary">All</span>
                        <small class="text-muted">2 hours ago</small>
                    </div>
                    <h5 class="card-title">Academic Calendar Updated</h5>
                    <p class="card-text">The academic calendar for the 2024-2025 academic year has been updated. Please review the important dates including registration deadlines, exam periods, and holidays.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">By: Admin Office</small>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newsModal1">
                            Read More
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students News -->
        <div class="col-lg-4 col-md-6 mb-4 news-item" data-category="students">
            <div class="card news-card student h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-success">Students</span>
                        <small class="text-muted">1 day ago</small>
                    </div>
                    <h5 class="card-title">Course Registration Opens</h5>
                    <p class="card-text">Course registration for the Spring 2025 semester will open on December 1st, 2024. Make sure to meet with your academic advisor before registering.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">By: Registrar Office</small>
                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#newsModal2">
                            Read More
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lecturers News -->
        <div class="col-lg-4 col-md-6 mb-4 news-item" data-category="lecturers">
            <div class="card news-card lecturer h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-purple">Lecturers</span>
                        <small class="text-muted">3 days ago</small>
                    </div>
                    <h5 class="card-title">Faculty Development Workshop</h5>
                    <p class="card-text">A workshop on "Modern Teaching Methods" will be held on November 15th. All faculty members are encouraged to attend this professional development opportunity.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">By: Faculty Development</small>
                        <button class="btn btn-sm btn-outline-purple" data-bs-toggle="modal" data-bs-target="#newsModal3">
                            Read More
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- All News -->
        <div class="col-lg-4 col-md-6 mb-4 news-item" data-category="all">
            <div class="card news-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary">All</span>
                        <small class="text-muted">1 week ago</small>
                    </div>
                    <h5 class="card-title">Library Hours Extended</h5>
                    <p class="card-text">The university library will now be open until 11 PM during exam periods to provide extended study hours for students. Additional study spaces have also been added.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">By: Library Services</small>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newsModal4">
                            Read More
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students News -->
        <div class="col-lg-4 col-md-6 mb-4 news-item" data-category="students">
            <div class="card news-card student h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-success">Students</span>
                        <small class="text-muted">1 week ago</small>
                    </div>
                    <h5 class="card-title">Scholarship Applications Open</h5>
                    <p class="card-text">Applications for merit-based scholarships for the 2024-2025 academic year are now open. Eligible students can apply through the student portal until December 31st.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">By: Financial Aid</small>
                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#newsModal5">
                            Read More
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lecturers News -->
        <div class="col-lg-4 col-md-6 mb-4 news-item" data-category="lecturers">
            <div class="card news-card lecturer h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-purple">Lecturers</span>
                        <small class="text-muted">2 weeks ago</small>
                    </div>
                    <h5 class="card-title">Research Grant Opportunities</h5>
                    <p class="card-text">New research grant opportunities are available for faculty members. The deadline for applications is January 15th, 2025. Contact the research office for more details.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">By: Research Office</small>
                        <button class="btn btn-sm btn-outline-purple" data-bs-toggle="modal" data-bs-target="#newsModal6">
                            Read More
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load More Button -->
    <div class="row">
        <div class="col-12 text-center">
            <button class="btn btn-primary" id="loadMoreBtn">
                <i class="fas fa-plus me-2"></i>Load More News
            </button>
        </div>
    </div>
</div>

<!-- News Detail Modals -->
<div class="modal fade" id="newsModal1" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Academic Calendar Updated</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <span class="badge bg-primary">All</span>
                    <small class="text-muted ms-2">2 hours ago | By: Admin Office</small>
                </div>
                <p>The academic calendar for the 2024-2025 academic year has been updated with the following important dates:</p>
                <ul>
                    <li><strong>Fall Semester:</strong> August 26 - December 20, 2024</li>
                    <li><strong>Spring Semester:</strong> January 13 - May 9, 2025</li>
                    <li><strong>Summer Session:</strong> May 19 - August 8, 2025</li>
                </ul>
                <p><strong>Important Deadlines:</strong></p>
                <ul>
                    <li>Course Registration: 2 weeks before semester starts</li>
                    <li>Add/Drop Period: First week of classes</li>
                    <li>Withdrawal Deadline: 8th week of classes</li>
                    <li>Final Exams: Last week of classes</li>
                </ul>
                <p>Please mark these dates in your calendar and plan accordingly. For any questions, contact the registrar's office.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Download Calendar</button>
            </div>
        </div>
    </div>
</div>

<style>
.news-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: none;
    border-radius: 10px;
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.news-card.student {
    border-left: 4px solid #28a745;
}

.news-card.lecturer {
    border-left: 4px solid #6f42c1;
}

.badge.bg-purple {
    background-color: #6f42c1 !important;
}

.btn-outline-purple {
    color: #6f42c1;
    border-color: #6f42c1;
}

.btn-outline-purple:hover {
    background-color: #6f42c1;
    border-color: #6f42c1;
    color: white;
}

.news-item.hidden {
    display: none;
}
</style>

<script>
// Filter functionality
document.querySelectorAll('input[name="filter"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const filter = this.value;
        const newsItems = document.querySelectorAll('.news-item');
        
        newsItems.forEach(item => {
            if (filter === 'all' || item.dataset.category === filter) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    });
});

// Search functionality
document.getElementById('searchNews').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const newsItems = document.querySelectorAll('.news-item');
    
    newsItems.forEach(item => {
        const title = item.querySelector('.card-title').textContent.toLowerCase();
        const text = item.querySelector('.card-text').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || text.includes(searchTerm)) {
            item.classList.remove('hidden');
        } else {
            item.classList.add('hidden');
        }
    });
});

// Load more functionality
document.getElementById('loadMoreBtn').addEventListener('click', function() {
    // Simulate loading more news
    const newsContainer = document.getElementById('newsContainer');
    
    // Create a new news item
    const newNewsItem = document.createElement('div');
    newNewsItem.className = 'col-lg-4 col-md-6 mb-4 news-item';
    newNewsItem.dataset.category = 'students';
    newNewsItem.innerHTML = `
        <div class="card news-card student h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-success">Students</span>
                    <small class="text-muted">Just now</small>
                </div>
                <h5 class="card-title">New Study Resources Available</h5>
                <p class="card-text">Additional study resources including video tutorials and practice exams are now available in the student portal for all registered courses.</p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">By: Academic Support</small>
                    <button class="btn btn-sm btn-outline-success">
                        Read More
                    </button>
                </div>
            </div>
        </div>
    `;
    
    newsContainer.appendChild(newNewsItem);
    
    // Disable button after loading a few items
    const loadedItems = newsContainer.children.length;
    if (loadedItems >= 12) {
        this.disabled = true;
        this.textContent = 'No More News';
    }
});
</script>

<?php
$page_content = ob_get_clean();
include '../includes/layout.php';
?> 