<?php
include 'auth.php';
include 'config.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'] ?? '';
    $userId = $_SESSION['user_id'];
    $name = $_SESSION['name'];
    
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO chat_messages (user_id, name, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $name, $message);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send message']);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get chat messages
    $stmt = $conn->prepare("SELECT name, message, created_at FROM chat_messages ORDER BY created_at ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = [];
    
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    
    echo json_encode(['success' => true, 'messages' => $messages]);
}
?>