<?php
// Include the database connection file
include 'db_connection.php';

// Define the queries to fetch the status of monitors and keyboards
$queryMonitors = "SELECT status FROM monitor";
$queryKeyboards = "SELECT status FROM keyboard";

// Execute the queries for monitors and keyboards
$resultMonitors = $conn->query($queryMonitors);
$resultKeyboards = $conn->query($queryKeyboards);

if ($resultMonitors === false || $resultKeyboards === false) {
    die("Error fetching data: " . $conn->error);
}

// Initialize counters for total and assigned monitors
$totalMonitors = 0;
$assignedMonitors = 0;

// Loop through the results for monitors
while ($row = $resultMonitors->fetch_assoc()) {
    $totalMonitors++;
    if ($row['status'] === 'Assigned') {
        $assignedMonitors++;
    }
}

// Calculate the percentage of assigned monitors
$assignedPercentageMonitors = ($totalMonitors === 0) ? 0 : ($assignedMonitors / $totalMonitors) * 100;

// Initialize counters for total and assigned keyboards
$totalKeyboards = 0;
$assignedKeyboards = 0;

// Loop through the results for keyboards
while ($row = $resultKeyboards->fetch_assoc()) {
    $totalKeyboards++;
    if ($row['status'] === 'Assigned') {
        $assignedKeyboards++;
    }
}

// Calculate the percentage of assigned keyboards
$assignedPercentageKeyboards = ($totalKeyboards === 0) ? 0 : ($assignedKeyboards / $totalKeyboards) * 100;

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor and Keyboard Assignment Progress</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .progress {
            height: 6px;
        }
        .small {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <!-- Monitor Progress -->
    <div id="monitor-progress" class="mb-2 container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="small">Assigned Monitors</span>
            </div>
            <span id="progress-text" class="small"><?= number_format($assignedPercentageMonitors, 2) ?>%</span>
        </div>
        <div class="progress">
            <div id="progress-bar" class="progress-bar" role="progressbar" style="width: <?= $assignedPercentageMonitors ?>%; background-color: #3498db;" aria-valuenow="<?= $assignedPercentageMonitors ?>" aria-valuemin="0" aria-valuemax="100"><?= number_format($assignedPercentageMonitors, 2) ?>%</div>
        </div>
    </div>

    <!-- Keyboard Progress -->
    <div id="keyboard-progress" class="mb-2 container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="small">Assigned Keyboards</span>
            </div>
            <span id="progress-text" class="small"><?= number_format($assignedPercentageKeyboards, 2) ?>%</span>
        </div>
        <div class="progress">
            <div id="progress-bar" class="progress-bar" role="progressbar" style="width: <?= $assignedPercentageKeyboards ?>%; background-color: #3498db;" aria-valuenow="<?= $assignedPercentageKeyboards ?>" aria-valuemin="0" aria-valuemax="100"><?= number_format($assignedPercentageKeyboards, 2) ?>%</div>
        </div>
    </div>

</body>
</html>
