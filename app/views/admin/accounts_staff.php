<div class="staff-management">
  <h2>Quản lý tài khoản nhân viên</h2>

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
      <tr>
        <td>201</td>
        <td>Nguyễn Văn E</td>
        <td>e@example.com</td>
        <td>0987654321</td>
        <td>Quản lý</td>
        <td><span class="status ACTIVE">ACTIVE</span></td>
        <td>2026-03-24</td>
        <td class="actions">
          <button class="edit">Sửa</button>
          <button class="delete">Xóa</button>
        </td>
      </tr>
      <tr>
        <td>202</td>
        <td>Phạm Thị F</td>
        <td>f@example.com</td>
        <td>0978123456</td>
        <td>Nhân viên lễ tân</td>
        <td><span class="status PENDING">PENDING</span></td>
        <td>2026-03-25</td>
        <td class="actions">
          <button class="edit">Sửa</button>
          <button class="delete">Xóa</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>

