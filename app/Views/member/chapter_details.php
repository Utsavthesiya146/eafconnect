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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts & Master KVV/BNI Revamp Theme -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets2/css/eaf-revamp.css'); ?>?v=3.5">
    <style>
        .filter-bar {
            border-radius: 8px;
        }

        .filter-bar .form-control,
        .filter-bar .form-select {
            height: 34px;
            font-size: 13px;
        }

        .filter-bar .input-group-text {
            padding: 4px 8px;
            font-size: 13px;
        }

        .filter-bar .btn {
            height: 34px;
            font-size: 13px;
            padding: 4px 10px;
        }

        .chapter-card-pro {
            background: #ffffff;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            transition: all .3s ease;
            position: relative;
            overflow: hidden;
        }

        /* TOP STRIP */
        .chapter-card-pro::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 4px;
            width: 100%;
            background: linear-gradient(90deg, #08426b, #ffc107);
        }

        .chapter-card-pro:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
        }

        /* HEADER */
        .chapter-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chapter-top h4 {
            font-weight: 600;
            margin: 0;
            font-size: 16px;
        }

        .location-badge {
            background: rgba(187, 24, 30, 0.1);
            color: #08426b;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            margin-top: 4px;
        }

        .card-menu {
            color: #999;
            cursor: pointer;
            font-size: 16px;
        }

        /* LEADER */
        .section-label {
            margin-top: 14px;
            font-size: 11px;
            font-weight: 700;
            color: #888;
            letter-spacing: .5px;
        }

        .leader-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 6px;
        }

        .leader-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fff3cd;
            color: #ffc107;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .leader-box strong {
            font-size: 12px;
        }

        .leader-box small {
            display: block;
            font-size: 12px;
            color: #777;
        }

        /* ===== STATS ===== */

        .chapter-stats {
            margin-top: 12px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .stat {
            background: #f9fafb;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            border: 1px solid #eee;
            transition: .25s;
        }

        .stat:hover {
            border-color: #1b4e8f33;
        }

        /* LABEL */
        .stat span {
            font-size: 12px;
            color: #666;
        }

        /* ✅ REDUCED COUNT SIZE */
        .stat.large h3 {
            margin: 4px 0 0;
            font-size: 18px;
            /* reduced from big size */
            font-weight: 700;
            color: #df2d23;
        }

        .stat.small h5 {
            margin-top: 4px;
            font-size: 15px;
            /* smaller numbers */
            font-weight: 700;
            color: #df2d23;
        }

        /* SMALL STATS ROW FIX */
        .row.mt-2 {
            margin-top: 6px !important;
        }

        /* BUTTON */
        .dashboard-btn {
            display: block;
            margin-top: 14px;
            text-align: center;
            padding: 9px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            color: #08426b;
            border: 2px solid #08426b;
            transition: all .3s;
        }

        .dashboard-btn:hover {
            background: linear-gradient(90deg, #08426b, #a11519);
            color: #fff;
            box-shadow: 0 5px 12px rgba(187, 24, 30, .3);
        }

        .leader-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
            background: #f2f2f2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .leader-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .leader-avatar {
            border: 2px solid #08426b;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
        }

        /* container */
        .card-menu {
            display: flex;
            gap: 3px;
        }

        /* base button */
        .action-btn {
            width: 22px;
            height: 22px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            text-decoration: none;
            color: #fff;
            transition: all .25s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .08);
        }

        /* view */
        .view-btn {
            background: #184b8d;
        }

        /* edit */
        .edit-btn {
            background: #f59e0b;
        }

        /* delete */
        .delete-btn {
            background: #ef4444;
        }

        /* hover effect */
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, .15);
            opacity: .9;
        }

        /* icon alignment fix */
        .action-btn i {
            font-size: 12px;
        }

        .chapter-card-pro {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100%;
            border-radius: 14px;
        }

        /* middle content grows automatically */
        .chapter-card-pro .leader-section,
        .chapter-card-pro .chapter-stats,
        .chapter-card-pro .row {
            flex-shrink: 0;
        }

        /* push button to bottom */
        .dashboard-btn {
            margin-top: auto;
        }

        .chapter-status {
            display: inline-flex;
            align-items: center;
            gap: 0px;
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            user-select: none;
        }

        /* ACTIVE */
        .status-active {
            background: #e8f8f0;
            color: #1ca67a;
        }

        .status-active:hover {
            background: #1ca67a;
            color: #fff;
            transform: translateY(-2px);
        }

        /* PENDING */
        .status-pending {
            background: #fff6e5;
            color: #e6a100;
        }

        .status-pending:hover {
            background: #e6a100;
            color: #fff;
            transform: translateY(-2px);
        }

        /* BLOCKED */
        .status-blocked {
            background: #ffeaea;
            color: #e53935;
        }

        .status-blocked:hover {
            background: #e53935;
            color: #fff;
            transform: translateY(-2px);
        }

        /* ICON SIZE */
        .chapter-status i {
            font-size: 14px;
        }

        .modern-table-wrapper {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            overflow: scroll;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* table */
        .modern-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            /* increased font size */
        }

        /* header */
        .modern-table thead {
            background: #08426b;
            color: #fff;
        }

        .modern-table th {
            padding: 14px 12px;
            /* increased padding */
            font-weight: 700;
            text-align: left;
        }

        /* rows */
        .modern-table td {
            padding: 14px 12px;
            /* increased padding */
            border-bottom: 1px solid #f1f1f1;
            vertical-align: middle;
        }

        .modern-table tbody tr {
            transition: background 0.25s ease;
        }

        .modern-table tbody tr:hover {
            background: #fff8e1;
        }

        /* member */
        .member-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            /* slightly bigger gap */
        }

        .avatar {
            width: 48px;
            /* slightly bigger avatar */
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f1f1f1;
            /* subtle border */
        }

        .name {
            font-weight: 600;
            font-size: 15px;
            /* increased font size */
        }

        .member-cell small {
            font-size: 13px;
            /* slightly bigger small text */
            color: #666;
        }

        /* business */
        .business {
            color: #08426b;
            font-weight: 600;
            font-size: 14px;
            /* increased font size */
        }

        /* role badge */
        .role-badge {
            background: #ffc107;
            padding: 6px 12px;
            /* more padding */
            border-radius: 20px;
            font-size: 12px;
            /* increased font size */
            font-weight: 600;
        }

        /* status badges */
        .status {
            padding: 6px 12px;
            /* more padding */
            border-radius: 20px;
            font-size: 12px;
            /* increased font size */
            font-weight: 600;
            display: inline-block;
            text-align: center;
        }

        .status.active {
            background: #e6f7ec;
            color: #198754;
        }

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.blocked {
            background: #fdeaea;
            color: #08426b;
        }

        /* action buttons */
        .btn-sm {
            padding: 6px 10px;
            font-size: 13px;
            /* slightly larger */
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-sm:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .card {
            margin-bottom: 10px;
        }

        .card:hover {
            transform: translateY(-3px);
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .stat-title {
            font-size: 12px;
            font-weight: 600;
            color: #555;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 22px;
            font-weight: 700;
            color: #08426b;
        }

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
            border-top: 4px solid #0a3d61;
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
            object-position: top center;
            margin-bottom: 10px;
            border: 3px solid #f9b309;
        }

        /* ===== NAME ===== */
        .member-name {
            font-weight: 700;
            color: #0a3d61;
            margin-bottom: 6px;
        }

        /* ===== CHAPTER BADGE ===== */
        .chapter-badge {
            background: rgba(249, 179, 9, .15);
            color: #0a3d61;
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
            padding: 10px;
            border-radius: 10px;
            margin: 7px 0;
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
            color: #0a3d61;
        }

        /* ===== CONTACT ===== */
        .member-contact {
            color: #0a3d61;
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
            background: #0a3d61;
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
                <h4>My Chapter Details</h4>
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <?php if (!empty($chapters)) : ?>
                            <?php foreach ($chapters as $row) : ?>
                                <div class="col-md-12 d-flex">
                                    <div class="chapter-card-pro">
                                        <div class="chapter-top">
                                            <div>
                                                <h4><?= esc($row['chapter_name']) ?></h4>
                                                <span class="location-badge">
                                                    <i class="bi bi-geo-alt-fill"></i>
                                                    <?= esc($row['location']) ?>
                                                </span>
                                            </div>
                                            <div class="card-menu">
                                                <?php
                                                $statusClass = '';
                                                $statusText  = '';
                                                $statusIcon  = '';
                                                if ($row['chapter_status'] == '1') {
                                                    $statusClass = 'status-active';
                                                    $statusText  = 'Active';
                                                    $statusIcon  = 'bi-check-circle-fill';
                                                } elseif ($row['chapter_status'] == '2') {
                                                    $statusClass = 'status-pending';
                                                    $statusText  = 'Hold';
                                                    $statusIcon  = 'bi-clock-fill';
                                                } else {
                                                    $statusClass = 'status-blocked';
                                                    $statusText  = 'Blocked';
                                                    $statusIcon  = 'bi-x-circle-fill';
                                                }
                                                ?>
                                                <span onclick="edit_chapter_status('<?= $row['chapter_code'] ?>');"
                                                    class="chapter-status <?= $statusClass ?>">
                                                    <i class="bi <?= $statusIcon ?>"></i>
                                                    <?= $statusText ?>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- LEADER -->
                                        <div class="leader-section">
                                            <p class="section-label">CHAPTER LEADER</p>
                                            <div class="leader-box">
                                                <div class="leader-avatar">
                                                    <?php if (!empty($row['leader_photo']) && $row['leader_photo'] != 'empty.png') : ?>
                                                        <img src="<?= base_url('uploads/profile/' . $row['leader_photo']) ?>"
                                                            alt="Leader"
                                                            class="leader-img">
                                                        <!-- <img src="<?= base_url('images/img1.png') ?>"
                                                            class="leader-img"
                                                            style="object-fit:cover;object-position:top center;"> -->
                                                    <?php else : ?>
                                                        <!-- <img src="<?= base_url('uploads/profile/empty.png') ?>"
                                                            alt="Leader"
                                                            class="leader-img"> -->
                                                        <img src="<?= base_url('images/img1.png') ?>"
                                                            class="leader-img"
                                                            style="object-fit:cover;object-position:top center;">
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <strong>
                                                        <?= !empty($row['leader_name'])
                                                            ? esc($row['leader_name'])
                                                            : 'Not Assigned' ?>
                                                    </strong>
                                                    <small>
                                                        Assign leader to activate insights
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="col-12 text-center">
                                <p>No Chapters Found</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                    $totalMembers = $chapters[0]['member_count'] ?? 0;
                    ?>
                    <div class="col-md-9">
                        <div class="row g-3">

                            <!-- TOTAL MEMBERS -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <div class="stat-card stat-primary">
                                    <div class="stat-icon">
                                        <i class="bi bi-people-fill"></i>
                                    </div>

                                    <div class="stat-content">
                                        <span class="stat-label">Total Members</span>
                                        <h3 class="stat-value">
                                            <?= esc($totalMembers) ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>

                            <!-- TOTAL MITB -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <div class="stat-card stat-success">
                                    <div class="stat-icon">
                                        <i class="bi bi-currency-rupee"></i>
                                    </div>

                                    <div class="stat-content">
                                        <span class="stat-label">Total MITB</span>
                                        <h3 class="stat-value">
                                            ₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($totalMITB) ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>

                            <!-- TOTAL REFERRALS -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <div class="stat-card stat-warning">
                                    <div class="stat-icon">
                                        <i class="bi bi-share-fill"></i>
                                    </div>

                                    <div class="stat-content">
                                        <span class="stat-label">Total Connects</span>
                                        <h3 class="stat-value">
                                            <?= esc($connects) ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>

                            <!-- TOTAL F2F -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <div class="stat-card stat-info">
                                    <div class="stat-icon">
                                        <i class="bi bi-person-video2"></i>
                                    </div>

                                    <div class="stat-content">
                                        <span class="stat-label">Total F2F</span>
                                        <h3 class="stat-value">
                                            <?= esc($meetings) ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>

                            <!-- OPEN DISCUSSION -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <div class="stat-card stat-secondary">
                                    <div class="stat-icon">
                                        <i class="bi bi-chat-dots-fill"></i>
                                    </div>

                                    <div class="stat-content">
                                        <span class="stat-label">Open Discussion</span>
                                        <h3 class="stat-value">
                                            <?= esc($notifications) ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>

                            <!-- VISITORS -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <div class="stat-card stat-dark">
                                    <div class="stat-icon">
                                        <i class="bi bi-person-walking"></i>
                                    </div>

                                    <div class="stat-content">
                                        <span class="stat-label">Total Visitors</span>
                                        <h3 class="stat-value">
                                            <?= esc($visitors) ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>

                            <!-- NOTIFICATIONS -->
                            <!-- <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <div class="stat-card stat-danger">
                                    <div class="stat-icon">
                                        <i class="bi bi-bell-fill"></i>
                                    </div>

                                    <div class="stat-content">
                                        <span class="stat-label">Notifications</span>
                                        <h3 class="stat-value">
                                            <?= esc($notifications) ?>
                                        </h3>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <style>
                        /* =========================================
   STAT CARDS
========================================= */

                        .stat-card {
                            position: relative;
                            display: flex;
                            align-items: center;
                            gap: 12px;
                            min-height: 82px;
                            padding: 14px 16px;
                            background: #fff;
                            border: 1px solid #edf0f3;
                            border-radius: 12px;
                            overflow: hidden;
                            transition: all .25s ease;
                            box-shadow: 0 3px 12px rgba(0, 0, 0, .05);
                        }

                        /* Left accent */
                        .stat-card::before {
                            content: "";
                            position: absolute;
                            left: 0;
                            top: 12px;
                            bottom: 12px;
                            width: 3px;
                            border-radius: 0 4px 4px 0;
                        }

                        /* Hover */
                        .stat-card:hover {
                            transform: translateY(-3px);
                            box-shadow: 0 8px 22px rgba(0, 0, 0, .09);
                        }

                        /* =========================================
   ICON
========================================= */

                        .stat-icon {
                            flex: 0 0 42px;
                            width: 42px;
                            height: 42px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            border-radius: 10px;
                            font-size: 19px;
                        }

                        .stat-content {
                            min-width: 0;
                        }

                        .stat-label {
                            display: block;
                            margin-bottom: 3px;
                            color: #7b8490;
                            font-size: 12px;
                            font-weight: 500;
                            line-height: 1.2;
                        }

                        .stat-value {
                            margin: 0;
                            color: #1f2937;
                            font-size: 22px;
                            font-weight: 700;
                            line-height: 1.15;
                            letter-spacing: -.3px;
                        }

                        /* =========================================
   PRIMARY
========================================= */

                        .stat-primary::before {
                            background: #0d6efd;
                        }

                        .stat-primary .stat-icon {
                            color: #0d6efd;
                            background: rgba(13, 110, 253, .10);
                        }

                        /* =========================================
   SUCCESS
========================================= */

                        .stat-success::before {
                            background: #198754;
                        }

                        .stat-success .stat-icon {
                            color: #198754;
                            background: rgba(25, 135, 84, .10);
                        }

                        /* =========================================
   WARNING
========================================= */

                        .stat-warning::before {
                            background: #f59e0b;
                        }

                        .stat-warning .stat-icon {
                            color: #d88900;
                            background: rgba(245, 158, 11, .12);
                        }

                        /* =========================================
   INFO
========================================= */

                        .stat-info::before {
                            background: #0dcaf0;
                        }

                        .stat-info .stat-icon {
                            color: #0891b2;
                            background: rgba(13, 202, 240, .11);
                        }

                        /* =========================================
   SECONDARY
========================================= */

                        .stat-secondary::before {
                            background: #6c757d;
                        }

                        .stat-secondary .stat-icon {
                            color: #6c757d;
                            background: rgba(108, 117, 125, .11);
                        }

                        /* =========================================
   DARK
========================================= */

                        .stat-dark::before {
                            background: #343a40;
                        }

                        .stat-dark .stat-icon {
                            color: #343a40;
                            background: rgba(52, 58, 64, .10);
                        }

                        /* =========================================
   DANGER
========================================= */

                        .stat-danger::before {
                            background: #dc3545;
                        }

                        .stat-danger .stat-icon {
                            color: #dc3545;
                            background: rgba(220, 53, 69, .10);
                        }

                        /* =========================================
   MOBILE
========================================= */

                        @media (max-width: 575.98px) {

                            .stat-card {
                                min-height: 72px;
                                padding: 11px 10px;
                                gap: 9px;
                                border-radius: 10px;
                            }

                            .stat-icon {
                                flex: 0 0 34px;
                                width: 34px;
                                height: 34px;
                                border-radius: 8px;
                                font-size: 15px;
                            }

                            .stat-label {
                                font-size: 10px;
                                margin-bottom: 2px;
                            }

                            .stat-value {
                                font-size: 17px;
                            }

                            .stat-card::before {
                                top: 10px;
                                bottom: 10px;
                                width: 2px;
                            }
                        }
                    </style>
                </div>
                <div class="row p-2">

                    <!-- ================= CHAPTER MEMBERS HEADER ================= -->
                    <div class="chapter-members-header">

                        <!-- LEFT: TITLE -->
                        <div class="chapter-members-title">

                            <div class="chapter-members-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>

                            <div>
                                <h4>Chapter Members</h4>
                                <p>View and search members in this chapter</p>
                            </div>

                        </div>

                        <!-- RIGHT: SEARCH -->
                        <form id="search-form" method="POST" class="chapter-members-search">
                            <?= csrf_field() ?>

                            <div class="member-search-input">

                                <i class="fa fa-search"></i>

                                <input type="text"
                                    id="search"
                                    name="search"
                                    placeholder="Search name, phone or email..."
                                    autocomplete="off">

                            </div>

                            <button type="button"
                                id="reset-btn"
                                class="member-reset-btn"
                                title="Reset search">

                                <i class="fa fa-arrow-counterclockwise"></i>
                                <span>Reset</span>

                            </button>

                        </form>

                    </div>


                    <style>
                        /* =========================================================
   CHAPTER MEMBERS HEADER
========================================================= */

                        .chapter-members-header {
                            width: 100%;
                            min-height: 70px;

                            display: flex;
                            align-items: center;
                            justify-content: space-between;

                            gap: 20px;

                            padding: 12px 15px;

                            background: #ffffff;

                            border: 1px solid #e5ebf0;
                            border-radius: 12px;

                            box-shadow: 0 3px 12px rgba(15, 61, 92, 0.05);

                            margin-bottom: 18px;
                        }


                        /* =========================================================
   TITLE
========================================================= */

                        .chapter-members-title {
                            display: flex;
                            align-items: center;

                            gap: 11px;

                            min-width: 230px;
                        }

                        .chapter-members-icon {
                            width: 42px;
                            height: 42px;

                            flex: 0 0 42px;

                            display: flex;
                            align-items: center;
                            justify-content: center;

                            border-radius: 10px;

                            background: linear-gradient(135deg,
                                    #0a3d61,
                                    #176b9c);

                            color: #ffffff;

                            font-size: 19px;

                            box-shadow: 0 4px 10px rgba(10, 61, 97, 0.16);
                        }

                        .chapter-members-title h4 {
                            margin: 0;

                            color: #173b56;

                            font-size: 18px;

                            font-weight: 700;

                            line-height: 1.2;
                        }

                        .chapter-members-title p {
                            margin: 4px 0 0;

                            color: #8a98a7;

                            font-size: 11px;

                            line-height: 1.2;
                        }


                        /* =========================================================
   SEARCH AREA
========================================================= */

                        .chapter-members-search {
                            width: 52%;

                            display: flex;
                            align-items: center;

                            gap: 7px;

                            margin: 0;
                        }


                        /* =========================================================
   SEARCH INPUT
========================================================= */

                        .member-search-input {
                            position: relative;

                            flex: 1;
                        }

                        .member-search-input>i {
                            position: absolute;

                            left: 13px;
                            top: 50%;

                            transform: translateY(-50%);

                            color: #8b99a6;

                            font-size: 15px;

                            pointer-events: none;

                            z-index: 2;
                        }

                        .member-search-input input {
                            width: 100%;
                            height: 42px;

                            padding: 0 13px 0 37px;

                            border: 1px solid #dfe6ec;

                            border-radius: 8px;

                            background: #f8fafc;

                            color: #34495e;

                            font-size: 12px;

                            outline: none;

                            transition: all 0.2s ease;
                        }

                        .member-search-input input::placeholder {
                            color: #9aa7b3;
                        }

                        .member-search-input input:hover {
                            background: #ffffff;

                            border-color: #cbd6df;
                        }

                        .member-search-input input:focus {
                            background: #ffffff;

                            border-color: #0a3d61;

                            box-shadow: 0 0 0 3px rgba(10, 61, 97, 0.07);
                        }


                        /* =========================================================
   RESET BUTTON
========================================================= */

                        .member-reset-btn {
                            height: 42px;

                            min-width: 78px;

                            padding: 0 13px;

                            display: inline-flex;
                            align-items: center;
                            justify-content: center;

                            gap: 6px;

                            border: 1px solid #dce3e8;

                            border-radius: 8px;

                            background: #ffffff;

                            color: #687784;

                            font-size: 11px;

                            font-weight: 600;

                            cursor: pointer;

                            transition: all 0.2s ease;
                        }

                        .member-reset-btn i {
                            font-size: 13px;

                            transition: transform 0.3s ease;
                        }

                        .member-reset-btn:hover {
                            background: #f3f7fa;

                            border-color: #0a3d61;

                            color: #0a3d61;

                            box-shadow: 0 3px 9px rgba(10, 61, 97, 0.08);
                        }

                        .member-reset-btn:hover i {
                            transform: rotate(-45deg);
                        }

                        .member-reset-btn:active {
                            transform: scale(0.97);
                        }


                        /* =========================================================
   BOTTOM ACCENT
========================================================= */

                        .chapter-members-header::after {
                            content: "";

                            position: absolute;

                            width: 0;
                        }


                        /* =========================================================
   TABLET
========================================================= */

                        @media (max-width: 991px) {

                            .chapter-members-header {
                                align-items: stretch;

                                flex-direction: column;

                                gap: 12px;
                            }

                            .chapter-members-title {
                                min-width: 0;
                            }

                            .chapter-members-search {
                                width: 100%;
                            }
                        }


                        /* =========================================================
   MOBILE
========================================================= */

                        @media (max-width: 575px) {

                            .chapter-members-header {
                                padding: 12px;

                                border-radius: 10px;

                                gap: 11px;
                            }

                            .chapter-members-icon {
                                width: 38px;
                                height: 38px;

                                flex-basis: 38px;

                                font-size: 17px;
                            }

                            .chapter-members-title h4 {
                                font-size: 16px;
                            }

                            .chapter-members-title p {
                                font-size: 10px;
                            }

                            .chapter-members-search {
                                gap: 6px;
                            }

                            .member-search-input input {
                                height: 40px;

                                font-size: 11px;
                            }

                            .member-reset-btn {
                                height: 40px;

                                min-width: 42px;

                                padding: 0 10px;
                            }

                            .member-reset-btn span {
                                display: none;
                            }
                        }
                    </style>


                    <div class="row g-2 mt-2" id="list_data">
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-2">
                        </div>
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
            }, 400);

            $('#search, #status1').on('keyup change', loadUsers);
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