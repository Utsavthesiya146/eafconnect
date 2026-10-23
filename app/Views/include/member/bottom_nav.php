<?php
/**
 * EAF Connect - Mobile Bottom Navigation Bar & Quick Slips Bottom Sheet
 * Phase 1 Revamp: Thumb-zone optimized navigation for Mobile Handsets & WebView
 */
$currentUri = service('uri')->getPath();
$isDashboard = strpos($currentUri, 'member/dashboard') !== false || $currentUri === '' || $currentUri === '/';
$isConnect   = strpos($currentUri, 'member/connect') !== false;
$isChapter   = strpos($currentUri, 'member/mychapter') !== false || strpos($currentUri, 'member/chapter') !== false;
$isProfile   = strpos($currentUri, 'member/profile') !== false;
?>

<!-- ==========================================================================
     MOBILE BOTTOM NAVIGATION BAR (FIXED THUMB ZONE)
     ========================================================================== -->
<nav class="eaf-bottom-nav-bar d-lg-none" id="eafBottomNav" aria-label="Mobile Navigation">
    <!-- 1. Home / Dashboard -->
    <a href="<?= base_url('member/dashboard'); ?>" class="eaf-nav-tab <?= $isDashboard ? 'active' : ''; ?>" id="bottomNavHome">
        <i class="ri-dashboard-3-line"></i>
        <span>Home</span>
    </a>

    <!-- 2. Connects / Referrals -->
    <a href="<?= base_url('member/connect'); ?>" class="eaf-nav-tab <?= $isConnect ? 'active' : ''; ?>" id="bottomNavConnects">
        <i class="ri-shake-hands-line"></i>
        <span>Connects</span>
    </a>

    <!-- 3. Central Hero Quick Slips Button (+) -->
    <button type="button" class="eaf-nav-center-btn" id="bottomNavQuickSlips" data-bs-toggle="modal" data-bs-target="#eafQuickSlipsModal" aria-label="Quick Action Slips">
        <i class="ri-add-line"></i>
    </button>

    <!-- 4. My Chapter -->
    <a href="<?= base_url('member/mychapter'); ?>" class="eaf-nav-tab <?= $isChapter ? 'active' : ''; ?>" id="bottomNavChapter">
        <i class="ri-community-line"></i>
        <span>Chapter</span>
    </a>

    <!-- 5. Profile -->
    <a href="<?= base_url('member/profile'); ?>" class="eaf-nav-tab <?= $isProfile ? 'active' : ''; ?>" id="bottomNavProfile">
        <i class="ri-user-3-line"></i>
        <span>Profile</span>
    </a>
</nav>

<!-- ==========================================================================
     QUICK SLIPS BOTTOM SHEET MODAL (MOBILE SLIDE-UP)
     ========================================================================== -->
<div class="modal fade eaf-bottom-sheet-modal" id="eafQuickSlipsModal" tabindex="-1" aria-labelledby="eafQuickSlipsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content eaf-bottom-sheet-content">
            <div class="eaf-sheet-handle"></div>
            <div class="modal-header border-0 pb-1 pt-2 px-4">
                <h6 class="modal-title fw-bold text-dark" id="eafQuickSlipsModalLabel" style="font-size:16px;">
                    <i class="ri-flashlight-line text-warning me-2"></i>Quick Action Slips
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="eaf-sheet-grid">
                    <!-- Slip 1: Give Connect -->
                    <div class="eaf-sheet-item" onclick="handleQuickSlip('referral');">
                        <div class="eaf-sheet-icon" style="background: rgba(37, 99, 235, 0.12); color: #2563EB;">
                            <i class="ri-send-plane-fill"></i>
                        </div>
                        <div class="eaf-sheet-title">Give Connect</div>
                        <div class="eaf-sheet-sub">Create a new connect</div>
                    </div>

                    <!-- Slip 2: Received Connects -->
                    <a href="<?= base_url('member/connect'); ?>" class="eaf-sheet-item">
                        <div class="eaf-sheet-icon" style="background: rgba(5, 150, 105, 0.12); color: #059669;">
                            <i class="ri-inbox-archive-fill"></i>
                        </div>
                        <div class="eaf-sheet-title">Received Connects</div>
                        <div class="eaf-sheet-sub">View inbound leads</div>
                    </a>

                    <!-- Slip 3: F2F Meeting (1-to-1) -->
                    <div class="eaf-sheet-item" onclick="handleQuickSlip('f2f');">
                        <div class="eaf-sheet-icon" style="background: rgba(124, 58, 237, 0.12); color: #7C3AED;">
                            <i class="ri-user-voice-fill"></i>
                        </div>
                        <div class="eaf-sheet-title">F2F Meeting</div>
                        <div class="eaf-sheet-sub">Record 1-to-1 meeting</div>
                    </div>

                    <!-- Slip 4: Closed Business (MITB) -->
                    <div class="eaf-sheet-item" onclick="handleQuickSlip('mitb');">
                        <div class="eaf-sheet-icon" style="background: rgba(217, 119, 6, 0.12); color: #D97706;">
                            <i class="ri-money-rupee-circle-fill"></i>
                        </div>
                        <div class="eaf-sheet-title">MITB</div>
                        <div class="eaf-sheet-sub">Record closed business</div>
                    </div>

                    <!-- Slip 5: Invite Visitor -->
                    <div class="eaf-sheet-item" style="grid-column: span 2;" onclick="handleQuickSlip('visitor');">
                        <div class="d-flex align-items-center gap-3 w-100 justify-content-center">
                            <div class="eaf-sheet-icon mb-0" style="background: rgba(13, 148, 136, 0.12); color: #0D9488;">
                                <i class="ri-user-add-fill"></i>
                            </div>
                            <div class="text-start">
                                <div class="eaf-sheet-title">Invite a Visitor</div>
                                <div class="eaf-sheet-sub">Register guest for upcoming meeting</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function handleQuickSlip(type) {
    const slipModalEl = document.getElementById('eafQuickSlipsModal');
    if (slipModalEl) {
        const bsModal = bootstrap.Modal.getInstance(slipModalEl);
        if (bsModal) {
            bsModal.hide();
        }
    }

    setTimeout(function() {
        if (type === 'referral') {
            if (typeof add_new_connects === 'function') {
                add_new_connects();
            } else {
                window.location.href = "<?= base_url('member/connect'); ?>";
            }
        } else if (type === 'mitb') {
            if (typeof add_mitb === 'function') {
                add_mitb();
            } else {
                window.location.href = "<?= base_url('member/mitb'); ?>";
            }
        } else if (type === 'f2f') {
            if (typeof add_f2f === 'function') {
                add_f2f();
            } else {
                window.location.href = "<?= base_url('member/f2f'); ?>";
            }
        } else if (type === 'visitor') {
            if (typeof add_vt === 'function') {
                add_vt();
            } else {
                window.location.href = "<?= base_url('member/visitor'); ?>";
            }
        }
    }, 300);
}
</script>
