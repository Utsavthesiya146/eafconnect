<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title><?= esc($reportTitle ?? 'Attendance Report') ?></title>

    <style>
        @page {
            margin: 25px 20px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #222;
            font-size: 10px;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-left {
            width: 70%;
        }

        .header-right {
            width: 30%;
            text-align: right;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .subtitle {
            margin-top: 5px;
            color: #666;
            font-size: 10px;
        }

        .date-display {
            font-size: 11px;
            font-weight: bold;
            color: #2563eb;
        }

        .generated {
            margin-top: 5px;
            color: #777;
            font-size: 8px;
        }


        /* SUMMARY */

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .summary-table td {
            width: 16.66%;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .summary-label {
            font-size: 8px;
            color: #777;
            text-transform: uppercase;
        }

        .summary-value {
            margin-top: 4px;
            font-size: 16px;
            font-weight: bold;
        }


        /* FILTER */

        .filter-box {
            border: 1px solid #ddd;
            background: #f8fafc;
            padding: 8px 10px;
            margin-bottom: 15px;
        }

        .filter-title {
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 5px;
        }

        .filter-text {
            color: #555;
            font-size: 8px;
        }


        /* TABLE */

        table.report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th {
            background: #2563eb;
            color: #fff;
            border: 1px solid #1d4ed8;
            padding: 7px 5px;
            font-size: 8px;
            text-align: center;
        }

        .report-table td {
            border: 1px solid #ddd;
            padding: 7px 5px;
            font-size: 8px;
            vertical-align: middle;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }


        /* STATUS */

        .status {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 7px;
        }

        .present {
            background: #dcfce7;
            color: #166534;
        }

        .absent {
            background: #fee2e2;
            color: #991b1b;
        }

        .late {
            background: #fef3c7;
            color: #92400e;
        }

        .medical {
            background: #e0f2fe;
            color: #075985;
        }

        .substitute {
            background: #ede9fe;
            color: #5b21b6;
        }

        .other {
            background: #f3f4f6;
            color: #374151;
        }


        /* EMPTY */

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }


        /* FOOTER */

        .footer {
            margin-top: 18px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            font-size: 8px;
            color: #888;
        }
    </style>

</head>


<body>


    <!-- HEADER -->

    <div class="header">

        <table class="header-table">

            <tr>

                <td class="header-left">

                    <div class="title">
                        <?= esc($reportTitle ?? 'Attendance Report') ?>
                    </div>

                    <div class="subtitle">
                        Member attendance and meeting statistics
                    </div>

                </td>


                <td class="header-right">

                    <div class="date-display">
                        <?= esc($dateDisplay ?? 'All Dates') ?>
                    </div>

                    <div class="generated">
                        Generated:
                        <?= date('d M Y h:i A') ?>
                    </div>

                </td>

            </tr>

        </table>

    </div>


    <!-- FILTER SUMMARY -->

    <?php

    $filters = [];

    if (!empty($search)) {
        $filters[] = 'Search: ' . $search;
    }

    if (!empty($memberCode)) {
        $filters[] = 'Member: ' . $memberCode;
    }

    if (!empty($status)) {
        $filters[] = 'Status: ' . ucfirst($status);
    }

    if (!empty($fromDate)) {
        $filters[] = 'From: ' . date('d-m-Y', strtotime($fromDate));
    }

    if (!empty($toDate)) {
        $filters[] = 'To: ' . date('d-m-Y', strtotime($toDate));
    }

    if (!empty($month)) {
        $filters[] = 'Month: ' . date('F Y', strtotime($month . '-01'));
    }

    ?>


    <?php if (!empty($filters)): ?>

        <div class="filter-box">

            <div class="filter-title">
                Applied Filters
            </div>

            <div class="filter-text">

                <?= esc(implode(' | ', $filters)) ?>

            </div>

        </div>

    <?php endif; ?>


    <!-- SUMMARY -->

    <table class="summary-table">

        <tr>

            <td>

                <div class="summary-label">
                    Total Records
                </div>

                <div class="summary-value">
                    <?= (int) ($totalRecords ?? 0) ?>
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Present
                </div>

                <div class="summary-value">
                    <?= (int) ($presentCount ?? 0) ?>
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Absent
                </div>

                <div class="summary-value">
                    <?= (int) ($absentCount ?? 0) ?>
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Late
                </div>

                <div class="summary-value">
                    <?= (int) ($lateCount ?? 0) ?>
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Medical
                </div>

                <div class="summary-value">
                    <?= (int) ($medicalCount ?? 0) ?>
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Substitute
                </div>

                <div class="summary-value">
                    <?= (int) ($substituteCount ?? 0) ?>
                </div>

            </td>

        </tr>

    </table>


    <!-- ATTENDANCE TABLE -->

    <table class="report-table">

        <thead>

            <tr>

                <th width="4%">
                    #
                </th>

                <th width="15%">
                    Member
                </th>

                <th width="13%">
                    Chapter
                </th>

                <th width="18%">
                    Meeting
                </th>

                <th width="10%">
                    Date
                </th>

                <th width="8%">
                    Status
                </th>

                <th width="22%">
                    Note
                </th>

                <th width="10%">
                    Role
                </th>

            </tr>

        </thead>


        <tbody>

            <?php if (!empty($attendance)): ?>

                <?php $i = 1; ?>

                <?php foreach ($attendance as $row): ?>

                    <?php

                    $currentStatus = strtolower(
                        trim(
                            (string) ($row['status'] ?? '')
                        )
                    );

                    $statusClass = 'other';

                    if ($currentStatus === 'present') {
                        $statusClass = 'present';
                    } elseif ($currentStatus === 'absent') {
                        $statusClass = 'absent';
                    } elseif ($currentStatus === 'late') {
                        $statusClass = 'late';
                    } elseif ($currentStatus === 'medical') {
                        $statusClass = 'medical';
                    } elseif ($currentStatus === 'substitute') {
                        $statusClass = 'substitute';
                    }

                    ?>

                    <tr>

                        <td class="text-center">
                            <?= $i++ ?>
                        </td>


                        <td class="text-left">

                            <strong>
                                <?= esc($row['name'] ?? '-') ?>
                            </strong>

                            <?php if (!empty($row['email'])): ?>

                                <br>

                                <span style="color:#777;">
                                    <?= esc($row['email']) ?>
                                </span>

                            <?php endif; ?>

                        </td>


                        <td class="text-left">

                            <?= esc(
                                $row['chapter_name']
                                    ?? '-'
                            ) ?>

                        </td>


                        <td class="text-left">

                            <?= esc(
                                $row['event_title']
                                    ?? '-'
                            ) ?>

                        </td>


                        <td class="text-center">

                            <?php if (!empty($row['event_date'])): ?>

                                <?= date(
                                    'd-m-Y',
                                    strtotime($row['event_date'])
                                ) ?>

                                <br>

                                <span style="color:#777;">
                                    <?= date(
                                        'h:i A',
                                        strtotime($row['event_date'])
                                    ) ?>
                                </span>

                            <?php else: ?>

                                -

                            <?php endif; ?>

                        </td>


                        <td class="text-center">

                            <span class="status <?= $statusClass ?>">

                                <?= esc(
                                    ucfirst(
                                        $currentStatus ?: 'Unknown'
                                    )
                                ) ?>

                            </span>

                        </td>


                        <td class="text-left">

                            <?= !empty($row['note'])
                                ? esc($row['note'])
                                : '-' ?>

                        </td>


                        <td class="text-center">

                            <?= esc(
                                $row['member_role']
                                    ?? '-'
                            ) ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td
                        colspan="8"
                        class="empty">

                        No attendance records found
                        for the selected filters.

                    </td>

                </tr>

            <?php endif; ?>

        </tbody>

    </table>


    <!-- FOOTER -->

    <div class="footer">

        <table width="100%">

            <tr>

                <td>
                    Attendance Management System
                </td>

                <td style="text-align:right;">
                    <?= esc($dateDisplay ?? 'All Dates') ?>
                </td>

            </tr>

        </table>

    </div>


</body>

</html>