/**
 * Rooms Management Events Handler - Complete Implementation
 * Xử lý sự kiện cho trang Quản Lý Phòng
 */

function getRoomsApiBaseUrl() {
    if (typeof window.BOOKMYROOM_API_BASE === 'string' && window.BOOKMYROOM_API_BASE.trim()) {
        return window.BOOKMYROOM_API_BASE.replace(/\/$/, '');
    }
    const el = document.querySelector('script[src*="rooms.js"]');
    if (el && el.src) {
        try {
            const u = new URL(el.src);
            const marker = '/public/js/admin/rooms.js';
            const i = u.pathname.indexOf(marker);
            if (i !== -1) {
                return u.origin + u.pathname.slice(0, i) + '/api';
            }
        } catch (err) { /* ignore */ }
    }
    return (window.location.origin || '') + '/BookMyRoom/api';
}

function roomsApiUrl(queryString) {
    const base = getRoomsApiBaseUrl();
    const q = queryString && queryString.charAt(0) === '?'
        ? queryString
        : (queryString ? '?' + queryString : '');
    return base + '/rooms.php' + q;
}

/**
 * Tìm kiếm: ô .rooms-search-input có thể inject sau DOMContentLoaded (sidebar partial).
 */
if (!window.__roomsSearchDelegationBound) {
    window.__roomsSearchDelegationBound = true;
    let roomsSearchDebounceTimer = null;

    function roomsSearchInput(el) {
        return el && el.classList && el.classList.contains('rooms-search-input');
    }

    function inRoomsPage(el) {
        const main = document.querySelector('.main-content');
        return main && el && main.contains(el);
    }

    document.addEventListener('input', function(e) {
        const t = e.target;
        if (!roomsSearchInput(t) || !inRoomsPage(t)) return;

        clearTimeout(roomsSearchDebounceTimer);
        const query = t.value.trim();

        if (query.length === 0) {
            loadRooms(1);
            return;
        }

        const grid = document.getElementById('room-list');
        if (grid) {
            grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;">⏳ Đang tìm kiếm...</div>';
        }

        roomsSearchDebounceTimer = setTimeout(function() {
            console.log('🔍 Searching rooms:', query);
            searchRooms(query);
        }, 300);
    });

    document.addEventListener('keydown', function(e) {
        if (e.key !== 'Enter') return;
        const t = e.target;
        if (!roomsSearchInput(t) || !inRoomsPage(t)) return;
        e.preventDefault();
        clearTimeout(roomsSearchDebounceTimer);
        const query = t.value.trim();
        if (query.length === 0) {
            loadRooms(1);
        } else {
            searchRooms(query);
        }
    });
}

document.addEventListener('adminPartialLoad', function(e) {
    if (e.detail && e.detail.page === 'rooms') {
        loadRooms(1);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    console.log('🏨 Rooms page loaded');

    if (document.getElementById('room-list')) {
        loadRooms();
    }

    // ===== FILTER SELECTS (Auto-apply) =====
    const filterSelects = document.querySelectorAll('.rooms-filter-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            console.log('⚙️ Filter changed:', this.getAttribute('data-filter'), this.value);
            applyFilters();
        });
    });

    // ===== VIEW BUTTON =====
    const viewButtons = document.querySelectorAll('.room-view-btn');
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const roomId = this.getAttribute('data-room-id');
            console.log('👁 View room ID:', roomId);
            openRoomDetail(roomId);
        });
    });

    // ===== EDIT BUTTON =====
    const editButtons = document.querySelectorAll('.room-edit-btn');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const roomId = this.getAttribute('data-room-id');
            console.log('✏️ Edit room ID:', roomId);
            openRoomEdit(roomId);
        });
    });

    // ===== FILTER BUTTON =====
    const filterBtn = document.querySelector('.rooms-filter-btn');
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            console.log('🔍 Filter button clicked');
            applyFilters();
        });
    }

    // ===== PAGINATION =====
    const paginationBtns = document.querySelectorAll('.rooms-pagination-btn');
    paginationBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const page = parseInt(this.getAttribute('data-page'));
            console.log('📄 Page clicked:', page);
            loadRooms(page);
            
            // Update active button
            paginationBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

/**
 * Load rooms with pagination
 */
async function loadRooms(page = 1) {
    try {
        const response = await fetch(roomsApiUrl(`action=getRooms&page=${page}&limit=12`));
        const result = await response.json();

        if (!result.success) {
            showError('Lỗi tải phòng: ' + result.error);
            return;
        }

        console.log('📦 Rooms loaded:', result.data.rooms.length);
        updateRoomsGrid(result.data.rooms);
        updatePagination(result.data);
        const pag = document.querySelector('.main-content .pagination');
        if (pag) pag.style.display = '';
    } catch (error) {
        console.error('Error loading rooms:', error);
        showError('Lỗi kết nối API');
    }
}

