<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Bright Future International School</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="contact_page.css">
    <link rel="stylesheet" href="../comp/nav/nav.css">
    <link rel="stylesheet" href="../comp/footer/footer.css">
</head>
<body>
<?php
    include_once("../comp/nav/nav.php");
  ?>
<section class="contact-hero">
    <h1><i class="bi bi-envelope-heart-fill"></i> Get in Touch</h1>
    <p>Have a question about admissions or school life? We'd love to hear from you.</p>
</section>

<section class="contact-wrap">
    <div class="contact-grid">

        <!-- LEFT: FORM -->
        <div class="contact-form-side">
            <form id="enquiryForm">
                <div class="field">
                    <label>Full Name <span class="req">*</span></label>
                    <input type="text" id="cName" placeholder="Your full name" required>
                </div>
                <div class="field">
                    <label>Phone Number <span class="req">*</span></label>
                    <input type="tel" id="cPhone" placeholder="10 digit mobile number" maxlength="10" required>
                </div>
                <div class="field">
                    <label>Email <span class="opt">(optional)</span></label>
                    <input type="email" id="cEmail" placeholder="name@email.com">
                </div>
                <div class="field">
                    <label>Why are you reaching out? <span class="opt">(optional)</span></label>
                    <textarea id="cMessage" placeholder="Tell us a little about your enquiry..."></textarea>
                </div>
                <button type="submit" id="submitBtn">
                    <span class="btn-text"><i class="bi bi-send-fill"></i> Send Enquiry</span>
                </button>
                <div id="formMsg" class="form-msg"></div>
            </form>
        </div>

        <!-- RIGHT: INFO + MAP -->
        <div class="contact-info-side">
            <div class="info-card">
                <h3>Bright Future International School</h3>
                <p class="info-sub">We're here to help with anything you need.</p>

                <div class="info-row">
                    <div class="info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <div>
                        <strong>Address</strong>
                        <p>Patna, Bihar, India</p>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="bi bi-telephone-fill"></i></div>
                    <div>
                        <strong>Phone</strong>
                        <p>+91 98765 43210</p>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="bi bi-envelope-fill"></i></div>
                    <div>
                        <strong>Email</strong>
                        <p>info@brightfuture.edu</p>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon"><i class="bi bi-clock-fill"></i></div>
                    <div>
                        <strong>Working Hours</strong>
                        <p>Mon – Sat, 8:00 AM – 3:00 PM</p>
                    </div>
                </div>
            </div>

            <div class="map-card">
                <iframe
                    src="https://www.google.com/maps?q=Patna,Bihar&output=embed"
                    width="100%" height="100%" style="border:0;"
                    allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>

    </div>
</section>
<?php include_once("../comp/footer/footer.php"); ?>

<script src="contact_page.js"></script>
</body>
</html>