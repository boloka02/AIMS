<?php
session_start();
include '../db_connection.php';

// Set timezone to Philippines (UTC+8)
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ticket_id"])) {
    $ticketId = $_POST["ticket_id"];

    // Ensure the user is logged in
    if (!isset($_SESSION["name"])) {
        echo json_encode(["success" => false, "message" => "User not logged in."]);
        exit();
    }

    $acceptedBy = $_SESSION["name"]; // Get the logged-in user's name
    $acceptTime = date("Y-m-d H:i:s"); // Get current timestamp in Philippine time

    // Start transaction
    $conn->begin_transaction();

    try {
        // Retrieve the ticket_number of the ticket
        $query = "SELECT ticket_number FROM ticket WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $ticketId);
        $stmt->execute();
        $result = $stmt->get_result();
        $ticket = $result->fetch_assoc();
        $stmt->close();

        if (!$ticket) {
            throw new Exception("Ticket not found.");
        }

        $ticketNumber = $ticket["ticket_number"];

        // Update the ticket table: mark as accepted, set process to "Accepted", status to "In Progress"
        $query = "UPDATE ticket SET accept = ?, process = 'Accepted', status = 'In Progress' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $acceptedBy, $ticketId);
        if (!$stmt->execute()) {
            throw new Exception("Error updating ticket.");
        }
        $stmt->close();

        // Insert into history table with accept time
        $insertHistoryQuery = "INSERT INTO history (ticket_number, accept, date_accept, accept_time) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertHistoryQuery);
        $stmt->bind_param("ssss", $ticketNumber, $acceptedBy, $acceptTime, $acceptTime);
        if (!$stmt->execute()) {
            throw new Exception("Error inserting into history.");
        }
        $stmt->close();

        // Commit transaction
        $conn->commit();

        echo json_encode(["success" => true, "message" => "Ticket accepted and history updated with accept time."]);

    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction on failure
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }

    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
