<div class="statisticals-content">
    <h1 class="statisticals-title">Thống kê</h1>

    <?php
    // ===== FIX COUNT ROLE =====
    function countUsersByRole($user_roles, $roleId)
    {
        $userIds = array_unique(array_column(
            array_filter($user_roles, fn($r) => $r['roleId'] == $roleId),
            'userId'
        ));
        return count($userIds);
    }
    // Lọc các giao dịch đã thanh toán
    $paidPayments = array_filter($payments, fn($p) => $p['paymentStatus'] === 'PAID');

    // Tính tổng phí nền tảng cho các giao dịch PAID
    $totalPlatformFee = array_sum(array_column($paidPayments, 'platformFee'));

    // Tính tổng tiền đối tác cho các giao dịch PAID
    $totalPartnerRevenue = array_sum(array_column($paidPayments, 'partnerRevenue'));
    // ===== USERS =====
    $totalUsers = count($users);
    $customersCount = countUsersByRole($user_roles, 3);
    $partnersCount = countUsersByRole($user_roles, 2);
    $adminsCount = countUsersByRole($user_roles, 1);

    // ===== HOTELS =====
    $totalHotels = count($hotels);

    // ===== ROOMS =====
    $totalRoomConfigs = count($roomconfigurations);
    $totalPhysicalRooms = count($physicalrooms);

    // ===== BOOKINGS =====
    $totalBookings = count($bookings);

    $completedBookings = array_filter($bookings, fn($b) => $b['status'] == 'COMPLETED');
    $confirmedBookings = array_filter($bookings, fn($b) => $b['status'] == 'CONFIRMED');

    $pendingBookings = array_filter($bookings, fn($b) => $b['status'] == 'PENDING');
    $cancelledBookings = array_filter($bookings, fn($b) => $b['status'] == 'CANCELLED');

    // ===== REVENUE (BOOKING) =====
    $totalRevenue = array_sum(array_column($completedBookings, 'totalAmount'));

    // ===== REVIEWS =====
    $avgRating = count($reviews) > 0
        ? array_sum(array_column($reviews, 'rating')) / count($reviews)
        : 0;

    // ===== TOP HOTEL =====
    $hotelBookingCount = [];

    foreach ($bookingdetails as $bd) {
        foreach ($roomconfigurations as $rc) {
            if ($rc['id'] == $bd['roomConfigId']) {
                $hotelId = $rc['hotelId'];
                $hotelBookingCount[$hotelId] = ($hotelBookingCount[$hotelId] ?? 0) + 1;
            }
        }
    }
    arsort($hotelBookingCount);
    $topHotels = array_slice($hotelBookingCount, 0, 3, true);
    ?>

    <!-- ===== SYSTEM OVERVIEW ===== -->
    <div class="statisticals-section">
        <h2 class="statisticals-section-title">Tổng quan hệ thống</h2>

        <div class="statisticals-summary">

            <div class="statisticals-card">
                <h3>Tổng user</h3>
                <p><?= $totalUsers ?></p>
            </div>
            <div class="statisticals-card">
                <h3>Khách hàng</h3>
                <p><?= $customersCount ?></p>
            </div>
            <div class="statisticals-card">
                <h3>Đối tác</h3>
                <p><?= $partnersCount ?></p>
            </div>
            <div class="statisticals-card">
                <h3>Admin</h3>
                <p><?= $adminsCount ?></p>
            </div>

            <div class="statisticals-card">
                <h3>Khách sạn</h3>
                <p><?= $totalHotels ?></p>
            </div>

            <div class="statisticals-card">
                <h3>Loại phòng</h3>
                <p><?= $totalRoomConfigs ?></p>
            </div>
            <div class="statisticals-card">
                <h3>Phòng</h3>
                <p><?= $totalPhysicalRooms ?></p>
            </div>

            <div class="statisticals-card">
                <h3>Tổng booking</h3>
                <p><?= $totalBookings ?></p>
            </div>
            <div class="statisticals-card">
                <h3>Booking đã xác nhận</h3>
                <p><?= count($confirmedBookings) ?></p>
            </div>
            <div class="statisticals-card">
                <h3>Booking hoàn thành</h3>
                <p><?= count($completedBookings) ?></p>
            </div>
            <div class="statisticals-card">
                <h3>Booking đang chờ</h3>
                <p><?= count($pendingBookings) ?></p>
            </div>
            <div class="statisticals-card">
                <h3>Booking đã huỷ</h3>
                <p><?= count($cancelledBookings) ?></p>
            </div>

            <div class="statisticals-card">
                <h3>Doanh thu</h3>
                <p><?= number_format($totalRevenue, 0, ',', '.') ?> đ</p>
            </div>
            <div class="statisticals-card">
                <h3>Phí nền tảng</h3>
                <p><?= number_format($totalPlatformFee, 0, ',', '.') ?> đ</p>
            </div>
            <div class="statisticals-card">
                <h3>Tiền đối tác</h3>
                <p><?= number_format($totalPartnerRevenue, 0, ',', '.') ?> đ</p>
            </div>

            <div class="statisticals-card">
                <h3>Đánh giá TB</h3>
                <p><?= number_format($avgRating, 1) ?>★</p>
            </div>

        </div>
    </div>

    <!-- ===== TOP HOTEL ===== -->
    <div class="statisticals-methods">
        <h2>Top khách sạn</h2>
        <?php foreach ($topHotels as $hotelId => $count): ?>
            <div class="statisticals-method-item">
                Hotel ID <?= $hotelId ?> - <?= $count ?> booking
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ===== FILTER ===== -->
    <form method="GET" class="statisticals-filter">
        <input type="date" name="from" value="<?= $_GET['from'] ?? '' ?>">
        <input type="date" name="to" value="<?= $_GET['to'] ?? '' ?>">
        <button type="submit">Lọc</button>
    </form>

    <?php
    $from = $_GET['from'] ?? null;
    $to = $_GET['to'] ?? null;

    // ===== FILTER PAYMENT =====
    $filteredPayments = array_filter($payments, function ($p) use ($from, $to) {
        $date = date('Y-m-d', strtotime($p['createdAt']));
        if ($from && $date < $from) return false;
        if ($to && $date > $to) return false;
        return true;
    });

    $paid = array_filter($filteredPayments, fn($p) => $p['paymentStatus'] == 'PAID');
    $totalPaid = array_sum(array_column($paid, 'amount'));

    // ===== REVENUE BY DATE =====
    $revenueByDate = [];
    foreach ($paid as $p) {
        $date = date('Y-m-d', strtotime($p['createdAt']));
        $revenueByDate[$date] = ($revenueByDate[$date] ?? 0) + $p['amount'];
    }
    ?>

    <!-- ===== PAYMENT SUMMARY ===== -->
    <div class="statisticals-summary">
        <div class="statisticals-card">
            <h3>Doanh thu thanh toán</h3>
            <p><?= number_format($totalPaid, 0, ',', '.') ?> đ</p>
        </div>
        <div class="statisticals-card">
            <h3>Giao dịch</h3>
            <p><?= count($filteredPayments) ?></p>
        </div>
    </div>

    <!-- ===== REVENUE BY DATE ===== -->
    <div class="statisticals-methods">
        <h2>Doanh thu theo ngày</h2>
        <?php foreach ($revenueByDate as $date => $money): ?>
            <div class="statisticals-method-item">
                <?= $date ?> - <?= number_format($money, 0, ',', '.') ?> đ
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="statisticals-table">
        <h2>Chi tiết giao dịch</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Booking</th>
                    <th>Số tiền</th>
                    <th>Phương thức</th>
                    <th>Trạng thái</th>
                    <th>Ngày</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filteredPayments as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= $p['bookingId'] ?></td>
                        <td><?= number_format($p['amount'], 0, ',', '.') ?> đ</td>
                        <td><?= $p['paymentMethod'] ?></td>
                        <td class="status-<?= strtolower($p['paymentStatus']) ?>">
                            <?= $p['paymentStatus'] ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($p['createdAt'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>