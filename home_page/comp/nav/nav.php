<?php 
require_once __DIR__ . '/../../../backend/config.php';
require_once __DIR__ . '/../../../backend/site_content/site_content_helper.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <!-- FontAwesome CDN (agar aapke paas already hai to hata sakte hain) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= app_url('/home_page/comp/nav/nav.css') ?>">
</head>
<body class="site-nav">
    
<!-- =========================================
     TIER 1: TOP INFO STRIP
     ========================================= -->
<div class="top-strip" id="topStrip">
    <!-- <div class="info-item">
        <i class="fas fa-phone"></i> <?//= htmlspecialchars(getContent('phone_number') ?: '+91 98765 43210') ?>
    </div> -->
    <div class="info-item">
        <i class="fas fa-phone"></i> <?= htmlspecialchars(getContent('footer_phone')) ?>
    </div>
    <div class="info-item">
        <i class="fas fa-envelope"></i> <?= htmlspecialchars(getContent('footer_email')) ?>
    </div>
    <div class="info-item admission">
        <i class="fas fa-star"></i> <?= htmlspecialchars(getContent('hero_badge_text')) ?>
    </div>
</div>

<!-- =========================================
     TIER 2: MAIN NAVBAR
     ========================================= -->
<nav class="main-navbar" id="siteNav">
    <a href="<?= app_url('/') ?>" class="nav-brand">
        <?php if (getContent('site_logo')): ?>
            <img src="<?= getContent('site_logo') ?>" style="height:42px;width:42px;border-radius:8px;object-fit:cover;">
        <?php else: ?>
            <i class="fas fa-graduation-cap"></i>
        <?php endif; ?>
        <span>
            <?= htmlspecialchars(getContent('site_name') ?: 'Bright Future') ?>
            <small><?= htmlspecialchars(getContent('site_tagline') ?: 'International School') ?></small>
        </span>
    </a>

    <!-- Hamburger for Mobile -->
    <button class="hamburger" id="navToggle" aria-label="Toggle menu">
        <span></span><span></span><span></span>
    </button>

    <!-- Navigation Links -->
    <ul class="nav-links" id="navLinks">
        <li><a href="<?= app_url('/') ?>" class="active">Home</a></li>
        <li><a href="<?= app_url('/home_page/about/about.php') ?>">About</a></li>
        <li><a href="<?= app_url('/home_page/teacher/teacher_page.php') ?>">Teachers</a></li>
        <li><a href="<?= app_url('/home_page/gallery/gallery_page.php') ?>">Gallery</a></li>
        <li><a href="<?= app_url('/home_page/notice/notice_page.php') ?>">Notices</a></li>
        <li><a href="<?= app_url('/home_page/contact/contact_page.php') ?>">Contact</a></li>
        <li><a href="<?= app_url('/home_page/review/review_page.php') ?>">Review</a></li>
        <li><a href="<?= app_url('/home_page/testimonial/testimonial_page.php') ?>">Testimonial</a></li>
        
        <li><a href="<?= app_url('/backend/login/login.php') ?>" class="login-link">Login</a></li>
        <!-- Apply Now button (aap ise bhi getContent se link kar sakte hain) -->
        <li><a href="<?= app_url('/home_page/contact/contact_page.php') ?>" class="apply-btn">Apply Now</a></li>
    </ul>
</nav>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/<?= htmlspecialchars(getContent('whatsapp_number')) ?>?text=<?= urlencode(getContent('whatsapp_message')) ?>"
   class="whatsapp-float" target="_blank" title="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<script src="<?= app_url('/home_page/comp/nav/nav.js') ?>"></script>
</body>
</html>