(function () {
    const nav = document.querySelector('.sidebar-nav');
    if (!nav) return;

    nav.addEventListener('click', function(e) {
        const item = e.target.closest('.nav-item');
        if (!item) return;
        e.preventDefault();

        document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
        item.classList.add('active');

        const page = item.getAttribute('data-page');
        var indexPhp = (typeof window.BOOKMYROOM_INDEX_PHP === 'string' && window.BOOKMYROOM_INDEX_PHP)
            ? window.BOOKMYROOM_INDEX_PHP
            : '/BookMyRoom/index.php';
        const url = indexPhp + '?url=admin/' + encodeURIComponent(page || 'dashboard') + '&partial=1&t=' + Date.now();
        fetch(url)
            .then(res => res.text())
            .then(html => {
                const content = document.querySelector('.main-content');
                if (content) {
                    content.innerHTML = html;
                    content.setAttribute('data-page', page || '');
                    window.dispatchEvent(new CustomEvent('adminPartialLoad', { detail: { page } }));
                } else {
                    console.warn('Không tìm thấy .main-content, đường dẫn admin chưa cấu hình đúng.');
                }
            })
            .catch(err => console.error('Lỗi tải nội dung:', err));
    });
})();

(function () {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebar-toggle');
    if (!sidebar || !toggleBtn) return;
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });
})();
