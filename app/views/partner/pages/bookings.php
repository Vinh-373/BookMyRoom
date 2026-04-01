<div class="booking-wrapper">
    <div class="booking-page-container">
        <section class="booking-filter-card">
            <form action="" method="GET" id="filterForm">
                <input type="hidden" name="tab" value="<?= htmlspecialchars($filters['status'] ?? 'all') ?>">
                
                <div class="filter-top-row">
                    <div class="search-main">
                        <i class="search-icon">🔍</i>
                        <input type="text" name="search" id="searchInput"
                            value="<?= htmlspecialchars($filters['search'] ?? '') ?>" 
                            placeholder="Tìm theo mã đơn, tên khách hoặc số điện thoại..."
                            autocomplete="off">
                    </div>
                    
                    <div class="dropdown-group">
                        <div class="date-range-wrapper">
                            <i class="calendar-icon">📅</i>
                            <input type="text" name="date_range" id="dateRangePicker" 
                                value="<?= htmlspecialchars($filters['date_range_raw'] ?? '') ?>" 
                                placeholder="Chọn khoảng ngày..." readonly>
                            
                            <?php if (!empty($filters['date_range_raw'])): ?>
                                <button type="button" id="clearDateBtn" class="btn-clear-date">✕</button>
                            <?php endif; ?>
                        </div>
                        
                        <select name="roomTypeId" class="room-type-select" onchange="this.form.submit()">
                            <option value="">Loại phòng: Tất cả</option>
                            <?php foreach($roomTypes as $type): ?>
                                <option value="<?= $type['id'] ?>" <?= (($filters['roomTypeId'] ?? '') == $type['id']) ? 'selected' : '' ?>>
                                    <?= $type['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <button type="button" class="btn-export" onclick="exportBookingCSV()">📤 Xuất file CSV</button>
                    </div>
                </div>
    
                <div class="tab-and-sort">
                    <div class="booking-tabs">
                        <?php 
                        $tabs = [
                            'all'       => 'Tất cả', 
                            'pending'   => 'Sắp đến', 
                            'confirmed' => 'Đang ở',  
                            'completed' => 'Đã trả phòng', 
                            'cancelled' => 'Đã hủy'
                        ];
                        $currentGet = $_GET;
                        foreach($tabs as $key => $label): 
                            $tabParams = $currentGet;
                            $tabParams['tab'] = $key;
                            $tabParams['page'] = 1; 
                            $tabUrl = "?" . http_build_query($tabParams);
                        ?>
                            <a href="<?= $tabUrl ?>" class="tab <?= ($filters['status'] == $key) ? 'active' : '' ?>">
                                <?= $label ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="sort-tool">
                        <label for="bookingSort">≡ SẮP XẾP:</label>
                        <select name="sort" id="bookingSort" class="sort-select" onchange="this.form.submit()">
                            <option value="newest" <?= ($filters['sort'] == 'newest') ? 'selected' : '' ?>>MỚI NHẤT</option>
                            <option value="oldest" <?= ($filters['sort'] == 'oldest') ? 'selected' : '' ?>>CŨ NHẤT</option>
                            <option value="price_high" <?= ($filters['sort'] == 'price_high') ? 'selected' : '' ?>>GIÁ: CAO - THẤP</option>
                            <option value="price_low" <?= ($filters['sort'] == 'price_low') ? 'selected' : '' ?>>GIÁ: THẤP - CAO</option>
                        </select>
                    </div>
                </div>
            </form>
    
            <div class="table-responsive">
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>MÃ ĐƠN & NGÀY</th>
                            <th>THÔNG TIN KHÁCH</th>
                            <th>PHÒNG & THỜI GIAN</th>
                            <th>TỔNG TIỀN & TRẠNG THÁI</th>
                            <th>THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($bookings)): ?>
                            <?php foreach($bookings as $b): ?>
                            <tr>
                                <td>
                                    <div class="id">#BK-<?= $b['id'] ?></div>
                                    <div class="date"><?= date('d/m/Y', strtotime($b['createdAt'])) ?></div>
                                </td>
                                <td>
                                    <div class="guest-flex">
                                        <div class="avatar-circle"><?= strtoupper(substr($b['fullName'], 0, 1)) ?></div>
                                        <div class="info">
                                            <div class="name"><?= $b['fullName'] ?></div>
                                            <div class="phone"><?= $b['phone'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="room"><?= $b['roomTypeName'] ?></div>
                                    <div class="duration">🌙 <?= $b['nights'] ?> Đêm</div>
                                </td>
                                <td>
                                    <div class="amount"><?= number_format($b['totalAmount'], 0, ',', '.') ?>đ</div>
                                    <span class="badge-status <?= strtolower($b['bookingStatus']) ?>">
                                        ● <?= $tabs[strtolower($b['bookingStatus'])] ?? $b['bookingStatus'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                        $status = strtoupper($b['bookingStatus'] ?? ''); 
                                        $bookingId = $b['id'];
                                        $guestName = addslashes($b['fullName'] ?? 'Khách hàng');
                                    ?>
                                    <div class="action-dropdown">
                                        <button type="button" class="btn-more" onclick="toggleActionMenu(this, event)">⋮</button>
                                        <div class="dropdown-menu-content">
                                            <a href="javascript:void(0);" onclick="viewBookingDetails(<?= $b['id'] ?>)">
                                                <i class="icon">👁️</i> Chi tiết & In
                                            </a>

                                            <?php if (in_array($status, ['PENDING', 'CONFIRMED'])): ?>
                                                <a href="javascript:void(0);" onclick="processBookingAction('checkin', <?= $bookingId ?>, '<?= $guestName ?>')">
                                                    <i class="fas fa-key icon-fw"></i> Nhận phòng nhanh
                                                </a>
                                                
                                                <div class="divider"></div>
                                                
                                                <a href="javascript:void(0);" class="text-danger" onclick="processBookingAction('cancel', <?= $bookingId ?>, '<?= $guestName ?>')">
                                                    <i class="fas fa-times-circle icon-fw"></i> Hủy đặt phòng
                                                </a>

                                            <?php elseif ($status === 'STAYING'): ?>
                                                <a href="javascript:void(0);" onclick="processBookingAction('checkout', <?= $bookingId ?>, '<?= $guestName ?>')">
                                                    <i class="fas fa-sign-out-alt icon-fw"></i> Trả phòng
                                                </a>
                                                
                                                <a href="<?= URLROOT ?>/partner/services/add/<?= $bookingId ?>">
                                                    <i class="fas fa-plus-circle icon-fw"></i> Thêm dịch vụ
                                                </a>

                                            <?php elseif ($status === 'COMPLETED'): ?>
                                                <a href="javascript:void(0);" onclick="viewReview(<?= $bookingId ?>)">
                                                    <i class="fas fa-star icon-fw text-warning"></i> Xem đánh giá
                                                </a>

                                            <?php elseif ($status === 'CANCELLED'): ?>
                                                <a href="javascript:void(0);" onclick="processBookingAction('restore', <?= $bookingId ?>, '<?= $guestName ?>')">
                                                    <i class="fas fa-undo icon-fw"></i> Khôi phục đơn
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center; padding:40px; color: #999;">Không tìm thấy dữ liệu phù hợp.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
    
            <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="pagination-area">
                <span class="showing">
                    Hiển thị <strong><?= $showingStart ?></strong> - <strong><?= $showingEnd ?></strong> 
                    trong <strong><?= number_format($totalCount) ?></strong> đơn hàng
                </span>
                <div class="pages">
                    <?php $pageParams = $_GET; ?>
                    <?php if($currentPage > 1): 
                        $pageParams['page'] = $currentPage - 1; ?>
                        <a href="?<?= http_build_query($pageParams) ?>" class="page-nav">‹</a>
                    <?php endif; ?>

                    <?php for($i = 1; $i <= $totalPages; $i++): 
                        $pageParams['page'] = $i; ?>
                        <a href="?<?= http_build_query($pageParams) ?>" 
                           class="page-num <?= ($currentPage == $i) ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if($currentPage < $totalPages): 
                        $pageParams['page'] = $currentPage + 1; ?>
                        <a href="?<?= http_build_query($pageParams) ?>" class="page-nav">›</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </section>

        <section class="insight-cards-grid">
            <div class="insight-card blue-gradient">
                <div class="card-head"><span class="label">📈 TRỰC TUYẾN</span></div>
                <div class="card-body">
                    <p>Tổng doanh thu (Tháng)</p>
                    <h3><?= number_format((float)$insights['revenue']['total'], 0, ',', '.') ?>đ</h3>
                    <div class="progress-wrap">
                        <div class="progress-bar" style="width: <?= $insights['revenue']['progress'] ?>%"></div>
                    </div>
                    <small><?= str_replace('target', 'mục tiêu', $insights['revenue']['goal_text']) ?></small>
                </div>
            </div>
    
            <div class="insight-card white-card">
                <div class="card-head">
                    <span class="label">TỶ LỆ LẤP ĐẦY</span>
                    <i class="chart-icon">📊</i>
                </div>
                <div class="occ-content">
                    <div class="val-flex">
                        <h2><?= $insights['occupancy']['rate'] ?></h2>
                        <span class="trend positive"><?= $insights['occupancy']['trend'] ?></span>
                    </div>
                    <div class="mini-bar-chart">
                        <div class="bar" style="height: 40%"></div>
                        <div class="bar" style="height: 60%"></div>
                        <div class="bar active" style="height: 85%"></div>
                        <div class="bar" style="height: <?= $insights['occupancy']['rate'] ?>%"></div>
                    </div>
                </div>
            </div>
    
            <div class="insight-card white-card">
                <div class="card-head"><span class="label">HOẠT ĐỘNG MỚI</span><i class="bolt-icon">⚡</i></div>
                <ul class="activity-log">
                    <?php foreach(array_slice($bookings, 0, 3) as $b): ?>
                        <li>
                            <span class="dot blue"></span> 
                            Đặt phòng mới: <?= $b['fullName'] ?>
                            <time><?= date('H:i', strtotime($b['createdAt'])) ?></time>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    </div>
    
    <div id="bookingDetailModal" class="custom-modal" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Chi tiết đơn hàng <span id="js-m-id"></span></h3>
                <button type="button" class="close-modal" onclick="closeBookingModal()">✕</button>
            </div>
            <div class="modal-body">
                <div class="modal-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="info-group">
                        <label style="font-size: 11px; color: #667085;">KHÁCH HÀNG</label>
                        <p id="js-m-name" style="font-weight: 700; margin: 5px 0;"></p>
                        <p id="js-m-phone" style="color: #667085; font-size: 0.9rem;"></p>
                    </div>
                    <div class="info-group" style="text-align: right;">
                        <label style="font-size: 11px; color: #667085;">TRẠNG THÁI TT</label><br>
                        <div id="js-m-payment" class="status-pill"></div>
                    </div>
                    <div style="grid-column: span 2; background: #f8f9fa; padding: 15px; border-radius: 10px; border: 1px solid #eee;">
                        <p>Phòng: <strong id="js-m-room"></strong></p>
                        <p>Thời gian: <span id="js-m-in"></span> - <span id="js-m-out"></span> (🌙 <span id="js-m-nights"></span> đêm)</p>
                        <hr style="border: none; border-top: 1px dashed #ddd; margin: 10px 0;">
                        <div style="display: flex; justify-content: space-between; font-weight: 800;">
                            <span>TỔNG CỘNG:</span>
                            <span id="js-m-total" style="color: #2261E0; font-size: 1.3rem;"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeBookingModal()">Đóng</button>
                <button type="button" class="btn-primary" onclick="window.print()">In hóa đơn</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
<script>
    const dateInput = document.getElementById('dateRangePicker');
    const clearBtn = document.getElementById('clearDateBtn');
    const initialDate = dateInput.value;
    
    let typingTimer;                
    const doneTypingInterval = 500; // Thời gian chờ (0.5 giây)
    const searchInput = document.getElementById('searchInput');
    const filterForm = document.getElementById('filterForm');

    <?php if (isset($_SESSION['flash_message'])): ?>
        Swal.fire({
            icon: '<?= $_SESSION['flash_message']['type'] ?>',
            title: '<?= $_SESSION['flash_message']['title'] ?>',
            text: '<?= $_SESSION['flash_message']['text'] ?>',
            timer: 2500,
            showConfirmButton: false
        });
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            // Xóa bộ đếm cũ mỗi khi người dùng gõ phím mới
            clearTimeout(typingTimer);
            
            // Thiết lập bộ đếm mới
            typingTimer = setTimeout(function() {
                // Tự động gửi Form để thực hiện Filter
                filterForm.submit();
            }, doneTypingInterval);
        });

        // Đưa con trỏ chuột về cuối văn bản sau khi trang load lại (giúp trải nghiệm mượt hơn)
        searchInput.focus();
        const val = searchInput.value;
        searchInput.value = '';
        searchInput.value = val;
    }
    // 1. Khởi tạo Litepicker bộ chọn ngày
    let start, end;
    if (initialDate && initialDate.includes(' - ')) {
        const parts = initialDate.split(' - ');
        start = parts[0];
        end = parts[1];
    }

    const picker = new Litepicker({
        element: dateInput,
        singleMode: false,
        numberOfMonths: 2,
        numberOfColumns: 2,
        format: 'YYYY-MM-DD',
        startDate: start,
        endDate: end,
        allowRepick: true,
        autoApply: true,
        setup: (picker) => {
            picker.on('selected', (date1, date2) => {
                if (dateInput.value !== initialDate) {
                    setTimeout(() => { filterForm.submit(); }, 100);
                }
            });
        },
    });

    // 2. Logic xóa bộ lọc ngày
    if (clearBtn) {
        clearBtn.addEventListener('click', (e) => {
            e.stopPropagation(); 
            dateInput.value = ''; 
            picker.clearSelection(); 
            document.getElementById('filterForm').submit();
        });
    }
    
    // 3. Menu thao tác (Dropdown Action)
    function toggleActionMenu(button, event) {
        event.stopPropagation();
        document.querySelectorAll('.dropdown-menu-content').forEach(menu => {
            if (menu !== button.nextElementSibling) {
                menu.classList.remove('show');
            }
        });
        const menu = button.nextElementSibling;
        menu.classList.toggle('show');
    }

    window.addEventListener('click', function(e) {
        if (!e.target.matches('.btn-more')) {
            document.querySelectorAll('.dropdown-menu-content').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });

    // 4. Xử lý xem chi tiết (Dùng dữ liệu Map từ PHP truyền vào)
    const BOOKING_STORAGE = <?= json_encode($detailMap) ?>;

    function viewBookingDetails(bookingId) {
        const data = BOOKING_STORAGE[bookingId];
        if (!data) {
            console.error("Không tìm thấy thông tin đơn hàng.");
            return;
        }

        document.getElementById('js-m-id').innerText = "#BK-" + data.id;
        document.getElementById('js-m-name').innerText = data.fullName;
        document.getElementById('js-m-phone').innerText = data.phone;
        document.getElementById('js-m-room').innerText = data.roomTypeName;
        document.getElementById('js-m-nights').innerText = data.nights;
        
        // Định dạng tiền tệ sang VND cho Modal
        const formattedTotal = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(Number(data.totalAmount.replace(/,/g, '')));
        document.getElementById('js-m-total').innerText = formattedTotal;
        
        document.getElementById('js-m-in').innerText = data.checkIn;
        document.getElementById('js-m-out').innerText = data.checkOut;
        
        const pBadge = document.getElementById('js-m-payment');
        pBadge.innerText = data.payment;
        pBadge.className = 'status-pill pill-' + data.payment.toLowerCase();

        document.getElementById('bookingDetailModal').style.display = 'flex';
    }

    function closeBookingModal() {
        document.getElementById('bookingDetailModal').style.display = 'none';
    }

    // 5. Các hành động xử lý đơn đặt phòng (SweetAlert2 Tiếng Việt)
    function processBookingAction(action, bookingId) {
        let config = {
            'checkin':  { title: 'Xác nhận Nhận phòng?', text: 'Khách hàng đã đến nhận phòng?', icon: 'info', color: '#2261E0' },
            'checkout': { title: 'Xác nhận Trả phòng?', text: 'Khách đã trả phòng và thanh toán đủ?', icon: 'warning', color: '#12B76A' },
            'restore':  { title: 'Khôi phục đơn hàng?', text: 'Đưa đơn hàng này trở lại trạng thái chờ?', icon: 'question', color: '#2261E0' },
            'cancel':   { title: 'Hủy đặt phòng?', text: 'Bạn có chắc chắn muốn hủy đơn này không?', icon: 'error', color: '#D92D20' }
        };

        const c = config[action];
        if(!c) return; 
        
        Swal.fire({
            title: c.title,
            text: c.text,
            icon: c.icon,
            showCancelButton: true,
            confirmButtonColor: c.color,
            cancelButtonColor: '#667085',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Đóng'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `?action=${action}&id=${bookingId}`;
            }
        });
    }

    function viewReview(bookingId) {
        const data = BOOKING_STORAGE[bookingId];
        if (!data || !data.rating) {
            Swal.fire('Thông báo', 'Đơn đặt phòng này chưa có đánh giá từ khách hàng.', 'info');
            return;
        }

        let stars = '⭐'.repeat(data.rating);
        Swal.fire({
            title: `Đánh giá từ ${data.fullName}`,
            html: `
                <div style="font-size: 1.5rem; margin-bottom: 10px;">${stars}</div>
                <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; font-style: italic;">
                    "${data.review_content}"
                </div>
            `,
            icon: 'success',
            confirmButtonText: 'Đóng',
            confirmButtonColor: '#2261E0'
        });
    }

    // 6. Xuất CSV
    function exportBookingCSV() {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('export', 'true');

        Swal.fire({
            title: 'Xác nhận xuất dữ liệu?',
            text: "Hệ thống sẽ tải xuống danh sách dựa trên các tiêu chí lọc hiện tại của bạn.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2261E0',
            cancelButtonColor: '#667085',
            confirmButtonText: '🚀 Tải file CSV',
            cancelButtonText: 'Hủy bỏ',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng đợi giây lát.',
                    timer: 2000,
                    showConfirmButton: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                window.location.href = "?" + urlParams.toString();
            }
        });
    }

    // Đóng modal bằng ESC hoặc click ra ngoài
    window.addEventListener('click', (e) => {
        const modal = document.getElementById('bookingDetailModal');
        if (e.target === modal) closeBookingModal();
    });
    window.addEventListener('keydown', (e) => {
        if (e.key === "Escape") closeBookingModal();
    });
</script>