<div class="transaction-wrapper">
    <header class="page-header">
        <h1>Lịch sử giao dịch</h1>
        <div class="header-tools">
            <form action="" method="GET" class="filter-flex">
                <select name="status" onchange="this.form.submit()">
                    <option value="all">Tất cả trạng thái</option>
                    <option value="PAID" <?= ($filters['status'] == 'PAID') ? 'selected' : '' ?>>Đã thanh toán</option>
                    <option value="PENDING" <?= ($filters['status'] == 'PENDING') ? 'selected' : '' ?>>Chờ xử lý</option>
                    <option value="REFUNDED" <?= ($filters['status'] == 'REFUNDED') ? 'selected' : '' ?>>Hoàn tiền</option>
                </select>
                
                <select name="method" onchange="this.form.submit()">
                    <option value="all">Tất cả phương thức</option>
                    <option value="MOMO" <?= ($filters['method'] == 'MOMO') ? 'selected' : '' ?>>Momo</option>
                    <option value="VNPAY" <?= ($filters['method'] == 'VNPAY') ? 'selected' : '' ?>>VNPay</option>
                    <option value="CASH" <?= ($filters['method'] == 'CASH') ? 'selected' : '' ?>>Tiền mặt</option>
                </select>
            </form>
        </div>
    </header>

    <table class="transaction-table">
        <thead>
            <tr>
                <th>MÃ GD</th>
                <th>NGÀY GIỜ</th>
                <th>KHÁCH HÀNG</th>
                <th>PHƯƠNG THỨC</th>
                <th>SỐ TIỀN</th>
                <th>TRẠNG THÁI</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($transactions as $t): ?>
            <tr>
                <td><strong>#TRX-<?= $t['id'] ?></strong></td>
                <td><?= date('d/m/Y H:i', strtotime($t['createdAt'])) ?></td>
                <td><?= $t['guestName'] ?></td>
                <td><span class="method-tag"><?= $t['paymentMethod'] ?></span></td>
                <td><strong><?= number_format($t['amount'], 0, ',', '.') ?>đ</strong></td>
                <td>
                    <span class="badge <?= strtolower($t['paymentStatus']) ?>">
                        <?= $t['paymentStatus'] ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div class="pagination-area">
            <span class="showing">
                Hiển thị <strong><?= $showingStart ?></strong> - <strong><?= $showingEnd ?></strong> 
                trong tổng số <strong><?= number_format($totalCount) ?></strong> giao dịch
            </span>
            
            <div class="pages">
                <?php 
                // Giữ lại các filter status và method khi chuyển trang
                $pageParams = $_GET; 
                ?>
                
                <?php if($currentPage > 1): 
                    $pageParams['page'] = $currentPage - 1;
                ?>
                    <a href="?<?= http_build_query($pageParams) ?>" class="page-nav">‹</a>
                <?php endif; ?>

                <?php for($i = 1; $i <= $totalPages; $i++): 
                    $pageParams['page'] = $i;
                ?>
                    <a href="?<?= http_build_query($pageParams) ?>" 
                       class="page-num <?= ($currentPage == $i) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if($currentPage < $totalPages): 
                    $pageParams['page'] = $currentPage + 1;
                ?>
                    <a href="?<?= http_build_query($pageParams) ?>" class="page-nav">›</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>