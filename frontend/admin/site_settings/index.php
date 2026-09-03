<?php
require_once __DIR__ . '/../../../backend/site_content/site_content_helper.php';
include '../../comp/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../comp/nav/nav.css">
    <link rel="stylesheet" href="../../comp/sidebar/sidebar.css">
    <link rel="stylesheet" href="index.css">
</head>
<body data-theme="light-blue">
<?php include_once('../../comp/nav/nav.php'); ?>
<div class="main-container">
<?php include_once('../../comp/sidebar/sidebar.php'); ?>
<div class="main-content" id="mainContent">

<header class="ss-header">
    <div class="brand"><i class="bi bi-gear-fill"></i> Site Settings</div>
    <p class="ss-subtitle">Manage all website content from one place</p>
</header>

<!-- ===== TABS ===== -->
<div class="ss-tabs">
    <button class="ss-tab active" data-tab="tabNav"><i class="bi bi-house-door"></i> Nav</button>
    <button class="ss-tab" data-tab="tabHero"><i class="bi bi-image"></i> Hero</button>
    <button class="ss-tab" data-tab="tabAbout"><i class="bi bi-book"></i> About</button>
    <button class="ss-tab" data-tab="tabFooter"><i class="bi bi-link"></i> Footer</button>
    <button class="ss-tab" data-tab="tabContact"><i class="bi bi-envelope"></i> Contact</button>
</div>

<!-- ===== TAB CONTENT ===== -->
<div class="ss-tab-content" id="tabNav">
    <div class="ss-card">
        <h3><i class="bi bi-house-door-fill"></i> Navbar</h3>
        <div class="field">
            <label>School Name</label>
            <input type="text" id="site_name" value="<?= htmlspecialchars(getContent('site_name')) ?>">
        </div>
        <div class="field">
            <label>Logo</label>
            <div class="img-preview-row">
                <?php if (getContent('site_logo')): ?>
                    <img src="<?= getContent('site_logo') ?>" id="logoPreview" class="preview-img">
                <?php else: ?>
                    <img src="#" id="logoPreview" class="preview-img" style="display:none;">
                <?php endif; ?>
                <input type="file" id="site_logo" accept="image/*" class="file-input">
                <span class="file-name" id="logoFileName">No file chosen</span>
            </div>
        </div>
        <div class="field">
            <label>WhatsApp Number</label>
            <input type="text" id="whatsapp_number" value="<?= htmlspecialchars(getContent('whatsapp_number')) ?>">
        </div>
        <div class="field">
            <label>WhatsApp Message</label>
            <input type="text" id="whatsapp_message" value="<?= htmlspecialchars(getContent('whatsapp_message')) ?>">
        </div>
    </div>
</div>

<div class="ss-tab-content" id="tabHero" style="display:none;">
    <div class="ss-card">
        <h3><i class="bi bi-image-fill"></i> Hero Section</h3>
        <div class="field">
            <label>Heading</label>
            <input type="text" id="hero_heading" value="<?= htmlspecialchars(getContent('hero_heading')) ?>">
        </div>
        <div class="field">
            <label>Subtext / Tagline</label>
            <input type="text" id="hero_subtext" value="<?= htmlspecialchars(getContent('hero_subtext')) ?>">
        </div>
        <div class="field">
            <label>Background Image</label>
            <div class="img-preview-row">
                <?php if (getContent('hero_bg')): ?>
                    <img src="<?= getContent('hero_bg') ?>" id="heroBgPreview" class="preview-img">
                <?php else: ?>
                    <img src="#" id="heroBgPreview" class="preview-img" style="display:none;">
                <?php endif; ?>
                <input type="file" id="hero_bg" accept="image/*" class="file-input">
                <span class="file-name" id="heroBgFileName">No file chosen</span>
            </div>
        </div>
        <div class="field">
            <label>Badge Text (e.g. "Admissions Open 2026–27")</label>
            <input type="text" id="hero_badge_text" value="<?= htmlspecialchars(getContent('hero_badge_text')) ?>">
        </div>
        <hr>
        <h4>Stats Row (4 boxes shown below the hero text)</h4>
        <small style="display:block;color:#94a3b8;font-size:0.78rem;margin-bottom:10px;">Value mein number ke saath suffix bhi likh sakte ho, e.g. "1200+" — counting-up animation apne aap chalega.</small>
        <div class="field-row">
            <input type="text" id="stat1_value" placeholder="Value e.g. 1200+" value="<?= htmlspecialchars(getContent('stat1_value')) ?>">
            <input type="text" id="stat1_label" placeholder="Label e.g. Students" value="<?= htmlspecialchars(getContent('stat1_label')) ?>">
        </div>
        <div class="field-row">
            <input type="text" id="stat2_value" placeholder="Value e.g. 85+" value="<?= htmlspecialchars(getContent('stat2_value')) ?>">
            <input type="text" id="stat2_label" placeholder="Label e.g. Teachers" value="<?= htmlspecialchars(getContent('stat2_label')) ?>">
        </div>
        <div class="field-row">
            <input type="text" id="stat3_value" placeholder="Value e.g. 15+" value="<?= htmlspecialchars(getContent('stat3_value')) ?>">
            <input type="text" id="stat3_label" placeholder="Label e.g. Years of Excellence" value="<?= htmlspecialchars(getContent('stat3_label')) ?>">
        </div>
        <div class="field-row">
            <input type="text" id="stat4_value" placeholder="Value e.g. 40+" value="<?= htmlspecialchars(getContent('stat4_value')) ?>">
            <input type="text" id="stat4_label" placeholder="Label e.g. Activities & Clubs" value="<?= htmlspecialchars(getContent('stat4_label')) ?>">
        </div>
    </div>
