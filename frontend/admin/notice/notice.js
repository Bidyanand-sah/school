/* ADD NOTICE */
document.getElementById('saveNoticeBtn').addEventListener('click', function () {
    const title = document.getElementById('nTitle').value.trim();
    const category = document.getElementById('nCategory').value;
    const content = document.getElementById('nContent').value.trim();
    const pdfFile = document.getElementById('nPdf').files[0];

    if (!title || !category) {
        alert('Title and Category are required');
        return;
    }

    const formData = new FormData();
    formData.append('title', title);
    formData.append('category', category);
    formData.append('content', content);
    if (pdfFile) formData.append('pdf', pdfFile);

    const btn = document.getElementById('saveNoticeBtn');
    btn.disabled = true;
    btn.innerHTML = 'Saving...';

    fetch('../../../backend/notice/add_notice.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-lg"></i> Add Notice';
            }
        })
        .catch(err => {
            alert('Something went wrong: ' + err.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Add Notice';
        });
});

/* DELETE */
document.addEventListener('click', function (e) {
    const deleteBtn = e.target.closest('.btn-delete');
    if (!deleteBtn) return;

    const item = deleteBtn.closest('.notice-item');
    const id = item.dataset.id;

    if (!confirm('Delete this notice permanently?')) return;

    fetch('../../../backend/notice/delete_notice.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            item.remove();
            updateNoticeCount();
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

    const item = editBtn.closest('.notice-item');
    document.getElementById('editId').value = item.dataset.id;
    document.getElementById('editTitle').value = item.dataset.title || '';
    document.getElementById('editCategory').value = item.dataset.category || '';
    document.getElementById('editContent').value = item.dataset.content || '';
    document.getElementById('editPdf').value = '';

    new bootstrap.Modal(document.getElementById('editNoticeModal')).show();
});

/* UPDATE NOTICE */
document.getElementById('updateNoticeBtn').addEventListener('click', function () {
    const id = document.getElementById('editId').value;
    const title = document.getElementById('editTitle').value.trim();
    const category = document.getElementById('editCategory').value;
    const content = document.getElementById('editContent').value.trim();
    const pdfFile = document.getElementById('editPdf').files[0];

    if (!title || !category) {
        alert('Title and Category are required');
        return;
    }

    const formData = new FormData();
    formData.append('id', id);
    formData.append('title', title);
    formData.append('category', category);
    formData.append('content', content);
    if (pdfFile) formData.append('pdf', pdfFile);

    fetch('../../../backend/notice/update_notice.php', { method: 'POST', body: formData })
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

function updateNoticeCount() {
    const total = document.querySelectorAll('.notice-item[data-id]').length;
    document.getElementById('totalNotices').textContent = total;
}

/* PIN / UNPIN */
document.addEventListener('click', function (e) {
    const pinBtn = e.target.closest('.btn-pin');
    if (!pinBtn) return;

    const id = pinBtn.dataset.id;

    fetch('../../../backend/notice/toggle_pin_notice.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload(); // reload taaki pinned notice top pe reorder ho jaye
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => alert('Network error: ' + err.message));
});