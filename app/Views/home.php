<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EAF Connect - Premium Business Networking Platform</title>
    <meta name="description" content="EAF Connect is an elite enterprise-grade business networking platform for entrepreneurs, corporate leaders, and founders to exchange high-value referrals and scale together.">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('images/favicon.png'); ?>">

    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
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

    <!-- =========================================================
         1. CORPORATE HEADER
    ========================================================= -->
    <?= view('include/header.php'); ?>

    <!-- =========================================================
         2. HIGH-IMPACT HERO SECTION (KVV + BNI Synthesis)
    ========================================================= -->
    <section class="eaf-public-hero">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center g-4 g-lg-5">
                
                <!-- 1. DIRECT LOGIN CARD (FIRST ON MOBILE, RIGHT SIDE ON DESKTOP) -->
                <div class="col-lg-5 order-1 order-lg-2">
                    <?php if (session()->get('user_id')): ?>
                        <!-- AUTHENTICATED ACTIVE SESSION CARD -->
                        <div class="eaf-hero-login-card">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                                <span class="badge" style="background:#C6A667; color:#101B35; font-weight:700;"><i class="ri-user-star-fill me-1"></i> ACTIVE SESSION</span>
                                <span class="text-success" style="font-size: 12px;"><i class="ri-checkbox-circle-fill me-1"></i> Logged In</span>
                            </div>
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <img src="<?= esc(get_member_avatar(session()->get('profile_photo'), '', session()->get('user_code'))) ?>" alt="Member Avatar" style="width: 58px; height: 58px; border-radius: 50%; object-fit: cover; border: 2px solid #C6A667; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                                <div>
                                    <h5 class="text-white fw-bold font-family-outfit mb-0"><?= esc(session()->get('name')) ?></h5>
                                    <small class="text-white-50 d-block"><?= esc(session()->get('email')) ?></small>
                                    <span class="badge mt-1" style="background: rgba(198, 166, 103, 0.2); color: #E4D4AE; border: 1px solid rgba(198, 166, 103, 0.3); font-size: 11px;">
                                        <?= esc(session()->get('member_role') ?? 'Member') ?>
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <a href="<?= session()->get('role') == 'admin' ? base_url('admin/dashboard') : base_url('member/dashboard') ?>" class="eaf-btn eaf-btn-gold w-100 justify-content-center py-2">
                                    <i class="ri-dashboard-3-line"></i> Go to <?= session()->get('role') == 'admin' ? 'Admin Dashboard' : 'Member Portal' ?>
                                </a>
                                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm w-100 py-2">
                                    <i class="ri-logout-box-line me-1"></i> Sign Out
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- DIRECT LOGIN FORM CARD -->
                        <div class="eaf-hero-login-card">
                            <div class="eaf-hero-login-header">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge" style="background:rgba(198,166,103,0.2); color:#C6A667; border:1px solid rgba(198,166,103,0.4); font-size:11px; font-weight:700; letter-spacing:0.05em; padding:4px 8px; border-radius:6px;">
                                        <i class="ri-shield-star-line me-1"></i> MEMBER PORTAL
                                    </span>
                                    <small class="text-white-50" style="font-size:11px;"><i class="ri-lock-2-line text-warning me-1"></i> 256-bit SSL</small>
                                </div>
                                <h4 class="text-white fw-bold font-family-outfit mb-1">Direct Member Sign In</h4>
                                <p class="text-white-50 mb-0" style="font-size: 12px;">Sign in directly to access your chapter dashboard.</p>
                            </div>

                            <!-- Alert Box -->
                            <?php if (session()->getFlashdata('error')) : ?>
                                <div class="alert alert-danger border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size:13px; background:#FEF3F2; border-left:4px solid #F04438 !important; color:#B42318;">
                                    <i class="ri-error-warning-fill text-danger fs-5"></i>
                                    <span><?= session()->getFlashdata('error'); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (session()->getFlashdata('success')) : ?>
                                <div class="alert alert-success border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size:13px; background:#EFFCF6; border-left:4px solid #12B76A !important; color:#027A48;">
                                    <i class="ri-checkbox-circle-fill text-success fs-5"></i>
                                    <span><?= session()->getFlashdata('success'); ?></span>
                                </div>
                            <?php endif; ?>
                            <div id="heroSignInAlertContainer"></div>

                            <form id="eafHeroSignInForm" method="post" action="<?= base_url('loginCheck') ?>" autocomplete="on">
                                <?= csrf_field(); ?>

                                <div class="mb-3">
                                    <label class="eaf-hero-label" for="hero_email">Registered Email / Username <span>*</span></label>
                                    <div class="eaf-hero-input-group">
                                        <i class="ri-mail-line eaf-hero-input-icon"></i>
                                        <input type="email" name="email" id="hero_email" class="form-control eaf-hero-input" placeholder="name@company.com" autocomplete="username" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label class="eaf-hero-label mb-0" for="hero_password">Password <span>*</span></label>
                                        <a href="<?= base_url('contact'); ?>" class="eaf-hero-forgot-link">Forgot Password?</a>
                                    </div>
                                    <div class="eaf-hero-input-group">
                                        <i class="ri-lock-2-line eaf-hero-input-icon"></i>
                                        <input type="password" name="password" id="hero_password" class="form-control eaf-hero-input pe-5" placeholder="••••••••" autocomplete="current-password" required>
                                        <button type="button" class="eaf-hero-eye-btn" onclick="toggleHeroPassword()" aria-label="Toggle password visibility">
                                            <i class="ri-eye-line" id="heroEyeIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="heroRememberMe" name="remember" value="1" checked>
                                        <label class="form-check-label text-white-50" for="heroRememberMe" style="font-size:12px;">
                                            Keep me signed in
                                        </label>
                                    </div>
                                    <span class="text-white-50" style="font-size:11px;"><i class="ri-checkbox-circle-fill text-success me-1"></i> Verified</span>
                                </div>

                                <button type="submit" id="btnHeroSignInSubmit" class="eaf-btn eaf-btn-gold w-100 justify-content-center py-2 mb-3">
                                    <span id="btnHeroSignInText"><i class="ri-login-box-line me-1"></i> Sign In to Member Portal</span>
                                </button>

                                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-secondary border-opacity-25" style="font-size: 13px;">
                                    <span class="text-white-50">Not a member?</span>
                                    <a href="#" onclick="if(typeof openEafAuthModal==='function'){openEafAuthModal('signup');}return false;" class="fw-bold text-decoration-none" style="color:#C6A667;">
                                        <i class="ri-user-add-line me-1"></i> Apply for Membership
                                    </a>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 2. HERO HEADLINE & VALUE PROP (SECOND ON MOBILE, LEFT SIDE ON DESKTOP) -->
                <div class="col-lg-7 order-2 order-lg-1">
                    <div class="eaf-hero-badge">
                        <i class="ri-shield-check-fill text-warning"></i>
                        <span>Elite Business Networking Platform</span>
                    </div>

                    <h1 class="eaf-hero-title">
                        Grow Your Business Through <span>Trusted Strategic Referrals</span>
                    </h1>

                    <p class="eaf-hero-lead">
                        Join an exclusive network of accomplished entrepreneurs, business owners, and industry leaders collaborating under a structured, proven framework to generate high-value commercial opportunities.
                    </p>

                    <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                        <a href="#" onclick="if(typeof openEafAuthModal==='function'){openEafAuthModal('signup');}return false;" class="eaf-btn eaf-btn-gold eaf-btn-lg">
                            <i class="ri-user-add-line"></i>
                            <span>Apply for Membership</span>
                        </a>

                        <a href="#about" class="eaf-btn eaf-btn-outline-gold eaf-btn-lg">
                            <i class="ri-arrow-down-line"></i>
                            <span>Explore Platform</span>
                        </a>
                    </div>

                    <!-- Highlight Badges -->
                    <div class="d-flex flex-wrap align-items-center gap-3 pt-2">
                        <div class="d-flex align-items-center gap-2 text-white-50" style="font-size: 13px;">
                            <i class="ri-check-double-line text-warning fs-5"></i>
                            <span>Category Exclusivity</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-white-50" style="font-size: 13px;">
                            <i class="ri-check-double-line text-warning fs-5"></i>
                            <span>Structured Weekly Meetings</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-white-50" style="font-size: 13px;">
                            <i class="ri-check-double-line text-warning fs-5"></i>
                            <span>Audited TYFCB Referrals</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- 3. PLATFORM PERFORMANCE METRICS RIBBON (BELOW LOGIN & HEADLINE) -->
            <div class="row mt-5 pt-2">
                <div class="col-12">
                    <div class="p-4 rounded-4" style="background: rgba(16, 27, 53, 0.75); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(198, 166, 103, 0.35); box-shadow: 0 15px 35px rgba(0,0,0,0.3);">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 pb-3 border-bottom border-secondary border-opacity-25 gap-2">
                            <div>
                                <h5 class="text-white mb-0 fw-bold font-family-outfit">Platform Performance & Network Scale</h5>
                                <small class="text-white-50" style="font-size: 12px;">Real-Time Verified Statistics Across All Chapters</small>
                            </div>
                            <span class="badge" style="background:#C6A667; color:#101B35; font-weight:700; padding:6px 12px; border-radius:6px;">LIVE VERIFIED METRICS</span>
                        </div>

                        <div class="row g-3">
                            <!-- CHAPTERS -->
                            <div class="col-6 col-md-3">
                                <div class="eaf-stat-box text-center">
                                    <div class="number"><?= esc($chapters); ?></div>
                                    <div class="label">Chapters</div>
                                    <small class="text-white-50 d-block mt-1" style="font-size:11px;">Active Communities</small>
                                </div>
                            </div>

                            <!-- MEMBERS -->
                            <div class="col-6 col-md-3">
                                <div class="eaf-stat-box text-center">
                                    <div class="number"><?= esc($user); ?></div>
                                    <div class="label">Members</div>
                                    <small class="text-white-50 d-block mt-1" style="font-size:11px;">Verified Business Owners</small>
                                </div>
                            </div>

                            <!-- MITB CLOSED BUSINESS IN INR -->
                            <div class="col-6 col-md-3">
                                <div class="eaf-stat-box text-center">
                                    <div class="number">
                                        <?php
                                        $amount = (float)$MITB;
                                        if ($amount >= 10000000) {
                                            echo '₹ ' . number_format($amount / 10000000, 2) . ' Cr';
                                        } elseif ($amount >= 100000) {
                                            echo '₹ ' . number_format($amount / 100000, 2) . ' L';
                                        } else {
                                            echo '₹ ' . number_format($amount);
                                        }
                                        ?>
                                    </div>
                                    <div class="label">Business Volume</div>
                                    <small class="text-white-50 d-block mt-1" style="font-size:11px;">Closed Business (TYFCB)</small>
                                </div>
                            </div>

                            <!-- AVERAGE MEMBER VALUE -->
                            <div class="col-6 col-md-3">
                                <div class="eaf-stat-box text-center">
                                    <div class="number">
                                        <?php
                                        $avg = (float)$averageMemberValue;
                                        if ($avg >= 100000) {
                                            echo '₹ ' . number_format($avg / 100000, 1) . ' L';
                                        } else {
                                            echo '₹ ' . number_format($avg);
                                        }
                                        ?>
                                    </div>
                                    <div class="label">Avg Member Seat</div>
                                    <small class="text-white-50 d-block mt-1" style="font-size:11px;">Annual Generated Value</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 text-center">
                            <small class="text-white-50" style="font-size:12px;">
                                <i class="ri-shield-flash-line text-warning me-1"></i>
                                Metrics audited across verified chapters in Karnataka, India
                            </small>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- =========================================================
         3. NETWORKING PILLARS ("WHY JOIN US")
    ========================================================= -->
    <section class="py-5" id="benefits" style="background:#FFFFFF;">
        <div class="container py-4">
            <div class="text-center max-w-700 mx-auto mb-5" style="max-width: 720px;">
                <span class="eaf-badge eaf-badge-gold mb-2">Exclusive Advantages</span>
                <h2 class="display-6 fw-bold font-family-outfit">Why Elite Entrepreneurs Choose EAF Connect</h2>
                <p class="text-muted fs-6">
                    We combine the operational discipline of international networking organizations with deep local commercial connectivity to deliver quantifiable returns on your membership.
                </p>
            </div>

            <div class="row g-4">
                <!-- Pillar 1 -->
                <div class="col-md-6 col-lg-3">
                    <div class="eaf-feature-card">
                        <div class="eaf-feature-icon">
                            <i class="ri-shield-user-line"></i>
                        </div>
                        <h4 class="fs-5 fw-bold mb-2">Category Exclusivity</h4>
                        <p class="text-muted fs-7 mb-0 flex-grow-1" style="font-size: 14px;">
                            Only one professional per business specialization is admitted into each chapter, locking out your direct competitors and directing 100% of chapter referrals to your seat.
                        </p>
                    </div>
                </div>

                <!-- Pillar 2 -->
                <div class="col-md-6 col-lg-3">
                    <div class="eaf-feature-card">
                        <div class="eaf-feature-icon">
                            <i class="ri-shake-hands-line"></i>
                        </div>
                        <h4 class="fs-5 fw-bold mb-2">Verified Referrals</h4>
                        <p class="text-muted fs-7 mb-0 flex-grow-1" style="font-size: 14px;">
                            Say goodbye to cold calls. Receive pre-qualified, warm business introductions directly from chapter members who have built personal trust with the prospect.
                        </p>
                    </div>
                </div>

                <!-- Pillar 3 -->
                <div class="col-md-6 col-lg-3">
                    <div class="eaf-feature-card">
                        <div class="eaf-feature-icon">
                            <i class="ri-focus-2-line"></i>
                        </div>
                        <h4 class="fs-5 fw-bold mb-2">Structured 1-to-1s</h4>
                        <p class="text-muted fs-7 mb-0 flex-grow-1" style="font-size: 14px;">
                            Our structured bilateral meeting framework helps you deeply understand fellow members' ideal target clients, value propositions, and strategic referral triggers.
                        </p>
                    </div>
                </div>

                <!-- Pillar 4 -->
                <div class="col-md-6 col-lg-3">
                    <div class="eaf-feature-card">
                        <div class="eaf-feature-icon">
                            <i class="ri-bar-chart-grouped-line"></i>
                        </div>
                        <h4 class="fs-5 fw-bold mb-2">Transparent ROI</h4>
                        <p class="text-muted fs-7 mb-0 flex-grow-1" style="font-size: 14px;">
                            Track every rupee of closed business, referral exchanged, and meeting attended through our enterprise portal with comprehensive weekly and quarterly reporting.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================
         4. ABOUT EAF CONNECT & LEADERSHIP PHILOSOPHY
    ========================================================= -->
    <section class="py-5" id="about" style="background:#FAF9F6; border-top: 1px solid #E2E8F0; border-bottom: 1px solid #E2E8F0;">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="position-relative">
                        <div class="p-4 p-md-5 rounded-4 eaf-card-navy">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <img src="<?= base_url('images/logo5.png') ?>" alt="EAF Connect" style="height: 60px; background:#fff; border-radius:10px; padding:4px;">
                                <div>
                                    <h4 class="text-white mb-0 fw-bold">EAF CONNECT</h4>
                                    <small style="color:#C6A667; font-weight:700; letter-spacing:0.05em;">WORLD INFRA NETWORKING</small>
                                </div>
                            </div>

                            <p class="text-white-50 mb-4" style="line-height: 1.8;">
                                Founded with the singular vision of connecting high-integrity business founders, EAF Connect operates as a structured referral generation powerhouse. Our philosophy is rooted in collaborative prosperity—when leaders empower each other, entire business ecosystems thrive.
                            </p>

                            <div class="row g-3 pt-3 border-top border-secondary border-opacity-25">
                                <div class="col-6">
                                    <div class="fs-4 fw-bold" style="color:#C6A667;"><?= esc($chapters); ?> Active</div>
                                    <small class="text-white-50">Local Chapter Ecosystems</small>
                                </div>
                                <div class="col-6">
                                    <div class="fs-4 fw-bold" style="color:#C6A667;">100% Verified</div>
                                    <small class="text-white-50">KYC & Business Audit</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <span class="eaf-badge eaf-badge-gold mb-2">Our Operating System</span>
                    <h2 class="display-6 fw-bold font-family-outfit mb-4">A Proven Architecture for Business Scalability</h2>
                    
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0" style="width: 44px; height: 44px; border-radius: 10px; background: rgba(16,27,53,0.08); color:#101B35; display:flex; align-items:center; justify-content:center; font-size:20px;">
                                <i class="ri-calendar-event-line"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Weekly Executive Meetings</h5>
                                <p class="text-muted mb-0 fs-7" style="font-size:14px;">
                                    Chapters convene weekly under a disciplined agenda designed to spotlight member capabilities, pass qualified referrals, and review collective chapter performance.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0" style="width: 44px; height: 44px; border-radius: 10px; background: rgba(198,166,103,0.15); color:#A88849; display:flex; align-items:center; justify-content:center; font-size:20px;">
                                <i class="ri-trophy-line"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Executive Leadership Governance</h5>
                                <p class="text-muted mb-0 fs-7" style="font-size:14px;">
                                    Each chapter is steered by elected officers: President, Vice President, Secretary Treasurer, and Growth Coordinators ensuring operational rigor and meeting discipline.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0" style="width: 44px; height: 44px; border-radius: 10px; background: rgba(13,148,136,0.12); color:#0D9488; display:flex; align-items:center; justify-content:center; font-size:20px;">
                                <i class="ri-line-chart-line"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Audited Financial Tracking (MITB)</h5>
                                <p class="text-muted mb-0 fs-7" style="font-size:14px;">
                                    Thank You For Closed Business slips ensure full accountability and real economic measurement of chapter value generated for every seat.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================
         5. ACTIVE CHAPTERS DIRECTORY
    ========================================================= -->
    <section class="py-5" id="chapters" style="background:#FFFFFF;">
        <div class="container py-4">
            <div class="d-flex flex-wrap align-items-end justify-content-between mb-5 gap-3">
                <div>
                    <span class="eaf-badge eaf-badge-gold mb-2">Regional Chapters</span>
                    <h2 class="display-6 fw-bold font-family-outfit mb-0">Explore Our Chapters</h2>
                </div>
                <a href="#" data-bs-toggle="modal" data-bs-target="#joinCommunityModal" class="eaf-btn eaf-btn-primary">
                    <i class="ri-building-line"></i>
                    <span>Visit a Chapter</span>
                </a>
            </div>

            <div class="row g-4">
                <?php if (!empty($activeChapters)): ?>
                    <?php foreach ($activeChapters as $ch): ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="eaf-chapter-card h-100 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="badge" style="background:rgba(16,27,53,0.08); color:#101B35; font-size:11px; font-weight:700;">
                                        CODE: <?= esc($ch['chapter_code']); ?>
                                    </span>
                                    <span class="badge bg-success-subtle text-success">Active</span>
                                </div>

                                <h4 class="fs-5 fw-bold mb-2 text-navy font-family-outfit">
                                    <?= esc($ch['chapter_name']); ?>
                                </h4>

                                <p class="text-muted fs-7 mb-3" style="font-size: 13px;">
                                    <?= !empty($ch['chapter_description']) ? esc(character_limiter($ch['chapter_description'], 80)) : 'Elite business networking chapter focused on cross-industry partnerships and referrals.'; ?>
                                </p>

                                <div class="mt-auto pt-3 border-top border-light d-flex flex-column gap-2" style="font-size: 13px;">
                                    <div class="d-flex align-items-center justify-content-between text-muted">
                                        <span><i class="ri-map-pin-line text-warning me-1"></i>Location:</span>
                                        <strong><?= esc($ch['location'] ?: 'Karnataka'); ?></strong>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between text-muted">
                                        <span><i class="ri-calendar-line text-warning me-1"></i>Meeting Day:</span>
                                        <strong><?= esc($ch['meeting_day'] ?: 'Weekly'); ?></strong>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between text-muted">
                                        <span><i class="ri-group-line text-warning me-1"></i>Seats Filled:</span>
                                        <strong class="text-success"><?= esc($ch['member_count']); ?> Members</strong>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#joinCommunityModal" class="btn btn-sm btn-outline-dark w-100">
                                        Request Chapter Visit
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-4 text-muted">
                        <i class="ri-community-line fs-2 mb-2 d-block"></i>
                        No chapters currently active.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- =========================================================
         6. TESTIMONIALS & TRUST
    ========================================================= -->
    <section class="py-5" style="background:#FAF9F6; border-top:1px solid #E2E8F0;">
        <div class="container py-4">
            <div class="text-center max-w-700 mx-auto mb-5" style="max-width: 720px;">
                <span class="eaf-badge eaf-badge-gold mb-2">Member Testimonials</span>
                <h2 class="display-6 fw-bold font-family-outfit">Real Results from Real Business Leaders</h2>
                <p class="text-muted fs-6">Hear how founders and executives leverage EAF Connect to build lasting strategic alliances and multiply their annual revenue.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="eaf-card p-4 h-100 d-flex flex-column">
                        <div class="d-flex align-items-center gap-1 text-warning mb-3">
                            <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
                        </div>
                        <p class="text-muted fs-6 flex-grow-1" style="font-size:14px; line-height:1.7;">
                            "Within my first 6 months at EAF Connect, our firm generated over ₹45 Lakhs in verified closed business. The category exclusivity ensures that when fellow members hear about an infrastructure project, my firm is the only one they call."
                        </p>
                        <div class="d-flex align-items-center gap-3 pt-3 border-top border-light">
                            <div class="avatar-sm rounded-circle bg-navy text-white d-flex align-items-center justify-content-center fw-bold" style="width:42px; height:42px; background:#101B35;">
                                HK
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Harri Kumar</h6>
                                <small class="text-muted">Hosting Baba & Cloud Infra</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="eaf-card p-4 h-100 d-flex flex-column">
                        <div class="d-flex align-items-center gap-1 text-warning mb-3">
                            <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
                        </div>
                        <p class="text-muted fs-6 flex-grow-1" style="font-size:14px; line-height:1.7;">
                            "The weekly meeting structure and disciplined 1-to-1 framework transformed how we pitch and partner. EAF Connect isn't just a networking platform—it's an outsourced executive sales team that works for you every day."
                        </p>
                        <div class="d-flex align-items-center gap-3 pt-3 border-top border-light">
                            <div class="avatar-sm rounded-circle bg-navy text-white d-flex align-items-center justify-content-center fw-bold" style="width:42px; height:42px; background:#172B50;">
                                RS
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Rajesh Sharma</h6>
                                <small class="text-muted">Corporate Legal Advisor</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="eaf-card p-4 h-100 d-flex flex-column">
                        <div class="d-flex align-items-center gap-1 text-warning mb-3">
                            <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
                        </div>
                        <p class="text-muted fs-6 flex-grow-1" style="font-size:14px; line-height:1.7;">
                            "As a business founder, the calibre of members inside EAF is unmatched. These are verified business leaders, audited for credibility, who genuinely invest in each other's success and commercial scalability."
                        </p>
                        <div class="d-flex align-items-center gap-3 pt-3 border-top border-light">
                            <div class="avatar-sm rounded-circle bg-navy text-white d-flex align-items-center justify-content-center fw-bold" style="width:42px; height:42px; background:#0B1325;">
                                AP
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Ananya Patel</h6>
                                <small class="text-muted">Commercial Real Estate</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================
         7. MEMBERSHIP REGISTRATION CTA BANNER
    ========================================================= -->
    <section class="py-5" style="background: linear-gradient(135deg, #101B35 0%, #172B50 100%); color:#FFFFFF; position:relative; overflow:hidden;">
        <div class="container py-5 text-center position-relative" style="z-index: 2;">
            <div class="max-w-700 mx-auto" style="max-width: 760px;">
                <span class="badge mb-3 px-3 py-2" style="background:rgba(198,166,103,0.2); color:#C6A667; border:1px solid rgba(198,166,103,0.4); font-size:12px; letter-spacing:0.06em;">
                    EXCLUSIVE MEMBERSHIP ADMISSIONS
                </span>
                <h2 class="display-5 fw-bold font-family-outfit mb-3 text-white">
                    Ready to Claim Category Exclusivity in Your Chapter?
                </h2>
                <p class="text-white-50 fs-5 mb-4">
                    Seats are limited to one business per category per chapter. Apply today to secure your specialization before your competitors do.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="#" onclick="if(typeof openEafAuthModal==='function'){openEafAuthModal('signup');}return false;" class="eaf-btn eaf-btn-gold eaf-btn-lg">
                        <i class="ri-shield-user-line"></i>
                        <span>Apply for Membership Now</span>
                    </a>
                    <a href="<?= base_url('contact'); ?>" class="eaf-btn eaf-btn-outline-gold eaf-btn-lg">
                        <i class="ri-phone-line"></i>
                        <span>Contact Executive Office</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================
         8. CORPORATE FOOTER
    ========================================================= -->
    <?= view('include/footer.php'); ?>

    <!-- Direct Hero Sign In Controller -->
    <script>
    function toggleHeroPassword() {
        const input = document.getElementById('hero_password');
        const icon = document.getElementById('heroEyeIcon');
        if (!input || !icon) return;
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'ri-eye-off-line';
        } else {
            input.type = 'password';
            icon.className = 'ri-eye-line';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const heroForm = document.getElementById('eafHeroSignInForm');
        if (!heroForm) return;

        heroForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnHeroSignInSubmit');
            const btnText = document.getElementById('btnHeroSignInText');
            const alertBox = document.getElementById('heroSignInAlertContainer');

            btn.disabled = true;
            btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Authenticating...';

            const formData = new FormData(heroForm);

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
                        <div class="alert alert-success border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size:13px; background:#EFFCF6; border-left:4px solid #12B76A !important;">
                            <i class="ri-checkbox-circle-fill text-success fs-5"></i>
                            <span style="color:#027A48; font-weight:600;">${data.message || 'Login successful! Redirecting...'}</span>
                        </div>
                    `;
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 500);
                } else {
                    alertBox.innerHTML = `
                        <div class="alert alert-danger border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size:13px; background:#FEF3F2; border-left:4px solid #F04438 !important;">
                            <i class="ri-error-warning-fill text-danger fs-5"></i>
                            <span style="color:#B42318; font-weight:600;">${data.message || 'Invalid email or password.'}</span>
                        </div>
                    `;
                }
            })
            .catch(err => {
                const isStaticPreview = window.location.hostname.includes('ship.place') || window.location.hostname.includes('github.io') || window.location.protocol === 'file:';
                if (isStaticPreview) {
                    btn.disabled = false;
                    btnText.innerHTML = '<i class="ri-login-box-line me-1"></i> Sign In to Member Portal';
                    alertBox.innerHTML = `
                        <div class="alert alert-success border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size:13px; background:#EFFCF6; border-left:4px solid #12B76A !important;">
                            <i class="ri-checkbox-circle-fill text-success fs-5"></i>
                            <span style="color:#027A48; font-weight:600;">Preview Demo: Authenticated! Redirecting to Member Dashboard...</span>
                        </div>
                    `;
                    setTimeout(() => {
                        window.location.href = 'member/dashboard.html';
                    }, 500);
                } else {
                    heroForm.submit();
                }
            });
        });
    });
    </script>

</body>
</html>