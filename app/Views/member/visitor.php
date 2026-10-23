<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Visitor Tracking</title>
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
   FILTER CARD
========================================================= */

        .filter-card {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 12px;
            background: #fff;
            border: 1px solid #e5ebf0;
            border-radius: 9px;
            box-shadow: 0 2px 10px rgba(8, 66, 107, .05);
        }

        .filter-card:hover {
            box-shadow: 0 4px 15px rgba(8, 66, 107, .08);
        }

        .filter-label {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 4px;
            color: #40515e;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .15px;
        }

        .filter-label i {
            color: #08426b;
            font-size: 10px;
        }

        .filter-input-wrap {
            position: relative;
            width: 100%;
        }

        .filter-input-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            color: #8b9aa5;
            font-size: 11px;
            pointer-events: none;
        }

        .filter-input {
            height: 34px;
            width: 100%;
            padding: 0 10px 0 31px;
            border: 1px solid #dce4e9;
            border-radius: 7px;
            background: #fbfcfd;
            color: #344550;
            font-size: 11px;
            box-shadow: none;
            transition: .2s ease;
        }

        .filter-input::placeholder {
            color: #a0abb2;
        }

        .filter-input:hover {
            background: #fff;
            border-color: #c8d5dd;
        }

        .filter-input:focus {
            background: #fff;
            border-color: #08426b;
            box-shadow: 0 0 0 2px rgba(8, 66, 107, .07);
            outline: none;
        }

        .filter-select {
            cursor: pointer;
            appearance: auto;
        }

        input[type="date"].filter-input {
            cursor: pointer;
        }

        .filter-reset-btn {
            width: 100%;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0 10px;
            border: 1px solid #d7dfe4;
            border-radius: 7px;
            background: #f8fafb;
            color: #566670;
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .filter-reset-btn i {
            font-size: 10px;
            transition: transform .2s ease;
        }

        .filter-reset-btn:hover {
            border-color: #08426b;
            background: #08426b;
            color: #fff;
            box-shadow: 0 3px 9px rgba(8, 66, 107, .13);
        }

        .filter-reset-btn:hover i {
            transform: rotate(-45deg);
        }


        /* =========================================================
   TABLE WRAPPER
========================================================= */

        .modern-table-wrapper {
            background: #fff;
            border-radius: 9px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .06);
            overflow-x: auto;
            overflow-y: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }


        /* =========================================================
   TABLE
========================================================= */

        .modern-table {
            width: 100%;
            min-width: 850px;
            border-collapse: collapse;
            font-size: 12px;
        }


        /* =========================================================
   TABLE HEADER
========================================================= */

        .modern-table thead {
            background: #08426b;
            color: #fff;
        }

        .modern-table th {
            padding: 9px 10px;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
            white-space: nowrap;
        }


        /* =========================================================
   TABLE BODY
========================================================= */

        .modern-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f0f2f4;
            vertical-align: middle;
        }

        .modern-table tbody tr {
            transition: background .2s ease;
        }

        .modern-table tbody tr:hover {
            background: #f8fbfd;
        }


        /* =========================================================
   MEMBER / VISITOR
========================================================= */

        .member-cell {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 150px;
        }

        .avatar {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #edf0f2;
        }

        .name {
            font-weight: 600;
            font-size: 12px;
            color: #222;
            line-height: 1.25;
        }

        .member-cell small {
            display: block;
            margin-top: 1px;
            font-size: 10px;
            color: #777;
            line-height: 1.2;
        }


        /* =========================================================
   BUSINESS
========================================================= */

        .business {
            color: #08426b;
            font-weight: 600;
            font-size: 12px;
        }


        /* =========================================================
   STATUS
========================================================= */

        .status {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
        }


        /* Status colors */

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.scheduled {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status.contacted {
            background: #cce5ff;
            color: #004085;
        }

        .status.completed,
        .status.attended {
            background: #d4edda;
            color: #155724;
        }

        .status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .status.invited {
            background: #e0f2fe;
            color: #0369a1;
        }

        .status.re-invited {
            background: #ede9fe;
            color: #6d28d9;
        }

        .status.visited {
            background: #dcfce7;
            color: #166534;
        }


        /* =========================================================
   ACTION BUTTONS
========================================================= */

        .btn-sm {
            padding: 4px 8px;
            font-size: 11px;
            border-radius: 6px;
            transition: all .2s ease;
        }

        .btn-sm:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, .12);
        }


        /* =========================================================
   SECTION HEADER
========================================================= */

        .visitor-section-header {
            margin-bottom: 8px !important;
        }

        .visitor-section-header h4 {
            margin-bottom: 1px !important;
            font-size: 16px;
            font-weight: 700;
            color: #173f59;
        }

        .visitor-section-header small {
            font-size: 10px;
            color: #7b8790;
        }

        .visitor-count {
            padding: 4px 9px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: 600;
        }


        /* =========================================================
   ADD BUTTON
========================================================= */

        .add-meeting-btn {
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0 11px 0 5px;
            border: 1px solid #174F70;
            border-radius: 18px;
            background: #174F70;
            color: #fff;
            font-size: 11px;
            font-weight: 650;
            line-height: 1;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(23, 79, 112, .13);
            transition: .2s ease;
        }

        .add-meeting-icon {
            width: 25px;
            height: 25px;
            flex: 0 0 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fff;
            color: #174F70;
            font-size: 10px;
            transition: .2s ease;
        }

        .add-meeting-text {
            white-space: nowrap;
        }

        .add-meeting-btn:hover {
            background: #123F59;
            border-color: #123F59;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(23, 79, 112, .18);
        }

        .add-meeting-btn:hover .add-meeting-icon {
            transform: rotate(90deg);
            background: #FFB900;
            color: #fff;
        }


        /* =========================================================
   TABS
========================================================= */

        .f2f-tabs {
            display: flex;
            gap: 6px;
        }

        .tab-btn {
            padding: 7px 12px;
            border-radius: 7px;
            background: #f3f4f6;
            color: #555;
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
            transition: .2s ease;
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
            margin-top: 10px;
        }

        .pagination {
            display: flex;
            gap: 3px;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .pagination li a,
        .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            height: 30px;
            padding: 4px 8px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            color: #333;
            background: #fff;
            font-size: 11px;
            text-decoration: none;
            transition: .2s ease;
        }

        .pagination li a:hover {
            background: #0F3B68;
            color: #fff;
            border-color: #0F3B68;
        }

        .pagination li.active span {
            background: #0F3B68;
            color: #fff;
            border-color: #0F3B68;
            font-weight: 600;
        }

        .pagination li.disabled span {
            opacity: .4;
            cursor: not-allowed;
        }


        /* =========================================================
   PAGINATION INFO
========================================================= */

        .pagination-info {
            font-size: 11px;
            color: #6c757d;
        }

        .pagination-info strong {
            color: #08426b;
        }


        /* =========================================================
   CARD
========================================================= */

        .card {
            margin-bottom: 8px;
        }

        .card:hover {
            transform: translateY(-2px);
            transition: .2s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .10);
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

            .modern-table {
                min-width: 0;
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


            /* VISITOR CARD */

            .modern-table tr {
                position: relative;
                background: #fff;
                border-radius: 11px;
                margin-bottom: 9px;
                padding: 10px;
                box-shadow: 0 3px 12px rgba(0, 0, 0, .06);
                border-left: 3px solid #0d6efd;
            }

            .modern-table td {
                display: block;
                border: none;
                padding: 0;
            }


            /* INDEX */

            .modern-table td:first-child {
                position: absolute;
                top: 8px;
                right: 9px;
                width: 21px;
                height: 21px;
                background: #0d6efd;
                color: #fff;
                font-size: 10px;
                font-weight: 700;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }


            /* VISITOR */

            .modern-table td:nth-child(2) {
                padding-right: 25px;
            }

            .modern-table td:nth-child(2) strong {
                font-size: 13px;
                font-weight: 700;
                color: #222;
            }

            .modern-table td:nth-child(2) small {
                font-size: 10px;
                color: #777;
            }


            /* COMPANY / PHONE / DATE */

            .modern-table td:nth-child(3),
            .modern-table td:nth-child(4),
            .modern-table td:nth-child(5) {
                display: flex;
                align-items: center;
                gap: 5px;
                margin-top: 4px;
                font-size: 11px;
                color: #555;
            }


            /* MEMBER */

            .member-cell {
                margin-top: 7px;
                gap: 7px;
            }

            .avatar {
                width: 32px;
                height: 32px;
                flex-basis: 32px;
            }

            .name {
                font-size: 11px;
            }

            .member-cell small {
                font-size: 9px;
            }


            /* STATUS */

            .status {
                margin-top: 7px;
                padding: 4px 8px;
                font-size: 9px;
            }


            /* ACTION */

            .modern-table td:last-child {
                display: flex;
                justify-content: flex-end;
                gap: 5px;
                margin-top: 8px;
            }

            .btn-sm {
                padding: 5px 8px;
                font-size: 10px;
                border-radius: 6px;
            }


            /* SECTION HEADER */

            .visitor-section-header {
                margin-bottom: 7px !important;
            }

            .visitor-section-header h4 {
                font-size: 14px;
            }

            .visitor-section-header small {
                font-size: 9px;
            }
        }


        /* =========================================================
   SMALL MOBILE
========================================================= */

        @media (max-width: 576px) {

            .filter-card {
                padding: 9px;
                margin-bottom: 10px;
                border-radius: 8px;
            }

            .filter-input {
                height: 33px;
                font-size: 10px;
            }

            .filter-reset-btn {
                height: 33px;
            }

            .filter-label {
                font-size: 9px;
            }

            .add-meeting-btn {
                height: 32px;
                padding: 0 9px 0 4px;
                font-size: 10px;
            }

            .add-meeting-icon {
                width: 24px;
                height: 24px;
                flex-basis: 24px;
                font-size: 9px;
            }

            .pagination li a,
            .pagination li span {
                min-width: 27px;
                height: 27px;
                padding: 3px 6px;
                font-size: 10px;
            }

        }
    </style>
    <style>
        .visitor-count-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 12px;
            margin: 4px 0 6px;
        }

        .visitor-count-card {
            min-height: 92px;

            background: #ffffff;

            border-radius: 12px;

            border: 1px solid #edf0f3;

            box-shadow: 0 4px 14px rgba(8, 66, 107, .07);

            display: flex;
            flex-direction: column;

            align-items: center;
            justify-content: center;

            text-align: center;

            transition:
                transform .2s ease,
                box-shadow .2s ease;
        }


        .visitor-count-card:hover {
            transform: translateY(-2px);

            box-shadow:
                0 7px 18px rgba(8, 66, 107, .12);
        }


        /* =========================================================
   TITLE
========================================================= */

        .visitor-count-title {
            font-size: 10px;

            font-weight: 700;

            color: #65727c;

            letter-spacing: .3px;

            margin-bottom: 5px;
        }


        /* =========================================================
   NUMBER
========================================================= */

        .visitor-count-number {
            display: flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            font-size: 19px;

            font-weight: 800;

            color: #111827;
        }


        .visitor-count-number i {
            font-size: 15px;

            color: #084f73;
        }


        /* =========================================================
   CHAPTER SECTION
========================================================= */

        .chapter-count-section {
            margin-top: 20px;
            margin-bottom: 20px;
        }


        /* =========================================================
   CHAPTER HEADER
========================================================= */

        .chapter-count-heading {
            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 9px;

            padding: 0 2px;
        }


        .chapter-count-heading h5 {
            margin: 0;

            font-size: 15px;

            font-weight: 700;

            color: #08426b;
        }


        .chapter-count-heading small {
            color: #8a969d;

            font-size: 11px;
        }


        .chapter-total-badge {
            min-width: 32px;

            height: 27px;

            padding: 0 9px;

            border-radius: 15px;

            background: #084f73;

            color: #fff;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            font-size: 11px;

            font-weight: 700;
        }


        /* =========================================================
   CHAPTER CARD
========================================================= */

        .chapter-card {
            border-top: 3px solid #084f73;
        }


        /* =========================================================
   RESPONSIVE
========================================================= */

        @media (max-width: 1200px) {

            .visitor-count-grid {
                grid-template-columns: repeat(3, 1fr);
            }

        }


        @media (max-width: 768px) {

            .visitor-count-grid {
                grid-template-columns: repeat(2, 1fr);

                gap: 9px;
            }

            .visitor-count-card {
                min-height: 82px;
            }

            .visitor-count-title {
                font-size: 9px;
            }

            .visitor-count-number {
                font-size: 17px;
            }

        }


        @media (max-width: 480px) {

            .visitor-count-grid {
                grid-template-columns: repeat(2, 1fr);

                gap: 7px;
            }

            .visitor-count-card {
                min-height: 75px;

                border-radius: 9px;
            }

            .visitor-count-number {
                font-size: 16px;
            }

            .visitor-count-number i {
                font-size: 13px;
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
                    <div class="col-md-6 col-6">
                        <h4>Visitor Tracking:</h4>
                    </div>
                    <div class="col-md-6 col-6">
                        <button type="button"
                            class="add-meeting-btn" style="float: right;"
                            onclick="add_vt();">
                            <span class="add-meeting-icon">
                                <i class="fa fa-plus"></i>
                            </span>
                            <span class="add-meeting-text">
                                Invite New Visitor
                            </span>
                        </button>
                    </div>
                </div>
                <div class="visitor-count-grid">

                    <!-- INVITED -->
                    <div class="visitor-count-card">
                        <div class="visitor-count-title">INVITED</div>

                        <div class="visitor-count-number">
                            <i class="fa fa-user-plus"></i>

                            <?= (int) (
                                ($statusCounts['Invited'] ?? 0) +
                                ($chapterStatusCounts['Invited'] ?? 0)
                            ) ?>
                        </div>
                    </div>


                    <!-- RE-INVITED -->
                    <div class="visitor-count-card">
                        <div class="visitor-count-title">RE-INVITED</div>

                        <div class="visitor-count-number">
                            <i class="fa fa-user-clock"></i>

                            <?= (int) (
                                ($statusCounts['Re-Invited'] ?? 0) +
                                ($chapterStatusCounts['Re-Invited'] ?? 0)
                            ) ?>
                        </div>
                    </div>


                    <!-- SCHEDULED -->
                    <div class="visitor-count-card">
                        <div class="visitor-count-title">SCHEDULED</div>

                        <div class="visitor-count-number">
                            <i class="fa fa-calendar-check"></i>

                            <?= (int) (
                                ($statusCounts['Scheduled'] ?? 0) +
                                ($chapterStatusCounts['Scheduled'] ?? 0)
                            ) ?>
                        </div>
                    </div>


                    <!-- PENDING -->
                    <div class="visitor-count-card">
                        <div class="visitor-count-title">PENDING</div>

                        <div class="visitor-count-number">
                            <i class="fa fa-clock"></i>

                            <?= (int) (
                                ($statusCounts['Pending'] ?? 0) +
                                ($chapterStatusCounts['Pending'] ?? 0)
                            ) ?>
                        </div>
                    </div>


                    <!-- ATTENDED -->
                    <div class="visitor-count-card">
                        <div class="visitor-count-title">ATTENDED</div>

                        <div class="visitor-count-number">
                            <i class="fa fa-user-check"></i>

                            <?= (int) (
                                ($statusCounts['Attended'] ?? 0) +
                                ($chapterStatusCounts['Attended'] ?? 0)
                            ) ?>
                        </div>
                    </div>


                    <!-- CANCELLED -->
                    <div class="visitor-count-card">
                        <div class="visitor-count-title">CANCELLED</div>

                        <div class="visitor-count-number">
                            <i class="fa fa-user-times"></i>

                            <?= (int) (
                                ($statusCounts['Cancelled'] ?? 0) +
                                ($chapterStatusCounts['Cancelled'] ?? 0)
                            ) ?>
                        </div>
                    </div>

                </div>



                <div class="col-md-12 col-12">

                    <div class="filter-card">

                        <form
                            id="search-form"
                            method="GET"
                            action="<?= current_url() ?>">

                            <div class="row g-2 align-items-end">

                                <!-- SEARCH -->

                                <div class="col-lg-4 col-md-4 col-12">

                                    <label class="filter-label">

                                        <i class="fa fa-search"></i>

                                        Search

                                    </label>

                                    <div class="filter-input-wrap">

                                        <i class="fa fa-search filter-input-icon"></i>

                                        <input
                                            type="text"
                                            id="search"
                                            name="search"
                                            class="form-control filter-input"
                                            placeholder="Search visitor, company, phone..."
                                            value="<?= esc($search ?? '') ?>">

                                    </div>

                                </div>


                                <!-- STATUS -->

                                <div class="col-lg-3 col-md-3 col-6">

                                    <label class="filter-label">

                                        <i class="fa fa-filter"></i>

                                        Status

                                    </label>

                                    <div class="filter-input-wrap">
                                        <select
                                            class="form-select filter-input filter-select"
                                            id="status1"
                                            name="status">

                                            <option value="">
                                                All Status
                                            </option>

                                            <option
                                                value="Invited"
                                                <?= (($status ?? '') === 'Invited') ? 'selected' : '' ?>>
                                                Invited
                                            </option>

                                            <option
                                                value="Re-Invited"
                                                <?= (($status ?? '') === 'Re-Invited') ? 'selected' : '' ?>>
                                                Re-Invited
                                            </option>

                                            <option
                                                value="Scheduled"
                                                <?= (($status ?? '') === 'Scheduled') ? 'selected' : '' ?>>
                                                Scheduled
                                            </option>

                                            <option
                                                value="Pending"
                                                <?= (($status ?? '') === 'Pending') ? 'selected' : '' ?>>
                                                Pending
                                            </option>

                                            <option
                                                value="Attended"
                                                <?= (($status ?? '') === 'Attended') ? 'selected' : '' ?>>
                                                Attended
                                            </option>

                                            <option
                                                value="Cancelled"
                                                <?= (($status ?? '') === 'Cancelled') ? 'selected' : '' ?>>
                                                Cancelled
                                            </option>

                                        </select>

                                    </div>

                                </div>





                                <div class="col-lg-3 col-md-3 col-6">

                                    <label class="filter-label">
                                        <i class="fa fa-calendar"></i>
                                        Date
                                    </label>

                                    <div class="filter-input-wrap">
                                        <input
                                            type="text"
                                            id="date"
                                            name="date"
                                            class="form-control filter-input"
                                            placeholder="dd-mm-yyyy"
                                            value="<?= esc($date ?? '') ?>"
                                            autocomplete="off">

                                    </div>

                                </div>


                                <!-- RESET -->

                                <div class="col-lg-2 col-md-2 col-12">

                                    <button
                                        type="button"
                                        id="reset-btn"
                                        class="filter-reset-btn">

                                        <i class="fa fa-rotate-left"></i>

                                        <span>
                                            Reset Filters
                                        </span>

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

                <div class="container-fluid mt-2">

                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <div>

                            <h4 class="mb-0">
                                My Visitors
                            </h4>

                            <small class="text-muted">
                                Visitors added by you
                            </small>

                        </div>

                        <?php if (($myTotal ?? 0) > 0): ?>

                            <span class="badge bg-light text-dark">
                                <?= (int) $myTotal ?> Visitors
                            </span>

                        <?php endif; ?>

                    </div>


                    <!-- TABLE -->
                    <div class="modern-table-wrapper">

                        <table class="modern-table">

                            <thead>

                                <tr>

                                    <th>#</th>

                                    <th>Visitor</th>

                                    <th>Company</th>

                                    <th>Phone</th>

                                    <th>Visited Date</th>

                                    <th>Added By</th>

                                    <th>Status</th>

                                    <th>Action</th>

                                </tr>

                            </thead>
                            <tbody id="list_data">
                            </tbody>
                            <tbody id="my_visitors_results">

                                <?php if (!empty($visitor)): ?>

                                    <?php

                                    $i = 1 + (
                                        (int) $perPage *
                                        ((int) $myPage - 1)
                                    );

                                    ?>

                                    <?php foreach ($visitor as $row): ?>

                                        <?= view(
                                            'include/member/card/visitor_card.php',
                                            [
                                                'row' => $row,
                                                'i'   => $i
                                            ]
                                        ) ?>

                                        <?php $i++; ?>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>

                                        <td
                                            colspan="8"
                                            class="text-center p-4">

                                            <i class="fa fa-users-slash me-2"></i>

                                            No Visitors Found

                                        </td>

                                    </tr>

                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (($myTotal ?? 0) > 0): ?>

                        <?php

                        $myPage =
                            max(1, (int) ($myPage ?? 1));

                        $myTotalPages =
                            max(1, (int) ($myTotalPages ?? 1));

                        $perPage =
                            max(1, (int) ($perPage ?? 10));

                        $myStartPage =
                            max(1, $myPage - 2);

                        $myEndPage =
                            min(
                                $myTotalPages,
                                $myPage + 2
                            );

                        ?>


                        <div class="row mt-3 align-items-center">

                            <!-- INFO -->
                            <div class="col-md-6">

                                <div class="pagination-info">

                                    Showing page

                                    <strong>
                                        <?= $myPage ?>
                                    </strong>

                                    of

                                    <strong>
                                        <?= $myTotalPages ?>
                                    </strong>

                                    <span class="ms-1">
                                        (<?= (int) $myTotal ?> visitors)
                                    </span>

                                </div>

                            </div>


                            <!-- PAGINATION -->
                            <div class="col-md-6">

                                <div class="d-flex justify-content-md-end justify-content-center">

                                    <ul class="pagination mb-0">


                                        <!-- PREVIOUS -->

                                        <?php if ($myPage > 1): ?>

                                            <li class="page-item">

                                                <a
                                                    class="page-link"
                                                    href="<?= current_url() ?>?<?= http_build_query([
                                                                                    'search'       => $search ?? '',
                                                                                    'status'       => $status ?? '',
                                                                                    'date'         => $date ?? '',
                                                                                    'perPage'      => $perPage,
                                                                                    'my_page'      => $myPage - 1,
                                                                                    'chapter_page' => $chapterPage ?? 1
                                                                                ]) ?>">

                                                    <i class="fa fa-chevron-left"></i>

                                                </a>

                                            </li>

                                        <?php endif; ?>


                                        <!-- FIRST PAGE -->

                                        <?php if ($myStartPage > 1): ?>

                                            <li class="page-item">

                                                <a
                                                    class="page-link"
                                                    href="<?= current_url() ?>?<?= http_build_query([
                                                                                    'search'       => $search ?? '',
                                                                                    'status'       => $status ?? '',
                                                                                    'date'         => $date ?? '',
                                                                                    'perPage'      => $perPage,
                                                                                    'my_page'      => 1,
                                                                                    'chapter_page' => $chapterPage ?? 1
                                                                                ]) ?>">
                                                    1
                                                </a>

                                            </li>

                                            <?php if ($myStartPage > 2): ?>

                                                <li class="page-item disabled">

                                                    <span class="page-link">
                                                        ...
                                                    </span>

                                                </li>

                                            <?php endif; ?>

                                        <?php endif; ?>


                                        <!-- PAGE NUMBERS -->

                                        <?php for (
                                            $p = $myStartPage;
                                            $p <= $myEndPage;
                                            $p++
                                        ): ?>

                                            <li
                                                class="page-item <?= ($p == $myPage) ? 'active' : '' ?>">

                                                <a
                                                    class="page-link"
                                                    href="<?= current_url() ?>?<?= http_build_query([
                                                                                    'search'       => $search ?? '',
                                                                                    'status'       => $status ?? '',
                                                                                    'date'         => $date ?? '',
                                                                                    'perPage'      => $perPage,
                                                                                    'my_page'      => $p,
                                                                                    'chapter_page' => $chapterPage ?? 1
                                                                                ]) ?>">

                                                    <?= $p ?>

                                                </a>

                                            </li>

                                        <?php endfor; ?>


                                        <!-- LAST PAGE -->

                                        <?php if ($myEndPage < $myTotalPages): ?>

                                            <?php if (
                                                $myEndPage <
                                                $myTotalPages - 1
                                            ): ?>

                                                <li class="page-item disabled">

                                                    <span class="page-link">
                                                        ...
                                                    </span>

                                                </li>

                                            <?php endif; ?>


                                            <li class="page-item">

                                                <a
                                                    class="page-link"
                                                    href="<?= current_url() ?>?<?= http_build_query([
                                                                                    'search'       => $search ?? '',
                                                                                    'status'       => $status ?? '',
                                                                                    'date'         => $date ?? '',
                                                                                    'perPage'      => $perPage,
                                                                                    'my_page'      => $myTotalPages,
                                                                                    'chapter_page' => $chapterPage ?? 1
                                                                                ]) ?>">

                                                    <?= $myTotalPages ?>

                                                </a>

                                            </li>

                                        <?php endif; ?>


                                        <!-- NEXT -->

                                        <?php if ($myPage < $myTotalPages): ?>

                                            <li class="page-item">

                                                <a
                                                    class="page-link"
                                                    href="<?= current_url() ?>?<?= http_build_query([
                                                                                    'search'       => $search ?? '',
                                                                                    'status'       => $status ?? '',
                                                                                    'date'         => $date ?? '',
                                                                                    'perPage'      => $perPage,
                                                                                    'my_page'      => $myPage + 1,
                                                                                    'chapter_page' => $chapterPage ?? 1
                                                                                ]) ?>">

                                                    <i class="fa fa-chevron-right"></i>

                                                </a>

                                            </li>

                                        <?php endif; ?>


                                    </ul>

                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- ============================================================
     MY CHAPTER MEMBERS VISITORS
