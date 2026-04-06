document.addEventListener('DOMContentLoaded', function () {


  const editForm_voucher = document.getElementById('vouchers-edit-form');



  // Base API path
  const currentPath = window.location.pathname.replace(/\/$/, '');
  const apiBase = currentPath.endsWith('/vouchers') ? currentPath : currentPath + '/vouchers';








  const addModal_voucher = document.getElementById('vouchers-add-modal');
  const addForm_voucher = document.getElementById('vouchers-add-form');

  document.querySelector('.vouchers-add-btn').addEventListener('click', function () {
    // reset formaddForm_voucher_voucher
    addForm_voucher.reset();
    addModal_voucher.style.display = 'flex';
  });

  // đóng modal
  document.getElementById('vouchers-btn-add-x').addEventListener('click', function () {
    addModal_voucher.style.display = 'none';
  });







  addForm_voucher.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = Object.fromEntries(new FormData(addForm_voucher));
    const currentPath = window.location.pathname.replace(/\/$/, '');
    const apiBase = currentPath.endsWith('/vouchers') ? currentPath : currentPath + '/vouchers';

    fetch(`${apiBase}/create_voucher`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(formData)
    })
      .then(res => res.json())
      .then(result => {
        if (result.success) {
          const voucher = result.voucher;

          // thêm row vào bảng
          const tbody = document.querySelector('.vouchers-table tbody');
          const row = document.createElement('tr');
          row.innerHTML = `
        <td>${voucher.id}</td>
        <td>${voucher.code}</td>
        <td>${voucher.quantity}</td>
        <td>${voucher.type} ${voucher.type === 'PERCENT' ? '(%)' : '(đ)'}</td>
        <td>${voucher.amount}</td>
        <td>${voucher.condition}</td>
        <td>${voucher.startDate} đến ${voucher.endDate}</td>
        <td><span class="vouchers-status ${voucher.endDate < new Date().toISOString().split('T')[0] ? 'expired' : 'active'}">${voucher.endDate < new Date().toISOString().split('T')[0] ? 'Hết hạn' : 'Còn hạn'}</span></td>
        <td>
          <button class="vouchers-btn-edit" data-id="${voucher.id}">Sửa</button>
          <button class="vouchers-btn-delete" data-id="${voucher.id}">Xóa</button>
        </td>
      `;
          tbody.appendChild(row);
          // cập nhật tổng số voucher
          document.querySelector('.vouchers-total').textContent = `Tổng số voucher ${tbody.children.length}`;

          addModal_voucher.style.display = 'none';
          alert('Thêm voucher thành công!');
        } else {
          alert(result.message || 'Thêm voucher thất bại!');
        }
      })
      .catch(err => {
        console.error('ERROR:', err);
        alert('Có lỗi xảy ra khi thêm voucher!');
      });
      console.log(formData);

  });


















  editForm_voucher.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = Object.fromEntries(new FormData(editForm_voucher));
    console.log('DATA:', formData);

    fetch(`${apiBase}/update_voucher`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id: formData.id,
        code: formData.code,
        quantity: formData.quantity,
        type: formData.type,
        amount: formData.amount,
        condition: formData.condition,
        startDate: document.getElementById('vouchers-edit-startDate-hidden').value,
        endDate: document.getElementById('vouchers-edit-endDate-hidden').value
      })
    })
      .then(res => res.json())
      .then(result => {
        console.log('RESULT:', result);

        if (result.success) {
          const updated = result.voucher;

          // 🔥 Tìm đúng row
          const row = document.querySelector(
            `.vouchers-btn-edit[data-id="${updated.id}"]`
          ).closest('tr');

          // 🔥 Update lại bảng
          row.querySelector('td:nth-child(2)').textContent = updated.code;
          row.querySelector('td:nth-child(3)').textContent = updated.quantity;

          row.querySelector('td:nth-child(4)').textContent =
            updated.type + (updated.type === 'PERCENT' ? ' (%)' : ' (đ)');

          row.querySelector('td:nth-child(5)').textContent = updated.amount;
          row.querySelector('td:nth-child(6)').textContent = updated.condition;

          row.querySelector('td:nth-child(7)').textContent =
            updated.startDate + ' đến ' + updated.endDate;

          // 🔥 Update trạng thái
          const today = new Date().toISOString().split('T')[0];
          const statusCell = row.querySelector('td:nth-child(8)');

          if (updated.endDate < today) {
            statusCell.innerHTML = '<span class="vouchers-status expired">Hết hạn</span>';
          } else {
            statusCell.innerHTML = '<span class="vouchers-status active">Còn hạn</span>';
          }

          // đóng modal
          document.getElementById('vouchers-edit-modal').style.display = 'none';

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


  document.getElementById('vouchers-btn-x').addEventListener('click', function () {
    document.getElementById('vouchers-edit-modal').style.display = 'none';
  });



  document.querySelectorAll('.vouchers-btn-edit').forEach(btn => {
    btn.addEventListener('click', function () {
      const id = this.dataset.id;
      const row = this.closest('tr');

      // Điền dữ liệu vào formaddForm_voucher_voucher
      document.getElementById('vouchers-edit-id').value = id;
      document.getElementById('vouchers-edit-code').value = row.querySelector('td:nth-child(2)').textContent;
      document.getElementById('vouchers-edit-quantity').value = row.querySelector('td:nth-child(3)').textContent;
      const typeText = row.querySelector('td:nth-child(4)').textContent;
      document.getElementById('vouchers-edit-type').value = typeText.includes('PERCENT') ? 'PERCENT' : 'FIXED';
      document.getElementById('vouchers-edit-amount').value = row.querySelector('td:nth-child(5)').textContent;
      document.getElementById('vouchers-edit-condition').value = row.querySelector('td:nth-child(6)').textContent;
      const [start, end] = row.querySelector('td:nth-child(7)').textContent.split(' đến ');
      const startVal = start.trim();
      const endVal = end.trim();

      // hiển thị
      document.getElementById('vouchers-edit-startDate').value = startVal;
      document.getElementById('vouchers-edit-endDate').value = endVal;

      // gửi lên server
      document.getElementById('vouchers-edit-startDate-hidden').value = startVal;
      document.getElementById('vouchers-edit-endDate-hidden').value = endVal;
      // Mở modal
      document.getElementById('vouchers-edit-modal').style.display = 'flex';
    });
  });


  document.querySelectorAll('.vouchers-btn-delete').forEach(btn => {
    btn.addEventListener('click', function () {
      const row = this.closest('tr'); // row chứa nút Xóa
      const id = row.querySelector('.vouchers-btn-edit').dataset.id; // lấy id từ data-id của nút Sửa

      if (confirm('Bạn có chắc muốn xóa voucher này không?')) {
        // Base API path
        const currentPath = window.location.pathname.replace(/\/$/, '');
        const apiBase = currentPath.endsWith('/vouchers') ? currentPath : currentPath + '/vouchers';

        fetch(`${apiBase}/delete_voucher`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: id })
        })
          .then(res => res.json())
          .then(result => {
            if (result.success) {
              // xóa row khỏi bảng
              row.remove();
              alert('Xóa voucher thành công!');
            } else {
              alert(result.message || 'Xóa thất bại!');
            }
          })
          .catch(err => {
            console.error('ERROR:', err);
            alert('Có lỗi xảy ra khi xóa voucher!');
          });
      }
    });
  });



  const searchInput = document.querySelector('.vouchers-search');
  const typeFilter = document.getElementById('voucherTypeFilter');

  function filterVouchers() {
    const keyword = searchInput.value.toLowerCase();
    const selectedType = typeFilter.value;

    const rows = document.querySelectorAll('.vouchers-table tbody tr');

    rows.forEach(row => {
      const id = row.dataset.id;
      const code = row.dataset.code;
      const type = row.dataset.type;

      let matchSearch = false;
      let matchType = false;

      // 🔍 tìm theo ID hoặc CODE
      if (
        id.includes(keyword) ||
        code.includes(keyword)
      ) {
        matchSearch = true;
      }

      // 🎯 lọc theo loại
      if (selectedType === "" || type === selectedType) {
        matchType = true;
      }

      // ✅ hiển thị hoặc ẩn
      if (matchSearch && matchType) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  // realtime
  searchInput.addEventListener('input', filterVouchers);
  typeFilter.addEventListener('change', filterVouchers);





});