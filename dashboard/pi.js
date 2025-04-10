document.addEventListener("DOMContentLoaded", function () {
    const monthInput = document.getElementById("filterMonth");

    // Default to current month
    const now = new Date();
    const monthString = now.toISOString().slice(0, 7); // YYYY-MM
    monthInput.value = monthString;
    loadCharts(monthString);

    monthInput.addEventListener("change", function () {
        loadCharts(this.value);
    });

    function loadCharts(monthYear) {
        fetch(`get_ticket_category.php?month=${monthYear}`)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('categoryChart').getContext('2d');
                if (window.categoryChart) window.categoryChart.destroy();
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
                            y: { beginAtZero: true, suggestedMax: 50, ticks: { stepSize: 5 } }
                        },
                        plugins: { legend: { display: true, position: 'top' } }
                    }
                });
            });

        fetch(`get_ticket_status.php?month=${monthYear}`)
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
