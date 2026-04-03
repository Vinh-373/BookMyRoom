/**
 * Payments Events Handler
 * Xử lý sự kiện cho trang Quản Lý Thanh Toán
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Payments JS Loaded');

    // ==================== Xử lý Filter Controls ====================
    
    const searchInput = document.querySelector('.payments-search-input');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value;
            
            searchTimeout = setTimeout(function() {
                console.log('Search payments:', query);
                // TODO: loadPayments(query)
            }, 300);
        });
    }

    // Status filter
    const statusSelects = document.querySelectorAll('.payments-filter-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const filterType = this.getAttribute('data-filter');
            const value = this.value;
            
            console.log(`Filter ${filterType}:`, value);
            // TODO: applyPaymentFilter(filterType, value)
        });
    });

    // ==================== Xử lý Table Actions ====================
    
    // View Details Button
    const viewButtons = document.querySelectorAll('.payment-view-btn');
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const paymentId = this.closest('tr').querySelector('td:first-child').textContent;
            console.log('View payment:', paymentId);
            openPaymentDetail(paymentId);
        });
    });

    // Download Invoice Button
    const downloadButtons = document.querySelectorAll('.payment-download-btn');
    downloadButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const paymentId = this.closest('tr').querySelector('td:first-child').textContent;
            console.log('Download invoice:', paymentId);
            downloadInvoice(paymentId);
        });
    });

    // Refund Button
    const refundButtons = document.querySelectorAll('.payment-refund-btn');
    refundButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const paymentId = this.closest('tr').querySelector('td:first-child').textContent;
            const amount = this.closest('tr').querySelector('td:nth-child(3)').textContent;
            
            showConfirmDialog(
                'Hoàn tiền',
                `Bạn chắc chắn muốn hoàn tiền ${amount}?`,
                () => processRefund(paymentId)
            );
        });
    });

    // ==================== Xử lý Row Hover ====================
    const tableRows = document.querySelectorAll('table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            const actionBtns = this.querySelectorAll('.payment-action-btn');
            actionBtns.forEach(btn => {
                btn.style.opacity = '1';
                btn.style.pointerEvents = 'auto';
            });
        });
        
        row.addEventListener('mouseleave', function() {
            const actionBtns = this.querySelectorAll('.payment-action-btn');
            actionBtns.forEach(btn => {
                btn.style.opacity = '0';
                btn.style.pointerEvents = 'none';
            });
        });
    });

    // ==================== Xử lý Reconciliation ====================
    
    const reconcileBtn = document.querySelector('.payments-reconcile-btn');
    if (reconcileBtn) {
        reconcileBtn.addEventListener('click', function() {
            console.log('Reconcile payments');
            // TODO: Mở modal reconciliation
            openReconcileModal();
        });
    }

    // ==================== Xử lý Export Report ====================
    
    const exportBtn = document.querySelector('.payments-export-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            console.log('Export payment report');
            exportPaymentReport();
        });
    }

});

// ==================== Helper Functions ====================

function openPaymentDetail(paymentId) {
    console.log('Opening payment detail:', paymentId);
    // TODO: Fetch payment data
}

function downloadInvoice(paymentId) {
    console.log('Downloading invoice:', paymentId);
    // TODO: Generate PDF invoice
}

function processRefund(paymentId) {
    console.log('Processing refund:', paymentId);
    // TODO: Gọi API
}

function openReconcileModal() {
    console.log('Opening reconcile modal');
    // TODO: Hiển thị modal
}

function exportPaymentReport() {
    console.log('Exporting payment report');
    // TODO: Generate report
}
