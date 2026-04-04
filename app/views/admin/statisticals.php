<div class="statisticals-content">
    <h1 class="statisticals-title">Thống kê</h1>

<?php
// ===== PAYMENTS (chỉ lấy PAID) =====
$paidPayments = array_filter($payments, fn($p) => $p['paymentStatus'] === 'PAID');

$totalPlatformFee = array_sum(array_map(fn($p) => $p['platformFee'] ?? 0, $paidPayments));
$totalPartnerRevenue = array_sum(array_map(fn($p) => $p['partnerRevenue'] ?? 0, $paidPayments));

// ===== USERS =====
$totalUsers = count($users);

$customersCount = count(array_filter($users, fn($u) => $u['role'] == 3));
$partnersCount  = count(array_filter($users, fn($u) => $u['role'] == 2));
$adminsCount    = count(array_filter($users, fn($u) => $u['role'] == 1));

// ===== HOTELS =====
$totalHotels = count($hotels);

// ===== ROOMS =====
$totalRoomConfigs = count($roomconfigurations);
$totalPhysicalRooms = count($physicalrooms);

// ===== BOOKINGS =====
$totalBookings = count($bookings);

$completedBookings = array_filter($bookings, fn($b) => $b['status'] == 'COMPLETED');
$confirmedBookings = array_filter($bookings, fn($b) => $b['status'] == 'CONFIRMED');
$pendingBookings   = array_filter($bookings, fn($b) => $b['status'] == 'PENDING');
$cancelledBookings = array_filter($bookings, fn($b) => $b['status'] == 'CANCELLED');

// ===== REVENUE =====
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

    <!-- ===== TỔNG QUAN ===== -->
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
                <h3>Đã xác nhận</h3>
                <p><?= count($confirmedBookings) ?></p>
            </div>

            <div class="statisticals-card">
                <h3>Hoàn thành</h3>
                <p><?= count($completedBookings) ?></p>
            </div>

            <div class="statisticals-card">
                <h3>Đang chờ</h3>
                <p><?= count($pendingBookings) ?></p>
            </div>

            <div class="statisticals-card">
                <h3>Đã huỷ</h3>
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
                <p><?= number_format($avgRating, 1) ?> ★</p>
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
$to   = $_GET['to'] ?? null;

// ===== FILTER PAYMENT =====
$filteredPayments = array_filter($payments, function ($p) use ($from, $to) {
    $date = date('Y-m-d', strtotime($p['createdAt']));
    if ($from && $date < $from) return false;
    if ($to && $date > $to) return false;
    return true;
});

$paid = array_filter($filteredPayments, fn($p) => $p['paymentStatus'] == 'PAID');
$totalPaid = array_sum(array_column($paid, 'amount'));

// ===== DOANH THU THEO NGÀY =====
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
            <h3>Số giao dịch</h3>
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

<div id="phantrang_statisticals"></div>

</div>



<script>
const tableRows = Array.from(document.querySelectorAll('.statisticals-table tbody tr'));
const itemsPerPage = 10; // số dòng mỗi trang
let currentPage = 1;

function showTablePage(page) {
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    tableRows.forEach((row, index) => {
        row.style.display = (index >= start && index < end) ? '' : 'none';
    });
    renderTablePagination();
}

function renderTablePagination() {
    const totalPages = Math.ceil(tableRows.length / itemsPerPage);
    const pagination = document.getElementById('phantrang_statisticals');
    pagination.innerHTML = '';

    // Nút "<" lùi trang
    const prevBtn = document.createElement('button');
    prevBtn.textContent = '<';
    prevBtn.disabled = currentPage === 1;
    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            showTablePage(currentPage);
        }
    });
    pagination.appendChild(prevBtn);

    // Các nút số trang
    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        if (i === currentPage) btn.classList.add('active');
        btn.addEventListener('click', () => {
            currentPage = i;
            showTablePage(currentPage);
        });
        pagination.appendChild(btn);
    }

    // Nút ">" tới trang tiếp
    const nextBtn = document.createElement('button');
    nextBtn.textContent = '>';
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            showTablePage(currentPage);
        }
    });
    pagination.appendChild(nextBtn);
}

// Khởi chạy phân trang
showTablePage(currentPage);
</script>

<style>
#phantrang_statisticals {
    text-align: center;
    margin-top: 15px;
}

#phantrang_statisticals button {
    margin: 2px;
    padding: 5px 10px;
    border: 1px solid #007bff;
    background-color: white;
    color: #007bff;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.2s;
}

#phantrang_statisticals button:hover {
    background-color: #007bff;
    color: white;
}

#phantrang_statisticals button.active {
    background-color: #007bff;
    color: white;
    cursor: default;
}

#phantrang_statisticals button:disabled {
    background-color: #e0e0e0;
    color: #888;
    border-color: #ccc;
    cursor: not-allowed;
}
</style>