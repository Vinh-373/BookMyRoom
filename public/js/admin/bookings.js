/**
 * Bookings Management Events Handler - Complete Implementation
 * Xử lý sự kiện cho trang Quản Lý Đặt Phòng
 */

// Add CSS animations for toast notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

/**
 * Base URL API (mặc định /BookMyRoom/api). Có thể ghi đè: window.BOOKMYROOM_API_BASE = '/ten-thu-muc/api'
 */
function getBookingsApiBaseUrl() {
    if (typeof window.BOOKMYROOM_API_BASE === 'string' && window.BOOKMYROOM_API_BASE.trim()) {
        return window.BOOKMYROOM_API_BASE.replace(/\/$/, '');
    }
    const el = document.querySelector('script[src*="bookings.js"]');
    if (el && el.src) {
        try {
            const u = new URL(el.src);
            const marker = '/public/js/admin/bookings.js';
            const i = u.pathname.indexOf(marker);
            if (i !== -1) {
                return u.origin + u.pathname.slice(0, i) + '/api';
            }
        } catch (err) { /* ignore */ }
    }
    return (window.location.origin || '') + '/BookMyRoom/api';
}

function bookingsApiUrl(queryString) {
    const base = getBookingsApiBaseUrl();
    const q = queryString && queryString.charAt(0) === '?' ? queryString : (queryString ? '?' + queryString : '');
    return base + '/bookings.php' + q;
}

/**
 * Nút Chi tiết: bảng có thể inject qua sidebar (partial). Chỉ đăng ký một lần (tránh tải script trùng).
 */
if (!window.__bookingsDetailDelegationBound) {
    window.__bookingsDetailDelegationBound = true;
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.booking-detail-btn');
        if (!btn) return;

        const bookingId = btn.getAttribute('data-booking-id');
        if (!bookingId) return;

        e.preventDefault();
        e.stopPropagation();
        console.log('👁 View booking detail ID:', bookingId);
        openBookingDetail(bookingId);
    });
}

/**
 * Tìm kiếm đơn: ô #search-booking có thể được inject sau DOMContentLoaded → dùng delegation.
 */
if (!window.__bookingsSearchDelegationBound) {
    window.__bookingsSearchDelegationBound = true;
    let bookingsSearchDebounceTimer = null;

    function isBookingsSearchInput(el) {
        return el && el.id === 'search-booking';
    }

    function bookingsSearchInScope(el) {
        const main = document.querySelector('.main-content');
        return main && el && main.contains(el);
    }

    document.addEventListener('focusin', function(e) {
        const t = e.target;
        if (!isBookingsSearchInput(t) || !bookingsSearchInScope(t)) return;
        const hints = [
            'Tên khách hàng: Khách hàng',
            'Email: customer1@',
            'Mã đơn: 1, 2, 3...',
            'Ngày: 2026-04 hoặc 04'
        ];
        console.log('💡 Search tip:', hints[Math.floor(Math.random() * hints.length)]);
    });

    document.addEventListener('input', function(e) {
        const t = e.target;
        if (!isBookingsSearchInput(t) || !bookingsSearchInScope(t)) return;

        clearTimeout(bookingsSearchDebounceTimer);
        const query = t.value.trim();

        if (query.length === 0) {
            loadBookings(1);
            return;
        }

        const tbody = document.getElementById('booking-table-body');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 20px;">⏳ Đang tìm kiếm...</td></tr>';
        }

        bookingsSearchDebounceTimer = setTimeout(function() {
            console.log('🔍 Searching bookings:', query);
            searchBookings(query);
        }, 300);
    });

    document.addEventListener('keydown', function(e) {
        if (e.key !== 'Enter') return;
        const t = e.target;
        if (!isBookingsSearchInput(t) || !bookingsSearchInScope(t)) return;
        e.preventDefault();
        clearTimeout(bookingsSearchDebounceTimer);
        const query = t.value.trim();
        if (query.length === 0) {
            loadBookings(1);
        } else {
            searchBookings(query);
        }
    });
}

