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

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if the ID number exists in the employee table and fetch name
    $checkEmployee = "SELECT name FROM employee WHERE idnumber='$idnumber'";
    $employeeResult = mysqli_query($conn, $checkEmployee);

    if ($employeeRow = mysqli_fetch_assoc($employeeResult)) {
        $name = mysqli_real_escape_string($conn, $employeeRow['name']); // Fetch name

        // Check if the ID number OR email is already registered
        $checkUser = "SELECT * FROM user WHERE idnumber='$idnumber' OR email='$email'";
        $userResult = mysqli_query($conn, $checkUser);

        if (mysqli_num_rows($userResult) == 0) {
            // Insert into user table with name
            $insertUser = "INSERT INTO user (idnumber, name, email, password, role) 
                           VALUES ('$idnumber', '$name', '$email', '$hashedPassword', 'Employee')";
            if (mysqli_query($conn, $insertUser)) {
                echo "<script>alert('Registration successful!'); window.location='../login/login.php';</script>";
            } else {
                echo "<script>alert('Error in registration.'); window.location='../login/login.php';</script>";
            }
        } else {
            echo "<script>alert('ID Number or Email is already registered.'); window.location='../login/login.php';</script>";
        }
    } else {
        echo "<script>alert('ID number not found in employee records.'); window.location='../login/login.php';</script>";
    }
}
?>
