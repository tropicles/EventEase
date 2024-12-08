<?php
require('inc/db_config.php');
if (isset($_POST['rem_feature'])) {
    $id = intval($_POST['rem_feature']); // Ensure the ID is an integer

    // Prepare the DELETE statement
    $stmt = $con->prepare("DELETE FROM features WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        // Check the number of affected rows
        if ($stmt->affected_rows > 0) {
            echo 1; // Success
        } else {
            echo 0; // No rows affected (ID may not exist)
        }
    } else {
        echo 0; // Failure
    }

    $stmt->close();
}

$con->close();
