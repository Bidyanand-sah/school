// main.js — Bacha hua common/shared JS (dono ko touch karta hai + init)

// Simulate Login
function simulateLogin(name, role) {
    localStorage.setItem('isLoggedIn', 'true');
    localStorage.setItem('userName', name);
    localStorage.setItem('userRole', role);
    updateUserInfo();
}

// Update User Info (navbar login button + sidebar profile dono update karta hai)
function updateUserInfo() {
    const isLoggedIn = localStorage.getItem('isLoggedIn');
    const userName = localStorage.getItem('userName') || 'Admin User';
    const userRole = localStorage.getItem('userRole') || 'Administrator';

    document.getElementById('userName').textContent = userName;
    document.getElementById('userRole').textContent = isLoggedIn ? userRole : 'Not logged in';

    const loginBtn = document.querySelector('.login-btn');
    if (isLoggedIn) {
        loginBtn.innerHTML = '<i class="fas fa-user"></i> Profile';
        loginBtn.onclick = () => alert('User profile page');
    } else {
        loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Login';
        loginBtn.onclick = handleLogin;
    }
}

// Event Listeners
window.addEventListener('resize', handleResize);

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadSavedTheme();
    updateUserInfo();
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