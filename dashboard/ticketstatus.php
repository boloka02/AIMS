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
            width: 1200px !important;
            height: 500px !important;
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

    <script src="pi.js"></script>
</body>
</html>