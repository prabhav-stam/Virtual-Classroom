<?php
$conn = new mysqli("localhost", "root", "");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected to MySQL server successfully!";

$result = $conn->query("SHOW DATABASES LIKE 'virtual_classroom'");
if ($result->num_rows > 0) {
    echo "<br>Database exists!";
} else {
    echo "<br>Database does NOT exist!";
}
$conn->close();
?>