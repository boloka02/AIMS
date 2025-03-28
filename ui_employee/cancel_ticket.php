<?php
include '../db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ticket_id'])) {
    $ticket_id = $_POST['ticket_id'];

    // Prepare the SQL statement to delete the specific ticket
    $stmt = $conn->prepare("DELETE FROM ticket WHERE ticket_number = ?");
    $stmt->bind_param("s", $ticket_id);

    if ($stmt->execute()) {
        echo "<script> window.location.href='../ui_employee/employee.php';</script>";
    } else {
        echo "<script> window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
