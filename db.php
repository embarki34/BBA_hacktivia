<?php
// Database configuration
$dbHost = 'localhost'; // Change this if your database server is on a different host
$dbUsername = 'root'; // Replace with your MySQL username
$dbPassword = ''; // Replace with your MySQL password
$dbName = 'codemaster'; // Replace with your database name

// Create database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    
}

// Set charset to UTF-8
$conn->set_charset("utf8");

// Optionally, you can also start a session if needed:
// session_start();
?>
