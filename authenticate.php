<?php
// authenticate.php
header('Content-Type: application/json');

// Dummy credentials for demonstration
$valid_users = [
    "admin" => "admin", // Username => Password
];

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (isset($valid_users[$username]) && $valid_users[$username] == $password) {
    echo json_encode(['success' => true, 'message' => 'Authentication successful']);
} else {
    echo json_encode(['success' => false, 'message' => 'Authentication failed']);
}