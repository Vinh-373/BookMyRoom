<h2 class="booking-title">Quản lý đặt phòng</h2>

<div class="booking-filters">
    <input 
        id="bookingSearch" 
        type="text" 
        placeholder="Tìm khách hàng..." 
        class="booking-input"
    >

    <div class="booking-select-wrapper">
    <select id="bookingStatus" class="booking-select">
        <option value="">Trạng thái</option>
        <option value="PENDING">PENDING</option>
        <option value="CONFIRMED">CONFIRMED</option>
        <option value="COMPLETED">COMPLETED</option>
        <option value="CANCELLED">CANCELLED</option>
    </select>
</div>

    <button id="bookingSearchBtn" class="booking-btn-search">
        Tìm kiếm
    </button>
</div>

<div class="booking-table-wrapper">
    <table class="booking-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Tổng tiền</th>
                <th>Thanh toán</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($bookings as $index => $b): ?>
            <tr 
                class="booking-row"
                data-name="<?= strtolower($b['fullName']) ?>"
                data-status="<?= $b['status'] ?>"
            >
                <td><?= $index + 1 ?></td>

                <td class="booking-id">
                    #<?= $b['id'] ?>
                </td>

                <td class="booking-customer">
                    <?= $b['fullName'] ?>
                </td>

                <td>
                    <?= $b['checkIn'] ?? '---' ?>
                </td>

                <td>
                    <?= $b['checkOut'] ?? '---' ?>
                </td>

                <td class="booking-price">
                    <?= number_format($b['totalAmount']) ?>đ
                </td>

                <td class="booking-payment <?= strtolower($b['paymentStatus'] ?? '') ?>">
                    <?= $b['paymentStatus'] ?? '---' ?>
                </td>

                <td>
                    <span class="booking-status <?= strtolower($b['status']) ?>">
                        <?= $b['status'] ?>
                    </span>
                </td>

                <td>
                        <button 
                            class="booking-btn-detail"
                            data-id="<?= $b['id'] ?>"
                            data-name="<?= $b['fullName'] ?>"
                            data-checkin="<?= $b['checkIn'] ?>"
                            data-checkout="<?= $b['checkOut'] ?>"
                            data-total="<?= $b['totalAmount'] ?>"
                            data-status="<?= $b['status'] ?>"
                            >
                            Chi tiết
                            </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="booking-modal" class="booking-modal">
  <div class="booking-modal-content">

    <span id="booking-close" class="booking-close">&times;</span>

    <h3>Chi tiết booking</h3>

    <p><strong>Mã đơn:</strong> <span id="modal-id"></span></p>
    <p><strong>Khách:</strong> <span id="modal-name"></span></p>
    <p><strong>Check-in:</strong> <span id="modal-checkin"></span></p>
    <p><strong>Check-out:</strong> <span id="modal-checkout"></span></p>
    <p><strong>Tổng tiền:</strong> <span id="modal-total"></span></p>

    <p>
      <strong>Trạng thái:</strong>
      <select id="modal-status">
        <option value="PENDING">PENDING</option>
        <option value="CONFIRMED">CONFIRMED</option>
        <option value="COMPLETED">COMPLETED</option>
        <option value="CANCELLED">CANCELLED</option>
      </select>
    </p>

    <button id="booking-update-btn" class="booking-btn-save">
        Cập nhật
    </button>

  </div>
</div>
</div>

<div id="phantrang_bookings"></div>

<script>
const bookingRows = Array.from(document.querySelectorAll('.booking-row'));
const itemsPerPage = 5; // số booking mỗi trang
let currentPage = 1;

function showBookingPage(page) {
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;

    // Ẩn tất cả
    bookingRows.forEach((row, index) => {
        row.style.display = (index >= start && index < end) ? 'table-row' : 'none';
    });

    renderBookingPagination();
}

function renderBookingPagination() {
    const totalPages = Math.ceil(bookingRows.length / itemsPerPage);
    const pagination = document.getElementById('phantrang_bookings');
    pagination.innerHTML = '';

    // Nút "<" lùi trang
    const prevBtn = document.createElement('button');
    prevBtn.textContent = '<';
    prevBtn.disabled = currentPage === 1;
    prevBtn.addEventListener('click', () => {
        if(currentPage > 1){
            currentPage--;
            showBookingPage(currentPage);
        }
    });
    pagination.appendChild(prevBtn);

    // Nút số trang
    for(let i = 1; i <= totalPages; i++){
        const btn = document.createElement('button');
        btn.textContent = i;
        if(i === currentPage) btn.classList.add('active');
        btn.addEventListener('click', () => {
            currentPage = i;
            showBookingPage(currentPage);
        });
        pagination.appendChild(btn);
    }

    // Nút ">" tới trang tiếp
    const nextBtn = document.createElement('button');
    nextBtn.textContent = '>';
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.addEventListener('click', () => {
        if(currentPage < totalPages){
            currentPage++;
            showBookingPage(currentPage);
        }
    });
    pagination.appendChild(nextBtn);
}

// Khởi chạy phân trang khi load
showBookingPage(currentPage);
</script>

<style>
/* CSS phân trang booking */
#phantrang_bookings {
    margin-top: 20px;
    text-align: center;
}

#phantrang_bookings button {
    margin: 2px;
    padding: 6px 12px;
    border: 1px solid #007bff;
    background-color: white;
    color: #007bff;
    font-weight: bold;
    cursor: pointer;
    border-radius: 4px;
    transition: all 0.2s ease;
}

#phantrang_bookings button:hover {
    background-color: #007bff;
    color: white;
}

#phantrang_bookings button.active {
    background-color: #007bff;
    color: white;
    cursor: default;
}

#phantrang_bookings button:disabled {
    background-color: #e0e0e0;
    color: #888;
    border-color: #ccc;
    cursor: not-allowed;
}
</style>