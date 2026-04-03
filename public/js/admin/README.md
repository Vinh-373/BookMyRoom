# JavaScript Event Handlers - Admin Panel

Hướng dẫn sử dụng các file JavaScript xử lý sự kiện cho trang admin quản lý.

## 📁 Cấu Trúc File

```
public/js/admin/
├── init.js                    # Script loader tự động
├── utils.js                   # Hàm công dụng chung
├── sidebar.js                 # Sidebar toggle (đã có)
├── dashboard.js               # Trang Dashboard
├── bookings.js                # Trang Quản Lý Đặt Phòng
├── hotels.js                  # Trang Quản Lý Khách Sạn
├── partner-moderation.js      # Trang Phê Duyệt Đối Tác
├── rooms.js                   # Trang Quản Lý Phòng
├── settings.js                # Trang Cài Đặt
├── payments.js                # Trang Quản Lý Thanh Toán
├── reviews.js                 # Trang Quản Lý Đánh Giá
├── vouchers.js                # Trang Quản Lý Khuyến Mãi
└── accounts.js                # Trang Quản Lý Tài Khoản
```

## 🚀 Cách Sử Dụng

### Option 1: Tự động load script (Khuyến Nghị)

Thêm vào cuối `admin.php`:

```php
<!-- Utils (required for all pages) -->
<script src="<?php echo URL; ?>public/js/admin/utils.js"></script>

<!-- Auto load page-specific script -->
<script src="<?php echo URL; ?>public/js/admin/init.js"></script>
```

Hoặc thêm `data-page` attribute vào body/main:

```php
<body data-page="dashboard">
    <!-- content -->
</body>
```

### Option 2: Manual load script (Cách cũ)

Thêm vào từng trang:

```php
<!-- dashboard.php -->
<script src="<?php echo URL; ?>public/js/admin/utils.js"></script>
<script src="<?php echo URL; ?>public/js/admin/dashboard.js"></script>

<!-- bookings.php -->
<script src="<?php echo URL; ?>public/js/admin/utils.js"></script>
<script src="<?php echo URL; ?>public/js/admin/bookings.js"></script>
```

## 📝 Mô Tả Từng File

### 1. **utils.js** (Hàm Công Dụng)
- **API Helpers**: `apiGet()`, `apiPost()`, `apiPut()`, `apiDelete()`
- **Notifications**: `showNotification()`, `showConfirmDialog()`, `showLoading()`
- **Table Utils**: `sortTable()`, `highlightTableRow()`
- **Form Utils**: `getFormData()`, `setFormData()`, `clearForm()`
- **Validation**: `validateEmail()`, `validatePassword()`
- **Date/Currency**: `formatDateVN()`, `formatCurrencyVN()`, `getDateRange()`
- **Storage**: `saveToStorage()`, `getFromStorage()`, `removeFromStorage()`

**Cách sử dụng:**
```javascript
// API calls
const data = await apiGet('/api/bookings');
await apiPost('/api/bookings', { roomId: 123 });

// Notifications
showNotification('Thành công!', 'success');
showConfirmDialog('Xác nhận', 'Bạn chắc chắn?', () => deleteItem());

// Form
const formData = getFormData(document.querySelector('form'));
setFormData(formElement, { name: 'Tran Van A', email: 'a@example.com' });

// Validation
if (validateEmail(email)) { /* ... */ }

// Storage
saveToStorage('dashboard-filter', { status: 'active' });
const settings = getFromStorage('dashboard-filter');
```

### 2. **dashboard.js** (Trang Dashboard)
**Sự kiện xử lý:**
- ✅ Click nút 30/90 ngày để thay đổi khoảng thời gian
- ✅ Click legend chart
- ✅ Hover activity timeline
- ✅ Click row trong bảng booking gần đây
- ✅ Auto refresh dữ liệu mỗi 5 phút

**Yêu cầu HTML:**
```html
<!-- Time period buttons -->
<button class="px-4 py-1.5">30 ngày</button>
<button class="px-4 py-1.5">90 ngày</button>

<!-- Chart legend -->
<button class="chart-legend">Legend item</button>

<!-- Activity timeline -->
<div class="activity-item">Timeline event</div>

<!-- Recent bookings table -->
<table>
    <tbody>
        <tr>...</tr>
    </tbody>
</table>
```

### 3. **bookings.js** (Trang Đặt Phòng)
**Sự kiện xử lý:**
- ✅ Click filter button (Tất cả, Hôm nay, Tuần này, Chờ xử lý)
- ✅ Click nút Xem, Sửa, Xóa trong bảng
- ✅ Hover row để hiển thị action buttons
- ✅ Click pagination
- ✅ Search bookings

**Yêu cầu class:**
```html
<button class="bookings-filter-btn">Tất cả</button>
<button class="booking-view-btn">Xem</button>
<button class="booking-edit-btn">Sửa</button>
<button class="booking-delete-btn">Xóa</button>
<input class="booking-search-input" />
<button class="pagination-btn">2</button>
```

