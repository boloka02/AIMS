<?php
$host = "localhost";
$username = "root";  // Default for XAMPP
$password = "";      // Default for XAMPP
$database = "db_ams";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
