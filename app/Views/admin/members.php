<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Members</title>
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
            padding: 3px 10px;
        }

        /* =========================================================
   CRM MODERN MEMBER TABLE
   Theme: #08426b
========================================================= */

        :root {
            --crm-primary: #08426b;
            --crm-primary-dark: #063653;
            --crm-primary-light: #eef6fb;

            --crm-text: #263238;
            --crm-muted: #78909c;
            --crm-border: #e3ebef;
            --crm-bg: #f7f9fb;

            --crm-success: #16865c;
            --crm-warning: #d99100;
            --crm-danger: #d9534f;
        }


        /* =========================================================
   TABLE WRAPPER
========================================================= */

        .modern-table-wrapper {
            position: relative;

            width: 100%;

            background: #fff;

            border: 1px solid var(--crm-border);
            border-radius: 13px;

            box-shadow: 0 4px 18px rgba(8, 66, 107, .07);

            overflow-x: auto;

            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

            scrollbar-width: thin;
            scrollbar-color: #c5d4dc transparent;
        }

        .modern-table-wrapper::-webkit-scrollbar {
            height: 6px;
        }

        .modern-table-wrapper::-webkit-scrollbar-track {
            background: transparent;
        }

        .modern-table-wrapper::-webkit-scrollbar-thumb {
            background: #c5d4dc;
            border-radius: 20px;
        }


        /* =========================================================
   TABLE
========================================================= */

        .modern-table {
            width: 100%;

            border-collapse: separate;
            border-spacing: 0;

            font-size: 14px;

            color: var(--crm-text);
        }


        /* =========================================================
   TABLE HEADER
========================================================= */

        .modern-table thead {
            background: var(--crm-primary);
            color: #fff;
        }

        .modern-table thead th {
            padding: 13px 12px;

            border: 0;

            font-size: 12px;
            font-weight: 700;

            text-align: left;

            white-space: nowrap;

            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .modern-table thead th:first-child {
            padding-left: 16px;

            border-radius: 12px 0 0 0;
        }

        .modern-table thead th:last-child {
            padding-right: 16px;

            border-radius: 0 12px 0 0;
        }


        /* =========================================================
   TABLE BODY
========================================================= */

        .modern-table tbody td {
            padding: 12px;

            border-bottom: 1px solid var(--crm-border);

            vertical-align: middle;

            color: #455a64;

            font-size: 13px;
        }

        .modern-table tbody tr:last-child td {
            border-bottom: 0;
        }


        /* =========================================================
   ROW HOVER
========================================================= */

        .modern-table tbody tr {
            background: #fff;

            transition:
                background .2s ease,
                box-shadow .2s ease;
        }

        .modern-table tbody tr:hover {
            background: #f8fbfd;

            box-shadow:
                inset 3px 0 0 var(--crm-primary);
        }


        /* =========================================================
   MEMBER CELL
========================================================= */

        .member-cell {
            display: flex;

            align-items: center;

            gap: 11px;

            min-width: 220px;
        }


        /* Avatar */

        .avatar {
            width: 46px;
            height: 46px;

            min-width: 46px;

            border-radius: 11px;

            object-fit: cover;

            border: 2px solid #edf2f5;

            background: #f4f7f9;

            transition: all .2s ease;
        }

        .modern-table tbody tr:hover .avatar {
            border-color: #c7dce7;

            transform: scale(1.03);
        }


        /* Member name */

        .name {
            max-width: 200px;

            color: var(--crm-text);

            font-size: 14px;

            font-weight: 700;

            line-height: 1.35;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;
        }


        /* Email */

        .member-cell small {
            display: block;

            max-width: 210px;

            margin-top: 4px;

            color: #78909c;

            font-size: 11px;

            line-height: 1.3;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;
        }


        /* =========================================================
   BUSINESS
========================================================= */

        .business {
            min-width: 170px;

            color: var(--crm-primary);

            font-size: 13px;

            font-weight: 700;

            line-height: 1.5;
        }

        .business p {
            margin: 5px 0 0;
        }

        .business small {
            color: #78909c !important;

            font-size: 10px;

            font-weight: 500;
        }

        .business small span {
            margin-right: 4px;

            color: #d11b2d !important;

            font-weight: 700;
        }


        /* =========================================================
   PHONE / LOCATION
========================================================= */

        .modern-table td[data-label="Phone"],
        .modern-table td[data-label="Location"] {
            color: #546e7a;

            font-size: 12px;

            white-space: nowrap;
        }


        /* =========================================================
   ROLE BADGE
========================================================= */

        .role-badge {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 5px;

            padding: 7px 11px;

            border: 0;

            border-radius: 8px;

            background: var(--crm-primary-light);

            color: var(--crm-primary);

            font-size: 11px;

            font-weight: 700;

            line-height: 1;

            cursor: pointer;

            transition: all .2s ease;
        }

        .role-badge:hover {
            background: var(--crm-primary);

            color: #fff;

            transform: translateY(-1px);

            box-shadow: 0 4px 10px rgba(8, 66, 107, .16);
        }


        /* =========================================================
   STATUS
========================================================= */

        .status {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 5px;

            min-width: 78px;

            padding: 7px 11px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: 700;

            line-height: 1;

            text-align: center;

            cursor: pointer;

            transition: all .2s ease;
        }


        /* Active */

        .status.active {
            background: #e8f7f0;

            color: var(--crm-success);
        }

        .status.active::before {
            content: '';

            width: 6px;
            height: 6px;

            border-radius: 50%;

            background: var(--crm-success);
        }


        /* Pending */

        .status.pending {
            background: #fff5df;

            color: var(--crm-warning);
        }

        .status.pending::before {
            content: '';

            width: 6px;
            height: 6px;

            border-radius: 50%;

            background: var(--crm-warning);
        }


        /* Blocked */

        .status.blocked {
            background: #fff0ef;

            color: var(--crm-danger);
        }

        .status.blocked::before {
            content: '';

            width: 6px;
            height: 6px;

            border-radius: 50%;

            background: var(--crm-danger);
        }

        .status:hover {
            transform: translateY(-1px);

            box-shadow: 0 3px 8px rgba(0, 0, 0, .07);
        }


        /* =========================================================
   ACTIONS
========================================================= */

        .actions {
            white-space: nowrap;

            text-align: center;
        }

        .actions .btn {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            width: 34px;
            height: 34px;

            padding: 0;

            margin-right: 3px;

            border: 0;

            border-radius: 8px;

            font-size: 12px;

            transition: all .2s ease;
        }

        .actions .btn:last-child {
            margin-right: 0;
        }


        /* View */

        .actions .btn-info {
            background: var(--crm-primary-light) !important;

            color: var(--crm-primary) !important;
        }


        /* Edit */

        .actions .btn-warning {
            background: #fff5df !important;

            color: var(--crm-warning) !important;
        }


        /* Delete */

        .actions .btn-danger {
            background: #fff0ef !important;

            color: var(--crm-danger) !important;
        }


        /* Hover */

        .actions .btn:hover {
            transform: translateY(-2px);

            box-shadow: 0 5px 11px rgba(0, 0, 0, .10);
        }

        .actions .btn-info:hover {
            background: var(--crm-primary) !important;

            color: #fff !important;
        }

        .actions .btn-warning:hover {
            background: var(--crm-warning) !important;

            color: #fff !important;
        }

        .actions .btn-danger:hover {
            background: var(--crm-danger) !important;

            color: #fff !important;
        }


        /* =========================================================
   SMALL BUTTONS
========================================================= */

        .btn-sm {
            padding: 6px 9px;

            border-radius: 7px;

            font-size: 12px;

            transition: all .2s ease;
        }

        .btn-sm:hover {
            transform: translateY(-1px);
        }


        /* =========================================================
   CARD
========================================================= */

        .card {
            margin-bottom: 8px;

            border: 1px solid var(--crm-border);

            border-radius: 11px;

            box-shadow: 0 3px 12px rgba(0, 0, 0, .04);

            transition: all .2s ease;
        }

        .card:hover {
            transform: translateY(-2px);

            box-shadow:
                0 7px 20px rgba(8, 66, 107, .08);
        }


        /* =========================================================
   PAGINATION
========================================================= */

        .pagination-wrapper {
            margin-top: 14px;
        }

        .pagination {
            display: flex;

            align-items: center;

            justify-content: center;

            gap: 5px;

            padding: 0;

            margin: 0;

            list-style: none;

            flex-wrap: wrap;
        }

        .pagination li a,
        .pagination li span {
            min-width: 32px;

            height: 32px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding: 0 10px;

            border: 1px solid #e0e8ed;

            border-radius: 8px;

            background: #fff;

            color: #607d8b;

            font-size: 12px;

            font-weight: 600;

            text-decoration: none;

            transition: all .2s ease;
        }

        .pagination li a:hover {
            background: var(--crm-primary);

            border-color: var(--crm-primary);

            color: #fff;

            transform: translateY(-1px);
        }

        .pagination li.active span {
            background: var(--crm-primary);

            border-color: var(--crm-primary);

            color: #fff;

            font-weight: 700;

            box-shadow: 0 3px 9px rgba(8, 66, 107, .18);
        }

        .pagination li.disabled span {
            background: #f5f7f8;

            color: #b0bec5;

            cursor: not-allowed;
        }


        /* =========================================================
   EMPTY STATE
========================================================= */

        .modern-table tbody tr td.text-center {
            padding: 45px 20px;

            color: #90a4ae;

            font-size: 13px;
        }


        /* =========================================================
   MOBILE CARD VIEW
========================================================= */

        @media (max-width: 768px) {

            .modern-table-wrapper {
                background: transparent;

                border: 0;

                box-shadow: none;

                overflow: visible;
            }


            /* Convert table to cards */

            .modern-table,
            .modern-table thead,
            .modern-table tbody,
            .modern-table th,
            .modern-table td,
            .modern-table tr {
                display: block;

                width: 100%;
            }


            .modern-table thead {
                display: none;
            }


            /* Card */

            .modern-table tbody tr {
                position: relative;

                margin-bottom: 12px;

                padding: 13px;

                background: #fff;

                border: 1px solid var(--crm-border);

                border-radius: 13px;

                box-shadow: 0 3px 14px rgba(8, 66, 107, .07);

                transition: all .2s ease;
            }

            .modern-table tbody tr:hover {
                background: #fff;

                box-shadow: 0 7px 20px rgba(8, 66, 107, .10);

                transform: translateY(-1px);
            }


            /* Cells */

            .modern-table td {
                display: flex;

                align-items: center;

                justify-content: space-between;

                min-height: 35px;

                padding: 6px 0;

                border: 0;

                font-size: 12px;

                text-align: right;
            }


            /* Labels */

            .modern-table td::before {
                content: attr(data-label);

                flex: 0 0 auto;

                margin-right: 15px;

                color: #78909c;

                font-size: 10px;

                font-weight: 700;

                text-align: left;

                text-transform: uppercase;

                letter-spacing: .3px;
            }


            /* =====================================================
       MOBILE MEMBER HEADER
    ===================================================== */

            .modern-table td.member-info {
                display: block;

                padding: 0 0 11px;

                margin-bottom: 6px;

                border-bottom: 1px solid var(--crm-border);

                text-align: left;
            }

            .modern-table td.member-info::before {
                display: none;
            }

            .member-cell {
                min-width: 0;

                gap: 11px;
            }

            .avatar {
                width: 46px;
                height: 46px;

                min-width: 46px;

                border-radius: 11px;
            }

            .name {
                max-width: 220px;

                font-size: 14px;
            }

            .member-cell small {
                max-width: 230px;

                font-size: 10px;
            }


            /* =====================================================
       MOBILE BUSINESS
    ===================================================== */

            .business {
                min-width: 0;

                font-size: 12px;

                text-align: right;
            }

            .business p {
                margin: 4px 0 0;
            }

            .business small {
                font-size: 9px;
            }


            /* =====================================================
       MOBILE ROLE
    ===================================================== */

            .role-badge {
                padding: 6px 10px;

                font-size: 10px;

                border-radius: 7px;
            }


            /* =====================================================
       MOBILE STATUS
    ===================================================== */

            .status {
                min-width: 72px;

                padding: 6px 10px;

                font-size: 10px;
            }


            /* =====================================================
       MOBILE ACTIONS
    ===================================================== */

            .modern-table td.actions {
                display: flex;

                align-items: center;

                justify-content: center;

                gap: 7px;

                padding: 10px 0 2px;

                margin-top: 7px;

                border-top: 1px solid var(--crm-border);
            }

            .modern-table td.actions::before {
                display: none;
            }

            .actions .btn {
                width: 36px;
                height: 36px;

                margin: 0 !important;

                border-radius: 8px;

                font-size: 12px;
            }


            /* =====================================================
       MOBILE PAGINATION
    ===================================================== */

            .pagination-wrapper {
                margin-top: 10px;
            }

            .pagination {
                gap: 4px;
            }

            .pagination li a,
            .pagination li span {
                min-width: 30px;

                height: 30px;

                padding: 0 8px;

                font-size: 11px;

                border-radius: 7px;
            }
        }


        /* =========================================================
   SMALL MOBILE
========================================================= */

        @media (max-width: 420px) {

            .modern-table tbody tr {
                padding: 11px;
            }

            .avatar {
                width: 42px;
                height: 42px;

                min-width: 42px;
            }

            .name {
                max-width: 180px;

                font-size: 13px;
            }

            .member-cell small {
                max-width: 190px;

                font-size: 9px;
            }

            .modern-table td {
                font-size: 11px;
            }

            .modern-table td::before {
                font-size: 9px;
            }

            .business {
                font-size: 11px;
            }

            .business small {
                font-size: 8px;
            }

            .role-badge {
                font-size: 9px;

                padding: 6px 9px;
            }

            .status {
                font-size: 9px;

                padding: 6px 9px;
            }

            .actions .btn {
                width: 34px;
                height: 34px;

                font-size: 11px;
            }
        }

        :root {
            --crm-primary: #0a3d61;
            --crm-primary-light: #edf5f9;
            --crm-text: #263238;
            --crm-muted: #78909c;
            --crm-border: #e4ebef;

            --crm-success: #16865c;
            --crm-warning: #d99100;
            --crm-danger: #d9534f;
        }


        /* ==========================================
       MEMBER FILTER CARD
    ========================================== */

        .crm-filter-card {
            background: #fff;
            border: 1px solid var(--crm-border);
            border-radius: 12px;
            padding: 16px 18px;
            margin-bottom: 15px;
            box-shadow: 0 3px 14px rgba(10, 61, 97, .04);
        }

        .crm-filter-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 13px;
        }

        .crm-filter-title {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .crm-filter-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--crm-primary-light);
            color: var(--crm-primary);
            font-size: 15px;
        }

        .crm-filter-title h6 {
            margin: 0;
            color: var(--crm-text);
            font-size: 14px;
            font-weight: 700;
        }

        .crm-filter-title span {
            display: block;
            color: var(--crm-muted);
            font-size: 10px;
            margin-top: 2px;
        }


        /* ==========================================
       FORM CONTROLS
    ========================================== */

        .crm-filter-label {
            display: block;
            margin-bottom: 5px;
            color: #607d8b;
            font-size: 10px;
            font-weight: 600;
        }

        .crm-filter-control {
            height: 38px !important;
            border: 1px solid #dce5ea !important;
            border-radius: 7px !important;
            color: var(--crm-text);
            font-size: 12px !important;
            background-color: #fff !important;
            box-shadow: none !important;
        }

        .crm-filter-control:focus {
            border-color: var(--crm-primary) !important;
            box-shadow: 0 0 0 3px rgba(10, 61, 97, .08) !important;
        }

        .crm-reset-btn {
            height: 38px;
            border-radius: 7px;
            border: 1px solid #dce5ea;
            background: #fff;
            color: #607d8b;
            font-size: 11px;
            font-weight: 600;
            transition: all .2s ease;
        }

        .crm-reset-btn:hover {
            background: var(--crm-primary);
            border-color: var(--crm-primary);
            color: #fff;
        }


        /* ==========================================
       MEMBER STATISTICS
    ========================================== */

        .crm-member-stat {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 78px;
            background: #fff;
            border: 1px solid var(--crm-border);
            border-radius: 10px;
            padding: 12px 14px;
            overflow: hidden;
            transition: all .25s ease;
            box-shadow: 0 2px 9px rgba(10, 61, 97, .035);
        }

        .crm-member-stat::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--crm-primary);
        }

        .crm-member-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 18px rgba(10, 61, 97, .09);
        }


        /* ==========================================
       STAT ICON
    ========================================== */

        .crm-stat-icon {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--crm-primary-light);
            color: var(--crm-primary);
            font-size: 19px;
            transition: all .2s ease;
        }

        .crm-member-stat:hover .crm-stat-icon {
            background: var(--crm-primary);
            color: #fff;
        }


        /* ==========================================
       STAT CONTENT
    ========================================== */

        .crm-stat-content {
            min-width: 0;
            flex: 1;
        }

        .crm-stat-label {
            display: block;
            color: var(--crm-muted);
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .2px;
            margin-bottom: 3px;
        }

        .crm-stat-value {
            display: block;
            color: var(--crm-text);
            font-size: 20px;
            line-height: 1;
            font-weight: 700;
        }

        .crm-stat-arrow {
            color: #b0bec5;
            font-size: 11px;
            transition: all .2s ease;
        }

        .crm-member-stat:hover .crm-stat-arrow {
            color: var(--crm-primary);
            transform: translateX(3px);
        }


        /* ==========================================
       STATUS VARIANTS
    ========================================== */

        .crm-stat-active::before {
            background: var(--crm-success);
        }

        .crm-stat-active .crm-stat-icon {
            background: #e9f7f1;
            color: var(--crm-success);
        }

        .crm-stat-hold::before {
            background: var(--crm-warning);
        }

        .crm-stat-hold .crm-stat-icon {
            background: #fff6e2;
            color: var(--crm-warning);
        }

        .crm-stat-blocked::before {
            background: var(--crm-danger);
        }

        .crm-stat-blocked .crm-stat-icon {
            background: #fff0ef;
            color: var(--crm-danger);
        }


        /* ==========================================
       MOBILE
    ========================================== */

        @media (max-width: 767px) {

            .crm-filter-card {
                padding: 13px;
                border-radius: 10px;
            }

            .crm-filter-header {
                margin-bottom: 11px;
            }

            .crm-filter-title h6 {
                font-size: 13px;
            }

            .crm-filter-title span {
                font-size: 9px;
            }

            .crm-filter-control,
            .crm-reset-btn {
                height: 36px !important;
            }

            .crm-member-stat {
                min-height: 70px;
                padding: 10px;
                gap: 9px;
            }

            .crm-stat-icon {
                width: 38px;
                height: 38px;
                min-width: 38px;
                font-size: 16px;
            }

            .crm-stat-label {
                font-size: 9px;
            }

            .crm-stat-value {
                font-size: 17px;
            }

            .crm-stat-arrow {
                display: none;
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
            <div class="page-content" style="background-color:#f5f8f9 ;">

                <div class="row p-2">
                    <div class="col-md-6 col-6">
                        <h5>Manage Members</h5>
                    </div>
                    <div class="col-md-6 col-6">
                        <a class="btn btn-primary btn-sm" onclick="add();" style="font-size: 12px; font-weight: 500; float: right;background-color:#d11c2f;margin:2px;"> Add New Members <i class="fa fa-plus-circle ml-1"></i></a>
                    </div>
                </div>

                <!-- ==========================================
     MEMBER FILTER
========================================== -->

                <div class="crm-filter-card">

                    <div class="crm-filter-header">

                        <div class="crm-filter-title">

                            <div class="crm-filter-icon">
                                <i class="bi bi-funnel-fill"></i>
                            </div>

                            <div>
                                <h6>Member Filters</h6>
                                <span>Search and filter members</span>

                            </div>

                        </div>

                    </div>


                    <form id="memberFilterForm">

                        <div class="row g-2 align-items-end">

                            <!-- Search -->
                            <div class="col-lg-7 col-md-6 col-8">

                                <label class="crm-filter-label">
                                    Search Members
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text crm-filter-control"
                                        style="border-right:0 !important; border-radius:7px 0 0 7px !important;">
                                        <i class="bi bi-search"></i>
                                    </span>

                                    <input type="text"
                                        id="search"
                                        name="search"
                                        class="form-control crm-filter-control"
                                        style="border-left:0 !important; border-radius:0 7px 7px 0 !important;"
                                        placeholder="Search name, phone, email...">

                                </div>

                            </div>


                            <!-- Status -->
                            <div class="col-lg-3 col-md-3 col-4">

                                <label class="crm-filter-label">
                                    Status
                                </label>

                                <select class="form-select crm-filter-control"
                                    id="status1"
                                    name="status">

                                    <option value="">All Status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Pending</option>
                                    <option value="3">Blocked</option>

                                </select>

                            </div>


                            <!-- Reset -->
                            <div class="col-lg-2 col-md-3 col-12">

                                <button type="button"
                                    id="reset-btn"
                                    class="btn crm-reset-btn w-100">

                                    <i class="bi bi-arrow-clockwise me-1"></i>
                                    Reset

                                </button>

                            </div>

                        </div>

                    </form>

                </div>


                <!-- ==========================================
     MEMBER STATISTICS
========================================== -->

                <div class="row g-2 mb-3">

                    <!-- All Members -->
                    <div class="col-xl-3 col-md-6 col-6">

                        <div class="crm-member-stat">

                            <div class="crm-stat-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>

                            <div class="crm-stat-content">

                                <span class="crm-stat-label">
                                    All Members
                                </span>

                                <span class="crm-stat-value">
                                    <?= number_format($count_all ?? 0); ?>
                                </span>

                            </div>

                            <i class="bi bi-chevron-right crm-stat-arrow"></i>

                        </div>

                    </div>


                    <!-- Active Members -->
                    <div class="col-xl-3 col-md-6 col-6">

                        <div class="crm-member-stat crm-stat-active">

                            <div class="crm-stat-icon">
                                <i class="bi bi-person-check-fill"></i>
                            </div>

                            <div class="crm-stat-content">

                                <span class="crm-stat-label">
                                    Active Members
                                </span>

                                <span class="crm-stat-value">
                                    <?= number_format($count_active ?? 0); ?>
                                </span>

                            </div>

                            <i class="bi bi-chevron-right crm-stat-arrow"></i>

                        </div>

                    </div>


                    <!-- Hold Members -->
                    <div class="col-xl-3 col-md-6 col-6">

                        <div class="crm-member-stat crm-stat-hold">

                            <div class="crm-stat-icon">
                                <i class="bi bi-person-dash-fill"></i>
                            </div>

                            <div class="crm-stat-content">

                                <span class="crm-stat-label">
                                    Hold Members
                                </span>

                                <span class="crm-stat-value">
                                    <?= number_format($count_hold ?? 0); ?>
                                </span>

                            </div>

                            <i class="bi bi-chevron-right crm-stat-arrow"></i>

                        </div>

                    </div>


                    <!-- Blocked Members -->
                    <div class="col-xl-3 col-md-6 col-6">

                        <div class="crm-member-stat crm-stat-blocked">

                            <div class="crm-stat-icon">
                                <i class="bi bi-person-x-fill"></i>
                            </div>

                            <div class="crm-stat-content">

                                <span class="crm-stat-label">
                                    Blocked Members
                                </span>

                                <span class="crm-stat-value">
                                    <?= number_format($count_blocked ?? 0); ?>
                                </span>

                            </div>

                            <i class="bi bi-chevron-right crm-stat-arrow"></i>

                        </div>

                    </div>

                </div>


                <div class="container-fluid mt-1">
                    <div class="modern-table-wrapper">
                        <table class="modern-table">
                            <!-- TABLE HEADER -->
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Member</th>
                                    <th>Business</th>
                                    <th>Contact</th>
                                    <th>Location</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Join Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <!-- TABLE BODY -->
                            <tbody id="old_results">
                                <?php if (!empty($users)): ?>
                                    <?php
                                    $page = $pager->getCurrentPage('users') ?? 1;
                                    $i = 1 + ($perPage * ($page - 1));
                                    ?>
                                    <?php foreach ($users as $row): ?>
                                        <tr>
                                            <td data-label="S.No"><?= $i++ ?></td>
                                            <?php
                                            $commonModel = new \App\Models\CommonModel();

                                            $refCount = $commonModel->exe_query("
    SELECT COUNT(*) AS total
    FROM users
    WHERE refferd_by = '" . $row->user_code . "'
")->getResultArray();

                                            $totalRef = !empty($refCount) ? $refCount[0]['total'] : 0;
                                            ?>
                                            <td class="member-info">
                                                <div class="member-cell">
                                                    <img src="<?= base_url('uploads/profile/' . $row->profile_photo) ?>"
                                                        class="avatar"
                                                        style="object-fit:cover;object-position:top center;">

                                                    <div>
                                                        <div class="name"><?= esc($row->name) ?></div>
                                                        <small><?= esc($row->email) ?></small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td data-label="Business" class="business">
                                                <?= esc($row->business_name) ?>(<?= $totalRef; ?>)<br>
                                                <p><small class="text-dark"> <span style="color:#d11b2d;">Referred By :</span><?= esc($row->refferd_by_name) ?></small></p>
                                            </td>
                                            <td data-label="Phone">
                                                <?= esc($row->phone) ?>
                                            </td>

                                            <td data-label="Location">
                                                📍 <?= esc($row->city) ?>
                                            </td>

                                            <td data-label="Role">
                                                <span class="role-badge"
                                                    onclick="edit_status('<?= $row->user_code ?>');">
                                                    <?= esc($row->member_role) ?>
                                                </span>
                                            </td>

                                            <td data-label="Status">
                                                <?php if ($row->status == '1'): ?>
                                                    <span onclick="edit_status('<?= $row->user_code ?>');"
                                                        class="status active">Active</span>
                                                <?php elseif ($row->status == '2'): ?>
                                                    <span onclick="edit_status('<?= $row->user_code ?>');"
                                                        class="status pending">Pending</span>
                                                <?php else: ?>
                                                    <span onclick="edit_status('<?= $row->user_code ?>');"
                                                        class="status blocked">Blocked</span>
                                                <?php endif; ?>
                                            </td>

                                            <td data-label="Join Date">
                                                <?= esc($row->join_date) ?>
                                            </td>

                                            <td class="actions">
                                                <a class="btn btn-info btn-sm me-1"
                                                    style="background:#184b8d;"
                                                    href="<?= base_url('admin/profile/details/' . $row->user_code) ?>">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <a class="btn btn-warning btn-sm me-1"
                                                    href="<?= base_url('admin/members/edit_m/' . $row->user_code) ?>">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <a class="btn btn-danger btn-sm"
                                                    onclick="delete_user('<?= $row->user_code ?>');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center p-4">No Members Found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tbody id="list_data"></tbody>
                        </table>
                    </div>
                    <div class="row mt-2">
                        <div class="d-flex justify-content-center">
                            <?= $pager->links('default', 'default_full') ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page-content -->
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php echo view('include/admin/popup/members.php') ?>

    <script>
        $(document).ready(function() {

            const STORAGE_KEY = 'userFilters';

            /*
            |--------------------------------------------------------------------------
            | Debounce
            |--------------------------------------------------------------------------
            */
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

            /*
            |--------------------------------------------------------------------------
            | Collect Filters
            |--------------------------------------------------------------------------
            */
            function collectFilters() {

                return {
                    search: $.trim($('#search').val() || ''),
                    status: $('#status1').val() || ''
                };
            }

            /*
            |--------------------------------------------------------------------------
            | Check If Any Filter Is Active
            |--------------------------------------------------------------------------
            */
            function hasActiveFilters(filters) {

                return (
                    filters.search !== '' ||
                    filters.status !== ''
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Save Filters
            |--------------------------------------------------------------------------
            */
            function saveFiltersToStorage() {

                const filters = collectFilters();

                if (hasActiveFilters(filters)) {
                    localStorage.setItem(
                        STORAGE_KEY,
                        JSON.stringify(filters)
                    );
                } else {
                    localStorage.removeItem(STORAGE_KEY);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Load Filters
            |--------------------------------------------------------------------------
            */
            function loadFiltersFromStorage() {

                const stored = localStorage.getItem(STORAGE_KEY);

                if (!stored) {
                    return null;
                }

                try {

                    const filters = JSON.parse(stored);

                    if (!filters) {
                        return null;
                    }

                    $('#search').val(filters.search || '');
                    $('#status1').val(
                        filters.status !== undefined && filters.status !== null ?
                        String(filters.status) :
                        ''
                    );

                    return filters;

                } catch (error) {

                    console.error('Invalid filter storage:', error);

                    localStorage.removeItem(STORAGE_KEY);

                    return null;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Load Users
            |--------------------------------------------------------------------------
            */
            const loadUsers = debounce(function() {

                const formData = collectFilters();

                /*
                |--------------------------------------------------------------------------
                | No Filters
                |--------------------------------------------------------------------------
                */
                if (!hasActiveFilters(formData)) {

                    $('#list_data')
                        .stop(true, true)
                        .hide()
                        .html('');

                    $('#old_results')
                        .stop(true, true)
                        .show();

                    localStorage.removeItem(STORAGE_KEY);

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Save Filters
                |--------------------------------------------------------------------------
                */
                saveFiltersToStorage();

                /*
                |--------------------------------------------------------------------------
                | AJAX
                |--------------------------------------------------------------------------
                */
                $.ajax({

                    type: 'POST',

                    url: '<?= base_url("admin/members/get_filtered_data") ?>',

                    data: formData,

                    dataType: 'html',

                    cache: false,

                    beforeSend: function() {

                        $('#old_results').hide();

                        $('#list_data')
                            .show()
                            .html(`
                        <div class="text-center py-4">
                            <div class="spinner-border spinner-border-sm text-primary mb-2"></div>
                            <div class="small text-muted">
                                Loading users...
                            </div>
                        </div>
                    `);
                    },

                    success: function(response) {

                        $('#old_results').hide();

                        $('#list_data')
                            .html(response)
                            .show();
                    },

                    error: function(xhr, status, error) {

                        console.error('AJAX Error:', {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });

                        $('#list_data')
                            .show()
                            .html(`
                        <div class="alert alert-danger text-center mb-0">
                            <i class="fa fa-exclamation-triangle me-1"></i>
                            Unable to load users. Please try again.
                        </div>
                    `);
                    }

                });

            }, 400);

            /*
            |--------------------------------------------------------------------------
            | Search Filter
            |--------------------------------------------------------------------------
            */
            $('#search').on('input', function() {
                loadUsers();
            });

            /*
            |--------------------------------------------------------------------------
            | Status Filter
            |--------------------------------------------------------------------------
            */
            $('#status1').on('change', function() {
                loadUsers();
            });

            /*
            |--------------------------------------------------------------------------
            | Reset
            |--------------------------------------------------------------------------
            */
            $('#reset-btn').on('click', function(e) {

                e.preventDefault();

                $('#search').val('');
                $('#status1').val('');

                localStorage.removeItem(STORAGE_KEY);

                $('#list_data')
                    .stop(true, true)
                    .hide()
                    .html('');

                $('#old_results')
                    .stop(true, true)
                    .show();
            });

            /*
            |--------------------------------------------------------------------------
            | Load Saved Filters
            |--------------------------------------------------------------------------
            */
            const savedFilters = loadFiltersFromStorage();

            if (savedFilters && hasActiveFilters(savedFilters)) {

                /*
                 * Small delay so DOM is fully ready
                 */
                setTimeout(function() {
                    loadUsers();
                }, 100);

            } else {

                $('#list_data').hide();
                $('#old_results').show();
            }

        });
    </script>
</body>

</html>