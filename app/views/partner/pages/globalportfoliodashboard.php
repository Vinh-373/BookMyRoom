<?php
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

?>
<div class="portfolio-wrapper">
    <section class="dashboard-header">
        <div class="welcome-msg">
            <h1>Chào mừng trở lại, <?= $_SESSION['user_name'] ?? 'Đối tác' ?>!</h1>
            <p>Bạn muốn quản lý khách sạn nào trong hôm nay?</p>
        </div>
        <button class="btn-add-property" onclick="openAddHotelModal()">+ Thêm khách sạn mới</button>
    </section>
    
    <div class="hotel-grid">
        <?php if (!empty($hotels)): ?>
            <?php foreach ($hotels as $hotel): ?>
            <div class="hotel-card">
                <div class="card-thumb">
                    <img src="<?= $hotel['imageUrl'] ?? 'h1.png' ?>" alt="<?= $hotel['hotelName'] ?>">
                    <?php 
                        $status = $hotel['status'] ?? 'ACTIVE';
                        $badgeClass = '';
                        $statusText = '';
                        
                        switch($status) {
                            case 'ACTIVE':
                                $badgeClass = 'badge-active';
                                $statusText = '● Đang hoạt động';
                                break;
                            case 'PENDING_STOP':
                                $badgeClass = 'badge-pending';
                                $statusText = '⌛ Chờ duyệt dừng';
                                break;
                            case 'STOPPED':
                                $badgeClass = 'badge-stopped';
                                $statusText = '🚫 Đã dừng';
                                break;
                        }
                    ?>
                    <span class="hotel-status-badge <?= $badgeClass ?>"><?= $statusText ?></span>
                    <button class="btn-more" title="Thêm tùy chọn" onclick="toggleHotelMenu(event, <?= $hotel['id'] ?>)">⋮</button>

                    <div id="hotel-menu-<?= $hotel['id'] ?>" class="hotel-action-menu">
                        <a href="javascript:void(0);" 
                            onclick='openEditHotelModal(<?= json_encode($hotel) ?>)'>
                            <i class="fas fa-edit"></i> Chỉnh sửa thông tin
                        </a>
                        
                        <?php if (($hotel['status'] ?? 'ACTIVE') === 'ACTIVE'): ?>
                            <div class="divider"></div>
                            <a href="javascript:void(0);" class="text-danger" 
                            onclick="confirmRequestStop(<?= $hotel['id'] ?>, '<?= addslashes($hotel['hotelName']) ?>')">
                                <i class="fas fa-hand-paper"></i> Yêu cầu dừng hoạt động
                            </a>
                        <?php elseif ($hotel['status'] === 'PENDING_STOP'): ?>
                            <div class="divider"></div>
                            <a href="javascript:void(0);" class="text-warning disabled" style="cursor: not-allowed; opacity: 0.7;">
                                <i class="fas fa-clock"></i> Đang chờ xét duyệt dừng
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <h3><?= $hotel['hotelName'] ?></h3>
                    <p class="location">📍 <?= $hotel['address'] ?>, <?= $hotel['wardName'] ?>, <?= $hotel['cityName'] ?></p>
                    
                    <div class="card-stats">
                        <div class="stat-box">
                            <span class="label">TỔNG SỐ PHÒNG</span>
                            <span class="val">🛏️ <?= $hotel['total_rooms'] ?></span>
                        </div>
                        <div class="stat-box">
                            <span class="label">ĐÁNH GIÁ</span>
                            <span class="val">⭐ <?= number_format($hotel['rating'], 1) ?></span>
                        </div>
                    </div>
                    <a href="<?= URLROOT ?>/manage/<?= $hotel['id'] ?>" class="btn-manage">ĐI ĐẾN QUẢN LÝ →</a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>Bạn chưa có khách sạn nào. Hãy bắt đầu bằng việc thêm khách sạn mới!</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="global-stats-bar">
        <div class="stat-group">
            <div class="stat-icon">💳</div>
            <div class="stat-info">
                <span class="label">TỔNG DOANH THU (THÁNG)</span>
                <span class="value"><?= number_format((float) str_replace(',', '', $chain_revenue), 0, ',', '.') ?>đ</span>
            </div>
        </div>
        <div class="stat-group">
            <div class="stat-icon">📅</div>
            <div class="stat-info">
                <span class="label">TỔNG ĐƠN ĐẶT (THÁNG)</span>
                <span class="value"><?= number_format($total_bookings) ?></span>
            </div>
        </div>
        <div class="portfolio-health">
            <div class="health-meta">
                <span class="label">SỨC KHỎE HỆ THỐNG</span>
                <span class="percentage"><?= $portfolio_health ?>%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?= $portfolio_health ?>%"></div>
            </div>
            <button class="btn-download" title="Tải báo cáo">📥</button>
        </div>
    </div>
        <div id="hotelModal" class="custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">🏨 Đăng ký khách sạn mới</h3>
                <button class="close-modal" onclick="closeHotelModal()">✕</button>
            </div>
            <form id="hotelForm" action="<?= URLROOT ?>/partner/addHotel" method="POST">
                <input type="hidden" name="hotelId" id="hotelId">
                
                <div class="modal-body">
                    <div class="form-row">
                        <label>Tên khách sạn</label>
                        <input type="text" name="hotelName" id="inputName" placeholder="Nhập tên khách sạn chính thức" required>
                    </div>
                    <div class="form-row">
                        <label>Mô tả</label>
                        <textarea name="description" id="inputDesc" rows="3" placeholder="Giới thiệu ngắn gọn..."></textarea>
                    </div>
                    <div class="form-grid">
                        <div class="form-row">
                            <label>Tỉnh / Thành phố</label>
                            <select name="cityId" id="citySelect" onchange="loadWards(this.value)" required>
                                <option value="">-- Chọn --</option>
                                <?php foreach($cities as $city): ?>
                                    <option value="<?= $city['id'] ?>"><?= $city['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-row">
                            <label>Phường / Xã</label>
                            <select name="wardId" id="wardSelect" required>
                                <option value="">-- Chọn Phường --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <label>Địa chỉ chi tiết</label>
                        <input type="text" name="address" id="inputAddr" placeholder="Số nhà, tên đường..." required>
                    </div>
                    <hr>
                    <div class="form-row">
                        <label>🖼️ Quản lý hình ảnh (Dùng URL ảnh mạng)</label>
                        <div class="input-group-url" style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <input type="text" id="image_url_input" placeholder="Dán link ảnh vào đây..." style="flex: 1;">
                            <button type="button" class="btn-add-url" onclick="addImageUrl()" style="padding: 0 15px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">+ Thêm</button>
                        </div>
                        
                        <div id="url-image-container" class="image-management-grid">
                            </div>
                        
                        <input type="hidden" name="image_data" id="image_data_json">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeHotelModal()">Hủy</button>
                    <button type="submit" id="btnSubmit" class="btn-submit">Xác nhận thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let hotelImages = [];
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
function openAddHotelModal() {
    const form = document.getElementById('hotelForm');
    form.reset();
    form.action = '<?= URLROOT ?>/partner/addHotel';
    
    document.getElementById('modalTitle').innerText = '🏨 Đăng ký khách sạn mới';
    document.getElementById('btnSubmit').innerText = 'Xác nhận thêm';
    document.getElementById('hotelId').value = '';
    document.getElementById('wardSelect').innerHTML = '<option value="">-- Chọn Phường --</option>';
    
    document.getElementById('hotelModal').style.display = 'flex';
}


function openEditHotelModal(hotel) {
    const form = document.getElementById('hotelForm');
    form.action = '<?= URLROOT ?>/partner/editHotel/' + hotel.id;

    document.getElementById('modalTitle').innerText = '📝 Chỉnh sửa thông tin';
    document.getElementById('btnSubmit').innerText = 'Lưu thay đổi';
    
    document.getElementById('hotelId').value = hotel.id;
    document.getElementById('inputName').value = hotel.hotelName;
    document.getElementById('inputDesc').value = hotel.description || '';
    document.getElementById('citySelect').value = hotel.cityId;
    document.getElementById('inputAddr').value = hotel.address;

    loadWards(hotel.cityId, hotel.wardId);

    hotelImages = hotel.images || []; 
    renderUrlImages();

    document.getElementById('hotelModal').style.display = 'flex';
}

function closeHotelModal() {
    document.getElementById('hotelModal').style.display = 'none';
}

function loadWards(cityId, selectedWardId = null) {
    const wardSelect = document.getElementById('wardSelect');
    if (!cityId) return;

    fetch('<?= URLROOT ?>/partner/getWardsAjax/' + cityId)
        .then(res => res.json())
        .then(data => {
            wardSelect.innerHTML = '<option value="">-- Chọn Phường --</option>';
            data.forEach(w => {
                const isSelected = (selectedWardId && w.id == selectedWardId) ? 'selected' : '';
                wardSelect.innerHTML += `<option value="${w.id}" ${isSelected}>${w.name}</option>`;
            });
        });
}

function toggleHotelMenu(event, id) {
    event.stopPropagation();
    const allMenus = document.querySelectorAll('.hotel-action-menu');
    const currentMenu = document.getElementById(`hotel-menu-${id}`);

    allMenus.forEach(m => {
        if (m.id !== `hotel-menu-${id}`) m.style.display = 'none';
    });

    currentMenu.style.display = (currentMenu.style.display === 'block') ? 'none' : 'block';
}

window.addEventListener('click', function() {
    document.querySelectorAll('.hotel-action-menu').forEach(m => m.style.display = 'none');
});

function confirmRequestStop(id, name) {
    Swal.fire({
        title: 'Yêu cầu dừng hoạt động?',
        text: `Hệ thống sẽ kiểm tra các đơn hàng hiện có của "${name}" trước khi gửi yêu cầu tới Admin.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Gửi yêu cầu xét duyệt',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `<?= URLROOT ?>/partner/requestStop/${id}`;
        }
    });
}

function addImageUrl() {
    const input = document.getElementById('image_url_input');
    const url = input.value.trim();

    if (url === "") return;
    
    // Nếu là ảnh đầu tiên, mặc định là ảnh chính
    const isPrimary = hotelImages.length === 0 ? 1 : 0;
    
    hotelImages.push({ url: url, isPrimary: isPrimary });
    input.value = ""; // Xóa input
    renderUrlImages();
}

function renderUrlImages() {
    const container = document.getElementById('url-image-container');
    container.innerHTML = "";

    hotelImages.forEach((img, index) => {
        const div = document.createElement('div');
        div.className = `url-preview-item ${img.isPrimary ? 'is-primary' : ''}`;
        div.innerHTML = `
            ${img.isPrimary ? '<div class="primary-label">Ảnh chính</div>' : ''}
            <img src="${img.url}" onerror="this.src='https://placehold.co/100x100?text=Lỗi+ảnh'">
            <button type="button" class="btn-remove-url" onclick="removeImage(${index})">✕</button>
            <button type="button" class="btn-set-primary" onclick="setPrimary(${index})">
                ${img.isPrimary ? '✅ Đang là ảnh chính' : 'Lấy làm ảnh chính'}
            </button>
        `;
        container.appendChild(div);
    });

    // Cập nhật vào input ẩn để gửi đi
    document.getElementById('image_data_json').value = JSON.stringify(hotelImages);
}

function setPrimary(index) {
    hotelImages.forEach((img, i) => img.isPrimary = (i === index ? 1 : 0));
    renderUrlImages();
}

function removeImage(index) {
    const wasPrimary = hotelImages[index].isPrimary;
    hotelImages.splice(index, 1);
    
    // Nếu xóa mất ảnh chính, set ảnh đầu tiên còn lại làm chính
    if (wasPrimary && hotelImages.length > 0) {
        hotelImages[0].isPrimary = 1;
    }
    renderUrlImages();
}
</script>