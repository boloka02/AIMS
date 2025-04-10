<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Status</title>
    <style>
        #statusChartContainer {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            width: 100%;
            height: 400px; /* Adjust height as needed */
        }
        #statusChart {
            width: 80%; /* Adjust chart width as needed */
            max-width: 600px; /* Optional: set a maximum width */
            height: auto; /* Maintain aspect ratio */
        }
        .filter-container {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
    </style>
</head>
<body>
    <?php include "../sidebar/sidebar.php"; ?>

    <h5 class="card-title">Ticket Status</h5>

    <div class="filter-container">
        <label for="month">Month:</label>
        <select id="month">
            <option value="">All</option>
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

        <label for="year">Year:</label>
        <select id="year">
            <?php
                $currentYear = date("Y");
                for ($i = $currentYear; $i >= 2020; $i--) {
                    echo "<option value='$i'>$i</option>";
                }
            ?>
            <option value="">All</option>
        </select>

        <button id="filterBtn">Filter</button>
    </div>

    <div id="statusChartContainer">
        <canvas id="statusChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let myChart; // To hold the chart instance

            function fetchAndRenderChart(month = '', year = '') {
                let fetchUrl = 'get_ticket_status.php';
                if (month || year) {
                    const params = new URLSearchParams();
                    if (month) params.append('month', month);
                    if (year) params.append('year', year);
                    fetchUrl += '?' + params.toString();
                }

                fetch(fetchUrl)
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('statusChart').getContext('2d');

                        // Destroy existing chart if it exists
                        if (myChart) {
                            myChart.destroy();
                        }

                        myChart = new Chart(ctx, {
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
            }

            // Initial chart render
            fetchAndRenderChart();

            // Date filter functionality
            document.getElementById('filterBtn').addEventListener('click', function() {
                const month = document.getElementById('month').value;
                const year = document.getElementById('year').value;
                fetchAndRenderChart(month, year);
            });
        });
    </script>
</body>
</html>