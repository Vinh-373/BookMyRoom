<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Partner Dashboard'; ?></title>
    <!-- Sử dụng URLROOT đã định nghĩa trong config.php -->
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/main-style.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/portfolio.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/bookings.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/rooms.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/inventory.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/staff.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/reviews.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/reports.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/transactions.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/vouchers.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="app-wrapper">
        <?php 
            if (!isset($hideSidebar) || $hideSidebar === false) {
                include 'sidebar.php';
            }
        ?>
        <main class="content-canvas <?php echo (isset($hideSidebar) && $hideSidebar === true) ? 'full-width' : ''; ?>">
            <div class="container-fluid">
                <?php echo $content; ?>
            </div>
            <?php include 'footer.php'; ?>
        </main>
    </div>

    <div id="userProfileModal" class="master-scoped-modal">
        <div class="m-modal-content">
            <form id="profileForm" onsubmit="saveProfile(event)">
                <div class="m-modal-header">
                    <h3>Tài khoản của tôi</h3>
                    <button type="button" class="m-close-modal" onclick="closeProfileModal()">✕</button>
                </div>
                <div class="m-modal-body">
                    <div class="profile-card-header">
                        <div class="avatar-wrapper">
                            <img id="displayAvatar" src="<?= URLROOT ?>/public/images/avatar.jpg" alt="Avatar" class="m-avatar-large">
                            <label for="avatarInput" class="edit-avatar-icon" title="Đổi ảnh đại diện">
                                <i class="fas fa-camera"></i>
                                <input type="file" id="avatarInput" accept="image/*" style="display:none" onchange="previewAvatar(this)">
                            </label>
                        </div>
                        <div class="m-user-titles">
                            <input type="text" name="fullName" class="m-input-title" value="<?= $_SESSION['user_name'] ?? 'Mr. Dalat' ?>" required>
                            <span class="m-status-tag">Đối tác xác thực</span>
                        </div>
                    </div>
                    
                    <div class="m-info-grid">
                        <div class="m-info-group">
                            <label>Email (Không thể thay đổi)</label>
                            <input type="email" value="<?= $_SESSION['user_email'] ?? 'dalat.palace@partner.com' ?>" disabled class="m-input-disabled">
                        </div>
                        <div class="m-info-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" value="<?= $_SESSION['user_phone'] ?? '' ?>" placeholder="Chưa cập nhật số điện thoại">
                        </div>
                        <div class="m-info-group">
                            <label>Ngày tham gia</label>
                            <p class="m-readonly-text"><?= $_SESSION['user_created_at'] ?? '24/10/2025' ?></p>
                        </div>
                        <div class="m-info-group">
                            <label>Vai trò</label>
                            <p class="m-readonly-text">Quản trị viên khách sạn</p>
                        </div>
                    </div>
                </div>
                <div class="m-modal-footer">
                    <button type="button" class="m-btn-close" onclick="closeProfileModal()">Hủy</button>
                    <button type="submit" id="btnSaveProfile" class="m-btn-save">
                        <i class="fas fa-save"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openProfileModal() {
            const menu = document.getElementById('userMenu');
            if(menu) menu.classList.remove('active');
            document.getElementById('userProfileModal').style.display = 'flex';
        }

        function closeProfileModal() {
            document.getElementById('userProfileModal').style.display = 'none';
        }

        // Xem trước ảnh đại diện khi chọn file
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('displayAvatar').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Xử lý lưu Profile qua AJAX
    async function saveProfile(event) {
        event.preventDefault();
        const btn = document.getElementById('btnSaveProfile');
        const form = event.target;
        const formData = new FormData(form);
        
        // Lấy dữ liệu để validate
        const fullName = formData.get('fullName').trim();
        const phone = formData.get('phone').trim();
        const avatarFile = document.getElementById('avatarInput').files[0];

        // --- BẮT ĐẦU VALIDATE ---
        
        // 1. Kiểm tra để trống
        if (!fullName || !phone) {
            Swal.fire('Chú ý', 'Vui lòng điền đầy đủ Họ tên và Số điện thoại.', 'warning');
            return;
        }

        // 2. Định dạng số điện thoại: 10 số, bắt đầu bằng số 0
        const phoneRegex = /^0\d{9}$/;
        if (!phoneRegex.test(phone)) {
            Swal.fire('Lỗi định dạng', 'Số điện thoại phải có 10 chữ số và bắt đầu bằng số 0.', 'error');
            return;
        }

        // --- KẾT THÚC VALIDATE ---

        if (avatarFile) {
            formData.append('avatar', avatarFile);
        }

        // Hiệu ứng Loading
        const originalBtnContent = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        btn.disabled = true;

        try {
            const response = await fetch('<?= URLROOT ?>/partner/updateProfileAjax', {
                method: 'POST',
                body: formData
            });

            // Kiểm tra nếu phản hồi không phải JSON (lỗi 500 hoặc HTML error)
            if (!response.ok) throw new Error('Phản hồi từ server không hợp lệ.');

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    title: 'Thành công!',
                    text: 'Thông tin cá nhân đã được cập nhật.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });

                // Update UI real-time
                const headerUserName = document.querySelector('.m-user-titles h4');
                const topHeaderName = document.querySelector('.header-user-name');
                
                if (headerUserName) headerUserName.innerText = fullName;
                if (topHeaderName) topHeaderName.innerText = fullName;

                setTimeout(() => closeProfileModal(), 1600);
            } else {
                // Hiển thị lỗi từ Server (ví dụ: trùng số điện thoại đã làm ở bước trước)
                Swal.fire('Thất bại', result.message || 'Cập nhật không thành công.', 'error');
            }
        } catch (error) {
            console.error('Lỗi luồng dữ liệu:', error);
            Swal.fire('Lỗi hệ thống', 'Không thể kết nối hoặc dữ liệu trả về bị lỗi.', 'error');
        } finally {
            btn.innerHTML = originalBtnContent;
            btn.disabled = false;
        }
    }

        window.onclick = (e) => {
            const modal = document.getElementById('userProfileModal');
            if (e.target === modal) closeProfileModal();
        };
    </script>
</body>
<style>
    .m-input-title {
        font-size: 1.2rem;
        font-weight: 700;
        border: 1px solid transparent;
        padding: 5px;
        width: 100%;
        background: transparent;
        color: #1e293b;
    }
    .m-input-title:focus {
        border-bottom: 2px solid #2261E0;
        outline: none;
    }
    .m-info-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-top: 5px;
        font-size: 0.95rem;
    }
    .m-input-disabled {
        background-color: #f8fafc;
        cursor: not-allowed;
        color: #94a3b8;
    }
    .avatar-wrapper {
        position: relative;
        display: inline-block;
    }
    .edit-avatar-icon {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #2261E0;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid white;
    }
    .m-btn-save {
        background: #2261E0;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
    }
    .m-readonly-text {
        padding: 10px 0;
        color: #64748b;
        font-weight: 500;
    }
</style>
</html>