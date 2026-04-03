/**
 * Hotels Events Handler
 * Xử lý sự kiện cho trang Quản Lý Khách Sạn
 */

function getHotelsApiBaseUrl() {
    if (typeof window.BOOKMYROOM_API_BASE === 'string' && window.BOOKMYROOM_API_BASE.trim()) {
        return window.BOOKMYROOM_API_BASE.replace(/\/$/, '');
    }
    const el = document.querySelector('script[src*="hotels.js"]');
    if (el && el.src) {
        try {
            const u = new URL(el.src);
            const marker = '/public/js/admin/hotels.js';
            const i = u.pathname.indexOf(marker);
            if (i !== -1) {
                return u.origin + u.pathname.slice(0, i) + '/api';
            }
        } catch (err) { /* ignore */ }
    }
    return (window.location.origin || '') + '/BookMyRoom/api';
}

function hotelsApiUrl(queryString) {
    const base = getHotelsApiBaseUrl();
    const q = queryString && queryString.charAt(0) === '?'
        ? queryString
        : (queryString ? '?' + queryString : '');
    return base + '/hotels.php' + q;
}

/**
 * Tìm kiếm: .hotels-search-input có thể inject sau DOMContentLoaded (sidebar partial).
 */
if (!window.__hotelsSearchDelegationBound) {
    window.__hotelsSearchDelegationBound = true;
    let hotelsSearchDebounceTimer = null;

    function hotelsSearchEl(el) {
        return el && el.classList && el.classList.contains('hotels-search-input');
    }

    function inHotelsPage(el) {
        const main = document.querySelector('.main-content');
        return main && el && main.contains(el);
    }

    document.addEventListener('input', function(e) {
        const t = e.target;
        if (!hotelsSearchEl(t) || !inHotelsPage(t)) return;

        clearTimeout(hotelsSearchDebounceTimer);
        const query = (t.value || '').trim();

        if (query.length >= 2) {
            const tbody = document.querySelector('.main-content .table-container tbody');
            if (tbody) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;">⏳ Đang tìm kiếm...</td></tr>';
            }
        }

        hotelsSearchDebounceTimer = setTimeout(function() {
            if (query.length >= 2) {
                searchHotels(query);
            } else if (query.length === 0) {
                loadHotels();
            }
        }, 300);
    });

    document.addEventListener('keydown', function(e) {
        if (e.key !== 'Enter') return;
        const t = e.target;
        if (!hotelsSearchEl(t) || !inHotelsPage(t)) return;
        e.preventDefault();
        clearTimeout(hotelsSearchDebounceTimer);
        const query = (t.value || '').trim();
        if (query.length >= 2) {
            searchHotels(query);
        } else {
            loadHotels();
        }
    });
}

