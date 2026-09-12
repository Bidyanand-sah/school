<?php 
require_once __DIR__ . '/../../../backend/config.php';
require_once __DIR__ . '/../../../backend/site_content/site_content_helper.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <!-- <link rel="stylesheet" href="footer.css"> -->
    <link rel="stylesheet" href="<?= app_url('/home_page/comp/footer/footer.css') ?>">
</head>
<body>

<!--
  MERGED VERSION — purana rich design (logo icon, social media icons,
  columns) + naya dynamic content. Brand, Tagline, 4 Quick Links,
  Social Links, Address/Phone/Email, Copyright — SAB admin panel >
  Site Settings se getContent() ke through aa rahe hain.
  Social icon tabhi dikhega jab admin uska URL bhare — khaali ho to
  woh icon hide ho jayega (broken "#" link nahi dikhega).
-->
<footer class="footer">
    <div class="footer-inner">

        <div class="footer-col footer-brand">
            <div class="footer-logo">
                <?php if (getContent('site_logo')): ?>
                    <img src="<?= htmlspecialchars(app_url(getContent('site_logo'))) ?>" alt="logo">
                <?php else: ?>
                    <i class="fas fa-graduation-cap"></i>
                <?php endif; ?>
                <span><?= htmlspecialchars(getContent('footer_brand')) ?></span>
            </div>
            <p><?= htmlspecialchars(getContent('footer_tagline')) ?></p>

            <?php
            $socials = [
                ['url' => getContent('footer_social_facebook'),  'icon' => 'fab fa-facebook-f'],
                ['url' => getContent('footer_social_instagram'), 'icon' => 'fab fa-instagram'],
                ['url' => getContent('footer_social_youtube'),   'icon' => 'fab fa-youtube'],
                ['url' => getContent('footer_social_twitter'),   'icon' => 'fab fa-twitter'],
            ];
            $hasAnySocial = false;
            foreach ($socials as $s) {
                if (!empty($s['url'])) { $hasAnySocial = true; break; }
            }
            ?>
            <?php if ($hasAnySocial): ?>
                <div class="footer-social">
                    <?php foreach ($socials as $s): if (!empty($s['url'])): ?>
                        <a href="<?= htmlspecialchars($s['url']) ?>" target="_blank" rel="noopener noreferrer">
                            <i class="<?= $s['icon'] ?>"></i>
                        </a>
                    <?php endif; endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="footer-col">
            <h4>Quick Links</h4>
            <a href="<?= htmlspecialchars(getContent('footer_link1_url')) ?>"><?= htmlspecialchars(getContent('footer_link1_text')) ?></a>
            <a href="<?= htmlspecialchars(getContent('footer_link2_url')) ?>"><?= htmlspecialchars(getContent('footer_link2_text')) ?></a>
            <a href="<?= htmlspecialchars(getContent('footer_link3_url')) ?>"><?= htmlspecialchars(getContent('footer_link3_text')) ?></a>
            <a href="<?= htmlspecialchars(getContent('footer_link4_url')) ?>"><?= htmlspecialchars(getContent('footer_link4_text')) ?></a>
        </div>

        <div class="footer-col">
            <h4>Contact</h4>
            <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars(getContent('footer_address')) ?></p>
            <p><i class="fas fa-phone"></i> <?= htmlspecialchars(getContent('footer_phone')) ?></p>
            <p><i class="fas fa-envelope"></i> <?= htmlspecialchars(getContent('footer_email')) ?></p>
        </div>

    </div>
    <div class="footer-bottom">
        <p><?= htmlspecialchars(getContent('footer_copyright')) ?></p>
    </div>
</footer>

</body>
</html>