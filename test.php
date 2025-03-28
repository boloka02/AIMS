<div class="row mt-4">
    <!-- Task Chart -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Ticket Categories</h5>
                <div class="chart-container">
                    <canvas id="myBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch('get_ticket_data.php') // Ensure this matches your PHP file
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('myBarChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(data), // X-axis (e.g., Ticket Categories)
                datasets: [{
                    label: 'Number of Tickets',
                    data: Object.values(data), // Y-axis (Ticket counts)
                    backgroundColor: ['darkred', '#B8860B', 'darkblue', 'darkviolet'],
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Ensures it scales properly
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Ticket Categories',
                            font: { size: 14, weight: 'bold' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Tickets',
                            font: { size: 14, weight: 'bold' }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    })
    .catch(error => console.error("Error fetching data:", error));
});
</script>

<style>
/* Ensures the chart scales properly */
.chart-container {
    position: relative;
    width: 100%;
    max-width: 600px; /* Adjust for larger or smaller size */
    height: 300px;
    margin: auto;
}
</style>
