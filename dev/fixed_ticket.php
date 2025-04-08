<?php
require '../db_connection.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ticket_id'])) {
    $ticket_id = $_POST['ticket_id'];
    
    // Fetch ticket details before updating
    $query = "SELECT subject, accept FROM ticket WHERE ticket_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $ticket_id);
    $stmt->execute();
    $stmt->bind_result($subject, $accept);
    $stmt->fetch();
    $stmt->close();

    if ($subject) {
        // Update ticket status to 'Done' and process to 'Solved'
        $updateQuery = "UPDATE ticket SET status = 'Done', process = 'Fixed' WHERE ticket_number = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("s", $ticket_id);
        $stmt->execute();
        $stmt->close();

        // Insert into history table
        $current_date = date("Y-m-d H:i:s"); // Get current timestamp
        $insertHistoryQuery = "INSERT INTO history (subject, accept, date_fixed) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertHistoryQuery);
        $stmt->bind_param("sss", $subject, $accept, $current_date);
        $stmt->execute();
        $stmt->close();

        // Redirect back to ticket list with success message
        header("Location: ../ui_employee/employee.php?success=Ticket marked as fixed");
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
