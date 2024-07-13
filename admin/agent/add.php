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
$agent_username = $agent_password = "";
$agent_username_err = $agent_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate agent username
    if (empty(trim($_POST["agent_username"]))) {
        $agent_username_err = "Please enter agent username.";
    } else {
        $agent_username = trim($_POST["agent_username"]);
    }

    // Validate agent password
    if (empty(trim($_POST["agent_password"]))) {
        $agent_password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["agent_password"])) < 6) {
        $agent_password_err = "Password must have at least 6 characters.";
    } else {
        $agent_password = trim($_POST["agent_password"]);
    }

    // Check input errors before inserting into database
    if (empty($agent_username_err) && empty($agent_password_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO Agent (username, password) VALUES (?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_agent_username, $param_agent_password);

            // Set parameters
            $param_agent_username = $agent_username;
            $param_agent_password = $agent_password; // Store the password as plain text

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to the list of agents page after adding
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
    <title>Add Agent</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Add Agent</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="mb-3">
            <label for="agent_username" class="form-label">Agent Username:</label>
            <input type="text" class="form-control <?php echo (!empty($agent_username_err)) ? 'is-invalid' : ''; ?>" id="agent_username" name="agent_username" value="<?php echo $agent_username; ?>" required>
            <div class="invalid-feedback"><?php echo $agent_username_err; ?></div>
        </div>
        <div class="mb-3">
            <label for="agent_password" class="form-label">Password:</label>
            <input type="password" class="form-control <?php echo (!empty($agent_password_err)) ? 'is-invalid' : ''; ?>" id="agent_password" name="agent_password" required>
            <div class="invalid-feedback"><?php echo $agent_password_err; ?></div>
        </div>
        <button type="submit" class="btn btn-primary">Add Agent</button>
    </form>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
