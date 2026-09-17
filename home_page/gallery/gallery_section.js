document.addEventListener('DOMContentLoaded', function () {
    const wrap = document.querySelector('.gs-outer-wrap');
    if (!wrap) return;

    const track = wrap.querySelector('.gs-track');
    const row = wrap.querySelector('.gs-row');
    const leftBtn = wrap.querySelector('.gs-arrow-left');
    const rightBtn = wrap.querySelector('.gs-arrow-right');
    const cards = Array.from(row.querySelectorAll('.gs-card'));

    let autoTimer = null;
    let isPaused = false;

    function cardStep() {
        const firstCard = row.querySelector('.gs-card');
        if (!firstCard) return 160;
        const gap = parseInt(window.getComputedStyle(row).columnGap || 22);
        return firstCard.getBoundingClientRect().width + gap;
    }

    function shift(direction) {
        const maxScroll = row.scrollWidth - track.clientWidth;
        if (maxScroll <= 1) return;

        let target = track.scrollLeft + direction * cardStep();
        if (target >= maxScroll - 5) target = 0;
        else if (target <= 0) target = maxScroll;

        track.scrollTo({ left: target, behavior: 'smooth' });
    }

    function startAutoScroll() {
        autoTimer = setInterval(function () {
            if (!isPaused) shift(1);
        }, 3500);
    }

    function resetAutoScroll() {
        clearInterval(autoTimer);
        startAutoScroll();
    }

    leftBtn.addEventListener('click', function () { shift(-1); resetAutoScroll(); });
    rightBtn.addEventListener('click', function () { shift(1); resetAutoScroll(); });

    wrap.addEventListener('mouseenter', function () { isPaused = true; });
    wrap.addEventListener('mouseleave', function () { isPaused = false; });

    cards.forEach(function (card) {
        const baseRotate = card.dataset.rotate;
        card.addEventListener('mouseenter', function () {
            card.style.transform = 'rotate(0deg) translateY(-14px) scale(1.08)';
        });
        card.addEventListener('mouseleave', function () {
            card.style.transform = 'rotate(' + baseRotate + 'deg)';
        });
    });

    function checkArrows() {
        const canScroll = row.scrollWidth > track.clientWidth + 2;
        leftBtn.style.visibility = canScroll ? 'visible' : 'hidden';
        rightBtn.style.visibility = canScroll ? 'visible' : 'hidden';
    }

    const ro = new ResizeObserver(checkArrows);
    ro.observe(track);
    window.addEventListener('resize', checkArrows);
    setTimeout(checkArrows, 150);

    startAutoScroll();
});