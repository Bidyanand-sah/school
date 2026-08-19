<?php
// achievement_section.php — SIRF content, koi html/head/nav/footer/css-link nahi
// Isse include karne wale ne pehle se $conn banaya hona chahiye (con1.php)
//
// Optional variables jo include karne se PEHLE set kar sakte ho:
//   $achievementLimit    -> int, kitne achievements dikhane hai (0 ya set na karo = sab)
//   $achievementImgPrefix -> string, image path ke aage kitna "../" lagana hai

if (!isset($achievementLimit)) {
    $achievementLimit = 0;
}
if (!isset($achievementImgPrefix)) {
    $achievementImgPrefix = "../../";
}

$achSql = "SELECT id, img, title, description, category, is_pinned FROM achievements ORDER BY is_pinned DESC, id DESC";
if ($achievementLimit > 0) {
    $achSql .= " LIMIT " . intval($achievementLimit);
}
$achResult = $conn->query($achSql);
$achievements = [];
while ($row = $achResult->fetch_assoc()) { $achievements[] = $row; }
?>
<div class="ach-section">
    <div class="ach-heading">
        <h2><i class="bi bi-trophy-fill"></i> Our Achievements</h2>
        <p>Proud moments that define our school's excellence</p>
    </div>

    <?php if (empty($achievements)): ?>
        <div class="ach-empty">
            <i class="bi bi-trophy"></i>
            No achievements added yet.
        </div>
    <?php else: ?>
        <div class="ach-outer-wrap">
            <button class="ach-arrow ach-arrow-left" aria-label="Scroll left">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="ach-row">
                <?php foreach ($achievements as $a): ?>
                    <div class="ach-card <?= $a['is_pinned'] ? 'ach-pinned' : '' ?>">
                        <?php if ($a['is_pinned']): ?>
                            <div class="ach-ribbon">Featured</div>
                        <?php endif; ?>
                        <div class="ach-img-wrap">
                            <img src="<?= htmlspecialchars($achievementImgPrefix . $a['img']) ?>"
                                 alt="<?= htmlspecialchars($a['title']) ?>" loading="lazy">
                        </div>
                        <div class="ach-body">
                            <span class="ach-badge"><?= htmlspecialchars($a['category']) ?></span>
                            <h4><?= htmlspecialchars($a['title']) ?></h4>
                            <?php if (!empty($a['description'])): ?>
                                <p><?= htmlspecialchars($a['description']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="ach-arrow ach-arrow-right" aria-label="Scroll right">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    <?php endif; ?>
</div>