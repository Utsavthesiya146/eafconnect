<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Administration Desk - EAF Connect</title>
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

<body style="background: radial-gradient(circle at 10% 20%, #0B1325 0%, #101B35 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
                <div class="eaf-card overflow-hidden shadow-lg border-0 rounded-4" style="box-shadow: 0 25px 60px rgba(0,0,0,0.55) !important;">
                    <div class="row g-0">
                        
                        <!-- LEFT : ADMIN CREDENTIALS -->
                        <div class="col-lg-6 p-4 p-md-5 bg-white d-flex flex-column justify-content-center">
                            
                            <!-- LOGO & BRAND -->
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <a href="<?= base_url() ?>">
                                    <img src="<?= base_url('images/logo5.png') ?>" alt="EAF Connect" style="height: 52px; width: auto; background:#fff; border-radius:8px; padding:3px; border:1px solid #E2E8F0;">
                                </a>
                                <div>
                                    <h4 class="mb-0 fw-bold font-family-outfit" style="color:#101B35;">EAF <span style="color:#C6A667;">CONNECT</span></h4>
                                    <small class="text-danger fw-bold letter-spacing-1 text-uppercase" style="font-size:11px;">Administrative Control</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h3 class="fw-bold font-family-outfit mb-1" style="color:#101B35;">Admin Console</h3>
                                <p class="text-muted fs-7 mb-0">Authorized executive administrators only</p>
                            </div>

                            <!-- ALERTS -->
                            <?php if (session()->getFlashdata('error')) : ?>
                                <div class="alert alert-danger border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size:13px;">
                                    <i class="ri-error-warning-fill text-danger fs-5"></i>
                                    <span><?= session()->getFlashdata('error'); ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- FORM -->
                            <form method="post" action="<?= base_url('loginCheckAdmin') ?>" autocomplete="off">
                                <?= csrf_field(); ?>

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-bold" style="font-size:13px; color:#101B35;">Administrator Email</label>
                                    <div class="position-relative">
                                        <input type="email" name="email" id="email" class="eaf-form-control pe-5" placeholder="admin@eafconnect.com" autocomplete="username" required>
                                        <i class="ri-shield-keyhole-line position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password-input" class="form-label fw-bold mb-1" style="font-size:13px; color:#101B35;">Master Password</label>
                                    <div class="position-relative">
                                        <input type="password" name="password" id="password-input" class="eaf-form-control pe-5" placeholder="••••••••" autocomplete="current-password" required>
                                        <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted text-decoration-none pe-3" onclick="togglePassword()" aria-label="Show password">
                                            <i class="ri-eye-line" id="eye-icon"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="submit" class="eaf-btn eaf-btn-primary eaf-btn-lg w-100 justify-content-center mb-3" style="background: linear-gradient(135deg, #0B1325 0%, #101B35 100%);">
                                    <i class="ri-lock-unlock-line"></i>
                                    <span>Authenticate Administrator</span>
                                </button>
                            </form>

                            <div class="text-center pt-3 border-top border-light">
                                <a href="<?= base_url('member_login'); ?>" class="text-muted" style="font-size:13px;">
                                    Switch to Member Login
                                </a>
                            </div>
                        </div>

                        <!-- RIGHT : ADMIN OVERVIEW -->
                        <div class="col-lg-6 p-4 p-md-5 text-white d-flex flex-column justify-content-center" style="background: linear-gradient(135deg, #0B1325 0%, #172B50 100%); border-left: 1px solid rgba(198,166,103,0.25);">
                            
                            <div class="mb-4">
                                <span class="badge mb-2 px-3 py-1" style="background:rgba(225,29,72,0.2); color:#FF8095; border:1px solid rgba(225,29,72,0.4); font-size:11px; letter-spacing:0.05em;">
                                    RESTRICTED ENVIRONMENT
                                </span>
                                <h3 class="fw-bold text-white font-family-outfit mb-2">Central Governance</h3>
                                <p class="text-white-50 mb-0 fs-7" style="line-height:1.6;">
                                    Direct access to platform-wide membership approvals, chapter configurations, revenue reconciliation, and meeting audits.
                                </p>
                            </div>

                            <div class="p-4 rounded-3 mb-4" style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08);">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <i class="ri-secure-payment-line text-warning fs-3"></i>
                                    <div>
                                        <strong class="text-white d-block">Encrypted Audit Logging</strong>
                                        <small class="text-white-50">All admin sessions are monitored & logged</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <i class="ri-user-settings-line text-info fs-3"></i>
                                    <div>
                                        <strong class="text-white d-block">Chapter Leadership Controls</strong>
                                        <small class="text-white-50">Manage president & executive committees</small>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 border-top border-secondary border-opacity-25 text-center" style="font-size:12px;">
                                <a href="<?= base_url(); ?>" class="text-white-50 text-decoration-none">
                                    <i class="ri-arrow-left-line me-1"></i> Back to EAF Public Website
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