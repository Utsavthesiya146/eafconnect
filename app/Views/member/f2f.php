<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>F2F </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts & Master KVV/BNI Revamp Theme -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets2/css/eaf-revamp.css'); ?>?v=3.0">
    <style>
        /* =========================================================
   FILTER BAR
========================================================= */

        .filter-bar {
            border-radius: 8px;
        }

        .filter-bar .form-control,
        .filter-bar .form-select {
            height: 32px;
            font-size: 12px;
        }

        .filter-bar .input-group-text {
            padding: 3px 7px;
            font-size: 12px;
        }

        .filter-bar .btn {
            height: 32px;
            font-size: 12px;
            padding: 3px 9px;
        }


        /* =========================================================
   TABLE WRAPPER
========================================================= */

        .modern-table-wrapper {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07);
            overflow: auto;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }


        /* =========================================================
   TABLE
========================================================= */

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }


        /* HEADER */

        .modern-table thead {
            background: #08426b;
            color: #fff;
        }

        .modern-table th {
            padding: 9px 10px;
            font-weight: 700;
            text-align: left;
            white-space: nowrap;
        }


        /* ROWS */

        .modern-table td {
            padding: 9px 10px;
            border-bottom: 1px solid #f1f1f1;
            vertical-align: middle;
        }

        .modern-table tbody tr {
            transition: background 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background: #fff8e1;
        }


        /* =========================================================
   MEMBER
========================================================= */

        .member-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #f1f1f1;
        }

        .name {
            font-weight: 600;
            font-size: 13px;
            line-height: 1.2;
        }

        .member-cell small {
            font-size: 11px;
            color: #666;
            line-height: 1.2;
        }


        /* =========================================================
   BUSINESS
========================================================= */

        .business {
            color: #08426b;
            font-weight: 600;
            font-size: 13px;
        }


        /* =========================================================
   BADGES
========================================================= */

        .role-badge {
            background: #ffc107;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
        }


        /* STATUS */

        .status {
            padding: 4px 9px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            text-align: center;
            transition: all 0.2s ease;
            cursor: pointer;
            white-space: nowrap;
        }


        /* STATUS COLORS */

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.contacted {
            background: #cce5ff;
            color: #004085;
        }

        .status.completed {
            background: #d4edda;
            color: #155724;
        }

        .status.cancelled {
            background: #f5c6cb;
            color: #721c24;
        }

        .status.scheduled {
            background: #cce5ff;
            color: #004085;
        }

        .status.re-scheduled,
        .status.rescheduled {
            background: #fff3cd;
            color: #856404;
        }

        .status.active {
            background: #e6f7ec;
            color: #198754;
        }

        .status.blocked {
            background: #fdeaea;
            color: #08426b;
        }

        .status:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.12);
        }


        /* =========================================================
   ACTION BUTTON
========================================================= */

        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .btn-sm:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
        }


        /* =========================================================
   CARD
========================================================= */

        .card {
            margin-bottom: 8px;
        }

        .card:hover {
            transform: translateY(-2px);
            transition: all 0.25s ease;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }


        /* =========================================================
   TABS
========================================================= */

        .f2f-tabs {
            display: flex;
            gap: 8px;
        }

        .tab-btn {
            padding: 7px 13px;
            border-radius: 7px;
            background: #f3f4f6;
            color: #555;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: .25s;
        }

        .tab-btn:hover {
            background: #e5e7eb;
        }

        .tab-btn.active {
            background: #184b8d;
            color: #fff;
            box-shadow: 0 2px 7px rgba(0, 0, 0, .12);
        }


        /* =========================================================
   PAGINATION
========================================================= */

        .pagination-wrapper {
            margin-top: 12px;
        }

        .pagination {
            display: flex;
            gap: 4px;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination li a,
        .pagination li span {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 7px;
            border: 1px solid #e0e0e0;
            color: #333;
            background: #fff;
            font-size: 12px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .pagination li a:hover {
            background: #8B5CF6;
            color: #fff;
            border-color: #8B5CF6;
            transform: translateY(-1px);
        }

        .pagination li.active span {
            background: #8B5CF6;
            color: #fff;
            border-color: #8B5CF6;
            font-weight: 700;
        }

        .pagination li.disabled span {
            opacity: 0.4;
            cursor: not-allowed;
        }


        /* =========================================================
   FILTER UI
========================================================= */

        .filter-field {
            min-width: 0;
        }

        .filter-label {
            display: flex;
            align-items: center;
            gap: 5px;
            margin: 0 0 4px 2px;
            color: #596873;
            font-size: 10px;
            font-weight: 700;
            line-height: 1;
        }

        .filter-label i {
            color: #174F70;
            font-size: 10px;
        }

        .filter-label-hidden {
            visibility: hidden;
        }


        /* CONTROL */

        .filter-control {
            position: relative;
            width: 100%;
        }


        /* ICON */

        .filter-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            z-index: 2;
            transform: translateY(-50%);
            color: #174F70;
            font-size: 10px;
            pointer-events: none;
        }


        /* SELECT */

        .filter-select {
            width: 100%;
            height: 36px;

            padding: 0 27px 0 30px !important;

            border: 1px solid #dce5ea !important;
            border-radius: 7px !important;

            background-color: #f8fafb !important;
            color: #26343d !important;

            font-size: 11px !important;
            font-weight: 600;

            box-shadow: none !important;
            cursor: pointer;

            transition:
                border-color .2s ease,
                background .2s ease,
                box-shadow .2s ease;
        }

        .filter-select:hover {
            background-color: #fff !important;
            border-color: #c4d3db !important;
        }

        .filter-select:focus {
            background-color: #fff !important;
            border-color: #174F70 !important;
            box-shadow: 0 0 0 2px rgba(23, 79, 112, .08) !important;
        }


        /* DATE INPUT */

        .filter-input {
            width: 100%;
            height: 36px;

            padding: 0 8px 0 30px !important;

            border: 1px solid #dce5ea !important;
            border-radius: 7px !important;

            background-color: #f8fafb !important;
            color: #26343d !important;

            font-size: 11px !important;
            font-weight: 600;

            box-shadow: none !important;

            transition:
                border-color .2s ease,
                background .2s ease,
                box-shadow .2s ease;
        }

        .filter-input:hover {
            background-color: #fff !important;
            border-color: #c4d3db !important;
        }

        .filter-input:focus {
            background-color: #fff !important;
            border-color: #174F70 !important;
            box-shadow: 0 0 0 2px rgba(23, 79, 112, .08) !important;
            outline: none;
        }


        /* =========================================================
   RESET BUTTON
========================================================= */

        .filter-reset-btn {
            width: 100%;
            height: 36px;

            display: flex;
            align-items: center;
            justify-content: center;

            gap: 6px;
            padding: 0 11px;

            border: 1px solid #d5dfe5;
            border-radius: 7px;

            background: #f5f7f8;
            color: #59656e;

            font-size: 11px;
            font-weight: 700;

            cursor: pointer;

            transition:
                background .2s ease,
                color .2s ease,
                border-color .2s ease,
                transform .2s ease,
                box-shadow .2s ease;
        }

        .filter-reset-btn i {
            font-size: 10px;
            transition: transform .3s ease;
        }

        .filter-reset-btn:hover {
            color: #fff;
            background: #174F70;
            border-color: #174F70;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(23, 79, 112, .15);
        }

        .filter-reset-btn:hover i {
            transform: rotate(180deg);
        }

        .filter-reset-btn:active {
            transform: translateY(0);
            box-shadow: none;
        }


        /* =========================================================
   ADD NEW MEETING BUTTON
========================================================= */

        .add-meeting-btn {
            height: 36px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 6px;

            padding: 0 11px 0 5px;

            border: 1px solid #174F70;
            border-radius: 20px;

            background: #174F70;
            color: #fff;

            font-size: 11px;
            font-weight: 650;

            line-height: 1;

            cursor: pointer;

            box-shadow: 0 3px 8px rgba(23, 79, 112, .14);

            transition:
                background .2s ease,
                border-color .2s ease,
                transform .2s ease,
                box-shadow .2s ease;
        }

        .add-meeting-icon {
            width: 27px;
            height: 27px;

            flex: 0 0 27px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #fff;
            color: #174F70;

            font-size: 10px;

            transition:
                transform .2s ease,
                background .2s ease;
        }

        .add-meeting-text {
            white-space: nowrap;
        }

        .add-meeting-btn:hover {
            background: #123F59;
            border-color: #123F59;
            color: #fff;

            transform: translateY(-1px);

            box-shadow: 0 5px 12px rgba(23, 79, 112, .20);
        }

        .add-meeting-btn:hover .add-meeting-icon {
            transform: rotate(90deg);
            background: #FFB900;
            color: #fff;
        }

        .add-meeting-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 5px rgba(23, 79, 112, .15);
        }


        /* =========================================================
   MOBILE
========================================================= */

        @media (max-width: 768px) {

            .modern-table-wrapper {
                overflow: hidden;
                background: transparent;
                box-shadow: none;
            }

            .modern-table thead {
                display: none;
            }

            .modern-table,
            .modern-table tbody,
            .modern-table tr {
                display: block;
                width: 100%;
            }

            .modern-table tr {
                background: #fff;
                border-radius: 12px;

                margin-bottom: 9px;
                padding: 10px;

                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);

                border-left: 3px solid #08426b;
                position: relative;
            }

            .modern-table td:first-child {
                position: absolute;

                top: 8px;
                right: 9px;

                font-size: 10px;
                font-weight: 700;

                background: #08426b;
                color: #fff;

                width: 21px;
                height: 21px;

                display: flex;
                align-items: center;
                justify-content: center;

                border-radius: 50%;
            }

            .modern-table td {
                display: block;
                border: none;
                padding: 0;
            }

            .member-cell {
                gap: 8px;
                margin-bottom: 7px;
            }

            .avatar {
                width: 38px;
                height: 38px;
            }

            .name {
                font-size: 13px;
            }

            .member-cell small {
                font-size: 11px;
            }

            small {
                font-size: 11px;
                color: #777;
            }

            .modern-table td:nth-child(4),
            .modern-table td:nth-child(5),
            .modern-table td:nth-child(6) {
                font-size: 12px;
                color: #555;

                margin-bottom: 4px;

                display: flex;
                align-items: center;
                gap: 5px;
            }

            .status {
                margin-top: 4px;
                font-size: 10px;
                padding: 4px 8px;
            }

            .modern-table td:last-child {
                margin-top: 7px;

                display: flex;
                justify-content: flex-end;
            }

            .btn-sm {
                font-size: 11px;
                padding: 5px 8px;
                border-radius: 6px;
            }
        }


        @media (max-width: 576px) {

            .filter-label {
                font-size: 9px;
                margin-bottom: 3px;
            }

            .filter-select,
            .filter-input,
            .filter-reset-btn {
                height: 34px;
            }

            .filter-select,
            .filter-input {
                font-size: 10px !important;
            }

            .filter-action-field {
                margin-top: 1px;
            }

            .filter-label-hidden {
                display: none;
            }

            .filter-reset-btn {
                margin-top: 4px;
            }

            .add-meeting-btn {
                height: 34px;
                padding: 0 9px 0 4px;
                gap: 5px;
                font-size: 10px;
            }

            .add-meeting-icon {
                width: 25px;
                height: 25px;
                flex-basis: 25px;
                font-size: 9px;
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
                <div class="row p-2">
                    <div class="col-md-5 col-6">
                        <h5>My F2F:</h5>
                    </div>
                    <div class="col-md-7 col-6">
                        <button type="button"
                            class="add-meeting-btn" style="float: right;"
                            onclick="add_f2f();">
                            <span class="add-meeting-icon">
                                <i class="fa fa-plus"></i>
                            </span>
                            <span class="add-meeting-text">
                                Submit F2F
                            </span>
                        </button>
                    </div>
                </div>
                <div class="row g-2 mb-3">

                    <!-- ALL -->
                    <div class="col-lg-3 col-md-3 col-6">
                        <div class="state-card all-card">

                            <div class="state-icon">
                                <i class="fa fa-users"></i>
                            </div>

                            <div class="state-content">
                                <span>All</span>
                                <h3><?= $allCount ?? 0 ?></h3>
                            </div>

                        </div>
                    </div>


                    <!-- THIS WEEK -->
                    <div class="col-lg-3 col-md-3 col-6">
                        <div class="state-card week-card">

                            <div class="state-icon">
                                <i class="fa fa-calendar-week"></i>
                            </div>

                            <div class="state-content">
                                <span>This Week</span>
                                <h3><?= $weekCount ?? 0 ?></h3>
                            </div>

                        </div>
                    </div>


                    <!-- CURRENT MONTH -->
                    <div class="col-lg-3 col-md-3 col-6">
                        <div class="state-card month-card">

                            <div class="state-icon">
                                <i class="fa fa-calendar"></i>
                            </div>

                            <div class="state-content">
                                <span>Current Month</span>
                                <h3><?= $monthCount ?? 0 ?></h3>
                            </div>

                        </div>
                    </div>


                    <!-- LAST MONTH -->
                    <div class="col-lg-3 col-md-3 col-6">
                        <div class="state-card last-month-card">

                            <div class="state-icon">
                                <i class="fa fa-history"></i>
                            </div>

                            <div class="state-content">
                                <span>Last Month</span>
                                <h3><?= $lastMonthCount ?? 0 ?></h3>
                            </div>

                        </div>
                    </div>

                </div>
                <style>
                    .state-card {
                        display: flex;
                        align-items: center;
                        gap: 10px;

                        min-height: 68px;
                        padding: 10px 12px;

                        background: #fff;
                        border-radius: 10px;

                        border: 1px solid #edf0f2;

                        box-shadow: 0 3px 12px rgba(0, 0, 0, .06);

                        transition: all .2s ease;
                    }

                    .state-card:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 6px 16px rgba(0, 0, 0, .10);
                    }

                    .state-icon {
                        width: 38px;
                        height: 38px;

                        flex: 0 0 38px;

                        display: flex;
                        align-items: center;
                        justify-content: center;

                        border-radius: 9px;

                        font-size: 15px;
                    }

                    .state-content {
                        min-width: 0;
                    }

                    .state-content span {
                        display: block;

                        color: #68757d;

                        font-size: 11px;
                        font-weight: 600;

                        line-height: 1.2;
                    }

                    .state-content h3 {
                        margin: 2px 0 0;

                        color: #174F70;

                        font-size: 20px;
                        font-weight: 700;

                        line-height: 1;
                    }


                    /* ALL */

                    .all-card .state-icon {
                        background: #e8f1f7;
                        color: #174F70;
                    }


                    /* THIS WEEK */

                    .week-card .state-icon {
                        background: #fff3d6;
                        color: #d89000;
                    }


                    /* CURRENT MONTH */

                    .month-card .state-icon {
                        background: #e7f7ed;
                        color: #198754;
                    }


                    @media (max-width: 576px) {

                        .state-card {
                            min-height: 60px;
                            padding: 8px;

                            gap: 7px;
                            border-radius: 8px;
                        }

                        .state-icon {
                            width: 30px;
                            height: 30px;
                            flex-basis: 30px;
                            font-size: 12px;
                            border-radius: 7px;
                        }

                        .state-content span {
                            font-size: 9px;
                        }

                        .state-content h3 {
                            font-size: 17px;
                        }
                    }
                </style>
                <div class="col-md-12 col-12">
                    <div class="card border shadow-sm filter-bar">
                        <div class="card-body p-2">
                            <form id="search-form" method="POST">
                                <div class="row g-2 align-items-center">
                                    <!-- SEARCH -->
                                    <div class="col-md-4 col-6">
                                        <label class="fw-bold">Search</label>
                                        <input type="text" id="search" name="search"
                                            class="form-control" placeholder="Search ...">
                                    </div>
                                    <!-- STATUS -->
                                    <div class="col-md-2 col-6">
                                        <label class="fw-bold">Status</label>
                                        <select class="form-select" id="status1" name="status">
                                            <option value="">Select Status</option>
                                            <option value="scheduled">Scheduled</option>
                                            <option value="completed">Completed</option>
                                            <option value="re-scheduled">Re-scheduled</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>

                                    <!-- MEMBER -->
                                    <div class="col-md-2 col-6">
                                        <label class="fw-bold">Member With👤</label>
                                        <select name="to_user" class="form-select" id="to_user">
                                            <option value="">-- Select Member --</option>
                                            <?php foreach ($member as $row): ?>
                                                <option value="<?= esc($row['user_code']) ?>">
                                                    <?= esc($row['name']) ?> (<?= esc($row['chapter_name']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <!-- DATE -->
                                    <div class="col-md-2 col-6">
                                        <label class="fw-bold">Date</label>
                                        <input type="date" id="date" name="date"
                                            class="form-control">
                                    </div>
                                    <!-- RESET -->
                                    <div class="col-md-2 col-12">
                                        <label class="fw-bold"> </label>
                                        <button type="button"
                                            id="reset-btn"
                                            class="btn btn-sm btn-outline-secondary w-100">
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-1">
                    <!-- ================= TABS ================= -->
                    <div class="f2f-tabs mb-3">
                        <a href="<?= current_url() ?>?tab=initiated_by_me"
                            class="tab-btn <?= ($tab == 'initiated_by_me') ? 'active' : '' ?>">
                            <i class="fa fa-user"></i> Initiated By Me
                        </a>
                        <a href="<?= current_url() ?>?tab=initiated_with_me"
                            class="tab-btn <?= ($tab == 'initiated_with_me') ? 'active' : '' ?>">
                            <i class="fa fa-users"></i> Initiated With Me
                        </a>

                    </div>
                    <!-- ================= TABLE ================= -->
                    <div class="modern-table-wrapper">
                        <table class="modern-table">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>From Member</th>
                                    <th>To Member</th>
                                    <th>Location</th>
                                    <th>Meeting Date</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="old_results">
                                <?php if (!empty($connect)): ?>

                                    <?php
                                    $page = $pager->getCurrentPage('default') ?? 1;
                                    $i = 1 + ($perPage * ($page - 1));
                                    ?>

                                    <?php foreach ($connect as $row): ?>

                                        <?= view('include/member/card/f2f_card.php', [
                                            'row' => $row,
                                            'i'   => $i
                                        ]) ?>

                                        <?php $i++; ?>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center justify-content-center py-3">
                                                <div style="width:54px; height:54px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#64748b; font-size:24px; margin-bottom:12px;">
                                                    <i class="ri-calendar-event-line"></i>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-1" style="font-size:15px;">No F2F Meetings Found</h6>
                                                <p class="text-muted mb-0" style="font-size:13px;">There are no One-to-One records found in this section.</p>
                                            </div>
                                        </td>
                                    </tr>

                                <?php endif; ?>
                            </tbody>

                            <tbody id="list_data"></tbody>
                        </table>
                    </div>

                    <!-- ================= PAGINATION ================= -->
                    <div class="row mt-3">
                        <div class="d-flex justify-content-center">
                            <?= $pager->links('default', 'default_full') ?>
                        </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php echo view('include/member/popup/f2f.php') ?>
    <script>
        $(document).ready(function() {

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
                    status: $('#status1').val(),
                    date: $('#date').val(),
                    to_user: $('#to_user').val(),
                    tab: '<?= esc($tab ?? 'initiated_by_me') ?>'
                };
            }

            function saveFilters() {
                localStorage.setItem('userFilters', JSON.stringify(collectFilters()));
            }

            function loadFilters() {
                let filters = localStorage.getItem('userFilters');
                if (!filters) return null;

                filters = JSON.parse(filters);

                $('#search').val(filters.search || '');
                $('#status1').val(filters.status || '');
                $('#date').val(filters.date || '');
                $('#to_user').val(filters.to_user || '');

                return filters;
            }

            const loadUsers = debounce(function() {

                let formData = collectFilters();
                saveFilters();

                let hasFilter = (formData.search !== "" || formData.status !== "" || formData.date !== "" || formData.to_user !== "");

                if (!hasFilter) {
                    $('#list_data').hide().html('');
                    $('#old_results').show();
                    localStorage.removeItem('userFilters');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: '<?= base_url("member/f2f/get_filtered_data") ?>',
                    data: formData,
                    dataType: 'html',

                    beforeSend: function() {
                        $('#list_data').show().html('<tr><td colspan="8" class="text-center py-5 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div> Loading meetings...</td></tr>');
                        $('#old_results').hide();
                    },

                    success: function(res) {
                        $('#list_data').html(res).show();
                    },

                    error: function(xhr) {
                        console.log(xhr.responseText);
                        $('#list_data').html('<tr><td colspan="8" class="text-center py-5 text-danger">Server Error. Please try again.</td></tr>');
                    }
                });

            }, 400);

            // ✅ EVENTS
            $('#search, #status1, #date, #to_user').on('keyup change', loadUsers);

            // ✅ RESET
            $('#reset-btn').click(function() {
                $('#search').val('');
                $('#status1').val('');
                $('#date').val('');
                $('#to_user').val('');

                $('#list_data').hide().html('');
                $('#old_results').show();

                localStorage.removeItem('userFilters');
            });

            // ✅ LOAD SAVED
            let saved = loadFilters();
            if (saved && Object.values(saved).some(v => v)) {
                loadUsers();
            } else {
                $('#list_data').hide();
                $('#old_results').show();
            }

        });
    </script>
</body>

</html>