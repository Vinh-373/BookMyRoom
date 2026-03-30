<div class="reviews-container">
  <h1 class="reviews-title">Quản lý đánh giá</h1>

  <!-- FILTER -->
  <div class="reviews-filter">
    <input type="text" placeholder="Tìm nội dung..." class="reviews-input">

    <select class="reviews-select">
      <option value="">Số sao</option>
      <option>5 sao</option>
      <option>4 sao</option>
      <option>3 sao</option>
      <option>2 sao</option>
      <option>1 sao</option>
    </select>

    <button class="reviews-btn">Lọc</button>
  </div>

  <!-- TABLE -->
  <div class="reviews-table-wrapper">
    <table class="reviews-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Khách hàng</th>
          <th>Khách sạn</th>
          <th>Đánh giá</th>
          <th>Nội dung</th>
          <th>Ngày</th>
          <th>Hành động</th>
        </tr>
      </thead>

      <tbody>

        <tr>
          <td>#1</td>
          <td>Khách Hàng A</td>
          <td>Skeeyzi Farm Stay</td>
          <td class="reviews-stars">★★★★★</td>
          <td>Dịch vụ tuyệt vời!</td>
          <td>25/03/2026</td>
          <td>
            <button class="reviews-action view">Xem</button>
            <button class="reviews-action delete">Xóa</button>
          </td>
        </tr>

        <tr>
          <td>#2</td>
          <td>Khách Hàng B</td>
          <td>Saigon Riverside</td>
          <td class="reviews-stars">★★★★☆</td>
          <td>Phòng hơi nhỏ</td>
          <td>25/03/2026</td>
          <td>
            <button class="reviews-action view">Xem</button>
            <button class="reviews-action delete">Xóa</button>
          </td>
        </tr>

        <tr>
          <td>#5</td>
          <td>Khách Hàng E</td>
          <td>Danang Beach Hotel</td>
          <td class="reviews-stars low">★★☆☆☆</td>
          <td>Hỗ trợ kém</td>
          <td>25/03/2026</td>
          <td>
            <button class="reviews-action view">Xem</button>
            <button class="reviews-action delete">Xóa</button>
          </td>
        </tr>

      </tbody>
    </table>
  </div>
</div>