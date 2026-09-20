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

/* ============================================================
   🔥 LIVE CLOCK — Naya Add (kuch delete nahi kiya)
   ============================================================ */
function updateLiveClock() {
    const now = new Date();

    // Time
    let hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12 || 12;
    const timeStr = String(hours).padStart(2, '0') + ':' + minutes + ':' + seconds + ' ' + ampm;

    // Day
    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const dayStr = days[now.getDay()];

    // Date
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const dateStr = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();

    // DOM update
    const dayEl = document.getElementById('clockDay');
    const dateEl = document.getElementById('clockDate');
    const timeEl = document.getElementById('clockTime');

    if (dayEl) dayEl.textContent = dayStr;
    if (dateEl) dateEl.textContent = dateStr;
    if (timeEl) timeEl.textContent = timeStr;
}

// Live clock start (turant + har second)
if (document.getElementById('liveClock')) {
    updateLiveClock();
    setInterval(updateLiveClock, 1000);
}

// Page load par sidebar state + clock
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('sidebar')) {
        loadSidebarState();
    }
    updateLiveClock();
});

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