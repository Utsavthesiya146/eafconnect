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
            object-position: top center;
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
        <?php echo view('include/admin/header.php') ?>
        <?php echo view('include/admin/sidebar.php') ?>
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
                                                    <?php else : ?>
                                                        <img src="<?= base_url('uploads/profile/empty.png') ?>"
                                                            alt="Leader"
                                                            class="leader-img">
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
                    <div class="col-md-9">
                        <div class="row g-3">

                            <?php
                            $totalMembers = $chapters[0]['member_count'] ?? 0;
                            ?>

                            <!-- TOTAL MEMBERS -->
                            <div class="col-md-3">
                                <div class="stat-card border-start border-4 border-primary shadow-sm p-3 rounded text-center">
                                    <h6 class="text-muted">TOTAL MEMBERS</h6>
                                    <p class="stat-value mb-0"><?= esc($totalMembers) ?></p>
                                </div>
                            </div>

                            <!-- TOTAL Business  -->
                            <div class="col-md-3">
                                <div class="stat-card border-start border-4 border-success shadow-sm p-3 rounded text-center">
                                    <h6 class="text-muted">Total Business  (MITB)</h6>
                                    <p class="stat-value mb-0">₹<?= esc($totalMITB) ?></p>
                                </div>
                            </div>
                            <!-- MITB -->

                            <!-- TOTAL REFERRALS -->
                            <div class="col-md-3">
                                <div class="stat-card border-start border-4 border-warning shadow-sm p-3 rounded text-center">
                                    <h6 class="text-muted">Total Connects </h6>
                                    <p class="stat-value mb-0"><?= esc($connects) ?></p>
                                </div>
                            </div>

                            <!-- ONE TO ONE -->
                            <div class="col-md-3">
                                <div class="stat-card border-start border-4 border-info shadow-sm p-3 rounded text-center">
                                    <h6 class="text-muted">Total F2F</h6>
                                    <p class="stat-value mb-0"><?= esc($meetings) ?></p>
                                </div>
                            </div>

                            <!-- OPEN DISCUSSION (Notifications) -->
                            <div class="col-md-3">
                                <div class="stat-card border-start border-4 border-secondary shadow-sm p-3 rounded text-center">
                                    <h6 class="text-muted">Open for Discussion</h6>
                                    <p class="stat-value mb-0"><?= esc($notifications) ?></p>
                                </div>
                            </div>

                            <!-- VISITORS -->
                            <div class="col-md-3">
                                <div class="stat-card border-start border-4 border-dark shadow-sm p-3 rounded text-center">
                                    <h6 class="text-muted">Total Visitors</h6>
                                    <p class="stat-value mb-0"><?= esc($visitors) ?></p>
                                </div>
                            </div>

                            <!-- NOTIFICATIONS -->
                            <div class="col-md-3">
                                <div class="stat-card border-start border-4 border-danger shadow-sm p-3 rounded text-center">
                                    <h6 class="text-muted">Notifications</h6>
                                    <p class="stat-value mb-0"><?= esc($notifications) ?></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row p-2">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Chapter Members</h4>
                        </div>
                        <div class="col-md-6">
                            <div class="card border shadow-sm mb-2 filter-bar">
                                <div class="card-body p-2">
                                    <form id="search-form" method="POST">
                                        <div class="row">
                                            <!-- Search -->
                                            <div class="col-lg-9 col-md-6 col-8">
                                                <div class="input-group input-group-sm">
                                                    <input type="text"
                                                        id="search"
                                                        name="search"
                                                        class="form-control"
                                                        placeholder="Search name, phone, email...">
                                                </div>
                                            </div>
                                            <!-- Buttons -->
                                            <div class="col-lg-3 col-md-3 d-flex gap-2">
                                                <button type="button" id="reset-btn" class="btn btn-sm btn-outline-secondary w-100">
                                                    Reset
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4" id="list_data">
                        </div>
                    </div>
                    <div class="row g-3 mt-3" id="old_results">

                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $row): ?>
                                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                    <?= view('include/admin/card/member_card', ['row' => $row]) ?>
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
        <?php echo view('include/admin/footer.php') ?>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php echo view('include/admin/popup/members.php') ?>
    <?php echo view('include/admin/popup/chapter.php') ?>
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
                    url: '<?= base_url("admin/chapter/get_filtered_data2") ?>',
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