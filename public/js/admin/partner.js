document.addEventListener('DOMContentLoaded', () => {
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
  function closeEditModalFunc() {
    editModal.classList.add('hidden');
    editForm.reset();
  }

  // Event listeners for modals
  addBtn.addEventListener('click', () => addModal.classList.remove('hidden'));
  closeAddModal.addEventListener('click', closeAddModalFunc);
  cancelAddBtn.addEventListener('click', closeAddModalFunc);
  closeEditModal.addEventListener('click', closeEditModalFunc);
  cancelEditBtn.addEventListener('click', closeEditModalFunc);

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

  // Attach listeners for edit/delete/approve buttons
  function attachActionListeners() {
    document.querySelectorAll('.partner-edit-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        editModal.classList.remove('hidden');
        // điền dữ liệu vào form sửa
        for (const [key, value] of Object.entries(this.dataset)) {
          const input = document.getElementById('edit' + key.charAt(0).toUpperCase() + key.slice(1));
          if (input) input.value = value;
        }
      });
    });

    document.querySelectorAll('.approve-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const userId = this.dataset.userId;
        if (confirm('Bạn có chắc muốn duyệt đối tác này?')) {
          fetch(`${apiBase}/approve`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId })
          })
            .then(res => res.json())
            .then(result => {
              alert(result.success ? 'Duyệt đối tác thành công!' : 'Lỗi: ' + result.message);
              if (result.success) location.reload();
            });
        }
      });
    });
  }

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

  // Edit partner form submit
  editForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(editForm));
    try { JSON.parse(data.businessLicense); } catch { alert('Giấy phép kinh doanh phải là JSON hợp lệ!'); return; }

    fetch(`${apiBase}/update`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
      .then(res => res.json())
      .then(result => {
        alert(result.success ? 'Cập nhật đối tác thành công!' : 'Lỗi: ' + result.message);
        if (result.success) { closeEditModalFunc(); location.reload(); }
      })
      .catch(err => { console.error('Error:', err); alert('Có lỗi xảy ra khi cập nhật đối tác!'); });
  });



////////////////Đây là hàm cập nhật nút KHÓA MỞ
  document.querySelectorAll('.partner-toggle-status-btn').forEach(btn => {
    btn.addEventListener('click', function () {

      const userId = this.dataset.userId;

      const row = this.closest('tr');
      const statusCell = row.querySelector('td:nth-child(6)');
      const currentStatus = statusCell.textContent.trim(); // ✅ lấy từ UI thật

      const newStatus = currentStatus === 'BLOCKED' ? 'ACTIVE' : 'BLOCKED';
      const actionText = newStatus === 'BLOCKED' ? 'khóa' : 'mở lại';

      if (!confirm(`Bạn có chắc muốn ${actionText} đối tác này?`)) return;

      // 👉 disable nút để tránh spam click
      this.disabled = true;

      fetch(`${apiBase}/toggleStatus`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ userId, status: newStatus })
      })
        .then(res => res.json())
        .then(result => {
          console.log(result); // 🔥 debug

          if (result.success) {

            // ✅ LẤY STATUS TỪ SERVER (quan trọng)
            const updatedStatus = result.newStatus;

            alert(`Đã ${actionText} đối tác thành công!`);

            // update UI theo server
            statusCell.textContent = updatedStatus;
            this.textContent = updatedStatus === 'BLOCKED' ? 'Mở' : 'Khóa';

            // màu
            if (updatedStatus === 'BLOCKED') {
              this.style.backgroundColor = '#479f5c'; // xanh
              this.style.color = '#fff';

            } else {
              this.style.backgroundColor = '#9f3039'; // đỏ
              this.style.color = '#fff';
            }
            filterTable();

          } else {
            alert('Lỗi: ' + result.message);
          }
        })
        .catch(err => {
          alert('Có lỗi kết nối: ' + err.message);
        })
        .finally(() => {
          this.disabled = false;
        });
    });
  });



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










  // Initial attach listeners
  attachActionListeners();
});