<div class="customers-container">
  <h1>customers</h1>


  <!-- Stats cards -->
  <div class="customers-sum-grid">
    <div class="customers-sum-all"><?php echo count($customers); ?> Tổng khách hàng</div>
    <div class="customers-sum-active"><?php echo count(array_filter($customers, fn($s) => $s['status'] == 'ACTIVE')); ?> Đang làm việc</div>
    <div class="customers-sum-pending"><?php echo count(array_filter($customers, fn($s) => $s['status'] == 'PENDING')); ?> Nghỉ việc</div>
    <div class="customers-sum-blocked"><?php echo count(array_filter($customers, fn($s) => $s['status'] == 'BLOCKED')); ?> Đang khóa</div>
  </div>

  <!-- Search & filter -->
  <div class="customers-search-filter">
    <input id="customersSearchInput" type="text" placeholder="Tìm kiếm nhân viên...">
    <select id="customersStatusFilter">
      <option value="">-- Lọc trạng thái --</option>
      <option value="ACTIVE">ACTIVE</option>
      <option value="PENDING">PENDING</option>
      <option value="BLOCKED">BLOCKED</option>
    </select>
    <button class="customers-btn-add-customer">+ Thêm khách hàng</button>
  </div>


  <!-- Table -->
  <table class="customers-data-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Mật khẩu</th>
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
          <td>********</td>
          <td><?php echo htmlspecialchars($customer['phone']); ?></td>
          <td><?php echo $customer['status']; ?></td>
          <td><?php echo htmlspecialchars($customer['address'] ?? ''); ?></td>
          <td><?php echo htmlspecialchars($customer['gender'] ?? ''); ?></td>
          <td><?php echo htmlspecialchars($customer['birthDate'] ?? ''); ?></td>
          <td>
            <img
              src="/BookMyRoom/<?php echo $customer['avatarUrl'] ?: '/public/images/avatars/default.jpg'; ?>"
              class="customers-avatar-img">
          </td>
          <td><?php echo htmlspecialchars($customer['cityName'] ?? ''); ?></td>
          <td><?php echo htmlspecialchars($customer['wardName'] ?? ''); ?></td>
          <td><?php echo date('Y-m-d', strtotime($customer['createdAt'])); ?></td>
          <td>
            <?php if ($customer['status'] == 'PENDING'): ?>
              <button class="customers-approve-btn btn-approve" data-user-id="<?php echo $customer['id']; ?>">Duyệt</button>
            <?php else: ?>
              <button class="customer-edit-btn btn-edit"
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
                data-created-at="<?php echo date('Y-m-d', strtotime($customer['createdAt'])); ?>">Sửa</button>
              <button
                class="customer-toggle-status-btn <?php echo $customer['status'] == 'BLOCKED' ? 'btn-active' : 'btn-blocked'; ?>"
                data-user-id="<?php echo $customer['id']; ?>">
                <?php echo $customer['status'] == 'ACTIVE' ? 'Khóa' : 'Mở'; ?>
              </button>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>


  <!-- Modal thêm đối tác -->
  <div id="addCustomersModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-gray-900">Thêm nhân viên mới</h3>
          <button id="closeAddModal_customer" class="text-gray-400 hover:text-gray-600">
            <span class="text-2xl">&times;</span>
          </button>
        </div>

        <form id="addCustomersForm" method="POST" action="/BookMyRoom/admin/customers/add" class="space-y-4"> <!-- Họ tên -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Họ tên</label>
            <input type="text" name="fullName" required
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" required
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            <span id="emailError" class="text-red-600 text-sm"></span>
          </div>


          <!-- Mật khẩu -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Mật khẩu</label>
            <input type="password" name="password" required
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>

          <!-- Số điện thoại -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
            <input type="tel" name="phone" required
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            <span id="phoneError" class="text-red-600 text-sm"></span>
          </div>


          <!-- Trạng thái -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
            <select name="status" required
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              <option value="PENDING">PENDING</option>
              <option value="ACTIVE">ACTIVE</option>
              <option value="BLOCKED">BLOCKED</option>
            </select>
          </div>

          <!-- Địa chỉ -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Địa chỉ</label>
            <input type="text" name="address"
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>

          <!-- Giới tính -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Giới tính</label>
            <select name="gender"
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              <option value="">-- Chọn giới tính --</option>
              <option value="male">Nam</option>
              <option value="female">Nữ</option>
              <option value="other">Khác</option>
            </select>
          </div>

          <!-- Ngày sinh -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Ngày sinh</label>
            <input type="date" name="birthDate"
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>

          <!-- Avatar URL -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Avatar URL</label>
            <input type="url" name="avatarUrl"
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>

          <!-- Thành phố -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Thành phố</label>
            <select name="cityId" id="addCityId" required
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              <option value="">-- Chọn thành phố --</option>
              <?php foreach ($cities as $city): ?>
                <option value="<?php echo $city['id']; ?>"><?php echo htmlspecialchars($city['name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Khu vực -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Khu vực</label>
            <select name="wardId" id="addWardId" required
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              <option value="">-- Chọn khu vực --</option>
              <?php foreach ($wards as $ward): ?>
                <option value="<?php echo $ward['id']; ?>" data-city-id="<?php echo $ward['cityId']; ?>">
                  <?php echo htmlspecialchars($ward['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Nút hành động -->
          <div class="flex justify-end space-x-3 pt-4">
            <button type="button" id="cancelBtn_customer"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:ring-2 focus:ring-gray-500">
              Hủy
            </button>
            <button type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
              Thêm đối tác
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal sửa đối tác -->
  <div id="editCustomersModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-gray-900">Sửa đối tác</h3>
          <button id="closeEditModal_customer" class="text-gray-400 hover:text-gray-600">
            <span class="text-2xl">&times;</span>
          </button>
        </div>

        <form id="editCustomersForm" class="space-y-4">
          <input type="hidden" name="id" id="editUserId">

          <!-- Họ tên -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Họ tên</label>
            <input type="text" name="fullName" id="editFullName" required
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="editEmail" required
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>

          <!-- Mật khẩu -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Mật khẩu</label>
            <input type="password" name="password" id="editPassword"
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Nhập mật khẩu mới">
          </div>

          <!-- Số điện thoại -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
            <input type="tel" name="phone" id="editPhone" required
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>

          <!-- Trạng thái -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
            <select name="status" id="editStatus"
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              <option value="ACTIVE">ACTIVE</option>
              <option value="BLOCKED">BLOCKED</option>
            </select>
          </div>

          <!-- Địa chỉ -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Địa chỉ</label>
            <input type="text" name="address" id="editAddress"
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>

          <!-- Giới tính -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Giới tính</label>
            <select name="gender" id="editGender"
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              <option value="">-- Chọn giới tính --</option>
              <option value="male">Nam</option>
              <option value="female">Nữ</option>
              <option value="other">Khác</option>
            </select>
          </div>

          <!-- Ngày sinh -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Ngày sinh</label>
            <input type="date" name="birthDate" id="editBirthDate"
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>

          <!-- Avatar URL -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Avatar URL</label>
            <input type="url" name="avatarUrl" id="editAvatarUrl"
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          </div>

          <!-- Thành phố -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Thành phố</label>
            <select name="cityId" id="editCityId" required
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              <option value="">-- Chọn thành phố --</option>
              <?php foreach ($cities as $city): ?>
                <option value="<?php echo $city['id']; ?>"><?php echo htmlspecialchars($city['name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Khu vực -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Khu vực</label>
            <select name="wardId" id="editWardId" required
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              <option value="">-- Chọn khu vực --</option>
              <?php foreach ($wards as $ward): ?>
                <option value="<?php echo $ward['id']; ?>" data-city-id="<?php echo $ward['cityId']; ?>">
                  <?php echo htmlspecialchars($ward['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Ngày tạo (readonly) -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Ngày tạo</label>
            <input type="text" name="createdAt" id="editCreatedAt" readonly
              class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm bg-gray-100">
          </div>


          <!-- Nút hành động -->
          <div class="flex justify-end space-x-3 pt-4">
            <button type="button" id="cancelEditBtn_customer"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:ring-2 focus:ring-gray-500">
              Hủy
            </button>
            <button type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
              Cập nhật
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>