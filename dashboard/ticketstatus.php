<?php
include '../db_connection.php'; // Ensure correct DB connection

header('Content-Type: application/json');

$query = "SELECT status, COUNT(*) as count FROM ticket GROUP BY status";
$result = $conn->query($query);

if (!$result) {
    echo json_encode(["error" => $conn->error]);
    exit();
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['status']] = (int) $row['count']; // Ensure integer values
}

echo json_encode($data);
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Ticket Status</h5>
        <div>
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Fetch the data from the server
    fetch('path/to/your/php/script.php') // Use the correct URL to your PHP file
    .then(response => response.json()) // Parse JSON response
    .then(data => {
        const ctx = document.getElementById('statusChart').getContext('2d');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(data), // Ticket Status
                datasets: [{
                    data: Object.values(data),
                    backgroundColor: ['green', 'orange', '#0095fd', 'indigo'], // Custom colors
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top' }
                }
            }
        });
    })
    .catch(error => console.error("Error fetching status data:", error)); // Handle errors
});
</script>
