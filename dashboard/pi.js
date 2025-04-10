document.addEventListener("DOMContentLoaded", function () {
    // DOM elements for month and year selects
    const monthSelect = document.getElementById("monthSelect");
    const yearSelect = document.getElementById("yearSelect");

    const monthSelectStatus = document.getElementById("monthSelectStatus");
    const yearSelectStatus = document.getElementById("yearSelectStatus");

    // Current year for default selection
    const currentYear = new Date().getFullYear();

    // Populate Year Select with years from 2020 to current year
    for (let y = currentYear; y >= 2020; y--) {
        let opt = document.createElement("option");
        opt.value = y;
        opt.textContent = y;
        yearSelect.appendChild(opt);
        yearSelectStatus.appendChild(opt.cloneNode(true)); // Same options for status chart
    }

    // Set default value for month and year
    monthSelect.value = String(new Date().getMonth() + 1).padStart(2, '0');
    yearSelect.value = currentYear;
    monthSelectStatus.value = String(new Date().getMonth() + 1).padStart(2, '0');
    yearSelectStatus.value = currentYear;

    // Get selected month and year as a formatted string 'YYYY-MM'
    const getSelectedMonthYear = () => `${yearSelect.value}-${monthSelect.value}`;
    const getSelectedMonthYearStatus = () => `${yearSelectStatus.value}-${monthSelectStatus.value}`;

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

    // Load initial charts
    loadCharts(getSelectedMonthYear());

    // Reload charts on month/year change
    monthSelect.addEventListener("change", () => loadCharts(getSelectedMonthYear()));
    yearSelect.addEventListener("change", () => loadCharts(getSelectedMonthYear()));
    
    monthSelectStatus.addEventListener("change", () => loadCharts(getSelectedMonthYearStatus()));
    yearSelectStatus.addEventListener("change", () => loadCharts(getSelectedMonthYearStatus()));
});