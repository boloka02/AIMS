<?php
session_start();
include '../db_connection.php'; // Include database connection

if (!isset($conn)) {
    die("Database connection failed.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM user WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // Regenerate session ID for security
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        // Determine redirect URL
        switch ($user['role']) {
            case 'Admin':
                $redirect_url = "../dashboard/dashboard.php";
                break;
            case 'Employee':
                $redirect_url = "../ui_employee/employee.php";
                break;
            case 'Dev-Support':
                $redirect_url = "../dev/employee.php";
                break;
            default:
                $redirect_url = "../login/login.php"; // Fallback for unknown roles
        }

        // Display a success message with Bootstrap styling
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Login Successful</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
            <style>
                body {
                    background-color: #f8f9fa;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                }
                .message-container {
                    background-color: #fff;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                    text-align: center;
                }
                .success-icon {
                    color: #28a745;
                    font-size: 2.5rem;
                    margin-bottom: 15px;
                }
                h2 {
                    color: #007bff;
                    margin-bottom: 15px;
                }
                p {
                    font-size: 1.1rem;
                    color: #495057;
                    margin-bottom: 20px;
                }
                .spinner-border {
                    width: 2rem;
                    height: 2rem;
                    margin-bottom: 15px;
                }
                .redirect-message {
                    font-size: 0.9rem;
                    color: #6c757d;
                }
            </style>
        </head>
        <body>
            <div class='message-container'>
                <div class='success-icon'>&#10004;</div>
                <p>Welcome, <strong>" . htmlspecialchars($user['name']) . "</strong>!</p>
                <div class='spinner-border text-primary' role='status'>
                    <span class='visually-hidden'>Loading...</span>
                </div>
                <p class='redirect-message'>Redirecting you in 1 second...</p>
            </div>

            <script>
                setTimeout(function() {
                    window.location = '$redirect_url';
                }, 1000);
            </script>
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
        </body>
        </html>";
        exit();
    } else {
        echo "<script>
                alert('Invalid email or password.');
                window.location='../login/login.php';
              </script>";
    }
}
?>
