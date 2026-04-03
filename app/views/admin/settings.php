<div class="page-container">

    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Cài đặt hệ thống</h1>
            <p class="page-subtitle">Quản lý cấu hình hệ thống, tài khoản và thanh toán.</p>
        </div>
        <button class="btn-primary" id="save-settings-btn">Lưu thay đổi</button>
    </div>

    <!-- ===== ACCOUNT SETTINGS ===== -->
    <div class="settings-section">
        <h2>Thông tin quản trị</h2>

        <div class="form-grid">
            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" id="profile-fullname" value="Admin Skeeyzi">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" id="profile-email" value="admin@skeeyzi.com">
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" id="profile-phone" value="0901234567">
            </div>

            <div class="form-group">
                <label>Mật khẩu mới</label>
                <input type="password" placeholder="••••••••">
            </div>
        </div>
    </div>

    <!-- ===== SYSTEM SETTINGS ===== -->
    <div class="settings-section">
        <h2>Cấu hình hệ thống</h2>

        <div class="form-grid">

            <div class="form-group">
                <label>Phí nền tảng (%)</label>
                <input type="number" value="10">
            </div>

            <div class="form-group">
                <label>Tiền tệ mặc định</label>
                <select>
                    <option>VNĐ</option>
                    <option>USD</option>
                </select>
            </div>

            <div class="form-group">
                <label>Ngôn ngữ hệ thống</label>
                <select>
                    <option>Tiếng Việt</option>
                    <option>English</option>
                </select>
            </div>

        </div>
    </div>

    <!-- ===== PAYMENT SETTINGS ===== -->
    <div class="settings-section">
        <h2>Cài đặt thanh toán</h2>

        <div class="toggle-group">
            <div class="toggle-item">
                <span>Thanh toán MoMo</span>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="toggle-item">
                <span>Thanh toán VNPay</span>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="toggle-item">
                <span>Thanh toán tiền mặt</span>
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- ===== NOTIFICATION SETTINGS ===== -->
    <div class="settings-section">
        <h2>Thông báo</h2>

        <div class="toggle-group">
            <div class="toggle-item">
                <span>Email khi có booking mới</span>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="toggle-item">
                <span>Email khi thanh toán thất bại</span>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="toggle-item">
                <span>Thông báo hệ thống</span>
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider"></span>
                </label>
            </div>
        </div>
    </div>

</div>