document.addEventListener('DOMContentLoaded', function () {
    const cards = Array.from(document.querySelectorAll('.gp-card'));
    const lightbox = document.getElementById('gpLightbox');
    const lbImg = document.getElementById('gpLbImg');
    const lbCaption = document.getElementById('gpLbCaption');
    const closeBtn = document.getElementById('gpLbClose');
    const prevBtn = document.getElementById('gpLbPrev');
    const nextBtn = document.getElementById('gpLbNext');

    let currentIndex = 0;

    function openLightbox(index) {
        currentIndex = index;
        const card = cards[index];
        lbImg.src = card.dataset.img;
        lbCaption.textContent = card.dataset.detail || '';
        lightbox.classList.add('active');
    }

    function closeLightbox() {
        lightbox.classList.remove('active');
    }

    function showNext() {
        currentIndex = (currentIndex + 1) % cards.length;
        openLightbox(currentIndex);
    }

    function showPrev() {
        currentIndex = (currentIndex - 1 + cards.length) % cards.length;
        openLightbox(currentIndex);
    }

    cards.forEach((card, i) => {
        card.addEventListener('click', () => openLightbox(i));
    });

    closeBtn.addEventListener('click', closeLightbox);
    nextBtn.addEventListener('click', showNext);
    prevBtn.addEventListener('click', showPrev);

    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) closeLightbox();
    });

    document.addEventListener('keydown', (e) => {
        if (!lightbox.classList.contains('active')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') showNext();
        if (e.key === 'ArrowLeft') showPrev();
    });
});