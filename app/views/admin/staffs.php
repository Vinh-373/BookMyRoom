<div class="staffs-container">
    <h1>Staffs</h1>

    <!-- Stats cards -->
    <div class="staffs-sum-grid">
      <div class="staffs-sum-all"><?php echo count($staffs); ?> Tổng nhân viên</div>
      <div class="staffs-sum-active"><?php echo count(array_filter($staffs, fn($s) => $s['status'] == 'ACTIVE')); ?> Đang làm việc</div>
      <div class="staffs-sum-pending"><?php echo count(array_filter($staffs, fn($s) => $s['status'] == 'PENDING')); ?> Nghỉ việc</div>
      <div class="staffs-sum-blocked"><?php echo count(array_filter($staffs, fn($s) => $s['status'] == 'BLOCKED')); ?> Đang khóa</div>
    </div>

    <!-- Search & filter -->
    <div class="staffs-search-filter">
      <input id="staffsSearchInput" type="text" placeholder="Tìm kiếm nhân viên...">
      <select id="staffsStatusFilter">
        <option>-- Lọc trạng thái --</option>
        <option>ACTIVE</option>
        <option>PENDING</option>
        <option>BLOCKED</option>
      </select>
      <button class="staffs-btn-add-staff">+ Thêm nhân viên</button>
    </div>



   <!-- Table -->
   <table class="staffs-data-table">
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
         <th>Thành phố</th>
         <th>Khu vực</th>
         <th>Ngày tạo</th>
         <th>Hành động</th>
       </tr>
     </thead>
     <tbody id="staffsTableBody">
       <?php foreach ($staffs as $staff): ?>
         <tr data-user-id="<?php echo $staff['id']; ?>">
           <td><?php echo $staff['id']; ?></td>
           <td><?php echo htmlspecialchars($staff['fullName']); ?></td>
           <td><?php echo htmlspecialchars($staff['email']); ?></td>
           <td>********</td>
           <td><?php echo htmlspecialchars($staff['phone']); ?></td>
           <td><?php echo $staff['status']; ?></td>
           <td><?php echo htmlspecialchars($staff['address'] ?? ''); ?></td>
           <td><?php echo htmlspecialchars($staff['gender'] ?? ''); ?></td>
           <td><?php echo htmlspecialchars($staff['birthDate'] ?? ''); ?></td>
           <td><?php echo htmlspecialchars($staff['avatarUrl'] ?? ''); ?></td>
           <td><?php echo htmlspecialchars($staff['cityName'] ?? ''); ?></td>
           <td><?php echo htmlspecialchars($staff['wardName'] ?? ''); ?></td>
           <td><?php echo date('Y-m-d', strtotime($staff['createdAt'])); ?></td>
           <td>
             <?php if ($staff['status'] == 'PENDING'): ?>
               <button class="staffs-approve-btn btn-approve" data-user-id="<?php echo $staff['userId']; ?>">Duyệt</button>
             <?php else: ?>
               <button class="staff-edit-btn btn-edit"
                 data-user-id="<?php echo $staff['id']; ?>"
                 data-full-name="<?php echo htmlspecialchars($staff['fullName']); ?>"
                 data-email="<?php echo htmlspecialchars($staff['email']); ?>"
                 data-phone="<?php echo htmlspecialchars($staff['phone']); ?>"
                 data-status="<?php echo $staff['status']; ?>"
                 data-address="<?php echo htmlspecialchars($staff['address'] ?? ''); ?>"
                 data-gender="<?php echo htmlspecialchars($staff['gender'] ?? ''); ?>"
                 data-birth-date="<?php echo htmlspecialchars($staff['birthDate'] ?? ''); ?>"
                 data-avatar-url="<?php echo htmlspecialchars($staff['avatarUrl'] ?? ''); ?>"
                 data-city-id="<?php echo htmlspecialchars($staff['cityId'] ?? ''); ?>"
                 data-ward-id="<?php echo htmlspecialchars($staff['wardId'] ?? ''); ?>"
                 data-created-at="<?php echo date('Y-m-d', strtotime($staff['createdAt'])); ?>">Sửa</button>
               <button
                 class="staff-toggle-status-btn <?php echo $staff['status'] == 'BLOCKED' ? 'btn-active' : 'btn-blocked'; ?>"
                 data-user-id="<?php echo $staff['id']; ?>">
                 <?php echo $staff['status'] == 'ACTIVE' ? 'Khóa' : 'Mở'; ?>
               </button>
             <?php endif; ?>
           </td>
         </tr>
       <?php endforeach; ?>
     </tbody>
   </table>
</div>