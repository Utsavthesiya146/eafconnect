<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Connects</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body data-sidebar="colored">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php echo view('include/member/header.php') ?>
        <?php echo view('include/member/sidebar.php') ?>
        <div class="main-content">
            <div class="page-content">
                <div class="row p-2">
                    <div class="col-md-6 col-6">
                        <h5>Manage Connects Details:</h5>
                    </div>
                    <div class="col-md-6 col-6">

                        <button type="button"
                            class="add-meeting-btn" style="float: right;"
                            onclick="add_new_connects();">

                            <span class="add-meeting-icon">
                                <i class="fa fa-plus"></i>
                            </span>

                            <span class="add-meeting-text">
                                Add New Connects
                            </span>

                        </button>
                    </div>
                </div>
                <div class="container-fluid mt-4">
                    <?php if (!empty($connect)): ?>
                        <?php
                        $page = $pager->getCurrentPage('default') ?? 1;
                        $i = 1 + ($perPage * ($page - 1));
                        ?>
                        <div class="row g-4 justify-content-center">
                            <?php foreach ($connect as $row): ?>
                                <div class="col-md-8 col-lg-6">
                                    <div class="referral-card">
                                        <!-- Card Header -->
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span class="card-title"><?= esc($row['business']) ?></span>
                                            <span class="badge status  bg-light <?= $row['status'] ?>" onclick="edit_refferral_status('<?= $row['referral_code'] ?>');"><?= esc(ucfirst($row['status'])) ?></span>
                                        </div>

                                        <!-- Card Body -->
                                        <div class="card-body">
                                            <div class="member-section d-flex justify-content-between mb-3">
                                                <!-- Given By -->
                                                <div class="member-info-block">
                                                    <h6>Given By</h6>
                                                    <div class="member-info">
                                                        <img src="<?= base_url('uploads/profile/' . ($row['from_profile'] ?? 'empty.png')) ?>" class="avatar" style="object-fit: cover;object-position: top center;">
                                                        <div class="member-details">
                                                            <div class="name"><?= esc($row['from_name']) ?></div>
                                                            <small><?= esc($row['from_email']) ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Arrow -->
                                                <div class="arrow-block d-flex align-items-center justify-content-center">
                                                    <i class="fa fa-arrow-right"></i>
                                                </div>
                                                <!-- Sent To -->
                                                <div class="member-info-block">
                                                    <h6>Sent To</h6>
                                                    <div class="member-info">
                                                        <img src="<?= base_url('uploads/profile/' . ($row['to_profile'] ?? 'empty.png')) ?>" class="avatar" style="object-fit: cover;object-position: top center;">
                                                        <div class="member-details">
                                                            <div class="name"><?= esc($row['to_name']) ?></div>
                                                            <small><?= esc($row['to_email']) ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Description -->
                                            <div class="description mb-3">
                                                <h6>Description & Details</h6>
                                                <p><?= esc($row['description'] ?? '-') ?></p>
                                            </div>
                                            <!-- Referral Details -->
                                            <div class="row referral-details mb-2">
                                                <div class="col-12 mb-2">
                                                    <p><strong>Connects Code:</strong> <span class="text-primary"><?= esc($row['referral_code']) ?></span></p>
                                                    <p><strong>Contact Number:</strong> <?= esc($row['contact_number'] ?? '-') ?></p>
                                                    <p><strong>Temperature:</strong> <?= esc($row['temperature'] ?? '-') ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Created At:</strong> <?= (new DateTime($row['created_at']))->format('d M Y, H:i') ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Updated At:</strong> <?= (new DateTime($row['updated_at']))->format('d M Y, H:i') ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Card Footer -->
                                        <div class="card-footer d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Recorded: <?= (new DateTime($row['created_at']))->format('d M Y, H:i') ?></small>
                                        </div>
                                    </div>
                                </div>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center p-5 fs-5">No Connects Found</div>
                    <?php endif; ?>

                    <!-- Pagination -->
                    <div class="row mt-4">
                        <div class="d-flex justify-content-center">
                            <?= $pager->links('default', 'default_full') ?>
                        </div>
                    </div>
                </div>

                <style>
                    :root {
                        --crm-primary: #0a3d61;
                        --crm-primary-dark: #072f4b;
                        --crm-secondary: #1d6f9f;
                        --crm-light: #f2f7fa;
                        --crm-border: #dce7ed;
                        --crm-text: #263746;
                        --crm-muted: #71808a;
                        --crm-accent: #f4ba40;
                    }



                    /* =========================================================
   ADD NEW MEETING BUTTON
========================================================= */

                    .add-meeting-btn {
                        height: 40px;

                        display: inline-flex;
                        align-items: center;
                        justify-content: center;

                        gap: 8px;

                        padding: 0 14px 0 6px;

                        border: 1px solid #174F70;

                        border-radius: 22px;

                        background: #174F70;

                        color: #ffffff;

                        font-size: 12px;
                        font-weight: 650;

                        line-height: 1;

                        cursor: pointer;

                        box-shadow: 0 4px 10px rgba(23, 79, 112, .16);

                        transition:
                            background .2s ease,
                            border-color .2s ease,
                            transform .2s ease,
                            box-shadow .2s ease;
                    }


                    /* ICON CIRCLE */

                    .add-meeting-icon {
                        width: 29px;
                        height: 29px;

                        flex: 0 0 29px;

                        display: flex;
                        align-items: center;
                        justify-content: center;

                        border-radius: 50%;

                        background: #ffffff;

                        color: #174F70;

                        font-size: 11px;

                        transition:
                            transform .2s ease,
                            background .2s ease;
                    }


                    /* TEXT */

                    .add-meeting-text {
                        white-space: nowrap;
                    }


                    /* HOVER */

                    .add-meeting-btn:hover {
                        background: #123F59;

                        border-color: #123F59;

                        color: #ffffff;

                        transform: translateY(-1px);

                        box-shadow: 0 6px 15px rgba(23, 79, 112, .22);
                    }


                    .add-meeting-btn:hover .add-meeting-icon {
                        transform: rotate(90deg);

                        background: #FFB900;

                        color: #ffffff;
                    }


                    /* ACTIVE */

                    .add-meeting-btn:active {
                        transform: translateY(0);

                        box-shadow: 0 2px 6px rgba(23, 79, 112, .15);
                    }


                    /* =========================================================
   MOBILE
========================================================= */

                    @media (max-width: 576px) {

                        .add-meeting-btn {
                            height: 37px;

                            padding: 0 10px 0 5px;

                            gap: 6px;

                            font-size: 11px;
                        }

                        .add-meeting-icon {
                            width: 27px;
                            height: 27px;

                            flex-basis: 27px;

                            font-size: 10px;
                        }

                    }

                    /* =========================================================
       MAIN CARD
    ========================================================= */

                    .referral-card {
                        background: #ffffff;
                        border: 1px solid var(--crm-border);
                        border-radius: 14px;
                        overflow: hidden;
                        box-shadow: 0 5px 20px rgba(10, 61, 97, 0.07);
                        transition: all .25s ease;
                        margin-bottom: 18px;
                    }

                    .referral-card:hover {
                        transform: translateY(-3px);
                        box-shadow: 0 10px 28px rgba(10, 61, 97, .12);
                    }


                    /* =========================================================
       HEADER
    ========================================================= */

                    .referral-card .card-header {
                        background: linear-gradient(135deg,
                                var(--crm-primary-dark),
                                var(--crm-primary));
                        color: #fff;
                        padding: 15px 20px;
                        border: 0;
                        border-radius: 0;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        min-height: 52px;
                    }

                    .referral-card .card-header h5,
                    .referral-card .card-header h6 {
                        margin: 0;
                        color: #fff;
                        font-size: 16px;
                        font-weight: 700;
                    }


                    /* =========================================================
       BODY
    ========================================================= */

                    .referral-card .card-body {
                        padding: 20px;
                        font-family: "Segoe UI", Arial, sans-serif;
                        color: var(--crm-text);
                    }


                    /* =========================================================
       MEMBER SECTION
    ========================================================= */

                    .member-section {
                        display: grid !important;
                        grid-template-columns: 1fr 90px 1fr;
                        align-items: center;
                        gap: 18px;
                        margin-bottom: 18px !important;
                        padding: 16px;
                        background: #fbfdfe;
                        border: 1px solid var(--crm-border);
                        border-radius: 12px;
                    }


                    .member-info-block h6 {
                        display: flex;
                        align-items: center;
                        gap: 6px;
                        margin: 0 0 10px;
                        color: var(--crm-primary);
                        font-size: 12px;
                        font-weight: 700;
                        letter-spacing: .4px;
                        text-transform: uppercase;
                    }

                    .member-info-block h6::before {
                        content: "";
                        width: 6px;
                        height: 6px;
                        background: var(--crm-primary);
                        border-radius: 50%;
                    }


                    .member-info {
                        display: flex;
                        align-items: center;
                        gap: 13px;
                    }


                    /* =========================================================
       AVATAR
    ========================================================= */

                    .avatar {
                        width: 58px;
                        height: 58px;
                        min-width: 58px;
                        border-radius: 50%;
                        object-fit: cover;
                        object-position: top center;
                        border: 2px solid #fff;
                        box-shadow:
                            0 0 0 3px #e4eef3,
                            0 3px 10px rgba(0, 0, 0, .08);
                    }


                    /* =========================================================
       MEMBER DETAILS
    ========================================================= */

                    .member-details .name {
                        color: var(--crm-text);
                        font-size: 15px;
                        font-weight: 700;
                        margin-bottom: 4px;
                    }

                    .member-details small {
                        display: block;
                        color: var(--crm-muted);
                        font-size: 11px;
                    }


                    /* =========================================================
       REFERRAL ARROW
    ========================================================= */

                    .arrow-block {
                        position: relative;
                        height: 55px;
                        flex-direction: column;
                    }

                    .arrow-block::before {
                        content: "";
                        position: absolute;
                        left: 0;
                        right: 0;
                        top: 26px;
                        height: 1px;
                        background: #d5e2e9;
                    }

                    .arrow-block i {
                        position: relative;
                        z-index: 2;
                        width: 34px;
                        height: 34px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: var(--crm-light);
                        border: 4px solid #fff;
                        border-radius: 50%;
                        color: var(--crm-primary);
                        font-size: 12px;
                    }


                    /* =========================================================
       DESCRIPTION
    ========================================================= */

                    .description {
                        padding: 15px 17px;
                        margin-bottom: 17px !important;
                        background: #f8fafb;
                        border-left: 3px solid var(--crm-primary);
                        border-radius: 0 10px 10px 0;
                    }

                    .description h6 {
                        display: flex;
                        align-items: center;
                        gap: 7px;
                        margin: 0 0 7px;
                        color: var(--crm-text);
                        font-size: 13px;
                        font-weight: 700;
                    }

                    .description h6::before {
                        content: "\f05a";
                        font-family: "Font Awesome 5 Free";
                        font-weight: 900;
                        color: var(--crm-primary);
                        font-size: 12px;
                    }

                    .description p {
                        margin: 0;
                        color: #5d6d77;
                        font-size: 12px;
                        line-height: 1.7;
                    }


                    /* =========================================================
       REFERRAL DETAILS
    ========================================================= */

                    .referral-details {
                        margin: 0 !important;
                        padding: 15px;
                        background: #fff;
                        border: 1px solid var(--crm-border);
                        border-radius: 11px;
                    }

                    .referral-details p {
                        display: flex;
                        align-items: center;
                        flex-wrap: wrap;
                        gap: 5px;
                        margin-bottom: 8px;
                        color: #5d6d77;
                        font-size: 12px;
                        line-height: 1.5;
                    }

                    .referral-details p:last-child {
                        margin-bottom: 0;
                    }

                    .referral-details strong {
                        color: #697983;
                        font-size: 11px;
                        font-weight: 700;
                    }

                    .referral-details .text-primary {
                        color: var(--crm-primary) !important;
                        background: var(--crm-light);
                        padding: 4px 8px;
                        border-radius: 5px;
                        font-size: 11px;
                        font-weight: 700;
                    }


                    /* =========================================================
       TEMPERATURE
    ========================================================= */

                    .referral-details p:nth-child(3) {
                        display: inline-flex;
                        padding: 5px 9px;
                        background: #fff8e8;
                        border-radius: 6px;
                    }

                    .referral-details p:nth-child(3) strong {
                        color: #9b7418;
                    }


                    /* =========================================================
       FOOTER
    ========================================================= */

                    .referral-card .card-footer {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        gap: 12px;
                        padding: 12px 20px;
                        background: #f8fafb;
                        border: 0;
                        border-top: 1px solid var(--crm-border);
                        border-radius: 0;
                    }

                    .referral-card .card-footer small {
                        color: var(--crm-muted) !important;
                        font-size: 10px;
                    }


                    /* =========================================================
       ACTION BUTTONS
    ========================================================= */

                    .action-buttons {
                        display: flex;
                        align-items: center;
                        gap: 6px;
                    }

                    .action-buttons .btn-sm {
                        margin: 0;
                        border: 0;
                        border-radius: 7px;
                        padding: 7px 12px;
                        font-size: 11px;
                        font-weight: 600;
                        transition: all .2s ease;
                    }

                    .btn-view {
                        background: var(--crm-primary);
                        color: #fff !important;
                    }

                    .btn-edit {
                        background: var(--crm-accent);
                        color: #fff !important;
                    }

                    .btn-delete {
                        background: #dc3545;
                        color: #fff !important;
                    }

                    .action-buttons .btn-sm:hover {
                        transform: translateY(-1px);
                        box-shadow: 0 4px 10px rgba(0, 0, 0, .12);
                    }


                    /* =========================================================
       PAGINATION
    ========================================================= */

                    .pagination {
                        gap: 5px;
                    }

                    .pagination .page-link {
                        border: 1px solid var(--crm-border);
                        border-radius: 7px !important;
                        color: var(--crm-primary);
                        background: #fff;
                        font-size: 12px;
                        padding: 7px 11px;
                    }

                    .pagination .page-link:hover {
                        background: var(--crm-light);
                        color: var(--crm-primary);
                    }

                    .pagination .active .page-link {
                        background: var(--crm-primary);
                        border-color: var(--crm-primary);
                        color: #fff;
                    }


                    /* =========================================================
       NO RECORDS
    ========================================================= */

                    .text-center.p-5.fs-5 {
                        padding: 40px 20px !important;
                        color: var(--crm-muted);
                        font-size: 15px !important;
                        background: #f8fafb;
                        border: 1px dashed #d7e3e9;
                        border-radius: 12px;
                    }


                    /* =========================================================
       TABLET / MOBILE
    ========================================================= */

                    @media (max-width: 767px) {

                        .referral-card .card-body {
                            padding: 15px;
                        }

                        .member-section {
                            grid-template-columns: 1fr;
                            gap: 14px;
                            padding: 14px;
                        }

                        .arrow-block {
                            height: 30px;
                            flex-direction: row;
                        }

                        .arrow-block::before {
                            left: 20%;
                            right: 20%;
                            top: 14px;
                        }

                        .arrow-block i {
                            width: 30px;
                            height: 30px;
                            font-size: 11px;
                        }

                        .referral-card .card-footer {
                            align-items: flex-start;
                            flex-direction: column;
                            padding: 12px 15px;
                        }

                        .action-buttons {
                            width: 100%;
                        }

                        .action-buttons .btn-sm {
                            flex: 1;
                            padding: 8px 10px;
                            font-size: 11px;
                        }
                    }


                    /* =========================================================
       SMALL MOBILE
    ========================================================= */

                    @media (max-width: 450px) {

                        .referral-card .card-header {
                            padding: 13px 15px;
                        }

                        .referral-card .card-header h5,
                        .referral-card .card-header h6 {
                            font-size: 14px;
                        }

                        .avatar {
                            width: 50px;
                            height: 50px;
                            min-width: 50px;
                        }

                        .member-details .name {
                            font-size: 14px;
                        }

                        .member-details small {
                            font-size: 10px;
                        }

                        .description p {
                            font-size: 11px;
                        }

                        .referral-details p {
                            font-size: 11px;
                        }

                        .referral-details strong {
                            font-size: 10px;
                        }

                    }
                </style>





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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php echo view('include/member/popup/connects.php') ?>
</body>

</html>