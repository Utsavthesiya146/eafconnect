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
        <?php echo view('include/admin/header.php') ?>
        <?php echo view('include/admin/sidebar.php') ?>
        <div class="main-content">
            <div class="page-content">
                <div class="row p-2">
                    <div class="col-md-6 col-6">
                        <h5>Manage Connects Details:</h5>
                    </div>
                    <div class="col-md-6 col-6">
                        <a class="btn btn-primary btn-sm" onclick="add_new_connects();" style="font-size: 12px; font-weight: 500; float: right;background-color:#08426b;margin:2px;"> Add New Connects <i class="fa fa-plus-circle ml-1"></i></a>
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
                                            <span class="badge status <?= $row['status'] ?>" onclick="edit_refferral_status('<?= $row['referral_code'] ?>');"><?= esc(ucfirst($row['status'])) ?></span>
                                        </div>

                                        <!-- Card Body -->
                                        <div class="card-body">
                                            <div class="member-section d-flex justify-content-between mb-3">
                                                <!-- Given By -->
                                                <div class="member-info-block">
                                                    <h6>Given By</h6>
                                                    <div class="member-info">
                                                        <img src="<?= base_url('uploads/profile/' . ($row['from_profile'] ?? 'empty.png')) ?>" class="avatar">
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
                                                        <img src="<?= base_url('uploads/profile/' . ($row['to_profile'] ?? 'empty.png')) ?>" class="avatar">
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
                                                    <p><strong>Note:</strong> <?= esc($row['admin_note'] ?? '-') ?></p>
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
                                            <div class="action-buttons">
                                                <a class="btn btn-sm btn-edit" onclick="edit_refferral('<?= $row['referral_code'] ?>');"><i class="fa fa-edit"></i></a>
                                                <a class="btn btn-sm btn-delete" onclick="delete_refferral('<?= $row['referral_code'] ?>');"><i class="fa fa-trash"></i></a>
                                            </div>
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
                    /* Referral Card */
                    .referral-card {
                        background: #fff;
                        border-radius: 16px;
                        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.08);
                        transition: all 0.3s ease;
                        display: flex;
                        flex-direction: column;
                    }

                    .referral-card:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
                    }

                    /* Card Header */
                    .card-header {
                        background-color: #08426b;
                        /* Theme Red */
                        color: #fff;
                        font-weight: 600;
                        padding: 16px 20px;
                        border-top-left-radius: 16px;
                        border-top-right-radius: 16px;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        font-size: 18px;
                    }

                    /* Status Badges */
                    .status {
                        padding: 6px 14px;
                        border-radius: 20px;
                        font-size: 13px;
                        font-weight: 600;
                        text-transform: capitalize;
                    }

                    .status.Hot {
                        background: #08426b;
                        color: #fff;
                    }

                    .status.Warm {
                        background: #ffc107;
                        color: #333;
                    }

                    /* Card Body */
                    .card-body {
                        padding: 20px;
                        font-family: 'Segoe UI', sans-serif;
                        color: #444;
                    }

                    .member-section {
                        gap: 16px;
                        align-items: center;
                    }

                    .member-info-block h6 {
                        font-size: 14px;
                        font-weight: 600;
                        margin-bottom: 6px;
                    }

                    .member-info {
                        display: flex;
                        align-items: center;
                        gap: 12px;
                    }

                    .avatar {
                        width: 52px;
                        height: 52px;
                        border-radius: 50%;
                        object-fit: cover;
                        border: 2px solid #eee;
                    }

                    .member-details .name {
                        font-weight: 600;
                        font-size: 14px;
                    }

                    .member-details small {
                        font-size: 12px;
                        color: #666;
                    }

                    .arrow-block i {
                        font-size: 20px;
                        color: #ffc107;
                        /* Accent gold */
                    }

                    /* Description */
                    .description h6 {
                        margin-bottom: 6px;
                        font-weight: 600;
                    }

                    .description p {
                        margin-bottom: 0;
                        font-size: 14px;
                        color: #555;
                    }

                    /* Referral Details */
                    .referral-details p {
                        margin-bottom: 4px;
                        font-size: 14px;
                    }

                    /* Card Footer */
                    .card-footer {
                        background: #f5f5f5;
                        padding: 12px 20px;
                        border-bottom-left-radius: 16px;
                        border-bottom-right-radius: 16px;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    }

                    .action-buttons .btn-sm {
                        margin-left: 6px;
                        border-radius: 10px;
                        padding: 6px 12px;
                        font-size: 13px;
                        transition: all 0.2s;
                    }

                    .btn-view {
                        background-color: #184b8d;
                        color: #fff;
                    }

                    .btn-edit {
                        background-color: #ffc107;
                        color: #fff;
                    }

                    .btn-delete {
                        background-color: #08426b;
                        color: #fff;
                    }

                    .btn-sm:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12);
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
    <?php echo view('include/admin/popup/connects.php') ?>
</body>

</html>