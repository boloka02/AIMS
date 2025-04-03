<?php
require '../db_connection.php'; // Include your database connection file

// Set timezone to Philippines (UTC+8)
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ticket_id'])) {
    $ticket_id = $_POST['ticket_id'];

    // Get the current date and time in Philippine time
    $fix_time = date("H:i:s"); // Fix time
    $date_fixed = date("Y-m-d"); // Fix date

    // Fetch ticket details before updating
    $query = "SELECT ticket_number, accept FROM ticket WHERE ticket_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $ticket_id);
    $stmt->execute();
    $stmt->bind_result($ticket_number, $accept);
    $stmt->fetch();
    $stmt->close();

    if ($ticket_number) {
        // Update ticket status to 'Done' and process to 'Fixed'
        $updateQuery = "UPDATE ticket SET status = 'Resolved', process = 'Fixed' WHERE ticket_number = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("s", $ticket_id);
        $stmt->execute();
        $stmt->close();

        // **Update existing history record to add fix_time and date_fixed**
        $updateHistoryQuery = "UPDATE history 
                               SET fix_time = ?, date_fixed = ? 
                               WHERE ticket_number = ? AND accept = ?";
        $stmt = $conn->prepare($updateHistoryQuery);
        $stmt->bind_param("ssss", $fix_time, $date_fixed, $ticket_number, $accept);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to update history. No matching record found.");
        }

        $stmt->close();

        // Redirect back to ticket list with success message
        header("Location: ../ui_employee/employee.php?success=Ticket marked as fixed on $date_fixed at $fix_time");
        exit();
    } else {
        // Redirect with error if ticket not found
        header("Location: ../ui_employee/employee.php?error=Ticket not found");
        exit();
    }
} else {
    header("Location: ../ui_employee/employee.php?error=Invalid request");
    exit();
}
?>
