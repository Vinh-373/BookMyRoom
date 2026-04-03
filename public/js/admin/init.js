/**
 * Admin Panel - Script Loader
 * Load tất cả các script cần thiết dựa trên trang hiện tại
 */

document.addEventListener('DOMContentLoaded', function() {
    const currentPage = document.body.getAttribute('data-page') || 
                       document.querySelector('main').getAttribute('data-page') ||
                       window.location.pathname.split('/').pop().replace('.php', '');

    console.log('Current Page:', currentPage);

    // Map page name với script cần load
    // Note: Đường dẫn có thể cần điều chỉnh tùy theo cấu trúc server
    const pageScripts = {
        'dashboard': '/BookMyRoom/public/js/admin/dashboard.js',
        'bookings': '/BookMyRoom/public/js/admin/bookings.js',
        'hotels': '/BookMyRoom/public/js/admin/hotels.js',
        'partner-moderation': '/BookMyRoom/public/js/admin/partner-moderation.js',
        'partner_moderation': '/BookMyRoom/public/js/admin/partner-moderation.js',
        'rooms': '/BookMyRoom/public/js/admin/rooms.js',
        'settings': '/BookMyRoom/public/js/admin/settings.js',
        'payments': '/BookMyRoom/public/js/admin/payments.js',
        'reviews': '/BookMyRoom/public/js/admin/reviews.js',
        'vouchers': '/BookMyRoom/public/js/admin/vouchers.js',
        'accounts_staff': '/BookMyRoom/public/js/admin/accounts.js',
        'accounts_partner': '/BookMyRoom/public/js/admin/accounts.js',
        'accounts_customer': '/BookMyRoom/public/js/admin/accounts.js',
        'accounts': '/BookMyRoom/public/js/admin/accounts.js'
    };

    const scriptPath = pageScripts[currentPage];

    if (scriptPath) {
        console.log('Loading:', scriptPath);
        loadScript(scriptPath);
    } else {
        console.warn('No script found for page:', currentPage);
    }
});

/**
 * Load script dynamically
 */
function loadScript(src) {
    const file = src.split('/').pop() || '';
    if (file && document.querySelector(`script[src$="${file}"]`)) {
        console.log('Skip duplicate script:', file);
        return;
    }
    const script = document.createElement('script');
    script.src = src;
    script.onload = function() {
        console.log('Script loaded successfully:', src);
    };
    script.onerror = function() {
        console.error('Failed to load script:', src);
    };
    document.head.appendChild(script);
}

/**
 * Alternative: Manual loading in HTML
 * In your HTML file, add a script tag:
 * <script src="/BookMyRoom/public/js/admin/utils.js"></script>
 * <script src="/BookMyRoom/public/js/admin/dashboard.js"></script>
 */
