

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Ticket Status</h5>
        <div>
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>
<script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    
document.addEventListener("DOMContentLoaded", function () {
    fetch('get_ticket_status.php') // Fetch status data
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('statusChart').getContext('2d');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(data), // Ticket Status
                datasets: [{
                    data: Object.values(data),
                    backgroundColor: ['green', 'orange', '#0095fd', 'indigo'], // Custom colors
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
});

</script>