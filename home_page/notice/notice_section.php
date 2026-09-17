<?php
// home_page/notice/notice_section.php
// SIRF content — koi html/head/nav/footer nahi, css link bhi yahan nahi
// Homepage ke liye — top notices, priority: pinned > Urgent > latest

if (!isset($noticeLimit)) {
    $noticeLimit = 5;
}

$result = $conn->query(
    "SELECT id, title, content, category, pdf, pinned, date 
     FROM notice 
     ORDER BY 
        pinned DESC, 
        (category = 'Urgent') DESC, 
        id DESC 
     LIMIT " . intval($noticeLimit)
);
$notices = [];
while ($row = $result->fetch_assoc()) { $notices[] = $row; }
?>
<section class="ns-section">
    <div class="ns-heading">
        <h2><i class="bi bi-bullhorn-fill"></i> Notices</h2>
        <p>Latest updates and announcements</p>
    </div>

    <?php if (empty($notices)): ?>
        <div class="ns-empty">
            <i class="bi bi-inbox"></i>
            No notices posted yet.
        </div>
    <?php else: ?>
        <div class="ns-timeline">
            <?php foreach ($notices as $n): ?>
                <div class="ns-item">
                    <div class="ns-date"><?= date('d M', strtotime($n['date'])) ?></div>
                    <div class="ns-line">
                        <span class="ns-dot dot-<?= htmlspecialchars($n['category']) ?>"></span>
                        <div class="ns-content">
                            <span class="ns-badge badge-<?= htmlspecialchars($n['category']) ?>"><?= htmlspecialchars($n['category']) ?></span>
                            <p class="ns-title"><?= htmlspecialchars($n['title']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="ns-viewall-wrap">
        <a href="<?= app_url('/home_page/notice/notice_page.php') ?>" class="ns-viewall">
            View all notices <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</section>