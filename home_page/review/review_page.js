// Star Rating Picker
const stars = document.querySelectorAll('#starPicker i');
const ratingInput = document.getElementById('ratingValue');
let selectedRating = 5;

function paintStars(value) {
    stars.forEach(star => {
        const val = parseInt(star.dataset.value);
        star.classList.toggle('active', val <= value);
    });
}
paintStars(selectedRating);

stars.forEach(star => {
    star.addEventListener('click', function () {
        selectedRating = parseInt(this.dataset.value);
        ratingInput.value = selectedRating;
        paintStars(selectedRating);
    });
    star.addEventListener('mouseenter', function () {
        paintStars(parseInt(this.dataset.value));
    });
});
document.getElementById('starPicker').addEventListener('mouseleave', function () {
    paintStars(selectedRating);
});

// Character counter
const textArea = document.getElementById('rText');
const charCount = document.getElementById('charCount');
textArea.addEventListener('input', function () {
    charCount.textContent = this.value.length;
});

// Form Submit
document.getElementById('reviewForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const name = document.getElementById('rName').value.trim();
    const studentClass = document.getElementById('rClass').value.trim();
    const reviewText = document.getElementById('rText').value.trim();
    const website = document.getElementById('rWebsite').value; // honeypot
    const msgBox = document.getElementById('formMsg');
    const btn = document.getElementById('submitReviewBtn');

    msgBox.textContent = '';
    msgBox.className = 'form-msg';

    if (!name || !reviewText) {
        msgBox.textContent = 'Name and Review are required';
        msgBox.classList.add('error');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = 'Submitting...';

    const formData = new FormData();
    formData.append('name', name);
    formData.append('class', studentClass);
    formData.append('review_text', reviewText);
    formData.append('rating', ratingInput.value);
    formData.append('website', website);

    fetch('../../backend/testimonial/add_testimonial.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            msgBox.textContent = 'Thank you! Your review has been posted.';
            msgBox.classList.add('success');
            document.getElementById('reviewForm').reset();
            selectedRating = 5;
            ratingInput.value = 5;
            paintStars(5);
            charCount.textContent = '0';
        } else {
            msgBox.textContent = data.message;
            msgBox.classList.add('error');
        }
    })
    .catch(() => {
        msgBox.textContent = 'Something went wrong. Please try again.';
        msgBox.classList.add('error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send-fill"></i> Submit Review';
    });
});