/* ADD PHOTO */
document.getElementById('savePhotoBtn').addEventListener('click', function () {
    const imageFile = document.getElementById('gImage').files[0];
    const detail = document.getElementById('gDetail').value.trim();

    if (!imageFile) {
        alert('Please select a photo');
        return;
    }

    const formData = new FormData();
    formData.append('image', imageFile);
    formData.append('detail', detail);

    const btn = document.getElementById('savePhotoBtn');
    btn.disabled = true;
    btn.innerHTML = 'Saving...';

    fetch('../../../backend/gallery/add_gallery.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-lg"></i> Add Photo';
            }
        })
        .catch(err => {
            alert('Something went wrong: ' + err.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Add Photo';
        });
});

/* DELETE */
document.addEventListener('click', function (e) {
    const deleteBtn = e.target.closest('.btn-delete');
    if (!deleteBtn) return;

    const card = deleteBtn.closest('.gallery-card');
    const id = card.dataset.id;

    if (!confirm('Delete this photo permanently?')) return;

    fetch('../../../backend/gallery/delete_gallery.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            card.remove();
            updatePhotoCount();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => alert('Network error: ' + err.message));
});

/* OPEN EDIT MODAL */
document.addEventListener('click', function (e) {
    const editBtn = e.target.closest('.btn-edit');
    if (!editBtn) return;

    const card = editBtn.closest('.gallery-card');
    document.getElementById('editId').value = card.dataset.id;
    document.getElementById('editDetail').value = card.dataset.detail || '';
    document.getElementById('editImage').value = '';

    const preview = document.getElementById('editPreviewImg');
    preview.src = card.dataset.img;
    preview.style.display = 'block';

    new bootstrap.Modal(document.getElementById('editPhotoModal')).show();
});

/* UPDATE PHOTO */
document.getElementById('updatePhotoBtn').addEventListener('click', function () {
    const id = document.getElementById('editId').value;
    const detail = document.getElementById('editDetail').value.trim();
    const imageFile = document.getElementById('editImage').files[0];

    const formData = new FormData();
    formData.append('id', id);
    formData.append('detail', detail);
    if (imageFile) formData.append('image', imageFile);

    fetch('../../../backend/gallery/update_gallery.php', { method: 'POST', body: formData })
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

function updatePhotoCount() {
    const total = document.querySelectorAll('.gallery-card[data-id]').length;
    document.getElementById('totalPhotos').textContent = total;
}