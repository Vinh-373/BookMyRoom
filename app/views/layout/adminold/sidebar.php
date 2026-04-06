<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <div class="logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </div>
            <div class="logo-text">
                <h1>HotelAdmin</h1>
                <p>Quản lý khách sạn</p>
            </div>
        </div>
    </div>

    <div class="sidebar-toggle-container">
        <button class="sidebar-toggle" id="sidebar-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
    </div>

    <?php
    $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $requestPath = trim($requestPath, '/');
    $parts = explode('/', $requestPath);
    $activePage = 'dashboard';

    if (isset($parts[0]) && $parts[0] === 'BookMyRoom') {
        if (isset($parts[1]) && $parts[1] === 'admin' && isset($parts[2]) && !empty($parts[2])) {
            $activePage = $parts[2];
        }
    } elseif (isset($parts[0]) && $parts[0] === 'admin' && isset($parts[1]) && !empty($parts[1])) {
        $activePage = $parts[1];
    }
    ?>

    <nav class="sidebar-nav">
        <a href="http://localhost/BookMyRoom/admin/dashboard" class="nav-item <?php echo $activePage === 'dashboard' ? 'active' : ''; ?>" data-page="dashboard">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <span>Tổng quan</span>
        </a>

        <a href="http://localhost/BookMyRoom/admin/hotels" class="nav-item <?php echo $activePage === 'hotels' ? 'active' : ''; ?>" data-page="hotels">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span>Quản lý Khách sạn</span>
        </a>


        <a href="http://localhost/BookMyRoom/admin/bookings" class="nav-item <?php echo $activePage === 'bookings' ? 'active' : ''; ?>" data-page="bookings">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span>Quản lý Đặt phòng</span>
        </a>


        <a href="http://localhost/BookMyRoom/admin/customers" class="nav-item <?php echo $activePage === 'customers' ? 'active' : ''; ?>" data-page="customers">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span>Tài khoản khách hàng</span>
        </a>

        <a href="http://localhost/BookMyRoom/admin/partners" class="nav-item <?php echo $activePage === 'partners' ? 'active' : ''; ?>" data-page="partners">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span>Tài khoản đối tác</span>
        </a>

        <a href="http://localhost/BookMyRoom/admin/payments" class="nav-item <?php echo $activePage === 'payments' ? 'active' : ''; ?>" data-page="payments">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span>Quản lý Thanh toán</span>
        </a>

        <a href="http://localhost/BookMyRoom/admin/vouchers" class="nav-item <?php echo $activePage === 'vouchers' ? 'active' : ''; ?>" data-page="vouchers">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span>Quản lý Khuyến mãi</span>
        </a>

        <a href="http://localhost/BookMyRoom/admin/reviews" class="nav-item <?php echo $activePage === 'reviews' ? 'active' : ''; ?>" data-page="reviews">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span>Quản lý Đánh giá</span>
        </a>

        <a href="http://localhost/BookMyRoom/admin/statisticals" class="nav-item <?php echo $activePage === 'statisticals' ? 'active' : ''; ?>" data-page="statisticals">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span>Thống kê</span>
        </a>

        <a href="http://localhost/BookMyRoom/admin/settings" class="nav-item <?php echo $activePage === 'settings' ? 'active' : ''; ?>" data-page="settings">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M12 1v6m0 6v6m5.66-13.66l-4.24 4.24m0 6l-4.24 4.24m13.66-5.66l-6-6m-6 0l-6 6m13.66 5.66l-4.24-4.24m-6 0l-4.24-4.24"></path>
            </svg>
            <span>Cài đặt</span>
        </a>

    </nav>
    <script src="public/js/admin/sidebar.js"></script>
</aside>