document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.ach-outer-wrap').forEach(function (wrap) {
        const row = wrap.querySelector('.ach-row');
        const leftBtn = wrap.querySelector('.ach-arrow-left');
        const rightBtn = wrap.querySelector('.ach-arrow-right');
        if (!row || !leftBtn || !rightBtn) return;

        function cardStep() {
            const firstCard = row.querySelector('.ach-card');
            if (!firstCard) return 280;
            const gap = parseInt(window.getComputedStyle(row).columnGap || 20);
            return firstCard.getBoundingClientRect().width + gap;
        }

        function shift(direction) {
            const maxScroll = row.scrollWidth - row.clientWidth;
            if (maxScroll <= 1) return;

            let target = row.scrollLeft + direction * cardStep();

            if (target >= maxScroll - 5) {
                target = 0;
            } else if (target <= 0) {
                target = maxScroll;
            }
            row.scrollTo({ left: target, behavior: 'smooth' });
        }

        leftBtn.addEventListener('click', function () { shift(-1); });
        rightBtn.addEventListener('click', function () { shift(1); });

        function checkArrows() {
            const canScroll = row.scrollWidth > row.clientWidth + 2;
            leftBtn.style.visibility = canScroll ? 'visible' : 'hidden';
            rightBtn.style.visibility = canScroll ? 'visible' : 'hidden';
        }

        const ro = new ResizeObserver(checkArrows);
        ro.observe(row);
        window.addEventListener('resize', checkArrows);
        setTimeout(checkArrows, 150);
    });
});