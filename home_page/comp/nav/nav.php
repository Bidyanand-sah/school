<?php require_once __DIR__ . '/../../../backend/site_content/site_content_helper.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>navbar</title>
    <link rel="stylesheet" href="nav.css">
</head>
<body>
    

<nav class="site-nav" id="siteNav">
    <div class="nav-inner">
        
     <a href="<?= app_url('/home_page/index.php') ?>" class="nav-brand">
    <?php if (getContent('site_logo')): ?>
        <img src="<?= getContent('site_logo') ?>" style="height:32px;width:32px;border-radius:6px;object-fit:cover;">
    <?php else: ?>
        <i class="fas fa-graduation-cap"></i>
    <?php endif; ?>
    <span><?= htmlspecialchars(getContent('site_name')) ?></span>
</a>
        <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
        <ul class="nav-links" id="navLinks">
            <li><a href="<?= app_url('/home_page/index.php') ?>">Home</a></li>
            <li><a href="<?= app_url('/home_page/about/about.php') ?>">About</a></li>
            <li><a href="<?= app_url('/home_page/teacher/teacher_page.php') ?>">Teachers</a></li>
            <li><a href="<?= app_url('/home_page/gallery/gallery_page.php') ?>">Gallery</a></li>
            <li><a href="<?= app_url('/home_page/notice/notice_page.php') ?>">Notice Board</a></li>
            <li><a href="<?= app_url('/home_page/contact/contact_page.php') ?>">Contact Us</a></li>
            <li><a href="<?= app_url('/home_page/review/review_page.php') ?>">Review</a></li>
            <li><a href="<?= app_url('/home_page/testimonial/testimonial_page.php') ?>">Testimonial</a></li>

         <li><a href="<?= app_url('/backend/login/login.php') ?>" class="nav-cta">Login</a></li>
        </ul>
    
    </div>
</nav>
<!-- WhatsApp Floating Button -->
<!-- <a href="https://wa.me/919876543210?text=Hi%2C%20mujhe%20admission%20ke%20baare%20mein%20jaankari%20chahiye"
   class="whatsapp-float" target="_blank" title="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a> -->

<a href="https://wa.me/<?= htmlspecialchars(getContent('whatsapp_number')) ?>?text=<?= urlencode(getContent('whatsapp_message')) ?>"
   class="whatsapp-float" target="_blank" title="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<script src="nav.js"></script>
</body>
</html>