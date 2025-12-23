document.addEventListener('DOMContentLoaded', function() {
    // 初始化图表
    initCharts();
    
    // 处理侧边栏折叠
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.querySelector('.sidebar-toggle');
    
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });
    }
});

// 初始化图表函数
function initCharts() {
    // 检查是否有图表容器
    const doughnutChartContainer = document.getElementById('doughnutChart');
    const barChartContainer = document.getElementById('barChart');
    
    // 创建环形图
    if (doughnutChartContainer) {
        const doughnutCtx = doughnutChartContainer.getContext('2d');
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Below 15 min', '15 - 20 min', '20 - 30 min', '30 - 45 min', 'Above 45 min'],
                datasets: [{
                    data: [12, 19, 15, 8, 6],
                    backgroundColor: [
                        '#1a4f8a',
                        '#28a745',
                        '#ffc107',
                        '#fd7e14',
                        '#dc3545'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Time Distribution'
                    }
                }
            }
        });
    }
    
    // 创建柱状图
    if (barChartContainer) {
        const barCtx = barChartContainer.getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Monthly Data',
                    data: [65, 59, 80, 81, 56, 55, 40, 65, 78, 85, 90, 75],
                    backgroundColor: '#1a4f8a',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Graph 2'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}