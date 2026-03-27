document.querySelector('.sidebar-nav').addEventListener('click', function(e) {
    const item = e.target.closest('.nav-item');
    if (item) {
        e.preventDefault();

        // Xóa active khỏi tất cả nav-item
        document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));

        // Thêm active cho item vừa click
        item.classList.add('active');

        // Redirect đến href của link (đã là clean URL)
        window.location.href = item.href;
    }
});


const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('sidebar-toggle');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
});