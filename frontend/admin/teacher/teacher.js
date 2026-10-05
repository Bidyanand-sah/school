/* ================================================================
   MODAL: TYPE FIELD TOGGLE (Subject required only for "Teacher")
================================================================ */
document.getElementById('tType').addEventListener('change', function () {
    const subjectInput = document.getElementById('tSubject');
    if (this.value === 'Teacher') {
        subjectInput.required = true;
    } else {
        subjectInput.required = false;
        subjectInput.value = '';
    }
});

/* ================================================================
   ADD TEACHER
================================================================ */
document.getElementById('saveTeacherBtn').addEventListener('click', async function () {
    const btn = this;
    const form = document.getElementById('addTeacherForm');

    const name = document.getElementById('tName').value.trim();
    const type = document.getElementById('tType').value;
    const subject = document.getElementById('tSubject').value.trim();
    const bio = document.getElementById('tText').value.trim();
    const imageFile = document.getElementById('tImage').files[0];

    if (!name || !type) {
        alert('Name and Type are required');
        return;
    }
    if (type === 'Teacher' && !subject) {
        alert('Subject is required for Teacher type');
        return;
    }

    const originalHtml = '<i class="bi bi-check-lg"></i> Add Teacher';
    btn.disabled = true;
    btn.innerHTML = imageFile ? 'Converting...' : 'Saving...';

    try {
        const formData = new FormData();
        formData.append('name', name);
        formData.append('type', type);
        formData.append('subject', subject);
        formData.append('bio', bio);

        if (imageFile) {
            const jpegBlob = await convertToJpeg(imageFile);
            formData.append('image', jpegBlob, 'photo.jpg');
            btn.innerHTML = 'Saving...';
        }

        const res = await fetch('../../../backend/teacher/add_teacher.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            form.reset();
            const modal = bootstrap.Modal.getInstance(document.getElementById('addTeacherModal'));
            if (modal) modal.hide();
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

/* ================================================================
   SCROLL NAVIGATION (arrows)
================================================================ */
function setupScrollArrows() {
    const container = document.getElementById('teacherScrollContainer');
    const leftBtn = document.getElementById('scrollLeftBtn');
    const rightBtn = document.getElementById('scrollRightBtn');
    if (!container || !leftBtn || !rightBtn) return;

    const scrollAmount = () => {
        const w = container.clientWidth;
        return Math.min(w * 0.8, 300);
    };

    leftBtn.addEventListener('click', () => container.scrollBy({ left: -scrollAmount(), behavior: 'smooth' }));
    rightBtn.addEventListener('click', () => container.scrollBy({ left: scrollAmount(), behavior: 'smooth' }));

    function toggleArrows() {
        const canScroll = container.scrollWidth > container.clientWidth;
        leftBtn.style.display = canScroll ? 'flex' : 'none';
        rightBtn.style.display = canScroll ? 'flex' : 'none';
    }

    const ro = new ResizeObserver(toggleArrows);
    ro.observe(container);
    window.addEventListener('resize', toggleArrows);
    setTimeout(toggleArrows, 100);

    document.addEventListener('keydown', (e) => {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
        if (e.key === 'ArrowLeft') { e.preventDefault(); leftBtn.click(); }
        else if (e.key === 'ArrowRight') { e.preventDefault(); rightBtn.click(); }
    });
}

document.addEventListener('DOMContentLoaded', setupScrollArrows);

/* ================================================================
   DELETE
================================================================ */
document.addEventListener('click', function (e) {
    const deleteBtn = e.target.closest('.btn-delete');
    if (!deleteBtn) return;

    const card = deleteBtn.closest('.admin-card, .teacher-card');
    if (!card) return;

    const id = card.dataset.id;
    if (!id || id == 0) {
        alert('Invalid teacher ID');
        return;
    }

    if (!confirm('Are you sure you want to permanently delete this teacher?\nThis action cannot be undone.')) {
        return;
    }

    fetch('../../../backend/teacher/delete_teacher.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const parent = card.closest('.col-card') || card;
            parent.remove();
            updateStats();
            alert('Teacher deleted successfully!');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => alert('Network error: ' + err.message));
});

/* ================================================================
   EDIT: open modal from card's data attributes
================================================================ */
document.addEventListener('click', function (e) {
    const editBtn = e.target.closest('.btn-edit');
    if (!editBtn) return;

    const card = editBtn.closest('.admin-card, .teacher-card');
    if (!card) {
        alert('Edit button not inside a valid card.');
        return;
    }

    const id = card.dataset.id;
    if (!id || id == 0) {
        alert('Invalid teacher ID');
        return;
    }

    document.getElementById('editId').value = id;
    document.getElementById('editName').value = card.dataset.name || '';
    document.getElementById('editType').value = card.dataset.type || '';
    document.getElementById('editSubject').value = card.dataset.subject || '';
    document.getElementById('editBio').value = card.dataset.bio || '';
    document.getElementById('editIdDisplay').value = id;

    const imgSrc = card.dataset.img || '';
    const previewImg = document.getElementById('editPreviewImg');
    if (imgSrc) {
        previewImg.src = imgSrc;
        previewImg.style.display = 'block';
    } else {
        previewImg.style.display = 'none';
    }

    document.getElementById('editImage').value = '';

    new bootstrap.Modal(document.getElementById('editTeacherModal')).show();
});

/* ================================================================
   UPDATE
================================================================ */
document.getElementById('updateTeacherBtn').addEventListener('click', async function () {
    const btn = this;
    const id = document.getElementById('editId').value;
    const name = document.getElementById('editName').value.trim();
    const type = document.getElementById('editType').value;
    const subject = document.getElementById('editSubject').value.trim();
    const bio = document.getElementById('editBio').value.trim();
    const imageFile = document.getElementById('editImage').files[0];

    if (!name || !type) {
        alert('Name and Type are required');
        return;
    }
    if (type === 'Teacher' && !subject) {
        alert('Subject is required for Teacher type');
        return;
    }

    const originalHtml = '<i class="bi bi-check-lg"></i> Update Teacher';
    btn.disabled = true;
    btn.innerHTML = imageFile ? 'Converting...' : 'Saving...';

    try {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('name', name);
        formData.append('type', type);
        formData.append('subject', subject);
        formData.append('bio', bio);

        if (imageFile) {
            const jpegBlob = await convertToJpeg(imageFile);
            formData.append('image', jpegBlob, 'photo.jpg');
            btn.innerHTML = 'Saving...';
        }

        const res = await fetch('../../../backend/teacher/update_teacher.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            alert('Teacher updated successfully!');
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

/* ================================================================
   HELPER: stats update after delete
================================================================ */
function updateStats() {
    const totalTeachers = document.querySelectorAll('.teacher-card, .admin-card[data-id]:not([data-id="0"])').length;
    const totalImages = document.querySelectorAll('.teacher-card .img-wrap img, .admin-card .img-wrap img').length;
    const totalTexts = document.querySelectorAll('.teacher-card .t-text, .admin-card .card-text').length;
    document.getElementById('totalTeachers').textContent = totalTeachers;
    document.getElementById('totalImages').textContent = totalImages;
    document.getElementById('totalTexts').textContent = totalTexts;
    document.getElementById('teacherCountLabel').textContent = totalTeachers + ' teachers';
}