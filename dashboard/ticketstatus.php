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
            width: 200px; /* Reduced sidebar width */
            background-color: #f4f4f4;
            padding: 15px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Align items to the top */
            align-items: flex-start; /* Align items to the left */
        }
        .filter-container {
            margin-bottom: 15px; /* Added margin between filter elements */
            width: 100%; /* Make filter elements take full width of sidebar */
        }
        .filter-container label,
        .filter-container select,
        .sidebar button {
            display: block;
            margin-bottom: 5px;
            width: 100%;
            box-sizing: border-box; /* Ensure padding doesn't increase width */
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
            width: 90%; /* Increased card width */
            max-width: 900px; /* Increased max card width */
            margin: 20px;
        }
        /* Chart Styling */
        #statusChart {
            width: 100%;
            height: 450px; /* Increased chart height */
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
            <label for="month">Month:</label>
            <select id="month" name="month">
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </div>
        <div class="filter-container">
            <label for="year">Year:</label>
            <select id="year" name="year">
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                </select>
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