/**
 * Search rooms by query
 */
async function searchRooms(query) {
    try {
        const response = await fetch(roomsApiUrl(`action=searchRooms&query=${encodeURIComponent(query)}`));
        const result = await response.json();

        if (!result.success) {
            showError('Lỗi tìm kiếm: ' + result.error);
            return;
        }

        console.log('🔎 Search results:', result.data.rooms.length);
        updateRoomsGrid(result.data.rooms);
        const pag = document.querySelector('.main-content .pagination');
        if (pag) pag.style.display = 'none';
    } catch (error) {
        console.error('Error searching rooms:', error);
        showError('Lỗi kết nối API');
    }
}

/**
 * Apply filters
 */
async function applyFilters() {
    try {
        const hotelId = document.querySelector('[data-filter="hotel"]')?.value || '';
        const roomTypeId = document.querySelector('[data-filter="roomType"]')?.value || '';
        const status = document.querySelector('[data-filter="status"]')?.value || '';

        const formData = new FormData();
        formData.append('action', 'filterRooms');
        formData.append('page', 1);
        formData.append('limit', 12);
        if (hotelId) formData.append('hotelId', hotelId);
        if (roomTypeId) formData.append('roomTypeId', roomTypeId);
        if (status) formData.append('status', status);

        console.log('Applying filters - Hotel:', hotelId, 'Type:', roomTypeId, 'Status:', status);

        const response = await fetch(roomsApiUrl(''), {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (!result.success) {
            showError('Lỗi lọc: ' + result.error);
            return;
        }

        console.log('🎯 Filtered rooms:', result.data.rooms.length);
        updateRoomsGrid(result.data.rooms);
        updatePagination(result.data);
        const pag = document.querySelector('.main-content .pagination');
        if (pag) pag.style.display = '';
        showSuccess('Cập nhật danh sách phòng thành công');
    } catch (error) {
        console.error('Error applying filters:', error);
        showError('Lỗi kết nối API');
    }
}

/**
 * Open room detail modal
 */
async function openRoomDetail(roomId) {
    try {
        const response = await fetch(roomsApiUrl(`action=getRoomDetail&roomId=${encodeURIComponent(roomId)}`));
        const result = await response.json();

        if (!result.success) {
            showError('Lỗi lấy chi tiết: ' + result.error);
            return;
        }

        const room = result.data;
        console.log('📋 Room detail:', room);

        // Create and show modal
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>${room.roomType} #${room.roomNumber}</h2>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <p><strong>Khách sạn:</strong> ${room.hotelName}</p>
                    <p><strong>Tầng:</strong> ${room.floor}</p>
                    <p><strong>Giá:</strong> ${number_format(room.price)}đ</p>
                    <p><strong>Diện tích:</strong> ${room.area}m²</p>
                    <p><strong>Sức chứa:</strong> ${room.maxPeople} người</p>
                    <p><strong>Trạng thái:</strong> 
                        <span class="badge ${room.status === 'AVAILABLE' ? 'available' : (room.status === 'BOOKED' ? 'booked' : 'maintenance')}">
                            ${room.status === 'AVAILABLE' ? 'Còn trống' : (room.status === 'BOOKED' ? 'Đã đặt' : 'Bảo trì')}
                        </span>
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn-secondary modal-close">Đóng</button>
                    <button class="btn-primary" onclick="openRoomEdit(${roomId})">Chỉnh sửa</button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        modal.style.display = 'block';

        // Close modal handlers
        modal.querySelectorAll('.modal-close').forEach(btn => {
            btn.addEventListener('click', function() {
                modal.remove();
            });
        });
    } catch (error) {
        console.error('Error opening room detail:', error);
        showError('Lỗi lấy thông tin phòng');
    }
}

/**
 * Open room edit modal
 */
async function openRoomEdit(roomId) {
    try {
        const response = await fetch(roomsApiUrl(`action=getRoomDetail&roomId=${encodeURIComponent(roomId)}`));
        const result = await response.json();

        if (!result.success) {
            showError('Lỗi lấy chi tiết: ' + result.error);
            return;
        }

        const room = result.data;
        console.log('✏️ Editing room:', room);

        // Create edit modal
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Chỉnh sửa phòng #${room.roomNumber}</h2>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Trạng thái:</label>
                        <select id="status-edit" value="${room.status}">
                            <option value="AVAILABLE" ${room.status === 'AVAILABLE' ? 'selected' : ''}>Còn trống</option>
                            <option value="BOOKED" ${room.status === 'BOOKED' ? 'selected' : ''}>Đã đặt</option>
                            <option value="MAINTENANCE" ${room.status === 'MAINTENANCE' ? 'selected' : ''}>Bảo trì</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-secondary modal-close">Hủy</button>
                    <button class="btn-primary" onclick="saveRoomEdit(${roomId})">Lưu</button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        modal.style.display = 'block';

        // Close handlers
        modal.querySelectorAll('.modal-close').forEach(btn => {
            btn.addEventListener('click', function() {
                modal.remove();
            });
        });
    } catch (error) {
        console.error('Error opening room edit:', error);
        showError('Lỗi mở form chỉnh sửa');
    }
}

/**
 * Save room edit
 */
async function saveRoomEdit(roomId) {
    try {
        const status = document.getElementById('status-edit')?.value;

        if (!status) {
            showError('Vui lòng chọn trạng thái');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'updateRoomStatus');
        formData.append('roomId', roomId);
        formData.append('status', status);

        const response = await fetch(roomsApiUrl(''), {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (!result.success) {
            showError('Lỗi cập nhật: ' + result.error);
            return;
        }

        console.log('✅ Room updated successfully');
        showSuccess('Cập nhật phòng thành công');
        
        // Close modal and reload
        document.querySelector('.modal')?.remove();
        loadRooms();
    } catch (error) {
        console.error('Error saving room edit:', error);
        showError('Lỗi kết nối API');
    }
}

/**
 * Update rooms grid display
 */
function updateRoomsGrid(rooms) {
    const grid = document.getElementById('room-list');
    if (!grid) return;

    if (rooms.length === 0) {
        grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 40px;"><p>Không tìm thấy phòng nào</p></div>';
        return;
    }

    grid.innerHTML = rooms.map(room => `
        <div class="room-card" data-room-id="${room.id}">
            <div class="room-img">
                <img src="public/images/room-default.jpg" alt="Room image">
                <span class="badge ${room.status === 'AVAILABLE' ? 'available' : (room.status === 'BOOKED' ? 'booked' : 'maintenance')}">
                    ${room.status === 'AVAILABLE' ? 'Còn trống' : (room.status === 'BOOKED' ? 'Đã đặt' : 'Bảo trì')}
                </span>
            </div>

            <div class="room-body">
                <p class="hotel-name">${room.hotelName || 'N/A'}</p>
                <h3>${room.roomType || 'Unknown'} #${room.roomNumber}</h3>
                <p class="price">${number_format(room.price || 0)}đ</p>

                <div class="room-info">
                    <span>🛏 Tầng ${room.floor}</span>
                    <span>👤 ${room.maxPeople} người</span>
                    <span>📐 ${room.area}m²</span>
                </div>

                <div class="room-actions">
                    <button class="btn-edit room-view-btn" data-room-id="${room.id}">Xem</button>
                    <button class="btn-delete room-edit-btn" data-room-id="${room.id}">Sửa</button>
                </div>
            </div>
        </div>
    `).join('');

    // Re-attach event listeners
    document.querySelectorAll('.room-view-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const roomId = this.getAttribute('data-room-id');
            openRoomDetail(roomId);
        });
    });

    document.querySelectorAll('.room-edit-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const roomId = this.getAttribute('data-room-id');
            openRoomEdit(roomId);
        });
    });
}

