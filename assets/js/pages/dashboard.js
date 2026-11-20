document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: JSON.parse(ctx.dataset.labels),
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: JSON.parse(ctx.dataset.values),
                backgroundColor: 'rgba(215, 0, 24, 0.8)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + '₫';
                        }
                    }
                }
            }
        }
    });
});