document.addEventListener('adminPartialLoad', function(e) {
    if (e.detail && e.detail.page === 'bookings') {
        loadBookings(1);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    console.log('🎫 Bookings page loaded');

    // Chỉ tải API khi đang ở view đặt phòng (tránh gọi API khi mở dashboard / trang khác)
    if (document.getElementById('booking-table-body')) {
        loadBookings();
    }

    // ===== FILTER SELECTS (Auto-apply) =====
    const filterSelects = document.querySelectorAll('#filter-status, #filter-source');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            console.log('⚙️ Filter changed');
            applyFilters();
        });
    });

    // ===== DATE FILTERS =====
    const dateInputs = document.querySelectorAll('#filter-from, #filter-to');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            applyFilters();
        });
    });

    // ===== FILTER BUTTON =====
    const filterBtn = document.querySelector('#btn-filter');
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            console.log('🔍 Filter button clicked');
            applyFilters();
        });
    }

    // ===== RESET BUTTON =====
    const resetBtn = document.querySelector('#btn-reset');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            document.querySelector('#search-booking').value = '';
            document.querySelector('#filter-status').value = '';
            document.querySelector('#filter-source').value = '';
            document.querySelector('#filter-from').value = '';
            document.querySelector('#filter-to').value = '';
            loadBookings();
        });
    }

    // ===== PAGINATION =====
    const paginationBtns = document.querySelectorAll('.pagination-btn');
    paginationBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const page = parseInt(this.getAttribute('data-page'));
            console.log('📄 Page clicked:', page);
            loadBookings(page);
            
            // Update active button
            paginationBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

/**
 * Load bookings with pagination
 */
async function loadBookings(page = 1) {
    try {
        const response = await fetch(bookingsApiUrl(`action=getBookings&page=${page}&limit=10`));
        const result = await response.json();

        if (!result.success) {
            showError('Lỗi tải đơn đặt phòng: ' + result.error);
            return;
        }

        console.log('📦 Bookings loaded:', result.data.bookings.length);
        updateBookingsTable(result.data.bookings);
        updatePagination(result.data);
        const pagination = document.querySelector('.pagination');
        if (pagination) {
            pagination.style.display = '';
        }
    } catch (error) {
        console.error('Error loading bookings:', error);
        showError('Lỗi kết nối API');
    }
}

/**
 * Search bookings by query
 * Supports: Customer name, email, booking ID, phone, hotel name, room type, dates
 */
async function searchBookings(query) {
    try {
        const response = await fetch(bookingsApiUrl(`action=searchBookings&query=${encodeURIComponent(query)}`));
        const result = await response.json();

        if (!result.success) {
            showError('Lỗi tìm kiếm: ' + result.error);
            return;
        }

        const bookings = result.data.bookings || [];
        console.log('🔎 Search results:', bookings.length);
        
        if (bookings.length === 0) {
            showError('❌ Không tìm thấy đơn đặt phòng nào khớp với: "' + query + '"');
        } else {
            showSuccess('✅ Tìm thấy ' + bookings.length + ' đơn đặt phòng');
        }
        
        updateBookingsTable(bookings);

        const pagination = document.querySelector('.pagination');
        if (pagination) {
            pagination.style.display = 'none';
        }

    } catch (error) {
        console.error('Error searching bookings:', error);
        showError('❌ Lỗi kết nối API');
    }
}

/**
 * Apply filters
 */
