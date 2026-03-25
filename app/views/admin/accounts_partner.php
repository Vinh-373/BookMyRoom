<div class="partner-management">
  <h2>Quản lý tài khoản đối tác</h2>

  <!-- Khối thống kê -->
  <div class="stats">
    <div class="stat-box total">
      <span class="number">50</span>
      <span class="label">Tổng đối tác</span>
    </div>
    <div class="stat-box active">
      <span class="number">35</span>
      <span class="label">Đang hoạt động</span>
    </div>
    <div class="stat-box pending">
      <span class="number">10</span>
      <span class="label">Đang chờ duyệt</span>
    </div>
    <div class="stat-box blocked">
      <span class="number">5</span>
      <span class="label">Bị khóa</span>
    </div>
  </div>

  <!-- Thanh công cụ -->
  <div class="toolbar">
    <input type="text" class="search-box" placeholder="Tìm kiếm đối tác...">
    <select class="filter-status">
      <option value="">-- Lọc trạng thái --</option>
      <option value="ACTIVE">ACTIVE</option>
      <option value="PENDING">PENDING</option>
      <option value="BLOCKED">BLOCKED</option>
    </select>
    <button class="add">+ Thêm đối tác</button>
  </div>

  <!-- Bảng danh sách -->
  <table class="partner-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Công ty</th>
        <th>Mã số thuế</th>
        <th>Trạng thái</th>
        <th>Ngày tạo</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>Nguyễn Văn A</td>
        <td>a@example.com</td>
        <td>Công ty ABC</td>
        <td>123456789</td>
        <td><span class="status ACTIVE">ACTIVE</span></td>
        <td>2026-03-20</td>
        <td class="actions">
          <button class="edit">Sửa</button>
          <button class="delete">Xóa</button>
        </td>
      </tr>
      <tr>
        <td>2</td>
        <td>Trần Thị B</td>
        <td>b@example.com</td>
        <td>Công ty XYZ</td>
        <td>987654321</td>
        <td><span class="status PENDING">PENDING</span></td>
        <td>2026-03-21</td>
        <td class="actions">
          <button class="edit">Sửa</button>
          <button class="delete">Xóa</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
