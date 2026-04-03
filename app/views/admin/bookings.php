<div class="page-container">

    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Quản lý đặt phòng</h1>
            <p class="page-subtitle">Theo dõi và quản lý tất cả đơn đặt phòng trong hệ thống.</p>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="stats-grid">
        <div class="stat-card">
            <p class="stat-label">Tổng đơn</p>
            <h2><?php echo isset($totalBookings) ? number_format($totalBookings) : 0; ?></h2>
        </div>

        <div class="stat-card">
            <p class="stat-label">Đã xác nhận</p>
            <h2><?php echo isset($confirmedBookings) ? number_format($confirmedBookings) : 0; ?></h2>
        </div>

        <div class="stat-card">
            <p class="stat-label">Chờ xử lý</p>
            <h2><?php echo isset($pendingBookings) ? number_format($pendingBookings) : 0; ?></h2>
        </div>

        <div class="stat-card highlight">
            <p class="stat-label">Doanh thu</p>
            <h2><?php echo isset($totalRevenue) ? number_format($totalRevenue / 1000000, 1) . ' tỷ' : '0 tỷ'; ?></h2>
        </div>
    </div>

    <!-- ===== FILTER ===== -->
    <div class="filter-bar">
        <input type="text" id="search-booking" placeholder="Mã đơn, khách hàng..." />

        <select id="filter-status">
            <option value="">Trạng thái</option>
            <option value="PENDING">Chờ xử lý</option>
            <option value="CONFIRMED">Đã xác nhận</option>
            <option value="CANCELLED">Đã hủy</option>
            <option value="COMPLETED">Hoàn thành</option>
        </select>

        <select id="filter-source">
            <option value="">Nguồn đặt</option>
            <option value="WEBSITE">Website</option>
            <option value="BOOKING_DOT_COM">Booking.com</option>
            <option value="EXPEDIA">Expedia</option>
            <option value="DIRECT">Trực tiếp</option>
        </select>

        <input type="date" id="filter-from">
        <input type="date" id="filter-to">

        <button id="btn-filter" class="btn-primary">Lọc dữ liệu</button>
        <button id="btn-reset" class="btn-secondary">Reset</button>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>

            <tbody id="booking-table-body">
                <?php if (isset($bookings) && is_array($bookings) && !empty($bookings)): ?>
                    <?php foreach ($bookings as $booking): ?>
                        <tr data-booking-id="<?php echo $booking['id']; ?>">
                            <td>#<?php echo str_pad($booking['id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td>
                                <div>
                                    <strong><?php echo htmlspecialchars($booking['customerName'] ?? 'N/A'); ?></strong><br>
                                    <small><?php echo htmlspecialchars($booking['customerEmail'] ?? ''); ?></small>
                                </div>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($booking['createdAt'])); ?></td>
                            <td><?php echo $booking['checkInDate'] ? date('d/m/Y', strtotime($booking['checkInDate'])) : 'N/A'; ?></td>
                            <td><?php echo $booking['checkOutDate'] ? date('d/m/Y', strtotime($booking['checkOutDate'])) : 'N/A'; ?></td>
                            <td><?php echo number_format($booking['totalAmount'] ?? 0); ?>đ</td>
                            <td><?php echo $booking['source'] ?? 'N/A'; ?></td>
                            <td>
                                <span class="status <?php 
                                    echo $booking['status'] === 'PENDING' ? 'pending' : 
                                         ($booking['status'] === 'CONFIRMED' ? 'confirmed' : 
                                         ($booking['status'] === 'COMPLETED' ? 'completed' : 'cancelled'));
                                ?>">
                                    <?php 
                                        echo $booking['status'] === 'PENDING' ? 'Chờ xử lý' :
                                             ($booking['status'] === 'CONFIRMED' ? 'Đã xác nhận' :
                                             ($booking['status'] === 'COMPLETED' ? 'Hoàn thành' : 'Đã hủy'));
                                    ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn-action booking-detail-btn" data-booking-id="<?php echo $booking['id']; ?>">Chi tiết</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 20px;">
                            Không tìm thấy đơn đặt phòng nào
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ===== PAGINATION ===== -->
    <div class="pagination">
        <?php if (isset($totalPages) && $totalPages > 0): ?>
            <?php for ($i = 1; $i <= min($totalPages, 5); $i++): ?>
                <button class="pagination-btn <?php echo $i === 1 ? 'active' : ''; ?>" data-page="<?php echo $i; ?>">
                    <?php echo $i; ?>
                </button>
            <?php endfor; ?>
        <?php endif; ?>
    </div>

</div>