    const roomsAddBtn = document.getElementById('rooms-add-btn');
    const roomsModal = document.getElementById('rooms-modal');
    const roomsCancelBtn = document.getElementById('rooms-cancel-btn');
    const roomsSaveBtn = document.getElementById('rooms-save-btn');
    const roomsTable = document.getElementById('rooms-table').querySelector('tbody');
    const roomsModalTitle = document.getElementById('rooms-modal-title');
    const roomsSearchInput = document.getElementById('rooms-search-input');

    let editingRow = null;

    // Mở modal thêm phòng
    roomsAddBtn.addEventListener('click', () => {
      roomsModal.classList.add('active');
      roomsModalTitle.textContent = 'Thêm phòng';
      document.getElementById('rooms-id').value = '';
      document.getElementById('rooms-name').value = '';
      document.getElementById('rooms-type').value = 'Standard';
      document.getElementById('rooms-price').value = '';
      document.getElementById('rooms-status').value = 'Trống';
      editingRow = null;
    });

    // Hủy modal
    roomsCancelBtn.addEventListener('click', () => {
      roomsModal.classList.remove('active');
    });

    // Lưu thêm / sửa phòng
    roomsSaveBtn.addEventListener('click', () => {
      const id = document.getElementById('rooms-id').value;
      const name = document.getElementById('rooms-name').value;
      const type = document.getElementById('rooms-type').value;
      const price = document.getElementById('rooms-price').value;
      const status = document.getElementById('rooms-status').value;

      if (!id || !name || !price) {
        alert('Vui lòng điền đầy đủ thông tin!');
        return;
      }

      if (editingRow) {
        editingRow.cells[0].textContent = id;
        editingRow.cells[1].textContent = name;
        editingRow.cells[2].textContent = type;
        editingRow.cells[3].textContent = Number(price).toLocaleString();
        editingRow.cells[4].textContent = status;
      } else {
        const row = roomsTable.insertRow();
        row.insertCell(0).textContent = id;
        row.insertCell(1).textContent = name;
        row.insertCell(2).textContent = type;
        row.insertCell(3).textContent = Number(price).toLocaleString();
        row.insertCell(4).textContent = status;
        const actionCell = row.insertCell(5);
        actionCell.innerHTML = `
          <button class="rooms-btn rooms-btn-edit">Sửa</button>
          <button class="rooms-btn rooms-btn-delete">Xóa</button>
        `;
      }

      roomsModal.classList.remove('active');
    });

    // Sửa/Xóa phòng
    roomsTable.addEventListener('click', (e) => {
      if (e.target.classList.contains('rooms-btn-edit')) {
        editingRow = e.target.closest('tr');
        roomsModal.classList.add('active');
        roomsModalTitle.textContent = 'Sửa phòng';
        document.getElementById('rooms-id').value = editingRow.cells[0].textContent;
        document.getElementById('rooms-name').value = editingRow.cells[1].textContent;
        document.getElementById('rooms-type').value = editingRow.cells[2].textContent;
        document.getElementById('rooms-price').value = editingRow.cells[3].textContent.replace(/,/g, '');
        document.getElementById('rooms-status').value = editingRow.cells[4].textContent;
      }

      if (e.target.classList.contains('rooms-btn-delete')) {
        if (confirm('Bạn có chắc muốn xóa phòng này?')) {
          e.target.closest('tr').remove();
        }
      }
    });

    // Tìm kiếm phòng
    roomsSearchInput.addEventListener('input', () => {
      const filter = roomsSearchInput.value.toLowerCase();
      Array.from(roomsTable.rows).forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        const type = row.cells[2].textContent.toLowerCase();
        row.style.display = (name.includes(filter) || type.includes(filter)) ? '' : 'none';
      });
    });
