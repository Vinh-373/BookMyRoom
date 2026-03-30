<div class="payments-container">
  <h1 class="payments-title">Quản lý thanh toán</h1>

  <!-- FILTER -->
  <div class="payments-filter">
    <input type="text" placeholder="Tìm theo mã booking..." class="payments-input">

    <select class="payments-select">
      <option value="">Trạng thái</option>
      <option>PENDING</option>
      <option>PAID</option>
      <option>FAILED</option>
      <option>REFUNDED</option>
    </select>

    <select class="payments-select">
      <option value="">Phương thức</option>
      <option>MOMO</option>
      <option>VNPAY</option>
      <option>VISA</option>
      <option>CASH</option>
    </select>

    <button class="payments-btn">Lọc</button>
  </div>

  <!-- TABLE -->
  <div class="payments-table-wrapper">
    <table class="payments-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Booking</th>
          <th>Khách hàng</th>
          <th>Số tiền</th>
          <th>Phương thức</th>
          <th>Trạng thái</th>
          <th>Ngày thanh toán</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>

        <!-- ROW -->
        <tr>
          <td>#1</td>
          <td>#1</td>
          <td>Khách Hàng A</td>
          <td class="payments-money">1,000,000đ</td>
          <td>MOMO</td>
          <td><span class="payments-badge paid">PAID</span></td>
          <td>25/03/2026</td>
          <td>
            <button class="payments-action view">Xem</button>
            <button class="payments-action edit">Cập nhật</button>
          </td>
        </tr>

        <tr>
          <td>#3</td>
          <td>#3</td>
          <td>Khách Hàng C</td>
          <td class="payments-money">1,200,000đ</td>
          <td>MOMO</td>
          <td><span class="payments-badge pending">PENDING</span></td>
          <td>25/03/2026</td>
          <td>
            <button class="payments-action view">Xem</button>
            <button class="payments-action edit">Cập nhật</button>
          </td>
        </tr>

        <tr>
          <td>#5</td>
          <td>#5</td>
          <td>Khách Hàng E</td>
          <td class="payments-money">1,500,000đ</td>
          <td>VNPAY</td>
          <td><span class="payments-badge failed">FAILED</span></td>
          <td>25/03/2026</td>
          <td>
            <button class="payments-action view">Xem</button>
            <button class="payments-action edit">Cập nhật</button>
          </td>
        </tr>

      </tbody>
    </table>
  </div>
</div>