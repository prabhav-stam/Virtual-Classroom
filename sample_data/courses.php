<?php
// Sample courses
$conn->query("INSERT INTO courses (title, code, description, teacher_id) VALUES 
    ('Mathematics 101', 'MATH101', 'Introduction to Calculus', 1),
    ('Computer Science', 'CS201', 'Data Structures and Algorithms', 2),
    ('English Literature', 'ENG105', 'Classic British Literature', 1)");

// Sample enrollments
$conn->query("INSERT INTO enrollments (student_id, course_id) VALUES 
    (3, 1), (3, 2), (4, 1), (4, 3), (5, 2), (5, 3)");
?>