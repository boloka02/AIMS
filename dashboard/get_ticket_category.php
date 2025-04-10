<?php
include '../db_connection.php';
header('Content-Type: application/json');

$date = $_GET['date'] ?? null;

if ($date) {
    $query = "SELECT category, COUNT(*) as count FROM ticket WHERE DATE(date_created) = ? GROUP BY category";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Fallback: return all data if no date is provided
    $result = $conn->query("SELECT category, COUNT(*) as count FROM ticket GROUP BY category");
}

if (!$result) {
    echo json_encode(["error" => $conn->error]);
    exit();
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['category']] = (int) $row['count'];
}

echo json_encode($data);
?>
