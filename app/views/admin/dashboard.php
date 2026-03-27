<div class="dashboard-container">
    <h1>Dashboard</h1>

    <!-- Main Stats Grid -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">📊</div>
        <div class="stat-content">
          <h3>1,250</h3>
          <p>Tổng đặt phòng</p>
          <span class="stat-change positive">+12.5%</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-content">
          <h3>850M VND</h3>
          <p>Doanh thu tháng</p>
          <span class="stat-change positive">+8.2%</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">🏨</div>
        <div class="stat-content">
          <h3>85</h3>
          <p>Khách sạn</p>
          <span class="stat-change neutral">+2</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-content">
          <h3>1,890</h3>
          <p>Khách hàng</p>
          <span class="stat-change positive">+15.3%</span>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
      <div class="chart-card">
        <h3>Doanh thu theo tháng</h3>
        <div class="chart-placeholder">
          <div class="chart-bar" style="height: 60%; background: #667eea;"></div>
          <div class="chart-bar" style="height: 75%; background: #764ba2;"></div>
          <div class="chart-bar" style="height: 45%; background: #f093fb;"></div>
          <div class="chart-bar" style="height: 80%; background: #f5576c;"></div>
          <div class="chart-bar" style="height: 90%; background: #4facfe;"></div>
          <div class="chart-bar" style="height: 85%; background: #00f2fe;"></div>
        </div>
        <div class="chart-labels">
          <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span>
        </div>
      </div>

      <div class="chart-card">
        <h3>Trạng thái đặt phòng</h3>
        <div class="pie-chart">
          <div class="pie-segment confirmed" style="--percentage: 65;"></div>
          <div class="pie-segment pending" style="--percentage: 20;"></div>
          <div class="pie-segment cancelled" style="--percentage: 15;"></div>
        </div>
        <div class="chart-legend">
          <div class="legend-item">
            <span class="legend-color confirmed"></span>
            <span>Đã xác nhận (65%)</span>
          </div>
          <div class="legend-item">
            <span class="legend-color pending"></span>
            <span>Chờ xử lý (20%)</span>
          </div>
          <div class="legend-item">
            <span class="legend-color cancelled"></span>
            <span>Đã hủy (15%)</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activities -->
    <div class="recent-activities">
      <h3>Hoạt động gần đây</h3>
      <div class="activity-list">
        <div class="activity-item">
          <div class="activity-icon">🆕</div>
          <div class="activity-content">
            <p><strong>Nguyễn Văn A</strong> đã đặt phòng tại Grand Hotel</p>
            <span class="activity-time">2 phút trước</span>
          </div>
        </div>
        <div class="activity-item">
          <div class="activity-icon">💳</div>
          <div class="activity-content">
            <p>Thanh toán thành công <strong>2,500,000 VND</strong></p>
            <span class="activity-time">5 phút trước</span>
          </div>
        </div>
        <div class="activity-item">
          <div class="activity-icon">⭐</div>
          <div class="activity-content">
            <p><strong>Trần Thị B</strong> đã đánh giá 5 sao cho Seaside Resort</p>
            <span class="activity-time">10 phút trước</span>
          </div>
        </div>
        <div class="activity-item">
          <div class="activity-icon">👤</div>
          <div class="activity-content">
            <p>Đối tác mới <strong>XYZ Travel</strong> đã đăng ký</p>
            <span class="activity-time">15 phút trước</span>
          </div>
        </div>
        <div class="activity-item">
          <div class="activity-icon">🏨</div>
          <div class="activity-content">
            <p>Khách sạn <strong>City Center Hotel</strong> đã được cập nhật</p>
            <span class="activity-time">20 phút trước</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
      <h3>Thao tác nhanh</h3>
      <div class="action-buttons">
        <button class="action-btn primary">
          <span class="action-icon">➕</span>
          <span>Thêm đặt phòng</span>
        </button>
        <button class="action-btn secondary">
          <span class="action-icon">🏨</span>
          <span>Thêm khách sạn</span>
        </button>
        <button class="action-btn secondary">
          <span class="action-icon">👥</span>
          <span>Thêm nhân viên</span>
        </button>
        <button class="action-btn secondary">
          <span class="action-icon">🎫</span>
          <span>Tạo voucher</span>
        </button>
      </div>
    </div>
</div>
