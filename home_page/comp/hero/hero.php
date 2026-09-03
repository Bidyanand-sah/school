<?php require_once __DIR__ . '/../../../backend/site_content/site_content_helper.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="hero.css">
</head>
<body>

<!--
  MERGED VERSION — purana rich design (badge, buttons, animated stats)
  + naya dynamic content. Heading, Subtext, Background Image, Badge
  Text, aur 4 Stats (value+label) — SAB admin panel > Site Settings >
  Hero tab se getContent() ke through aa rahe hain. Koi text yahan
  hardcoded nahi hai.
-->
<?php $heroBg = getContent('hero_bg'); ?>
<section class="hero-section<?= $heroBg ? '' : ' hero-section-default' ?>"
    <?php if ($heroBg): ?>style="background-image:url('<?= htmlspecialchars($heroBg) ?>');"<?php endif; ?>>
    <div class="hero-badge"><i class="fas fa-star"></i> <?= htmlspecialchars(getContent('hero_badge_text')) ?></div>

    <div class="hero-content">
        <h1><?= htmlspecialchars(getContent('hero_heading')) ?></h1>
        <p><?= htmlspecialchars(getContent('hero_subtext')) ?></p>
        <div class="hero-actions">
            <a href="/sms_teacher/home_page/contact/contact_page.php" class="btn-primary">
                Admission Enquiry <i class="fas fa-arrow-right"></i>
            </a>
            <a href="#about" class="btn-outline">Know More</a>
        </div>
    </div>

    <div class="hero-stats">
        <div class="stat-box"><div class="num" data-target="<?= htmlspecialchars(getContent('stat1_value')) ?>">0</div><div class="label"><?= htmlspecialchars(getContent('stat1_label')) ?></div></div>
        <div class="stat-box"><div class="num" data-target="<?= htmlspecialchars(getContent('stat2_value')) ?>">0</div><div class="label"><?= htmlspecialchars(getContent('stat2_label')) ?></div></div>
        <div class="stat-box"><div class="num" data-target="<?= htmlspecialchars(getContent('stat3_value')) ?>">0</div><div class="label"><?= htmlspecialchars(getContent('stat3_label')) ?></div></div>
        <div class="stat-box"><div class="num" data-target="<?= htmlspecialchars(getContent('stat4_value')) ?>">0</div><div class="label"><?= htmlspecialchars(getContent('stat4_label')) ?></div></div>
    </div>
</section>

<script src="hero.js"></script>
</body>
</html>