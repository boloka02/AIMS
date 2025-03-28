<?php
session_start();
include '../db_connection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if user exists in the database
    $query = "SELECT * FROM user WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name']; // Store name in session

        // Redirect based on user role
        if ($user['role'] == 'Admin') {
            $redirect_url = "../dashboard/dashboard.php";
        } elseif ($user['role'] == 'Employee') {
            $redirect_url = "../ui_employee/employee.php";
        } else {
            $redirect_url = "../login/login.php"; // Fallback for unknown roles
        }

        echo "<script>
                alert('Welcome, " . $user['name'] . "!');
                window.location='$redirect_url';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Invalid email or password.');
                window.location='../login/login.php';
              </script>";
    }
}
?>
