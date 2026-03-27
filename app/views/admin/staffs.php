<div class="staffs-container">
    <h1>Staffs</h1>

    <!-- Stats cards -->
    <div class="stats-grid">
      <div class="stat-card">45 Tổng nhân viên</div>
      <div class="stat-card">38 Đang làm việc</div>
      <div class="stat-card">5 Nghỉ việc</div>
      <div class="stat-card">2 Bị đình chỉ</div>
    </div>

    <!-- Search & filter -->
    <div class="search-filter">
      <input type="text" placeholder="Tìm kiếm nhân viên...">
      <select>
        <option>-- Lọc trạng thái --</option>
        <option>ACTIVE</option>
        <option>RESIGNED</option>
        <option>SUSPENDED</option>
      </select>
      <button class="btn-add-staff">+ Thêm nhân viên</button>
    </div>

    <!-- Table -->
    <table class="staffs-data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Họ tên</th>
          <th>Email</th>
          <th>Số điện thoại</th>
          <th>Chức vụ</th>
          <th>Trạng thái</th>
          <th>Ngày vào làm</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Nguyễn Văn A</td>
          <td>a@bookmyroom.com</td>
          <td>0901234567</td>
          <td>Quản lý</td>
          <td>ACTIVE</td>
          <td>2024-01-15</td>
          <td><button>Sửa</button> <button>Xóa</button></td>
        </tr>
        <tr>
          <td>2</td>
          <td>Trần Thị B</td>
          <td>b@bookmyroom.com</td>
          <td>0912345678</td>
          <td>Lễ tân</td>
          <td>ACTIVE</td>
          <td>2024-03-10</td>
          <td><button>Sửa</button> <button>Xóa</button></td>
        </tr>
        <tr>
          <td>3</td>
          <td>Lê Văn C</td>
          <td>c@bookmyroom.com</td>
          <td>0923456789</td>
          <td>Kỹ thuật viên</td>
          <td>RESIGNED</td>
          <td>2023-08-20</td>
          <td><button>Khôi phục</button></td>
        </tr>
      </tbody>
    </table>
</div>