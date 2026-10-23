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

        /* wrapper */
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

        /* STATUS BADGES */
        .status {
            padding: 6px 14px;
            /* more padding for better look */
            border-radius: 20px;
            font-size: 13px;
            /* readable size */
            font-weight: 600;
            display: inline-block;
            text-align: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        /* Colors for each referral status */
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

        /* Hover effect */
        .status:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .pagination-wrapper {
            margin-top: 20px;
        }

        .pagination {
            display: flex;
            gap: 6px;
            padding: 0;
            list-style: none;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination li a,
        .pagination li span {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            color: #333;
            background: #fff;
            font-size: 14px;
            transition: all 0.25s ease;
            text-decoration: none;
        }

        /* Hover effect */
        .pagination li a:hover {
            background: #bb171d;
            color: #fff;
            border-color: #bb171d;
            transform: translateY(-1px);
        }

        /* Active page */
        .pagination li.active span {
            background: #bb171d;
            color: #fff;
            border-color: #bb171d;
            font-weight: 600;
        }

        /* Disabled buttons */
        .pagination li.disabled span {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* First/Last/Prev/Next styling */
        .pagination li:first-child a,
        .pagination li:last-child a {
            font-weight: 500;
        }

        /* ==========================
   MOBILE CARD VIEW
========================== */
        @media (max-width: 768px) {

            .modern-table-wrapper {
                overflow: hidden !important;
                background: transparent;
                box-shadow: none;
                padding: 0;
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

            /* Hide header */
            .modern-table thead {
                display: none;
            }

            /* Card */
            .modern-table tbody tr {
                background: #fff;
                border-radius: 12px;
                margin-bottom: 10px;
                padding: 10px;
                border: 1px solid #eee;
                box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            }

            /* Normal rows */
            .modern-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 6px 0;
                border: 0;
                font-size: 12px;
                text-align: right;
            }

            .modern-table td:before {
                content: attr(data-label);
                font-weight: 600;
                color: #555;
                text-align: left;
            }

            /* Member block */
            .modern-table td.member-info {
                display: block;
                text-align: left;
                border-bottom: 1px solid #eee;
                padding-bottom: 8px;
                margin-bottom: 8px;
            }

            .modern-table td.member-info:before {
                display: none;
            }

            .member-cell {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .avatar {
                width: 42px;
                height: 42px;
            }

            .name {
                font-size: 13px;
                margin-bottom: 2px;
            }

            .member-cell small {
                font-size: 11px;
            }

            .business {
                font-size: 12px;
            }

            /* Status */
            .status,
            .role-badge {
                padding: 3px 8px;
                font-size: 11px;
            }

            /* Actions */
            .modern-table td.actions {
                display: flex;
                justify-content: center;
                gap: 5px;
                border-top: 1px solid #eee;
                margin-top: 8px;
                padding-top: 8px;
            }

            .modern-table td.actions:before {
                display: none;
            }

            .btn-sm {
                padding: 4px 8px;
                font-size: 11px;
                border-radius: 6px;
            }

            /* Pagination */
            .pagination {
                justify-content: center;
                flex-wrap: wrap;
                gap: 4px;
            }

            .pagination li a,
            .pagination li span {
                padding: 5px 8px;
                font-size: 11px;
            }
        }



        :root {
            --crm-primary: #08426b;
            --crm-primary-dark: #063653;
            --crm-primary-hover: #0b527f;
            --crm-primary-light: #eef6fb;
            --crm-primary-soft: #f5f9fc;

            --crm-text: #263238;
            --crm-text-dark: #1f2d35;
            --crm-muted: #71828c;
            --crm-muted-light: #90a4ae;

            --crm-border: #dfe7ec;
            --crm-border-light: #edf2f5;

            --crm-green: #16865c;
            --crm-orange: #d99100;
            --crm-red: #d9534f;
        }


        /* =========================================================
   TABLE WRAPPER
========================================================= */

        .crm-table-wrapper {
            width: 100%;
            background: #ffffff;

            border: 1px solid var(--crm-border);

            border-radius: 14px;

            box-shadow:
                0 4px 18px rgba(8, 66, 107, 0.06);

            overflow-x: auto;

            -webkit-overflow-scrolling: touch;
        }


        /* =========================================================
   TABLE
========================================================= */

        .crm-connect-table {
            width: 100%;

            min-width: 1150px;

            border-collapse: separate;
            border-spacing: 0;

            background: #ffffff;

            font-family:
                'Segoe UI',
                Tahoma,
                Geneva,
                Verdana,
                sans-serif;

            font-size: 14px;
        }


        /* =========================================================
   TABLE HEADER
========================================================= */

        .crm-connect-table thead {
            background: var(--crm-primary);
        }

        .crm-connect-table thead th {
            padding: 14px 11px;

            color: #ffffff;

            font-size: 12px;

            font-weight: 700;

            line-height: 1.3;

            text-align: left;

            text-transform: uppercase;

            letter-spacing: 0.4px;

            white-space: nowrap;

            border: 0;
        }

        .crm-connect-table thead th:first-child {
            border-radius: 13px 0 0 0;
        }

        .crm-connect-table thead th:last-child {
            border-radius: 0 13px 0 0;
        }


        /* =========================================================
   TABLE BODY
========================================================= */

        .crm-connect-table tbody tr {
            background: #ffffff;

            transition:
                background 0.2s ease,
                box-shadow 0.2s ease;
        }

        .crm-connect-table tbody tr:hover {
            background: #f9fcfe;

            box-shadow:
                inset 3px 0 0 var(--crm-primary);
        }

        .crm-connect-table tbody td {
            padding: 13px 11px;

            vertical-align: middle;

            color: var(--crm-text);

            font-size: 13px;

            line-height: 1.4;

            border-bottom: 1px solid var(--crm-border-light);
        }

        .crm-connect-table tbody tr:last-child td {
            border-bottom: 0;
        }


        /* =========================================================
   SERIAL NUMBER
========================================================= */

        .crm-sno {
            width: 55px;

            text-align: center;
        }

        .crm-number {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            width: 30px;
            height: 30px;

            border-radius: 8px;

            background: var(--crm-primary-light);

            color: var(--crm-primary);

            font-size: 12px;

            font-weight: 700;
        }


        /* =========================================================
   MEMBER
========================================================= */

        .crm-member {
            display: flex;

            align-items: center;

            gap: 11px;

            min-width: 205px;
        }


        /* =========================================================
   AVATAR
========================================================= */

        .crm-avatar-wrapper {
            position: relative;

            flex-shrink: 0;
        }

        .crm-avatar {
            width: 46px;
            height: 46px;

            display: block;

            border-radius: 11px;

            object-fit: cover;

            object-position: top center;

            background: #f3f6f8;

            border: 2px solid #e9eff3;

            transition: all 0.2s ease;
        }

        .crm-connect-table tbody tr:hover .crm-avatar {
            border-color: #bfd4df;

            transform: scale(1.04);
        }


        /* =========================================================
   ONLINE DOT
========================================================= */

        .crm-online-dot {
            position: absolute;

            right: -2px;

            bottom: -1px;

            width: 10px;
            height: 10px;

            border-radius: 50%;

            background: #20a46a;

            border: 2px solid #ffffff;
        }


        /* =========================================================
   MEMBER DETAILS
========================================================= */

        .crm-member-details {
            min-width: 0;
        }

        .crm-member-name {
            max-width: 190px;

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;

            color: var(--crm-text-dark);

            font-size: 14px;

            font-weight: 700;

            line-height: 1.35;
        }

        .crm-member-email {
            display: flex;

            align-items: center;

            gap: 5px;

            max-width: 190px;

            margin-top: 4px;

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;

            color: var(--crm-muted);

            font-size: 11px;

            line-height: 1.3;
        }

        .crm-member-email i {
            color: #9aaab3;

            font-size: 10px;
        }


        /* =========================================================
   CONNECTION ARROW
========================================================= */

        .crm-connection-arrow {
            width: 55px;

            text-align: center;
        }

        .crm-connect-icon {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            width: 31px;
            height: 31px;

            border-radius: 50%;

            background: var(--crm-primary-light);

            color: var(--crm-primary);

            font-size: 11px;

            transition: all 0.2s ease;
        }

        .crm-connect-table tbody tr:hover .crm-connect-icon {
            background: var(--crm-primary);

            color: #ffffff;

            transform: translateX(3px);
        }


        /* =========================================================
   BUSINESS
========================================================= */

        .crm-business {
            display: flex;

            align-items: center;

            gap: 10px;

            min-width: 165px;
        }

        .crm-business-icon {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            width: 35px;
            height: 35px;

            flex-shrink: 0;

            border-radius: 9px;

            background: var(--crm-primary-light);

            color: var(--crm-primary);

            font-size: 13px;
        }

        .crm-business-name {
            max-width: 160px;

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;

            color: var(--crm-primary);

            font-size: 13px;

            font-weight: 700;

            line-height: 1.3;
        }

        .crm-business small {
            display: block;

            margin-top: 3px;

            color: var(--crm-muted-light);

            font-size: 10px;

            line-height: 1.2;
        }


        /* =========================================================
   CONTACT
========================================================= */

        .crm-contact {
            display: flex;

            align-items: center;

            gap: 8px;

            color: #536872;

            font-size: 13px;

            font-weight: 600;

            white-space: nowrap;
        }

        .crm-contact-icon {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            width: 30px;
            height: 30px;

            flex-shrink: 0;

            border-radius: 8px;

            background: #f1f6f9;

            color: var(--crm-primary);

            font-size: 11px;
        }


        /* =========================================================
   STATUS - BASE
========================================================= */

        .crm-status {
            display: inline-flex;

            align-items: center;

            gap: 7px;

            min-width: 110px;

            padding: 8px 11px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: 700;

            line-height: 1;

            white-space: nowrap;

            cursor: pointer;

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .crm-status:hover {
            transform: translateY(-1px);

            box-shadow:
                0 4px 10px rgba(0, 0, 0, 0.08);
        }


        /* Status dot */

        .crm-status-dot {
            width: 7px;
            height: 7px;

            flex-shrink: 0;

            border-radius: 50%;
        }


        /* Status arrow */

        .crm-status-arrow {
            margin-left: auto;

            font-size: 9px;

            opacity: 0.55;
        }


        /* =========================================================
   PENDING
========================================================= */

        .crm-status-pending {
            background: #fff5df;

            color: #b77900;
        }

        .crm-status-pending .crm-status-dot {
            background: #d99100;
        }


        /* =========================================================
   CONTACTED
========================================================= */

        .crm-status-contacted {
            background: #eaf3ff;

            color: #2869a8;
        }

        .crm-status-contacted .crm-status-dot {
            background: #4285c5;
        }


        /* =========================================================
   COMPLETED
========================================================= */

        .crm-status-completed {
            background: #e8f7f0;

            color: #16865c;
        }

        .crm-status-completed .crm-status-dot {
            background: #16865c;
        }


        /* =========================================================
   CLOSED BUSINESS
========================================================= */

        .crm-status-closed {
            background: #edf6ef;

            color: #34783f;
        }

        .crm-status-closed .crm-status-dot {
            background: #34783f;
        }


        /* =========================================================
   MITB
========================================================= */

        .crm-status-business {
            background: #e9f7f5;

            color: #087f73;
        }

        .crm-status-business .crm-status-dot {
            background: #087f73;
        }


        /* =========================================================
   NOT CONVERTED
========================================================= */

        .crm-status-not-converted {
            background: #f2f3f5;

            color: #68757d;
        }

        .crm-status-not-converted .crm-status-dot {
            background: #7b8790;
        }


        /* =========================================================
   CANCELLED
========================================================= */

        .crm-status-cancelled {
            background: #fff0ef;

            color: #d9534f;
        }

        .crm-status-cancelled .crm-status-dot {
            background: #d9534f;
        }


        /* =========================================================
   DATE
========================================================= */

        .crm-date {
            display: flex;

            align-items: center;

            gap: 9px;

            min-width: 115px;
        }

        .crm-date-icon {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            width: 32px;
            height: 32px;

            flex-shrink: 0;

            border-radius: 8px;

            background: #f1f6f9;

            color: var(--crm-primary);

            font-size: 11px;
        }

        .crm-date-main {
            color: #455a64;

            font-size: 12px;

            font-weight: 700;

            line-height: 1.3;
        }

        .crm-date small {
            display: block;

            margin-top: 3px;

            color: var(--crm-muted-light);

            font-size: 10px;

            line-height: 1.2;
        }


        /* =========================================================
   ACTIONS
========================================================= */

        .crm-actions {
            display: flex;

            align-items: center;

            justify-content: center;

            gap: 6px;
        }

        .crm-action-btn {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            width: 35px;
            height: 35px;

            border-radius: 8px;

            text-decoration: none;

            font-size: 12px;

            transition:
                transform 0.2s ease,
                background 0.2s ease,
                color 0.2s ease,
                box-shadow 0.2s ease;
        }


        /* View */

        .crm-view {
            background: #eef6fb;

            color: var(--crm-primary);
        }

        .crm-view:hover {
            background: var(--crm-primary);

            color: #ffffff;

            transform: translateY(-2px);

            box-shadow:
                0 4px 10px rgba(8, 66, 107, 0.18);
        }


        /* Edit */

        .crm-edit {
            background: #fff5df;

            color: #c18400;
        }

        .crm-edit:hover {
            background: #d99100;

            color: #ffffff;

            transform: translateY(-2px);

            box-shadow:
                0 4px 10px rgba(217, 145, 0, 0.18);
        }


        /* Delete */

        .crm-delete {
            background: #fff0ef;

            color: #d9534f;
        }

        .crm-delete:hover {
            background: #d9534f;

            color: #ffffff;

            transform: translateY(-2px);

            box-shadow:
                0 4px 10px rgba(217, 83, 79, 0.18);
        }


        /* =========================================================
   EMPTY STATE
========================================================= */

        .crm-empty-state {
            padding: 60px 20px !important;

            text-align: center;

            border-bottom: 0 !important;
        }

        .crm-empty-icon {
            display: flex;

            align-items: center;
            justify-content: center;

            width: 62px;
            height: 62px;

            margin: 0 auto 14px;

            border-radius: 16px;

            background: var(--crm-primary-light);

            color: var(--crm-primary);

            font-size: 23px;
        }

        .crm-empty-title {
            color: #455a64;

            font-size: 16px;

            font-weight: 700;
        }

        .crm-empty-text {
            margin-top: 6px;

            color: var(--crm-muted-light);

            font-size: 12px;
        }


        /* =========================================================
   PAGINATION
========================================================= */

        .crm-pagination-wrapper {
            display: flex;

            justify-content: center;

            margin-top: 16px;

            margin-bottom: 5px;
        }

        .crm-pagination-wrapper .pagination {
            display: flex;

            align-items: center;

            justify-content: center;

            gap: 5px;

            margin: 0;

            padding: 0;

            flex-wrap: wrap;

            list-style: none;
        }

        .crm-pagination-wrapper .pagination li a,
        .crm-pagination-wrapper .pagination li span {
            display: flex;

            align-items: center;
            justify-content: center;

            min-width: 34px;

            height: 34px;

            padding: 0 10px;

            border: 1px solid var(--crm-border);

            border-radius: 8px;

            background: #ffffff;

            color: #546e7a;

            font-size: 12px;

            font-weight: 600;

            text-decoration: none;

            transition:
                background 0.2s ease,
                color 0.2s ease,
                border-color 0.2s ease;
        }

        .crm-pagination-wrapper .pagination li a:hover {
            background: var(--crm-primary);

            border-color: var(--crm-primary);

            color: #ffffff;
        }

        .crm-pagination-wrapper .pagination li.active span {
            background: var(--crm-primary);

            border-color: var(--crm-primary);

            color: #ffffff;

            font-weight: 700;
        }

        .crm-pagination-wrapper .pagination li.disabled span {
            opacity: 0.45;

            cursor: not-allowed;
        }


        /* =========================================================
   MOBILE CARD VIEW
========================================================= */

        @media (max-width: 768px) {

            .crm-table-wrapper {
                overflow: hidden;

                background: transparent;

                border: 0;

                box-shadow: none;
            }


            .crm-connect-table {
                width: 100%;

                min-width: 100%;

                display: block;
            }


            .crm-connect-table thead {
                display: none;
            }


            .crm-connect-table tbody {
                display: block;

                width: 100%;
            }


            .crm-connect-table tbody tr {
                display: block;

                width: 100%;

                margin-bottom: 13px;

                padding: 12px;

                border: 1px solid var(--crm-border);

                border-radius: 13px;

                background: #ffffff;

                box-shadow:
                    0 3px 14px rgba(8, 66, 107, 0.07);
            }


            .crm-connect-table tbody tr:hover {
                background: #ffffff;

                box-shadow:
                    0 5px 18px rgba(8, 66, 107, 0.11);
            }


            .crm-connect-table tbody td {
                display: flex;

                align-items: center;

                justify-content: space-between;

                width: 100%;

                min-height: 42px;

                padding: 7px 0;

                border: 0;

                font-size: 13px;

                text-align: right;
            }


            /* Mobile labels */

            .crm-connect-table tbody td::before {
                content: attr(data-label);

                flex-shrink: 0;

                margin-right: 12px;

                color: var(--crm-muted);

                font-size: 10px;

                font-weight: 700;

                text-align: left;

                text-transform: uppercase;

                letter-spacing: 0.3px;
            }


            /* =====================================================
       MOBILE MEMBER
    ====================================================== */

            .crm-connect-table tbody td.member-info {
                display: block;

                padding: 9px 0;

                text-align: left;

                border-bottom: 1px solid var(--crm-border-light);
            }


            .crm-connect-table tbody td.member-info::before {
                display: none;
            }


            .crm-member {
                width: 100%;

                min-width: 0;
            }


            .crm-avatar {
                width: 45px;

                height: 45px;
            }


            .crm-member-name {
                max-width: 220px;

                font-size: 14px;
            }


            .crm-member-email {
                max-width: 220px;

                font-size: 10px;
            }


            /* =====================================================
       CONNECTION ARROW
    ====================================================== */

            .crm-connect-table tbody td.crm-connection-arrow {
                display: none;
            }


            /* =====================================================
       BUSINESS
    ====================================================== */

            .crm-business {
                justify-content: flex-end;

                min-width: 0;
            }


            .crm-business-name {
                max-width: 190px;

                font-size: 13px;
            }


            .crm-business small {
                font-size: 10px;
            }


            /* =====================================================
       CONTACT
    ====================================================== */

            .crm-contact {
                font-size: 12px;
            }


            /* =====================================================
       STATUS
    ====================================================== */

            .crm-status {
                min-width: 105px;

                padding: 8px 10px;

                font-size: 10px;
            }


            /* =====================================================
       DATE
    ====================================================== */

            .crm-date {
                justify-content: flex-end;
            }


            .crm-date-main {
                font-size: 11px;
            }


            .crm-date small {
                font-size: 9px;
            }


            /* =====================================================
       ACTIONS
    ====================================================== */

            .crm-connect-table tbody td.actions {
                display: flex;

                justify-content: center;

                padding-top: 12px;

                margin-top: 6px;

                border-top: 1px solid var(--crm-border-light);
            }


            .crm-connect-table tbody td.actions::before {
                display: none;
            }


            .crm-action-btn {
                width: 38px;

                height: 38px;

                font-size: 13px;
            }


            /* =====================================================
       EMPTY STATE
    ====================================================== */

            .crm-empty-state {
                display: block !important;

                padding: 45px 15px !important;
            }


            .crm-empty-title {
                font-size: 15px;
            }


            .crm-empty-text {
                font-size: 11px;
            }


            /* =====================================================
       PAGINATION
    ====================================================== */

            .crm-pagination-wrapper {
                margin-top: 12px;
            }


            .crm-pagination-wrapper .pagination {
                gap: 4px;
            }


            .crm-pagination-wrapper .pagination li a,
            .crm-pagination-wrapper .pagination li span {
                min-width: 32px;

                height: 32px;

                padding: 0 8px;

                font-size: 11px;
            }

        }


        /* =========================================================
   SMALL MOBILE
========================================================= */

        @media (max-width: 420px) {

            .crm-connect-table tbody tr {
                padding: 10px;

                border-radius: 11px;
            }


            .crm-connect-table tbody td {
                font-size: 12px;
            }


            .crm-avatar {
                width: 42px;

                height: 42px;
            }


            .crm-member-name {
                max-width: 180px;

                font-size: 13px;
            }


            .crm-member-email {
                max-width: 180px;

                font-size: 9px;
            }


            .crm-business-name {
                max-width: 150px;

                font-size: 12px;
            }


            .crm-business small {
                font-size: 9px;
            }


            .crm-contact {
                font-size: 11px;
            }


            .crm-status {
                min-width: 92px;

                padding: 7px 8px;

                font-size: 9px;
            }


            .crm-date-main {
                font-size: 10px;
            }


            .crm-date small {
                font-size: 8px;
            }


            .crm-action-btn {
                width: 36px;

                height: 36px;

                font-size: 12px;
            }


            .crm-empty-title {
                font-size: 14px;
            }


            .crm-empty-text {
                font-size: 10px;
            }

        }


        /* =========================================================
   VERY SMALL DEVICES
========================================================= */

        @media (max-width: 360px) {

            .crm-connect-table tbody tr {
                padding: 9px;
            }


            .crm-member-name {
                max-width: 150px;

                font-size: 12px;
            }


            .crm-member-email {
                max-width: 150px;

                font-size: 8px;
            }


            .crm-business-name {
                max-width: 130px;

                font-size: 11px;
            }


            .crm-status {
                min-width: 85px;

                font-size: 8px;
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
                <div class="row p-2">
                    <div class="col-md-6 col-6">
                        <h5>Manage Connects:</h5>
                    </div>
                    <div class="col-md-6 col-6">
                        <a class="btn btn-primary btn-sm" onclick="add_new_connects();" style="font-size: 12px; font-weight: 500; float: right;background-color:#08426b;margin:2px;"> Add New Connects <i class="fa fa-plus-circle ml-1"></i></a>
                    </div>
                </div>
                <div class="card border shadow-sm mb-2 filter-bar">
                    <div class="card-body p-2">
                        <form id="search-form" method="POST">
                            <div class="row g-2">
                                <!-- Search -->
                                <div class="col-lg-6 col-md-6 col-8">
                                    <div class="input-group input-group-sm">
                                        <input type="text"
                                            id="search"
                                            name="search"
                                            class="form-control"
                                            placeholder="Search name, phone, email...">
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-lg-3 col-md-3 col-4">
                                    <select class="form-select form-select-sm" id="status1" name="status">
                                        <option value="">Select Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="Open For Discussion">Open For Discussion</option>
                                        <option value="contacted">Contacted</option>
                                        <option value="closed business">Closed Business</option>
                                        <option value="not converted">Not Converted</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                        <option value="business_done">MITB</option>
                                    </select>
                                </div>

                                <!-- Buttons -->
                                <div class="col-lg-3 col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-primary w-100">
                                        Filter
                                    </button>
                                    <button type="button" id="reset-btn" class="btn btn-sm btn-outline-secondary w-100">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="container-fluid mt-1">

                    <!-- =========================================================
         CRM CONNECT TABLE
    ========================================================== -->

                    <div class="crm-table-wrapper">

                        <table class="crm-connect-table">

                            <!-- =====================================================
                 HEADER
            ====================================================== -->

                            <?php
                            // Safe pagination values
                            $currentPage = 1;
                            $currentPerPage = isset($perPage) ? (int) $perPage : 10;

                            if (isset($pager) && $pager !== null) {
                                try {
                                    $currentPage = (int) ($pager->getCurrentPage('default') ?? 1);
                                } catch (\Throwable $e) {
                                    $currentPage = 1;
                                }
                            }

                            if ($currentPage < 1) {
                                $currentPage = 1;
                            }

                            if ($currentPerPage < 1) {
                                $currentPerPage = 10;
                            }

                            $i = 1 + ($currentPerPage * ($currentPage - 1));
                            ?>

                            <thead>

                                <tr>

                                    <th width="55">
                                        #
                                    </th>

                                    <th>
                                        From Member
                                    </th>

                                    <th width="50">
                                        Connect
                                    </th>

                                    <th>
                                        To Member
                                    </th>

                                    <th>
                                        Business
                                    </th>

                                    <th>
                                        Contact
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Created
                                    </th>

                                    <th width="110">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <!-- =====================================================
                 NORMAL SERVER-SIDE DATA
            ====================================================== -->

                            <tbody id="old_results">

                                <?php if (!empty($connect)): ?>

                                    <?php foreach ($connect as $row): ?>

                                        <tr class="crm-connect-row">

                                            <!-- =================================================
                                 S.NO
                            ================================================== -->

                                            <td
                                                data-label="S.No"
                                                class="crm-sno">

                                                <span class="crm-number">
                                                    <?= $i ?>
                                                </span>

                                            </td>


                                            <!-- =================================================
                                 FROM MEMBER
                            ================================================== -->

                                            <td
                                                class="member-info"
                                                data-label="From Member">

                                                <div class="crm-member">

                                                    <div class="crm-avatar-wrapper">

                                                        <img
                                                            src="<?= base_url('uploads/profile/' . ($row['from_profile'] ?? 'empty.png')) ?>"
                                                            class="crm-avatar"
                                                            alt="<?= esc($row['from_name'] ?? '') ?>"
                                                            onerror="this.src='<?= base_url('uploads/profile/empty.png') ?>';">

                                                        <span class="crm-online-dot"></span>

                                                    </div>


                                                    <div class="crm-member-details">

                                                        <div class="crm-member-name">
                                                            <?= esc($row['from_name'] ?? '') ?>
                                                        </div>

                                                        <div class="crm-member-email">

                                                            <i class="fa fa-envelope"></i>

                                                            <?= esc($row['from_email'] ?? '') ?>

                                                        </div>

                                                    </div>

                                                </div>

                                            </td>


                                            <!-- =================================================
                                 CONNECT ARROW
                            ================================================== -->

                                            <td
                                                class="crm-connection-arrow"
                                                data-label="Connect">

                                                <span class="crm-connect-icon">

                                                    <i class="fa fa-arrow-right"></i>

                                                </span>

                                            </td>


                                            <!-- =================================================
                                 TO MEMBER
                            ================================================== -->

                                            <td
                                                class="member-info"
                                                data-label="To Member">

                                                <div class="crm-member">

                                                    <div class="crm-avatar-wrapper">

                                                        <img
                                                            src="<?= base_url('uploads/profile/' . ($row['to_profile'] ?? 'empty.png')) ?>"
                                                            class="crm-avatar"
                                                            alt="<?= esc($row['to_name'] ?? '') ?>"
                                                            onerror="this.src='<?= base_url('uploads/profile/empty.png') ?>';">

                                                    </div>


                                                    <div class="crm-member-details">

                                                        <div class="crm-member-name">
                                                            <?= esc($row['to_name'] ?? '') ?>
                                                        </div>

                                                        <div class="crm-member-email">

                                                            <i class="fa fa-envelope"></i>

                                                            <?= esc($row['to_email'] ?? '') ?>

                                                        </div>

                                                    </div>

                                                </div>

                                            </td>


                                            <!-- =================================================
                                 BUSINESS
                            ================================================== -->

                                            <td
                                                class="business"
                                                data-label="Business">

                                                <div class="crm-business">

                                                    <span class="crm-business-icon">

                                                        <i class="fa fa-briefcase"></i>

                                                    </span>

                                                    <div>

                                                        <div class="crm-business-name">

                                                            <?= esc($row['business'] ?? '') ?>

                                                        </div>

                                                        <small>
                                                            Business Opportunity
                                                        </small>

                                                    </div>

                                                </div>

                                            </td>


                                            <!-- =================================================
                                 CONTACT
                            ================================================== -->

                                            <td data-label="Contact">

                                                <div class="crm-contact">

                                                    <span class="crm-contact-icon">

                                                        <i class="fa fa-phone"></i>

                                                    </span>

                                                    <span>
                                                        <?= esc($row['contact_number'] ?? '') ?>
                                                    </span>

                                                </div>

                                            </td>


                                            <!-- =================================================
                                 STATUS
                            ================================================== -->

                                            <td data-label="Status">

                                                <?php

                                                $status = strtolower(
                                                    trim($row['status'] ?? '')
                                                );

                                                $statusClass = match ($status) {

                                                    'pending'
                                                    => 'crm-status crm-status-pending',

                                                    'contacted'
                                                    => 'crm-status crm-status-contacted',

                                                    'completed'
                                                    => 'crm-status crm-status-completed',

                                                    'closed business'
                                                    => 'crm-status crm-status-closed',

                                                    'business_done'
                                                    => 'crm-status crm-status-business',

                                                    'not converted'
                                                    => 'crm-status crm-status-not-converted',

                                                    'cancelled'
                                                    => 'crm-status crm-status-cancelled',

                                                    default
                                                    => 'crm-status crm-status-pending'
                                                };

                                                ?>

                                                <span
                                                    class="<?= $statusClass ?>"
                                                    onclick="edit_refferral_status('<?= esc($row['referral_code'] ?? '', 'js') ?>');"
                                                    title="Click to edit status">

                                                    <span class="crm-status-dot"></span>

                                                    <?= esc(
                                                        ucwords(
                                                            str_replace(
                                                                '_',
                                                                ' ',
                                                                $row['status'] ?? 'Pending'
                                                            )
                                                        )
                                                    ) ?>

                                                    <i class="fa fa-angle-down crm-status-arrow"></i>

                                                </span>

                                            </td>


                                            <!-- =================================================
                                 CREATED DATE
                            ================================================== -->

                                            <td data-label="Created">

                                                <div class="crm-date">

                                                    <span class="crm-date-icon">

                                                        <i class="fa fa-calendar"></i>

                                                    </span>

                                                    <div>

                                                        <?php

                                                        $createdDate = null;

                                                        if (!empty($row['created_at'])) {

                                                            try {

                                                                $createdDate =
                                                                    new DateTime(
                                                                        $row['created_at']
                                                                    );
                                                            } catch (\Exception $e) {

                                                                $createdDate = null;
                                                            }
                                                        }

                                                        ?>

                                                        <?php if ($createdDate): ?>

                                                            <div class="crm-date-main">

                                                                <?= $createdDate->format('d M Y') ?>

                                                            </div>

                                                            <small>

                                                                <?= $createdDate->format('h:i A') ?>

                                                            </small>

                                                        <?php else: ?>

                                                            <div class="crm-date-main">
                                                                —
                                                            </div>

                                                        <?php endif; ?>

                                                    </div>

                                                </div>

                                            </td>


                                            <!-- =================================================
                                 ACTIONS
                            ================================================== -->

                                            <td
                                                class="actions"
                                                data-label="Actions">

                                                <div class="crm-actions">

                                                    <!-- VIEW -->

                                                    <a
                                                        href="<?= site_url('admin/connect/details/' . ($row['referral_code'] ?? '')) ?>"
                                                        class="crm-action-btn crm-view"
                                                        title="View Details">

                                                        <i class="fa fa-eye"></i>

                                                    </a>


                                                    <!-- EDIT -->

                                                    <a
                                                        href="javascript:void(0);"
                                                        onclick="edit_refferral('<?= esc($row['referral_code'] ?? '', 'js') ?>');"
                                                        class="crm-action-btn crm-edit"
                                                        title="Edit Connect">

                                                        <i class="fa fa-edit"></i>

                                                    </a>


                                                    <!-- DELETE -->

                                                    <a
                                                        href="javascript:void(0);"
                                                        onclick="delete_refferral('<?= esc($row['referral_code'] ?? '', 'js') ?>');"
                                                        class="crm-action-btn crm-delete"
                                                        title="Delete Connect">

                                                        <i class="fa fa-trash"></i>

                                                    </a>

                                                </div>

                                            </td>

                                        </tr>

                                        <?php $i++; ?>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>

                                        <td
                                            colspan="9"
                                            class="crm-empty-state">

                                            <div class="crm-empty-icon">

                                                <i class="fa fa-users"></i>

                                            </div>

                                            <div class="crm-empty-title">
                                                No Connects Found
                                            </div>

                                            <div class="crm-empty-text">
                                                No Connects connections are available.
                                            </div>

                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>


                            <!-- =====================================================
                 AJAX FILTER RESULTS
                 IMPORTANT:
                 Keep this separate from old_results.
            ====================================================== -->

                            <tbody
                                id="list_data"
                                style="display:none;">
                            </tbody>

                        </table>

                    </div>


                    <!-- =========================================================
         PAGINATION
         OLD UI KEPT
    ========================================================== -->

                    <?php if (isset($pager) && $pager !== null): ?>

                        <div class="crm-pagination-wrapper">

                            <?= $pager->links('default', 'default_full') ?>

                        </div>

                    <?php endif; ?>


                </div>
            </div>
            <!-- End Page-content -->
            <?php echo view('include/admin/footer.php') ?>
        </div>
        <!-- end main content-->
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

            /* ============================================================
               CONFIG
            ============================================================ */

            const filterStorageKey = 'userFilters';

            const filterUrl =
                '<?= base_url("admin/connect/get_filtered_data") ?>';


            /* ============================================================
               DEBOUNCE
            ============================================================ */

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


            /* ============================================================
               COLLECT FILTER VALUES
            ============================================================ */

            function collectFilters() {

                return {

                    search: $.trim(
                        $('#search').val() || ''
                    ),

                    status: $.trim(
                        $('#status1').val() || ''
                    )

                };
            }


            /* ============================================================
               CHECK ACTIVE FILTER
            ============================================================ */

            function hasActiveFilter(filters) {

                return (
                    filters.search !== '' ||
                    filters.status !== ''
                );

            }


            /* ============================================================
               SAVE FILTERS
            ============================================================ */

            function saveFiltersToStorage(filters) {

                if (hasActiveFilter(filters)) {

                    localStorage.setItem(
                        filterStorageKey,
                        JSON.stringify(filters)
                    );

                } else {

                    localStorage.removeItem(
                        filterStorageKey
                    );

                }

            }


            /* ============================================================
               LOAD FILTERS
            ============================================================ */

            function loadFiltersFromStorage() {

                const storedFilters =
                    localStorage.getItem(
                        filterStorageKey
                    );

                if (!storedFilters) {

                    return null;
                }


                try {

                    const filters =
                        JSON.parse(storedFilters);


                    if (
                        typeof filters !== 'object' ||
                        filters === null
                    ) {

                        localStorage.removeItem(
                            filterStorageKey
                        );

                        return null;
                    }


                    $('#search').val(
                        filters.search || ''
                    );


                    $('#status1').val(
                        filters.status || ''
                    );


                    return {

                        search: filters.search || '',

                        status: filters.status || ''

                    };

                } catch (error) {

                    console.error(
                        'Filter storage error:',
                        error
                    );


                    localStorage.removeItem(
                        filterStorageKey
                    );


                    return null;
                }
            }


            /* ============================================================
               HIDE OLD RESULTS
            ============================================================ */

            function hideOldResults() {

                $('#old_results')
                    .stop(true, true)
                    .hide();

            }


            /* ============================================================
               SHOW OLD RESULTS
            ============================================================ */

            function showOldResults() {

                $('#list_data')
                    .stop(true, true)
                    .hide()
                    .html('');


                $('#old_results')
                    .stop(true, true)
                    .show();

            }


            /* ============================================================
               CLEAR FILTERED RESULTS
            ============================================================ */

            function clearFilteredResults() {

                $('#list_data')
                    .stop(true, true)
                    .hide()
                    .html('');

            }


            /* ============================================================
               LOADING HTML
            ============================================================ */

            function loadingHtml() {

                return `
            <div class="text-center py-4">

                <div
                    class="spinner-border spinner-border-sm text-primary"
                    role="status">
                </div>

                <span class="ms-2">
                    Loading...
                </span>

            </div>
        `;
            }


            /* ============================================================
               ERROR HTML
            ============================================================ */

            function errorHtml() {

                return `
            <div class="alert alert-danger">

                <strong>Error:</strong>

                Unable to load filtered data.
                Please try again.

            </div>
        `;
            }


            /* ============================================================
               LOAD FILTERED DATA
            ============================================================ */

            const loadUsers = debounce(function() {

                const filters =
                    collectFilters();


                const anyFilter =
                    hasActiveFilter(filters);


                /* ========================================================
                   NO FILTER
                ======================================================== */

                if (!anyFilter) {

                    localStorage.removeItem(
                        filterStorageKey
                    );


                    showOldResults();

                    return;
                }


                /* ========================================================
                   FILTER ACTIVE
                ======================================================== */

                // IMPORTANT:
                // Hide old/original table immediately
                hideOldResults();


                // Save filters
                saveFiltersToStorage(
                    filters
                );


                /* ========================================================
                   SHOW LOADING
                ======================================================== */

                $('#list_data')
                    .stop(true, true)
                    .show()
                    .html(
                        loadingHtml()
                    );


                /* ========================================================
                   AJAX REQUEST
                ======================================================== */

                $.ajax({

                    type: 'POST',

                    url: filterUrl,

                    data: {

                        search: filters.search,

                        status: filters.status

                    },

                    dataType: 'html',

                    cache: false,


                    /* ----------------------------------------------------
                       BEFORE SEND
                    ---------------------------------------------------- */

                    beforeSend: function() {

                        // Never show old data
                        hideOldResults();


                        $('#list_data')
                            .show();

                    },


                    /* ----------------------------------------------------
                       SUCCESS
                    ---------------------------------------------------- */

                    success: function(response) {

                        // Check again because user may have
                        // cleared the filter while request was running.

                        const latestFilters =
                            collectFilters();


                        if (
                            !hasActiveFilter(
                                latestFilters
                            )
                        ) {

                            showOldResults();

                            return;
                        }


                        // Keep original data hidden
                        hideOldResults();


                        // Show filtered response
                        $('#list_data')
                            .html(response)
                            .show();

                    },


                    /* ----------------------------------------------------
                       ERROR
                    ---------------------------------------------------- */

                    error: function(
                        xhr,
                        status,
                        error
                    ) {

                        console.error(
                            'AJAX Error:',
                            error
                        );


                        const latestFilters =
                            collectFilters();


                        // If filter was cleared while
                        // request was running
                        if (
                            !hasActiveFilter(
                                latestFilters
                            )
                        ) {

                            showOldResults();

                            return;
                        }


                        // Keep old data hidden
                        hideOldResults();


                        $('#list_data')
                            .html(
                                errorHtml()
                            )
                            .show();

                    }

                });

            }, 400);


            /* ============================================================
               SEARCH FILTER
            ============================================================ */

            $('#search').on(
                'keyup',
                function() {

                    const filters =
                        collectFilters();


                    if (
                        hasActiveFilter(
                            filters
                        )
                    ) {

                        // Hide old data immediately
                        hideOldResults();


                        // Show loading area
                        $('#list_data')
                            .show()
                            .html(
                                loadingHtml()
                            );

                    }


                    loadUsers();

                }
            );


            /* ============================================================
               STATUS FILTER
            ============================================================ */

            $('#status1').on(
                'change',
                function() {

                    const filters =
                        collectFilters();


                    if (
                        hasActiveFilter(
                            filters
                        )
                    ) {

                        // Hide old data immediately
                        hideOldResults();


                        // Show loading area
                        $('#list_data')
                            .show()
                            .html(
                                loadingHtml()
                            );

                    }


                    loadUsers();

                }
            );


            /* ============================================================
               RESET BUTTON
            ============================================================ */

            $('#reset-btn').on(
                'click',
                function(e) {

                    e.preventDefault();


                    /* ----------------------------------------------------
                       Clear inputs
                    ---------------------------------------------------- */

                    $('#search')
                        .val('');


                    $('#status1')
                        .val('');


                    /* ----------------------------------------------------
                       Clear local storage
                    ---------------------------------------------------- */

                    localStorage.removeItem(
                        filterStorageKey
                    );


                    /* ----------------------------------------------------
                       Clear AJAX results
                    ---------------------------------------------------- */

                    clearFilteredResults();


                    /* ----------------------------------------------------
                       Show original data
                    ---------------------------------------------------- */

                    $('#old_results')
                        .stop(true, true)
                        .show();

                }
            );


            /* ============================================================
               INITIAL PAGE LOAD
            ============================================================ */

            const savedFilters =
                loadFiltersFromStorage();


            if (
                savedFilters &&
                hasActiveFilter(
                    savedFilters
                )
            ) {

                /* --------------------------------------------------------
                   SAVED FILTER EXISTS
                -------------------------------------------------------- */

                // IMPORTANT:
                // Hide original records immediately
                hideOldResults();


                // Show loading
                $('#list_data')
                    .show()
                    .html(
                        loadingHtml()
                    );


                // Load filtered records
                loadUsers();

            } else {

                /* --------------------------------------------------------
                   NO SAVED FILTER
                -------------------------------------------------------- */

                localStorage.removeItem(
                    filterStorageKey
                );


                clearFilteredResults();


                // Show original table
                $('#old_results')
                    .show();

            }

        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php echo view('include/admin/popup/connects.php') ?>
</body>

</html>