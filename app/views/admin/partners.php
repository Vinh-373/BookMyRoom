 <div class="partners-container">
   <h1>Quản lý tài khoản đối tác</h1>

   <!-- Stats cards -->
   <div class="partners-stats-grid">
     <div class="partners-stat-card"><?php echo count($partners); ?> Tổng tài khoản</div>
     <div class="partners-stat-card"><?php echo count(array_filter($partners, fn($p) => $p['status'] == 'ACTIVE')); ?> Đang hoạt động</div>
     <div class="partners-stat-card"><?php echo count(array_filter($partners, fn($p) => $p['status'] == 'PENDING')); ?> Đang chờ duyệt</div>
     <div class="partners-stat-card"><?php echo count(array_filter($partners, fn($p) => $p['status'] == 'BLOCKED')); ?> Bị khóa</div>
   </div>

   <!-- Search & filter -->
   <div class="partners-search-filter">
     <input type="text" id="searchInput" placeholder="Tìm kiếm đối tác...">
     <select id="partnerStatusFilter">
       <option value="">-- Lọc trạng thái --</option>
       <option value="ACTIVE">ACTIVE</option>
       <option value="PENDING">PENDING</option>
       <option value="BLOCKED">BLOCKED</option>
     </select>
     <button class="partners-btn-add-partner">+ Thêm đối tác</button>
   </div>

   <!-- Table -->
   <table class="partners-data-table">
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
         <th>AvatarUrl</th>
         <th>Thành phố/th>
         <th>Khu vực</th>
         <th>Ngày tạo</th>
         <th>Công ty</th>
         <th>Mã số thuế</th>
         <th>Giấy phép kinh doanh</th>
         <th>Hành động</th>
       </tr>
     </thead>
     <tbody id="partnersTableBody">
       <?php foreach ($partners as $partner): ?>
         <tr data-user-id="<?php echo $partner['userId']; ?>">
           <td><?php echo $partner['userId']; ?></td>
           <td><?php echo htmlspecialchars($partner['fullName']); ?></td>
           <td><?php echo htmlspecialchars($partner['email']); ?></td>
           <td>********</td>
           <td><?php echo htmlspecialchars($partner['phone']); ?></td>
           <td><?php echo $partner['status']; ?></td>
           <td><?php echo htmlspecialchars($partner['address'] ?? ''); ?></td>
           <td><?php echo htmlspecialchars($partner['gender'] ?? ''); ?></td>
           <td><?php echo htmlspecialchars($partner['birthDate'] ?? ''); ?></td>
           <td><?php echo htmlspecialchars($partner['avatarUrl'] ?? ''); ?></td>
           <td><?php echo htmlspecialchars($partner['cityId'] ?? ''); ?></td>
           <td><?php echo htmlspecialchars($partner['wardId'] ?? ''); ?></td>
           <td><?php echo date('Y-m-d', strtotime($partner['createdAt'])); ?></td>
           <td><?php echo htmlspecialchars($partner['companyName']); ?></td>
           <td><?php echo htmlspecialchars($partner['taxCode']); ?></td>
           <td><?php echo htmlspecialchars($partner['businessLicense']); ?></td>
           <td>
             <?php if ($partner['status'] == 'PENDING'): ?>
               <button class="approve-btn" data-user-id="<?php echo $partner['userId']; ?>">Duyệt</button>
             <?php else: ?>
               <button class="partner-edit-btn"
                 data-user-id="<?php echo $partner['userId']; ?>"
                 data-full-name="<?php echo htmlspecialchars($partner['fullName']); ?>"
                 data-email="<?php echo htmlspecialchars($partner['email']); ?>"
                 data-phone="<?php echo htmlspecialchars($partner['phone']); ?>"
                 data-status="<?php echo $partner['status']; ?>"
                 data-address="<?php echo htmlspecialchars($partner['address'] ?? ''); ?>"
                 data-gender="<?php echo htmlspecialchars($partner['gender'] ?? ''); ?>"
                 data-birth-date="<?php echo htmlspecialchars($partner['birthDate'] ?? ''); ?>"
                 data-avatar-url="<?php echo htmlspecialchars($partner['avatarUrl'] ?? ''); ?>"
                 data-city-id="<?php echo htmlspecialchars($partner['cityId'] ?? ''); ?>"
                 data-ward-id="<?php echo htmlspecialchars($partner['wardId'] ?? ''); ?>"
                 data-created-at="<?php echo date('Y-m-d', strtotime($partner['createdAt'])); ?>"
                 data-company-name="<?php echo htmlspecialchars($partner['companyName']); ?>"
                 data-tax-code="<?php echo htmlspecialchars($partner['taxCode']); ?>"
                 data-business-license="<?php echo htmlspecialchars($partner['businessLicense']); ?>">Sửa</button>
             <?php endif; ?>
             <button
               class="partner-toggle-status-btn <?php echo $partner['status'] == 'BLOCKED' ? 'btn-active' : 'btn-blocked'; ?>"
               data-user-id="<?php echo $partner['userId']; ?>">
               <?php echo $partner['status'] == 'ACTIVE' ? 'Khóa' : 'Mở'; ?>
             </button>
           </td>
         </tr>
       <?php endforeach; ?>
     </tbody>
   </table>
 </div>

 <!-- Modal thêm đối tác -->
 <div id="addPartnerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
   <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
     <div class="mt-3">
       <div class="flex items-center justify-between mb-4">
         <h3 class="text-lg font-medium text-gray-900">Thêm đối tác mới</h3>
         <button id="closeModal" class="text-gray-400 hover:text-gray-600">
           <span class="text-2xl">&times;</span>
         </button>
       </div>

       <form id="addPartnerForm" method="POST" action="/BookMyRoom/admin/partners/add" class="space-y-4"> <!-- Họ tên -->
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

         <!-- Công ty -->
         <div>
           <label class="block text-sm font-medium text-gray-700">Tên công ty</label>
           <input type="text" name="companyName" required
             class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
         </div>

         <!-- Mã số thuế -->
         <div>
           <label class="block text-sm font-medium text-gray-700">Mã số thuế</label>
           <input type="text" name="taxCode" required
             class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
         </div>

         <!-- Giấy phép kinh doanh -->
         <div>
           <label class="block text-sm font-medium text-gray-700">Giấy phép kinh doanh (JSON)</label>
           <textarea name="businessLicense" rows="3" placeholder='{"license": "L001", "issued": "2024-01-01"}' required
             class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
         </div>

         <!-- Nút hành động -->
         <div class="flex justify-end space-x-3 pt-4">
           <button type="button" id="cancelBtn"
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
 <div id="editPartnerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
   <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
     <div class="mt-3">
       <div class="flex items-center justify-between mb-4">
         <h3 class="text-lg font-medium text-gray-900">Sửa đối tác</h3>
         <button id="closeEditModal" class="text-gray-400 hover:text-gray-600">
           <span class="text-2xl">&times;</span>
         </button>
       </div>

       <form id="editPartnerForm" class="space-y-4">
         <input type="hidden" name="userId" id="editUserId">

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
             <option value="PENDING">PENDING</option>
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

         <!-- Công ty -->
         <div>
           <label class="block text-sm font-medium text-gray-700">Tên công ty</label>
           <input type="text" name="companyName" id="editCompanyName" required
             class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
         </div>

         <!-- Mã số thuế -->
         <div>
           <label class="block text-sm font-medium text-gray-700">Mã số thuế</label>
           <input type="text" name="taxCode" id="editTaxCode" required
             class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
         </div>

         <!-- Giấy phép kinh doanh -->
         <div>
           <label class="block text-sm font-medium text-gray-700">Giấy phép kinh doanh (JSON)</label>
           <textarea name="businessLicense" id="editBusinessLicense" rows="3" required
             class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
         </div>

         <!-- Nút hành động -->
         <div class="flex justify-end space-x-3 pt-4">
           <button type="button" id="cancelEditBtn"
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