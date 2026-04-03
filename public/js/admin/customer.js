document.addEventListener('DOMContentLoaded', () => {

  const searchInput_customer = document.getElementById('customersSearchInput');
  const statusFilter_customer = document.getElementById('customersStatusFilter');
  const addForm_customer = document.getElementById('addCustomersForm');
  const editForm_customer = document.getElementById('editCustomersForm');


  const addModal_customer = document.getElementById('addCustomersModal');
  const editModal_customer = document.getElementById('editCustomersModal');
  const addBtn_customer = document.querySelector('.customers-btn-add-customer');
  const closeAddModal_customer = document.getElementById('closeAddModal_customer');
  const closeEditModal_customer = document.getElementById('closeEditModal_customer');
  const cancelAddBtn_customer = document.getElementById('cancelBtn_customer');
  const cancelEditBtn_customer = document.getElementById('cancelEditBtn_customer');


  // Base API path
  const currentPath_customer = window.location.pathname.replace(/\/$/, '');
  const apiBase_customer = currentPath_customer.endsWith('/customers') ? currentPath_customer : currentPath_customer + '/customers';
  // Escape HTML helper
  function escapeHtml_customer(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  // Modal functions
  function closeAddModalFunc_customer() {
    addModal_customer.classList.add('hidden');
    addForm_customer.reset();
  }

  // Event listeners for modals
  addBtn_customer.addEventListener('click', () => addModal_customer.classList.remove('hidden'));
  closeAddModal_customer.addEventListener('click', closeAddModalFunc_customer);
  cancelAddBtn_customer.addEventListener('click', closeAddModalFunc_customer);
  ;

  // // Close modals khi click ra ngoài
  // [addModal_customer, editModal_customer].forEach(modal_customer => {
  //   modal_customer.addEventListener('click', e => {
  //     if (e.target === modal_customer) {
  //       modal_customer.classList.add('hidden');
  //       (modal_customer === addModal_customer ? addForm_customer: editForm_customer).reset();
  //     }
  //   });
  // });

  // Update stats
  function updateStats_customer(customers) {
    const total = customers.length;
    const active = customers.filter(p => p.status === 'ACTIVE').length;
    const pending = customers.filter(p => p.status === 'PENDING').length;
    const blocked = customers.filter(p => p.status === 'BLOCKED').length;
    document.querySelector('.customers-sum-all').textContent = `${total} Tổng nhân viên`;
    document.querySelector('.customers-sum-active').textContent = `${active} Đang làm việc`;
    document.querySelector('.customers-sum-pending').textContent = `${pending} Nghỉ việc`;
    document.querySelector('.customers-sum-blocked').textContent = `${blocked} Đang khóa`;
  }







  //////////////////////////Tìm kiếm///////////////////
  //////////////////// Hàm thực hiện lọc bảng
  function filterTable_customer() {
    //////////////// Lấy tất cả các hàng <tr> trong tbody của bảng
    const rows = document.querySelectorAll('#customersTableBody tr');
    // Lấy từ khóa nhập vào, chuyển thành chữ thường để so sánh
    const keyword = searchInput_customer.value.toLowerCase();
    // Lấy trạng thái được chọn trong dropdown
    const statusSelected = statusFilter_customer.value;

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
  searchInput_customer.addEventListener('input', filterTable_customer);
  // Gắn sự kiện: khi chọn trạng thái thì lọc ngay
  statusFilter_customer.addEventListener('change', filterTable_customer);


  // 🔹 Hàm đóng modal
  function closeEditModalFunc_customer() {
    editModal_customer.classList.add('hidden');
    editForm_customer.reset();
  }
  closeEditModal_customer.addEventListener('click', closeEditModalFunc_customer);
  cancelEditBtn_customer.addEventListener('click', closeEditModalFunc_customer);



  // ================= Edit Form =================
  editForm_customer.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = Object.fromEntries(new FormData(editForm_customer));
    console.log('DATA:', formData);


    fetch(`${apiBase_customer}/update_customer`, {
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
            `.customer-edit-btn[data-user-id="${formData.id}"]`
          ).closest('tr');

          // Update DOM bảng
          row.querySelector('td:nth-child(2)').textContent = updated.fullName;
          row.querySelector('td:nth-child(3)').textContent = updated.email;
          row.querySelector('td:nth-child(4)').textContent = '••••••••'; // password ẩn
          row.querySelector('td:nth-child(5)').textContent = updated.phone || '';
          row.querySelector('td:nth-child(6)').textContent = updated.status;
          const btn = row.querySelector('.customer-toggle-status-btn');
          updateStatusButton_customer(btn, updated.status);
          row.querySelector('td:nth-child(7)').textContent = updated.address || '';
          row.querySelector('td:nth-child(8)').textContent = updated.gender || '';

          // format ngày sinh
          row.querySelector('td:nth-child(9)').textContent = updated.birthDate || '';

          row.querySelector('td:nth-child(10)').textContent = updated.avatarUrl || '';
          row.querySelector('td:nth-child(11)').textContent = updated.cityName || '';
          row.querySelector('td:nth-child(12)').textContent = updated.wardName || '';

          closeEditModalFunc_customer();
          filterTable_customer();
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
  document.getElementById('customersTableBody').addEventListener('click', function (e) {
    const row = e.target.closest('tr'); // luôn lấy row từ click target
    if (!row) return;



    // 🔹 Duyệt nhân viên
    if (e.target.classList.contains('customers-approve-btn')) {
      const id = e.target.dataset.userId;
      if (!confirm('Bạn có chắc muốn duyệt nhân viên này?')) return;

      fetch(`${apiBase_customer}/approve_customer`, {
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
          <button class="customer-edit-btn" data-user-id="${id}">Sửa</button>
          <button class="customer-toggle-status-btn" data-user-id="${id}">Khóa</button>
        `;

            // Cập nhật nút toggle màu
            const toggleBtn = actionCell.querySelector('.customer-toggle-status-btn');
            updateStatusButton_customer(toggleBtn, 'ACTIVE');

            alert('Duyệt nhân viên thành công!');
          } else {
            alert(result.message || 'Duyệt thất bại!');
          }
        })
        .catch(err => alert('Có lỗi xảy ra: ' + err.message));
    }



    // 🔹 Toggle status
    if (e.target.classList.contains('customer-toggle-status-btn')) {
      const btn = e.target;
      const id = btn.dataset.userId;
      const statusCell = row.querySelector('td:nth-child(6)');
      const currentStatus = statusCell.textContent.trim();
      const newStatus = currentStatus === 'BLOCKED' ? 'ACTIVE' : 'BLOCKED';

      if (!confirm(`Bạn có chắc muốn ${newStatus === 'BLOCKED' ? 'khóa' : 'mở'} nhân viên này?`)) return;

      fetch(`${apiBase_customer}/toggleStatus_customer`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, status: newStatus })
      })
        .then(res => res.json())
        .then(result => {
          if (result.success) {
            const updatedStatus = newStatus;
            statusCell.textContent = updatedStatus;
            updateStatusButton_customer(btn, updatedStatus);

            const editBtn = row.querySelector('.customer-edit-btn');
            editBtn.dataset.status = updatedStatus;
          } else {
            alert('Lỗi: ' + result.message);
          }
        })
        .catch(err => alert('Có lỗi kết nối: ' + err.message));
    }

    // 🔹 Mở modal edit
    if (e.target.classList.contains('customer-edit-btn')) {
      const rowData = row.querySelectorAll('td');
      const editBtn = e.target;

      editForm_customer.id.value = editBtn.dataset.userId;
      editForm_customer.fullName.value = rowData[1].textContent;
      editForm_customer.email.value = rowData[2].textContent;
      editForm_customer.password.value = '';
      editForm_customer.phone.value = rowData[4].textContent;
      editForm_customer.status.value = rowData[5].textContent;
      editForm_customer.address.value = rowData[6].textContent;
      editForm_customer.gender.value = rowData[7].textContent;
      editForm_customer.birthDate.value = rowData[8].textContent;
      editForm_customer.avatarUrl.value = rowData[9].textContent;
      editForm_customer.cityId.value = editBtn.dataset.cityId || '';
      editCitySelect_customer.dispatchEvent(new Event('change'));
      editForm_customer.wardId.value = editBtn.dataset.wardId || '';
      editForm_customer.createdAt.value = rowData[12].textContent;

      editModal_customer.classList.remove('hidden');
    }
  });



  function updateStatusButton_customer(btn, status) {
    if (status === 'BLOCKED') {
      btn.textContent = 'Mở';
      btn.style.backgroundColor = '#479f5c';
    } else if (status === 'ACTIVE') {
      btn.textContent = 'Khóa';
      btn.style.backgroundColor = '#9f3039';
    }
    btn.style.color = '#fff';
  }
  document.querySelectorAll('.customer-toggle-status-btn').forEach(btn => {
    const row = btn.closest('tr');
    const status = row.querySelector('td:nth-child(6)').textContent.trim();
    updateStatusButton_customer(btn, status);
  });





  ///////////////// Thêm thành phố 
  const addCitySelect_customer = document.getElementById('addCityId');
  const addWardSelect_customer = document.getElementById('addWardId');

  addCitySelect_customer.addEventListener('change', () => {
    const cityId = addCitySelect_customer.value;
    Array.from(addWardSelect_customer.options).forEach(option => {
      option.style.display = (!option.dataset.cityId || option.dataset.cityId === cityId) ? '' : 'none';
    });
    addWardSelect_customer.value = '';
  });

  ///////////////// Sửa tp
  const editCitySelect_customer = document.getElementById('editCityId');
  const editWardSelect_customer = document.getElementById('editWardId');

  editCitySelect_customer.addEventListener('change', () => {
    const cityId = editCitySelect_customer.value;
    Array.from(editWardSelect_customer.options).forEach(option => {
      option.style.display = (!option.dataset.cityId || option.dataset.cityId === cityId) ? '' : 'none';
    });
    editWardSelect_customer.value = '';
  });


  // Add customer form submit
  addForm_customer.addEventListener('submit', function (e) {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(addForm_customer));

    fetch(`${apiBase_customer}/add_customer`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
      .then(res => res.json())
      .then(result => {
        alert(result.success ? 'Thêm nhân viên thành công!' : 'Lỗi: ' + result.message);
        if (result.success) { closeAddModalFunc_customer(); location.reload(); }
      })
      .catch(err => { console.error('Error:', err); alert('Có lỗi xảy ra khi thêm nhân viên!'); });
  });








});