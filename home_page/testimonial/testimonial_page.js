// testimonial_page.js — 1 arrow pair, click par dono rows ek saath shift, koi auto-scroll nahi

document.addEventListener('DOMContentLoaded', function () {
    const rowEls = [document.getElementById('tpRow1'), document.getElementById('tpRow2')].filter(Boolean);
    const leftBtn = document.getElementById('tpArrowLeft');
    const rightBtn = document.getElementById('tpArrowRight');
    if (!rowEls.length || !leftBtn || !rightBtn) return;

    function cardStep(rowEl) {
        const firstCard = rowEl.querySelector('.tp-card');
        if (!firstCard) return 220;
        const gap = parseInt(window.getComputedStyle(rowEl).columnGap || 14);
        return firstCard.getBoundingClientRect().width + gap;
    }

    function shiftAll(direction) {
        rowEls.forEach(function (rowEl) {
            const maxScroll = rowEl.scrollWidth - rowEl.clientWidth;
            if (maxScroll <= 1) return;

            let target = rowEl.scrollLeft + direction * cardStep(rowEl);

            if (target >= maxScroll - 5) {
                target = 0;
            } else if (target <= 0) {
                target = maxScroll;
            }
            rowEl.scrollTo({ left: target, behavior: 'smooth' });
        });
    }

    leftBtn.addEventListener('click', function () { shiftAll(-1); });
    rightBtn.addEventListener('click', function () { shiftAll(1); });

    function checkArrows() {
        const canScroll = rowEls.some(function (rowEl) {
            return rowEl.scrollWidth > rowEl.clientWidth + 2;
        });
        leftBtn.style.visibility = canScroll ? 'visible' : 'hidden';
        rightBtn.style.visibility = canScroll ? 'visible' : 'hidden';
    }

    const ro = new ResizeObserver(checkArrows);
    rowEls.forEach(function (rowEl) { ro.observe(rowEl); });
    window.addEventListener('resize', checkArrows);
    setTimeout(checkArrows, 150);
});