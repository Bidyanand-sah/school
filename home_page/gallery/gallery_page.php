<?php
// gallery/gallery_page.php — public/client-facing, read-only
include '../../backend/con1.php';

$result = $conn->query("SELECT id, img, detail FROM gallery ORDER BY id DESC");
$photos = [];
while ($row = $result->fetch_assoc()) { $photos[] = $row; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Gallery - School Name</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="../comp/nav/nav.css">
    <link rel="stylesheet" href="gallery_page.css">
</head>
<body data-theme="light-blue">

    <?php
    include_once("../comp/nav/nav.php");
  ?>

    <div class="gp-hero">
        <h1><i class="bi bi-images me-2"></i>Our Gallery</h1>
        <p>Moments captured from school life — events, achievements &amp; memories</p>
    </div>

    <div class="gp-backdrop">
        <div class="gp-grid" id="gpGrid">
            <?php if (empty($photos)): ?>
                <div class="gp-empty">
                    <i class="bi bi-image"></i>
                    Gallery coming soon. Stay tuned!
                </div>
            <?php else: ?>
                <?php foreach ($photos as $p): ?>
                    <div class="gp-card"
                         data-img="../../<?= htmlspecialchars($p['img']) ?>"
                         data-detail="<?= htmlspecialchars($p['detail']) ?>">
                        <div class="gp-photo-wrap">
                            <img src="../../<?= htmlspecialchars($p['img']) ?>"
                                 alt="<?= htmlspecialchars($p['detail'] ?: 'Gallery photo') ?>"
                                 loading="lazy" />
                            <div class="gp-zoom-hint"><i class="bi bi-arrows-fullscreen"></i></div>
                        </div>
                        <?php if (!empty($p['detail'])): ?>
                            <div class="gp-caption"><p><?= htmlspecialchars($p['detail']) ?></p></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- LIGHTBOX -->
    <div class="gp-lightbox" id="gpLightbox">
        <button class="gp-lb-close" id="gpLbClose"><i class="bi bi-x-lg"></i></button>
        <button class="gp-lb-nav gp-lb-prev" id="gpLbPrev"><i class="bi bi-chevron-left"></i></button>
        <div class="gp-lb-content">
            <img id="gpLbImg" src="" alt="" />
            <p id="gpLbCaption"></p>
        </div>
        <button class="gp-lb-nav gp-lb-next" id="gpLbNext"><i class="bi bi-chevron-right"></i></button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="gallery_page.js"></script>
</body>
</html>