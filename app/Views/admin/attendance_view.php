<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Chapter Attendance Report</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --crm-primary: #0d6efd;
            --crm-dark: #1f2937;
            --crm-muted: #6b7280;
            --crm-border: #e5e7eb;
            --crm-bg: #f5f7fb;

            --present: #16a34a;
            --absent: #dc2626;
            --medical: #d97706;
            --late: #ea580c;
            --substitute: #7c3aed;
        }


        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            background: var(--crm-bg);

            color: var(--crm-dark);

            font-family:
                Inter,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Arial,
                sans-serif;

            font-size: 14px;
        }


        /* =========================================================
           MAIN CONTAINER
        ========================================================= */

        .crm-report {

            max-width: 1500px;

            margin: 25px auto;

            padding: 0 18px 30px;
        }


        /* =========================================================
           HEADER
        ========================================================= */

        .report-header {

            background: #ffffff;

            border: 1px solid var(--crm-border);

            border-radius: 12px;

            padding: 20px 22px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 15px;

            box-shadow:
                0 2px 8px rgba(15, 23, 42, 0.04);
        }


        .header-left {

            display: flex;

            align-items: center;

            gap: 14px;
        }


        .header-icon {

            width: 48px;

            height: 48px;

            border-radius: 10px;

            background: #eff6ff;

            color: var(--crm-primary);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 20px;

            flex-shrink: 0;
        }


        .report-title {

            margin: 0;

            font-size: 21px;

            font-weight: 700;

            color: #111827;
        }


        .report-subtitle {

            margin-top: 3px;

            color: var(--crm-muted);

            font-size: 13px;
        }


        .header-actions {

            display: flex;

            gap: 8px;

            flex-wrap: wrap;
        }


        .header-actions .btn {

            border-radius: 7px;

            font-size: 13px;

            font-weight: 600;

            padding: 8px 14px;
        }


        /* =========================================================
           FILTER BAR
        ========================================================= */

        .filter-panel {

            background: #ffffff;

            border: 1px solid var(--crm-border);

            border-radius: 12px;

            padding: 15px 18px;

            margin-bottom: 15px;

            box-shadow:
                0 2px 8px rgba(15, 23, 42, 0.03);
        }


        .filter-title {

            display: flex;

            align-items: center;

            gap: 7px;

            font-size: 13px;

            font-weight: 700;

            color: #374151;

            margin-bottom: 10px;
        }


        .filter-title i {

            color: var(--crm-primary);
        }


        .filter-chips {

            display: flex;

            flex-wrap: wrap;

            gap: 7px;
        }


        .filter-chip {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            background: #f8fafc;

            border: 1px solid #e2e8f0;

            border-radius: 6px;

            padding: 6px 10px;

            color: #475569;

            font-size: 12px;
        }


        .filter-chip strong {

            color: #111827;

            font-weight: 600;
        }


        .filter-chip i {

            color: #64748b;

            font-size: 11px;
        }


        .all-records {

            color: #64748b;

            font-size: 13px;
        }


        /* =========================================================
           KPI CARDS
        ========================================================= */

        .kpi-grid {

            display: grid;

            grid-template-columns:
                repeat(6, minmax(0, 1fr));

            gap: 12px;

            margin-bottom: 15px;
        }


        .kpi-card {

            position: relative;

            background: #ffffff;

            border: 1px solid var(--crm-border);

            border-radius: 10px;

            padding: 15px;

            min-height: 95px;

            overflow: hidden;

            box-shadow:
                0 2px 7px rgba(15, 23, 42, 0.03);

            transition:
                transform .2s ease,
                box-shadow .2s ease;
        }


        .kpi-card:hover {

            transform: translateY(-2px);

            box-shadow:
                0 6px 18px rgba(15, 23, 42, 0.07);
        }


        .kpi-top {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 10px;
        }


        .kpi-label {

            color: #6b7280;

            font-size: 12px;

            font-weight: 600;

            text-transform: uppercase;

            letter-spacing: .3px;
        }


        .kpi-icon {

            width: 32px;

            height: 32px;

            border-radius: 8px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 13px;
        }


        .kpi-value {

            display: block;

            margin-top: 9px;

            font-size: 25px;

            line-height: 1;

            font-weight: 700;

            color: #111827;
        }


        .kpi-total .kpi-icon {

            background: #eff6ff;

            color: #2563eb;
        }


        .kpi-present {

            border-left: 3px solid var(--present);
        }


        .kpi-present .kpi-icon {

            background: #ecfdf5;

            color: var(--present);
        }


        .kpi-absent {

            border-left: 3px solid var(--absent);
        }


        .kpi-absent .kpi-icon {

            background: #fef2f2;

            color: var(--absent);
        }


        .kpi-medical {

            border-left: 3px solid var(--medical);
        }


        .kpi-medical .kpi-icon {

            background: #fffbeb;

            color: var(--medical);
        }


        .kpi-late {

            border-left: 3px solid var(--late);
        }


        .kpi-late .kpi-icon {

            background: #fff7ed;

            color: var(--late);
        }


        .kpi-substitute {

            border-left: 3px solid var(--substitute);
        }


        .kpi-substitute .kpi-icon {

            background: #f5f3ff;

            color: var(--substitute);
        }


        /* =========================================================
           TABLE CARD
        ========================================================= */

        .table-card {

            background: #ffffff;

            border: 1px solid var(--crm-border);

            border-radius: 12px;

            overflow: hidden;

            box-shadow:
                0 2px 8px rgba(15, 23, 42, 0.04);
        }


        .table-card-header {

            padding: 14px 18px;

            border-bottom: 1px solid var(--crm-border);

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 10px;
        }


        .table-title {

            display: flex;

            align-items: center;

            gap: 8px;

            font-size: 14px;

            font-weight: 700;

            color: #1f2937;
        }


        .table-title i {

            color: var(--crm-primary);
        }


        .record-count {

            background: #f1f5f9;

            border: 1px solid #e2e8f0;

            color: #475569;

            border-radius: 20px;

            padding: 4px 9px;

            font-size: 11px;

            font-weight: 600;
        }


        .table-responsive {

            overflow-x: auto;
        }


        .attendance-table {

            width: 100%;

            min-width: 950px;

            border-collapse: separate;

            border-spacing: 0;
        }


        .attendance-table thead th {

            background: #f8fafc;

            color: #64748b;

            font-size: 11px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .35px;

            white-space: nowrap;

            padding: 12px 14px;

            border-bottom: 1px solid #e5e7eb;

            text-align: left;
        }


        .attendance-table tbody td {

            padding: 13px 14px;

            border-bottom: 1px solid #f1f5f9;

            vertical-align: middle;

            color: #374151;

            font-size: 13px;
        }


        .attendance-table tbody tr {

            transition: background .15s ease;
        }


        .attendance-table tbody tr:hover {

            background: #f8fafc;
        }


        .attendance-table tbody tr:last-child td {

            border-bottom: 0;
        }


        .serial {

            width: 50px;

            color: #94a3b8;

            font-weight: 600;
        }


        /* =========================================================
           MEMBER
        ========================================================= */

        .member-cell {

            display: flex;

            align-items: center;

            gap: 10px;

            min-width: 200px;
        }


        .member-avatar {

            width: 36px;

            height: 36px;

            border-radius: 50%;

            background: #eff6ff;

            color: #2563eb;

            display: flex;

            align-items: center;

            justify-content: center;

            font-weight: 700;

            font-size: 13px;

            flex-shrink: 0;

            overflow: hidden;
        }


        .member-avatar img {

            width: 100%;

            height: 100%;

            object-fit: cover;
        }


        .member-name {

            font-weight: 600;

            color: #111827;

            line-height: 1.2;
        }


        .member-email {

            margin-top: 3px;

            color: #94a3b8;

            font-size: 11px;
        }


        /* =========================================================
           EVENT
        ========================================================= */

        .event-name {

            font-weight: 600;

            color: #334155;
        }


        .chapter-name {

            color: #64748b;

            font-size: 12px;
        }


        .date-cell {

            white-space: nowrap;

            color: #475569;

            font-weight: 500;
        }


        .date-icon {

            color: #94a3b8;

            margin-right: 5px;
        }


        /* =========================================================
           STATUS
        ========================================================= */

        .status-badge {

            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 5px 9px;

            border-radius: 6px;

            font-size: 11px;

            font-weight: 700;

            white-space: nowrap;
        }


        .status-present {

            background: #ecfdf5;

            color: #15803d;
        }


        .status-absent {

            background: #fef2f2;

            color: #b91c1c;
        }


        .status-medical {

            background: #fffbeb;

            color: #a16207;
        }


        .status-late {

            background: #fff7ed;

            color: #c2410c;
        }


        .status-substitute {

            background: #f5f3ff;

            color: #6d28d9;
        }


        .status-default {

            background: #f1f5f9;

            color: #475569;
        }


        /* =========================================================
           NOTE
        ========================================================= */

        .note-cell {

            max-width: 220px;

            color: #64748b;

            font-size: 12px;

            line-height: 1.4;
        }


        /* =========================================================
           EMPTY
        ========================================================= */

        .empty-state {

            padding: 65px 20px;

            text-align: center;
        }


        .empty-icon {

            width: 64px;

            height: 64px;

            margin: 0 auto 15px;

            border-radius: 50%;

            background: #f1f5f9;

            color: #94a3b8;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 24px;
        }


        .empty-state h5 {

            margin-bottom: 5px;

            font-size: 16px;

            font-weight: 700;

            color: #374151;
        }


        .empty-state p {

            color: #94a3b8;

            margin: 0;

            font-size: 13px;
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .report-footer {

            padding: 12px 18px;

            border-top: 1px solid var(--crm-border);

            color: #94a3b8;

            font-size: 11px;

            display: flex;

            justify-content: space-between;

            gap: 10px;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media(max-width: 1200px) {

            .kpi-grid {

                grid-template-columns:
                    repeat(3, 1fr);
            }
        }


        @media(max-width: 768px) {

            .crm-report {

                margin: 10px auto;

                padding:
                    0 10px 20px;
            }


            .report-header {

                display: block;

                padding: 16px;
            }


            .header-actions {

                margin-top: 15px;
            }


            .header-actions .btn {

                flex: 1;
            }


            .kpi-grid {

                grid-template-columns:
                    repeat(2, 1fr);

                gap: 8px;
            }


            .kpi-card {

                padding: 12px;
            }


            .kpi-value {

                font-size: 21px;
            }


            .filter-panel {

                padding: 13px;
            }


            .table-card-header {

                padding: 12px;
            }


            .report-title {

                font-size: 18px;
            }
        }


        @media(max-width: 450px) {

            .kpi-grid {

                grid-template-columns: 1fr 1fr;
            }


            .header-left {

                align-items: flex-start;
            }


            .header-icon {

                width: 40px;

                height: 40px;

                font-size: 16px;
            }
        }


        /* =========================================================
           PRINT
        ========================================================= */

        @media print {

            body {

                background: #fff !important;

                font-size: 11px;
            }


            .crm-report {

                max-width: 100%;

                margin: 0;

                padding: 0;
            }


            .report-header,
            .filter-panel,
            .kpi-card,
            .table-card {

                box-shadow: none;
            }


            .report-header {

                border: 0;

                border-bottom:
                    1px solid #ddd;

                border-radius: 0;

                padding: 10px 0;
            }


            .header-actions {

                display: none !important;
            }


            .filter-panel {

                border-radius: 0;

                margin-top: 10px;
            }


            .kpi-grid {

                grid-template-columns:
                    repeat(6, 1fr);

                gap: 5px;
            }


            .kpi-card {

                min-height: auto;

                padding: 8px;
            }


            .kpi-icon {

                display: none;
            }


            .kpi-value {

                font-size: 17px;
            }


            .table-card {

                border-radius: 0;

                border: 0;
            }


            .table-card-header {

                padding:
                    10px 0;
            }


            .attendance-table {

                min-width: 100%;

                font-size: 10px;
            }


            .attendance-table thead th,
            .attendance-table tbody td {

                padding: 7px;
            }


            .report-footer {

                padding:
                    8px 0;
            }
        }
    </style>

</head>


<body>


    <div class="crm-report">


        <!-- =========================================================
         HEADER
    ========================================================== -->

        <div class="report-header">

            <div class="header-left">

                <div class="header-icon">

                    <i class="fa-solid fa-calendar-check"></i>

                </div>


                <div>

                    <h1 class="report-title">

                        Chapter Attendance Report

                    </h1>

                    <div class="report-subtitle">

                        Attendance overview and member activity

                    </div>

                </div>

            </div>


            <div class="header-actions">

                <button
                    type="button"
                    id="printAttendanceBtn"
                    class="btn btn-primary">

                    <i class="fa-solid fa-print me-1"></i>

                    Print Report

                </button>

            </div>

        </div>



        <!-- =========================================================
         FILTER INFORMATION
    ========================================================== -->

        <div class="filter-panel">

            <div class="filter-title">

                <i class="fa-solid fa-filter"></i>

                Applied Filters

            </div>


            <div class="filter-chips">


                <?php if (!empty($filters['search'])): ?>

                    <div class="filter-chip">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        Search:

                        <strong>
                            <?= esc($filters['search']) ?>
                        </strong>

                    </div>

                <?php endif; ?>


                <?php if (!empty($filters['to_user'])): ?>

                    <div class="filter-chip">

                        <i class="fa-solid fa-user"></i>

                        Member:

                        <strong>
                            <?= esc($filters['to_user']) ?>
                        </strong>

                    </div>

                <?php endif; ?>


                <?php if (!empty($filters['status'])): ?>

                    <div class="filter-chip">

                        <i class="fa-solid fa-circle-check"></i>

                        Status:

                        <strong>
                            <?= esc(
                                ucfirst(
                                    $filters['status']
                                )
                            ) ?>
                        </strong>

                    </div>

                <?php endif; ?>


                <?php if (!empty($filters['date'])): ?>

                    <div class="filter-chip">

                        <i class="fa-solid fa-calendar-day"></i>

                        Date:

                        <strong>
                            <?= esc($filters['date']) ?>
                        </strong>

                    </div>

                <?php endif; ?>


                <?php if (!empty($filters['month'])): ?>

                    <div class="filter-chip">

                        <i class="fa-solid fa-calendar-days"></i>

                        Month:

                        <strong>
                            <?= esc($filters['month']) ?>
                        </strong>

                    </div>

                <?php endif; ?>


                <?php if (
                    empty($filters['search']) &&
                    empty($filters['to_user']) &&
                    empty($filters['status']) &&
                    empty($filters['date']) &&
                    empty($filters['month'])
                ): ?>

                    <span class="all-records">

                        <i class="fa-solid fa-layer-group me-1"></i>

                        All attendance records

                    </span>

                <?php endif; ?>

            </div>

        </div>



        <?php

        /*
    |--------------------------------------------------------------------------
    | CALCULATE SUMMARY
    |--------------------------------------------------------------------------
    */

        $totalRecords = count($attendance);

        $present = 0;
        $absent = 0;
        $medical = 0;
        $late = 0;
        $substitute = 0;

        foreach ($attendance as $row) {

            $status =
                strtolower(
                    trim(
                        (string) ($row->status ?? '')
                    )
                );

            switch ($status) {

                case 'present':
                    $present++;
                    break;

                case 'absent':
                    $absent++;
                    break;

                case 'medical':
                    $medical++;
                    break;

                case 'late':
                    $late++;
                    break;

                case 'substitute':
                    $substitute++;
                    break;
            }
        }

        ?>


        <!-- =========================================================
         KPI SUMMARY
    ========================================================== -->

        <div class="kpi-grid">


            <!-- TOTAL -->

            <div class="kpi-card kpi-total">

                <div class="kpi-top">

                    <span class="kpi-label">
                        Total Records
                    </span>

                    <div class="kpi-icon">

                        <i class="fa-solid fa-layer-group"></i>

                    </div>

                </div>

                <span class="kpi-value">

                    <?= $totalRecords ?>

                </span>

            </div>


            <!-- PRESENT -->

            <div class="kpi-card kpi-present">

                <div class="kpi-top">

                    <span class="kpi-label">
                        Present
                    </span>

                    <div class="kpi-icon">

                        <i class="fa-solid fa-check"></i>

                    </div>

                </div>

                <span class="kpi-value">

                    <?= $present ?>

                </span>

            </div>


            <!-- ABSENT -->

            <div class="kpi-card kpi-absent">

                <div class="kpi-top">

                    <span class="kpi-label">
                        Absent
                    </span>

                    <div class="kpi-icon">

                        <i class="fa-solid fa-xmark"></i>

                    </div>

                </div>

                <span class="kpi-value">

                    <?= $absent ?>

                </span>

            </div>


            <!-- MEDICAL -->

            <div class="kpi-card kpi-medical">

                <div class="kpi-top">

                    <span class="kpi-label">
                        Medical
                    </span>

                    <div class="kpi-icon">

                        <i class="fa-solid fa-notes-medical"></i>

                    </div>

                </div>

                <span class="kpi-value">

                    <?= $medical ?>

                </span>

            </div>


            <!-- LATE -->

            <div class="kpi-card kpi-late">

                <div class="kpi-top">

                    <span class="kpi-label">
                        Late
                    </span>

                    <div class="kpi-icon">

                        <i class="fa-solid fa-clock"></i>

                    </div>

                </div>

                <span class="kpi-value">

                    <?= $late ?>

                </span>

            </div>


            <!-- SUBSTITUTE -->

            <div class="kpi-card kpi-substitute">

                <div class="kpi-top">

                    <span class="kpi-label">
                        Substitute
                    </span>

                    <div class="kpi-icon">

                        <i class="fa-solid fa-user-clock"></i>

                    </div>

                </div>

                <span class="kpi-value">

                    <?= $substitute ?>

                </span>

            </div>

        </div>



        <!-- =========================================================
         ATTENDANCE TABLE
    ========================================================== -->

        <div class="table-card">


            <div class="table-card-header">

                <div class="table-title">

                    <i class="fa-solid fa-users"></i>

                    Attendance Details

                </div>


                <div class="record-count">

                    <?= $totalRecords ?> Records

                </div>

            </div>


            <?php if (!empty($attendance)): ?>


                <div class="table-responsive">

                    <table class="attendance-table">


                        <thead>

                            <tr>

                                <th class="serial">
                                    #
                                </th>

                                <th>
                                    Member
                                </th>

                                <th>
                                    Meeting / Event
                                </th>

                                <th>
                                    Chapter
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Note
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php $serial = 1; ?>


                            <?php foreach ($attendance as $a): ?>


                                <?php

                                $statusValue =
                                    strtolower(
                                        trim(
                                            (string)
                                            ($a->status ?? '')
                                        )
                                    );


                                switch ($statusValue) {

                                    case 'present':

                                        $statusClass =
                                            'status-present';

                                        $statusIcon =
                                            'fa-check';

                                        break;


                                    case 'absent':

                                        $statusClass =
                                            'status-absent';

                                        $statusIcon =
                                            'fa-xmark';

                                        break;


                                    case 'medical':

                                        $statusClass =
                                            'status-medical';

                                        $statusIcon =
                                            'fa-notes-medical';

                                        break;


                                    case 'late':

                                        $statusClass =
                                            'status-late';

                                        $statusIcon =
                                            'fa-clock';

                                        break;


                                    case 'substitute':

                                        $statusClass =
                                            'status-substitute';

                                        $statusIcon =
                                            'fa-user-clock';

                                        break;


                                    default:

                                        $statusClass =
                                            'status-default';

                                        $statusIcon =
                                            'fa-circle';

                                        break;
                                }


                                $memberName =
                                    trim(
                                        (string)
                                        ($a->name ?? '')
                                    );


                                $initial =
                                    strtoupper(
                                        substr(
                                            $memberName,
                                            0,
                                            1
                                        )
                                    );

                                ?>


                                <tr>


                                    <!-- SERIAL -->

                                    <td class="serial">

                                        <?= $serial++ ?>

                                    </td>


                                    <!-- MEMBER -->

                                    <td>

                                        <div class="member-cell">


                                            <div class="member-avatar">


                                                <?php if (
                                                    !empty($a->profile_photo)
                                                ): ?>

                                                    <img
                                                        src="<?= base_url(
                                                                    'uploads/profile/' .
                                                                        $a->profile_photo
                                                                ) ?>"
                                                        alt="<?= esc(
                                                                    $memberName
                                                                ) ?>">

                                                <?php else: ?>

                                                    <?= esc(
                                                        $initial ?: '?'
                                                    ) ?>

                                                <?php endif; ?>


                                            </div>


                                            <div>

                                                <div class="member-name">

                                                    <?= esc(
                                                        $memberName
                                                    ) ?>

                                                </div>


                                                <?php if (
                                                    !empty($a->email)
                                                ): ?>

                                                    <div class="member-email">

                                                        <?= esc(
                                                            $a->email
                                                        ) ?>

                                                    </div>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    </td>


                                    <!-- EVENT -->

                                    <td>

                                        <div class="event-name">

                                            <?= esc(
                                                $a->event_title ?? ''
                                            ) ?>

                                        </div>

                                    </td>


                                    <!-- CHAPTER -->

                                    <td>

                                        <div class="chapter-name">

                                            <i class="fa-solid fa-building-columns me-1"></i>

                                            <?= esc(
                                                $a->chapter_name ?? ''
                                            ) ?>

                                        </div>

                                    </td>


                                    <!-- DATE -->

                                    <td class="date-cell">

                                        <?php if (
                                            !empty($a->event_date)
                                        ): ?>

                                            <i class="fa-regular fa-calendar date-icon"></i>

                                            <?= date(
                                                'd M Y',
                                                strtotime(
                                                    $a->event_date
                                                )
                                            ) ?>

                                        <?php else: ?>

                                            -

                                        <?php endif; ?>

                                    </td>


                                    <!-- STATUS -->

                                    <td>

                                        <span
                                            class="status-badge <?= $statusClass ?>">

                                            <i
                                                class="fa-solid <?= $statusIcon ?>">
                                            </i>

                                            <?= esc(
                                                ucfirst(
                                                    $statusValue
                                                )
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- NOTE -->

                                    <td>

                                        <div class="note-cell">

                                            <?php if (
                                                !empty($a->note)
                                            ): ?>

                                                <?= esc(
                                                    $a->note
                                                ) ?>

                                            <?php else: ?>

                                                <span
                                                    class="text-muted">
                                                    —
                                                </span>

                                            <?php endif; ?>

                                        </div>

                                    </td>


                                </tr>


                            <?php endforeach; ?>


                        </tbody>

                    </table>

                </div>


            <?php else: ?>


                <div class="empty-state">

                    <div class="empty-icon">

                        <i class="fa-regular fa-calendar-xmark"></i>

                    </div>


                    <h5>
                        No Attendance Records
                    </h5>


                    <p>

                        No attendance records match
                        the selected filters.

                    </p>

                </div>


            <?php endif; ?>


            <!-- FOOTER -->

            <div class="report-footer">

                <span>

                    <i class="fa-solid fa-circle-info me-1"></i>

                    Chapter Attendance Management

                </span>


                <span>

                    Generated:
                    <?= date('d M Y, h:i A') ?>

                </span>

            </div>


        </div>


    </div>



    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                const printBtn =
                    document.getElementById(
                        'printAttendanceBtn'
                    );


                if (printBtn) {

                    printBtn.addEventListener(
                        'click',
                        function() {

                            window.print();

                        }
                    );

                }

            }
        );
    </script>


</body>

</html>