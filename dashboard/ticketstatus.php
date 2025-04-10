<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Status Chart</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            padding: 20px;
        }

        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 500px;
            margin-top: 40px;
        }

        #statusChart {
            width: 800px !important;
            height: 400px !important;
        }

        .card-title {
            text-align: center;
            font-size: 24px;
            margin-top: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <?php include "../sidebar/sidebar.php"; ?>

    <div class="container">
        <h5 class="card-title">Ticket Status</h5>
        <div class="chart-container">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Fetch status data
            fetch('get_ticket_status.php')
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('statusChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'pie', // Ensure the type is 'pie' for a round chart
                        data: {
                            labels: Object.keys(data),
                            datasets: [{
                                data: Object.values(data),
                                backgroundColor: ['green', 'orange', '#0095fd', 'indigo'],
                                borderColor: '#fff',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error("Error fetching status data:", error));
        });
    </script>
</body>
</html>