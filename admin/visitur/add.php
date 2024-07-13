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
$visitor_name = $visit_start_date = $visit_end_date = $username = $password = "";
$visitor_name_err = $visit_start_date_err = $visit_end_date_err = $username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate visitor name
    if (empty(trim($_POST["visitor_name"]))) {
        $visitor_name_err = "Please enter visitor name.";
    } else {
        $visitor_name = trim($_POST["visitor_name"]);
    }

    // Validate visit start date
    if (empty(trim($_POST["visit_start_date"]))) {
        $visit_start_date_err = "Please enter visit start date.";
    } else {
        $visit_start_date = trim($_POST["visit_start_date"]);
    }

    // Validate visit end date
    if (empty(trim($_POST["visit_end_date"]))) {
        $visit_end_date_err = "Please enter visit end date.";
    } else {
        $visit_end_date = trim($_POST["visit_end_date"]);
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

    // Check input errors before inserting into database
    if (empty($visitor_name_err) && empty($visit_start_date_err) && empty($visit_end_date_err) && empty($username_err) && empty($password_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO Visitor (visitor_name, visit_start_date, visit_end_date, username, password) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss", $param_visitor_name, $param_visit_start_date, $param_visit_end_date, $param_username, $param_password);

            // Set parameters
            $param_visitor_name = $visitor_name;
            $param_visit_start_date = $visit_start_date;
            $param_visit_end_date = $visit_end_date;
            $param_username = $username;
            $param_password = $password;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to the list of visitors page after adding
                header("location: show.php");
                exit;
            } else {
                echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Visitor</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Add Visitor</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="mb-3">
            <label for="visitor_name" class="form-label">Visitor Name:</label>
            <input type="text" class="form-control" id="visitor_name" name="visitor_name" value="<?php echo $visitor_name; ?>" required>
            <span class="text-danger"><?php echo $visitor_name_err; ?></span>
        </div>
        <div class="mb-3">
            <label for="visit_start_date" class="form-label">Visit Start Date:</label>
            <input type="date" class="form-control" id="visit_start_date" name="visit_start_date" value="<?php echo $visit_start_date; ?>" required>
            <span class="text-danger"><?php echo $visit_start_date_err; ?></span>
        </div>
        <div class="mb-3">
            <label for="visit_end_date" class="form-label">Visit End Date:</label>
            <input type="date" class="form-control" id="visit_end_date" name="visit_end_date" value="<?php echo $visit_end_date; ?>" required>
            <span class="text-danger"><?php echo $visit_end_date_err; ?></span>
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
