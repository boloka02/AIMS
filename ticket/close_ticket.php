<?php
include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $ticketId = mysqli_real_escape_string($conn, $_POST["id"]);

    $query = "UPDATE ticket SET status='Close', process='Close' WHERE id='$ticketId'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    }

    mysqli_close($conn);
}
?>
