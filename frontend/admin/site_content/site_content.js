document.getElementById('saveBtn').addEventListener('click', function () {
    const fd = new FormData();
    fd.append('site_name', document.getElementById('siteName').value.trim());
    fd.append('whatsapp_number', document.getElementById('waNumber').value.trim());
    fd.append('whatsapp_message', document.getElementById('waMessage').value.trim());
    const logoFile = document.getElementById('siteLogo').files[0];
    if (logoFile) fd.append('site_logo', logoFile);

    const msg = document.getElementById('saveMsg');
    msg.textContent = 'Saving...';

    fetch('../../../backend/site_content/update_site_content.php', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(data => {
            msg.textContent = data.success ? 'Saved' : ('Error: ' + data.message);
            if (data.success) setTimeout(() => location.reload(), 800);
        })
        .catch(() => msg.textContent = 'Network error');
});