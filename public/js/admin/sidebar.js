document.querySelector('.sidebar-nav').addEventListener('click', function(e) {
    const item = e.target.closest('.nav-item');
    if (item) {
        e.preventDefault();

        // Xóa active khỏi tất cả nav-item
        document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));

        // Thêm active cho item vừa click
        item.classList.add('active');

        // Fetch nội dung partial (chỉ content) để thay phần main
        const page = item.getAttribute('data-page');
        const url = `index.php?url=admin/${page}&partial=1&t=${Date.now()}`;
        fetch(url)
            .then(res => res.text())
            .then(html => {
                const content = document.querySelector('.main-content');
                if (content) {
                    content.innerHTML = html;
                    window.dispatchEvent(new CustomEvent('adminPartialLoad', { detail: { page } }));
                } else {
                    console.warn('Không tìm thấy .main-content, đường dẫn admin chưa cấu hình đúng.');
                }
            })
            .catch(err => console.error('Lỗi tải nội dung:', err));
    }
});


const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('sidebar-toggle');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
});