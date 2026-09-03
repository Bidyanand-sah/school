// ===== TAB SWITCHING =====
document.querySelectorAll('.ss-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.ss-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');

        const target = this.dataset.tab;
        document.querySelectorAll('.ss-tab-content').forEach(c => c.style.display = 'none');
        document.getElementById(target).style.display = 'block';
    });
});

// ===== IMAGE FILE NAME PREVIEW =====
document.querySelectorAll('.file-input').forEach(input => {
    input.addEventListener('change', function() {
        const fileNameSpan = this.parentElement.querySelector('.file-name');
        if (this.files && this.files[0]) {
            fileNameSpan.textContent = this.files[0].name;
            // Show preview
            const preview = this.parentElement.querySelector('.preview-img');
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            fileNameSpan.textContent = 'No file chosen';
        }
    });
});

// ===== SAVE ALL =====
document.getElementById('saveAllBtn').addEventListener('click', function() {
    const btn = this;
    const msg = document.getElementById('saveMsg');
    msg.textContent = '';
    msg.className = '';

    // Collect all form data
    const fields = [
        'site_name', 'whatsapp_number', 'whatsapp_message',
        'hero_heading', 'hero_subtext',
        'hero_badge_text',
        'stat1_value', 'stat1_label',
        'stat2_value', 'stat2_label',
        'stat3_value', 'stat3_label',
        'stat4_value', 'stat4_label',
        'about_tag', 'about_heading', 'about_text',
        'about_point1_title', 'about_point1_text',
        'about_point2_title', 'about_point2_text',
        'about_point3_title', 'about_point3_text',
        'about_point4_title', 'about_point4_text',
        'footer_brand', 'footer_tagline',
        'footer_social_facebook', 'footer_social_instagram',
        'footer_social_youtube', 'footer_social_twitter',
        'footer_link1_text', 'footer_link1_url',
        'footer_link2_text', 'footer_link2_url',
        'footer_link3_text', 'footer_link3_url',
        'footer_link4_text', 'footer_link4_url',
        'footer_address', 'footer_phone', 'footer_email', 'footer_copyright',
        'contact_heading', 'contact_subtext', 'contact_hours',
        'contact_address', 'contact_phone', 'contact_email', 'contact_map_link'
    ];

    const formData = new FormData();

    fields.forEach(id => {
        const el = document.getElementById(id);
        if (el) formData.append(id, el.value.trim());
    });

    // Image files
    const imageFields = ['site_logo', 'hero_bg', 'about_img'];
    imageFields.forEach(id => {
        const input = document.getElementById(id);
        if (input && input.files && input.files[0]) {
            formData.append(id, input.files[0]);
        }
    });

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Saving...';
    msg.textContent = 'Saving...';

    fetch('../../../backend/site_content/update_site_content.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            msg.textContent = '✅ ' + data.message;
            msg.className = 'success';
            setTimeout(() => location.reload(), 1200);
        } else {
            msg.textContent = '❌ ' + data.message;
            msg.className = 'error';
        }
    })
    .catch(err => {
        msg.textContent = '❌ Network error: ' + err.message;
        msg.className = 'error';
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check2-circle"></i> Save All Changes';
    });
});