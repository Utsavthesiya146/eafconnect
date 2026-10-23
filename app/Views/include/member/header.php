<?php
$db = \Config\Database::connect();
$headerUserCode = session()->get('user_code');
$headerMember = $db->table('users')->where('user_code', $headerUserCode)->get()->getRowArray();
$headerChapter = $db->table('chapter_members cm')
    ->select('c.chapter_name')
    ->join('chapters c', 'c.chapter_code = cm.chapter_code', 'left')
    ->where('cm.user_code', $headerUserCode)
    ->where('cm.members_status', '1')
    ->get()->getRowArray();

$headerName = !empty($headerMember['name']) ? $headerMember['name'] : (session()->get('name') ?: 'Harri Kumar');
$headerPhoto = !empty($headerMember['profile_photo']) ? $headerMember['profile_photo'] : (session()->get('profile_photo') ?: 'empty.png');
$headerChapterName = !empty($headerChapter['chapter_name']) ? $headerChapter['chapter_name'] : (session()->get('chapter_name') ?: 'Pu Ti Na');
$headerBusiness = !empty($headerMember['business_name']) ? $headerMember['business_name'] : (!empty($headerMember['company_name']) ? $headerMember['company_name'] : 'HOSTINGBABA');
$headerIndustry = !empty($headerMember['industry']) ? $headerMember['industry'] : 'Web Development & Digital Marketing';
$headerRole = !empty($headerMember['member_role']) ? $headerMember['member_role'] : (session()->get('member_role') ?: 'Sadasyaru');

$unreadNotificationsCount = $db->table('notifications')
    ->where('user_code', $headerUserCode)
    ->where('status', 'unread')
    ->countAllResults();
?>

<!-- EAF Connect: Master Revamp Fonts & Stylesheet (KVV Benchmark) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
<link rel="stylesheet" href="<?= base_url('assets2/css/eaf-design-system.css') ?>?v=5.0">
<link rel="stylesheet" href="<?= base_url('assets2/css/eaf-revamp.css') ?>?v=5.0">

<header id="page-topbar">
    <div class="navbar-header">
        <!-- Left Side: Hamburger & Member Profile Info (KVV Benchmark) -->
        <div class="d-flex align-items-center gap-3">
            <button type="button" class="btn btn-sm px-2 font-size-22 header-item waves-effect vertical-menu-btn" id="vertical-menu-btn">
                <i class="ri-menu-2-line align-middle" style="font-size:22px; color:#1E293B;"></i>
            </button>

            <!-- KVV Member Profile Header Widget -->
            <div class="kvv-header-user-block">
                <a href="<?= base_url('member/profile'); ?>">
                    <img src="<?= get_member_avatar($headerPhoto, $headerMember['gender'] ?? '', $headerUserCode); ?>" 
                         alt="<?= esc($headerName); ?>" 
                         class="kvv-header-avatar"
                         onerror="this.onerror=null; this.src='<?= base_url('images/empty.png'); ?>';">
                </a>
                <div class="kvv-header-user-info">
                    <div class="kvv-header-name">
                        <a href="<?= base_url('member/profile'); ?>" style="color:inherit;text-decoration:none;">
                            <?= esc($headerName); ?> <span class="kvv-name-divider">|</span> <?= esc($headerChapterName); ?>
                        </a>
                    </div>
                    <div class="kvv-header-sub">
                        <span><?= esc($headerIndustry); ?> • <?= esc($headerBusiness); ?></span>
                        <span class="kvv-role-badge"><i class="ri-vip-crown-fill me-1 text-warning"></i>Gold Member</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Notifications & Settings Dropdown -->
        <div class="d-flex align-items-center gap-2">
            <!-- Notifications Dropdown -->
            <div class="dropdown d-inline-block">
                <a href="<?= base_url('member/notifications'); ?>" class="btn header-item noti-icon waves-effect position-relative" style="display:inline-flex;align-items:center;justify-content:center;color:#475569;">
                    <i class="ri-notification-3-line font-size-20"></i>
                    <?php if ($unreadNotificationsCount > 0): ?>
                        <span class="badge rounded-pill bg-danger position-absolute top-0 end-0 mt-3 me-2" style="font-size:10px;">
                            <?= $unreadNotificationsCount; ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>

            <!-- Profile Settings Dropdown -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="dropdown" aria-expanded="false" style="color:#475569;">
                    <i class="ri-settings-3-line font-size-20"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end p-0 border-0 shadow-lg rounded-4 overflow-hidden" style="min-width:260px;">
                    <div class="p-3 text-center text-white" style="background: linear-gradient(135deg, #101B35, #172B50); border-bottom: 2px solid #C6A667;">
                        <img src="<?= get_member_avatar($headerPhoto, $headerMember['gender'] ?? '', $headerUserCode); ?>"
                             class="rounded-circle shadow-sm"
                             alt="Profile Image"
                             width="64" height="64"
                             style="object-fit:cover; object-position:top center; border: 2px solid #C6A667;"
                             onerror="this.onerror=null; this.src='<?= base_url('images/empty.png'); ?>';">
                        <h6 class="mt-2 mb-0 fw-bold" style="color:#ffffff;">
                            <?= esc($headerName); ?>
                        </h6>
                        <small class="text-white-50"><?= esc($headerChapterName); ?> • <?= esc($headerRole); ?></small>
                    </div>
                    <div class="p-2">
                        <a href="<?= base_url('member/profile'); ?>" class="dropdown-item d-flex align-items-center rounded-3 py-2">
                            <i class="ri-user-line fs-5 text-warning me-2"></i>
                            <span>My Profile</span>
                        </a>
                        <a href="<?= base_url('member/profile'); ?>" class="dropdown-item d-flex align-items-center rounded-3 py-2">
                            <i class="ri-lock-password-line fs-5 text-primary me-2"></i>
                            <span>Account Settings</span>
                        </a>
                        <hr class="my-2">
                        <a href="<?= base_url('logout'); ?>" class="dropdown-item d-flex align-items-center rounded-3 py-2 text-danger">
                            <i class="ri-logout-box-r-line fs-5 me-2"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="watermark"></div>
<style>
    .watermark {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("<?= base_url('images/logo5.png'); ?>") no-repeat center;
        background-size: 320px;
        opacity: 0.02;
        z-index: 0;
        pointer-events: none;
    }

    .page-content {
        position: relative;
        z-index: 1;
    }
</style>