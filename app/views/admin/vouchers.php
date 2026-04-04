<div class="vouchers-content">
  <h1 class="vouchers-title">Quản lý Voucher</h1>
  <div class="vouchers-header">
    <span class="vouchers-total">Tổng số voucher <?php echo count($vouchers) ?></span>
    <input type="text" class="vouchers-search" placeholder="Tìm kiếm theo mã voucher...">
    <select id="voucherTypeFilter" class="vouchers-search">
      <option value="">Tất cả loại</option>
      <option value="PERCENT">PERCENT (%)</option>
      <option value="FIXED">FIXED (đ)</option>
    </select>
    <button class="vouchers-add-btn">+ Thêm Voucher</button>
  </div>

  <div class="vouchers-table-wrapper">
    <table class="vouchers-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Mã Voucher</th>
          <th>Số lượng</th>
          <th>Loại</th>
          <th>Giá trị</th>
          <th>Điều kiện</th>
          <th>Thời gian</th>
          <th>Trạng thái</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($vouchers as $voucher): ?>
          <tr data-id="<?php echo $voucher['id']; ?>"
            data-code="<?php echo strtolower($voucher['code']); ?>"
            data-type="<?php echo $voucher['type']; ?>">
            <td><?php echo $voucher['id']; ?></td>
            <td><?php echo $voucher['code']; ?></td>
            <td><?php echo $voucher['quantity']; ?></td>
            <td><?php echo $voucher['type'] . ' ' .
                  ($voucher['type'] == 'PERCENT' ? '(%)' : '(đ)');  ?></td>
            <td><?php echo $voucher['amount']; ?></td>
            <td><?php echo $voucher['condition']; ?></td>
            <td><?php echo $voucher['startDate'] . ' đến ' . $voucher['endDate']; ?></td>
            <td> <?php
                  $today = date('Y-m-d');

                  if ($voucher['endDate'] < $today) {
                    echo '<span class="vouchers-status expired">Hết hạn</span>';
                  } else {
                    echo '<span class="vouchers-status active">Còn hạn</span>';
                  }
                  ?></td>
            <td>
              <button class="vouchers-btn-edit" data-id="<?php echo $voucher['id']; ?>">Sửa</button>
              <button class="vouchers-btn-delete" data-id="<?php echo $voucher['id']; ?>">Xóa</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>





  <div id="vouchers-add-modal" class="vouchers-add-modal">
    <h2>Thêm Voucher</h2>

    <form id="vouchers-add-form">
      <label>Mã voucher</label>
      <input type="text" name="code" id="vouchers-add-code" required>

      <label>Số lượng</label>
      <input type="number" name="quantity" id="vouchers-add-quantity" required>

      <label>Loại</label>
      <select name="type" id="vouchers-add-type">
        <option value="PERCENT">PERCENT (%)</option>
        <option value="FIXED">FIXED (đ)</option>
      </select>

      <label>Giá trị</label>
      <input type="number" name="amount" id="vouchers-add-amount" required>

      <label>Điều kiện</label>
      <input type="number" name="condition" id="vouchers-add-condition">

      <label>Ngày bắt đầu</label>
      <input type="date" name="startDate" id="vouchers-add-startDate">

      <label>Ngày kết thúc</label>
      <input type="date" name="endDate" id="vouchers-add-endDate">

      <div class="vouchers-modal-actions">
        <button type="submit" id="vouchers-btn-add-save">Thêm</button>
        <button type="button" id="vouchers-btn-add-x">Hủy</button>
      </div>
    </form>
  </div>









  <div id="vouchers-edit-modal" class="vouchers-edit-modal">
    <h2>Sửa Voucher</h2>

    <form id="vouchers-edit-form">

      <input type="hidden" name="id" id="vouchers-edit-id">

      <label>Mã voucher</label>
      <input type="text" name="code" id="vouchers-edit-code" required>

      <label>Số lượng</label>
      <input type="number" name="quantity" id="vouchers-edit-quantity" required>

      <label>Loại</label>
      <select name="type" id="vouchers-edit-type">
        <option value="PERCENT">PERCENT (%)</option>
        <option value="FIXED">FIXED (đ)</option>
      </select>

      <label>Giá trị</label>
      <input type="number" name="amount" id="vouchers-edit-amount" required>

      <label>Điều kiện</label>
      <input type="number" name="condition" id="vouchers-edit-condition">

      <label>Ngày bắt đầu</label>
      <input type="date" id="vouchers-edit-startDate" readonly>
      <input type="hidden" name="startDate" id="vouchers-edit-startDate-hidden">

      <label>Ngày kết thúc</label>
      <input type="date" id="vouchers-edit-endDate" readonly>
      <input type="hidden" name="endDate" id="vouchers-edit-endDate-hidden">

      <div class="vouchers-modal-actions">
        <!-- 🔥 type="submit" để trigger JS -->
        <button type="submit" id="vouchers-btn-save">Lưu</button>

        <!-- 🔥 type="button" để không submit -->
        <button type="button" id="vouchers-btn-x">Hủy</button>
      </div>

    </form>
  </div>





</div>