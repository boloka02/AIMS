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

// Query to count total tickets
$query = "SELECT COUNT(*) AS ticket_count FROM ticket";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$ticket_count = $row['ticket_count'];

// Define asset type mappings including "Blank"
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
    // Check if the table exists
    $checkTableQuery = "SHOW TABLES LIKE '$table'";
    $checkResult = mysqli_query($conn, $checkTableQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // If table exists, count available or empty status items
        $query = "SELECT COUNT(*) AS count FROM $table WHERE status = 'Available' OR status IS NULL OR status = ''";
        $result = mysqli_query($conn, $query);
        if ($row = mysqli_fetch_assoc($result)) {
            $total_available += $row['count'];
        }
    }
}

// Query to count assigned tickets (assign_to is not null or empty)
$query = "SELECT COUNT(*) AS assigned_count FROM ticket WHERE assign_to IS NOT NULL AND assign_to != ''";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$assigned_count = $row['assigned_count'];

// Fetch history records
$historyQuery = "SELECT id, subject, accept, date_fixed, time FROM history ORDER BY date_fixed DESC, time DESC LIMIT 5";
$historyResult = mysqli_query($conn, $historyQuery);

mysqli_close($conn);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/jpeg" href="https://adongroup.com.au/wp-content/uploads/2020/12/aog-favicon-192px.svg">
<title>ADON PH</title>

<?php include "../sidebar/sidebar.php"; ?>

