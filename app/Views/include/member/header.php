<?php
$headerUserCode = session()->get('user_code');
$headerMember = session()->get('cached_header_member');
$headerChapter = session()->get('cached_header_chapter');

if (empty($headerMember) || empty($headerChapter)) {
    try {
        $db = \Config\Database::connect();
        if (empty($headerMember) && $headerUserCode) {
            $headerMember = $db->table('users')->where('user_code', $headerUserCode)->get()->getRowArray() ?: [];
            session()->set('cached_header_member', $headerMember);
        }
        if (empty($headerChapter) && $headerUserCode) {
            $headerChapter = $db->table('chapter_members cm')
                ->select('c.chapter_name')
                ->join('chapters c', 'c.chapter_code = cm.chapter_code', 'left')
                ->where('cm.user_code', $headerUserCode)
                ->where('cm.members_status', '1')
                ->get()->getRowArray() ?: [];
            session()->set('cached_header_chapter', $headerChapter);
        }
    } catch (\Throwable $e) {}
}

$headerName = !empty($headerMember['name']) ? $headerMember['name'] : (session()->get('name') ?: 'EAF Member');
$headerPhoto = !empty($headerMember['profile_photo']) ? $headerMember['profile_photo'] : (session()->get('profile_photo') ?: 'empty.png');
$headerChapterName = !empty($headerChapter['chapter_name']) ? $headerChapter['chapter_name'] : (session()->get('chapter_name') ?: 'Chapter 1');
$headerBusiness = !empty($headerMember['business_name']) ? $headerMember['business_name'] : (!empty($headerMember['company_name']) ? $headerMember['company_name'] : 'EAF Connect');
$headerIndustry = !empty($headerMember['industry']) ? $headerMember['industry'] : 'Business Networking';
$headerRole = !empty($headerMember['member_role']) ? $headerMember['member_role'] : (session()->get('member_role') ?: 'Member');

$unreadNotificationsCount = 0;
$headerNotifications = [];
if ($headerUserCode) {
    try {
        $db = \Config\Database::connect();
        $unreadNotificationsCount = $db->table('notifications')
            ->where('user_code', $headerUserCode)
            ->where('status', 'unread')
            ->countAllResults();

        $headerNotifications = $db->table('notifications')
            ->where('user_code', $headerUserCode)
            ->orderBy('status', 'ASC') // 'unread' first
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()->getResult();
    } catch (\Throwable $e) {}
}
?>

<!-- EAF Connect: Master Revamp Fonts & Stylesheet -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
<link rel="stylesheet" href="<?= base_url('assets2/css/eaf-design-system.css') ?>?v=7.0">
<link rel="stylesheet" href="<?= base_url('assets2/css/eaf-revamp.css') ?>?v=10.0">