</div>

<div class="ss-tab-content" id="tabAbout" style="display:none;">
    <div class="ss-card">
        <h3><i class="bi bi-book-fill"></i> About Section</h3>
        <div class="field">
            <label>Tag (e.g. "Who We Are")</label>
            <input type="text" id="about_tag" value="<?= htmlspecialchars(getContent('about_tag')) ?>">
        </div>
        <div class="field">
            <label>Heading</label>
            <input type="text" id="about_heading" value="<?= htmlspecialchars(getContent('about_heading')) ?>">
        </div>
        <div class="field">
            <label>Paragraph</label>
            <textarea id="about_text" rows="4"><?= htmlspecialchars(getContent('about_text')) ?></textarea>
        </div>
        <div class="field">
            <label>Image</label>
            <div class="img-preview-row">
                <?php if (getContent('about_img')): ?>
                    <img src="<?= getContent('about_img') ?>" id="aboutImgPreview" class="preview-img">
                <?php else: ?>
                    <img src="#" id="aboutImgPreview" class="preview-img" style="display:none;">
                <?php endif; ?>
                <input type="file" id="about_img" accept="image/*" class="file-input">
                <span class="file-name" id="aboutImgFileName">No file chosen</span>
            </div>
        </div>

        <div class="ss-subsection">
            <h4><i class="bi bi-grid-3x3-gap-fill"></i> Highlight Points (4 boxes shown below the paragraph)</h4>
            <div class="ss-point-box">
                <span class="ss-point-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                <div class="field"><label>Point 1 — Title</label><input type="text" id="about_point1_title" value="<?= htmlspecialchars(getContent('about_point1_title')) ?>"></div>
                <div class="field"><label>Point 1 — Description</label><input type="text" id="about_point1_text" value="<?= htmlspecialchars(getContent('about_point1_text')) ?>"></div>
            </div>
            <div class="ss-point-box">
                <span class="ss-point-icon"><i class="fas fa-school"></i></span>
                <div class="field"><label>Point 2 — Title</label><input type="text" id="about_point2_title" value="<?= htmlspecialchars(getContent('about_point2_title')) ?>"></div>
                <div class="field"><label>Point 2 — Description</label><input type="text" id="about_point2_text" value="<?= htmlspecialchars(getContent('about_point2_text')) ?>"></div>
            </div>
            <div class="ss-point-box">
                <span class="ss-point-icon"><i class="fas fa-heart"></i></span>
                <div class="field"><label>Point 3 — Title</label><input type="text" id="about_point3_title" value="<?= htmlspecialchars(getContent('about_point3_title')) ?>"></div>
                <div class="field"><label>Point 3 — Description</label><input type="text" id="about_point3_text" value="<?= htmlspecialchars(getContent('about_point3_text')) ?>"></div>
            </div>
            <div class="ss-point-box">
                <span class="ss-point-icon"><i class="fas fa-shield-alt"></i></span>
                <div class="field"><label>Point 4 — Title</label><input type="text" id="about_point4_title" value="<?= htmlspecialchars(getContent('about_point4_title')) ?>"></div>
                <div class="field"><label>Point 4 — Description</label><input type="text" id="about_point4_text" value="<?= htmlspecialchars(getContent('about_point4_text')) ?>"></div>
            </div>
        </div>
    </div>
</div>

