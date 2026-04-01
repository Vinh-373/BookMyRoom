document.addEventListener('DOMContentLoaded', () => {

  const searchInput_staff = document.getElementById('staffsSearchInput');
  const statusFilter_staff = document.getElementById('staffsStatusFilter');
  const addForm_staff = document.getElementById('addStaffsForm');
  const editForm_staff = document.getElementById('editStaffsForm');


  const addModal_staff = document.getElementById('addStaffsModal');
  const editModal_staff = document.getElementById('editStaffsModal');
  const addBtn_staff = document.querySelector('.staffs-btn-add-staff');
  const closeAddModal_staff = document.getElementById('closeModal_staff');
  const closeEditModal_staff = document.getElementById('closeEditModal_staff');
  const cancelAddBtn_staff = document.getElementById('cancelBtn_staff');
  const cancelEditBtn_staff = document.getElementById('cancelEditBtn_staff');


  // Base API path
  const currentPath_staff = window.location.pathname.replace(/\/$/, '');
  const apiBase_staff = currentPath_staff.endsWith('/staffs') ? currentPath_staff : currentPath_staff + '/staffs';
  // Escape HTML helper
  function escapeHtml_staff(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  // Modal functions
  function closeAddModalFunc_staff() {
    addModal_staff.classList.add('hidden');
    addForm_staff.reset();
  }

  // Event listeners for modals
  addBtn_staff.addEventListener('click', () => addModal_staff.classList.remove('hidden'));
  closeAddModal_staff.addEventListener('click', closeAddModalFunc_staff);
  cancelAddBtn_staff.addEventListener('click', closeAddModalFunc_staff);
  ;

  // // Close modals khi click ra ngoài
  // [addModal_staff, editModal_staff].forEach(modal_staff => {
  //   modal_staff.addEventListener('click', e => {
  //     if (e.target === modal_staff) {
  //       modal_staff.classList.add('hidden');
  //       (modal_staff === addModal_staff ? addForm_staff: editForm_staff).reset();
  //     }
  //   });
  // });

  // Update stats
  function updateStats_staff(staffs) {
    const total = staffs.length;
    const active = staffs.filter(p => p.status === 'ACTIVE').length;
    const pending = staffs.filter(p => p.status === 'PENDING').length;
    const blocked = staffs.filter(p => p.status === 'BLOCKED').length;
    document.querySelector('.staffs-sum-all').textContent = `${total} Tổng nhân viên`;
    document.querySelector('.staffs-sum-active').textContent = `${active} Đang làm việc`;
    document.querySelector('.staffs-sum-pending').textContent = `${pending} Nghỉ việc`;
    document.querySelector('.staffs-sum-blocked').textContent = `${blocked} Đang khóa`;
  }







  //////////////////////////Tìm kiếm///////////////////
  //////////////////// Hàm thực hiện lọc bảng
  function filterTable_staff() {
    //////////////// Lấy tất cả các hàng <tr> trong tbody của bảng
    const rows = document.querySelectorAll('#staffsTableBody tr');
    // Lấy từ khóa nhập vào, chuyển thành chữ thường để so sánh
    const keyword = searchInput_staff.value.toLowerCase();
    // Lấy trạng thái được chọn trong dropdown
    const statusSelected = statusFilter_staff.value;

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
  searchInput_staff.addEventListener('input', filterTable_staff);
  // Gắn sự kiện: khi chọn trạng thái thì lọc ngay
  statusFilter_staff.addEventListener('change', filterTable_staff);


  // 🔹 Hàm đóng modal
  function closeEditModalFunc_staff() {
    editModal_staff.classList.add('hidden');
    editForm_staff.reset();
  }
  closeEditModal_staff.addEventListener('click', closeEditModalFunc_staff);
  cancelEditBtn_staff.addEventListener('click', closeEditModalFunc_staff);



  // ================= Edit Form =================
  editForm_staff.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = Object.fromEntries(new FormData(editForm_staff));
    console.log('DATA:', formData);


    fetch(`${apiBase_staff}/update_staff`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id: formData.id,
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
        wardId: formData.wardId || null
      })
    })
      .then(res => res.json())
      .then(result => {
        console.log('RESULT:', result);

        if (result.success) {
          const updated = result.updatedUser;

          // Lấy row trong bảng
          const row = document.querySelector(
            `.staff-edit-btn[data-user-id="${formData.id}"]`
          ).closest('tr');

          // Update DOM bảng
          row.querySelector('td:nth-child(2)').textContent = updated.fullName;
          row.querySelector('td:nth-child(3)').textContent = updated.email;
          row.querySelector('td:nth-child(4)').textContent = '••••••••'; // password ẩn
          row.querySelector('td:nth-child(5)').textContent = updated.phone || '';
          row.querySelector('td:nth-child(6)').textContent = updated.status;
          const btn = row.querySelector('.staff-toggle-status-btn');
          updateStatusButton_staff(btn, updated.status);
          row.querySelector('td:nth-child(7)').textContent = updated.address || '';
          row.querySelector('td:nth-child(8)').textContent = updated.gender || '';

          // format ngày sinh
          row.querySelector('td:nth-child(9)').textContent = updated.birthDate || '';

          row.querySelector('td:nth-child(10)').textContent = updated.avatarUrl || '';
          row.querySelector('td:nth-child(11)').textContent = updated.cityName || '';
          row.querySelector('td:nth-child(12)').textContent = updated.wardName || '';

          closeEditModalFunc_staff();
          filterTable_staff();
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


  /////////////////////////////////////////////




  // ================= ĐIỀN FORM KHI CLICK SỬA =================
  document.getElementById('staffsTableBody').addEventListener('click', function (e) {
    const row = e.target.closest('tr'); // luôn lấy row từ click target
    if (!row) return;



    // 🔹 Duyệt nhân viên
    if (e.target.classList.contains('staffs-approve-btn')) {
      const id = e.target.dataset.userId;
      if (!confirm('Bạn có chắc muốn duyệt nhân viên này?')) return;

      fetch(`${apiBase_staff}/approve_staff`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      })
        .then(res => res.json())
        .then(result => {
          if (result.success) {
            // Update DOM row
            row.querySelector('td:nth-child(6)').textContent = 'ACTIVE'; // cột status
            const actionCell = e.target.parentElement;
            actionCell.innerHTML = `
          <button class="staff-edit-btn" data-user-id="${id}">Sửa</button>
          <button class="staff-toggle-status-btn" data-user-id="${id}">Khóa</button>
        `;

            // Cập nhật nút toggle màu
            const toggleBtn = actionCell.querySelector('.staff-toggle-status-btn');
            updateStatusButton_staff(toggleBtn, 'ACTIVE');

            alert('Duyệt nhân viên thành công!');
          } else {
            alert(result.message || 'Duyệt thất bại!');
          }
        })
        .catch(err => alert('Có lỗi xảy ra: ' + err.message));
    }



    // 🔹 Toggle status
    if (e.target.classList.contains('staff-toggle-status-btn')) {
      const btn = e.target;
      const id = btn.dataset.userId;
      const statusCell = row.querySelector('td:nth-child(6)');
      const currentStatus = statusCell.textContent.trim();
      const newStatus = currentStatus === 'BLOCKED' ? 'ACTIVE' : 'BLOCKED';

      if (!confirm(`Bạn có chắc muốn ${newStatus === 'BLOCKED' ? 'khóa' : 'mở'} nhân viên này?`)) return;

      fetch(`${apiBase_staff}/toggleStatus_staff`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, status: newStatus })
      })
        .then(res => res.json())
        .then(result => {
          if (result.success) {
            const updatedStatus = newStatus;
            statusCell.textContent = updatedStatus;
            updateStatusButton_staff(btn, updatedStatus);

            const editBtn = row.querySelector('.staff-edit-btn');
            editBtn.dataset.status = updatedStatus;
          } else {
            alert('Lỗi: ' + result.message);
          }
        })
        .catch(err => alert('Có lỗi kết nối: ' + err.message));
    }

    // 🔹 Mở modal edit
    if (e.target.classList.contains('staff-edit-btn')) {
      const rowData = row.querySelectorAll('td');
      const editBtn = e.target;

      editForm_staff.id.value = editBtn.dataset.userId;
      editForm_staff.fullName.value = rowData[1].textContent;
      editForm_staff.email.value = rowData[2].textContent;
      editForm_staff.password.value = '';
      editForm_staff.phone.value = rowData[4].textContent;
      editForm_staff.status.value = rowData[5].textContent;
      editForm_staff.address.value = rowData[6].textContent;
      editForm_staff.gender.value = rowData[7].textContent;
      editForm_staff.birthDate.value = rowData[8].textContent;
      editForm_staff.avatarUrl.value = rowData[9].textContent;
      editForm_staff.cityId.value = editBtn.dataset.cityId || '';
      editCitySelect_staff.dispatchEvent(new Event('change'));
      editForm_staff.wardId.value = editBtn.dataset.wardId || '';
      editForm_staff.createdAt.value = rowData[12].textContent;

      editModal_staff.classList.remove('hidden');
    }
  });



  function updateStatusButton_staff(btn, status) {
    if (status === 'BLOCKED') {
      btn.textContent = 'Mở';
      btn.style.backgroundColor = '#479f5c';
    } else if (status === 'ACTIVE') {
      btn.textContent = 'Khóa';
      btn.style.backgroundColor = '#9f3039';
    }
    btn.style.color = '#fff';
  }
  document.querySelectorAll('.staff-toggle-status-btn').forEach(btn => {
    const row = btn.closest('tr');
    const status = row.querySelector('td:nth-child(6)').textContent.trim();
    updateStatusButton_staff(btn, status);
  });





  ///////////////// Thêm thành phố 
  const addCitySelect_staff = document.getElementById('addCityId');
  const addWardSelect_staff = document.getElementById('addWardId');

  addCitySelect_staff.addEventListener('change', () => {
    const cityId = addCitySelect_staff.value;
    Array.from(addWardSelect_staff.options).forEach(option => {
      option.style.display = (!option.dataset.cityId || option.dataset.cityId === cityId) ? '' : 'none';
    });
    addWardSelect_staff.value = '';
  });

  ///////////////// Sửa tp
  const editCitySelect_staff = document.getElementById('editCityId');
  const editWardSelect_staff = document.getElementById('editWardId');

  editCitySelect_staff.addEventListener('change', () => {
    const cityId = editCitySelect_staff.value;
    Array.from(editWardSelect_staff.options).forEach(option => {
      option.style.display = (!option.dataset.cityId || option.dataset.cityId === cityId) ? '' : 'none';
    });
    editWardSelect_staff.value = '';
  });


  // Add staff form submit
  addForm_staff.addEventListener('submit', function (e) {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(addForm_staff));

    fetch(`${apiBase_staff}/add_staff`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
      .then(res => res.json())
      .then(result => {
        alert(result.success ? 'Thêm nhân viên thành công!' : 'Lỗi: ' + result.message);
        if (result.success) { closeAddModalFunc_staff(); location.reload(); }
      })
      .catch(err => { console.error('Error:', err); alert('Có lỗi xảy ra khi thêm nhân viên!'); });
  });








});