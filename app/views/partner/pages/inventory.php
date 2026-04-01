<div class="inventory-v3-wrapper">
    <div class="inventory-header">
        <div class="header-left">
            <h1>Inventory Timeline</h1>
            <p>Quản lý giá và phòng trống theo chuỗi ngày (30 ngày gần nhất)</p>
        </div>
        <!-- <div class="header-right">
            <button class="btn-bulk-update" onclick="openBulkModal()">
                <i class="fas fa-layer-group"></i> + Bulk Update
            </button>
        </div> -->
    </div>

    <div class="inventory-filter-bar">
        <div class="filter-controls">
            <div class="control-item">
                <span>📅</span>
                <input type="date" id="start_date" value="<?= $_GET['start_date'] ?? date('Y-m-d') ?>" onchange="updateInventoryFilter()">
            </div>
            <div class="control-item">
                <span>↔️</span>
                <select id="view_days" onchange="updateInventoryFilter()">
                    <option value="7" <?= (isset($_GET['view_days']) && $_GET['view_days'] == 7) ? 'selected' : '' ?>>7 Ngày</option>
                    <option value="14" <?= (!isset($_GET['view_days']) || $_GET['view_days'] == 14) ? 'selected' : '' ?>>14 Ngày</option>
                    <option value="30" <?= (isset($_GET['view_days']) && $_GET['view_days'] == 30) ? 'selected' : '' ?>>30 Ngày</option>
                </select>
            </div>
            <div class="control-item">
                <span>🏨</span>
                <select id="room_type" onchange="updateInventoryFilter()">
                    <option value="">Tất cả loại phòng</option>
                    <?php foreach ($roomTypes as $type): ?>
                        <option value="<?= $type['id'] ?>" <?= (isset($_GET['roomTypeId']) && $_GET['roomTypeId'] == $type['id']) ? 'selected' : '' ?>>
                            <?= $type['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="days-timeline">
        <?php foreach ($days as $day): 
            $dateObj = new DateTime($day['full']);
        ?>
            <div class="day-card">
                <div class="date-block <?= $day['is_weekend'] ? 'weekend' : '' ?>">
                    <span class="day-name"><?= $day['day_name'] ?></span>
                    <div class="day-num"><?= $day['day_num'] ?></div>
                    <span class="month-year"><?= $dateObj->format('M Y') ?></span>
                </div>

                <div class="rooms-matrix">
                    <?php foreach ($grid as $configId => $item): 
                        $d = $item['days'][$day['full']];
                        $isSoldOut = ($d['available'] <= 0);
                    ?>
                        <div class="room-data-point">
                            <span class="room-type-name"><?= $item['info']['name'] ?></span>
                            <div class="room-price-display">
                                <?= number_format($d['price'], 0) ?>
                                <small>đ</small>
                            </div>
                            <div class="stock-status <?= $isSoldOut ? 'stock-soldout' : 'stock-available' ?>">
                                <span><?= $isSoldOut ? '🚫 Hết phòng' : '✔ Còn ' . $d['available'] . ' phòng' ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="day-actions">
                    <button class="btn-edit-day" onclick="editSingleDay('<?= $day['full'] ?>')">
                        ✏ Chỉnh sửa
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="quickEditModal" class="inventory-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Chỉnh sửa ngày <span id="displayDate"></span></h3>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <form id="quickEditForm" action="<?= URLROOT ?>/partner/updateInventory" method="POST">
                <input type="hidden" name="date" id="inputDate">
                <div class="modal-body">
                    <div class="modal-quick-actions">
                        <span>Thao tác nhanh:</span>
                        <button type="button" class="btn-action-sm open-all" onclick="setAllStatus('open')">🔓 Mở tất cả</button>
                        <button type="button" class="btn-action-sm close-all" onclick="setAllStatus('closed')">🔒 Đóng tất cả</button>
                    </div>

                    <div id="roomEditList">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Hủy</button>
                    <button type="submit" class="btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>

    <div id="bulkUpdateModal" class="inventory-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Cập nhật hàng loạt (Bulk Update)</h3>
                <button class="close-btn" onclick="closeBulkModal()">&times;</button>
            </div>
            <form action="<?= URLROOT ?>/partner/processBulkUpdate" method="POST">
                <div class="modal-body">
                    <div class="row-flex" style="display: flex; gap: 15px; margin-bottom: 20px;">
                        <div class="input-group" style="flex: 1;">
                            <label>Từ ngày</label>
                            <input type="date" name="startDate" id="bulkStartDate" required class="form-control" 
                                min="<?= date('Y-m-d') ?>"> 
                        </div>
                        <div class="input-group" style="flex: 1;">
                            <label>Đến ngày</label>
                            <input type="date" name="endDate" id="bulkEndDate" required class="form-control">
                        </div>
                    </div>

                    <div class="input-group" style="margin-bottom: 20px;">
                        <label>Áp dụng cho các ngày:</label>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;">
                            <?php 
                            $weekdays = [1=>'T2', 2=>'T3', 3=>'T4', 4=>'T5', 5=>'T6', 6=>'T7', 7=>'CN'];
                            foreach($weekdays as $val => $label): ?>
                                <label style="background: #f1f5f9; padding: 5px 10px; border-radius: 5px; cursor: pointer;">
                                    <input type="checkbox" name="weekdays[]" value="<?= $val ?>" checked> <?= $label ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="input-group" style="margin-bottom: 20px;">
                        <label>Loại phòng áp dụng:</label>
                        <select name="roomConfigIds[]" multiple class="form-control" style="height: 100px;" required>
                            <?php foreach($roomTypes as $type): ?>
                                <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small style="color: #94a3b8;">Giữ Ctrl để chọn nhiều loại phòng</small>
                    </div>

                    <div class="row-flex" style="display: flex; gap: 15px; background: #f8fafc; padding: 15px; border-radius: 12px;">
                        <div class="input-group" style="flex: 1;">
                            <label>Giá mới (VNĐ)</label>
                            <input type="number" name="bulkPrice" placeholder="Để trống nếu không đổi giá" class="form-control">
                        </div>
                        <div class="input-group" style="flex: 1;">
                            <label>Trạng thái</label>
                            <select name="bulkStatus" class="form-control">
                                <option value="">-- Giữ nguyên --</option>
                                <option value="open">🟢 Hoạt động</option>
                                <option value="closed">🟠 Tạm đóng</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeBulkModal()">Hủy</button>
                    <button type="submit" class="btn-primary" style="background: #2261E0;">Xác nhận cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const inventoryGrid = <?= json_encode($grid) ?>;
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

function updateInventoryFilter() {
    const start = document.getElementById('start_date').value;
    const view = document.getElementById('view_days').value;
    const type = document.getElementById('room_type').value;
    window.location.href = `?controller=partner&action=inventory&start_date=${start}&view_days=${view}&roomTypeId=${type}`;
}

function setAllStatus(status) {
    const selects = document.querySelectorAll('#roomEditList select');
    selects.forEach(select => {
        select.value = status;
    });
}

function editSingleDay(date) {
    const modal = document.getElementById('quickEditModal');
    const displayDate = document.getElementById('displayDate');
    const inputDate = document.getElementById('inputDate');
    const roomList = document.getElementById('roomEditList');

    displayDate.innerText = date;
    inputDate.value = date;
    roomList.innerHTML = ''; 

    for (const configId in inventoryGrid) {
        const room = inventoryGrid[configId];
        const dayData = room.days[date];

        const row = document.createElement('div');
        row.className = 'room-edit-row';
        row.innerHTML = `
            <div class="room-info">
                <strong>${room.info.name}</strong>
                <small>Khả dụng tối đa: ${room.info.total} phòng</small>
            </div>
            <div class="room-inputs">
                <div class="input-group">
                    <label>Giá (VNĐ)</label>
                    <input type="number" name="prices[${configId}]" value="${dayData.price}" min="0" step="1000">
                </div>
                <div class="input-group">
                    <label>Bán hàng</label>
                    <select name="status[${configId}]">
                        <option value="open" ${!dayData.is_sold_out ? 'selected' : ''}>Hoạt động</option>
                        <option value="closed" ${dayData.is_sold_out ? 'selected' : ''}>Tạm đóng</option>
                    </select>
                </div>
            </div>
        `;
        roomList.appendChild(row);
    }
    modal.style.display = 'flex';
}

function closeModal() {
    document.getElementById('quickEditModal').style.display = 'none';
}

// Đóng modal khi click ra ngoài
window.onclick = function(event) {
    const modal = document.getElementById('quickEditModal');
    if (event.target == modal) closeModal();
}
function openBulkModal() {
    document.getElementById('bulkUpdateModal').style.display = 'flex';
}
function closeBulkModal() {
    document.getElementById('bulkUpdateModal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('bulkStartDate');
    const endDateInput = document.getElementById('bulkEndDate');

    startDateInput.addEventListener('change', function() {
        if (this.value) {
            // Tạo đối tượng Date từ giá trị ngày bắt đầu
            let selectedDate = new Date(this.value);
            
            // Cộng thêm 1 ngày
            selectedDate.setDate(selectedDate.getDate() + 1);
            
            // Chuyển ngược lại thành định dạng YYYY-MM-DD
            let minEndDate = selectedDate.toISOString().split('T')[0];
            
            // Cập nhật thuộc tính min cho ô Đến ngày
            endDateInput.min = minEndDate;

            // Nếu ngày kết thúc hiện tại nhỏ hơn ngày min mới, hãy cập nhật lại nó
            if (endDateInput.value && endDateInput.value < minEndDate) {
                endDateInput.value = minEndDate;
            }
        }
    });
});
</script>