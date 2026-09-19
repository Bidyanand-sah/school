<?php
require_once __DIR__ . '/../../backend/config.php';
require_once __DIR__ . '/../../backend/con1.php';

$result = $conn->query("SELECT id, name, class, review_text, rating FROM testimonials ORDER BY id DESC");
$reviews = [];
while ($row = $result->fetch_assoc()) { $reviews[] = $row; }

$total = count($reviews);
$counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
$sum = 0;
foreach ($reviews as $r) {
    $rt = max(1, min(5, (int)$r['rating']));
    $counts[$rt]++;
    $sum += $rt;
}
$avg = $total ? round($sum / $total, 1) : 0;
$visible = 5; // shuru mein kitne reviews dikhane hain

function tpStars($n) {
    $out = '';
    for ($i = 1; $i <= 5; $i++) {
        $out .= $i <= $n ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
    }
    return $out;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonials - Bright Future International School</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="../comp/nav/nav.css">
    <link rel="stylesheet" href="../comp/footer/footer.css">
    <link rel="stylesheet" href="testimonial_page.css">
</head>
<body>
    <?php require_once __DIR__ . '/../comp/nav/nav.php'; ?>

    <div class="tp-hero">
        <h1><i class="bi bi-chat-quote-fill"></i> What Parents Say</h1>
        <p>Real reviews from our school community</p>
    </div>

    <div class="tp-backdrop">
        <?php if ($total === 0): ?>
            <div class="tp-empty">
                <i class="bi bi-chat-square-dots"></i>
                No reviews yet. Be the first to share your experience!
                <a href="<?= app_url('/home_page/review/review_page.php') ?>" class="tp-write-btn tp-empty-btn">
                    <i class="bi bi-pencil-fill"></i> Write a review
                </a>
            </div>
        <?php else: ?>
            <div class="tp-layout">

                <!-- LEFT: SUMMARY -->
                <aside class="tp-summary">
                    <div class="tp-avg"><?= number_format($avg, 1) ?></div>
                    <div class="tp-avg-stars"><?= tpStars((int)round($avg)) ?></div>
                    <div class="tp-total"><?= $total ?> review<?= $total > 1 ? 's' : '' ?></div>

                    <div class="tp-bars">
                        <?php for ($s = 5; $s >= 1; $s--):
                            $pct = round(($counts[$s] / $total) * 100); ?>
                            <div class="tp-bar-row">
                                <span class="tp-bar-label"><?= $s ?></span>
                                <span class="tp-bar-track"><span class="tp-bar-fill" style="width: <?= $pct ?>%;"></span></span>
                                <span class="tp-bar-count"><?= $counts[$s] ?></span>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <a href="<?= app_url('/home_page/review/review_page.php') ?>" class="tp-write-btn">
                        <i class="bi bi-pencil-fill"></i> Write a review
                    </a>
                </aside>

                <!-- RIGHT: REVIEW LIST -->
                <div class="tp-list">
                    <?php foreach ($reviews as $i => $r): ?>
                        <article class="tp-item<?= $i >= $visible ? ' tp-hidden' : '' ?>">
                            <div class="tp-avatar"><?= strtoupper(htmlspecialchars(mb_substr($r['name'], 0, 1))) ?></div>
                            <div class="tp-item-body">
                                <div class="tp-item-top">
                                    <span class="tp-name"><?= htmlspecialchars($r['name']) ?></span>
                                    <span class="tp-stars"><?= tpStars((int)$r['rating']) ?></span>
                                </div>
                                <div class="tp-role"><?= htmlspecialchars($r['class'] ?: 'Parent') ?></div>
                                <p class="tp-text"><?= htmlspecialchars($r['review_text']) ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>

                    <?php if ($total > $visible): ?>
                        <button type="button" class="tp-more" id="tpMore">
                            Show more (<span class="tp-more-count"><?= $total - $visible ?></span>)
                        </button>
                    <?php endif; ?>
                </div>

            </div>
        <?php endif; ?>
    </div>

    <?php require_once __DIR__ . '/../comp/footer/footer.php'; ?>

    <script src="testimonial_page.js"></script>
</body>
</html>