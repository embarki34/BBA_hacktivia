<?php
// Include database connection
require_once('../../db.php');
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
// Function to retrieve and display visitors
function displayVisitors() {
    global $conn;
    $visitors = $conn->query("SELECT visitor_id, visitor_name, visit_start_date, visit_end_date, username, password FROM Visitor");
    if ($visitors->num_rows > 0) {
        echo "<table class='table'>";
        echo "<thead><tr><th>ID</th><th>Name</th><th>Visit Start Date</th><th>Visit End Date</th><th>Username</th><th>Password</th><th>Actions</th></tr></thead>";
        echo "<tbody>";
        while ($row = $visitors->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['visitor_id'] . "</td>";
            echo "<td>" . $row['visitor_name'] . "</td>";
            echo "<td>" . $row['visit_start_date'] . "</td>";
            echo "<td>" . $row['visit_end_date'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['password'] . "</td>";
            echo "<td>";
            echo "<a href='edit.php?id=" . $row['visitor_id'] . "' class='btn btn-primary'>Edit</a>";
            echo "<a href='delete.php?id=" . $row['visitor_id'] . "' class='btn btn-danger'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No visitors found.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Visitors</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="../dashboard.php">School Management</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="../../admin/student/show.php">Student</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../../admin/agent/show.php">Agent</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../../admin/visitur/show.php">Visitor</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../../admin/employee/show.php">Employee</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h2>List of Visitors</h2>
    <a href="../../admin/visitur/add.php" class="btn btn-primary mb-3">Add Visitor</a>
    <?php
    // Display visitors
    displayVisitors();
    ?>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
