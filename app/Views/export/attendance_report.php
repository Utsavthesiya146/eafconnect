<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Attendance Overall Report</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            padding: 30px;
        }

        .report-card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
        }

        .report-title {
            font-size: 24px;
            font-weight: 700;
        }

        .summary {
            margin-bottom: 20px;
            color: #666;
        }

        table th {
            background: #f1f3f5 !important;
        }

        .print-btn {
            margin-bottom: 20px;
        }

        @media print {

            body {
                background: #fff;
                padding: 0;
            }

            .print-btn {
                display: none;
            }

            .report-card {
                box-shadow: none;
            }

        }
    </style>

</head>

<body>

    <div class="container-fluid">

        <div class="report-card">

            <button
                type="button"
                class="btn btn-primary print-btn"
                onclick="window.print()">

                <i class="fa fa-print"></i>
                Print Report

            </button>


            <div class="report-title">
                Overall Attendance Report
            </div>


            <div class="summary">

                <?php if (!empty($filters['month'])): ?>

                    Month:
                    <strong>
                        <?= esc($filters['month']) ?>
                    </strong>

                <?php elseif (!empty($filters['date'])): ?>

                    Date:
                    <strong>
                        <?= esc($filters['date']) ?>
                    </strong>

                <?php else: ?>

                    Overall Attendance

                <?php endif; ?>

            </div>


            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Member Name</th>

                            <th>Total Meetings</th>

                            <th>Present</th>

                            <th>Absent</th>

                            <th>Late</th>

                            <th>Substitute</th>

                            <th>Medical</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if (!empty($report)): ?>

                            <?php $i = 1; ?>

                            <?php foreach ($report as $row): ?>

                                <tr>

                                    <td>
                                        <?= $i++ ?>
                                    </td>

                                    <td>
                                        <strong>
                                            <?= esc($row['name']) ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?= (int) $row['total_meetings'] ?>
                                    </td>

                                    <td>
                                        <?= (int) $row['present_count'] ?>
                                    </td>

                                    <td>
                                        <?= (int) $row['absent_count'] ?>
                                    </td>

                                    <td>
                                        <?= (int) $row['late_count'] ?>
                                    </td>

                                    <td>
                                        <?= (int) $row['substitute_count'] ?>
                                    </td>

                                    <td>
                                        <?= (int) $row['medical_count'] ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-4">

                                    No attendance records found.

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>

</html>