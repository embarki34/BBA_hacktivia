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
$employee_name = $username = $password = "";
$employee_name_err = $username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate employee name
    if (empty(trim($_POST["employee_name"]))) {
        $employee_name_err = "Please enter employee name.";
    } else {
        $employee_name = trim($_POST["employee_name"]);
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
    if (empty($employee_name_err) && empty($username_err) && empty($password_err)) {
        // Prepare an update statement
        $sql = "UPDATE Employee SET employee_name = ?, username = ?, password = ? WHERE employee_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssi", $param_employee_name, $param_username, $param_password, $param_employee_id);

            // Set parameters
            $param_employee_name = $employee_name;
            $param_username = $username;
            $param_password = $password;
            $param_employee_id = $_GET["id"];

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to the employees list page
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
    // Retrieve employee information based on GET parameter
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Prepare a select statement
        $sql = "SELECT * FROM Employee WHERE employee_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_employee_id);

            // Set parameters
            $param_employee_id = trim($_GET["id"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    // Fetch the employee record
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    // Retrieve individual field values
                    $employee_name = $row["employee_name"];
                    $username = $row["username"];
                    $password = $row["password"];
                } else {
                    // Employee ID not found, redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
            }

            // Close statement
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Edit Employee</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET["id"]); ?>">
        <div class="mb-3">
            <label for="employee_name" class="form-label">Employee Name:</label>
            <input type="text" class="form-control" id="employee_name" name="employee_name" value="<?php echo $employee_name; ?>" required>
            <span class="text-danger"><?php echo $employee_name_err; ?></span>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
            <span class="text-danger"><?php echo $username_err; ?></span>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>" required>
            <span class="text-danger"><?php echo $password_err; ?></span>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
