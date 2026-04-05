<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="room-wrapper">
    <div class="rooms-container">
        <header class="rooms-header">
            <div class="rooms-header__text">
                <h1>Loại phòng & Kế hoạch giá</h1>
                <p>Quản lý kho phòng, tiện nghi và chiến lược giá linh hoạt của bạn.</p>
            </div>
            <div class="rooms-header__actions">
                <button class="btn btn-map" onclick="openRoomMap()">
                    <i class="fas fa-th"></i> Sơ đồ phòng vật lý
                </button>

                <select class="filter-select" onchange="filterByRoomType(this.value)">
                    <option value="">Tất cả loại phòng</option>
                    <?php foreach ($allTypes as $type): ?>
                        <option value="<?= $type['id'] ?>" <?= ($activeFilter == $type['id']) ? 'selected' : '' ?>>
                            <?= $type['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <button class="btn btn-add" onclick="openAddRoomModal()">+ Thêm loại phòng mới</button>
            </div>
        </header>
    
        <div class="rooms-grid">
            <?php if (!empty($rooms)): ?>
                <?php foreach ($rooms as $r): ?>
                    <article class="room-card">
                        <div class="room-card__image-wrapper">
                            <img src="<?= $r['primaryImage'] ?>" alt="<?= $r['name'] ?>" class="room-card__img">
                            <span class="badge <?= $r['badge_class'] ?>"><?= $r['badge_text'] ?></span>
                            <button class="btn-delete-icon" onclick="confirmDeleteRoom(<?= $r['id'] ?>, '<?= $r['name'] ?>')">🗑️</button>
                            <div class="room-card__title-overlay">
                                <h3><?= $r['name'] ?></h3>
                            </div>
                        </div>
                        
                        <div class="room-card__details">
                            <div class="amenities-tags">
                                <?php foreach ($r['amenities_list'] as $amenity): ?>
                                    <span><?= $amenity ?></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="inventory-price-row">
                                <div class="inv">
                                    <span class="label">TỔNG KHO PHÒNG</span>
                                    <span class="val">📋 <?= $r['totalInventory'] ?? 0 ?> Phòng</span>
                                </div>
                                <div class="price">
                                    <span class="label">GIÁ CƠ BẢN</span>
                                    <span class="val val--price"><?= number_format($r['basePrice'], 0, ',', '.') ?>đ<small>/đêm</small></span>
                                </div>
                            </div>
                            
                            <div class="room-card__actions">
                                <button class="btn btn-edit-outline" onclick="openEditRoomModal(<?= $r['id'] ?>)">🖋️ Sửa chi tiết</button>
                                <button class="btn btn-manage-units" onclick="openManageUnitsModal(<?= $r['id'] ?>)">🏨 Quản lý danh sách phòng</button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; grid-column: span 3; padding: 50px;">Chưa có cấu hình phòng nào được thiết lập.</p>
            <?php endif; ?>
        </div>
    
        <footer class="inventory-health-card">
            <div class="inventory-health-card__left">
                <div class="ih-icon">📈</div>
                <div class="ih-text">
                    <h4>Sức khỏe kho phòng</h4>
                    <p><?= $inventoryStats['healthScore'] ?>% loại phòng có lịch giá hoạt động trong 90 ngày tới.</p>
                </div>
            </div>
            <div class="inventory-health-card__right">
                <div class="ih-stat-item">
                    <span class="ih-val"><?= sprintf("%02d", $inventoryStats['totalActive']) ?></span>
                    <span class="ih-lab">TỔNG PHÒNG HOẠT ĐỘNG</span>
                </div>
                <div class="ih-stat-item">
                    <span class="ih-val"><?= sprintf("%02d", $inventoryStats['maintenance']) ?></span>
                    <span class="ih-lab">ĐANG BẢO TRÌ</span>
                </div>
            </div>
        </footer>
    </div>
    
    <div id="roomAddModal" class="custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Thêm loại phòng mới</h3>
                <button type="button" class="close-modal" onclick="closeAddModal()">✕</button>
            </div>
            <form id="addRoomForm" action="<?= URLROOT ?>/partner/addRoom" method="POST">
                <div class="modal-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div style="grid-column: span 2;">
                            <label>Chọn loại phòng hệ thống</label>
                            <select name="roomTypeId" class="form-control" required>
                                <option value="">-- Chọn loại phòng --</option>
                                <?php foreach ($systemRoomTypes as $st): ?>
                                    <option value="<?= $st['id'] ?>"><?= $st['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label>Giá cơ bản (VNĐ)</label>
                            <input type="number" name="basePrice" class="form-control" required>
                        </div>
                        <div>
                            <label>Sức chứa tối đa</label>
                            <input type="number" name="maxPeople" class="form-control" required>
                        </div>
                        <div>
                            <label>Diện tích (m²)</label>
                            <input type="number" name="area" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeAddModal()">Hủy</button>
                    <button type="submit" class="btn-primary">Tạo cấu hình</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="roomEditModal" class="custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Chỉnh sửa: <span id="display-room-name"></span></h3>
                <button type="button" class="close-modal" onclick="closeRoomModal()">✕</button>
            </div>
            <form id="editRoomForm" action="<?= URLROOT ?>/partner/updateRoom" method="POST">
                <input type="hidden" name="id" id="edit-room-id">
                <div class="modal-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div style="grid-column: span 2;">
                            <label>Tên loại phòng (Chỉ xem)</label>
                            <input type="text" id="edit-room-name" class="form-control" disabled>
                        </div>
                        <div>
                            <label>Giá cơ bản (VNĐ)</label>
                            <input type="number" name="basePrice" id="edit-room-price" class="form-control" required>
                        </div>
                        <div>
                            <label>Sức chứa</label>
                            <input type="number" name="maxPeople" id="edit-room-people" class="form-control" required>
                        </div>
                        <div>
                            <label>Diện tích (m²)</label>
                            <input type="number" name="area" id="edit-room-area" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeRoomModal()">Hủy</button>
                    <button type="submit" class="btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="manageUnitsModal" class="custom-modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3>Danh sách phòng: <span id="manage-room-name"></span></h3>
                <button type="button" class="close-modal" onclick="closeManageUnitsModal()">✕</button>
            </div>
            <div class="modal-body">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <p style="margin: 0; color: #666; font-size: 0.9rem;">Quản lý các số phòng thực tế cho cấu hình này.</p>
                    <button class="btn-primary" style="padding: 5px 12px; font-size: 0.8rem;" onclick="quickAddUnit()">+ Thêm số phòng</button>
                </div>
                
                <div style="max-height: 300px; overflow-y: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; text-align: left; position: sticky; top: 0;">
                                <th style="padding: 10px; border-bottom: 2px solid #eee;">Số phòng</th>
                                <th style="padding: 10px; border-bottom: 2px solid #eee;">Trạng thái</th>
                                <th style="padding: 10px; border-bottom: 2px solid #eee; text-align: right;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="units-list-body"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeManageUnitsModal()">Đóng</button>
            </div>
        </div>
    </div>
    <div id="roomMapModal" class="custom-modal">
        <div class="modal-content" style="max-width: 900px;"> 
            <div class="modal-header">
                <h3>🗺️ Sơ đồ phòng vật lý</h3>
                <button class="close-modal" onclick="closeRoomMap()">✕</button>
            </div>
            
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <div class="room-map-grid">
                    <?php if(!empty($roomMap)): ?>
                        <?php foreach ($roomMap as $floor => $rooms): ?>
                            <div class="floor-group">
                                <div class="floor-tag">Tầng <?= $floor == 0 ? 'G' : $floor ?></div>
                                <div class="floor-rooms">
                                    <?php foreach ($rooms as $r): ?>
                                        <?php 
                                            $currentStatus = strtoupper($r['status']);
                                            $statusClass = strtolower($currentStatus);
                                        ?>
                                        <div class="room-item status-<?= $statusClass ?>" 
                                            onclick="selectRoom(<?= $r['id'] ?>, '<?= $r['roomNumber'] ?>', '<?= $currentStatus ?>')">
                                            
                                            <span class="r-num"><?= $r['roomNumber'] ?></span>
                                            <span class="r-type"><?= $r['roomTypeName'] ?></span>
                                            
                                            <?php if($currentStatus === 'MAINTENANCE'): ?>
                                                <i class="fas fa-tools" style="position:absolute; top:5px; right:8px; font-size:0.75rem; color:#64748b;"></i>
                                            <?php endif; ?>

                                            <span class="status-dot"></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="text-align: center; padding: 40px; color: #94a3b8;">
                            <i class="fas fa-layer-group" style="font-size: 3rem; margin-bottom: 10px;"></i>
                            <p>Chưa có dữ liệu phòng vật lý cho khách sạn này.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeRoomMap()">Đóng cửa sổ</button>
            </div>
        </div>
    </div>
</div>

<script>
    const ROOM_STORAGE = <?= json_encode($roomDetailMap) ?>;
    const PHYSICAL_STORAGE = <?= json_encode($physicalRoomMap) ?>;
    let currentActiveConfigId = null;
    
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
    
    function openAddRoomModal() { document.getElementById('roomAddModal').style.display = 'flex'; }
    function closeAddModal() { document.getElementById('roomAddModal').style.display = 'none'; }
        
    function openEditRoomModal(roomId) {
        const r = ROOM_STORAGE[roomId];
        if (!r) return;
        document.getElementById('edit-room-id').value = r.id;
        document.getElementById('display-room-name').innerText = r.name;
        document.getElementById('edit-room-name').value = r.name;
        document.getElementById('edit-room-price').value = r.basePrice;
        document.getElementById('edit-room-area').value = r.area; 
        document.getElementById('edit-room-people').value = r.maxPeople; 
        document.getElementById('roomEditModal').style.display = 'flex';
    }
    function closeRoomModal() { document.getElementById('roomEditModal').style.display = 'none'; }
    
    function openManageUnitsModal(configId) {
        currentActiveConfigId = configId;
        const r = ROOM_STORAGE[configId];
        const units = PHYSICAL_STORAGE[configId] || []; 
    
        document.getElementById('manage-room-name').innerText = r.name;
        const tbody = document.getElementById('units-list-body');
        tbody.innerHTML = '';
    
        if (units.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" style="text-align:center; padding:20px; color:#999;">Chưa có số phòng nào được tạo.</td></tr>';
        } else {
            units.forEach(unit => {
                tbody.innerHTML += `
                    <tr>
                        <td style="padding:10px; border-bottom:1px solid #eee;"><strong>${unit.roomNumber}</strong></td>
                        <td style="padding:10px; border-bottom:1px solid #eee;">
                            <span class="badge ${unit.status}">
                                ${unit.status === 'AVAILABLE' ? 'TRỐNG' : unit.status}
                            </span>
                        </td>
                        <td style="padding:10px; border-bottom:1px solid #eee; text-align:right;">
                            <button onclick="confirmDeleteUnit(${unit.id})" style="background:none; border:none; color:#d33; cursor:pointer; font-size:1.1rem;">🗑️</button>
                        </td>
                    </tr>
                `;
            });
        }
        document.getElementById('manageUnitsModal').style.display = 'flex';
    }
    
    function closeManageUnitsModal() { document.getElementById('manageUnitsModal').style.display = 'none'; }
    
    function quickAddUnit() {
        Swal.fire({
            title: 'Thêm phòng vật lý mới',
            html: `
                <div style="text-align: left;">
                    <label style="font-size: 0.8rem; font-weight: bold;">Số lầu (Tầng)</label>
                    <input id="swal-floor" type="number" class="swal2-input" placeholder="Ví dụ: 3" min="1">
                    <label style="font-size: 0.8rem; font-weight: bold; margin-top: 10px; display: block;">Số thứ tự phòng</label>
                    <input id="swal-sequence" type="number" class="swal2-input" placeholder="Ví dụ: 05" min="1" max="99">
                    <small style="color: #666;">Số phòng sẽ tự động là: <b id="preview-room-no">...</b></small>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Xác nhận thêm',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#2261E0',
            didOpen: () => {
                const floorInp = document.getElementById('swal-floor');
                const seqInp = document.getElementById('swal-sequence');
                const preview = document.getElementById('preview-room-no');
    
                const updatePreview = () => {
                    const f = floorInp.value;
                    const s = seqInp.value.padStart(2, '0');
                    preview.innerText = f && seqInp.value ? (f + s) : '...';
                };
    
                floorInp.addEventListener('input', updatePreview);
                seqInp.addEventListener('input', updatePreview);
            },
            preConfirm: () => {
                const floor = document.getElementById('swal-floor').value;
                const sequence = document.getElementById('swal-sequence').value;
    
                if (!floor || !sequence) {
                    Swal.showValidationMessage('Vui lòng nhập đầy đủ thông tin!');
                    return false;
                }
                const roomNumber = floor + sequence.padStart(2, '0');
                return { floor, roomNumber };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { floor, roomNumber } = result.value;
                window.location.href = `<?= URLROOT ?>/partner/addPhysicalRoom?configId=${currentActiveConfigId}&roomNumber=${roomNumber}&floor=${floor}`;
            }
        });
    }
    
    function confirmDeleteUnit(unitId) {
        Swal.fire({
            title: 'Xóa phòng này?',
            text: "Phòng sẽ bị loại bỏ khỏi kho phòng vật lý.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?= URLROOT ?>/partner/deletePhysicalRoom/${unitId}`;
            }
        });
    }
    
    function filterByRoomType(roomTypeId) {
        const url = new URL(window.location.href);
        if (roomTypeId) url.searchParams.set('roomTypeId', roomTypeId);
        else url.searchParams.delete('roomTypeId');
        window.location.href = url.toString();
    }
    
    function confirmDeleteRoom(id, name) {
        Swal.fire({
            title: 'Xóa toàn bộ cấu hình?',
            text: `Hành động này sẽ xóa sạch các phòng vật lý thuộc loại "${name}"!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Xác nhận xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = `<?= URLROOT ?>/partner/deleteRoom/${id}`;
        });
    }
    
    window.addEventListener('click', (e) => {
        ['roomAddModal', 'roomEditModal', 'manageUnitsModal'].forEach(id => {
            const m = document.getElementById(id);
            if (e.target === m) m.style.display = 'none';
        });
    });
    
    ['addRoomForm', 'editRoomForm'].forEach(id => {
        document.getElementById(id).addEventListener('submit', () => {
            Swal.fire({ title: 'Đang xử lý...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });
        });
    });
    function openRoomMap() {
        document.getElementById('roomMapModal').style.display = 'flex';
    }

    function closeRoomMap() {
        document.getElementById('roomMapModal').style.display = 'none';
    }

    function selectRoom(id, roomNumber, currentStatus) {
        let title = '';
        let text = '';
        let confirmButtonText = '';

        // Chuẩn hóa trạng thái về viết hoa để so sánh chính xác
        const status = currentStatus.toUpperCase();

        if (status === 'MAINTENANCE') {
            title = `Hoàn tất bảo trì phòng ${roomNumber}?`;
            text = "Xác nhận phòng đã sửa xong và chuyển sang trạng thái chờ dọn dẹp.";
            confirmButtonText = "Xác nhận hoàn tất";
        } else if (status === 'AVAILABLE' || status === 'CLEANING') {
            title = `Đưa phòng ${roomNumber} vào bảo trì?`;
            text = "Phòng sẽ bị khóa và không thể nhận khách đặt mới cho đến khi sửa xong.";
            confirmButtonText = "Bắt đầu bảo trì";
        } else if (status === 'OCCUPIED') {
            Swal.fire({
                title: 'Không thể thực hiện',
                text: 'Phòng đang có khách ở, vui lòng thực hiện trả phòng trước khi bảo trì.',
                icon: 'warning',
                confirmButtonColor: '#2261E0'
            });
            return;
        } else {
            return; // Các trạng thái khác không xử lý
        }

        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2261E0',
            cancelButtonColor: '#64748b',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?= URLROOT ?>/partner/changeRoomStatus/${id}?current=${status}`;
            }
        });
    }
</script>