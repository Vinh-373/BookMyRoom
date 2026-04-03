<div class="page-container">
    <!-- Tiêu đề + button -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Quản lý khách sạn</h1>
            <p class="page-subtitle">Điều hành và giám sát hệ thống đối tác khách sạn cao cấp toàn cầu.</p>
        </div>
        
    </div>

    <!-- Thống kê -->
    <div class="stats-grid">
        <div class="stat-card">
            <p class="stat-label">Tổng đối tác</p>
            <h2><?php echo isset($totalHotels) ? $totalHotels : 0; ?></h2>
        </div>

        <div class="stat-card">
            <p class="stat-label">Đang hoạt động</p>
            <h2><?php echo isset($activeHotels) ? $activeHotels : 0; ?></h2>
        </div>

        <div class="stat-card">
            <p class="stat-label">Chờ duyệt</p>
            <h2><?php echo isset($pendingHotels) ? $pendingHotels : 0; ?></h2>
        </div>

        <div class="stat-card highlight">
            <p class="stat-label">Doanh thu đối tác</p>
            <h2>15.2 tỷ VNĐ</h2>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="filter-bar">
        <input type="text" class="hotels-search-input" placeholder="Tìm kiếm tên khách sạn, mã đối tác..." />

        <select class="hotels-filter-select" data-filter="cityId">
            <option value="">Thành phố</option>
            <?php
            if (isset($cities) && is_array($cities)) {
                foreach ($cities as $city) {
                    echo '<option value="' . htmlspecialchars($city['id']) . '">' . htmlspecialchars($city['name']) . '</option>';
                }
            }
            ?>
        </select>

        <select class="hotels-filter-select" data-filter="status">
            <option value="">Trạng thái</option>
            <option value="active">Hoạt động</option>
            <option value="pending">Chờ duyệt</option>
            <option value="suspended">Tạm dừng</option>
        </select>

        <select class="hotels-filter-select" data-filter="rating">
            <option value="">Hạng sao</option>
            <option value="5">5 sao</option>
            <option value="4">4 sao</option>
            <option value="3">3 sao</option>
        </select>
        <button class="hotels-filter-btn btn-primary">Lọc dữ liệu</button>
        <button class="btn-secondary">Reset</button>
    </div>

    <!-- Bảng khách sạn -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tên khách sạn</th>
                    <th>Địa điểm</th>
                    <th>Phòng</th>
                    <th>Tỷ lệ lấp đầy</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if (isset($hotels) && is_array($hotels) && count($hotels) > 0) {
                    foreach ($hotels as $index => $hotel) {
                        // Mock occupancy data
                        $occupancyRates = [85, 92, 78, 88, 90, 75, 82, 95, 70, 86];
                        $occupancyRate = isset($occupancyRates[$index % count($occupancyRates)]) ? $occupancyRates[$index % count($occupancyRates)] : 80;
                        
                        // Status
                        $status = 'active';
                        $statusText = 'Hoạt động';
                        $statusClass = 'active';
                        
                        if ($index % 3 == 1) {
                            $status = 'pending';
                            $statusText = 'Chờ duyệt';
                            $statusClass = 'pending';
                        } elseif ($index % 5 == 0) {
                            $status = 'suspended';
                            $statusText = 'Tạm dừng';
                            $statusClass = 'pause';
                        }
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($hotel['hotelName'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($hotel['cityName'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($hotel['id'] * 10); ?></td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar" style="width:<?php echo $occupancyRate; ?>%"></div>
                        </div>
                        <?php echo $occupancyRate; ?>%
                    </td>
                    <td><span class="status <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                    <td>
                        <button class="hotel-view-btn btn-action" data-hotel-id="<?php echo $hotel['id']; ?>">Xem</button>
                        <button class="hotel-edit-btn btn-action" data-hotel-id="<?php echo $hotel['id']; ?>">Sửa</button>
                        <button class="hotel-block-btn btn-action" data-hotel-id="<?php echo $hotel['id']; ?>">Khóa</button>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="6" style="text-align:center; padding:20px;">Không có dữ liệu khách sạn</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php
        // Calculate pagination
        $totalHotels = isset($totalHotels) ? $totalHotels : 0;
        $limit = 10;
        $totalPages = ceil($totalHotels / $limit);
        
        if ($totalPages > 0) {
            for ($i = 1; $i <= min($totalPages, 5); $i++) {
                $activeClass = ($i == 1) ? 'active' : '';
                echo '<button class="hotel-pagination-btn ' . $activeClass . '">' . $i . '</button>';
            }
            
            if ($totalPages > 5) {
                echo '<button class="pagination-dots" disabled>...</button>';
                echo '<button class="hotel-pagination-btn">' . $totalPages . '</button>';
            }
        } else {
            echo '<button class="hotel-pagination-btn active">1</button>';
        }
        ?>
    </div>
</div>