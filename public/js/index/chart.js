// Fungsi untuk memuat data chart
async function loadChartData() {
    try {
        const response = await fetch('/chart-data');
        const data = await response.json();
        
        // Update Status Pie Chart
        const statusLabels = data.statusData.map(item => item.status_pemakaian);
        const statusValues = data.statusData.map(item => item.total);
        
        const statusPieChart = new Chart(
            document.getElementById('statusPieChart'),
            {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusValues,
                        backgroundColor: ['#0d9488', '#dc2626', '#eab308'],
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

        // Jenis Aplikasi Pie Chart
        const jenisPieChart = new Chart(
            document.getElementById('jenisPieChart'),
            {
                type: 'doughnut',
                data: {
                    labels: data.jenisData.map(item => item.jenis),
                    datasets: [{
                        data: data.jenisData.map(item => item.total),
                        backgroundColor: ['#3b82f6', '#8b5cf6', '#ec4899', '#f97316', '#84cc16'],
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

        // Basis Platform Pie Chart
        const basisPieChart = new Chart(
            document.getElementById('basisPieChart'),
            {
                type: 'doughnut',
                data: {
                    labels: data.basisData.map(item => item.basis_aplikasi),
                    datasets: [{
                        data: data.basisData.map(item => item.total),
                        backgroundColor: ['#06b6d4', '#6366f1', '#f43f5e'],
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

        // Pengembang Pie Chart
        const pengembangPieChart = new Chart(
            document.getElementById('pengembangPieChart'),
            {
                type: 'doughnut',
                data: {
                    labels: data.pengembangData.map(item => item.pengembang),
                    datasets: [{
                        data: data.pengembangData.map(item => item.total),
                        backgroundColor: ['#0ea5e9', '#14b8a6', '#f59e0b'],
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

    } catch (error) {
        console.error('Error loading chart data:', error);
    }
}

// Panggil fungsi saat halaman dimuat
document.addEventListener('DOMContentLoaded', loadChartData); 