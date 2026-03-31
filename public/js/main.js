document.addEventListener('DOMContentLoaded', function() {
    const trigger = document.getElementById('userDropdownTrigger');
    const menu = document.getElementById('userMenu');

    if (trigger && menu) {
        // 1. Khi click vào khu vực User
        trigger.addEventListener('click', function(e) {
            e.stopPropagation(); // Ngăn sự kiện nổi bọt lên window
            menu.classList.toggle('active');
        });

        // 2. Khi click vào bất kỳ đâu ngoài menu
        window.addEventListener('click', function() {
            if (menu.classList.contains('active')) {
                menu.classList.remove('active');
            }
        });
    }
});