<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Weekly Slips</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body data-sidebar="colored">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php echo view('include/member/header.php') ?>
        <?php echo view('include/member/sidebar.php') ?>
        <div class="main-content">
            <div class="page-content">
                <div class="page-content py-4">

                    <!-- =========================================================
     WEEKLY REPORT CARD
========================================================= -->
                    <div class="d-flex justify-content-between align-items-center mb-">
                        <h2 class="fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Weekly Slips</h2>
                        <div>
                            <a href="<?= base_url('member/reports/download_weekly_report?start_date=' . $startDate . '&end_date=' . $endDate) ?>"
                                class="btn btn-sm btn-primary rounded-pill">
                                <i class="bi bi-download me-1"></i> Download PDF
                            </a>
                            <a href="<?= base_url('member/dashboard') ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="weekly-report-card">

                        <!-- ================= HEADER ================= -->
                        <div class="weekly-report-header">

                            <div class="report-title-area">

                                <div class="report-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>

                                <div>
                                    <span class="report-label">
                                        WEEKLY ACTIVITY REPORT
                                    </span>

                                    <h5>
                                        Performance Summary
                                    </h5>
                                </div>

                            </div>


                            <!-- DATE RANGE -->
                            <div class="report-date">

                                <i class="far fa-calendar-alt"></i>

                                <span>
                                    <?= date('d M Y', strtotime($startDate)) ?>
                                    <b>—</b>
                                    <?= date('d M Y', strtotime($endDate)) ?>
                                </span>

                            </div>

                        </div>


                        <!-- ================= SUMMARY STRIP ================= -->
                        <div class="report-summary">

                            <div class="summary-item">

                                <div class="summary-icon blue">
                                    <i class="fas fa-share-alt"></i>
                                </div>

                                <div>
                                    <span>CONNECTS GIVEN</span>
                                    <strong><?= $r_g_c ?? 0 ?></strong>
                                </div>

                            </div>


                            <div class="summary-item">

                                <div class="summary-icon purple">
                                    <i class="fas fa-reply"></i>
                                </div>

                                <div>
                                    <span>CONNECTS RECEIVED</span>
                                    <strong><?= $r_r_c ?? 0 ?></strong>
                                </div>

                            </div>


                            <div class="summary-item">

                                <div class="summary-icon green">
                                    <i class="fas fa-rupee-sign"></i>
                                </div>

                                <div>
                                    <span>MITB TOTAL</span>

                                    <strong>
                                        ₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))
                                                ->format(($totalMITBGiven ?? 0) + ($totalMITBReceived ?? 0)) ?>
                                    </strong>

                                </div>

                            </div>


                            <div class="summary-item">

                                <div class="summary-icon orange">
                                    <i class="fas fa-users"></i>
                                </div>

                                <div>
                                    <span>F2F MEETINGS</span>
                                    <strong><?= $meetingsCount ?? 0 ?></strong>
                                </div>

                            </div>

                        </div>


                        <!-- ================= TABLE ================= -->
                        <div class="report-table-wrapper">

                            <div class="report-table-heading">

                                <div>
                                    <h6>
                                        Activity Breakdown
                                    </h6>

                                    <span>
                                        Detailed activity recorded during this period
                                    </span>
                                </div>

                                <span class="record-count">
                                    <?= 7 ?> Activities
                                </span>

                            </div>


                            <div class="table-responsive">

                                <table class="table weekly-report-table align-middle mb-0">

                                    <thead>

                                        <tr>

                                            <th>
                                                Activity
                                            </th>

                                            <th class="text-center">
                                                Count / Amount
                                            </th>

                                            <th class="text-end">
                                                Status
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        <!-- Connects Given -->
                                        <tr>

                                            <td>
                                                <div class="activity-name">

                                                    <div class="activity-icon blue">
                                                        <i class="fas fa-share-alt"></i>
                                                    </div>

                                                    <div>
                                                        <strong>
                                                            Connects Given
                                                        </strong>

                                                        <small>
                                                            Connections shared with members
                                                        </small>
                                                    </div>

                                                </div>
                                            </td>

                                            <td class="text-center">

                                                <span class="metric-number blue-text">
                                                    <?= $r_g_c ?? 0 ?>
                                                </span>

                                            </td>

                                            <td class="text-end">

                                                <span class="report-status">
                                                    <i class="fas fa-check"></i>
                                                    Recorded
                                                </span>

                                            </td>

                                        </tr>


                                        <!-- Connects Received -->
                                        <tr>

                                            <td>
                                                <div class="activity-name">

                                                    <div class="activity-icon purple">
                                                        <i class="fas fa-reply"></i>
                                                    </div>

                                                    <div>
                                                        <strong>
                                                            Connects Received
                                                        </strong>

                                                        <small>
                                                            Connections received from members
                                                        </small>
                                                    </div>

                                                </div>
                                            </td>

                                            <td class="text-center">

                                                <span class="metric-number purple-text">
                                                    <?= $r_r_c ?? 0 ?>
                                                </span>

                                            </td>

                                            <td class="text-end">

                                                <span class="report-status">
                                                    <i class="fas fa-check"></i>
                                                    Recorded
                                                </span>

                                            </td>

                                        </tr>


                                        <!-- MITB Given -->
                                        <tr>

                                            <td>

                                                <div class="activity-name">

                                                    <div class="activity-icon green">
                                                        <i class="fas fa-arrow-up"></i>
                                                    </div>

                                                    <div>

                                                        <strong>
                                                            MITB Given
                                                        </strong>

                                                        <small>
                                                            Business generated for others
                                                        </small>

                                                    </div>

                                                </div>

                                            </td>

                                            <td class="text-center">

                                                <span class="metric-amount green-text">
                                                    ₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))
                                                            ->format($totalMITBGiven ?? 0) ?>
                                                </span>

                                            </td>

                                            <td class="text-end">

                                                <span class="report-status">
                                                    <i class="fas fa-check"></i>
                                                    Recorded
                                                </span>

                                            </td>

                                        </tr>


                                        <!-- MITB Received -->
                                        <tr>

                                            <td>

                                                <div class="activity-name">

                                                    <div class="activity-icon teal">
                                                        <i class="fas fa-arrow-down"></i>
                                                    </div>

                                                    <div>

                                                        <strong>
                                                            MITB Received
                                                        </strong>

                                                        <small>
                                                            Business received from members
                                                        </small>

                                                    </div>

                                                </div>

                                            </td>

                                            <td class="text-center">

                                                <span class="metric-amount teal-text">
                                                    ₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))
                                                            ->format($totalMITBReceived ?? 0) ?>
                                                </span>

                                            </td>

                                            <td class="text-end">

                                                <span class="report-status">
                                                    <i class="fas fa-check"></i>
                                                    Recorded
                                                </span>

                                            </td>

                                        </tr>


                                        <!-- F2F Meetings -->
                                        <tr>

                                            <td>

                                                <div class="activity-name">

                                                    <div class="activity-icon info">
                                                        <i class="fas fa-handshake"></i>
                                                    </div>

                                                    <div>

                                                        <strong>
                                                            F2F Meetings
                                                        </strong>

                                                        <small>
                                                            Face-to-face meetings attended
                                                        </small>

                                                    </div>

                                                </div>

                                            </td>

                                            <td class="text-center">

                                                <span class="metric-number info-text">
                                                    <?= $meetingsCount ?? 0 ?>
                                                </span>

                                            </td>

                                            <td class="text-end">

                                                <span class="report-status">
                                                    <i class="fas fa-check"></i>
                                                    Recorded
                                                </span>

                                            </td>

                                        </tr>


                                        <!-- Visitors -->
                                        <tr>

                                            <td>

                                                <div class="activity-name">

                                                    <div class="activity-icon orange">
                                                        <i class="fas fa-user-plus"></i>
                                                    </div>

                                                    <div>

                                                        <strong>
                                                            Verified Visitors
                                                        </strong>

                                                        <small>
                                                            Visitors successfully verified
                                                        </small>

                                                    </div>

                                                </div>

                                            </td>

                                            <td class="text-center">

                                                <span class="metric-number orange-text">
                                                    <?= $visitors_c ?? 0 ?>
                                                </span>

                                            </td>

                                            <td class="text-end">

                                                <span class="report-status">
                                                    <i class="fas fa-check"></i>
                                                    Recorded
                                                </span>

                                            </td>

                                        </tr>


                                        <!-- TOTAL -->
                                        <tr class="total-row">

                                            <td>

                                                <div class="activity-name">

                                                    <div class="activity-icon total">
                                                        <i class="fas fa-chart-pie"></i>
                                                    </div>

                                                    <div>

                                                        <strong>
                                                            Total MITB Amount
                                                        </strong>

                                                        <small>
                                                            Given + Received
                                                        </small>

                                                    </div>

                                                </div>

                                            </td>

                                            <td class="text-center">

                                                <span class="total-amount">

                                                    ₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))
                                                            ->format(
                                                                ($totalMITBGiven ?? 0) +
                                                                    ($totalMITBReceived ?? 0)
                                                            ) ?>

                                                </span>

                                            </td>

                                            <td class="text-end">

                                                <span class="total-status">
                                                    <i class="fas fa-check-circle"></i>
                                                    Total
                                                </span>

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>


                        <!-- ================= FOOTER NOTE ================= -->
                        <div class="report-footer">

                            <div class="report-note-icon">
                                <i class="fas fa-info"></i>
                            </div>

                            <div>

                                <strong>
                                    Weekly Report
                                </strong>

                                <p>
                                    This report covers your recorded activity for the selected
                                    period. You can download or print this summary to share
                                    during your chapter meeting.
                                </p>

                            </div>

                        </div>

                    </div>



                    <style>
                        /* =========================================================
       WEEKLY CRM REPORT - FULL CSS
    ========================================================= */

                        :root {
                            --report-primary: #0a3d61;
                            --report-primary-dark: #072f4b;
                            --report-primary-light: #eaf3f8;

                            --report-blue: #0a3d61;
                            --report-purple: #7653a8;
                            --report-green: #23915a;
                            --report-teal: #238888;
                            --report-orange: #c47b18;
                            --report-info: #2480ad;

                            --report-light: #f2f7fa;
                            --report-bg: #f8fafc;
                            --report-border: #dfe9ef;

                            --report-text: #263746;
                            --report-muted: #71818b;

                            --report-success-bg: #edf8f2;
                            --report-success: #238753;
                        }


                        /* =========================================================
       MAIN REPORT CARD
    ========================================================= */

                        .weekly-report-card {
                            background: #ffffff;
                            border: 1px solid var(--report-border);
                            border-radius: 16px;
                            overflow: hidden;

                            box-shadow:
                                0 6px 25px rgba(10, 61, 97, 0.07);

                            transition: all .25s ease;
                        }

                        .weekly-report-card:hover {
                            box-shadow:
                                0 10px 32px rgba(10, 61, 97, 0.10);
                        }


                        /* =========================================================
       HEADER
    ========================================================= */

                        .weekly-report-header {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;

                            gap: 20px;

                            padding: 20px 22px;

                            background:
                                linear-gradient(135deg,
                                    var(--report-primary-dark),
                                    var(--report-primary));

                            color: #ffffff;
                        }


                        .report-title-area {
                            display: flex;
                            align-items: center;
                            gap: 14px;
                        }


                        .report-icon {
                            width: 46px;
                            height: 46px;
                            min-width: 46px;

                            display: flex;
                            align-items: center;
                            justify-content: center;

                            background: rgba(255, 255, 255, .12);

                            border: 1px solid rgba(255, 255, 255, .18);

                            border-radius: 11px;

                            color: #ffffff;

                            font-size: 18px;
                        }


                        .report-label {
                            display: block;

                            margin-bottom: 3px;

                            color: rgba(255, 255, 255, .70);

                            font-size: 11px !important;

                            font-weight: 700;

                            letter-spacing: 1px;

                            text-transform: uppercase;
                        }


                        .report-title-area h5 {
                            margin: 0;

                            color: #ffffff;

                            font-size: 20px !important;

                            font-weight: 700;
                        }


                        /* =========================================================
       DATE RANGE
    ========================================================= */

                        .report-date {
                            display: flex;
                            align-items: center;

                            gap: 9px;

                            padding: 9px 14px;

                            background: rgba(255, 255, 255, .10);

                            border: 1px solid rgba(255, 255, 255, .15);

                            border-radius: 9px;

                            color: #ffffff;

                            font-size: 14px !important;

                            font-weight: 500;

                            white-space: nowrap;
                        }


                        .report-date i {
                            color: #c3dce9;

                            font-size: 14px;
                        }


                        .report-date b {
                            margin: 0 4px;

                            color: rgba(255, 255, 255, .50);
                        }


                        /* =========================================================
       SUMMARY SECTION
    ========================================================= */

                        .report-summary {
                            display: grid;

                            grid-template-columns: repeat(4, 1fr);

                            background: #fbfdfe;

                            border-bottom: 1px solid var(--report-border);
                        }


                        .summary-item {
                            display: flex;
                            align-items: center;

                            gap: 12px;

                            padding: 16px 18px;

                            border-right: 1px solid var(--report-border);

                            transition: background .2s ease;
                        }


                        .summary-item:hover {
                            background: #f5f9fb;
                        }


                        .summary-item:last-child {
                            border-right: 0;
                        }


                        .summary-icon {
                            width: 40px;
                            height: 40px;
                            min-width: 40px;

                            display: flex;
                            align-items: center;
                            justify-content: center;

                            border-radius: 9px;

                            font-size: 14px;
                        }


                        .summary-item span {
                            display: block;

                            margin-bottom: 3px;

                            color: var(--report-muted);

                            font-size: 10px !important;

                            font-weight: 700;

                            letter-spacing: .5px;
                        }


                        .summary-item strong {
                            display: block;

                            color: var(--report-text);

                            font-size: 20px !important;

                            line-height: 1.2;

                            font-weight: 700;
                        }


                        /* =========================================================
       COLOR UTILITIES
    ========================================================= */

                        .blue {
                            background: #edf5fa;
                            color: var(--report-blue);
                        }


                        .purple {
                            background: #f2eefb;
                            color: var(--report-purple);
                        }


                        .green {
                            background: #edf8f2;
                            color: var(--report-green);
                        }


                        .teal {
                            background: #edf8f8;
                            color: var(--report-teal);
                        }


                        .orange {
                            background: #fff5e9;
                            color: var(--report-orange);
                        }


                        .info {
                            background: #edf7fc;
                            color: var(--report-info);
                        }


                        .total {
                            background: #eaf3f8;
                            color: var(--report-primary);
                        }


                        .blue-text {
                            color: var(--report-blue) !important;
                        }


                        .purple-text {
                            color: var(--report-purple) !important;
                        }


                        .green-text {
                            color: var(--report-green) !important;
                        }


                        .teal-text {
                            color: var(--report-teal) !important;
                        }


                        .orange-text {
                            color: var(--report-orange) !important;
                        }


                        .info-text {
                            color: var(--report-info) !important;
                        }


                        /* =========================================================
       TABLE SECTION
    ========================================================= */

                        .report-table-wrapper {
                            padding: 20px 22px 10px;
                        }


                        .report-table-heading {
                            display: flex;

                            align-items: center;

                            justify-content: space-between;

                            gap: 12px;

                            margin-bottom: 14px;
                        }


                        .report-table-heading h6 {
                            margin: 0 0 4px;

                            color: var(--report-text);

                            font-size: 17px !important;

                            font-weight: 700;
                        }


                        .report-table-heading span {
                            color: var(--report-muted);

                            font-size: 12px !important;
                        }


                        .record-count {
                            display: inline-flex;

                            align-items: center;

                            padding: 6px 10px;

                            background: var(--report-light);

                            border: 1px solid #e1edf2;

                            border-radius: 7px;

                            color: var(--report-primary) !important;

                            font-size: 11px !important;

                            font-weight: 600;

                            white-space: nowrap;
                        }


                        /* =========================================================
       TABLE
    ========================================================= */

                        .weekly-report-table {
                            width: 100%;

                            border-collapse: separate;

                            border-spacing: 0;

                            border: 1px solid var(--report-border);

                            border-radius: 11px;

                            overflow: hidden;
                        }


                        .weekly-report-table thead th {
                            padding: 13px 15px;

                            background: #f4f7f9;

                            border-bottom: 1px solid var(--report-border);

                            color: #667984;

                            font-size: 11px !important;

                            font-weight: 700;

                            text-transform: uppercase;

                            letter-spacing: .5px;
                        }


                        .weekly-report-table tbody td {
                            padding: 14px 15px;

                            background: #ffffff;

                            border-bottom: 1px solid #edf2f5;

                            vertical-align: middle;
                        }


                        .weekly-report-table tbody tr:last-child td {
                            border-bottom: 0;
                        }


                        .weekly-report-table tbody tr {
                            transition:
                                background .2s ease,
                                transform .2s ease;
                        }


                        .weekly-report-table tbody tr:hover td {
                            background: #f9fbfc;
                        }


                        /* =========================================================
       ACTIVITY NAME
    ========================================================= */

                        .activity-name {
                            display: flex;

                            align-items: center;

                            gap: 12px;
                        }


                        .activity-icon {
                            width: 40px;
                            height: 40px;
                            min-width: 40px;

                            display: flex;
                            align-items: center;
                            justify-content: center;

                            border-radius: 9px;

                            font-size: 13px;
                        }


                        .activity-name strong {
                            display: block;

                            margin-bottom: 3px;

                            color: var(--report-text);

                            font-size: 14px !important;

                            font-weight: 650;
                        }


                        .activity-name small {
                            display: block;

                            color: var(--report-muted);

                            font-size: 11px !important;

                            line-height: 1.4;
                        }


                        /* =========================================================
       METRIC NUMBERS
    ========================================================= */

                        .metric-number {
                            display: inline-block;

                            font-size: 18px !important;

                            font-weight: 700;

                            line-height: 1.2;
                        }


                        .metric-amount {
                            display: inline-block;

                            font-size: 17px !important;

                            font-weight: 700;

                            white-space: nowrap;
                        }


                        /* =========================================================
       STATUS
    ========================================================= */

                        .report-status {
                            display: inline-flex;

                            align-items: center;

                            gap: 6px;

                            padding: 6px 11px;

                            background: var(--report-success-bg);

                            color: var(--report-success);

                            border: 1px solid #d9eee2;

                            border-radius: 20px;

                            font-size: 11px !important;

                            font-weight: 600;

                            white-space: nowrap;
                        }


                        .report-status i {
                            font-size: 10px !important;
                        }


                        /* =========================================================
       TOTAL ROW
    ========================================================= */

                        .weekly-report-table .total-row td {
                            background: #f0f6f9;

                            border-top: 2px solid #d5e4eb;

                            border-bottom: 0;
                        }


                        .weekly-report-table .total-row:hover td {
                            background: #f0f6f9;
                        }


                        .total-row .activity-name strong {
                            color: var(--report-primary);

                            font-size: 15px !important;
                        }


                        .total-row .activity-name small {
                            color: #6c7f8b;

                            font-size: 11px !important;
                        }


                        .total-amount {
                            color: var(--report-primary);

                            font-size: 20px !important;

                            font-weight: 800;

                            white-space: nowrap;
                        }


                        .total-status {
                            display: inline-flex;

                            align-items: center;

                            gap: 6px;

                            color: var(--report-primary);

                            font-size: 12px !important;

                            font-weight: 700;
                        }


                        .total-status i {
                            font-size: 13px;
                        }


                        /* =========================================================
       FOOTER NOTE
    ========================================================= */

                        .report-footer {
                            display: flex;

                            align-items: flex-start;

                            gap: 12px;

                            margin: 10px 22px 20px;

                            padding: 14px 16px;

                            background: #f6fafc;

                            border: 1px solid var(--report-border);

                            border-radius: 10px;
                        }


                        .report-note-icon {
                            width: 32px;
                            height: 32px;
                            min-width: 32px;

                            display: flex;
                            align-items: center;
                            justify-content: center;

                            background: var(--report-light);

                            color: var(--report-primary);

                            border-radius: 8px;

                            font-size: 12px !important;
                        }


                        .report-footer strong {
                            display: block;

                            margin-bottom: 3px;

                            color: var(--report-text);

                            font-size: 12px !important;

                            font-weight: 700;
                        }


                        .report-footer p {
                            margin: 0;

                            color: var(--report-muted);

                            font-size: 11px !important;

                            line-height: 1.6;
                        }


                        /* =========================================================
       RESPONSIVE - TABLET
    ========================================================= */

                        @media (max-width: 991px) {

                            .report-summary {
                                grid-template-columns: repeat(2, 1fr);
                            }


                            .summary-item:nth-child(2) {
                                border-right: 0;
                            }


                            .summary-item:nth-child(-n+2) {
                                border-bottom: 1px solid var(--report-border);
                            }

                        }


                        /* =========================================================
       RESPONSIVE - MOBILE
    ========================================================= */

                        @media (max-width: 767px) {

                            .weekly-report-card {
                                border-radius: 12px;
                            }


                            .weekly-report-header {
                                flex-direction: column;

                                align-items: flex-start;

                                padding: 17px;
                            }


                            .report-title-area h5 {
                                font-size: 18px !important;
                            }


                            .report-label {
                                font-size: 10px !important;
                            }


                            .report-date {
                                width: 100%;

                                justify-content: center;

                                font-size: 12px !important;
                            }


                            .report-summary {
                                grid-template-columns: 1fr 1fr;
                            }


                            .summary-item {
                                padding: 14px 12px;
                            }


                            .summary-icon {
                                width: 36px;
                                height: 36px;
                                min-width: 36px;
                            }


                            .summary-item span {
                                font-size: 9px !important;
                            }


                            .summary-item strong {
                                font-size: 18px !important;
                            }


                            .report-table-wrapper {
                                padding: 17px 12px 8px;
                            }


                            .report-table-heading {
                                align-items: flex-start;
                            }


                            .report-table-heading h6 {
                                font-size: 16px !important;
                            }


                            .report-table-heading span {
                                font-size: 11px !important;
                            }


                            .weekly-report-table {
                                min-width: 700px;
                            }


                            .weekly-report-table thead th {
                                font-size: 10px !important;
                            }


                            .activity-name strong {
                                font-size: 13px !important;
                            }


                            .activity-name small {
                                font-size: 10px !important;
                            }


                            .metric-number {
                                font-size: 17px !important;
                            }


                            .metric-amount {
                                font-size: 16px !important;
                            }


                            .report-status {
                                font-size: 10px !important;
                            }


                            .total-amount {
                                font-size: 18px !important;
                            }


                            .total-status {
                                font-size: 11px !important;
                            }


                            .report-footer {
                                margin: 9px 12px 15px;

                                padding: 13px;
                            }


                            .report-footer strong {
                                font-size: 11px !important;
                            }


                            .report-footer p {
                                font-size: 10px !important;
                            }

                        }


                        /* =========================================================
       SMALL MOBILE
    ========================================================= */

                        @media (max-width: 480px) {

                            .weekly-report-header {
                                padding: 15px;
                            }


                            .report-icon {
                                width: 42px;
                                height: 42px;
                                min-width: 42px;
                            }


                            .report-title-area h5 {
                                font-size: 16px !important;
                            }


                            .report-summary {
                                grid-template-columns: 1fr;
                            }


                            .summary-item {
                                border-right: 0 !important;

                                border-bottom: 1px solid var(--report-border);
                            }


                            .summary-item:last-child {
                                border-bottom: 0;
                            }


                            .summary-item strong {
                                font-size: 19px !important;
                            }


                            .report-date {
                                font-size: 11px !important;
                            }


                            .report-table-heading {
                                flex-direction: column;
                            }


                            .record-count {
                                font-size: 10px !important;
                            }


                            .report-footer {
                                align-items: flex-start;
                            }

                        }
                    </style>




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
</body>

</html>