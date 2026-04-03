<?php
    $url = $_GET['url'] ?? '';
    $urlArray = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));
    
    $activePage = !empty($urlArray[0]) ? strtolower($urlArray[0]) : 'dashboard'; 
?>
<aside class="main-sidebar">
    <div class="sidebar-context">
        <div class="context-icon">🏢</div>
        <div class="context-text">
            <h4><?= $_SESSION['active_hotel_name'] ?? 'Dalat Palace Hotel' ?></h4>
            <p>ACTIVE PROPERTY</p>
        </div>
    </div>

    <nav class="sidebar-menu">
        <ul>
            <li>
                <a href="<?= URLROOT ?>/dashboard" class="menu-item <?= ($activePage == 'dashboard') ? 'active' : '' ?>">
                    <span class="icon">📊</span> Dashboard
                </a>
            </li>

            <li>
                <a href="<?= URLROOT ?>/bookings" class="menu-item <?= ($activePage == 'bookings') ? 'active' : '' ?>">
                    <span class="icon">📅</span> Bookings
                </a>
            </li>

            <li>
                <a href="<?= URLROOT ?>/rooms" class="menu-item <?= ($activePage == 'rooms') ? 'active' : '' ?>">
                    <span class="icon">🛌</span> Rooms & Pricing
                </a>
            </li>

            <li>
                <a href="<?= URLROOT ?>/inventory" class="menu-item <?= ($activePage == 'inventory') ? 'active' : '' ?>">
                    <span class="icon">🗓️</span> Inventory
                </a>
            </li>

            <li>
                <a href="<?= URLROOT ?>/staff" class="menu-item <?= ($activePage == 'staff') ? 'active' : '' ?>">
                    <span class="icon">👥</span> Staff Assignment
                </a>
            </li>

            <li>
                <a href="<?= URLROOT ?>/reviews" class="menu-item <?= ($activePage == 'reviews') ? 'active' : '' ?>">
                    <span class="icon">💬</span> Reviews
                </a>
            </li>

            <li>
                <a href="<?= URLROOT ?>/reports" class="menu-item <?= ($activePage == 'reports') ? 'active' : '' ?>">
                    <span class="icon">💵</span> Financial Reports
                </a>
            </li>
            <li>
                <a href="<?= URLROOT ?>/transactions" class="menu-item <?= ($activePage == 'transactions') ? 'active' : '' ?>">
                    <span class="icon">💳</span> Transactions
                </a>
            </li>
            <li>
                <a href="<?= URLROOT ?>/vouchers" class="menu-item <?= ($activePage == 'vouchers') ? 'active' : '' ?>">
                    <span class="icon">🏷️</span> Vouchers & Promos
                </a>
            </li>
        </ul>
    </nav>

    <!-- <div class="sidebar-footer">
        <a href="<?= URLROOT ?>/support" class="menu-item <?= ($activePage == 'support') ? 'active' : '' ?>">
            <span class="icon">❓</span> Support
        </a>
    </div> -->
</aside>