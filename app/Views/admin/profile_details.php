<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* ======= BASE STYLING ======= */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 1rem 1.25rem;
        }

        /* ======= PROFILE BANNER ======= */
        .card.bg-primary {
            background-color: #0a3d61 !important;
            /* theme red */
            color: #fff !important;
            text-align: center;
            padding: 2rem 1rem;
        }

        .card.bg-primary img {
            border: 4px solid #fff;
        }

        /* ======= BADGES ======= */
        .badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.35rem 0.7rem;
            border-radius: 20px;
        }

        .badge.bg-warning {
            background-color: #f9b309;
            /* gold accent */
            color: #212529;
        }

        .badge.bg-light {
            background-color: #e9ecef;
            color: #212529;
        }

        .badge.bg-dark {
            background-color: #343a40;
            color: #fff;
        }

        /* ======= STAT CARDS ======= */
        .card.text-center {
            padding: 1rem;
            background-color: #fff;
        }

        .card.text-center h4 {
            font-size: 1.6rem;
            margin-top: 0.5rem;
            color: #0a3d61;
            /* theme red for numbers */
        }

        .card.text-center h6 {
            font-weight: 500;
            color: #6c757d;
        }

        /* ======= ACTIVITY BREAKDOWN ======= */
        .bg-light {
            background-color: #f1f3f5 !important;
            padding: 1rem;
            border-radius: 8px;
        }

        .activity-breakdown small {
            display: block;
            font-size: 0.75rem;
            color: #6c757d;
        }

        .activity-breakdown h6 {
            margin-top: 0.25rem;
            font-weight: 600;
            color: #0a3d61;
            /* highlight red */
        }

        /* ======= GIVEN / RECEIVED LIST ======= */
        .mitb-list .card,
        .given-list .card {
            background-color: #fff;
            padding: 0.75rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .mitb-list .card:hover,
        .given-list .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #f9b309;
            /* gold highlight on hover */
        }

        /* ======= EMPTY STATE ======= */
        .empty,
        .no-records {
            text-align: center;
            padding: 2rem;
            color: #adb5bd;
            font-weight: 500;
        }

        /* ======= RESPONSIVE ======= */
        @media (max-width: 768px) {
            .card.text-center h4 {
                font-size: 1.3rem;
            }

            .card.bg-primary {
                padding: 1.5rem 1rem;
            }

            .card-body {
                padding: 0.75rem 1rem;
            }
        }

        .profile-banner {
            background: linear-gradient(135deg, #0a3d61, #0a3d61);
            padding: 20px 25px;
            /* ↓ reduced from 40px */
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        }

        /* PROFILE IMAGE */
        .profile-img-wrapper {
            position: relative;
        }

        .profile-img {
            width: 95px;
            /* ↓ reduced */
            height: 95px;
            border-radius: 12px;
            object-fit: cover;
            border: 3px solid #fff;
        }

        /* CAMERA ICON */
        .camera-icon {
            position: absolute;
            bottom: 3px;
            right: 3px;
            background: #fff;
            width: 28px;
            height: 28px;
            font-size: 12px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0a3d61;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
        }

        /* USER TEXT AREA */
        .profile-banner h2 {
            font-size: 20px;
            margin-bottom: 4px !important;
        }

        .profile-banner .mb-2 {
            margin-bottom: 6px !important;
            font-size: 14px;
        }

        /* BADGES */
        .badge-pill,
        .badge-pill-dark {
            padding: 4px 12px;
            font-size: 12px;
            border-radius: 20px;
        }

        /* SAVE BUTTON */
        .save-btn {
            background: #fff;
            color: #0a3d61;
            padding: 8px 18px;
            /* ↓ reduced */
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
        }

        /* STATS BOX */
        .stats-box {
            background: #fff;
            border-radius: 0 0 16px 16px;
            padding: 15px;
            /* ↓ reduced */
            box-shadow: 0 4px 10px rgba(0, 0, 0, .05);
            margin-top: -6px;
        }

        .stats-box h6 {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .stats-box h2 {
            font-size: 20px;
            margin: 0;
        }



        /* ===== Cards ===== */
        .profile-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .05);
        }

        /* ===== Card Header ===== */
        .profile-header {
            background: #fff;
            border-bottom: 1px solid #f1f1f1;
            padding: 14px 20px;
            font-weight: 600;
            color: #0a3d61;
        }

        /* ===== Inputs ===== */
        .form-control,
        .form-select {
            border-radius: 10px;
            height: 44px;
            font-size: 14px;
            border: 1px solid #e5e7eb;
        }

        textarea.form-control {
            height: auto;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0a3d61;
            box-shadow: 0 0 0 .15rem rgba(225, 29, 72, .15);
        }

        /* ===== Labels ===== */
        .form-label {
            font-size: 13px;
            margin-bottom: 6px;
        }

        /* ===== Sidebar ===== */
        .profile-sidebar {
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .05);
        }

        /* ===== Sidebar Tabs ===== */
        .profile-sidebar .nav-link {
            border-radius: 10px;
            color: #444;
            font-size: 14px;
            padding: 12px 14px;
            transition: .2s;
        }

        .profile-sidebar .nav-link:hover {
            background: #fff1f2;
            color: #0a3d61;
        }

        .profile-sidebar .nav-link.active {
            background: #0a3d61;
            color: #fff;
        }

        /* ===== Section spacing ===== */
        .profile-section .row>div {
            margin-bottom: 10px;
        }

        /* ===== Badge ===== */
        .badge {
            font-size: 12px;
        }

        /* Sticky sidebar */
        .sticky-card {
            position: sticky;
            top: 20px;
        }

        /* reduce card spacing */
        .card {
            margin-bottom: 14px !important;
        }

        /* reduce card body padding */
        .card-body {
            padding: 18px !important;
        }

        /* compact header */
        .card-header {
            padding: 10px 18px !important;
        }

        /* reduce row spacing */
        .row {
            --bs-gutter-y: .4rem;
        }

        /* form label smaller */
        .form-label {
            margin-bottom: 4px;
            font-size: 13px;
        }

        /* compact inputs */
        .form-control,
        .form-select {
            height: 38px;
            padding: 6px 10px;
            font-size: 14px;
        }

        textarea.form-control {
            min-height: 70px;
        }

        /* sidebar buttons compact */
        .nav-pills .nav-link {
            padding: 9px 12px !important;
            font-size: 14px;
        }

        /* remove extra tab gap */
        .tab-pane {
            margin-top: 0;
        }

        /* smaller section title */
        .card-header h5 {
            font-size: 16px;
        }

        /* reduce column spacing */
        .mb-3 {
            margin-bottom: .6rem !important;
        }

        /* reduce large spacing */
        .p-4 {
            padding: 1rem !important;
        }

        /* sidebar card padding */
        .card-body.p-2 {
            padding: .6rem !important;
        }

        /* banner spacing */
        .profile-banner {
            padding: 18px 22px;
        }

        /* profile image */
        .profile-img {
            width: 85px;
            height: 85px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
        }

        /* camera icon position */
        .camera-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #0a3d61;
            color: #fff;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
        }

        /* save button alignment */
        .save-btn {
            white-space: nowrap;
            padding: 8px 16px;
        }

        /* stats box */
        .stats-box {
            background: #fff;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            margin-top: -6px;
        }

        /* stat items */
        .stat-item {
            padding: 14px 10px;
        }

        .stat-item h6 {
            font-size: 12px;
            color: #777;
            margin-bottom: 3px;
        }

        .stat-item h3 {
            font-weight: 700;
        }

        /* mobile fix */
        @media(max-width:768px) {

            .profile-banner .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 12px;
            }

            .save-btn {
                width: 100%;
                text-align: center;
            }
        }

        /* ===== WRAPPER ===== */
        .connect-wrapper {
            background: #f4f6f9;
            padding: 15px 18px;
            /* reduced space */
        }

        /* ===== HEADER ===== */
        .connect-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            /* reduced */
        }

        .title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .subtitle {
            font-size: 13px;
            color: #7b8794;
            margin: 0;
        }

        /* ===== MAIN TABS ===== */
        .connect-tabs {
            display: flex;
            background: #e6e8eb;
            border-radius: 10px;
            padding: 4px;
            gap: 4px;
            margin-bottom: 12px;
        }

        .tab-btn {
            flex: 1;
            border: none;
            padding: 8px 10px;
            /* reduced */
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            background: transparent;
            transition: .25s;
        }

        .tab-btn.active {
            background: #0a3d61;
            color: #fff;
        }

        /* ===== CONTENT AREA ===== */
        .connect-content {
            margin-top: 5px;
        }

        /* ===== CARD ===== */
        .content-card {
            background: #fff;
            border-radius: 12px;
            padding: 18px;
            /* reduced */
            box-shadow: 0 3px 10px rgba(0, 0, 0, .06);
        }

        /* ===== FILTER TABS ===== */
        .filter-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .filter-btn {
            border: none;
            background: #ece6dd;
            padding: 6px 14px;
            /* smaller */
            border-radius: 7px;
            font-size: 13px;
            transition: .2s;
        }

        .filter-btn.active {
            background: #0a3d61;
            color: #fff;
        }

        /* ===== TAB CONTENT VISIBILITY ===== */
        .tab-content-box {
            display: none;
        }

        .tab-content-box.active {
            display: block;
        }

        .connect-modal {
            font-size: 14px;
        }

        .member-source {
            display: flex;
            gap: 10px;
        }

        .radio-card {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
            text-align: center;
            transition: .25s;
        }

        .radio-card input {
            display: none;
        }

        .radio-card.active {
            background: #08426b;
            color: #fff;
            border-color: #08426b;
        }

        .radio-card:hover {
            transform: translateY(-2px);
        }




        /* refral card */
        :root {
            --theme: #0a3d61;
            /* CHANGE YOUR THEME COLOR HERE */
        }

        /* CARD */
        .referral-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            border-radius: 14px;
            padding: 18px 22px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            transition: .25s ease;
            gap: 20px;
        }

        .referral-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
        }

        /* SECTIONS */
        .card-section {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        /* MEMBERS */
        .member {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .member img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f1f1f1;
        }

        .member .name {
            font-weight: 600;
            font-size: 15px;
        }

        .member small {
            color: #777;
            font-size: 12px;
        }

        /* FLOW ARROW */
        .flow-arrow {
            color: var(--theme);
            font-size: 18px;
        }

        /* BUSINESS INFO */
        .business-info {
            flex-direction: column;
            align-items: flex-start;
        }

        .info {
            display: flex;
            gap: 8px;
            font-size: 14px;
        }

        .label {
            color: #888;
            min-width: 70px;
        }

        .value {
            font-weight: 500;
        }

        /* STATUS */
        .status {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        /* STATUS COLORS */
        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.contacted {
            background: #e3f2fd;
            color: #0d6efd;
        }

        .status.completed {
            background: #d4edda;
            color: #198754;
        }

        .status.closed {
            background: #e2e3e5;
            color: #444;
        }

        .status.done {
            background: #d1e7dd;
            color: #146c43;
        }

        .status.not-converted {
            background: #fde2e2;
            color: #0a3d61;
        }

        .status.cancelled {
            background: #f8d7da;
            color: #0a3d61;
        }

        /* DATE */
        .date {
            font-size: 12px;
            color: #888;
        }

        /* ACTION BUTTONS */
        .actions {
            display: flex;
            gap: 8px;
        }

        .btn.action {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f6ff;
            color: var(--theme);
            transition: .2s;
        }

        .btn.action:hover {
            background: var(--theme);
            color: #fff;
        }

        /* BOX */
        .mitb-box {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .06);
        }

        /* HEADER */
        .mitb-box-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            font-weight: 600;
        }

        .mitb-box-header.given {
            color: #0a3d61;
            border-bottom: 2px solid #f4ba40;
        }

        /* PRINT BUTTON */
        .print-btn {
            border: 1px solid #0a3d61;
            color: #0a3d61;
            background: #fff;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
        }

        .print-btn .badge {
            background: #0a3d61;
            color: #fff;
            border-radius: 50%;
            padding: 3px 8px;
            margin-left: 6px;
        }

        /* COLUMN HEADER */
        .mitb-columns {
            display: flex;
            padding: 12px 20px;
            background: #fafafa;
            font-weight: 600;
            border-bottom: 1px solid #eee;
        }

        /* ROW */
        .mitb-row {
            display: flex;
            align-items: center;
            padding: 5px 8px;
            border-bottom: 1px solid #f1f1f1;
            transition: .2s;
        }

        .mitb-row:hover {
            background: #fff8e8;
        }

        /* COLUMN WIDTHS */
        .col-member {
            flex: 3;
        }

        .col-amount {
            flex: 1.2;
        }

        .col-date {
            flex: 1.2;
        }

        .col-action {
            flex: .5;
            text-align: center;
        }

        /* MEMBER */
        .member-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .member-info img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* NAME */
        .name {
            font-weight: 600;
            color: #222;
        }

        /* AMOUNT */
        .amount {
            font-weight: 700;
            color: #0a3d61;
            font-size: 16px;
        }

        /* DELETE ICON */
        .delete {
            color: #bb171d;
            cursor: pointer;
            font-size: 16px;
        }

        .delete:hover {
            color: #f4ba40;
        }

        /* EMPTY */
        .empty {
            text-align: center;
            padding: 30px;
            color: #999;
        }

        /* ================= RECEIVED THEME ================= */

        .mitb-box-header.received {
            color: #0f7b3f;
            border-bottom: 2px solid #f4ba40;
        }

        /* count badge */
        .count-badge {
            background: #0f7b3f;
            color: #fff;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        /* received hover */
        .received-row:hover {
            background: #fff6db;
        }

        /* amount color */
        .received-amount {
            color: #0f7b3f;
        }

        /* delete icon */
        .received-delete {
            color: #f4ba40;
        }

        .received-delete:hover {
            color: #bb171d;
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
            color: #1b4e8f;
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
            color: #1b4e8f;
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

        /* Gradient background */
        .profile-gradient {
            background: linear-gradient(135deg, #0a3d61, #0a3d61);
            color: #fff;
        }

        /* Profile image */
        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            object-position: top center;
            border: 4px solid #fff;
        }

        /* Divider */
        .profile-divider {
            border-color: rgba(255, 255, 255, .5);
            margin: 18px 0;
        }

        /* Section title */
        .section-title {
            font-weight: 600;
            margin-bottom: 10px;
        }

        /* Info rows alignment */
        .info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            font-size: 14px;
        }

        /* Icon alignment */
        .info-row i {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }

        /* Links */
        .info-row a {
            color: #fff;
            text-decoration: none;
            word-break: break-all;
        }

        .info-row a:hover {
            text-decoration: underline;
        }

        /* Inactive tab: solid red background */
        .theme-tab {
            background: #0a3d61;
            color: #fff;
            border: none;
            border-radius: 8px 8px 0 0;
            font-weight: 500;
            transition: all 0.3s;
            margin-right: 5px;
        }

        /* Active tab: gradient red → gold */
        .theme-tab.active {
            background: linear-gradient(90deg, #0a3d61, #0a3d61);
            color: #fff;
        }

        /* Hover effect */
        .theme-tab:hover {
            opacity: 0.85;
        }

        /* Card hover effect */
        .referral-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .social-icons-row {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .social-icon {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #1a1a1a;
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
            font-size: 16px;
        }

        .social-icon:hover {
            transform: translateY(-3px);
            opacity: 0.85;
        }

        /* Brand colors */
        .social-icon.linkedin {
            background: #0077b5;
        }

        .social-icon.facebook {
            background: #1877f2;
        }

        .social-icon.instagram {
            background: #e4405f;
        }

        .social-icon.twitter {
            background: #1da1f2;
        }

        .social-icon.youtube {
            background: #ff0000;
        }

        .social-icon.google {
            background: #db4437;
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
                <div class="row">
                    <h4>Member Profile</h4>
                </div>
                <div class="container-fluid p-3">
                    <?php foreach ($member as $row): ?>
                        <div class="row mb-4">
                            <!-- PROFILE LEFT -->
                            <div class="col-lg-4 mb-2">
                                <div class="card shadow-sm rounded-4 border-0 overflow-hidden profile-card">
                                    <div class="card-body text-center p-4 profile-gradient">

                                        <!-- Profile Image with Gold Ring & AI Badge -->
                                        <?php $adminAvatar = get_member_avatar($row->profile_photo ?? '', $row->gender ?? '', $row->user_code ?? '', true); ?>
                                        <div class="position-relative d-inline-block mb-3">
                                            <img src="<?= $adminAvatar['url'] ?>"
                                                class="rounded-circle profile-img"
                                                style="border: 3.5px solid #C6A667; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                                            <?php if ($adminAvatar['is_ai']): ?>
                                                <span class="badge" style="position: absolute; bottom: 0; right: 0; background: linear-gradient(135deg, #C6A667, #E4D4AE); color: #101B35; font-size: 10px; font-weight: 700; border-radius: 10px; padding: 2px 7px; border: 1.5px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                                                    <i class="ri-sparkling-fill me-1"></i>AI Avatar
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Name -->
                                        <h3 class="fw-bold mb-1" style="color:#ffffff;"><?= esc($row->name) ?></h3>
                                        <h6 class="fw-semibold mb-0" style="color:#ffffff;"><?= esc($row->chapter_name) ?></h6>
                                        <small class="fw-light d-block mb-3">
                                            <?= esc($row->business_name) ?>
                                        </small>

                                        <!-- Bio -->
                                        <p class="mb-3 small">
                                            <i class="fas fa-briefcase me-2"></i>
                                            <?= esc($row->bio ?: 'Member') ?>
                                        </p>

                                        <hr class="profile-divider">

                                        <!-- ================= CONTACT ================= -->
                                        <div class="info-section text-start">
                                            <h6 class="section-title" style="color:#ffffff;">Contact Information</h6>

                                            <div class="info-row">
                                                <i class="fas fa-envelope"></i>
                                                <span><?= esc($row->email) ?></span>
                                            </div>

                                            <div class="info-row">
                                                <i class="fas fa-phone"></i>
                                                <span><?= esc($row->phone) ?></span>
                                            </div>

                                            <div class="info-row">
                                                <i class="fab fa-whatsapp"></i>
                                                <span><?= esc($row->whatsapp) ?></span>
                                            </div>

                                            <?php if ($row->website): ?>
                                                <div class="info-row">
                                                    <i class="fas fa-link"></i>
                                                    <a href="<?= esc($row->website) ?>" target="_blank">
                                                        <?= esc($row->website) ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <hr class="profile-divider">
                                        <div class="info-section text-start">
                                            <h6 class="section-title" style="color:#ffffff;">More Information</h6>

                                            <?php if (!empty($row->business_name)) { ?>
                                                <p class="mb-1"><strong>Business:</strong> <?= esc($row->business_name) ?></p>
                                            <?php } ?>

                                            <?php if (!empty($row->industry)) { ?>
                                                <p class="mb-1"><strong>Industry:</strong> <?= esc($row->industry) ?></p>
                                            <?php } ?>

                                            <?php if (!empty($row->subcategory)) { ?>
                                                <p class="mb-1"><strong>Category:</strong> <?= esc($row->subcategory) ?></p>
                                            <?php } ?>
                                            <?php if (!empty($row->services)) { ?>
                                                <p class="mb-1"><strong>Services:</strong> <?= esc($row->services) ?></p>
                                            <?php } ?>

                                            <?php if (!empty($row->products)) { ?>
                                                <p class="mb-1"><strong>Products:</strong> <?= esc($row->products) ?></p>
                                            <?php } ?>

                                            <?php if (!empty($row->description)) { ?>
                                                <p class="mb-1"><strong>Description:</strong> <?= esc($row->description) ?></p>
                                            <?php } ?>
                                        </div>
                                        <hr class="profile-divider">
                                        <!-- ================= SOCIAL ================= -->
                                        <div class="info-section text-start">
                                            <h6 class="section-title" style="color:#ffffff;">Social Profiles</h6>

                                            <div class="social-icons-row">

                                                <?php if ($row->linkedin): ?>
                                                    <a href="<?= esc($row->linkedin) ?>" target="_blank" class="social-icon linkedin">
                                                        <i class="fa fa-linkedin"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($row->facebook): ?>
                                                    <a href="<?= esc($row->facebook) ?>" target="_blank" class="social-icon facebook">
                                                        <i class="fa fa-facebook"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($row->instagram): ?>
                                                    <a href="<?= esc($row->instagram) ?>" target="_blank" class="social-icon instagram">
                                                        <i class="fa fa-instagram"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($row->twitter): ?>
                                                    <a href="<?= esc($row->twitter) ?>" target="_blank" class="social-icon twitter">
                                                        <i class="fa fa-twitter"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($row->youtube): ?>
                                                    <a href="<?= esc($row->youtube) ?>" target="_blank" class="social-icon youtube">
                                                        <i class="fa fa-youtube"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($row->google_business): ?>
                                                    <a href="<?= esc($row->google_business) ?>" target="_blank" class="social-icon google">
                                                        <i class="fa fa-google"></i>
                                                    </a>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                        <hr class="profile-divider">
                                        <?php if (!empty($row->business_card)): ?>
                                            <a href="<?= base_url('uploads/business_cards/' . $row->business_card) ?>"
                                                target="_blank"
                                                class="btn btn-sm btn-danger" style="font-size:10px;background-color:#c21920;color:#ffffff;">
                                                Download Card
                                            </a>
                                        <?php endif; ?> <?php if (!empty($row->brochure)): ?>
                                            <a href="<?= base_url('uploads/brochures/' . $row->brochure) ?>"
                                                target="_blank"
                                                class="btn btn-sm" style="font-size:10px;background-color:#c21920;color:#ffffff;">
                                                Download Brochure
                                            </a>
                                        <?php endif; ?> <?php if (!empty($row->portfolio)): ?>
                                            <a href="<?= base_url('uploads/portfolios/' . $row->portfolio) ?>"
                                                target="_blank"
                                                class="btn btn-sm " style="font-size:10px;background-color:#c21920;color:#ffffff;">
                                                Download Portfolio
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- PROFILE RIGHT -->
                            <div class="col-md-8">

                                <!-- ================= ACTIVITY BREAKDOWN ================= -->
                                <div class="activity-card mt-3">

                                    <div class="activity-header">
                                        <div>
                                            <h5 class="activity-title">
                                                <i class="fa-solid fa-chart-simple"></i>
                                                Activity Breakdown
                                            </h5>
                                            <p class="activity-subtitle mb-0">
                                                Quick overview of your networking activities
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row g-3">

                                        <!-- Connect Given -->
                                        <div class="col-xl-3 col-md-6">
                                            <div class="activity-item activity-blue">

                                                <div class="activity-icon">
                                                    <i class="fa-solid fa-share-nodes"></i>
                                                </div>

                                                <div class="activity-info">
                                                    <span>Connect Given</span>
                                                    <strong><?= $r_g_c ?? 0 ?></strong>
                                                </div>

                                                <div class="activity-arrow">
                                                    <i class="fa-solid fa-arrow-up-right"></i>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Connect Received -->
                                        <div class="col-xl-3 col-md-6">
                                            <div class="activity-item activity-green">

                                                <div class="activity-icon">
                                                    <i class="fa-solid fa-handshake"></i>
                                                </div>

                                                <div class="activity-info">
                                                    <span>Connect Received</span>
                                                    <strong><?= $r_r_c ?? 0 ?></strong>
                                                </div>

                                                <div class="activity-arrow">
                                                    <i class="fa-solid fa-arrow-down-left"></i>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Visitors -->
                                        <div class="col-xl-3 col-md-6">
                                            <div class="activity-item activity-orange">

                                                <div class="activity-icon">
                                                    <i class="fa-solid fa-user-group"></i>
                                                </div>

                                                <div class="activity-info">
                                                    <span>Visitor Count</span>
                                                    <strong><?= $visitors_c ?? 0 ?></strong>
                                                </div>

                                                <div class="activity-arrow">
                                                    <i class="fa-solid fa-users"></i>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Meetings -->
                                        <div class="col-xl-3 col-md-6">
                                            <div class="activity-item activity-purple">

                                                <div class="activity-icon">
                                                    <i class="fa-solid fa-calendar-check"></i>
                                                </div>

                                                <div class="activity-info">
                                                    <span>Meetings Attended</span>
                                                    <strong><?= $meetingsCount ?? 0 ?></strong>
                                                </div>

                                                <div class="activity-arrow">
                                                    <i class="fa-solid fa-check"></i>
                                                </div>

                                            </div>
                                        </div>
    <div class="col-xl-5 col-md-6 col-6">
                                                <div class="activity-item">
                                                    <div class="activity-icon">
                                                        <i class="fas fa-calendar-check"></i>
                                                    </div>
                                                    <div class="activity-content">
                                                        <span class="activity-label">
                                                            MITB Given
                                                        </span>
                                                        <strong class="activity-number" style="font-size: 18px;">
                                                            <?= $totalMITBGiven ?? 0 ?>
                                                        </strong>
                                                        <span class="activity-status">
                                                            <i class="fas fa-check-circle"></i>
                                                            MITB
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-6 col-6">
                                                <div class="activity-item">

                                                    <div class="activity-icon">
                                                        <i class="fas fa-calendar-check"></i>
                                                    </div>

                                                    <div class="activity-content">
                                                        <span class="activity-label">
                                                            MITB Received
                                                        </span>

                                                        <strong class="activity-number" style="font-size: 18px;">
                                                            <?= $totalMITBReceived ?? 0 ?>
                                                        </strong>

                                                        <span class="activity-status">
                                                            <i class="fas fa-check-circle"></i>
                                                            Received
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                    </div>
                                </div>


                                <style>
                                    /* ================= ACTIVITY CARD ================= */

                                    .activity-card {
                                        background: #fff;
                                        border: 1px solid #edf0f4;
                                        border-radius: 16px;
                                        padding: 18px;
                                        box-shadow: 0 4px 18px rgba(20, 40, 70, 0.06);
                                    }

                                    /* ================= HEADER ================= */

                                    .activity-header {
                                        display: flex;
                                        align-items: center;
                                        justify-content: space-between;
                                        margin-bottom: 15px;
                                    }

                                    .activity-title {
                                        margin: 0;
                                        font-size: 16px;
                                        font-weight: 700;
                                        color: #1f2937;
                                        display: flex;
                                        align-items: center;
                                        gap: 9px;
                                    }

                                    .activity-title i {
                                        width: 32px;
                                        height: 32px;
                                        display: inline-flex;
                                        align-items: center;
                                        justify-content: center;
                                        border-radius: 9px;
                                        background: #eef4ff;
                                        color: #0f4aa1;
                                        font-size: 14px;
                                    }

                                    .activity-subtitle {
                                        font-size: 11px;
                                        color: #8a94a6;
                                        margin-left: 41px;
                                        margin-top: 2px;
                                    }

                                    /* ================= ACTIVITY ITEM ================= */

                                    .activity-item {
                                        position: relative;
                                        display: flex;
                                        align-items: center;
                                        gap: 12px;
                                        min-height: 76px;
                                        padding: 13px 14px;
                                        border-radius: 13px;
                                        background: #fafbfc;
                                        border: 1px solid #eef1f5;
                                        overflow: hidden;
                                        transition: all .25s ease;
                                    }

                                    .activity-item:hover {
                                        transform: translateY(-3px);
                                        box-shadow: 0 8px 22px rgba(20, 40, 70, .10);
                                        background: #fff;
                                    }

                                    /* ================= ICON ================= */

                                    .activity-icon {
                                        width: 42px;
                                        height: 42px;
                                        min-width: 42px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        border-radius: 11px;
                                        font-size: 16px;
                                    }

                                    .activity-blue .activity-icon {
                                        background: #eaf2ff;
                                        color: #0f4aa1;
                                    }

                                    .activity-green .activity-icon {
                                        background: #eafaf2;
                                        color: #198754;
                                    }

                                    .activity-orange .activity-icon {
                                        background: #fff4e8;
                                        color: #ed8a17;
                                    }

                                    .activity-purple .activity-icon {
                                        background: #f2edff;
                                        color: #7456d8;
                                    }

                                    /* ================= TEXT ================= */

                                    .activity-info {
                                        display: flex;
                                        flex-direction: column;
                                        gap: 2px;
                                        min-width: 0;
                                    }

                                    .activity-info span {
                                        color: #7b8494;
                                        font-size: 11px;
                                        font-weight: 500;
                                        white-space: nowrap;
                                    }

                                    .activity-info strong {
                                        color: #202936;
                                        font-size: 22px;
                                        line-height: 1;
                                        font-weight: 700;
                                    }

                                    /* ================= RIGHT ICON ================= */

                                    .activity-arrow {
                                        margin-left: auto;
                                        width: 27px;
                                        height: 27px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        border-radius: 50%;
                                        background: #f1f3f6;
                                        color: #8993a2;
                                        font-size: 10px;
                                        transition: .25s ease;
                                    }

                                    .activity-item:hover .activity-arrow {
                                        transform: rotate(8deg);
                                    }

                                    /* ================= LEFT ACCENT ================= */

                                    .activity-item::before {
                                        content: "";
                                        position: absolute;
                                        left: 0;
                                        top: 12px;
                                        bottom: 12px;
                                        width: 3px;
                                        border-radius: 0 4px 4px 0;
                                    }

                                    .activity-blue::before {
                                        background: #0f4aa1;
                                    }

                                    .activity-green::before {
                                        background: #198754;
                                    }

                                    .activity-orange::before {
                                        background: #ed8a17;
                                    }

                                    .activity-purple::before {
                                        background: #7456d8;
                                    }

                                    /* ================= MOBILE ================= */

                                    @media (max-width: 767px) {

                                        .activity-card {
                                            padding: 14px;
                                            border-radius: 14px;
                                        }

                                        .activity-header {
                                            margin-bottom: 12px;
                                        }

                                        .activity-title {
                                            font-size: 15px;
                                        }

                                        .activity-subtitle {
                                            font-size: 10px;
                                            margin-left: 41px;
                                        }

                                        .activity-item {
                                            min-height: 68px;
                                            padding: 11px 12px;
                                        }

                                        .activity-icon {
                                            width: 38px;
                                            height: 38px;
                                            min-width: 38px;
                                            font-size: 14px;
                                        }

                                        .activity-info strong {
                                            font-size: 19px;
                                        }

                                        .activity-info span {
                                            font-size: 10px;
                                        }
                                    }
                                </style>


                                <!-- GIVEN CONNECTS -->
                                <div class="card mt-3 shadow-sm rounded-4">
                                    <div class="card-body">
                                        <!-- Tabs nav with theme colors -->
                                        <ul class="nav nav-tabs mb-3" id="referralTabs" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link theme-tab active d-flex align-items-center justify-content-between"
                                                    id="given-tab" data-bs-toggle="tab" data-bs-target="#given"
                                                    type="button" role="tab" aria-controls="given" aria-selected="true">
                                                    Given Connects
                                                    <span class="badge bg-white text-dark ms-2"><?= count($given) ?></span>
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link theme-tab d-flex align-items-center justify-content-between"
                                                    id="mitb-tab" data-bs-toggle="tab" data-bs-target="#mitb"
                                                    type="button" role="tab" aria-controls="mitb" aria-selected="false">
                                                    Business Recived
                                                    <span class="badge bg-white text-dark ms-2"><?= count($mitb_received) ?></span>
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link theme-tab d-flex align-items-center justify-content-between"
                                                    id="mitb-tab2" data-bs-toggle="tab" data-bs-target="#mitb2"
                                                    type="button" role="tab" aria-controls="mitb2" aria-selected="false">
                                                    Business Given
                                                    <span class="badge bg-white text-dark ms-2"><?= count($mitb_given) ?></span>
                                                </button>
                                            </li>
                                        </ul>


                                        <!-- Tabs content -->
                                        <div class="tab-content" id="referralTabsContent">
                                            <!-- GIVEN REFERRAL TAB -->
                                            <div class="tab-pane fade show active" id="given" role="tabpanel" aria-labelledby="given-tab">
                                                <?php if (!empty($given)): ?>
                                                    <div class="row g-2">
                                                        <?php foreach ($given as $row): ?>
                                                            <div class="col-12">
                                                                <div class="referral-card p-2 d-flex justify-content-between align-items-center shadow-sm rounded-3" style="background-color: #fff7f0; transition: transform 0.2s;">
                                                                    <!-- LEFT: Member Info -->
                                                                    <div class="d-flex align-items-center">
                                                                        <img src="<?= get_member_avatar($row['to_profile'] ?? '', '', $row['to_user_code'] ?? '') ?>"
                                                                            class="rounded-circle me-3" width="60" height="60" style="object-fit: cover;object-position: top center; border: 2px solid #C6A667;">
                                                                        <div>
                                                                            <div class="fw-bold"><?= esc($row['to_name']) ?></div>
                                                                            <small class="text-muted"><?= esc($row['to_email']) ?></small>
                                                                        </div>
                                                                    </div>

                                                                    <!-- CENTER: Business Info -->
                                                                    <div class="text-center flex-fill mx-3" style="min-width: 200px;">
                                                                        <p class="mb-0" style="font-size: 14px;">
                                                                            <strong>Business:</strong> <?= esc($row['business']) ?><br>
                                                                            <i class="fa fa-phone"></i> <?= esc($row['contact_number']) ?>
                                                                        </p>
                                                                    </div>

                                                                    <!-- RIGHT: Status & Actions -->
                                                                    <div class="text-end">
                                                                        <?php
                                                                        $statusClass = match ($row['status']) {
                                                                            'pending' => 'bg-warning text-dark',
                                                                            'contacted' => 'bg-info text-white',
                                                                            'completed' => 'bg-primary text-white',
                                                                            'closed business' => 'bg-secondary text-white',
                                                                            'business_done' => 'bg-success text-white',
                                                                            'not converted' => 'bg-danger text-white',
                                                                            'cancelled' => 'bg-dark text-white',
                                                                            default => 'bg-warning text-dark'
                                                                        };
                                                                        ?>
                                                                        <span class="badge <?= $statusClass ?> mb-2"
                                                                            style="cursor:pointer;"
                                                                            onclick="edit_refferral_status('<?= $row['referral_code'] ?>');">
                                                                            <?= esc(ucwords(str_replace('_', ' ', $row['status']))) ?>
                                                                        </span>
                                                                        <div class="text-muted mb-2" style="font-size: 12px;">
                                                                            <?= (new DateTime($row['created_at']))->format('d M Y • h:i A') ?>
                                                                        </div>

                                                                    </div> <a href="<?= site_url('admin/connect/details/' . $row['referral_code']) ?>"
                                                                        class="btn btn-sm" style="background-color:#f05423;color:#fff;">
                                                                        <i class="fa fa-eye"></i> View
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-center text-muted py-4">No Given Connects </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- MITB RECEIVED TAB -->
                                            <div class="tab-pane fade" id="mitb" role="tabpanel" aria-labelledby="mitb-tab">
                                                <?php if (!empty($mitb_received)): ?>
                                                    <div class="row g-2">
                                                        <?php foreach ($mitb_received as $row): ?>
                                                            <div class="mitb-row received-row">
                                                                <div class="col-member">
                                                                    <div class="member-info">
                                                                        <img src="<?= get_member_avatar($row['from_profile'] ?? '', '', $row['from_user_code'] ?? ($row['user_code'] ?? '')) ?>" style="border-radius: 50%; object-fit: cover; object-position: top center; border: 1.5px solid #101B35;">
                                                                        <div>
                                                                            <div class="name"><?= esc($row['from_name']) ?> (<small><?= esc($row['category']) ?>)</small></div>
                                                                            <p style="font-size:13px;"><?= esc($row['description']) ?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-amount amount received-amount">
                                                                    ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($row['amount'], 2) ?>
                                                                </div>
                                                                <div class="col-date">
                                                                    <?= date('M d, Y', strtotime($row['created_at'])) ?>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-center text-muted py-4">No Records Found</div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="tab-pane fade" id="mitb2" role="tabpanel" aria-labelledby="mitb-tab2">
                                                <?php if (!empty($mitb_given)): ?>
                                                    <div class="row g-2">
                                                        <?php foreach ($mitb_given as $row): ?>
                                                            <div class="mitb-row received-row">
                                                                <div class="col-member">
                                                                    <div class="member-info">
                                                                        <img src="<?= get_member_avatar($row['to_profile'] ?? ($row['from_profile'] ?? ''), '', $row['to_user_code'] ?? ($row['from_user_code'] ?? '')) ?>" style="border-radius: 50%; object-fit: cover; object-position: top center; border: 1.5px solid #C6A667;">
                                                                        <div>
                                                                            <div class="name"><?= esc($row['from_name']) ?> (<small><?= esc($row['category']) ?>)</small></div>
                                                                            <p style="font-size:13px;"><?= esc($row['description']) ?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-amount amount received-amount">
                                                                    ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($row['amount'], 2) ?>
                                                                </div>
                                                                <div class="col-date">
                                                                    <?= date('M d, Y', strtotime($row['created_at'])) ?>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-center text-muted py-4">No Records Found</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold text-danger mb-0">
                                    <i class="bi bi-graph-up-arrow me-2"></i>
                                    Business Performance
                                </h5>
                                <ul class="nav nav-tabs custom-report-tabs">
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
                            <div class="card dash-card ">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-semibold mb-0">
                                            <i class="bi bi-bar-chart-line-fill me-2"></i>
                                            Business Report
                                        </h6>
                                        <select id="mitbFilter" class="form-select form-select-sm w-auto">
                                            <option value="7" <?= ($filter == '7') ? 'selected' : '' ?>>Last 7 Days</option>
                                            <option value="30" <?= ($filter == '30') ? 'selected' : '' ?>>1 Month</option>
                                            <option value="180" <?= ($filter == '180') ? 'selected' : '' ?>>6 Months</option>
                                            <option value="all" <?= ($filter == 'all') ? 'selected' : '' ?>>Lifetime</option>
                                        </select>
                                    </div>
                                    <canvas id="weeklyMITBChart" height="130"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><br><br>
            <!-- End Page-content -->
            <?php echo view('include/admin/footer.php') ?>
        </div>
        <!-- end main content-->
    </div>
    <div class="rightbar-overlay"></div>
    <!-- JAVASCRIPT -->
    <?php echo view('include/admin/popup/profile.php') ?>
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
    <script>
        const labels = <?= $dates ?>;
        const givenData = <?= $givenData ?>;
        const receivedData = <?= $receivedData ?>;

        new Chart(document.getElementById('weeklyMITBChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Business  Given',
                        data: givenData,
                        borderColor: '#cf2030',
                        backgroundColor: 'rgba(237,45,123,.1)',
                        tension: .4,
                        fill: true
                    },
                    {
                        label: 'Business  Received',
                        data: receivedData,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40,167,69,.1)',
                        tension: .4,
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
                }
            }
        });
    </script>
    <script>
        function previewImg(input, id) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    $('#' + id).attr('src', e.target.result).show();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        function togglePass(id, btn) {
            let input = document.getElementById(id);
            let icon = btn.querySelector("i");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>

    <script>
        document.getElementById('mitbFilter').addEventListener('change', function() {
            let filter = this.value;
            window.location.href =
                "<?= base_url('member/dashboard') ?>?filter=" + filter;
        });
    </script>

    <script>
        document.getElementById('mitbFilter')
            .addEventListener('change', function() {

                const value = this.value;
                const url = new URL(window.location.href);
                url.searchParams.set('filter', value);

                window.location.href = url.toString();
            });
    </script>
</body>

</html>