<?php
require_once __DIR__ . '/../../../backend/con1.php';
require_once __DIR__ . '/../../comp/auth_check.php';

$result = $conn->query("SELECT id, name, class, review_text, rating, created_at FROM testimonials ORDER BY id DESC");
$reviews = [];
while ($row = $result->fetch_assoc()) { $reviews[] = $row; }
$total = count($reviews);

function renderStars($rating) {
    $out = '';
    for ($i = 1; $i <= 5; $i++) {
        $out .= $i <= $rating ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
    }
    return $out;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonials - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="../../comp/nav/nav.css">
    <link rel="stylesheet" href="../../comp/sidebar/sidebar.css">
    <link rel="stylesheet" href="testimonial.css">
</head>
<body data-theme="light-blue">

<?php require_once __DIR__ . '/../../comp/nav/nav.php'; ?>

<div class="main-container">
    <?php require_once __DIR__ . '/../../comp/sidebar/sidebar.php'; ?>

    <div class="main-content" id="mainContent">

        <header class="tm-header">
            <div class="brand"><i class="bi bi-chat-quote-fill"></i> Testimonials</div>
            <span class="count-badge"><i class="bi bi-star-fill"></i> Total: <span id="totalCount"><?= $total ?></span></span>
        </header>

        <section class="tm-actionbar">
            <button class="btn-add-tm" data-bs-toggle="modal" data-bs-target="#addTmModal">
                <i class="bi bi-plus-lg"></i> Add Testimonial
            </button>
            <small class="text-muted"><i class="bi bi-info-circle"></i> Hover a card to edit / delete</small>
        </section>

        <div class="tm-grid" id="tmGrid">
            <?php if (empty($reviews)): ?>
                <div class="tm-empty">
                    <i class="bi bi-chat-square-dots"></i>
                    No testimonials yet.
                </div>
            <?php else: ?>
                <?php foreach ($reviews as $r): ?>
                    <div class="tm-card"
                         data-id="<?= $r['id'] ?>"
                         data-name="<?= htmlspecialchars($r['name']) ?>"
                         data-class="<?= htmlspecialchars($r['class']) ?>"
                         data-review="<?= htmlspecialchars($r['review_text']) ?>"
                         data-rating="<?= $r['rating'] ?>">
                        <div class="tm-actions">
                            <button class="btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn-delete" title="Delete"><i class="bi bi-x-lg"></i></button>
                        </div>
                        <div class="tm-stars"><?= renderStars($r['rating']) ?></div>
                        <p class="tm-text"><?= htmlspecialchars($r['review_text']) ?></p>
                        <div class="tm-author">
                            <div class="tm-avatar"><?= strtoupper(substr($r['name'], 0, 1)) ?></div>
                            <div>
                                <p class="tm-name"><?= htmlspecialchars($r['name']) ?></p>
                                <p class="tm-role">Class : <?= htmlspecialchars($r['class'] ?: 'Parent') ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- ADD MODAL -->
        <div class="modal fade" id="addTmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-plus-lg me-2"></i>Add Testimonial</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rating *</label>
                            <div class="star-picker" id="starPickerAdd">
                                <i class="bi bi-star-fill" data-value="1"></i>
                                <i class="bi bi-star-fill" data-value="2"></i>
                                <i class="bi bi-star-fill" data-value="3"></i>
                                <i class="bi bi-star-fill" data-value="4"></i>
                                <i class="bi bi-star-fill" data-value="5"></i>
                            </div>
                            <input type="hidden" id="addRatingValue" value="5">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" id="addName" required>
                        </div>
                        <!-- <div class="mb-3">
                            <label class="form-label">Class / Role</label>
                            <input type="number" class="form-control" id="addClass" placeholder="e.g. Parent, Class 4">
                        </div> -->
                        <div class="mb-3">
                            <label class="form-label">Review *</label>
                            <textarea class="form-control" id="addReview" rows="3" maxlength="500" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveTmBtn">
                            <i class="bi bi-check-lg"></i> Add Testimonial
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- EDIT MODAL -->
        <div class="modal fade" id="editTmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Testimonial</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label class="form-label">Rating *</label>
                            <div class="star-picker" id="starPickerEdit">
                                <i class="bi bi-star-fill" data-value="1"></i>
                                <i class="bi bi-star-fill" data-value="2"></i>
                                <i class="bi bi-star-fill" data-value="3"></i>
                                <i class="bi bi-star-fill" data-value="4"></i>
                                <i class="bi bi-star-fill" data-value="5"></i>
                            </div>
                            <input type="hidden" id="editRatingValue" value="5">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" id="editName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Class / Role</label>
                            <input type="text" class="form-control" id="editClass">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Review *</label>
                            <textarea class="form-control" id="editReview" rows="3" maxlength="500" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="updateTmBtn">
                            <i class="bi bi-check-lg"></i> Update
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../comp/nav/nav.js"></script>
<script src="../../comp/sidebar/sidebar.js"></script>
<script src="testimonial.js"></script>
</body>
</html>