<?php
    $list_hotel = [];
    if(!empty($partnerHotels)){
        $list_hotel = $partnerHotels;
    }
?>
<header class="main-header">
    <div class="header-left">
        <div class="logo">
            <a href="<?= URLROOT ?>/partner" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                <span class="logo-text">BookMyRoom</span>
            </a>
        </div>
    </div>
    <?php if(!empty($activeHotelId)): ?>
        <div class="header-center">
            <div class="hotel-selector">
                <span class="icon">⭐</span>
                <select name="active_hotel" id="hotelSelect" onchange="switchHotel(this.value)">
                    <?php foreach($list_hotel as $hotel) :?>
                        <option value="<?= $hotel['id'] ?>" <?= ($hotel['id'] == $activeHotelId ? 'selected' : '') ?>><?= $hotel['hotelName'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    <?php endif?>
    <div class="header-right">
        <div class="header-actions">
            <button class="btn-icon">🔔</button>
        </div>
        <div class="user-dropdown-container" id="userDropdownTrigger">
            <div class="user-profile">
                <div class="user-info">
                    <span class="u-name"><?= $_SESSION['user']['name'] ?></span>
                    <span class="u-role"><?= $_SESSION['user']['role'] ?></span>
                </div>
                <img src="<?= URLROOT ?>/public/images/avatar.jpg" alt="User Avatar" class="avatar">
            </div>

            <div class="user-menu-dropdown" id="userMenu">
                <ul>
                    <li><a href="javascript:void(0)" onclick="openProfileModal()">👤 View Profile</a></li>
                    <li class="divider"></li>
                    <li>
                        <a href="<?= URLROOT ?>/partner/logout" class="logout-link" onclick="handleLogout(event)">
                            ↪️ Sign Out
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
<script>
function switchHotel(hotelId) {
    if (hotelId) {
        window.location.href = "<?= URLROOT ?>/manage/" + hotelId;
    }
}
function handleLogout(event) {
    event.preventDefault();

    localStorage.removeItem("token");
    localStorage.removeItem("user");

    window.location.href = event.currentTarget.href;
}
</script>