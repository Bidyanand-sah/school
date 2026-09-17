<?php
// teacher_section.php — SIRF content, koi html/head/nav/footer/css-link nahi
// Include karne wale ne pehle se $conn (con1.php) aur config.php (app_url ke liye) bana rakha hona chahiye.
//
// Mode control (optional variable, include karne se PEHLE set karo):
//   $teacherView = 'home'  -> sirf top-3 leadership (Director/Principal/VP) carousel design mein
//   $teacherView = 'full'  -> (default, agar variable set nahi kiya) top-3 grid design + poora teacher list scroll-row

if (!isset($teacherView)) {
    $teacherView = 'full';
}

function getLatestByType_ts($conn, $type) {
    $stmt = $conn->prepare("SELECT id, name, type, subject, bio, img FROM teacher WHERE type = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

$leadershipTypes = ['Director', 'Principal', 'Vice Principal'];
$leaders = [];
foreach ($leadershipTypes as $type) {
    $row = getLatestByType_ts($conn, $type);
    if ($row) {
        $leaders[] = $row;
    }
}

function printAdminCard_ts($data, $roleLabel) {
    if ($data) {
        $img = $data['img'] ? app_url($data['img']) : 'https://i.pravatar.cc/200';
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

<?php if ($teacherView === 'home'): ?>

    <!-- ============================================================
         HOME MODE — sirf top-3 leadership, spotlight carousel design
         ============================================================ -->
    <div class="th-section">
        <div class="th-heading">
            <h2><i class="bi bi-mortarboard-fill"></i> Our Leadership</h2>
            <p>Meet the people guiding our school</p>
        </div>

        <?php if (empty($leaders)): ?>
            <div class="th-empty">
                <i class="bi bi-person-x-fill"></i>
                Leadership information coming soon.
            </div>
        <?php else: ?>
            <div class="th-carousel" id="thCarousel"
                 data-leaders='<?= htmlspecialchars(json_encode(array_map(function ($l) {
                     return [
                         'name' => $l['name'],
                         'role' => $l['type'],
                         'bio'  => $l['bio'],
                         'img'  => $l['img'] ? app_url($l['img']) : ''
                     ];
                 }, $leaders)), ENT_QUOTES, 'UTF-8') ?>'>

                <button class="th-arrow th-arrow-left" id="thPrev" aria-label="Previous">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="th-track" id="thTrack"></div>

                <button class="th-arrow th-arrow-right" id="thNext" aria-label="Next">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>

            <div class="th-dots" id="thDots"></div>

            <script>
            (function () {
                var carousel = document.getElementById('thCarousel');
                if (!carousel) return;

                var leaders = JSON.parse(carousel.dataset.leaders || '[]');
                if (!leaders.length) return;

                var track = document.getElementById('thTrack');
                var dotsWrap = document.getElementById('thDots');
                var prevBtn = document.getElementById('thPrev');
                var nextBtn = document.getElementById('thNext');

                var idx = 0;
                var animating = false;
                var timer = null;

                function buildSlide(leader) {
                    var slide = document.createElement('div');
                    slide.className = 'th-slide';
                    var avatarHtml = leader.img
                        ? '<img src="' + leader.img + '" alt="' + leader.name + '">'
                        : '<i class="bi bi-person-circle"></i>';
                    slide.innerHTML =
                        '<div class="th-avatar">' + avatarHtml + '</div>' +
                        '<div class="th-name">' + leader.name + '</div>' +
                        '<div class="th-role">' + leader.role + '</div>' +
                        '<div class="th-bio">' + (leader.bio || '') + '</div>';
                    return slide;
                }

                function buildDots() {
                    dotsWrap.innerHTML = '';
                    leaders.forEach(function (_, i) {
                        var dot = document.createElement('div');
                        dot.className = 'th-dot' + (i === 0 ? ' active' : '');
                        dot.addEventListener('click', function () {
                            if (i === idx) return;
                            goTo(i);
                            resetTimer();
                        });
                        dotsWrap.appendChild(dot);
                    });
                }

                function updateDots(activeIdx) {
                    Array.prototype.forEach.call(dotsWrap.children, function (d, i) {
                        d.classList.toggle('active', i === activeIdx);
                    });
                }

                function initRender() {
                    track.innerHTML = '';
                    var slide = buildSlide(leaders[idx]);
                    slide.style.position = 'relative';
                    track.appendChild(slide);
                    buildDots();
                }

                function transitionTo(newIdx, dir) {
                    if (animating) return;
                    animating = true;

                    var oldSlide = track.firstElementChild;
                    var newSlide = buildSlide(leaders[newIdx]);

                    newSlide.style.transform = 'translateX(' + (dir * 100) + '%)';
                    newSlide.style.transition = 'none';
                    track.appendChild(newSlide);

                    void newSlide.offsetWidth;

                    oldSlide.style.transition = 'transform 0.4s ease';
                    newSlide.style.transition = 'transform 0.4s ease';
                    oldSlide.style.transform = 'translateX(' + (-dir * 100) + '%)';
                    newSlide.style.transform = 'translateX(0)';

                    updateDots(newIdx);

                    setTimeout(function () {
                        oldSlide.remove();
                        newSlide.style.position = 'relative';
                        idx = newIdx;
                        animating = false;
                    }, 400);
                }

                function go(dir) {
                    if (leaders.length <= 1) return;
                    var newIdx = (idx + dir + leaders.length) % leaders.length;
                    transitionTo(newIdx, dir);
                }

                function goTo(targetIdx) {
                    var dir = targetIdx > idx ? 1 : -1;
                    transitionTo(targetIdx, dir);
                }

                function resetTimer() {
                    if (timer) clearInterval(timer);
                    if (leaders.length > 1) {
                        timer = setInterval(function () { go(1); }, 2500);
                    }
                }

                prevBtn.addEventListener('click', function () { go(-1); resetTimer(); });
                nextBtn.addEventListener('click', function () { go(1); resetTimer(); });

                initRender();
                resetTimer();
            })();
            </script>
        <?php endif; ?>
    </div>

<?php else: ?>

    <!-- ============================================================
         FULL MODE — purana design: top-3 grid + poora teacher list
         ============================================================ -->
    <section class="top-section" id="topSection">
        <div class="container-fluid px-3 px-md-4">
            <div class="row g-3 g-md-4 h-100 align-items-stretch">
                <?php
                $directorData = null; $principalData = null; $vpData = null;
                foreach ($leaders as $l) {
                    if ($l['type'] === 'Director') $directorData = $l;
                    if ($l['type'] === 'Principal') $principalData = $l;
                    if ($l['type'] === 'Vice Principal') $vpData = $l;
                }
                printAdminCard_ts($directorData, 'Director');
                printAdminCard_ts($principalData, 'Principal');
                printAdminCard_ts($vpData, 'Vice Principal');
                ?>
            </div>
        </div>
    </section>

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
                    <?php
                    $teacherResult = $conn->query("SELECT id, name, type, subject, bio, img FROM teacher WHERE type = 'Teacher' ORDER BY id DESC");
                    $teachers = [];
                    while ($row = $teacherResult->fetch_assoc()) {
                        $teachers[] = $row;
                    }
                    ?>
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
                                        <img src="<?= htmlspecialchars(app_url($t['img'])) ?>" alt="<?= htmlspecialchars($t['name']) ?>" loading="lazy" />
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

<?php endif; ?>