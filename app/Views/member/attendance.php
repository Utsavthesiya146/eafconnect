<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Blank</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Container and Typography */
        .container h3 {
            font-weight: 600;
            color: #08426b;
        }

        .container p {
            font-size: 0.95rem;
            color: #6c757d;
        }

        /* Alert Box */
        .alert {
            font-weight: 500;
            font-size: 0.95rem;
        }

        /* Table Styling */
        .table-responsive {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
            background-color: #fff;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background-color: #08426b;
            color: #fff;
            font-weight: 600;
            border: none;
            vertical-align: middle;
            text-align: left;
        }

        .table tbody tr {
            transition: background 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f9f2f2;
        }

        /* Profile Image */
        .member-avatar {
            width: 40px;
            height: 40px;
            object-fit: cover;
            object-position: top center;
            border-radius: 50%;
            border: 1px solid #ddd;
        }

        /* Status Dropdown */
        .form-select-sm {
            min-width: 120px;
            border-radius: 0.35rem;
            font-size: 0.875rem;
        }

        /* Note Input */
        .form-control-sm {
            font-size: 0.875rem;
            border-radius: 0.35rem;
            border-width: 1.5px;
        }

        /* Button */
        .btn-lg {
            font-weight: 500;
            border-radius: 0.5rem;
            padding: 0.6rem 1.5rem;
            transition: background 0.2s ease, transform 0.1s ease;
        }

        .btn-lg:hover {
            background-color: #08426b;
            /* Slightly darker red */
            transform: translateY(-1px);
        }

        /* Responsive Adjustments */
        @media (max-width: 767px) {
            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border-bottom: 2px solid #efefef;
            }

            .table tbody td {
                display: flex;
                justify-content: space-between;
                padding: 0.5rem;
                border: none;
            }

            .table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #333;
            }
        }

        .badge-option input {
            display: none;
        }

        .badge-option span {
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 20px;
            opacity: 0.6;
            transition: 0.2s;
        }

        .badge-option input:checked+span {
            opacity: 1;
            transform: scale(1.1);
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        .status-checkbox {
            margin-right: 4px;
            transform: scale(1.1);
        }

        td label {
            margin-right: 10px;
            cursor: pointer;
        }

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

        .f2f-tabs {
            display: flex;
            gap: 12px;
        }

        .tab-btn {
            padding: 10px 18px;
            border-radius: 8px;
            background: #f3f4f6;
            color: #555;
            text-decoration: none;
            font-weight: 600;
            transition: .3s;
        }

        .tab-btn:hover {
            background: #e5e7eb;
        }

        .tab-btn.active {
            background: #184b8d;
            color: #fff;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .15);
        }

        .member-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
        }

        .status {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
            color: #fff;
        }

        .status.scheduled {
            background: #3b82f6;
        }

        .status.completed {
            background: #10b981;
        }

        .status.cancelled {
            background: #ef4444;
        }

        .status.pending {
            background: #f59e0b;
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
            background: #0F3B68;
            color: #fff;
            border-color: #0F3B68;
            transform: translateY(-1px);
        }

        /* Active page */
        .pagination li.active span {
            background: #0F3B68;
            color: #fff;
            border-color: #0F3B68;
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

            /* =========================
       VISITOR CARD
    ========================= */
            .modern-table tr {
                background: #fff;
                border-radius: 16px;
                margin-bottom: 14px;
                padding: 14px;
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
                border-left: 4px solid #0d6efd;
                position: relative;
            }

            /* INDEX BADGE */
            .modern-table td:first-child {
                position: absolute;
                top: 10px;
                right: 12px;
                width: 24px;
                height: 24px;
                background: #0d6efd;
                color: #fff;
                font-size: 12px;
                font-weight: 700;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .modern-table td {
                display: block;
                border: none;
                padding: 0;
            }

            /* =========================
       VISITOR INFO (NAME + EMAIL)
    ========================= */
            .modern-table td:nth-child(2) strong {
                font-size: 15px;
                font-weight: 700;
                color: #222;
            }

            .modern-table td:nth-child(2) small {
                font-size: 12px;
                color: #777;
            }

            /* =========================
       COMPANY + PHONE + DATE
    ========================= */
            .modern-table td:nth-child(3),
            .modern-table td:nth-child(4),
            .modern-table td:nth-child(5) {
                font-size: 13px;
                color: #555;
                margin-top: 6px;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            /* =========================
       MEMBER BLOCK (reuse class)
    ========================= */
            .member-cell {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-top: 10px;
            }

            .avatar {
                width: 42px;
                height: 42px;
                border-radius: 50%;
                object-fit: cover;
            }

            .name {
                font-size: 14px;
                font-weight: 600;
            }

            /* =========================
       STATUS BADGE
    ========================= */
            .status {
                display: inline-block;
                margin-top: 10px;
                font-size: 11px;
                padding: 5px 10px;
                border-radius: 20px;
                font-weight: 600;
            }

            .status.completed {
                background: #d4edda;
                color: #155724;
            }

            .status.pending {
                background: #fff3cd;
                color: #856404;
            }

            .status.cancelled {
                background: #f8d7da;
                color: #721c24;
            }

            /* =========================
       ACTION BUTTONS
    ========================= */
            .modern-table td:last-child {
                margin-top: 12px;
                display: flex;
                justify-content: flex-end;
                gap: 6px;
            }

            .btn-sm {
                font-size: 12px;
                padding: 6px 10px;
                border-radius: 8px;
            }

            .btn-warning.btn-sm {
                background: #ffc107;
                border: none;
            }
        }
    </style>
    <style>
        .attendance-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;

            padding: 2px 10px;

            border-radius: 20px;

            font-size: 12px;
            font-weight: 600;

            white-space: nowrap;
        }


        /* PRESENT */

        .status-present {
            color: #198754;
            background: #e9f8ef;
            border: 1px solid #bce8ce;
        }


        /* ABSENT */

        .status-absent {
            color: #dc3545;
            background: #fff0f1;
            border: 1px solid #f5c2c7;
        }


        /* MEDICAL */

        .status-medical {
            color: #0d6efd;
            background: #edf4ff;
            border: 1px solid #b6d4fe;
        }


        /* LATE */

        .status-substitute {
            color: #d88900;
            background: #fff7e6;
            border: 1px solid #ffe08a;
        }


        /* UNKNOWN */

        .status-unknown {
            color: #6c757d;
            background: #f1f3f5;
            border: 1px solid #dee2e6;
        }
    </style>
    <style>
        .event-info-card {
            position: relative;
            overflow: hidden;
            border: 0;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 4px 18px rgba(8, 66, 107, 0.08);
            border-left: 5px solid #08426b;
        }

        .event-info-card::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 180px;
            height: 180px;
            background: linear-gradient(135deg,
                    rgba(8, 66, 107, 0.07),
                    transparent);
            border-radius: 0 0 0 100%;
            pointer-events: none;
        }

        .event-info-body {
            position: relative;
            padding: 22px 24px;
        }

        .event-title {
            margin: 0;
            color: #08426b;
            font-size: 21px;
            font-weight: 700;
            line-height: 1.3;
        }

        .event-subtitle {
            color: #7a8793;
            font-size: 13px;
            margin-top: 5px;
        }

        .event-details {

            border-top: 1px solid #edf1f4;
        }

        .event-detail {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 55px;
            padding: 8px 12px;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .event-detail:hover {
            background: #f6f9fb;
            transform: translateY(-1px);
        }

        .event-icon {
            width: 38px;
            height: 38px;
            min-width: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: rgba(8, 66, 107, 0.08);
            color: #08426b;
            font-size: 15px;
        }

        .event-detail-label {
            display: block;
            margin-bottom: 2px;
            color: #8a969f;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .event-detail-value {
            display: block;
            color: #26343d;
            font-size: 14px;
            font-weight: 600;
        }

        .member-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 20px;
            background: #eaf3f8;
            color: #08426b;
            font-size: 13px;
            font-weight: 700;
        }

        @media (max-width: 767px) {
            .event-info-body {
                padding: 18px;
            }

            .event-title {
                font-size: 18px;
            }

            .event-detail {
                padding: 8px 5px;
            }

            .event-details {
                margin-top: 15px;
                padding-top: 12px;
            }
        }

        .all-present-check {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border: 1px solid #d9e3ea;
            border-radius: 8px;
            background: #f8fafc;
            cursor: pointer;
            transition: .2s;
        }

        .all-present-check:hover {
            border-color: #198754;
            background: #f1fff7;
        }

        .all-present-check input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #198754;
        }

        .all-present-check span {
            font-size: 13px;
            font-weight: 600;
            color: #198754;
        }

        .all-present-check.saving {
            opacity: .6;
            pointer-events: none;
        }

        .attendance-table {
            width: 100%;
            font-size: 14px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .attendance-table thead th {
            background: #f7f9fb;
            color: #0a3d61;
            font-weight: 700;
            font-size: 13px;
            padding: 13px 15px;
            border-bottom: 1px solid #e8edf2;
            vertical-align: middle;
            white-space: nowrap;
        }

        .attendance-table tbody td {
            padding: 10px 15px;
            border-bottom: 1px solid #edf1f4;
            vertical-align: middle;
            background: #fff;
        }

        .attendance-table tbody tr {
            transition: background-color .2s ease;
        }

        .attendance-table tbody tr:hover td {
            background: #fafcff;
        }

        .attendance-table tbody tr:last-child td {
            border-bottom: 0;
        }


        /* =========================================================
       MEMBER INFORMATION
    ========================================================= */

        .member-info {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 180px;
        }

        .member-photo {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 50%;
            object-fit: cover;
            object-position: top center;
            border: 2px solid #eef2f5;
            background: #f5f7f9;
        }

        .member-name {
            font-weight: 600;
            color: #263238;
            line-height: 1.3;
        }

        .member-info small {
            display: block;
            margin-top: 2px;
            font-size: 11px;
            color: #7b8794 !important;
        }


        /* =========================================================
       EMAIL
    ========================================================= */

        .attendance-table td .text-muted {
            color: #6c757d !important;
            font-size: 13px;
        }


        /* =========================================================
       ATTENDANCE BUTTON CONTAINER
    ========================================================= */

        .attendance-buttons {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            white-space: nowrap;
        }


        /* =========================================================
       COMMON ATTENDANCE BUTTON
    ========================================================= */

        .attendance-btn {
            position: relative;

            width: 38px;
            height: 34px;
            min-width: 38px;

            padding: 0;

            border: 1px solid #dce3e8;
            border-radius: 7px;

            background: #fff;
            color: #59636e;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 3px;

            font-size: 12px;
            font-weight: 700;

            line-height: 1;

            cursor: pointer;

            outline: none;

            transition:
                background-color .18s ease,
                border-color .18s ease,
                color .18s ease,
                transform .18s ease,
                box-shadow .18s ease,
                opacity .18s ease;
        }

        .attendance-btn i {
            font-size: 11px;
            line-height: 1;
            color: inherit;
        }

        .attendance-btn span {
            font-size: 11px;
            line-height: 1;
            color: inherit;
        }

        .attendance-btn:hover {
            transform: translateY(-1px);
        }

        .attendance-btn:focus {
            outline: none;
            box-shadow: none;
        }

        .attendance-btn:active {
            transform: scale(.96);
        }


        /* =========================================================
       PRESENT - GREEN
    ========================================================= */

        .attendance-btn.present:hover,
        .attendance-btn.present.active {
            background-color: #198754 !important;
            border-color: #198754 !important;
            color: #fff !important;
        }

        .attendance-btn.present:hover i,
        .attendance-btn.present:hover span,
        .attendance-btn.present.active i,
        .attendance-btn.present.active span {
            color: #fff !important;
        }


        /* =========================================================
       ABSENT - RED
    ========================================================= */

        .attendance-btn.absent:hover,
        .attendance-btn.absent.active {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: #fff !important;
        }

        .attendance-btn.absent:hover i,
        .attendance-btn.absent:hover span,
        .attendance-btn.absent.active i,
        .attendance-btn.absent.active span {
            color: #fff !important;
        }


        /* =========================================================
       MEDICAL - BLUE
    ========================================================= */

        .attendance-btn.medical:hover,
        .attendance-btn.medical.active {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: #fff !important;
        }

        .attendance-btn.medical:hover i,
        .attendance-btn.medical:hover span,
        .attendance-btn.medical.active i,
        .attendance-btn.medical.active span {
            color: #fff !important;
        }


        /* =========================================================
       SUBSTITUTE - ORANGE
    ========================================================= */

        .attendance-btn.substitute:hover,
        .attendance-btn.substitute.active {
            background-color: #f59f00 !important;
            border-color: #f59f00 !important;
            color: #fff !important;
        }

        .attendance-btn.substitute:hover i,
        .attendance-btn.substitute:hover span,
        .attendance-btn.substitute.active i,
        .attendance-btn.substitute.active span {
            color: #fff !important;
        }


        /* =========================================================
       SAVING STATE
    ========================================================= */

        .attendance-btn.saving {
            opacity: .55 !important;
            pointer-events: none !important;
            cursor: wait !important;
            transform: none !important;
        }

        .attendance-btn.saving::after {
            content: "";
            position: absolute;

            width: 10px;
            height: 10px;

            border: 2px solid rgba(255, 255, 255, .45);
            border-top-color: #fff;

            border-radius: 50%;

            animation: attendanceSpin .7s linear infinite;
        }

        @keyframes attendanceSpin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }


        /* =========================================================
       SAVED ROW
    ========================================================= */

        .attendance-table tbody tr.attendance-saved td {
            background-color: rgba(25, 135, 84, .05);
        }


        /* =========================================================
       NOTE INPUT
    ========================================================= */

        .attendance-note {
            width: 100%;
            min-width: 180px;
            height: 34px;

            padding: 6px 10px;

            border: 1px solid #dce3e8;
            border-radius: 6px;

            background: #fff;

            color: #263238;

            font-size: 13px;

            outline: none;

            transition:
                border-color .18s ease,
                box-shadow .18s ease;
        }

        .attendance-note::placeholder {
            color: #9aa5af;
            opacity: 1;
        }

        .attendance-note:hover {
            border-color: #cbd5dc;
        }

        .attendance-note:focus {
            border-color: #0a3d61 !important;

            box-shadow:
                0 0 0 2px rgba(10, 61, 97, .08) !important;

            outline: none;
        }


        /* =========================================================
       CLOSED BADGE
    ========================================================= */

        .attendance-table .badge.bg-secondary {
            font-size: 11px;
            font-weight: 600;
            padding: 5px 9px;
            border-radius: 5px;
        }


        /* =========================================================
       MARK ALL PRESENT
    ========================================================= */

        #markAllPresent {
            cursor: pointer;
        }

        #markAllPresent:disabled {
            cursor: wait;
            opacity: .6;
        }


        /* =========================================================
       DESKTOP ALIGNMENT
    ========================================================= */

        .attendance-table th:first-child,
        .attendance-table td:first-child {
            text-align: center;
            width: 50px;
        }

        .attendance-table th:nth-child(4),
        .attendance-table td:nth-child(4) {
            text-align: center;
        }


        /* =========================================================
       MOBILE
    ========================================================= */

        @media (max-width: 767px) {

            .attendance-table {
                font-size: 13px;
            }

            .attendance-table thead {
                display: none;
            }

            .attendance-table,
            .attendance-table tbody,
            .attendance-table tr,
            .attendance-table td {
                display: block;
                width: 100%;
            }

            .attendance-table tbody tr {
                position: relative;

                padding: 12px;

                margin-bottom: 10px;

                border: 1px solid #e5e9ed;
                border-radius: 8px;

                background: #fff;
            }

            .attendance-table tbody td {
                border: 0 !important;

                padding: 5px 0 !important;

                background: transparent !important;
            }

            .attendance-table tbody tr:hover td {
                background: transparent !important;
            }

            .attendance-table td:first-child {
                display: none;
            }


            /* Member */

            .member-info {
                min-width: 0;
                width: 100%;
            }

            .member-photo {
                width: 40px;
                height: 40px;
                min-width: 40px;
            }

            .member-name {
                font-size: 14px;
            }


            /* Email */

            .attendance-table td:nth-child(3) {
                margin-top: 2px;
            }


            /* Attendance */

            .attendance-table td:nth-child(4) {
                text-align: left;
                margin-top: 8px;
            }

            .attendance-buttons {
                justify-content: flex-start;
                gap: 6px;
                margin-top: 4px;
            }


            /* Note */

            .attendance-note {
                width: 100%;
                min-width: 0;
                height: 36px;
                margin-top: 5px;
            }
        }


        /* =========================================================
       SMALL MOBILE
    ========================================================= */

        @media (max-width: 400px) {

            .attendance-btn {
                width: 36px;
                height: 33px;
                min-width: 36px;
            }

            .attendance-buttons {
                gap: 5px;
            }

            .member-photo {
                width: 38px;
                height: 38px;
                min-width: 38px;
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

                <div class="container mt-2">

                    <!-- EVENT DETAILS -->



                    <div class="card event-info-card mb-4">
                        <div class="event-info-body">

                            <!-- HEADER -->
                            <div class="d-flex align-items-start justify-content-between gap-3">

                                <div>
                                    <h4 class="event-title">
                                        <?= esc($event_title) ?>
                                    </h4>

                                    <div class="event-subtitle">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        Event Information
                                    </div>
                                </div>

                                <span class="member-badge">
                                    <i class="fas fa-users"></i>
                                    <?= esc($member_count) ?>
                                </span>

                            </div>

                            <!-- DETAILS -->
                            <div class="row event-details g-2">

                                <!-- CHAPTER -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="event-detail">
                                        <div class="event-icon">
                                            <i class="fas fa-building"></i>
                                        </div>

                                        <div>
                                            <span class="event-detail-label">Chapter</span>
                                            <span class="event-detail-value">
                                                <?= esc($chapter->chapter_name) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- DATE -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="event-detail">
                                        <div class="event-icon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>

                                        <div>
                                            <span class="event-detail-label">Event Date</span>
                                            <span class="event-detail-value">
                                                <?= date('d M Y', strtotime($event_date)) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- LEADER -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="event-detail">
                                        <div class="event-icon">
                                            <i class="fas fa-user-tie"></i>
                                        </div>

                                        <div>
                                            <span class="event-detail-label">Leader</span>
                                            <span class="event-detail-value">
                                                <?= esc($leader_name) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- MEMBERS -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="event-detail">
                                        <div class="event-icon">
                                            <i class="fas fa-users"></i>
                                        </div>

                                        <div>
                                            <span class="event-detail-label">Members</span>
                                            <span class="event-detail-value">
                                                <?= esc($member_count) ?> Members
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>



                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!$allowMark): ?>
                        <div class="alert alert-danger">
                            Attendance can be marked only on event date
                            (<?= date('d M Y', strtotime($event_date)) ?>)
                        </div>
                    <?php endif; ?>
                    <?php
                    $db = \Config\Database::connect();

                    $rowData = $db->table('users u')
                        ->select('u.*')
                        ->where(
                            'u.user_code',
                            session()->get('user_code')
                        )
                        ->get()
                        ->getRowArray();

                    /*
|--------------------------------------------------------------------------
| PRESIDENT / VICE PRESIDENT
|--------------------------------------------------------------------------
*/

                    $isAttendanceUser = (
                        ($rowData['user_code'] ?? '') === session()->get('user_code')
                        &&
                        in_array(
                            $rowData['member_role'] ?? '',
                            ['President', 'Vice President'],
                            true
                        )
                    );

                    if ($isAttendanceUser):
                    ?>

                        <div class="card border-0 shadow-sm">

                            <!-- =========================================================
         HEADER
    ========================================================== -->

                            <div class="card-header bg-white border-0 py-3">

                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                                    <!-- TITLE -->

                                    <div>

                                        <h5
                                            class="mb-0 fw-bold"
                                            style="color:#0a3d61;">
                                            <i class="fa fa-calendar-check me-2"></i>
                                            Mark Attendance
                                        </h5>

                                        <small class="text-muted">

                                            <?php if (!empty($isMeetingClosed)): ?>

                                                <span class="text-danger fw-bold">

                                                    <i class="fa fa-lock me-1"></i>

                                                    Meeting Closed — Attendance Locked

                                                </span>

                                            <?php elseif (!empty($isAttendanceFrozen)): ?>

                                                <span class="text-danger fw-bold">

                                                    <i class="fa fa-lock me-1"></i>

                                                    Attendance Frozen

                                                </span>

                                            <?php elseif (!$allowMark): ?>

                                                <span class="text-warning fw-bold">

                                                    <i class="fa fa-calendar-times me-1"></i>

                                                    Attendance can be marked only on event date

                                                </span>

                                            <?php else: ?>

                                                Click a status to mark attendance

                                            <?php endif; ?>

                                        </small>

                                    </div>



                                    <div class="d-flex align-items-center gap-2">

                                        <?php if ($allowMark && empty($isAttendanceFrozen)): ?>

                                            <!-- MARK ALL -->

                                            <label class="all-present-check mb-0">

                                                <input
                                                    type="checkbox"
                                                    id="markAllPresent">

                                                <span>
                                                    <i class="fa fa-check-double"></i>
                                                    Mark All Present
                                                </span>

                                            </label>


                                            <!-- FREEZE -->

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                id="freezeAttendanceBtn"
                                                data-event-code="<?= esc($event_code) ?>">

                                                <i class="fa fa-lock me-1"></i>
                                                Freeze Attendance

                                            </button>


                                        <?php elseif (!empty($isAttendanceFrozen)): ?>

                                            <!-- UNFREEZE -->

                                            <button
                                                type="button"
                                                class="btn btn-success btn-sm"
                                                id="unfreezeAttendanceBtn"
                                                data-event-code="<?= esc($event_code) ?>">

                                                <i class="fa fa-unlock me-1"></i>
                                                Unfreeze Attendance

                                            </button>

                                        <?php elseif (!empty($isMeetingClosed)): ?>

                                            <!-- MEETING CLOSED -->

                                            <span class="badge bg-danger px-3 py-2">

                                                <i class="fa fa-lock me-1"></i>
                                                Meeting Closed

                                            </span>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </div>


                            <!-- =========================================================
         TABLE
    ========================================================== -->

                            <div
                                class="table-responsive"
                                style="height:400px;overflow-y:scroll;">

                                <table class="table attendance-table align-middle mb-0">

                                    <thead>

                                        <tr>

                                            <th>#</th>

                                            <th>Member</th>

                                            <th>Email</th>

                                            <th class="text-center">
                                                Attendance
                                            </th>

                                            <th>
                                                Note
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        <?php $i = 1; ?>

                                        <?php foreach ($members as $m): ?>

                                            <?php

                                            /*
                    |--------------------------------------------------------------------------
                    | PROFILE PHOTO
                    |--------------------------------------------------------------------------
                    */

                                            $photo = (
                                                !empty($m->profile_photo)
                                                &&
                                                file_exists(
                                                    FCPATH .
                                                        'uploads/profile/' .
                                                        $m->profile_photo
                                                )
                                            )
                                                ? base_url(
                                                    'uploads/profile/' .
                                                        $m->profile_photo
                                                )
                                                : base_url(
                                                    'uploads/profile/default.png'
                                                );


                                            /*
                    |--------------------------------------------------------------------------
                    | ATTENDANCE STATUS
                    |--------------------------------------------------------------------------
                    */

                                            $status = strtolower(
                                                trim(
                                                    $m->attendance_status ?? ''
                                                )
                                            );


                                            /*
                    |--------------------------------------------------------------------------
                    | DEFAULT PRESENT
                    |--------------------------------------------------------------------------
                    */

                                            if ($status === '') {

                                                $status = 'present';
                                            }


                                            /*
                    |--------------------------------------------------------------------------
                    | VALID STATUSES
                    |--------------------------------------------------------------------------
                    */

                                            $allowedStatuses = [
                                                'present',
                                                'absent',
                                                'medical',
                                                'substitute'
                                            ];


                                            if (
                                                !in_array(
                                                    $status,
                                                    $allowedStatuses,
                                                    true
                                                )
                                            ) {

                                                $status = 'present';
                                            }


                                            $attendanceNote =
                                                $m->attendance_note ?? '';

                                            ?>

                                            <tr
                                                data-user="<?= esc($m->user_code) ?>"
                                                data-status="<?= esc($status) ?>">

                                                <!-- NUMBER -->

                                                <td>
                                                    <?= $i++; ?>
                                                </td>


                                                <!-- MEMBER -->

                                                <td>

                                                    <div class="member-info">

                                                        <img
                                                            src="<?= esc($photo) ?>"
                                                            class="member-photo"
                                                            alt="<?= esc($m->name) ?>">

                                                        <div>

                                                            <div class="member-name">

                                                                <?= esc($m->name) ?>

                                                            </div>

                                                            <small class="text-muted">

                                                                <?= esc($m->user_code) ?>

                                                            </small>

                                                        </div>

                                                    </div>

                                                </td>


                                                <!-- EMAIL -->

                                                <td>

                                                    <span class="text-muted">

                                                        <?= esc($m->email) ?>

                                                    </span>

                                                </td>


                                                <!-- =================================================
                             ATTENDANCE
                        ================================================== -->

                                                <td class="text-center">

                                                    <?php if ($allowMark): ?>

                                                        <div class="attendance-buttons">

                                                            <!-- PRESENT -->

                                                            <button
                                                                type="button"
                                                                class="attendance-btn present <?= $status === 'present' ? 'active' : '' ?>"
                                                                data-status="present"
                                                                title="Present">

                                                                <i class="fa fa-check"></i>

                                                                <span>P</span>

                                                            </button>


                                                            <!-- ABSENT -->

                                                            <button
                                                                type="button"
                                                                class="attendance-btn absent <?= $status === 'absent' ? 'active' : '' ?>"
                                                                data-status="absent"
                                                                title="Absent">

                                                                <i class="fa fa-xmark"></i>

                                                                <span>A</span>

                                                            </button>


                                                            <!-- MEDICAL -->

                                                            <button
                                                                type="button"
                                                                class="attendance-btn medical <?= $status === 'medical' ? 'active' : '' ?>"
                                                                data-status="medical"
                                                                title="Medical">

                                                                <i class="fa fa-notes-medical"></i>

                                                                <span>M</span>

                                                            </button>


                                                            <!-- SUBSTITUTE -->

                                                            <button
                                                                type="button"
                                                                class="attendance-btn substitute <?= $status === 'substitute' ? 'active' : '' ?>"
                                                                data-status="substitute"
                                                                title="Substitute">

                                                                <i class="fa fa-user-check"></i>

                                                                <span>S</span>

                                                            </button>

                                                        </div>

                                                    <?php else: ?>

                                                        <!-- CLOSED -->

                                                        <span class="badge bg-secondary">

                                                            <i class="fa fa-lock me-1"></i>

                                                            Closed

                                                        </span>

                                                    <?php endif; ?>

                                                </td>


                                                <!-- =================================================
                             NOTE
                        ================================================== -->

                                                <td>

                                                    <?php if ($allowMark): ?>

                                                        <input
                                                            type="text"
                                                            class="form-control form-control-sm attendance-note"
                                                            placeholder="Optional note"
                                                            value="<?= esc($attendanceNote) ?>">

                                                    <?php else: ?>

                                                        <?php if ($attendanceNote !== ''): ?>

                                                            <span class="text-muted">

                                                                <?= esc($attendanceNote) ?>

                                                            </span>

                                                        <?php else: ?>

                                                            <span class="text-muted">
                                                                -
                                                            </span>

                                                        <?php endif; ?>

                                                    <?php endif; ?>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    <?php endif; ?>



                    <div style="height: 400px;overflow-y:scroll;">

                        <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
                            <h4 class="mb-0">Attendance Details</h4>

                            <div>
                                <a href="<?= base_url('member/meetings/exportAttendanceExcel/' . $event_code) ?>"
                                    class="btn btn-success btn-sm">
                                    <i class="fa fa-file-excel"></i> Export Excel
                                </a>

                                <a href="<?= base_url('member/meetings/exportAttendancePdf/' . $event_code) ?>"
                                    class="btn btn-danger btn-sm"
                                    target="_blank">
                                    <i class="fa fa-file-pdf"></i> Export PDF
                                </a>
                            </div>
                        </div>

                        <table class="table table-bordered">
                            <thead style="background:#198754;color:#fff;">
                                <tr>
                                    <th>#</th>
                                    <th>Member</th>
                                    <th>Status</th>
                                    <th>Note</th>
                                    <th>Updated</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if (!empty($attendance)): ?>

                                    <?php $i = 1; ?>

                                    <?php foreach ($attendance as $a): ?>

                                        <?php
                                        $status = strtolower(trim($a->status ?? ''));

                                        switch ($status) {

                                            case 'present':
                                                $statusClass = 'status-present';
                                                $statusIcon  = 'fa-check-circle';
                                                $statusText  = 'Present';
                                                break;

                                            case 'absent':
                                                $statusClass = 'status-absent';
                                                $statusIcon  = 'fa-times-circle';
                                                $statusText  = 'Absent';
                                                break;

                                            case 'medical':
                                                $statusClass = 'status-medical';
                                                $statusIcon  = 'fa-notes-medical';
                                                $statusText  = 'Medical';
                                                break;

                                            case 'substitute':
                                                $statusClass = 'status-substitute';
                                                $statusIcon  = 'fa-clock';
                                                $statusText  = 'Substitute';
                                                break;

                                            default:
                                                $statusClass = 'status-unknown';
                                                $statusIcon  = 'fa-question-circle';
                                                $statusText  = ucfirst($status);
                                                break;
                                        }
                                        ?>

                                        <tr>

                                            <!-- NUMBER -->
                                            <td><?= $i++; ?></td>

                                            <!-- NAME -->
                                            <td>
                                                <strong><?= esc($a->name) ?></strong>
                                            </td>

                                            <!-- STATUS -->
                                            <td>
                                                <span class="attendance-status <?= $statusClass ?>">
                                                    <i class="fa <?= $statusIcon ?>"></i>
                                                    <?= esc($statusText) ?>
                                                </span>
                                            </td>

                                            <!-- NOTE -->
                                            <td>
                                                <?php if (!empty($a->note)): ?>
                                                    <?= esc($a->note) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">—</span>
                                                <?php endif; ?>
                                            </td>

                                            <!-- DATE -->
                                            <td>
                                                <?= !empty($a->updated_on)
                                                    ? date('d M Y h:i A', strtotime($a->updated_on))
                                                    : '—'
                                                ?>
                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">

                                            <i class="fa fa-calendar-xmark me-1"></i>

                                            No Attendance Marked

                                        </td>
                                    </tr>

                                <?php endif; ?>

                            </tbody>


                        </table>
                    </div>
                    <div class="p-2 mt-2">
                        <h4 class="mb-0">Visitor List</h4>
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
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($visitor)): ?>
                                        <?php $i = 1; ?>
                                        <?php foreach ($visitor as $row): ?>
                                            <tr>
                                                <td data-label="ID"><?= $i ?></td>
                                                <td class="visitor-info" data-label="Visitor">
                                                    <div>
                                                        <div class="name"><?= esc($row->visitor_name) ?></div>
                                                        <small><?= esc($row->email) ?></small>
                                                    </div>
                                                </td>
                                                <td data-label="Company">
                                                    <?= esc($row->company) ?>
                                                </td>
                                                <td data-label="Phone">
                                                    <?= esc($row->phone) ?>
                                                </td>
                                                <td data-label="Visited Date">
                                                    <?= !empty($row->date_visited) ? date('d M Y', strtotime($row->date_visited)) : '-' ?>
                                                </td>
                                                <td class="member-info" data-label="Added By">
                                                    <div class="member-cell">
                                                        <img src="<?= base_url('uploads/profile/' . ($row->member_profile ?? 'empty.png')) ?>"
                                                            class="avatar"
                                                            style="object-fit:cover;object-position:top center;">
                                                        <div>
                                                            <div class="name"><?= esc($row->member_name) ?></div>
                                                            <small><?= esc($row->member_email) ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td data-label="Status">
                                                    <?php
                                                    $statusClass = match ($row->status) {
                                                        'visited'   => 'status completed',
                                                        'pending'   => 'status pending',
                                                        'cancelled' => 'status cancelled',
                                                        default     => 'status pending'
                                                    };
                                                    ?>
                                                    <span class="<?= $statusClass ?>"
                                                        onclick="status_v_a('<?= $row->visitors_code ?>');">
                                                        <?= ucfirst($row->status) ?>
                                                    </span>
                                                </td>
                                                <!-- <td class="actions" data-label="Action">
                                                    <a class="btn btn-danger btn-sm"
                                                        onclick="delete_v('<?= $row->visitors_code ?>');">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td> -->
                                            </tr>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>

                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center p-4">
                                                No Visitors Found
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End Page-content -->
                <?php echo view('include/member/footer.php') ?>
            </div>
            <!-- end main content-->
        </div>
        <div class="rightbar-overlay"></div>
        <!-- JAVASCRIPT --> <?php echo view('include/member/popup/visitor.php') ?>
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

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            const attendanceUrl =
                "<?= base_url('member/meetings/ajax_save_attendance') ?>";

            const eventCode =
                "<?= esc($event_code) ?>";

            const chapterCode =
                "<?= esc($chapter->chapter_code) ?>";


            /*
            |--------------------------------------------------------------------------
            | NOTE SAVE TIMERS
            |--------------------------------------------------------------------------
            */

            let noteTimers = {};


            /*
            |--------------------------------------------------------------------------
            | SAVE ATTENDANCE
            |--------------------------------------------------------------------------
            */

            function saveAttendance(row, status, note, button = null) {

                const userCode = row.attr('data-user');

                status = String(status || '')
                    .trim()
                    .toLowerCase();

                note = note || '';

                /*
                |--------------------------------------------------------------------------
                | Validate
                |--------------------------------------------------------------------------
                */

                if (!userCode || !status) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Saving state
                |--------------------------------------------------------------------------
                */

                if (button) {
                    button.addClass('saving');
                }


                /*
                |--------------------------------------------------------------------------
                | AJAX
                |--------------------------------------------------------------------------
                */

                $.ajax({

                    url: attendanceUrl,

                    type: 'POST',

                    dataType: 'json',

                    data: {
                        event_code: eventCode,
                        chapter_code: chapterCode,
                        user_code: userCode,
                        status: status,
                        note: note
                    },


                    /*
                    |--------------------------------------------------------------------------
                    | SUCCESS
                    |--------------------------------------------------------------------------
                    */

                    success: function(res) {

                        if (
                            res &&
                            res.status === 'success'
                        ) {

                            /*
                            |--------------------------------------------------------------------------
                            | Make selected status active
                            |--------------------------------------------------------------------------
                            */

                            row.find('.attendance-btn')
                                .removeClass('active');

                            const selectedButton = row.find(
                                '.attendance-btn[data-status="' +
                                status +
                                '"]'
                            );

                            selectedButton.addClass('active');


                            /*
                            |--------------------------------------------------------------------------
                            | Remove saving
                            |--------------------------------------------------------------------------
                            */

                            if (button) {

                                button.removeClass('saving');

                                button.css(
                                    'transform',
                                    'scale(1.08)'
                                );

                                setTimeout(function() {

                                    button.css(
                                        'transform',
                                        ''
                                    );

                                }, 150);
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Saved animation
                            |--------------------------------------------------------------------------
                            */

                            row.addClass(
                                'attendance-saved'
                            );

                            setTimeout(function() {

                                row.removeClass(
                                    'attendance-saved'
                                );

                            }, 700);


                        } else {

                            /*
                            |--------------------------------------------------------------------------
                            | Save failed
                            |--------------------------------------------------------------------------
                            */

                            if (button) {
                                button.removeClass('saving');
                            }

                            console.log(
                                res && res.message ?
                                res.message :
                                'Unable to save attendance'
                            );
                        }
                    },


                    /*
                    |--------------------------------------------------------------------------
                    | ERROR
                    |--------------------------------------------------------------------------
                    */

                    error: function(xhr) {

                        console.log(
                            'Attendance AJAX Error:',
                            xhr.responseText
                        );

                        if (button) {
                            button.removeClass('saving');
                        }
                    }

                });
            }


            /*
            |--------------------------------------------------------------------------
            | ATTENDANCE BUTTON CLICK
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '.attendance-btn',
                function() {

                    const btn = $(this);

                    const row = btn.closest('tr');

                    /*
                    |--------------------------------------------------------------------------
                    | Get status
                    |--------------------------------------------------------------------------
                    */

                    const status = String(
                            btn.attr('data-status') || ''
                        )
                        .trim()
                        .toLowerCase();


                    /*
                    |--------------------------------------------------------------------------
                    | Get note
                    |--------------------------------------------------------------------------
                    */

                    const note =
                        row.find('.attendance-note').val() || '';


                    /*
                    |--------------------------------------------------------------------------
                    | Validate
                    |--------------------------------------------------------------------------
                    */

                    if (!status) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Don't allow double click while saving
                    |--------------------------------------------------------------------------
                    */

                    if (btn.hasClass('saving')) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Remove active from all buttons
                    |--------------------------------------------------------------------------
                    */

                    row.find('.attendance-btn')
                        .removeClass('active');


                    /*
                    |--------------------------------------------------------------------------
                    | Add active to selected button
                    |--------------------------------------------------------------------------
                    */

                    btn.addClass('active');


                    /*
                    |--------------------------------------------------------------------------
                    | Save attendance
                    |--------------------------------------------------------------------------
                    */

                    saveAttendance(
                        row,
                        status,
                        note,
                        btn
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | NOTE AUTO SAVE
            |--------------------------------------------------------------------------
            |
            | Saves attendance note after user stops typing for 700ms.
            |
            */

            $(document).on(
                'input',
                '.attendance-note',
                function() {

                    const input = $(this);

                    const row = input.closest('tr');

                    const userCode =
                        row.attr('data-user');


                    /*
                    |--------------------------------------------------------------------------
                    | Validate user
                    |--------------------------------------------------------------------------
                    */

                    if (!userCode) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Get currently selected status
                    |--------------------------------------------------------------------------
                    */

                    let status = row
                        .find('.attendance-btn.active')
                        .attr('data-status');


                    if (!status) {
                        status = 'present';
                    }


                    status = String(status)
                        .trim()
                        .toLowerCase();




                    clearTimeout(
                        noteTimers[userCode]
                    );




                    noteTimers[userCode] = setTimeout(
                        function() {

                            const note =
                                input.val() || '';


                            saveAttendance(
                                row,
                                status,
                                note
                            );

                        },
                        700
                    );

                }
            );



            $(document).on(
                'change',
                '#markAllPresent',
                function() {

                    const checkbox = $(this);


                    if (!checkbox.is(':checked')) {
                        return;
                    }


                    if (!confirm(
                            'Mark all members as Present?'
                        )) {

                        checkbox.prop(
                            'checked',
                            false
                        );

                        return;
                    }


                    checkbox.prop(
                        'disabled',
                        true
                    );


                    const rows =
                        $('tbody tr[data-user]');


                    let completed = 0;

                    let failed = false;


                    if (rows.length === 0) {

                        checkbox.prop(
                            'disabled',
                            false
                        );

                        checkbox.prop(
                            'checked',
                            false
                        );

                        return;
                    }


                    rows.each(function() {

                        const row = $(this);

                        const userCode =
                            row.attr('data-user');


                        const note =
                            row.find(
                                '.attendance-note'
                            ).val() || '';
                        const presentBtn =
                            row.find(
                                '.attendance-btn[data-status="present"]'
                            );

                        row.find('.attendance-btn')
                            .removeClass('active');

                        presentBtn.addClass('active');

                        $.ajax({

                            url: attendanceUrl,

                            type: 'POST',

                            dataType: 'json',

                            data: {

                                event_code: eventCode,

                                chapter_code: chapterCode,

                                user_code: userCode,

                                status: 'present',

                                note: note
                            },

                            success: function(res) {

                                if (
                                    !res ||
                                    res.status !== 'success'
                                ) {

                                    failed = true;
                                }

                            },


                            error: function(xhr) {

                                console.log(
                                    'Mark All Present Error:',
                                    xhr.responseText
                                );

                                failed = true;
                            },


                            complete: function() {

                                completed++;

                                if (
                                    completed ===
                                    rows.length
                                ) {

                                    checkbox.prop(
                                        'disabled',
                                        false
                                    );


                                    if (failed) {

                                        checkbox.prop(
                                            'checked',
                                            false
                                        );

                                        alert(
                                            'Some attendance records could not be saved.'
                                        );

                                    } else {

                                        checkbox.prop(
                                            'checked',
                                            true
                                        );

                                        alert(
                                            'All members marked Present.'
                                        );
                                    }
                                }
                            }

                        });

                    });

                }
            );

            $(document).ready(function() {

                $('tbody tr[data-user]').each(
                    function() {

                        const row = $(this);

                        const activeButton =
                            row.find(
                                '.attendance-btn.active'
                            );


                        if (
                            activeButton.length === 0
                        ) {

                            row.find(
                                '.attendance-btn[data-status="present"]'
                            ).addClass('active');
                        }

                    }
                );

            });

            $(document).on(
                'click',
                '.attendance-btn.substitute',
                function() {

                    console.log(
                        'Substitute selected:',
                        $(this).attr('data-status')
                    );

                }
            );
        </script>
        <script>
            $(document).on('click', '#freezeAttendanceBtn', function() {

                const button = $(this);

                const eventCode = button.data('event-code');

                if (!eventCode) {
                    alert('Event code missing.');
                    return;
                }

                const confirmed = confirm(
                    'Are you sure you want to FREEZE attendance?\n\n' +
                    'After freezing:\n' +
                    '• Attendance cannot be edited.\n' +
                    '• Meeting will be marked Completed.\n' +
                    '• Absent members will receive an email notification.\n\n' +
                    'This action cannot be undone.'
                );

                if (!confirmed) {
                    return;
                }

                button.prop('disabled', true);

                button.html(
                    '<i class="fa fa-spinner fa-spin me-1"></i> Freezing...'
                );

                $.ajax({

                    url: "<?= base_url('member/meetings/freeze_attendance') ?>",

                    type: "POST",

                    dataType: "json",

                    data: {
                        event_code: eventCode
                    },

                    success: function(res) {

                        if (res && res.status === 'success') {

                            alert(res.message);

                            /*
                            |--------------------------------------------------------------------------
                            | Reload page
                            |--------------------------------------------------------------------------
                            */

                            location.reload();

                        } else {

                            alert(
                                res && res.message ?
                                res.message :
                                'Unable to freeze attendance.'
                            );

                            button.prop('disabled', false);

                            button.html(
                                '<i class="fa fa-lock me-1"></i> Freeze Attendance'
                            );
                        }
                    },

                    error: function(xhr) {

                        console.log(
                            'Freeze Attendance Error:',
                            xhr.responseText
                        );

                        alert(
                            'Unable to freeze attendance. Please try again.'
                        );

                        button.prop('disabled', false);

                        button.html(
                            '<i class="fa fa-lock me-1"></i> Freeze Attendance'
                        );
                    }

                });

            });
        </script>
        <script>
            $(document).on('click', '#unfreezeAttendanceBtn', function() {

                const button = $(this);
                const eventCode = button.data('event-code');

                if (!eventCode) {
                    alert('Event code is missing.');
                    return;
                }

                if (!confirm(
                        'Are you sure you want to unfreeze attendance for this meeting?\n\n' +
                        'Attendance will become editable again.'
                    )) {
                    return;
                }

                button.prop('disabled', true);

                $.ajax({
                    url: "<?= base_url('member/meetings/unfreeze_attendance') ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        event_code: eventCode
                    },

                    success: function(response) {

                        if (response.status === 'success') {

                            alert(response.message);

                            // Reload only this event/attendance page
                            location.reload();

                        } else {

                            alert(response.message || 'Unable to unfreeze attendance.');

                            button.prop('disabled', false);
                        }
                    },

                    error: function(xhr) {

                        console.log(xhr.responseText);

                        alert('Something went wrong while unfreezing attendance.');

                        button.prop('disabled', false);
                    }
                });

            });
        </script>
</body>

</html>