### 4. **hotels.js** (Trang Khách Sạn)
**Sự kiện xử lý:**
- ✅ Click "Thêm Đối Tác Mới"
- ✅ Search hotels
- ✅ Filter by city, status, stars
- ✅ Click View/Edit/Block
- ✅ Hover row để hiển thị actions

**Yêu cầu class:**
```html
<button class="hotels-add-partner-btn">Thêm Đối Tác</button>
<input class="hotels-search-input" />
<select class="hotels-filter-select" data-filter="city">
<button class="hotel-view-btn">Xem</button>
<button class="hotel-edit-btn">Sửa</button>
<button class="hotel-block-btn">Khóa</button>
```

### 5. **partner-moderation.js** (Phê Duyệt Đối Tác)
**Sự kiện xử lý:**
- ✅ Search and filter requests
- ✅ Click View application
- ✅ Click Approve/Reject
- ✅ Show rejection reason modal
- ✅ History and export

### 6. **rooms.js** (Quản Lý Phòng)
**Sự kiện xử lý:**
- ✅ Search and filter rooms
- ✅ Click View/Edit/Delete room
- ✅ Change room status
- ✅ Hover card/row actions
- ✅ Pagination

### 7. **settings.js** (Cài Đặt)
**Sự kiện xử lý:**
- ✅ Avatar upload (click image)
- ✅ Password visibility toggle (eye icon)
- ✅ 2FA toggle enable/disable
- ✅ Form input validation
- ✅ Save button submit
- ✅ Tab switching
- ✅ Notification preferences

**Yêu cầu class:**
```html
<img class="settings-avatar-img" src="..." />
<input class="settings-input" name="fullname" />
<input type="email" class="settings-input" />
<input type="password" />
<span class="settings-password-toggle">visibility</span>
<input type="checkbox" class="settings-2fa-toggle" />
<button class="settings-save-btn">Lưu thay đổi</button>
```

### 8. **payments/reviews/vouchers/accounts.js**
- Filter, search, view details
- Approve/Reject/Delete actions
- Modal for new items
- Responsive table actions

## 🔗 Integration Checklist

**Trong mỗi trang PHP admin, thêm:**

```php
<!-- Bottom of page -->
<script src="<?php echo URL; ?>public/js/admin/utils.js"></script>
<!-- Hoặc sử dụng auto-loader -->
<script src="<?php echo URL; ?>public/js/admin/init.js"></script>
<script>
    // Optional: Set data-page nếu auto-loader không detect được
    document.body.setAttribute('data-page', 'dashboard');
</script>
```

**Các class CSS cần thiết:**
- `.active` - Active state
- `.disabled` - Disabled state
- `.error` - Error state
- `group` / `group-hover:opacity-x` - Hover effects
- `.opacity-0` / `.opacity-100` - Visibility
- `.pointer-events-none` - Disable pointer events

## 🎯 TODO Items (Backend API)

Sau khi event handlers được setup, cần tạo các API endpoints:

- [ ] GET `/api/dashboard/stats` - KPI data
- [ ] GET `/api/bookings` - List bookings with filters
- [ ] POST `/api/bookings/:id/cancel` - Cancel booking
- [ ] GET `/api/hotels` - List hotels
- [ ] POST `/api/hotels` - Create hotel
- [ ] PUT `/api/hotels/:id` - Update hotel
- [ ] POST `/api/partner-moderation/:id/approve` - Approve partner
- [ ] POST `/api/partner-moderation/:id/reject` - Reject partner
- [ ] GET `/api/rooms` - List rooms
- [ ] PUT `/api/rooms/:id/status` - Update room status
- [ ] PUT `/api/settings` - Save settings
- [ ] POST `/api/settings/avatar` - Upload avatar
- [ ] POST `/api/settings/2fa/enable` - Enable 2FA
- [ ] Etc.

## 🐛 Debugging Tips

1. **Check console logs:**
   ```
   Open DevTools (F12) > Console
   All handlers log their actions
   ```

2. **Verify classes are correct:**
   ```javascript
   // In Console
   document.querySelectorAll('.booking-view-btn').length > 0
   ```

3. **Test API calls:**
   ```javascript
   // In Console
   apiGet('/api/bookings').then(data => console.log(data));
   ```

4. **Enable verbose logging:**
   Add to utils.js:
   ```javascript
   window.DEBUG = true;
   ```

## 📱 Responsive Design

Handlers là responsive-ready! Các sự kiện hoạt động trên:
- ✅ Desktop
- ✅ Tablet
- ✅ Mobile

## 🔐 Security Notes

- CSRF token handling (add khi gọi API)
- Input validation trước khi submit
- Authorization check server-side
- Sanitize output khi hiển thị

## 📞 Support

Nếu có issue:
1. Check console logs
2. Verify HTML class names match expected selectors
3. Check Database.php connection
4. Run test-mysql.php để debug DB

---

**Tạo bởi:** GitHub Copilot
**Ngày:** 2024
**Phiên bản:** 1.0.0
