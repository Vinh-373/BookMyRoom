document.addEventListener('DOMContentLoaded', () => {

  const searchInput_staff = document.getElementById('staffsSearchInput');
  const statusFilter_staff = document.getElementById('staffsStatusFilter');








//////////////////////////Tìm kiếm///////////////////
  //////////////// Lấy tất cả các hàng <tr> trong tbody của bảng
  const rows = document.querySelectorAll('#staffsTableBody tr');
  //////////////////// Hàm thực hiện lọc bảng
  function filterTable() {
    // Lấy từ khóa nhập vào, chuyển thành chữ thường để so sánh
    const keyword = searchInput_staff.value.toLowerCase();
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
  searchInput_staff.addEventListener('input', filterTable);
  // Gắn sự kiện: khi chọn trạng thái thì lọc ngay
  statusFilter_staff.addEventListener('change', filterTable);











});