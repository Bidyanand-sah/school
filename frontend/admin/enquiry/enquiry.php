<?php
require_once __DIR__ . '/../../../backend/con1.php';
require_once __DIR__ . '/../../comp/auth_check.php';
// Not-called wale pehle dikhenge
$result = $conn->query("SELECT * FROM enquiry ORDER BY is_called ASC, id DESC");
$enquiries = [];
while ($row = $result->fetch_assoc()) {
    $enquiries[] = $row;
}
$total = count($enquiries);
$pending = count(array_filter($enquiries, fn($e) => $e['is_called'] == 0));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquiries - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="../../comp/nav/nav.css">
    <link rel="stylesheet" href="../../comp/sidebar/sidebar.css">
    <link rel="stylesheet" href="enquiry.css">
</head>
<body data-theme="light-blue">

<!-- NavBar -->
    <?php
        require_once __DIR__ . '/../../comp/nav/nav.php';
    ?>
<!-- NavBar -->

<div class="main-container">
    
  <?php
        require_once __DIR__ . '/../../comp/sidebar/sidebar.php';
    ?>

    <div class="main-content" id="mainContent">

        <header class="enq-header">
            <div class="brand"><i class="bi bi-envelope-heart-fill"></i> Enquiries</div>
            <div class="enq-stats">
                <span class="stat-pill"><i class="bi bi-inbox-fill"></i> Total: <strong><?= $total ?></strong></span>
                <span class="stat-pill pending"><i class="bi bi-hourglass-split"></i> Pending: <strong id="pendingCount"><?= $pending ?></strong></span>
            </div>
        </header>

        <section class="enq-list">
            <?php if (empty($enquiries)): ?>
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <p>No enquiries yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($enquiries as $e): ?>
                    <div class="enq-card <?= $e['is_called'] ? 'is-called' : '' ?>" data-id="<?= $e['id'] ?>">
                        <div class="enq-check">
                            <label class="check-wrap">
                                <input type="checkbox" class="calledCheckbox" data-id="<?= $e['id'] ?>" <?= $e['is_called'] ? 'checked' : '' ?>>
                                <span class="checkmark"><i class="bi bi-check-lg"></i></span>
                            </label>
                        </div>
                        <div class="enq-body">
                            <div class="enq-top">
                                <span class="enq-name"><?= htmlspecialchars($e['name']) ?></span>
                                <span class="enq-badge <?= $e['is_called'] ? 'badge-called' : 'badge-pending' ?>">
                                    <?= $e['is_called'] ? 'Called' : 'Pending' ?>
                                </span>
                            </div>
                            <div class="enq-meta">
                                <span><i class="bi bi-telephone-fill"></i> <?= htmlspecialchars($e['phone']) ?></span>
                                <?php if ($e['email']): ?>
                                    <span><i class="bi bi-envelope-fill"></i> <?= htmlspecialchars($e['email']) ?></span>
                                <?php endif; ?>
                                <span><i class="bi bi-clock-fill"></i> <?= date('d M Y, h:i A', strtotime($e['time'])) ?></span>
                            </div>
                            <?php if ($e['message']): ?>
                                <div class="enq-message"><?= htmlspecialchars($e['message']) ?></div>
                            <?php endif; ?>
                        </div>
                        <button class="enq-delete" data-id="<?= $e['id'] ?>" title="Delete">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

    </div>
</div>

<script src="../../comp/nav/nav.js"></script>
<script src="../../comp/sidebar/sidebar.js"></script>
<script src="enquiry.js"></script>
</body>
</html>