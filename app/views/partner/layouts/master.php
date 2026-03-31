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
            <div class="m-modal-header">
                <h3>Tài khoản của tôi</h3>
                <button type="button" class="m-close-modal" onclick="closeProfileModal()">✕</button>
            </div>
            <div class="m-modal-body">
                <div class="profile-card-header">
                    <img src="<?= URLROOT ?>/public/images/avatar.jpg" alt="Avatar" class="m-avatar-large">
                    <div class="m-user-titles">
                        <h4>Mr. Dalat</h4><!--$_SESSION['user_name'] -->
                        <span class="m-status-tag">Đối tác xác thực</span>
                    </div>
                </div>
                
                <div class="m-info-grid">
                    <div class="m-info-group">
                        <label>Email</label>
                        <p>dalat.palace@partner.com</p><!--$_SESSION['user_email']-->
                    </div>
                    <div class="m-info-group">
                        <label>Vai trò</label>
                        <p>Quản trị viên khách sạn (Partner)</p><!--$_SESSION['user_role']-->
                    </div>
                    <div class="m-info-group">
                        <label>Ngày tham gia</label>
                        <p>24/10/2025</p><!--$_SESSION['user_created_at']-->
                    </div>
                </div>
            </div>
            <div class="m-modal-footer">
                <button type="button" class="m-btn-close" onclick="closeProfileModal()">Đóng</button>
                <a href="<?= URLROOT ?>/partner/profile" class="m-btn-edit">Chỉnh sửa Profile</a>
            </div>
        </div>
    </div>

    <script>
        function openProfileModal() {
            // Đóng dropdown menu trong header nếu đang mở
            const menu = document.getElementById('userMenu');
            if(menu) menu.classList.remove='active';
            
            document.getElementById('userProfileModal').style.display = 'flex';
        }

        function closeProfileModal() {
            document.getElementById('userProfileModal').style.display = 'none';
        }

        // Đóng khi click ra ngoài vùng modal
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('userProfileModal');
            if (e.target === modal) closeProfileModal();
        });
    </script>
</body>
</html>