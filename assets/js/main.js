document.addEventListener('DOMContentLoaded', () => {
    // 1. Sidebar Toggle (Mobile)
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle && sidebar && overlay) {
        const toggleMenu = () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        };
        sidebarToggle.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);
    }

    // 2. Real-time Date on Navbar
    const dateEl = document.getElementById('navbarDate');
    if (dateEl) {
        const updateDate = () => {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateEl.textContent = now.toLocaleDateString('id-ID', options);
        };
        updateDate();
        // optionally update every minute, though date changes rarely
        setInterval(updateDate, 60000); 
    }

    // 3. Auto-hide Alerts
    const alerts = document.querySelectorAll('.alert[data-auto-hide]');
    alerts.forEach(alert => {
        const delay = parseInt(alert.getAttribute('data-auto-hide')) || 4000;
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 500);
        }, delay);
    });
});

// 4. Global Confirm Delete Function
function confirmDelete(url, itemName) {
    if (confirm(`Apakah Anda yakin ingin menghapus data: ${itemName}? Tindakan ini tidak dapat dibatalkan.`)) {
        window.location.href = url;
    }
}
