<?php
$host = "34.101.76.100";  // MySQL server IP
$username = "root";       // MySQL username
$password = "T0pSecret@2025!";  // MySQL password
$database = "db_ams";     // The database you're using

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connection successful!";
}
?>
