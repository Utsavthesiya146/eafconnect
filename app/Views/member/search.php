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
            border-color: #08426b;
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
            background: #08426b;
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
            border-top: 4px solid #c1281c;
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
            color: #c1281c;
            margin-bottom: 6px;
        }

        /* ===== CHAPTER BADGE ===== */
        .chapter-badge {
            background: rgba(249, 179, 9, .15);
            color: #c1281c;
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
            margin: 14px 0;
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
            color: #c1281c;
        }

        /* ===== CONTACT ===== */
        .member-contact {
            color: #c1281c;
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
            background: #c1281c;
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

                <div class="container">

                    <!-- ================= SEARCH HEADER ================= -->
                    <div class="search-header">
                        <div class="row align-items-center g-3">

                            <!-- Title -->
                            <div class="col-lg-6 col-md-5">
                                <div class="search-title">
                                    <div class="title-icon">
                                        <i class="ri-user-search-line"></i>
                                    </div>

                                    <div>
                                        <h4 class="mb-0">Search Result</h4>
                                        <span>Find members quickly and easily</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Search -->
                            <div class="col-lg-6 col-md-7">
                                <form action="<?= site_url('member/search') ?>" method="post">
                                    <?= csrf_field() ?>

                                    <div class="search-box">
                                        <i class="ri-search-line search-icon"></i>

                                        <input
                                            type="text"
                                            name="search"
                                            class="form-control"
                                            placeholder="Search members..."
                                            value="<?= esc($search ?? '') ?>"
                                            autocomplete="off">

                                        <button type="submit" class="search-btn">
                                            <i class="ri-search-line"></i>
                                            <span>Search</span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <style>
                        /* ================= SEARCH HEADER ================= */
                        .search-header {
                            background: #fff;
                            border: 1px solid #e8edf3;
                            border-radius: 14px;
                            padding: 18px 20px;
                            margin-bottom: 20px;
                            box-shadow: 0 3px 12px rgba(15, 61, 92, 0.05);
                        }

                        /* Title */
                        .search-title {
                            display: flex;
                            align-items: center;
                            gap: 12px;
                        }

                        .title-icon {
                            width: 42px;
                            height: 42px;
                            flex-shrink: 0;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background: linear-gradient(135deg, #0a3d61, #176b9c);
                            color: #fff;
                            border-radius: 10px;
                            font-size: 20px;
                            box-shadow: 0 4px 10px rgba(10, 61, 97, 0.18);
                        }

                        .search-title h4 {
                            color: #173b56;
                            font-size: 18px;
                            font-weight: 700;
                            line-height: 1.2;
                        }

                        .search-title span {
                            display: block;
                            margin-top: 3px;
                            color: #8a98a7;
                            font-size: 12px;
                        }

                        /* Search Box */
                        .search-box {
                            position: relative;
                            display: flex;
                            align-items: center;
                            width: 100%;
                            height: 46px;
                            background: #f7f9fb;
                            border: 1px solid #e1e7ed;
                            border-radius: 10px;
                            padding-left: 42px;
                            transition: all 0.25s ease;
                        }

                        .search-box:focus-within {
                            background: #fff;
                            border-color: #0a3d61;
                            box-shadow: 0 0 0 3px rgba(10, 61, 97, 0.08);
                        }

                        /* Search Icon */
                        .search-icon {
                            position: absolute;
                            left: 15px;
                            color: #82909e;
                            font-size: 18px;
                            pointer-events: none;
                        }

                        /* Input */
                        .search-box .form-control {
                            height: 44px;
                            border: 0;
                            outline: 0;
                            background: transparent;
                            box-shadow: none !important;
                            padding: 0 10px 0 0;
                            font-size: 13px;
                            color: #34495e;
                        }

                        .search-box .form-control::placeholder {
                            color: #9aa7b3;
                        }

                        /* Button */
                        .search-btn {
                            height: 36px;
                            margin-right: 5px;
                            padding: 0 15px;
                            border: 0;
                            border-radius: 7px;
                            background: #0a3d61;
                            color: #fff;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            gap: 6px;
                            font-size: 12px;
                            font-weight: 600;
                            cursor: pointer;
                            transition: all 0.25s ease;
                        }

                        .search-btn i {
                            font-size: 15px;
                        }

                        .search-btn:hover {
                            background: #072f4b;
                            transform: translateY(-1px);
                            box-shadow: 0 4px 10px rgba(10, 61, 97, 0.18);
                        }

                        /* ================= RESPONSIVE ================= */
                        @media (max-width: 767px) {

                            .search-header {
                                padding: 15px;
                                border-radius: 12px;
                            }

                            .search-title {
                                margin-bottom: 2px;
                            }

                            .search-title h4 {
                                font-size: 16px;
                            }

                            .search-box {
                                height: 44px;
                            }

                            .search-btn {
                                padding: 0 12px;
                            }

                            .search-btn span {
                                display: none;
                            }
                        }
                    </style>


                    <br>
                    <div class="row g-3">
                        <?php if (!empty($users)) : ?>
                            <?php foreach ($users as $row) : ?>
                                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                    <?= view('include/member/card/member_card', ['row' => $row]) ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
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

</body>

</html>