document.addEventListener('adminPartialLoad', function(e) {
    if (e.detail && e.detail.page === 'hotels') {
        loadHotels();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    console.log('Hotels JS Loaded');

    // ==================== Xử lý Nút "Thêm Đối Tác Mới" ====================
    const addPartnerBtn = document.querySelector('.hotels-add-partner-btn');
    
    if (addPartnerBtn) {
        addPartnerBtn.addEventListener('click', function() {
            console.log('Add new partner clicked');
            // TODO: Mở modal thêm đối tác mới
            openAddPartnerModal();
        });
    }

    // ==================== Xử lý Select Filters ====================
    const filterSelects = document.querySelectorAll('.hotels-filter-select');
    
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            const filterType = this.getAttribute('data-filter');
            const filterValue = this.value;
            
            // Highlight selected option
            this.style.fontWeight = filterValue ? 'bold' : 'normal';
            
            // Auto-apply filter on change
            applyFilters();
        });
    });

    // ==================== Xử lý Table Rows - View/Edit/Delete ====================
    
    // View Button
    const viewButtons = document.querySelectorAll('.hotel-view-btn');
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const hotelId = this.getAttribute('data-hotel-id');
            console.log('👁 View hotel ID:', hotelId);
            openHotelDetail(hotelId);
        });
    });

    // Edit Button
    const editButtons = document.querySelectorAll('.hotel-edit-btn');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const hotelId = this.getAttribute('data-hotel-id');
            console.log('✏️ Edit hotel ID:', hotelId);
            openHotelEdit(hotelId);
        });
    });

    // Block/Suspend Button
    const blockButtons = document.querySelectorAll('.hotel-block-btn');
    blockButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const hotelId = this.getAttribute('data-hotel-id');
            const action = this.textContent.includes('block') ? 'block' : 'suspend';
            
            const confirmMsg = action === 'block' ? 'Bạn chắc chắn muốn khóa khách sạn này?' : 'Bạn chắc chắn muốn tạm dừng khách sạn này?';
            
            if (confirm(confirmMsg)) {
                console.log('🛑 Block hotel ID:', hotelId);
                blockHotel(hotelId);
            }
        });
    });

    // ==================== Xử lý Table Row Hover ====================
    const tableRows = document.querySelectorAll('table tbody tr');
    
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            const actionBtns = this.querySelectorAll('.hotel-action-btn');
            actionBtns.forEach(btn => {
                btn.style.opacity = '1';
                btn.style.pointerEvents = 'auto';
            });
        });
        
        row.addEventListener('mouseleave', function() {
            const actionBtns = this.querySelectorAll('.hotel-action-btn');
            actionBtns.forEach(btn => {
                btn.style.opacity = '0';
                btn.style.pointerEvents = 'none';
            });
        });
    });

    // ==================== Xử lý Filter Button (Lọc Dữ Liệu) ====================
    const filterBtn = document.querySelector('.hotels-filter-btn');
    
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            console.log('🔍 Filter button clicked');
            applyFilters();
        });
    }
    
    // ==================== Xử lý Nút Reset ====================
    const resetBtn = document.querySelector('.btn-secondary');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            console.log('🔄 Reset filters clicked');
            // Clear all filters
            const filterSelects = document.querySelectorAll('.hotels-filter-select');
            filterSelects.forEach(select => {
                select.value = '';
                select.style.fontWeight = 'normal';
            });
            
            // Clear search
            const searchInput = document.querySelector('.hotels-search-input');
            if (searchInput) {
                searchInput.value = '';
            }
            
            // Reload all hotels
            loadHotels();
        });
    }

    // ==================== Xử lý Pagination ====================
    const paginationButtons = document.querySelectorAll('.hotel-pagination-btn');
    
    paginationButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.classList.contains('active')) return;
            
            const page = this.textContent.trim();
            
            if (page && !isNaN(page)) {
                paginationButtons.forEach(b => b.classList.remove('active', 'bg-white'));
                this.classList.add('active', 'bg-white');
                
                loadHotels(null, null, page);
            }
        });
    });

});

// ==================== API Functions ====================

/**
 * Tải danh sách khách sạn từ API
 * @param {string} query - Từ khóa tìm kiếm
 * @param {object} filters - Đối tượng lọc {cityId: 1, rating: 4.5}
 * @param {number} page - Trang cần tải
 */
async function loadHotels(query = null, filters = null, page = 1) {
    try {
        const url = hotelsApiUrl('action=getHotels&page=' + page + '&limit=10');
        
        const response = await fetch(url);
        const result = await response.json();
        
        if (!result.success) {
            showError('Lỗi tải dữ liệu: ' + result.error);
            return;
        }
        
        console.log('Hotels loaded:', result.data);
        updateHotelsTable(result.data.hotels);
        updatePagination(result.data.pagination);
        const pag = document.querySelector('.main-content .pagination');
        if (pag) pag.style.display = '';
        
    } catch (error) {
        showError('Lỗi kết nối: ' + error.message);
        console.error(error);
    }
}

/**
 * Tìm kiếm khách sạn
 * @param {string} query - Từ khóa tìm kiếm
 */
async function searchHotels(query) {
    try {
        const url = hotelsApiUrl('action=searchHotels&query=' + encodeURIComponent(query));
        
        const response = await fetch(url);
        const result = await response.json();
        
        if (!result.success) {
            showError('Lỗi tìm kiếm: ' + result.error);
            return;
        }
        
        console.log('Search results:', result.data);
        updateHotelsTable(result.data.results);
        const pag = document.querySelector('.main-content .pagination');
        if (pag) pag.style.display = 'none';
        
    } catch (error) {
        showError('Lỗi tìm kiếm: ' + error.message);
        console.error(error);
    }
}

/**
 * Áp dụng bộ lọc
 */
