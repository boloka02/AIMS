<?php
include '../db_connection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idnumber = mysqli_real_escape_string($conn, $_POST['idnumber']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Validate password (at least 8 characters and one special character)
    if (strlen($password) < 8 || !preg_match('/[\W_]/', $password)) {
        echo "<script>alert('Password must be at least 8 characters long and contain at least one special character.'); window.location='../login/login.php';</script>";
        exit();
    }

    // Validate email format (must be example@adongroup.com.au or this@adongroup.com.au)
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@adongroup\.com\.au$/', $email)) {
        echo "<script>alert('Email must be in the format: example@adongroup.com.au'); window.location='../login/login.php';</script>";
        exit();
    }

    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if the ID number exists in the employee table and fetch name
    $checkEmployeeQuery = "SELECT name FROM employee WHERE idnumber = ?";
    if ($stmt = mysqli_prepare($conn, $checkEmployeeQuery)) {
        mysqli_stmt_bind_param($stmt, "s", $idnumber);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $name);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($name) {
            // Check if the ID number OR email is already registered in the user table
            $checkUserQuery = "SELECT * FROM user WHERE idnumber = ? OR email = ?";
            if ($stmt = mysqli_prepare($conn, $checkUserQuery)) {
                mysqli_stmt_bind_param($stmt, "ss", $idnumber, $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                
                if (mysqli_stmt_num_rows($stmt) == 0) {
                    // Insert the new user into the user table
                    $insertUserQuery = "INSERT INTO user (idnumber, name, email, password, role, status) 
                                        VALUES (?, ?, ?, ?, 'Employee', 'Active')";
                    if ($stmt = mysqli_prepare($conn, $insertUserQuery)) {
                        mysqli_stmt_bind_param($stmt, "ssss", $idnumber, $name, $email, $hashedPassword);
                        if (mysqli_stmt_execute($stmt)) {
                            echo "<script>alert('Registration successful!'); window.location='../login/login.php';</script>";
                        } else {
                            echo "<script>alert('Error in registration.'); window.location='../login/login.php';</script>";
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        echo "<script>alert('Error preparing insert query.'); window.location='../login/login.php';</script>";
                    }
                } else {
                    echo "<script>alert('ID Number or Email is already registered.'); window.location='../login/login.php';</script>";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "<script>alert('Error preparing check user query.'); window.location='../login/login.php';</script>";
            }
        } else {
            echo "<script>alert('ID number not found in employee records.'); window.location='../login/login.php';</script>";
        }
    } else {
        echo "<script>alert('Error preparing check employee query.'); window.location='../login/login.php';</script>";
    }
}
?>
