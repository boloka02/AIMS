
document.addEventListener("DOMContentLoaded", function () {
    const dateInput = document.getElementById("filterDate");

    // Load charts on page load with today's date
    const today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
    loadCharts(today);

    dateInput.addEventListener("change", function () {
        loadCharts(this.value);
    });

    function loadCharts(selectedDate) {
        // Load category chart
        fetch(`get_ticket_category.php?date=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('categoryChart').getContext('2d');
                if (window.categoryChart) window.categoryChart.destroy(); // destroy previous chart
                window.categoryChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(data),
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
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMax: 50,
                                ticks: { stepSize: 5 }
                            }
                        },
                        plugins: { legend: { display: true, position: 'top' } }
                    }
                });
            });

        // Load status chart
        fetch(`get_ticket_status.php?date=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('statusChart').getContext('2d');
                if (window.statusChart) window.statusChart.destroy();
                window.statusChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(data),
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
                        plugins: { legend: { display: true, position: 'top' } }
                    }
                });
            });
    }
});

