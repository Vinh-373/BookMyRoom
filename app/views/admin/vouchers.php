<div class="vouchers-container">
  <h1 class="vouchers-title">Quản lý khuyến mãi</h1>

  <!-- FILTER -->
  <div class="vouchers-filter">
    <input type="text" placeholder="Nhập mã voucher..." class="vouchers-input">

    <select class="vouchers-select">
      <option value="">Loại</option>
      <option>PERCENT</option>
      <option>FIXED</option>
    </select>

    <button class="vouchers-btn">Lọc</button>
    <button class="vouchers-btn add">+ Thêm</button>
  </div>

  <!-- TABLE -->
  <div class="vouchers-table-wrapper">
    <table class="vouchers-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Code</th>
          <th>Loại</th>
          <th>Giá trị</th>
          <th>Điều kiện</th>
          <th>Số lượng</th>
          <th>Thời gian</th>
          <th>Hành động</th>
        </tr>
      </thead>

      <tbody>

        <tr>
          <td>#1</td>
          <td class="vouchers-code">WELCOME</td>
          <td><span class="vouchers-badge percent">%</span></td>
          <td class="vouchers-value">10%</td>
          <td>Không điều kiện</td>
          <td>100</td>
          <td>01/01/2026 - 31/12/2026</td>
          <td>
            <button class="vouchers-action edit">Sửa</button>
            <button class="vouchers-action delete">Xóa</button>
          </td>
        </tr>

        <tr>
          <td>#2</td>
          <td class="vouchers-code">SALE50</td>
          <td><span class="vouchers-badge fixed">₫</span></td>
          <td class="vouchers-value">50,000đ</td>
          <td>≥ 500,000đ</td>
          <td>50</td>
          <td>01/03/2026 - 01/04/2026</td>
          <td>
            <button class="vouchers-action edit">Sửa</button>
            <button class="vouchers-action delete">Xóa</button>
          </td>
        </tr>

        <tr>
          <td>#3</td>
          <td class="vouchers-code">VIP30</td>
          <td><span class="vouchers-badge percent">%</span></td>
          <td class="vouchers-value">30%</td>
          <td>≥ 2,000,000đ</td>
          <td>10</td>
          <td>01/01/2026 - 31/12/2026</td>
          <td>
            <button class="vouchers-action edit">Sửa</button>
            <button class="vouchers-action delete">Xóa</button>
          </td>
        </tr>

      </tbody>
    </table>
  </div>
</div>