<?php
// testimonial_section.php — SIRF content, koi html/head/nav/footer/css-link nahi
// Include karne wale ne pehle se $conn banaya hona chahiye (con1.php)
//
// Mode control (optional variable, include karne se PEHLE set karo):
//   $testimonialView = 'home' -> index.php ke liye: split layout (inline form + frosted glass cards)
//   $testimonialView = 'full' -> (default) purana design — testimonial_page.php isi ko use karta hai

if (!isset($testimonialView)) {
    $testimonialView = 'full';
}

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

function renderTsGlassCard($r) {
    echo '<div class="ts-card">
        <div class="ts-stars">' . renderStars($r['rating']) . '</div>
        <p class="ts-text">' . htmlspecialchars($r['review_text']) . '</p>
        <div class="ts-author">
            <div class="ts-avatar">' . strtoupper(substr($r['name'], 0, 1)) . '</div>
            <div>
                <p class="ts-name">' . htmlspecialchars($r['name']) . '</p>
                <p class="ts-role">' . htmlspecialchars($r['class'] ?: 'Parent') . '</p>
            </div>
        </div>
    </div>';
}
?>

<?php if ($testimonialView === 'home'): ?>

<!-- ============================================================
     HOME MODE — sirf index.php ke liye. Left: inline review form
     (koi redirect nahi, seedha add_testimonial.php ko call karta
     hai). Right: frosted-glass scrollable review cards.
     ============================================================ -->
<section class="ts-section">
    <div class="ts-heading">
        <h2><i class="bi bi-chat-quote-fill"></i> What Parents Say</h2>
        <p>Real reviews from our school community</p>
    </div>

    <div class="ts-split">

        <div class="ts-form-side">
            <h3><i class="bi bi-pencil-fill"></i> Share your experience</h3>
            <form id="tsReviewForm">
                <div class="ts-field">
                    <label>Your Rating <span class="ts-req">*</span></label>
                    <div class="ts-star-picker" id="tsStarPicker">
                        <i class="bi bi-star-fill" data-value="1"></i>
                        <i class="bi bi-star-fill" data-value="2"></i>
                        <i class="bi bi-star-fill" data-value="3"></i>
                        <i class="bi bi-star-fill" data-value="4"></i>
                        <i class="bi bi-star-fill" data-value="5"></i>
                    </div>
                    <input type="hidden" id="tsRatingValue" value="5">
                </div>
                <div class="ts-field">
                    <label>Your Name <span class="ts-req">*</span></label>
                    <input type="text" id="tsName" placeholder="e.g. Priya Sharma" required>
                </div>
                <div class="ts-field">
                    <label>Your Review <span class="ts-req">*</span></label>
                    <textarea id="tsText" maxlength="500" placeholder="Apna experience share karein..." required></textarea>
                </div>
                <input type="text" id="tsWebsite" name="website" class="ts-hp-field" tabindex="-1" autocomplete="off">
                <button type="submit" id="tsSubmitBtn">
                    <i class="bi bi-send-fill"></i> Submit Review
                </button>
                <div id="tsFormMsg" class="ts-form-msg"></div>
            </form>
        </div>

        <div class="ts-cards-side">
            <?php if (empty($reviews)): ?>
                <div class="ts-empty">
                    <i class="bi bi-chat-square-dots"></i>
                    No reviews yet. Be the first to share your experience!
                </div>
            <?php else: ?>
                <div class="ts-cards-scroll">
                    <?php foreach ($reviews as $r): renderTsGlassCard($r); endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<script>
(function () {
    var pickerEl = document.getElementById('tsStarPicker');
    if (!pickerEl) return;
    var hiddenInput = document.getElementById('tsRatingValue');
    var stars = pickerEl.querySelectorAll('i');
    var selected = 5;

    function paint(value) {
        stars.forEach(function (star) {
            star.classList.toggle('active', parseInt(star.dataset.value) <= value);
        });
    }
    paint(selected);

    stars.forEach(function (star) {
        star.addEventListener('click', function () {
            selected = parseInt(this.dataset.value);
            hiddenInput.value = selected;
            paint(selected);
        });
        star.addEventListener('mouseenter', function () {
            paint(parseInt(this.dataset.value));
        });
    });
    pickerEl.addEventListener('mouseleave', function () { paint(selected); });

    document.getElementById('tsReviewForm').addEventListener('submit', function (e) {
        e.preventDefault();

        var name = document.getElementById('tsName').value.trim();
        var reviewText = document.getElementById('tsText').value.trim();
        var website = document.getElementById('tsWebsite').value;
        var msgBox = document.getElementById('tsFormMsg');
        var btn = document.getElementById('tsSubmitBtn');

        msgBox.textContent = '';
        msgBox.className = 'ts-form-msg';

        if (!name || !reviewText) {
            msgBox.textContent = 'Name and Review are required';
            msgBox.classList.add('error');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = 'Submitting...';

        var formData = new FormData();
        formData.append('name', name);
        formData.append('review_text', reviewText);
        formData.append('rating', hiddenInput.value);
        formData.append('website', website);

        fetch('<?= app_url('/backend/testimonial/add_testimonial.php') ?>', {
            method: 'POST',
            body: formData
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.success) {
                msgBox.textContent = 'Thank you! Your review has been posted.';
                msgBox.classList.add('success');
                document.getElementById('tsReviewForm').reset();
                selected = 5;
                hiddenInput.value = 5;
                paint(5);
                setTimeout(function () { location.reload(); }, 1200);
            } else {
                msgBox.textContent = data.message;
                msgBox.classList.add('error');
            }
        })
        .catch(function () {
            msgBox.textContent = 'Something went wrong. Please try again.';
            msgBox.classList.add('error');
        })
        .finally(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send-fill"></i> Submit Review';
        });
    });
})();
</script>

<?php else: ?>

<!-- ============================================================
     FULL MODE — bilkul purana design, KOI CHANGE NAHI.
     testimonial_page.php isi block ko use karta hai
     (testimonial_page.css + testimonial_page.js ke saath).
     ============================================================ -->
<div class="tp-hero">
    <h1><i class="bi bi-chat-quote-fill"></i> What Parents Say</h1>
    <p>Real reviews from our school community</p>
    <a href="<?= app_url('/home_page/review/review_page.php') ?>" class="tp-cta">
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

<?php endif; ?>