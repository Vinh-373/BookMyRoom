// Admin Bootstrap Script — tải helper khi layout dùng entry này (tùy chọn)
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

    loadScript('/BookMyRoom/public/js/admin/utils.js');
    loadScript('/BookMyRoom/public/js/admin/init.js');
})();
