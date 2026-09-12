<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave a Review - Bright Future International School</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="../comp/nav/nav.css">
    <link rel="stylesheet" href="review_page.css">
    <link rel="stylesheet" href="../comp/footer/footer.css">
</head>
<body>
<?php require_once __DIR__ . '/../comp/nav/nav.php';?>

<section class="review-hero">
    <h1><i class="bi bi-chat-heart-fill"></i> Share Your Experience</h1>
    <p>Aapka feedback hamare liye bahut matter karta hai — apna review share karein.</p>
</section>

<section class="review-form-wrap">
    <form id="reviewForm" class="review-card">

        <div class="field">
            <label>Your Rating <span class="req">*</span></label>
            <div class="star-picker" id="starPicker">
                <i class="bi bi-star-fill" data-value="1"></i>
                <i class="bi bi-star-fill" data-value="2"></i>
                <i class="bi bi-star-fill" data-value="3"></i>
                <i class="bi bi-star-fill" data-value="4"></i>
                <i class="bi bi-star-fill" data-value="5"></i>
            </div>
            <input type="hidden" id="ratingValue" value="5">
        </div>

        <div class="field">
            <label>Your Name <span class="req">*</span></label>
            <input type="text" id="rName" placeholder="e.g. Rakesh Kumar" required>
        </div>

        <!-- <div class="field">
            <label>You Are <span class="opt">(optional)</span></label>
            <input type="text" id="rClass" placeholder="e.g. Parent, Class 4 / Alumni">
        </div> -->

        <div class="field">
            <label>Your Review <span class="req">*</span></label>
            <textarea id="rText" maxlength="500" placeholder="Apna experience share karein..." required></textarea>
            <small class="char-hint"><span id="charCount">0</span>/500</small>
        </div>

        <!-- Honeypot — sirf bots ke liye trap, insaan ko nahi dikhta -->
        <input type="text" id="rWebsite" name="website" class="hp-field" tabindex="-1" autocomplete="off">

        <button type="submit" id="submitReviewBtn">
            <i class="bi bi-send-fill"></i> Submit Review
        </button>
        <div id="formMsg" class="form-msg"></div>
    </form>
</section>
<?php require_once __DIR__ . '/../comp/footer/footer.php'; ?>

<script src="review_page.js"></script>
</body>
</html>