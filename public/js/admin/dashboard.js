/**
 * Dashboard Events Handler
 * Xử lý sự kiện cho trang Dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard JS Loaded');

    // ==================== Xử lý nút Time Period ====================
    const timePeriodButtons = document.querySelectorAll('.dashboard-time-period button');
    
    timePeriodButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Loại bỏ active class từ tất cả button
            timePeriodButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-surface-container-low', 'text-primary');
                btn.classList.add('text-slate-400', 'hover:bg-surface-container-low');
            });

            // Thêm active class vào button được click
            this.classList.add('active', 'bg-surface-container-low', 'text-primary');
            this.classList.remove('text-slate-400', 'hover:bg-surface-container-low');

            // Lấy data từ button
            const period = this.textContent.trim();
            console.log('Time period changed to:', period);

            // TODO: Gọi API để lấy dữ liệu theo period
            // loadDashboardData(period);
        });
    });

    // ==================== Xử lý Chart Legend Click ====================
    const chartLegends = document.querySelectorAll('.chart-legend button');
    
    chartLegends.forEach(legend => {
        legend.addEventListener('click', function() {
            const metric = this.textContent.trim();
            console.log('Chart legend clicked:', metric);
            // TODO: Cập nhật biểu đồ khi click legend
        });
    });

    // ==================== Xử lý Activity Timeline ====================
    const activityItems = document.querySelectorAll('.activity-item');
    
    activityItems.forEach((item, index) => {
        item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(0, 89, 187, 0.05)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'transparent';
        });
    });

    // ==================== Xử lý Recent Booking Table ====================
    const bookingRows = document.querySelectorAll('table tbody tr');
    
    bookingRows.forEach(row => {
        row.addEventListener('click', function() {
            const bookingId = this.querySelector('td:first-child').textContent;
            console.log('Booking clicked:', bookingId);
            // TODO: Mở chi tiết booking
        });
    });

    // ==================== Auto Refresh Data ====================
    // Refresh dữ liệu mỗi 5 phút
    setInterval(function() {
        console.log('Auto refreshing dashboard data...');
        // TODO: loadDashboardData();
    }, 300000); // 5 phút

});

// ==================== Helper Functions ====================

/**
 * Format tiền tệ
 */
function formatCurrency(value) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(value);
}

/**
 * Format ngày tháng
 */
function formatDate(date) {
    return new Intl.DateTimeFormat('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    }).format(new Date(date));
}
