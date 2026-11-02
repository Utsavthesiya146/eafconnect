<?php
$session = session();
$userId = $session->get('user_id');
$role = $session->get('role');
?>
<!-- EAF Connect Design System -->
<link rel="stylesheet" href="<?= base_url('assets2/css/eaf-design-system.css') ?>?v=5.0">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">

<style>
/* =========================================================
   PREMIUM CORPORATE HEADER
   Deep Midnight Navy (#101B35) + Champagne Gold (#C6A667)
========================================================= */
.eaf-navbar-master {
    position: sticky;
    top: 0;
    z-index: 1050;
    background: #101B35;
    border-bottom: 1px solid rgba(198, 166, 103, 0.25);
    box-shadow: 0 4px 20px rgba(16, 27, 53, 0.25);
    transition: all 0.2s ease;
}

.eaf-topbar-strip {
    background: #0B1325;
    padding: 7px 0;
    font-size: 12px;
    color: #94A3B8;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.eaf-topbar-strip a {
    color: #E4D4AE;
    text-decoration: none;
    font-weight: 500;
}

.eaf-topbar-strip a:hover {
    color: #FFFFFF;
}

.eaf-nav-container {
    padding: 12px 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.eaf-brand-group {
    display: flex;
    align-items: center;
    gap: 14px;
    text-decoration: none;
}

.eaf-brand-logo {
    height: 52px;
    width: auto;
    background: #FFFFFF;
    border-radius: 8px;
    padding: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.eaf-brand-title {
    display: flex;
    flex-direction: column;
}

.eaf-brand-title .main-title {
    color: #FFFFFF;
    font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    font-size: 20px;
    font-weight: 800;
    letter-spacing: -0.02em;
    line-height: 1.1;
}

.eaf-brand-title .main-title span {
    color: #C6A667;
}

.eaf-brand-title .sub-title {
    color: #94A3B8;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.eaf-desktop-nav {
    display: flex;
    align-items: center;
    gap: 4px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.eaf-desktop-nav li a {
    color: #E2E8F0;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.03em;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
}

.eaf-desktop-nav li a:hover,
.eaf-desktop-nav li a.active {
    color: #C6A667;
    background: rgba(198, 166, 103, 0.12);
}

.eaf-header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.eaf-mobile-toggle {
    display: none;
    background: transparent;
    border: 1px solid rgba(198, 166, 103, 0.3);
    color: #FFFFFF;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 22px;
    cursor: pointer;
}

@media (max-width: 991px) {
    .eaf-desktop-nav {
        display: none;
    }
    .eaf-mobile-toggle {
        display: block;
    }
    .eaf-topbar-strip {
        display: none;
    }
}
</style>

<!-- TOP METADATA BAR -->
<div class="eaf-topbar-strip">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-4">
            <span class="d-inline-flex align-items-center gap-2">
                <i class="ri-phone-fill text-warning"></i>
                <span>Call Us:</span>
                <a href="tel:+919886115993">+91-9886115993</a>
            </span>
            <span class="d-inline-flex align-items-center gap-2">
                <i class="ri-mail-fill text-warning"></i>
                <span>Email:</span>
                <a href="mailto:info@eafconnect.com">info@eafconnect.com</a>
            </span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white-50"><i class="ri-map-pin-2-fill text-warning me-1"></i>Karnataka, India</span>
            <span class="text-white-50">|</span>
            <a href="<?= base_url('contact'); ?>" class="text-decoration-none">
                <i class="ri-customer-service-2-line me-1"></i>Support Desk
            </a>
        </div>
    </div>
</div>

<!-- MAIN NAVBAR -->
<nav class="eaf-navbar-master">
    <div class="container eaf-nav-container">
        <!-- Brand Logo -->
        <a href="<?= base_url(); ?>" class="eaf-brand-group">
            <img src="<?= base_url('images/logo5.png'); ?>" alt="EAF Connect" class="eaf-brand-logo">
            <div class="eaf-brand-title">
                <div class="main-title">EAF <span>CONNECT</span></div>
                <div class="sub-title">World Infra Networking</div>
            </div>
        </a>

        <!-- Desktop Navigation Links -->
        <ul class="eaf-desktop-nav">
            <li><a href="<?= base_url(); ?>">HOME</a></li>
            <li><a href="<?= base_url('#about'); ?>">ABOUT</a></li>
            <li><a href="<?= base_url('#chapters'); ?>">CHAPTERS</a></li>
            <li><a href="<?= base_url('#benefits'); ?>">BENEFITS</a></li>
            <li><a href="<?= base_url('blogs'); ?>">BLOGS</a></li>
            <li><a href="<?= base_url('contact'); ?>">CONTACT</a></li>
        </ul>

        <!-- Action CTAs -->
        <div class="eaf-header-actions">
            <?php if ($userId): ?>
                <?php if ($role == 'admin'): ?>
                    <a href="<?= base_url('admin/dashboard'); ?>" class="eaf-btn eaf-btn-gold">
                        <i class="ri-shield-star-line"></i>
                        <span>Admin Dashboard</span>
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('member/dashboard'); ?>" class="eaf-btn eaf-btn-gold">
                        <i class="ri-dashboard-3-line"></i>
                        <span>Member Portal</span>
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?= base_url('member_login'); ?>" onclick="if(typeof openEafAuthModal === 'function'){ openEafAuthModal('signin'); return false; }" class="eaf-btn eaf-btn-gold">
                    <i class="ri-login-box-line"></i>
                    <span>Sign In</span>
                </a>
                <a href="#" onclick="if(typeof openEafAuthModal === 'function'){ openEafAuthModal('signup'); return false; }" class="eaf-btn eaf-btn-outline-gold d-none d-xl-inline-flex">
                    <i class="ri-user-add-line"></i>
                    <span>Apply / Sign Up</span>
                </a>
            <?php endif; ?>

            <!-- Mobile Hamburger Toggle -->
            <button class="eaf-mobile-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#eafMobileNav" aria-controls="eafMobileNav">
                <i class="ri-menu-3-line"></i>
            </button>
        </div>
    </div>
</nav>

<!-- OFFCANVAS MOBILE DRAWER -->
<div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="eafMobileNav" style="background:#101B35 !important; border-left: 1px solid rgba(198,166,103,0.3);">
    <div class="offcanvas-header border-bottom border-secondary border-opacity-25 p-3">
        <div class="d-flex align-items-center gap-2">
            <img src="<?= base_url('images/logo5.png'); ?>" alt="EAF Connect" style="height:40px; background:#fff; border-radius:6px; padding:2px;">
            <span class="fs-5 fw-bold text-white font-family-outfit">EAF <span style="color:#C6A667;">CONNECT</span></span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-4">
        <ul class="nav flex-column gap-2 mb-4">
            <li class="nav-item">
                <a class="nav-link text-white py-2 px-3 rounded" href="<?= base_url(); ?>"><i class="ri-home-4-line me-2 text-warning"></i>Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white py-2 px-3 rounded" href="<?= base_url('#about'); ?>"><i class="ri-information-line me-2 text-warning"></i>About EAF</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white py-2 px-3 rounded" href="<?= base_url('#chapters'); ?>"><i class="ri-community-line me-2 text-warning"></i>Chapters</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white py-2 px-3 rounded" href="<?= base_url('#benefits'); ?>"><i class="ri-trophy-line me-2 text-warning"></i>Membership Benefits</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white py-2 px-3 rounded" href="<?= base_url('blogs'); ?>"><i class="ri-article-line me-2 text-warning"></i>Blogs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white py-2 px-3 rounded" href="<?= base_url('contact'); ?>"><i class="ri-mail-line me-2 text-warning"></i>Contact</a>
            </li>
        </ul>

        <div class="border-top border-secondary border-opacity-25 pt-3">
            <?php if ($userId): ?>
                <a href="<?= $role == 'admin' ? base_url('admin/dashboard') : base_url('member/dashboard') ?>" class="eaf-btn eaf-btn-gold w-100 justify-content-center mb-2">
                    <i class="ri-dashboard-line"></i> Dashboard
                </a>
                <a href="<?= base_url('logout'); ?>" class="btn btn-outline-danger w-100 btn-sm">
                    <i class="ri-logout-box-line"></i> Logout
                </a>
            <?php else: ?>
                <a href="<?= base_url('member_login'); ?>" onclick="if(typeof openEafAuthModal === 'function'){ openEafAuthModal('signin'); return false; }" data-bs-dismiss="offcanvas" class="eaf-btn eaf-btn-gold w-100 justify-content-center mb-2">
                    <i class="ri-login-box-line"></i> Member Sign In
                </a>
                <a href="#" onclick="if(typeof openEafAuthModal === 'function'){ openEafAuthModal('signup'); return false; }" data-bs-dismiss="offcanvas" class="eaf-btn eaf-btn-outline-gold w-100 justify-content-center mb-2">
                    <i class="ri-user-add-line"></i> Apply for Membership
                </a>
                <a href="<?= base_url('admin'); ?>" class="btn btn-outline-light w-100 btn-sm mt-2">
                    <i class="ri-admin-line"></i> Admin Portal
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>