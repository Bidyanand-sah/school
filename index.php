<?php
    ini_set('display_errors', 1);
error_reporting(E_ALL);

// home_page/testimonial/testimonial_page.php — public, read-only, sab reviews dikhata hai
require_once __DIR__ .  '/backend/con1.php';
require_once __DIR__ . '/backend/config.php';
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

<!-- <link rel="stylesheet" href="home_page/testimonial/testimonial_page.css"> -->

<link rel="stylesheet" href="home_page/comp/achievement/achievement.css">

<link rel="stylesheet" href="<?= app_url('/home_page/notice/notice_section.css') ?>">

<link rel="stylesheet" href="home_page/gallery/gallery_section.css">

<link rel="stylesheet" href="home_page/testimonial/testimonial_split.css">

<link rel="stylesheet" href="home_page/comp/footer/footer.css">
<link rel="stylesheet" href="home_page/comp/achievement/achievement.css">
</head>
<body>

<?php require_once __DIR__ . '/home_page/comp/nav/nav.php' ; ?>
<?php require_once __DIR__ . '/home_page/comp/hero/hero.php'; ?>
<?php require_once __DIR__ . '/home_page/about/about_section.php'; ?>

<?php
$teacherView = 'home';
require_once __DIR__ . '/home_page/teacher/teacher_section.php';
?>

<?php require_once __DIR__ . '/home_page/explore/explore.php'; ?>


<?php

$achievementLimit = 6;
$achievementImgPrefix = "";
require_once __DIR__ . '/home_page/comp/achievement/achievement_section.php';
?>


<?php require_once __DIR__ . '/home_page/gallery/gallery_section.php'; ?>

<?php require_once __DIR__ . '/home_page/notice/notice_section.php'; ?>

<?php
$testimonialView = 'home';
require_once __DIR__ . '/home_page/testimonial/testimonial_section.php';
?>

<?php require_once __DIR__ . '/home_page/comp/footer/footer.php'; ?>

<!-- <script src="home_page/comp/nav/nav.js"></script> -->
<script src="home_page/comp/hero/hero.js"></script>

<script src="home_page/comp/achievement/achievement.js"></script>

<script src="home_page/testimonial/testimonial_page.js"></script>

<script src="home_page/gallery/gallery_section.js"></script>

</body>
</html>