<?php
$conn = mysqli_connect("localhost", "root", "");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS ANONCES";
if (mysqli_query($conn, $sql)) {
    
} else {
    echo "Error creating database: " . mysqli_error($conn);
}

// Select the database
mysqli_select_db($conn, "ANONCES");

$sql2 = "CREATE TABLE IF NOT EXISTS pubs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    description TEXT,
    price DECIMAL(10, 2),
    imgname VARCHAR(255)
)";
if (mysqli_query($conn, $sql2)) {
    
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
?>
