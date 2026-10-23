<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Connects</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets2/css/eaf-revamp.css?v=8.0" rel="stylesheet" type="text/css" />

    <style>
        /* =========================================================
   REFERRAL CRM CARD UI
   Theme: #c31a20
========================================================= */

        :root {
            --theme: #0d4769;
            --theme-dark: #0d4769;
            --theme-light: #fff2f3;
            --theme-soft: #fff8f8;

            --text-dark: #243746;
            --text: #40515e;
            --muted: #7b8992;

            --border: #e4eaee;
            --background: #f7f9fb;
            --white: #ffffff;

            --success: #198754;
            --success-bg: #eaf7ef;

            --warning: #9a6700;
            --warning-bg: #fff6dc;

            --info: #0d6efd;
            --info-bg: #eaf3ff;

            --danger: #dc3545;
            --danger-bg: #fff0f1;

            --shadow:
                0 4px 18px rgba(30, 55, 70, 0.07);

            --shadow-hover:
                0 10px 28px rgba(30, 55, 70, 0.12);
        }


        /* =========================================================
   REFERRAL CARD
========================================================= */

        .referral-card {
            position: relative;

            display: flex;
            align-items: center;
            justify-content: space-between;

            width: 100%;

            padding: 17px 20px;

            background: var(--white);

            border: 1px solid var(--border);

            border-radius: 12px;

            box-shadow: var(--shadow);

            gap: 22px;

            overflow: hidden;

            transition:
                transform .22s ease,
                box-shadow .22s ease,
                border-color .22s ease;
        }


        /* Left theme indicator */

        .referral-card::before {
            content: "";

            position: absolute;

            left: 0;
            top: 0;
            bottom: 0;

            width: 3px;

            background:
                linear-gradient(180deg,
                    var(--theme),
                    #e06b70);

            opacity: .9;
        }


        .referral-card:hover {
            transform: translateY(-2px);

            border-color: #d8e0e5;

            box-shadow: var(--shadow-hover);
        }


        /* =========================================================
   CARD SECTIONS
========================================================= */

        .card-section {
            display: flex;

            align-items: center;

            gap: 16px;

            min-width: 0;
        }


        /* =========================================================
   MEMBER
========================================================= */

        .member {
            display: flex;

            align-items: center;

            gap: 12px;

            min-width: 200px;
        }


        /* Profile Image */

        .member img {
            width: 50px;
            height: 50px;

            min-width: 50px;

            border-radius: 50%;

            object-fit: cover;

            object-position: top center;

            border: 3px solid #ffffff;

            outline: 1px solid #dfe6ea;

            background: #f4f6f8;

            transition: .2s ease;
        }


        .referral-card:hover .member img {
            outline-color: rgba(195, 26, 32, .35);
        }


        /* Member text */

        .member .name {
            margin-bottom: 3px;

            color: var(--text-dark);

            font-size: 15px;

            line-height: 1.3;

            font-weight: 700;
        }


        .member small {
            display: block;

            max-width: 190px;

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;

            color: var(--muted);

            font-size: 12px;

            line-height: 1.4;
        }


        /* =========================================================
   FLOW ARROW
========================================================= */

        .flow-arrow {
            width: 32px;
            height: 32px;

            min-width: 32px;

            display: flex;

            align-items: center;
            justify-content: center;

            background: var(--theme-light);

            color: var(--theme);

            border-radius: 50%;

            font-size: 13px;

            transition: .2s ease;
        }


        .referral-card:hover .flow-arrow {
            background: var(--theme);

            color: #ffffff;

            transform: translateX(2px);
        }


        /* =========================================================
   BUSINESS INFORMATION
========================================================= */

        .business-info {
            display: flex;

            flex-direction: column;

            align-items: flex-start;

            justify-content: center;

            min-width: 190px;

            gap: 5px;

            padding-left: 16px;

            border-left: 1px solid var(--border);
        }


        .info {
            display: flex;

            align-items: baseline;

            gap: 8px;

            width: 100%;

            font-size: 13px;

            line-height: 1.5;
        }


        .label {
            min-width: 72px;

            color: var(--muted);

            font-size: 12px;

            font-weight: 500;
        }


        .value {
            color: var(--text-dark);

            font-size: 13px;

            font-weight: 600;

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;

            max-width: 220px;
        }


        /* =========================================================
   STATUS AREA
========================================================= */

        .status-area {
            display: flex;

            flex-direction: column;

            align-items: flex-end;

            gap: 6px;
        }


        /* =========================================================
   STATUS
========================================================= */

        .status {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            min-width: 85px;

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 12px;

            line-height: 1.2;

            font-weight: 700;

            text-transform: capitalize;

            cursor: pointer;

            border: 1px solid transparent;

            transition:
                transform .18s ease,
                box-shadow .18s ease;
        }


        .status::before {
            content: "";

            width: 6px;
            height: 6px;

            border-radius: 50%;

            background: currentColor;

            opacity: .8;
        }


        .status:hover {
            transform: translateY(-1px);

            box-shadow:
                0 4px 10px rgba(0, 0, 0, .08);
        }


        /* Pending */

        .status.pending {
            background: var(--warning-bg);

            color: var(--warning);

            border-color: #f3df9d;
        }


        /* Contacted */

        .status.contacted {
            background: var(--info-bg);

            color: var(--info);

            border-color: #cfe2ff;
        }


        /* Completed */

        .status.completed {
            background: var(--success-bg);

            color: var(--success);

            border-color: #c9ead7;
        }


        /* Closed */

        .status.closed {
            background: #f0f2f4;

            color: #5e6870;

            border-color: #dfe3e6;
        }


        /* Business Done */

        .status.done {
            background: #e7f6ee;

            color: #146c43;

            border-color: #c8e8d6;
        }


        /* Not Converted */

        .status.not-converted {
            background: var(--danger-bg);

            color: var(--danger);

            border-color: #f4cbd0;
        }


        /* Cancelled */

        .status.cancelled {
            background: #f8e9eb;

            color: #842029;

            border-color: #eccfd3;
        }


        /* =========================================================
   DATE
========================================================= */

        .date {
            display: flex;

            align-items: center;

            gap: 5px;

            color: var(--muted);

            font-size: 11px;

            line-height: 1.4;

            white-space: nowrap;
        }


        .date::before {
            content: "\f017";

            font-family: "Font Awesome 5 Free";

            font-weight: 900;

            color: #9aa7af;

            font-size: 10px;
        }


        /* =========================================================
   ACTION BUTTONS
========================================================= */

        .actions {
            display: flex;

            align-items: center;

            gap: 6px;

            padding-left: 14px;

            border-left: 1px solid var(--border);
        }


        /* Action Button */

        .btn.action {
            width: 36px;
            height: 36px;

            padding: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #f7f9fa;

            color: var(--theme);

            border: 1px solid #e1e7ea;

            border-radius: 8px;

            font-size: 13px;

            transition:
                background .2s ease,
                color .2s ease,
                border-color .2s ease,
                transform .2s ease,
                box-shadow .2s ease;
        }


        .btn.action:hover {
            background: var(--theme);

            color: #ffffff;

            border-color: var(--theme);

            transform: translateY(-2px);

            box-shadow:
                0 5px 12px rgba(195, 26, 32, .18);
        }


        /* =========================================================
   MAIN CONNECT TABS
========================================================= */

        .connect-tabs {
            display: flex;

            align-items: center;

            gap: 6px;

            padding: 5px;

            background: #f1f5f7;

            border: 1px solid var(--border);

            border-radius: 10px;

            width: fit-content;
        }


        .connect-tabs .tab-btn {
            margin: 0 !important;

            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 8px 14px;

            border: 0;

            border-radius: 7px;

            color: var(--muted);

            background: transparent;

            font-size: 13px;

            font-weight: 600;

            transition: .2s ease;
        }


        .connect-tabs .tab-btn:hover {
            color: var(--theme);

            background: #ffffff;
        }


        .connect-tabs .tab-btn.active {
            background: var(--theme);

            color: #ffffff;

            box-shadow:
                0 4px 10px rgba(195, 26, 32, .16);
        }


        .connect-tabs .tab-btn .badge {
            min-width: 22px;

            padding: 3px 6px;

            border-radius: 5px;

            background: rgba(255, 255, 255, .18) !important;

            color: inherit !important;

            font-size: 10px;
        }


        /* =========================================================
   REFERRAL ITEMS
========================================================= */

        .referral-item {
            display: flex;

            width: 100%;

            margin-bottom: 9px;
        }


        .referral-item:last-child {
            margin-bottom: 0;
        }


        /* =========================================================
   FILTER TABS
========================================================= */

        .filter-tabs {
            display: flex;

            align-items: center;

            flex-wrap: wrap;

            gap: 6px;

            padding: 10px;

            background: #f8fafb;

            border: 1px solid var(--border);

            border-radius: 9px;
        }


        .filter-tabs .filter-btn {
            margin: 0 !important;

            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 6px 10px;

            background: #ffffff;

            border: 1px solid var(--border);

            border-radius: 6px;

            color: var(--muted);

            font-size: 11px;

            font-weight: 600;

            transition: .2s ease;
        }


        .filter-tabs .filter-btn:hover {
            color: var(--theme);

            border-color: rgba(195, 26, 32, .35);

            background: var(--theme-light);
        }


        .filter-tabs .filter-btn.active {
            background: var(--theme);

            border-color: var(--theme);

            color: #ffffff;

            box-shadow:
                0 3px 8px rgba(195, 26, 32, .15);
        }


        /* =========================================================
   TAB CONTENT
========================================================= */

        .tab-content-box {
            display: none;

            animation: crmFadeIn .25s ease;
        }


        .tab-content-box.active {
            display: block;
        }


        @keyframes crmFadeIn {

            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }


        /* =========================================================
   CONTENT CARD
========================================================= */

        .content-card {
            background: #ffffff;

            border: 1px solid var(--border);

            border-radius: 13px;

            box-shadow: var(--shadow);
        }


        .content-card>h5 {
            margin-bottom: 14px;

            color: var(--text-dark);

            font-size: 18px;

            font-weight: 700;
        }


        /* =========================================================
   EMPTY STATE
========================================================= */

        .content-card .text-center.p-4 {
            padding: 50px 20px !important;

            color: var(--muted);

            font-size: 14px;

            font-weight: 500;
        }


        /* =========================================================
   CARD TEXT
========================================================= */

        .referral-card p,
        .referral-card span,
        .referral-card div {
            line-height: 1.5;
        }


        /* =========================================================
   RESPONSIVE - TABLET
========================================================= */

        @media (max-width: 1100px) {

            .referral-card {
                flex-wrap: wrap;

                align-items: flex-start;

                padding: 16px;
            }


            .card-section {
                flex: 1 1 auto;
            }


            .business-info {
                border-left: 0;

                border-top: 1px solid var(--border);

                padding-left: 0;

                padding-top: 10px;

                min-width: 100%;
            }


            .actions {
                border-left: 0;

                padding-left: 0;
            }

        }


        /* =========================================================
   RESPONSIVE - MOBILE
========================================================= */

        @media (max-width: 767px) {

            .referral-card {
                display: block;

                padding: 15px;

                border-radius: 11px;
            }


            .card-section {
                width: 100%;

                margin-bottom: 13px;

                gap: 10px;
            }


            .card-section:last-child {
                margin-bottom: 0;
            }


            .member {
                min-width: 0;

                width: 100%;
            }


            .member img {
                width: 46px;
                height: 46px;

                min-width: 46px;
            }


            .member .name {
                font-size: 14px;
            }


            .member small {
                max-width: 100%;

                font-size: 11px;
            }


            .flow-arrow {
                width: 28px;
                height: 28px;

                min-width: 28px;

                font-size: 11px;
            }


            .business-info {
                width: 100%;

                min-width: 0;

                padding-top: 12px;

                gap: 6px;
            }


            .info {
                font-size: 12px;
            }


            .label {
                min-width: 65px;

                font-size: 11px;
            }


            .value {
                max-width: calc(100vw - 130px);

                font-size: 12px;
            }


            .status-area {
                width: 100%;

                flex-direction: row;

                align-items: center;

                justify-content: space-between;

                padding-top: 11px;

                border-top: 1px solid var(--border);
            }


            .status {
                min-width: auto;

                padding: 6px 10px;

                font-size: 11px;
            }


            .date {
                font-size: 10px;
            }


            .actions {
                width: 100%;

                justify-content: flex-end;

                padding-top: 10px;

                border-top: 1px solid var(--border);
            }


            .btn.action {
                width: 34px;
                height: 34px;
            }


            .connect-tabs {
                width: 100%;
            }


            .connect-tabs .tab-btn {
                flex: 1;

                justify-content: center;

                font-size: 12px;
            }


            .filter-tabs {
                padding: 8px;
            }


            .filter-tabs .filter-btn {
                font-size: 10px;

                padding: 6px 8px;
            }


            .content-card {
                border-radius: 11px;
            }

        }


        /* =========================================================
   SMALL MOBILE
========================================================= */

        @media (max-width: 480px) {

            .referral-card {
                padding: 13px;
            }


            .member img {
                width: 43px;
                height: 43px;

                min-width: 43px;
            }


            .member .name {
                font-size: 13px;
            }


            .member small {
                font-size: 10px;
            }


            .info {
                font-size: 11px;
            }


            .label {
                font-size: 10px;
            }


            .value {
                font-size: 11px;
            }


            .status {
                font-size: 10px;

                padding: 5px 8px;
            }


            .date {
                font-size: 9px;
            }


            .btn.action {
                width: 32px;
                height: 32px;

                font-size: 12px;
            }


            .connect-tabs .tab-btn {
                padding: 7px 9px;

                font-size: 11px;
            }


            .filter-tabs .filter-btn {
                font-size: 9px;
            }

        }


        /* =========================================================
   REDUCED MOTION
========================================================= */

        @media (prefers-reduced-motion: reduce) {

            .referral-card,
            .btn.action,
            .status,
            .flow-arrow,
            .tab-content-box {
                transition: none !important;

                animation: none !important;
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

                <!-- =========================================================
     REFERRAL CRM REPORT
========================================================= -->
                <div class="row p-2">
                    <div class="col-md-6 col-6">

                    </div>
                    <div class="col-md-6 col-6">
                        <style>
                            /* =========================================================
   ADD NEW MEETING BUTTON
========================================================= */

                            .add-meeting-btn {
                                height: 40px;

                                display: inline-flex;
                                align-items: center;
                                justify-content: center;

                                gap: 8px;

                                padding: 0 14px 0 6px;

                                border: 1px solid #174F70;

                                border-radius: 22px;

                                background: #174F70;

                                color: #ffffff;

                                font-size: 12px;
                                font-weight: 650;

                                line-height: 1;

                                cursor: pointer;

                                box-shadow: 0 4px 10px rgba(23, 79, 112, .16);

                                transition:
                                    background .2s ease,
                                    border-color .2s ease,
                                    transform .2s ease,
                                    box-shadow .2s ease;
                            }


                            /* ICON CIRCLE */

                            .add-meeting-icon {
                                width: 29px;
                                height: 29px;

                                flex: 0 0 29px;

                                display: flex;
                                align-items: center;
                                justify-content: center;

                                border-radius: 50%;

                                background: #ffffff;

                                color: #174F70;

                                font-size: 11px;

                                transition:
                                    transform .2s ease,
                                    background .2s ease;
                            }


                            /* TEXT */

                            .add-meeting-text {
                                white-space: nowrap;
                            }


                            /* HOVER */

                            .add-meeting-btn:hover {
                                background: #123F59;

                                border-color: #123F59;

                                color: #ffffff;

                                transform: translateY(-1px);

                                box-shadow: 0 6px 15px rgba(23, 79, 112, .22);
                            }


                            .add-meeting-btn:hover .add-meeting-icon {
                                transform: rotate(90deg);

                                background: #FFB900;

                                color: #ffffff;
                            }


                            /* ACTIVE */

                            .add-meeting-btn:active {
                                transform: translateY(0);

                                box-shadow: 0 2px 6px rgba(23, 79, 112, .15);
                            }


                            /* =========================================================
   MOBILE
========================================================= */

                            @media (max-width: 576px) {

                                .add-meeting-btn {
                                    height: 37px;

                                    padding: 0 10px 0 5px;

                                    gap: 6px;

                                    font-size: 11px;
                                }

                                .add-meeting-icon {
                                    width: 27px;
                                    height: 27px;

                                    flex-basis: 27px;

                                    font-size: 10px;
                                }

                            }
                        </style>
                        <button type="button"
                            class="add-meeting-btn" style="float: right;"
                            onclick="add_new_connects();">

                            <span class="add-meeting-icon">
                                <i class="fa fa-plus"></i>
                            </span>

                            <span class="add-meeting-text">
                                Add New Connects
                            </span>

                        </button>
                    </div>
                </div>
                <div class="crm-referral-page">

                    <!-- PAGE HEADER -->
                    <div class="crm-page-header">

                        <div class="crm-header-left">
                            <div class="crm-header-icon">
                                <i class="fas fa-handshake"></i>
                            </div>

                            <div>
                                <span class="crm-eyebrow">RELATIONSHIP MANAGEMENT</span>
                                <h4 class="crm-page-title">Connects Report</h4>
                                <p class="crm-page-subtitle">
                                    Manage, track and monitor your business connects
                                </p>
                            </div>
                        </div>

                        <div class="crm-header-summary">
                            <div class="crm-summary-item">
                                <span>Total Received</span>
                                <strong><?= $receivedCount ?></strong>
                            </div>

                            <div class="crm-summary-divider"></div>

                            <div class="crm-summary-item">
                                <span>Total Given</span>
                                <strong><?= count($given) ?></strong>
                            </div>
                        </div>

                    </div>


                    <!-- =====================================================
         MAIN CONNECT TABS
    ====================================================== -->

                    <div class="crm-main-tabs">

                        <button
                            type="button"
                            class="crm-main-tab active"
                            data-tab="received">

                            <span class="crm-tab-icon received">
                                <i class="fas fa-inbox"></i>
                            </span>

                            <span class="crm-tab-content">
                                <strong>Received</strong>
                                <small>Connects received</small>
                            </span>

                            <span class="crm-tab-count">
                                <?= $receivedCount ?>
                            </span>

                        </button>


                        <button
                            type="button"
                            class="crm-main-tab"
                            data-tab="given">

                            <span class="crm-tab-icon given">
                                <i class="fas fa-paper-plane"></i>
                            </span>

                            <span class="crm-tab-content">
                                <strong>Given</strong>
                                <small>Connects shared</small>
                            </span>

                            <span class="crm-tab-count">
                                <?= count($given) ?>
                            </span>

                        </button>

                    </div>


                    <!-- =====================================================
         CONTENT AREA
    ====================================================== -->

                    <div class="crm-content-area">


                        <!-- =================================================
             RECEIVED REFERRALS
        ================================================== -->

                        <div
                            class="tab-content-box active"
                            id="received">

                            <div class="crm-panel">

                                <!-- PANEL HEADER -->
                                <div class="crm-panel-header">

                                    <div class="crm-panel-title">

                                        <div class="crm-panel-icon received-icon">
                                            <i class="fas fa-inbox"></i>
                                        </div>

                                        <div>
                                            <h5>Received Connects</h5>
                                            <p>
                                                Connects shared with you
                                            </p>
                                        </div>

                                    </div>

                                    <div class="crm-record-count">
                                        <i class="fas fa-list-ul"></i>
                                        <?= $receivedCount ?> Records
                                    </div>

                                </div>


                                <!-- STATUS FILTERS -->
                                <div class="crm-filter-section">

                                    <div class="crm-filter-label">
                                        <i class="fas fa-filter"></i>
                                        Filter by status
                                    </div>

                                    <div class="crm-filter-tabs">

                                        <?php foreach ($statusCounts as $status => $count): ?>

                                            <button
                                                type="button"
                                                class="crm-filter-btn <?= $status == 'all' ? 'active' : '' ?>"
                                                data-status="<?= esc($status) ?>">

                                                <span>
                                                    <?= esc(ucfirst($status)) ?>
                                                </span>

                                                <b>
                                                    <?= $count ?>
                                                </b>

                                            </button>

                                        <?php endforeach; ?>

                                    </div>

                                </div>


                                <!-- REFERRAL LIST -->
                                <div class="crm-list-area">

                                    <?php if (!empty($received)): ?>

                                        <?php foreach ($received as $row): ?>

                                            <div
                                                class="crm-referral-item referral-item"
                                                data-status="<?= strtolower(trim($row['status'] ?? 'new')) ?>">

                                                <?= view(
                                                    'include/member/card/recived_card.php',
                                                    ['row' => $row]
                                                ) ?>

                                            </div>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <div class="crm-empty-state">

                                            <div class="crm-empty-icon">
                                                <i class="fas fa-inbox"></i>
                                            </div>

                                            <h6>No Received Connects</h6>

                                            <p>
                                                There are currently no Connects received.
                                            </p>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>


                        <!-- =================================================
             GIVEN REFERRALS
        ================================================== -->

                        <div
                            class="tab-content-box"
                            id="given">

                            <div class="crm-panel">

                                <!-- PANEL HEADER -->
                                <div class="crm-panel-header">

                                    <div class="crm-panel-title">

                                        <div class="crm-panel-icon given-icon">
                                            <i class="fas fa-paper-plane"></i>
                                        </div>

                                        <div>
                                            <h5>Given Connects </h5>
                                            <p>
                                                Connects  you have shared
                                            </p>
                                        </div>

                                    </div>

                                    <div class="crm-record-count">
                                        <i class="fas fa-list-ul"></i>
                                        <?= count($given) ?> Records
                                    </div>

                                </div>


                                <!-- GIVEN LIST -->

                                <div class="crm-list-area" style="overflow-x: auto; width: 100%; padding-bottom: 10px; -webkit-overflow-scrolling: touch;">

                                    <?php if (!empty($given)): ?>

                                        <?php foreach ($given as $row): ?>

                                            <div class="crm-referral-item">

                                                <?= view(
                                                    'include/member/card/given_card.php',
                                                    ['row' => $row]
                                                ) ?>

                                            </div>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <div class="crm-empty-state">

                                            <div class="crm-empty-icon">
                                                <i class="fas fa-paper-plane"></i>
                                            </div>

                                            <h6>No Given Connects </h6>

                                            <p>
                                                You have not shared any connects yet.
                                            </p>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- =====================================================
         ANALYTICS
    ====================================================== -->

                    <div class="crm-analytics-card">

                        <div class="crm-analytics-header">

                            <div class="crm-analytics-title">

                                <div class="crm-analytics-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>

                                <div>
                                    <h5>Connects Overview</h5>
                                    <p>
                                        Connects activity performance
                                    </p>
                                </div>

                            </div>

                            <div class="crm-analytics-badge">
                                <i class="fas fa-chart-area"></i>
                                Activity Report
                            </div>

                        </div>


                        <div class="crm-chart-container">

                            <canvas id="mitbChart"></canvas>

                        </div>

                    </div>

                </div>


                <!-- =========================================================
     CHART JS
========================================================= -->

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {

                        const chartElement = document.getElementById('mitbChart');

                        if (chartElement) {

                            const ctx = chartElement.getContext('2d');

                            new Chart(ctx, {

                                type: 'line',

                                data: {

                                    labels: <?= json_encode($chartLabels) ?>,

                                    datasets: [

                                        {
                                            label: 'Business Received',

                                            data: <?= json_encode($chartReceived) ?>,

                                            backgroundColor: 'rgba(10, 61, 97, 0.08)',

                                            borderColor: '#0a3d61',

                                            borderWidth: 2,

                                            fill: true,

                                            tension: 0.4,

                                            pointRadius: 4,

                                            pointHoverRadius: 6,

                                            pointBackgroundColor: '#ffffff',

                                            pointBorderColor: '#0a3d61',

                                            pointBorderWidth: 2
                                        },

                                        {
                                            label: 'Business Given',

                                            data: <?= json_encode($chartGiven) ?>,

                                            backgroundColor: 'rgba(35, 145, 90, 0.05)',

                                            borderColor: '#23915a',

                                            borderWidth: 2,

                                            fill: false,

                                            tension: 0.4,

                                            pointRadius: 4,

                                            pointHoverRadius: 6,

                                            pointBackgroundColor: '#ffffff',

                                            pointBorderColor: '#23915a',

                                            pointBorderWidth: 2
                                        }

                                    ]

                                },

                                options: {

                                    responsive: true,

                                    maintainAspectRatio: false,

                                    interaction: {

                                        mode: 'index',

                                        intersect: false

                                    },

                                    plugins: {

                                        legend: {

                                            position: 'top',

                                            align: 'end',

                                            labels: {

                                                usePointStyle: true,

                                                pointStyle: 'circle',

                                                padding: 20,

                                                font: {

                                                    size: 12,

                                                    weight: '600'

                                                }

                                            }

                                        },

                                        tooltip: {

                                            backgroundColor: '#0a3d61',

                                            titleColor: '#ffffff',

                                            bodyColor: '#ffffff',

                                            padding: 12,

                                            cornerRadius: 8,

                                            displayColors: true

                                        }

                                    },

                                    scales: {

                                        x: {

                                            grid: {

                                                display: false

                                            },

                                            ticks: {

                                                color: '#71818b',

                                                font: {

                                                    size: 11

                                                }

                                            }

                                        },

                                        y: {

                                            beginAtZero: true,

                                            ticks: {

                                                stepSize: 1,

                                                color: '#71818b',

                                                font: {

                                                    size: 11

                                                }

                                            },

                                            grid: {

                                                color: '#edf2f5',

                                                drawBorder: false

                                            }

                                        }

                                    }

                                }

                            });

                        }

                    });
                </script>


                <!-- =========================================================
     CRM UI CSS
========================================================= -->

                <style>
                    /* =========================================================
   CRM REFERRAL REPORT - COMPLETE CSS
   Theme: #0a3d61
========================================================= */

                    :root {
                        --crm-primary: #0a3d61;
                        --crm-primary-dark: #072f4b;
                        --crm-primary-light: #eaf3f8;

                        --crm-green: #23915a;
                        --crm-green-light: #edf8f2;

                        --crm-orange: #c47b18;
                        --crm-orange-light: #fff6e9;

                        --crm-red: #c21920;
                        --crm-red-light: #fff0f1;

                        --crm-text: #263746;
                        --crm-text-dark: #172b3a;
                        --crm-muted: #71818b;

                        --crm-border: #dfe9ef;
                        --crm-bg: #f6f9fb;
                        --crm-white: #ffffff;

                        --crm-shadow:
                            0 5px 20px rgba(10, 61, 97, 0.07);

                        --crm-shadow-hover:
                            0 10px 30px rgba(10, 61, 97, 0.12);
                    }


                    /* =========================================================
   MAIN PAGE
========================================================= */

                    .crm-referral-page {
                        width: 100%;
                        color: var(--crm-text);

                        font-family:
                            "Segoe UI",
                            Roboto,
                            Arial,
                            sans-serif;

                        font-size: 14px;
                    }


                    /* =========================================================
   PAGE HEADER
========================================================= */

                    .crm-page-header {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;

                        gap: 20px;

                        padding: 22px 24px;
                        margin-bottom: 16px;

                        background: var(--crm-white);

                        border: 1px solid var(--crm-border);
                        border-radius: 15px;

                        box-shadow: var(--crm-shadow);

                        transition: all 0.25s ease;
                    }

                    .crm-page-header:hover {
                        box-shadow: var(--crm-shadow-hover);
                    }


                    /* Header Left */

                    .crm-header-left {
                        display: flex;
                        align-items: center;
                        gap: 15px;
                    }


                    .crm-header-icon {
                        width: 52px;
                        height: 52px;

                        min-width: 52px;

                        display: flex;
                        align-items: center;
                        justify-content: center;

                        background:
                            linear-gradient(135deg,
                                var(--crm-primary),
                                #185b83);

                        color: #ffffff;

                        border-radius: 12px;

                        font-size: 21px;

                        box-shadow:
                            0 6px 16px rgba(10, 61, 97, 0.20);
                    }


                    .crm-eyebrow {
                        display: block;

                        margin-bottom: 4px;

                        color: var(--crm-primary);

                        font-size: 12px;

                        font-weight: 700;

                        letter-spacing: 1.2px;

                        text-transform: uppercase;
                    }


                    .crm-page-title {
                        margin: 0;

                        color: var(--crm-text-dark);

                        font-size: 26px;

                        line-height: 1.2;

                        font-weight: 700;
                    }


                    .crm-page-subtitle {
                        margin: 5px 0 0;

                        color: var(--crm-muted);

                        font-size: 14px;

                        line-height: 1.4;
                    }


                    /* =========================================================
   HEADER SUMMARY
========================================================= */

                    .crm-header-summary {
                        display: flex;
                        align-items: center;

                        padding: 9px 15px;

                        background: #f7fafc;

                        border: 1px solid var(--crm-border);

                        border-radius: 11px;
                    }


                    .crm-summary-item {
                        min-width: 100px;

                        text-align: center;
                    }


                    .crm-summary-item span {
                        display: block;

                        margin-bottom: 3px;

                        color: var(--crm-muted);

                        font-size: 12px;

                        font-weight: 600;

                        text-transform: uppercase;
                    }


                    .crm-summary-item strong {
                        display: block;

                        color: var(--crm-primary);

                        font-size: 22px;

                        line-height: 1.2;

                        font-weight: 700;
                    }


                    .crm-summary-divider {
                        width: 1px;
                        height: 34px;

                        margin: 0 12px;

                        background: var(--crm-border);
                    }


                    /* =========================================================
   MAIN TABS
========================================================= */

                    .crm-main-tabs {
                        display: flex;

                        gap: 8px;

                        padding: 5px;
                        margin-bottom: 14px;

                        background: #edf3f6;

                        border: 1px solid var(--crm-border);

                        border-radius: 12px;
                    }


                    .crm-main-tab {
                        position: relative;

                        flex: 1;

                        display: flex;
                        align-items: center;

                        gap: 12px;

                        padding: 12px 15px;

                        background: transparent;

                        border: 0;

                        border-radius: 9px;

                        color: var(--crm-muted);

                        text-align: left;

                        cursor: pointer;

                        transition:
                            background 0.2s ease,
                            color 0.2s ease,
                            box-shadow 0.2s ease,
                            transform 0.2s ease;
                    }


                    .crm-main-tab:hover {
                        background: rgba(255, 255, 255, 0.75);

                        color: var(--crm-primary);

                        transform: translateY(-1px);
                    }


                    .crm-main-tab.active {
                        background: var(--crm-white);

                        color: var(--crm-primary);

                        box-shadow:
                            0 4px 12px rgba(10, 61, 97, 0.09);
                    }


                    /* Tab Icon */

                    .crm-tab-icon {
                        width: 40px;
                        height: 40px;

                        min-width: 40px;

                        display: flex;
                        align-items: center;
                        justify-content: center;

                        border-radius: 9px;

                        font-size: 15px;
                    }


                    .crm-tab-icon.received {
                        background: var(--crm-primary-light);
                        color: var(--crm-primary);
                    }


                    .crm-tab-icon.given {
                        background: var(--crm-green-light);
                        color: var(--crm-green);
                    }


                    /* Tab Text */

                    .crm-tab-content {
                        flex: 1;
                    }


                    .crm-tab-content strong {
                        display: block;

                        font-size: 15px;

                        line-height: 1.3;

                        font-weight: 700;
                    }


                    .crm-tab-content small {
                        display: block;

                        margin-top: 3px;

                        color: var(--crm-muted);

                        font-size: 12px;

                        line-height: 1.3;
                    }


                    /* Tab Count */

                    .crm-tab-count {
                        min-width: 31px;

                        padding: 5px 8px;

                        background: #eef3f6;

                        border-radius: 6px;

                        color: var(--crm-primary);

                        font-size: 13px;

                        font-weight: 700;

                        text-align: center;
                    }


                    .crm-main-tab.active .crm-tab-count {
                        background: var(--crm-primary);
                        color: #ffffff;
                    }


                    /* =========================================================
   TAB CONTENT
========================================================= */

                    .tab-content-box {
                        display: none;
                    }


                    .tab-content-box.active {
                        display: block;
                    }


                    /* =========================================================
   CRM PANEL
========================================================= */

                    .crm-panel {
                        background: var(--crm-white);

                        border: 1px solid var(--crm-border);

                        border-radius: 14px;

                        overflow: hidden;

                        box-shadow: var(--crm-shadow);

                        transition: box-shadow 0.25s ease;
                    }


                    .crm-panel:hover {
                        box-shadow: var(--crm-shadow-hover);
                    }


                    /* =========================================================
   PANEL HEADER
========================================================= */

                    .crm-panel-header {
                        display: flex;

                        align-items: center;
                        justify-content: space-between;

                        gap: 15px;

                        padding: 17px 20px;

                        background: #ffffff;

                        border-bottom: 1px solid var(--crm-border);
                    }


                    .crm-panel-title {
                        display: flex;
                        align-items: center;

                        gap: 12px;
                    }


                    .crm-panel-icon {
                        width: 42px;
                        height: 42px;

                        min-width: 42px;

                        display: flex;
                        align-items: center;
                        justify-content: center;

                        border-radius: 10px;

                        font-size: 15px;
                    }


                    .received-icon {
                        background: var(--crm-primary-light);
                        color: var(--crm-primary);
                    }


                    .given-icon {
                        background: var(--crm-green-light);
                        color: var(--crm-green);
                    }


                    .crm-panel-title h5 {
                        margin: 0;

                        color: var(--crm-text-dark);

                        font-size: 18px;

                        line-height: 1.3;

                        font-weight: 700;
                    }


                    .crm-panel-title p {
                        margin: 3px 0 0;

                        color: var(--crm-muted);

                        font-size: 13px;

                        line-height: 1.4;
                    }


                    /* Record Count */

                    .crm-record-count {
                        display: flex;
                        align-items: center;

                        gap: 7px;

                        padding: 7px 11px;

                        background: #f5f8fa;

                        border: 1px solid var(--crm-border);

                        border-radius: 7px;

                        color: var(--crm-muted);

                        font-size: 12px;

                        font-weight: 600;

                        white-space: nowrap;
                    }


                    .crm-record-count i {
                        color: var(--crm-primary);

                        font-size: 12px;
                    }


                    /* =========================================================
   FILTER SECTION
========================================================= */

                    .crm-filter-section {
                        display: flex;

                        align-items: center;

                        gap: 16px;

                        padding: 13px 20px;

                        background: #fbfcfd;

                        border-bottom: 1px solid var(--crm-border);
                    }


                    .crm-filter-label {
                        display: flex;
                        align-items: center;

                        gap: 7px;

                        color: var(--crm-muted);

                        font-size: 13px;

                        font-weight: 600;

                        white-space: nowrap;
                    }


                    .crm-filter-label i {
                        color: var(--crm-primary);

                        font-size: 13px;
                    }


                    .crm-filter-tabs {
                        display: flex;

                        flex-wrap: wrap;

                        gap: 7px;
                    }


                    .crm-filter-btn {
                        display: inline-flex;

                        align-items: center;

                        gap: 7px;

                        padding: 7px 11px;

                        background: #ffffff;

                        border: 1px solid var(--crm-border);

                        border-radius: 7px;

                        color: var(--crm-muted);

                        font-size: 12px;

                        font-weight: 600;

                        line-height: 1.2;

                        cursor: pointer;

                        transition:
                            all 0.2s ease;
                    }


                    .crm-filter-btn b {
                        min-width: 20px;

                        padding: 3px 5px;

                        background: #eef3f6;

                        border-radius: 4px;

                        color: var(--crm-primary);

                        font-size: 10px;

                        line-height: 1;

                        text-align: center;
                    }


                    .crm-filter-btn:hover {
                        border-color: var(--crm-primary);

                        color: var(--crm-primary);

                        background: var(--crm-primary-light);
                    }


                    .crm-filter-btn.active {
                        background: var(--crm-primary);

                        border-color: var(--crm-primary);

                        color: #ffffff;

                        box-shadow:
                            0 3px 8px rgba(10, 61, 97, 0.15);
                    }


                    .crm-filter-btn.active b {
                        background: rgba(255, 255, 255, 0.18);

                        color: #ffffff;
                    }


                    /* =========================================================
   REFERRAL LIST AREA
========================================================= */

                    .crm-list-area {
                        padding: 14px;

                        background: var(--crm-bg);
                    }


                    .crm-referral-item {
                        margin-bottom: 11px;
                    }


                    .crm-referral-item:last-child {
                        margin-bottom: 0;
                    }


                    /* =========================================================
   EMPTY STATE
========================================================= */

                    .crm-empty-state {
                        padding: 55px 20px;

                        text-align: center;
                    }


                    .crm-empty-icon {
                        width: 60px;
                        height: 60px;

                        margin: 0 auto 14px;

                        display: flex;
                        align-items: center;
                        justify-content: center;

                        background: var(--crm-primary-light);

                        color: var(--crm-primary);

                        border-radius: 50%;

                        font-size: 21px;
                    }


                    .crm-empty-state h6 {
                        margin: 0 0 6px;

                        color: var(--crm-text-dark);

                        font-size: 17px;

                        font-weight: 700;
                    }


                    .crm-empty-state p {
                        margin: 0;

                        color: var(--crm-muted);

                        font-size: 13px;
                    }


                    /* =========================================================
   ANALYTICS CARD
========================================================= */

                    .crm-analytics-card {
                        margin-top: 16px;

                        background: var(--crm-white);

                        border: 1px solid var(--crm-border);

                        border-radius: 14px;

                        overflow: hidden;

                        box-shadow: var(--crm-shadow);

                        transition: box-shadow 0.25s ease;
                    }


                    .crm-analytics-card:hover {
                        box-shadow: var(--crm-shadow-hover);
                    }


                    /* Analytics Header */

                    .crm-analytics-header {
                        display: flex;

                        align-items: center;
                        justify-content: space-between;

                        gap: 15px;

                        padding: 17px 20px;

                        border-bottom: 1px solid var(--crm-border);
                    }


                    .crm-analytics-title {
                        display: flex;
                        align-items: center;

                        gap: 12px;
                    }


                    .crm-analytics-icon {
                        width: 42px;
                        height: 42px;

                        display: flex;
                        align-items: center;
                        justify-content: center;

                        background: var(--crm-primary-light);

                        color: var(--crm-primary);

                        border-radius: 10px;

                        font-size: 16px;
                    }


                    .crm-analytics-title h5 {
                        margin: 0;

                        color: var(--crm-text-dark);

                        font-size: 18px;

                        line-height: 1.3;

                        font-weight: 700;
                    }


                    .crm-analytics-title p {
                        margin: 3px 0 0;

                        color: var(--crm-muted);

                        font-size: 13px;

                        line-height: 1.4;
                    }


                    .crm-analytics-badge {
                        display: flex;

                        align-items: center;

                        gap: 7px;

                        padding: 7px 11px;

                        background: var(--crm-primary-light);

                        color: var(--crm-primary);

                        border-radius: 7px;

                        font-size: 12px;

                        font-weight: 600;
                    }


                    /* =========================================================
   CHART
========================================================= */

                    .crm-chart-container {
                        position: relative;

                        width: 100%;

                        height: 320px;

                        padding: 18px 22px 22px;
                    }


                    .crm-chart-container canvas {
                        max-width: 100%;
                    }


                    /* =========================================================
   REFERRAL CARD OVERRIDE
   Makes included referral cards fit CRM style
========================================================= */

                    .crm-referral-item .card,
                    .crm-referral-item .referral-card {
                        margin-bottom: 0 !important;

                        border: 1px solid var(--crm-border) !important;

                        border-radius: 11px !important;

                        box-shadow:
                            0 2px 8px rgba(10, 61, 97, 0.04) !important;

                        transition:
                            transform 0.2s ease,
                            box-shadow 0.2s ease,
                            border-color 0.2s ease !important;
                    }


                    .crm-referral-item .card:hover,
                    .crm-referral-item .referral-card:hover {
                        transform: translateY(-2px);

                        border-color: #c9dce7 !important;

                        box-shadow:
                            0 7px 20px rgba(10, 61, 97, 0.10) !important;
                    }


                    /* =========================================================
   GENERAL CARD TYPOGRAPHY
========================================================= */

                    .crm-referral-item .card h5,
                    .crm-referral-item .card h6,
                    .crm-referral-item .referral-card h5,
                    .crm-referral-item .referral-card h6 {
                        color: var(--crm-text-dark);
                    }


                    .crm-referral-item .card p,
                    .crm-referral-item .referral-card p {
                        font-size: 14px;
                        line-height: 1.55;
                    }


                    /* =========================================================
   BUTTONS INSIDE REFERRAL CARDS
========================================================= */

                    .crm-referral-item .btn {
                        border-radius: 7px;

                        font-size: 13px;

                        font-weight: 600;

                        transition: all 0.2s ease;
                    }


                    .crm-referral-item .btn:hover {
                        transform: translateY(-1px);

                        box-shadow:
                            0 4px 10px rgba(10, 61, 97, 0.12);
                    }


                    /* =========================================================
   BADGES
========================================================= */

                    .crm-referral-item .badge {
                        font-size: 11px;

                        padding: 6px 9px;

                        border-radius: 6px;
                    }


                    /* =========================================================
   LINKS
========================================================= */

                    .crm-referral-page a {
                        transition: color 0.2s ease;
                    }


                    .crm-referral-page a:hover {
                        color: var(--crm-primary);
                    }


                    /* =========================================================
   MOBILE - TABLET
========================================================= */

                    @media (max-width: 991px) {

                        .crm-page-header {
                            padding: 18px 20px;
                        }


                        .crm-page-title {
                            font-size: 23px;
                        }


                        .crm-header-summary {
                            padding: 8px 10px;
                        }


                        .crm-summary-item {
                            min-width: 85px;
                        }


                        .crm-panel-header {
                            padding: 15px 17px;
                        }


                        .crm-filter-section {
                            padding: 12px 17px;
                        }


                        .crm-list-area {
                            padding: 11px;
                        }
                    }


                    /* =========================================================
   MOBILE
========================================================= */

                    @media (max-width: 767px) {

                        .crm-page-header {
                            flex-direction: column;

                            align-items: flex-start;

                            padding: 17px;

                            gap: 15px;
                        }


                        .crm-header-left {
                            width: 100%;
                        }


                        .crm-header-icon {
                            width: 46px;
                            height: 46px;

                            min-width: 46px;

                            font-size: 18px;
                        }


                        .crm-eyebrow {
                            font-size: 10px;
                        }


                        .crm-page-title {
                            font-size: 21px;
                        }


                        .crm-page-subtitle {
                            font-size: 13px;
                        }


                        .crm-header-summary {
                            width: 100%;

                            justify-content: center;

                            padding: 9px;
                        }


                        .crm-summary-item {
                            flex: 1;

                            min-width: auto;
                        }


                        .crm-summary-item span {
                            font-size: 10px;
                        }


                        .crm-summary-item strong {
                            font-size: 20px;
                        }


                        .crm-main-tabs {
                            flex-direction: column;

                            gap: 5px;
                        }


                        .crm-main-tab {
                            width: 100%;

                            padding: 11px 13px;
                        }


                        .crm-tab-content strong {
                            font-size: 14px;
                        }


                        .crm-tab-content small {
                            font-size: 11px;
                        }


                        .crm-panel-header {
                            align-items: flex-start;

                            flex-direction: column;

                            padding: 15px;
                        }


                        .crm-record-count {
                            align-self: flex-start;

                            font-size: 11px;
                        }


                        .crm-filter-section {
                            align-items: flex-start;

                            flex-direction: column;

                            gap: 9px;

                            padding: 12px 15px;
                        }


                        .crm-filter-label {
                            font-size: 12px;
                        }


                        .crm-filter-tabs {
                            width: 100%;
                        }


                        .crm-filter-btn {
                            font-size: 11px;

                            padding: 7px 10px;
                        }


                        .crm-list-area {
                            padding: 8px;
                        }


                        .crm-referral-item {
                            margin-bottom: 8px;
                        }


                        .crm-analytics-header {
                            align-items: flex-start;

                            flex-direction: column;

                            padding: 15px;
                        }


                        .crm-analytics-title h5 {
                            font-size: 17px;
                        }


                        .crm-analytics-title p {
                            font-size: 12px;
                        }


                        .crm-analytics-badge {
                            font-size: 11px;
                        }


                        .crm-chart-container {
                            height: 270px;

                            padding: 12px;
                        }

                    }


                    /* =========================================================
   SMALL MOBILE
========================================================= */

                    @media (max-width: 480px) {

                        .crm-page-title {
                            font-size: 20px;
                        }


                        .crm-page-subtitle {
                            font-size: 12px;
                        }


                        .crm-header-left {
                            gap: 11px;
                        }


                        .crm-header-icon {
                            width: 43px;
                            height: 43px;

                            min-width: 43px;
                        }


                        .crm-summary-item span {
                            font-size: 9px;
                        }


                        .crm-summary-item strong {
                            font-size: 18px;
                        }


                        .crm-tab-icon {
                            width: 36px;
                            height: 36px;

                            min-width: 36px;
                        }


                        .crm-tab-content strong {
                            font-size: 13px;
                        }


                        .crm-tab-content small {
                            font-size: 10px;
                        }


                        .crm-tab-count {
                            font-size: 11px;
                        }


                        .crm-panel-title h5 {
                            font-size: 16px;
                        }


                        .crm-panel-title p {
                            font-size: 11px;
                        }


                        .crm-filter-label {
                            font-size: 11px;
                        }


                        .crm-filter-btn {
                            font-size: 10px;

                            padding: 6px 8px;
                        }


                        .crm-filter-btn b {
                            font-size: 9px;
                        }


                        .crm-empty-state {
                            padding: 45px 15px;
                        }


                        .crm-empty-state h6 {
                            font-size: 16px;
                        }


                        .crm-empty-state p {
                            font-size: 12px;
                        }


                        .crm-analytics-title h5 {
                            font-size: 16px;
                        }


                        .crm-analytics-title p {
                            font-size: 11px;
                        }


                        .crm-chart-container {
                            height: 240px;

                            padding: 8px;
                        }

                    }


                    /* =========================================================
   PRINT
========================================================= */

                    @media print {

                        .crm-main-tabs,
                        .crm-filter-section,
                        .crm-header-summary,
                        .crm-analytics-card {
                            display: none !important;
                        }


                        .crm-page-header,
                        .crm-panel {
                            box-shadow: none !important;

                            border: 1px solid #ddd !important;
                        }


                        .crm-referral-item {
                            break-inside: avoid;
                        }

                    }
                </style>


                <!-- =========================================================
     TAB + FILTER JAVASCRIPT
========================================================= -->

                <script>
                    document.addEventListener("DOMContentLoaded", function() {

                        /* ================================================
                           MAIN TABS
                        ================================================= */

                        const mainTabs =
                            document.querySelectorAll('.crm-main-tab');

                        const tabContents =
                            document.querySelectorAll('.tab-content-box');


                        mainTabs.forEach(function(button) {

                            button.addEventListener('click', function() {

                                const target =
                                    this.getAttribute('data-tab');


                                mainTabs.forEach(function(tab) {

                                    tab.classList.remove('active');

                                });


                                tabContents.forEach(function(content) {

                                    content.classList.remove('active');

                                });


                                this.classList.add('active');


                                const targetContent =
                                    document.getElementById(target);


                                if (targetContent) {

                                    targetContent.classList.add('active');

                                }

                            });

                        });


                        /* ================================================
                           STATUS FILTER
                        ================================================= */

                        const filterButtons =
                            document.querySelectorAll('.crm-filter-btn');


                        filterButtons.forEach(function(button) {

                            button.addEventListener('click', function() {

                                const status =
                                    this.getAttribute('data-status');


                                filterButtons.forEach(function(btn) {

                                    btn.classList.remove('active');

                                });


                                this.classList.add('active');


                                const referralItems =
                                    document.querySelectorAll(
                                        '#received .referral-item'
                                    );


                                referralItems.forEach(function(item) {

                                    const itemStatus =
                                        item.getAttribute('data-status');


                                    if (
                                        status === 'all' ||
                                        status === itemStatus
                                    ) {

                                        item.style.display = '';

                                    } else {

                                        item.style.display = 'none';

                                    }

                                });

                            });

                        });

                    });
                </script>



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
    <?php echo view('include/member/popup/connects.php') ?>
    <!-- JS: TAB SWITCH & STATUS FILTER -->
    <script>
        // TAB SWITCH
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tab = this.dataset.tab;
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                document.querySelectorAll('.tab-content-box').forEach(c => c.classList.remove('active'));
                document.getElementById(tab).classList.add('active');
            });
        });

        // FILTER STATUS
        document.querySelectorAll('#received .filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const status = this.dataset.status;

                document.querySelectorAll('#received .filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                document.querySelectorAll('#received .referral-item').forEach(item => {
                    if (status === 'all' || item.dataset.status === status) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>


</body>

</html>