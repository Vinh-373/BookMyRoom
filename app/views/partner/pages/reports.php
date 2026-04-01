<div class="financial-wrapper">
    <header class="reports-header">
        <div class="header-left">
            <h1>Tổng quan Tài chính</h1>
            <p>Chỉ số hiệu suất tài chính thời gian thực cho <?= $_SESSION['hotel_name'] ?? 'Khách sạn của bạn' ?></p>
        </div>
        <div class="header-right">
            <form action="" method="GET" style="display: flex; gap: 12px;">
                <select name="period" class="filter-period" onchange="this.form.submit()">
                    <option value="this_month" <?= ($activePeriod == 'this_month') ? 'selected' : '' ?>>Tháng này</option>
                    <option value="last_month" <?= ($activePeriod == 'last_month') ? 'selected' : '' ?>>Tháng trước</option>
                    <option value="this_year" <?= ($activePeriod == 'this_year') ? 'selected' : '' ?>>Năm nay</option>
                </select>
                <button type="button" class="btn btn-primary-blue btn-export" onclick="exportFinancialReport()">
                    📥 Xuất PDF/Excel
                </button>
            </form>
        </div>
    </header>

    <div class="financial-cards">
        <div class="f-card">
            <div class="f-card__top">
                <div class="f-icon blue">💵</div>
                <span class="f-trend positive">+12.5%</span>
            </div>
            <span class="f-label">TỔNG DOANH THU (GROSS)</span>
            <h2 class="f-value"><?= number_format($stats['total_revenue'] ?? 0, 0, ',', '.') ?>đ</h2>
        </div>

        <div class="f-card">
            <div class="f-card__top">
                <div class="f-icon orange">🗂️</div>
                <span class="f-trend">Cố định 10%</span>
            </div>
            <span class="f-label">HOA HỒNG HỆ THỐNG</span>
            <h2 class="f-value"><?= number_format($stats['commission'] ?? 0, 0, ',', '.') ?>đ</h2>
        </div>

        <div class="f-card">
            <div class="f-card__top">
                <div class="f-icon green">✅</div>
                <span class="f-status ready">Sẵn sàng</span>
            </div>
            <span class="f-label">THỰC NHẬN (NET PAYOUT)</span>
            <h2 class="f-value text-green"><?= number_format($stats['net_payout'] ?? 0, 0, ',', '.') ?>đ</h2>
        </div>
    </div>

    <div class="reports-grid">
        <div class="chart-container">
            <div class="chart-header">
                <div>
                    <h3>Phân bổ doanh thu</h3>
                    <p>Doanh thu theo từng hạng phòng trong giai đoạn này</p>
                </div>
                <button class="btn-more">⋮</button>
            </div>
            
            <div class="bar-chart-wrapper">
                <?php if(!empty($chartData)): ?>
                    <?php foreach($chartData as $item): 
                        $height = ($item['amount'] / $stats['max_revenue']) * 100;
                    ?>
                    <div class="bar-item" title="<?= number_format($item['amount'], 0, ',', '.') ?>đ">
                        <div class="bar" style="height: <?= $height ?>%"></div>
                        <span><?= strtoupper($item['room_type']) ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #667085; font-size: 0.9rem;">Không có dữ liệu biểu đồ.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="payouts-container">
            <div class="chart-header">
                <div>
                    <h3>Thanh toán gần đây</h3>
                    <p>Các giao dịch chuyển khoản mới nhất</p>
                </div>
            </div>
            
            <table class="payout-table">
                <thead>
                    <tr>
                        <th>GIAO DỊCH</th>
                        <th>SỐ TIỀN</th>
                        <th>TRẠNG THÁI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($payouts)): ?>
                        <?php foreach($payouts as $p): ?>
                        <tr>
                            <td>
                                <strong>#<?= $p['transaction_id'] ?></strong><br>
                                <small><?= date('d/m/Y', strtotime($p['created_at'])) ?></small>
                            </td>
                            <td><strong><?= number_format($p['amount'], 0, ',', '.') ?>đ</strong></td>
                            <td>
                                <span class="badge <?= strtolower($p['status']) ?>">
                                    <?php 
                                        switch(strtoupper($p['status'])) {
                                            case 'PAID': echo 'ĐÃ CHI'; break;
                                            case 'PENDING': echo 'ĐANG XỬ LÝ'; break;
                                            case 'FAILED': echo 'THẤT BẠI'; break;
                                            default: echo strtoupper($p['status']);
                                        }
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" style="text-align:center; padding: 20px; color: #999;">Chưa có giao dịch nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <a href="<?= URLROOT ?>/transactions" class="view-all-link">XEM TẤT CẢ GIAO DỊCH →</a>
        </div>
    </div>
</div>

<script>
// Hiệu ứng biểu đồ khi load trang
document.addEventListener('DOMContentLoaded', () => {
    const bars = document.querySelectorAll('.bar');
    bars.forEach(bar => {
        const finalHeight = bar.style.height;
        bar.style.height = '0';
        setTimeout(() => {
            bar.style.transition = 'height 1s ease-out';
            bar.style.height = finalHeight;
        }, 300);
    });
});

function exportFinancialReport() {
    // Lấy giá trị period hiện tại từ thẻ select
    const period = document.querySelector('.filter-period').value;
    let periodText = '';
    
    switch(period) {
        case 'this_month': periodText = 'Tháng này'; break;
        case 'last_month': periodText = 'Tháng trước'; break;
        case 'this_year': periodText = 'Năm nay'; break;
        default: periodText = period;
    }

    Swal.fire({
        title: 'Xác nhận xuất báo cáo',
        text: `Bạn muốn tải báo cáo tài chính của "${periodText}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '📊 Tải Excel (CSV)',
        confirmButtonColor: '#12B76A',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            // Chuyển hướng kèm tham số period
            window.location.href = `<?= URLROOT ?>/partner/exportFinanceCSV?period=${period}`;
        }
    });
}
</script>