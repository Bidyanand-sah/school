/* ============== STAR PICKERS (Add + Edit) ============== */
function setupStarPicker(pickerId, hiddenId, defaultVal) {
    const stars = document.querySelectorAll(`#${pickerId} i`);
    const hiddenInput = document.getElementById(hiddenId);
    let selected = defaultVal;

    function paint(value) {
        stars.forEach(star => {
            star.classList.toggle('active', parseInt(star.dataset.value) <= value);
        });
    }
    paint(selected);

    stars.forEach(star => {
        star.addEventListener('click', function () {
            selected = parseInt(this.dataset.value);
            hiddenInput.value = selected;
            paint(selected);
        });
        star.addEventListener('mouseenter', function () {
            paint(parseInt(this.dataset.value));
        });
    });
    document.getElementById(pickerId).addEventListener('mouseleave', function () {
        paint(selected);
    });

    return { setValue: (v) => { selected = v; hiddenInput.value = v; paint(v); } };
}

const addStarPicker = setupStarPicker('starPickerAdd', 'addRatingValue', 5);
const editStarPicker = setupStarPicker('starPickerEdit', 'editRatingValue', 5);

/* ============== ADD TESTIMONIAL ============== */
document.getElementById('saveTmBtn').addEventListener('click', function () {
    const name = document.getElementById('addName').value.trim();
    const studentClass = document.getElementById('addClass').value.trim();
    const review = document.getElementById('addReview').value.trim();
    const rating = document.getElementById('addRatingValue').value;

    if (!name || !review) {
        alert('Name and Review are required');
        return;
    }

    const formData = new FormData();
    formData.append('name', name);
    formData.append('class', studentClass);
    formData.append('review_text', review);
    formData.append('rating', rating);

    const btn = this;
    btn.disabled = true;
    btn.innerHTML = 'Saving...';

    fetch('../../../backend/testimonial/add_testimonial.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-lg"></i> Add Testimonial';
            }
        })
        .catch(err => {
            alert('Something went wrong: ' + err.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Add Testimonial';
        });
});

/* ============== OPEN EDIT MODAL ============== */
document.addEventListener('click', function (e) {
    const editBtn = e.target.closest('.btn-edit');
    if (!editBtn) return;

    const card = editBtn.closest('.tm-card');
    document.getElementById('editId').value = card.dataset.id;
    document.getElementById('editName').value = card.dataset.name || '';
    document.getElementById('editClass').value = card.dataset.class || '';
    document.getElementById('editReview').value = card.dataset.review || '';
    editStarPicker.setValue(parseInt(card.dataset.rating) || 5);

    new bootstrap.Modal(document.getElementById('editTmModal')).show();
});

/* ============== UPDATE TESTIMONIAL ============== */
document.getElementById('updateTmBtn').addEventListener('click', function () {
    const id = document.getElementById('editId').value;
    const name = document.getElementById('editName').value.trim();
    const studentClass = document.getElementById('editClass').value.trim();
    const review = document.getElementById('editReview').value.trim();
    const rating = document.getElementById('editRatingValue').value;

    if (!name || !review) {
        alert('Name and Review are required');
        return;
    }

    const formData = new FormData();
    formData.append('id', id);
    formData.append('name', name);
    formData.append('class', studentClass);
    formData.append('review_text', review);
    formData.append('rating', rating);

    fetch('../../../backend/testimonial/update_testimonial.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(err => alert('Network error: ' + err.message));
});

/* ============== DELETE TESTIMONIAL ============== */
document.addEventListener('click', function (e) {
    const deleteBtn = e.target.closest('.btn-delete');
    if (!deleteBtn) return;

    const card = deleteBtn.closest('.tm-card');
    const id = card.dataset.id;

    if (!confirm('Delete this testimonial permanently?')) return;

    fetch('../../../backend/testimonial/delete_testimonial.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            card.remove();
            const total = document.querySelectorAll('.tm-card').length;
            document.getElementById('totalCount').textContent = total;
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => alert('Network error: ' + err.message));
});