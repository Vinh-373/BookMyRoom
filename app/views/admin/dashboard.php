<div class="staff-management">
  <h2>Trang tổng quan</h2>

  <!-- Khối thống kê -->
  <div class="stats">
    <div class="stat-box total">
      <span class="number">120</span>
      <span class="label">Tổng tài khoản</span>
    </div>
    <div class="stat-box active">
      <span class="number">95</span>
      <span class="label">Đang hoạt động</span>
    </div>
    <div class="stat-box pending">
      <span class="number">15</span>
      <span class="label">Đang chờ duyệt</span>
    </div>
    <div class="stat-box blocked">
      <span class="number">10</span>
      <span class="label">Bị khóa</span>
    </div>
  </div>

  <!-- Thanh công cụ -->
  <div class="toolbar">
    <input type="text" class="search-box" placeholder="Tìm kiếm nhân viên...">
    <select class="filter-status">
      <option value="">-- Lọc trạng thái --</option>
      <option value="ACTIVE">ACTIVE</option>
      <option value="PENDING">PENDING</option>
      <option value="BLOCKED">BLOCKED</option>
    </select>
    <button class="add">+ Thêm nhân viên</button>
  </div>

  <!-- Bảng danh sách -->
  <table class="staff-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Số điện thoại</th>
        <th>Chức vụ</th>
        <th>Trạng thái</th>
        <th>Ngày tạo</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>

    </tbody>
  </table>
</div>

