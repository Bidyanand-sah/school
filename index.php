<?php
    ini_set('display_errors', 1);
error_reporting(E_ALL);

// home_page/testimonial/testimonial_page.php — public, read-only, sab reviews dikhata hai
require_once __DIR__ .  '/backend/con1.php';
// require_once __DIR__ . '/frontend/comp/auth_check.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Future School</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="home_page/comp/nav/nav.css">
<link rel="stylesheet" href="home_page/comp/hero/hero.css">
<link rel="stylesheet" href="home_page/about/about.css">
<link rel="stylesheet" href="home_page/testimonial/testimonial_page.css">
<link rel="stylesheet" href="home_page/comp/footer/footer.css">
<link rel="stylesheet" href="home_page/comp/achievement/achievement.css">
</head>
<body>

<?php require_once __DIR__ . '/home_page/comp/nav/nav.php' ; ?>
<?php require_once __DIR__ . '/home_page/comp/hero/hero.php'; ?>
<?php require_once __DIR__ . '/home_page/about/about_section.php'; ?>

<section class="modules-preview">
  <div class="modules-inner">
    <span class="section-tag">Explore</span>
    <h2>Everything About Our School, In One Place</h2>
    <div class="modules-grid">
      <!-- <a href="/sms_teacher/home_page/teacher/teacher_page.php" class="module-card"> -->
      <a href="<?= app_url('/home_page/teacher/teacher_page.php') ?>" class="module-card">
        <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
        <h3>Our Teachers</h3><p>Milein hamare experienced aur caring teaching staff se.</p>
        <span class="explore">Explore &rarr;</span>
      </a>
      <!-- <a href="/sms_teacher/home_page/gallery/gallery_page.php" class="module-card"> -->
        <a href="<?= app_url('/home_page/gallery/gallery_page.php') ?>" class="module-card">

        <div class="icon"><i class="fas fa-images"></i></div>
        <h3>Gallery</h3><p>School events, activities aur campus life ki jhalak.</p>
        <span class="explore">Explore &rarr;</span>
      </a>
      <!-- <a href="/sms_teacher/home_page/notice/notice_page.php" class="module-card"> -->
        <a href="<?= app_url('/home_page/notice/notice_page.php') ?>" class="module-card">

        <div class="icon"><i class="fas fa-bullhorn"></i></div>
        <h3>Notice Board</h3><p>Latest announcements aur important updates yahan.</p>
        <span class="explore">Explore &rarr;</span>
      </a>
      <!-- <a href="/sms_teacher/home_page/contact/contact_page.php" class="module-card"> -->
        <a href="<?= app_url('/home_page/contact/contact_page.php') ?>" class="module-card">

        <div class="icon"><i class="fas fa-envelope"></i></div>
        <h3>Contact Us</h3><p>Admission ya kisi bhi query ke liye humse judein.</p>
        <span class="explore">Explore &rarr;</span>
      </a>
    </div>
  </div>
</section>

<?php

$achievementLimit = 6;
$achievementImgPrefix = "";
require_once __DIR__ . '/home_page/comp/achievement/achievement_section.php';
?>

<?php require_once __DIR__ . '/home_page/testimonial/testimonial_section.php'; ?>
<?php require_once __DIR__ . '/home_page/comp/footer/footer.php'; ?>

<script src="home_page/comp/nav/nav.js"></script>
<script src="home_page/comp/hero/hero.js"></script>

<script src="home_page/comp/achievement/achievement.js"></script>

<script src="home_page/testimonial/testimonial_page.js"></script>

</body>
</html>