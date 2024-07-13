<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Students</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- QR Code Generator Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
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
  <div class="row mb-3">
    <div class="col">
      <h2>List of Students</h2>
    </div>
    <div class="col text-end">
      <a href="../../admin/student/add.php" class="btn btn-primary">Add Student</a>
    </div>
  </div>
  <?php
  // Include database connection
  require_once('../../db.php');

  // Function to retrieve and display students
  function displayStudents() {
      global $conn;
      $students = $conn->query("SELECT * FROM Student");
      if ($students->num_rows > 0) {
          echo "<table class='table'>";
          echo "<thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>";
          echo "<tbody>";
          while ($row = $students->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['student_id'] . "</td>";
              echo "<td>" . $row['student_name'] . "</td>";
              echo "<td class='table-buttons'>";
              echo "<a href='edit.php?id=" . $row['student_id'] . "' class='btn btn-primary'>Edit</a>";
              echo "<a href='delete.php?id=" . $row['student_id'] . "' class='btn btn-danger'>Delete</a>";
              echo "<button class='btn btn-secondary' onclick='showQRCode(\"" . $row['student_id'] . "\", \"" . $row['student_name'] . "\")'>Show QR Code</button>";
              echo "</td>";
              echo "</tr>";
          }
          echo "</tbody>";
          echo "</table>";
      } else {
          echo "No students found.";
      }
  }

  // Display students
  displayStudents();
  ?>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showQRCode(studentId, studentName) {
        // Generate QR Code
        var qr = new QRious({
            value: 'Student ID: ' + studentId + ', Student Name: ' + studentName
        });

        // Show QR Code in Modal
        var modalContent = '<img src="' + qr.toDataURL() + '" alt="QR Code" class="img-fluid">';
        document.getElementById('qrModalBody').innerHTML = modalContent;
        var myModal = new bootstrap.Modal(document.getElementById('qrModal'));
        myModal.show();
    }
</script>

<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="qrModalLabel">QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="qrModalBody">
        <!-- QR code will be displayed here -->
      </div>
    </div>
  </div>
</div>

</body>
</html>