<header id="page-topbar">
    <div class="navbar-header">
        <!-- Left Side: Hamburger & Member Profile Info -->
        <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
            <!-- Sidebar toggle button (Mobile & Tablet: prominent & responsive, Desktop: hidden by theme) -->
            <button type="button" class="btn btn-sm px-2 font-size-20 header-item waves-effect vertical-menu-btn d-lg-none" 
                    id="vertical-menu-btn" 
                    aria-label="Toggle Navigation Menu" 
                    onclick="document.body.classList.toggle('sidebar-enable');"
                    style="display:inline-flex !important; align-items:center; justify-content:center; width:38px; height:38px; min-width:38px; border-radius:10px; background:#F8FAFC; color:#1E293B; border:1px solid #E2E8F0; margin-right:4px; flex-shrink:0; cursor:pointer;">
                <i class="ri-menu-2-line align-middle" style="font-size:22px; color:#1E293B;"></i>
            </button>

            <!-- Member Profile Header Widget -->
            <div class="kvv-header-user-block">
                <a href="<?= base_url('member/profile'); ?>" class="position-relative d-inline-block">
                    <img src="<?= get_member_avatar($headerPhoto, $headerMember['gender'] ?? '', $headerUserCode); ?>" 
                         alt="<?= esc($headerName); ?>" 
                         class="kvv-header-avatar"
                         onerror="this.onerror=null; this.src='<?= base_url('images/empty.png'); ?>';">
                    <span class="kvv-header-status-dot" title="Online Member"></span>
                </a>
                <div class="kvv-header-user-info">
                    <div class="kvv-header-name">
                        <a href="<?= base_url('member/profile'); ?>" class="kvv-name-link" title="<?= esc($headerName); ?>">
                            <?= esc($headerName); ?>
                        </a>
                        <span class="kvv-header-chapter-pill" title="<?= esc($headerChapterName); ?>">
                            <?= esc($headerChapterName); ?>
                        </span>
                    </div>
                    <!-- Desktop / Tablet Details -->
                    <div class="kvv-header-sub d-none d-md-flex">
                        <span class="kvv-sub-biz" title="<?= esc($headerIndustry); ?> • <?= esc($headerBusiness); ?>">
                            <?= esc($headerIndustry); ?> • <?= esc($headerBusiness); ?>
                        </span>
                        <span class="kvv-role-badge">
                            <i class="ri-vip-crown-fill me-1 text-warning"></i><?= esc($headerRole ?: 'Member'); ?>
                        </span>
                    </div>
                    <!-- Mobile Compact Category (shown only on small mobile) -->
                    <div class="kvv-header-sub-mobile d-flex d-md-none">
                        <span class="kvv-mobile-biz"><?= esc($headerBusiness ?: $headerRole); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Notifications & Settings Dropdown -->
        <div class="d-flex align-items-center gap-1 gap-sm-2 flex-shrink-0">
            <!-- Notifications Dropdown -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect position-relative kvv-header-icon-btn" 
                        id="page-header-notifications-dropdown" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false" 
                        aria-label="Notifications" 
                        title="Notifications">
                    <i class="ri-notification-3-line font-size-20"></i>
                    <?php if ($unreadNotificationsCount > 0): ?>
                        <span class="badge rounded-pill bg-danger position-absolute kvv-noti-badge" id="header-noti-badge">
                            <?= $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount; ?>
                        </span>
                    <?php endif; ?>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0 shadow-lg rounded-4 overflow-hidden" 
                     aria-labelledby="page-header-notifications-dropdown"
                     style="min-width:320px; max-width:360px; z-index:1060;">
                    
                    <!-- Header -->
                    <div class="px-3 py-2 text-white d-flex align-items-center justify-content-between" 
                         style="background: linear-gradient(135deg, #101B35, #172B50); border-bottom: 2px solid #C6A667;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-notification-3-fill text-warning"></i>
                            <h6 class="mb-0 fw-bold text-white fs-6">Notifications</h6>
                        </div>
                        <?php if ($unreadNotificationsCount > 0): ?>
                            <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size:11px;">
                                <?= $unreadNotificationsCount ?> New
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Items List -->
                    <div class="p-2" style="max-height: 320px; overflow-y: auto;">
                        <?php if (!empty($headerNotifications)): ?>
                            <?php foreach ($headerNotifications as $hNotif): ?>
                                <?php 
                                    $hMeta = get_notification_icon($hNotif->title ?? '', $hNotif->message ?? '');
                                    $hTimeAgo = notification_time_ago($hNotif->created_at);
                                    $isHUnread = ($hNotif->status === 'unread');
                                ?>
                                <a href="javascript:void(0);" 
                                   onclick="viewNotificationDetails('<?= esc($hNotif->n_code) ?>')" 
                                   class="dropdown-item d-flex align-items-start gap-2 p-2 rounded-3 mb-1 <?= $isHUnread ? 'bg-light' : '' ?>"
                                   style="white-space: normal; text-decoration: none; border-left: 3px solid <?= $isHUnread ? '#C6A667' : 'transparent' ?>;">
                                    <div class="flex-shrink-0" style="width:34px; height:34px; border-radius:8px; background:<?= $hMeta['bg'] ?>; color:<?= $hMeta['color'] ?>; display:flex; align-items:center; justify-content:center; font-size:16px;">
                                        <i class="<?= esc($hMeta['icon']) ?>"></i>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                            <h6 class="mb-0 fs-7 fw-semibold text-dark text-truncate" style="font-size:13px;">
                                                <?= esc($hNotif->title) ?>
                                            </h6>
                                            <small class="text-muted text-nowrap" style="font-size:10px;">
                                                <?= esc($hTimeAgo) ?>
                                            </small>
                                        </div>
                                        <p class="mb-0 text-muted small text-truncate" style="font-size:11.5px; max-width:230px;">
                                            <?= esc(strip_tags($hNotif->message)) ?>
                                        </p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted">
                                <i class="ri-notification-off-line fs-2 d-block mb-1 text-secondary opacity-50"></i>
                                <span class="small">No notifications yet</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer -->
                    <div class="p-2 border-top bg-light text-center">
                        <a href="<?= base_url('member/notifications'); ?>" class="btn btn-sm text-white w-100 fw-bold rounded-pill" style="background:#0F3B68; font-size:12px;">
                            View All Notifications <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Settings Dropdown -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect kvv-header-icon-btn" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account Menu" title="Settings">
                    <i class="ri-settings-3-line font-size-20"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end p-0 border-0 shadow-lg rounded-4 overflow-hidden" style="min-width:260px; z-index: 1060;">
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

    /* Mobile Menu Drawer & Toggle System */
    @media (max-width: 991px) {
        #page-topbar #vertical-menu-btn,
        .navbar-header #vertical-menu-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 38px !important;
            height: 38px !important;
            min-width: 38px !important;
            border-radius: 10px !important;
            background: #F8FAFC !important;
            color: #1E293B !important;
            border: 1px solid #E2E8F0 !important;
            padding: 0 !important;
            margin-right: 6px !important;
            flex-shrink: 0 !important;
            cursor: pointer !important;
            visibility: visible !important;
            opacity: 1 !important;
            z-index: 1005 !important;
        }

        .vertical-menu {
            position: fixed !important;
            top: 0 !important;
            bottom: 0 !important;
            left: 0 !important;
            width: 285px !important;
            max-width: 82vw !important;
            height: 100vh !important;
            height: 100dvh !important;
            background: #FFFFFF !important;
            z-index: 1060 !important;
            transform: translateX(-100%) !important;
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1) !important;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.18) !important;
            overflow-y: auto !important;
            -webkit-overflow-scrolling: touch !important;
            display: block !important;
        }

        body.sidebar-enable .vertical-menu {
            transform: translateX(0) !important;
            pointer-events: auto !important;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 1055;
        }

        body.sidebar-enable .sidebar-backdrop {
            display: block !important;
        }
    }

    @media (min-width: 992px) {
        body:not(.vertical-collpsed) #page-topbar #vertical-menu-btn {
            display: none !important;
        }
    }
</style>

<?php if (!defined('EAF_NOTIFICATION_POPUP_LOADED')): ?>
    <?php define('EAF_NOTIFICATION_POPUP_LOADED', true); ?>
    <?= view('include/member/popup/notification.php'); ?>
<?php endif; ?>