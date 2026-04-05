<?php
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

?>
<div class="dashboard-wrapper">
    <div class="dashboard-content-header">
        <div class="header-title">
            <h1>Bảng điều khiển - <?= $hotel['hotelName'] ?? 'Dalat Palace Hotel' ?></h1>
            <p>Chào mừng trở lại. Dưới đây là tình hình hoạt động hôm nay.</p>
        </div>
        <div class="header-actions">
            </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="card-icon">➡️</div>
            <div class="card-data">
                <span class="label">KHÁCH ĐẾN</span>
                <div class="main-val">
                    <span class="number"><?= $stats['arrivals'] ?></span>
                </div>
                <p class="sub-label">Lượt nhận phòng hôm nay</p>
            </div>
        </div>
    
        <div class="stat-card orange">
            <div class="card-icon">📊</div>
            <div class="card-data">
                <span class="label">CÔNG SUẤT PHÒNG</span>
                <div class="main-val">
                    <span class="number"><?= $stats['occupancy'] ?>%</span>
                    <span class="trend <?= $stats['occupancy'] > 70 ? 'warning' : '' ?>">
                        <?= $stats['occupancy'] > 70 ? 'Nhu cầu cao' : 'Bình thường' ?>
                    </span>
                </div>
                <p class="sub-label">Tỉ lệ lấp đầy</p>
            </div>
        </div>
    
        <div class="stat-card gray">
            <div class="card-icon">⬅️</div>
            <div class="card-data">
                <span class="label">KHÁCH ĐI</span>
                <div class="main-val">
                    <span class="number"><?= $stats['departures'] ?></span>
                    <span class="trend">Dự kiến hôm nay</span>
                </div>
                <p class="sub-label">Lượt trả phòng hôm nay</p>
            </div>
        </div>
    
        <div class="stat-card red">
            <div class="card-icon">🔔</div>
            <div class="card-data">
                <span class="label">CẢNH BÁO</span>
                <div class="main-val">
                    <span class="number"><?= $stats['alerts'] ?? 0 ?></span>
                    <span class="trend danger">Cần xử lý</span>
                </div>
                <p class="sub-label">Yêu cầu đang chờ</p>
            </div>
        </div>
    </div>
    
    <div class="charts-container">
        <div class="revenue-chart-box">
            <div class="box-header">
                <h3>Xu hướng doanh thu</h3>
                <p>Hiệu suất hàng ngày trong 30 ngày qua</p>
            </div>
            <?php if(!empty($revenueTrends)):?>
                <div class="chart-area" style="position: relative; height:300px; width:100%">
                    <canvas id="revenueChart"></canvas>
                </div>
            <?php else: ?>
                <div class="empty-data">
                    <span>Không có dữ liệu biểu đồ.</span>
                </div>
            <?php endif ?>
        </div>
    
        <div class="booking-sources-box">
            <div class="box-header">
                <h3>Nguồn đặt phòng</h3>
            </div>
            <div class="chart-wrapper">
                <?php if(!empty($sources)):?>
                    <div class="donut-chart-container">
                        <canvas id="bookingSourcesChart"></canvas> 
                        <div class="donut-hole">
                            <strong>100%</strong>
                            <span>KÊNH</span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-data">
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
                    $sourceNames = [
                        'WEBSITE' => 'Trang web',
                        'BOOKING_DOT_COM' => 'Booking.com',
                        'EXPEDIA' => 'Expedia',
                        'DIRECT' => 'Trực tiếp'
                    ];
                    foreach($sources as $s): 
                        $color = $colors[$s['source']] ?? '#eee';
                        $displayName = $sourceNames[$s['source']] ?? str_replace('_', ' ', $s['source']);
                    ?>
                    <li>
                        <span class="dot" style="background-color: <?= $color ?>"></span>
                        <span class="source-name"><?= $displayName ?></span>
                        <span class="val"><?= $s['percentage'] ?>%</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="recent-activity-box">
        <div class="box-header">
            <h3>Hoạt động gần đây</h3>
            <a href="<?= URLROOT ?>/transactions" class="btn-link">XEM TẤT CẢ GIAO DỊCH →</a>
        </div>
        
        <table class="activity-table">
            <thead>
                <tr>
                    <th>Khách hàng</th>
                    <th>Loại phòng</th>
                    <th>Ngày đến</th>
                    <th>Trạng thái</th>
                    <th style="text-align: right;">Số tiền</th>
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
                        <td><?= date('d/m/Y', strtotime($act['checkIn'])) ?></td>
                        <td>
                            <span class="badge <?= strtolower($act['status'] ?? 'pending') ?>">
                                <?php 
                                    $statusVn = [
                                        'PAID' => 'Đã thanh toán',
                                        'PENDING' => 'Chờ xử lý',
                                        'REFUNDED' => 'Hoàn tiền',
                                        'FAILED' => 'Thất bại'
                                    ];
                                    echo $statusVn[strtoupper($act['status'])] ?? $act['status'];
                                ?>
                            </span>
                        </td>
                        <td class="amount-cell" style="text-align: right;">
                            <strong><?= number_format($act['amount'], 0, ',', '.') ?>đ</strong>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                            Không tìm thấy hoạt động nào gần đây.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Khởi tạo Biểu đồ Doanh thu (Line Chart)
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
                label: 'Doanh thu (đ)',
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
                    ticks: { 
                        callback: (val) => val.toLocaleString() + 'đ' 
                    }
                }
            }
        }
    });

    // 2. Khởi tạo Biểu đồ Nguồn đặt phòng (Doughnut Chart)
    const rawSourceData = <?= json_encode($sources ?? []) ?>;
    const sourceNamesVn = {
        'WEBSITE': 'Trang web',
        'BOOKING_DOT_COM': 'Booking.com',
        'EXPEDIA': 'Expedia',
        'DIRECT': 'Trực tiếp'
    };
    const srcLabels = rawSourceData.map(s => sourceNamesVn[s.source] || s.source);
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
            cutout: '75%',
            plugins: { legend: { display: false } }
        }
    });
</script>