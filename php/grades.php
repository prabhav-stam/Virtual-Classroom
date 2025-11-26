<?php
include 'auth.php';
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = $_SESSION['user_id'];
    
    // Get all grades for student
    $stmt = $conn->prepare("SELECT c.title as course_title, a.title as assignment_title, 
                           a.due_date, s.grade, a.max_score, s.submitted_at, s.feedback
                           FROM submissions s
                           JOIN assignments a ON s.assignment_id = a.id
                           JOIN courses c ON a.course_id = c.id
                           WHERE s.student_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $grades = [];
    
    while ($row = $result->fetch_assoc()) {
        $grades[] = $row;
    }
    
    // Calculate summary stats
    $stmt = $conn->prepare("SELECT 
                           COUNT(*) as total,
                           SUM(IF(s.grade IS NOT NULL, 1, 0)) as graded,
                           AVG(s.grade/a.max_score*100) as average
                           FROM submissions s
                           JOIN assignments a ON s.assignment_id = a.id
                           WHERE s.student_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stats = $stmt->get_result()->fetch_assoc();
    
    echo json_encode([
        'success' => true,
        'grades' => $grades,
        'stats' => $stats
    ]);
}

$conn->close();
?>