<?php
include '../db_connection.php'; // Ensure correct DB connection

header('Content-Type: application/json');

$query = "SELECT category, COUNT(*) as count FROM ticket GROUP BY category";
$result = $conn->query($query);

if (!$result) {
    echo json_encode(["error" => $conn->error]);
    exit();
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['category']] = (int) $row['count']; // Ensure integer values
}

echo json_encode($data);
?>
