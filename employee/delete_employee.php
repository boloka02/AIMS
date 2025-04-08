<?php
include '../db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to delete the employee
    $query = "DELETE FROM employee WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "Employee deleted successfully.";
    } else {
        echo "Error deleting employee: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
