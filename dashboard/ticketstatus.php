<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ticket Status</title>
  <style>
    body {
      margin: 0;
      font-family: sans-serif;
      display: flex;
    }

    .content {
      flex-grow: 1;
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh; /* Full viewport height */
      box-sizing: border-box;
    }

    .card {
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 600px;
    }

    .card-title {
      color: #333;
      margin-bottom: 15px;
      text-align: center;
    }

    #statusChart {
      width: 100%;
      height: 400px;
    }

    .filter-container {
      margin-bottom: 20px;
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
      justify-content: center;
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

  <?php include "../sidebar/sidebar.php"; ?>

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

      <canvas id="statusChart"></canvas>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      let myChart;

      function loadChartData(month = "", year = "") {
        let url = 'get_ticket_status.php';
        const params = [];
        if (month) params.push(`month=${month}`);
        if (year) params.push(`year=${year}`);
        if (params.length > 0) url += '?' + params.join('&');

        fetch(url)
          .then(response => response.json())
          .then(data => {
            const labels = Object.keys(data);
            const values = Object.values(data);

            if (myChart) {
              myChart.data.labels = labels;
              myChart.data.datasets[0].data = values;
              myChart.update();
            } else {
              const ctx = document.getElementById('statusChart').getContext('2d');
              myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                  labels,
                  datasets: [{
                    data: values,
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
            }
          })
          .catch(error => console.error("Error fetching data:", error));
      }

      loadChartData();

      document.getElementById('filterBtn').addEventListener('click', function () {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;
        loadChartData(month, year);
      });
    });
  </script>
</body>
</html>
