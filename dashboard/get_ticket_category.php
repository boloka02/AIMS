<?php
include '../db_connection.php';
header('Content-Type: application/json');

$month = $_GET['month'] ?? null;

if ($month) {
    $query = "
        SELECT category, COUNT(*) as count 
        FROM ticket 
        WHERE DATE_FORMAT(STR_TO_DATE(date_created, '%Y/%c/%e'), '%Y-%m') = ?
        GROUP BY category
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $month);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT category, COUNT(*) as count FROM ticket GROUP BY category");
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['category']] = (int) $row['count'];
}

echo json_encode($data);
?>
