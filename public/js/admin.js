/**
 * Admin Panel Scripts
 * Sidebar Toggle, Toast Notifications
 */

// ===== SIDEBAR TOGGLE =====
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}

// ===== TOAST NOTIFICATIONS =====
function showToasts() {
    if (!window.flashData) return;

    if (window.flashData.success) {
        Toastify({
            text: window.flashData.success,
            duration: 3000,
            close: true,
            gravity: 'top',
            position: 'right',
            stopOnFocus: true,
            style: {
                background: '#ffffff',
                color: '#333333',
                borderLeft: '5px solid #2ecc71',
                borderRadius: '8px',
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                padding: '16px 20px',
                fontWeight: '500'
            }
        }).showToast();
        window.flashData.success = null;
    }

    if (window.flashData.error) {
        Toastify({
            text: window.flashData.error,
            duration: 3000,
            close: true,
            gravity: 'top',
            position: 'right',
            stopOnFocus: true,
            style: {
                background: '#ffffff',
                color: '#333333',
                borderLeft: '5px solid #e74c3c',
                borderRadius: '8px',
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                padding: '16px 20px',
                fontWeight: '500'
            }
        }).showToast();
        window.flashData.error = null;
    }
}

// ===== INIT =====
document.addEventListener('DOMContentLoaded', function () {
    // Show flash toasts
    showToasts();

    // Close sidebar when clicking a nav link on mobile/tablet
    document.querySelectorAll('.nav-item').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 992) {
                document.getElementById('sidebar').classList.remove('open');
                document.getElementById('sidebarOverlay').classList.remove('open');
            }
        });
    });
});


// ===== CREATOR & DEVELOPER SIGNATURE =====
console.log(
    '%c ⚡ Crafted with precision %c Duma Augusto %c Full Stack Developer ',
    'background: #18181b; color: #a1a1aa; padding: 5px 9px; border-radius: 4px 0 0 4px; font-size: 11px; font-weight: 500; font-family: monospace;',
    'background: #09090b; color: #ffffff; padding: 5px 9px; font-size: 11px; font-weight: 700; border-top: 1px solid #27272a; border-bottom: 1px solid #27272a; font-family: monospace;',
    'background: #27272a; color: #22c55e; padding: 5px 9px; border-radius: 0 4px 4px 0; font-size: 11px; font-weight: 600; font-family: monospace;'
);
