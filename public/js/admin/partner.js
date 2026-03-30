document.addEventListener('DOMContentLoaded', () => {
  const addModal = document.getElementById('addPartnerModal');
  const editModal = document.getElementById('editPartnerModal');
  const addBtn = document.querySelector('.partners-btn-add-partner');
  const closeAddModal = document.getElementById('closeModal_partner');
  const closeEditModal = document.getElementById('closeEditModal_partner');
  const cancelAddBtn = document.getElementById('cancelBtn_partner');
  const cancelEditBtn = document.getElementById('cancelEditBtn_partner');
  const addForm = document.getElementById('addPartnerForm');
  const editForm = document.getElementById('editPartnerForm');
  const searchInput = document.getElementById('searchInput_partner');
  const statusFilter = document.getElementById('partnerStatusFilter');

  // Base API path
  const currentPath = window.location.pathname.replace(/\/$/, '');
  const apiBase = currentPath.endsWith('/partners') ? currentPath : currentPath + '/partners';

  // Escape HTML helper
  function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  // Modal functions
  function closeAddModalFunc() {
    addModal.classList.add('hidden');
    addForm.reset();
  }


  // Event listeners for modals
  addBtn.addEventListener('click', () => addModal.classList.remove('hidden'));
  closeAddModal.addEventListener('click', closeAddModalFunc);
  cancelAddBtn.addEventListener('click', closeAddModalFunc);


  // Close modals when clicking outside
  [addModal, editModal].forEach(modal => {
    modal.addEventListener('click', e => {
      if (e.target === modal) {
        modal.classList.add('hidden');
        (modal === addModal ? addForm : editForm).reset();
      }
    });
  });

  // Update stats
  function updateStats(partners) {
    const total = partners.length;
    const active = partners.filter(p => p.status === 'ACTIVE').length;
    const pending = partners.filter(p => p.status === 'PENDING').length;
    const blocked = partners.filter(p => p.status === 'BLOCKED').length;

    document.querySelectorAll('.partners-stat-card')[0].textContent = `${total} Tổng tài khoản`;
    document.querySelectorAll('.partners-stat-card')[1].textContent = `${active} Đang hoạt động`;
    document.querySelectorAll('.partners-stat-card')[2].textContent = `${pending} Đang chờ duyệt`;
    document.querySelectorAll('.partners-stat-card')[3].textContent = `${blocked} Bị khóa`;
  }




  //////////////// Lấy tất cả các hàng <tr> trong tbody của bảng
  const rows = document.querySelectorAll('#partnersTableBody tr');
  //////////////////// Hàm thực hiện lọc bảng
  function filterTable() {
    // Lấy từ khóa nhập vào, chuyển thành chữ thường để so sánh
    const keyword = searchInput.value.toLowerCase();
    // Lấy trạng thái được chọn trong dropdown
    const statusSelected = statusFilter.value;

    // Duyệt qua từng hàng trong bảng
    rows.forEach(row => {
      const fullName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
      const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
      const phone = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
      const status = row.querySelector('td:nth-child(6)').textContent.trim();

      // Kiểm tra có khớp từ khóa không (tìm theo tên hoặc email)
      const matchKeyword = fullName.includes(keyword) || email.includes(keyword) || phone.includes(keyword);
      // Kiểm tra có khớp trạng thái không (nếu chưa chọn thì coi như khớp hết)
      const matchStatus = !statusSelected || status === statusSelected;

      // Nếu cả hai điều kiện đều đúng thì hiện hàng, ngược lại ẩn đi
      row.style.display = (matchKeyword && matchStatus) ? '' : 'none';
    });
  }
  // Gắn sự kiện: khi nhập từ khóa thì lọc ngay
  searchInput.addEventListener('input', filterTable);
  // Gắn sự kiện: khi chọn trạng thái thì lọc ngay
  statusFilter.addEventListener('change', filterTable);







  
  ////////////////////////////////////////////////////////////
  // ================= MODAL & FORM =================
  // 🔹 Hàm đóng modal
  function closeEditModalFunc() {
    editModal.classList.add('hidden');
    editForm.reset();
  }
  closeEditModal.addEventListener('click', closeEditModalFunc);
  cancelEditBtn.addEventListener('click', closeEditModalFunc);




  // ================= Edit Form =================
  editForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = Object.fromEntries(new FormData(editForm));
    console.log('DATA:', formData);

    // Kiểm tra businessLicense là JSON hợp lệ
    try {
      if (formData.businessLicense) JSON.parse(formData.businessLicense);
    } catch {
      alert('Giấy phép kinh doanh phải là JSON hợp lệ!');
      return;
    }

    fetch(`${apiBase}/update`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        userId: formData.userId,
        fullName: formData.fullName,
        email: formData.email,
        password: formData.password, // hash ở backend
        phone: formData.phone,
        status: formData.status,
        address: formData.address,
        gender: formData.gender,
        birthDate: formData.birthDate,
        avatarUrl: formData.avatarUrl,
        cityId: formData.cityId || null,
        wardId: formData.wardId || null,
        companyName: formData.companyName,
        taxCode: formData.taxCode,
        businessLicense: formData.businessLicense
      })
    })
      .then(res => res.json())
      .then(result => {
        console.log('RESULT:', result);

        if (result.success) {
          const updated = result.updatedUser;

          // Lấy row trong bảng
          const row = document.querySelector(
            `.partner-edit-btn[data-user-id="${formData.userId}"]`
          ).closest('tr');

          // Update DOM bảng
          row.querySelector('td:nth-child(2)').textContent = updated.fullName;
          row.querySelector('td:nth-child(3)').textContent = updated.email;
          row.querySelector('td:nth-child(4)').textContent = '••••••••'; // password ẩn
          row.querySelector('td:nth-child(5)').textContent = updated.phone || '';
          row.querySelector('td:nth-child(6)').textContent = updated.status;
          const btn = row.querySelector('.partner-toggle-status-btn');
          updateStatusButton(btn, updated.status);
          row.querySelector('td:nth-child(7)').textContent = updated.address || '';
          row.querySelector('td:nth-child(8)').textContent = updated.gender || '';

          // format ngày sinh
          row.querySelector('td:nth-child(9)').textContent = updated.birthDate || '';

          row.querySelector('td:nth-child(10)').textContent = updated.avatarUrl || '';
          row.querySelector('td:nth-child(11)').textContent = updated.cityName || '';
          row.querySelector('td:nth-child(12)').textContent = updated.wardName || '';
          row.querySelector('td:nth-child(14)').textContent = updated.companyName || '';
          row.querySelector('td:nth-child(15)').textContent = updated.taxCode || '';
          row.querySelector('td:nth-child(16)').textContent = updated.businessLicense || '';

          closeEditModalFunc();
          filterTable();
          alert('Cập nhật thành công!');
        } else {
          alert(result.message || 'Cập nhật thất bại!');
        }
      })
      .catch(err => {
        console.error('ERROR:', err);
        alert('Có lỗi xảy ra!');
      });
  });

  // ================= ĐIỀN FORM KHI CLICK SỬA =================
  document.getElementById('partnersTableBody').addEventListener('click', function (e) {
    const row = e.target.closest('tr'); // luôn lấy row từ click target
    if (!row) return;



// 🔹 Duyệt đối tác
  if (e.target.classList.contains('partners-approve-btn')) {
    const userId = e.target.dataset.userId;
    if (!confirm('Bạn có chắc muốn duyệt đối tác này?')) return;

    fetch(`${apiBase}/approve`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ userId })
    })
    .then(res => res.json())
    .then(result => {
      if (result.success) {
        // Update DOM row
        row.querySelector('td:nth-child(6)').textContent = 'ACTIVE'; // cột status
        const actionCell = e.target.parentElement;
        actionCell.innerHTML = `
          <button class="partner-edit-btn" data-user-id="${userId}">Sửa</button>
          <button class="partner-toggle-status-btn" data-user-id="${userId}">Khóa</button>
        `;

        // Cập nhật nút toggle màu
        const toggleBtn = actionCell.querySelector('.partner-toggle-status-btn');
        updateStatusButton(toggleBtn, 'ACTIVE');

        alert('Duyệt đối tác thành công!');
      } else {
        alert(result.message || 'Duyệt thất bại!');
      }
    })
    .catch(err => alert('Có lỗi xảy ra: ' + err.message));
  }



    // 🔹 Toggle status
    if (e.target.classList.contains('partner-toggle-status-btn')) {
      const btn = e.target;
      const userId = btn.dataset.userId;
      const statusCell = row.querySelector('td:nth-child(6)');
      const currentStatus = statusCell.textContent.trim();
      const newStatus = currentStatus === 'BLOCKED' ? 'ACTIVE' : 'BLOCKED';

      if (!confirm(`Bạn có chắc muốn ${newStatus === 'BLOCKED' ? 'khóa' : 'mở'} đối tác này?`)) return;

      fetch(`${apiBase}/toggleStatus`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ userId, status: newStatus })
      })
        .then(res => res.json())
        .then(result => {
          if (result.success) {
            const updatedStatus = newStatus;
            statusCell.textContent = updatedStatus;
            updateStatusButton(btn, updatedStatus);

            const editBtn = row.querySelector('.partner-edit-btn');
            editBtn.dataset.status = updatedStatus;
          } else {
            alert('Lỗi: ' + result.message);
          }
        })
        .catch(err => alert('Có lỗi kết nối: ' + err.message));
    }

    // 🔹 Mở modal edit
    if (e.target.classList.contains('partner-edit-btn')) {
      const rowData = row.querySelectorAll('td');
      const editBtn = e.target;

      editForm.userId.value = editBtn.dataset.userId;
      editForm.fullName.value = rowData[1].textContent;
      editForm.email.value = rowData[2].textContent;
      editForm.password.value = '';
      editForm.phone.value = rowData[4].textContent;
      editForm.status.value = rowData[5].textContent;
      editForm.address.value = rowData[6].textContent;
      editForm.gender.value = rowData[7].textContent;
      editForm.birthDate.value = rowData[8].textContent;
      editForm.avatarUrl.value = rowData[9].textContent;
      editForm.cityId.value = editBtn.dataset.cityId || '';
      editCitySelect.dispatchEvent(new Event('change'));
      editForm.wardId.value = editBtn.dataset.wardId || '';
      editForm.companyName.value = rowData[13].textContent;
      editForm.taxCode.value = rowData[14].textContent;
      editForm.businessLicense.value = rowData[15].textContent;

      editModal.classList.remove('hidden');
    }
  });

  function updateStatusButton(btn, status) {
    if (status === 'BLOCKED') {
      btn.textContent = 'Mở';
      btn.style.backgroundColor = '#479f5c';
    } else if (status === 'ACTIVE') {
      btn.textContent = 'Khóa';
      btn.style.backgroundColor = '#9f3039';
    }
    btn.style.color = '#fff';
  }
  document.querySelectorAll('.partner-toggle-status-btn').forEach(btn => {
    const row = btn.closest('tr');
    const status = row.querySelector('td:nth-child(6)').textContent.trim();
    updateStatusButton(btn, status);
  });






  ///////////////// Thêm đối tác
  const addCitySelect = document.getElementById('addCityId');
  const addWardSelect = document.getElementById('addWardId');

  addCitySelect.addEventListener('change', () => {
    const cityId = addCitySelect.value;
    Array.from(addWardSelect.options).forEach(option => {
      option.style.display = (!option.dataset.cityId || option.dataset.cityId === cityId) ? '' : 'none';
    });
    addWardSelect.value = '';
  });

  ///////////////// Sửa đối tác
  const editCitySelect = document.getElementById('editCityId');
  const editWardSelect = document.getElementById('editWardId');

  editCitySelect.addEventListener('change', () => {
    const cityId = editCitySelect.value;
    Array.from(editWardSelect.options).forEach(option => {
      option.style.display = (!option.dataset.cityId || option.dataset.cityId === cityId) ? '' : 'none';
    });
    editWardSelect.value = '';
  });








  // Add partner form submit
  addForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(addForm));
    try { JSON.parse(data.businessLicense); } catch { alert('Giấy phép kinh doanh phải là JSON hợp lệ!'); return; }

    fetch(`${apiBase}/add`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
      .then(res => res.json())
      .then(result => {
        alert(result.success ? 'Thêm đối tác thành công!' : 'Lỗi: ' + result.message);
        if (result.success) { closeAddModalFunc(); location.reload(); }
      })
      .catch(err => { console.error('Error:', err); alert('Có lỗi xảy ra khi thêm đối tác!'); });
  });




});