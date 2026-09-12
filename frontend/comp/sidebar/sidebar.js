// sidebar.js — Sidebar related JS (theme selector, menu items, logout)

// Theme Change Function
function changeTheme(theme) {
    document.body.setAttribute('data-theme', theme);

    document.querySelectorAll('.theme-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`.theme-btn.${theme}`).classList.add('active');

    localStorage.setItem('selectedTheme', theme);
}

// Load saved theme
function loadSavedTheme() {
    const savedTheme = localStorage.getItem('selectedTheme') || 'light-blue';
    changeTheme(savedTheme);
}

// Show Section Function (sidebar menu items)
function showSection(section) {
    const sections = [
        'dashboard', 'students', 'teachers', 'classes', 'gallery', 'notice'
    ];

    sections.forEach(sec => {
        const element = document.getElementById(`${sec}-section`);
        if (element) {
            element.style.display = 'none';
        }
    });

    const selectedSection = document.getElementById(`${section}-section`);
    if (selectedSection) {
        selectedSection.style.display = 'block';
    }

    document.querySelectorAll('.menu-item').forEach(item => {
        item.classList.remove('active');
    });

    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        if (item.getAttribute('onclick') && item.getAttribute('onclick').includes(section)) {
            item.classList.add('active');
        }
    });

    // Mobile pe selection ke baad sidebar close
    if (window.innerWidth <= 768) {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.add('hidden');
        const toggleBtn = document.getElementById('sidebarToggle');
        toggleBtn.querySelector('i').className = 'fas fa-bars';
    }
}

// Logout Handler
// function handleLogout() {
//      fetch('../../../backend/logout.php', { method: 'POST' })
//     .then(response => {
//         // Logout hone ke baad user ko login page par bhej dega
//         window.location.href = '../../../backend/login/login.php'; 
//     });
// }
function handleLogout() {
    fetch(window.APP_BASE_URL + '/backend/logout.php', {
        method: 'POST'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Logout failed');
        }

        window.location.href =
            window.APP_BASE_URL + '/backend/login/login.php';
    })
    .catch(error => {
        console.error('Logout failed:', error);
    });
}