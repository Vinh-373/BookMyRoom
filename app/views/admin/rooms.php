<div class="rooms-content">
  <div class="rooms-header">
    <h2>Danh sách phòng</h2>
    <button id="rooms-add-btn">Thêm phòng mới</button>
  </div>

  <!-- Ô tìm kiếm -->
  <div class="rooms-search">
    <input type="text" id="rooms-search-input" placeholder="Tìm kiếm theo tên hoặc loại phòng...">
  </div>

  <table class="rooms-table" id="rooms-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Tên phòng</th>
        <th>Loại phòng</th>
        <th>Giá / đêm</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>101</td>
        <td>Phòng Deluxe</td>
        <td>Deluxe</td>
        <td>1,200,000</td>
        <td>Trống</td>
        <td>
          <button class="rooms-btn rooms-btn-edit">Sửa</button>
          <button class="rooms-btn rooms-btn-delete">Xóa</button>
        </td>
      </tr>
      <tr>
        <td>102</td>
        <td>Phòng Standard</td>
        <td>Standard</td>
        <td>800,000</td>
        <td>Đã đặt</td>
        <td>
          <button class="rooms-btn rooms-btn-edit">Sửa</button>
          <button class="rooms-btn rooms-btn-delete">Xóa</button>
        </td>
      </tr>
    </tbody>
  </table>

  <!-- Modal -->
  <div class="rooms-modal" id="rooms-modal">
    <div class="rooms-modal-content">
      <h3 id="rooms-modal-title">Thêm phòng</h3>
      <label for="rooms-id">ID</label>
      <input type="number" id="rooms-id">

      <label for="rooms-name">Tên phòng</label>
      <input type="text" id="rooms-name">

      <label for="rooms-type">Loại phòng</label>
      <select id="rooms-type">
        <option value="Standard">Standard</option>
        <option value="Deluxe">Deluxe</option>
        <option value="Suite">Suite</option>
      </select>

      <label for="rooms-price">Giá / đêm</label>
      <input type="number" id="rooms-price">

      <label for="rooms-status">Trạng thái</label>
      <select id="rooms-status">
        <option value="Trống">Trống</option>
        <option value="Đã đặt">Đã đặt</option>
      </select>

      <div class="rooms-modal-actions">
        <button class="rooms-btn-cancel" id="rooms-cancel-btn">Hủy</button>
        <button class="rooms-btn-save" id="rooms-save-btn">Lưu</button>
      </div>
    </div>
  </div>
</div>