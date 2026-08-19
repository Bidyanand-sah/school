// Called checkbox toggle
document.querySelectorAll('.calledCheckbox').forEach(cb => {
    cb.addEventListener('change', function () {
        const id = this.dataset.id;
        const card = this.closest('.enq-card');
        const badge = card.querySelector('.enq-badge');

        fetch('../../../backend/enquiry/toggle_called.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (data.is_called == 1) {
                    card.classList.add('is-called');
                    badge.textContent = 'Called';
                    badge.classList.remove('badge-pending');
                    badge.classList.add('badge-called');
                } else {
                    card.classList.remove('is-called');
                    badge.textContent = 'Pending';
                    badge.classList.remove('badge-called');
                    badge.classList.add('badge-pending');
                }
                updatePendingCount();
            } else {
                alert(data.message);
                this.checked = !this.checked;
            }
        })
        .catch(() => {
            alert('Network error');
            this.checked = !this.checked;
        });
    });
});

// Delete enquiry
document.querySelectorAll('.enq-delete').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        if (!confirm('Delete this enquiry permanently?')) return;

        fetch('../../../backend/enquiry/delete_enquiry.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.closest('.enq-card').remove();
                updatePendingCount();
            } else {
                alert(data.message);
            }
        })
        .catch(() => alert('Network error'));
    });
});

function updatePendingCount() {
    const pendingEl = document.getElementById('pendingCount');
    if (!pendingEl) return;
    const pending = document.querySelectorAll('.enq-card:not(.is-called)').length;
    pendingEl.textContent = pending;
}