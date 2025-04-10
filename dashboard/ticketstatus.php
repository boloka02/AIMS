<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            display: flex;
            margin: 0;
            height: 100vh;
        }
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #f4f4f4;
            padding: 15px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .filter-container {
            margin-top: 20px;
        }
        /* Center Content Styles */
        .content {
            flex-grow: 1;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            width: 80%;
            max-width: 600px;
            margin: 20px;
        }
        /* Chart Styling */
        #statusChart {
            width: 100%;
            height: 300px;
        }
    </style>
</head>
<body>
    <?php include "../sidebar/sidebar.php"; ?>
    
    <div class="content">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Ticket Status</h5>
                <div>
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="sidebar">
        <h4>Filter by Date</h4>
        <div class="filter-container">
            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" name="startDate">
        </div>
        <div class="filter-container">
            <label for="endDate">End Date:</label>
            <input type="date" id="endDate" name="endDate">
        </div>
        <button id="filterBtn" style="margin-top: 15px;">Apply Filter</button>
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
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;

                // You can send these date values to the server or adjust the chart data based on the filter
                console.log('Filter applied:', { startDate, endDate });

                // Add logic to fetch data with filters applied
                // For now, it just logs the selected date range
            });
        });
    </script>
</body>
</html>
