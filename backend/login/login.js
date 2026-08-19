document.getElementById('loginForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    const errorMsg = document.getElementById('errorMsg');
    const btn = document.getElementById('loginBtn');

    errorMsg.textContent = '';

    if (!username || !password) {
        errorMsg.textContent = 'Username and password required';
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Signing in...';

    const formData = new FormData();
    formData.append('username', username);
    formData.append('password', password);

    fetch('check_login.php', {   // <-- apne folder depth ke hisaab se path check karna
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = '../../frontend/admin/index.php'; // <-- login ke baad kis page pe jana hai, link tum lagana
            } else {
                errorMsg.textContent = data.message;
                btn.disabled = false;
                btn.textContent = 'Login';
            }
        })
        .catch(err => {
            errorMsg.textContent = 'Something went wrong. Please try again.';
            btn.disabled = false;
            btn.textContent = 'Login';
        });
});


