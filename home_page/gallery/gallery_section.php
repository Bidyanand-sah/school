<?php
// gallery_section.php — SIRF content, koi html/head/nav/footer/css-link nahi
// Include karne wale ne pehle se $conn (con1.php) aur app_url() (config.php) banaya hona chahiye

$result = $conn->query("SELECT id, img, detail FROM gallery ORDER BY id DESC");
$photos = [];
while ($row = $result->fetch_assoc()) { $photos[] = $row; }

// Har photo ko halka alag tilt dene ke liye (polaroid look), cycle karte hue
function gsTiltFor($index) {
    $angles = [-6, 4, -3, 7, -8, 5, -4, 6, -5, 3];
    return $angles[$index % count($angles)];
}
?>
<div class="gs-section">
    <div class="gs-heading">
        <h2><i class="bi bi-images"></i> Gallery Glimpse</h2>
        <p>School life ke chhote chhote khoobsurat pal</p>
    </div>

    <?php if (empty($photos)): ?>
        <div class="gs-empty">
            <i class="bi bi-image"></i>
            Gallery coming soon. Stay tuned!
        </div>
    <?php else: ?>
        <div class="gs-outer-wrap">
            <button class="gs-arrow gs-arrow-left" aria-label="Scroll left">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="gs-track">
                <div class="gs-row">
                    <?php foreach ($photos as $i => $p): $rot = gsTiltFor($i); ?>
                        <div class="gs-card" style="transform: rotate(<?= $rot ?>deg);" data-rotate="<?= $rot ?>">
                            <div class="gs-photo">
                                <img src="<?= htmlspecialchars(app_url($p['img'])) ?>"
                                     alt="<?= htmlspecialchars($p['detail'] ?: 'Gallery photo') ?>"
                                     loading="lazy">
                            </div>
                            <p class="gs-caption<?= empty($p['detail']) ? ' gs-caption-empty' : '' ?>">
                                <?= htmlspecialchars($p['detail'] ?: '') ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button class="gs-arrow gs-arrow-right" aria-label="Scroll right">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <div class="gs-viewall-row">
            <a href="<?= app_url('/home_page/gallery/gallery_page.php') ?>" class="gs-viewall-btn">
                View Full Gallery <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    <?php endif; ?>
</div>