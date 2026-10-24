<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>My Chapter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* ===============================
   MEMBER CARD - THEME STYLE
=================================*/

        .member-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
            transition: all .3s ease;
            height: 100%;
            position: relative;
            border-top: 4px solid #114466;
        }

        .member-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 30px rgba(0, 0, 0, .18);
        }

        /* ===== PROFILE IMAGE ===== */
        .member-avatar {
            width: 95px;
            height: 95px;
            border-radius: 14px;
            object-fit: cover;
            margin-bottom: 10px;
            border: 3px solid #f9b309;
        }

        /* ===== NAME ===== */
        .member-name {
            font-weight: 700;
            color: #114466;
            margin-bottom: 6px;
        }

        /* ===== CHAPTER BADGE ===== */
        .chapter-badge {
            background: rgba(249, 179, 9, .15);
            color: #114466;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            font-weight: 600;
        }

        /* ===== ROLE BOX ===== */
        .member-role-box {
            background: #fff7e0;
            border-left: 4px solid #f9b309;
            padding: 5px;
            border-radius: 10px;
            margin: 6px 0;
            font-size: 13px;
            font-weight: 500;
        }

        /* ===== STATS ===== */
        .member-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 12px;
        }

        .stat-box {
            background: #fff;
            border: 1px solid #f1f1f1;
            padding: 8px;
            border-radius: 10px;
            font-size: 12px;
            transition: .3s;
        }

        .stat-box:hover {
            background: #fff7e0;
            border-color: #f9b309;
        }

        .stat-box span {
            display: block;
            color: #777;
        }

        .stat-box strong {
            font-size: 15px;
            color: #114466;
        }

        /* ===== CONTACT ===== */
        .member-contact {
            color: #114466;
            font-weight: 600;
            font-size: 14px;
            margin-top: 10px;
        }

        /* ===== ACTION BUTTONS ===== */
        .member-actions {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .member-actions .btn-success {
            background: #f9b309;
            border: none;
            color: #000;
        }

        .member-actions .btn-warning {
            background: #114466;
            border: none;
            color: #fff;
        }

        .member-actions .btn-danger {
            background: #222;
            border: none;
        }

        /* ===== HOVER GLOW ===== */
        .member-card:hover .member-name {
            color: #f9b309;
        }
    </style>
</head>

<body data-sidebar="colored">
    <div id="layout-wrapper">
        <?php echo view('include/member/header.php') ?>
        <?php echo view('include/member/sidebar.php') ?>
        <div class="main-content">
            <div class="page-content">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="mb-0">My Chapter Details</h4>

                    <a href="<?= base_url('member/dashboard') ?>"
                        class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 px-3 py-2">
                        <i class="fa fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>
                <hr style="height: 1px; border: 0; background: #114466; margin: 8px 0;">
                <div class="row g-3 mb-3">

                    <div class="col-md-3">
                        <?php if (!empty($chapters)) : ?>

                            <?php foreach ($chapters as $row) : ?>

                                <?php
                                // Chapter Status
                                if ($row['chapter_status'] == '1') {
                                    $statusClass = 'status-active';
                                    $statusText  = 'Active';
                                    $statusIcon  = 'bi-check-circle-fill';
                                } elseif ($row['chapter_status'] == '2') {
                                    $statusClass = 'status-hold';
                                    $statusText  = 'Hold';
                                    $statusIcon  = 'bi-clock-fill';
                                } else {
                                    $statusClass = 'status-blocked';
                                    $statusText  = 'Blocked';
                                    $statusIcon  = 'bi-x-circle-fill';
                                }

                                // Leader
                                $leaderName = !empty($row['leader_name'])
                                    ? esc($row['leader_name'])
                                    : 'Not Assigned';

                                // Leader image
                                $leaderPhoto = !empty($row['leader_photo'])
                                    ? trim($row['leader_photo'])
                                    : 'empty.png';

                                if ($leaderPhoto === '' || $leaderPhoto === 'empty.png') {
                                    $leaderPhoto = 'empty.png';
                                }

                                $leaderImage = get_member_avatar($leaderPhoto, '', $row['leader_code'] ?? '');
                                ?>

                                <div class="chapter-card-pro">

                                    <!-- TOP ACCENT -->
                                    <div class="chapter-accent"></div>

                                    <!-- HEADER -->
                                    <div class="chapter-header">

                                        <div class="chapter-title-area">

                                            <div class="chapter-icon">
                                                <i class="bi bi-diagram-3-fill"></i>
                                            </div>

                                            <div class="chapter-title">
                                                <h4>
                                                    <?= esc($row['chapter_name']) ?>
                                                </h4>

                                                <div class="chapter-location">
                                                    <i class="bi bi-geo-alt-fill"></i>
                                                    <span><?= esc($row['location']) ?></span>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- STATUS -->
                                        <button
                                            type="button"
                                            onclick="edit_chapter_status('<?= esc($row['chapter_code']) ?>');"
                                            class="chapter-status <?= $statusClass ?>"
                                            title="Change chapter status">

                                            <i class="bi <?= $statusIcon ?>"></i>
                                            <span><?= $statusText ?></span>

                                        </button>

                                    </div>


                                    <!-- DIVIDER -->
                                    <div class="chapter-divider"></div>


                                    <!-- LEADER SECTION -->
                                    <div class="leader-section">

                                        <div class="section-heading">
                                            <span>CHAPTER LEADER</span>
                                            <i class="bi bi-person-badge-fill"></i>
                                        </div>

                                        <div class="leader-box">

                                            <!-- AVATAR -->
                                            <div class="leader-avatar">

                                                <img
                                                    src="<?= esc($leaderImage) ?>"
                                                    alt="<?= !empty($row['leader_name']) ? esc($row['leader_name']) : 'Chapter Leader' ?>"
                                                    class="leader-img"
                                                    onerror="this.onerror=null;this.src='<?= base_url('uploads/profile/empty.png') ?>';">

                                                <?php if (!empty($row['leader_name'])) : ?>
                                                    <span class="leader-online"></span>
                                                <?php endif; ?>

                                            </div>

                                            <!-- DETAILS -->
                                            <div class="leader-details">

                                                <strong>
                                                    <?= $leaderName ?>
                                                </strong>

                                                <?php if (!empty($row['leader_name'])) : ?>

                                                    <span class="leader-role">
                                                        <i class="bi bi-shield-check"></i>
                                                        Chapter Leader
                                                    </span>

                                                <?php else : ?>

                                                    <span class="leader-role not-assigned">
                                                        <i class="bi bi-info-circle"></i>
                                                        Leader not assigned
                                                    </span>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    </div>



                                </div>

                            <?php endforeach; ?>

                        <?php else : ?>

                            <div class="chapter-empty">
                                <div class="empty-icon">
                                    <i class="bi bi-diagram-3"></i>
                                </div>

                                <h5>No Chapters Found</h5>

                                <p>
                                    There are no chapters available at the moment.
                                </p>
                            </div>

                        <?php endif; ?>
                        <style>
                            /* =========================================================
   CHAPTER CARD
========================================================= */

                            .chapter-card-pro {
                                position: relative;
                                width: 100%;
                                background: #ffffff;
                                border: 1px solid #e8edf1;
                                border-radius: 16px;
                                padding: 0;
                                margin-bottom: 16px;
                                overflow: hidden;

                                box-shadow:
                                    0 3px 12px rgba(13, 71, 105, 0.06);

                                transition:
                                    transform 0.25s ease,
                                    box-shadow 0.25s ease,
                                    border-color 0.25s ease;
                            }

                            .chapter-card-pro:hover {
                                transform: translateY(-4px);

                                border-color: rgba(13, 71, 105, 0.18);

                                box-shadow:
                                    0 10px 28px rgba(13, 71, 105, 0.12);
                            }


                            /* =========================================================
   TOP ACCENT
========================================================= */

                            .chapter-accent {
                                height: 4px;
                                width: 100%;

                                background: linear-gradient(90deg,
                                        #0d4769 0%,
                                        #0d4769 65%,
                                        #0d4769 100%);
                            }


                            /* =========================================================
   HEADER
========================================================= */

                            .chapter-header {
                                display: flex;
                                align-items: flex-start;
                                justify-content: space-between;

                                gap: 10px;

                                padding: 16px 16px 12px;
                            }

                            .chapter-title-area {
                                display: flex;
                                align-items: center;

                                gap: 11px;
                                min-width: 0;
                            }


                            /* =========================================================
   CHAPTER ICON
========================================================= */

                            .chapter-icon {
                                width: 42px;
                                height: 42px;
                                min-width: 42px;

                                display: flex;
                                align-items: center;
                                justify-content: center;

                                border-radius: 11px;

                                background: rgba(13, 71, 105, 0.09);
                                color: #0d4769;

                                font-size: 18px;
                            }

                            .chapter-icon i {
                                line-height: 1;
                            }


                            /* =========================================================
   TITLE
========================================================= */

                            .chapter-title {
                                min-width: 0;
                            }

                            .chapter-title h4 {
                                margin: 0 0 5px;

                                color: #173b4d;

                                font-size: 16px;
                                font-weight: 750;

                                line-height: 1.25;

                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                            }


                            /* =========================================================
   LOCATION
========================================================= */

                            .chapter-location {
                                display: flex;
                                align-items: center;

                                gap: 4px;

                                color: #7b8993;

                                font-size: 11px;
                                font-weight: 500;

                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                            }

                            .chapter-location i {
                                color: #ffbb00;
                                font-size: 12px;
                            }


                            /* =========================================================
   STATUS BUTTON
========================================================= */

                            .chapter-status {
                                flex-shrink: 0;

                                display: inline-flex;
                                align-items: center;

                                gap: 5px;

                                border: 0;
                                outline: none;

                                padding: 5px 9px;

                                border-radius: 30px;

                                font-size: 10px;
                                font-weight: 700;

                                cursor: pointer;

                                transition: all 0.2s ease;
                            }

                            .chapter-status:hover {
                                transform: scale(1.04);
                            }

                            .chapter-status i {
                                font-size: 10px;
                            }


                            /* ACTIVE */

                            .status-active {
                                color: #16804b;
                                background: #eaf8f1;
                            }

                            .status-active:hover {
                                background: #d9f3e6;
                            }


                            /* HOLD */

                            .status-hold {
                                color: #a87500;
                                background: #fff7df;
                            }

                            .status-hold:hover {
                                background: #ffefbd;
                            }


                            /* BLOCKED */

                            .status-blocked {
                                color: #c33d4e;
                                background: #fff0f2;
                            }

                            .status-blocked:hover {
                                background: #ffe1e5;
                            }


                            /* =========================================================
   DIVIDER
========================================================= */

                            .chapter-divider {
                                height: 1px;
                                margin: 0 16px;

                                background: #edf1f3;
                            }


                            /* =========================================================
   LEADER SECTION
========================================================= */

                            .leader-section {
                                padding: 13px 16px 12px;
                            }

                            .section-heading {
                                display: flex;
                                align-items: center;
                                justify-content: space-between;

                                margin-bottom: 9px;
                            }

                            .section-heading span {
                                font-size: 9px;
                                font-weight: 800;

                                letter-spacing: 0.8px;

                                color: #98a5ad;
                            }

                            .section-heading i {
                                color: #c4cdd2;
                                font-size: 13px;
                            }


                            /* =========================================================
   LEADER BOX
========================================================= */

                            .leader-box {
                                display: flex;
                                align-items: center;

                                gap: 10px;

                                padding: 10px;

                                background: #f8fafb;

                                border: 1px solid #edf1f3;

                                border-radius: 11px;

                                transition: all 0.2s ease;
                            }

                            .chapter-card-pro:hover .leader-box {
                                background: #f6f9fa;
                            }


                            /* =========================================================
   LEADER AVATAR
========================================================= */

                            .leader-avatar {
                                position: relative;

                                width: 42px;
                                height: 42px;
                                min-width: 42px;
                            }

                            .leader-img {
                                width: 42px;
                                height: 42px;

                                display: block;

                                border-radius: 50%;

                                object-fit: cover;
                                object-position: top center;

                                border: 2px solid #ffffff;

                                box-shadow:
                                    0 2px 7px rgba(13, 71, 105, 0.14);
                            }


                            /* ONLINE DOT */

                            .leader-online {
                                position: absolute;

                                right: 0;
                                bottom: 1px;

                                width: 10px;
                                height: 10px;

                                border-radius: 50%;

                                background: #24b66f;

                                border: 2px solid #ffffff;
                            }


                            /* =========================================================
   LEADER DETAILS
========================================================= */

                            .leader-details {
                                min-width: 0;

                                display: flex;
                                flex-direction: column;
                            }

                            .leader-details strong {
                                display: block;

                                color: #263f4c;

                                font-size: 12px;
                                font-weight: 700;

                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                            }

                            .leader-role {
                                display: flex;
                                align-items: center;

                                gap: 4px;

                                margin-top: 3px;

                                color: #6f818c;

                                font-size: 9px;
                                font-weight: 500;
                            }

                            .leader-role i {
                                color: #0d4769;
                                font-size: 9px;
                            }

                            .leader-role.not-assigned {
                                color: #a07a15;
                            }

                            .leader-role.not-assigned i {
                                color: #ffbb00;
                            }


                            /* =========================================================
   FOOTER
========================================================= */

                            .chapter-footer {
                                display: flex;
                                align-items: center;
                                justify-content: space-between;

                                padding: 9px 16px;

                                border-top: 1px solid #f0f2f4;

                                background: #fcfdfd;

                                color: #9aa6ad;

                                font-size: 9px;
                                font-weight: 600;
                            }

                            .chapter-footer i {
                                margin-right: 3px;
                            }

                            .chapter-code {
                                padding: 3px 7px;

                                border-radius: 5px;

                                background: #eef4f7;

                                color: #0d4769;

                                font-size: 9px;
                                font-weight: 700;
                            }


                            /* =========================================================
   EMPTY STATE
========================================================= */

                            .chapter-empty {
                                width: 100%;

                                padding: 35px 15px;

                                text-align: center;

                                border: 1px dashed #dce3e7;
                                border-radius: 14px;

                                background: #fafcfd;
                            }

                            .empty-icon {
                                width: 50px;
                                height: 50px;

                                margin: 0 auto 10px;

                                display: flex;
                                align-items: center;
                                justify-content: center;

                                border-radius: 50%;

                                background: #eef4f7;
                                color: #0d4769;

                                font-size: 21px;
                            }

                            .chapter-empty h5 {
                                margin: 0 0 4px;

                                color: #354c59;

                                font-size: 14px;
                                font-weight: 700;
                            }

                            .chapter-empty p {
                                margin: 0;

                                color: #9aa6ad;

                                font-size: 11px;
                            }


                            /* =========================================================
   TABLET
========================================================= */

                            @media (max-width: 991px) {

                                .chapter-header {
                                    padding: 14px;
                                }

                                .leader-section {
                                    padding: 12px 14px;
                                }

                                .chapter-footer {
                                    padding: 8px 14px;
                                }

                            }


                            /* =========================================================
   MOBILE
========================================================= */

                            @media (max-width: 575px) {

                                .chapter-card-pro {
                                    margin-bottom: 12px;
                                    border-radius: 13px;
                                }

                                .chapter-header {
                                    padding: 13px 12px 10px;
                                    gap: 6px;
                                }

                                .chapter-icon {
                                    width: 36px;
                                    height: 36px;
                                    min-width: 36px;

                                    font-size: 15px;
                                    border-radius: 9px;
                                }

                                .chapter-title h4 {
                                    font-size: 13px;
                                }

                                .chapter-location {
                                    font-size: 9px;
                                }

                                .chapter-status {
                                    padding: 4px 7px;
                                    font-size: 8px;
                                }

                                .chapter-status i {
                                    font-size: 8px;
                                }

                                .leader-section {
                                    padding: 11px 12px;
                                }

                                .leader-box {
                                    padding: 8px;
                                }

                                .leader-avatar,
                                .leader-img {
                                    width: 38px;
                                    height: 38px;
                                    min-width: 38px;
                                }

                                .leader-details strong {
                                    font-size: 11px;
                                }

                                .chapter-footer {
                                    padding: 8px 12px;
                                }
                            }
                        </style>
                    </div>

                    <div class="col-md-9">
                        <div class="row g-2">

                            <!-- TOTAL MEMBERS -->
                            <div class="col-xl-4 col-md-6 col-6">
                                <div class="dashboard-stat-card members-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">Total Members</span>
                                        <h3 class="stat-value"><?= esc($totalMembers) ?></h3>
                                        <span class="stat-info">Active members</span>
                                    </div>
                                </div>
                            </div>

                            <!-- MITB -->
                            <div class="col-xl-4 col-md-6 col-6">
                                <div class="dashboard-stat-card mitb-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-indian-rupee-sign"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">Total MITB</span>
                                        <h3 class="stat-value">
                                            ₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($mitb) ?>
                                        </h3>
                                        <span class="stat-info">Business generated</span>
                                    </div>
                                </div>
                            </div>

                            <!-- REFERRALS -->
                            <div class="col-xl-4 col-md-6 col-6">
                                <div class="dashboard-stat-card referral-stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-share-nodes"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">Total Connects</span>
                                        <h3 class="stat-value"><?= esc($connects) ?></h3>
                                        <span class="stat-info">Connections given</span>
                                    </div>
                                </div>
                            </div>

                            <!-- F2F -->
                            <div class="col-xl-4 col-md-6 col-6">
                                <div class="dashboard-stat-card meeting-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-handshake"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">Total F2F</span>
                                        <h3 class="stat-value"><?= esc($meetings) ?></h3>
                                        <span class="stat-info">One-to-one meetings</span>
                                    </div>
                                </div>
                            </div>

                            <!-- VISITORS -->
                            <div class="col-xl-4 col-md-6 col-6">
                                <div class="dashboard-stat-card visitor-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">Total Visitors</span>
                                        <h3 class="stat-value"><?= esc($visitors) ?></h3>
                                        <span class="stat-info">Visitors received</span>
                                    </div>
                                </div>
                            </div>

                            <!-- NOTIFICATIONS -->
                            <!-- <div class="col-xl-4 col-md-6 col-6">
                                <div class="dashboard-stat-card notification-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">Notifications</span>
                                        <h3 class="stat-value"><?= esc($notifications) ?></h3>
                                        <span class="stat-info">Latest updates</span>
                                    </div>
                                </div>
                            </div> -->

                        </div>
                    </div>
                    <style>
                        /* =========================================
   DASHBOARD STAT CARDS
========================================= */

                        .dashboard-stat-card {
                            position: relative;
                            display: flex;
                            align-items: center;
                            gap: 15px;
                            min-height: 90px;
                            padding: 9px 20px;
                            background: #ffffff;
                            border: 1px solid #edf0f4;
                            border-radius: 14px;
                            overflow: hidden;
                            box-shadow: 0 4px 18px rgba(13, 71, 105, 0.07);
                            transition: all 0.3s ease;
                        }

                        /* Decorative background */
                        .dashboard-stat-card::after {
                            content: "";
                            position: absolute;
                            width: 80px;
                            height: 80px;
                            right: -25px;
                            bottom: -30px;
                            border-radius: 50%;
                            opacity: 0.08;
                            transition: all 0.3s ease;
                        }

                        /* Hover */
                        .dashboard-stat-card:hover {
                            transform: translateY(-4px);
                            box-shadow: 0 10px 28px rgba(13, 71, 105, 0.13);
                        }

                        .dashboard-stat-card:hover::after {
                            transform: scale(1.4);
                        }

                        /* =========================================
   ICON
========================================= */

                        .stat-icon {
                            width: 52px;
                            height: 52px;
                            min-width: 52px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            border-radius: 13px;
                            font-size: 21px;
                            position: relative;
                            z-index: 2;
                        }

                        .stat-icon i {
                            line-height: 1;
                        }

                        /* =========================================
   CONTENT
========================================= */

                        .stat-content {
                            min-width: 0;
                            position: relative;
                            z-index: 2;
                        }

                        .stat-label {
                            display: block;
                            font-size: 13px;
                            font-weight: 600;
                            color: #687785;
                            margin-bottom: 4px;
                            white-space: nowrap;
                        }

                        .stat-value {
                            margin: 0;
                            font-size: 18px;
                            line-height: 1.15;
                            font-weight: 800;
                            color: #0d4769;
                            letter-spacing: -0.4px;
                        }

                        .stat-info {
                            display: block;
                            margin-top: 5px;
                            font-size: 11px;
                            color: #98a4ad;
                            white-space: nowrap;
                        }

                        /* =========================================
   MEMBERS
========================================= */

                        .members-card {
                            border-left: 4px solid #0d4769;
                        }

                        .members-card .stat-icon {
                            color: #0d4769;
                            background: rgba(13, 71, 105, 0.10);
                        }

                        .members-card::after {
                            background: #0d4769;
                        }

                        /* =========================================
   MITB
========================================= */

                        .mitb-card {
                            border-left: 4px solid #20a464;
                        }

                        .mitb-card .stat-icon {
                            color: #20a464;
                            background: rgba(32, 164, 100, 0.11);
                        }

                        .mitb-card .stat-value {
                            color: #168650;
                        }

                        .mitb-card::after {
                            background: #20a464;
                        }

                        /* =========================================
   REFERRAL
========================================= */

                        .referral-stat-card,
                        .referral-card {
                            border-left: 4px solid #ffbb00;
                        }

                        .referral-stat-card .stat-icon,
                        .referral-card .stat-icon {
                            color: #d99d00;
                            background: rgba(255, 187, 0, 0.13);
                        }

                        .referral-stat-card .stat-value,
                        .referral-card .stat-value {
                            color: #b27e00;
                        }

                        .referral-stat-card::after,
                        .referral-card::after {
                            background: #ffbb00;
                        }

                        /* =========================================
   F2F
========================================= */

                        .meeting-card {
                            border-left: 4px solid #2187b8;
                        }

                        .meeting-card .stat-icon {
                            color: #2187b8;
                            background: rgba(33, 135, 184, 0.11);
                        }

                        .meeting-card .stat-value {
                            color: #176b95;
                        }

                        .meeting-card::after {
                            background: #2187b8;
                        }

                        /* =========================================
   VISITORS
========================================= */

                        .visitor-card {
                            border-left: 4px solid #7a5af8;
                        }

                        .visitor-card .stat-icon {
                            color: #7050e8;
                            background: rgba(122, 90, 248, 0.11);
                        }

                        .visitor-card .stat-value {
                            color: #6041d2;
                        }

                        .visitor-card::after {
                            background: #7a5af8;
                        }

                        /* =========================================
   NOTIFICATIONS
========================================= */

                        .notification-card {
                            border-left: 4px solid #e85d75;
                        }

                        .notification-card .stat-icon {
                            color: #df4e67;
                            background: rgba(232, 93, 117, 0.11);
                        }

                        .notification-card .stat-value {
                            color: #c83e56;
                        }

                        .notification-card::after {
                            background: #e85d75;
                        }

                        /* =========================================
   MOBILE
========================================= */

                        @media (max-width: 767px) {

                            .dashboard-stat-card {
                                min-height: 100px;
                                padding: 14px 12px;
                                gap: 10px;
                                border-radius: 12px;
                            }

                            .stat-icon {
                                width: 42px;
                                height: 42px;
                                min-width: 42px;
                                border-radius: 10px;
                                font-size: 17px;
                            }

                            .stat-label {
                                font-size: 11px;
                                margin-bottom: 3px;
                            }

                            .stat-value {
                                font-size: 19px;
                            }

                            .stat-info {
                                font-size: 9px;
                                margin-top: 3px;
                            }
                        }

                        @media (max-width: 420px) {

                            .dashboard-stat-card {
                                padding: 12px 9px;
                                gap: 8px;
                            }

                            .stat-icon {
                                width: 36px;
                                height: 36px;
                                min-width: 36px;
                                font-size: 14px;
                            }

                            .stat-label {
                                font-size: 10px;
                            }

                            .stat-value {
                                font-size: 17px;
                            }

                            .stat-info {
                                display: none;
                            }
                        }
                    </style>
                    <div class="row p-2">

                        <style>
                            /* ================================
   RESET BUTTON
================================ */
                            .reset-btn {
                                height: 42px;
                                border: 1.5px solid #0d4769;
                                border-radius: 8px;
                                background: #ffffff;
                                color: #0d4769;
                                font-size: 14px;
                                font-weight: 700;
                                letter-spacing: .2px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                gap: 6px;
                                transition: all .25s ease;
                                box-shadow: 0 2px 6px rgba(13, 71, 105, 0.08);
                            }

                            /* Icon */
                            .reset-btn i {
                                font-size: 13px;
                                transition: transform .3s ease;
                            }

                            /* Hover */
                            .reset-btn:hover {
                                background: #0d4769;
                                border-color: #0d4769;
                                color: #ffffff;
                                transform: translateY(-1px);
                                box-shadow: 0 5px 14px rgba(13, 71, 105, 0.20);
                            }

                            .reset-btn:hover i {
                                transform: rotate(-45deg);
                            }

                            /* Active */
                            .reset-btn:active {
                                transform: translateY(0);
                                box-shadow: 0 2px 5px rgba(13, 71, 105, 0.15);
                            }

                            /* Focus */
                            .reset-btn:focus {
                                outline: none;
                                box-shadow: 0 0 0 3px rgba(255, 187, 0, 0.25);
                            }

                            /* Mobile */
                            @media (max-width: 576px) {
                                .reset-btn {
                                    height: 40px;
                                    font-size: 13px;
                                }
                            }
                        </style>

                        <!-- ================= CHAPTER MEMBERS HEADER ================= -->
                        <div class="row align-items-center g-1 mb-3">

                            <!-- Title + Actions -->
                            <div class="col-lg-6 col-md-6">
                                <div class="members-heading">

                                    <div class="members-title">
                                        <div class="members-icon">
                                            <i class="bi bi-people-fill"></i>
                                        </div>

                                        <div>
                                            <h4 class="mb-0">Chapter Members</h4>
                                            <span>Manage and view chapter members</span>
                                        </div>
                                    </div>

                                    <div class="members-actions">
                                        <a href="<?= base_url('member/chapter/report/' . $row['chapter_code']) ?>"
                                            class="action-btn report-btn"
                                            title="View Report">
                                            <i class="bi bi-bar-chart-line"></i>
                                            <span>Report</span>
                                        </a>

                                        <a href="<?= base_url('member/chapter/download_pdf/' . $row['chapter_code']) ?>"
                                            class="action-btn pdf-btn"
                                            title="Download PDF">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                            <span>PDF</span>
                                        </a>
                                    </div>

                                </div>
                            </div>

                            <!-- ================= SEARCH / FILTER ================= -->
                            <div class="col-lg-6 col-md-6">
                                <div class="filter-card">

                                    <form id="search-form">
                                        <div class="filter-search">

                                            <div class="search-input">
                                                <i class="bi bi-search"></i>

                                                <input type="text"
                                                    id="search"
                                                    name="search"
                                                    class="form-control"
                                                    placeholder="Search name, phone or email..."
                                                    autocomplete="off">
                                            </div>

                                            <button type="button"
                                                id="reset-btn"
                                                class="reset-btn"
                                                title="Reset Search">
                                                <i class="fas fa-rotate-left"></i>
                                                <span>Reset</span>
                                            </button>

                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>

                        <style>
                            /* =========================================================
   CHAPTER MEMBERS HEADER
========================================================= */

                            .members-heading {
                                min-height: 64px;
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                gap: 15px;
                                padding: 12px 14px;
                                background: #fff;
                                border: 1px solid #e7edf2;
                                border-radius: 12px;
                                box-shadow: 0 3px 12px rgba(15, 61, 92, 0.05);
                            }

                            /* Title */
                            .members-title {
                                display: flex;
                                align-items: center;
                                gap: 11px;
                                min-width: 0;
                            }

                            .members-icon {
                                width: 40px;
                                height: 40px;
                                flex: 0 0 40px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                border-radius: 10px;
                                background: linear-gradient(135deg, #0a3d61, #176b9c);
                                color: #fff;
                                font-size: 18px;
                                box-shadow: 0 4px 10px rgba(10, 61, 97, 0.15);
                            }

                            .members-title h4 {
                                color: #173b56;
                                font-size: 17px;
                                font-weight: 700;
                                line-height: 1.2;
                            }

                            .members-title span {
                                display: block;
                                margin-top: 3px;
                                color: #8a98a7;
                                font-size: 11px;
                            }

                            /* =========================================================
   ACTION BUTTONS
========================================================= */

                            .members-actions {
                                display: flex;
                                align-items: center;
                                gap: 6px;
                            }

                            .action-btn {
                                height: 34px;
                                padding: 0 11px;
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                gap: 5px;
                                border-radius: 7px;
                                font-size: 11px;
                                font-weight: 600;
                                text-decoration: none;
                                transition: all 0.2s ease;
                                white-space: nowrap;
                            }

                            .action-btn i {
                                font-size: 14px;
                            }

                            /* Report */
                            .report-btn {
                                color: #0a3d61;
                                background: #f1f7fb;
                                border: 1px solid #d5e5ef;
                            }

                            .report-btn:hover {
                                color: #fff;
                                background: #0a3d61;
                                border-color: #0a3d61;
                                transform: translateY(-1px);
                            }

                            /* PDF */
                            .pdf-btn {
                                color: #dc3545;
                                background: #fff5f5;
                                border: 1px solid #f1c9ce;
                            }

                            .pdf-btn:hover {
                                color: #fff;
                                background: #dc3545;
                                border-color: #dc3545;
                                transform: translateY(-1px);
                            }

                            /* =========================================================
   FILTER CARD
========================================================= */

                            .filter-card {
                                min-height: 64px;
                                display: flex;
                                align-items: center;
                                padding: 9px;
                                background: #fff;
                                border: 1px solid #e7edf2;
                                border-radius: 12px;
                                box-shadow: 0 3px 12px rgba(15, 61, 92, 0.05);
                            }

                            .filter-card form {
                                width: 100%;
                            }

                            /* Search row */
                            .filter-search {
                                display: flex;
                                align-items: center;
                                gap: 7px;
                                width: 100%;
                            }

                            /* Search input */
                            .search-input {
                                position: relative;
                                flex: 1;
                            }

                            .search-input i {
                                position: absolute;
                                left: 12px;
                                top: 50%;
                                transform: translateY(-50%);
                                color: #8b9aa8;
                                font-size: 14px;
                                z-index: 2;
                            }

                            .search-input .form-control {
                                height: 42px;
                                padding-left: 35px;
                                padding-right: 12px;
                                border: 1px solid #dfe6ec;
                                border-radius: 8px;
                                background: #f8fafc;
                                color: #34495e;
                                font-size: 12px;
                                box-shadow: none;
                                transition: all 0.2s ease;
                            }

                            .search-input .form-control::placeholder {
                                color: #9aa7b3;
                            }

                            .search-input .form-control:focus {
                                background: #fff;
                                border-color: #0a3d61;
                                box-shadow: 0 0 0 3px rgba(10, 61, 97, 0.07);
                            }

                            /* =========================================================
   RESET BUTTON
========================================================= */

                            .reset-btn {
                                height: 42px;
                                min-width: 78px;
                                padding: 0 12px;
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                gap: 6px;
                                border: 1px solid #dfe4e8;
                                border-radius: 8px;
                                background: #f8f9fa;
                                color: #66727d;
                                font-size: 11px;
                                font-weight: 600;
                                transition: all 0.2s ease;
                            }

                            .reset-btn:hover {
                                background: #eef1f3;
                                border-color: #cbd3d9;
                                color: #263746;
                                transform: translateY(-1px);
                            }

                            .reset-btn i {
                                font-size: 12px;
                            }

                            /* =========================================================
   RESPONSIVE
========================================================= */

                            @media (max-width: 991px) {

                                .members-heading {
                                    min-height: auto;
                                }

                                .filter-card {
                                    min-height: auto;
                                }
                            }

                            @media (max-width: 575px) {

                                .members-heading {
                                    flex-direction: column;
                                    align-items: stretch;
                                    gap: 10px;
                                }

                                .members-actions {
                                    width: 100%;
                                }

                                .action-btn {
                                    flex: 1;
                                }

                                .filter-search {
                                    gap: 6px;
                                }

                                .reset-btn {
                                    min-width: 70px;
                                }

                                .reset-btn span {
                                    display: none;
                                }
                            }
                        </style>


                        <div class="container-fluid">
                            <div class="row g-2 mt-1" id="list_data">
                            </div>
                            <div class="row g-2 mt-2" id="old_results">
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $row): ?>
                                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                            <?= view('include/member/card/member_card', ['row' => $row]) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-12 text-center p-5">
                                        <h5>No Members Found</h5>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo view('include/member/footer.php') ?>
        </div>
    </div>
    <div class="rightbar-overlay"></div>
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
    <script>
        $(document).ready(function() {

            // Debounce helper
            function debounce(fn, delay) {
                let timer;
                return function() {
                    const args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        fn.apply(null, args);
                    }, delay);
                };
            }

            function collectFilters() {
                return {
                    search: $('#search').val().trim(),
                    status: $('#status1').val()
                };
            }

            function saveFiltersToStorage() {
                localStorage.setItem('userFilters', JSON.stringify(collectFilters()));
            }

            function loadFiltersFromStorage() {
                let filters = localStorage.getItem('userFilters');
                if (!filters) return null;

                filters = JSON.parse(filters);

                if (filters) {
                    $('#search').val(filters.search || '');
                    $('#status1').val(filters.status || '');
                }
                return filters;
            }

            const loadUsers = debounce(function() {
                const formData = collectFilters();
                saveFiltersToStorage();

                const anyFilter = Object.values(formData).some(val => val !== "");
                if (!anyFilter) {
                    $('#list_data').hide().html('');
                    $('#old_results').show();
                    localStorage.removeItem('userFilters');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: '<?= base_url("member/chapter/get_filtered_data2") ?>',
                    data: formData,
                    dataType: 'html',
                    beforeSend: function() {
                        $("#list_data").show().html('<div class="text-center py-3">Loading...</div>');
                        $('#old_results').hide();
                    },
                    success: function(response) {
                        $("#list_data").html(response).show();
                        $('#old_results').hide();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error);
                        $("#list_data").html('<div class="alert alert-danger">An error occurred. Please try again later.</div>');
                    }
                });
            }, 200);

            $('#search, #status1').on('input keyup change', loadUsers);
            $('#reset-btn').on('click', function() {
                // Clear filter inputs
                $('#search').val('');
                $('#status1').val('');

                // Clear filtered data
                $('#list_data').hide().html('');

                // Show original table
                $('#old_results').show();

                // Clear localStorage
                localStorage.removeItem('userFilters');
            });

            const savedFilters = loadFiltersFromStorage();
            if (savedFilters && Object.values(savedFilters).some(val => val)) {
                loadUsers();
            } else {
                $('#list_data').hide();
                $('#old_results').show();
            }

        });
    </script>
</body>

</html>