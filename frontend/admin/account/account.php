<?php
require_once __DIR__ . '/../../../backend/config.php';
require_once __DIR__ . '/../../comp/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="../../comp/nav/nav.css">
    <link rel="stylesheet" href="../../comp/sidebar/sidebar.css">
    <link rel="stylesheet" href="account.css">
</head>
<body data-theme="light-blue">

<?php require_once __DIR__ . '/../../comp/nav/nav.php'; ?>

<div class="main-container">
<?php require_once __DIR__ . '/../../comp/sidebar/sidebar.php'; ?>

    <div class="main-content" id="mainContent">

        <header class="acc-header">
            <div class="brand"><i class="bi bi-person-gear"></i> My Account</div>
            <p class="acc-subtitle">Manage your login credentials</p>
        </header>

        <div class="acc-wrap">
            <div class="acc-card">

                <div class="acc-card-top">
                    <div class="acc-avatar"><i class="bi bi-shield-lock-fill"></i></div>
                    <div>
                        <h3>Change Username / Password</h3>
                        <p class="acc-hint">Current password daalna zaroori hai. Jo change nahi karna, use khaali chhod do.</p>
                    </div>
                </div>

                <div class="acc-field">
                    <label>Current Password <span class="req">*</span></label>
                    <div class="acc-input-group">
                        <i class="bi bi-lock-fill acc-input-icon"></i>
                        <input type="password" id="currentPassword" placeholder="Enter current password" required>
                        <button type="button" class="acc-eye-toggle" data-target="currentPassword">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                </div>

                <div class="acc-divider"><span>Optional changes</span></div>

                <div class="acc-field">
                    <label>New Username</label>
                    <div class="acc-input-group">
                        <i class="bi bi-person-fill acc-input-icon"></i>
                        <input type="text" id="newUsername" placeholder="Leave empty to keep current username">
                    </div>
                </div>

                <div class="acc-field">
                    <label>New Password</label>
                    <div class="acc-input-group">
                        <i class="bi bi-key-fill acc-input-icon"></i>
                        <input type="password" id="newPassword" placeholder="Leave empty to keep current password">
                        <button type="button" class="acc-eye-toggle" data-target="newPassword">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                </div>

                <div class="acc-field">
                    <label>Confirm New Password</label>
                    <div class="acc-input-group">
                        <i class="bi bi-key-fill acc-input-icon"></i>
                        <input type="password" id="confirmPassword" placeholder="Re-enter new password">
                        <button type="button" class="acc-eye-toggle" data-target="confirmPassword">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                </div>

                <button id="updateAccountBtn"><i class="bi bi-check-lg"></i> Save Changes</button>
                <div id="accMsg" class="acc-msg"></div>

            </div>
        </div>

    </div>
</div>

<script>
window.APP_BASE_URL = <?= json_encode(BASE_URL) ?>;
</script>
<script src="../../comp/nav/nav.js"></script>
<script src="../../comp/sidebar/sidebar.js"></script>
<script src="account.js"></script>
</body>
</html>