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
                <span>Manage</span>
            </div>
        </div>

        <!-- 🔥 LIVE CLOCK (Text ki jagah) -->
<div class="live-clock" id="liveClock" title="Current Date & Time">
    <i class="fas fa-clock"></i>
    <span class="clock-day" id="clockDay">Mon</span>
    <span class="clock-date" id="clockDate">23 Oct 2024</span>
    <span class="clock-divider">|</span>
    <span class="clock-time" id="clockTime">10:45:00 AM</span>
</div>

        <div class="navbar-right">
    <button class="logout-btn" onclick="handleLogout()">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
</div>
    </nav>
</body>
</html>