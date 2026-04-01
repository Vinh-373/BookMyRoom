<div class="reviews-content">

  <div class="reviews-header">
    <div class="reviews-header-content">
      <h1 class="reviews-title">Quản lý Đánh giá</h1>
      <div>Tổng số đánh giá:<?php echo count($reviews); ?></div>
      <div>
        <div class="medium-rating" id="mediumRating">
          ★ <?php echo number_format(array_sum(array_column($reviews, 'rating')) / count($reviews), 1); ?>
        </div>
        <div>Điểm trung bình</div>
      </div>
    </div>
    <!-- Summary -->
    <div class="reviews-summary">
      <?php for ($i = 5; $i >= 1; $i--) { ?>
        <div class="reviews-summary-item">
          <ul>
            <li data-rating="<?php echo $i; ?>"><?php echo $i; ?> ★</li>
            <li>
              <?php
              echo count(array_filter($reviews, fn($r) => $r['rating'] == $i));
              ?> đánh giá
            </li>
          </ul>
        </div>
      <?php } ?>
    </div>

    <div class="reviews-filter">
      <input type="text" class="reviews-search" placeholder="Tìm kiếm theo nội dung, khách hàng hoặc khách sạn...">
      <select class="reviews-partners">
        <option value="">Chọn công ty</option>
        <?php foreach ($partners as $partner) { ?>
          <option value="<?php echo $partner['userId']; ?>">
            <?php echo htmlspecialchars($partner['companyName']); ?>
          </option>
        <?php } ?>
      </select>

      <button class="reviews-clear-btn">Xóa bộ lọc</button>
    </div>

  </div>

  <!-- Reviews list -->
  <div class="reviews-list">

    <div class="reviews-card">
      <?php foreach ($reviews as $review) { ?>
        <div class="reviews-item"
          data-id="<?php echo $review['id']; ?>"
          data-rating="<?php echo $review['rating']; ?>"
          data-content="<?php echo htmlspecialchars($review['content']); ?>"
          data-name="<?php echo htmlspecialchars($review['fullName']); ?>"
          data-bookingId="<?php echo $review['bookingId'] ?? ''; ?>"
          data-hotel="<?php echo htmlspecialchars($review['hotelName']); ?>"
          data-date="<?php echo date('d/m/Y', strtotime($review['createdAt'])); ?>"
          data-partner="<?php echo $review['partnerId'] ?? ''; ?>">
          <div class="review-head">
            <div class="reviews-name"><?php echo $review['fullName']; ?></div>
            <div class="reviews-rating">★ <?php echo $review['rating']; ?></div>
          </div>
          <div class="reviews-text"><?php echo $review['content']; ?></div>
          <div class="reviews-footer">
            <span><?php echo date('d/m/Y', strtotime($review['createdAt'])); ?></span>
            <span>Khách sạn: <?php echo $review['hotelName']; ?></span>
          </div>
          <button class="reviews-btn-view">Xem</button>
          <button class="reviews-btn-delete">Xóa</button>
        </div>
      <?php } ?>
    </div>

  </div>

  <div class="reviews-detail-content" id="reviewDetail" style="display:none;">
    <div class="review-detail-box">
      <h2>Chi tiết đánh giá</h2>
      <p><b>Khách hàng:</b> <span id="d-name"></span></p>
      <p><b>BookingId:</b> <span id="d-bookingId"></span></p>
      <p><b>Khách sạn:</b> <span id="d-hotel"></span></p>
      <p><b>Ngày:</b> <span id="d-date"></span></p>
      <p><b>Rating:</b> ⭐ <span id="d-rating"></span></p>
      <p><b>Nội dung:</b></p>
      <div id="d-content"></div>

      <button id="reviews-closeDetail">Đóng</button>
    </div>
  </div>


</div>