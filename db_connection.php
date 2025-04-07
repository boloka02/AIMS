<?php
$host = "192.168.4.55";
$username = "root";
$password = "T0pSecret@2025!";
$database = "db_ams";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connection successful!";
}
?>
