<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login - EAF Connect</title>
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
</head>

<body style="background: radial-gradient(circle at 15% 20%, #172B50 0%, #101B35 85%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <div class="eaf-card overflow-hidden shadow-lg border-0 rounded-4" style="box-shadow: 0 25px 60px rgba(0,0,0,0.45) !important;">
                    <div class="row g-0">
                        
                        <!-- LEFT : AUTHENTICATION FORM -->
                        <div class="col-lg-6 p-4 p-md-5 bg-white d-flex flex-column justify-content-center">
                            
                            <!-- LOGO & BRAND -->
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <a href="<?= base_url() ?>">
                                    <img src="<?= base_url('images/logo5.png') ?>" alt="EAF Connect" style="height: 52px; width: auto; background:#fff; border-radius:8px; padding:3px; border:1px solid #E2E8F0;">
                                </a>
                                <div>
                                    <h4 class="mb-0 fw-bold font-family-outfit" style="color:#101B35;">EAF <span style="color:#C6A667;">CONNECT</span></h4>
                                    <small class="text-muted fw-semibold letter-spacing-1 text-uppercase" style="font-size:11px;">Member Portal Access</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h3 class="fw-bold font-family-outfit mb-1" style="color:#101B35;">Welcome Back</h3>
                                <p class="text-muted fs-7 mb-0">Sign in with your registered member credentials</p>
                            </div>

                            <!-- ALERT MESSAGES -->
                            <?php if (session()->getFlashdata('error')) : ?>
                                <div class="alert alert-danger border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size:13px;">
                                    <i class="ri-error-warning-fill text-danger fs-5"></i>
                                    <span><?= session()->getFlashdata('error'); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('success')) : ?>
                                <div class="alert alert-success border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size:13px;">
                                    <i class="ri-checkbox-circle-fill text-success fs-5"></i>
                                    <span><?= session()->getFlashdata('success'); ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- FORM -->
                            <form method="post" action="<?= base_url('loginCheck') ?>" autocomplete="off">
                                <?= csrf_field(); ?>

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-bold" style="font-size:13px; color:#101B35;">Registered Email Address</label>
                                    <div class="position-relative">
                                        <input type="email" name="email" id="email" class="eaf-form-control pe-5" placeholder="name@company.com" autocomplete="username" required>
                                        <i class="ri-mail-line position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label for="password-input" class="form-label fw-bold mb-0" style="font-size:13px; color:#101B35;">Password</label>
                                        <a href="<?= base_url('contact'); ?>" class="text-muted" style="font-size:12px;">Forgot password?</a>
                                    </div>
                                    <div class="position-relative">
                                        <input type="password" name="password" id="password-input" class="eaf-form-control pe-5" placeholder="••••••••" autocomplete="current-password" required>
                                        <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted text-decoration-none pe-3" onclick="togglePassword()" aria-label="Show password">
                                            <i class="ri-eye-line" id="eye-icon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="auth-remember-check" name="remember">
                                        <label class="form-check-label text-muted" for="auth-remember-check" style="font-size:13px;">
                                            Keep me logged in
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="eaf-btn eaf-btn-primary eaf-btn-lg w-100 justify-content-center mb-3">
                                    <span>Sign In to Member Portal</span>
                                    <i class="ri-arrow-right-line"></i>
                                </button>
                            </form>

                            <div class="text-center pt-3 border-top border-light">
                                <p class="text-muted mb-0" style="font-size:13px;">
                                    Not a verified member yet? 
                                    <a href="<?= base_url('#benefits') ?>" class="fw-bold" style="color:#C6A667;">Apply for Chapter Seat</a>
                                </p>
                            </div>
                        </div>

                        <!-- RIGHT : CORPORATE METRICS SHOWCASE -->
                        <div class="col-lg-6 p-4 p-md-5 text-white d-flex flex-column justify-content-center" style="background: linear-gradient(135deg, #101B35 0%, #172B50 100%); border-left: 1px solid rgba(198,166,103,0.25);">
                            
                            <div class="mb-4">
                                <span class="badge mb-2 px-3 py-1" style="background:rgba(198,166,103,0.2); color:#C6A667; border:1px solid rgba(198,166,103,0.3); font-size:11px; letter-spacing:0.05em;">
                                    VERIFIED NETWORK VALUE
                                </span>
                                <h3 class="fw-bold text-white font-family-outfit mb-2">EAF Connect Network</h3>
                                <p class="text-white-50 mb-0 fs-7" style="line-height:1.6;">
                                    Driving exponential commercial growth for our members through structured, accountable referral ecosystems.
                                </p>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="p-3 rounded-3" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08);">
                                        <div class="fs-3 fw-bold font-family-outfit" style="color:#C6A667; line-height:1;"><?= esc($chapters); ?></div>
                                        <small class="text-white-50 mt-1 d-block" style="font-size:12px;">Active Chapters</small>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="p-3 rounded-3" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08);">
                                        <div class="fs-3 fw-bold font-family-outfit" style="color:#C6A667; line-height:1;"><?= esc($user); ?></div>
                                        <small class="text-white-50 mt-1 d-block" style="font-size:12px;">Verified Leaders</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08);">
                                        <small class="text-white-50 d-block" style="font-size:11px; text-transform:uppercase; letter-spacing:0.04em;">Total Closed Business (TYFCB)</small>
                                        <div class="fs-3 fw-bold font-family-outfit" style="color:#C6A667;">
                                            <?php
                                            $amount = (float)$MITB;
                                            if ($amount >= 10000000) {
                                                echo '₹ ' . number_format($amount / 10000000, 2) . ' Crores';
                                            } else {
                                                echo '₹ ' . number_format($amount);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-3 border-top border-secondary border-opacity-25" style="font-size:12px;">
                                <a href="<?= base_url(); ?>" class="text-white-50 text-decoration-none">
                                    <i class="ri-arrow-left-line me-1"></i> Return to Homepage
                                </a>
                                <a href="<?= base_url('admin'); ?>" class="text-warning text-decoration-none">
                                    Admin Login <i class="ri-arrow-right-line ms-1"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            var passInput = document.getElementById("password-input");
            var eyeIcon = document.getElementById("eye-icon");
            if (passInput.type === "password") {
                passInput.type = "text";
                eyeIcon.classList.remove("ri-eye-line");
                eyeIcon.classList.add("ri-eye-off-line");
            } else {
                passInput.type = "password";
                eyeIcon.classList.remove("ri-eye-off-line");
                eyeIcon.classList.add("ri-eye-line");
            }
        }
    </script>
</body>
</html>