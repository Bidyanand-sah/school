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

// Load External Pages (navbar-menu links)
function loadPage(page) {
    const pages = {
        home: '/index.html',
        contact: '/contact.html',
        gallery: '/gallery.html',
        notice: '/notice.html'
    };

    if (pages[page]) {
        alert(`Loading ${page} page...\nIn production, this would load: ${pages[page]}`);
    }
}

// Login Handler
function handleLogin() {
    const isLoggedIn = localStorage.getItem('isLoggedIn');

    if (isLoggedIn) {
        alert('You are already logged in!');
    } else {
        simulateLogin('Rahul Sharma', 'School Admin');
        alert('Login successful! Welcome Rahul Sharma');
    }
}

function toggleNavMenu() {
    const navMenu = document.getElementById('navbarMenu');
    navMenu.classList.toggle('show');
}

document.addEventListener('click', function(e) {
    const navMenu = document.getElementById('navbarMenu');
    const toggleBtn = document.getElementById('navMenuToggle');

    if (navMenu && navMenu.classList.contains('show')) {
        const clickedInsideMenu = navMenu.contains(e.target);
        const clickedToggle = toggleBtn.contains(e.target);

        if (!clickedInsideMenu && !clickedToggle) {
            navMenu.classList.remove('show');
        }
    }
});

document.querySelectorAll('#navbarMenu a').forEach(link => {
    link.addEventListener('click', () => {
        document.getElementById('navbarMenu').classList.remove('show');
    });
});