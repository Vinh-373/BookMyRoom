<div class="page-container">
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Tổng quan hệ thống</h1>
            <p class="page-subtitle">
                Theo dõi hiệu suất kinh doanh, tình trạng đặt phòng và hoạt động khách sạn trên toàn hệ thống.
            </p>
        </div>

        <div class="header-actions">
            <button class="btn-secondary">Xuất báo cáo</button>
            <button class="btn-primary">+ Tạo đơn đặt phòng</button>
        </div>
    </div>

    <!-- Revenue Selection Tabs -->
    <div class="stats-grid" style="margin-bottom: 20px;">
        <div class="stat-card tab-button active" onclick="switchRevenueView('day')" style="cursor: pointer; text-align: center;">
            <p class="stat-label">Theo Ngày</p>
            <p style="font-size: 12px; margin: 10px 0 0 0;">Hôm nay</p>
        </div>

        <div class="stat-card tab-button" onclick="switchRevenueView('month')" style="cursor: pointer; text-align: center;">
            <p class="stat-label">Theo Tháng</p>
            <p style="font-size: 12px; margin: 10px 0 0 0;">Tháng này</p>
        </div>

        <div class="stat-card tab-button" onclick="switchRevenueView('year')" style="cursor: pointer; text-align: center;">
            <p class="stat-label">Theo Năm</p>
            <p style="font-size: 12px; margin: 10px 0 0 0;">Năm nay</p>
        </div>

        <div class="stat-card" style="text-align: center;">
            <p class="stat-label">Tùy chọn</p>
            <input type="date" id="revenue-date-picker" style="width: 100%; padding: 8px; margin-top: 5px;" onchange="loadCustomRevenue()">
        </div>
    </div>

    <!-- Revenue Stats -->
    <div class="stats-grid" id="revenue-stats">
        <div class="stat-card">
            <p class="stat-label">Tổng doanh thu</p>
            <h2 id="total-revenue">0đ</h2>
            <span class="trend">Từ các giao dịch</span>
        </div>

        <div class="stat-card">
            <p class="stat-label">Doanh thu hoàn thành</p>
            <h2 id="completed-revenue">0đ</h2>
            <span class="trend up">Đã thanh toán</span>
        </div>

        <div class="stat-card">
            <p class="stat-label">Đơn đặt phòng</p>
            <h2 id="booking-count">0</h2>
            <span class="trend">Số lượng đơn</span>
        </div>

        <div class="stat-card highlight">
            <p class="stat-label">Khách hàng</p>
            <h2 id="customer-count">0</h2>
            <span class="trend">Khách hàng khác nhau</span>
        </div>
    </div>

    <!-- Charts -->
    <div class="dashboard-grid">
        <div class="card">
            <h3>Biểu đồ doanh thu</h3>
            <p class="card-sub">Doanh thu theo thời gian</p>
            <canvas id="revenue-chart" style="max-height: 300px;"></canvas>
        </div>

        <div class="card">
            <h3>Doanh thu theo nguồn</h3>
            <p class="card-sub">Phân bố theo kênh đặt phòng</p>
            <div id="revenue-source-list" style="max-height: 300px; overflow-y: auto;">
                <p style="text-align: center; color: #999;">Đang tải...</p>
            </div>
        </div>
    </div>

    <!-- Top Hotels Chart -->
    <div class="card">
        <h3>Top khách sạn theo doanh thu</h3>
        <p class="card-sub">Khách sạn có doanh thu cao nhất</p>
        <canvas id="hotels-chart" style="max-height: 300px;"></canvas>
    </div>

</div>

