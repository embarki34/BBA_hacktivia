<?php
// Include database connection
require_once('../db.php');
session_start();

// Check if the agent is logged in, if not, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard</title>
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
        }

        .qr-scanner {
            text-align: center;
            margin-bottom: 30px;
        }

        #scan-result {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        #preview {
            width: 100%;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Welcome,
            <?php echo $_SESSION["username"]; ?>!
        </h2>

        <div class="form-group">
            <label for="userType">Select User Type:</label>
            <select class="form-control" id="userType">
                <option value="student">Student</option>
                <option value="employee">Employee</option>
                <option value="visitor">Visitor</option>
            </select>
        </div>

        <div class="qr-scanner">
            <h3>Scan QR Code:</h3>
            <video id="preview"></video>
            <div id="scan-result"></div>
        </div>

    </div>

    <!-- JavaScript for QR code scanner and AJAX request -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview'),
            mirror: false
        });

        scanner.addListener('scan', function (content) {
            let userType = document.getElementById("userType").value;
            let data = { userType: userType, qrCode: content };

            // Make an AJAX request to search.php with userType and qrCode
            $.ajax({
                type: "POST",
                url: "search.php",
                data: data,
                success: function (response) {
                    document.getElementById("scan-result").innerText = response;
                },
                error: function () {
                    alert("Error occurred while processing the request.");
                }
            });
        });

        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[cameras.length - 1]); // Use the last available camera (likely the rear camera)
            } else {
                console.error('No cameras found.');
            }
        }).catch(function (error) {
            console.error(error);
        });
    </script>

</body>

</html>