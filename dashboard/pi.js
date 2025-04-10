
document.addEventListener("DOMContentLoaded", function () {
    const monthSelect = document.getElementById("monthSelect");
    const yearSelect = document.getElementById("yearSelect");

    // Populate year dropdown (from 2020 to current year)
    const currentYear = new Date().getFullYear();
    for (let y = currentYear; y >= 2020; y--) {
        const option = document.createElement("option");
        option.value = y;
        option.textContent = y;
        yearSelect.appendChild(option);
    }

    // Set current month and year
    monthSelect.value = String(new Date().getMonth() + 1).padStart(2, '0');
    yearSelect.value = currentYear;

    const getSelectedMonthYear = () => `${yearSelect.value}-${monthSelect.value}`;

    const loadCharts = (monthYear) => {
        fetch(`get_ticket_category.php?month=${monthYear}`)
            .then(res => res.json())
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
                            y: { beginAtZero: true }
                        },
                        plugins: { legend: { display: true, position: 'top' } }
                    }
                });
            });

        fetch(`get_ticket_status.php?month=${monthYear}`)
            .then(res => res.json())
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
    };

    // Initial chart load
    loadCharts(getSelectedMonthYear());

    // Reload charts on dropdown change
    monthSelect.addEventListener("change", () => loadCharts(getSelectedMonthYear()));
    yearSelect.addEventListener("change", () => loadCharts(getSelectedMonthYear()));
});

