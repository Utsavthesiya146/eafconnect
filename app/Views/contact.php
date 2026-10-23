<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Executive Office - EAF Connect</title>
    <meta name="description" content="Get in touch with the executive leadership team of EAF Connect for inquiries, chapter visits, and partnerships.">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('images/favicon.png'); ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Remix Icons -->
    <link href="<?= base_url('assets2/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">

    <!-- EAF Enterprise Design System -->
    <link href="<?= base_url('assets2/css/eaf-design-system.css'); ?>?v=5.0" rel="stylesheet" type="text/css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body style="background-color: #FAF9F6; color: #101B35;">

    <!-- HEADER -->
    <?= view('include/header.php'); ?>

    <!-- HERO STRIP -->
    <section class="py-5" style="background: radial-gradient(circle at 10% 20%, rgba(23,43,80,0.95) 0%, #101B35 80%); color:#fff; border-bottom:1px solid rgba(198,166,103,0.3);">
        <div class="container py-3 text-center">
            <span class="eaf-badge eaf-badge-gold mb-2">Executive Office</span>
            <h1 class="display-5 fw-bold font-family-outfit mb-2 text-white">Contact EAF Connect</h1>
            <p class="text-white-50 fs-6 max-w-600 mx-auto mb-0" style="max-width: 620px;">
                Have questions about chapter admissions, regional partnerships, or prospective membership? Our executive team is at your service.
            </p>
        </div>
    </section>

    <!-- CONTACT FORM & INFO SECTION -->
    <section class="py-5">
        <div class="container py-3">
            <div class="row justify-content-center">
                <div class="col-xl-11">
                    <div class="eaf-card overflow-hidden shadow-lg border-0 rounded-4">
                        <div class="row g-0">
                            <!-- LEFT CORPORATE INFO PANEL -->
                            <div class="col-lg-5 p-5 text-white position-relative" style="background: linear-gradient(135deg, #101B35 0%, #172B50 100%); border-right: 1px solid rgba(198,166,103,0.25);">
                                <span class="badge mb-3 px-3 py-2" style="background:rgba(198,166,103,0.15); color:#C6A667; border:1px solid rgba(198,166,103,0.3); font-size:11px; letter-spacing:0.05em;">
                                    CONNECT DIRECTLY
                                </span>

                                <h3 class="fw-bold text-white font-family-outfit mb-3">Executive Headquarters</h3>
                                <p class="text-white-50 mb-4 fs-7" style="line-height:1.7;">
                                    We welcome business owners seeking chapter admissions or corporate leaders interested in cross-border referrals.
                                </p>

                                <div class="d-flex flex-column gap-3 mb-5">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08);">
                                        <div class="flex-shrink-0" style="width:44px; height:44px; border-radius:10px; background:rgba(198,166,103,0.18); color:#C6A667; display:flex; align-items:center; justify-content:center; font-size:20px;">
                                            <i class="ri-phone-fill"></i>
                                        </div>
                                        <div>
                                            <small class="text-white-50 d-block" style="font-size:11px;">Telephone</small>
                                            <strong class="text-white">+91 9886115993</strong>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08);">
                                        <div class="flex-shrink-0" style="width:44px; height:44px; border-radius:10px; background:rgba(198,166,103,0.18); color:#C6A667; display:flex; align-items:center; justify-content:center; font-size:20px;">
                                            <i class="ri-mail-fill"></i>
                                        </div>
                                        <div>
                                            <small class="text-white-50 d-block" style="font-size:11px;">Official Email</small>
                                            <strong class="text-white">info@eafconnect.com</strong>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08);">
                                        <div class="flex-shrink-0" style="width:44px; height:44px; border-radius:10px; background:rgba(198,166,103,0.18); color:#C6A667; display:flex; align-items:center; justify-content:center; font-size:20px;">
                                            <i class="ri-map-pin-fill"></i>
                                        </div>
                                        <div>
                                            <small class="text-white-50 d-block" style="font-size:11px;">Regional Hub</small>
                                            <strong class="text-white">Karnataka, India</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 border-top border-secondary border-opacity-25">
                                    <small class="text-white-50 d-block mb-2">Instant Assistance via WhatsApp:</small>
                                    <a href="https://wa.me/919341873532" target="_blank" class="eaf-btn eaf-btn-gold btn-sm w-100 justify-content-center">
                                        <i class="ri-whatsapp-line"></i>
                                        <span>Chat on WhatsApp</span>
                                    </a>
                                </div>
                            </div>

                            <!-- RIGHT INQUIRY FORM -->
                            <div class="col-lg-7 p-5 bg-white">
                                <?php if (session()->getFlashdata('success')): ?>
                                    <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4">
                                        <i class="ri-checkbox-circle-fill me-2"></i>
                                        <?= session()->getFlashdata('success') ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (session()->getFlashdata('error')): ?>
                                    <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
                                        <i class="ri-error-warning-fill me-2"></i>
                                        <?= session()->getFlashdata('error') ?>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-4">
                                    <h3 class="fw-bold font-family-outfit mb-1" style="color:#101B35;">Send an Official Inquiry</h3>
                                    <p class="text-muted fs-7 mb-0">Fill out the details below and an executive coordinator will respond within 24 business hours.</p>
                                </div>

                                <form method="post" action="<?= base_url('contact_save') ?>">
                                    <?= csrf_field(); ?>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" style="font-size:13px; color:#101B35;">Your Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="eaf-form-control" placeholder="e.g. Harri Kumar" value="<?= old('name') ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" style="font-size:13px; color:#101B35;">Corporate Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="eaf-form-control" placeholder="name@company.com" value="<?= old('email') ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" style="font-size:13px; color:#101B35;">Mobile / WhatsApp <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="eaf-form-control" placeholder="+91 98861 15993" value="<?= old('phone') ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" style="font-size:13px; color:#101B35;">Inquiry Subject</label>
                                            <input type="text" name="subject" class="eaf-form-control" placeholder="Chapter Admission / Partnership" value="<?= old('subject') ?>">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-bold" style="font-size:13px; color:#101B35;">Your Message or Business Profile <span class="text-danger">*</span></label>
                                            <textarea name="message" rows="5" class="eaf-form-control" placeholder="Tell us about your business, industry category, and objectives..." required><?= old('message') ?></textarea>
                                        </div>

                                        <div class="col-12 pt-2">
                                            <button type="submit" class="eaf-btn eaf-btn-primary eaf-btn-lg w-100 justify-content-center">
                                                <i class="ri-send-plane-fill"></i>
                                                <span>Submit Official Inquiry</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <?= view('include/footer.php'); ?>

</body>
</html>