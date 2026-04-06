document.addEventListener('DOMContentLoaded', () => {

  // ===== DATA GỐC =====
  let staffsData = window.staffsData || []; // lấy từ PHP
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

  const currentPath_staff = window.location.pathname.replace(/\/$/, '');
  const apiBase_staff = currentPath_staff.endsWith('/staffs') ? currentPath_staff : currentPath_staff + '/staffs';

  // ===== MODAL =====
  function closeAddModalFunc_staff() {
    addModal_staff.classList.add('hidden');
    addForm_staff.reset();
  }

  function closeEditModalFunc_staff() {
    editModal_staff.classList.add('hidden');
    editForm_staff.reset();
  }

  addBtn_staff.addEventListener('click', () => addModal_staff.classList.remove('hidden'));
  closeAddModal_staff.addEventListener('click', closeAddModalFunc_staff);
  cancelAddBtn_staff.addEventListener('click', closeAddModalFunc_staff);
  closeEditModal_staff.addEventListener('click', closeEditModalFunc_staff);
  cancelEditBtn_staff.addEventListener('click', closeEditModalFunc_staff);

  // ===== STATS (CHUẨN THEO DATA) =====
  function recalcStats_staff() {
    const total = staffsData.length;
    const active = staffsData.filter(s => s.status === 'ACTIVE').length;
    const pending = staffsData.filter(s => s.status === 'PENDING').length;
    const blocked = staffsData.filter(s => s.status === 'BLOCKED').length;

    document.querySelector('.staffs-sum-all').textContent = `${total} Tổng nhân viên`;
    document.querySelector('.staffs-sum-active').textContent = `${active} Đang làm việc`;
    document.querySelector('.staffs-sum-pending').textContent = `${pending} Nghỉ việc`;
    document.querySelector('.staffs-sum-blocked').textContent = `${blocked} Đang khóa`;
  }

  // ===== FILTER UI =====
  function filterTable_staff() {
    const rows = document.querySelectorAll('#staffsTableBody tr');
    const keyword = searchInput_staff.value.toLowerCase();
    const statusSelected = statusFilter_staff.value;

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

  searchInput_staff.addEventListener('input', filterTable_staff);
  statusFilter_staff.addEventListener('change', filterTable_staff);

  // ===== UPDATE STATUS BUTTON =====
  function updateStatusButton_staff(btn, status) {
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
  document.getElementById('staffsTableBody').addEventListener('click', function (e) {
    const row = e.target.closest('tr');
    if (!row) return;

    // ===== TOGGLE STATUS =====
    if (e.target.classList.contains('staff-toggle-status-btn')) {
      const btn = e.target;
      const id = btn.dataset.userId;
      const statusCell = row.querySelector('td:nth-child(6)');
      const currentStatus = statusCell.textContent.trim();
      const newStatus = currentStatus === 'BLOCKED' ? 'ACTIVE' : 'BLOCKED';
      // ✅ THÊM CONFIRM Ở ĐÂY
      const isConfirm = confirm(
        newStatus === 'BLOCKED'
          ? 'Bạn có chắc muốn KHÓA nhân viên này?'
          : 'Bạn có chắc muốn MỞ khóa nhân viên này?'
      );

      if (!isConfirm) return; // ❌ nếu bấm Cancel thì dừng
      fetch(`${apiBase_staff}/toggleStatus_staff`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, status: newStatus })
      })
        .then(res => res.json())
        .then(result => {
          if (result.success) {

            // update UI
            statusCell.textContent = newStatus;
            updateStatusButton_staff(btn, newStatus);

            // update DATA
            const staff = staffsData.find(s => s.id == id);
            if (staff) staff.status = newStatus;

            recalcStats_staff(); // 👈 CHUẨN

          } else {
            alert(result.message);
          }
        });
    }

    // ===== APPROVE =====
    if (e.target.classList.contains('staffs-approve-btn')) {
      const id = e.target.dataset.userId;

      fetch(`${apiBase_staff}/approve_staff`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      })
        .then(res => res.json())
        .then(result => {
          if (result.success) {

            row.querySelector('td:nth-child(6)').textContent = 'ACTIVE';

            const staff = staffsData.find(s => s.id == id);
            if (staff) staff.status = 'ACTIVE';

            recalcStats_staff();
          }
        });
    }
  });

  // ===== INIT =====
  recalcStats_staff();

});