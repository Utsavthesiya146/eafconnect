<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Notifications Center | EAF Connect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">

    <style>
        :root {
            --eaf-navy: #0F3B68;
            --eaf-navy-dark: #0A2544;
            --eaf-gold: #C6A667;
            --eaf-gold-light: #FBF8F2;
            --eaf-border: #E2E8F0;
            --eaf-surface: #FFFFFF;
            --eaf-bg-muted: #F8FAFC;
        }

        body {
            background-color: #F8FAFC;
            color: #334155;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        /* Hero Header */
        .notif-hero-card {
            background: linear-gradient(135deg, #0F3B68 0%, #172B50 100%);
            border-radius: 18px;
            padding: 24px 28px;
            color: #ffffff;
            margin-bottom: 24px;
            box-shadow: 0 10px 25px -5px rgba(15, 59, 104, 0.25);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(198, 166, 103, 0.25);
        }

        .notif-hero-card::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(198, 166, 103, 0.22) 0%, rgba(255, 255, 255, 0) 70%);
            pointer-events: none;
        }

        .notif-hero-title {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 6px;
            color: #FFFFFF;
            letter-spacing: -0.02em;
        }

        .notif-hero-sub {
            color: rgba(255, 255, 255, 0.82);
            font-size: 13.5px;
            margin-bottom: 0;
            max-width: 580px;
        }

        /* Stat Pills */
        .notif-stat-pill {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 10px 18px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .notif-stat-num {
            font-size: 20px;
            font-weight: 800;
            line-height: 1;
            color: #FFD700;
        }

        .notif-stat-label {
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.2;
        }

        /* Action Buttons */
        .btn-notif-action {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 10px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-notif-primary {
            background: #C6A667;
            color: #101B35;
            border: none;
            box-shadow: 0 4px 12px rgba(198, 166, 103, 0.35);
        }

        .btn-notif-primary:hover {
            background: #b59556;
            color: #101B35;
            transform: translateY(-1px);
        }

        .btn-notif-outline-light {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .btn-notif-outline-light:hover {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            transform: translateY(-1px);
        }

        /* Section Container */
        .notification-section {
            background: #ffffff;
            border: 1px solid var(--eaf-border);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .section-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 14px;
            margin-bottom: 16px;
            border-bottom: 1px solid #F1F5F9;
        }

        .section-header-title {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-header-title.unread {
            color: #0F3B68;
        }

        .section-header-title.read {
            color: #64748B;
        }

        .section-count-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
        }

        .section-count-badge.unread {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FECACA;
        }

        .section-count-badge.read {
            background: #F1F5F9;
            color: #475569;
            border: 1px solid #E2E8F0;
        }

        /* Container list */
        .notification-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex-grow: 1;
        }

        /* Card Styles */
        .notification-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            cursor: pointer;
        }

        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 59, 104, 0.08);
            border-color: #CBD5E1;
        }

        /* Unread accent */
        .notification-card.unread {
            background: #F8FAFC;
            border-left: 4px solid #C6A667;
            border-color: #E2E8F0 #E2E8F0 #E2E8F0 #C6A667;
        }

        .notification-card.unread::after {
            content: '';
            position: absolute;
            top: 14px;
            right: 14px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #DC2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15);
        }

        .notification-card.is-read {
            background: #FFFFFF;
            opacity: 0.94;
        }

        .notification-card.is-read:hover {
            opacity: 1;
        }

        /* Icon Column */
        .notif-category-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .notification-card:hover .notif-category-icon {
            transform: scale(1.06);
        }

        /* Date Column */
        .notif-date {
            width: 60px;
            min-width: 60px;
            text-align: center;
            padding-right: 12px;
            border-right: 1px solid #E2E8F0;
            flex-shrink: 0;
        }

        .notif-date .day {
            display: block;
            font-size: 20px;
            font-weight: 800;
            color: #0F3B68;
            line-height: 1;
        }

        .notif-date .month {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748B;
            margin-top: 3px;
            line-height: 1;
        }

        .notif-date .year {
            display: block;
            font-size: 9.5px;
            color: #94A3B8;
            margin-top: 2px;
            line-height: 1;
        }

        .notif-date .time {
            display: block;
            font-size: 9px;
            color: #94A3B8;
            margin-top: 4px;
            line-height: 1;
        }

        /* Content Column */
        .notif-content {
            flex-grow: 1;
            min-width: 0;
        }

        .notif-title {
            font-size: 14.5px;
            font-weight: 700;
            color: #1E293B;
            line-height: 1.3;
        }

        .notif-type-tag {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .notif-rel-time {
            font-size: 11.5px;
            color: #64748B;
            white-space: nowrap;
        }

        .notif-message-text {
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-top: 4px;
        }

        /* Action Column */
        .notif-action-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
            margin-left: auto;
        }

        .btn-status-toggle {
            font-size: 11.5px;
            font-weight: 600;
            padding: 5px 11px;
            border-radius: 20px;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.18s ease;
            cursor: pointer;
            background: #F1F5F9;
            color: #475569;
        }

        .btn-status-toggle.unread {
            background: #FEF2F2;
            color: #DC2626;
            border-color: #FECACA;
        }

        .btn-status-toggle.unread:hover {
            background: #DC2626;
            color: #FFFFFF;
        }

        .btn-status-toggle.is-read {
            background: #F1F5F9;
            color: #64748B;
            border-color: #E2E8F0;
        }

        .btn-status-toggle.is-read:hover {
            background: #0F3B68;
            color: #FFFFFF;
        }

        .btn-delete-notif {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid #FEE2E2;
            background: #FEF2F2;
            color: #DC2626;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .btn-delete-notif:hover {
            background: #DC2626;
            color: #FFFFFF;
            border-color: #DC2626;
            transform: scale(1.05);
        }

        /* Empty state */
        .empty-mini {
            padding: 36px 16px;
            text-align: center;
            color: #94A3B8;
            font-size: 13.5px;
            background: #F8FAFC;
            border: 2px dashed #E2E8F0;
            border-radius: 12px;
            margin: auto 0;
        }

        .empty-global {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 50px 24px;
            text-align: center;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
        }

        /* Pagination */
        .pagination-eaf .page-link {
            color: #0F3B68;
            font-size: 12.5px;
            font-weight: 600;
            padding: 6px 12px;
            border-color: #E2E8F0;
            border-radius: 8px !important;
            margin: 0 2px;
        }

        .pagination-eaf .page-item.active .page-link {
            background-color: #0F3B68;
            border-color: #0F3B68;
            color: #FFFFFF;
        }

        .pagination-eaf .page-item.disabled .page-link {
            color: #CBD5E1;
            background-color: #F8FAFC;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .notification-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                padding: 12px;
            }
            .notif-date {
                border-right: none;
                border-bottom: 1px solid #E2E8F0;
                width: 100%;
                text-align: left;
                display: flex;
                align-items: center;
                gap: 8px;
                padding-bottom: 6px;
                padding-right: 0;
            }
            .notif-date .day { font-size: 15px; }
            .notif-date .month, .notif-date .year, .notif-date .time { margin: 0; }
            .notif-action-group {
                width: 100%;
                justify-content: flex-end;
                padding-top: 6px;
                border-top: 1px dashed #F1F5F9;
            }
        }
    </style>
