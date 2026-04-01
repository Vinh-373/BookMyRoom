<div class="payments-container">
  <h1 class="payments-title">Quản lý thanh toán</h1>

  <!-- ===== STATS ===== -->
  <div class="payments-stats-grid">
    <div class="payments-stat-card">
      <?php
      $paidPayments = array_filter($payments, fn($p) => $p['paymentStatus'] == 'PAID');
      echo number_format(array_sum(array_column($paidPayments, 'amount')), 0, ',', '.');
      ?>
      <span>Tổng doanh thu</span>
    </div>

    <div class="payments-stat-card">
      <?php echo count(array_filter($payments, fn($p) => $p['paymentStatus'] == 'PAID')); ?>
      <span>Giao dịch thành công</span>
    </div>

    <div class="payments-stat-card">
      <?php echo count(array_filter($payments, fn($p) => $p['paymentStatus'] == 'PENDING')); ?>
      <span>Đang chờ duyệt</span>
    </div>

    <div class="payments-stat-card">
      <?php echo count(array_filter($payments, fn($p) => $p['paymentStatus'] == 'REFUNDED' || $p['paymentStatus'] == 'FAILED')); ?>
      <span>Hoàn tiền / Thất bại</span>
    </div>
  </div>

  <!-- ===== SUMMARY ===== -->
  <div class="payments-summary">
    <div><?php echo count($payments); ?> <span>Tổng hóa đơn</span></div>

    <div>
      <?php echo number_format(array_sum(array_column($payments, 'amount')), 0, ',', '.'); ?>đ
      <span>Tổng tiền</span>
    </div>

    <div>
      <?php echo number_format(array_sum(array_column($payments, 'platformFee')), 0, ',', '.'); ?>đ
      <span>Phí nền tảng</span>
    </div>

    <div>
      <?php echo number_format(array_sum(array_column($payments, 'partnerRevenue')), 0, ',', '.'); ?>đ
      <span>Tiền đối tác</span>
    </div>
  </div>

  <!-- ===== FILTER ===== -->
  <div class="payments-filter">
    <input type="text" placeholder="Tìm theo booking..." class="payments-input">

    <select class="payments-select">
      <option value="">Trạng thái</option>
      <option value="PENDING">PENDING</option>
      <option value="PAID">PAID</option>
      <option value="FAILED">FAILED</option>
      <option value="REFUNDED">REFUNDED</option>
    </select>

    <select class="payments-select">
      <option value="">Phương thức</option>
      <option value="MOMO">MOMO</option>
      <option value="VNPAY">VNPAY</option>
    </select>
  </div>

  <!-- ===== TABLE ===== -->
  <div class="payments-table-box">
    <table class="payments-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Booking</th>
          <th>Khách hàng</th>
          <th>Số tiền</th>
          <th>Phương thức</th>
          <th>Trạng thái</th>
          <th>Ngày</th>
          <th>Hành động</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($payments as $payment): ?>
          <tr>
            <td><?php echo $payment['id']; ?></td>
            <td>#<?php echo $payment['bookingId']; ?></td>
            <td><?php echo $payment['fullName']; ?></td>
            <td><?php echo number_format($payment['amount'], 0, ',', '.'); ?>đ</td>
            <td><?php echo $payment['paymentMethod']; ?></td>

            <td>
              <span class="badge <?php echo strtolower($payment['paymentStatus']); ?>">
                <?php echo $payment['paymentStatus']; ?>
              </span>
            </td>

            <td>
              <?php echo !empty($payment['createdAt']) ? date('d/m/Y', strtotime($payment['createdAt'])) : 'N/A'; ?>
            </td>

            <td>
              <button class="btn btn-view">Xem</button>

              <?php if ($payment['paymentStatus'] == 'PENDING'): ?>
                <button class="btn btn-success">Xác nhận</button>
              <?php endif; ?>

              <?php if ($payment['paymentStatus'] == 'PAID'): ?>
                <button class="btn btn-danger">Hoàn tiền</button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>