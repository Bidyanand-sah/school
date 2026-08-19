<?php
// testimonial_section.php — SIRF content, koi html/head/nav/footer/css-link nahi
// Is file ko include karne wale ne pehle se $conn banaya hona chahiye (con1.php)

$result = $conn->query("SELECT id, name, class, review_text, rating, created_at FROM testimonials ORDER BY id DESC");
$reviews = [];
while ($row = $result->fetch_assoc()) { $reviews[] = $row; }

function renderStars($rating) {
    $out = '';
    for ($i = 1; $i <= 5; $i++) {
        $out .= $i <= $rating ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
    }
    return $out;
}

function renderTpCard($r) {
    echo '<div class="tp-card">
        <div class="tp-stars">' . renderStars($r['rating']) . '</div>
        <p class="tp-text">' . htmlspecialchars($r['review_text']) . '</p>
        <div class="tp-author">
            <div class="tp-avatar">' . strtoupper(substr($r['name'], 0, 1)) . '</div>
            <div>
                <p class="tp-name">' . htmlspecialchars($r['name']) . '</p>
                <p class="tp-role">' . htmlspecialchars($r['class'] ?: 'Parent') . '</p>
            </div>
        </div>
    </div>';
}
?>
<div class="tp-hero">
    <h1><i class="bi bi-chat-quote-fill"></i> What Parents Say</h1>
    <p>Real reviews from our school community</p>
    <a href="/sms_teacher/home_page/review/review_page.php" class="tp-cta">
        <i class="bi bi-pencil-fill"></i> Write a Review
    </a>
</div>

<div class="tp-backdrop">
    <?php if (empty($reviews)): ?>
        <div class="tp-empty">
            <i class="bi bi-chat-square-dots"></i>
            No reviews yet. Be the first to share your experience!
        </div>
    <?php else: ?>
        <?php
        $row1 = [];
        $row2 = [];
        foreach ($reviews as $i => $r) {
            if ($i % 2 === 0) { $row1[] = $r; } else { $row2[] = $r; }
        }
        ?>
        <div class="tp-outer-wrap">
            <button class="tp-scroll-arrow" id="tpArrowLeft" aria-label="Scroll left">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="tp-rows-col">
                <div class="tp-row" id="tpRow1">
                    <?php foreach ($row1 as $r): renderTpCard($r); endforeach; ?>
                </div>
                <?php if (!empty($row2)): ?>
                <div class="tp-row" id="tpRow2">
                    <?php foreach ($row2 as $r): renderTpCard($r); endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <button class="tp-scroll-arrow" id="tpArrowRight" aria-label="Scroll right">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    <?php endif; ?>
</div>