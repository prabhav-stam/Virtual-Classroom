<?php
include 'auth.php';
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get courses for current user
    if ($_SESSION['role'] === 'teacher') {
        $stmt = $conn->prepare("SELECT * FROM courses WHERE teacher_id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
    } else {
        $stmt = $conn->prepare("SELECT c.* FROM courses c 
                              JOIN enrollments e ON c.id = e.course_id 
                              WHERE e.student_id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $courses = [];
    
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    
    echo json_encode(['success' => true, 'courses' => $courses]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new course (teacher/admin only)
    if ($_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    $title = $data['title'];
    $code = $data['code'];
    $description = $data['description'];
    $teacher_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("INSERT INTO courses (title, code, description, teacher_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $code, $description, $teacher_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'course_id' => $stmt->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

$conn->close();
?>