<!-- =========================================================
     EAF CONNECT – PREMIUM SIGN IN & SIGN UP MODAL
     Midnight Navy (#101B35) + Champagne Gold (#C6A667)
========================================================= -->

<div class="modal fade" id="eafAuthModal" tabindex="-1" aria-labelledby="eafAuthModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content eaf-auth-modal-content">
            
            <!-- MODAL HEADER -->
            <div class="eaf-auth-modal-header">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center gap-3">
                        <img src="<?= base_url('images/logo5.png') ?>" alt="EAF Connect" class="eaf-auth-logo">
                        <div>
                            <h4 class="eaf-auth-brand-title mb-0">EAF <span>CONNECT</span></h4>
                            <small class="eaf-auth-brand-sub">World Infra Networking</small>
                        </div>
                    </div>
                    
                    <button type="button" class="eaf-auth-close-btn" data-bs-dismiss="modal" aria-label="Close" id="closeEafAuthModalBtn">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <!-- DUAL AUTHENTICATION TABS -->
                <div class="eaf-auth-tabs-wrapper mt-3">
                    <ul class="nav nav-pills eaf-auth-pills" id="eafAuthTabs" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link active w-100" id="pills-signin-tab" data-bs-toggle="pill" data-bs-target="#pills-signin" type="button" role="tab" aria-controls="pills-signin" aria-selected="true">
                                <i class="ri-login-box-line me-1"></i> Member Sign In
                            </button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100" id="pills-signup-tab" data-bs-toggle="pill" data-bs-target="#pills-signup" type="button" role="tab" aria-controls="pills-signup" aria-selected="false">
                                <i class="ri-user-add-line me-1"></i> Apply for Membership
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- MODAL BODY -->
            <div class="modal-body eaf-auth-modal-body p-4 p-md-4">
                <div class="tab-content" id="eafAuthTabsContent">
                    
                    <!-- ====================================================
                         TAB 1: SIGN IN (MEMBERS & ADMIN)
                    ==================================================== -->
                    <div class="tab-pane fade show active" id="pills-signin" role="tabpanel" aria-labelledby="pills-signin-tab">
                        <div class="mb-3 text-center text-md-start">
                            <span class="badge eaf-auth-badge mb-1">MEMBER & EXECUTIVE PORTAL</span>
                            <h5 class="fw-bold font-family-outfit text-navy mb-1" style="color:#101B35;">Welcome to EAF Connect</h5>
                            <p class="text-muted fs-7 mb-0">Enter your registered credentials to access your chapter dashboard.</p>
                        </div>

                        <!-- AJAX / Flash Alert Container -->
                        <div id="signinAlertContainer">
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
                        </div>

                        <form id="eafSignInForm" method="post" action="<?= base_url('loginCheck') ?>" autocomplete="on">
                            <?= csrf_field(); ?>

                            <!-- Email / Username -->
                            <div class="mb-3">
                                <label for="auth_email" class="eaf-auth-label">Registered Email / Username <span>*</span></label>
                                <div class="eaf-auth-input-group">
                                    <i class="ri-mail-line eaf-auth-input-icon"></i>
                                    <input type="email" name="email" id="auth_email" class="form-control eaf-auth-input" placeholder="name@company.com" autocomplete="username" required>
                                </div>
                            </div>

                            <!-- Password with Show/Hide -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label for="auth_password" class="eaf-auth-label mb-0">Password <span>*</span></label>
                                    <a href="<?= base_url('contact'); ?>" class="eaf-auth-forgot-link">Forgot Password?</a>
                                </div>
                                <div class="eaf-auth-input-group">
                                    <i class="ri-lock-2-line eaf-auth-input-icon"></i>
                                    <input type="password" name="password" id="auth_password" class="form-control eaf-auth-input pe-5" placeholder="••••••••" autocomplete="current-password" required>
                                    <button type="button" class="eaf-auth-eye-btn" onclick="toggleAuthPassword()" aria-label="Toggle password visibility">
                                        <i class="ri-eye-line" id="authEyeIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Remember Me Checkbox -->
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="authRememberMe" name="remember" value="1" checked>
                                    <label class="form-check-label text-muted" for="authRememberMe" style="font-size:12px;">
                                        Keep me signed in on this device
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="btnSignInSubmit" class="eaf-btn eaf-btn-gold w-100 justify-content-center py-2 mb-3">
                                <span id="btnSignInText"><i class="ri-login-box-line me-1"></i> Sign In to Member Portal</span>
                            </button>

                            <!-- Switch to Sign Up -->
                            <div class="text-center pt-2 border-top border-light">
                                <p class="mb-0 text-muted" style="font-size:13px;">
                                    Not an approved member yet? 
                                    <a href="javascript:void(0)" onclick="switchToSignUpTab()" class="fw-bold" style="color:#C6A667; text-decoration:none;">
                                        Apply for Membership
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>


                    <!-- ====================================================
                         TAB 2: SIGN UP (APPLY FOR MEMBERSHIP)
                    ==================================================== -->
                    <div class="tab-pane fade" id="pills-signup" role="tabpanel" aria-labelledby="pills-signup-tab">
                        <div class="mb-3 text-center text-md-start">
                            <span class="badge eaf-auth-badge mb-1">MEMBERSHIP ADMISSIONS</span>
                            <h5 class="fw-bold font-family-outfit text-navy mb-1" style="color:#101B35;">Apply for Chapter Membership</h5>
                            <p class="text-muted fs-7 mb-0">Seats are limited to one business per category per chapter. Complete your application below.</p>
                        </div>

                        <!-- Alert Container -->
                        <div id="signupAlertContainer"></div>

                        <form id="eafSignUpForm" method="post" action="<?= base_url('addlead') ?>" autocomplete="on">
                            <?= csrf_field(); ?>

                            <div class="row g-2 mb-2">
                                <!-- First Name -->
                                <div class="col-md-6">
                                    <label for="reg_name" class="eaf-auth-label">First Name <span>*</span></label>
                                    <div class="eaf-auth-input-group">
                                        <i class="ri-user-line eaf-auth-input-icon"></i>
                                        <input type="text" name="name" id="reg_name" class="form-control eaf-auth-input" placeholder="First Name" required>
                                    </div>
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6">
                                    <label for="reg_last_name" class="eaf-auth-label">Last Name <span>*</span></label>
                                    <div class="eaf-auth-input-group">
                                        <i class="ri-user-line eaf-auth-input-icon"></i>
                                        <input type="text" name="last_name" id="reg_last_name" class="form-control eaf-auth-input" placeholder="Last Name" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2 mb-2">
                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="reg_email" class="eaf-auth-label">Business Email <span>*</span></label>
                                    <div class="eaf-auth-input-group">
                                        <i class="ri-mail-line eaf-auth-input-icon"></i>
                                        <input type="email" name="email" id="reg_email" class="form-control eaf-auth-input" placeholder="name@business.com" required>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label for="reg_phone" class="eaf-auth-label">Mobile / WhatsApp <span>*</span></label>
                                    <div class="eaf-auth-input-group">
                                        <i class="ri-phone-line eaf-auth-input-icon"></i>
                                        <input type="tel" name="phone" id="reg_phone" class="form-control eaf-auth-input" placeholder="+91 9876543210" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2 mb-2">
                                <!-- Business Name -->
                                <div class="col-md-6">
                                    <label for="reg_business" class="eaf-auth-label">Business / Company Name <span>*</span></label>
                                    <div class="eaf-auth-input-group">
                                        <i class="ri-building-line eaf-auth-input-icon"></i>
                                        <input type="text" name="business" id="reg_business" class="form-control eaf-auth-input" placeholder="e.g. Apex Infra Corp" required>
                                    </div>
                                </div>

                                <!-- Business Category -->
                                <div class="col-md-6">
                                    <label for="reg_category" class="eaf-auth-label">Category / Specialization <span>*</span></label>
                                    <div class="eaf-auth-input-group">
                                        <i class="ri-price-tag-3-line eaf-auth-input-icon"></i>
                                        <input type="text" name="category" id="reg_category" class="form-control eaf-auth-input" placeholder="e.g. Architect, CA, IT Solutions" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <!-- Business Location -->
                                <div class="col-md-4">
                                    <label for="reg_location" class="eaf-auth-label">City / Location <span>*</span></label>
                                    <div class="eaf-auth-input-group">
                                        <i class="ri-map-pin-line eaf-auth-input-icon"></i>
                                        <input type="text" name="location" id="reg_location" class="form-control eaf-auth-input" placeholder="e.g. Bengaluru" required>
                                    </div>
                                </div>

                                <!-- Business Since Year -->
                                <div class="col-md-4">
                                    <label for="reg_since" class="eaf-auth-label">Business Since <span>*</span></label>
                                    <div class="eaf-auth-input-group">
                                        <i class="ri-calendar-line eaf-auth-input-icon"></i>
                                        <input type="text" name="since" id="reg_since" class="form-control eaf-auth-input" placeholder="e.g. 2018" required>
                                    </div>
                                </div>

                                <!-- GST (Optional) -->
                                <div class="col-md-4">
                                    <label for="reg_gst" class="eaf-auth-label">GST Number <small class="text-muted">(Optional)</small></label>
                                    <div class="eaf-auth-input-group">
                                        <i class="ri-file-text-line eaf-auth-input-icon"></i>
                                        <input type="text" name="gst" id="reg_gst" class="form-control eaf-auth-input" placeholder="29ABCDE1234F1Z5">
                                    </div>
                                </div>
                            </div>

                            <!-- Trust note -->
                            <div class="p-2 mb-3 rounded-3" style="background:rgba(198,166,103,0.08); border:1px solid rgba(198,166,103,0.25);">
                                <small class="text-muted d-flex align-items-center gap-2" style="font-size:11px;">
                                    <i class="ri-shield-check-fill text-warning fs-6"></i>
                                    <span><strong>Category Exclusivity:</strong> Applications undergo executive committee review to ensure conflict-free category exclusivity.</span>
                                </small>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="btnSignUpSubmit" class="eaf-btn eaf-btn-gold w-100 justify-content-center py-2 mb-3">
                                <span id="btnSignUpText"><i class="ri-send-plane-fill me-1"></i> Submit Membership Application</span>
                            </button>

                            <!-- Switch to Sign In -->
                            <div class="text-center pt-2 border-top border-light">
                                <p class="mb-0 text-muted" style="font-size:13px;">
                                    Already an active member? 
                                    <a href="javascript:void(0)" onclick="switchToSignInTab()" class="fw-bold" style="color:#C6A667; text-decoration:none;">
                                        Sign In to Member Portal
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <!-- MODAL FOOTER NOTE -->
            <div class="eaf-auth-modal-footer text-center py-2 px-3">
                <small class="text-muted" style="font-size:11px;">
                    <i class="ri-lock-line me-1"></i> 256-bit SSL Encrypted • EAF Connect Enterprise Platform
                </small>
            </div>

        </div>
    </div>
</div>

<!-- =========================================================
     EAF AUTH MODAL STYLING (MATCHING THEME)
========================================================= -->
<style>
/* Backdrop Blur & Overlay */
.modal-backdrop.show {
    opacity: 0.82 !important;
    background-color: #101B35 !important;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.eaf-auth-modal-content {
    border: 1px solid rgba(198, 166, 103, 0.45) !important;
    border-radius: 20px !important;
    box-shadow: 0 25px 70px rgba(16, 27, 53, 0.5) !important;
    overflow: hidden;
    background: #FFFFFF;
}

.eaf-auth-modal-header {
    background: linear-gradient(135deg, #101B35 0%, #172B50 100%);
    padding: 20px 24px 16px;
    border-bottom: 1px solid rgba(198, 166, 103, 0.3);
    position: relative;
}

.eaf-auth-logo {
    height: 48px;
    width: auto;
    background: #FFFFFF;
    border-radius: 8px;
    padding: 3px;
    border: 1px solid rgba(198, 166, 103, 0.4);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.eaf-auth-brand-title {
    color: #FFFFFF;
    font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    font-size: 19px;
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.01em;
}

.eaf-auth-brand-title span {
    color: #C6A667;
}

.eaf-auth-brand-sub {
    color: #94A3B8;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.eaf-auth-close-btn {
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #FFFFFF;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.25s ease;
}

.eaf-auth-close-btn:hover {
    background: #C6A667;
    color: #101B35;
    transform: rotate(90deg);
}

/* Nav Pills inside Header */
.eaf-auth-tabs-wrapper {
    background: rgba(0, 0, 0, 0.25);
    border-radius: 12px;
    padding: 4px;
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.eaf-auth-pills .nav-link {
    color: #CBD5E1;
    font-size: 13px;
    font-weight: 600;
    padding: 9px 16px;
    border-radius: 9px;
    transition: all 0.2s ease;
    border: 0;
}

.eaf-auth-pills .nav-link:hover {
    color: #FFFFFF;
}

.eaf-auth-pills .nav-link.active {
    background: #C6A667 !important;
    color: #101B35 !important;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(198, 166, 103, 0.35);
}

/* Form inputs & styling */
.eaf-auth-modal-body {
    background: #FAF9F6;
    max-height: calc(85vh - 120px);
    overflow-y: auto;
}

.eaf-auth-badge {
    background: rgba(198, 166, 103, 0.15);
    color: #93712C;
    border: 1px solid rgba(198, 166, 103, 0.4);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.05em;
    padding: 4px 8px;
    border-radius: 6px;
}

.eaf-auth-label {
    font-size: 12px;
    font-weight: 700;
    color: #101B35;
    margin-bottom: 4px;
    display: block;
}

.eaf-auth-label span {
    color: #DC2626;
}

.eaf-auth-input-group {
    position: relative;
}

.eaf-auth-input-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94A3B8;
    font-size: 16px;
    pointer-events: none;
    transition: color 0.2s ease;
}

.eaf-auth-input {
    min-height: 44px;
    padding: 8px 14px 8px 40px;
    border-radius: 9px;
    border: 1px solid #CBD5E1;
    background: #FFFFFF;
    font-size: 13px;
    color: #101B35;
    font-weight: 500;
    transition: all 0.2s ease;
}

.eaf-auth-input:focus {
    border-color: #C6A667;
    box-shadow: 0 0 0 3px rgba(198, 166, 103, 0.2);
    background: #FFFFFF;
    color: #101B35;
}

.eaf-auth-input:focus ~ .eaf-auth-input-icon {
    color: #C6A667;
}

.eaf-auth-eye-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: 0;
    color: #94A3B8;
    font-size: 18px;
    cursor: pointer;
    padding: 4px;
    transition: color 0.2s ease;
}

.eaf-auth-eye-btn:hover {
    color: #101B35;
}

.eaf-auth-forgot-link {
    font-size: 12px;
    color: #64748B;
    text-decoration: none;
    transition: color 0.2s ease;
}

.eaf-auth-forgot-link:hover {
    color: #C6A667;
    text-decoration: underline;
}

.eaf-auth-modal-footer {
    background: #FFFFFF;
    border-top: 1px solid #E2E8F0;
}

@media (max-width: 576px) {
    .eaf-auth-modal-content {
        border-radius: 14px !important;
    }
    .eaf-auth-modal-header {
        padding: 16px 16px 12px;
    }
    .eaf-auth-modal-body {
        padding: 16px !important;
    }
    .eaf-auth-pills .nav-link {
        font-size: 12px;
        padding: 8px 10px;
    }
}
</style>

<!-- =========================================================
     MODAL CONTROLLER JAVASCRIPT
========================================================= -->
<script>
// Toggle Password Visibility
function toggleAuthPassword() {
    const input = document.getElementById('auth_password');
    const icon = document.getElementById('authEyeIcon');
    if (!input || !icon) return;
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ri-eye-off-line';
    } else {
        input.type = 'password';
        icon.className = 'ri-eye-line';
    }
}

// Tab Switching Helpers
function switchToSignInTab() {
    const tabTrigger = document.querySelector('#pills-signin-tab');
    if (tabTrigger) {
        bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
    }
}

function switchToSignUpTab() {
    const tabTrigger = document.querySelector('#pills-signup-tab');
    if (tabTrigger) {
        bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
    }
}

// Function to Open Modal Programmatically
function openEafAuthModal(tab = 'signin') {
    const modalEl = document.getElementById('eafAuthModal');
    if (!modalEl) return;
    
    if (tab === 'signup') {
        switchToSignUpTab();
    } else {
        switchToSignInTab();
    }
    
    const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
    modalInstance.show();
}

// Auto-Opening on Page Load for Unauthenticated Visitors
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('eafAuthModal');
    if (!modalEl) return;

    // Check if user is already authenticated
    const isAuthenticated = <?= session()->get('logged_in') ? 'true' : 'false' ?>;
    if (isAuthenticated) {
        // Do not display popup if already authenticated
        return;
    }

    // Check URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const forceAuth = urlParams.get('auth') || urlParams.get('signin');
    const forceSignUp = urlParams.get('signup');

    if (forceSignUp) {
        openEafAuthModal('signup');
    } else if (forceAuth) {
        openEafAuthModal('signin');
    } else {
        // Do not auto-display popup if page already features direct hero sign-in
        const hasHeroDirectLogin = document.getElementById('eafHeroSignInForm') !== null;
        if (!hasHeroDirectLogin) {
            setTimeout(function() {
                openEafAuthModal('signin');
            }, 350);
        }
    }

    // ── AJAX SIGN IN SUBMISSION ────────────────────────────────
    const signinForm = document.getElementById('eafSignInForm');
    if (signinForm) {
        signinForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSignInSubmit');
            const btnText = document.getElementById('btnSignInText');
            const alertBox = document.getElementById('signinAlertContainer');

            btn.disabled = true;
            btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Authenticating...';

            const formData = new FormData(signinForm);

            fetch('<?= base_url('loginCheck') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btnText.innerHTML = '<i class="ri-login-box-line me-1"></i> Sign In to Member Portal';

                if (data.status === 'success') {
                    alertBox.innerHTML = `
                        <div class="alert alert-success border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size:13px;">
                            <i class="ri-checkbox-circle-fill text-success fs-5"></i>
                            <span>${data.message || 'Login successful! Redirecting...'}</span>
                        </div>
                    `;
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 600);
                } else {
                    alertBox.innerHTML = `
                        <div class="alert alert-danger border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size:13px;">
                            <i class="ri-error-warning-fill text-danger fs-5"></i>
                            <span>${data.message || 'Invalid email or password.'}</span>
                        </div>
                    `;
                }
            })
            .catch(err => {
                // If running on static staging preview (e.g. ship.place) or local file demo:
                const isStaticPreview = window.location.hostname.includes('ship.place') || window.location.hostname.includes('github.io') || window.location.protocol === 'file:';
                if (isStaticPreview) {
                    btn.disabled = false;
                    btnText.innerHTML = '<i class="ri-login-box-line me-1"></i> Sign In to Member Portal';
                    alertBox.innerHTML = `
                        <div class="alert alert-success border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size:13px;">
                            <i class="ri-checkbox-circle-fill text-success fs-5"></i>
                            <span>Preview Demo: Authenticated! Redirecting to Member Dashboard...</span>
                        </div>
                    `;
                    setTimeout(() => {
                        window.location.href = 'member/dashboard.html';
                    }, 600);
                } else {
                    // Fallback to standard form submission if on live/XAMPP server
                    signinForm.submit();
                }
            });
        });
    }

    // ── AJAX SIGN UP (MEMBERSHIP APPLICATION) SUBMISSION ────────
    const signupForm = document.getElementById('eafSignUpForm');
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSignUpSubmit');
            const btnText = document.getElementById('btnSignUpText');
            const alertBox = document.getElementById('signupAlertContainer');

            btn.disabled = true;
            btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Submitting Application...';

            const formData = new FormData(signupForm);

            fetch('<?= base_url('addlead') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btnText.innerHTML = '<i class="ri-send-plane-fill me-1"></i> Submit Membership Application';

                if (data.status === 'success') {
                    signupForm.reset();
                    alertBox.innerHTML = `
                        <div class="alert alert-success border-0 rounded-3 py-3 px-3 mb-3" style="font-size:13px; background:#EFFCF6; border-left:4px solid #12B76A !important;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="ri-checkbox-circle-fill text-success fs-5"></i>
                                <strong style="color:#027A48;">Application Received!</strong>
                            </div>
                            <div style="color:#027A48; line-height:1.5;">${data.message}</div>
                        </div>
                    `;
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Application Submitted',
                            text: data.message,
                            confirmButtonColor: '#C6A667'
                        });
                    }
                } else {
                    alertBox.innerHTML = `
                        <div class="alert alert-danger border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size:13px;">
                            <i class="ri-error-warning-fill text-danger fs-5"></i>
                            <span>${data.message || 'Unable to submit application. Please check all fields.'}</span>
                        </div>
                    `;
                }
            })
            .catch(err => {
                const isStaticPreview = window.location.hostname.includes('ship.place') || window.location.hostname.includes('github.io') || window.location.protocol === 'file:';
                if (isStaticPreview) {
                    btn.disabled = false;
                    btnText.innerHTML = '<i class="ri-send-plane-fill me-1"></i> Submit Membership Application';
                    signupForm.reset();
                    alertBox.innerHTML = `
                        <div class="alert alert-success border-0 rounded-3 py-3 px-3 mb-3" style="font-size:13px; background:#EFFCF6; border-left:4px solid #12B76A !important;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="ri-checkbox-circle-fill text-success fs-5"></i>
                                <strong style="color:#027A48;">Application Received (Preview Demo)!</strong>
                            </div>
                            <div style="color:#027A48; line-height:1.5;">Your membership application was demonstrated successfully. In the live portal, this submits to the Chapter Executive Committee.</div>
                        </div>
                    `;
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Application Received (Demo)',
                            text: 'Your membership application was demonstrated successfully.',
                            confirmButtonColor: '#C6A667'
                        });
                    }
                } else {
                    signupForm.submit();
                }
            });
        });
    }
});
</script>
