<?php
// Include database connection
require_once('../db.php');
session_start();

// Check if the user is already logged in, if yes, then redirect them to the agent dashboard
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: login.php");
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
        $agent_password_err = "Please enter your password.";
    } else {
        $agent_password = trim($_POST["agent_password"]);
    }

    // Check input errors before querying the database
    if (empty($agent_username_err) && empty($agent_password_err)) {
        // Prepare a select statement
        $sql = "SELECT agent_id, username, password FROM agent WHERE 1";

        if ($result = $conn->query($sql)) {
            if ($result->num_rows > 0) {
                // Fetch result row
                while ($row = $result->fetch_assoc()) {
                    if ($row["username"] === $agent_username && $row["password"] === $agent_password) {
                        // Password is correct, start a new session
                        session_start();

                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["agent_id"] = $row["agent_id"];
                        $_SESSION["username"] = $row["username"];

                        // Redirect user to the agent dashboard page
                        header("location: index.php");
                        exit;
                    } else {
                        // Display an error message if username or password is not valid
                        $agent_username_err = "Invalid username or password.";
                    }
                }
            } else {
                // Display an error message if no agents found
                $agent_username_err = "No agents found.";
            }

            // Free result set
            $result->free();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
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
    <title>Agent Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Agent Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="mb-3">
            <label for="agent_username" class="form-label">Username:</label>
            <input type="text" class="form-control <?php echo (!empty($agent_username_err)) ? 'is-invalid' : ''; ?>" id="agent_username" name="agent_username" value="<?php echo $agent_username; ?>">
            <div class="invalid-feedback"><?php echo $agent_username_err; ?></div>
        </div>
        <div class="mb-3">
            <label for="agent_password" class="form-label">Password:</label>
            <input type="password" class="form-control <?php echo (!empty($agent_password_err)) ? 'is-invalid' : ''; ?>" id="agent_password" name="agent_password">
            <div class="invalid-feedback"><?php echo $agent_password_err; ?></div>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
