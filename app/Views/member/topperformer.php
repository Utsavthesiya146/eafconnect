<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Top Performers</title>
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
    <div id="layout-wrapper">
        <?php echo view('include/member/header.php') ?>
        <?php echo view('include/member/sidebar.php') ?>
        <div class="main-content">
            <div class="page-content">

                <style>
                    .top-performer-section {
                        padding: 8px 0;
                    }

                    .performer-heading {
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        margin-bottom: 16px;
                    }

                    .performer-heading .icon-box {
                        width: 48px;
                        height: 48px;
                        border-radius: 16px;
                        background: linear-gradient(135deg, #08426b, #08426b);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: #fff;
                        font-size: 20px;
                        box-shadow: 0 8px 20px rgba(194, 40, 29, .22);
                        flex-shrink: 0;
                    }

                    .performer-heading h3 {
                        margin: 0;
                        font-size: 22px;
                        font-weight: 800;
                        color: #222;
                    }

                    .performer-heading p {
                        margin: 0;
                        color: #777;
                        font-size: 13px;
                    }

                    .performer-link {
                        text-decoration: none;
                        display: block;
                        height: 100%;
                    }

                    /* SINGLE ROW TOP 5 */
                    .performer-row {
                        display: flex;
                        gap: 14px;
                        overflow-x: auto;
                        padding-bottom: 5px;
                    }

                    .performer-row::-webkit-scrollbar {
                        height: 5px;
                    }

                    .performer-row::-webkit-scrollbar-thumb {
                        background: #ddd;
                        border-radius: 20px;
                    }

                    .performer-col {
                        min-width: 240px;
                        max-width: 240px;
                        flex: 0 0 240px;
                    }

                    .performer-card {
                        position: relative;
                        background: #fff;
                        border-radius: 24px;
                        overflow: hidden;
                        transition: .3s ease;
                        box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
                        border: 1px solid #f1f1f1;
                        height: 100%;
                    }

                    .performer-card:hover {
                        transform: translateY(-5px);
                    }

                    .performer-card.top-rank {
                        border: 2px solid #fac532;
                        box-shadow: 0 10px 28px rgba(255, 179, 0, .22);
                    }

                    .performer-top {
                        height: 70px;
                        background: linear-gradient(135deg, #08426b, #08426b);
                        position: relative;
                    }

                    .top-rank .performer-top {
                        background: linear-gradient(135deg, #fac532, #0a3d61);
                    }

                    .performer-rank {
                        position: absolute;
                        top: 12px;
                        right: 12px;
                        width: 36px;
                        height: 36px;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, .2);
                        color: #fff;
                        font-size: 14px;
                        font-weight: 800;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .king-badge {
                        position: absolute;
                        left: 50%;
                        bottom: -14px;
                        transform: translateX(-50%);
                        width: 34px;
                        height: 34px;
                        border-radius: 50%;
                        background: linear-gradient(135deg, #fac532, #0a3d61);
                        color: #fff;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border: 3px solid #fff;
                        font-size: 15px;
                        z-index: 5;
                    }

                    .performer-body {
                        padding: 0 14px 14px;
                        text-align: center;
                        margin-top: -40px;
                    }

                    /* IMAGE ALIGNMENT FIX */
                    .performer-avatar-wrap {
                        width: 82px;
                        height: 82px;
                        margin: auto;
                        border-radius: 50%;
                        padding: 3px;
                        background: #fff;
                        box-shadow: 0 6px 18px rgba(0, 0, 0, .12);
                        overflow: hidden;
                    }

                    .top-rank .performer-avatar-wrap {
                        background: linear-gradient(135deg, #fac532, #0a3d61);
                    }

                    .performer-avatar {
                        width: 100%;
                        height: 100%;
                        border-radius: 50%;
                        object-fit: cover;
                        object-position: center top;
                        display: block;
                        border: 3px solid #fff;
                        background: #f5f5f5;
                    }

                    .performer-name {
                        font-size: 16px;
                        font-weight: 800;
                        color: #222;
                        margin-top: 10px;
                        line-height: 1.3;
                        min-height: 35px;
                    }

                    .performer-industry {
                        display: inline-flex;
                        align-items: center;
                        gap: 5px;
                        padding: 5px 12px;
                        border-radius: 30px;
                        background: rgba(194, 40, 29, .08);
                        color: #08426b;
                        font-size: 11px;
                        font-weight: 700;
                        margin: 8px 0 14px;
                        max-width: 100%;
                    }

                    .stats-box {
                        border-radius: 18px;
                        background: #fff8f7;
                        padding: 12px 10px;
                        border: 1px solid #ffe2df;
                    }

                    .stats-icon {
                        width: 38px;
                        height: 38px;
                        border-radius: 12px;
                        background: rgba(194, 40, 29, .1);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: #08426b;
                        margin: auto auto 8px;
                        font-size: 15px;
                    }

                    .stats-label {
                        font-size: 10px;
                        color: #777;
                        font-weight: 700;
                        letter-spacing: .5px;
                    }

                    .stats-value {
                        font-size: 20px;
                        font-weight: 800;
                        color: #222;
                        margin-top: 3px;
                    }

                    .empty-card {
                        background: #fff;
                        border-radius: 20px;
                        padding: 30px;
                        text-align: center;
                        border: 1px dashed #ddd;
                        color: #999;
                    }

                    @media(max-width:768px) {

                        .performer-col {
                            min-width: 200px;
                            max-width: 200px;
                            flex: 0 0 200px;
                        }

                        .performer-heading h3 {
                            font-size: 19px;
                        }
                    }

                    /* =========================================
CARD FIX UI
========================================= */

                    .performer-card {
                        position: relative;
                        background: #fff;
                        border-radius: 26px;
                        overflow: hidden;
                        transition: .3s ease;
                        box-shadow: 0 8px 22px rgba(0, 0, 0, .06);
                        border: 1px solid #f0f0f0;
                        height: 100%;
                    }

                    /* TOP HEADER */
                    .performer-top {
                        height: 70px;
                        background: linear-gradient(135deg, #0a3d61, #0a3d61);
                        position: relative;
                    }

                    .top-rank .performer-top {
                        background: linear-gradient(135deg, #fac532, #fac532);
                    }

                    /* BODY */
                    .performer-body {
                        position: relative;
                        padding: 0 7px 9px;
                        text-align: center;
                        margin-top: -48px;
                    }

                    /* =========================================
IMAGE PERFECT ALIGNMENT FIX
========================================= */

                    .performer-avatar-wrap {
                        width: 88px;
                        height: 88px;
                        margin: auto;
                        border-radius: 50%;
                        padding: 4px;
                        background: #fff;
                        position: relative;
                        overflow: hidden;
                        box-shadow: 0 6px 18px rgba(0, 0, 0, .15);
                        border: 4px solid #fff;
                    }

                    /* GOLD BORDER FOR TOP */
                    .top-rank .performer-avatar-wrap {
                        background: linear-gradient(135deg, #fac532, #fac532);
                        padding: 3px;
                    }

                    .performer-avatar {
                        width: 100%;
                        height: 100%;
                        border-radius: 50%;

                        /* IMPORTANT FIX */
                        object-fit: cover;
                        object-position: center top;

                        display: block;
                        background: #f5f5f5;
                    }

                    /* KING BADGE */
                    .king-badge {
                        position: absolute;
                        top: -5px;
                        right: -2px;
                        width: 28px;
                        height: 28px;
                        border-radius: 50%;
                        background: linear-gradient(135deg, #fac532, #fac532);
                        color: #fff;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 12px;
                        border: 2px solid #fff;
                        z-index: 10;
                        box-shadow: 0 4px 10px rgba(0, 0, 0, .15);
                    }

                    /* RANK */
                    .performer-rank {
                        position: absolute;
                        top: 12px;
                        right: 12px;
                        width: 34px;
                        height: 34px;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, .22);
                        color: #fff;
                        font-size: 13px;
                        font-weight: 800;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    /* NAME */
                    .performer-name {
                        font-size: 15px;
                        font-weight: 800;
                        color: #222;
                        margin-top: 10px;
                        line-height: 1.3;
                        min-height: 25px;
                    }

                    /* INDUSTRY */
                    .performer-industry {
                        display: inline-flex;
                        align-items: center;
                        gap: 5px;
                        padding: 6px 12px;
                        border-radius: 30px;
                        background: rgba(214, 40, 31, .08);
                        color: #d6281f;
                        font-size: 11px;
                        font-weight: 700;
                        margin: 8px 0 14px;
                    }

                    /* STATS */
                    .stats-box {
                        border-radius: 20px;
                        background: #fff8f7;
                        padding: 7px 10px;
                        border: 1px solid #ffe2df;
                    }

                    .stats-icon {
                        width: 42px;
                        height: 42px;
                        border-radius: 14px;
                        background: rgba(214, 40, 31, .08);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: #d6281f;
                        margin: auto auto 8px;
                        font-size: 16px;
                    }

                    .stats-label {
                        font-size: 11px;
                        color: #777;
                        font-weight: 700;
                        margin-bottom: 4px;
                        letter-spacing: .5px;
                    }

                    .stats-value {
                        font-size: 18px;
                        font-weight: 800;
                        color: #222;
                    }

                    /* HOVER */
                    .performer-card:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 14px 28px rgba(0, 0, 0, .10);
                    }

                    /* SCROLL ROW */
                    .performer-row {
                        display: flex;
                        gap: 14px;
                        overflow-x: auto;
                        padding-bottom: 8px;
                    }

                    .performer-col {
                        min-width: 235px;
                        max-width: 235px;
                        flex: 0 0 235px;
                    }

                    /* MOBILE */
                    @media(max-width:768px) {

                        .performer-col {
                            min-width: 190px;
                            max-width: 190px;
                            flex: 0 0 190px;
                        }

                        .performer-avatar-wrap {
                            width: 72px;
                            height: 72px;
                        }

                        .performer-name {
                            font-size: 14px;
                        }

                        .stats-value {
                            font-size: 16px;
                        }
                    }

                    /* ===================================
   MOBILE VIEW IMPROVEMENT
=================================== */
                    @media (max-width: 768px) {

                        .top-performer-section {
                            padding: 5px 0;
                        }

                        .performer-heading {
                            gap: 10px;
                            margin-bottom: 12px;
                        }

                        .performer-heading .icon-box {
                            width: 40px;
                            height: 40px;
                            font-size: 16px;
                            border-radius: 12px;
                        }

                        .performer-heading h3 {
                            font-size: 18px;
                            line-height: 1.2;
                            margin-bottom: 2px;
                        }

                        .performer-heading p {
                            font-size: 11px;
                        }

                        /* Better horizontal scrolling */
                        .performer-row {
                            gap: 10px;
                            padding: 5px 10px 10px;
                            scroll-snap-type: x mandatory;
                            -webkit-overflow-scrolling: touch;
                        }

                        .performer-col {
                            min-width: 180px;
                            max-width: 180px;
                            flex: 0 0 180px;
                            scroll-snap-align: start;
                        }

                        .performer-card {
                            border-radius: 20px;
                        }

                        .performer-top {
                            height: 70px;
                        }

                        .performer-body {
                            margin-top: -38px;
                            padding: 0 10px 14px;
                        }

                        .performer-avatar-wrap {
                            width: 68px;
                            height: 68px;
                            border-width: 3px;
                        }

                        .performer-rank {
                            width: 28px;
                            height: 28px;
                            font-size: 11px;
                            top: 8px;
                            right: 8px;
                        }

                        .king-badge {
                            width: 24px;
                            height: 24px;
                            font-size: 10px;
                            top: -2px;
                            right: -2px;
                        }

                        .performer-name {
                            font-size: 13px;
                            line-height: 1.3;
                            min-height: 34px;
                            margin-top: 8px;
                        }

                        .performer-industry {
                            font-size: 10px;
                            padding: 4px 10px;
                            margin: 6px 0 10px;
                            max-width: 100%;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                        }

                        .stats-box {
                            padding: 10px 8px;
                            border-radius: 14px;
                        }

                        .stats-icon {
                            width: 34px;
                            height: 34px;
                            font-size: 13px;
                            margin-bottom: 6px;
                        }

                        .stats-label {
                            font-size: 9px;
                            line-height: 1.2;
                        }

                        .stats-value {
                            font-size: 15px;
                            line-height: 1.2;
                        }

                        .empty-card {
                            padding: 20px;
                            font-size: 13px;
                        }
                    }
                </style>
                <?php
                function performer_card($list, $type = 'business')
                {
                ?>

                    <?php if (!empty($list)) : ?>

                        <div class="performer-row">

                            <?php foreach ($list as $key => $row) : ?>

                                <div class="performer-col">

                                    <a href="<?= base_url('member/profile/details/' . $row['user_code']) ?>"
                                        class="performer-link">

                                        <div class="performer-card <?= ($key == 0) ? 'top-rank' : '' ?>">

                                            <div class="performer-top">

                                                <div class="performer-rank">
                                                    #<?= $key + 1 ?>
                                                </div>

                                                <?php if ($key == 0) : ?>
                                                    <div class="king-badge">
                                                        <i class="fa fa-crown"></i>
                                                    </div>
                                                <?php endif; ?>

                                            </div>

                                            <div class="performer-body">

                                                <!-- IMAGE ALIGNMENT FIX -->
                                                <div class="performer-avatar-wrap">

                                                    <img
                                                        src="<?= get_member_avatar($row['profile_photo'] ?? '', '', $row['user_code'] ?? '') ?>"
                                                        class="performer-avatar"
                                                        alt="<?= esc($row['name']) ?>">

                                                </div>

                                                <div class="performer-name">
                                                    <?= esc($row['name']) ?>
                                                </div>

                                                <div class="stats-box">
                                                    <?php if ($type == 'business') : ?>

                                                        <div class="stats-icon">
                                                            <i class="fa fa-arrow-up"></i>
                                                        </div>

                                                        <div class="stats-label">
                                                            Business Given
                                                        </div>

                                                        <div class="stats-value">
                                                            ₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($row['business_amount']) ?>
                                                        </div>

                                                    <?php elseif ($type == 'business_received') : ?>

                                                        <div class="stats-icon">
                                                            <i class="fa fa-arrow-down"></i>
                                                        </div>

                                                        <div class="stats-label">
                                                            Business Received
                                                        </div>

                                                        <div class="stats-value">
                                                            ₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($row['business_amount_recived']) ?>
                                                        </div>

                                                    <?php else : ?>

                                                        <div class="stats-icon">
                                                            <i class="fa fa-handshake"></i>
                                                        </div>

                                                        <div class="stats-label">
                                                            Connects 
                                                        </div>

                                                        <div class="stats-value">
                                                            <?= $row['referrals'] ?>
                                                        </div>

                                                    <?php endif; ?>

                                                </div>

                                            </div>

                                        </div>

                                    </a>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php else : ?>

                        <div class="empty-card">
                            No Records Found
                        </div>

                    <?php endif; ?>

                <?php } ?><!-- OVERALL BUSINESS TOP 5 -->




                <!-- OVERALL REFERRAL TOP 5 -->

            


                <!-- MY CHAPTER BUSINESS TOP 5 -->

                <div class="top-performer-section mt-4">

                    <div class="performer-heading">

                        <div class="icon-box">
                            <i class="fa fa-building"></i>
                        </div>

                        <div>
                            <h3>My Chapter Business Top 5</h3>
                            <p>Top chapter business members</p>
                        </div>

                    </div>

                    <?php performer_card($chapterBusinessTop, 'business'); ?>

                </div>



                <!-- MY CHAPTER REFERRAL TOP 5 -->

                <div class="top-performer-section mt-4">

                    <div class="performer-heading">

                        <div class="icon-box">
                            <i class="fa fa-users"></i>
                        </div>

                        <div>
                            <h3>My Chapter Connects Top 5</h3>
                            <p>Top chapter Connects members</p>
                        </div>

                    </div>

                    <?php performer_card($chapterReferralTop, 'referral'); ?>

                </div>
            </div>
        </div>
        <?php echo view('include/member/footer.php') ?>
    </div>
    </div>
    <div class="rightbar-overlay"></div>
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