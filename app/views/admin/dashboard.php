<div class="dashboard-content"> <!-- Tổng quan -->
  <h1 class="dashboard-title">Tổng quan Admin</h1>
  <p class="dashboard-subtitle">Thống kê và phân tích hệ thống booking khách sạn</p>

  <div class="dashboard-stats-grid">

    <div class="dashboard-stat-card">
      <div class="dashboard-stat-icon">$</div>
      <div class="dashboard-sum-hotels">
        <span class="dashboard-stat-value">1000</span>
        <span class="dashboard-stat-label">Tổng khách sạn</span>
        <span class="dashboard-stat-change positive">+12.5%</span>
      </div>
    </div>
    <div class="dashboard-stat-card">
      <div class="dashboard-stat-icon">📦</div>
      <div class="dashboard-sum-rooms">
        <span class="dashboard-stat-value">3,400</span>
        <span class="dashboard-stat-label">Tổng phòng</span>
        <span class="dashboard-stat-change positive">+8.2%</span>
      </div>
    </div>
    <div class="dashboard-stat-card">
      <div class="dashboard-stat-icon">📦</div>
      <div class="dashboard-sum-bookings">
        <span class="dashboard-stat-value">3,400</span>
        <span class="dashboard-stat-label">Tổng booking</span>
        <span class="dashboard-stat-change positive">+8.2%</span>
      </div>
    </div>
    <div class="dashboard-stat-card">
      <div class="dashboard-stat-icon">👥</div>
      <div class="dashboard-sum-customers">
        <span class="dashboard-stat-value">2,847</span>
        <span class="dashboard-stat-label">Tổng khách hàng</span>
        <span class="dashboard-stat-change positive">+15.3%</span>
      </div>
    </div>
    <div class="dashboard-stat-card">
      <div class="dashboard-stat-icon">📦</div>
      <div class="dashboard-sum-staffs">
        <span class="dashboard-stat-value">3,400</span>
        <span class="dashboard-stat-label">Tổng nhân viên</span>
        <span class="dashboard-stat-change positive">+8.2%</span>
      </div>
    </div>
    <div class="dashboard-stat-card">
      <div class="dashboard-stat-icon">👥</div>
      <div class="dashboard-sum-partners">
        <span class="dashboard-stat-value">2,847</span>
        <span class="dashboard-stat-label">Tổng đối tác</span>
        <span class="dashboard-stat-change positive">+15.3%</span>
      </div>
    </div>
    <div class="dashboard-stat-card">
      <div class="dashboard-stat-icon">👥</div>
      <div class="dashboard-sum-payments">
        <span class="dashboard-stat-value">2,847</span>
        <span class="dashboard-stat-label">Tổng thanh toán</span>
        <span class="dashboard-stat-change positive">+15.3%</span>
      </div>
    </div>

    <div class="dashboard-stat-card">
      <div class="dashboard-stat-icon">👥</div>
      <div class="dashboard-sum-vouchers">
        <span class="dashboard-stat-value">2,847</span>
        <span class="dashboard-stat-label">Tổng phiếu giảm giá</span>
        <span class="dashboard-stat-change positive">+15.3%</span>
      </div>
    </div>
    <div class="dashboard-stat-card">
      <div class="dashboard-stat-icon">🏢</div>
      <div class="dashboard-sum-reviews">
        <span class="dashboard-stat-value">156</span>
        <span class="dashboard-stat-label">Tổng đánh giá</span>
        <span class="dashboard-stat-change negative">-2.4%</span>
      </div>
    </div>
  </div>

  <!-- Biểu đồ & trạng thái -->
  <div class="dashboard-charts-grid"> <!-- Line chart doanh thu --><!-- Donut chart trạng thái booking -->
    <div class="dashboard-chart-card">
      <h2 class="dashboard-chart-title">Trạng thái Booking</h2> <canvas id="dashboard-donut-chart"></canvas>
      <ul class="dashboard-donut-legend">
        <li><span class="dashboard-legend-dot complete"></span> Hoàn thành - 2580</li>
        <li><span class="dashboard-legend-dot confirmed"></span> Đã xác nhận - 420</li>
        <li><span class="dashboard-legend-dot pending"></span> Chờ xử lý - 165</li>
        <li><span class="dashboard-legend-dot cancelled"></span> Đã hủy - 235</li>
      </ul>
    </div>
  </div>



