<?php
include 'auth.php';
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $course_id = isset($_GET['course_id']) ? $_GET['course_id'] : null;
    $user_id = $_SESSION['user_id'];
    
    if ($_SESSION['role'] === 'teacher') {
        // Teacher gets all assignments for their courses
        $query = "SELECT a.*, c.title as course_title 
                 FROM assignments a
                 JOIN courses c ON a.course_id = c.id
                 WHERE c.teacher_id = ?";
        $params = [$user_id];
        
        if ($course_id) {
            $query .= " AND a.course_id = ?";
            $params[] = $course_id;
        }
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat("i", count($params)), ...$params);
    } else {
        // Student gets assignments for enrolled courses
        $query = "SELECT a.*, c.title as course_title, s.grade, s.submitted_at
                 FROM assignments a
                 JOIN courses c ON a.course_id = c.id
                 JOIN enrollments e ON c.id = e.course_id
                 LEFT JOIN submissions s ON a.id = s.assignment_id AND s.student_id = ?
                 WHERE e.student_id = ?";
        $params = [$user_id, $user_id];
        
        if ($course_id) {
            $query .= " AND a.course_id = ?";
            $params[] = $course_id;
        }
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat("i", count($params)), ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $assignments = [];
    
    while ($row = $result->fetch_assoc()) {
        $assignments[] = $row;
    }
    
    echo json_encode(['success' => true, 'assignments' => $assignments]);
}

$conn->close();
?>