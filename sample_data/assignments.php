<?php
// Sample assignments
$conn->query("INSERT INTO assignments (course_id, title, description, due_date, max_score) VALUES 
    (1, 'Calculus Homework 1', 'Basic differentiation problems', NOW() + INTERVAL 7 DAY, 100),
    (1, 'Midterm Exam', 'Covers chapters 1-5', NOW() + INTERVAL 14 DAY, 200),
    (2, 'Programming Project', 'Implement a linked list', NOW() + INTERVAL 10 DAY, 150),
    (3, 'Essay Assignment', '500-word essay on Shakespeare', NOW() + INTERVAL 5 DAY, 80)");

// Sample submissions
$conn->query("INSERT INTO submissions (assignment_id, student_id, grade, feedback) VALUES 
    (1, 3, 85, 'Good work, but show more steps next time'),
    (3, 5, 92, 'Excellent implementation')");
?>