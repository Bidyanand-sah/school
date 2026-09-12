<?php
// backend/con1.php se connection le rahe hain
require_once __DIR__ . '/../../../backend/con1.php';
require_once __DIR__ . '/../../comp/auth_check.php';

function getLatestByType($conn, $type) {
    $stmt = $conn->prepare("SELECT id, name, type, subject, bio, img, time FROM teacher WHERE type = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

$director      = getLatestByType($conn, 'Director');
$principal     = getLatestByType($conn, 'Principal');
$vicePrincipal = getLatestByType($conn, 'Vice Principal');

$teacherResult = $conn->query("SELECT id, name, type, subject, bio, img, time FROM teacher WHERE type = 'Teacher' ORDER BY id DESC");
$teachers = [];
while ($row = $teacherResult->fetch_assoc()) {
    $teachers[] = $row;
}

// Correct printAdminCard with data attributes and proper edit button
function printAdminCard($data, $roleLabel) {
    if ($data) {
        $img = $data['img'] ? '../../../' . $data['img'] : '';
        echo '<div class="col-card d-flex">
            <div class="admin-card w-100" 
                 data-id="' . $data['id'] . '"
                 data-name="' . htmlspecialchars($data['name']) . '"
                 data-type="' . htmlspecialchars($data['type']) . '"
                 data-subject="' . htmlspecialchars($data['subject'] ?? '') . '"
                 data-bio="' . htmlspecialchars($data['bio'] ?? '') . '"
                 data-img="' . htmlspecialchars($img) . '">
                <div class="card-actions">
                    <button class="btn-edit" data-id="' . $data['id'] . '" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn-delete" data-id="' . $data['id'] . '" title="Delete"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="img-wrap"><img src="' . ($img ?: 'https://i.pravatar.cc/200') . '" alt="' . htmlspecialchars($data['name']) . '" /></div>
                <div class="card-title">' . htmlspecialchars($data['name']) . '</div>
                <div class="card-role">' . htmlspecialchars($data['type']) . '</div>
                <div class="card-text">' . htmlspecialchars($data['bio']) . '</div>
                <span class="badge-fixed"><i class="bi bi-pin-fill"></i> fixed</span>
            </div>
        </div>';
    } else {
        // placeholder – no buttons
        echo '<div class="col-card d-flex">
            <div class="admin-card w-100" data-id="0">
                <div class="card-actions" style="display:none;"></div>
                <div class="img-wrap"><i class="bi bi-person-circle" style="font-size:3rem;"></i></div>
                <div class="card-title">Not added yet</div>
                <div class="card-role">' . htmlspecialchars($roleLabel) . '</div>
                <span class="badge-fixed"><i class="bi bi-pin-fill"></i> fixed</span>
            </div>
        </div>';
    }
}

// Helpers for stats
function countWithImage($arr) { $c=0; foreach($arr as $t) if(!empty($t['img'])) $c++; return $c; }
function countWithBio($arr)   { $c=0; foreach($arr as $t) if(!empty($t['bio'])) $c++; return $c; }
$totalTeachers = count($teachers);
$totalImages   = countWithImage($teachers);
$totalTexts    = countWithBio($teachers);
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
    <link rel="stylesheet" href="teacher.css">
</head>
<body data-theme="light-blue">

    <!-- Navbar -->
    <?php
        require_once __DIR__ . '/../../comp/nav/nav.php';

    ?>

    <div class="main-container">
        <?php
            require_once __DIR__ . '/../../comp/nav/nav.php';

        ?>
        <!-- Main Content -->
        <div class="main-content" id="mainContent">

            <header class="admin-header">
                <div class="container-fluid px-3 px-md-4">
                    <div class="row align-items-center g-2">
                        <div class="col-6 col-md-4">
                            <div class="brand">
                                <i class="bi bi-mortarboard-fill"></i> Teacher<span style="font-weight:300;">Panel</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-8 text-end">
                            <span class="badge-stats d-inline-flex align-items-center">
                                <i class="bi bi-database"></i> DB:
                                <span id="dbStats" class="ms-1">0 img · 0 txt</span>
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            <section class="stats-row">
                <div class="container-fluid px-3 px-md-4">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-9">
                            <div class="d-flex flex-wrap gap-2 gap-md-3">
                                <div class="stat-item"><i class="bi bi-people-fill"></i> Teachers: <span class="num" id="totalTeachers"><?= $totalTeachers ?></span></div>
                                <div class="stat-item"><i class="bi bi-image-fill"></i> Images: <span class="num" id="totalImages"><?= $totalImages ?></span></div>
                                <div class="stat-item"><i class="bi bi-file-text-fill"></i> Texts: <span class="num" id="totalTexts"><?= $totalTexts ?></span></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 text-md-end">
                            <small class="text-muted-light"><i class="bi bi-clock-history"></i> live</small>
                        </div>
                    </div>
                </div>
            </section>

            <section class="action-bar">
                <div class="container-fluid px-3 px-md-4">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                                <i class="bi bi-person-plus-fill"></i> Add Teacher Details
                            </button>
                            <span class="ms-3 text-muted-light" style="font-size:0.85rem;">
                                <i class="bi bi-info-circle"></i> Top 3 are fixed (Admin), bottom is dynamic
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TOP SECTION -->
            <section class="top-section" id="topSection">
                <div class="container-fluid px-3 px-md-4">
                    <div class="row g-3 g-md-4 h-100 align-items-stretch">
                        <?php
                        printAdminCard($director, 'Director');
                        printAdminCard($principal, 'Principal');
                        printAdminCard($vicePrincipal, 'Vice Principal');
                        ?>
                    </div>
                </div>
            </section>

            <!-- BOTTOM SECTION -->
            <section class="bottom-section" id="bottomSection">
                <div class="container-fluid px-3 px-md-4 d-flex flex-column h-100">
                    <div class="section-label">
                        <span>
                            <i class="bi bi-person-lines-fill me-1"></i> Teaching Staff
                            <span class="teacher-count" id="teacherCountLabel"><?= $totalTeachers ?> teachers</span>
                        </span>
                        <small class="text-muted-light"><i class="bi bi-arrow-left-right"></i> scroll / arrows</small>
                    </div>

                    <div class="scroll-wrapper flex-grow-1">
                        <button class="scroll-arrow left" id="scrollLeftBtn" aria-label="Scroll left">
                            <i class="bi bi-chevron-left"></i>
                        </button>

                        <div class="scroll-container" id="teacherScrollContainer">
                            <?php if (empty($teachers)): ?>
                                <div class="empty-teachers">
                                    <i class="bi bi-person-x-fill"></i>
                                    No teachers added yet.<br>
                                    Click <strong>"Add Teacher Details"</strong> to get started.
                                </div>
                            <?php else: ?>
                                <?php foreach ($teachers as $t): ?>
                                    <!-- TEACHER CARD with full data attributes -->
                                    <div class="teacher-card" 
                                         data-id="<?= $t['id'] ?>"
                                         data-name="<?= htmlspecialchars($t['name']) ?>"
                                         data-type="<?= htmlspecialchars($t['type']) ?>"
                                         data-subject="<?= htmlspecialchars($t['subject'] ?? '') ?>"
                                         data-bio="<?= htmlspecialchars($t['bio'] ?? '') ?>"
                                         data-img="<?= htmlspecialchars($t['img'] ? '../../../' . $t['img'] : '') ?>">
                                        <div class="card-actions">
                                            <button class="btn-edit" data-id="<?= $t['id'] ?>" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <button class="btn-delete" data-id="<?= $t['id'] ?>" title="Delete"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                        <div class="img-wrap">
                                            <?php if ($t['img']): ?>
                                                <img src="../../../<?= htmlspecialchars($t['img']) ?>" alt="<?= htmlspecialchars($t['name']) ?>" loading="lazy" />
                                            <?php else: ?>
                                                <i class="bi bi-person-circle"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="t-name"><?= htmlspecialchars($t['name']) ?></div>
                                        <div class="t-subject"><?= htmlspecialchars($t['subject']) ?></div>
                                        <div class="t-text"><?= htmlspecialchars($t['bio'] ?: '—') ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <button class="scroll-arrow right" id="scrollRightBtn" aria-label="Scroll right">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </section>

            <!-- MODAL – Add Teacher -->
            <div class="modal fade" id="addTeacherModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Add New Teacher</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addTeacherForm">
                                <div class="mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="tName" placeholder="e.g. Mr. Amit Singh" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Type *</label>
                                    <select class="form-control" id="tType" required>
                                        <option value="">-- Select Type --</option>
                                        <option value="Director">Director</option>
                                        <option value="Principal">Principal</option>
                                        <option value="Vice Principal">Vice Principal</option>
                                        <option value="Teacher">Teacher</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Subject / Department *</label>
                                    <input type="text" class="form-control" id="tSubject" placeholder="e.g. Mathematics" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Short Bio / Text</label>
                                    <input type="text" class="form-control" id="tText" placeholder="e.g. 10 years of teaching experience" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Teacher Image</label>
                                    <input type="file" class="form-control" id="tImage" accept="image/*" />
                                    <small class="text-muted-light">Choose an image (JPG, PNG, etc.)</small>
                                    <div id="imagePreview" class="mt-2" style="display:none;">
                                        <img id="previewImg" src="#" alt="Preview" style="max-width:100px; border-radius:8px;" />
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="saveTeacherBtn">
                                <i class="bi bi-check-lg"></i> Add Teacher
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL – Edit Teacher -->
            <div class="modal fade" id="editTeacherModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Teacher</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editTeacherForm">
                                <input type="hidden" id="editId" />
                                <div class="mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="editName" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Type *</label>
                                    <select class="form-control" id="editType" required>
                                        <option value="">-- Select Type --</option>
                                        <option value="Director">Director</option>
                                        <option value="Principal">Principal</option>
                                        <option value="Vice Principal">Vice Principal</option>
                                        <option value="Teacher">Teacher</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Subject / Department *</label>
                                    <input type="text" class="form-control" id="editSubject" placeholder="e.g. Mathematics" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Short Bio / Text</label>
                                    <input type="text" class="form-control" id="editBio" placeholder="e.g. 10 years of experience" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Teacher Image</label>
                                    <input type="file" class="form-control" id="editImage" accept="image/*" />
                                    <small class="text-muted-light">Leave empty to keep current image</small>
                                    <div id="editImagePreview" class="mt-2">
                                        <img id="editPreviewImg" src="#" alt="Current Image" style="max-width:100px; border-radius:8px; display:none;" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">ID (read‑only)</label>
                                    <input type="text" class="form-control" id="editIdDisplay" disabled />
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="updateTeacherBtn">
                                <i class="bi bi-check-lg"></i> Update Teacher
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
    <script src="teacher.js"></script>
</body>
</html>