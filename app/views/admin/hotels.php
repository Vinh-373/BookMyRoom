<div class="hotels-content">

    <!-- HEADER -->
    <div class="hotels-header">
        <h2 class="hotels-title">Quản lý khách sạn</h2>

        <!-- STATS -->
        <div class="hotels-stats-grid">
            <div class="hotels-stat-card">
                <div class="num"><?php echo count($hotels); ?></div>
                <div class="label">Tổng KS</div>
            </div>

            <div class="hotels-stat-card">
                <div class="num">
                    <?php 
                        $ratings = array_column($hotels, 'rating');
                        echo number_format(array_sum($ratings)/max(count($ratings),1),1);
                    ?>
                </div>
                <div class="label">Rating</div>
            </div>

            <div class="hotels-stat-card">
                <div class="num"><?php echo array_sum(array_column($hotels,'totalRooms')); ?></div>
                <div class="label">Phòng</div>
            </div>

            <div class="hotels-stat-card">
                <div class="num"><?php echo array_sum(array_column($hotels,'totalBookings')); ?></div>
                <div class="label">Booking</div>
            </div>

            <div class="hotels-stat-card">
                <div class="num"><?php echo number_format(array_sum(array_column($hotels,'totalRevenue'))); ?>đ</div>
                <div class="label">Doanh thu</div>
            </div>
        </div>

        <!-- TOOLBAR -->
        <div class="hotels-toolbar">
            <input type="text" id="search" placeholder="🔍 Tìm khách sạn...">

            <select id="filterPartner">
                <option value="">Tất cả đối tác</option>
                <?php foreach ($partners as $p): ?>
                    <option value="<?php echo $p['companyName']; ?>">
                        <?php echo $p['companyName']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <a href="?action=create" class="btn-add">+ Thêm</a>
        </div>
    </div>

    <!-- LIST -->
    <div class="hotels-grid" id="hotelsList">
        <?php foreach ($hotels as $hotel): ?>
        <div class="hotel-card"
            data-name="<?php echo strtolower($hotel['hotelName']); ?>"
            data-partner="<?php echo $hotel['companyName']; ?>">

            <div class="img">
                <img src="<?php echo $hotel['image'] ?? 'default.jpg'; ?>">
                <span class="rating">⭐ <?php echo $hotel['rating'] ?? 0; ?></span>
            </div>

            <div class="info">
                <h3><?php echo $hotel['hotelName']; ?></h3>

                <p>📍 <?php echo $hotel['address']; ?></p>
                <p><?php echo $hotel['wardName']; ?>, <?php echo $hotel['cityName']; ?></p>
                <p>🏢 <?php echo $hotel['companyName'] ?? '---'; ?></p>

                <div class="meta">
                    <span>🛏 <?php echo $hotel['totalRooms']; ?></span>
                    <span>📖 <?php echo $hotel['totalBookings']; ?></span>
                    <span>💰 <?php echo number_format($hotel['totalRevenue']); ?></span>
                </div>

                <div class="actions">
                    <a href="?action=detail&id=<?php echo $hotel['id']; ?>">Xem</a>
                    <a href="?action=edit&id=<?php echo $hotel['id']; ?>">Sửa</a>
                    <a href="?action=delete&id=<?php echo $hotel['id']; ?>" 
                       onclick="return confirm('Xóa?')">Xóa</a>
                </div>
            </div>

        </div>
        <?php endforeach; ?>
    </div>

</div>