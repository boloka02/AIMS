<?php
include '../db_connection.php'; // Ensure correct DB connection

header('Content-Type: application/json');

$query = "SELECT status, COUNT(*) as count FROM ticket GROUP BY status";
$result = $conn->query($query);

if (!$result) {
    echo json_encode(["error" => $conn->error]);
    exit();
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['status']] = (int) $row['count']; // Ensure integer values
}

echo json_encode($data);
?>
