<div class="dashboard-wrapper">
    <div class="dashboard-content-header">
        <div class="header-title">
            <h1>Dashboard - <?= $hotel['hotelName'] ?? 'Dalat Palace Hotel' ?></h1>
            <p>Welcome back. Here is what's happening today.</p>
        </div>
        <div class="header-actions">
            <!-- <div class="date-picker">
                <span>📅 Today, <?= date('d M Y') ?></span>
                <i class="arrow">▾</i>
            </div> 
            <button class="btn-export">📤 Quick Export</button> -->
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="card-icon">➡️</div>
            <div class="card-data">
                <span class="label">ARRIVALS</span>
                <div class="main-val">
                    <span class="number"><?= $stats['arrivals'] ?></span>
                </div>
                <p class="sub-label">Today's Arrivals</p>
            </div>
        </div>
    
        <div class="stat-card orange">
            <div class="card-icon">📊</div>
            <div class="card-data">
                <span class="label">OCCUPANCY</span>
                <div class="main-val">
                    <span class="number"><?= $stats['occupancy'] ?>%</span>
                    <span class="trend <?= $stats['occupancy'] > 70 ? 'warning' : '' ?>">
                        <?= $stats['occupancy'] > 70 ? 'High Demand' : 'Normal' ?>
                    </span>
                </div>
                <p class="sub-label">Occupancy Rate</p>
            </div>
        </div>
    
        <div class="stat-card gray">
            <div class="card-icon">⬅️</div>
            <div class="card-data">
                <span class="label">DEPARTURES</span>
                <div class="main-val">
                    <span class="number"><?= $stats['departures'] ?></span>
                    <span class="trend">Expected today</span>
                </div>
                <p class="sub-label">Today's Departures</p>
            </div>
        </div>
    
        <div class="stat-card red">
            <div class="card-icon">🔔</div>
            <div class="card-data">
                <span class="label">ALERTS</span>
                <div class="main-val">
                    <span class="number"><?= $stats['alerts'] ?? 0 ?></span>
                    <span class="trend danger">Requires action</span>
                </div>
                <p class="sub-label">Pending Requests</p>
            </div>
        </div>
    </div>
    
    <div class="charts-container">
        <div class="revenue-chart-box">
            <div class="box-header">
                <h3>Revenue Trends</h3>
                <p>Daily performance for the last 30 days</p>
            </div>
            <?php if(!empty($revenueTrends)):?>
                <div class="chart-area" style="position: relative; height:300px; width:100%">
                    <canvas id="revenueChart"></canvas>
                </div>
            <?php else: ?>
                <div>
                    <span>Không có dữ liệu biểu đồ.</span>
                </div>
            <?php endif ?>
        </div>
    
        <div class="booking-sources-box">
            <div class="box-header">
                <h3>Booking Sources</h3>
            </div>
            <div class="chart-wrapper">
                <?php if(!empty($sources)):?>
                    <div class="donut-chart-container">
                        <canvas id="bookingSourcesChart"></canvas> 
                        <div class="donut-hole">
                            <strong>100%</strong>
                            <span>CHANNELS</span>
                        </div>
                    </div>
                <?php else: ?>
                    <div>
                        <span>Chưa có dữ liệu.</span>
                    </div>
                <?php endif ?>
                <ul class="source-legend">
                    <?php 
                    $colors = [
                        'WEBSITE' => '#4e73df',
                        'BOOKING_DOT_COM' => '#1cc88a',
                        'EXPEDIA' => '#36b9cc',
                        'DIRECT' => '#f6c23e'
                    ];
                    foreach($sources as $s): 
                        $color = $colors[$s['source']] ?? '#eee';
                    ?>
                    <li>
                        <span class="dot" style="background-color: <?= $color ?>"></span>
                        <span class="source-name"><?= str_replace('_', ' ', $s['source']) ?></span>
                        <span class="val"><?= $s['percentage'] ?>%</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="recent-activity-box">
        <div class="box-header">
            <h3>Recent Activity</h3>
            <a href="<?= URLROOT ?>/transactions" class="btn-link">XEM TẤT CẢ GIAO DỊCH →</a>
        </div>
        
        <table class="activity-table">
            <thead>
                <tr>
                    <th>Guest</th>
                    <th>Room Type</th>
                    <th>Check In</th>
                    <th>Status</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($activities)): ?>
                    <?php foreach($activities as $act): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; color: #777;">
                                    <?= strtoupper(substr($act['guestName'] ?? 'G', 0, 1)) ?>
                                </div>
                                <strong><?= $act['guestName'] ?></strong>
                            </div>
                        </td>
                        <td><span style="color: #666;"><?= $act['roomType'] ?></span></td>
                        <td><?= date('d M, Y', strtotime($act['checkIn'])) ?></td>
                        <td>
                            <span class="badge <?= strtolower($act['status'] ?? 'pending') ?>">
                                <?= $act['status'] ?? 'PENDING' ?>
                            </span>
                        </td>
                        <td class="amount-cell" style="text-align: right;">
                            <strong>$<?= number_format($act['amount'], 2) ?></strong>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                            No recent activity found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Khởi tạo Line Chart từ hàm getDailyRevenueLast30Days
        const rawRevenueData = <?= json_encode($revenueTrends ?? []) ?>;
        const revLabels = rawRevenueData.map(item => {
            const date = new Date(item.date);
            return date.getDate() + '/' + (date.getMonth() + 1);
        });
        const revValues = rawRevenueData.map(item => item.daily_revenue);
    
        new Chart(document.getElementById('revenueChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: revLabels,
                datasets: [{
                    label: 'Revenue ($)',
                    data: revValues,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: '#4e73df'
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: (val) => '$' + val.toLocaleString() }
                    }
                }
            }
        });
    
        // 2. Khởi tạo Doughnut Chart từ hàm getBookingSources
        const rawSourceData = <?= json_encode($sources ?? []) ?>;
        const srcLabels = rawSourceData.map(s => s.source.replace(/_/g, ' '));
        const srcValues = rawSourceData.map(s => s.percentage);
        const srcColors = rawSourceData.map(s => {
            const map = {'WEBSITE': '#4e73df', 'BOOKING_DOT_COM': '#1cc88a', 'EXPEDIA': '#36b9cc', 'DIRECT': '#f6c23e'};
            return map[s.source] ?? '#eee';
        });
    
        new Chart(document.getElementById('bookingSourcesChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: srcLabels,
                datasets: [{
                    data: srcValues,
                    backgroundColor: srcColors,
                    hoverOffset: 4,
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '75%', // Để lộ khoảng trống cho lớp donut-hole hiện chữ "100% CHANNELS"
                plugins: { legend: { display: false } }
            }
        });
    </script>