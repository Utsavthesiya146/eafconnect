<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>MITB Closed Business | EAF Connect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">

    <!-- Google Fonts & Remix Icon -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets2/css/eaf-design-system.css?v=10.0" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/eaf-revamp.css?v=10.0" rel="stylesheet" type="text/css" />
    <style>
        /* =========================================================
   GLOBAL PAGE
========================================================= */

        .mitb-page {
            width: 100%;
            padding: 10px 12px;
            color: #263747;
            font-family: 'Segoe UI', Arial, sans-serif;
            box-sizing: border-box;
        }

        .mitb-page * {
            box-sizing: border-box;
        }


        /* =========================================================
   HEADER
========================================================= */

        .mitb-page-header {
            min-height: 46px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 15px;
        }

        .mitb-page-title {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
        }

        .mitb-title-icon {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #eaf3f8;
            color: #174f70;
            font-size: 17px;
        }

        .mitb-title-content h5 {
            margin: 0;
            color: #174f70;
            font-size: 17px;
            font-weight: 750;
        }

        .mitb-title-content p {
            margin: 3px 0 0;
            color: #89969e;
            font-size: 11px;
        }


        /* =========================================================
   SUBMIT MITB BUTTON
========================================================= */

        .add-meeting-btn {
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 4px 15px 4px 6px;
            border: 1px solid #174f70;
            border-radius: 25px;
            background: #174f70;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            cursor: pointer;
            box-shadow: 0 3px 9px rgba(23, 79, 112, .15);
            transition: all .25s ease;
        }

        .add-meeting-icon {
            width: 29px;
            height: 29px;
            flex: 0 0 29px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fff;
            color: #174f70;
            transition: all .25s ease;
        }

        .add-meeting-btn:hover {
            background: #103d57;
            border-color: #103d57;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(23, 79, 112, .2);
        }

        .add-meeting-btn:hover .add-meeting-icon {
            background: #ffbd00;
            color: #fff;
            transform: rotate(90deg);
        }


        /* =========================================================
   SUMMARY CARDS
========================================================= */

        .business-summary {
            margin-bottom: 14px;
        }

        .business-summary>div {
            display: flex;
        }

        .summary-card {
            position: relative;
            width: 100%;
            min-height: 94px;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 16px;
            background: #fff;
            border: 1px solid #e5ebef;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(23, 79, 112, .05);
            transition: all .25s ease;
        }

        .summary-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 4px;
            background: #174f70;
        }

        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 18px rgba(23, 79, 112, .10);
        }

        .summary-icon {
            width: 46px;
            height: 46px;
            flex: 0 0 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: #eaf3f7;
            color: #174f70;
            font-size: 18px;
        }

        .summary-content {
            flex: 1;
            min-width: 0;
        }

        .summary-title {
            margin-bottom: 4px;
            color: #788690;
            font-size: 11px;
            font-weight: 700;
        }

        .summary-value {
            color: #174f70;
            font-size: clamp(18px, 1.6vw, 23px);
            font-weight: 750;
            line-height: 1.2;
            letter-spacing: -.4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .summary-subtitle {
            margin-top: 5px;
            color: #9ba5ac;
            font-size: 10px;
        }

        .revenue-card::before {
            background: #15965e;
        }

        .revenue-card .summary-icon {
            background: #eaf8f1;
            color: #15965e;
        }

        .revenue-card .summary-value {
            color: #15965e;
        }

        .member-value-card::before {
            background: #174f70;
        }

        .member-value-card .summary-icon {
            background: #eaf3f7;
            color: #174f70;
        }

        .member-value-card .summary-value {
            color: #174f70;
        }

        .records-card::before {
            background: #1769a8;
        }

        .records-card .summary-icon {
            background: #edf5fa;
            color: #1769a8;
        }

        .records-card .summary-value {
            color: #1769a8;
        }


        /* =========================================================
   FILTER CARD
========================================================= */

        .meeting-filter-card {
            position: relative;
            margin-bottom: 5px;
            border: 1px solid #e2e9ed !important;
            border-radius: 12px !important;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(23, 79, 112, .05) !important;
        }

        .meeting-filter-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: #174f70;
        }

        .meeting-filter-card .card-body {
            padding: 5px !important;
        }

        .meeting-filter-row {
            display: grid;
            grid-template-columns:
                minmax(145px, 1.5fr) repeat(3, minmax(100px, 1fr)) minmax(110px, 1fr) minmax(125px, 1.1fr) minmax(125px, 1.1fr);
            gap: 10px;
            align-items: end;
        }

        .meeting-search,
        .meeting-filter {
            min-width: 0;
        }

        .filter-label {
            display: flex;
            align-items: center;
            gap: 5px;
            margin: 0 0 7px 2px;
            color: #5b6a75;
            font-size: 10px;
            font-weight: 700;
        }

        .filter-label i {
            color: #174f70;
            font-size: 11px;
        }

        .filter-input-wrap {
            position: relative;
            width: 100%;
        }

        .filter-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            z-index: 2;
            transform: translateY(-50%);
            color: #174f70;
            font-size: 11px;
            pointer-events: none;
        }

        .meeting-input,
        .meeting-select {
            width: 100%;
            height: 38px !important;
            min-height: 38px !important;
            border: 1px solid #dce5ea !important;
            border-radius: 8px !important;
            background: #f8fafb !important;
            color: #344653 !important;
            font-size: 11px !important;
            font-weight: 500;
            box-shadow: none !important;
            transition: all .2s ease;
        }

        .meeting-input {
            padding: 0 9px 0 31px !important;
        }

        .meeting-select {
            padding: 0 24px 0 31px !important;
            cursor: pointer;
        }

        .meeting-input:hover,
        .meeting-select:hover {
            border-color: #b9cbd5 !important;
            background: #fff !important;
        }

        .meeting-input:focus,
        .meeting-select:focus {
            border-color: #174f70 !important;
            background: #fff !important;
            box-shadow: 0 0 0 3px rgba(23, 79, 112, .09) !important;
            outline: none;
        }


        /* =========================================================
   FILTER BUTTONS
========================================================= */

        .meeting-filter-actions {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 7px;
        }

        .filter-btn {
            height: 36px;
            min-width: 83px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0 12px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
            cursor: pointer;
            transition: all .2s ease;
        }

        .filter-btn:hover {
            transform: translateY(-2px);
        }

        .reset-filter {
            color: #59656e;
            background: #f5f7f8;
            border: 1px solid #d9e1e6;
        }

        .reset-filter:hover {
            color: #fff;
            background: #174f70;
            border-color: #174f70;
        }

        .excel-filter {
            color: #168b59;
            background: #edf9f3;
            border: 1px solid #cce8d9;
        }

        .excel-filter:hover {
            color: #fff;
            background: #168b59;
            border-color: #168b59;
        }

        .pdf-filter {
            color: #df3d39;
            background: #fff1f1;
            border: 1px solid #f0d1d1;
        }

        .pdf-filter:hover {
            color: #fff;
            background: #df3d39;
            border-color: #df3d39;
        }


        /* =========================================================
   REPORT PANELS
========================================================= */

        .mitb-report-row {
            align-items: stretch;
        }

        .mitb-report-row>div {
            display: flex;
            min-width: 0;
        }

        .mitb-box {
            width: 100%;
            min-width: 0;
            display: flex;
            flex-direction: column;
            background: #fff;
            border: 1px solid #e6ebef;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 13px rgba(0, 0, 0, .055);
            transition: box-shadow .25s ease;
        }

        .mitb-box:hover {
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        }


        /* =========================================================
   REPORT HEADERS
========================================================= */

        .mitb-box-header {
            min-height: 73px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 12px 16px;
            background: #fff;
            border-bottom: 2px solid #f4ba40;
        }

        .mitb-box-header.given {
            color: #bb171d;
        }

        .mitb-box-header.received {
            color: #0f7b3f;
        }

        .mitb-heading {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
        }

        .mitb-heading-icon {
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #fff1f1;
            color: #bb171d;
            font-size: 16px;
        }

        .received .mitb-heading-icon {
            background: #eaf8f0;
            color: #0f7b3f;
        }

        .mitb-heading-content h4 {
            margin: 0;
            font-size: 14px;
            font-weight: 750;
            line-height: 1.3;
        }

        .mitb-heading-content small {
            display: block;
            margin-top: 3px;
            color: #929da5;
            font-size: 10px;
            font-weight: 500;
        }

        .count-badge {
            min-width: 37px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 750;
        }

        .given-count {
            background: #fff0f0;
            color: #bb171d;
        }

        .received-count {
            background: #eaf8f0;
            color: #0f7b3f;
        }


        /* =========================================================
   COLUMN HEADERS
========================================================= */

        .mitb-columns {
            min-height: 39px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            background: #f7f9fa;
            border-bottom: 1px solid #e9eef1;
            color: #71808b;
            font-size: 10px;
            font-weight: 750;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .col-member {
            flex: 3;
            min-width: 0;
        }

        .col-amount {
            flex: 1.3;
            min-width: 0;
        }

        .col-date {
            flex: 1.4;
            min-width: 0;
        }

        .col-action {
            flex: .65;
            min-width: 30px;
            text-align: center;
        }


        /* =========================================================
   REPORT LIST
========================================================= */

        .mitb-list {
            flex: 1;
            min-height: 120px;
            max-height: 520px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: #cbd6dc #f5f7f8;
        }

        .mitb-list::-webkit-scrollbar {
            width: 5px;
        }

        .mitb-list::-webkit-scrollbar-thumb {
            background: #cbd6dc;
            border-radius: 10px;
        }

        .mitb-row {
            min-height: 60px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border-bottom: 1px solid #f0f2f4;
            transition: background .2s ease;
        }

        .mitb-row:hover {
            background: #fffaf0;
        }

        .member-info {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .member-info img {
            width: 36px;
            height: 36px;
            flex: 0 0 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #eef1f3;
        }

        .mitb-row .name {
            margin: 0;
            color: #344451;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        .mitb-row .amount {
            color: #bb171d;
            font-size: 12px;
            font-weight: 750;
            white-space: nowrap;
        }

        .received-row:hover {
            background: #fffaf0;
        }

        .received-amount {
            color: #0f7b3f !important;
        }

        .delete {
            color: #bb171d;
            font-size: 14px;
            cursor: pointer;
        }

        .delete:hover {
            color: #e2a500;
        }

        .received-delete {
            color: #0f7b3f;
        }


        /* =========================================================
   EMPTY STATE
========================================================= */

        .empty {
            min-height: 150px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 25px;
            color: #9ba6ad;
            font-size: 12px;
        }

        .empty i {
            font-size: 27px;
            color: #c6d0d6;
        }

        .empty p {
            margin: 0;
        }


        /* =========================================================
   PAGINATION
========================================================= */

        .mitb-pagination {
            min-height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 9px 10px;
            border-top: 1px solid #f0f2f4;
        }

        .mitb-pagination .pagination {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 5px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .mitb-pagination .pagination li a,
        .mitb-pagination .pagination li span {
            min-width: 29px;
            height: 29px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 8px;
            border: 1px solid #e1e7eb;
            border-radius: 7px;
            background: #fff;
            color: #536572;
            font-size: 11px;
            text-decoration: none;
            transition: all .2s ease;
        }

        .mitb-pagination .pagination li.active span {
            background: #174f70;
            border-color: #174f70;
            color: #fff;
        }

        .mitb-pagination .pagination li a:hover {
            background: #174f70;
            border-color: #174f70;
            color: #fff;
        }

        .mitb-pagination .pagination li.disabled span {
            opacity: .4;
        }


        /* =========================================================
   AJAX TABLE
========================================================= */

        .modern-table-wrapper {
            width: 100%;
            overflow-x: auto;
            background: #fff;
            border-radius: 10px;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .modern-table thead {
            background: #08426b;
            color: #fff;
        }

        .modern-table th,
        .modern-table td {
            padding: 11px 12px;
            border-bottom: 1px solid #edf0f2;
            text-align: left;
            vertical-align: middle;
        }

        .modern-table tbody tr:hover {
            background: #fff8e8;
        }

        .modern-table .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
        }


        /* =========================================================
   TABLET
========================================================= */

        @media (max-width: 1199px) {

            .meeting-filter-row {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }

            .meeting-search {
                grid-column: span 2;
            }

            .meeting-filter-actions {
                grid-column: 1 / -1;
            }

            .mitb-columns {
                font-size: 9px;
                gap: 5px;
                padding-left: 9px;
                padding-right: 9px;
            }

            .mitb-row {
                padding-left: 9px;
                padding-right: 9px;
                gap: 5px;
            }
        }


        /* =========================================================
   MOBILE
========================================================= */

        @media (max-width: 767px) {

            .mitb-page {
                padding: 8px 5px;
            }

            .mitb-page-header {
                margin-bottom: 12px;
            }

            .mitb-title-content h5 {
                font-size: 15px;
            }

            .mitb-title-content p {
                font-size: 10px;
            }

            .summary-card {
                min-height: 82px;
                padding: 10px;
                gap: 9px;
            }

            .summary-icon {
                width: 37px;
                height: 37px;
                flex-basis: 37px;
                font-size: 15px;
            }

            .summary-title {
                font-size: 10px;
            }

            .summary-value {
                font-size: 17px;
            }

            .summary-subtitle {
                font-size: 9px;
            }

            .meeting-filter-card .card-body {
                padding: 11px !important;
            }

            .meeting-filter-row {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .meeting-search {
                grid-column: 1 / -1;
            }

            .meeting-filter-actions {
                grid-column: 1 / -1;
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 7px;
            }

            .filter-btn {
                width: 100%;
                min-width: 0;
                padding: 0 6px;
            }

            .mitb-report-row {
                gap: 12px;
            }

            .mitb-report-row>div {
                width: 100%;
            }

            .mitb-box-header {
                min-height: 64px;
                padding: 10px 12px;
            }

            .mitb-heading-icon {
                width: 34px;
                height: 34px;
                flex-basis: 34px;
                font-size: 14px;
            }

            .mitb-heading-content h4 {
                font-size: 13px;
            }

            .mitb-heading-content small {
                font-size: 9px;
            }

            .mitb-columns {
                display: none;
            }

            .mitb-list {
                max-height: 420px;
            }

            .mitb-row {
                flex-wrap: wrap;
                align-items: flex-start;
                padding: 10px;
                gap: 7px;
            }

            .member-info img {
                width: 34px;
                height: 34px;
                flex-basis: 34px;
            }

            .mitb-row .name {
                font-size: 12px;
            }

            .mitb-row .amount {
                font-size: 13px;
            }

            .mitb-pagination {
                min-height: 45px;
            }

        }


        /* =========================================================
   SMALL MOBILE
========================================================= */

        @media (max-width: 400px) {

            .mitb-page-header {
                gap: 7px;
            }

            .mitb-title-icon {
                width: 34px;
                height: 34px;
                flex-basis: 34px;
            }

            .add-meeting-btn {
                height: 35px;
                gap: 5px;
                padding: 3px 9px 3px 5px;
                font-size: 10px;
            }

            .add-meeting-icon {
                width: 25px;
                height: 25px;
                flex-basis: 25px;
            }

            .summary-card {
                padding: 9px;
                gap: 7px;
            }

            .summary-value {
                font-size: 15px;
            }

            .meeting-filter-row {
                grid-template-columns: 1fr;
            }

            .meeting-search,
            .meeting-filter,
            .meeting-filter-actions {
                grid-column: 1 / -1;
            }

            .meeting-filter-actions {
                grid-template-columns: repeat(3, 1fr);
            }

        }


        /* =========================================================
   REDUCED MOTION
========================================================= */

        @media (prefers-reduced-motion: reduce) {

            .mitb-page *,
            .mitb-page *::before,
            .mitb-page *::after {
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

            <div class="page-content mitb-page">

                <!-- =====================================================
         PAGE HEADER
    ====================================================== -->
                <div class="mitb-page-header">

                    <div class="mitb-page-title">
                        <div class="mitb-title-icon">
                            <i class="fa fa-bar-chart"></i>
                        </div>

                        <div class="mitb-title-content">
                            <h5>MITB Reports</h5>
                            <p>Track business generated and received</p>
                        </div>
                    </div>

                    <button type="button"
                        class="add-meeting-btn"
                        onclick="add_mitb();">

                        <span class="add-meeting-icon">
                            <i class="fa fa-plus"></i>
                        </span>

                        <span class="add-meeting-text">
                            Submit MITB
                        </span>

                    </button>

                </div>


                <!-- =====================================================
         BUSINESS SUMMARY
    ====================================================== -->
                <div class="row g-3 business-summary">

                    <!-- BUSINESS REVENUE -->
                    <div class="col-xl-4 col-md-4 col-sm-6 col-12">
                        <div class="summary-card revenue-card">

                            <div class="summary-icon">
                                <i class="fa fa-inr"></i>
                            </div>

                            <div class="summary-content">

                                <div class="summary-title">
                                    Business Revenue
                                </div>

                                <div class="summary-value">
                                    ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))
                                            ->format($totalRevenue ?? 0, 2) ?>
                                </div>

                                <div class="summary-subtitle">
                                    Total business generated
                                </div>

                            </div>
                        </div>
                    </div>


                    <!-- AVG MEMBER VALUE -->
                    <div class="col-xl-4 col-md-4 col-sm-6 col-12">
                        <div class="summary-card member-value-card">

                            <div class="summary-icon">
                                <i class="fa fa-user"></i>
                            </div>

                            <div class="summary-content">

                                <div class="summary-title">
                                    Avg Member Value
                                </div>

                                <div class="summary-value">
                                    ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))
                                            ->format($avgMemberValue ?? 0, 2) ?>
                                </div>

                                <div class="summary-subtitle">
                                    Average value per member
                                </div>

                            </div>
                        </div>
                    </div>


                    <!-- TOTAL RECORDS -->
                    <div class="col-xl-4 col-md-4 col-sm-6 col-12">
                        <div class="summary-card records-card">

                            <div class="summary-icon">
                                <i class="fa fa-database"></i>
                            </div>

                            <div class="summary-content">

                                <div class="summary-title">
                                    Total Records
                                </div>

                                <div class="summary-value">
                                    <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))
                                        ->format($totalRecords ?? 0) ?>
                                </div>

                                <div class="summary-subtitle">
                                    Total records available
                                </div>

                            </div>
                        </div>
                    </div>

                </div>


                <!-- =====================================================
         FILTER SECTION
    ====================================================== -->

                <div class="card meeting-filter-card">
                    <div class="card-body">

                        <form id="search-form" onsubmit="return false;">

                            <div class="row">
                                <div class="col-md-3">
                                    <!-- SEARCH -->
                                    <div class="meeting-search">
                                        <label class="filter-label">
                                            <i class="fa fa-search"></i> Search
                                        </label>
                                        <div class="filter-input-wrap">
                                            <i class="fa fa-search filter-icon"></i>
                                            <input type="text" id="search"
                                                class="form-control meeting-input"
                                                placeholder="Search...">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <!-- CATEGORY -->
                                    <div class="meeting-filter">
                                        <label class="filter-label">
                                            <i class="fa fa-tag"></i> Category
                                        </label>
                                        <div class="filter-input-wrap">
                                            <i class="fa fa-tag filter-icon"></i>
                                            <select class="form-select meeting-select" id="category">
                                                <option value="">All Categories</option>
                                                <option value="New">New</option>
                                                <option value="Visitor">Visitor</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <!-- BUSINESS TYPE -->
                                    <div class="meeting-filter">
                                        <label class="filter-label">
                                            <i class="fa fa-briefcase"></i> Business
                                        </label>
                                        <div class="filter-input-wrap">
                                            <i class="fa fa-briefcase filter-icon"></i>
                                            <select class="form-select meeting-select" id="business_type">
                                                <option value="">All Business</option>
                                                <option value="New">New</option>
                                                <option value="Repeat">Repeat</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">


                                    <!-- CHAPTER -->
                                    <div class="meeting-filter chapter-filter">
                                        <label class="filter-label">
                                            <i class="fa fa-users"></i> Chapter
                                        </label>
                                        <div class="filter-input-wrap">
                                            <i class="fa fa-users filter-icon"></i>
                                            <select class="form-select meeting-select"
                                                id="chapter_code" name="chapter_code">
                                                <option value="">All Chapters</option>
                                                <?php if (!empty($chapters)) : ?>
                                                    <?php foreach ($chapters as $chapter) : ?>
                                                        <option value="<?= esc($chapter['chapter_code']) ?>">
                                                            <?= esc($chapter['chapter_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">

                                    <!-- DURATION -->
                                    <div class="meeting-filter">
                                        <label class="filter-label">
                                            <i class="fa fa-clock-o"></i> Duration
                                        </label>
                                        <div class="filter-input-wrap">
                                            <i class="fa fa-clock-o filter-icon"></i>
                                            <select class="form-select meeting-select" id="month_filter">
                                                <option value="">All Duration</option>
                                                <option value="7">7 Days</option>
                                                <option value="30">1 Month</option>
                                                <option value="180">6 Months</option>
                                                <option value="365">12 Months</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <!-- FROM DATE -->
                                    <div class="meeting-filter date-filter">
                                        <label class="filter-label">
                                            <i class="fa fa-calendar"></i> From Date
                                        </label>
                                        <div class="filter-input-wrap">
                                            <i class="fa fa-calendar filter-icon"></i>
                                            <input type="date" id="from_date"
                                                class="form-control meeting-input">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <!-- TO DATE -->
                                    <div class="meeting-filter date-filter">
                                        <label class="filter-label">
                                            <i class="fa fa-calendar"></i> To Date
                                        </label>
                                        <div class="filter-input-wrap">
                                            <i class="fa fa-calendar filter-icon"></i>
                                            <input type="date" id="to_date"
                                                class="form-control meeting-input">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">


                                    <!-- ACTION BUTTONS -->
                                    <div class="meeting-filter-actions mt-3">

                                        <button type="button" id="reset-btn"
                                            class="filter-btn reset-filter">
                                            <i class="fa fa-refresh"></i>
                                            <span>Reset</span>
                                        </button>

                                        <button type="button" id="export-excel"
                                            class="filter-btn excel-filter"
                                            title="Export Excel">
                                            <i class="fa fa-file-excel-o"></i>
                                            <span>Excel</span>
                                        </button>

                                        <button type="button" id="export-pdf"
                                            class="filter-btn pdf-filter"
                                            title="Export PDF">
                                            <i class="fa fa-file-pdf-o"></i>
                                            <span>PDF</span>
                                        </button>
                                    </div>




                                </div>

                            </div>

                        </form>

                    </div>
                </div>


                <!-- =====================================================
         HIDDEN AJAX TABLE
    ====================================================== -->
                <div class="modern-table-wrapper">

                    <table class="modern-table">

                        <tbody id="list_data"></tbody>

                    </table>

                </div>
                <style>
                    /* =========================================
   MITB PAGINATION
========================================= */

                    .mitb-pagination-row td {
                        padding: 16px 12px !important;
                        border-bottom: 0 !important;
                    }

                    .mitb-pagination-wrapper {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        flex-wrap: wrap;
                        gap: 15px;
                        width: 100%;
                    }

                    .mitb-pagination-info {
                        color: #64748b;
                        font-size: 13px;
                    }

                    .mitb-pagination-info strong {
                        color: #0a8260;
                        font-weight: 700;
                    }

                    .mitb-pagination {
                        display: flex;
                        align-items: center;
                        justify-content: flex-end;
                        flex-wrap: wrap;
                        gap: 5px;
                        list-style: none;
                        padding: 0;
                        margin: 0;
                    }

                    .mitb-pagination li {
                        margin: 0;
                        padding: 0;
                    }

                    .mitb-pagination-link {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        min-width: 34px;
                        height: 34px;
                        padding: 0 10px;
                        border: 1px solid #e2e8f0;
                        border-radius: 7px;
                        background: #fff;
                        color: #475569;
                        font-size: 13px;
                        font-weight: 600;
                        text-decoration: none;
                        transition: all 0.2s ease;
                    }

                    .mitb-pagination-link:hover {
                        background: #e8f5f0;
                        color: #0a8260;
                        border-color: #0a8260;
                    }

                    .mitb-pagination-link.active {
                        background: #0a8260;
                        color: #fff;
                        border-color: #0a8260;
                        box-shadow: 0 2px 5px rgba(10, 130, 96, 0.2);
                    }

                    .mitb-pagination-link.disabled {
                        opacity: 0.4;
                        pointer-events: none;
                        cursor: not-allowed;
                    }

                    .mitb-pagination-dots {
                        padding: 0 5px;
                        color: #94a3b8;
                        font-size: 13px;
                    }

                    @media(max-width: 576px) {

                        .mitb-pagination-wrapper {
                            flex-direction: column;
                            align-items: center;
                        }

                        .mitb-pagination {
                            justify-content: center;
                        }

                    }
                </style>

                <!-- =====================================================
         MITB REPORTS
    ====================================================== -->
                <div class="row g-3 mitb-report-row" id="old_results">

                    <style>
                        /* =========================================
   MITB PAGINATION COMMON
========================================= */

                        .mitb-pagination {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            padding: 16px 12px;
                            margin-top: 12px;
                        }

                        .mitb-pagination nav {
                            display: flex;
                            justify-content: center;
                            width: 100%;
                        }

                        .mitb-pagination ul {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            flex-wrap: wrap;
                            gap: 6px;
                            padding: 0;
                            margin: 0;
                            list-style: none;
                        }

                        .mitb-pagination ul li {
                            margin: 0;
                        }

                        .mitb-pagination ul li a,
                        .mitb-pagination ul li span {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            min-width: 36px;
                            height: 36px;
                            padding: 0 11px;
                            border: 1px solid #e2e8f0;
                            border-radius: 8px;
                            background: #ffffff;
                            color: #344054;
                            font-size: 13px;
                            font-weight: 600;
                            text-decoration: none;
                            transition: all 0.25s ease;
                        }

                        /* HOVER */
                        .mitb-pagination ul li a:hover {
                            background: #f1f5f9;
                            border-color: #94a3b8;
                            color: #0f172a;
                        }

                        /* =========================================
   GIVEN ACTIVE PAGE - GREEN
========================================= */

                        .given-pagination ul li.active a,
                        .given-pagination ul li.active span,
                        .given-pagination ul li a[aria-current="page"],
                        .given-pagination ul li span[aria-current="page"] {
                            background: #bfa76d !important;
                            border-color: #bfa76d !important;
                            color: #ffffff !important;
                            font-weight: 700;
                            box-shadow: 0 3px 8px rgba(8, 127, 91, 0.22);
                        }

                        /* =========================================
   RECEIVED ACTIVE PAGE - PINK
========================================= */

                        .received-pagination ul li.active a,
                        .received-pagination ul li.active span,
                        .received-pagination ul li a[aria-current="page"],
                        .received-pagination ul li span[aria-current="page"] {
                            background: #bfa76d !important;
                            border-color: #bfa76d !important;
                            color: #ffffff !important;
                            font-weight: 700;
                            box-shadow: 0 3px 8px rgba(237, 45, 123, 0.22);
                        }

                        /* DISABLED */
                        .mitb-pagination ul li.disabled span,
                        .mitb-pagination ul li.disabled a {
                            opacity: 0.45;
                            cursor: not-allowed;
                        }

                        /* MOBILE */
                        @media (max-width: 576px) {
                            .mitb-pagination {
                                padding: 12px 5px;
                            }

                            .mitb-pagination ul {
                                gap: 4px;
                            }

                            .mitb-pagination ul li a,
                            .mitb-pagination ul li span {
                                min-width: 32px;
                                height: 32px;
                                padding: 0 8px;
                                font-size: 12px;
                            }
                        }
                    </style>
                    <!-- =================================================
             MITB RECEIVED
        ================================================== -->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-12">

                        <div class="mitb-box" >

                            <!-- HEADER -->
                            <div class="mitb-box-header given">

                                <div class="mitb-heading">

                                    <div class="mitb-heading-icon">
                                        <i class="fa fa-handshake"></i>
                                    </div>

                                    <div class="mitb-heading-content">

                                        <h4>MITB Received</h4>

                                        <small>
                                            Business received from members
                                        </small>

                                    </div>

                                </div>

                                <span class="count-badge given-count">
                                    <?= $givenCount ?>
                                </span>

                            </div>


                            <!-- COLUMN TITLES -->
                            <div class="mitb-columns">

                                <div class="col-member">From</div>

                                <div class="col-member">To</div>

                                <div class="col-amount">Amount</div>

                                <div class="col-date">Date</div>

                                <div class="col-action">Action</div>

                            </div>


                            <!-- LIST -->
                            <div class="mitb-list" style="overflow-x: scroll;">

                                <?php if (!empty($mitb_given)): ?>

                                    <?php foreach ($mitb_given as $row): ?>

                                        <?= view(
                                            'include/member/card/mitb_card2',
                                            ['row' => $row]
                                        ) ?>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <div class="empty">

                                        <i class="fa fa-folder-open-o"></i>

                                        <p>No Records Found</p>

                                    </div>

                                <?php endif; ?>

                            </div>


                            <!-- PAGINATION -->
                            <div class="mitb-pagination given-pagination">

                                <?= $pager_given->links('given', 'default_full') ?>

                            </div>

                        </div>

                    </div>



                    <!-- =================================================
             MITB GIVEN
        ================================================== -->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-12">

                        <div class="mitb-box" >

                            <!-- HEADER -->
                            <div class="mitb-box-header received">

                                <div class="mitb-heading">

                                    <div class="mitb-heading-icon">
                                        <i class="fa fa-gift"></i>
                                    </div>

                                    <div class="mitb-heading-content">

                                        <h4>MITB Given</h4>

                                        <small>
                                            Business referred to members
                                        </small>

                                    </div>

                                </div>

                                <span class="count-badge received-count">
                                    <?= $receivedCount ?>
                                </span>

                            </div>


                            <!-- COLUMN TITLES -->
                            <div class="mitb-columns">

                                <div class="col-member">From</div>

                                <div class="col-member">To</div>

                                <div class="col-amount">Amount</div>

                                <div class="col-date">Date</div>

                            </div>


                            <!-- LIST -->
                            <div class="mitb-list">

                                <?php if (!empty($mitb_received)): ?>

                                    <?php foreach ($mitb_received as $row): ?>

                                        <?= view(
                                            'include/member/card/mitb_row_received',
                                            ['row' => $row]
                                        ) ?>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <div class="empty">

                                        <i class="fa fa-folder-open-o"></i>

                                        <p>No Records Found</p>

                                    </div>

                                <?php endif; ?>

                            </div>


                            <!-- PAGINATION -->
                            <div class="mitb-pagination received-pagination">

                                <?= $pager_received->links('received', 'default_full') ?>

                            </div>

                        </div>

                    </div>

                </div>



            </div>



            <!-- MONTHLY BUSINESS SUMMARY -->
            <div class="row g-3 mb-4 monthly-summary-row">

                <!-- BUSINESS GIVEN -->
                <div class="col-xl-3 col-md-6 col-12">

                    <div class="monthly-stat-card stat-given">

                        <div class="stat-card-top">
                            <div class="stat-icon">
                                <i class="fas fa-arrow-up-right-from-square"></i>
                            </div>

                            <span class="stat-tag">GIVEN</span>
                        </div>

                        <div class="stat-content">

                            <span class="stat-label">Business Given</span>

                            <h4>
                                ₹<?= number_format(array_sum($monthlyChart['given']), 2) ?>
                            </h4>

                            <div class="stat-bottom">
                                <span class="stat-count-icon">
                                    <i class="fas fa-exchange-alt"></i>
                                </span>

                                <span>
                                    <strong>
                                        <?= number_format(array_sum($monthlyChart['given_count'])) ?>
                                    </strong>
                                    Transactions
                                </span>

                                <span class="stat-period">
                                    <?= $chartYear ?>
                                </span>
                            </div>

                        </div>

                        <div class="stat-decoration"></div>

                    </div>

                </div>

                <!-- BUSINESS RECEIVED -->
                <div class="col-xl-3 col-md-6 col-12">

                    <div class="monthly-stat-card stat-received">

                        <div class="stat-card-top">
                            <div class="stat-icon">
                                <i class="fas fa-arrow-down-left-from-square"></i>
                            </div>

                            <span class="stat-tag">RECEIVED</span>
                        </div>

                        <div class="stat-content">

                            <span class="stat-label">Business Received</span>

                            <h4>
                                ₹<?= number_format(array_sum($monthlyChart['received']), 2) ?>
                            </h4>

                            <div class="stat-bottom">
                                <span class="stat-count-icon">
                                    <i class="fas fa-exchange-alt"></i>
                                </span>

                                <span>
                                    <strong>
                                        <?= number_format(array_sum($monthlyChart['received_count'])) ?>
                                    </strong>
                                    Transactions
                                </span>

                                <span class="stat-period">
                                    <?= $chartYear ?>
                                </span>
                            </div>

                        </div>

                        <div class="stat-decoration"></div>

                    </div>

                </div>

                <!-- GIVEN RECORDS -->
                <div class="col-xl-3 col-md-6 col-12">

                    <div class="monthly-stat-card stat-given-count">

                        <div class="stat-card-top">
                            <div class="stat-icon">
                                <i class="fas fa-file-export"></i>
                            </div>

                            <span class="stat-tag">ACTIVITY</span>
                        </div>

                        <div class="stat-content">

                            <span class="stat-label">Given Records</span>

                            <h4>
                                <?= number_format(array_sum($monthlyChart['given_count'])) ?>
                            </h4>

                            <div class="stat-bottom">
                                <span class="stat-count-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </span>

                                <span>
                                    Total records given
                                </span>

                                <span class="stat-period">
                                    <?= $chartYear ?>
                                </span>
                            </div>

                        </div>

                        <div class="stat-decoration"></div>

                    </div>

                </div>

                <!-- RECEIVED RECORDS -->
                <div class="col-xl-3 col-md-6 col-12">

                    <div class="monthly-stat-card stat-received-count">

                        <div class="stat-card-top">
                            <div class="stat-icon">
                                <i class="fas fa-file-import"></i>
                            </div>

                            <span class="stat-tag">ACTIVITY</span>
                        </div>

                        <div class="stat-content">

                            <span class="stat-label">Received Records</span>

                            <h4>
                                <?= number_format(array_sum($monthlyChart['received_count'])) ?>
                            </h4>

                            <div class="stat-bottom">
                                <span class="stat-count-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </span>

                                <span>
                                    Total records received
                                </span>

                                <span class="stat-period">
                                    <?= $chartYear ?>
                                </span>
                            </div>

                        </div>

                        <div class="stat-decoration"></div>

                    </div>

                </div>

            </div>


            <!-- MONTHLY BUSINESS CHART -->
            <div class="monthly-performance-card p-2 mb-5">

                <div class="monthly-performance-header">

                    <div class="performance-heading">

                        <div class="performance-heading-icon">
                            <i class="fas fa-chart-column"></i>
                        </div>

                        <div>
                            <h5>Monthly Business Performance</h5>
                            <p>Business volume and transaction activity</p>
                        </div>

                    </div>

                    <div class="performance-year">
                        <i class="far fa-calendar-alt"></i>
                        <span><?= $chartYear ?></span>
                    </div>

                </div>

                <div class="monthly-chart-container">
                    <canvas id="monthlyBusinessChart"></canvas>
                </div>

                <div class="chart-footer-info">
                    <span>
                        <i class="fas fa-info-circle"></i>
                        Amounts and transaction counts are shown separately.
                    </span>
                </div>

            </div>
            <style>
                /* ==========================================
   MONTHLY BUSINESS SUMMARY CARDS
========================================== */

                .monthly-summary-row {
                    margin-top: 5px;
                }

                .monthly-stat-card {
                    position: relative;
                    min-height: 175px;
                    height: 100%;
                    padding: 19px 20px;
                    border-radius: 15px;
                    border: 1px solid #e9edf4;
                    background: #fff;
                    overflow: hidden;
                    transition: all .3s ease;
                    box-shadow: 0 3px 12px rgba(16, 27, 54, .045);
                    z-index: 1;
                }

                .monthly-stat-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 25px rgba(16, 27, 54, .10);
                }

                /* TOP SECTION */

                .stat-card-top {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 17px;
                }

                .stat-icon {
                    width: 43px;
                    height: 43px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 17px;
                    transition: transform .3s ease;
                }

                .monthly-stat-card:hover .stat-icon {
                    transform: scale(1.1) rotate(-5deg);
                }

                .stat-tag {
                    padding: 5px 10px;
                    font-size: 9px;
                    font-weight: 800;
                    letter-spacing: 1px;
                    border-radius: 20px;
                    background: rgba(255, 255, 255, .75);
                }

                /* CARD CONTENT */

                .stat-content {
                    position: relative;
                    z-index: 2;
                }

                .stat-label {
                    display: block;
                    color: #64748b;
                    font-size: 12px;
                    font-weight: 600;
                    margin-bottom: 5px;
                }

                .stat-content h4 {
                    font-size: clamp(19px, 1.5vw, 25px);
                    font-weight: 800;
                    color: #101b36;
                    margin: 0 0 13px;
                    letter-spacing: -.5px;
                    overflow-wrap: anywhere;
                }

                /* BOTTOM INFORMATION */

                .stat-bottom {
                    display: flex;
                    align-items: center;
                    gap: 7px;
                    font-size: 10px;
                    color: #64748b;
                    flex-wrap: wrap;
                }

                .stat-bottom strong {
                    color: #101b36;
                    font-size: 12px;
                }

                .stat-count-icon {
                    width: 24px;
                    height: 24px;
                    border-radius: 7px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 10px;
                }

                .stat-period {
                    margin-left: auto;
                    font-size: 10px;
                    font-weight: 700;
                    padding: 4px 8px;
                    border-radius: 6px;
                    background: rgba(255, 255, 255, .75);
                }

                /* DECORATIVE CIRCLE */

                .stat-decoration {
                    position: absolute;
                    width: 115px;
                    height: 115px;
                    border-radius: 50%;
                    right: -38px;
                    bottom: -57px;
                    opacity: .12;
                    transition: transform .4s ease;
                }

                .monthly-stat-card:hover .stat-decoration {
                    transform: scale(1.35);
                }

                /* GIVEN */

                .stat-given {
                    background: linear-gradient(135deg, #f4f6fc 0%, #eaf0ff 100%);
                    border-color: #e0e7fa;
                }

                .stat-given .stat-icon {
                    background: #101b36;
                    color: #fff;
                }

                .stat-given .stat-tag {
                    color: #101b36;
                }

                .stat-given .stat-count-icon {
                    background: #dce5fb;
                    color: #101b36;
                }

                .stat-given .stat-decoration {
                    background: #101b36;
                }

                /* RECEIVED */

                .stat-received {
                    background: linear-gradient(135deg, #fbf9f3 0%, #f5efdf 100%);
                    border-color: #eee5d0;
                }

                .stat-received .stat-icon {
                    background: #bfa76d;
                    color: #fff;
                }

                .stat-received .stat-tag {
                    color: #967c42;
                }

                .stat-received .stat-count-icon {
                    background: #eadfbe;
                    color: #967c42;
                }

                .stat-received .stat-decoration {
                    background: #bfa76d;
                }

                /* GIVEN COUNT */

                .stat-given-count {
                    background: linear-gradient(135deg, #f7f4ff 0%, #eee9ff 100%);
                    border-color: #e7dffc;
                }

                .stat-given-count .stat-icon {
                    background: #7050c8;
                    color: #fff;
                }

                .stat-given-count .stat-tag {
                    color: #7050c8;
                }

                .stat-given-count .stat-count-icon {
                    background: #e1d9fb;
                    color: #7050c8;
                }

                .stat-given-count .stat-decoration {
                    background: #7050c8;
                }

                /* RECEIVED COUNT */

                .stat-received-count {
                    background: linear-gradient(135deg, #effaf6 0%, #e0f5ec 100%);
                    border-color: #d9efe4;
                }

                .stat-received-count .stat-icon {
                    background: #168b68;
                    color: #fff;
                }

                .stat-received-count .stat-tag {
                    color: #168b68;
                }

                .stat-received-count .stat-count-icon {
                    background: #d0ecdf;
                    color: #168b68;
                }

                .stat-received-count .stat-decoration {
                    background: #168b68;
                }


                /* ==========================================
   MONTHLY PERFORMANCE CHART CARD
========================================== */

                .monthly-performance-card {
                    background: #fff;
                    border: 1px solid #e9edf4;
                    border-radius: 16px;
                    padding: 20px 22px 14px;
                    box-shadow: 0 3px 15px rgba(16, 27, 54, .05);
                    overflow: hidden;
                }

                /* CHART HEADER */

                .monthly-performance-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 15px;
                    padding-bottom: 17px;
                    border-bottom: 1px solid #edf0f5;
                    margin-bottom: 18px;
                }

                .performance-heading {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                .performance-heading-icon {
                    width: 43px;
                    height: 43px;
                    border-radius: 12px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background: #101b36;
                    color: #bfa76d;
                    font-size: 18px;
                }

                .performance-heading h5 {
                    margin: 0 0 5px;
                    font-size: 16px;
                    font-weight: 800;
                    color: #101b36;
                }

                .performance-heading p {
                    margin: 0;
                    font-size: 11px;
                    color: #8993a5;
                }

                .performance-year {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    background: #f4f6fa;
                    padding: 8px 13px;
                    border-radius: 8px;
                    font-size: 12px;
                    font-weight: 700;
                    color: #101b36;
                }

                /* CHART */

                .monthly-chart-container {
                    position: relative;
                    width: 100%;
                    height: 370px;
                }

                /* FOOTER */

                .chart-footer-info {
                    border-top: 1px solid #edf0f5;
                    margin-top: 12px;
                    padding-top: 12px;
                    color: #929bad;
                    font-size: 10px;
                }

                .chart-footer-info i {
                    margin-right: 5px;
                    color: #bfa76d;
                }


                /* ==========================================
   RESPONSIVE DESIGN
========================================== */

                @media(max-width: 991px) {

                    .monthly-stat-card {
                        min-height: 165px;
                    }

                }

                @media(max-width: 576px) {

                    .monthly-stat-card {
                        min-height: 155px;
                        padding: 16px;
                        border-radius: 12px;
                    }

                    .stat-card-top {
                        margin-bottom: 12px;
                    }

                    .stat-icon {
                        width: 37px;
                        height: 37px;
                        font-size: 15px;
                    }

                    .stat-content h4 {
                        font-size: 20px;
                    }

                    .monthly-performance-card {
                        padding: 14px;
                        border-radius: 12px;
                    }

                    .monthly-performance-header {
                        align-items: flex-start;
                    }

                    .performance-heading h5 {
                        font-size: 13px;
                    }

                    .performance-heading p {
                        font-size: 10px;
                    }

                    .performance-heading-icon {
                        width: 36px;
                        height: 36px;
                        font-size: 15px;
                    }

                    .performance-year {
                        padding: 7px 9px;
                        font-size: 11px;
                    }

                    .monthly-chart-container {
                        height: 300px;
                    }

                }
            </style>
            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    const chartElement = document.getElementById('monthlyBusinessChart');

                    if (!chartElement) return;

                    const chartData = <?= json_encode(
                                            $monthlyChart,
                                            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
                                        ) ?>;

                    const ctx = chartElement.getContext('2d');

                    new Chart(ctx, {

                        type: 'bar',

                        data: {

                            labels: chartData.labels,

                            datasets: [

                                // GIVEN AMOUNT
                                {
                                    type: 'bar',
                                    label: 'Business Given (₹)',
                                    data: chartData.given,

                                    backgroundColor: '#101b36',
                                    borderColor: '#101b36',

                                    borderRadius: 5,
                                    borderWidth: 1,

                                    yAxisID: 'y',

                                    maxBarThickness: 32,
                                    order: 4
                                },

                                // RECEIVED AMOUNT
                                {
                                    type: 'bar',
                                    label: 'Business Received (₹)',
                                    data: chartData.received,

                                    backgroundColor: '#bfa76d',
                                    borderColor: '#bfa76d',

                                    borderRadius: 5,
                                    borderWidth: 1,

                                    yAxisID: 'y',

                                    maxBarThickness: 32,
                                    order: 4
                                },

                                // GIVEN COUNT
                                {
                                    type: 'line',
                                    label: 'Given Count',
                                    data: chartData.given_count,

                                    borderColor: '#3978e8',
                                    backgroundColor: '#3978e8',

                                    pointBackgroundColor: '#3978e8',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,

                                    pointRadius: 5,
                                    pointHoverRadius: 7,

                                    borderWidth: 3,

                                    tension: 0.35,
                                    fill: false,

                                    yAxisID: 'y1',

                                    order: 1
                                },

                                // RECEIVED COUNT
                                {
                                    type: 'line',
                                    label: 'Received Count',
                                    data: chartData.received_count,

                                    borderColor: '#13a477',
                                    backgroundColor: '#13a477',

                                    pointBackgroundColor: '#13a477',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,

                                    pointRadius: 5,
                                    pointHoverRadius: 7,

                                    borderWidth: 3,

                                    tension: 0.35,
                                    fill: false,

                                    yAxisID: 'y1',

                                    order: 1
                                }

                            ]

                        },

                        options: {

                            responsive: true,

                            maintainAspectRatio: false,

                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },

                            plugins: {

                                legend: {

                                    position: 'top',

                                    align: 'end',

                                    labels: {

                                        usePointStyle: true,

                                        pointStyle: 'rectRounded',

                                        padding: 18,

                                        font: {
                                            size: 11,
                                            weight: '600'
                                        }

                                    }

                                },

                                tooltip: {

                                    backgroundColor: '#091938',

                                    titleFont: {
                                        size: 13,
                                        weight: 'bold'
                                    },

                                    bodyFont: {
                                        size: 12
                                    },

                                    padding: 12,

                                    callbacks: {

                                        label: function(context) {

                                            const value = Number(context.raw || 0);

                                            if (context.dataset.yAxisID === 'y') {

                                                return context.dataset.label + ': ₹' +
                                                    value.toLocaleString('en-IN', {
                                                        minimumFractionDigits: 2,
                                                        maximumFractionDigits: 2
                                                    });

                                            }

                                            return context.dataset.label + ': ' +
                                                value.toLocaleString('en-IN') +
                                                ' Transactions';

                                        }

                                    }

                                }

                            },

                            scales: {

                                x: {

                                    grid: {
                                        display: false
                                    },

                                    ticks: {
                                        color: '#64748b',
                                        font: {
                                            size: 12
                                        }
                                    }

                                },

                                // LEFT AXIS - AMOUNT
                                y: {

                                    type: 'linear',

                                    position: 'left',

                                    beginAtZero: true,

                                    title: {
                                        display: true,
                                        text: 'Business Amount (₹)',
                                        color: '#101b36',
                                        font: {
                                            weight: '600'
                                        }
                                    },

                                    grid: {
                                        color: '#edf0f5'
                                    },

                                    ticks: {

                                        color: '#64748b',

                                        callback: function(value) {

                                            return '₹' +
                                                Number(value).toLocaleString('en-IN');

                                        }

                                    }

                                },

                                // RIGHT AXIS - COUNT
                                y1: {

                                    type: 'linear',

                                    position: 'right',

                                    beginAtZero: true,

                                    title: {
                                        display: true,
                                        text: 'Number of Transactions',
                                        color: '#3978e8',
                                        font: {
                                            weight: '600'
                                        }
                                    },

                                    grid: {
                                        drawOnChartArea: false
                                    },

                                    ticks: {

                                        color: '#3978e8',

                                        precision: 0,

                                        callback: function(value) {
                                            return Number(value).toLocaleString('en-IN');
                                        }

                                    }

                                }

                            }

                        }

                    });

                });
            </script><br><br>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php echo view('include/member/popup/mitb.php') ?>
    <script>
        $(document).ready(function() {

            let request = null;
            let debounceTimer = null;

            const filterFields = [
                '#search',
                '#category',
                '#business_type',
                '#chapter_code',
                '#month_filter',
                '#from_date',
                '#to_date'
            ];

            /* =========================================
               GET FILTER VALUES
            ========================================= */
            function collectFilters() {

                return {
                    search: $('#search').val() || '',
                    category: $('#category').val() || '',
                    business_type: $('#business_type').val() || '',
                    chapter_code: $('#chapter_code').val() || '',
                    month_filter: $('#month_filter').val() || '',
                    from_date: $('#from_date').val() || '',
                    to_date: $('#to_date').val() || ''
                };
            }

            /* =========================================
               CHECK ACTIVE FILTERS
            ========================================= */
            function hasFilters(filters) {

                return Object.values(filters).some(function(value) {
                    return value !== '';
                });
            }

            /* =========================================
               RESTORE FILTERS FROM URL
            ========================================= */
            function restoreFilters() {

                const params = new URLSearchParams(window.location.search);

                filterFields.forEach(function(selector) {

                    const field = selector.replace('#', '');

                    if (params.has(field)) {
                        $(selector).val(params.get(field));
                    }

                });

                return {
                    ...collectFilters(),
                    page: Math.max(1, parseInt(params.get('page')) || 1)
                };
            }

            /* =========================================
               UPDATE URL WITHOUT RELOAD
            ========================================= */
            function updateURL(filters, page) {

                const params = new URLSearchParams();

                Object.keys(filters).forEach(function(key) {

                    if (filters[key] !== '') {
                        params.set(key, filters[key]);
                    }

                });

                if (page > 1) {
                    params.set('page', page);
                }

                const queryString = params.toString();

                const newURL = window.location.pathname +
                    (queryString ? '?' + queryString : '');

                window.history.pushState({}, '', newURL);
            }

            /* =========================================
               AJAX LOAD
            ========================================= */
            function loadUsers(page = 1, updateHistory = true) {

                const filters = collectFilters();

                page = Math.max(1, parseInt(page) || 1);

                if (updateHistory) {
                    updateURL(filters, page);
                }

                // No filters: show original table
                if (!hasFilters(filters)) {

                    if (request) {
                        request.abort();
                        request = null;
                    }

                    $('#list_data').hide().html('');
                    $('#old_results').show();

                    return;
                }

                const ajaxData = {
                    ...filters,
                    page: page,
                    per_page: 10
                };

                if (request) {
                    request.abort();
                }

                request = $.ajax({

                    type: 'POST',

                    url: "<?= site_url('member/mitb/get_filtered_data') ?>",

                    data: ajaxData,

                    beforeSend: function() {

                        $('#old_results').hide();

                        $('#list_data')
                            .show()
                            .html(`
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="spinner-border spinner-border-sm text-success"></div>
                                <span class="ms-2">Loading records...</span>
                            </td>
                        </tr>
                    `);

                    },

                    success: function(response) {

                        $('#list_data').html(response).show();

                    },

                    error: function(xhr, status) {

                        if (status !== 'abort') {

                            $('#list_data').html(`
                        <tr>
                            <td colspan="8" class="text-center text-danger py-3">
                                Unable to load records. Please try again.
                            </td>
                        </tr>
                    `);

                        }

                    },

                    complete: function() {
                        request = null;
                    }

                });

            }

            /* =========================================
               DEBOUNCE
            ========================================= */
            function debounceLoad() {

                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(function() {

                    loadUsers(1, true);

                }, 400);

            }

            /* =========================================
               FILTER EVENTS
            ========================================= */
            $('#search').on('keyup', debounceLoad);

            $('#category, #business_type, #chapter_code, #month_filter, #from_date, #to_date')
                .on('change', debounceLoad);

            $('#filter-btn').on('click', function(e) {

                e.preventDefault();

                clearTimeout(debounceTimer);

                loadUsers(1, true);

            });

            /* =========================================
               PAGINATION CLICK
            ========================================= */
            $(document).on('click', '.mitb-pagination-link', function(e) {

                e.preventDefault();

                const page = $(this).data('page');

                if ($(this).hasClass('disabled')) {
                    return;
                }

                loadUsers(page, true);

            });

            /* =========================================
               RESET FILTERS
            ========================================= */
            $('#reset-btn').on('click', function(e) {

                e.preventDefault();

                clearTimeout(debounceTimer);

                if (request) {
                    request.abort();
                    request = null;
                }

                $('#search-form')[0].reset();

                $('#list_data').hide().html('');

                $('#old_results').show();

                window.history.pushState({}, '', window.location.pathname);

            });

            /* =========================================
               EXPORT EXCEL
            ========================================= */
            $('#export-excel').on('click', function() {

                const params = new URLSearchParams(collectFilters());

                window.location.href =
                    "<?= site_url('member/mitb/export_excel') ?>?" +
                    params.toString();

            });

            /* =========================================
               EXPORT PDF
            ========================================= */
            $('#export-pdf').on('click', function() {

                const params = new URLSearchParams(collectFilters());

                window.location.href =
                    "<?= site_url('member/mitb/export_pdf') ?>?" +
                    params.toString();

            });

            /* =========================================
               BROWSER BACK / FORWARD
            ========================================= */
            window.addEventListener('popstate', function() {

                const state = restoreFilters();

                if (hasFilters(state)) {

                    loadUsers(state.page, false);

                } else {

                    if (request) {
                        request.abort();
                        request = null;
                    }

                    $('#list_data').hide().html('');
                    $('#old_results').show();

                }

            });

            /* =========================================
               INITIAL PAGE LOAD
            ========================================= */
            const initialState = restoreFilters();

            if (hasFilters(initialState)) {

                loadUsers(initialState.page, false);

            }

        });
    </script>
</body>

</html>