<div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="card-title">Ticket Categories</h5>
                <button class="btn btn-primary">View Category</button>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>document.addEventListener("DOMContentLoaded", function () {
    fetch('get_ticket_category.php') // Fetch category data
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('categoryChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(data), // Ticket Categories
                datasets: [{
                    label: 'Number of Tickets',
                    data: Object.values(data),
                    backgroundColor: ['orange', 'darkred', 'darkviolet', 'darkblue'],
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Allows it to scale
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 50,
                        ticks: {
                            stepSize: 5
                        }
                    }
                },
                plugins: {
                    legend: { display: true, position: 'top' }
                }
            }
        });
    })
    .catch(error => console.error("Error fetching category data:", error));
});
</script>