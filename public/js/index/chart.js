// Status Pie Chart
const statusPieChart = new Chart(
    document.getElementById('statusPieChart'),
    {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Tidak Digunakan'],
            datasets: [{
                data: [75, 25],
                backgroundColor: ['#0d9488', '#dc2626'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    }
);

// Users Bar Chart
const userBarChart = new Chart(
    document.getElementById('userBarChart'),
    {
        type: 'bar',
        data: {
            labels: ['App 1', 'App 2', 'App 3', 'App 4', 'App 5'],
            datasets: [{
                label: 'Pengguna Aktif',
                data: [65, 59, 80, 81, 56],
                backgroundColor: '#3498db',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    }
); 