</head>

<body data-sidebar="colored">

    <!-- Begin page -->
    <div id="layout-wrapper">
        <?= view('include/member/header.php') ?>
        <?= view('include/member/sidebar.php') ?>
        
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid px-2 px-md-3">

                    <!-- Executive Hero Banner -->
                    <div class="notif-hero-card">
                        <div class="row align-items-center g-3">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge rounded-pill" style="background: rgba(198, 166, 103, 0.25); color: #FFD700; font-weight: 700; font-size: 11px; padding: 5px 12px; border: 1px solid rgba(198,166,103,0.4);">
                                        <i class="ri-notification-3-fill me-1"></i> Notification Center
                                    </span>
                                </div>
                                <h1 class="notif-hero-title">Stay Connected & Updated</h1>
                                <p class="notif-hero-sub">
                                    Real-time alerts for referrals, 1-to-1 meetings, meeting schedules, MITB notes, and chapter activity.
                                </p>
                            </div>

                            <div class="col-lg-6">
                                <div class="d-flex flex-wrap align-items-center justify-content-lg-end gap-2 gap-sm-3">
                                    <!-- Stats -->
                                    <div class="notif-stat-pill">
                                        <div class="notif-stat-num" id="hero-unread-count"><?= $unreadTotal ?? 0 ?></div>
                                        <div class="notif-stat-label">Unread<br>Alerts</div>
                                    </div>
                                    <div class="notif-stat-pill">
                                        <div class="notif-stat-num text-white"><?= $readTotal ?? 0 ?></div>
                                        <div class="notif-stat-label">Read<br>History</div>
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="d-flex gap-2 w-100 w-sm-auto justify-content-lg-end mt-1 mt-sm-0">
                                        <?php if (!empty($unreadTotal) && $unreadTotal > 0): ?>
                                            <button type="button" class="btn-notif-action btn-notif-primary" onclick="markAllNotificationsRead()">
                                                <i class="ri-check-double-line"></i> Mark All Read
                                            </button>
                                        <?php endif; ?>
                                        <?php if (!empty($readTotal) && $readTotal > 0): ?>
                                            <button type="button" class="btn-notif-action btn-notif-outline-light" onclick="clearAllReadNotifications()">
                                                <i class="ri-delete-bin-line"></i> Clear Read
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Dual Feed -->
                    <div class="notification-wrapper">
                        <div class="row g-4 align-items-stretch">

                            <!-- ================= UNREAD NOTIFICATIONS ================= -->
                            <div class="col-lg-6 col-md-12">
                                <div class="notification-section">
                                    <div class="section-header-bar">
                                        <h5 class="section-header-title unread">
                                            <i class="ri-notification-3-line text-warning fs-5"></i>
                                            <span>Unread Notifications</span>
                                        </h5>
                                        <span class="section-count-badge unread">
                                            <?= $unreadTotal ?? 0 ?> New
                                        </span>
                                    </div>

                                    <div class="notification-container unread-container">
                                        <?php if (!empty($unread_notifications)): ?>
                                            <?php foreach ($unread_notifications as $row): ?>
                                                <?= view('include/member/card/notification_card', ['row' => $row]); ?>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="empty-mini">
                                                <i class="ri-checkbox-circle-line fs-3 d-block mb-1 text-success opacity-75"></i>
                                                <span class="fw-semibold">All caught up!</span>
                                                <p class="small text-muted mb-0 mt-1">No unread notifications at the moment.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($unreadTotalPages) && $unreadTotalPages > 1): ?>
                                        <nav class="mt-4 pt-2 border-top">
                                            <ul class="pagination pagination-sm pagination-eaf justify-content-center mb-0">
                                                <li class="page-item <?= ($unreadPage <= 1) ? 'disabled' : '' ?>">
                                                    <a class="page-link" href="?unread_page=<?= $unreadPage - 1 ?>&read_page=<?= $readPage ?>">
                                                        <i class="ri-arrow-left-s-line"></i> Prev
                                                    </a>
                                                </li>
                                                <?php for ($i = 1; $i <= $unreadTotalPages; $i++): ?>
                                                    <li class="page-item <?= ($i == $unreadPage) ? 'active' : '' ?>">
                                                        <a class="page-link" href="?unread_page=<?= $i ?>&read_page=<?= $readPage ?>">
                                                            <?= $i ?>
                                                        </a>
                                                    </li>
                                                <?php endfor; ?>
                                                <li class="page-item <?= ($unreadPage >= $unreadTotalPages) ? 'disabled' : '' ?>">
                                                    <a class="page-link" href="?unread_page=<?= $unreadPage + 1 ?>&read_page=<?= $readPage ?>">
                                                        Next <i class="ri-arrow-right-s-line"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- ================= READ NOTIFICATIONS ================= -->
                            <div class="col-lg-6 col-md-12">
                                <div class="notification-section">
                                    <div class="section-header-bar">
                                        <h5 class="section-header-title read">
                                            <i class="ri-checkbox-circle-line text-secondary fs-5"></i>
                                            <span>Read History</span>
                                        </h5>
                                        <span class="section-count-badge read">
                                            <?= $readTotal ?? 0 ?> Total
                                        </span>
                                    </div>

                                    <div class="notification-container read-container">
                                        <?php if (!empty($read_notifications)): ?>
                                            <?php foreach ($read_notifications as $row): ?>
                                                <?= view('include/member/card/notification_card', ['row' => $row]); ?>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="empty-mini">
                                                <i class="ri-inbox-line fs-3 d-block mb-1 text-secondary opacity-50"></i>
                                                <span class="fw-semibold">No read history</span>
                                                <p class="small text-muted mb-0 mt-1">Notifications you have reviewed will appear here.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($readTotalPages) && $readTotalPages > 1): ?>
                                        <nav class="mt-4 pt-2 border-top">
                                            <ul class="pagination pagination-sm pagination-eaf justify-content-center mb-0">
                                                <li class="page-item <?= ($readPage <= 1) ? 'disabled' : '' ?>">
                                                    <a class="page-link" href="?read_page=<?= $readPage - 1 ?>&unread_page=<?= $unreadPage ?>">
                                                        <i class="ri-arrow-left-s-line"></i> Prev
                                                    </a>
                                                </li>
                                                <?php for ($i = 1; $i <= $readTotalPages; $i++): ?>
                                                    <li class="page-item <?= ($i == $readPage) ? 'active' : '' ?>">
                                                        <a class="page-link" href="?read_page=<?= $i ?>&unread_page=<?= $unreadPage ?>">
                                                            <?= $i ?>
                                                        </a>
                                                    </li>
                                                <?php endfor; ?>
                                                <li class="page-item <?= ($readPage >= $readTotalPages) ? 'disabled' : '' ?>">
                                                    <a class="page-link" href="?read_page=<?= $readPage + 1 ?>&unread_page=<?= $unreadPage ?>">
                                                        Next <i class="ri-arrow-right-s-line"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- GLOBAL EMPTY STATE -->
                        <?php if (empty($unread_notifications) && empty($read_notifications)): ?>
                            <div class="empty-global mt-4">
                                <div style="width:64px; height:64px; border-radius:50%; background:#F1F5F9; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; color:#64748B; font-size:28px;">
                                    <i class="ri-notification-off-line"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">No Notifications Available</h5>
                                <p class="text-muted small mb-0 max-w-sm mx-auto">
                                    You're completely up to date! When members schedule 1-to-1s, give referrals, or chapter meetings are announced, they will be listed here.
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
            <!-- End Page-content -->
            <?= view('include/member/footer.php') ?>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="<?= base_url(); ?>assets2/libs/jquery/jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/metismenu/metisMenu.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/simplebar/simplebar.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/node-waves/waves.min.js"></script>
    <script src="<?= base_url(); ?>assets2/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?= view('include/member/popup/notification.php'); ?>
</body>

</html>