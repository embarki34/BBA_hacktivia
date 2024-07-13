<?php
// Include database connection
require_once('../db.php');

// Initialize the session
session_start();

// Check if the student is already logged in, if not, then redirect them to the student login page
if (!isset($_SESSION["student_loggedin"]) || $_SESSION["student_loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Logout logic
if(isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: relative; /* Make the container a reference for absolute positioning */
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .student-id {
            background-color: #f2f2f2;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 30px;
        }
        #qrcode {
            text-align: center;
        }
        /* Center the logout button */
        .logout-btn {
            position: absolute;
            left: 50%;
            bottom: 20px;
            transform: translateX(-50%);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
    <div class="student-id">
        <h3>Your Student ID:</h3>
        <p><?php echo $_SESSION["student_id"]; ?></p>
    </div>

    <h3>QR Code for Your Student ID:</h3>
    <div id="qrcode"></div>
        <br>
        <br>
        <br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="logout-btn">
        <button type="submit" name="logout" class="btn btn-danger">Logout</button>
    </form>
</div>

<!-- JavaScript to generate QR code -->
<script>
    // Generate QR code for student ID
    var studentId = "<?php echo $_SESSION["student_id"]; ?>";
    var qrcodeDiv = document.getElementById("qrcode");

    // Create QR code element
    var qrCode = document.createElement("img");
    qrCode.src = "https://api.qrserver.com/v1/create-qr-code/?data=" + encodeURIComponent(studentId) + "&size=200x200";

    // Append QR code to the container
    qrcodeDiv.appendChild(qrCode);
</script>

</body>
</html>
