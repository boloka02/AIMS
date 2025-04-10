<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Status</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            min-height: 100vh; /* Ensure full viewport height */
            margin: 0;
            background-color: #f4f4f4; /* Light background */
        }

        .content {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center text within the content */
        }

        .card-title {
            color: #333;
            margin-bottom: 20px;
        }

        #statusChart {
            width: 300px; /* Adjust as needed */
            height: 300px; /* Adjust as needed */
            margin: 20px auto; /* Center the chart */
        }

        .filter-container {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column; /* Stack filter elements */
            align-items: center; /* Center filter elements horizontally */
            gap: 10px;
        }

        .filter-container label {
            color: #555;
        }

        .filter-container select,
        .filter-container button {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }

        .filter-container button {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .filter-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="content">
        <h5 class="card-title">Ticket Status</h5>
        <div class="filter-container">
            <label for="month">Month:</label>
            <select id="month">
                <option value="">All Months</option>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
            <label for="year">Year:</label>
            <select id="year">
                <option value="">All Years</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
            </select>
            <button id="filterBtn">Filter</button>
        </div>
        <div>
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
                                legend: { display: true, position: 'bottom' } /* Moved legend to the bottom */
                            }
                        }
                    });
                })
                .catch(error => console.error("Error fetching status data:", error));

            // Date filter functionality
            document.getElementById('filterBtn').addEventListener('click', function() {
                const month = document.getElementById('month').value;
                const year = document.getElementById('year').value;

                let url = 'get_ticket_status.php';
                const params = [];
                if (month) {
                    params.push(`month=${month}`);
                }
                if (year) {
                    params.push(`year=${year}`);
                }
                if (params.length > 0) {
                    url += '?' + params.join('&');
                }

                fetch(url)
                    .then(response => response.json())
                    .then(filteredData => {
                        const chart = Chart.getChart('statusChart'); // Get the existing chart instance
                        if (chart) {
                            chart.data.labels = Object.keys(filteredData);
                            chart.data.datasets[0].data = Object.values(filteredData);
                            chart.update();
                        } else {
                            console.error("Chart instance not found.");
                        }
                    })
                    .catch(error => console.error("Error fetching filtered data:", error));
            });
        });
    </script>
</body>
</html>