============================================================ -->

                <?php if (!empty($isChapterLeader) && $isChapterLeader): ?>

                    <div class="container-fluid mt-4">

                        <!-- HEADER -->
                        <div class="d-flex justify-content-between align-items-center mb-2">

                            <div>

                                <h4 class="mb-0">
                                    My Chapter Members – Visitors List
                                </h4>

                                <small class="text-muted">
                                    Visitors from your chapter members
                                </small>

                            </div>


                            <?php if (($chapterTotal ?? 0) > 0): ?>

                                <span class="badge bg-light text-dark">

                                    <?= (int) $chapterTotal ?>

                                    Visitors

                                </span>

                            <?php endif; ?>

                        </div>

                        <div class="visitor-count-grid">

                            <?php
                            $statuses = [
                                'Invited' => ['icon' => 'fa-user-plus', 'label' => 'INVITED'],
                                'Re-Invited' => ['icon' => 'fa-user-clock', 'label' => 'RE-INVITED'],
                                'Scheduled' => ['icon' => 'fa-calendar-check', 'label' => 'SCHEDULED'],
                                'Pending' => ['icon' => 'fa-clock', 'label' => 'PENDING'],
                                'Attended' => ['icon' => 'fa-user-check', 'label' => 'ATTENDED'],
                                'Cancelled' => ['icon' => 'fa-user-times', 'label' => 'CANCELLED'],
                            ];
                            ?>

                            <?php foreach ($statuses as $statusName => $item): ?>

                                <?php
                                $myCount = (int) ($statusCounts[$statusName] ?? 0);

                                $chapterCount = $isChapterLeader
                                    ? (int) ($chapterStatusCounts[$statusName] ?? 0)
                                    : 0;

                                $totalCount = $myCount + $chapterCount;
                                ?>

                                <div class="visitor-count-card">

                                    <div class="visitor-count-title">
                                        <?= esc($item['label']) ?>
                                    </div>

                                    <div class="visitor-count-number">
                                        <i class="fa <?= esc($item['icon']) ?>"></i>

                                        <?= $totalCount ?>
                                    </div>



                                </div>

                            <?php endforeach; ?>

                        </div>
                        <!-- TABLE -->
                        <div class="modern-table-wrapper mt-2">

                            <table class="modern-table">

                                <thead>

                                    <tr>

                                        <th>#</th>

                                        <th>Visitor</th>

                                        <th>Company</th>

                                        <th>Phone</th>

                                        <th>Visited Date</th>

                                        <th>Added By</th>

                                        <th>Status</th>

                                        <th>Action</th>

                                    </tr>

                                </thead>


                                <tbody id="chapter_visitors_results">

                                    <?php if (!empty($Visitors_chapter)): ?>

                                        <?php

                                        $chapterPage =
                                            max(
                                                1,
                                                (int) ($chapterPage ?? 1)
                                            );

                                        $perPage =
                                            max(
                                                1,
                                                (int) ($perPage ?? 10)
                                            );

                                        $i = 1 + (
                                            $perPage *
                                            ($chapterPage - 1)
                                        );

                                        ?>


                                        <?php foreach (
                                            $Visitors_chapter
                                            as $row
                                        ): ?>

                                            <?= view(
                                                'include/member/card/visitor_card.php',
                                                [
                                                    'row' => $row,
                                                    'i'   => $i
                                                ]
                                            ) ?>

                                            <?php $i++; ?>

                                        <?php endforeach; ?>


                                    <?php else: ?>

                                        <tr>

                                            <td
                                                colspan="8"
                                                class="text-center p-4">

                                                <i class="fa fa-users-slash me-2"></i>

                                                No Chapter Visitors Found

                                            </td>

                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>


                        <!-- ====================================================
             CHAPTER PAGINATION
        ===================================================== -->

                        <?php if (($chapterTotal ?? 0) > 0): ?>

                            <?php

                            $chapterPage =
                                max(
                                    1,
                                    (int) ($chapterPage ?? 1)
                                );

                            $chapterTotalPages =
                                max(
                                    1,
                                    (int) ($chapterTotalPages ?? 1)
                                );

                            $perPage =
                                max(
                                    1,
                                    (int) ($perPage ?? 10)
                                );

                            $chapterStartPage =
                                max(
                                    1,
                                    $chapterPage - 2
                                );

                            $chapterEndPage =
                                min(
                                    $chapterTotalPages,
                                    $chapterPage + 2
                                );

                            ?>


                            <div class="row mt-3 align-items-center">

                                <!-- INFO -->

                                <div class="col-md-6">

                                    <div class="pagination-info">

                                        Showing page

                                        <strong>
                                            <?= $chapterPage ?>
                                        </strong>

                                        of

                                        <strong>
                                            <?= $chapterTotalPages ?>
                                        </strong>

                                        <span class="ms-1">

                                            (<?= (int) $chapterTotal ?>
                                            visitors)

                                        </span>

                                    </div>

                                </div>


                                <!-- PAGINATION -->

                                <div class="col-md-6">

                                    <div class="d-flex justify-content-md-end justify-content-center">

                                        <ul class="pagination mb-0">


                                            <!-- PREVIOUS -->

                                            <?php if ($chapterPage > 1): ?>

                                                <li class="page-item">

                                                    <a
                                                        class="page-link"
                                                        href="<?= current_url() ?>?<?= http_build_query([
                                                                                        'search'       => $search ?? '',
                                                                                        'status'       => $status ?? '',
                                                                                        'date'         => $date ?? '',
                                                                                        'perPage'      => $perPage,
                                                                                        'my_page'      => $myPage ?? 1,
                                                                                        'chapter_page' => $chapterPage - 1
                                                                                    ]) ?>">

                                                        <i class="fa fa-chevron-left"></i>

                                                    </a>

                                                </li>

                                            <?php endif; ?>


                                            <!-- FIRST PAGE -->

                                            <?php if ($chapterStartPage > 1): ?>

                                                <li class="page-item">

                                                    <a
                                                        class="page-link"
                                                        href="<?= current_url() ?>?<?= http_build_query([
                                                                                        'search'       => $search ?? '',
                                                                                        'status'       => $status ?? '',
                                                                                        'date'         => $date ?? '',
                                                                                        'perPage'      => $perPage,
                                                                                        'my_page'      => $myPage ?? 1,
                                                                                        'chapter_page' => 1
                                                                                    ]) ?>">

                                                        1

                                                    </a>

                                                </li>


                                                <?php if ($chapterStartPage > 2): ?>

                                                    <li class="page-item disabled">

                                                        <span class="page-link">
                                                            ...
                                                        </span>

                                                    </li>

                                                <?php endif; ?>

                                            <?php endif; ?>


                                            <!-- PAGE NUMBERS -->

                                            <?php for (
                                                $p = $chapterStartPage;
                                                $p <= $chapterEndPage;
                                                $p++
                                            ): ?>

                                                <li
                                                    class="page-item <?= ($p == $chapterPage) ? 'active' : '' ?>">

                                                    <a
                                                        class="page-link"
                                                        href="<?= current_url() ?>?<?= http_build_query([
                                                                                        'search'       => $search ?? '',
                                                                                        'status'       => $status ?? '',
                                                                                        'date'         => $date ?? '',
                                                                                        'perPage'      => $perPage,
                                                                                        'my_page'      => $myPage ?? 1,
                                                                                        'chapter_page' => $p
                                                                                    ]) ?>">

                                                        <?= $p ?>

                                                    </a>

                                                </li>

                                            <?php endfor; ?>


                                            <!-- LAST PAGE -->

                                            <?php if (
                                                $chapterEndPage <
                                                $chapterTotalPages
                                            ): ?>


                                                <?php if (
                                                    $chapterEndPage <
                                                    $chapterTotalPages - 1
                                                ): ?>

                                                    <li class="page-item disabled">

                                                        <span class="page-link">
                                                            ...
                                                        </span>

                                                    </li>

                                                <?php endif; ?>


                                                <li class="page-item">

                                                    <a
                                                        class="page-link"
                                                        href="<?= current_url() ?>?<?= http_build_query([
                                                                                        'search'       => $search ?? '',
                                                                                        'status'       => $status ?? '',
                                                                                        'date'         => $date ?? '',
                                                                                        'perPage'      => $perPage,
                                                                                        'my_page'      => $myPage ?? 1,
                                                                                        'chapter_page' => $chapterTotalPages
                                                                                    ]) ?>">

                                                        <?= $chapterTotalPages ?>

                                                    </a>

                                                </li>

                                            <?php endif; ?>


                                            <!-- NEXT -->

                                            <?php if (
                                                $chapterPage <
                                                $chapterTotalPages
                                            ): ?>

                                                <li class="page-item">

                                                    <a
                                                        class="page-link"
                                                        href="<?= current_url() ?>?<?= http_build_query([
                                                                                        'search'       => $search ?? '',
                                                                                        'status'       => $status ?? '',
                                                                                        'date'         => $date ?? '',
                                                                                        'perPage'      => $perPage,
                                                                                        'my_page'      => $myPage ?? 1,
                                                                                        'chapter_page' => $chapterPage + 1
                                                                                    ]) ?>">

                                                        <i class="fa fa-chevron-right"></i>

                                                    </a>

                                                </li>

                                            <?php endif; ?>


                                        </ul>

                                    </div>

                                </div>

                            </div>

                        <?php endif; ?>

                    </div>

                <?php endif; ?>

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
    <?php echo view('include/member/popup/visitor.php') ?>

    <script>
        $(document).ready(function() {

            let ajaxRequest = null;

            function debounce(fn, delay) {
                let timer;

                return function() {
                    const context = this;
                    const args = arguments;

                    clearTimeout(timer);

                    timer = setTimeout(function() {
                        fn.apply(context, args);
                    }, delay);
                };
            }

            function collectFilters() {

                return {
                    search: $.trim($('#search').val()),
                    status: $('#status1').val(),
                    date: $('#date').val()
                };
            }

            function saveFilters() {

                const filters = collectFilters();

                localStorage.setItem(
                    'userFilters',
                    JSON.stringify(filters)
                );
            }

            function loadFilters() {

                const saved = localStorage.getItem('userFilters');

                if (!saved) {
                    return false;
                }

                try {

                    const filters = JSON.parse(saved);

                    $('#search').val(filters.search || '');
                    $('#status1').val(filters.status || '');
                    $('#date').val(filters.date || '');

                    return true;

                } catch (e) {

                    console.error('Invalid localStorage data:', e);

                    localStorage.removeItem('userFilters');

                    return false;
                }
            }

            function clearResults() {

                $('#list_data')
                    .hide()
                    .html('');

                $('#my_visitors_results')
                    .show();
            }

            function loadUsers() {

                const formData = collectFilters();

                console.log('Sending filters:', formData);

                saveFilters();

                const hasFilter =
                    formData.search !== '' ||
                    formData.status !== '' ||
                    formData.date !== '';

                /*
                 * No filters
                 */
                if (!hasFilter) {

                    localStorage.removeItem('userFilters');

                    clearResults();

                    return;
                }

                /*
                 * Abort previous AJAX request
                 */
                if (ajaxRequest !== null) {
                    ajaxRequest.abort();
                }

                ajaxRequest = $.ajax({

                    type: 'POST',

                    url: '<?= base_url("member/visitor/get_filtered_data") ?>',

                    data: {
                        search: formData.search,
                        status: formData.status,
                        date: formData.date
                    },

                    dataType: 'html',

                    beforeSend: function() {

                        $('#my_visitors_results').hide();

                        $('#list_data')
                            .show()
                            .html(
                                '<div class="text-center py-4">' +
                                '<span class="spinner-border spinner-border-sm"></span>' +
                                ' Loading...' +
                                '</div>'
                            );
                    },

                    success: function(response) {

                        console.log('AJAX response:', response);

                        if ($.trim(response) === '') {

                            $('#list_data')
                                .html(
                                    '<div class="alert alert-warning text-center">' +
                                    'No visitors found.' +
                                    '</div>'
                                )
                                .show();

                            return;
                        }

                        $('#list_data')
                            .html(response)
                            .show();
                    },

                    error: function(xhr, status, error) {

                        if (status === 'abort') {
                            return;
                        }

                        console.error('AJAX Error:', error);

                        console.error(
                            'Server Response:',
                            xhr.responseText
                        );

                        $('#list_data')
                            .html(
                                '<div class="alert alert-danger">' +
                                '<strong>Server Error</strong><br>' +
                                'Please check the browser console.' +
                                '</div>'
                            )
                            .show();
                    },

                    complete: function() {
                        ajaxRequest = null;
                    }

                });
            }

            /*
             * Debounced search
             */
            const searchVisitors = debounce(function() {
                loadUsers();
            }, 400);


            /*
             * SEARCH
             */
            $('#search').on('keyup', function() {
                searchVisitors();
            });


            /*
             * STATUS
             */
            $('#status1').on('change', function() {
                loadUsers();
            });


            /*
             * DATE
             */
            $('#date').on('change', function() {
                loadUsers();
            });


            /*
             * RESET
             */
            $('#reset-btn').on('click', function() {

                $('#search').val('');

                $('#status1').val('');

                $('#date').val('');

                localStorage.removeItem('userFilters');

                clearResults();
            });


            /*
             * LOAD SAVED FILTERS
             */
            const hasSavedFilters = loadFilters();

            if (hasSavedFilters) {

                const filters = collectFilters();

                const hasFilter =
                    filters.search !== '' ||
                    filters.status !== '' ||
                    filters.date !== '';

                if (hasFilter) {
                    loadUsers();
                } else {
                    clearResults();
                }

            } else {

                clearResults();
            }

        });
    </script>



    <!-- DATE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const dateInput = document.getElementById('date');

            if (!dateInput) {
                return;
            }

            const meetingDay = <?= json_encode($meetingDay ?? '') ?>;

            console.log('Chapter Meeting Day:', meetingDay);

            const dayMap = {
                sun: 0,
                sunday: 0,

                mon: 1,
                monday: 1,

                tue: 2,
                tues: 2,
                tuesday: 2,

                wed: 3,
                wednesday: 3,

                th: 4,
                thu: 4,
                thur: 4,
                thurs: 4,
                thursday: 4,

                fri: 5,
                friday: 5,

                sat: 6,
                saturday: 6
            };

            const dayName = String(meetingDay)
                .trim()
                .toLowerCase();

            const allowedDay = dayMap[dayName];

            console.log('Allowed Day:', allowedDay);

            flatpickr(dateInput, {

                dateFormat: 'Y-m-d',

                altInput: true,
                altFormat: 'd-m-Y',

                allowInput: false,

                clickOpens: true,

                disableMobile: true,

                disable: allowedDay !== undefined ? [
                    function(date) {

                        return date.getDay() !== allowedDay;

                    }
                ] : [],

                /*
                |--------------------------------------------------------------------------
                | Validate Existing Date
                |--------------------------------------------------------------------------
                */

                onReady: function(selectedDates, dateStr, instance) {

                    if (
                        selectedDates.length > 0 &&
                        allowedDay !== undefined
                    ) {

                        if (selectedDates[0].getDay() !== allowedDay) {

                            instance.clear();

                        }

                    }

                },

                /*
                |--------------------------------------------------------------------------
                | Validate When User Selects Date
                |--------------------------------------------------------------------------
                */

                onChange: function(selectedDates, dateStr, instance) {

                    if (
                        selectedDates.length > 0 &&
                        allowedDay !== undefined
                    ) {

                        const selectedDay =
                            selectedDates[0].getDay();

                        if (selectedDay !== allowedDay) {

                            instance.clear();

                            alert(
                                'Please select only the chapter meeting day: ' +
                                meetingDay
                            );

                        }

                    }

                }

            });

        });
    </script>
</body>

</html>