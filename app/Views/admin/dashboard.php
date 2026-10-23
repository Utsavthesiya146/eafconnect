<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Enterprise Admin Dashboard - EAF Connect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
    <link rel="stylesheet" href="<?= base_url('assets2/css/eaf-design-system.css'); ?>?v=5.0">
    <style>
        :root {
            --theme: #101B35;
            --theme-light: #FAF9F6;
            --theme-soft: rgba(198, 166, 103, .15);
            --theme-gradient: linear-gradient(135deg, #101B35, #172B50);
            --theme-gold: #C6A667;
        }

        .dash-card {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .06);
        }

        .stat-box {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 15px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid #08426b;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .05);
            transition: .25s ease;
            height: 100%;
        }

        .stat-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
        }

        /* ICON */
        .stat-box i {
            font-size: 20px;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--theme-gradient);
            color: #fff;
            flex-shrink: 0;

        }

        /* TEXT AREA */
        .stat-box .stat-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .stat-box small {
            font-size: 12px;
            color: #888;
        }

        .stat-box h5 {
            margin: 2px 0 0;
            font-weight: 700;
            color: #222;
        }


        /* ===================================
   PROFILE PROGRESS BAR
=================================== */
        .profile-progress {
            height: 6px;
            border-radius: 10px;
            background: #f1f3ff;
            overflow: hidden;
        }

        .profile-progress .progress-bar {
            background: var(--theme-gradient);
            transition: width 1.5s ease-in-out;
        }


        /* ===================================
   MEETING ROW ALIGNMENT
=================================== */
        .meeting-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 14px 16px;
            border-radius: 12px;
            background: #fff;
            margin-bottom: 10px;
            border: 1px solid #eee;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .05);
            transition: .25s ease;
            flex-wrap: wrap;
        }

        .meeting-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        }

        .col-block {
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .col-block.number {
            font-weight: 600;
            color: var(--theme);
            min-width: 45px;
        }

        .col-block i {
            color: var(--theme);
        }


        /* ===================================
   WEEKLY CARD
=================================== */
        .weekly-card {
            border-radius: 14px;
            transition: .3s;
        }

        .weekly-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
        }


        /* ===================================
   REPORT FILTER TABS (FIXED ALIGNMENT)
=================================== */
        .custom-report-tabs {
            border: none;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .custom-report-tabs .nav-link {
            border: none;
            background: #f5f6ff;
            color: var(--theme);
            font-weight: 600;
            padding: 6px 18px;
            border-radius: 25px;
            transition: .3s;
        }

        .custom-report-tabs .nav-link:hover {
            background: rgba(207, 31, 46, .12);
        }

        .custom-report-tabs .nav-link.active {
            background: #08426b;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(40, 172, 66, 0.35);
        }

        /* remove bootstrap border */
        .nav-tabs .nav-link:focus,
        .nav-tabs .nav-link:hover {
            border: none;
        }


        /* ===================================
   MOBILE FIX
=================================== */
        @media(max-width:768px) {

            .meeting-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .stat-box {
                padding: 12px;
            }

            .custom-report-tabs {
                justify-content: center;
                flex-wrap: wrap;
            }
        }

        /* ===== Section Title ===== */
        .chapter-title {
            color: #08426b;
            font-weight: 700;
        }

        /* ===== KPI Cards ===== */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 18px 14px;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            transition: all .3s ease;
            overflow: hidden;
        }

        /* left gradient bar */
        .stat-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 6px;
            border-radius: 14px 0 0 14px;
        }

        /* hover effect */
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
        }

        /* label */
        .stat-card span {
            font-size: 11px;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* value */
        .stat-card h3 {
            margin-top: 8px;
            font-size: 20px;
            font-weight: 700;
            color: #212529;
        }

        /* ===== Color Variants ===== */
        .stat-primary::before {
            background: linear-gradient(180deg, #4e73df, #224abe);
        }

        .stat-success::before {
            background: linear-gradient(180deg, #1cc88a, #13855c);
        }

        .stat-warning::before {
            background: linear-gradient(180deg, #f6c23e, #dda20a);
        }

        .stat-info::before {
            background: linear-gradient(180deg, #36b9cc, #258391);
        }

        .stat-secondary::before {
            background: linear-gradient(180deg, #858796, #60616f);
        }

        .stat-dark::before {
            background: linear-gradient(180deg, #343a40, #121416);
        }

        /* Responsive improvement */
        @media (max-width:768px) {
            .stat-card h3 {
                font-size: 20px;
            }
        }

        .report-card {
            border: 0;
            border-radius: 14px;
            padding: 10px 20px;
            background: #fff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .report-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.12);
        }

        .report-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
        }

        /* Theme colors */
        .bg-theme {
            background: #08426b;
        }

        .bg-theme-light {
            background: #ffecee;
            color: #08426b;
        }

        .report-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 0;
        }

        .report-arrow {
            font-size: 20px;
            color: #999;
        }

        /* =========================
   MOBILE UI OPTIMIZATION
=========================*/
        @media (max-width: 768px) {

            /* Reduce overall spacing */
            .container,
            .container-fluid {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }

            .row {
                --bs-gutter-x: 10px;
                --bs-gutter-y: 10px;
            }

            /* Dashboard Cards */
            .dash-card,
            .weekly-card {
                border-radius: 10px;
                margin-bottom: 10px;
            }

            /* Stat Boxes */
            .stat-box {
                padding: 10px;
                gap: 10px;
                border-radius: 10px;
                min-height: 70px;
            }

            .stat-box i {
                width: 38px;
                height: 38px;
                font-size: 16px;
                border-radius: 8px;
            }

            .stat-box small {
                font-size: 11px;
                line-height: 1.2;
            }

            .stat-box h5 {
                font-size: 15px;
                margin: 0;
            }

            /* KPI Cards */
            .stat-card {
                padding: 12px 10px;
                border-radius: 10px;
            }

            .stat-card span {
                font-size: 10px;
            }

            .stat-card h3 {
                font-size: 18px;
                margin-top: 5px;
            }

            /* Report Cards */
            .report-card {
                padding: 12px;
                border-radius: 10px;
                gap: 10px;
            }

            .report-icon {
                width: 42px;
                height: 42px;
                font-size: 18px;
                border-radius: 10px;
                flex-shrink: 0;
            }

            .report-title {
                font-size: 14px;
                line-height: 1.3;
            }

            .report-arrow {
                font-size: 16px;
            }

            /* Meeting Row Mobile Card */
            .meeting-row {
                display: block;
                padding: 12px;
                border-radius: 10px;
            }

            .meeting-row .col-block {
                width: 100%;
                padding: 4px 0;
                font-size: 13px;
                word-break: break-word;
            }

            .meeting-row .col-block.number {
                padding-bottom: 8px;
                border-bottom: 1px solid #eee;
                margin-bottom: 6px;
            }

            /* Tabs */
            .custom-report-tabs {
                justify-content: center;
                flex-wrap: wrap;
                gap: 6px;
            }

            .custom-report-tabs .nav-link {
                padding: 6px 12px;
                font-size: 12px;
            }

            /* Titles */
            .chapter-title {
                font-size: 18px;
                text-align: center;
                margin-bottom: 12px;
            }

            /* Prevent Overflow */
            .card,
            .dash-card,
            .weekly-card,
            .report-card,
            .meeting-row {
                overflow: hidden;
            }

            /* Long Text Fix */
            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            p,
            span,
            div {
                word-wrap: break-word;
                overflow-wrap: break-word;
            }
        }

        /* Extra Small Devices */
        @media (max-width: 576px) {

            .stat-box {
                min-height: auto;
            }

            .stat-box h5 {
                font-size: 14px;
            }

            .stat-card h3 {
                font-size: 16px;
            }

            .report-title {
                font-size: 13px;
            }

            .meeting-row {
                padding: 10px;
            }
        }
    </style>
    <style>
        :root {
            --crm-primary: #0a3d61;
            --crm-primary-light: #edf5f9;
            --crm-text: #263238;
            --crm-muted: #78909c;
            --crm-border: #e5ebef;
            --crm-success: #16865c;
            --crm-danger: #d9534f;
            --crm-warning: #d99100;
        }


        /* =========================================
   MAIN CARD
========================================= */

        .crm-meeting-card {
            background: #fff;
            border: 1px solid var(--crm-border);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 14px rgba(10, 61, 97, .05);
        }


        /* =========================================
   HEADER
========================================= */

        .crm-meeting-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 17px 20px;
            border-bottom: 1px solid var(--crm-border);
            background: #fff;
        }

        .crm-meeting-title {
            margin: 0;
            color: var(--crm-text);
            font-size: 16px;
            font-weight: 700;
        }

        .crm-meeting-title i {
            color: var(--crm-primary);
            margin-right: 7px;
        }

        .crm-meeting-subtitle {
            display: block;
            color: var(--crm-muted);
            font-size: 11px;
            margin-top: 4px;
        }

        .crm-view-all {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: var(--crm-primary);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
        }

        .crm-view-all:hover {
            color: var(--crm-primary);
            text-decoration: none;
        }

        .crm-view-all i {
            transition: transform .2s ease;
        }

        .crm-view-all:hover i {
            transform: translateX(3px);
        }


        /* =========================================
   MEETING LIST
========================================= */

        .crm-meeting-list {
            padding: 0;
        }


        /* =========================================
   MEETING ITEM
========================================= */

        .crm-meeting-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-bottom: 1px solid #eef2f5;
            position: relative;
            transition: all .2s ease;
        }

        .crm-meeting-item:last-child {
            border-bottom: 0;
        }

        .crm-meeting-item:hover {
            background: #f9fbfc;
        }


        /* =========================================
   NUMBER
========================================= */

        .crm-meeting-number {
            width: 30px;
            min-width: 30px;
            height: 30px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--crm-primary-light);
            color: var(--crm-primary);
            font-size: 11px;
            font-weight: 700;
        }


        /* =========================================
   DATE
========================================= */

        .crm-meeting-date {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 105px;
        }

        .crm-date-day {
            width: 38px;
            height: 42px;
            border-radius: 7px;
            background: var(--crm-primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
        }

        .crm-date-info {
            display: flex;
            flex-direction: column;
        }

        .crm-date-info strong {
            color: var(--crm-text);
            font-size: 11px;
            font-weight: 700;
        }

        .crm-date-info small {
            color: var(--crm-muted);
            font-size: 10px;
            margin-top: 3px;
        }

        .crm-date-info small i {
            margin-right: 2px;
        }


        /* =========================================
   MEETING INFO
========================================= */

        .crm-meeting-info {
            min-width: 150px;
            flex: 1;
        }

        .crm-meeting-name {
            color: var(--crm-text);
            font-size: 13px;
            font-weight: 700;
            line-height: 1.35;
        }

        .crm-meeting-description {
            color: var(--crm-muted);
            font-size: 10px;
            margin-top: 3px;

            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .crm-meeting-chapter {
            color: var(--crm-primary);
            font-size: 10px;
            font-weight: 600;
            margin-top: 5px;
        }

        .crm-meeting-chapter i {
            margin-right: 3px;
        }


        /* =========================================
   LOCATION
========================================= */

        .crm-meeting-location {
            min-width: 100px;
            max-width: 130px;
        }

        .crm-meeting-location>div {
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--crm-text);
            font-size: 10px;
            font-weight: 600;
        }

        .crm-meeting-location>div i {
            color: var(--crm-primary);
        }

        .crm-meeting-location>div span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .crm-meeting-location small {
            display: block;
            color: var(--crm-muted);
            font-size: 9px;
            margin-top: 4px;
            padding-left: 15px;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        /* =========================================
   MODE
========================================= */

        .crm-meeting-mode {
            min-width: 75px;
        }

        .crm-mode {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .crm-mode.online {
            background: #e8f7f0;
            color: var(--crm-success);
        }

        .crm-mode.offline {
            background: #fff5df;
            color: var(--crm-warning);
        }

        .crm-mode.hybrid {
            background: #edf1f8;
            color: var(--crm-primary);
        }

        .crm-mode i {
            font-size: 9px;
        }


        /* =========================================
   REGISTRATION
========================================= */

        .crm-meeting-registration {
            display: flex;
            align-items: center;
            gap: 7px;
            min-width: 75px;
        }

        .crm-registration-icon {
            width: 29px;
            height: 29px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #edf5f9;
            color: var(--crm-primary);
            font-size: 12px;
        }

        .crm-meeting-registration div:last-child {
            display: flex;
            flex-direction: column;
        }

        .crm-meeting-registration strong {
            color: var(--crm-text);
            font-size: 12px;
        }

        .crm-meeting-registration small {
            color: var(--crm-muted);
            font-size: 9px;
        }


        /* =========================================
   JOIN BUTTON
========================================= */

        .crm-meeting-action {
            min-width: 55px;
        }

        .crm-join-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 10px;
            border-radius: 6px;
            background: var(--crm-primary);
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s ease;
        }

        .crm-join-btn:hover {
            background: #082f4a;
            color: #fff;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .crm-no-link {
            color: #b0bec5;
            font-size: 13px;
        }


        /* =========================================
   ACTION BUTTONS
========================================= */

        .crm-meeting-actions {
            display: flex;
            gap: 5px;
        }

        .crm-action-btn {
            width: 29px;
            height: 29px;
            padding: 0;
            border: 0;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .2s ease;
        }

        .crm-action-btn.edit {
            background: #fff5df;
            color: var(--crm-warning);
        }

        .crm-action-btn.delete {
            background: #fff0ef;
            color: var(--crm-danger);
        }

        .crm-action-btn:hover {
            transform: translateY(-2px);
        }

        .crm-action-btn.edit:hover {
            background: var(--crm-warning);
            color: #fff;
        }

        .crm-action-btn.delete:hover {
            background: var(--crm-danger);
            color: #fff;
        }


        /* =========================================
   EMPTY STATE
========================================= */

        .crm-empty-state {
            text-align: center;
            padding: 45px 20px;
        }

        .crm-empty-icon {
            width: 55px;
            height: 55px;
            margin: 0 auto 12px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--crm-primary-light);
            color: var(--crm-primary);
            font-size: 22px;
        }

        .crm-empty-state h6 {
            color: var(--crm-text);
            font-size: 13px;
            margin-bottom: 4px;
        }

        .crm-empty-state p {
            color: var(--crm-muted);
            font-size: 11px;
            margin: 0;
        }


        /* =========================================
   MOBILE
========================================= */

        @media (max-width: 991px) {

            .crm-meeting-location {
                display: none;
            }

            .crm-meeting-mode {
                display: none;
            }

            .crm-meeting-registration {
                display: none;
            }

        }


        @media (max-width: 767px) {

            .crm-meeting-header {
                padding: 14px 15px;
            }

            .crm-meeting-title {
                font-size: 14px;
            }

            .crm-meeting-subtitle {
                font-size: 10px;
            }

            .crm-meeting-item {
                padding: 12px;
                gap: 9px;
            }

            .crm-meeting-number {
                display: none;
            }

            .crm-meeting-date {
                min-width: 80px;
            }

            .crm-date-day {
                width: 34px;
                height: 38px;
                font-size: 14px;
            }

            .crm-date-info strong {
                font-size: 10px;
            }

            .crm-date-info small {
                font-size: 9px;
            }

            .crm-meeting-name {
                font-size: 11px;
            }

            .crm-meeting-description {
                display: none;
            }

            .crm-meeting-chapter {
                font-size: 9px;
            }

            .crm-meeting-action {
                min-width: auto;
            }

            .crm-join-btn {
                padding: 6px 8px;
                font-size: 9px;
            }

            .crm-meeting-actions {
                gap: 3px;
            }

            .crm-action-btn {
                width: 27px;
                height: 27px;
                font-size: 10px;
            }

        }
    </style>
</head>

<body data-sidebar="colored">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php echo view('include/admin/header.php') ?>
        <?php echo view('include/admin/sidebar.php') ?>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid py-1">
                    <!-- Header -->
                    <h4 class="fw-semibold mb-1">Hello <?= session()->get('name'); ?></h4>
                    <div class="row g-2">
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <h5 class="fw-bold text-danger mb-0">
                                <i class="bi bi-graph-up-arrow me-2"></i>
                                MITB Performance
                            </h5>

                            <ul class="nav nav-tabs custom-report-tabs">

                                <li class="nav-item">
                                    <a class="nav-link <?= ($filter == '15') ? 'active' : '' ?>"
                                        href="<?= current_url() . '?filter=15' ?>">
                                        15 Days
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($filter == '180') ? 'active' : '' ?>"
                                        href="<?= current_url() . '?filter=180' ?>">
                                        6 Months
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?= ($filter == '365') ? 'active' : '' ?>"
                                        href="<?= current_url() . '?filter=365' ?>">
                                        12 Months
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?= ($filter == 'all') ? 'active' : '' ?>"
                                        href="<?= current_url() . '?filter=all' ?>">
                                        Lifetime
                                    </a>
                                </li>

                            </ul>

                        </div>
                        <!-- ===== Chart ===== -->

                        <!-- ===== Stats ===== -->
                        <style>
                            :root {
                                --crm-primary: #0a3d61;
                                --crm-primary-light: #eaf3f8;
                                --crm-text: #263238;
                                --crm-muted: #78909c;
                                --crm-border: #e6edf2;
                            }

                            /* CRM Metrics Grid */
                            .crm-metrics-row {
                                row-gap: 16px;
                            }

                            .crm-metric-card {
                                display: flex;
                                align-items: center;
                                gap: 14px;
                                position: relative;
                                background: #fff;
                                border: 1px solid var(--crm-border);
                                border-radius: 10px;
                                padding: 17px 18px;
                                min-height: 92px;
                                text-decoration: none !important;
                                overflow: hidden;
                                transition: all .25s ease;
                                box-shadow: 0 2px 8px rgba(10, 61, 97, 0.04);
                            }

                            .crm-metric-card::before {
                                content: "";
                                position: absolute;
                                left: 0;
                                top: 0;
                                bottom: 0;
                                width: 3px;
                                background: var(--crm-primary);
                                opacity: .9;
                            }

                            .crm-metric-card:hover {
                                transform: translateY(-3px);
                                border-color: rgba(10, 61, 97, .18);
                                box-shadow: 0 8px 22px rgba(10, 61, 97, 0.10);
                            }

                            /* Icon */
                            .crm-metric-icon {
                                width: 48px;
                                height: 48px;
                                min-width: 48px;
                                border-radius: 10px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                background: var(--crm-primary-light);
                                color: var(--crm-primary);
                                font-size: 21px;
                                transition: all .25s ease;
                            }

                            .crm-metric-card:hover .crm-metric-icon {
                                background: var(--crm-primary);
                                color: #fff;
                            }

                            /* Content */
                            .crm-metric-content {
                                min-width: 0;
                                flex: 1;
                            }

                            .crm-metric-label {
                                display: block;
                                color: var(--crm-muted);
                                font-size: 12px;
                                font-weight: 600;
                                line-height: 1.3;
                                margin-bottom: 4px;
                                text-transform: uppercase;
                                letter-spacing: .3px;
                            }

                            .crm-metric-value {
                                display: block;
                                color: var(--crm-text);
                                font-size: 21px;
                                font-weight: 700;
                                line-height: 1.2;
                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                            }

                            /* Arrow */
                            .crm-metric-arrow {
                                color: #b0bec5;
                                font-size: 12px;
                                transition: all .25s ease;
                            }

                            .crm-metric-card:hover .crm-metric-arrow {
                                color: var(--crm-primary);
                                transform: translateX(3px);
                            }

                            /* Small initiated number */
                            .crm-initiated {
                                color: var(--crm-primary);
                                font-weight: 700;
                                font-size: 11px;
                            }

                            /* Mobile */
                            @media (max-width: 767px) {

                                .crm-metric-card {
                                    min-height: 82px;
                                    padding: 13px 14px;
                                    gap: 11px;
                                    border-radius: 9px;
                                }

                                .crm-metric-icon {
                                    width: 42px;
                                    height: 42px;
                                    min-width: 42px;
                                    font-size: 18px;
                                    border-radius: 8px;
                                }

                                .crm-metric-label {
                                    font-size: 10px;
                                    margin-bottom: 3px;
                                }

                                .crm-metric-value {
                                    font-size: 18px;
                                }

                                .crm-metric-arrow {
                                    display: none;
                                }
                            }
                        </style>


                        <div class="row crm-metrics-row">

                            <!-- MITB -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <a href="<?= base_url(); ?>admin/mitb" class="crm-metric-card">

                                    <div class="crm-metric-icon">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>

                                    <div class="crm-metric-content">
                                        <span class="crm-metric-label">MITB Received</span>

                                        <span class="crm-metric-value">
                                            ₹<?= (new NumberFormatter(
                                                    'en_IN',
                                                    NumberFormatter::DECIMAL
                                                ))->format($totalMITB ?? 0, 2); ?>
                                        </span>
                                    </div>

                                    <i class="bi bi-chevron-right crm-metric-arrow"></i>

                                </a>
                            </div>
                            <!-- Notifications -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <a href="<?= base_url(); ?>admin/notifications" class="crm-metric-card">

                                    <div class="crm-metric-icon">
                                        <i class="bi bi-bell-fill"></i>
                                    </div>

                                    <div class="crm-metric-content">
                                        <span class="crm-metric-label">Referral Value</span>

                                        <span class="crm-metric-value">
                                             ₹<?= (new NumberFormatter(
                                                    'en_IN',
                                                    NumberFormatter::DECIMAL
                                                ))->format($averageMemberValue ?? 0, 2); ?>
                                        </span>
                                    </div>

                                    <i class="bi bi-chevron-right crm-metric-arrow"></i>

                                </a>
                            </div>

                            <!-- Visitors -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <a href="<?= base_url(); ?>admin/visitor" class="crm-metric-card">

                                    <div class="crm-metric-icon">
                                        <i class="bi bi-people-fill"></i>
                                    </div>

                                    <div class="crm-metric-content">
                                        <span class="crm-metric-label">Visitors</span>

                                        <span class="crm-metric-value">
                                            <?= $visitors_c ?? 0; ?>
                                        </span>
                                    </div>

                                    <i class="bi bi-chevron-right crm-metric-arrow"></i>

                                </a>
                            </div>


                            <!-- Referral -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <a href="<?= base_url(); ?>admin/connect" class="crm-metric-card">

                                    <div class="crm-metric-icon">
                                        <i class="bi bi-send-fill"></i>
                                    </div>

                                    <div class="crm-metric-content">
                                        <span class="crm-metric-label">Connects Given</span>

                                        <span class="crm-metric-value">
                                            <?= $r_g_c ?? 0; ?>
                                        </span>
                                    </div>

                                    <i class="bi bi-chevron-right crm-metric-arrow"></i>

                                </a>
                            </div>


                            <!-- Meetings -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <a href="<?= base_url(); ?>admin/meetings" class="crm-metric-card">

                                    <div class="crm-metric-icon">
                                        <i class="bi bi-calendar-check-fill"></i>
                                    </div>

                                    <div class="crm-metric-content">
                                        <span class="crm-metric-label">Meetings</span>

                                        <span class="crm-metric-value">
                                            <?= $meetingsCount ?? 0; ?>
                                        </span>
                                    </div>

                                    <i class="bi bi-chevron-right crm-metric-arrow"></i>

                                </a>
                            </div>


                            <!-- F2F -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <a href="<?= base_url(); ?>admin/f2f" class="crm-metric-card">

                                    <div class="crm-metric-icon">
                                        <i class="bi bi-person-up"></i>
                                    </div>

                                    <div class="crm-metric-content">
                                        <span class="crm-metric-label">
                                            F2F
                                            <span class="crm-initiated">
                                                Initiated
                                            </span>
                                        </span>

                                        <span class="crm-metric-value">
                                            <?= $f2f__sc ?? 0; ?>
                                        </span>
                                    </div>

                                    <i class="bi bi-chevron-right crm-metric-arrow"></i>

                                </a>
                            </div>


                            <!-- Chapters -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <a href="<?= base_url(); ?>admin/chapter" class="crm-metric-card">

                                    <div class="crm-metric-icon">
                                        <i class="bi bi-diagram-3-fill"></i>
                                    </div>

                                    <div class="crm-metric-content">
                                        <span class="crm-metric-label">Chapters</span>

                                        <span class="crm-metric-value">
                                            <?= $chapters_c ?? 0; ?>
                                        </span>
                                    </div>

                                    <i class="bi bi-chevron-right crm-metric-arrow"></i>

                                </a>
                            </div>


                            <!-- Chapter Members -->
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                <a href="<?= base_url(); ?>admin/chapter" class="crm-metric-card">

                                    <div class="crm-metric-icon">
                                        <i class="bi bi-person-vcard-fill"></i>
                                    </div>

                                    <div class="crm-metric-content">
                                        <span class="crm-metric-label">Chapter Members</span>

                                        <span class="crm-metric-value">
                                            <?= $member_c ?? 0; ?>
                                        </span>
                                    </div>

                                    <i class="bi bi-chevron-right crm-metric-arrow"></i>

                                </a>
                            </div>




                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-6">
                            <div class="card dash-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-semibold mb-0">
                                            <i class="bi bi-bar-chart-line-fill me-2"></i>
                                            MITB Report
                                        </h6>
                                        <select id="mitbFilter" class="form-select form-select-sm w-auto">
                                            <option value="7" <?= ($filter == '7') ? 'selected' : '' ?>>Last 7 Days</option>
                                            <option value="30" <?= ($filter == '30') ? 'selected' : '' ?>>1 Month</option>
                                            <option value="180" <?= ($filter == '180') ? 'selected' : '' ?>>6 Months</option>
                                            <option value="all" <?= ($filter == 'all') ? 'selected' : '' ?>>Lifetime</option>
                                        </select>
                                    </div>
                                    <canvas id="weeklyMITBChart2" height="130"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="crm-meeting-card">

                                <!-- Header -->
                                <div class="crm-meeting-header">
                                    <div>
                                        <h5 class="crm-meeting-title">
                                            <i class="bi bi-calendar2-week"></i>
                                            Upcoming Meeting Details
                                        </h5>
                                        <span class="crm-meeting-subtitle">
                                            Latest meetings and activities
                                        </span>
                                    </div>

                                    <a href="<?= base_url(); ?>admin/meetings"
                                        class="crm-view-all">
                                        View All
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>

                                <!-- Meeting List -->
                                <div class="crm-meeting-list">

                                    <?php if (!empty($events)): ?>

                                        <?php $i = 1; ?>

                                        <?php foreach ($events as $row): ?>

                                            <?= view('include/admin/card/event_card2.php', [
                                                'row' => $row,
                                                'i'   => $i
                                            ]) ?>

                                            <?php $i++; ?>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <div class="crm-empty-state">
                                            <div class="crm-empty-icon">
                                                <i class="bi bi-calendar-x"></i>
                                            </div>

                                            <h6>No Meetings Found</h6>

                                            <p>
                                                There are no Upcoming meetings to display.
                                            </p>
                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="card p-4">
                                <h5>Chapter Performance</h5>
                                <canvas id="chapterChart" height="200"></canvas>

                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                <script>
                                    const ctx = document.getElementById('chapterChart').getContext('2d');

                                    new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: <?= $chapterLabels ?>,

                                            datasets: [{
                                                    label: 'Members',
                                                    data: <?= $chapterMembers ?>,
                                                    backgroundColor: '#777edd'
                                                },
                                                {
                                                    label: 'Connects',
                                                    data: <?= $chapterReferrals ?>,
                                                    backgroundColor: '#ed2d7b'
                                                },
                                                {
                                                    label: 'MITB ',
                                                    data: <?= $chapterMITB ?>,
                                                    backgroundColor: '#28a745'
                                                }
                                            ]
                                        },
                                        options: {
                                            responsive: true,
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo view('include/admin/footer.php') ?>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php echo view('include/admin/popup/meeting.php') ?>

    <!-- ===========================================
FIX ALL ERRORS IN YOUR VIEW FILE
Replace bottom scripts with this full code
=========================================== -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        /* =====================================================
   CHART 1 : Dashboard Multi Report
===================================================== */
        const labels = <?= $labels ?? '[]' ?>;
        const visitorData = <?= $visitorData ?? '[]' ?>;
        const referralData = <?= $referralData ?? '[]' ?>;
        const meetingData = <?= $meetingData ?? '[]' ?>;
        const f2fData = <?= $f2fData ?? '[]' ?>;
        const memberData = <?= $memberData ?? '[]' ?>;
        const mitbData = <?= $mitbData ?? '[]' ?>;

        const chart2 = document.getElementById('weeklyMITBChart2');

        if (chart2) {
            new Chart(chart2, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Visitors',
                            data: visitorData,
                            borderColor: '#20c997',
                            tension: .4
                        },
                        {
                            label: 'Connects',
                            data: referralData,
                            borderColor: '#ffc107',
                            tension: .4
                        },
                        {
                            label: 'Meetings',
                            data: meetingData,
                            borderColor: '#0d6efd',
                            tension: .4
                        },
                        {
                            label: 'One To One',
                            data: f2fData,
                            borderColor: '#dc3545',
                            tension: .4
                        },
                        {
                            label: 'Members',
                            data: memberData,
                            borderColor: '#6f42c1',
                            tension: .4
                        },
                        {
                            label: 'MITB ',
                            data: mitbData,
                            borderColor: '#28a745',
                            tension: .4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        }
    </script>


    <script>
        /* =====================================================
   CHART 2 : OLD MITB REPORT FIXED
===================================================== */
        const chart1 = document.getElementById('weeklyMITBChart');

        if (chart1) {
            new Chart(chart1, {
                type: 'line',
                data: {
                    labels: <?= $dates ?? $labels ?? '[]' ?>,
                    datasets: [{
                            label: 'MITB  Given',
                            data: <?= $givenData ?? $mitbData ?? '[]' ?>,
                            borderColor: '#cf2030',
                            backgroundColor: 'rgba(237,45,123,.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'MITB  Received',
                            data: <?= $receivedData ?? $mitbData ?? '[]' ?>,
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40,167,69,.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>


    <script>
        /* =====================================================
   CHAPTER CHART
===================================================== */
        const chapterCanvas = document.getElementById('chapterChart');

        if (chapterCanvas) {
            new Chart(chapterCanvas, {
                type: 'bar',
                data: {
                    labels: <?= $chapterLabels ?? '[]' ?>,
                    datasets: [{
                            label: 'Members',
                            data: <?= $chapterMembers ?? '[]' ?>,
                            backgroundColor: '#777edd'
                        },
                        {
                            label: 'Connects',
                            data: <?= $chapterReferrals ?? '[]' ?>,
                            backgroundColor: '#ed2d7b'
                        },
                        {
                            label: 'MITB ',
                            data: <?= $chapterMITB ?? '[]' ?>,
                            backgroundColor: '#28a745'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>


    <script>
        /* =====================================================
   FILTER
===================================================== */
        document.getElementById('mitbFilter')?.addEventListener('change', function() {
            let value = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('filter', value);
            window.location.href = url.toString();
        });
    </script>
</body>

</html>