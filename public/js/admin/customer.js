document.addEventListener('DOMContentLoaded', () => {

  // ===== DATA GỐC =====
  let customersData = window.customersData || [];

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

  const currentPath_customer = window.location.pathname.replace(/\/$/, '');
  const apiBase_customer = currentPath_customer.endsWith('/customers') ? currentPath_customer : currentPath_customer + '/customers';

  // ===== MODAL =====
  function closeAddModalFunc_customer() {
    addModal_customer.classList.add('hidden');
    addForm_customer.reset();
  }

  function closeEditModalFunc_customer() {
    editModal_customer.classList.add('hidden');
    editForm_customer.reset();
  }

  addBtn_customer.addEventListener('click', () => addModal_customer.classList.remove('hidden'));
  closeAddModal_customer.addEventListener('click', closeAddModalFunc_customer);
  cancelAddBtn_customer.addEventListener('click', closeAddModalFunc_customer);
  closeEditModal_customer.addEventListener('click', closeEditModalFunc_customer);
  cancelEditBtn_customer.addEventListener('click', closeEditModalFunc_customer);

  // ===== STATS =====
  function updateStats_customer() {
    const total = customersData.length;
    const active = customersData.filter(c => c.status === 'ACTIVE').length;
    const pending = customersData.filter(c => c.status === 'PENDING').length;
    const blocked = customersData.filter(c => c.status === 'BLOCKED').length;

    document.querySelector('.customers-sum-all').textContent = `${total} Tổng khách hàng`;
    document.querySelector('.customers-sum-active').textContent = `${active} Đang hoạt động`;
    document.querySelector('.customers-sum-pending').textContent = `${pending} Chờ duyệt`;
    document.querySelector('.customers-sum-blocked').textContent = `${blocked} Bị khóa`;
  }

  // ===== FILTER =====
  function filterTable_customer() {
    const rows = document.querySelectorAll('#customersTableBody tr');
    const keyword = searchInput_customer.value.toLowerCase();
    const statusSelected = statusFilter_customer.value;

    rows.forEach(row => {
      const fullName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
      const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
      const phone = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
      const status = row.querySelector('td:nth-child(6)').textContent.trim();

      const matchKeyword = fullName.includes(keyword) || email.includes(keyword) || phone.includes(keyword);
      const matchStatus = !statusSelected || status === statusSelected;

      row.style.display = (matchKeyword && matchStatus) ? '' : 'none';
    });
  }

  searchInput_customer.addEventListener('input', filterTable_customer);
  statusFilter_customer.addEventListener('change', filterTable_customer);

  // ===== BUTTON STATUS =====
  function updateStatusButton_customer(btn, status) {
    if (status === 'BLOCKED') {
      btn.textContent = 'Mở';
      btn.style.backgroundColor = '#479f5c';
    } else {
      btn.textContent = 'Khóa';
      btn.style.backgroundColor = '#9f3039';
    }
    btn.style.color = '#fff';
  }

  // ===== CLICK TABLE =====
  document.getElementById('customersTableBody').addEventListener('click', function (e) {
    const row = e.target.closest('tr');
    if (!row) return;

    // ===== APPROVE =====
    if (e.target.classList.contains('customers-approve-btn')) {
      const id = e.target.dataset.userId;
      if (!confirm('Bạn có chắc muốn duyệt khách hàng này?')) return;

      fetch(`${apiBase_customer}/approve_customer`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      })
        .then(res => res.json())
        .then(result => {
          if (result.success) {

            row.querySelector('td:nth-child(6)').textContent = 'ACTIVE';

            const staff = customersData.find(c => c.id == id);
            if (staff) staff.status = 'ACTIVE';

            updateStats_customer();
          }
        });
    }

    // ===== TOGGLE STATUS =====
    if (e.target.classList.contains('customer-toggle-status-btn')) {
      const btn = e.target;
      const id = btn.dataset.userId;
      const statusCell = row.querySelector('td:nth-child(6)');
      const currentStatus = statusCell.textContent.trim();
      const newStatus = currentStatus === 'BLOCKED' ? 'ACTIVE' : 'BLOCKED';

      if (!confirm(`Bạn có chắc muốn ${newStatus === 'BLOCKED' ? 'khóa' : 'mở'} khách hàng này?`)) return;

      fetch(`${apiBase_customer}/toggleStatus_customer`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, status: newStatus })
      })
        .then(res => res.json())
        .then(result => {
          if (result.success) {

            statusCell.textContent = newStatus;
            updateStatusButton_customer(btn, newStatus);

            const staff = customersData.find(c => c.id == id);
            if (staff) staff.status = newStatus;

            updateStats_customer();
          }
        });
    }

    // ===== EDIT =====
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
      editForm_customer.createdAt.value = rowData[12].textContent;

      editModal_customer.classList.remove('hidden');
    }
  });

  // ===== UPDATE EDIT =====
  editForm_customer.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = Object.fromEntries(new FormData(editForm_customer));

    fetch(`${apiBase_customer}/update_customer`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(formData)
    })
      .then(res => res.json())
      .then(result => {
        if (result.success) {
          const updated = result.updatedUser;

          const row = document.querySelector(
            `.customer-edit-btn[data-user-id="${formData.id}"]`
          ).closest('tr');

          row.querySelector('td:nth-child(2)').textContent = updated.fullName;
          row.querySelector('td:nth-child(3)').textContent = updated.email;
          row.querySelector('td:nth-child(5)').textContent = updated.phone || '';
          row.querySelector('td:nth-child(6)').textContent = updated.status;

          const btn = row.querySelector('.customer-toggle-status-btn');
          updateStatusButton_customer(btn, updated.status);

          // update data gốc
          const staff = customersData.find(c => c.id == formData.id);
          if (staff) Object.assign(staff, updated);

          updateStats_customer();

          closeEditModalFunc_customer();
          alert('Cập nhật thành công!');
        }
      });
  });

  // ===== INIT =====
  document.querySelectorAll('.customer-toggle-status-btn').forEach(btn => {
    const row = btn.closest('tr');
    const status = row.querySelector('td:nth-child(6)').textContent.trim();
    updateStatusButton_customer(btn, status);
  });

  updateStats_customer();

});