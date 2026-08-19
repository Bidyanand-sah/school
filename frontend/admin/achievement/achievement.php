<?php
include '../../../backend/con1.php';
include '../../comp/auth_check.php';

$achResult = $conn->query("SELECT id, img, title, description, category, is_pinned FROM achievements ORDER BY is_pinned DESC, id DESC");
$achievements = [];
while ($row = $achResult->fetch_assoc()) { $achievements[] = $row; }
$totalAchievements = count($achievements);

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
    <link rel="stylesheet" href="achievement.css">
</head>
<body data-theme="light-blue">

    <?php include_once('../../comp/nav/nav.php'); ?>

    <div class="main-container">
        <?php include_once('../../comp/sidebar/sidebar.php'); ?>

        <div class="main-content" id="mainContent">

           

    
            <section class="gallery-actionbar">
    <div class="d-flex gap-2 flex-wrap">
        <button class="btn-add-photo btn-add-achievement" data-bs-toggle="modal" data-bs-target="#addAchievementModal">
            <i class="bi bi-trophy-fill"></i> Add Achievement
        </button>
    </div>
    <small class="text-muted"><i class="bi bi-info-circle"></i> Hover a card to edit / delete</small>
</section>

        <!-- ============ ACHIEVEMENTS SECTION (admin) ============ -->
<header class="gallery-header ach-admin-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="brand"><i class="bi bi-trophy-fill"></i> Achievement<span style="font-weight:300;">Manager</span></div>
        <span class="count-badge"><i class="bi bi-award"></i> Total: <span id="totalAchievements"><?= $totalAchievements ?></span></span>
    </div>
</header>

<div class="gallery-backdrop">
    <div class="gallery-grid" id="achievementGrid">
        <?php if (empty($achievements)): ?>
            <div class="empty-gallery">
                <i class="bi bi-trophy"></i>
                No achievements added yet. Click <strong>"Add Achievement"</strong> to get started.
            </div>
        <?php else: ?>
            <?php foreach ($achievements as $a): ?>
                <div class="gallery-card ach-admin-card <?= $a['is_pinned'] ? 'ach-admin-pinned' : '' ?>"
                     data-id="<?= $a['id'] ?>"
                     data-title="<?= htmlspecialchars($a['title']) ?>"
                     data-description="<?= htmlspecialchars($a['description']) ?>"
                     data-category="<?= htmlspecialchars($a['category']) ?>"
                     data-pinned="<?= $a['is_pinned'] ?>"
                     data-img="<?= htmlspecialchars('../../../' . $a['img']) ?>">

                    <div class="gallery-photo-wrap">
                        <?php if ($a['is_pinned']): ?>
                            <div class="ach-admin-ribbon"><i class="bi bi-star-fill"></i> Pinned</div>
                        <?php endif; ?>
                        <div class="gallery-actions">
                            <button class="btn-edit-ach" data-id="<?= $a['id'] ?>" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn-delete-ach" data-id="<?= $a['id'] ?>" title="Delete"><i class="bi bi-x-lg"></i></button>
                        </div>
                        <img src="../../../<?= htmlspecialchars($a['img']) ?>" alt="achievement photo" loading="lazy" />
                    </div>

                    <div class="gallery-caption">
                        <span class="ach-admin-badge"><?= htmlspecialchars($a['category']) ?></span>
                        <p style="margin-top:6px;font-weight:600;"><?= htmlspecialchars($a['title']) ?></p>
                        <?php if (!empty($a['description'])): ?>
                            <p><?= htmlspecialchars($a['description']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

            
            <!-- MODAL: Add Achievement -->
<div class="modal fade" id="addAchievementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-trophy-fill me-2"></i>Add New Achievement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addAchievementForm">
                    <div class="mb-3">
                        <label class="form-label">Photo *</label>
                        <input type="file" class="form-control" id="achImage" accept="image/*" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" class="form-control" id="achTitle" placeholder="e.g. State Level Chess Championship" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="achDescription" rows="3" placeholder="Short detail about the achievement"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-control" id="achCategory">
                            <option value="Academic">Academic</option>
                            <option value="Sports">Sports</option>
                            <option value="Cultural">Cultural</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="achPinned">
                        <label class="form-check-label" for="achPinned">
                            Pin this achievement (shows featured at the front)
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveAchievementBtn">
                    <i class="bi bi-check-lg"></i> Add Achievement
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Edit Achievement -->
<div class="modal fade" id="editAchievementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Achievement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editAchId" />
                <div class="mb-3">
                    <img id="editAchPreviewImg" src="#" style="max-width:120px; border-radius:12px; display:none;" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Replace Photo</label>
                    <input type="file" class="form-control" id="editAchImage" accept="image/*" />
                    <small class="text-muted">Leave empty to keep current photo</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Title *</label>
                    <input type="text" class="form-control" id="editAchTitle" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" id="editAchDescription" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select class="form-control" id="editAchCategory">
                        <option value="Academic">Academic</option>
                        <option value="Sports">Sports</option>
                        <option value="Cultural">Cultural</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="editAchPinned">
                    <label class="form-check-label" for="editAchPinned">
                        Pin this achievement (shows featured at the front)
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateAchievementBtn">
                    <i class="bi bi-check-lg"></i> Update Achievement
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
    <script src="achievement.js"></script>
</body>
</html>