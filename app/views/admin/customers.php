<div class="customers-container">
    <h1>Quản lý tài khoản khách hàng</h1>

    <!-- Stats cards -->
    <div class="customers-stats-grid">
        <div class="customers-stat-card"><?php echo count($customers); ?> Tổng tài khoản</div>
        <div class="customers-stat-card"><?php echo count(array_filter($customers, fn($c) => $c['status'] == 'ACTIVE')); ?> Đang hoạt động</div>
        <div class="customers-stat-card"><?php echo count(array_filter($customers, fn($c) => $c['status'] == 'PENDING')); ?> Đang chờ duyệt</div>
        <div class="customers-stat-card"><?php echo count(array_filter($customers, fn($c) => $c['status'] == 'BLOCKED')); ?> Bị khóa</div>
    </div>

    <!-- Search & filter -->
    <div class="customers-search-filter">
        <input type="text" id="customersSearchInput" placeholder="Tìm kiếm khách hàng...">
        <select id="customersStatusFilter">
            <option value="">-- Lọc trạng thái --</option>
            <option value="ACTIVE">ACTIVE</option>
            <option value="PENDING">PENDING</option>
            <option value="BLOCKED">BLOCKED</option>
        </select>
        <button class="customers-btn-add">+ Thêm khách hàng</button>
    </div>

    <!-- Table -->
    <table class="customers-data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Trạng thái</th>
                <th>Địa chỉ</th>
                <th>Giới tính</th>
                <th>Ngày sinh</th>
                <th>Avatar</th>
                <th>Thành phố</th>
                <th>Khu vực</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody id="customersTableBody">
            <?php foreach ($customers as $customer): ?>
            <tr data-user-id="<?php echo $customer['id']; ?>">
                <td><?php echo $customer['id']; ?></td>
                <td><?php echo htmlspecialchars($customer['fullName']); ?></td>
                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                <td><?php echo $customer['status']; ?></td>
                <td><?php echo htmlspecialchars($customer['address'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($customer['gender'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($customer['birthDate'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($customer['avatarUrl'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($customer['cityName'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($customer['wardName'] ?? ''); ?></td>
                <td><?php echo date('Y-m-d', strtotime($customer['createdAt'])); ?></td>
                <td>
                    <button class="customers-edit-btn" 
                        data-user-id="<?php echo $customer['id']; ?>"
                        data-full-name="<?php echo htmlspecialchars($customer['fullName']); ?>"
                        data-email="<?php echo htmlspecialchars($customer['email']); ?>"
                        data-phone="<?php echo htmlspecialchars($customer['phone']); ?>"
                        data-status="<?php echo $customer['status']; ?>"
                        data-address="<?php echo htmlspecialchars($customer['address'] ?? ''); ?>"
                        data-gender="<?php echo htmlspecialchars($customer['gender'] ?? ''); ?>"
                        data-birth-date="<?php echo htmlspecialchars($customer['birthDate'] ?? ''); ?>"
                        data-avatar-url="<?php echo htmlspecialchars($customer['avatarUrl'] ?? ''); ?>"
                        data-city-id="<?php echo htmlspecialchars($customer['cityId'] ?? ''); ?>"
                        data-ward-id="<?php echo htmlspecialchars($customer['wardId'] ?? ''); ?>"
                        data-created-at="<?php echo date('Y-m-d', strtotime($customer['createdAt'])); ?>"
                    >Sửa</button>

                    <button class="customers-toggle-status-btn <?php echo $customer['status'] == 'BLOCKED' ? 'btn-active' : 'btn-blocked'; ?>"
                        data-user-id="<?php echo $customer['id']; ?>">
                        <?php echo $customer['status'] == 'ACTIVE' ? 'Khóa' : 'Mở'; ?>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal thêm khách hàng -->
<div id="addCustomerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Thêm khách hàng mới</h3>
                <button id="closeAddCustomerModal" class="text-gray-400 hover:text-gray-600">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            <form id="addCustomerForm" method="POST" action="/BookMyRoom/admin/customers/add" class="space-y-4">
                <div>
                    <label>Họ tên</label>
                    <input type="text" name="fullName" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label>Mật khẩu</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label>Số điện thoại</label>
                    <input type="tel" name="phone" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label>Trạng thái</label>
                    <select name="status" required class="w-full px-3 py-2 border rounded">
                        <option value="PENDING">PENDING</option>
                        <option value="ACTIVE">ACTIVE</option>
                        <option value="BLOCKED">BLOCKED</option>
                    </select>
                </div>
                <!-- Thành phố & Khu vực -->
                <div>
                    <label>Thành phố</label>
                    <select name="cityId" required class="w-full px-3 py-2 border rounded">
                        <option value="">-- Chọn thành phố --</option>
                        <?php foreach ($cities as $city): ?>
                        <option value="<?php echo $city['id']; ?>"><?php echo htmlspecialchars($city['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Khu vực</label>
                    <select name="wardId" required class="w-full px-3 py-2 border rounded">
                        <option value="">-- Chọn khu vực --</option>
                        <?php foreach ($wards as $ward): ?>
                        <option value="<?php echo $ward['id']; ?>" data-city-id="<?php echo $ward['cityId']; ?>">
                            <?php echo htmlspecialchars($ward['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelAddCustomerBtn" class="px-4 py-2 bg-gray-300 rounded">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Thêm khách hàng</button>
                </div>
            </form>
        </div>
    </div>
</div>