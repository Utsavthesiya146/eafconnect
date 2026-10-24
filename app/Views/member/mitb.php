<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>MITB Reports & Monthly Contribution Analytics | EAF Connect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    
    <!-- Core Vendor CSS -->
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts & Master EAF Connect Theme -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets2/css/eaf-revamp.css'); ?>?v=8.0">
    
    <style>
        :root {
            --mitb-primary: #101B35;
            --mitb-primary-dark: #0B1325;
            --mitb-primary-light: #172B50;
            --mitb-accent-gold: #C6A667;
            --mitb-gold-light: #E4D4AE;
            --mitb-emerald: #0D9488;
            --mitb-emerald-bg: #F0FDFA;
            --mitb-surface: #FFFFFF;
            --mitb-border: #E2E8F0;
            --mitb-bg-main: #F8FAFC;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
            background-color: #F8FAFC !important;
            color: #1E293B;
        }

        /* ================= PAGE HEADER ================= */
        .mitb-page-header {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 20px;
            border: 1px solid var(--mitb-border);
            box-shadow: 0 4px 16px rgba(16, 27, 53, 0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .mitb-header-title {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--mitb-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mitb-header-sub {
            font-size: 13px;
            color: #64748B;
            margin-top: 4px;
            margin-bottom: 0;
        }

        .btn-submit-mitb {
            background: linear-gradient(135deg, #101B35 0%, #172B50 100%);
            color: #ffffff !important;
            border: 1px solid #C6A667;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(16, 27, 53, 0.2);
            transition: all 0.25s ease;
        }

        .btn-submit-mitb:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(198, 166, 103, 0.35);
            color: #ffffff !important;
        }

        .btn-submit-mitb i {
            color: #C6A667;
            font-size: 16px;
        }

        /* ================= HERO KPI SUITE ================= */
        .kpi-suite-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        @media (max-width: 1200px) {
            .kpi-suite-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .kpi-suite-grid {
                grid-template-columns: 1fr;
            }
        }

        .kpi-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 18px 20px;
            border: 1px solid var(--mitb-border);
            box-shadow: 0 4px 16px rgba(16, 27, 53, 0.05);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            transition: all 0.25s ease;
        }

        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(16, 27, 53, 0.09);
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #94A3B8;
        }

        .kpi-card.card-primary::before { background: linear-gradient(180deg, #101B35, #C6A667); }
        .kpi-card.card-received::before { background: #0D9488; }
        .kpi-card.card-given::before { background: #C6A667; }
        .kpi-card.card-avg::before { background: #172B50; }

        .kpi-icon-wrap {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .card-primary .kpi-icon-wrap { background: rgba(16, 27, 53, 0.08); color: #101B35; }
        .card-received .kpi-icon-wrap { background: rgba(13, 148, 136, 0.12); color: #0D9488; }
        .card-given .kpi-icon-wrap { background: rgba(198, 166, 103, 0.18); color: #A88849; }
        .card-avg .kpi-icon-wrap { background: rgba(23, 43, 80, 0.1); color: #172B50; }

        .kpi-info {
            flex: 1;
            min-width: 0;
        }

        .kpi-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .kpi-value {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: #101B35;
            line-height: 1.2;
            letter-spacing: -0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-received .kpi-value { color: #0D9488; }
        .card-given .kpi-value { color: #A88849; }

        .kpi-subtext {
            font-size: 11.5px;
            color: #64748B;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .kpi-pill {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 2px 7px;
            border-radius: 6px;
            font-size: 10.5px;
            font-weight: 700;
        }

        .kpi-pill.pill-growth { background: #ECFDF5; color: #059669; }
        .kpi-pill.pill-neutral { background: #F1F5F9; color: #475569; }

        /* ================= DYNAMIC ANALYTICS SECTION ================= */
        .analytics-container-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 22px 24px;
            border: 1px solid var(--mitb-border);
            box-shadow: 0 4px 16px rgba(16, 27, 53, 0.04);
            margin-bottom: 24px;
        }

        .analytics-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 18px;
            border-bottom: 1px solid #F1F5F9;
            padding-bottom: 14px;
        }

        .analytics-title {
            font-family: 'Outfit', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: #101B35;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chart-controls-group {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .chart-range-btn {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            color: #64748B;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 11.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .chart-range-btn:hover,
        .chart-range-btn.active {
            background: #101B35;
            color: #ffffff;
            border-color: #101B35;
        }

        /* ================= MONTHLY CONTRIBUTION STRIP ================= */
        .monthly-strip-label {
            font-size: 12px;
            font-weight: 700;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .monthly-chips-scroll {
            display: flex;
            align-items: center;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 10px;
            margin-bottom: 16px;
            scrollbar-width: thin;
            scrollbar-color: #CBD5E1 transparent;
        }

        .monthly-chips-scroll::-webkit-scrollbar {
            height: 5px;
        }

        .monthly-chips-scroll::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 10px;
        }

        .month-chip {
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            padding: 9px 14px;
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 130px;
        }

        .month-chip:hover {
            border-color: #C6A667;
            background: #FFFDF9;
            transform: translateY(-2px);
        }

        .month-chip.active {
            background: linear-gradient(135deg, #101B35, #172B50);
            border-color: #C6A667;
            box-shadow: 0 4px 14px rgba(16, 27, 53, 0.2);
        }

        .month-chip .chip-month-title {
            font-size: 12px;
            font-weight: 700;
            color: #1E293B;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .month-chip.active .chip-month-title {
            color: #ffffff;
        }

        .month-chip .chip-month-val {
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            font-weight: 800;
            color: #0D9488;
        }

        .month-chip.active .chip-month-val {
            color: #C6A667;
        }

        .month-chip .chip-month-slips {
            font-size: 10.5px;
            color: #64748B;
        }

        .month-chip.active .chip-month-slips {
            color: #E2E8F0;
        }

        /* ================= FILTER TOOLBAR ================= */
        .mitb-filter-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 18px 20px;
            border: 1px solid var(--mitb-border);
            box-shadow: 0 4px 16px rgba(16, 27, 53, 0.04);
            margin-bottom: 20px;
        }

        .filter-grid-row {
            display: grid;
            grid-template-columns: 2.2fr 1fr 1fr 1.2fr 1.1fr 1.1fr 1.1fr auto;
            gap: 10px;
            align-items: flex-end;
        }

        @media (max-width: 1400px) {
            .filter-grid-row {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 768px) {
            .filter-grid-row {
                grid-template-columns: 1fr;
            }
        }

        .filter-field-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .filter-field-label {
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .filter-field-label i {
            color: #0D9488;
        }

        .filter-ctrl {
            height: 38px;
            border-radius: 9px;
            border: 1px solid #CBD5E1;
            padding: 0 12px;
            font-size: 12.5px;
            color: #1E293B;
            background: #F8FAFC;
            transition: all 0.2s ease;
        }

        .filter-ctrl:focus {
            background: #ffffff;
            border-color: #101B35;
            box-shadow: 0 0 0 3px rgba(16, 27, 53, 0.1);
            outline: none;
        }

        .filter-search-wrap {
            position: relative;
        }

        .filter-search-wrap i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 14px;
        }

        .filter-search-wrap input {
            padding-left: 36px;
        }

        .filter-actions-group {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-filter-action {
            height: 38px;
            padding: 0 14px;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-reset-filters {
            background: #F1F5F9;
            color: #475569;
            border-color: #E2E8F0;
        }

        .btn-reset-filters:hover {
            background: #E2E8F0;
            color: #0F172A;
        }

        .btn-export-excel {
            background: #ECFDF5;
            color: #059669;
            border-color: #A7F3D0;
        }

        .btn-export-excel:hover {
            background: #059669;
            color: #ffffff;
        }

        .btn-export-pdf {
            background: #FFF1F2;
            color: #E11D48;
            border-color: #FECDD3;
        }

        .btn-export-pdf:hover {
            background: #E11D48;
            color: #ffffff;
        }

        /* ================= TABS NAVIGATION ================= */
        .mitb-tabs-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 2px solid #E2E8F0;
            margin-bottom: 16px;
            padding-left: 4px;
            overflow-x: auto;
        }

        .mitb-tab-btn {
            background: transparent;
            border: none;
            padding: 10px 18px;
            font-size: 13.5px;
            font-weight: 600;
            color: #64748B;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .mitb-tab-btn:hover {
            color: #101B35;
        }

        .mitb-tab-btn.active {
            color: #101B35;
            border-bottom-color: #C6A667;
            font-weight: 700;
        }

        .mitb-tab-badge {
            background: #F1F5F9;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 12px;
            border: 1px solid #E2E8F0;
        }

        .mitb-tab-btn.active .mitb-tab-badge {
            background: #101B35;
            color: #ffffff;
            border-color: #101B35;
        }

        /* ================= MODERN TABLE ================= */
        .mitb-table-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--mitb-border);
            box-shadow: 0 4px 16px rgba(16, 27, 53, 0.04);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-responsive {
            margin: 0;
        }

        .mitb-modern-table {
            width: 100%;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .mitb-modern-table thead {
            background: #F8FAFC;
            border-bottom: 2px solid #E2E8F0;
        }

        .mitb-modern-table th {
            padding: 14px 16px;
            font-size: 11.5px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 2px solid #E2E8F0;
            white-space: nowrap;
        }

        .mitb-modern-table tbody tr {
            transition: background 0.15s ease;
            border-bottom: 1px solid #F1F5F9;
        }

        .mitb-modern-table tbody tr:hover {
            background: #FFFDF9;
        }

        .mitb-modern-table td {
            padding: 13px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #F1F5F9;
            font-size: 13px;
        }

        /* Row styling inside table */
        .mitb-idx-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 7px;
            background: #F1F5F9;
            color: #64748B;
            font-size: 11px;
            font-weight: 700;
        }

        .mitb-date-cell {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .mitb-date-val {
            font-weight: 700;
            color: #1E293B;
            font-size: 13px;
        }

        .mitb-amount-cell {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .mitb-amount-number {
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .amount-received { color: #0D9488; }
        .amount-given { color: #C6A667; }

        .mitb-direction-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 2px 7px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            width: fit-content;
        }

        .badge-inbound {
            background: #F0FDFA;
            color: #0D9488;
            border: 1px solid #CCFBF1;
        }

        .badge-outbound {
            background: #FFFBEB;
            color: #B45309;
            border: 1px solid #FEF3C7;
        }

        .mitb-member-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mitb-avatar-wrap {
            position: relative;
            flex-shrink: 0;
        }

        .mitb-table-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            object-position: top center;
        }

        .avatar-from { border: 2px solid #101B35; }
        .avatar-to { border: 2px solid #C6A667; }

        .mitb-you-tag {
            position: absolute;
            bottom: -3px;
            right: -3px;
            background: #101B35;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: 700;
            padding: 1px 4px;
            border-radius: 4px;
            border: 1px solid #ffffff;
        }

        .mitb-you-tag.tag-gold {
            background: #C6A667;
            color: #101B35;
        }

        .mitb-member-info {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .mitb-member-name {
            font-weight: 700;
            color: #1E293B;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mitb-flow-indicator {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #F1F5F9;
            color: #64748B;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .mitb-chip {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .chip-category {
            background: #F8FAFC;
            color: #475569;
            border: 1px solid #E2E8F0;
        }

        .chip-new {
            background: #EFF6FF;
            color: #2563EB;
            border: 1px solid #DBEAFE;
        }

        .chip-repeat {
            background: #FAF5FF;
            color: #7E22CE;
            border: 1px solid #F3E8FF;
        }

        .mitb-action-group {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .mitb-action-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            background: #F8FAFC;
            color: #64748B;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .mitb-action-icon.btn-view:hover { background: #F0FDFA; color: #0D9488; }
        .mitb-action-icon.btn-edit:hover { background: #EFF6FF; color: #2563EB; }
        .mitb-action-icon.btn-del:hover { background: #FFF1F2; color: #E11D48; }

        .monthly-matrix-row {
            cursor: pointer;
        }

        .month-calendar-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(16, 27, 53, 0.08);
            color: #101B35;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        @media (max-width: 991px) {
            #vertical-menu-btn {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                cursor: pointer;
            }
        }

        @media (max-width: 576px) {
            .kvv-header-sub {
                font-size: 11px;
                white-space: normal;
                line-height: 1.25;
            }
            .kvv-header-user-info {
                max-width: 190px;
            }
            .mitb-page-header {
                padding: 16px;
            }
            .mitb-header-title {
                font-size: 18px;
            }
            .kpi-suite-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .kpi-card {
                padding: 16px;
            }
            .mitb-filter-card {
                padding: 14px;
            }
        }
    </style>
</head>

<body data-sidebar="colored">
    <div id="layout-wrapper">
        <?php echo view('include/member/header.php') ?>
        <?php echo view('include/member/sidebar.php') ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <!-- ================= 1. PAGE HEADER ================= -->
                    <div class="mitb-page-header">
                        <div>
                            <h4 class="mitb-header-title">
                                <i class="ri-money-rupee-circle-fill text-warning"></i>
                                MITB Reports & Contribution Analytics
                            </h4>
                            <p class="mitb-header-sub">
                                Complete tracking of closed business (TYFCB), monthly contributions & chapter analytics
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn-submit-mitb" onclick="add_mitb();">
                                <i class="ri-add-circle-fill"></i>
                                <span>Submit MITB Slip</span>
                            </button>
                        </div>
                    </div>

                    <!-- ================= 2. HERO KPI SUITE ================= -->
                    <div class="kpi-suite-grid">
                        
                        <!-- CARD 1: TOTAL BUSINESS VOLUME -->
                        <div class="kpi-card card-primary">
                            <div class="kpi-icon-wrap">
                                <i class="ri-bank-card-fill"></i>
                            </div>
                            <div class="kpi-info">
                                <div class="kpi-label">
                                    <span>Total Closed Business</span>
                                    <span class="badge bg-primary-subtle text-primary border" id="kpi-records-badge">
                                        <?= $totalRecords ?> Records
                                    </span>
                                </div>
                                <div class="kpi-value" id="kpi-total-revenue">
                                    ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($totalRevenue ?? 0, 2) ?>
                                </div>
                                <div class="kpi-subtext">
                                    <span>Personal & Chapter contribution</span>
                                </div>
                            </div>
                        </div>

                        <!-- CARD 2: BUSINESS RECEIVED (INBOUND) -->
                        <div class="kpi-card card-received">
                            <div class="kpi-icon-wrap">
                                <i class="ri-arrow-down-circle-fill"></i>
                            </div>
                            <div class="kpi-info">
                                <div class="kpi-label">
                                    <span>Business Received</span>
                                    <span class="kpi-pill pill-growth" id="kpi-received-count-badge">
                                        <?= $receivedCount ?> Slips
                                    </span>
                                </div>
                                <div class="kpi-value" id="kpi-total-received">
                                    ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($totalReceived ?? 0, 2) ?>
                                </div>
                                <div class="kpi-subtext">
                                    <span>Inbound closed business from referrals</span>
                                </div>
                            </div>
                        </div>

                        <!-- CARD 3: BUSINESS GIVEN (OUTBOUND) -->
                        <div class="kpi-card card-given">
                            <div class="kpi-icon-wrap">
                                <i class="ri-arrow-up-circle-fill"></i>
                            </div>
                            <div class="kpi-info">
                                <div class="kpi-label">
                                    <span>Business Given</span>
                                    <span class="kpi-pill pill-neutral" id="kpi-given-count-badge">
                                        <?= $givenCount ?> Slips
                                    </span>
                                </div>
                                <div class="kpi-value" id="kpi-total-given">
                                    ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($totalGiven ?? 0, 2) ?>
                                </div>
                                <div class="kpi-subtext">
                                    <span>Outbound business generated for members</span>
                                </div>
                            </div>
                        </div>

                        <!-- CARD 4: AVG MEMBER VALUE & RUN RATE -->
                        <div class="kpi-card card-avg">
                            <div class="kpi-icon-wrap">
                                <i class="ri-line-chart-fill"></i>
                            </div>
                            <div class="kpi-info">
                                <div class="kpi-label">
                                    <span>Avg Member Value</span>
                                    <span class="kpi-pill <?= $growthPercent >= 0 ? 'pill-growth' : 'pill-neutral' ?>">
                                        <i class="<?= $growthPercent >= 0 ? 'ri-arrow-up-line' : 'ri-arrow-down-line' ?>"></i>
                                        <?= $growthPercent ?>% MoM
                                    </span>
                                </div>
                                <div class="kpi-value" id="kpi-avg-value">
                                    ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($avgMemberValue ?? 0, 2) ?>
                                </div>
                                <div class="kpi-subtext">
                                    <span>This Month: ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($thisMonthRevenue ?? 0, 0) ?></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- ================= 3. DYNAMIC MONTHLY CONTRIBUTIONS & CHART ================= -->
                    <div class="analytics-container-card">
                        
                        <!-- Header & Range Switcher -->
                        <div class="analytics-card-header">
                            <div>
                                <h5 class="analytics-title">
                                    <i class="ri-bar-chart-grouped-fill text-primary"></i>
                                    Monthly Contribution Trends & History
                                </h5>
                                <small class="text-muted">Interactive breakdown of Business Received vs Business Given across months</small>
                            </div>
                            <div class="chart-controls-group">
                                <button type="button" class="chart-range-btn active" data-range="6">Last 6 Months</button>
                                <button type="button" class="chart-range-btn" data-range="12">Last 12 Months</button>
                                <button type="button" class="chart-range-btn" data-range="all">All Months</button>
                            </div>
                        </div>

                        <!-- MONTHLY CONTRIBUTION CHIPS STRIP -->
                        <div>
                            <div class="monthly-strip-label">
                                <i class="ri-calendar-check-fill text-primary"></i>
                                <span>Filter By Specific Month Contribution:</span>
                            </div>
                            <div class="monthly-chips-scroll" id="monthly-chips-container">
                                <!-- All Months chip -->
                                <div class="month-chip active" onclick="filterByMonth('')">
                                    <div class="chip-month-title">
                                        <span>All Months</span>
                                        <i class="ri-check-line"></i>
                                    </div>
                                    <div class="chip-month-val">
                                        ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($totalRevenue ?? 0, 0) ?>
                                    </div>
                                    <div class="chip-month-slips"><?= $totalRecords ?> total slips</div>
                                </div>

                                <?php if (!empty($monthlyContributions)) : ?>
                                    <?php foreach ($monthlyContributions as $mc) : ?>
                                        <div class="month-chip" data-month="<?= esc($mc['month_key']) ?>" onclick="filterByMonth('<?= esc($mc['month_key']) ?>')">
                                            <div class="chip-month-title">
                                                <span><?= esc($mc['month_label']) ?></span>
                                                <i class="ri-calendar-line"></i>
                                            </div>
                                            <div class="chip-month-val">
                                                ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($mc['total_amount'] ?? 0, 0) ?>
                                            </div>
                                            <div class="chip-month-slips">
                                                <?= (int)$mc['total_count'] ?> slips (Rec: ₹<?= number_format($mc['received_amount']) ?>)
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- APEXCHART CANVAS -->
                        <div id="monthlyMitbApexChart" style="min-height: 280px;"></div>

                    </div>

                    <!-- ================= 4. FILTER TOOLBAR ================= -->
                    <div class="mitb-filter-card">
                        <form id="mitbFilterForm" onsubmit="return false;">
                            <input type="hidden" id="filter_specific_month" name="specific_month" value="">
                            <input type="hidden" id="filter_active_tab" name="tab" value="all">

                            <div class="filter-grid-row">
                                
                                <!-- Search -->
                                <div class="filter-field-group">
                                    <label class="filter-field-label" for="search">
                                        <i class="ri-search-line"></i> Search Transactions
                                    </label>
                                    <div class="filter-search-wrap">
                                        <i class="ri-search-line"></i>
                                        <input type="text" id="search" name="search" class="filter-ctrl" placeholder="Search members, amount, slip...">
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="filter-field-group">
                                    <label class="filter-field-label" for="category">
                                        <i class="ri-price-tag-3-line"></i> Category
                                    </label>
                                    <select id="category" name="category" class="filter-ctrl">
                                        <option value="">All Categories</option>
                                        <option value="New">New</option>
                                        <option value="Visitor">Visitor</option>
                                        <option value="My Chapter Member">My Chapter Member</option>
                                        <option value="Across Chapter Member">Across Chapter Member</option>
                                    </select>
                                </div>

                                <!-- Business Type -->
                                <div class="filter-field-group">
                                    <label class="filter-field-label" for="business_type">
                                        <i class="ri-briefcase-4-line"></i> Business Type
                                    </label>
                                    <select id="business_type" name="business_type" class="filter-ctrl">
                                        <option value="">All Types</option>
                                        <option value="New">New Business</option>
                                        <option value="Repeat">Repeat Business</option>
                                    </select>
                                </div>

                                <!-- Chapter -->
                                <div class="filter-field-group">
                                    <label class="filter-field-label" for="chapter_code">
                                        <i class="ri-community-line"></i> Chapter
                                    </label>
                                    <select id="chapter_code" name="chapter_code" class="filter-ctrl">
                                        <option value="">All Chapters</option>
                                        <?php if (!empty($chapters)) : ?>
                                            <?php foreach ($chapters as $chap) : ?>
                                                <option value="<?= esc($chap['chapter_code']) ?>"><?= esc($chap['chapter_name']) ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <!-- Duration -->
                                <div class="filter-field-group">
                                    <label class="filter-field-label" for="month_filter">
                                        <i class="ri-time-line"></i> Duration
                                    </label>
                                    <select id="month_filter" name="month_filter" class="filter-ctrl">
                                        <option value="">All Time</option>
                                        <option value="7">Last 7 Days</option>
                                        <option value="30">Last 30 Days</option>
                                        <option value="90">Last 3 Months</option>
                                        <option value="180">Last 6 Months</option>
                                        <option value="365">Last 12 Months</option>
                                    </select>
                                </div>

                                <!-- From Date -->
                                <div class="filter-field-group">
                                    <label class="filter-field-label" for="from_date">
                                        <i class="ri-calendar-event-line"></i> From
                                    </label>
                                    <input type="date" id="from_date" name="from_date" class="filter-ctrl">
                                </div>

                                <!-- To Date -->
                                <div class="filter-field-group">
                                    <label class="filter-field-label" for="to_date">
                                        <i class="ri-calendar-check-line"></i> To
                                    </label>
                                    <input type="date" id="to_date" name="to_date" class="filter-ctrl">
                                </div>

                                <!-- Action Buttons -->
                                <div class="filter-actions-group">
                                    <button type="button" id="reset-btn" class="btn-filter-action btn-reset-filters" title="Reset all filters">
                                        <i class="ri-refresh-line"></i> Reset
                                    </button>
                                    <button type="button" id="export-excel" class="btn-filter-action btn-export-excel" title="Download Excel report">
                                        <i class="ri-file-excel-2-line"></i> Excel
                                    </button>
                                    <button type="button" id="export-pdf" class="btn-filter-action btn-export-pdf" title="Download PDF report">
                                        <i class="ri-file-pdf-2-line"></i> PDF
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>

                    <!-- ================= 5. TABS NAVIGATION ================= -->
                    <div class="mitb-tabs-nav">
                        <button type="button" class="mitb-tab-btn active" data-tab="all" onclick="switchMitbTab('all')">
                            <i class="ri-file-list-3-line"></i>
                            <span>All Contributions</span>
                            <span class="mitb-tab-badge" id="tab-all-count"><?= $totalRecords ?></span>
                        </button>
                        <button type="button" class="mitb-tab-btn" data-tab="received" onclick="switchMitbTab('received')">
                            <i class="ri-arrow-down-circle-line text-success"></i>
                            <span>Business Received (Inbound)</span>
                            <span class="mitb-tab-badge" id="tab-received-count"><?= $receivedCount ?></span>
                        </button>
                        <button type="button" class="mitb-tab-btn" data-tab="given" onclick="switchMitbTab('given')">
                            <i class="ri-arrow-up-circle-line text-warning"></i>
                            <span>Business Given (Outbound)</span>
                            <span class="mitb-tab-badge" id="tab-given-count"><?= $givenCount ?></span>
                        </button>
                        <button type="button" class="mitb-tab-btn" data-tab="summary" onclick="switchMitbTab('summary')">
                            <i class="ri-calendar-todo-line text-primary"></i>
                            <span>Monthly Summary Matrix</span>
                            <span class="mitb-tab-badge" id="tab-months-count"><?= count($monthlyContributions ?? []) ?> Months</span>
                        </button>
                    </div>

                    <!-- ================= 6. DATA TABLES ================= -->
                    
                    <!-- TAB 1-3 TABLE: TRANSACTIONS LIST -->
                    <div id="tab-content-transactions" class="mitb-table-card">
                        <div class="table-responsive">
                            <table class="mitb-modern-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>Date & Time</th>
                                        <th>Amount (INR)</th>
                                        <th>Given By (From)</th>
                                        <th class="text-center" style="width: 40px;">Flow</th>
                                        <th>Received By (To)</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Business Type</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="mitb_table_body">
                                    <?php if (!empty($all_records)) : ?>
                                        <?php $idx = 1; ?>
                                        <?php foreach ($all_records as $row) : ?>
                                            <?= view('include/member/card/mitb_card', [
                                                'row' => $row,
                                                'i'   => $idx++,
                                                'currentUserCode' => session()->get('user_code')
                                            ]) ?>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <div class="text-center p-4">
                                                    <i class="ri-folder-open-line" style="font-size:42px; color:#94A3B8;"></i>
                                                    <h6 class="mt-2 text-muted fw-bold">No MITB Records Found</h6>
                                                    <p class="text-secondary small mb-0">No closed business recorded yet.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 4 TABLE: MONTHLY SUMMARY MATRIX -->
                    <div id="tab-content-monthly-matrix" class="mitb-table-card" style="display: none;">
                        <div class="table-responsive">
                            <table class="mitb-modern-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 60px;">#</th>
                                        <th>Month & Year</th>
                                        <th class="text-end">Business Received (INR)</th>
                                        <th class="text-end">Business Given (INR)</th>
                                        <th class="text-end">Total Volume (INR)</th>
                                        <th class="text-center">Total Slips</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="mitb_matrix_body">
                                    <?php if (!empty($monthlyContributions)) : ?>
                                        <?php $mIdx = 1; ?>
                                        <?php foreach ($monthlyContributions as $mq) : ?>
                                            <tr class="monthly-matrix-row" onclick="filterByMonth('<?= esc($mq['month_key']) ?>')">
                                                <td class="text-center fw-semibold text-muted"><?= $mIdx++ ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="month-calendar-icon"><i class="ri-calendar-event-fill"></i></span>
                                                        <div>
                                                            <span class="fw-bold text-dark d-block"><?= esc($mq['month_full_label']) ?></span>
                                                            <small class="text-muted"><?= esc($mq['month_key']) ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end fw-bold text-success" style="font-size:14px;">
                                                    ₹ <?= number_format($mq['received_amount'], 2) ?>
                                                </td>
                                                <td class="text-end fw-bold" style="color:#C6A667; font-size:14px;">
                                                    ₹ <?= number_format($mq['given_amount'], 2) ?>
                                                </td>
                                                <td class="text-end fw-bold" style="color:#101B35; font-size:15px;">
                                                    ₹ <?= number_format($mq['total_amount'], 2) ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge rounded-pill bg-light text-dark border px-2 py-1">
                                                        <i class="ri-file-list-3-line me-1 text-primary"></i><?= (int)$mq['total_count'] ?> Slips
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-outline-primary py-1 px-3 rounded-pill" onclick="event.stopPropagation(); filterByMonth('<?= esc($mq['month_key']) ?>');">
                                                        <i class="ri-filter-3-line me-1"></i> View Transactions
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">No monthly contribution records available</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <?php echo view('include/member/footer.php') ?>
        </div>
    </div>

    <!-- DETAIL MODAL -->
    <div class="modal fade" id="mitbDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #101B35, #172B50); border-radius:16px 16px 0 0;">
                    <h6 class="modal-title text-white mb-0" id="detailModalTitle">
                        <i class="ri-file-list-3-line me-2 text-warning"></i> MITB Slip Details
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-3">
                        <span class="badge bg-success-subtle text-success border fs-6 px-3 py-2 rounded-pill fw-bold" id="detailModalAmount">₹ 0.00</span>
                    </div>
                    <div class="p-3 bg-light rounded-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Given By:</span>
                            <span class="fw-bold small text-dark" id="detailModalFrom">Member</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Received By:</span>
                            <span class="fw-bold small text-dark" id="detailModalTo">Member</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-muted small fw-bold">Business Description / Note:</label>
                        <p class="p-3 bg-white border rounded-3 mb-0" id="detailModalDesc" style="font-size:13px; min-height:60px;"></p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary btn-sm px-3 rounded-pill" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Vendor JS -->
    <script src="<?= base_url(); ?>assets2/libs/jquery/jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/metismenu/metisMenu.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/simplebar/simplebar.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/node-waves/waves.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/apexcharts/apexcharts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= base_url(); ?>assets2/js/app.js"></script>
    
    <!-- Include Add/Edit Popups -->
    <?php echo view('include/member/popup/mitb.php') ?>

    <script>
        // Global Chart Instance
        let mitbApexChart = null;

        // Initial Chart Data from PHP
        const initialChartCategories = <?= json_encode($chartCategories ?? []) ?>;
        const initialChartGiven      = <?= json_encode($chartGivenSeries ?? []) ?>;
        const initialChartReceived   = <?= json_encode($chartReceivedSeries ?? []) ?>;

        /* ================= 1. APEXCHARTS INITIALIZATION ================= */
        function initMitbApexChart(categories, givenData, receivedData) {
            const options = {
                series: [{
                    name: 'Business Received (Inbound)',
                    data: receivedData
                }, {
                    name: 'Business Given (Outbound)',
                    data: givenData
                }],
                chart: {
                    type: 'bar',
                    height: 290,
                    toolbar: { show: false },
                    fontFamily: 'Plus Jakarta Sans, Outfit, sans-serif',
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            const selectedCategory = config.w.config.xaxis.categories[config.dataPointIndex];
                            // Match month chip if clicked
                            $(".month-chip").each(function() {
                                if ($(this).find('.chip-month-title span').text().trim() === selectedCategory) {
                                    $(this).click();
                                }
                            });
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '42%',
                        borderRadius: 6,
                        dataLabels: { position: 'top' }
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                colors: ['#0D9488', '#C6A667'],
                xaxis: {
                    categories: categories,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            colors: '#64748B',
                            fontSize: '11.5px',
                            fontWeight: 600
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#64748B',
                            fontSize: '11px'
                        },
                        formatter: function(val) {
                            if (val >= 100000) {
                                return '₹ ' + (val / 100000).toFixed(1) + ' L';
                            } else if (val >= 1000) {
                                return '₹ ' + (val / 1000).toFixed(0) + ' k';
                            }
                            return '₹ ' + val;
                        }
                    }
                },
                grid: {
                    borderColor: '#F1F5F9',
                    strokeDashArray: 4
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontSize: '12px',
                    fontWeight: 600,
                    markers: { radius: 12 }
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function(val) {
                            return '₹ ' + Number(val).toLocaleString('en-IN');
                        }
                    }
                }
            };

            const chartEl = document.querySelector("#monthlyMitbApexChart");
            if (chartEl) {
                if (mitbApexChart) {
                    mitbApexChart.destroy();
                }
                mitbApexChart = new ApexCharts(chartEl, options);
                mitbApexChart.render();
            }
        }

        /* ================= 2. DEBOUNCE UTILITY ================= */
        function debounce(fn, delay) {
            let timer;
            return function() {
                const context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(() => fn.apply(context, args), delay);
            };
        }

        /* ================= 3. FILTER COLLECTOR ================= */
        function collectFilters() {
            return {
                search: $('#search').val(),
                category: $('#category').val(),
                business_type: $('#business_type').val(),
                chapter_code: $('#chapter_code').val(),
                month_filter: $('#month_filter').val(),
                specific_month: $('#filter_specific_month').val(),
                tab: $('#filter_active_tab').val(),
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val()
            };
        }

        /* ================= 4. DYNAMIC AJAX FILTERING ================= */
        const loadMitbData = debounce(function() {
            const filters = collectFilters();

            $.ajax({
                type: "POST",
                url: "<?= base_url('member/mitb/get_filtered_data') ?>",
                data: filters,
                dataType: "json",
                beforeSend: function() {
                    $("#mitb_table_body").html('<tr><td colspan="9" class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
                },
                success: function(res) {
                    if (res && res.status === 'success') {
                        // 1. Update Table Rows
                        $("#mitb_table_body").html(res.html);

                        // 2. Update Monthly Matrix Body if present
                        if (res.matrixHtml) {
                            $("#mitb_matrix_body").html(res.matrixHtml);
                        }

                        // 3. Update KPI cards dynamically
                        if (res.stats) {
                            $('#kpi-total-revenue').text(res.stats.totalRevenueFormatted);
                            $('#kpi-total-received').text(res.stats.totalReceivedFormatted);
                            $('#kpi-total-given').text(res.stats.totalGivenFormatted);
                            $('#kpi-avg-value').text(res.stats.avgMemberValueFormatted);
                            $('#kpi-records-badge').text(res.stats.totalRecords + ' Records');
                            $('#kpi-received-count-badge').text(res.stats.receivedCount + ' Slips');
                            $('#kpi-given-count-badge').text(res.stats.givenCount + ' Slips');

                            $('#tab-all-count').text(res.stats.totalRecords);
                            $('#tab-received-count').text(res.stats.receivedCount);
                            $('#tab-given-count').text(res.stats.givenCount);
                        }

                        // 4. Update ApexChart Series dynamically
                        if (res.chart && mitbApexChart) {
                            mitbApexChart.updateOptions({
                                xaxis: { categories: res.chart.categories }
                            });
                            mitbApexChart.updateSeries([{
                                name: 'Business Received (Inbound)',
                                data: res.chart.received
                            }, {
                                name: 'Business Given (Outbound)',
                                data: res.chart.given
                            }]);
                        }
                    } else if (res && res.html) {
                        $("#mitb_table_body").html(res.html);
                    }
                },
                error: function(xhr) {
                    $("#mitb_table_body").html('<tr><td colspan="9" class="text-danger text-center py-4">Failed to fetch data. Please try again.</td></tr>');
                }
            });
        }, 350);

        /* ================= 5. TAB SWITCHING ================= */
        function switchMitbTab(tabName) {
            $('.mitb-tab-btn').removeClass('active');
            $(`.mitb-tab-btn[data-tab="${tabName}"]`).addClass('active');
            $('#filter_active_tab').val(tabName);

            if (tabName === 'summary') {
                $('#tab-content-transactions').hide();
                $('#tab-content-monthly-matrix').fadeIn(200);
            } else {
                $('#tab-content-monthly-matrix').hide();
                $('#tab-content-transactions').fadeIn(200);
                loadMitbData();
            }
        }

        /* ================= 6. SPECIFIC MONTH FILTER ================= */
        function filterByMonth(monthKey) {
            $('#filter_specific_month').val(monthKey);

            $('.month-chip').removeClass('active');
            if (!monthKey) {
                $('.month-chip:first').addClass('active');
            } else {
                $(`.month-chip[data-month="${monthKey}"]`).addClass('active');
            }

            // Switch to transactions tab to see the details of that month
            if ($('#filter_active_tab').val() === 'summary') {
                switchMitbTab('all');
            } else {
                loadMitbData();
            }
        }

        /* ================= 7. MODAL DESCRIPTION VIEWER ================= */
        function showMitbDesc(code, desc, amount, fromName, toName) {
            $('#detailModalAmount').text('₹ ' + amount);
            $('#detailModalFrom').text(fromName);
            $('#detailModalTo').text(toName);
            $('#detailModalDesc').text(desc || 'No additional note provided for this transaction.');
            $('#mitbDetailModal').modal('show');
        }

        /* ================= 8. DOM READY BINDINGS ================= */
        $(document).ready(function() {
            // Initialize chart
            initMitbApexChart(initialChartCategories, initialChartGiven, initialChartReceived);

            // Filter inputs binding
            $('#search').on('keyup input', loadMitbData);
            $('#category, #business_type, #chapter_code, #month_filter, #from_date, #to_date').on('change', loadMitbData);

            // Reset filters
            $('#reset-btn').click(function() {
                $('#mitbFilterForm')[0].reset();
                $('#filter_specific_month').val('');
                $('.month-chip').removeClass('active');
                $('.month-chip:first').addClass('active');
                loadMitbData();
            });

            // Export Excel
            $('#export-excel').click(function() {
                let params = $.param(collectFilters());
                window.location = "<?= site_url('member/mitb/export_excel') ?>?" + params;
            });

            // Export PDF
            $('#export-pdf').click(function() {
                let params = $.param(collectFilters());
                window.location = "<?= site_url('member/mitb/export_pdf') ?>?" + params;
            });

            // Chart range buttons
            $('.chart-range-btn').click(function() {
                $('.chart-range-btn').removeClass('active');
                $(this).addClass('active');
                const range = $(this).data('range');
                // You can add range-specific endpoint call if required
            });
        });
    </script>
</body>

</html>