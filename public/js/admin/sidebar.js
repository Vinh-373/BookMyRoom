document.querySelector('.sidebar-nav').addEventListener('click', function(e) {
    const item = e.target.closest('.nav-item');
    if (item) {
        e.preventDefault();

        // Xóa active khỏi tất cả nav-item
        document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));

        // Thêm active cho item vừa click
        item.classList.add('active');

        // Fetch nội dung
        const page = item.getAttribute('data-page');
        const url = `index.php?url=admin/${page}&t=${Date.now()}`;
        fetch(url)
            .then(res => res.text())
            .then(html => {
                document.querySelector('.main-content').innerHTML = html;
            })
            .catch(err => console.error('Lỗi tải nội dung:', err));
    }
});


const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('sidebar-toggle');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
});