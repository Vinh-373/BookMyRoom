<header class="header">
    <div class="header-content">
        <!-- Ô tìm kiếm -->
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <input type="text" class="search-input" placeholder="Tìm kiếm...">
        </div>

        <!-- Hành động bên phải -->
        <div class="header-actions">
            <button class="notification-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="notification-badge"></span>
            </button>

            <div class="user-menu">
                <div class="user-avatar"></div>
                <div class="user-info">
                    <p class="user-name"><?= $_SESSION['admin_name'] ?></p>
                    <p class="user-email"><?= $_SESSION['admin_email'] ?></p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
                <button class="logout">Đăng xuất</button>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const logoutBtn = document.querySelector('.logout');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();

            // Hiển thị hộp thoại xác nhận (tùy chọn)
            if (confirm('Bạn có chắc chắn muốn đăng xuất khỏi hệ thống?')) {
                // Hiệu ứng loading nhẹ trên nút
                logoutBtn.innerText = 'Đang thoát...';
                logoutBtn.style.opacity = '0.5';
                logoutBtn.style.pointerEvents = 'none';

                // Chuyển hướng về Route logout trong Auth Controller
                window.location.href = '/BookMyRoom/admin/auth/logout';
            }
        });
    }
});
</script>