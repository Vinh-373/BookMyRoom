<div class="hotels-content">

    <!-- HEADER -->
    <div class="hotels-header">
        <h2 class="hotels-title">Quản lý khách sạn</h2>

        <!-- STATS -->
        <div class="hotels-stats-grid">
            <div class="hotels-stat-card a">
                <div class="num"><?php echo count($partners); ?></div>
                <div class="label">Tổng đối tác</div>
            </div>
            <div class="hotels-stat-card b">
                <div class="num"><?php echo count($hotels); ?></div>
                <div class="label">Tổng khách sạn</div>
            </div>
            <div class="hotels-stat-card c">
                <div class="num"><?php echo count(array_filter($hotels, fn($s) => $s['status'] == 'ACTIVE')); ?></div>
                <div class="label">Khách sạn đang hoạt động</div>
            </div>
            <div class="hotels-stat-card d">
                <div class="num"><?php echo count(array_filter($hotels, fn($s) => $s['status'] == 'PENDING_STOP')); ?></div>
                <div class="label">Khách sạn chờ duyệt</div>
            </div>
            <div class="hotels-stat-card e">
                <div class="num"><?php echo count(array_filter($hotels, fn($s) => $s['status'] == 'STOP')); ?></div>
                <div class="label">Khách sạn tạm ngừng</div>
            </div>
            <div class="hotels-stat-card f">
                <div class="num"><?php echo array_sum(array_column($hotels, 'totalRooms')); ?></div>
                <div class="label">Phòng</div>
            </div>
            <div class="hotels-stat-card g">
                <div class="num"><?php echo array_sum(array_column($hotels, 'totalBookings')); ?></div>
                <div class="label">Booking</div>
            </div>
        </div>

        <!-- TOOLBAR -->
        <div class="hotels-toolbar">
            <input type="text" id="hotels-search" placeholder="🔍 Tìm khách sạn...">

            <select id="hotelsStatusFilter">
                <option value="">-- Lọc trạng thái --</option>
                <option value="ACTIVE">ACTIVE</option>
                <option value="PENDING_STOP">PENDING_STOP</option>
                <option value="STOP">STOP</option>
            </select>

            <select id="hotels-filter-partner">
                <option value="">Tất cả đối tác</option>
                <?php foreach ($partners as $p): ?>
                    <option value="<?php echo $p['companyName']; ?>">
                        <?php echo $p['companyName']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select id="hotels-filter-nameHotel">
                <option value="">Tất cả khách sạn</option>
                <?php foreach ($hotels as $h): ?>
                    <option value="<?php echo strtolower($h['hotelName']); ?>" data-company="<?php echo $h['companyName']; ?>">
                        <?php echo $h['hotelName']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button id="btn-xoa-bo-loc-hotel">Xóa bộ lọc</button>
        </div>
    </div>

    <!-- LIST -->
    <div class="hotels-grid" id="hotelsList">
        <?php foreach ($hotels as $hotel): ?>
            <div class="hotels-card"
                data-id="<?php echo $hotel['id']; ?>"
                data-name="<?php echo strtolower($hotel['hotelName']); ?>"
                data-partner="<?php echo $hotel['companyName']; ?>"
                data-status="<?php echo $hotel['status']; ?>">

                <div class="hotels-img">
                    <img src="<?php echo $hotel['image'] ?? 'default.jpg'; ?>">
                    <span class="rating">⭐ <?php echo $hotel['rating'] ?? 0; ?></span>
                </div>

                <div class="hotels-info">
                    <h3><?php echo $hotel['hotelName']; ?></h3>
                    <p>📍 <?php echo $hotel['address']; ?></p>
                    <p><?php echo $hotel['wardName']; ?>, <?php echo $hotel['cityName']; ?></p>
                    <p>🏢 <?php echo $hotel['companyName'] ?? '---'; ?></p>

                    <div class="hotels-meta">
                        <span>🛏 <?php echo $hotel['totalRooms']; ?></span>
                        <span>📖 <?php echo $hotel['totalBookings']; ?></span>
                        <span>💰 <?php echo number_format($hotel['totalRevenue']); ?></span>
                    </div>

                    <div class="hotels-actions">
                        <button class="hotels-btn-view">Xem</button>
                        <?php
                        $status = $hotel['status'];
                        $btnText = '';
                        $btnClass = '';
                        $nextStatus = '';

                        if ($status === 'PENDING_STOP') {
                            $btnText = 'Duyệt';
                            $btnClass = 'hotels-btn-pending_stop';
                            $nextStatus = 'ACTIVE';
                        } elseif ($status === 'ACTIVE') {
                            $btnText = 'Khóa';
                            $btnClass = 'hotels-btn-stop';
                            $nextStatus = 'STOP';
                        } elseif ($status === 'STOP') {
                            $btnText = 'Mở';
                            $btnClass = 'hotels-btn-active';
                            $nextStatus = 'ACTIVE';
                        }
                        ?>

                        <button
                            class="hotels-toggle-status-btn <?php echo $btnClass; ?>"
                            data-id="<?php echo $hotel['id']; ?>"
                            data-next-status="<?php echo $nextStatus; ?>">
                            <?php echo $btnText; ?>
                        </button>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
