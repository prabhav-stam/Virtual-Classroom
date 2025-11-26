<?php
include 'config.php';

// SQL queries to create tables
$sql_queries = [
    "CREATE DATABASE IF NOT EXISTS virtual_classroom CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci",
    "USE virtual_classroom",
    
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('student','teacher','admin') NOT NULL DEFAULT 'student',
        profile_pic VARCHAR(255) DEFAULT 'profile-placeholder.png',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB",
    
    "CREATE TABLE IF NOT EXISTS courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        code VARCHAR(20) NOT NULL UNIQUE,
        description TEXT,
        teacher_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB",
    
    "CREATE TABLE IF NOT EXISTS enrollments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        course_id INT NOT NULL,
        enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
        UNIQUE KEY (student_id, course_id)
    ) ENGINE=InnoDB",
    
    "CREATE TABLE IF NOT EXISTS assignments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_id INT NOT NULL,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        due_date DATETIME NOT NULL,
        max_score INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    ) ENGINE=InnoDB",
    
    "CREATE TABLE IF NOT EXISTS submissions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        assignment_id INT NOT NULL,
        student_id INT NOT NULL,
        file_path VARCHAR(255),
        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        grade DECIMAL(5,2),
        feedback TEXT,
        FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE CASCADE,
        FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB",
    
    "CREATE TABLE IF NOT EXISTS announcements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_id INT NOT NULL,
        title VARCHAR(100) NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    ) ENGINE=InnoDB"
];

// Execute each query
foreach ($sql_queries as $query) {
    if ($conn->query($query) === TRUE) {
        echo "Query executed successfully: " . substr($query, 0, 50) . "...<br>";
    } else {
        echo "Error executing query: " . $conn->error . "<br>";
    }
}

// Insert sample data if tables are empty
function insert_sample_data($conn, $table, $data_sql) {
    $result = $conn->query("SELECT COUNT(*) as count FROM $table");
    if ($result && $result->fetch_assoc()['count'] == 0) {
        if ($conn->multi_query($data_sql)) {
            echo "Sample data inserted into $table<br>";
        } else {
            echo "Error inserting sample data into $table: " . $conn->error . "<br>";
        }
    }
}

// Sample data for users
insert_sample_data($conn, 'users', "
    INSERT INTO users (name, email, password, role) VALUES 
    ('Professor Smith', 'smith@university.edu', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher'),
    ('Dr. Johnson', 'johnson@university.edu', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher'),
    ('Alice Brown', 'alice@student.edu', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
    ('Bob Wilson', 'bob@student.edu', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
    ('Charlie Davis', 'charlie@student.edu', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student');
");

// Sample data for courses
insert_sample_data($conn, 'courses', "
    INSERT INTO courses (title, code, description, teacher_id) VALUES 
    ('Mathematics 101', 'MATH101', 'Introduction to Calculus', 1),
    ('Computer Science', 'CS201', 'Data Structures and Algorithms', 2),
    ('English Literature', 'ENG105', 'Classic British Literature', 1);
");

// Sample data for enrollments
insert_sample_data($conn, 'enrollments', "
    INSERT INTO enrollments (student_id, course_id) VALUES 
    (3, 1), (3, 2), (4, 1), (4, 3), (5, 2), (5, 3);
");

// Sample data for assignments
insert_sample_data($conn, 'assignments', "
    INSERT INTO assignments (course_id, title, description, due_date, max_score) VALUES 
    (1, 'Calculus Homework 1', 'Basic differentiation problems', NOW() + INTERVAL 7 DAY, 100),
    (1, 'Midterm Exam', 'Covers chapters 1-5', NOW() + INTERVAL 14 DAY, 200),
    (2, 'Programming Project', 'Implement a linked list', NOW() + INTERVAL 10 DAY, 150),
    (3, 'Essay Assignment', '500-word essay on Shakespeare', NOW() + INTERVAL 5 DAY, 80);
");

// Sample data for submissions
insert_sample_data($conn, 'submissions', "
    INSERT INTO submissions (assignment_id, student_id, grade, feedback) VALUES 
    (1, 3, 85, 'Good work, but show more steps next time'),
    (3, 5, 92, 'Excellent implementation');
");

echo "Database setup complete!";
$conn->close();
?>