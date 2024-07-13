<?php
// Include database connection
require_once('../../db.php');
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
// Check if visitor ID parameter exists
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Prepare a delete statement
    $sql = "DELETE FROM Visitor WHERE visitor_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_visitor_id);

        // Set parameters
        $param_visitor_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to the visitors list page
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
?>
