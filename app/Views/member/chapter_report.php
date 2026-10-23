<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Chapter Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets2/css/app.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #eef1f5;
            font-size: 14px;
        }

        /* HERO */
        .chapter-hero {
            background: linear-gradient(135deg, #08426b, #08426b);
            color: #fff;
            padding: 18px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .chapter-hero h2 {
            color: #fff;
            font-size: 20px;
            margin-bottom: 5px;
        }

        .chapter-hero p {
            margin-bottom: 6px;
            font-size: 13px;
        }

        .leader-img {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            border: 2px solid #d4af37;
            object-fit: cover;
        }

        /* BUTTON */
        .btn-download {
            background: #08426b;
            color: #fff;
            padding: 6px 14px;
            font-size: 13px;
            border-radius: 6px;
        }

        .btn-download:hover {
            background: #5a0e10;
            color: #fff;
        }

        /* STAT CARDS */
        .stat-card {
            border-radius: 10px;
            padding: 12px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .stat-red {
            background: #08426b;
        }

        .stat-yellow {
            background: #d4af37;
        }

        .stat-dark {
            background: #2f3542;
        }

        .stat-card h6 {
            color: #fff;
            font-size: 12px;
            margin-bottom: 3px;
        }

        .stat-card h3 {
            color: #fff;
            font-size: 18px;
            margin: 0;
        }

        .stat-card i {
            font-size: 20px;
            opacity: 0.2;
            position: absolute;
            right: 10px;
            bottom: 8px;
        }

        /* TOP PERFORMER */
        .card {
            border-radius: 10px;
        }

        .card-body {
            padding: 12px;
        }

        /* TABLE */
        .report-table {
            font-size: 13px;
        }

        .report-table thead {
            background: #1a4d8d;
            color: #fff;
        }

        .report-table th,
        .report-table td {
            padding: 6px 8px;
            vertical-align: middle;
        }

        .report-table tbody tr:hover {
            background: #f1f3f6;
        }

        .badge-role {
            background: #d4af37;
            color: #000;
            padding: 3px 6px;
            font-size: 11px;
            border-radius: 5px;
        }

        /* CARD HEADER */
        .card-header {
            padding: 10px 12px;
            font-size: 14px;
        }
    </style>
</head>

<body data-sidebar="colored">

    <div id="layout-wrapper">

        <?php echo view('include/member/header.php') ?>
        <?php echo view('include/member/sidebar.php') ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container mt-4">

                    <!-- ================= HERO ================= -->
                    <div class="chapter-hero mb-4">
                        <div class="row align-items-center">

                            <div class="col-md-8">
                                <h2><?= esc($chapter_info['chapter_name']) ?></h2>
                                <p><i class="fa fa-map-marker-alt"></i> <?= esc($chapter_info['location']) ?></p>
                                <span class="badge bg-warning text-dark">Active Chapter</span>
                            </div>

                            <div class="col-md-4 text-end">
                                <img src="<?= base_url('uploads/profile/' . ($chapter_info['leader_photo'] ?? 'empty.png')) ?>" class="leader-img" style="object-fit: cover; object-position: top center;">
                                <p class="mb-0 mt-2"><strong><?= $chapter_info['leader_name'] ?></strong></p>
                                <small>Chapter Leader</small>
                            </div>

                        </div>
                    </div>

                    <!-- ================= BUTTON ================= -->
                    <a href="<?= base_url('member/chapter/download_pdf/' . $chapter_code) ?>"
                        class="btn mb-3"
                        style="background:#08426b;color:#fff;">
                        <i class="fa fa-file-pdf"></i> Download Report
                    </a>

                    <!-- ================= STATS ================= -->
                    <div class="row mb-4">

                        <?php
                        $totalMITB = array_sum(array_column($report, 'mitb_given'));
                        ?>

                        <div class="col-md-2">
                            <div class="stat-card stat-red">
                                <h6>Members</h6>
                                <h3><?= count($report) ?></h3>
                                <i class="fa fa-users"></i>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="stat-card stat-yellow">
                                <h6>F2F</h6>
                                <h3><?= array_sum(array_column($report, 'f2f')) ?></h3>
                                <i class="fa fa-handshake"></i>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="stat-card stat-red">
                                <h6>Connections</h6>
                                <h3><?= array_sum(array_column($report, 'connections')) ?></h3>
                                <i class="fa fa-link"></i>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="stat-card stat-yellow">
                                <h6>Visitors</h6>
                                <h3><?= array_sum(array_column($report, 'visitors')) ?></h3>
                                <i class="fa fa-user-check"></i>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="stat-card stat-red">
                                <h6>Total Business (MITB)</h6>
                                <h3>₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($totalMITB) ?></h3>
                                <i class="fa fa-rupee-sign"></i>
                            </div>
                        </div>

                    </div>

                    <!-- ================= TOP PERFORMER ================= -->
                    <?php if (!empty($report)): ?>

                        <?php
                        $top = array_reduce($report, function ($a, $b) {

                            if ($a === null) return $b;

                            $a_total = ($a['mitb_given'] ?? 0);
                            $b_total = ($b['mitb_given'] ?? 0);

                            return ($a_total > $b_total) ? $a : $b;
                        });
                        ?>

                        <?php if (!empty($top)): ?>
                            <div class="card mb-4 shadow-sm" style="border-left:5px solid #ffc107;">
                                <div class="card-body">
                                    <h5>🏆 Top Performer</h5>
                                    <h4><?= esc($top['name'] ?? '-') ?></h4>
                                    <p>
                                        Total Business: ₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format(
                                                                ($top['mitb_given'] ?? 0)
                                                            ) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>

                    <!-- ================= TABLE ================= -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            Member Wise Report
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered report-table text-center">

                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>F2F</th>
                                        <th>Ref G</th>
                                        <th>Ref R</th>
                                        <th>Conn</th>
                                        <th>Visit</th>
                                        <th>Inv</th>
                                        <th>MITB G</th>
                                        <th>MITB R</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $i = 1;
                                    foreach ($report as $row): ?>
                                        <tr>

                                            <td><?= $i++ ?></td>

                                            <td>
                                                <strong><?= esc($row['name']) ?></strong><br>
                                                <small><?= esc($row['phone']) ?></small>
                                            </td>

                                            <td><span class="badge-role"><?= esc($row['role']) ?></span></td>

                                            <td><?= $row['f2f'] ?></td>
                                            <td><?= $row['ref_given'] ?></td>
                                            <td><?= $row['ref_received'] ?></td>
                                            <td><strong><?= $row['connections'] ?></strong></td>
                                            <td><?= $row['visitors'] ?></td>
                                            <td><?= $row['invites'] ?></td>

                                            <td class="text-success">₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($row['mitb_given'] ?? 0, 2) ?></td>
                                            <td class="text-primary">₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($row['mitb_received'] ?? 0, 2) ?></td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <?php echo view('include/member/footer.php') ?>

        </div>
    </div>

    <script src="<?= base_url(); ?>assets2/libs/jquery/jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url(); ?>assets2/js/app.js"></script>

</body>

</html>