<div id="pagination_hotels"></div>
    <!-- MODAL XEM CHI TIẾT -->
    <div id="hotelModal" class="hotel-modal" style="display:none;">
        <div class="hotel-modal-content">
            <span class="hotel-modal-close">&times;</span>
            <h2 id="modalHotelName"></h2>
            <img id="modalHotelImage" src="" alt="Hotel Image" style="max-width: 100%; margin-bottom: 15px;">
            <p><strong>Địa chỉ:</strong> <span id="modalHotelAddress"></span></p>
            <p><strong>Phường / Quận:</strong> <span id="modalHotelWard"></span></p>
            <p><strong>Thành phố:</strong> <span id="modalHotelCity"></span></p>
            <p><strong>Công ty:</strong> <span id="modalHotelCompany"></span></p>
            <p><strong>Phòng:</strong> <span id="modalHotelRooms"></span></p>
            <p><strong>Booking:</strong> <span id="modalHotelBookings"></span></p>
            <p><strong>Doanh thu:</strong> <span id="modalHotelRevenue"></span></p>
            <p><strong>Đánh giá:</strong> <span id="modalHotelRating"></span></p>
        </div>
    </div>

</div>
<script>

const hotelsList = document.getElementById('hotelsList');
let hotels = Array.from(hotelsList.children); // tất cả card hiện tại
let filteredHotels = hotels; // danh sách đang hiển thị sau lọc/tìm kiếm
const itemsPerPage = 6;
let currentPage = 1;

// Lọc & tìm kiếm
const searchInput = document.getElementById('hotels-search');
const statusFilter = document.getElementById('hotelsStatusFilter');
const partnerFilter = document.getElementById('hotels-filter-partner');
const nameHotelFilter = document.getElementById('hotels-filter-nameHotel');

function filterHotels() {
    const searchValue = searchInput.value.toLowerCase();
    const statusValue = statusFilter.value;
    const partnerValue = partnerFilter.value;
    const nameHotelValue = nameHotelFilter.value;

    filteredHotels = hotels.filter(hotel => {
        const name = hotel.dataset.name;
        const status = hotel.dataset.status;
        const partner = hotel.dataset.partner;

        return (!searchValue || name.includes(searchValue)) &&
               (!statusValue || status === statusValue) &&
               (!partnerValue || partner === partnerValue) &&
               (!nameHotelValue || name === nameHotelValue);
    });

    currentPage = 1; // reset trang về 1 khi filter
    showPage(currentPage);
}

// Phân trang
function showPage(page) {
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;

    hotels.forEach(hotel => hotel.style.display = 'none'); // ẩn hết
    filteredHotels.slice(start, end).forEach(hotel => hotel.style.display = 'block'); // hiển thị page hiện tại

    renderPagination();
}

// Render phân trang theo filteredHotels
function renderPagination() {
    const totalPages = Math.ceil(filteredHotels.length / itemsPerPage);
    const pagination = document.getElementById('pagination_hotels');
    pagination.innerHTML = '';

    const prevBtn = document.createElement('button');
    prevBtn.textContent = '<';
    prevBtn.disabled = currentPage === 1;
    prevBtn.addEventListener('click', () => { if(currentPage>1){currentPage--; showPage(currentPage);} });
    pagination.appendChild(prevBtn);

    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        if(i===currentPage) btn.classList.add('active');
        btn.addEventListener('click', () => { currentPage=i; showPage(currentPage); });
        pagination.appendChild(btn);
    }

    const nextBtn = document.createElement('button');
    nextBtn.textContent = '>';
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.addEventListener('click', () => { if(currentPage<totalPages){currentPage++; showPage(currentPage);} });
    pagination.appendChild(nextBtn);
}

// Bắt sự kiện filter & search
searchInput.addEventListener('input', filterHotels);
statusFilter.addEventListener('change', filterHotels);
partnerFilter.addEventListener('change', filterHotels);
nameHotelFilter.addEventListener('change', filterHotels);

// Hiển thị trang đầu tiên khi load
showPage(currentPage);



</script>

<style>
    #pagination button {
    margin: 2px;
    padding: 5px 10px;
}
#pagination button.active {
    background-color: #007bff;
    color: white;
}


/* CSS cho phân trang */
#pagination_hotels {
    margin-top: 20px;
    text-align: center;
}

#pagination_hotels button {
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

#pagination_hotels button:hover {
    background-color: #007bff;
    color: white;
}

#pagination_hotels button.active {
    background-color: #007bff;
    color: white;
    cursor: default;
}

#pagination_hotels button:disabled {
    background-color: #e0e0e0;
    color: #888;
    border-color: #ccc;
    cursor: not-allowed;
}

</style>

