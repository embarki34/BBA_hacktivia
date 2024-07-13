<?php
// Include database connection
require_once('../../db.php');
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
// Define variables and initialize with empty values
$student_name = $username = $password = "";
$student_name_err = $username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate student name
    if (empty(trim($_POST["student_name"]))) {
        $student_name_err = "Please enter student name.";
    } else {
        $student_name = trim($_POST["student_name"]);
    }

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before updating the database
    if (empty($student_name_err) && empty($username_err) && empty($password_err)) {
        // Prepare an update statement
        $sql = "UPDATE Student SET student_name = ?, username = ?, password = ? WHERE student_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssi", $param_student_name, $param_username, $param_password, $param_student_id);

            // Set parameters
            $param_student_name = $student_name;
            $param_username = $username;
            $param_password = $password;
            $param_student_id = $_GET["id"]; // Student ID from URL

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to the list of students page after updating
                header("location: show.php");
                exit();
            } else {
                echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
} else {
    // Check existence of student ID parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $student_id = trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT student_name, username, password FROM Student WHERE student_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_student_id);

            // Set parameters
            $param_student_id = $student_id;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if student ID exists
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($student_name, $username, $password);

                    // Fetch one row
                    $stmt->fetch();
                } else {
                    // Redirect to error page if student ID doesn't exist
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
            }

            // Close statement
            $stmt->close();
        }
    } else {
        // Redirect to error page if student ID parameter is missing in the URL
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Edit Student</h2>
    <form method="post">
        <div class="mb-3">
            <label for="student_name" class="form-label">Student Name:</label>
            <input type="text" class="form-control" id="student_name" name="student_name" value="<?php echo $student_name; ?>" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="text" class="form-control" id="password" name="password" value="<?php echo $password; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="show.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
