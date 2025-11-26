<?php
// Sample teachers
$conn->query("INSERT INTO users (name, email, password, role) VALUES 
    ('Professor Smith', 'smith@university.edu', '$2y$10$EXAMPLEHASH', 'teacher'),
    ('Dr. Johnson', 'johnson@university.edu', '$2y$10$EXAMPLEHASH', 'teacher')");

// Sample students
$conn->query("INSERT INTO users (name, email, password) VALUES 
    ('Alice Brown', 'alice@student.edu', '$2y$10$EXAMPLEHASH'),
    ('Bob Wilson', 'bob@student.edu', '$2y$10$EXAMPLEHASH'),
    ('Charlie Davis', 'charlie@student.edu', '$2y$10$EXAMPLEHASH')");
?>  