<script>
    // Revenue data storage
    let currentRevenueView = 'month';
    let revenueChartInstance = null;

    // Format number to VND
    function formatVND(number) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);
    }

    // Switch revenue view
    function switchRevenueView(period) {
        currentRevenueView = period;
        
        // Update active tab
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        event.target.closest('.tab-button').classList.add('active');

        loadRevenueData(period);
    }

    // Load revenue data
    async function loadRevenueData(period) {
        try {
            let endpoint = `/BookMyRoom/api/revenue.php?action=getRevenueBy`;
            
            if (period === 'day') {
                endpoint += `Day&date=${new Date().toISOString().split('T')[0]}`;
            } else if (period === 'month') {
                // For testing: Use March 2026 as current month data
                // In production, this would use current month
                let monthDate = new Date();
                if (monthDate.getFullYear() === 2026 && monthDate.getMonth() === 3) {
                    // We're in April 2026, use March data for demo
                    endpoint += `Month&month=2026-03`;
                } else {
                    endpoint += `Month&month=${new Date().toISOString().slice(0, 7)}`;
                }
            } else if (period === 'year') {
                endpoint += `Year&year=${new Date().getFullYear()}`;
            }

            const response = await fetch(endpoint);
            const result = await response.json();

            if (result.success) {
                updateRevenueStats(result.data, period);
                loadChartData(period);
                loadRevenueBySource(period);
                loadTopHotels(period);
            }
        } catch (error) {
            console.error('Error loading revenue:', error);
        }
    }

    // Update revenue stats on UI
    function updateRevenueStats(data, period) {
        document.getElementById('total-revenue').textContent = formatVND(data.totalRevenue || 0);
        document.getElementById('completed-revenue').textContent = formatVND(data.completedRevenue || 0);
        document.getElementById('booking-count').textContent = data.bookingCount || 0;
        document.getElementById('customer-count').textContent = data.uniqueCustomers || data.bookingCount || 0;
    }

    // Load chart data
    async function loadChartData(period) {
        try {
            let action = period === 'day' ? 'getDailyRevenueChart' : 
                        period === 'month' ? 'getDailyRevenueChart' : 'getMonthlyRevenueChart';
            
            let params;
            if (period === 'day') {
                params = `&month=${new Date().toISOString().slice(0, 7)}`;
            } else if (period === 'month') {
                // Use March 2026 for April testing
                let monthDate = new Date();
                if (monthDate.getFullYear() === 2026 && monthDate.getMonth() === 3) {
                    params = `&month=2026-03`;
                } else {
                    params = `&month=${new Date().toISOString().slice(0, 7)}`;
                }
            } else {
                params = `&year=${new Date().getFullYear()}`;
            }

            const response = await fetch(`/BookMyRoom/api/revenue.php?action=${action}${params}`);
            const result = await response.json();

            if (result.success && result.data && result.data.labels) {
                if (result.data.labels.length > 0) {
                    displayChart(result.data);
                } else {
                    // No data for this period, show message
                    const canvas = document.getElementById('revenue-chart');
                    canvas.parentElement.innerHTML = '<p style="text-align: center; color: #999; padding: 40px 0;">Không có dữ liệu cho giai đoạn này</p>';
                }
            }
        } catch (error) {
            console.error('Error loading chart:', error);
        }
    }

    // Display chart
    function displayChart(chartData) {
        const canvas = document.getElementById('revenue-chart');
        const ctx = canvas.getContext('2d');

        if (revenueChartInstance) {
            revenueChartInstance.destroy();
        }

        // Create bar chart using Chart.js
        revenueChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels || [],
                datasets: [
                    {
                        label: 'Doanh thu (VND)',
                        data: chartData.data || [],
                        backgroundColor: 'rgba(0, 123, 255, 0.6)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Số đơn đặt',
                        data: chartData.counts || [],
                        backgroundColor: 'rgba(40, 167, 69, 0.6)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1,
                        yAxisID: 'y1',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    if (context.dataset.yAxisID === 'y1') {
                                        label += context.parsed.y;
                                    } else {
                                        label += formatVND(context.parsed.y);
                                    }
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            callback: function(value) {
                                return 'đ' + (value / 1000000).toFixed(0) + 'M';
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }
                }
            }
        });
    }

    // Load revenue by source
    let sourceChartInstance = null;

    async function loadRevenueBySource(period) {
        try {
            let date;
            if (period === 'day') {
                date = new Date().toISOString().split('T')[0];
            } else if (period === 'month') {
                let monthDate = new Date();
                if (monthDate.getFullYear() === 2026 && monthDate.getMonth() === 3) {
                    date = '2026-03'; // Use March for April testing
                } else {
                    date = monthDate.toISOString().slice(0, 7);
                }
            } else {
                date = new Date().getFullYear();
            }

            const response = await fetch(`/BookMyRoom/api/revenue.php?action=getRevenueBySource&period=${period}&date=${date}`);
            const result = await response.json();

            if (result.success && result.data.sources && result.data.sources.length > 0) {
                displaySourceChart(result.data);
            }
        } catch (error) {
            console.error('Error loading revenue by source:', error);
        }
    }

    // Display revenue source as pie chart
    function displaySourceChart(sourceData) {
        const container = document.getElementById('revenue-source-list');
        
        // Create canvas for pie chart
        if (!document.getElementById('source-chart')) {
            container.innerHTML = '<canvas id="source-chart" style="max-height: 300px;"></canvas>';
        }

        const canvas = document.getElementById('source-chart');
        const ctx = canvas.getContext('2d');

        if (sourceChartInstance) {
            sourceChartInstance.destroy();
        }

        const sources = sourceData.sources || [];
        const totalRevenue = sources.reduce((sum, s) => sum + (s.revenue || 0), 0);
        const colors = [
            'rgba(0, 123, 255, 0.7)',
            'rgba(40, 167, 69, 0.7)',
            'rgba(255, 193, 7, 0.7)',
            'rgba(220, 53, 69, 0.7)',
            'rgba(111, 66, 193, 0.7)',
            'rgba(23, 162, 184, 0.7)'
        ];

        sourceChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: sources.map(s => s.source || 'Unknown'),
                datasets: [{
                    data: sources.map(s => s.revenue || 0),
                    backgroundColor: colors.slice(0, sources.length),
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const percentage = ((context.parsed || 0) / totalRevenue * 100).toFixed(1);
                                return context.label + ': ' + formatVND(context.parsed) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Load top hotels
    let hotelsChartInstance = null;

    async function loadTopHotels(period) {
        try {
            let date;
            if (period === 'day') {
                date = new Date().toISOString().split('T')[0];
            } else if (period === 'month') {
                let monthDate = new Date();
                if (monthDate.getFullYear() === 2026 && monthDate.getMonth() === 3) {
                    date = '2026-03'; // Use March for April testing
                } else {
                    date = monthDate.toISOString().slice(0, 7);
                }
            } else {
                date = new Date().getFullYear();
            }

            const response = await fetch(`/BookMyRoom/api/revenue.php?action=getTopHotels&period=${period}&date=${date}&limit=5`);
            const result = await response.json();

            if (result.success && result.data.hotels) {
                displayHotelsChart(result.data.hotels);
            }
        } catch (error) {
            console.error('Error loading top hotels:', error);
        }
    }

    // Display top hotels as bar chart
    function displayHotelsChart(hotels) {
        const canvas = document.getElementById('hotels-chart');
        const ctx = canvas.getContext('2d');

        if (hotelsChartInstance) {
            hotelsChartInstance.destroy();
        }

        hotelsChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: hotels.map(h => h.hotelName || 'Unknown'),
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: hotels.map(h => h.revenue || 0),
                    backgroundColor: [
                        'rgba(0, 123, 255, 0.7)',
                        'rgba(40, 167, 69, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(220, 53, 69, 0.7)',
                        'rgba(111, 66, 193, 0.7)'
                    ],
                    borderColor: [
                        'rgba(0, 123, 255, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(111, 66, 193, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Doanh thu: ' + formatVND(context.parsed.x);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            callback: function(value) {
                                return 'đ' + (value / 1000000).toFixed(0) + 'M';
                            }
                        }
                    }
                }
            }
        });
    }

    // Load custom revenue by date
    function loadCustomRevenue() {
        const date = document.getElementById('revenue-date-picker').value;
        if (!date) return;

        // Switch to single day view
        currentRevenueView = 'day';
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-button')[0].classList.add('active');

        fetch(`/BookMyRoom/api/revenue.php?action=getRevenueByDay&date=${date}`)
            .then(r => r.json())
            .then(result => {
                if (result.success) {
                    updateRevenueStats(result.data, 'day');
                    loadRevenueBySource('day');
                    loadTopHotels('day');
                }
            });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadRevenueData('month');
    });
</script>

<style>
    .tab-button {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .tab-button.active {
        background: #007bff !important;
        color: white !important;
        border-color: #0056b3;
    }

    .tab-button:hover {
        border-color: #007bff;
    }
</style>