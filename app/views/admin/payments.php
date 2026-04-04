<div class="payments-container">
  <h1 class="payments-title">Quản lý thanh toán</h1>

  <!-- ===== STATS ===== -->
  <div class="payments-stats-grid">

    <div id="payments-count-div" class="payments-stat-card aaa"><?php echo count($payments); ?> <span>Tổng hóa đơn</span></div>

    <div id="payments-paid-div" class="payments-stat-card bbb">
      <?php echo count(array_filter($payments, fn($p) => $p['paymentStatus'] == 'PAID')); ?>
      <span>Giao dịch thành công</span>
    </div>

    <div id="payments-pending-div" class="payments-stat-card ccc">
      <?php echo count(array_filter($payments, fn($p) => $p['paymentStatus'] == 'PENDING')); ?>
      <span>Đang chờ duyệt</span>
    </div>

    <div id="payments-faild-div" class="payments-stat-card ddd">
      <?php echo count(array_filter($payments, fn($p) => $p['paymentStatus'] == 'REFUNDED' || $p['paymentStatus'] == 'FAILED')); ?>
      <span>Hoàn tiền / Thất bại</span>
    </div>
  </div>

  <!-- ===== SUMMARY ===== -->
  <div class="payments-summary">
    <!-- Tổng doanh thu -->
    <div id="payments-sum-div" class="payments-summary aaa">
      <?php
      $paidPayments = array_filter($payments, fn($p) => $p['paymentStatus'] === 'PAID');
      echo number_format(array_sum(array_column($paidPayments, 'amount')), 0, ',', '.');
      ?>
      <span>Tổng doanh thu</span>
    </div>

    <!-- Phí nền tảng chỉ của các giao dịch PAID -->
    <div id="payments-platformm-div" class="payments-summary ccc">
      <?php
      echo number_format(array_sum(array_column($paidPayments, 'platformFee')), 0, ',', '.');
      ?> đ
      <span>Phí nền tảng</span>
    </div>

    <!-- Tiền đối tác chỉ của các giao dịch PAID -->
    <div id="payments-partnerFee-div" class="payments-summary ddd">
      <?php
      echo number_format(array_sum(array_column($paidPayments, 'partnerRevenue')), 0, ',', '.');
      ?> đ
      <span>Tiền đối tác</span>
    </div>
  </div>

  <!-- ===== FILTER ===== -->
  <div class="payments-filter">
    <input id="paymentSearch" type="text" placeholder="Tìm theo booking..." class="payments-input">

    <select id="paymentStatus" class="payments-select">
      <option value="">Trạng thái</option>
      <option value="PENDING">PENDING</option>
      <option value="PAID">PAID</option>
      <option value="FAILED">FAILED</option>
      <option value="REFUNDED">REFUNDED</option>
    </select>

    <select id="paymentMethod" class="payments-select">
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
          <tr
            data-booking="<?php echo $payment['bookingId']; ?>"
            data-status="<?php echo $payment['paymentStatus']; ?>"
            data-method="<?php echo $payment['paymentMethod']; ?>"
            data-amount="<?php echo $payment['amount']; ?>"
            data-platform-fee="<?php echo $payment['platformFee']; ?>"
            data-partner-revenue="<?php echo $payment['partnerRevenue']; ?>">
            <td><?php echo $payment['id']; ?></td>
            <td>#<?php echo $payment['bookingId']; ?></td>
            <td><?php echo $payment['fullName']; ?></td>
            <td><?php echo number_format($payment['amount'], 0, ',', '.'); ?>đ</td>
            <td><?php echo $payment['paymentMethod']; ?></td>

            <td>
              <span class="payments-badge <?php echo strtolower($payment['paymentStatus']); ?>">
                <?php echo $payment['paymentStatus']; ?>
              </span>
            </td>

            <td>
              <?php echo !empty($payment['createdAt']) ? date('d/m/Y', strtotime($payment['createdAt'])) : 'N/A'; ?>
            </td>

            <td>
              <button class="payments-btn-view">Xem</button>

              <?php if ($payment['paymentStatus'] == 'PENDING'): ?>
                <button class="payments-btn-success">Xác nhận</button>
              <?php endif; ?>

              <?php if ($payment['paymentStatus'] == 'PAID'): ?>
                <button class="payments-btn-danger">Hoàn tiền</button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div id="view-modal-payment" class="payments-modal">
    <div class="payments-modal-content">
      <h2>Chi tiết thanh toán</h2>

      <p><strong>ID:</strong> <span id="payments-view-id"></span></p>
      <p><strong>Booking:</strong> <span id="payments-view-booking"></span></p>
      <p><strong>Khách hàng:</strong> <span id="payments-view-name"></span></p>
      <p><strong>Số tiền:</strong> <span id="payments-view-amount"></span></p>
      <p><strong>Phương thức:</strong> <span id="payments-view-method"></span></p>
      <p><strong>Trạng thái:</strong> <span id="payments-view-status"></span></p>
      <p><strong>Ngày:</strong> <span id="payments-view-date"></span></p>

      <button id="close-modal-payment">Đóng</button>
    </div>
  </div>






</div>