document.addEventListener("DOMContentLoaded", function () {
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



