<div class="voucher-wrapper">
    <header class="page-header">
        <div class="header-title">
            <h1>Quản lý Khuyến mãi</h1>
            <p>Tạo và quản lý các mã giảm giá cho khách hàng</p>
        </div>
        
        <div class="header-actions">
            <form action="" method="GET" class="filter-form">
                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="all" <?= ($filters['status'] == 'all') ? 'selected' : '' ?>>Tất cả trạng thái</option>
                    <option value="active" <?= ($filters['status'] == 'active') ? 'selected' : '' ?>>Đang hoạt động</option>
                    <option value="upcoming" <?= ($filters['status'] == 'upcoming') ? 'selected' : '' ?>>Sắp diễn ra</option>
                    <option value="expired" <?= ($filters['status'] == 'expired') ? 'selected' : '' ?>>Đã kết thúc</option>
                    <option value="out_of_stock" <?= ($filters['status'] == 'out_of_stock') ? 'selected' : '' ?>>Hết lượt sử dụng</option>
                </select>
                <?php if(!empty($_GET['search'])): ?>
                    <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
                <?php endif; ?>
            </form>
            <button class="btn-primary" onclick="openVoucherModal()">+ Tạo Voucher mới</button>
        </div>
    </header>

    <div class="voucher-grid">
        <?php if(!empty($vouchers)): ?>
            <?php foreach($vouchers as $v): ?>
            <div class="voucher-card <?= $v['status_class'] ?>">
                <div class="v-badge <?= $v['status_class'] ?>"><?= $v['status_text'] ?></div>
                
                <div class="v-content">
                    <div class="v-info">
                        <span class="v-code"><?= $v['code'] ?></span>
                        <h3 class="v-amount">
                            <?= $v['type'] === 'PERCENT' ? $v['amount'].'%' : number_format($v['amount']).'đ' ?>
                        </h3>
                    </div>
                    <div class="v-details">
                        <p><strong>Số lượng:</strong> <?= $v['quantity'] ?></p>
                        <p><strong>Tối thiểu:</strong> <?= number_format($v['condition']) ?>đ</p>
                        <p><strong>Hạn dùng:</strong> <?= date('d/m/Y', strtotime($v['startDate'])) ?> - <?= date('d/m/Y', strtotime($v['endDate'])) ?></p>
                    </div>
                </div>

                <div class="v-actions">
                    <?php if($v['can_edit_or_delete']): ?>
                        <button class="btn-icon edit" onclick="editVoucher(<?= htmlspecialchars(json_encode($v)) ?>)" title="Chỉnh sửa">✏️</button>
                        <button class="btn-icon delete" onclick="confirmDelete(<?= $v['id'] ?>)" title="Xóa">🗑️</button>
                    <?php else: ?>
                        <span class="lock-icon" title="Không thể sửa/xóa khi đang chạy hoặc đã kết thúc">🔒</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">Chưa có mã khuyến mãi nào được tạo.</div>
        <?php endif; ?>
    </div>
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php 
            $queryString = $_GET; 
            ?>
            
            <?php if ($currentPage > 1): ?>
                <?php $queryString['page'] = $currentPage - 1; ?>
                <a href="?<?= http_build_query($queryString) ?>" class="page-link">&laquo;</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php $queryString['page'] = $i; ?>
                <a href="?<?= http_build_query($queryString) ?>" 
                   class="page-link <?= ($i == $currentPage) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <?php $queryString['page'] = $currentPage + 1; ?>
                <a href="?<?= http_build_query($queryString) ?>" class="page-link">&raquo;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div id="voucherModal" class="custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Tạo Voucher mới</h3>
                <span class="close-modal" onclick="closeVoucherModal()">&times;</span>
            </div>
            <form id="voucherForm" action="<?= URLROOT ?>/partner/saveVoucher" method="POST">
                <input type="hidden" name="id" id="voucherId">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Mã Voucher</label>
                        <input type="text" name="code" id="vCode" placeholder="Ví dụ: SUMMER2026" required>
                    </div>
                    <div class="form-group">
                        <label>Số lượng</label>
                        <input type="number" name="quantity" id="vQty" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Loại giảm giá</label>
                        <select name="type" id="vType">
                            <option value="PERCENT">Phần trăm (%)</option>
                            <option value="FIXED">Số tiền cố định (đ)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mức giảm</label>
                        <input type="number" name="amount" id="vAmount" required>
                    </div>
                    <div class="form-group">
                        <label>Đơn tối thiểu</label>
                        <input type="number" name="condition" id="vCond" value="0">
                    </div>
                    <div class="form-group">
                        <label>Ngày bắt đầu</label>
                        <input type="date" name="startDate" id="vStart" required>
                    </div>
                    <div class="form-group">
                        <label>Ngày kết thúc</label>
                        <input type="date" name="endDate" id="vEnd" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeVoucherModal()">Hủy</button>
                    <button type="submit" class="btn-primary">Lưu Voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

function setupDateConstraints() {
    const vStart = document.getElementById('vStart');
    const vEnd = document.getElementById('vEnd');
    
    // 1. Ngày bắt đầu min = Ngày mai (Hôm nay + 1)
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = tomorrow.toISOString().split('T')[0];
    
    vStart.setAttribute('min', tomorrowStr);

    // 2. Khi ngày bắt đầu thay đổi, cập nhật min cho ngày kết thúc
    vStart.addEventListener('change', function() {
        if (this.value) {
            const startDate = new Date(this.value);
            startDate.setDate(startDate.getDate() + 1);
            const minEndDate = startDate.toISOString().split('T')[0];
            
            vEnd.setAttribute('min', minEndDate);
            
            // Nếu ngày kết thúc hiện tại nhỏ hơn ngày min mới, hãy reset nó
            if (vEnd.value && vEnd.value < minEndDate) {
                vEnd.value = minEndDate;
            }
        }
    });
}


function openVoucherModal() {
    document.getElementById('voucherForm').reset();
    document.getElementById('voucherId').value = '';
    document.getElementById('modalTitle').innerText = 'Tạo Voucher mới';
    
    setupDateConstraints(); // Thiết lập ràng buộc ngày
    document.getElementById('voucherModal').style.display = 'flex';
}

function closeVoucherModal() {
    document.getElementById('voucherModal').style.display = 'none';
}

function editVoucher(data) {
    document.getElementById('modalTitle').innerText = 'Sửa Voucher: ' + data.code;
    document.getElementById('voucherId').value = data.id;
    document.getElementById('vCode').value = data.code;
    document.getElementById('vQty').value = data.quantity;
    document.getElementById('vType').value = data.type;
    document.getElementById('vAmount').value = data.amount;
    document.getElementById('vCond').value = data.condition;
    document.getElementById('vStart').value = data.startDate;
    document.getElementById('vEnd').value = data.endDate;
    document.getElementById('voucherModal').style.display = 'flex';
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Xác nhận xóa?',
        text: "Hành động này không thể hoàn tác!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Xóa ngay',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `<?= URLROOT ?>/partner/deleteVoucher/${id}`;
        }
    })
}
</script>