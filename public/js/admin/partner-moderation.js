/**
 * Partner Moderation Events Handler
 * Xử lý sự kiện cho trang Phê Duyệt Đối Tác
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Partner Moderation JS Loaded');

    // ==================== Xử lý Search Input ====================
    const searchInput = document.querySelector('.moderation-search-input');
    
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value;
            
            searchTimeout = setTimeout(function() {
                console.log('Search moderation requests:', query);
                // TODO: loadModerationRequests(query)
            }, 300);
        });
    }

    // ==================== Xử lý Filter Selects ====================
    const filterSelects = document.querySelectorAll('.moderation-filter-select');
    
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            const filterType = this.getAttribute('data-filter');
            const value = this.value;
            
            if (value) {
                console.log(`Filter ${filterType}:`, value);
                // TODO: applyModerationFilter(filterType, value)
            }
        });
    });

    // ==================== Xử lý Filter Button ====================
    const filterBtn = document.querySelector('.moderation-filter-btn');
    
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            console.log('Apply filters clicked');
            
            const filters = {};
            filterSelects.forEach(select => {
                const filterType = select.getAttribute('data-filter');
                const value = select.value;
                if (value) {
                    filters[filterType] = value;
                }
            });
            
            console.log('Applied filters:', filters);
            // TODO: loadModerationRequests(null, filters)
        });
    }

    // ==================== Xử lý Table Row Actions ====================
    
    // View/Xem Hồ Sơ Button
    const viewButtons = document.querySelectorAll('.moderation-view-btn');
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const row = this.closest('tr');
            const hotelId = row.querySelector('td:first-child').textContent;
            console.log('View application:', hotelId);
            openApplicationDetail(hotelId);
        });
    });

    // Approve/Phê Duyệt Button
    const approveButtons = document.querySelectorAll('.moderation-approve-btn');
    approveButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const row = this.closest('tr');
            const hotelId = row.querySelector('td:first-child').textContent;
            const hotelName = row.querySelector('td:nth-child(1)').textContent;
            
            if (confirm(`Phê duyệt đối tác: ${hotelName}?`)) {
                console.log('Approve application:', hotelId);
                approveApplication(hotelId);
            }
        });
    });

    // Reject/Từ Chối Button
    const rejectButtons = document.querySelectorAll('.moderation-reject-btn');
    rejectButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const row = this.closest('tr');
            const hotelId = row.querySelector('td:first-child').textContent;
            const hotelName = row.querySelector('td:nth-child(1)').textContent;
            
            // Mở modal nhập lý do từ chối
            openRejectReasonModal(hotelId, hotelName);
        });
    });

    // ==================== Xử lý Row Hover ====================
    const tableRows = document.querySelectorAll('table tbody tr');
    
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            const actionBtns = this.querySelectorAll('.moderation-action-btn');
            actionBtns.forEach(btn => {
                btn.style.opacity = '1';
                btn.style.pointerEvents = 'auto';
            });
        });
        
        row.addEventListener('mouseleave', function() {
            const actionBtns = this.querySelectorAll('.moderation-action-btn');
            actionBtns.forEach(btn => {
                btn.style.opacity = '0';
                btn.style.pointerEvents = 'none';
            });
        });
    });

    // ==================== Xử lý History Button ====================
    const historyBtn = document.querySelector('.moderation-history-btn');
    
    if (historyBtn) {
        historyBtn.addEventListener('click', function() {
            console.log('Show approval history');
            // TODO: Mở modal lịch sử phê duyệt
            openHistoryModal();
        });
    }

    // ==================== Xử lý Export Report Button ====================
    const exportBtn = document.querySelector('.moderation-export-btn');
    
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            console.log('Export report clicked');
            // TODO: Generate và download report
            exportModerationReport();
        });
    }

});

// ==================== Modal & Helper Functions ====================

/**
 * Mở chi tiết hồ sơ ứng dụng
 */
function openApplicationDetail(hotelId) {
    console.log('Opening application detail:', hotelId);
    // TODO: Fetch application data và hiển thị modal
}

/**
 * Phê duyệt ứng dụng
 */
function approveApplication(hotelId) {
    console.log('Approving application:', hotelId);
    // TODO: Gọi API
    // Sau khi thành công:
    // - Cập nhật status
    // - Hiển thị notification
    // - Refresh list
}

/**
 * Mở modal nhập lý do từ chối
 */
function openRejectReasonModal(hotelId, hotelName) {
    console.log('Opening reject reason modal for:', hotelId);
    // TODO: Hiển thị modal với textarea nhập lý do
    // Khi submit:
    // - rejectApplication(hotelId, reason)
}

/**
 * Từ chối ứng dụng
 */
function rejectApplication(hotelId, reason) {
    console.log('Rejecting application:', hotelId, 'Reason:', reason);
    // TODO: Gọi API
}

/**
 * Mở modal lịch sử phê duyệt
 */
function openHistoryModal() {
    console.log('Opening approval history');
    // TODO: Fetch history data
}

/**
 * Export báo cáo
 */
function exportModerationReport() {
    console.log('Exporting moderation report');
    // TODO: Generate report (PDF/Excel)
}
