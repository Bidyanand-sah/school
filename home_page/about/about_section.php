<?php require_once __DIR__ . '/../../backend/site_content/site_content_helper.php'; ?>
<!--
  MERGED VERSION — purana rich design (image + 4 highlight "points"
  with icons) + naya dynamic content. Tag, Heading, Paragraph, Image,
  aur 4 Points (title+text) — SAB admin panel > Site Settings > About
  tab se getContent() ke through aa rahe hain. Icons fixed hain
  (structural design choice), baaki sab text editable hai.
-->
<section class="about-section" id="about">
    <div class="about-inner">
        <div class="about-media">
            <?php if (getContent('about_img')): ?>
                <img src="<?= htmlspecialchars(getContent('about_img')) ?>" alt="Our campus">
            <?php else: ?>
                <div class="about-placeholder"><i class="fas fa-school"></i></div>
            <?php endif; ?>
        </div>
        <div class="about-content">
            <span class="section-tag"><?= htmlspecialchars(getContent('about_tag')) ?></span>
            <h2><?= htmlspecialchars(getContent('about_heading')) ?></h2>
            <p><?= nl2br(htmlspecialchars(getContent('about_text'))) ?></p>

            <div class="about-points">
                <div class="point">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <div>
                        <h4><?= htmlspecialchars(getContent('about_point1_title')) ?></h4>
                        <p><?= htmlspecialchars(getContent('about_point1_text')) ?></p>
                    </div>
                </div>
                <div class="point">
                    <i class="fas fa-school"></i>
                    <div>
                        <h4><?= htmlspecialchars(getContent('about_point2_title')) ?></h4>
                        <p><?= htmlspecialchars(getContent('about_point2_text')) ?></p>
                    </div>
                </div>
                <div class="point">
                    <i class="fas fa-heart"></i>
                    <div>
                        <h4><?= htmlspecialchars(getContent('about_point3_title')) ?></h4>
                        <p><?= htmlspecialchars(getContent('about_point3_text')) ?></p>
                    </div>
                </div>
                <div class="point">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <h4><?= htmlspecialchars(getContent('about_point4_title')) ?></h4>
                        <p><?= htmlspecialchars(getContent('about_point4_text')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>