async function loadChartData() {
    try {
        const response = await fetch("/chart-data");
        const data = await response.json();

        // Status Pie Chart
        const statusColors = {
            Aktif: "#28a745", 
            "Tidak Aktif": "#dc2626", 
        };

        // Urutkan data status agar Aktif selalu di awal
        const sortedStatusData = data.statusData.sort((a, b) => {
            if (a.status_pemakaian === "Aktif") return -1;
            if (b.status_pemakaian === "Aktif") return 1;
            return 0;
        });

        const statusLabels = sortedStatusData.map(
            (item) => `${item.status_pemakaian} (${item.total})`
        );
        const statusValues = sortedStatusData.map((item) => item.total);
        const statusBackgroundColors = sortedStatusData.map(
            (item) => statusColors[item.status_pemakaian]
        );

        const statusPieChart = new Chart(
            document.getElementById("statusPieChart"),
            {
                type: "doughnut",
                data: {
                    labels: statusLabels,
                    datasets: [
                        {
                            data: statusValues,
                            backgroundColor: statusBackgroundColors,
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
                                font: {
                                    size: 12,
                                },
                                generateLabels: function (chart) {
                                    const data = chart.data;
                                    if (
                                        data.labels.length &&
                                        data.datasets.length
                                    ) {
                                        return data.labels.map((label, i) => {
                                            const value =
                                                data.datasets[0].data[i];
                                            const backgroundColor =
                                                data.datasets[0]
                                                    .backgroundColor[i];
                                            return {
                                                text: `${label}`,
                                                fillStyle: backgroundColor,
                                                strokeStyle: backgroundColor,
                                                lineWidth: 0,
                                                hidden:
                                                    isNaN(value) || value === 0,
                                                index: i,
                                            };
                                        });
                                    }
                                    return [];
                                },
                            },
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce(
                                        (a, b) => a + b,
                                        0
                                    );
                                    const percentage = (
                                        (value / total) *
                                        100
                                    ).toFixed(1);
                                    return `${value} (${percentage}%)`;
                                },
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
                    labels: data.jenisData.map(
                        (item) => `${item.jenis} (${item.total})`
                    ),
                    datasets: [
                        {
                            data: data.jenisData.map((item) => item.total),
                            backgroundColor: [
                                "#2ecc71", 
                                "#3498db", 
                                "#e74c3c"
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
                                font: {
                                    size: 12,
                                },
                            },
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce(
                                        (a, b) => a + b,
                                        0
                                    );
                                    const percentage = (
                                        (value / total) *
                                        100
                                    ).toFixed(1);
                                    return `${value} (${percentage}%)`;
                                },
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
                    labels: data.basisData.map(
                        (item) => `${item.basis_aplikasi} (${item.total})`
                    ),
                    datasets: [
                        {
                            data: data.basisData.map((item) => item.total),
                            backgroundColor: [
                                "#3B82F6", 
                                "#10B981", 
                                "#F59E0B", 
                                "#6366F1", 
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
                                font: {
                                    size: 12,
                                },
                            },
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce(
                                        (a, b) => a + b,
                                        0
                                    );
                                    const percentage = (
                                        (value / total) *
                                        100
                                    ).toFixed(1);
                                    return `${value} (${percentage}%)`;
                                },
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
                    labels: data.pengembangData.map(
                        (item) => `${item.pengembang} (${item.total})`
                    ),
                    datasets: [
                        {
                            data: data.pengembangData.map((item) => item.total),
                            backgroundColor: [
                                "#8B5CF6", 
                                "#14B8A6", 
                                "#F43F5E", 
                                "#0EA5E9", 
                                "#22C55E", 
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
                                font: {
                                    size: 12,
                                },
                            },
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce(
                                        (a, b) => a + b,
                                        0
                                    );
                                    const percentage = (
                                        (value / total) *
                                        100
                                    ).toFixed(1);
                                    return `${value} (${percentage}%)`;
                                },
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
