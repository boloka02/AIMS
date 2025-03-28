<?php
session_start();
include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ticket_id"])) {
    $ticketId = $_POST["ticket_id"];

    // Ensure the user is logged in
    if (!isset($_SESSION["name"])) {
        echo json_encode(["success" => false, "message" => "User not logged in."]);
        exit();
    }

    $onHoldBy = $_SESSION["name"]; // Get the logged-in user's name

    // Update the ticket table: mark process as "On Hold" and status as "Waiting"
    $query = "UPDATE ticket SET accept = ?, process = 'On Hold', status = 'Waiting' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $onHoldBy, $ticketId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Ticket placed on hold successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
