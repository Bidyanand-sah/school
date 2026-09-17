<?php
// home_page/teacher/teacher_page.php — public/client-facing, read-only
require_once __DIR__ . '/../../backend/config.php';
require_once __DIR__ . '/../../backend/con1.php';
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
    <link rel="stylesheet" href="../comp/footer/footer.css">
</head>

<body data-theme="light-blue">

    <?php
    require_once __DIR__ . '/../comp/nav/nav.php';
    ?>

    <div class="main-content" id="publicContent">

        <header class="admin-header">
            <div class="container-fluid px-3 px-md-4">
                <div class="brand">
                    <i class="bi bi-mortarboard-fill"></i> Our<span style="font-weight:300;">Teachers</span>
                </div>
            </div>
        </header>

        <!-- ============================================================
             TEACHER SECTION — same section jo home_page (index.php) pe
             bhi include hoti hai. Sirf content yahin se aata hai,
             design/CSS is page ka apna (teacher_page.css) rahega.
             ============================================================ -->
        <?php require_once __DIR__ . '/teacher_section.php'; ?>

    </div>
    <?php require_once __DIR__ . '/../comp/footer/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="teacher_page.js"></script>
</body>

</html>