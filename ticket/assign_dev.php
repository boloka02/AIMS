<?php
include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $devSupport = mysqli_real_escape_string($conn, $_POST['dev_support']);
    $ticketNumber = mysqli_real_escape_string($conn, $_POST['ticket_number']);

    if (!empty($devSupport) && !empty($ticketNumber)) {
        // Find the employee with the matching name
        $query = "SELECT id FROM employee WHERE name = '$devSupport' LIMIT 1";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $employee = mysqli_fetch_assoc($result);
            $employeeId = $employee['id'];

            // ✅ Use backticks around `dev-ticket`
            $updateEmployeeQuery = "UPDATE employee SET `dev-ticket` = '$ticketNumber' WHERE id = '$employeeId'";
            
            // ✅ Update the ticket table: assign_to column
            $updateTicketQuery = "UPDATE ticket SET assign_to = '$devSupport' WHERE ticket_number = '$ticketNumber'";

            if (mysqli_query($conn, $updateEmployeeQuery) && mysqli_query($conn, $updateTicketQuery)) {
                echo "<script>alert('Ticket assigned successfully!'); window.location.href = '../ticket/tickets.php';</script>";
            } else {
                echo "<script>alert('Failed to assign ticket.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Employee not found!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Invalid input. Please try again.'); window.history.back();</script>";
    }
}

mysqli_close($conn);
?>
