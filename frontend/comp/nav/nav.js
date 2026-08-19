// navbar.js — Navbar related JS (toggle button, top menu links, login)

// Sidebar Toggle Function (button navbar me hai, isliye yahan rakha)
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('hidden');

    const toggleBtn = document.getElementById('sidebarToggle');
    const icon = toggleBtn.querySelector('i');

    if (sidebar.classList.contains('hidden')) {
        icon.className = 'fas fa-bars';
        toggleBtn.title = 'Show Sidebar';
    } else {
        icon.className = 'fas fa-times';
        toggleBtn.title = 'Hide Sidebar';
    }

    localStorage.setItem('sidebarHidden', sidebar.classList.contains('hidden'));
}

// Load sidebar state (toggle button ka icon set karta hai)
function loadSidebarState() {
    const sidebarHidden = localStorage.getItem('sidebarHidden') === 'true';
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    if (sidebarHidden) {
        sidebar.classList.add('hidden');
        toggleBtn.querySelector('i').className = 'fas fa-bars';
        toggleBtn.title = 'Show Sidebar';
    } else {
        sidebar.classList.remove('hidden');
        toggleBtn.querySelector('i').className = 'fas fa-times';
        toggleBtn.title = 'Hide Sidebar';
    }
}

// Handle responsive sidebar (mobile pe auto-hide)
function handleResize() {
    if (window.innerWidth <= 768) {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.add('hidden');
        const toggleBtn = document.getElementById('sidebarToggle');
        toggleBtn.querySelector('i').className = 'fas fa-bars';
    }
}


