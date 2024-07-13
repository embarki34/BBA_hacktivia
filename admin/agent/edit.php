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
$agent_id = $agent_username = $agent_password = "";
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

    // Check input errors before updating the database
    if (empty($agent_username_err) && empty($agent_password_err)) {
        // Prepare an update statement
        $sql = "UPDATE Agent SET username=?, password=? WHERE agent_id=?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssi", $param_agent_username, $param_agent_password, $param_agent_id);

            // Set parameters
            $param_agent_username = $agent_username;
            $param_agent_password = password_hash($agent_password, PASSWORD_DEFAULT); // Hash the password
            $param_agent_id = $_GET["id"];

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to the list of agents page after updating
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
} else {
    // Retrieve agent details from database
    $agent_id = $_GET["id"];
    $sql = "SELECT username FROM Agent WHERE agent_id=?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind the agent id as a parameter
        $stmt->bind_param("i", $param_agent_id);

        // Set parameter values
        $param_agent_id = $agent_id;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();

            // Check if agent exists
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($agent_username);

                // Fetch agent details
                if ($stmt->fetch()) {
                    // Username retrieved successfully
                }
            } else {
                // Agent not found, redirect to error page
                header("location: error.php");
                exit;
            }
        } else {
            echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
        }

        // Close statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agent</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Edit Agent</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $agent_id; ?>">
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
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