/**
 * Update pagination buttons
 */
function updatePagination(data) {
    const paginationContainer = document.querySelector('.pagination');
    if (!paginationContainer) return;

    const totalPages = data.totalPages || 1;
    let html = '';

    for (let i = 1; i <= Math.min(totalPages, 5); i++) {
        html += `<button class="rooms-pagination-btn ${i === data.page ? 'active' : ''}" data-page="${i}">${i}</button>`;
    }

    paginationContainer.innerHTML = html;

    // Re-attach pagination listeners
    paginationContainer.querySelectorAll('.rooms-pagination-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const page = parseInt(this.getAttribute('data-page'));
            loadRooms(page);
            
            paginationContainer.querySelectorAll('.rooms-pagination-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

/**
 * Helper function to format numbers
 */
function number_format(num) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(num));
}

/**
 * Show error notification
 */
function showError(message) {
    console.error('❌', message);
    alert(message); // Replace with better notification UI later
}

/**
 * Show success notification
 */
function showSuccess(message) {
    console.log('✅', message);
    // Replace with better notification UI later
}

/**
 * Thay đổi trạng thái phòng
 */
function changeRoomStatus(roomId, newStatus) {
    console.log('Changing room status:', roomId, 'to', newStatus);
    // TODO: Gọi API
    // Status có thể là: available, occupied, maintenance, booked
    
    // Sau khi thành công:
    // - Cập nhật UI
    // - Hiển thị notification
}

/**
 * Bulk action (nếu có)
 */
function bulkUpdateRooms(roomIds, action, value) {
    console.log('Bulk action:', action, 'on rooms:', roomIds);
    // TODO: Thực hiện hành động hàng loạt
}
