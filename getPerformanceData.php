<?php
session_start();
require_once 'db.php';
require_once 'middleware.php';

// Ensure user is logged in
requireAuth();

// Set JSON content type
header('Content-Type: application/json');

try {
    $userId = getCurrentUserId();
    $userRole = getCurrentUserRole();
    $courseId = $_GET['course_id'] ?? null;
    $semester = $_GET['semester'] ?? null;
    $academicYear = $_GET['academic_year'] ?? null;
    
    $response = [];
    
    // Get data based on user role
    switch ($userRole) {
        case 'student':
            $response = getStudentPerformanceData($userId, $courseId, $semester, $academicYear);
            break;
            
        case 'lecturer':
            $response = getLecturerPerformanceData($userId, $courseId, $semester, $academicYear);
            break;
            
        case 'admin':
            $response = getAdminPerformanceData($courseId, $semester, $academicYear);
            break;
            
        default:
            throw new Exception('Invalid user role');
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    error_log("Performance data error: " . $e->getMessage());
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

/**
 * Get performance data for students
 */
function getStudentPerformanceData($studentId, $courseId = null, $semester = null, $academicYear = null) {
    $whereConditions = ["g.student_id = ?"];
    $params = [$studentId];
    
    if ($courseId) {
        $whereConditions[] = "g.course_id = ?";
        $params[] = $courseId;
    }
    
    if ($semester) {
        $whereConditions[] = "e.semester = ?";
        $params[] = $semester;
    }
    
    if ($academicYear) {
        $whereConditions[] = "e.academic_year = ?";
        $params[] = $academicYear;
    }
    
    $whereClause = implode(" AND ", $whereConditions);
    
    // Get grades by course
    $gradesSql = "SELECT 
                    c.course_name,
                    c.course_code,
                    g.assignment_name,
                    g.assignment_type,
                    g.grade_points,
                    g.max_points,
                    g.grade_letter,
                    g.graded_at,
                    e.semester,
                    e.academic_year
                  FROM grades g
                  JOIN courses c ON g.course_id = c.id
                  JOIN enrollments e ON g.student_id = e.student_id AND g.course_id = e.course_id
                  WHERE {$whereClause}
                  ORDER BY c.course_name, g.graded_at DESC";
    
    $grades = fetchAll($gradesSql, $params);
    
    // Calculate course averages
    $courseAverages = [];
    $assignmentTypes = [];
    
    foreach ($grades as $grade) {
        $courseKey = $grade['course_code'];
        
        if (!isset($courseAverages[$courseKey])) {
            $courseAverages[$courseKey] = [
                'course_name' => $grade['course_name'],
                'course_code' => $grade['course_code'],
                'total_points' => 0,
                'max_points' => 0,
                'assignments' => 0,
                'grades' => []
            ];
        }
        
        $courseAverages[$courseKey]['total_points'] += $grade['grade_points'];
        $courseAverages[$courseKey]['max_points'] += $grade['max_points'];
        $courseAverages[$courseKey]['assignments']++;
        $courseAverages[$courseKey]['grades'][] = [
            'assignment' => $grade['assignment_name'],
            'type' => $grade['assignment_type'],
            'points' => $grade['grade_points'],
            'max_points' => $grade['max_points'],
            'percentage' => ($grade['grade_points'] / $grade['max_points']) * 100,
            'letter' => $grade['grade_letter'],
            'date' => $grade['graded_at']
        ];
        
        if (!isset($assignmentTypes[$grade['assignment_type']])) {
            $assignmentTypes[$grade['assignment_type']] = 0;
        }
        $assignmentTypes[$grade['assignment_type']]++;
    }
    
    // Calculate percentages
    foreach ($courseAverages as &$course) {
        $course['average_percentage'] = $course['max_points'] > 0 ? 
            ($course['total_points'] / $course['max_points']) * 100 : 0;
        $course['average_letter'] = calculateGradeLetter($course['average_percentage']);
    }
    
    return [
        'success' => true,
        'data' => [
            'courses' => array_values($courseAverages),
            'assignment_types' => $assignmentTypes,
            'total_courses' => count($courseAverages),
            'total_assignments' => count($grades)
        ]
    ];
}

/**
 * Get performance data for lecturers
 */
function getLecturerPerformanceData($lecturerId, $courseId = null, $semester = null, $academicYear = null) {
    $whereConditions = ["c.lecturer_id = ?"];
    $params = [$lecturerId];
    
    if ($courseId) {
        $whereConditions[] = "c.id = ?";
        $params[] = $courseId;
    }
    
    if ($semester) {
        $whereConditions[] = "e.semester = ?";
        $params[] = $semester;
    }
    
    if ($academicYear) {
        $whereConditions[] = "e.academic_year = ?";
        $params[] = $academicYear;
    }
    
    $whereClause = implode(" AND ", $whereConditions);
    
    // Get course statistics
    $courseStatsSql = "SELECT 
                        c.id,
                        c.course_name,
                        c.course_code,
                        COUNT(DISTINCT e.student_id) as student_count,
                        AVG(g.grade_points / g.max_points * 100) as avg_percentage,
                        COUNT(g.id) as assignment_count
                      FROM courses c
                      LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'enrolled'
                      LEFT JOIN grades g ON c.id = g.course_id
                      WHERE {$whereClause}
                      GROUP BY c.id, c.course_name, c.course_code
                      ORDER BY c.course_name";
    
    $courseStats = fetchAll($courseStatsSql, $params);
    
    // Get grade distribution
    $gradeDistributionSql = "SELECT 
                               g.grade_letter,
                               COUNT(*) as count
                             FROM grades g
                             JOIN courses c ON g.course_id = c.id
                             WHERE c.lecturer_id = ?
                             GROUP BY g.grade_letter
                             ORDER BY g.grade_letter";
    
    $gradeDistribution = fetchAll($gradeDistributionSql, [$lecturerId]);
    
    // Get assignment type statistics
    $assignmentTypeSql = "SELECT 
                           g.assignment_type,
                           AVG(g.grade_points / g.max_points * 100) as avg_percentage,
                           COUNT(*) as count
                         FROM grades g
                         JOIN courses c ON g.course_id = c.id
                         WHERE c.lecturer_id = ?
                         GROUP BY g.assignment_type";
    
    $assignmentTypes = fetchAll($assignmentTypeSql, [$lecturerId]);
    
    return [
        'success' => true,
        'data' => [
            'courses' => $courseStats,
            'grade_distribution' => $gradeDistribution,
            'assignment_types' => $assignmentTypes,
            'total_courses' => count($courseStats)
        ]
    ];
}

/**
 * Get performance data for admins
 */
function getAdminPerformanceData($courseId = null, $semester = null, $academicYear = null) {
    $whereConditions = ["1=1"];
    $params = [];
    
    if ($courseId) {
        $whereConditions[] = "c.id = ?";
        $params[] = $courseId;
    }
    
    if ($semester) {
        $whereConditions[] = "e.semester = ?";
        $params[] = $semester;
    }
    
    if ($academicYear) {
        $whereConditions[] = "e.academic_year = ?";
        $params[] = $academicYear;
    }
    
    $whereClause = implode(" AND ", $whereConditions);
    
    // Get department statistics
    $deptStatsSql = "SELECT 
                       c.department,
                       COUNT(DISTINCT c.id) as course_count,
                       COUNT(DISTINCT e.student_id) as student_count,
                       AVG(g.grade_points / g.max_points * 100) as avg_percentage
                     FROM courses c
                     LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'enrolled'
                     LEFT JOIN grades g ON c.id = g.course_id
                     WHERE {$whereClause}
                     GROUP BY c.department
                     ORDER BY c.department";
    
    $deptStats = fetchAll($deptStatsSql, $params);
    
    // Get overall grade distribution
    $overallGradeSql = "SELECT 
                         g.grade_letter,
                         COUNT(*) as count
                       FROM grades g
                       JOIN courses c ON g.course_id = c.id
                       WHERE {$whereClause}
                       GROUP BY g.grade_letter
                       ORDER BY g.grade_letter";
    
    $overallGrades = fetchAll($overallGradeSql, $params);
    
    // Get semester performance
    $semesterSql = "SELECT 
                     e.semester,
                     e.academic_year,
                     AVG(g.grade_points / g.max_points * 100) as avg_percentage,
                     COUNT(DISTINCT e.student_id) as student_count
                   FROM enrollments e
                   JOIN grades g ON e.student_id = g.student_id AND e.course_id = g.course_id
                   WHERE {$whereClause}
                   GROUP BY e.semester, e.academic_year
                   ORDER BY e.academic_year DESC, e.semester";
    
    $semesterStats = fetchAll($semesterSql, $params);
    
    return [
        'success' => true,
        'data' => [
            'departments' => $deptStats,
            'grade_distribution' => $overallGrades,
            'semester_performance' => $semesterStats,
            'total_departments' => count($deptStats)
        ]
    ];
}

/**
 * Calculate grade letter based on percentage
 */
function calculateGradeLetter($percentage) {
    if ($percentage >= 93) return 'A';
    if ($percentage >= 90) return 'A-';
    if ($percentage >= 87) return 'B+';
    if ($percentage >= 83) return 'B';
    if ($percentage >= 80) return 'B-';
    if ($percentage >= 77) return 'C+';
    if ($percentage >= 73) return 'C';
    if ($percentage >= 70) return 'C-';
    if ($percentage >= 67) return 'D+';
    if ($percentage >= 63) return 'D';
    if ($percentage >= 60) return 'D-';
    return 'F';
}
?> 