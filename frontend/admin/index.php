<?php include '../comp/auth_check.php'; ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../comp/nav/nav.css">
    <link rel="stylesheet" href="../comp/sidebar/sidebar.css">
</head>

<body data-theme="light-blue">

    <!-- Top Navbar -->
        <?php
            include_once('../comp/nav/nav.php');
        ?>
    <!-- End Navbar  -->

    <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
            <?php
                include_once('../comp/sidebar/sidebar.php');
            ?>
        <!-- Sidebar -->
        
        <!-- Main Content -->
        <div class="main-content" id="mainContent">

            <!-- Dashboard Section -->
            <div id="dashboard-section">
                <!-- Welcome Banner -->
                <div class="welcome-banner">
                    <h1>👋 Welcome back, Admin!</h1>
                    <p>Here's what's happening at your school today.</p>
                </div>

                <!-- Statistics Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon icon-students">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="stat-info">
                            <h3>1,234</h3>
                            <p>Total Students</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon icon-teachers">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="stat-info">
                            <h3>85</h3>
                            <p>Total Teachers</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon icon-classes">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <div class="stat-info">
                            <h3>45</h3>
                            <p>Active Classes</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon icon-attendance">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-info">
                            <h3>95%</h3>
                            <p>Attendance Today</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="action-card" onclick="showSection('students')">
                        <i class="fas fa-user-plus"></i>
                        <h4>Add Student</h4>
                    </div>
                    <div class="action-card" onclick="showSection('teachers')">
                        <i class="fas fa-user-tie"></i>
                        <h4>Add Teacher</h4>
                    </div>
                    <div class="action-card">
                        <i class="fas fa-calendar-plus"></i>
                        <h4>Mark Attendance</h4>
                    </div>
                    <div class="action-card">
                        <i class="fas fa-file-invoice"></i>
                        <h4>Generate Report</h4>
                    </div>
                </div>
            </div>

            <!-- Students Section (Hidden by default) -->
            <div id="students-section" class="content-section" style="display:none;">
                <h2><i class="fas fa-user-graduate"></i> Students Management</h2>
                <p>Students management content will be displayed here.</p>
            </div>

            <!-- Teachers Section (Hidden by default) -->
            

            <!-- Classes Section (Hidden by default) -->
            <div id="classes-section" class="content-section" style="display:none;">
                <h2><i class="fas fa-door-open"></i> Classes Management</h2>
                <p>Classes management content will be displayed here.</p>
            </div>

            <!-- Gallery Section (Hidden by default) -->
            

            <!-- Notice Section (Hidden by default) -->
            
        </div>
    </div>

    <script src="../comp/nav/nav.js"></script>
    <script src="../comp/sidebar/sidebar.js"></script>
    <script src="script.js"></script>
</body>

</html>