async function applyFilters() {
    try {
        const status = document.querySelector('#filter-status')?.value || '';
        const source = document.querySelector('#filter-source')?.value || '';
        const fromDate = document.querySelector('#filter-from')?.value || '';
        const toDate = document.querySelector('#filter-to')?.value || '';

        const formData = new FormData();
        formData.append('action', 'filterBookings');
        formData.append('page', 1);
        formData.append('limit', 10);
        if (status) formData.append('status', status);
        if (source) formData.append('source', source);
        if (fromDate) formData.append('fromDate', fromDate);
        if (toDate) formData.append('toDate', toDate);

        console.log('Applying filters - Status:', status, 'Source:', source);

        const response = await fetch(bookingsApiUrl(''), {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (!result.success) {
            showError('Lỗi lọc: ' + result.error);
            return;
        }

        console.log('🎯 Filtered bookings:', result.data.bookings.length);
        updateBookingsTable(result.data.bookings);
        updatePagination(result.data);
        const pag = document.querySelector('.pagination');
        if (pag) pag.style.display = '';
        showSuccess('Cập nhật danh sách đơn đặt phòng thành công');
    } catch (error) {
        console.error('Error applying filters:', error);
        showError('Lỗi kết nối API');
    }
}

/**
 * Open booking detail
 */
async function openBookingDetail(bookingId) {
    try {
        const response = await fetch(bookingsApiUrl(`action=getBookingDetail&bookingId=${encodeURIComponent(bookingId)}`));
        const result = await response.json();

        if (!result.success) {
            showError('Lỗi lấy chi tiết: ' + result.error);
            return;
        }

        const raw = result.data;
        const booking = {
            id: raw.id,
            customerName: raw.customerName || 'N/A',
            customerEmail: raw.customerEmail ?? raw.email ?? '',
            customerPhone: raw.customerPhone ?? raw.phone ?? '',
            customerAddress: raw.customerAddress ?? raw.address ?? '',
            createdAt: raw.createdAt ?? raw.bookingDate ?? raw.created_at,
            totalAmount: raw.totalAmount,
            source: raw.source || 'N/A',
            status: raw.status || 'PENDING',
            details: raw.details || []
        };
        console.log('📋 Booking detail:', booking);

        const statusKey = String(booking.status || 'PENDING');
        const statusClass = statusKey.toLowerCase();
        const createdLabel = booking.createdAt
            ? new Date(booking.createdAt).toLocaleDateString('vi-VN')
            : 'N/A';

        const canApprove = statusKey === 'PENDING';
        const canCancelOrder = statusKey === 'PENDING' || statusKey === 'CONFIRMED';

        // Create and show modal (inline style vì chưa có .modal-overlay trong CSS)
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:10050;display:flex;align-items:center;justify-content:center;padding:16px;box-sizing:border-box;';
        modal.innerHTML = `
            <div class="modal-content" style="max-width:600px;width:100%;background:#fff;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,0.2);overflow:auto;max-height:90vh;">
                <div class="modal-header">
                    <h2>Chi tiết đơn đặt phòng #${booking.id}</h2>
                    <button type="button" class="modal-close" onclick="this.closest('.modal-overlay').remove()">&times;</button>
                </div>
                <div class="modal-body">
                    <div style="margin-bottom: 15px;">
                        <strong>Khách hàng:</strong> ${booking.customerName}<br>
                        <strong>Email:</strong> ${booking.customerEmail}<br>
                        <strong>Điện thoại:</strong> ${booking.customerPhone}<br>
                        <strong>Địa chỉ:</strong> ${booking.customerAddress || 'N/A'}
                    </div>

                    <div style="margin-bottom: 15px; padding: 10px; background: #f5f5f5; border-radius: 8px;">
                        <strong>Thông tin đặt phòng:</strong><br>
                        <table style="width: 100%; margin-top: 10px; font-size: 14px;">
                            <tr>
                                <td><strong>Ngày đặt:</strong></td>
                                <td>${createdLabel}</td>
                            </tr>
                            <tr>
                                <td><strong>Trạng thái:</strong></td>
                                <td><span class="status ${statusClass}">${getStatusLabel(statusKey)}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Nguồn đặt:</strong></td>
                                <td>${booking.source}</td>
                            </tr>
                            <tr>
                                <td><strong>Tổng tiền:</strong></td>
                                <td><strong>${number_format(booking.totalAmount)}đ</strong></td>
                            </tr>
                        </table>
                    </div>

                    ${booking.details && booking.details.length > 0 ? `
                        <div style="margin-bottom: 15px;">
                            <strong>Chi tiết phòng:</strong>
                            <table style="width: 100%; margin-top: 10px; border-collapse: collapse; font-size: 14px;">
                                <tr style="background: #f0f0f0;">
                                    <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Khách sạn</th>
                                    <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Phòng</th>
                                    <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Check-in</th>
                                    <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Check-out</th>
                                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">Giá</th>
                                </tr>
                                ${booking.details.map(d => `
                                    <tr>
                                        <td style="padding: 8px; border: 1px solid #ddd;">${d.hotelName}</td>
                                        <td style="padding: 8px; border: 1px solid #ddd;">${d.roomType} ${d.roomNumber ? '#' + d.roomNumber : ''}</td>
                                        <td style="padding: 8px; border: 1px solid #ddd;">${new Date(d.checkIn).toLocaleDateString('vi-VN')}</td>
                                        <td style="padding: 8px; border: 1px solid #ddd;">${new Date(d.checkOut).toLocaleDateString('vi-VN')}</td>
                                        <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">${number_format(d.amount)}đ</td>
                                    </tr>
                                `).join('')}
                            </table>
                        </div>
                    ` : ''}

                    <div style="margin-top: 18px; padding-top: 16px; border-top: 1px solid #e8e8e8;">
                        <strong style="display:block;margin-bottom:10px;">Xử lý đơn</strong>
                        <div class="booking-detail-actions" style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:16px;">
                            ${canApprove ? `
                                <button type="button" class="booking-action-approve btn-booking-approve" data-booking-id="${booking.id}"
                                    style="flex:1;min-width:120px;padding:10px 14px;background:#0d9488;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                                    Duyệt đơn
                                </button>
                            ` : ''}
                            ${canCancelOrder ? `
                                <button type="button" class="booking-action-cancel btn-booking-cancel" data-booking-id="${booking.id}"
                                    style="flex:1;min-width:120px;padding:10px 14px;background:#fff;color:#b91c1c;border:2px solid #fecaca;border-radius:8px;font-weight:600;cursor:pointer;">
                                    Hủy đơn
                                </button>
                            ` : ''}
                            ${!canApprove && !canCancelOrder ? `
                                <p style="margin:0;color:#64748b;font-size:14px;">Đơn đã hoàn thành hoặc đã hủy — không thể duyệt / hủy từ đây.</p>
                            ` : ''}
                        </div>
                        <strong style="display:block;margin-bottom:6px;font-size:13px;color:#64748b;">Thay đổi trạng thái khác</strong>
                        <select id="booking-status-select" class="booking-status-select" style="width: 100%; padding: 8px; margin-top: 4px; border-radius: 6px; border: 1px solid #cbd5e1;">
                            <option value="PENDING" ${statusKey === 'PENDING' ? 'selected' : ''}>Chờ xử lý</option>
                            <option value="CONFIRMED" ${statusKey === 'CONFIRMED' ? 'selected' : ''}>Đã xác nhận</option>
                            <option value="COMPLETED" ${statusKey === 'COMPLETED' ? 'selected' : ''}>Hoàn thành</option>
                            <option value="CANCELLED" ${statusKey === 'CANCELLED' ? 'selected' : ''}>Đã hủy</option>
                        </select>
                        <button type="button" class="booking-action-save-status btn-booking-save-status" data-booking-id="${booking.id}"
                            style="width: 100%; padding: 10px; margin-top: 10px; background: #2563eb; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                            Cập nhật trạng thái
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        const statusSelect = modal.querySelector('#booking-status-select');
        const approveBtn = modal.querySelector('.btn-booking-approve');
        const cancelBtn = modal.querySelector('.btn-booking-cancel');
        const saveStatusBtn = modal.querySelector('.btn-booking-save-status');

        if (approveBtn) {
            approveBtn.addEventListener('click', function() {
                const id = this.getAttribute('data-booking-id');
                updateBookingStatus(id, 'CONFIRMED');
            });
        }
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                const id = this.getAttribute('data-booking-id');
                if (!confirm('Bạn chắc chắn muốn hủy đơn đặt phòng này?')) return;
                updateBookingStatus(id, 'CANCELLED');
            });
        }
        if (saveStatusBtn && statusSelect) {
            saveStatusBtn.addEventListener('click', function() {
                const id = this.getAttribute('data-booking-id');
                updateBookingStatus(id, statusSelect.value);
            });
        }

        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.remove();
        });

    } catch (error) {
        console.error('Error opening booking detail:', error);
        showError('Lỗi tải chi tiết đơn đặt phòng');
    }
}

/**
 * Update booking status
 */
async function updateBookingStatus(bookingId, status) {
    try {
        const formData = new FormData();
        formData.append('action', 'updateBookingStatus');
        formData.append('bookingId', bookingId);
        formData.append('status', status);

        const response = await fetch(bookingsApiUrl(''), {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            showSuccess('Cập nhật trạng thái thành công');
            setTimeout(() => {
                document.querySelector('.modal-overlay')?.remove();
                loadBookings();
            }, 500);
        } else {
            showError('Lỗi cập nhật: ' + result.error);
        }
    } catch (error) {
        console.error('Error updating status:', error);
        showError('Lỗi kết nối API');
    }
}

window.updateBookingStatus = updateBookingStatus;
window.openBookingDetail = openBookingDetail;

/**
 * Update table with bookings data
 */
function updateBookingsTable(bookings) {
    const tbody = document.getElementById('booking-table-body');
    if (!tbody) return;

    if (bookings.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 20px;">Không tìm thấy đơn đặt phòng nào</td></tr>';
        return;
    }

    tbody.innerHTML = bookings.map(booking => {
        const email = booking.customerEmail ?? booking.email ?? '';
        const bookedAt = booking.createdAt ?? booking.bookingDate;
        const st = String(booking.status || 'PENDING');
        return `
        <tr data-booking-id="${booking.id}">
            <td>#${String(booking.id).padStart(4, '0')}</td>
            <td>
                <div>
                    <strong>${booking.customerName || 'N/A'}</strong><br>
                    <small>${email}</small>
                </div>
            </td>
            <td>${bookedAt ? new Date(bookedAt).toLocaleDateString('vi-VN') : 'N/A'}</td>
            <td>${booking.checkInDate ? new Date(booking.checkInDate).toLocaleDateString('vi-VN') : 'N/A'}</td>
            <td>${booking.checkOutDate ? new Date(booking.checkOutDate).toLocaleDateString('vi-VN') : 'N/A'}</td>
            <td>${number_format(booking.totalAmount || 0)}đ</td>
            <td>${booking.source || 'N/A'}</td>
            <td>
                <span class="status ${st.toLowerCase()}">
                    ${getStatusLabel(booking.status)}
                </span>
            </td>
            <td>
                <button class="btn-action booking-detail-btn" data-booking-id="${booking.id}">Chi tiết</button>
            </td>
        </tr>
    `;
    }).join('');
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
        html += `<button class="pagination-btn ${i === (data.page || 1) ? 'active' : ''}" data-page="${i}">${i}</button>`;
    }

    paginationContainer.innerHTML = html;

    // Re-attach pagination listeners
    paginationContainer.querySelectorAll('.pagination-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const page = parseInt(this.getAttribute('data-page'));
            loadBookings(page);
            
            paginationContainer.querySelectorAll('.pagination-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

/**
 * Get status label in Vietnamese
 */
function getStatusLabel(status) {
    const labels = {
        'PENDING': 'Chờ xử lý',
        'CONFIRMED': 'Đã xác nhận',
        'COMPLETED': 'Hoàn thành',
        'CANCELLED': 'Đã hủy'
    };
    return labels[status] || status;
}

/**
 * Helper function to format numbers
 */
function number_format(num) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(num));
}

/**
 * Show success notification
 */
function showSuccess(message) {
    console.log('✅', message);
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #4caf50;
        color: white;
        padding: 12px 20px;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        z-index: 9999;
        max-width: 400px;
        animation: slideIn 0.3s ease;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * Show error notification
 */
function showError(message) {
    console.error('❌', message);
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #f44336;
        color: white;
        padding: 12px 20px;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        z-index: 9999;
        max-width: 400px;
        animation: slideIn 0.3s ease;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}
