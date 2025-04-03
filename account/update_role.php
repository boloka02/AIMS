<?php
include '../db_connection.php';

if (isset($_POST['id']) && isset($_POST['role'])) {
    $id = $_POST['id'];
    $role = $_POST['role'];

    // Escape variables to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $id);
    $role = mysqli_real_escape_string($conn, $role);

    // Prepare the SQL query
    $query = "UPDATE user SET role = '$role' WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "Role updated successfully";
    } else {
        // Log the error to the error log
        error_log("Error updating role: " . mysqli_error($conn));
        echo "Error updating role: " . mysqli_error($conn); // Display error message for debugging
    }

    mysqli_close($conn);
} else {
    echo "Invalid request";
}
?>