async function applyFilters() {
    try {
        const filterSelects = document.querySelectorAll('.hotels-filter-select');
        const formData = new FormData();
        
        formData.append('action', 'filterHotels');
        formData.append('page', 1);
        formData.append('limit', 10);
        
        filterSelects.forEach(select => {
            const filterType = select.getAttribute('data-filter');
            const value = select.value;
            if (value) {
                formData.append(filterType + 'Id', value);
            }
        });
        
        const response = await fetch(hotelsApiUrl(''), {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (!result.success) {
            showError('Lỗi lọc: ' + result.error);
            return;
        }
        
        console.log('Filtered hotels:', result.data);
        updateHotelsTable(result.data.hotels);
        const pag = document.querySelector('.main-content .pagination');
        if (pag) pag.style.display = '';
        
    } catch (error) {
        showError('Lỗi lọc: ' + error.message);
        console.error(error);
    }
}

/**
 * Xem chi tiết khách sạn
 * @param {number} hotelId - ID khách sạn
 */
async function openHotelDetail(hotelId) {
    try {
        console.log('Fetching hotel detail:', hotelId);
        
        const url = hotelsApiUrl('action=getHotelDetail&hotelId=' + encodeURIComponent(hotelId));
        const response = await fetch(url);
        const result = await response.json();
        
        if (!result.success) {
            showError('Lỗi tải chi tiết: ' + result.error);
            return;
        }
        
        console.log('Hotel detail:', result.data);
        showHotelDetailModal(result.data);
        
    } catch (error) {
        showError('Lỗi tải chi tiết: ' + error.message);
        console.error(error);
    }
}

/**
 * Chỉnh sửa khách sạn
 * @param {number} hotelId - ID khách sạn
 */
async function openHotelEdit(hotelId) {
    try {
        const url = hotelsApiUrl('action=getHotelDetail&hotelId=' + encodeURIComponent(hotelId));
        const response = await fetch(url);
        const result = await response.json();
        
        if (!result.success) {
            showError('Lỗi tải dữ liệu: ' + result.error);
            return;
        }
        
        console.log('Edit hotel:', result.data);
        showHotelEditModal(result.data.hotel);
        
    } catch (error) {
        showError('Lỗi: ' + error.message);
        console.error(error);
    }
}

/**
 * Khóa/Tạm dừng khách sạn
 * @param {number} hotelId - ID khách sạn
 */
async function blockHotel(hotelId) {
    try {
        const formData = new FormData();
        formData.append('action', 'blockHotel');
        formData.append('hotelId', hotelId);
        formData.append('reason', 'Khóa từ admin panel');
        
        const response = await fetch(hotelsApiUrl(''), {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (!result.success) {
            showError('Lỗi khóa khách sạn: ' + result.error);
            return;
        }
        
        showSuccess('Khóa khách sạn thành công');
        loadHotels(); // Reload table
        
    } catch (error) {
        showError('Lỗi: ' + error.message);
        console.error(error);
    }
}

// ==================== UI Update Functions ====================

/**
 * Cập nhật bảng khách sạn
 */
function updateHotelsTable(hotels) {
    const tbody = document.querySelector('table tbody');
    if (!tbody) return;
    
    // Clear existing rows
    tbody.innerHTML = '';
    
    if (!hotels || hotels.length === 0) {
        tbody.innerHTML = '<tr><td colspan="100%">Không tìm thấy khách sạn</td></tr>';
        return;
    }
    
    // Add rows
    hotels.forEach(hotel => {
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>${hotel.id}</td>
            <td>${hotel.hotelName}</td>
            <td>${hotel.address}</td>
            <td>${hotel.cityName || '-'}</td>
            <td>${hotel.rating || 0}</td>
            <td>
                <button class="btn btn-sm btn-info hotel-view-btn">Xem</button>
                <button class="btn btn-sm btn-warning hotel-edit-btn">Sửa</button>
                <button class="btn btn-sm btn-danger hotel-block-btn">Khóa</button>
            </td>
        `;
        
        tbody.appendChild(row);
    });
    
    // Re-attach event listeners
    attachTableEventListeners();
}

/**
 * Cập nhật phân trang
 */
function updatePagination(pagination) {
    const paginationContainer = document.querySelector('.pagination');
    if (!paginationContainer) return;
    
    paginationContainer.innerHTML = '';
    
    for (let i = 1; i <= pagination.pages; i++) {
        const btn = document.createElement('button');
        btn.className = 'btn btn-sm hotel-pagination-btn';
        btn.textContent = i;
        
        if (i === pagination.page) {
            btn.classList.add('active', 'bg-white');
        } else {
            btn.classList.add('btn-light');
        }
        
        paginationContainer.appendChild(btn);
    }
    
    // Re-attach listeners
    const paginationButtons = document.querySelectorAll('.hotel-pagination-btn');
    paginationButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.classList.contains('active')) return;
            const page = this.textContent.trim();
            if (page && !isNaN(page)) {
                loadHotels(null, null, page);
            }
        });
    });
}

/**
 * Hiển thị modal chi tiết khách sạn
 */
function showHotelDetailModal(data) {
    const hotel = data.hotel;
    
    const modalHtml = `
        <div class="modal fade" id="hotelDetailModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${hotel.hotelName}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Địa chỉ:</strong> ${hotel.address}</p>
                        <p><strong>Thành phố:</strong> ${hotel.cityName}</p>
                        <p><strong>Đánh giá:</strong> ${hotel.rating}/5</p>
                        <p><strong>Mô tả:</strong> ${hotel.description || '-'}</p>
                        <p><strong>Đối tác:</strong> ${hotel.partnerName || '-'}</p>
                        
                        <hr/>
                        <h6>Thống kê</h6>
                        <p>Tổng phòng: ${data.stats.totalRooms}</p>
                        <p>Tổng đánh giá: ${data.stats.totalReviews}</p>
                        <p>Đánh giá trung bình: ${data.stats.avgRating}/5</p>
                        <p>Tỷ lệ chiếm dụng: ${data.stats.avgOccupancy}%</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Create and show modal
    let modal = document.getElementById('hotelDetailModal');
    if (modal) modal.remove();
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    modal = new bootstrap.Modal(document.getElementById('hotelDetailModal'));
    modal.show();
}

/**
 * Hiển thị modal chỉnh sửa khách sạn
 */
function showHotelEditModal(hotel) {
    const modalHtml = `
        <div class="modal fade" id="hotelEditModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa: ${hotel.hotelName}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            <div class="mb-3">
                                <label class="form-label">Tên khách sạn</label>
                                <input type="text" class="form-control" name="hotelName" value="${hotel.hotelName}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" name="address" value="${hotel.address}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea class="form-control" name="description">${hotel.description || ''}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Đánh giá</label>
                                <input type="number" class="form-control" name="rating" step="0.1" value="${hotel.rating}">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-primary" onclick="saveHotelEdit(${hotel.id})">Lưu</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    let modal = document.getElementById('hotelEditModal');
    if (modal) modal.remove();
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    modal = new bootstrap.Modal(document.getElementById('hotelEditModal'));
    modal.show();
}

/**
 * Lưu chỉnh sửa khách sạn
 */
async function saveHotelEdit(hotelId) {
    try {
        const form = document.getElementById('editForm');
        const formData = new FormData(form);
        
        formData.append('action', 'updateHotel');
        formData.append('hotelId', hotelId);
        
        const response = await fetch(hotelsApiUrl(''), {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (!result.success) {
            showError('Lỗi cập nhật: ' + result.error);
            return;
        }
        
        showSuccess('Cập nhật thành công');
        bootstrap.Modal.getInstance(document.getElementById('hotelEditModal')).hide();
        loadHotels();
        
    } catch (error) {
        showError('Lỗi: ' + error.message);
        console.error(error);
    }
}

/**
 * Re-attach event listeners sau khi cập nhật bảng
 */
function attachTableEventListeners() {
    // View buttons
    document.querySelectorAll('.hotel-view-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const hotelId = this.closest('tr').querySelector('td:first-child').textContent.trim();
            openHotelDetail(hotelId);
        });
    });
    
    // Edit buttons
    document.querySelectorAll('.hotel-edit-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const hotelId = this.closest('tr').querySelector('td:first-child').textContent.trim();
            openHotelEdit(hotelId);
        });
    });
    
    // Block buttons
    document.querySelectorAll('.hotel-block-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const hotelId = this.closest('tr').querySelector('td:first-child').textContent.trim();
            if (confirm('Bạn chắc chắn muốn khóa khách sạn này?')) {
                blockHotel(hotelId);
            }
        });
    });
}

// ==================== Helper Functions ====================

/**
 * Hiển thị thông báo lỗi
 */
function showError(message) {
    const alertHtml = `
        <div class="alert alert-danger alert-dismissible fade show" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    setTimeout(() => {
        document.querySelector('.alert')?.remove();
    }, 5000);
}

/**
 * Hiển thị thông báo thành công
 */
function showSuccess(message) {
    const alertHtml = `
        <div class="alert alert-success alert-dismissible fade show" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    setTimeout(() => {
        document.querySelector('.alert')?.remove();
    }, 3000);
}
