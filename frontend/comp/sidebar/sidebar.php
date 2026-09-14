<?php
require_once __DIR__ . '/../../../backend/config.php';
// require_once __DIR__ . '/../../backend/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="sidebar" id="sidebar">
    <!-- Theme Selector -->
    <div class="theme-selector">
        <button class="theme-btn light-blue active" onclick="changeTheme('light-blue')"
            title="Light Blue Theme"></button>
        <button class="theme-btn yellow" onclick="changeTheme('yellow')" title="Yellow Theme"></button>
        <button class="theme-btn neon-blue" onclick="changeTheme('neon-blue')" title="Neon Blue Theme"></button>
    </div>

    <!-- Menu Items -->
    <div class="sidebar-menu">
        <a class="menu-item active" href="<?= app_url('/frontend/admin/index.php') ?>">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
        <!-- <a class="menu-item" onclick="showSection('students')">
            <i class="fas fa-user-graduate"></i>
            <span>Students</span>
        </a> -->
        <a class="menu-item" href="<?= app_url('/frontend/admin/teacher/teacher.php') ?>">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Teachers</span>
        </a>
        <a class="menu-item" href="<?= app_url('/frontend/admin/achievement/achievement.php') ?>">
            <i class="fas fa-door-open"></i>
            <span>Achievement</span>
        </a>
        <a class="menu-item" href="<?= app_url('/frontend/admin/gallery/gallery.php') ?>">
            <i class="fas fa-images"></i>
            <span>Gallery</span>
        </a>
        <a class="menu-item" href="<?= app_url('/frontend/admin/notice/notice.php') ?>">
            <i class="fas fa-bullhorn"></i>
            <span>Notice Board</span>
        </a>
        <a class="menu-item" href="<?= app_url('/frontend/admin/enquiry/enquiry.php') ?>">
            <i class="fas fa-address-card"></i>
            <span>Enquiry</span>
        </a>
        <a class="menu-item" href="<?= app_url('/frontend/admin/testimonial/testimonial.php') ?>">
            <i class="fas fa-address-card"></i>
            <span>Testimonial</span>
        </a>
        <?php if (($_SESSION['admin_role'] ?? '') === 'superadmin'): ?>
<a class="menu-item" href="<?= app_url('/frontend/admin/site_settings/index.php') ?>">
    <i class="bi bi-gear-fill"></i>
    <span>Site Settings</span>
</a>
<?php endif; ?>

    <a class="menu-item" href="<?= app_url('/frontend/admin/account/account.php') ?>">
    <i class="bi bi-person-gear"></i>
    <span>My Account</span>
</a>
    </div>

    <!-- User Profile Footer -->
    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-info-sidebar">
                <h4 id="userName">Admin User</h4>
                <p id="userRole">Administrator</p>
            </div>
        </div>
        <button class="logout-btn" onclick="handleLogout()">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </div>
</div>

<!-- ============================================================
     SIDEBAR STATE FIX — refresh pe flash/flicker rokne ke liye
     Ye script sidebar HTML ke turant baad chalta hai, DOMContentLoaded
     ka wait nahi karta, isliye sahi state pehle hi lag jati hai.
     ============================================================ -->
<script>
(function () {
    var isHidden = localStorage.getItem('sidebarHidden') === 'true';
    var sidebar = document.getElementById('sidebar');
    if (!sidebar) return;

    sidebar.classList.add('no-transition');
    if (isHidden) {
        sidebar.classList.add('hidden');
    }

    requestAnimationFrame(function () {
        requestAnimationFrame(function () {
            sidebar.classList.remove('no-transition');
        });
    });
})();
</script>

<script>
window.APP_BASE_URL = <?= json_encode(BASE_URL) ?>;
</script>

<script src="<?= app_url('/frontend/comp/sidebar/sidebar.js') ?>"></script>
</body>
</html>