<!-- Phần mở rộng dưới các biểu đồ cũ -->
<div class="dashboard-lower-grid">
  <!-- Top khách sạn -->
  <div class="dashboard-card">
    <h2 class="dashboard-card-title">
      Top khách sạn
      <a href="#" class="dashboard-link">Xem tất cả →</a>
    </h2>
    <ul class="dashboard-top-hotels">
      <li>
        <span class="dashboard-rank">1</span>
        <img class="dashboard-hotel-thumb" src="https://via.placeholder.com/60" alt="Imperial Palace Hotel">
        <div class="dashboard-hotel-info">
          <span class="dashboard-hotel-name">Imperial Palace Hotel</span>
          <span class="dashboard-hotel-location">Quận 1, TP.HCM</span>
          <span class="dashboard-hotel-rating">⭐ 4.9 - 156 booking</span>
        </div>
        <span class="dashboard-hotel-revenue">45.000.000 đ</span>
      </li>
      <li>
        <span class="dashboard-rank">2</span>
        <img class="dashboard-hotel-thumb" src="https://via.placeholder.com/60" alt="Gold Star Luxury Resort">
        <div class="dashboard-hotel-info">
          <span class="dashboard-hotel-name">Gold Star Luxury Resort</span>
          <span class="dashboard-hotel-location">Phú Quốc</span>
          <span class="dashboard-hotel-rating">⭐ 4.8 - 132 booking</span>
        </div>
        <span class="dashboard-hotel-revenue">38.000.000 đ</span>
      </li>
      <li>
        <span class="dashboard-rank">3</span>
        <img class="dashboard-hotel-thumb" src="https://via.placeholder.com/60" alt="Sunrise Grand Hotel">
        <div class="dashboard-hotel-info">
          <span class="dashboard-hotel-name">Sunrise Grand Hotel</span>
          <span class="dashboard-hotel-location">Hà Nội</span>
          <span class="dashboard-hotel-rating">⭐ 4.7 - 118 booking</span>
        </div>
        <span class="dashboard-hotel-revenue">32.000.000 đ</span>
      </li>
      <li>
        <span class="dashboard-rank">4</span>
        <img class="dashboard-hotel-thumb" src="https://via.placeholder.com/60" alt="Oceanic Beach Resort">
        <div class="dashboard-hotel-info">
          <span class="dashboard-hotel-name">Oceanic Beach Resort</span>
          <span class="dashboard-hotel-location">Đà Nẵng</span>
          <span class="dashboard-hotel-rating">⭐ 4.6 - 95 booking</span>
        </div>
        <span class="dashboard-hotel-revenue">28.000.000 đ</span>
      </li>
    </ul>
  </div>
</div>

<!-- Booking gần đây -->
<div class="dashboard-card">
  <h2 class="dashboard-card-title">
    Booking gần đây
    <a href="#" class="dashboard-link">Xem tất cả →</a>
  </h2>
  <table class="dashboard-table">
    <thead>
      <tr>
        <th>MÃ BOOKING</th>
        <th>KHÁCH HÀNG</th>
        <th>KHÁCH SẠN</th>
        <th>THỜI GIAN</th>
        <th>TỔNG TIỀN</th>
        <th>TRẠNG THÁI</th>
        <th>HÀNH ĐỘNG</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><a href="#">BK12345</a></td>
        <td>Nguyễn Văn An</td>
        <td>Imperial Palace Hotel</td>
        <td>1/4/2026 - 3/4/2026 <br><small>2 giờ trước</small></td>
        <td>3.000.000 đ</td>
        <td><span class="dashboard-status confirmed">Đã xác nhận</span></td>
        <td><span class="dashboard-action">👁️</span></td>
      </tr>
      <!-- Thêm booking khác tương tự -->
    </tbody>
  </table>
</div>
</div>