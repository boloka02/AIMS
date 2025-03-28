<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

// Include database connection
include '../db_connection.php';

// Query to count employees
$query = "SELECT COUNT(*) AS employee_count FROM employee";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$employee_count = $row['employee_count'];

// Query to count tickets with status 'Pending'
$query = "SELECT COUNT(*) AS pending_count FROM ticket WHERE status = 'Pending'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$pending_count = $row['pending_count'];

// Define asset type mappings
$assets = [
    "Laptop" => "laptop",
    "Headset" => "headset",
    "Keyboard" => "keyboard",
    "Mboard" => "mboard",
    "Monitor" => "monitor",
    "2nd Monitor" => "monitor2",
    "Mouse" => "mouse",
    "Processor" => "processor",
    "RAM" => "ram",
    "Webcam" => "webcam"
];

// Initialize total available count
$total_available = 0;

// Count available assets for each type
foreach ($assets as $type => $table) {
    $query = "SELECT COUNT(*) AS count FROM $table WHERE status = 'Available'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $total_available += $row['count'];
    }
}

mysqli_close($conn);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<?php include "../sidebar/sidebar.php"; ?>

<div class="container mt-3">
    <div class="row g-3">
        <!-- Cards -->
        <?php
        $cards = [
            ["icon" => "fas fa-tools", "title" => "Equipment", "value" => "50 Units"],
            ["icon" => "fas fa-users", "title" => "Employees", "value" => "$employee_count Staff"],
            ["icon" => "fas fa-ticket-alt", "title" => "Tickets", "value" => "35 Open"],
            ["icon" => "fas fa-tasks", "title" => "Tasks", "value" => "75 Assigned"],
            ["icon" => "fas fa-hourglass-half", "title" => "Pending", "value" => "$pending_count Pending"], // Updated dynamically
            ["icon" => "fas fa-check-circle", "title" => "Assign", "value" => "60 Completed"],
            ["icon" => "fas fa-box-open", "title" => "Available", "value" => "$total_available Units"],
            ["icon" => "fas fa-wrench", "title" => "Maintenance", "value" => "10 In Progress"]
        ];
        
        foreach ($cards as $card) {
            echo "<div class='col-lg-3 col-md-4 col-sm-6'>
                    <div class='card shadow-sm p-3'>
                        <div class='d-flex align-items-center'>
                            <i class='{$card['icon']} fa-2x'></i>
                            <div class='ms-3'>
                                <h5>{$card['title']}</h5>
                                <p class='mb-0'>{$card['value']}</p>
                            </div>
                        </div>
                    </div>
                </div>";
        }
        ?>
    </div>



    <div class="row mt-4">
    <!-- Ticket Categories (Bar Chart) -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Ticket Categories</h5>
                <div style="height: 300px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Status (Pie Chart) -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Ticket Status</h5>
                <div style="height: 300px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>




<!-- Load Chart.js -->

<script src="pi.js"></script>



<style>
/* Ensures the chart scales properly */
.chart-container {
    position: relative;
    width: 100%;
    max-width: 600px; /* Adjust for a larger or smaller size */
    height: 300px;
    margin: auto;
}
</style>






