document.getElementById('enquiryForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const name = document.getElementById('cName').value.trim();
    const phone = document.getElementById('cPhone').value.trim();
    const email = document.getElementById('cEmail').value.trim();
    const message = document.getElementById('cMessage').value.trim();
    const msgBox = document.getElementById('formMsg');
    const btn = document.getElementById('submitBtn');

    msgBox.textContent = '';
    msgBox.className = 'form-msg';

    if (!name || !phone) {
        msgBox.textContent = 'Name and Phone are required';
        msgBox.classList.add('error');
        return;
    }
    if (!/^[0-9]{10}$/.test(phone)) {
        msgBox.textContent = 'Enter a valid 10 digit phone number';
        msgBox.classList.add('error');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<span class="btn-text">Sending...</span>';

    const formData = new FormData();
    formData.append('name', name);
    formData.append('phone', phone);
    formData.append('email', email);
    formData.append('message', message);

    fetch('../../backend/enquiry/add_enquiry.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            msgBox.textContent = 'Thank you! We will get back to you soon.';
            msgBox.classList.add('success');
            document.getElementById('enquiryForm').reset();
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
        btn.innerHTML = '<span class="btn-text"><i class="bi bi-send-fill"></i> Send Enquiry</span>';
    });
});