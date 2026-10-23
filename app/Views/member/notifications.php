<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Notifications</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* ========================================================= MAIN ROW SPACING ========================================================= */
        .notification-row {
            --bs-gutter-x: 14px;
            --bs-gutter-y: 14px;
        }

        /* ========================================================= NOTIFICATION SECTION ========================================================= */
        .notification-section {
            height: 100%;
            padding: 12px;
            background: #ffffff;
            border: 1px solid #e8edf0;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .04);
        }

        /* ========================================================= SECTION TITLE ========================================================= */
        .section-title {
            display: flex;
            align-items: center;
            gap: 7px;
            margin: 0 0 10px;
            padding: 0 2px 8px;
            border-bottom: 1px solid #edf0f2;
            color: #34414b;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.2;
        }

        .section-title i {
            font-size: 14px;
        }

        /* UNREAD TITLE */
        .unread-title {
            color: #c2291c;
        }

        /* READ TITLE */
        .read-title {
            color: #5f6b74;
        }

        /* ========================================================= NOTIFICATION CONTAINER ========================================================= */
        .notification-container {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        /* ========================================================= NOTIFICATION CARD ========================================================= */
        .notification-card {
            display: flex;
            align-items: center;
            width: 100%;
            min-height: 64px;
            padding: 7px 8px;
            background: #ffffff;
            border: 1px solid #e9eef1;
            border-left: 3px solid transparent;
            border-radius: 8px;
            box-shadow: none;
            transition: transform .2s ease, background-color .2s ease, border-color .2s ease, box-shadow .2s ease;
        }

        /* CARD HOVER */
        .notification-card:hover {
            transform: translateX(2px);
            background: #fafcfd;
            border-color: #dfe6ea;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .05);
        }

        /* ========================================================= UNREAD CARD ========================================================= */
        .notification-card.unread {
            background: #fffafa;
            border-left: 3px solid #fac532;
        }

        .notification-card.unread:hover {
            background: #fff7f7;
            border-left-color: #fac532;
        }

        /* ========================================================= DATE SECTION ========================================================= */
        .notif-date {
            width: 58px;
            min-width: 58px;
            margin-right: 10px;
            padding-right: 9px;
            text-align: center;
            border-right: 1px solid #e8edef;
        }

        /* DAY */
        .notif-date .day {
            display: block;
            color: #29343d;
            font-size: 21px;
            font-weight: 700;
            line-height: 1;
        }

        /* MONTH */
        .notif-date .month {
            display: block;
            margin-top: 4px;
            color: #7f8b94;
            font-size: 10px;
            font-weight: 600;
            line-height: 1;
            text-transform: uppercase;
        }

        /* YEAR / SMALL TEXT */
        .notif-date small {
            display: block;
            margin-top: 3px;
            color: #a8b0b6;
            font-size: 9px;
            line-height: 1;
        }

        /* ========================================================= NOTIFICATION CONTENT ========================================================= */
        .notif-content {
            flex: 1;
            min-width: 0;
        }

        /* ========================================================= NOTIFICATION MESSAGE ========================================================= */
        .notif-message {
            margin: 0;
            color: #3c474f;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ========================================================= STATUS BADGE ========================================================= */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 4px;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            line-height: 1.3;
        }

        /* UNREAD BADGE */
        .status-badge.unread {
            background: rgba(194, 41, 28, .09);
            color: #c2291c;
        }

        /* READ BADGE */
        .status-badge.read {
            background: #f0f2f4;
            color: #68737c;
        }

        /* ========================================================= ACTION AREA ========================================================= */
        .notif-action {
            flex: 0 0 auto;
            margin-left: 8px;
        }

        /* ========================================================= DELETE BUTTON ========================================================= */
        .btn-delete {
            width: 29px;
            height: 29px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 0;
            border-radius: 7px;
            background: #fff1f1;
            color: #e74c3c;
            font-size: 11px;
            cursor: pointer;
            transition: background .2s ease, color .2s ease, transform .2s ease;
        }

        /* DELETE HOVER */
        .btn-delete:hover {
            background: #e74c3c;
            color: #ffffff;
            transform: scale(1.04);
        }

        /* ========================================================= READ SECTION ========================================================= */
        .read-section .notification-card {
            opacity: .92;
            border-left-color: #dfe4e7;
        }

        .read-section .notification-card:hover {
            opacity: 1;
            border-left-color: #9ca8b0;
        }

        /* ========================================================= SMALL EMPTY MESSAGE ========================================================= */
        .empty-mini {
            padding: 20px 10px;
            border: 1px dashed #dfe5e8;
            border-radius: 7px;
            color: #98a2a9;
            font-size: 12px;
            text-align: center;
        }

        /* ========================================================= PAGINATION ========================================================= */
        .notification-section nav {
            margin-top: 8px !important;
        }

        .notification-section .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2px;
            margin: 0;
        }

        /* PAGINATION ITEM */
        .notification-section .page-item {
            margin: 0;
        }

        /* PAGINATION LINK */
        .notification-section .page-link {
            min-width: 27px;
            height: 27px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2px 7px;
            border: 1px solid #e1e6e9;
            border-radius: 5px !important;
            background: #ffffff;
            color: #68737c;
            font-size: 10px;
            font-weight: 600;
            line-height: 1;
            box-shadow: none;
            transition: background .2s ease, color .2s ease, border-color .2s ease;
        }

        /* PAGE HOVER */
        .notification-section .page-link:hover {
            background: #f2f6f8;
            border-color: #cfd8de;
            color: #0a3d61;
        }

        /* ACTIVE */
        .notification-section .page-item.active .page-link {
            background: #0a3d61;
            border-color: #0a3d61;
            color: #ffffff;
        }

        /* DISABLED */
        .notification-section .page-item.disabled .page-link {
            background: #f7f8f9;
            border-color: #e8ecee;
            color: #b3bbc0;
            pointer-events: none;
        }

        /* ========================================================= GLOBAL EMPTY STATE ========================================================= */
        .empty-state {
            padding: 35px 15px;
            color: #9aa4ab;
            text-align: center;
        }

        .empty-state i {
            display: block;
            margin-bottom: 8px;
            font-size: 32px;
        }

        .empty-state p {
            margin: 0;
            font-size: 12px;
        }

        /* ========================================================= BOOTSTRAP ROW GAP OVERRIDE ========================================================= */
        .row.g-4.align-items-stretch {
            --bs-gutter-x: 14px;
            --bs-gutter-y: 14px;
        }

        /* ========================================================= TABLET ========================================================= */
        @media (max-width: 991px) {
            .notification-section {
                padding: 10px;
            }

            .notification-container {
                gap: 5px;
            }

            .section-title {
                margin-bottom: 8px;
            }

            .notification-card {
                min-height: 61px;
            }
        }

        /* ========================================================= MOBILE ========================================================= */
        @media (max-width: 575px) {
            .notification-section {
                padding: 9px;
                border-radius: 8px;
            }

            /* TITLE */
            .section-title {
                gap: 6px;
                margin-bottom: 8px;
                padding-bottom: 7px;
                font-size: 14px;
            }

            .section-title i {
                font-size: 13px;
            }

            /* CARD */
            .notification-card {
                min-height: 57px;
                padding: 6px;
                border-radius: 7px;
            }

            /* DATE */
            .notif-date {
                width: 48px;
                min-width: 48px;
                margin-right: 8px;
                padding-right: 7px;
            }

            .notif-date .day {
                font-size: 18px;
            }

            .notif-date .month {
                font-size: 9px;
            }

            .notif-date small {
                font-size: 8px;
            }

            /* MESSAGE */
            .notif-message {
                font-size: 12px;
                line-height: 1.4;
            }

            /* BADGE */
            .status-badge {
                margin-top: 3px;
                padding: 3px 7px;
                font-size: 9px;
            }

            /* ACTION */
            .notif-action {
                margin-left: 6px;
            }

            /* DELETE */
            .btn-delete {
                width: 26px;
                height: 26px;
                font-size: 10px;
            }

            /* EMPTY */
            .empty-mini {
                padding: 16px 8px;
                font-size: 11px;
            }

            /* PAGINATION */
            .notification-section .page-link {
                min-width: 25px;
                height: 25px;
                padding: 2px 6px;
                font-size: 9px;
            }

            /* GLOBAL EMPTY */
            .empty-state {
                padding: 30px 10px;
            }

            .empty-state i {
                font-size: 28px;
            }

            .empty-state p {
                font-size: 11px;
            }
        }

        /* ========================================================= SMALL MOBILE ========================================================= */
        @media (max-width: 380px) {
            .notification-section {
                padding: 8px;
            }

            .section-title {
                font-size: 13px;
            }

            .notification-card {
                min-height: 54px;
                padding: 5px;
            }

            .notif-date {
                width: 43px;
                min-width: 43px;
                margin-right: 7px;
                padding-right: 6px;
            }

            .notif-date .day {
                font-size: 17px;
            }

            .notif-date .month {
                font-size: 8px;
            }

            .notif-message {
                font-size: 11px;
            }

            .status-badge {
                font-size: 8px;
            }

            .btn-delete {
                width: 24px;
                height: 24px;
            }
        }
    </style>
