<div class="staff-wrapper">
    <div class="staff-header">
        <div class="header-left">
            <h1>Staff at <?= $_SESSION['hotel_name'] ?? 'Property' ?></h1>
            <p>Manage personnel roles, shifts, and property access permissions.</p>
        </div>
        <div class="header-right">
            <div class="search-staff">
                <input type="text" id="staffSearch" placeholder="Filter staff..." onkeyup="filterStaff()">
            </div>
            <button class="btn btn-add" onclick="openStaffModal()">👤 Add New Staff</button>
        </div>
    </div>

    <div class="staff-list" id="staffList">
        <?php if (!empty($staffList)): ?>
            <?php foreach ($staffList as $s): 
                $roleClass = ($s['role'] === 'Partner' || $s['role'] === 'Admin') ? 'manager' : 'receptionist';
                $isOnline = ($s['status'] === 'ACTIVE');
            ?>
            <div class="staff-card" data-name="<?= strtolower($s['fullName']) ?>">
                <div class="staff-info">
                    <div class="staff-avatar">
                        <img src="<?= URLROOT ?>/public/images/avatars/<?= $s['avatarUrl'] ?? 'default.png' ?>" alt="Avatar">
                        <span class="status-dot <?= $isOnline ? 'online' : 'offline' ?>"></span>
                    </div>
                    <div class="staff-meta">
                        <div class="name-row">
                            <strong><?= $s['fullName'] ?></strong>
                            <span class="role-badge <?= $roleClass ?>"><?= strtoupper($s['role']) ?></span>
                        </div>
                        <p><?= $s['email'] ?></p>
                    </div>
                </div>

                <div class="staff-status">
                    <span class="label">SHIFT STATUS</span>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="status-text <?= $isOnline ? 'online' : 'offline' ?>">
                            ● <?= $isOnline ? 'On Shift' : 'Off Duty' ?>
                        </span>
                        <button class="btn-status-toggle" 
                                onclick="handleToggleStatus(<?= $s['id'] ?>, '<?= $s['status'] ?>')" 
                                title="<?= $isOnline ? 'Lock Account/Off Duty' : 'Unlock Account/On Shift' ?>">
                            <?= $isOnline ? '⏸️' : '▶️' ?>
                        </button>
                    </div>
                </div>

                <div class="staff-perms">
                    <span class="label">PERMISSIONS</span>
                    <div class="perm-icons">
                        <span title="View Access">👁️</span> 
                        <span title="Edit Access">📝</span> 
                        <?php if($roleClass === 'manager'): ?>
                            <span title="Admin/Shield Access">🛡️</span>
                        <?php endif; ?>
                    </div>
                </div>

                <button class="btn-reset-pw" title="Reset Password" onclick="handleResetPassword(<?= $s['id'] ?>, '<?= $s['fullName'] ?>')">
                    🔑
                </button>
                <div class="staff-actions">
                    <button class="btn-outline" onclick="openEditRoleModal(<?= $s['id'] ?>)">Change Role</button>
                    <button class="btn-text-danger" onclick="confirmRemove(<?= $s['id'] ?>)">Remove from Property</button>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #667085;">
                Chưa có nhân viên nào được đăng ký cho khách sạn này.
            </div>
        <?php endif; ?>
    </div>

    <div class="staff-stats-grid">
        <div class="stat-item">
            <span class="s-label">Total Staff</span>
            <span class="s-value">
                <?= sprintf("%02d", $stats['total'] ?? 0) ?>
            </span>
        </div>

        <div class="stat-item">
            <span class="s-label">Active Shifts</span>
            <span class="s-value color-green">
                <?= sprintf("%02d", $stats['active'] ?? 0) ?>
            </span>
        </div>

        <div class="stat-item">
            <span class="s-label">Managers</span>
            <span class="s-value color-blue">
                <?= sprintf("%02d", $stats['managers'] ?? 0) ?>
            </span>
        </div>

        <div class="stat-item">
            <span class="s-label">Locked Accounts</span>
            <span class="s-value color-orange">
                <?= sprintf("%02d", $stats['blocked'] ?? 0) ?>
            </span>
        </div>
    </div>

    <div id="addStaffModal" class="custom-modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tạo tài khoản nhân viên</h3>
                <button class="close-modal" onclick="closeStaffModal()">✕</button>
            </div>
            <form action="<?= URLROOT ?>/partner/createStaff" method="POST">
                <div class="modal-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div style="grid-column: span 2;">
                            <label>Họ và tên</label>
                            <input type="text" name="fullName" class="form-control" placeholder="Nhập tên nhân viên" required>
                        </div>
                        <div>
                            <label>Email đăng nhập</label>
                            <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
                        </div>
                        <div>
                            <label>Mật khẩu tạm thời</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <div>
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" placeholder="090...">
                        </div>
                        <div>
                            <label>Quyền hạn</label>
                            <select name="role" class="form-control">
                                <option value="Staff">Staff (Receptionist)</option>
                                <option value="Partner">Manager (Supervisor)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeStaffModal()">Hủy</button>
                    <button type="submit" class="btn-primary">Tạo tài khoản</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStaffModal() { 
    document.getElementById('addStaffModal').style.display = 'flex'; 
}