<div class="container mt-3">
    <div class="row g-3">
        <!-- Cards -->
        <?php
        $cards = [
            ["icon" => "fas fa-tools", "title" => "Equipment", "value" => "50 Units"],
            ["icon" => "fas fa-users", "title" => "Employees", "value" => "$employee_count Staff"],
            ["icon" => "fas fa-ticket-alt", "title" => "Tickets", "value" => "$ticket_count Ticket"],
            ["icon" => "fas fa-tasks", "title" => "Tasks", "value" => "75 Assigned"],
            ["icon" => "fas fa-hourglass-half", "title" => "Pending", "value" => "$pending_count Pending"],
            ["icon" => "fas fa-check-circle", "title" => "Assigned Ticket", "value" => "$assigned_count Assigned"], // Updated dynamically
            ["icon" => "fas fa-box-open", "title" => "Available", "value" => "$total_available Units"], // Updated dynamically
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

    <div class="row mt-2">

    <div class="col-lg-6">
        <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="card-title">Ticket Categories</h5>
        </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="card-title">Ticket Status</h5>
        </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>



    <div class="row mt-2">
    <!-- Recent Activity (Smaller Box, Left Side) -->
    <div class="col-lg-6"> <!-- Reduced width from col-lg-6 to col-lg-4 -->
        <div class="card shadow-sm h-100" style="max-height: 2500px;"> <!-- Reduced height -->
            <div class="card-body">
                <h5 class="card-title text-left">Recent Activity</h5>
                <div style="height: 250px; overflow-y: auto; border: 1px solid #ddd; padding-right: 5px; text-align: left;"> 
                    <!-- Smaller height for scrollbar -->
                    <ul class="list-group list-group-flush">
                    <?php
                    // Include database connection
                    include '../db_connection.php';

                    // Fetch history data sorted by most recent action first
                    $query = "SELECT ticket_number, accept, date_accept, accept_time, date_fixed, fix_time 
                            FROM history 
                            ORDER BY 
                            -- Order by the most recent Fix or Accept time
                            GREATEST(
                                IFNULL(STR_TO_DATE(CONCAT(date_fixed, ' ', fix_time), '%Y-%m-%d %H:%i:%s'), '0000-00-00 00:00:00'), 
                                IFNULL(STR_TO_DATE(CONCAT(date_accept, ' ', accept_time), '%Y-%m-%d %H:%i:%s'), '0000-00-00 00:00:00')
                            ) DESC, 
                            -- Ensure Fix is prioritized when times are the same
                            date_fixed DESC, fix_time DESC, 
                            date_accept DESC, accept_time DESC";

                    $historyResult = mysqli_query($conn, $query);

                    // Check if there are results
                    if (mysqli_num_rows($historyResult) > 0) {
                        echo "<ul class='list-group'>";

                        while ($row = mysqli_fetch_assoc($historyResult)) {
                            $hasFix = !empty($row['fix_time']) && $row['date_fixed'] !== "0000-00-00" && $row['fix_time'] !== "00:00:00";
                            $hasAccept = !empty($row['accept']) && !empty($row['date_accept']) && !empty($row['accept_time']);

                            // Display each Fix action as a separate entry
                            if ($hasFix) {
                                echo "<li class='list-group-item'>";
                                echo "Ticket #{$row['ticket_number']} <span class='text-success'>Fixed</span> on {$row['date_fixed']} at {$row['fix_time']}";
                                echo "</li>";
                            }

                            // Display each Accept action as a separate entry
                            if ($hasAccept) {
                                echo "<li class='list-group-item'>";
                                echo "Ticket #{$row['ticket_number']} <span class='text-primary'>Accepted</span> by {$row['accept']} on {$row['date_accept']} at {$row['accept_time']}";
                                echo "</li>";
                            }
                        }

                        echo "</ul>";
                    } else {
                        echo "<ul class='list-group'><li class='list-group-item'>No recent activity.</li></ul>";
                    }

                    // Close database connection
                    mysqli_close($conn);
                    ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
    <div class="card shadow-sm h-100">
        <div class="card-body">
            <h5 class="card-title mb-2">Asset Distribution</h5>
            <h6 class="card-subtitle mb-3 text-muted small">Breakdown by category</h6>
            <?php
include '../db_connection.php';

// Function to fetch total and assigned count for a given table
function getAssignedPercentage($conn, $table) {
    $query = "SELECT status FROM $table";
    $result = $conn->query($query);

    if ($result === false) {
        die("Error fetching data from $table: " . $conn->error);
    }

    $total = 0;
    $assigned = 0;

    while ($row = $result->fetch_assoc()) {
        $total++;
        if ($row['status'] === 'Assigned') {
            $assigned++;
        }
    }

    return ($total === 0) ? 0 : ($assigned / $total) * 100;
}

// Fetch assigned percentages for all devices
$percentages = [
    "Monitor" => getAssignedPercentage($conn, "monitor"),
    "Keyboard" => getAssignedPercentage($conn, "keyboard"),
    "Laptop" => getAssignedPercentage($conn, "laptop"),
    "Mouse" => getAssignedPercentage($conn, "mouse"),
    "Webcam" => getAssignedPercentage($conn, "webcam"),
    "Headset" => getAssignedPercentage($conn, "headset")
];

// Close connection
$conn->close();
?>

<div>
    <?php foreach ($percentages as $device => $percentage) : ?>
        <div class="mb-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <span class="small"><?= htmlspecialchars($device) ?></span>
                </div>
                <span class="small"><?= (int)$percentage ?>%</span> <!-- Removed decimal places -->
            </div>
            <div class="progress" style="height: 6px;">
                <div class="progress-bar" role="progressbar" 
                    style="width: <?= (int)$percentage ?>%; background-color: <?= getColor($device) ?>;" 
                    aria-valuenow="<?= (int)$percentage ?>" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<?php
// Function to get color based on device type
function getColor($device) {
    $colors = [
        "Monitor" => "rgb(176, 238, 5)",
        "Keyboard" => "#3498db",
        "Laptop" => "#2ecc71",
        "Mouse" => "#9b59b6",
        "Webcam" => "rgb(21, 168, 179)",
        "Headset" => "rgb(190, 20, 63)"
    ];
    return $colors[$device] ?? "#ccc"; // Default color if not found
}
?>

<style>
.progress {
    background-color: #e9ecef;
    border-radius: 0.2rem; /* Slightly smaller border-radius */
}

.progress-bar {
    border-radius: 0.2rem; /* Slightly smaller border-radius */
}
</style>


<script src="pi.js"></script>


