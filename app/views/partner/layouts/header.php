<?php
    $list_hotel = [];
    if(!empty($partnerHotels)){
        $list_hotel = $partnerHotels;
    }
?>
<header class="main-header">
    <div class="header-left">
        <div class="logo">
            <span class="logo-text">BookMyRoom</span>
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
                    <span class="u-name">Mr. Dalat</span> <!--$_SESSION['user_name'] -->
                    <span class="u-role">Partner</span> <!--$_SESSION['user_role'] -->
                </div>
                <img src="<?= URLROOT ?>/public/images/avatar.jpg" alt="User Avatar" class="avatar">
            </div>

            <div class="user-menu-dropdown" id="userMenu">
                <ul>
                    <li><a href="javascript:void(0)" onclick="openProfileModal()">👤 View Profile</a></li>
                    <li class="divider"></li>
                    <li><a href="<?= URLROOT ?>/logout" class="logout-link">↪️ Sign Out</a></li>
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
</script>