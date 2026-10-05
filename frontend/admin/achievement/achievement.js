/* ============================================================
   ACHIEVEMENTS ADMIN — add / edit / delete
   Har image upload se pehle browser mein JPEG mein convert hoti hai

/* ---------- ADD ACHIEVEMENT ---------- */

document.getElementById('saveAchievementBtn').addEventListener('click', async function () {
    const btn = this;
    const imageFile = document.getElementById('achImage').files[0];
    const title = document.getElementById('achTitle').value.trim();
    const description = document.getElementById('achDescription').value.trim();
    const category = document.getElementById('achCategory').value;
    const isPinned = document.getElementById('achPinned').checked ? '1' : '0';

    if (!imageFile) {
        alert('Please select a photo');
        return;
    }
    if (!title) {
        alert('Please enter a title');
        return;
    }

    const originalHtml = '<i class="bi bi-check-lg"></i> Add Achievement';
    btn.disabled = true;
    btn.innerHTML = 'Converting...';

    try {
        const jpegBlob = await convertToJpeg(imageFile);

        const formData = new FormData();
        formData.append('image', jpegBlob, 'photo.jpg');
        formData.append('title', title);
        formData.append('description', description);
        formData.append('category', category);
        formData.append('is_pinned', isPinned);

        btn.innerHTML = 'Saving...';

        const res = await fetch('../../../backend/achievement/add_achievement.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    } catch (err) {
        alert(err.message || 'Something went wrong');
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }
});

/* ---------- DELETE ACHIEVEMENT ---------- */

document.addEventListener('click', function (e) {
    const deleteBtn = e.target.closest('.btn-delete-ach');
    if (!deleteBtn) return;

    const card = deleteBtn.closest('.gallery-card');
    const id = card.dataset.id;

    if (!confirm('Delete this achievement permanently?')) return;

    fetch('../../../backend/achievement/delete_achievement.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            card.remove();
            updateAchievementCount();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => alert('Network error: ' + err.message));
});

/* ---------- OPEN EDIT MODAL ---------- */

document.addEventListener('click', function (e) {
    const editBtn = e.target.closest('.btn-edit-ach');
    if (!editBtn) return;

    const card = editBtn.closest('.gallery-card');
    document.getElementById('editAchId').value = card.dataset.id;
    document.getElementById('editAchTitle').value = card.dataset.title || '';
    document.getElementById('editAchDescription').value = card.dataset.description || '';
    document.getElementById('editAchCategory').value = card.dataset.category || 'Other';
    document.getElementById('editAchPinned').checked = card.dataset.pinned === '1';
    document.getElementById('editAchImage').value = '';

    const preview = document.getElementById('editAchPreviewImg');
    preview.src = card.dataset.img;
    preview.style.display = 'block';

    new bootstrap.Modal(document.getElementById('editAchievementModal')).show();
});

/* ---------- UPDATE ACHIEVEMENT ---------- */

document.getElementById('updateAchievementBtn').addEventListener('click', async function () {
    const btn = this;
    const id = document.getElementById('editAchId').value;
    const title = document.getElementById('editAchTitle').value.trim();
    const description = document.getElementById('editAchDescription').value.trim();
    const category = document.getElementById('editAchCategory').value;
    const isPinned = document.getElementById('editAchPinned').checked ? '1' : '0';
    const imageFile = document.getElementById('editAchImage').files[0];

    if (!title) {
        alert('Please enter a title');
        return;
    }

    const originalHtml = '<i class="bi bi-check-lg"></i> Update Achievement';
    btn.disabled = true;
    btn.innerHTML = imageFile ? 'Converting...' : 'Saving...';

    try {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('title', title);
        formData.append('description', description);
        formData.append('category', category);
        formData.append('is_pinned', isPinned);

        if (imageFile) {
            const jpegBlob = await convertToJpeg(imageFile);
            formData.append('image', jpegBlob, 'photo.jpg');
            btn.innerHTML = 'Saving...';
        }

        const res = await fetch('../../../backend/achievement/update_achievement.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    } catch (err) {
        alert(err.message || 'Something went wrong');
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }
});

/* ---------- COUNT ---------- */

function updateAchievementCount() {
    const total = document.querySelectorAll('#achievementGrid .gallery-card[data-id]').length;
    document.getElementById('totalAchievements').textContent = total;
}