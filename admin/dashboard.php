<?php
// Include database connection
require_once('../db.php');

// Initialize the session
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Define function to fetch statistics for each table
function fetchTableStatistics($conn, $tableName) {
    $sql = "SELECT COUNT(*) as total FROM $tableName";
    $result = $conn->query($sql);
    $count = $result->fetch_assoc()['total'];
    echo "<li class='list-group-item'>$tableName Count: $count</li>";
}

// Function to fetch total count of all records
function fetchTotalCount($conn) {
    $total = 0;
    $tables = ['Student', 'Agent', 'Visitor', 'Employee'];
    foreach ($tables as $table) {
        $sql = "SELECT COUNT(*) as total FROM $table";
        $result = $conn->query($sql);
        $count = $result->fetch_assoc()['total'];
        $total += $count;
    }
    echo "<li class='list-group-item'>Total Records: $total</li>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="../admin/dashboard.php">School Management</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="../admin/student/show.php">Student</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../admin/agent/show.php">Agent</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../admin/visitur/show.php">Visitor</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../admin/employee/show.php">Employee</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
    <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
    <h3>Database Statistics:</h3>
    <ul class="list-group">
        <?php
        // Fetch statistics for each table
        $tables = ['Student', 'Agent', 'Visitor', 'Employee'];
        foreach ($tables as $table) {
            fetchTableStatistics($conn, $table);
        }

        // Fetch total count of all records
        fetchTotalCount($conn);
        ?>
    </ul>
    <br>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
