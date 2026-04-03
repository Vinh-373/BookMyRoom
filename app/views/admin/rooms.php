<div class="page-header">
    <h1>Quản lý phòng</h1>
    <p>Theo dõi và cập nhật trạng thái phòng của các đối tác khách sạn</p>
</div>

<!-- ===== STATS ===== -->
<div class="stats-container">
    <div class="stat-card">
        <p>Tổng số phòng</p>
        <h2><?php echo isset($totalRooms) ? number_format($totalRooms) : 0; ?></h2>
    </div>

    <div class="stat-card success">
        <p>Phòng trống</p>
        <h2><?php echo isset($availableRooms) ? number_format($availableRooms) : 0; ?></h2>
    </div>

    <div class="stat-card primary">
        <p>Đang có khách</p>
        <h2><?php echo isset($bookedRooms) ? number_format($bookedRooms) : 0; ?></h2>
    </div>

    <div class="stat-card danger">
        <p>Bảo trì</p>
        <h2><?php echo isset($maintenanceRooms) ? number_format($maintenanceRooms) : 0; ?></h2>
    </div>
</div>

<!-- ===== FILTER ===== -->
<div class="filter-container">
    <input type="text" id="search-room" class="rooms-search-input" placeholder="Tên khách sạn...">

    <select id="filter-hotel" class="rooms-filter-select" data-filter="hotel">
        <option value="">Tất cả khách sạn</option>
        <?php if (isset($hotels) && is_array($hotels)): ?>
            <?php foreach ($hotels as $hotel): ?>
                <option value="<?php echo $hotel['id']; ?>"><?php echo htmlspecialchars($hotel['hotelName']); ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>

    <select id="filter-type" class="rooms-filter-select" data-filter="roomType">
        <option value="">Tất cả hạng phòng</option>
        <?php if (isset($roomTypes) && is_array($roomTypes)): ?>
            <?php foreach ($roomTypes as $type): ?>
                <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['typeName']); ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>

    <select id="filter-status" class="rooms-filter-select" data-filter="status">
        <option value="">Tất cả trạng thái</option>
        <option value="AVAILABLE">Còn trống</option>
        <option value="BOOKED">Đã đặt</option>
        <option value="MAINTENANCE">Bảo trì</option>
    </select>

    <button id="btn-filter" class="rooms-filter-btn">Lọc dữ liệu</button>
</div>

<!-- ===== ROOM LIST ===== -->
<div class="room-grid" id="room-list">
    <?php if (isset($rooms) && is_array($rooms) && !empty($rooms)): ?>
        <?php foreach ($rooms as $room): ?>
            <div class="room-card" data-room-id="<?php echo $room['id']; ?>">
                <div class="room-img">
                    <img src="public/images/room-default.jpg" alt="Room image">
                    <span class="badge <?php 
                        echo $room['status'] === 'AVAILABLE' ? 'available' : 
                             ($room['status'] === 'BOOKED' ? 'booked' : 'maintenance'); 
                    ?>">
                        <?php 
                            echo $room['status'] === 'AVAILABLE' ? 'Còn trống' :
                                 ($room['status'] === 'BOOKED' ? 'Đã đặt' : 'Bảo trì');
                        ?>
                    </span>
                </div>

                <div class="room-body">
                    <p class="hotel-name"><?php echo htmlspecialchars($room['hotelName'] ?? 'N/A'); ?></p>
                    <h3>
                        <?php echo htmlspecialchars($room['roomType'] ?? 'Unknown'); ?>
                        #<?php echo htmlspecialchars($room['roomNumber']); ?>
                    </h3>
                    <p class="price"><?php echo number_format($room['price'] ?? 0); ?>đ</p>

                    <div class="room-info">
                        <span>🛏 Tầng <?php echo $room['floor']; ?></span>
                        <span>👤 <?php echo $room['maxPeople']; ?> người</span>
                        <span>📐 <?php echo $room['area']; ?>m²</span>
                    </div>

                    <div class="room-actions">
                        <!-- <button class="btn-edit room-view-btn" data-room-id="<?php echo $room['id']; ?>">Xem</button>
                        <button class="btn-delete room-edit-btn" data-room-id="<?php echo $room['id']; ?>">Sửa</button> -->
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
            <p>Không tìm thấy phòng nào</p>
        </div>
    <?php endif; ?>
</div>

<!-- ===== PAGINATION ===== -->
<div class="pagination">
    <?php if (isset($totalPages) && $totalPages > 0): ?>
        <?php for ($i = 1; $i <= min($totalPages, 5); $i++): ?>
            <button class="rooms-pagination-btn <?php echo $i === 1 ? 'active' : ''; ?>" data-page="<?php echo $i; ?>">
                <?php echo $i; ?>
            </button>
        <?php endfor; ?>
    <?php endif; ?>
</div>