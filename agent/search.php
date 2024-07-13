<?php

require_once('../db.php');

if (isset($_POST['userType']) && isset($_POST['qrCode'])) {
    $userType = $_POST['userType'];
    $qrCode = $_POST['qrCode'];

    $id = substr($qrCode, 0, 8); $tableName = '';
    $fieldName = '';

    switch ($userType) {
        case 'student':
            $tableName = 'student';
            $fieldName = 'student_id';
            break;
        case 'employee':
            $tableName = 'employee';
            $fieldName = 'employee_id';
            break;
        case 'visitor':
            $tableName = 'visitor';
            $fieldName = 'visitor_id';
            break;
        default:
            echo "Invalid user type.";
            exit;
    }

    $sql = "SELECT * FROM $tableName WHERE $fieldName = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "found";
        } else {
            echo "not found";
        }
        
        

        $stmt->close();
    } else {
        echo "Error occurred while preparing the SQL statement.";
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>
