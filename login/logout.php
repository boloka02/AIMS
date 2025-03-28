<?php
session_start();

// Unset only the session variables of the current user
$_SESSION = []; // Clear session variables
session_unset(); // Unset session data

// Destroy the session only for the current user
session_destroy();

// Redirect to login page
header("Location: ../login/login.php");
exit();
?>
