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
      <select id="statusFilter">
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
          <th>Số điện thoại</th>
          <th>Công ty</th>
          <th>Trạng thái</th>
          <th>Ngày tạo</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody id="partnersTableBody">
        <?php foreach ($partners as $partner): ?>
        <tr data-user-id="<?php echo $partner['userId']; ?>">
          <td><?php echo $partner['userId']; ?></td>
          <td><?php echo htmlspecialchars($partner['fullName']); ?></td>
          <td><?php echo htmlspecialchars($partner['email']); ?></td>
          <td><?php echo htmlspecialchars($partner['phone']); ?></td>
          <td><?php echo htmlspecialchars($partner['companyName']); ?></td>
          <td><?php echo $partner['status']; ?></td>
          <td><?php echo date('Y-m-d', strtotime($partner['createdAt'])); ?></td>
          <td>
            <?php if ($partner['status'] == 'PENDING'): ?>
              <button class="approve-btn" data-user-id="<?php echo $partner['userId']; ?>">Duyệt</button>
            <?php else: ?>
              <button class="edit-btn" data-user-id="<?php echo $partner['userId']; ?>" 
                      data-fullname="<?php echo htmlspecialchars($partner['fullName']); ?>"
                      data-email="<?php echo htmlspecialchars($partner['email']); ?>"
                      data-phone="<?php echo htmlspecialchars($partner['phone']); ?>"
                      data-company="<?php echo htmlspecialchars($partner['companyName']); ?>"
                      data-taxcode="<?php echo htmlspecialchars($partner['taxCode']); ?>"
                      data-license="<?php echo htmlspecialchars($partner['businessLicense']); ?>"
                      data-status="<?php echo $partner['status']; ?>">Sửa</button>
            <?php endif; ?>
            <button class="delete-btn" data-user-id="<?php echo $partner['userId']; ?>">Xóa</button>
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
      
      <form id="addPartnerForm" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Họ tên</label>
          <input type="text" name="fullName" required 
                 class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" name="email" required 
                 class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
          <input type="tel" name="phone" required 
                 class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Tên công ty</label>
          <input type="text" name="companyName" required 
                 class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Mã số thuế</label>
          <input type="text" name="taxCode" required 
                 class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Giấy phép kinh doanh (JSON)</label>
          <textarea name="businessLicense" rows="3" placeholder='{"license": "L001", "issued": "2024-01-01"}' required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>
        
        <div class="flex justify-end space-x-3 pt-4">
          <button type="button" id="cancelBtn" 
                  class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
            Hủy
          </button>
          <button type="submit" 
                  class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Họ tên</label>
          <input type="text" name="fullName" id="editFullName" required 
                 class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" name="email" id="editEmail" required 
                 class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
          <input type="tel" name="phone" id="editPhone" required 
                 class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Tên công ty</label>
          <input type="text" name="companyName" id="editCompanyName" required 
                 class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Mã số thuế</label>
          <input type="text" name="taxCode" id="editTaxCode" required 
                 class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
          <select name="status" id="editStatus" 
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <option value="ACTIVE">ACTIVE</option>
            <option value="PENDING">PENDING</option>
            <option value="BLOCKED">BLOCKED</option>
          </select>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Giấy phép kinh doanh (JSON)</label>
          <textarea name="businessLicense" id="editBusinessLicense" rows="3" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>
        
        <div class="flex justify-end space-x-3 pt-4">
          <button type="button" id="cancelEditBtn" 
                  class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
            Hủy
          </button>
          <button type="submit" 
                  class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Cập nhật
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// JavaScript để xử lý modal và các chức năng
document.addEventListener('DOMContentLoaded', function() {
    const addModal = document.getElementById('addPartnerModal');
    const editModal = document.getElementById('editPartnerModal');
    const addBtn = document.querySelector('.partners-btn-add-partner');
    const closeAddModal = document.getElementById('closeModal');
    const closeEditModal = document.getElementById('closeEditModal');
    const cancelAddBtn = document.getElementById('cancelBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const addForm = document.getElementById('addPartnerForm');
    const editForm = document.getElementById('editPartnerForm');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');

    // Modal functions
    function closeAddModalFunc() {
        addModal.classList.add('hidden');
        addForm.reset();
    }

    function closeEditModalFunc() {
        editModal.classList.add('hidden');
        editForm.reset();
    }

    // Event listeners for modals
    addBtn.addEventListener('click', function() {
        addModal.classList.remove('hidden');
    });

    closeAddModal.addEventListener('click', closeAddModalFunc);
    cancelAddBtn.addEventListener('click', closeAddModalFunc);
    closeEditModal.addEventListener('click', closeEditModalFunc);
    cancelEditBtn.addEventListener('click', closeEditModalFunc);

    // Close modals when clicking outside
    [addModal, editModal].forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                if (modal === addModal) addForm.reset();
                else editForm.reset();
            }
        });
    });

    // Search and filter functionality
    const currentPath = window.location.pathname.replace(/\/$/, '');
    const apiBase = currentPath.endsWith('/partners') ? currentPath : currentPath + '/partners';

    function performSearch() {
        const query = searchInput.value.trim();
        const status = statusFilter.value;

        console.log('Searching with:', { query, status }); // Debug log

        fetch(`${apiBase}/search?q=${encodeURIComponent(query)}&status=${encodeURIComponent(status)}`)
        .then(response => {
            console.log('Response status:', response.status); // Debug log
            return response.json();
        })
        .then(result => {
            console.log('Search result:', result); // Debug log
            if (result.success) {
                updateTable(result.partners);
                updateStats(result.partners);
            } else {
                console.error('Search failed:', result);
            }
        })
        .catch(error => {
            console.error('Search error:', error);
        });
    }

    searchInput.addEventListener('input', performSearch);
    statusFilter.addEventListener('change', performSearch);

    // Update table with search results
    function updateTable(partners) {
        const tbody = document.getElementById('partnersTableBody');
        tbody.innerHTML = '';
        
        partners.forEach(partner => {
            const row = `
                <tr data-user-id="${partner.userId}">
                    <td>${partner.userId}</td>
                    <td>${escapeHtml(partner.fullName)}</td>
                    <td>${escapeHtml(partner.email)}</td>
                    <td>${escapeHtml(partner.phone)}</td>
                    <td>${escapeHtml(partner.companyName)}</td>
                    <td>${partner.status}</td>
                    <td>${new Date(partner.createdAt).toLocaleDateString('vi-VN')}</td>
                    <td>
                        ${partner.status == 'PENDING' ? 
                            `<button class="approve-btn" data-user-id="${partner.userId}">Duyệt</button>` :
                            `<button class="edit-btn" data-user-id="${partner.userId}" 
                                    data-fullname="${escapeHtml(partner.fullName)}"
                                    data-email="${escapeHtml(partner.email)}"
                                    data-phone="${escapeHtml(partner.phone)}"
                                    data-company="${escapeHtml(partner.companyName)}"
                                    data-taxcode="${escapeHtml(partner.taxCode)}"
                                    data-license="${escapeHtml(partner.businessLicense)}"
                                    data-status="${partner.status}">Sửa</button>`
                        }
                        <button class="delete-btn" data-user-id="${partner.userId}">Xóa</button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
        
        // Re-attach event listeners
        attachActionListeners();
    }

    // Update stats
    function updateStats(partners) {
        const total = partners.length;
        const active = partners.filter(p => p.status == 'ACTIVE').length;
        const pending = partners.filter(p => p.status == 'PENDING').length;
        const blocked = partners.filter(p => p.status == 'BLOCKED').length;
        
        document.querySelectorAll('.partners-stat-card')[0].textContent = `${total} Tổng tài khoản`;
        document.querySelectorAll('.partners-stat-card')[1].textContent = `${active} Đang hoạt động`;
        document.querySelectorAll('.partners-stat-card')[2].textContent = `${pending} Đang chờ duyệt`;
        document.querySelectorAll('.partners-stat-card')[3].textContent = `${blocked} Bị khóa`;
    }

    // Attach action listeners
    function attachActionListeners() {
        // Edit buttons
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const fullName = this.getAttribute('data-fullname');
                const email = this.getAttribute('data-email');
                const phone = this.getAttribute('data-phone');
                const companyName = this.getAttribute('data-company');
                const taxCode = this.getAttribute('data-taxcode');
                const businessLicense = this.getAttribute('data-license');
                const status = this.getAttribute('data-status');
                
                document.getElementById('editUserId').value = userId;
                document.getElementById('editFullName').value = fullName;
                document.getElementById('editEmail').value = email;
                document.getElementById('editPhone').value = phone;
                document.getElementById('editCompanyName').value = companyName;
                document.getElementById('editTaxCode').value = taxCode;
                document.getElementById('editBusinessLicense').value = businessLicense;
                document.getElementById('editStatus').value = status;
                
                editModal.classList.remove('hidden');
            });
        });

        // Delete buttons
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                if (confirm('Bạn có chắc muốn xóa đối tác này?')) {
                    fetch(`${apiBase}/delete`, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({userId: userId})
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert('Xóa đối tác thành công!');
                            location.reload();
                        } else {
                            alert('Lỗi: ' + result.message);
                        }
                    });
                }
            });
        });

        // Approve buttons
        document.querySelectorAll('.approve-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                if (confirm('Bạn có chắc muốn duyệt đối tác này?')) {
                    fetch(`${apiBase}/approve`, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({userId: userId})
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert('Duyệt đối tác thành công!');
                            location.reload();
                        } else {
                            alert('Lỗi: ' + result.message);
                        }
                    });
                }
            });
        });
    }

    // Initial attach listeners
    attachActionListeners();

    // Add partner form submit
    addForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(addForm);
        const data = Object.fromEntries(formData);
        
        // Validate JSON
        try {
            JSON.parse(data.businessLicense);
        } catch (error) {
            alert('Giấy phép kinh doanh phải là JSON hợp lệ!');
            return;
        }

        fetch(`${apiBase}/add`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Thêm đối tác thành công!');
                closeAddModalFunc();
                location.reload();
            } else {
                alert('Lỗi: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thêm đối tác!');
        });
    });

    // Edit partner form submit
    editForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(editForm);
        const data = Object.fromEntries(formData);
        
        // Validate JSON
        try {
            JSON.parse(data.businessLicense);
        } catch (error) {
            alert('Giấy phép kinh doanh phải là JSON hợp lệ!');
            return;
        }

        fetch(`${apiBase}/update`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Cập nhật đối tác thành công!');
                closeEditModalFunc();
                location.reload();
            } else {
                alert('Lỗi: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi cập nhật đối tác!');
        });
    });

    // Helper function
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>
