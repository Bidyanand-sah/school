<?php
require_once __DIR__ . '/../../comp/sidebar/sidebar.php';
require_once __DIR__ . '/../../comp/auth_check.php';

$result = $conn->query("SELECT id, img, detail FROM gallery ORDER BY id DESC");
$photos = [];
while ($row = $result->fetch_assoc()) { $photos[] = $row; }
$totalPhotos = count($photos);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="../../comp/nav/nav.css">
    <link rel="stylesheet" href="../../comp/sidebar/sidebar.css">
    <link rel="stylesheet" href="gallery.css">
</head>
<body data-theme="light-blue">

    <?php require_once __DIR__ . '/../../comp/nav/nav.php'; ?>

    <div class="main-container">
        <?php require_once __DIR__ . '/../../comp/sidebar/sidebar.php'; ?>

        <div class="main-content" id="mainContent">

            <header class="gallery-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="brand"><i class="bi bi-images"></i> Gallery<span style="font-weight:300;">Manager</span></div>
                    <span class="count-badge"><i class="bi bi-collection"></i> Photos: <span id="totalPhotos"><?= $totalPhotos ?></span></span>
                </div>
            </header>

            <section class="gallery-actionbar">
                <button class="btn-add-photo" data-bs-toggle="modal" data-bs-target="#addPhotoModal">
                    <i class="bi bi-image-fill"></i> Add Photo
                </button>
                <small class="text-muted"><i class="bi bi-info-circle"></i> Hover a photo to edit / delete</small>
            </section>
            <!-- gallery grid  -->
            <div class="gallery-backdrop">
                <div class="gallery-grid" id="galleryGrid">
                    <?php if (empty($photos)): ?>
                        <div class="empty-gallery">
                            <i class="bi bi-image"></i>
                            No photos added yet. Click <strong>"Add Photo"</strong> to get started.
                        </div>
                    <?php else: ?>
                        <?php foreach ($photos as $p): ?>
                            <div class="gallery-card"
                                 data-id="<?= $p['id'] ?>"
                                 data-detail="<?= htmlspecialchars($p['detail']) ?>"
                                 data-img="<?= htmlspecialchars('../../../' . $p['img']) ?>">

                                <div class="gallery-photo-wrap">
                                    <div class="gallery-actions">
                                        <button class="btn-edit" data-id="<?= $p['id'] ?>" title="Edit"><i class="bi bi-pencil"></i></button>
                                        <button class="btn-delete" data-id="<?= $p['id'] ?>" title="Delete"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <img src="../../../<?= htmlspecialchars($p['img']) ?>" alt="gallery photo" loading="lazy" />
                                </div>

                                <div class="gallery-caption">
                                    <?php if (!empty($p['detail'])): ?>
                                        <p><?= htmlspecialchars($p['detail']) ?></p>
                                    <?php else: ?>
                                        <p class="no-detail">No description added</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- MODAL: Add Photo -->
            <div class="modal fade" id="addPhotoModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-image-fill me-2"></i>Add New Photo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addPhotoForm">
                                <div class="mb-3">
                                    <label class="form-label">Photo *</label>
                                    <input type="file" class="form-control" id="gImage" accept="image/*" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Detail / Caption</label>
                                    <textarea class="form-control" id="gDetail" rows="3" placeholder="e.g. Annual Sports Day 2026"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="savePhotoBtn">
                                <i class="bi bi-check-lg"></i> Add Photo
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL: Edit Photo -->
            <div class="modal fade" id="editPhotoModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Photo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="editId" />
                            <div class="mb-3">
                                <img id="editPreviewImg" src="#" style="max-width:120px; border-radius:12px; display:none;" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Replace Photo</label>
                                <input type="file" class="form-control" id="editImage" accept="image/*" />
                                <small class="text-muted">Leave empty to keep current photo</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Detail / Caption</label>
                                <textarea class="form-control" id="editDetail" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="updatePhotoBtn">
                                <i class="bi bi-check-lg"></i> Update Photo
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
    <script src="gallery.js"></script>
</body>
</html>