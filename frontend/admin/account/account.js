// Password show/hide toggle
document.querySelectorAll('.acc-eye-toggle').forEach(btn => {
    btn.addEventListener('click', function () {
        const targetId = this.dataset.target;
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash-fill';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye-fill';
        }
    });
});

document.getElementById('updateAccountBtn').addEventListener('click', function () {
    const currentPassword = document.getElementById('currentPassword').value.trim();
    const newUsername = document.getElementById('newUsername').value.trim();
    const newPassword = document.getElementById('newPassword').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();
    const msgBox = document.getElementById('accMsg');
    const btn = this;

    msgBox.textContent = '';
    msgBox.className = 'acc-msg';

    if (!currentPassword) {
        msgBox.textContent = 'Current password is required';
        msgBox.classList.add('error');
        return;
    }

    if (!newUsername && !newPassword) {
        msgBox.textContent = 'Enter a new username or new password to update';
        msgBox.classList.add('error');
        return;
    }

    if (newPassword && newPassword !== confirmPassword) {
        msgBox.textContent = 'New password and confirm password do not match';
        msgBox.classList.add('error');
        return;
    }

    const formData = new FormData();
    formData.append('current_password', currentPassword);
    formData.append('new_username', newUsername);
    formData.append('new_password', newPassword);
    formData.append('confirm_password', confirmPassword);

    btn.disabled = true;
    btn.innerHTML = 'Saving...';

    fetch('../../../backend/admin/update_account.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            msgBox.textContent = data.message + ' — logging you out to sign in again...';
            msgBox.classList.add('success');
            btn.innerHTML = '<i class="bi bi-box-arrow-right"></i> Redirecting...';

            // Credentials change ho gayi — session invalidate karke login page pe bhejo
            const base = window.APP_BASE_URL || '';
            setTimeout(() => {
                fetch(base + '/backend/logout.php', { method: 'POST' })
                    .finally(() => {
                        window.location.href = base + '/backend/login/login.php';
                    });
            }, 1400);
        } else {
            msgBox.textContent = data.message;
            msgBox.classList.add('error');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Save Changes';
        }
    })
    .catch(() => {
        msgBox.textContent = 'Something went wrong. Please try again.';
        msgBox.classList.add('error');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Save Changes';
    });
});