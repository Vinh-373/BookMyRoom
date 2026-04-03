// Admin Bootstrap Script
// Đảm bảo các scripts core được load trước khi thực thi trang admin cụ thể
(function() {
    function loadScript(src) {
        if (!src) return;
        if (document.querySelector('script[src="' + src + '"]')) return;

        const script = document.createElement('script');
        script.src = src;
        script.async = false;
        script.onload = function() {
            console.log('Loaded admin script:', src);
        };
        script.onerror = function() {
            console.error('Cannot load admin script:', src);
        };
        document.head.appendChild(script);
    }

    // Load helper và page router script
    loadScript('/BookMyRoom/public/js/admin/utils.js');
    loadScript('/BookMyRoom/public/js/admin/init.js');
})();
