<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <style>

        @page {
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #2C3E50;
            margin: 0;
            padding: 0;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .header {
            background: #1a4d8d;
            color: #ffffff;
            padding: 12px 15px;
        }

        .header h2 {
            margin: 0;
            padding: 0;
            font-size: 17px;
        }

        .header p {
            margin: 3px 0 0;
            font-size: 10px;
        }


        /* =====================================================
           SUMMARY
        ===================================================== */

        .stats {
            width: 100%;
            margin-top: 10px;
        }

        .stat-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
        }

        .stat-box {
            width: 20%;
            background: #f7f9fc;
            border: 1px solid #e1e5ea;
            padding: 7px;
            text-align: center;
        }

        .stat-title {
            font-size: 9px;
            color: #777777;
            margin-bottom: 3px;
        }

        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #1a4d8d;
        }


        /* =====================================================
           TOP PERFORMER
        ===================================================== */

        .top-box {
            margin-top: 8px;
            padding: 7px 9px;

            border-left: 4px solid #d4af37;

            background: #fff8e1;

            font-size: 10px;
        }

        .top-box strong {
            color: #1a4d8d;
        }


        /* =====================================================
           MAIN TABLE
        ===================================================== */

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .report-table th {
            background: #1a4d8d;
            color: #ffffff;

            padding: 6px 5px;

            font-size: 9px;
            font-weight: bold;

            border: 1px solid #1a4d8d;
        }

        .report-table td {
            padding: 5px;

            font-size: 9px;

            border: 1px solid #e5e7eb;

            text-align: center;

            vertical-align: middle;
        }

        .report-table tr:nth-child(even) {
            background: #f8fafc;
        }


        /* =====================================================
           NAME
        ===================================================== */

        .name {
            text-align: left !important;
            font-weight: bold;
        }

        .phone {
            display: block;

            margin-top: 2px;

            font-size: 8px;
            font-weight: normal;

            color: #777777;
        }


        /* =====================================================
           MONEY
        ===================================================== */

        .green {
            color: #27ae60;
            font-weight: bold;
        }

        .blue {
            color: #2980b9;
            font-weight: bold;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {
            margin-top: 8px;

            font-size: 8px;

            text-align: right;

            color: #777777;
        }


        /* =====================================================
           EMPTY
        ===================================================== */

        .empty {
            padding: 20px;

            text-align: center;

            color: #777777;

            font-size: 11px;
        }

    </style>

</head>


<body>


    <!-- =====================================================
         HEADER
    ===================================================== -->

    <div class="header">

        <h2>
            <?= esc($chapter_info['chapter_name'] ?? '-') ?>
            - Chapter Report
        </h2>

        <p>
            Location:
            <?= esc($chapter_info['location'] ?? '-') ?>
        </p>

        <p>
            Leader:
            <?= esc($chapter_info['leader_name'] ?? '-') ?>
        </p>

    </div>


    <!-- =====================================================
         CALCULATE TOTALS
    ===================================================== -->

    <?php

    $totalMembers = count($report);

    $totalF2F = 0;
    $totalConnections = 0;
    $totalVisitors = 0;
    $totalMITBGiven = 0;
    $totalMITBReceived = 0;

    foreach ($report as $r) {

        $totalF2F += (int) ($r['f2f'] ?? 0);

        $totalConnections += (int) (
            $r['connections'] ?? 0
        );

        $totalVisitors += (int) (
            $r['visitors'] ?? 0
        );

        $totalMITBGiven += (float) (
            $r['mitb_given'] ?? 0
        );

        $totalMITBReceived += (float) (
            $r['mitb_received'] ?? 0
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Indian Currency Formatter
    |--------------------------------------------------------------------------
    |
    | No NumberFormatter / Intl dependency.
    |
    */
    function formatIndianNumber($number)
    {
        $number = (float) $number;

        $formatted = number_format(
            $number,
            2,
            '.',
            ','
        );

        return $formatted;
    }


    /*
    |--------------------------------------------------------------------------
    | Top Performer
    |--------------------------------------------------------------------------
    */
    $top = null;

    foreach ($report as $member) {

        if ($top === null) {

            $top = $member;

            continue;
        }

        if (
            (float) ($member['mitb_given'] ?? 0)
            >
            (float) ($top['mitb_given'] ?? 0)
        ) {

            $top = $member;
        }
    }

    ?>


    <!-- =====================================================
         STATS
    ===================================================== -->

    <div class="stats">

        <table class="stat-table">

            <tr>

                <td class="stat-box">

                    <div class="stat-title">
                        Members
                    </div>

                    <div class="stat-value">
                        <?= $totalMembers ?>
                    </div>

                </td>


                <td class="stat-box">

                    <div class="stat-title">
                        F2F
                    </div>

                    <div class="stat-value">
                        <?= $totalF2F ?>
                    </div>

                </td>


                <td class="stat-box">

                    <div class="stat-title">
                        Connections
                    </div>

                    <div class="stat-value">
                        <?= $totalConnections ?>
                    </div>

                </td>


                <td class="stat-box">

                    <div class="stat-title">
                        Visitors
                    </div>

                    <div class="stat-value">
                        <?= $totalVisitors ?>
                    </div>

                </td>


                <td class="stat-box">

                    <div class="stat-title">
                        Total MITB
                    </div>

                    <div class="stat-value">
                        ₹<?= formatIndianNumber($totalMITBGiven) ?>
                    </div>

                </td>

            </tr>

        </table>

    </div>


    <!-- =====================================================
         TOP PERFORMER
    ===================================================== -->

    <?php if (!empty($top)): ?>

        <div class="top-box">

            🏆

            <strong>
                Top Performer:
            </strong>

            <?= esc($top['name'] ?? '-') ?>

            &nbsp; | &nbsp;

            ₹<?= formatIndianNumber(
                $top['mitb_given'] ?? 0
            ) ?>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         REPORT TABLE
    ===================================================== -->

    <?php if (!empty($report)): ?>

        <table class="report-table">

            <thead>

                <tr>

                    <th width="4%">
                        #
                    </th>

                    <th width="24%" class="name">
                        Member
                    </th>

                    <th width="10%">
                        Role
                    </th>

                    <th width="7%">
                        F2F
                    </th>

                    <th width="7%">
                        Ref
                    </th>

                    <th width="8%">
                        Conn
                    </th>

                    <th width="8%">
                        Visit
                    </th>

                    <th width="16%">
                        MITB Given
                    </th>

                    <th width="16%">
                        MITB Received
                    </th>

                </tr>

            </thead>


            <tbody>

                <?php $i = 1; ?>

                <?php foreach ($report as $r): ?>

                    <tr>

                        <td>
                            <?= $i++ ?>
                        </td>


                        <td class="name">

                            <?= esc(
                                $r['name'] ?? '-'
                            ) ?>

                            <span class="phone">

                                <?= esc(
                                    $r['phone'] ?? '-'
                                ) ?>

                            </span>

                        </td>


                        <td>
                            <?= esc(
                                $r['role'] ?? '-'
                            ) ?>
                        </td>


                        <td>
                            <?= (int) (
                                $r['f2f'] ?? 0
                            ) ?>
                        </td>


                        <td>
                            <?= (int) (
                                $r['ref_given'] ?? 0
                            ) ?>
                        </td>


                        <td>
                            <?= (int) (
                                $r['connections'] ?? 0
                            ) ?>
                        </td>


                        <td>
                            <?= (int) (
                                $r['visitors'] ?? 0
                            ) ?>
                        </td>


                        <td class="green">

                            ₹<?= formatIndianNumber(
                                $r['mitb_given'] ?? 0
                            ) ?>

                        </td>


                        <td class="blue">

                            ₹<?= formatIndianNumber(
                                $r['mitb_received'] ?? 0
                            ) ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php else: ?>

        <div class="empty">
            No active members found for this chapter.
        </div>

    <?php endif; ?>


    <!-- =====================================================
         FOOTER
    ===================================================== -->

    <div class="footer">

        Generated on:
        <?= date('d M Y h:i A') ?>

    </div>


</body>

</html>