<div class="ss-tab-content" id="tabFooter" style="display:none;">
    <div class="ss-card">
        <h3><i class="bi bi-link-45deg"></i> Footer</h3>
        <div class="field">
            <label>Brand Name (left side)</label>
            <input type="text" id="footer_brand" value="<?= htmlspecialchars(getContent('footer_brand')) ?>">
        </div>
        <div class="field">
            <label>Tagline (below brand)</label>
            <input type="text" id="footer_tagline" value="<?= htmlspecialchars(getContent('footer_tagline')) ?>">
        </div>
        <hr>
        <h4>Social Media Links</h4>
        <small style="display:block;color:#94a3b8;font-size:0.78rem;margin-bottom:10px;">Khaali chhod do agar koi platform use nahi karte — icon website pe nahi dikhega.</small>
        <div class="field">
            <label><i class="fab fa-facebook-f"></i> Facebook URL</label>
            <input type="text" id="footer_social_facebook" placeholder="https://facebook.com/yourschool" value="<?= htmlspecialchars(getContent('footer_social_facebook')) ?>">
        </div>
        <div class="field">
            <label><i class="fab fa-instagram"></i> Instagram URL</label>
            <input type="text" id="footer_social_instagram" placeholder="https://instagram.com/yourschool" value="<?= htmlspecialchars(getContent('footer_social_instagram')) ?>">
        </div>
        <div class="field">
            <label><i class="fab fa-youtube"></i> YouTube URL</label>
            <input type="text" id="footer_social_youtube" placeholder="https://youtube.com/@yourschool" value="<?= htmlspecialchars(getContent('footer_social_youtube')) ?>">
        </div>
        <div class="field">
            <label><i class="fab fa-twitter"></i> X / Twitter URL</label>
            <input type="text" id="footer_social_twitter" placeholder="https://x.com/yourschool" value="<?= htmlspecialchars(getContent('footer_social_twitter')) ?>">
        </div>
        <hr>
        <h4>Quick Links (4 links)</h4>
        <div class="field-row">
            <input type="text" id="footer_link1_text" placeholder="Link 1 Text" value="<?= htmlspecialchars(getContent('footer_link1_text')) ?>">
            <input type="text" id="footer_link1_url" placeholder="Link 1 URL" value="<?= htmlspecialchars(getContent('footer_link1_url')) ?>">
        </div>
        <div class="field-row">
            <input type="text" id="footer_link2_text" placeholder="Link 2 Text" value="<?= htmlspecialchars(getContent('footer_link2_text')) ?>">
            <input type="text" id="footer_link2_url" placeholder="Link 2 URL" value="<?= htmlspecialchars(getContent('footer_link2_url')) ?>">
        </div>
        <div class="field-row">
            <input type="text" id="footer_link3_text" placeholder="Link 3 Text" value="<?= htmlspecialchars(getContent('footer_link3_text')) ?>">
            <input type="text" id="footer_link3_url" placeholder="Link 3 URL" value="<?= htmlspecialchars(getContent('footer_link3_url')) ?>">
        </div>
        <div class="field-row">
            <input type="text" id="footer_link4_text" placeholder="Link 4 Text" value="<?= htmlspecialchars(getContent('footer_link4_text')) ?>">
            <input type="text" id="footer_link4_url" placeholder="Link 4 URL" value="<?= htmlspecialchars(getContent('footer_link4_url')) ?>">
        </div>
        <hr>
        <h4>Contact Details (right side)</h4>
        <div class="field">
            <label>Address</label>
            <input type="text" id="footer_address" value="<?= htmlspecialchars(getContent('footer_address')) ?>">
        </div>
        <div class="field">
            <label>Phone</label>
            <input type="text" id="footer_phone" value="<?= htmlspecialchars(getContent('footer_phone')) ?>">
        </div>
        <div class="field">
            <label>Email</label>
            <input type="text" id="footer_email" value="<?= htmlspecialchars(getContent('footer_email')) ?>">
        </div>
        <div class="field">
            <label>Copyright Text</label>
            <input type="text" id="footer_copyright" value="<?= htmlspecialchars(getContent('footer_copyright')) ?>">
        </div>
    </div>
</div>

<div class="ss-tab-content" id="tabContact" style="display:none;">
    <div class="ss-card">
        <h3><i class="bi bi-envelope-fill"></i> Contact Page</h3>
        <div class="field">
            <label>Page Heading</label>
            <input type="text" id="contact_heading" value="<?= htmlspecialchars(getContent('contact_heading')) ?>">
        </div>
        <div class="field">
            <label>Subtext</label>
            <input type="text" id="contact_subtext" value="<?= htmlspecialchars(getContent('contact_subtext')) ?>">
        </div>
        <div class="field">
            <label>Address</label>
            <input type="text" id="contact_address" value="<?= htmlspecialchars(getContent('contact_address')) ?>">
        </div>
        <div class="field">
            <label>Phone</label>
            <input type="text" id="contact_phone" value="<?= htmlspecialchars(getContent('contact_phone')) ?>">
        </div>
        <div class="field">
            <label>Email</label>
            <input type="text" id="contact_email" value="<?= htmlspecialchars(getContent('contact_email')) ?>">
        </div>
        <div class="field">
            <label>Working Hours</label>
            <input type="text" id="contact_hours" placeholder="e.g. Mon – Sat, 8:00 AM – 3:00 PM" value="<?= htmlspecialchars(getContent('contact_hours')) ?>">
        </div>
        <div class="field">
            <label>Google Maps Embed Link</label>
            <input type="text" id="contact_map_link" value="<?= htmlspecialchars(getContent('contact_map_link')) ?>">
            <small>Paste the full embed URL from Google Maps</small>
        </div>
    </div>
</div>

<!-- ===== SAVE BUTTON ===== -->
<div class="ss-save-row">
    <button id="saveAllBtn"><i class="bi bi-check2-circle"></i> Save All Changes</button>
    <span id="saveMsg"></span>
</div>

</div></div>

<script src="../../comp/nav/nav.js"></script>
<script src="../../comp/sidebar/sidebar.js"></script>
<script src="index.js"></script>
</body>
</html>