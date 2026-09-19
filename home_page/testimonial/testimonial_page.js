// testimonial_page.js — "Show more" button: har click par 5 aur reviews khulte hain

document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('tpMore');
    if (!btn) return; // index.php par ya jab reviews kam hon, tab kuch nahi karega

    const STEP = 5;
    const countEl = btn.querySelector('.tp-more-count');

    function updateButton() {
        const left = document.querySelectorAll('.tp-item.tp-hidden').length;
        if (left === 0) {
            btn.style.display = 'none';
        } else {
            countEl.textContent = left;
        }
    }

    btn.addEventListener('click', function () {
        const hidden = document.querySelectorAll('.tp-item.tp-hidden');
        for (let i = 0; i < STEP && i < hidden.length; i++) {
            hidden[i].classList.remove('tp-hidden');
        }
        updateButton();
    });
});