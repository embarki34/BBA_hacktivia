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
        // Prepare a select statement to check if the username already exists
        $sql = "SELECT student_id FROM Student WHERE username = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before inserting into database
    if (empty($student_name_err) && empty($username_err) && empty($password_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO Student (student_name, username, password) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_student_name, $param_username, $param_password);

            // Set parameters
            $param_student_name = $student_name;
            $param_username = $username;
            $param_password = $password;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to the list of students page after adding
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
<?php
// Include database connection
require_once('../../db.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $student_name = $_POST['student_name'];

    // Sanitize input data
    $student_name = mysqli_real_escape_string($conn, $student_name);

    // Insert into database
    $sql = "INSERT INTO Student (student_name) VALUES ('$student_name')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Student added successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Add Student</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="mb-3">
            <label for="student_name" class="form-label">Student Name:</label>
            <input type="text" class="form-control" id="student_name" name="student_name" value="<?php echo $student_name; ?>" required>
            <span class="text-danger"><?php echo $student_name_err; ?></span>
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
