async function loadChartData() {
    try {
        const response = await fetch("/chart-data");
        const data = await response.json();

        // Update Status Pie Chart
        const statusLabels = data.statusData.map(
            (item) => item.status_pemakaian
        );
        const statusValues = data.statusData.map((item) => item.total);

        const statusPieChart = new Chart(
            document.getElementById("statusPieChart"),
            {
                type: "doughnut",
                data: {
                    labels: statusLabels,
                    datasets: [
                        {
                            data: statusValues,
                            backgroundColor: ["#28a745", "#dc2626", "#eab308"], // Existing colors
                            borderWidth: 0,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "left",
                            labels: {
                                boxWidth: 10,
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: "circle",
                                textAlign: "left",
                            },
                        },
                    },
                    onResize: function (chart, size) {
                        if (size.width <= 1050) {
                            chart.options.plugins.legend.position = "top";
                        } else {
                            chart.options.plugins.legend.position = "left";
                        }
                        chart.update();
                    },
                },
            }
        );

        // Jenis Aplikasi Pie Chart
        const jenisPieChart = new Chart(
            document.getElementById("jenisPieChart"),
            {
                type: "doughnut",
                data: {
                    labels: data.jenisData.map((item) => item.jenis),
                    datasets: [
                        {
                            data: data.jenisData.map((item) => item.total),
                            backgroundColor: [
                                "#1abc9c",
                                "#3498db",
                                "#9b59b6",
                                "#e74c3c",
                            ],
                            borderWidth: 0,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "left",
                            labels: {
                                boxWidth: 10,
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: "circle",
                                textAlign: "left",
                            },
                        },
                    },
                    onResize: function (chart, size) {
                        if (size.width <= 1050) {
                            chart.options.plugins.legend.position = "top";
                        } else {
                            chart.options.plugins.legend.position = "left";
                        }
                        chart.update();
                    },
                },
            }
        );

        // Basis Platform Pie Chart
        const basisPieChart = new Chart(
            document.getElementById("basisPieChart"),
            {
                type: "doughnut",
                data: {
                    labels: data.basisData.map((item) => item.basis_aplikasi),
                    datasets: [
                        {
                            data: data.basisData.map((item) => item.total),
                            backgroundColor: [
                                "#f39c12",
                                "#27ae60",
                                "#e67e22",
                                "#95a5a6",
                            ],
                            borderWidth: 0,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "left",
                            labels: {
                                boxWidth: 10,
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: "circle",
                                textAlign: "left",
                            },
                        },
                    },
                    onResize: function (chart, size) {
                        if (size.width <= 1050) {
                            chart.options.plugins.legend.position = "top";
                        } else {
                            chart.options.plugins.legend.position = "left";
                        }
                        chart.update();
                    },
                },
            }
        );

        // Pengembang Pie Chart
        const pengembangPieChart = new Chart(
            document.getElementById("pengembangPieChart"),
            {
                type: "doughnut",
                data: {
                    labels: data.pengembangData.map((item) => item.pengembang),
                    datasets: [
                        {
                            data: data.pengembangData.map((item) => item.total),
                            backgroundColor: ["#34495e", "#2ecc71", "#3498db"], // Added one more color
                            borderWidth: 0,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "left",
                            labels: {
                                boxWidth: 10,
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: "circle",
                                textAlign: "left",
                            },
                        },
                    },
                    onResize: function (chart, size) {
                        if (size.width <= 1050) {
                            chart.options.plugins.legend.position = "top";
                        } else {
                            chart.options.plugins.legend.position = "left";
                        }
                        chart.update();
                    },
                },
            }
        );
    } catch (error) {
        console.error("Error loading chart data:", error);
    }
}

// Panggil fungsi saat halaman dimuat
document.addEventListener("DOMContentLoaded", loadChartData);
