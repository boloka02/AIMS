<?php
include '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $type = $_POST['type'] ?? null;

    // Validate input
    if (!$id || !$type) {
        echo json_encode(["success" => false, "message" => "Invalid request."]);
        exit;
    }

    // Allowed asset types
    $allowedTypes = ['mboard', 'keyboard', 'mouse', 'monitor', 'monitor2', 'webcam', 'headset', 'processor', 'ram', 'laptop',`biometric`, 'patch_cord', 'printer', 'router', 'switch', 'avr', 'adaptor', 'cctv', 'ups', 'modem'];
    if (!in_array($type, $allowedTypes)) {
        echo json_encode(["success" => false, "message" => "Invalid asset type."]);
        exit;
    }

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM `$type` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();

    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Deletion failed."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
