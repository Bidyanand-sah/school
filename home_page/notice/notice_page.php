<?php
include '../../backend/con1.php';

$result = $conn->query("SELECT id, title, content, category, pdf, pinned, date FROM notice ORDER BY pinned DESC, id DESC");
$notices = [];
while ($row = $result->fetch_assoc()) { $notices[] = $row; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices - School Name</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="../comp/nav/nav.css">
    <link rel="stylesheet" href="notice_page.css">
    <link rel="stylesheet" href="../comp/footer/footer.css">
</head>
<body data-theme="light-blue">

    <?php
    include_once("../comp/nav/nav.php");
  ?>

    <div class="np-hero">
        <h1><i class="bi bi-bullhorn me-2"></i>School Notices</h1>
        <p>Latest announcements, circulars &amp; updates</p>
    </div>

    <div class="np-body">
        <?php if (empty($notices)): ?>
            <div class="np-empty">
                <i class="bi bi-inbox"></i>
                No notices posted yet.
            </div>
        <?php else: ?>
            <?php foreach ($notices as $n): ?>
                <div class="np-item <?= $n['pinned'] ? 'is-pinned' : '' ?>">
                    <div class="np-strip strip-<?= htmlspecialchars($n['category']) ?>"></div>
                    <div class="np-content">
                        <div class="np-meta">
                            <?php if ($n['pinned']): ?>
                                <span class="np-pinned-tag"><i class="bi bi-pin-angle-fill"></i> Pinned</span>
                            <?php endif; ?>
                            <span class="np-badge badge-<?= htmlspecialchars($n['category']) ?>"><?= htmlspecialchars($n['category']) ?></span>
                            <span class="np-date"><?= date('d M Y', strtotime($n['date'])) ?></span>
                        </div>
                        <div class="np-title"><?= htmlspecialchars($n['title']) ?></div>
                        <div class="np-text"><?= nl2br(htmlspecialchars($n['content'])) ?></div>
                        <?php if (!empty($n['pdf'])): ?>
                            <a class="np-attachment" href="../../<?= htmlspecialchars($n['pdf']) ?>" target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> Download attachment
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php include_once("../comp/footer/footer.php"); ?>

</body>
</html>