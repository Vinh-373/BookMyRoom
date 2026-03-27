<div class="customers-container">
    <h1>Customers</h1>

    <!-- Stats cards -->
    <div class="stats-grid">
      <div class="stat-card">1250 Tổng khách hàng</div>
      <div class="stat-card">980 Đang hoạt động</div>
      <div class="stat-card">150 Chưa xác thực</div>
      <div class="stat-card">120 Bị khóa</div>
    </div>

    <!-- Search & filter -->
    <div class="search-filter">
      <input type="text" placeholder="Tìm kiếm khách hàng...">
      <select>
        <option>-- Lọc trạng thái --</option>
        <option>ACTIVE</option>
        <option>UNVERIFIED</option>
        <option>BLOCKED</option>
      </select>
      <button class="btn-add-customer">+ Thêm khách hàng</button>
    </div>

    <!-- Table -->
    <table class="customers-data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Họ tên</th>
          <th>Email</th>
          <th>Số điện thoại</th>
          <th>Địa chỉ</th>
          <th>Trạng thái</th>
          <th>Ngày đăng ký</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Nguyễn Văn A</td>
          <td>a@gmail.com</td>
          <td>0901234567</td>
          <td>Hà Nội</td>
          <td>ACTIVE</td>
          <td>2024-01-15</td>
          <td><button>Sửa</button> <button>Xóa</button></td>
        </tr>
        <tr>
          <td>2</td>
          <td>Trần Thị B</td>
          <td>b@gmail.com</td>
          <td>0912345678</td>
          <td>TP.HCM</td>
          <td>ACTIVE</td>
          <td>2024-03-10</td>
          <td><button>Sửa</button> <button>Xóa</button></td>
        </tr>
        <tr>
          <td>3</td>
          <td>Lê Văn C</td>
          <td>c@gmail.com</td>
          <td>0923456789</td>
          <td>Đà Nẵng</td>
          <td>UNVERIFIED</td>
          <td>2024-03-25</td>
          <td><button>Xác thực</button> <button>Xóa</button></td>
        </tr>
      </tbody>
    </table>
</div>