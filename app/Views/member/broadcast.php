<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>broadcast</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* =========================================================
   THEME
========================================================= */

        :root {
            --theme-blue: #0a3d61;
            --theme-blue-dark: #072f4a;
            --theme-blue-light: #0d527f;

            --theme-gold: #fac532;
            --theme-gold-light: #ffd85f;

            --white: #ffffff;

            --text-dark: #1f2937;
            --text-muted: #7b8794;
            --text-light: #9aa4ae;

            --border: #e8edf1;
            --bg-light: #f7f9fb;

            --danger: #dc3545;
        }


        /* =========================================================
   ANNOUNCEMENT LIST
========================================================= */

        .announcement-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }


        /* =========================================================
   ANNOUNCEMENT ITEM
========================================================= */

        .announcement-item {
            position: relative;

            display: flex;
            align-items: center;

            width: 100%;
            min-height: 105px;

            padding: 12px 14px;

            background: #fff;

            border: 1px solid var(--border);
            border-left: 3px solid var(--theme-blue);

            border-radius: 10px;

            overflow: hidden;

            transition:
                transform .25s ease,
                box-shadow .25s ease,
                border-color .25s ease;
        }

        .announcement-item:hover {
            border-left-color: var(--theme-gold);

            transform: translateX(3px);

            box-shadow: 0 7px 20px rgba(10, 61, 97, .09);
        }


        /* =========================================================
   DATE SECTION
========================================================= */

        .announcement-date {
            flex: 0 0 62px;

            width: 62px;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            gap: 5px;
        }


        /* ICON */

        .announcement-icon {
            width: 32px;
            height: 32px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 8px;

            background: rgba(10, 61, 97, .08);

            color: var(--theme-blue);

            font-size: 13px;

            transition: all .25s ease;
        }

        .announcement-item:hover .announcement-icon {
            background: var(--theme-gold);

            color: var(--theme-blue);

            transform: scale(1.05);
        }


        /* DATE TEXT */

        .announcement-date-text {
            display: flex;
            flex-direction: column;
            align-items: center;

            line-height: 1;
        }

        .announcement-date-text strong {
            color: var(--theme-blue);

            font-size: 14px;
            font-weight: 700;
        }

        .announcement-date-text span {
            margin-top: 3px;

            color: var(--text-light);

            font-size: 9px;
            font-weight: 500;

            text-transform: uppercase;
        }


        /* =========================================================
   IMAGE
========================================================= */

        .announcement-image {
            flex: 0 0 110px;

            width: 110px;
            height: 78px;

            margin: 0 15px;

            overflow: hidden;

            border-radius: 8px;

            background: #f3f5f7;
        }

        .announcement-image img {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;

            transition: transform .35s ease;
        }

        .announcement-item:hover .announcement-image img {
            transform: scale(1.06);
        }


        /* =========================================================
   CONTENT
========================================================= */

        .announcement-content {
            flex: 1;

            min-width: 0;

            padding-right: 8px;
        }


        /* LABEL */

        .announcement-label {
            display: flex;
            align-items: center;

            gap: 10px;

            margin-bottom: 3px;
        }

        .announcement-label span {
            display: inline-flex;
            align-items: center;

            color: var(--theme-gold);

            font-size: 9px;
            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .5px;
        }

        .announcement-label span i {
            margin-right: 4px;
        }

        .announcement-label small {
            display: inline-flex;
            align-items: center;

            color: var(--text-light);

            font-size: 9px;
        }

        .announcement-label small i {
            margin-right: 4px;
        }


        /* =========================================================
   TITLE
========================================================= */

        .announcement-title {
            margin: 0 0 4px;

            color: var(--text-dark);

            font-size: 15px;
            font-weight: 700;

            line-height: 1.35;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;
        }


        /* =========================================================
   MESSAGE
========================================================= */

        .announcement-message {
            max-width: 850px;

            margin: 0;

            color: var(--text-muted);

            font-size: 12px;

            line-height: 1.45;

            display: -webkit-box;

            -webkit-line-clamp: 1;

            -webkit-box-orient: vertical;

            overflow: hidden;
        }


        /* =========================================================
   FOOTER
========================================================= */

        .announcement-footer {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 10px;

            margin-top: 8px;
        }


        /* DATE */

        .announcement-time {
            color: var(--text-light);

            font-size: 10px;

            white-space: nowrap;
        }

        .announcement-time i {
            margin-right: 4px;

            color: var(--theme-blue);
        }


        /* =========================================================
   ACTIONS
========================================================= */

        .announcement-actions {
            display: flex;

            align-items: center;

            gap: 6px;

            margin-left: auto;
        }


        /* COMMON ACTION */

        .announcement-video,
        .announcement-view {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 5px;

            padding: 5px 9px;

            border-radius: 6px;

            font-size: 10px;

            font-weight: 600;

            line-height: 1;

            text-decoration: none !important;

            white-space: nowrap;

            cursor: pointer;

            transition:
                background .2s ease,
                color .2s ease,
                border-color .2s ease,
                transform .2s ease;
        }


        /* VIDEO */

        .announcement-video {
            border: 1px solid #f0d5d5;

            background: #fff7f7;

            color: var(--danger) !important;
        }

        .announcement-video:hover {
            border-color: var(--danger);

            background: var(--danger);

            color: #fff !important;

            transform: translateY(-1px);
        }


        /* VIEW */

        .announcement-view {
            border: 1px solid var(--theme-blue);

            background: var(--theme-blue);

            color: #fff;

            outline: none;
        }

        .announcement-view:hover {
            border-color: var(--theme-gold);

            background: var(--theme-gold);

            color: var(--theme-blue);

            transform: translateY(-1px);
        }

        .announcement-view:focus {
            outline: none;

            box-shadow: none;
        }

        .announcement-view i {
            font-size: 9px;

            transition: transform .2s ease;
        }

        .announcement-view:hover i {
            transform: translateX(3px);
        }

        /* =========================================================
   MODAL - ISOLATED FROM ANNOUNCEMENT HOVER
========================================================= */

        .announcement-modal {
            position: relative;

            width: 100%;

            margin: 0;

            padding: 0;

            border: 0 !important;

            border-radius: 14px !important;

            overflow: hidden;

            background: #ffffff !important;

            color: #1f2937 !important;

            box-shadow: 0 20px 55px rgba(0, 0, 0, .22) !important;

            transform: none !important;

            transition: none !important;
        }


        /* IMPORTANT:
   Prevent announcement-item hover styles affecting modal
*/

        .announcement-item:hover .announcement-modal {
            transform: none !important;

            box-shadow: 0 20px 55px rgba(0, 0, 0, .22) !important;

            border-left: 0 !important;
        }


        /* =========================================================
   MODAL HEADER
========================================================= */

        .announcement-modal .announcement-modal-header {
            position: relative;

            display: flex;

            align-items: center;

            justify-content: space-between;

            width: 100%;

            min-height: 64px;

            margin: 0;

            padding: 12px 16px;

            background: #0a3d61 !important;

            color: #ffffff !important;

            border: 0 !important;

            transform: none !important;

            transition: none !important;
        }


        /* =========================================================
   TITLE AREA
========================================================= */

        .announcement-modal .modal-title-area {
            display: flex;

            align-items: center;

            gap: 10px;

            min-width: 0;

            margin: 0;

            padding: 0;
        }


        /* =========================================================
   MODAL ICON
========================================================= */

        .announcement-modal .modal-badge {
            width: 36px;
            height: 36px;

            min-width: 36px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 8px;

            background: #fac532 !important;

            color: #0a3d61 !important;

            font-size: 14px;

            transform: none !important;

            transition: none !important;
        }


        /* Prevent parent hover changing icon */

        .announcement-item:hover .announcement-modal .modal-badge {
            transform: none !important;

            background: #fac532 !important;

            color: #0a3d61 !important;
        }


        /* =========================================================
   MODAL SMALL TITLE
========================================================= */

        .announcement-modal .modal-title-area small {
            display: block;

            margin: 0 0 2px;

            padding: 0;

            color: rgba(255, 255, 255, .65) !important;

            font-size: 9px;

            font-weight: 600;

            line-height: 1.2;

            text-transform: uppercase;

            letter-spacing: .6px;
        }


        /* =========================================================
   MODAL TITLE
========================================================= */

        .announcement-modal .modal-title-area h5 {
            margin: 0;

            padding: 0;

            max-width: 600px;

            color: #ffffff !important;

            font-size: 16px;

            font-weight: 700;

            line-height: 1.3;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

            transform: none !important;

            transition: none !important;
        }


        /* =========================================================
   CLOSE BUTTON
========================================================= */

        .announcement-modal .modal-close {
            width: 32px;
            height: 32px;

            min-width: 32px;

            display: flex;

            align-items: center;
            justify-content: center;

            margin: 0;

            padding: 0;

            border: 0 !important;

            border-radius: 7px;

            background: rgba(255, 255, 255, .12) !important;

            color: #ffffff !important;

            font-size: 12px;

            cursor: pointer;

            outline: none !important;

            box-shadow: none !important;

            transform: none !important;

            transition:
                background .2s ease,
                color .2s ease;
        }

        .announcement-modal .modal-close:hover {
            background: #fac532 !important;

            color: #0a3d61 !important;

            transform: none !important;
        }


        /* =========================================================
   MODAL BODY
========================================================= */

        .announcement-modal .announcement-modal-body {
            position: relative;

            display: block;

            width: 100%;

            margin: 0;

            padding: 18px;

            background: #ffffff !important;

            color: #4b5563 !important;

            border: 0 !important;

            transform: none !important;

            transition: none !important;
        }


        /* =========================================================
   MODAL IMAGE
========================================================= */

        .announcement-modal .announcement-modal-image {
            position: relative;

            width: 100%;

            margin: 0 0 12px;

            padding: 0;

            overflow: hidden;

            border-radius: 9px;

            background: #f3f5f7;

            transform: none !important;

            transition: none !important;
        }

        .announcement-modal .announcement-modal-image img {
            width: 100%;

            max-height: 330px;

            display: block;

            margin: 0;

            padding: 0;

            object-fit: cover;

            transform: none !important;

            transition: none !important;
        }


        /* VERY IMPORTANT:
   Parent announcement hover should NOT zoom modal image
*/

        .announcement-item:hover .announcement-modal .announcement-modal-image img {
            transform: none !important;
        }


        /* =========================================================
   MODAL DATE
========================================================= */

        .announcement-modal .announcement-modal-date {
            display: inline-flex;

            align-items: center;

            gap: 5px;

            margin: 0 0 12px;

            padding: 5px 9px;

            border-radius: 5px;

            background: rgba(10, 61, 97, .06) !important;

            color: #0a3d61 !important;

            font-size: 10px;

            font-weight: 600;

            transform: none !important;
        }


        /* =========================================================
   MODAL MESSAGE
========================================================= */

        .announcement-modal .announcement-full-message {
            position: relative;

            display: block;

            width: 100%;

            margin: 0;

            padding: 0;

            color: #56616d !important;

            background: transparent !important;

            font-size: 13px;

            font-weight: 400;

            line-height: 1.7;

            white-space: pre-line;

            word-break: break-word;

            opacity: 1 !important;

            visibility: visible !important;

            transform: none !important;

            transition: none !important;
        }


        /* =========================================================
   MODAL VIDEO
========================================================= */

        .announcement-modal .announcement-video-wrapper {
            position: relative;

            width: 100%;

            margin: 15px 0 0;

            padding: 0;

            overflow: hidden;

            border-radius: 9px;

            background: #000;

            transform: none !important;

            transition: none !important;
        }

        .announcement-modal .announcement-video-wrapper video {
            width: 100%;

            max-height: 400px;

            display: block;

            margin: 0;

            padding: 0;

            transform: none !important;
        }


        /* =========================================================
   REMOVE HOVER EFFECTS INSIDE MODAL
========================================================= */

        .announcement-item:hover .announcement-modal * {
            transform: none !important;
        }


        /* =========================================================
   MOBILE
========================================================= */

        @media (max-width: 767px) {

            .announcement-modal .announcement-modal-header {
                min-height: 58px;

                padding: 10px 12px;
            }

            .announcement-modal .modal-badge {
                width: 32px;
                height: 32px;

                min-width: 32px;

                font-size: 12px;
            }

            .announcement-modal .modal-title-area {
                gap: 8px;
            }

            .announcement-modal .modal-title-area h5 {
                max-width: calc(100vw - 120px);

                font-size: 14px;
            }

            .announcement-modal .announcement-modal-body {
                padding: 14px;
            }

            .announcement-modal .announcement-full-message {
                font-size: 12px;

                line-height: 1.6;
            }

            .announcement-modal .announcement-modal-image img {
                max-height: 280px;
            }
        }


        @media (max-width: 575px) {

            .announcement-modal .announcement-modal-header {
                padding: 10px;
            }

            .announcement-modal .modal-title-area h5 {
                max-width: calc(100vw - 105px);

                font-size: 13px;
            }

            .announcement-modal .announcement-modal-body {
                padding: 12px;
            }

            .announcement-modal .announcement-full-message {
                font-size: 11px;

                line-height: 1.6;
            }

            .announcement-modal .announcement-modal-image img {
                max-height: 240px;
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

                <div class="container-fluid py-2">

                    <!-- PAGE TITLE -->
                    <div class="row mb-1">
                        <div class="col-12">
                            <div class="broadcast-header p-4 rounded-4 shadow-sm " style="background-color: #fac532;">
                                <h3 class="fw-bold text-white mb-1">
                                    <i class="fa fa-bullhorn me-2"></i> Broadcast Messages
                                </h3>
                                <p class="mb-0 text-dark">Latest announcements, updates and notifications</p>
                            </div>
                        </div>
                    </div>

                    <div class="announcement-list mt-4">

                        <?php if (!empty($broadcast)) : ?>

                            <?php foreach ($broadcast as $row) : ?>

                                <div class="announcement-item">

                                    <!-- DATE / ICON -->
                                    <div class="announcement-date">

                                        <div class="announcement-icon">
                                            <i class="fa fa-bullhorn"></i>
                                        </div>

                                        <?php if (!empty($row['created_at'])) : ?>
                                            <div class="announcement-date-text">
                                                <strong>
                                                    <?= date('d', strtotime($row['created_at'])) ?>
                                                </strong>
                                                <span>
                                                    <?= date('M Y', strtotime($row['created_at'])) ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                    </div>


                                    <!-- IMAGE -->
                                    <?php if (!empty($row['image'])) : ?>

                                        <div class="announcement-image">
                                            <img src="<?= base_url('uploads/broadcast/' . $row['image']); ?>"
                                                alt="<?= esc($row['title']) ?>">
                                        </div>

                                    <?php endif; ?>


                                    <!-- CONTENT -->
                                    <div class="announcement-content">

                                        <div class="announcement-label">
                                            <span>
                                                <i class="fa fa-bullhorn"></i>
                                                Announcement
                                            </span>

                                            <?php if (!empty($row['video'])) : ?>
                                                <small>
                                                    <i class="fa fa-video-camera"></i>
                                                    Video
                                                </small>
                                            <?php endif; ?>
                                        </div>


                                        <h5 class="announcement-title">
                                            <?= esc($row['title']) ?>
                                        </h5>


                                        <p class="announcement-message">
                                            <?= esc(strip_tags($row['message'])) ?>
                                        </p>


                                        <div class="announcement-footer">

                                            <?php if (!empty($row['created_at'])) : ?>
                                                <span class="announcement-time">
                                                    <i class="fa fa-clock-o"></i>
                                                    <?= date('d M Y', strtotime($row['created_at'])) ?>
                                                </span>
                                            <?php endif; ?>


                                            <div class="announcement-actions">

                                                <?php if (!empty($row['video'])) : ?>

                                                    <a href="<?= base_url('uploads/broadcast/' . $row['video']); ?>"
                                                        target="_blank"
                                                        class="announcement-video">
                                                        <i class="fa fa-play"></i>
                                                        Video
                                                    </a>

                                                <?php endif; ?>


                                                <button type="button"
                                                    class="announcement-view"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewModal<?= $row['broadcast_id']; ?>">

                                                    View Details
                                                    <i class="fa fa-arrow-right"></i>

                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <!-- MODAL -->
                                <div class="modal fade"
                                    id="viewModal<?= esc($row['broadcast_id']) ?>"
                                    tabindex="-1"
                                    aria-labelledby="viewModalLabel<?= esc($row['broadcast_id']) ?>"
                                    aria-hidden="true">

                                    <div class="modal-dialog modal-lg modal-dialog-centered">

                                        <div class="modal-content announcement-modal">

                                            <!-- MODAL HEADER -->
                                            <div class="announcement-modal-header">

                                                <div class="modal-title-area">

                                                    <div class="modal-badge">
                                                        <i class="fa fa-bullhorn"></i>
                                                    </div>

                                                    <div>
                                                        <small>Announcement</small>

                                                        <h5>
                                                            <?= esc($row['title']) ?>
                                                        </h5>
                                                    </div>

                                                </div>

                                                <button type="button"
                                                    class="modal-close"
                                                    data-bs-dismiss="modal">
                                                    <i class="fa fa-times"></i>
                                                </button>

                                            </div>


                                            <!-- MODAL BODY -->
                                            <div class="modal-body announcement-modal-body">

                                                <?php if (!empty($row['image'])) : ?>

                                                    <div class="announcement-modal-image">
                                                        <img src="<?= base_url('uploads/broadcast/' . $row['image']); ?>"
                                                            alt="<?= esc($row['title']) ?>">
                                                    </div>

                                                <?php endif; ?>


                                                <?php if (!empty($row['created_at'])) : ?>

                                                    <div class="announcement-modal-date">
                                                        <i class="fa fa-calendar"></i>
                                                        <?= date('d M Y', strtotime($row['created_at'])) ?>
                                                    </div>

                                                <?php endif; ?>


                                                <div class="announcement-full-message">
                                                    <?= esc(strip_tags($row['message'])) ?>
                                                </div>


                                                <?php if (!empty($row['video'])) : ?>

                                                    <div class="announcement-video-wrapper">

                                                        <video controls>
                                                            <source src="<?= base_url('uploads/broadcast/' . $row['video']); ?>">
                                                        </video>

                                                    </div>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach; ?>


                        <?php else : ?>

                            <!-- EMPTY STATE -->
                            <div class="announcement-empty">

                                <div class="empty-icon">
                                    <i class="fa fa-bullhorn"></i>
                                </div>

                                <h5>No Announcements</h5>

                                <p>
                                    New announcements and updates will appear here.
                                </p>

                            </div>

                        <?php endif; ?>

                    </div>

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
</body>

</html>