<?php
require_once __DIR__ . '/../../../backend/config.php';
require_once __DIR__ . '/../../../backend/con1.php';
require_once __DIR__ . '/../../comp/auth_check.php';

$result = $conn->query("SELECT id, title, content, category, pdf, pinned, date FROM notice ORDER BY pinned DESC, id DESC");
$notices = [];
while ($row = $result->fetch_assoc()) { $notices[] = $row; }
$totalNotices = count($notices);
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
    <link rel="stylesheet" href="notice.css">
</head>
<body data-theme="light-blue">

    <?php require_once __DIR__ . '/../../comp/nav/nav.php'; ?>

    <div class="main-container">
        <?php require_once __DIR__ . '/../../comp/sidebar/sidebar.php'; ?>

        <div class="main-content" id="mainContent">

            <header class="notice-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="brand"><i class="bi bi-bullhorn"></i> Notice<span style="font-weight:300;">Board</span></div>
                    <span class="count-badge"><i class="bi bi-file-earmark-text"></i> Notices: <span id="totalNotices"><?= $totalNotices ?></span></span>
                </div>
            </header>

            <section class="notice-actionbar">
                <button class="btn-add-notice" data-bs-toggle="modal" data-bs-target="#addNoticeModal">
                    <i class="bi bi-plus-lg"></i> Add Notice
                </button>
                <small class="text-muted"><i class="bi bi-info-circle"></i> Hover a notice to pin / edit / delete</small>
            </section>

            <div class="notice-body" id="noticeList">
                <?php if (empty($notices)): ?>
                    <div class="empty-notice">
                        <i class="bi bi-inbox"></i>
                        No notices posted yet. Click <strong>"Add Notice"</strong> to get started.
                    </div>
                <?php else: ?>
                    <?php foreach ($notices as $n): ?>
                        <div class="notice-item <?= $n['pinned'] ? 'is-pinned' : '' ?>"
                             data-id="<?= $n['id'] ?>"
                             data-title="<?= htmlspecialchars($n['title']) ?>"
                             data-content="<?= htmlspecialchars($n['content']) ?>"
                             data-category="<?= htmlspecialchars($n['category']) ?>">

                            <div class="notice-strip strip-<?= htmlspecialchars($n['category']) ?>"></div>

                            <div class="notice-content">

                                <button class="btn-pin <?= $n['pinned'] ? 'active' : '' ?>"
                                        data-id="<?= $n['id'] ?>"
                                        title="<?= $n['pinned'] ? 'Unpin' : 'Pin to top' ?>">
                                    <i class="bi <?= $n['pinned'] ? 'bi-pin-fill' : 'bi-pin' ?>"></i>
                                </button>

                                <div class="notice-actions">
                                    <button class="btn-edit" data-id="<?= $n['id'] ?>" title="Edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn-delete" data-id="<?= $n['id'] ?>" title="Delete"><i class="bi bi-x-lg"></i></button>
                                </div>

                                <div class="notice-meta">
                                    <?php if ($n['pinned']): ?>
                                        <span class="pinned-tag"><i class="bi bi-pin-angle-fill"></i> Pinned</span>
                                    <?php endif; ?>
                                    <span class="notice-badge badge-<?= htmlspecialchars($n['category']) ?>"><?= htmlspecialchars($n['category']) ?></span>
                                    <span class="notice-date"><?= date('d M Y', strtotime($n['date'])) ?></span>
                                </div>

                                <div class="notice-title"><?= htmlspecialchars($n['title']) ?></div>
                                <div class="notice-text"><?= nl2br(htmlspecialchars($n['content'])) ?></div>

                                <?php if (!empty($n['pdf'])): ?>
                                    <a class="notice-attachment" href="../../../<?= htmlspecialchars($n['pdf']) ?>" target="_blank">
                                        <i class="bi bi-paperclip"></i> View attachment
                                    </a>
                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- MODAL: Add Notice -->
            <div class="modal fade" id="addNoticeModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-plus-lg me-2"></i>Add New Notice</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addNoticeForm">
                                <div class="mb-3">
                                    <label class="form-label">Title *</label>
                                    <input type="text" class="form-control" id="nTitle" placeholder="e.g. Half-yearly exam datesheet" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Category *</label>
                                    <select class="form-select" id="nCategory" required>
                                        <option value="">-- Select Category --</option>
                                        <option value="Urgent">Urgent</option>
                                        <option value="Holiday">Holiday</option>
                                        <option value="General">General</option>
                                        <option value="Exam">Exam</option>
                                        <option value="Event">Event</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea class="form-control" id="nContent" rows="3" placeholder="Notice details..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Attachment (PDF)</label>
                                    <input type="file" class="form-control" id="nPdf" accept="application/pdf" />
                                    <small class="text-muted">Optional — PDF only</small>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="saveNoticeBtn">
                                <i class="bi bi-check-lg"></i> Add Notice
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL: Edit Notice -->
            <div class="modal fade" id="editNoticeModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Notice</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="editId" />
                            <div class="mb-3">
                                <label class="form-label">Title *</label>
                                <input type="text" class="form-control" id="editTitle" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Category *</label>
                                <select class="form-select" id="editCategory" required>
                                    <option value="Urgent">Urgent</option>
                                    <option value="Holiday">Holiday</option>
                                    <option value="General">General</option>
                                    <option value="Exam">Exam</option>
                                    <option value="Event">Event</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea class="form-control" id="editContent" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Replace Attachment (PDF)</label>
                                <input type="file" class="form-control" id="editPdf" accept="application/pdf" />
                                <small class="text-muted">Leave empty to keep current attachment</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="updateNoticeBtn">
                                <i class="bi bi-check-lg"></i> Update Notice
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
    <script src="notice.js"></script>
</body>
</html>