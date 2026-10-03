// main.js — Bacha hua common/shared JS (dono ko touch karta hai + init)


// Update User Info (navbar login button + sidebar profile dono update karta hai)

// Event Listeners
window.addEventListener('resize', handleResize);

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadSavedTheme();
    loadSidebarState();

    showSection('dashboard');

    if (window.innerWidth <= 768) {
        handleResize();
    }
});

// Ctrl + B to toggle sidebar
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'b') {
        e.preventDefault();
        toggleSidebar();
    }
});