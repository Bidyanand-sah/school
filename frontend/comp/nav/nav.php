<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NavBar</title>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <button class="sidebar-toggle" onclick="toggleSidebar()" id="sidebarToggle" title="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <div class="brand-logo">
                <i class="fas fa-school"></i>
                <span>EduManage</span>
            </div>
        </div>

        <ul class="navbar-menu">
            <li><a onclick="loadPage('home')"><i class="fas fa-home"></i> Home</a></li>
            <li><a onclick="loadPage('contact')"><i class="fas fa-envelope"></i> Contact</a></li>
            <li><a onclick="loadPage('gallery')"><i class="fas fa-images"></i> Gallery</a></li>
            <li><a onclick="loadPage('notice')"><i class="fas fa-bullhorn"></i> Notice</a></li>
        </ul>

        <div class="navbar-right">
            <button class="login-btn" onclick="handleLogin()">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </div>
    </nav>
</body>
</html>