<div class = "portfolio-wrapper">
    <section class="dashboard-header">
        <div class="welcome-msg">
            <h1>Welcome back, <?= $_SESSION['user_name'] ?? 'Partner' ?>!</h1>
            <p>Which hotel would you like to manage today?</p>
        </div>
        <button class="btn-add-property" onclick="window.location.href='<?= URLROOT ?>/hotels/add'">+ Add New Property</button>
    </section>
    
    <div class="hotel-grid">
        <?php if (!empty($hotels)): ?>
            <?php foreach ($hotels as $hotel): ?>
            <div class="hotel-card">
                <div class="card-thumb">
                    <img src="<?= URLIMAGE ?>/<?= $hotel['imageUrl'] ?? 'h1.png' ?>" alt="<?= $hotel['hotelName'] ?>">
                    <button class="btn-more">⋮</button>
                </div>
                <div class="card-body">
                    <h3><?= $hotel['hotelName'] ?></h3>
                    <p class="location">📍 <?= $hotel['address'] ?>, <?= $hotel['wardName'] ?>, <?= $hotel['cityName'] ?></p>
                    
                    <div class="card-stats">
                        <div class="stat-box">
                            <span class="label">TOTAL ROOMS</span>
                            <span class="val">🛏️ <?= $hotel['total_rooms'] ?></span>
                        </div>
                        <div class="stat-box">
                            <span class="label">RATING</span>
                            <span class="val">⭐ <?= number_format($hotel['rating'], 1) ?></span>
                        </div>
                    </div>
                    <a href="<?= URLROOT ?>/manage/<?= $hotel['id'] ?>" class="btn-manage">GO TO MANAGEMENT →</a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>You don't have any properties yet. Start by adding one!</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="global-stats-bar">
        <div class="stat-group">
            <div class="stat-icon">💳</div>
            <div class="stat-info">
                <span class="label">CHAIN TOTAL REVENUE (MTD)</span>
                <span class="value">$<?= $chain_revenue ?></span>
            </div>
        </div>
        <div class="stat-group">
            <div class="stat-icon">📅</div>
            <div class="stat-info">
                <span class="label">TOTAL BOOKINGS (MTD)</span>
                <span class="value"><?= $total_bookings ?></span>
            </div>
        </div>
        <div class="portfolio-health">
            <div class="health-meta">
                <span class="label">PORTFOLIO HEALTH</span>
                <span class="percentage"><?= $portfolio_health ?>%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?= $portfolio_health ?>%"></div>
            </div>
            <button class="btn-download" title="Download Report">📥</button>
        </div>
    </div>
</div>