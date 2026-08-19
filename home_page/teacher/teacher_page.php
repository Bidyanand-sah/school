<?php
// home page/teacher/teacher_page.php — public/client-facing, read-only
include '../../backend/con1.php';

function getLatestByType($conn, $type) {
    $stmt = $conn->prepare("SELECT id, name, type, subject, bio, img FROM teacher WHERE type = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

$director      = getLatestByType($conn, 'Director');
$principal     = getLatestByType($conn, 'Principal');
$vicePrincipal = getLatestByType($conn, 'Vice Principal');

$teacherResult = $conn->query("SELECT id, name, type, subject, bio, img FROM teacher WHERE type = 'Teacher' ORDER BY id DESC");
$teachers = [];
while ($row = $teacherResult->fetch_assoc()) {
    $teachers[] = $row;
}

function printAdminCard($data, $roleLabel) {
    if ($data) {
        $img = $data['img'] ? '../../' . $data['img'] : 'https://i.pravatar.cc/200';
        echo '<div class="col-card d-flex">
            <div class="admin-card w-100">
                <div class="img-wrap"><img src="' . htmlspecialchars($img) . '" alt="' . htmlspecialchars($data['name']) . '" /></div>
                <div class="card-title">' . htmlspecialchars($data['name']) . '</div>
                <div class="card-role">' . htmlspecialchars($data['type']) . '</div>
                <div class="card-text">' . htmlspecialchars($data['bio']) . '</div>
            </div>
        </div>';
    } else {
        echo '<div class="col-card d-flex">
            <div class="admin-card w-100">
                <div class="img-wrap"><i class="bi bi-person-circle" style="font-size:3rem;"></i></div>
                <div class="card-title">Coming Soon</div>
                <div class="card-role">' . htmlspecialchars($roleLabel) . '</div>
            </div>
        </div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Teachers - School Name</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <!-- Wahi CSS jo admin panel mein use hui — design bilkul same rahega -->
    
    <link rel="stylesheet" href="../comp/nav/nav.css">
    <link rel="stylesheet" href="../../frontend/admin/teacher/teacher.css">
    <link rel="stylesheet" href="teacher_page.css">
</head>

<body data-theme="light-blue">

    <?php
    include_once("../comp/nav/nav.php");
  ?>

    <div class="main-content" id="publicContent">

        <header class="admin-header">
            <div class="container-fluid px-3 px-md-4">
                <div class="brand">
                    <i class="bi bi-mortarboard-fill"></i> Our<span style="font-weight:300;">Teachers</span>
                </div>
            </div>
        </header>

        <!-- TOP SECTION – Director / Principal / Vice Principal -->
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

        <!-- BOTTOM SECTION – Teaching Staff -->
        <section class="bottom-section" id="bottomSection">
            <div class="container-fluid px-3 px-md-4 d-flex flex-column h-100">

                <div class="section-label">
                    <span>
                        <i class="bi bi-person-lines-fill me-1"></i> Meet Our Teachers
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
                                Teacher information coming soon.
                            </div>
                        <?php else: ?>
                            <?php foreach ($teachers as $t): ?>
                                <div class="teacher-card">
                                    <div class="img-wrap">
                                        <?php if ($t['img']): ?>
                                            <img src="../../<?= htmlspecialchars($t['img']) ?>" alt="<?= htmlspecialchars($t['name']) ?>" loading="lazy" />
                                        <?php else: ?>
                                            <i class="bi bi-person-circle"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="t-name"><?= htmlspecialchars($t['name']) ?></div>
                                    <div class="t-subject"><?= htmlspecialchars($t['subject']) ?></div>
                                    <div class="t-text"><?= htmlspecialchars($t['bio'] ?: '') ?></div>
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

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="teacher_page.js"></script>
</body>

</html>