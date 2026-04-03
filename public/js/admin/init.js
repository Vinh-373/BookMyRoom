/**
 * Admin Panel - Script Loader
 * Load tất cả các script cần thiết dựa trên trang hiện tại
 */

function adminJsBasePath() {
    var b = (typeof window.BOOKMYROOM_PUBLIC_BASE === 'string' && window.BOOKMYROOM_PUBLIC_BASE)
        ? window.BOOKMYROOM_PUBLIC_BASE.replace(/\/$/, '')
        : '/BookMyRoom';
    return b + '/public/js/admin/';
}

document.addEventListener('DOMContentLoaded', function() {
    const main = document.querySelector('main.main-content');
    const currentPage = document.body.getAttribute('data-page') ||
        (main && main.getAttribute('data-page')) ||
        window.location.pathname.split('/').pop().replace('.php', '');

    console.log('Current Page:', currentPage);

    const base = adminJsBasePath();
    const pageScripts = {
        'dashboard': base + 'dashboard.js',
        'bookings': base + 'bookings.js',
        'hotels': base + 'hotels.js',
        'partner-moderation': base + 'partner-moderation.js',
        'partner_moderation': base + 'partner-moderation.js',
        'rooms': base + 'rooms.js',
        'settings': base + 'settings.js',
        'payments': base + 'payments.js',
        'reviews': base + 'review.js',
        'vouchers': base + 'voucher.js',
        'staffs': base + 'staff.js',
        'customers': base + 'customer.js',
        'partners': base + 'partner.js',
        'statisticals': base + 'dashboard.js',
        'accounts_staff': base + 'accounts.js',
        'accounts_partner': base + 'accounts.js',
        'accounts_customer': base + 'accounts.js',
        'accounts': base + 'accounts.js'
    };

    const scriptPath = pageScripts[currentPage];

    if (scriptPath) {
        console.log('Loading:', scriptPath);
        loadScript(scriptPath);
    } else {
        console.warn('No script found for page:', currentPage);
    }
});

document.addEventListener('adminPartialLoad', function(e) {
    const page = e.detail && e.detail.page ? e.detail.page : '';
    if (!page) return;
    const base = adminJsBasePath();
    const pageScripts = {
        'dashboard': base + 'dashboard.js',
        'bookings': base + 'bookings.js',
        'hotels': base + 'hotels.js',
        'rooms': base + 'rooms.js',
        'settings': base + 'settings.js',
        'payments': base + 'payments.js',
        'reviews': base + 'review.js',
        'vouchers': base + 'voucher.js',
        'staffs': base + 'staff.js',
        'customers': base + 'customer.js',
        'partners': base + 'partner.js',
        'statisticals': base + 'dashboard.js'
    };
    const scriptPath = pageScripts[page];
    if (scriptPath) {
        loadScript(scriptPath);
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
