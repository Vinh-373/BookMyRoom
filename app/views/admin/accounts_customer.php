<div class="customer-management">
  <h2>Quản lý tài khoản khách hàng</h2>

  <!-- Khối thống kê -->
  <div class="stats">
    <div class="stat-box total">
      <span class="number">250</span>
      <span class="label">Tổng khách hàng</span>
    </div>
    <div class="stat-box active">
      <span class="number">200</span>
      <span class="label">Đang hoạt động</span>
    </div>
    <div class="stat-box pending">
      <span class="number">30</span>
      <span class="label">Đang chờ duyệt</span>
    </div>
    <div class="stat-box blocked">
      <span class="number">20</span>
      <span class="label">Bị khóa</span>
    </div>
  </div>

  <!-- Thanh công cụ -->
  <div class="toolbar">
    <input type="text" class="search-box" placeholder="Tìm kiếm khách hàng...">
    <select class="filter-status">
      <option value="">-- Lọc trạng thái --</option>
      <option value="ACTIVE">ACTIVE</option>
      <option value="PENDING">PENDING</option>
      <option value="BLOCKED">BLOCKED</option>
    </select>
    <button class="add">+ Thêm khách hàng</button>
  </div>

  <!-- Bảng danh sách -->
  <table class="customer-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Số điện thoại</th>
        <th>Địa chỉ</th>
        <th>Trạng thái</th>
        <th>Ngày tạo</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>101</td>
        <td>Phạm Văn C</td>
        <td>c@example.com</td>
        <td>0901234567</td>
        <td>Quận 1, TP.HCM</td>
        <td><span class="status ACTIVE">ACTIVE</span></td>
        <td>2026-03-22</td>
        <td class="actions">
          <button class="edit">Sửa</button>
          <button class="delete">Xóa</button>
        </td>
      </tr>
      <tr>
        <td>102</td>
        <td>Lê Thị D</td>
        <td>d@example.com</td>
        <td>0912345678</td>
        <td>Quận 3, TP.HCM</td>
        <td><span class="status BLOCKED">BLOCKED</span></td>
        <td>2026-03-23</td>
        <td class="actions">
          <button class="edit">Sửa</button>
          <button class="delete">Xóa</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
