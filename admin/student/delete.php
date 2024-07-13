<?php
// Include database connection
require_once('../../db.php');
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if student ID is set and not empty
    if (isset($_POST["student_id"]) && !empty($_POST["student_id"])) {
        // Prepare a delete statement
        $sql = "DELETE FROM Student WHERE student_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_student_id);

            // Set parameters
            $param_student_id = trim($_POST["student_id"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to the list of students page after deleting
                header("location: show.php");
                exit;
            } else {
                echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
            }

            // Close statement
            $stmt->close();
        }
    } else {
        // Redirect to error page if student ID is not specified or empty
        header("location: error.php");
        exit;
    }
}

// Check if student ID is set and valid
if (!isset($_GET["id"]) || empty(trim($_GET["id"]))) {
    // Redirect to error page if student ID is not specified or empty
    header("location: error.php");
    exit;
}

// Retrieve student details from database based on ID passed in URL
$sql = "SELECT student_name FROM Student WHERE student_id = ?";
if ($stmt = $conn->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("i", $param_student_id);

    // Set parameters
    $param_student_id = trim($_GET["id"]);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Store result
        $stmt->store_result();

        // Check if student ID exists
        if ($stmt->num_rows == 1) {
            // Bind result variables
            $stmt->bind_result($student_name);

            // Fetch one row
            $stmt->fetch();
        } else {
            // Redirect to error page if student ID doesn't exist
            header("location: error.php");
            exit;
        }
    } else {
        echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Delete Student</h2>
    <p>Are you sure you want to delete this student?</p>
    <form method="post">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" class="form-control" value="<?php echo $student_name; ?>" readonly>
        </div>
        <input type="hidden" name="student_id" value="<?php echo trim($_GET["id"]); ?>">
        <button type="submit" class="btn btn-danger">Yes, delete</button>
        <a href="show.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
