<?php
require_once __DIR__ . '/../../../backend/config.php';
require_once __DIR__ . '/../../../backend/site_content/site_content_helper.php';
require_once __DIR__ . '/../../comp/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../comp/nav/nav.css">
    <link rel="stylesheet" href="../../comp/sidebar/sidebar.css">
    <link rel="stylesheet" href="site_content.css">
    <title>Site Content - Admin Panel</title>
</head>
<body data-theme="light-blue">
<?php require_once __DIR__ . '/../../comp/nav/nav.php'; ?>
<div class="main-container">
<?php require_once __DIR__ . '/../../comp/sidebar/sidebar.php'; ?>
<div class="main-content" id="mainContent">

<header class="sc-header"><div class="brand"><i class="fas fa-sliders-h"></i> Site Content</div></header>

<div class="sc-card">
    <h3>1. Navbar</h3>
    <label>School name</label>
    <input type="text" id="siteName" value="<?= htmlspecialchars(getContent('site_name')) ?>">

    <label>Logo</label>
    <div class="sc-logo-row">
        <img src="<?= getContent('site_logo') ? htmlspecialchars(app_url(getContent('site_logo'))) : '#' ?>"
             id="logoPreview" style="<?= getContent('site_logo') ? '' : 'display:none;' ?>">
        <input type="file" id="siteLogo" accept="image/*">
    </div>

    <label>WhatsApp number</label>
    <input type="text" id="waNumber" value="<?= htmlspecialchars(getContent('whatsapp_number')) ?>">

    <label>WhatsApp message</label>
    <input type="text" id="waMessage" value="<?= htmlspecialchars(getContent('whatsapp_message')) ?>">

    <button id="saveBtn">Save changes</button>
    <span id="saveMsg"></span>
</div>

</div></div>
<script src="../../comp/nav/nav.js"></script>
<script src="../../comp/sidebar/sidebar.js"></script>
<script src="site_content.js"></script>
</body>
</html>