</head>

<body data-sidebar="colored">

    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php echo view('include/member/header.php') ?>
        <?php echo view('include/member/sidebar.php') ?>
        <div class="main-content">
            <div class="page-content">

                <h4 class="title pt-2 mb-4">Notifications</h4>

                <div class="notification-wrapper">

                    <div class="row g-4 align-items-stretch">

                        <!-- ================= UNREAD ================= -->
                        <div class="col-lg-6 col-md-12">

                            <div class="notification-section">

                                <h5 class="section-title unread-title">
                                    <i class="fa fa-bell"></i> Unread Notifications
                                </h5>

                                <div class="notification-container">
                                    <?php if (!empty($unread_notifications)): ?>
                                        <?php foreach ($unread_notifications as $row): ?>
                                            <?= view('include/member/card/notification_card', ['row' => $row]); ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="empty-mini">No unread notifications</div>
                                    <?php endif; ?>
                                    <?php if ($unreadTotalPages > 1): ?>
                                        <nav class="mt-3">
                                            <ul class="pagination pagination-sm justify-content-center">

                                                <li class="page-item <?= ($unreadPage <= 1) ? 'disabled' : '' ?>">
                                                    <a class="page-link"
                                                        href="?unread_page=<?= $unreadPage - 1 ?>&read_page=<?= $readPage ?>">
                                                        Prev
                                                    </a>
                                                </li>

                                                <?php for ($i = 1; $i <= $unreadTotalPages; $i++): ?>
                                                    <li class="page-item <?= ($i == $unreadPage) ? 'active' : '' ?>">
                                                        <a class="page-link"
                                                            href="?unread_page=<?= $i ?>&read_page=<?= $readPage ?>">
                                                            <?= $i ?>
                                                        </a>
                                                    </li>
                                                <?php endfor; ?>

                                                <li class="page-item <?= ($unreadPage >= $unreadTotalPages) ? 'disabled' : '' ?>">
                                                    <a class="page-link"
                                                        href="?unread_page=<?= $unreadPage + 1 ?>&read_page=<?= $readPage ?>">
                                                        Next
                                                    </a>
                                                </li>

                                            </ul>
                                        </nav>
                                    <?php endif; ?>
                                </div>

                            </div>

                        </div>


                        <!-- ================= READ ================= -->
                        <div class="col-lg-6 col-md-12">
                            <div class="notification-section">
                                <h5 class="section-title read-title">
                                    <i class="fa fa-check-circle"></i> Read Notifications
                                </h5>
                                <div class="notification-container read-section">
                                    <?php if (!empty($read_notifications)): ?>
                                        <?php foreach ($read_notifications as $row): ?>
                                            <?= view('include/member/card/notification_card', ['row' => $row]); ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="empty-mini">No read notifications</div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($readTotalPages > 1): ?>
                                    <nav class="mt-3">
                                        <ul class="pagination pagination-sm justify-content-center">

                                            <li class="page-item <?= ($readPage <= 1) ? 'disabled' : '' ?>">
                                                <a class="page-link"
                                                    href="?read_page=<?= $readPage - 1 ?>&unread_page=<?= $unreadPage ?>">
                                                    Prev
                                                </a>
                                            </li>

                                            <?php for ($i = 1; $i <= $readTotalPages; $i++): ?>
                                                <li class="page-item <?= ($i == $readPage) ? 'active' : '' ?>">
                                                    <a class="page-link"
                                                        href="?read_page=<?= $i ?>&unread_page=<?= $unreadPage ?>">
                                                        <?= $i ?>
                                                    </a>
                                                </li>
                                            <?php endfor; ?>

                                            <li class="page-item <?= ($readPage >= $readTotalPages) ? 'disabled' : '' ?>">
                                                <a class="page-link"
                                                    href="?read_page=<?= $readPage + 1 ?>&unread_page=<?= $unreadPage ?>">
                                                    Next
                                                </a>
                                            </li>

                                        </ul>
                                    </nav>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- GLOBAL EMPTY -->
                    <?php if (empty($unread_notifications) && empty($read_notifications)): ?>
                        <div class="empty-state">
                            <i class="bi bi-bell-slash"></i>
                            <p>No notifications available</p>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
            <!-- End Page-content -->
            <?php echo view('include/member/footer.php') ?>
        </div>
        <!-- end main content-->
    </div>
    <div class="rightbar-overlay"></div>
    <!-- JAVASCRIPT -->
    <script src="<?= base_url(); ?>assets2/libs/jquery/jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/metismenu/metisMenu.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/simplebar/simplebar.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/node-waves/waves.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/apexcharts/apexcharts.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/jsvectormap/maps/world-merc.js"></script>
    <script src="<?= base_url(); ?>assets2/js/pages/dashboard.init.js"></script>
    <script src="<?= base_url(); ?>assets2/js/app.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php echo view('include/member/popup/notification.php'); ?>
</body>

</html>