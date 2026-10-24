<!-- ========================= EAF CONNECT CORPORATE FOOTER ========================= -->
<footer class="eaf-corporate-footer">
    <div class="container">
        <div class="row g-4 py-5">
            <!-- Brand & Mission Column -->
            <div class="col-12 col-lg-5">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="<?= base_url('images/logo5.png') ?>" alt="EAF Connect" style="height: 54px; background:#fff; border-radius:8px; padding:3px;">
                    <div>
                        <h4 class="mb-0 text-white font-family-outfit fw-bold">EAF <span style="color:#C6A667;">CONNECT</span></h4>
                        <small class="text-white-50 letter-spacing-1 text-uppercase" style="font-size:11px;">World Infra Networking</small>
                    </div>
                </div>

                <p class="text-white-50 mb-4 pe-lg-4" style="font-size: 14px; line-height: 1.7;">
                    EAF Connect is an elite business networking organization empowering entrepreneurs, business owners, and corporate leaders to build trusted relationships, exchange high-value referrals, and scale sustainable enterprises.
                </p>

                <div class="d-flex gap-3">
                    <div class="eaf-footer-pill">
                        <i class="ri-team-line text-warning"></i>
                        <div>
                            <strong>Structured Network</strong>
                            <small class="d-block text-white-50">Verified Members</small>
                        </div>
                    </div>
                    <div class="eaf-footer-pill">
                        <i class="ri-shake-hands-line text-warning"></i>
                        <div>
                            <strong>Quality Referrals</strong>
                            <small class="d-block text-white-50">High Conversion</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-6 col-lg-2">
                <h6 class="text-white fw-bold mb-3 text-uppercase letter-spacing-1" style="color:#C6A667 !important;">Quick Links</h6>
                <ul class="list-unstyled d-flex flex-column gap-2 mb-0" style="font-size: 14px;">
                    <li><a href="<?= base_url(); ?>" class="eaf-footer-link"><i class="ri-arrow-right-s-line me-1 text-warning"></i>Home</a></li>
                    <li><a href="<?= base_url('#about'); ?>" class="eaf-footer-link"><i class="ri-arrow-right-s-line me-1 text-warning"></i>About EAF</a></li>
                    <li><a href="<?= base_url('#chapters'); ?>" class="eaf-footer-link"><i class="ri-arrow-right-s-line me-1 text-warning"></i>Chapters</a></li>
                    <li><a href="<?= base_url('#benefits'); ?>" class="eaf-footer-link"><i class="ri-arrow-right-s-line me-1 text-warning"></i>Why Join</a></li>
                    <li><a href="<?= base_url('blogs'); ?>" class="eaf-footer-link"><i class="ri-arrow-right-s-line me-1 text-warning"></i>Articles</a></li>
                    <li><a href="<?= base_url('contact'); ?>" class="eaf-footer-link"><i class="ri-arrow-right-s-line me-1 text-warning"></i>Contact Support</a></li>
                </ul>
            </div>

            <!-- Member Access -->
            <div class="col-6 col-lg-2">
                <h6 class="text-white fw-bold mb-3 text-uppercase letter-spacing-1" style="color:#C6A667 !important;">Portals</h6>
                <ul class="list-unstyled d-flex flex-column gap-2 mb-0" style="font-size: 14px;">
                    <li><a href="<?= base_url('/#hero_email'); ?>" class="eaf-footer-link"><i class="ri-login-circle-line me-1 text-warning"></i>Member Login</a></li>
                    <li><a href="<?= base_url('member/dashboard'); ?>" class="eaf-footer-link"><i class="ri-dashboard-line me-1 text-warning"></i>My Dashboard</a></li>
                    <li><a href="<?= base_url('member/mychapter'); ?>" class="eaf-footer-link"><i class="ri-community-line me-1 text-warning"></i>My Chapter</a></li>
                    <li><a href="<?= base_url('member/search'); ?>" class="eaf-footer-link"><i class="ri-search-line me-1 text-warning"></i>Directory Search</a></li>
                    <li><a href="<?= base_url('admin'); ?>" class="eaf-footer-link"><i class="ri-shield-keyhole-line me-1 text-warning"></i>Admin Desk</a></li>
                </ul>
            </div>

            <!-- Connect & CTA -->
            <div class="col-12 col-lg-3">
                <h6 class="text-white fw-bold mb-3 text-uppercase letter-spacing-1" style="color:#C6A667 !important;">Connect With Us</h6>
                <p class="text-white-50 mb-3" style="font-size: 13px;">
                    Ready to accelerate your professional growth? Join Karnataka's premier business network today.
                </p>

                <!-- Social Handles -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <a href="https://www.facebook.com/connecteaf" target="_blank" class="eaf-social-btn" title="Facebook">
                        <i class="ri-facebook-fill"></i>
                    </a>
                    <a href="https://www.instagram.com/connect.eaf" target="_blank" class="eaf-social-btn" title="Instagram">
                        <i class="ri-instagram-line"></i>
                    </a>
                    <a href="https://www.youtube.com/@connecteaf" target="_blank" class="eaf-social-btn" title="YouTube">
                        <i class="ri-youtube-fill"></i>
                    </a>
                    <a href="https://wa.me/919341873532" target="_blank" class="eaf-social-btn" title="WhatsApp Support">
                        <i class="ri-whatsapp-line"></i>
                    </a>
                </div>

                <a href="#" onclick="if(typeof openEafAuthModal==='function'){openEafAuthModal('signup');}return false;" class="eaf-btn eaf-btn-gold w-100 justify-content-center">
                    <i class="ri-user-add-line"></i>
                    <span>Apply for Membership</span>
                </a>
            </div>
        </div>

        <!-- Footer Bottom Bar -->
        <div class="border-top border-secondary border-opacity-25 py-3 d-flex flex-wrap align-items-center justify-content-between gap-2" style="font-size: 13px;">
            <div class="text-white-50">
                &copy; <?= date('Y') ?> EAF Connect. All Rights Reserved. Enterprise Networking Platform.
            </div>
            <div class="text-white-50">
                Technology Partner: <a href="https://webhostingbaba.com" target="_blank" class="text-warning text-decoration-none fw-semibold">Hosting Baba</a>
            </div>
        </div>
    </div>
</footer>

<style>
.eaf-corporate-footer {
    background: #101B35;
    border-top: 1px solid rgba(198, 166, 103, 0.25);
    color: #94A3B8;
}

.eaf-footer-link {
    color: #CBD5E1;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
}

.eaf-footer-link:hover {
    color: #C6A667;
    transform: translateX(3px);
}

.eaf-footer-pill {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    font-size: 13px;
    color: #FFFFFF;
}

.eaf-footer-pill i {
    font-size: 20px;
}

.eaf-social-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #FFFFFF;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.eaf-social-btn:hover {
    background: #C6A667;
    color: #101B35;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(198, 166, 103, 0.3);
}
</style>

<!-- INCLUDE LEAD CAPTURE MODAL -->
<?php if (file_exists(APPPATH . 'Views/include/modal.php')) {
    include(APPPATH . 'Views/include/modal.php');
} ?>

<!-- INCLUDE EAF AUTHENTICATION MODAL (SIGN IN & SIGN UP) -->
<?php if (file_exists(APPPATH . 'Views/include/auth_modal.php')) {
    include(APPPATH . 'Views/include/auth_modal.php');
} ?>