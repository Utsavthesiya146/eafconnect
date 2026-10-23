<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Notifications</title>
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
        /* CONTAINER */
        .notification-container {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* CARD */
        .notification-card {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            transition: 0.3s;
            border-left: 4px solid transparent;
        }

        .notification-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
        }

        /* UNREAD STYLE */
        .notification-card.unread {
            background: #f8f9ff;
            border-left: 4px solid #777edd;
        }

        /* DATE SECTION */
        .notif-date {
            width: 90px;
            text-align: center;
            border-right: 1px solid #eee;
            margin-right: 18px;
        }

        .notif-date .day {
            font-size: 26px;
            font-weight: 700;
            color: #333;
        }

        .notif-date .month {
            font-size: 13px;
            color: #888;
        }

        .notif-date small {
            color: #aaa;
            font-size: 12px;
        }

        /* CONTENT */
        .notif-content {
            flex: 1;
        }

        .notif-message {
            font-size: 15px;
            color: #333;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        /* STATUS BADGE */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.unread {
            background: #777edd20;
            color: #777edd;
        }

        .status-badge.read {
            background: #e9ecef;
            color: #666;
        }

        /* ACTION */
        .notif-action {
            margin-left: 15px;
        }

        .btn-delete {
            border: none;
            background: #fff1f1;
            color: #e74c3c;
            padding: 8px 10px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-delete:hover {
            background: #e74c3c;
            color: #fff;
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 10px;
        }

        /* SECTION BOX */
        .notification-section {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            height: 100%;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        }

        /* TITLE */
        .section-title {
            font-weight: 600;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* COLORS */
        .unread-title {
            color: #777edd;
        }

        .read-title {
            color: #666;
        }

        /* CONTAINER */
        .notification-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* READ STYLE */
        .read-section .notification-card {
            opacity: 0.85;
        }

        /* SMALL EMPTY TEXT */
        .empty-mini {
            text-align: center;
            padding: 30px;
            color: #aaa;
            font-size: 14px;
        }

        /* GLOBAL EMPTY */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        @media (max-width: 768px) {

            .notification-section {
                padding: 12px;
                border-radius: 10px;
            }

            .notification-container {
                gap: 8px;
            }

            .notification-card {
                padding: 10px;
                border-radius: 10px;
                align-items: flex-start;
            }

            .notif-date {
                width: 50px;
                min-width: 50px;
                margin-right: 8px;
            }

            .notif-date .day {
                font-size: 18px;
            }

            .notif-date .month {
                font-size: 10px;
            }

            .notif-date small {
                font-size: 9px;
            }

            .notif-message {
                font-size: 12px;
                line-height: 1.3;
                margin-bottom: 4px;
            }

            .status-badge {
                padding: 2px 7px;
                font-size: 10px;
            }

            .notif-action {
                margin-left: 6px;
            }

            .btn-delete {
                padding: 5px 7px;
                font-size: 11px;
            }

            .section-title {
                font-size: 15px;
                margin-bottom: 10px;
            }

            .empty-state {
                padding: 20px 10px;
            }

            .empty-state i {
                font-size: 28px;
            }
        }

        @media (max-width: 768px) {

            .notification-section {
                padding: 8px;
            }

            .notification-container {
                gap: 6px;
            }

            .notification-card {
                padding: 8px;
                border-radius: 8px;
            }

            .notif-date {
                width: 42px;
                min-width: 42px;
                margin-right: 6px;
            }

            .notif-date .day {
                font-size: 15px;
            }

            .notif-date .month,
            .notif-date small {
                font-size: 8px;
            }

            .notif-message {
                font-size: 11px;
                line-height: 1.25;
                margin-bottom: 3px;
            }

            .status-badge {
                padding: 2px 6px;
                font-size: 9px;
            }

            .notif-action {
                margin-left: 4px;
            }

            .btn-delete {
                padding: 4px 6px;
                font-size: 10px;
            }

            .section-title {
                font-size: 14px;
                margin-bottom: 8px;
            }

            .empty-state {
                padding: 15px 8px;
            }

            .empty-state i {
                font-size: 22px;
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
                        <h5>Notifications:</h5>
                    </div>

                    <div class="col-md-6 col-6">
                        <a class="btn btn-primary btn-sm"
                            onclick="Add_new_notification();"
                            style="font-size:12px;font-weight:500;float:right;background-color:#08426b;margin:2px;">
                            Sent Notifications <i class="fa fa-plus-circle ml-1"></i>
                        </a>
                    </div>
                </div>
                <style>
                    /* =========================================================
   NOTIFICATION CRM TABLE
========================================================= */

                    :root {
                        --notif-primary: #08426b;
                        --notif-primary-dark: #063653;
                        --notif-primary-light: #eef6fb;

                        --notif-text: #263238;
                        --notif-muted: #71828c;

                        --notif-border: #e4ebef;

                        --notif-green: #198754;
                        --notif-red: #dc3545;
                    }


                    /* =========================================================
   MAIN WRAPPER
========================================================= */

                    .notification-wrapper {
                        width: 100%;
                    }

                    .notification-card {
                        background: #fff;

                        border: 1px solid var(--notif-border);

                        border-radius: 14px;

                        overflow: hidden;

                        box-shadow:
                            0 4px 18px rgba(8, 66, 107, .07);
                    }


                    /* =========================================================
   HEADER
========================================================= */

                    .notification-header {
                        display: flex;

                        align-items: center;

                        justify-content: space-between;

                        padding: 15px 18px;

                        border-bottom: 1px solid var(--notif-border);

                        background: #fff;
                    }

                    .notification-heading {
                        display: flex;

                        align-items: center;

                        gap: 10px;
                    }

                    .notification-heading-icon {
                        width: 38px;
                        height: 38px;

                        display: flex;

                        align-items: center;
                        justify-content: center;

                        border-radius: 10px;

                        background: var(--notif-primary-light);

                        color: var(--notif-primary);

                        font-size: 15px;
                    }

                    .notification-heading h5 {
                        margin: 0;

                        color: var(--notif-primary);

                        font-size: 16px;

                        font-weight: 700;
                    }

                    .notification-heading span {
                        display: block;

                        margin-top: 2px;

                        color: var(--notif-muted);

                        font-size: 10px;
                    }

                    .notification-count {
                        display: inline-flex;

                        align-items: center;

                        gap: 6px;

                        padding: 7px 11px;

                        border-radius: 20px;

                        background: var(--notif-primary-light);

                        color: var(--notif-primary);

                        font-size: 10px;

                        font-weight: 700;
                    }


                    /* =========================================================
   TABLE
========================================================= */

                    .notification-table {
                        width: 100%;

                        min-width: 850px;

                        border-collapse: separate;

                        border-spacing: 0;
                    }

                    .notification-table thead {
                        background: var(--notif-primary);
                    }

                    .notification-table th {
                        padding: 12px 13px;

                        color: #fff;

                        border: 0;

                        font-size: 11px;

                        font-weight: 700;

                        text-transform: uppercase;

                        letter-spacing: .35px;

                        white-space: nowrap;
                    }

                    .notification-table td {
                        padding: 13px;

                        border-bottom: 1px solid #edf2f5;

                        color: var(--notif-text);

                        font-size: 13px;

                        vertical-align: middle;
                    }

                    .notification-table tbody tr {
                        background: #fff;

                        transition: all .2s ease;
                    }

                    .notification-table tbody tr:hover {
                        background: #f8fbfd;
                    }

                    .notification-table tbody tr:last-child td {
                        border-bottom: 0;
                    }


                    /* =========================================================
   UNREAD ROW
========================================================= */

                    .notification-table tbody tr.notification-unread {
                        background: #fbfdff;
                    }

                    .notification-table tbody tr.notification-unread:hover {
                        background: #f2f8fc;
                    }


                    /* =========================================================
   NUMBER
========================================================= */

                    .notification-number {
                        width: 30px;
                        height: 30px;

                        display: inline-flex;

                        align-items: center;
                        justify-content: center;

                        border-radius: 8px;

                        background: var(--notif-primary-light);

                        color: var(--notif-primary);

                        font-size: 11px;

                        font-weight: 700;
                    }


                    /* =========================================================
   DATE
========================================================= */

                    .notification-date {
                        display: flex;

                        align-items: center;

                        gap: 9px;

                        min-width: 145px;
                    }

                    .notification-date-icon {
                        width: 35px;
                        height: 35px;

                        flex-shrink: 0;

                        display: flex;

                        align-items: center;
                        justify-content: center;

                        border-radius: 9px;

                        background: #f1f6f9;

                        color: var(--notif-primary);

                        font-size: 13px;
                    }

                    .notification-date-content strong {
                        display: block;

                        color: #455a64;

                        font-size: 12px;

                        font-weight: 700;
                    }

                    .notification-date-content small {
                        display: block;

                        margin-top: 3px;

                        color: #8a9aa3;

                        font-size: 10px;
                    }


                    /* =========================================================
   CONTENT
========================================================= */

                    .notification-content {
                        max-width: 550px;
                    }

                    .notification-title {
                        display: flex;

                        align-items: center;

                        gap: 6px;

                        margin-bottom: 3px;

                        color: #263238;

                        font-size: 14px;

                        font-weight: 700;
                    }

                    .notification-message {
                        color: #687980;

                        font-size: 12px;

                        line-height: 1.5;

                        max-width: 500px;
                    }

                    .notification-message p {
                        margin: 0;
                    }

                    .notification-message br {
                        line-height: 1.8;
                    }


                    /* UNREAD DOT */

                    .unread-dot {
                        width: 7px;
                        height: 7px;

                        flex-shrink: 0;

                        border-radius: 50%;

                        background: #dc3545;

                        box-shadow: 0 0 0 3px #fdeaea;
                    }


                    /* =========================================================
   STATUS
========================================================= */

                    .status-badge {
                        display: inline-flex;

                        align-items: center;

                        gap: 6px;

                        padding: 6px 10px;

                        border-radius: 20px;

                        font-size: 10px;

                        font-weight: 700;

                        white-space: nowrap;
                    }

                    .status-badge.unread {
                        background: #fff0f0;

                        color: #dc3545;
                    }

                    .status-badge.read {
                        background: #e8f7ef;

                        color: #198754;
                    }


                    /* =========================================================
   ACTION BUTTONS
========================================================= */

                    .notification-actions {
                        text-align: center;

                        white-space: nowrap;
                    }

                    .notification-action {
                        width: 34px;
                        height: 34px;

                        display: inline-flex;

                        align-items: center;
                        justify-content: center;

                        margin: 0 2px;

                        border: 0;

                        border-radius: 8px;

                        cursor: pointer;

                        transition: all .2s ease;
                    }


                    /* EDIT */

                    .notification-action.edit {
                        background: #fff5df;

                        color: #c88400;
                    }

                    .notification-action.edit:hover {
                        background: #d89400;

                        color: #fff;

                        transform: translateY(-2px);
                    }


                    /* DELETE */

                    .notification-action.delete {
                        background: #fff0f0;

                        color: #dc3545;
                    }

                    .notification-action.delete:hover {
                        background: #dc3545;

                        color: #fff;

                        transform: translateY(-2px);
                    }


                    /* =========================================================
   EMPTY STATE
========================================================= */

                    .notification-empty {
                        padding: 55px 20px;

                        text-align: center;
                    }

                    .notification-empty-icon {
                        width: 65px;
                        height: 65px;

                        margin: 0 auto 15px;

                        display: flex;

                        align-items: center;
                        justify-content: center;

                        border-radius: 16px;

                        background: var(--notif-primary-light);

                        color: var(--notif-primary);

                        font-size: 25px;
                    }

                    .notification-empty h5 {
                        margin: 0;

                        color: #455a64;

                        font-size: 15px;

                        font-weight: 700;
                    }

                    .notification-empty p {
                        margin: 5px 0 0;

                        color: #90a4ae;

                        font-size: 11px;
                    }


                    /* =========================================================
   MOBILE
========================================================= */

                    @media (max-width: 768px) {

                        .notification-card {
                            border-radius: 12px;
                        }

                        .notification-header {
                            padding: 12px 13px;
                        }

                        .notification-heading-icon {
                            width: 34px;
                            height: 34px;

                            font-size: 13px;
                        }

                        .notification-heading h5 {
                            font-size: 14px;
                        }

                        .notification-heading span {
                            font-size: 9px;
                        }

                        .notification-count {
                            padding: 6px 8px;

                            font-size: 9px;
                        }


                        /* Convert table rows into cards */

                        .notification-table {
                            min-width: 100%;
                        }

                        .notification-table thead {
                            display: none;
                        }

                        .notification-table,
                        .notification-table tbody,
                        .notification-table tr,
                        .notification-table td {
                            display: block;

                            width: 100%;
                        }

                        .notification-table tbody {
                            padding: 8px;
                        }

                        .notification-table tbody tr {
                            margin-bottom: 10px;

                            padding: 9px;

                            border: 1px solid var(--notif-border);

                            border-radius: 12px;

                            box-shadow:
                                0 3px 10px rgba(8, 66, 107, .06);
                        }

                        .notification-table td {
                            display: flex;

                            align-items: center;

                            justify-content: space-between;

                            min-height: 40px;

                            padding: 5px 0;

                            border: 0;

                            font-size: 11px;

                            text-align: right;
                        }

                        .notification-table td::before {
                            content: attr(data-label);

                            margin-right: 10px;

                            color: #71828c;

                            font-size: 9px;

                            font-weight: 700;

                            text-transform: uppercase;
                        }


                        /* Notification content */

                        .notification-table td[data-label="Notification"] {
                            display: block;

                            padding: 8px 0;

                            margin-bottom: 3px;

                            border-bottom: 1px solid #edf2f5;

                            text-align: left;
                        }

                        .notification-table td[data-label="Notification"]::before {
                            display: none;
                        }

                        .notification-content {
                            max-width: 100%;
                        }

                        .notification-title {
                            font-size: 12px;
                        }

                        .notification-message {
                            font-size: 10px;
                        }


                        /* Date */

                        .notification-date {
                            justify-content: flex-end;
                        }

                        .notification-date-content strong {
                            font-size: 10px;
                        }

                        .notification-date-content small {
                            font-size: 9px;
                        }


                        /* Status */

                        .status-badge {
                            padding: 6px 9px;

                            font-size: 9px;
                        }


                        /* Actions */

                        .notification-table td.notification-actions {
                            justify-content: center;

                            padding-top: 10px;

                            margin-top: 5px;

                            border-top: 1px solid #edf2f5;
                        }

                        .notification-table td.notification-actions::before {
                            display: none;
                        }

                        .notification-action {
                            width: 35px;
                            height: 35px;
                        }


                        /* Number */

                        .notification-number {
                            width: 27px;
                            height: 27px;

                            font-size: 10px;
                        }
                    }


                    /* =========================================================
   SMALL MOBILE
========================================================= */

                    @media (max-width: 420px) {

                        .notification-count {
                            display: none;
                        }

                        .notification-table tbody {
                            padding: 6px;
                        }

                        .notification-table tbody tr {
                            padding: 8px;
                        }

                        .notification-title {
                            font-size: 11px;
                        }

                        .notification-message {
                            font-size: 9px;
                        }

                        .notification-action {
                            width: 33px;
                            height: 33px;
                        }

                        .notification-empty {
                            padding: 40px 15px;
                        }
                    }
                </style>
                <!-- =========================================================
     NOTIFICATION TABLE
========================================================= -->

                <div class="notification-wrapper">



                    <!-- TABLE HEADER -->



                    <?php if (!empty($notifications)): ?>

                        <!-- RESPONSIVE TABLE -->
                        <div class="table-responsive notification-table-responsive">

                            <table class="notification-table">

                                <thead>

                                    <tr>

                                        <th width="60">#</th>

                                        <th width="180">
                                            Date
                                        </th>

                                        <th>
                                            Notification
                                        </th>

                                        <th width="130">
                                            Status
                                        </th>

                                        <th width="110" class="text-center">
                                            Action
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <?php $i = 1; ?>

                                    <?php foreach ($notifications as $row): ?>

                                        <?php
                                        $createdAt = strtotime($row->created_at);

                                        $isUnread = ($row->status === 'unread');
                                        ?>

                                        <tr class="<?= $isUnread ? 'notification-unread' : '' ?>">

                                            <!-- NUMBER -->
                                            <td data-label="#">

                                                <span class="notification-number">
                                                    <?= $i ?>
                                                </span>

                                            </td>


                                            <!-- DATE -->
                                            <td data-label="Date">

                                                <div class="notification-date">

                                                    <div class="notification-date-icon">

                                                        <i class="bi bi-calendar3"></i>

                                                    </div>

                                                    <div class="notification-date-content">

                                                        <strong>
                                                            <?= date('d M Y', $createdAt) ?>
                                                        </strong>

                                                        <small>
                                                            <i class="bi bi-clock"></i>
                                                            <?= date('h:i A', $createdAt) ?>
                                                        </small>

                                                    </div>

                                                </div>

                                            </td>


                                            <!-- NOTIFICATION -->
                                            <td data-label="Notification">

                                                <div class="notification-content">

                                                    <div class="notification-title">

                                                        <?php if ($isUnread): ?>

                                                            <span class="unread-dot"></span>

                                                        <?php endif; ?>

                                                        <?= strip_tags(
                                                            $row->title,
                                                            '<p><b><br><strong><em>'
                                                        ) ?>

                                                    </div>


                                                    <div class="notification-message">

                                                        <?= strip_tags(
                                                            $row->message,
                                                            '<p><b><br><strong><em>'
                                                        ) ?>

                                                    </div>

                                                </div>

                                            </td>


                                            <!-- STATUS -->
                                            <td data-label="Status">

                                                <?php if ($isUnread): ?>

                                                    <span class="status-badge unread">

                                                        <i class="bi bi-envelope-fill"></i>

                                                        Unread

                                                    </span>

                                                <?php else: ?>

                                                    <span class="status-badge read">

                                                        <i class="bi bi-envelope-open-fill"></i>

                                                        Read

                                                    </span>

                                                <?php endif; ?>

                                            </td>


                                            <!-- ACTION -->
                                            <td
                                                data-label="Action"
                                                class="notification-actions">

                                                <button
                                                    type="button"
                                                    class="notification-action edit"
                                                    onclick="edi_notf('<?= esc($row->n_code) ?>')"
                                                    title="Edit Notification">

                                                    <i class="fa fa-pen"></i>

                                                </button>


                                                <button
                                                    type="button"
                                                    class="notification-action delete"
                                                    onclick="delete_notf('<?= esc($row->n_code) ?>')"
                                                    title="Delete Notification">

                                                    <i class="fa fa-trash"></i>

                                                </button>

                                            </td>

                                        </tr>

                                        <?php $i++; ?>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>


                    <?php else: ?>

                        <!-- EMPTY STATE -->

                        <div class="notification-empty">

                            <div class="notification-empty-icon">

                                <i class="bi bi-bell-slash-fill"></i>

                            </div>

                            <h5>No Notifications Available</h5>

                            <p>
                                There are currently no notifications to display.
                            </p>

                        </div>

                    <?php endif; ?>

                </div>





                <!-- =========================
     PAGINATION UI
========================= -->
                <?php if (!empty($totalPages) && $totalPages > 1): ?>

                    <nav class="crm-pagination-wrapper mt-3"
                        aria-label="Notification pagination">

                        <ul class="crm-pagination">

                            <!-- FIRST -->
                            <li class="<?= ($currentPage <= 1) ? 'disabled' : '' ?>">

                                <?php if ($currentPage > 1): ?>

                                    <a href="<?= base_url(
                                                    'admin/notifications?page=1'
                                                ) ?>"
                                        title="First Page">

                                        <i class="bi bi-chevron-double-left"></i>

                                    </a>

                                <?php else: ?>

                                    <span>
                                        <i class="bi bi-chevron-double-left"></i>
                                    </span>

                                <?php endif; ?>

                            </li>


                            <!-- PREVIOUS -->
                            <li class="<?= ($currentPage <= 1) ? 'disabled' : '' ?>">

                                <?php if ($currentPage > 1): ?>

                                    <a href="<?= base_url(
                                                    'admin/notifications?page=' . ($currentPage - 1)
                                                ) ?>"
                                        title="Previous">

                                        <i class="bi bi-chevron-left"></i>

                                    </a>

                                <?php else: ?>

                                    <span>
                                        <i class="bi bi-chevron-left"></i>
                                    </span>

                                <?php endif; ?>

                            </li>


                            <?php

                            /*
            |--------------------------------------------------------------------------
            | Smart Pagination
            |--------------------------------------------------------------------------
            */

                            $startPage = max(1, $currentPage - 2);
                            $endPage   = min($totalPages, $currentPage + 2);

                            ?>


                            <!-- FIRST PAGE -->
                            <?php if ($startPage > 1): ?>

                                <li>

                                    <a href="<?= base_url(
                                                    'admin/notifications?page=1'
                                                ) ?>">
                                        1
                                    </a>

                                </li>

                                <?php if ($startPage > 2): ?>

                                    <li class="dots">
                                        <span>...</span>
                                    </li>

                                <?php endif; ?>

                            <?php endif; ?>


                            <!-- PAGE NUMBERS -->

                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>

                                <li class="<?= ($i == $currentPage) ? 'active' : '' ?>">

                                    <a href="<?= base_url(
                                                    'admin/notifications?page=' . $i
                                                ) ?>">

                                        <?= $i ?>

                                    </a>

                                </li>

                            <?php endfor; ?>


                            <!-- LAST PAGE -->

                            <?php if ($endPage < $totalPages): ?>

                                <?php if ($endPage < $totalPages - 1): ?>

                                    <li class="dots">
                                        <span>...</span>
                                    </li>

                                <?php endif; ?>

                                <li>

                                    <a href="<?= base_url(
                                                    'admin/notifications?page=' . $totalPages
                                                ) ?>">

                                        <?= $totalPages ?>

                                    </a>

                                </li>

                            <?php endif; ?>


                            <!-- NEXT -->
                            <li class="<?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">

                                <?php if ($currentPage < $totalPages): ?>

                                    <a href="<?= base_url(
                                                    'admin/notifications?page=' . ($currentPage + 1)
                                                ) ?>"
                                        title="Next">

                                        <i class="bi bi-chevron-right"></i>

                                    </a>

                                <?php else: ?>

                                    <span>
                                        <i class="bi bi-chevron-right"></i>
                                    </span>

                                <?php endif; ?>

                            </li>


                            <!-- LAST -->
                            <li class="<?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">

                                <?php if ($currentPage < $totalPages): ?>

                                    <a href="<?= base_url(
                                                    'admin/notifications?page=' . $totalPages
                                                ) ?>"
                                        title="Last Page">

                                        <i class="bi bi-chevron-double-right"></i>

                                    </a>

                                <?php else: ?>

                                    <span>
                                        <i class="bi bi-chevron-double-right"></i>
                                    </span>

                                <?php endif; ?>

                            </li>

                        </ul>

                    </nav>

                <?php endif; ?><style>
                    /* =========================================================
   CRM PAGINATION
========================================================= */

                    .crm-pagination-wrapper {
                        width: 100%;
                    }

                    .crm-pagination {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 5px;

                        margin: 0;
                        padding: 10px 0;

                        list-style: none;
                    }

                    .crm-pagination li {
                        display: flex;
                    }

                    .crm-pagination li a,
                    .crm-pagination li span {
                        min-width: 34px;
                        height: 34px;

                        padding: 0 10px;

                        display: flex;
                        align-items: center;
                        justify-content: center;

                        border: 1px solid #e1e7eb;
                        border-radius: 8px;

                        background: #fff;

                        color: #52636d;

                        text-decoration: none;

                        font-size: 12px;
                        font-weight: 600;

                        transition: all .2s ease;
                    }

                    /* Hover */

                    .crm-pagination li a:hover {
                        background: #08426b;
                        border-color: #08426b;
                        color: #fff;

                        transform: translateY(-1px);
                    }

                    /* Active */

                    .crm-pagination li.active a {
                        background: #08426b;
                        border-color: #08426b;

                        color: #fff;

                        box-shadow: 0 3px 8px rgba(8, 66, 107, .20);
                    }

                    /* Disabled */

                    .crm-pagination li.disabled span {
                        background: #f5f7f8;
                        border-color: #edf0f2;

                        color: #b8c1c6;

                        cursor: not-allowed;
                    }

                    /* Dots */

                    .crm-pagination li.dots span {
                        min-width: 25px;

                        padding: 0;

                        border: none;

                        background: transparent;

                        color: #8b999f;

                        cursor: default;
                    }


                    /* =========================================================
   MOBILE
========================================================= */

                    @media (max-width: 576px) {

                        .crm-pagination {
                            gap: 3px;
                            padding: 8px 0;
                        }

                        .crm-pagination li a,
                        .crm-pagination li span {
                            min-width: 30px;
                            height: 30px;

                            padding: 0 7px;

                            border-radius: 7px;

                            font-size: 11px;
                        }

                        .crm-pagination li.dots span {
                            min-width: 20px;
                        }
                    }
                </style>
            </div>
            <!-- End Page-content -->
            <?php echo view('include/admin/footer.php') ?>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php echo view('include/admin/popup/notification.php') ?>
</body>

</html>