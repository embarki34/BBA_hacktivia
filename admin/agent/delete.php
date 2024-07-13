<?php
// Include database connection
require_once('../../db.php');
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
// Check if agent ID is provided in the URL
if(isset($_GET['id'])) {
    $agent_id = $_GET['id'];

    // Fetch agent details from the database
    $sql = "SELECT * FROM Agent WHERE agent_id = $agent_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Delete the agent from the database
        $delete_sql = "DELETE FROM Agent WHERE agent_id = $agent_id";
        if ($conn->query($delete_sql) === TRUE) {
            echo "<script>alert('Agent deleted successfully');</script>";
            // Redirect back to the list of agents page after deletion
            header("Location: show.php");
            exit;
        } else {
            echo "<script>alert('Error deleting agent: " . $conn->error . "');</script>";
        }
    } else {
        echo "Agent not found.";
        exit;
    }
} else {
    echo "Agent ID not provided.";
    exit;
}
?>
