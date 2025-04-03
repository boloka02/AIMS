<?php
include '../db_connection.php';

if (isset($_GET['id'])) {
    $employeeId = $_GET['id'];

    // Escape the employee ID to prevent SQL injection
    $employeeId = mysqli_real_escape_string($conn, $employeeId);

    // Prepare the SQL query to delete the employee
    $query = "DELETE FROM user WHERE id = '$employeeId'";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo "Employee deleted successfully";
    } else {
        // Log error to the error log for debugging
        error_log("Error deleting employee: " . mysqli_error($conn));
        echo "Error deleting employee: " . mysqli_error($conn); // Show error for debugging
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo "Invalid request. Employee ID is missing.";
}
?>
