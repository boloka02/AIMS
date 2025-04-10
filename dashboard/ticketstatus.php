<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include "../sidebar/sidebar.php"; ?>

    
                <h5 class="card-title">Ticket Status</h5>
                <div>
                    <canvas id="statusChart"></canvas>
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
                        type: 'pie',
                        data: {
                            labels: Object.keys(data), // Ticket Status
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
                                legend: { display: true, position: 'top' }
                            }
                        }
                    });
                })
                .catch(error => console.error("Error fetching status data:", error));

            // Date filter functionality
            document.getElementById('filterBtn').addEventListener('click', function() {
                const month = document.getElementById('month').value;
                const year = document.getElementById('year').value;

                // You can send these month and year values to the server or adjust the chart data based on the filter
                console.log('Filter applied:', { month, year });

                // Add logic to fetch data with filters applied
                // For now, it just logs the selected month and year
            });
        });
    </script>
</body>
</html>