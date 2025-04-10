<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
        }

        .sidebar {
            background-color: #2c3e50;
            color: #fff;
            width: 200px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .sidebar h1 {
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .sidebar ul li {
            padding: 10px 0;
            border-bottom: 1px solid #34495e;
            width: 100%;
        }

        .sidebar ul li:last-child {
            border-bottom: none;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
        }

        .card {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-title {
            color: #333;
            margin-bottom: 15px;
        }

        #statusChart {
            width: 100%; /* Full width */
            height: 400px; /* Adjust height as needed */
        }

        .filter-container {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-container label {
            color: #555;
        }

        .filter-container select,
        .filter-container button {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
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

        .user-info {
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: #bdc3c7;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h1>AD ON GROUP</h1>
        <ul>
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-box"></i> Inventory</a></li>
            <li><a href="#"><i class="fas fa-cubes"></i> Assets</a></li>
            <li><a href="#"><i class="fas fa-users"></i> Employee</a></li>
            <li><a href="#"><i class="fas fa-ticket-alt"></i> Tickets</a></li>
            <li><a href="#"><i class="fas fa-coins"></i> Accounts</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Log out</a></li>
        </ul>
        <div class="user-info">
            User: Admin Shop Name2
            <br>
            © AD ON GROUP 2025
        </div>
    </div>

    <div class="content">
        <div class="card">
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
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
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
                // For example:
                // fetch(`get_ticket_status.php?month=${month}&year=${year}`)
                //     .then(response => response.json())
                //     .then(filteredData => {
                //         // Update the chart with filteredData
                //         myChart.data.labels = Object.keys(filteredData);
                //         myChart.data.datasets[0].data = Object.values(filteredData);
                //         myChart.update();
                //     })
                //     .catch(error => console.error("Error fetching filtered data:", error));
            });

            // Initialize an empty chart variable to update later if filtering is implemented
            let myChart;

            fetch('get_ticket_status.php')
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('statusChart').getContext('2d');
                    myChart = new Chart(ctx, { // Assign the chart instance to myChart
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

            // Date filter functionality (updated to potentially update the chart)
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
                        if (myChart) {
                            myChart.data.labels = Object.keys(filteredData);
                            myChart.data.datasets[0].data = Object.values(filteredData);
                            myChart.update();
                        } else {
                            console.error("Chart instance not yet created.");
                        }
                    })
                    .catch(error => console.error("Error fetching filtered data:", error));
            });
        });
    </script>
</body>
</html>