<div class="portfolio-wrapper">
    <section class="dashboard-header">
        <div class="welcome-msg">
            <h1>Chào mừng trở lại, <?= $_SESSION['user_name'] ?? 'Đối tác' ?>!</h1>
            <p>Bạn muốn quản lý khách sạn nào trong hôm nay?</p>
        </div>
        <button class="btn-add-property" onclick="window.location.href='<?= URLROOT ?>/hotels/add'">+ Thêm khách sạn mới</button>
    </section>
    
    <div class="hotel-grid">
        <?php if (!empty($hotels)): ?>
            <?php foreach ($hotels as $hotel): ?>
            <div class="hotel-card">
                <div class="card-thumb">
                    <img src="<?= URLIMAGE ?>/<?= $hotel['imageUrl'] ?? 'h1.png' ?>" alt="<?= $hotel['hotelName'] ?>">
                    <button class="btn-more" title="Thêm tùy chọn">⋮</button>
                </div>
                <div class="card-body">
                    <h3><?= $hotel['hotelName'] ?></h3>
                    <p class="location">📍 <?= $hotel['address'] ?>, <?= $hotel['wardName'] ?>, <?= $hotel['cityName'] ?></p>
                    
                    <div class="card-stats">
                        <div class="stat-box">
                            <span class="label">TỔNG SỐ PHÒNG</span>
                            <span class="val">🛏️ <?= $hotel['total_rooms'] ?></span>
                        </div>
                        <div class="stat-box">
                            <span class="label">ĐÁNH GIÁ</span>
                            <span class="val">⭐ <?= number_format($hotel['rating'], 1) ?></span>
                        </div>
                    </div>
                    <a href="<?= URLROOT ?>/manage/<?= $hotel['id'] ?>" class="btn-manage">ĐI ĐẾN QUẢN LÝ →</a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>Bạn chưa có khách sạn nào. Hãy bắt đầu bằng việc thêm khách sạn mới!</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="global-stats-bar">
        <div class="stat-group">
            <div class="stat-icon">💳</div>
            <div class="stat-info">
                <span class="label">TỔNG DOANH THU (THÁNG)</span>
                <span class="value"><?= number_format((float) str_replace(',', '', $chain_revenue), 0, ',', '.') ?>đ</span>
            </div>
        </div>
        <div class="stat-group">
            <div class="stat-icon">📅</div>
            <div class="stat-info">
                <span class="label">TỔNG ĐƠN ĐẶT (THÁNG)</span>
                <span class="value"><?= number_format($total_bookings) ?></span>
            </div>
        </div>
        <div class="portfolio-health">
            <div class="health-meta">
                <span class="label">SỨC KHỎE HỆ THỐNG</span>
                <span class="percentage"><?= $portfolio_health ?>%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?= $portfolio_health ?>%"></div>
            </div>
            <button class="btn-download" title="Tải báo cáo">📥</button>
        </div>
    </div>
</div>