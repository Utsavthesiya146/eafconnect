<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Attendance Report</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>

        body {
            background: #f5f7fa;
            font-family: Arial, sans-serif;
        }

        .report-wrapper {
            max-width: 1400px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
        }

        .report-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .report-title {
            font-size: 25px;
            font-weight: 700;
        }

        .filter-badge {
            display: inline-block;
            background: #f1f3f5;
            padding: 6px 12px;
            margin: 3px;
            border-radius: 20px;
            font-size: 13px;
        }

        .table th {
            background: #f8f9fa;
            white-space: nowrap;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            color: #fff;
        }

        .present {
            background: #28a745;
        }

        .absent {
            background: #dc3545;
        }

        .medical {
            background: #ffc107;
            color: #222;
        }

        .late {
            background: #fd7e14;
        }

        .substitute {
            background: #6f42c1;
        }

        .print-btn {
            margin-bottom: 20px;
        }

        @media print {

            body {
                background: #fff;
            }

            .report-wrapper {
                margin: 0;
                max-width: 100%;
                box-shadow: none;
            }

            .print-btn {
                display: none;
            }

        }

    </style>

</head>


<body>

<div class="report-wrapper">


    <!-- PRINT -->

    <div class="text-end print-btn">

        <button
            onclick="window.print()"
            class="btn btn-primary">

            <i class="fa fa-print me-1"></i>

            Print

        </button>

    </div>


    <!-- HEADER -->

    <div class="report-header">

        <div class="report-title">

            <i class="fa fa-calendar-check me-2"></i>

            Chapter Attendance Report

        </div>

        <div class="text-muted">

            Attendance details based on selected filters

        </div>

    </div>


    <!-- FILTERS -->

    <div class="mb-4">

        <?php if (!empty($filters['search'])): ?>

            <span class="filter-badge">

                Search:
                <strong>
                    <?= esc($filters['search']) ?>
                </strong>

            </span>

        <?php endif; ?>


        <?php if (!empty($filters['to_user'])): ?>

            <span class="filter-badge">

                Member:
                <strong>
                    <?= esc($filters['to_user']) ?>
                </strong>

            </span>

        <?php endif; ?>


        <?php if (!empty($filters['status'])): ?>

            <span class="filter-badge">

                Status:
                <strong>
                    <?= esc(ucfirst($filters['status'])) ?>
                </strong>

            </span>

        <?php endif; ?>


        <?php if (!empty($filters['date'])): ?>

            <span class="filter-badge">

                Date:
                <strong>
                    <?= esc($filters['date']) ?>
                </strong>

            </span>

        <?php endif; ?>


        <?php if (!empty($filters['month'])): ?>

            <span class="filter-badge">

                Month:
                <strong>
                    <?= esc($filters['month']) ?>
                </strong>

            </span>

        <?php endif; ?>

    </div>


    <!-- SUMMARY -->

    <div class="alert alert-light border">

        <strong>
            Total Attendance Records:
        </strong>

        <?= count($attendance) ?>

    </div>


    <!-- TABLE -->

    <?php if (!empty($attendance)): ?>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Member</th>

                        <th>Event</th>

                        <th>Chapter</th>

                        <th>Date</th>

                        <th>Status</th>

                        <th>Note</th>

                    </tr>

                </thead>

                <tbody>

                <?php $i = 1; ?>

                <?php foreach ($attendance as $a): ?>

                    <?php

                    $statusClass =
                        strtolower(
                            $a->status ?? ''
                        );

                    ?>

                    <tr>

                        <td>
                            <?= $i++ ?>
                        </td>

                        <td>

                            <strong>
                                <?= esc($a->name) ?>
                            </strong>

                            <?php if (!empty($a->email)): ?>

                                <br>

                                <small class="text-muted">

                                    <?= esc($a->email) ?>

                                </small>

                            <?php endif; ?>

                        </td>

                        <td>
                            <?= esc($a->event_title) ?>
                        </td>

                        <td>
                            <?= esc($a->chapter_name) ?>
                        </td>

                        <td>

                            <?php if (!empty($a->event_date)): ?>

                                <?= date(
                                    'd M Y',
                                    strtotime($a->event_date)
                                ) ?>

                            <?php endif; ?>

                        </td>

                        <td>

                            <span
                                class="badge-status <?= esc($statusClass) ?>">

                                <?= ucfirst(
                                    esc($a->status)
                                ) ?>

                            </span>

                        </td>

                        <td>
                            <?= esc($a->note ?? '') ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php else: ?>

        <div class="alert alert-warning text-center">

            <i class="fa fa-calendar-xmark me-1"></i>

            No attendance records found.

        </div>

    <?php endif; ?>


</div>

</body>

</html>