function closeStaffModal() { 
    document.getElementById('addStaffModal').style.display = 'none'; 
}

function filterStaff() {
    let input = document.getElementById('staffSearch').value.toLowerCase();
    let cards = document.querySelectorAll('.staff-card');
    cards.forEach(card => {
        let name = card.getAttribute('data-name');
        card.style.display = name.includes(input) ? "flex" : "none";
    });
}

function confirmRemove(staffId) {
    Swal.fire({
        title: 'Xóa nhân viên?',
        text: "Nhân viên này sẽ không thể truy cập vào hệ thống khách sạn nữa!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#667085',
        confirmButtonText: 'Đồng ý xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `<?= URLROOT ?>/partner/removeStaff/${staffId}`;
        }
    })
}

// Chức năng đổi Role nhanh bằng SweetAlert2
function openEditRoleModal(staffId) {
    Swal.fire({
        title: 'Thay đổi vai trò',
        input: 'select',
        inputOptions: {
            'Staff': 'Staff (Receptionist)',
            'Partner': 'Manager (Supervisor)'
        },
        inputPlaceholder: 'Chọn vai trò mới',
        showCancelButton: true,
        confirmButtonText: 'Cập nhật',
        confirmButtonColor: '#2261E0',
        preConfirm: (role) => {
            return fetch('<?= URLROOT ?>/partner/changeRole', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `staffId=${staffId}&newRole=${role}`
            }).then(response => response.json());
        }
    }).then((result) => {
        if (result.value && result.value.success) {
            Swal.fire('Thành công!', 'Vai trò đã được cập nhật.', 'success')
            .then(() => location.reload());
        }
    });
}

// Hiển thị thông báo từ URL (nếu có)
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('success')) {
    Swal.fire({ icon: 'success', title: 'Thành công!', text: 'Tài khoản nhân viên đã được tạo.', timer: 2000 });
}
if (urlParams.has('msg') && urlParams.get('msg') === 'removed') {
    Swal.fire({ icon: 'success', title: 'Đã xóa!', text: 'Nhân viên đã bị loại khỏi danh sách.', timer: 2000 });
}

// Đóng modal khi click ra ngoài vùng content
window.onclick = function(event) {
    const modal = document.getElementById('addStaffModal');
    if (event.target == modal) {
        closeStaffModal();
    }
}

function handleResetPassword(staffId, staffName) {
    Swal.fire({
        title: 'Đặt lại mật khẩu',
        text: `Nhập mật khẩu mới cho ${staffName}`,
        input: 'password',
        inputAttributes: { autocapitalize: 'off', placeholder: 'Mật khẩu mới ít nhất 6 ký tự' },
        showCancelButton: true,
        confirmButtonText: 'Cập nhật',
        confirmButtonColor: '#2261E0',
        showLoaderOnConfirm: true,
        preConfirm: (newPassword) => {
            if (newPassword.length < 6) {
                Swal.showValidationMessage('Mật khẩu quá ngắn!');
                return false;
            }
            // Gửi dữ liệu qua AJAX
            let formData = new FormData();
            formData.append('staffId', staffId);
            formData.append('newPassword', newPassword);

            return fetch('<?= URLROOT ?>/partner/resetPassword', {
                method: 'POST',
                body: formData
            }).then(response => response.json());
        }
    }).then((result) => {
        if (result.isConfirmed && result.value.success) {
            Swal.fire('Thành công!', 'Mật khẩu đã được thay đổi.', 'success');
        } else if (result.isConfirmed) {
            Swal.fire('Lỗi!', 'Không thể thực hiện tác vụ này.', 'error');
        }
    });
}

function handleToggleStatus(staffId, currentStatus) {
    const actionName = (currentStatus === 'ACTIVE') ? 'Tạm khóa/Nghỉ ca' : 'Mở khóa/Vào ca';
    const nextStatus = (currentStatus === 'ACTIVE') ? 'BLOCKED' : 'ACTIVE';

    Swal.fire({
        title: `${actionName}?`,
        text: `Bạn muốn thay đổi trạng thái hoạt động của nhân viên này?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2261E0',
        cancelButtonColor: '#667085',
        confirmButtonText: 'Xác nhận',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            // Chuyển hướng đến controller xử lý
            window.location.href = `<?= URLROOT ?>/partner/toggleStatus/${staffId}`;
        }
    });
}
</script>