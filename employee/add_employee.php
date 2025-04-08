<?php
include '../db_connection.php'; // Ensure this file contains the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form values
    $idnumber = mysqli_real_escape_string($conn, $_POST['idnumber']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $date_hired = mysqli_real_escape_string($conn, $_POST['date_hired']);

    // Prepared statement to prevent SQL injection
    $sql = "INSERT INTO employee (idnumber, name, position, department, status, date_hired) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ssssss", $idnumber, $name, $position, $department, $status, $date_hired);
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Employee added successfully!'); window.location.href='/AIMS/employee/employee.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
        
        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error preparing statement: " . mysqli_error($conn) . "');</script>";
    }
}

mysqli_close($conn);
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="icon" type="image/jpeg" href="https://adongroup.com.au/wp-content/uploads/2020/12/aog-favicon-192px.svg">
<title>ADON PH</title>

<style>
    .form-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }
    .form-section {
        background: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px; /* Limits width */
    }
    .section-header {
        font-size: 1.2rem;
        font-weight: bold;
        color: #2E86C1;
        border-bottom: 2px solid #2E86C1;
        padding-bottom: 5px;
        margin-bottom: 15px;
    }
</style>

<div class="container form-container">
    <div class="form-section">
        <h2 class="text-center mb-4">Add New Employee</h2>

        <form method="POST">
            <div class="section-header">Basic Info</div>

            <div class="mb-3">
                <label for="idnumber" class="form-label">ID No:</label>
                <input type="text" class="form-control" name="idnumber" required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Full Name:</label>
                <input type="text" class="form-control" name="name" required> 
            </div>
            
            <div class="mb-3">
                <label for="position" class="form-label">Position:</label>
                <input type="text" class="form-control" name="position" required> 
            </div>

            <div class="mb-3">
                <label for="department" class="form-label">Department:</label>
                <input type="text" class="form-control" name="department" required> 
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <input type="text" class="form-control" name="status" required> 
            </div>

            <div class="mb-3">
                <label for="date_hired" class="form-label">Date Hired:</label>
                <input type="date" class="form-control" name="date_hired" required> 
            </div>

            <!-- Submit & Cancel Buttons -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="/AIMS/employee/employee.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
