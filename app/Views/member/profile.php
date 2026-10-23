<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts & Master KVV/BNI Revamp Theme -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets2/css/eaf-revamp.css'); ?>?v=3.0">
    <style>
        .profile-banner {
            background: linear-gradient(135deg, #101B35 0%, #172B50 55%, #0B1325 100%);
            border: 1px solid rgba(198, 166, 103, 0.32);
            padding: 24px 28px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(16, 27, 53, 0.18);
            position: relative;
            overflow: hidden;
        }

        .profile-banner::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 250px;
            height: 100%;
            background: radial-gradient(circle at top right, rgba(198, 166, 103, 0.12), transparent 70%);
            pointer-events: none;
        }

        /* PROFILE IMAGE */
        .profile-img-wrapper {
            position: relative;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 18px;
            object-fit: cover;
            border: 3px solid #C6A667;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        }

        /* CAMERA ICON */
        .camera-icon {
            position: absolute;
            bottom: 3px;
            right: 3px;
            background: #C6A667;
            width: 28px;
            height: 28px;
            font-size: 13px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #101B35;
            border: 2px solid #FFFFFF;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .25);
        }

        /* USER TEXT AREA */
        .profile-banner h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 4px !important;
        }

        .profile-banner .mb-2 {
            margin-bottom: 6px !important;
            font-size: 14px;
            color: #CBD5E1;
        }

        /* BADGES */
        .badge-pill,
        .badge-pill-dark {
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 50px;
        }

        /* SAVE BUTTON */
        .save-btn {
            background: linear-gradient(135deg, #C6A667, #D4B679);
            color: #101B35;
            padding: 8px 22px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            border: none;
            box-shadow: 0 4px 12px rgba(198, 166, 103, 0.3);
            transition: all 0.25s ease;
        }

        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(198, 166, 103, 0.4);
            color: #101B35;
        }

        /* STATS BOX */
        .stats-box {
            background: #fff;
            border-radius: 0 0 16px 16px;
            padding: 16px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .05);
            margin-top: -6px;
            border: 1px solid #E2E8F0;
            border-top: none;
        }

        .stats-box h6 {
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            margin-bottom: 5px;
        }

        .stats-box h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #101B35;
            margin: 0;
        }

        /* ===== Cards ===== */
        .profile-card {
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(15, 59, 104, .05);
            background: #FFFFFF;
        }

        /* ===== Card Header ===== */
        .profile-header {
            background: #fff;
            border-bottom: 1px solid #F1F5F9;
            padding: 16px 20px;
            font-weight: 700;
            color: #0F3B68;
        }

        /* ===== Inputs ===== */
        .form-control,
        .form-select {
            border-radius: 10px;
            height: 44px;
            font-size: 14px;
            border: 1px solid #CBD5E1;
        }

        textarea.form-control {
            height: auto;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0F3B68;
            box-shadow: 0 0 0 .15rem rgba(15, 59, 104, .18);
        }

        /* ===== Labels ===== */
        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        /* ===== Sidebar ===== */
        .profile-sidebar {
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(15, 59, 104, .05);
            background: #FFFFFF;
        }

        /* ===== Sidebar Tabs ===== */
        .profile-sidebar .nav-link {
            border-radius: 10px;
            color: #475569;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 16px;
            transition: .2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-sidebar .nav-link:hover {
            background: rgba(15, 59, 104, 0.06);
            color: #0F3B68;
        }

        .profile-sidebar .nav-link.active {
            background: linear-gradient(135deg, #0F3B68, #1E293B);
            color: #fff;
            border-left: 3px solid #D97706;
            box-shadow: 0 4px 12px rgba(15, 59, 104, 0.2);
        }

        /* ===== Section spacing ===== */
        .profile-section .row>div {
            margin-bottom: 10px;
        }

        /* ===== Badge ===== */
        .badge {
            font-size: 12px;
        }

        /* Sticky sidebar */
        .sticky-card {
            position: sticky;
            top: 20px;
        }

        /* reduce card spacing */
        .card {
            margin-bottom: 14px !important;
        }

        /* reduce card body padding */
        .card-body {
            padding: 18px !important;
        }

        /* compact header */
        .card-header {
            padding: 10px 18px !important;
        }

        /* reduce row spacing */
        .row {
            --bs-gutter-y: .4rem;
        }

        /* form label smaller */
        .form-label {
            margin-bottom: 4px;
            font-size: 13px;
        }

        /* compact inputs */
        .form-control,
        .form-select {
            height: 38px;
            padding: 6px 10px;
            font-size: 14px;
        }

        textarea.form-control {
            min-height: 70px;
        }

        /* sidebar buttons compact */
        .nav-pills .nav-link {
            padding: 9px 12px !important;
            font-size: 14px;
        }

        /* remove extra tab gap */
        .tab-pane {
            margin-top: 0;
        }

        /* smaller section title */
        .card-header h5 {
            font-size: 16px;
        }

        /* reduce column spacing */
        .mb-3 {
            margin-bottom: .6rem !important;
        }

        /* reduce large spacing */
        .p-4 {
            padding: 1rem !important;
        }

        /* sidebar card padding */
        .card-body.p-2 {
            padding: .6rem !important;
        }

        /* banner spacing */
        .profile-banner {
            padding: 18px 22px;
        }

        /* profile image */
        .profile-img {
            width: 85px;
            height: 85px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
        }

        /* camera icon position */
        .camera-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #ed2d7b;
            color: #fff;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
        }

        /* save button alignment */
        .save-btn {
            white-space: nowrap;
            padding: 8px 16px;
        }

        /* stats box */
        .stats-box {
            background: #fff;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            margin-top: -6px;
        }

        /* stat items */
        .stat-item {
            padding: 14px 10px;
        }

        .stat-item h6 {
            font-size: 12px;
            color: #777;
            margin-bottom: 3px;
        }

        .stat-item h3 {
            font-weight: 700;
        }

        /* mobile fix */
        @media(max-width:768px) {

            .profile-banner .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 12px;
            }

            .save-btn {
                width: 100%;
                text-align: center;
            }
        }/* =========================================================
   PROFILE BANNER
   Theme:
   Primary  #0a3d61
   Accent   #fac532
========================================================= */

.profile-banner {
    position: relative;

    width: 100%;

    padding: 16px 20px;

    overflow: hidden;

    border-radius: 12px;

    background:
        linear-gradient(
            135deg,
            #0a3d61 0%,
            #0d4d76 55%,
            #0a3d61 100%
        );

    box-shadow:
        0 6px 20px rgba(10, 61, 97, .15);
}


/* =========================================================
   SUBTLE DECORATIVE ELEMENT
========================================================= */

.profile-banner::after {
    content: "";

    position: absolute;

    width: 180px;
    height: 180px;

    right: -70px;
    top: -90px;

    border-radius: 50%;

    background: rgba(250, 197, 50, .08);

    pointer-events: none;
}


/* =========================================================
   INNER
========================================================= */

.profile-banner-inner {
    position: relative;

    z-index: 2;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;
}


/* =========================================================
   PROFILE INFO
========================================================= */

.profile-info {
    display: flex;

    align-items: center;

    min-width: 0;

    gap: 14px;
}


/* =========================================================
   PROFILE IMAGE
========================================================= */

.profile-img-wrapper {
    position: relative;

    flex: 0 0 68px;

    width: 68px;
    height: 68px;

    padding: 3px;

    border-radius: 50%;

    background: #fac532;

    box-shadow:
        0 3px 10px rgba(0, 0, 0, .18);
}


.profile-img {
    width: 100%;
    height: 100%;

    display: block;

    border-radius: 50%;

    border: 2px solid #fff;

    background: #eef2f4;
}


/* =========================================================
   USER DETAILS
========================================================= */

.profile-details {
    min-width: 0;
}


/* =========================================================
   NAME
========================================================= */

.profile-name {
    margin: 0 0 4px;

    color: #fff;

    font-size: 20px;

    font-weight: 700;

    line-height: 1.2;

    letter-spacing: -.2px;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;
}


/* =========================================================
   BIO
========================================================= */

.profile-bio {
    display: flex;

    align-items: center;

    gap: 6px;

    max-width: 650px;

    margin-bottom: 8px;

    color: rgba(255,255,255,.78);

    font-size: 11px;

    line-height: 1.4;
}


.profile-bio i {
    flex: 0 0 auto;

    color: #fac532;

    font-size: 10px;
}


/* =========================================================
   BADGES
========================================================= */

.profile-badges {
    display: flex;

    align-items: center;

    flex-wrap: wrap;

    gap: 6px;
}


/* =========================================================
   COMMON BADGE
========================================================= */

.profile-badge {
    display: inline-flex;

    align-items: center;

    gap: 5px;

    padding: 4px 8px;

    border-radius: 5px;

    font-size: 9px;

    font-weight: 600;

    line-height: 1.2;

    white-space: nowrap;
}


.profile-badge i {
    font-size: 8px;
}


/* ROLE */

.role-badge {
    background: rgba(250, 197, 50, .16);

    border: 1px solid rgba(250, 197, 50, .3);

    color: #fac532;
}


/* SINCE */

.since-badge {
    background: rgba(255,255,255,.10);

    border: 1px solid rgba(255,255,255,.13);

    color: rgba(255,255,255,.78);
}


/* =========================================================
   PROFILE ACTION
========================================================= */

.profile-action {
    position: relative;

    flex: 0 0 auto;

    display: inline-flex;

    align-items: center;

    gap: 7px;

    padding: 8px 12px;

    border: 1px solid rgba(250,197,50,.7);

    border-radius: 7px;

    background: #fac532;

    color: #0a3d61;

    font-size: 11px;

    font-weight: 700;

    text-decoration: none;

    box-shadow: 0 3px 8px rgba(0,0,0,.12);

    transition:
        transform .2s ease,
        background .2s ease,
        box-shadow .2s ease;
}


.profile-action:hover {
    background: #ffd45b;

    color: #0a3d61;

    text-decoration: none;

    transform: translateY(-1px);

    box-shadow: 0 5px 14px rgba(0,0,0,.18);
}


/* ACTION ICON */

.profile-action-icon {
    width: 22px;
    height: 22px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 5px;

    background: rgba(10,61,97,.10);
}


.profile-action-icon i {
    font-size: 9px;
}


/* ARROW */

.profile-arrow {
    margin-left: 2px;

    font-size: 8px;

    transition: transform .2s ease;
}


.profile-action:hover .profile-arrow {
    transform: translateX(3px);
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 767px) {

    .profile-banner {
        padding: 13px 14px;

        border-radius: 10px;
    }

    .profile-banner-inner {
        gap: 12px;
    }

    .profile-info {
        gap: 11px;
    }

    .profile-img-wrapper {
        flex: 0 0 58px;

        width: 58px;
        height: 58px;
    }

    .profile-name {
        font-size: 17px;
    }

    .profile-bio {
        margin-bottom: 6px;

        font-size: 10px;
    }

    .profile-badge {
        padding: 3px 7px;

        font-size: 8px;
    }

    .profile-action {
        padding: 7px 9px;

        font-size: 10px;
    }

    .profile-action-icon {
        width: 20px;
        height: 20px;
    }
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 575px) {

    .profile-banner {
        padding: 11px;

        border-radius: 9px;
    }

    .profile-banner-inner {
        align-items: center;

        gap: 9px;
    }

    .profile-info {
        gap: 9px;
    }

    .profile-img-wrapper {
        flex: 0 0 48px;

        width: 48px;
        height: 48px;

        padding: 2px;
    }

    .profile-name {
        max-width: 180px;

        margin-bottom: 3px;

        font-size: 15px;
    }

    .profile-bio {
        max-width: 190px;

        margin-bottom: 5px;

        font-size: 9px;

        white-space: nowrap;

        overflow: hidden;

        text-overflow: ellipsis;
    }

    .profile-bio i {
        font-size: 8px;
    }

    .profile-badges {
        gap: 4px;
    }

    .profile-badge {
        padding: 3px 6px;

        font-size: 7px;
    }

    .profile-badge i {
        font-size: 7px;
    }

    .profile-action {
        padding: 6px 8px;

        border-radius: 6px;

        font-size: 9px;
    }

    .profile-action-icon {
        width: 18px;
        height: 18px;
    }

    .profile-action-icon i {
        font-size: 8px;
    }

    .profile-arrow {
        display: none;
    }
}


/* =========================================================
   VERY SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .profile-banner {
        padding: 9px;
    }

    .profile-img-wrapper {
        flex: 0 0 43px;

        width: 43px;
        height: 43px;
    }

    .profile-name {
        max-width: 145px;

        font-size: 14px;
    }

    .profile-bio {
        max-width: 145px;

        font-size: 8px;
    }

    .since-badge {
        display: none;
    }

    .profile-action {
        padding: 6px;

        font-size: 8px;
    }

    .profile-action-icon {
        width: 18px;
        height: 18px;
    }
}
    </style>
</head>

<body data-sidebar="colored">

    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php echo view('include/member/header.php') ?>
        <?php echo view('include/member/sidebar.php') ?>
        <div class="main-content">
            <div class="page-content">
                <?php foreach ($member as $row) { ?>
                   
                    <div class="profile-banner">

                        <div class="profile-banner-inner">

                            <!-- LEFT -->
                            <div class="profile-info">

                                <!-- PROFILE IMAGE -->
                                <?php $bAvatar = get_member_avatar($row->profile_photo ?? '', $row->gender ?? '', $row->user_code ?? '', true); ?>
                                <div class="profile-img-wrapper position-relative">

                                    <img src="<?= $bAvatar['url'] ?>"
                                        class="profile-img"
                                        alt="<?= esc($row->name) ?>"
                                        style="object-fit: cover; object-position: top center; border: 3px solid #C6A667;"
                                        onerror="this.onerror=null; this.src='<?= base_url('images/empty.png'); ?>';">

                                </div>


                                <!-- USER DETAILS -->
                                <div class="profile-details">

                                    <h2 class="profile-name">
                                        <?= esc($row->name) ?>
                                    </h2>

                                    <?php 
                                    $pBio = trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $row->bio ?? ''));
                                    if (empty($pBio)) $pBio = 'Active business member in EAF Connect.';
                                    ?>
                                    <div class="profile-bio" style="max-width:760px; line-height:1.6; font-size:13px; color:#CBD5E1; margin:6px 0 10px;">
                                        <i class="ri-briefcase-3-line" style="color:#C6A667; margin-right:4px;"></i>
                                        <span style="display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;"><?= esc($pBio) ?></span>
                                    </div>


                                    <!-- BADGES -->
                                    <div class="profile-badges">

                                        <?php if (!empty($row->member_role)) : ?>

                                            <span class="profile-badge role-badge">

                                                <i class="fas fa-user-tag"></i>

                                                <?= esc($row->member_role) ?>

                                            </span>

                                        <?php endif; ?>


                                        <span class="profile-badge since-badge">

                                            <i class="fas fa-calendar-alt"></i>

                                            Since <?= date('M Y', strtotime($row->join_date ?? $row->created_at)) ?>

                                        </span>

                                    </div>

                                </div>

                            </div>


                            <!-- PROFILE BUTTON -->
                            <a href="<?= base_url('member/profile/details/' . esc($row->user_code)) ?>"
                                class="profile-action">

                                <span class="profile-action-icon">
                                    <i class="fas fa-user"></i>
                                </span>

                                <span>Profile</span>

                                <i class="fas fa-arrow-right profile-arrow"></i>

                            </a>

                        </div>

                    </div>
                  
                    <?php
                    /* ======================================================
   LOAD MODELS
====================================================== */
                    $commonModel = new \App\Models\CommonModel();
                    $mitbModel   = new \App\Models\TyfcbModel();

                    $u = $row->user_code;

                    /* ======================================================
   F2F COUNT (One to One Meetings)
====================================================== */
                    $f2fCount = $commonModel
                        ->exe_query("SELECT f2f_id FROM one_to_ones WHERE from_user='$u'")
                        ->getNumRows();

                    /* ======================================================
   CONNECTION COUNT (Referrals Given)
====================================================== */
                    $conGiven = $commonModel
                        ->exe_query("SELECT referral_id FROM referrals WHERE from_user='$u'")
                        ->getNumRows();

                    /* ======================================================
   CONNECTION COUNT (Referrals Received)
====================================================== */
                    $conReceived = $commonModel
                        ->exe_query("SELECT referral_id FROM referrals WHERE to_user='$u'")
                        ->getNumRows();

                    /* ======================================================
   VISITORS COUNT
====================================================== */
                    $visCount = $commonModel
                        ->exe_query("SELECT visitors_id FROM visitors WHERE user_code='$u'")
                        ->getNumRows();

                    /* ======================================================
   EVENTS ATTENDED
   (change table name if different)
====================================================== */
                    $eventCount = $commonModel
                        ->exe_query("SELECT event_a_id FROM event_attendance WHERE user_code='$u'")
                        ->getNumRows();

                    /* ======================================================
   MITB TOTAL (Given + Received)
====================================================== */
                    $mitb = $mitbModel
                        ->selectSum('amount')
                        ->groupStart()
                        ->where('from_user', $u)
                        ->orWhere('to_user', $u)
                        ->groupEnd()
                        ->first();

                    $mitbAmount = $mitb['amount'] ?? 0;
                    ?>
                    <div class="row text-center stats-box m-1">

                        <!-- CONNECTS GIVEN -->
                        <div class="col-md-4 col-4 border-end">
                            <h6>Connects Given</h6>
                            <h2 class="text-danger"><?= $conGiven ?></h2>
                        </div>

                        <!-- CONNECTS RECEIVED -->
                        <div class="col-md-4 col-4 border-end">
                            <h6>Connects Received</h6>
                            <h2 class="text-success"><?= $conReceived ?></h2>
                        </div>

                        <!-- EVENTS ATTENDED -->
                        <div class="col-md-4 col-4">
                            <h6>Meetings Attended</h6>
                            <h2 class="text-primary"><?= $eventCount ?></h2>
                        </div>

                    </div>

                    <div class="row mt-3">
                        <!-- Sidebar Tabs -->
                        <div class="col-lg-3">
                            <div class="card border-0 shadow-sm rounded-4 mb-4 position-sticky" style="top: 20px;">
                                <div class="card-body p-2">
                                    <div class="nav flex-column nav-pills" id="profile-tabs" role="tablist">
                                        <button class="nav-link active py-3 text-start fw-bold" id="personal-tab" data-bs-toggle="pill"
                                            data-bs-target="#personal" type="button" role="tab"><i class="fas fa-user-circle fa-fw me-3"></i>
                                            Personal Information</button>
                                        <button class="nav-link py-3 text-start fw-bold" id="professional-tab" data-bs-toggle="pill"
                                            data-bs-target="#professional" type="button" role="tab"><i
                                                class="fas fa-graduation-cap fa-fw me-3"></i> Professional Info</button>
                                        <button class="nav-link py-3 text-start fw-bold" id="business-tab" data-bs-toggle="pill"
                                            data-bs-target="#business" type="button" role="tab"><i class="fas fa-building fa-fw me-3"></i>
                                            Business Details</button>
                                        <button class="nav-link py-3 text-start fw-bold" id="location-tab" data-bs-toggle="pill"
                                            data-bs-target="#location" type="button" role="tab"><i
                                                class="fas fa-map-marked-alt fa-fw me-3"></i> Location & Hours</button>
                                        <button class="nav-link py-3 text-start fw-bold" id="social-tab" data-bs-toggle="pill"
                                            data-bs-target="#social" type="button" role="tab"><i class="fas fa-globe fa-fw me-3"></i> Social
                                            Presence</button>
                                        <button class="nav-link py-3 text-start fw-bold" id="additional-tab" data-bs-toggle="pill"
                                            data-bs-target="#additional" type="button" role="tab"><i
                                                class="fas fa-info-circle fa-fw me-3"></i> Additional Info</button>
                                        <button class="nav-link py-3 text-start fw-bold" id="media-tab" data-bs-toggle="pill"
                                            data-bs-target="#media" type="button" role="tab"><i class="fas fa-images fa-fw me-3"></i> Media &
                                            Docs</button>
                                        <button class="nav-link py-3 text-start fw-bold text-danger border-danger" id="password-tab"
                                            data-bs-toggle="pill" data-bs-target="#password" type="button" role="tab"><i
                                                class="fas fa-key fa-fw me-3"></i> Change Password</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div class="col-lg-9">
                            <div class="tab-content" id="profile-tabs-content">
                                <!-- 1. Personal Information -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                                        <div class="card-header bg-white border-bottom border-danger border-opacity-25 py-0 px-4">
                                            <h5 class="mb-0 fw-bold text-danger d-flex align-items-center" style="height: 60px;">
                                                <i class="fas fa-user-circle fa-fw me-2"></i> <span>Personal Information</span>
                                            </h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <form id="updateMemberForm" method="post">
                                                <!-- IMPORTANT ID -->
                                                <input type="hidden" name="user_code" value="<?= esc($row->user_code); ?>">
                                                <div class="row">
                                                    <div class="col-md-12 mb-2">
                                                        <label class="form-label fw-bold">Full Name *</label>
                                                        <input type="text" class="form-control" name="name"
                                                            value="<?= esc($row->name); ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-bold">Gender</label>
                                                        <select class="form-select" name="gender">
                                                            <option value="">Select Gender</option>
                                                            <option value="Male" <?= $row->gender == 'Male' ? 'selected' : '' ?>>Male</option>
                                                            <option value="Female" <?= $row->gender == 'Female' ? 'selected' : '' ?>>Female</option>
                                                            <option value="Other" <?= $row->gender == 'Other' ? 'selected' : '' ?>>Other</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-bold">Date of Birth</label>
                                                        <input type="date" class="form-control" name="dob"
                                                            value="<?= esc($row->dob); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-bold">Email *</label>
                                                        <input type="email" class="form-control" name="email"
                                                            value="<?= esc($row->email); ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-bold">Phone</label>
                                                        <input type="text" class="form-control" name="phone"
                                                            value="<?= esc($row->phone); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-bold">Alternate Phone</label>
                                                        <input type="text" class="form-control" name="alt_phone"
                                                            value="<?= esc($row->alt_phone); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-bold">WhatsApp</label>
                                                        <input type="text" class="form-control" name="whatsapp"
                                                            value="<?= esc($row->whatsapp); ?>">
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label class="form-label fw-bold">Address</label>
                                                        <textarea class="form-control" name="address"><?= esc($row->address); ?></textarea>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label fw-bold">City</label>
                                                        <input type="text" class="form-control" name="city"
                                                            value="<?= esc($row->city); ?>">
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label fw-bold">State</label>
                                                        <input type="text" class="form-control" name="state"
                                                            value="<?= esc($row->state); ?>">
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label fw-bold">Pincode</label>
                                                        <input type="text" class="form-control" name="pincode"
                                                            value="<?= esc($row->pincode); ?>">
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <button type="submit"
                                                        class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm">
                                                        <i class="fas fa-shield-alt me-2"></i> Update
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Professional Information -->
                                <div class="tab-pane fade" id="professional" role="tabpanel">
                                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                                        <div class="card-header bg-white border-bottom border-danger border-opacity-25 py-0 px-4">
                                            <h5 class="mb-0 fw-bold text-danger d-flex align-items-center" style="height: 60px;">
                                                <i class="fas fa-graduation-cap fa-fw me-2"></i> <span>Professional & Skills</span>
                                            </h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <form id="updateProfessionalSkillsForm" method="post">
                                                <div class="row">
                                                    <div class="col-md-8 mb-2">
                                                        <input type="hidden" name="user_code" value="<?= esc($row->user_code); ?>">
                                                        <label class="form-label fw-bold">Designation</label>
                                                        <input type="text" class="form-control" name="designation"
                                                            value="<?= esc($row->designation) ?>" placeholder="e.g. Managing Director">
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label fw-bold">Years of Exp.</label>
                                                        <input type="number" class="form-control" name="exp_years"
                                                            value="<?= esc($row->exp_years) ?>">
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label class="form-label fw-bold">Skills / Expertise</label>
                                                        <input type="text" class="form-control" name="skills"
                                                            value="<?= esc($row->skills) ?>"
                                                            placeholder="e.g. Strategic Planning, Team Leadership">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-bold">Services Offered</label>
                                                        <textarea class="form-control" name="services"
                                                            rows="2"><?= esc($row->services) ?></textarea>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-bold">Products Offered</label>
                                                        <textarea class="form-control" name="products"
                                                            rows="2"><?= esc($row->products) ?></textarea>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label class="form-label fw-bold">Bio / About Me</label>
                                                        <textarea class="form-control" name="bio"
                                                            rows="4"><?= esc($row->bio) ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <button type="submit"
                                                        class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm">
                                                        <i class="fas fa-shield-alt me-2"></i> Update
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3. Business Details -->
                                <div class="tab-pane fade" id="business" role="tabpanel">
                                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                                        <div class="card-header bg-white border-bottom border-danger border-opacity-25 py-0 px-4">
                                            <h5 class="mb-0 fw-bold text-danger d-flex align-items-center" style="height: 60px;">
                                                <i class="fas fa-building fa-fw me-2"></i> <span>Business Information</span>
                                            </h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <form id="UpdateBusinessInformationForm" method="post">
                                                <div class="row">
                                                    <div class="col-md-12 mb-2">
                                                        <input type="hidden" name="user_code" value="<?= esc($row->user_code); ?>">
                                                        <label class="form-label fw-bold">Business Name</label>
                                                        <input type="text" class="form-control" name="business_name"
                                                            value="<?= esc($row->business_name) ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-bold">Industry / Category</label>
                                                        <input type="text" class="form-control" name="industry"
                                                            value="<?= esc($row->industry) ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-bold">Subcategory</label>
                                                        <input type="text" class="form-control" name="subcategory"
                                                            value="<?= esc($row->subcategory) ?>">
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label class="form-label fw-bold">Business Description</label>
                                                        <textarea class="form-control" name="description"
                                                            rows="3"><?= esc($row->description) ?></textarea>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-bold">Registration Number</label>
                                                        <input type="text" class="form-control" name="reg_number"
                                                            value="<?= esc($row->reg_number) ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-bold">GST Number</label>
                                                        <input type="text" class="form-control" name="gst_number"
                                                            value="<?= esc($row->gst_number) ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-bold">Year Started</label>
                                                        <input type="number" class="form-control" name="start_year"
                                                            value="<?= esc($row->start_year) ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label fw-bold">No. of Employees</label>
                                                        <input type="number" class="form-control" name="employees"
                                                            value="<?= esc($row->employees) ?>">
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <button type="submit"
                                                        class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm">
                                                        <i class="fas fa-shield-alt me-2"></i> Update
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- 4. Location & Hours -->
                                <div class="tab-pane fade" id="location" role="tabpanel">
                                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                                        <div class="card-header bg-white border-bottom border-danger border-opacity-25 py-0 px-4">
                                            <h5 class="mb-0 fw-bold text-danger d-flex align-items-center" style="height: 60px;">
                                                <i class="fas fa-map-marked-alt fa-fw me-2"></i> <span>Business Location</span>
                                            </h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <form id="UpdateBusinessLocationForm" method="post">
                                                <input type="hidden" name="user_code" value="<?= esc($row->user_code); ?>">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Office Address</label>
                                                    <textarea class="form-control" name="office_address"
                                                        rows="3"><?= esc($row->office_address); ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Working Hours</label>
                                                    <input type="text" class="form-control" name="working_hours"
                                                        value="<?= esc($row->working_hours); ?>"
                                                        placeholder="e.g. Mon-Fri 9:00 AM - 6:00 PM">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Service Areas (Locations Covered)</label>
                                                    <textarea class="form-control" name="service_areas" rows="2"
                                                        placeholder="e.g. Bangalore, Mumbai, International"><?= esc($row->service_areas); ?></textarea>
                                                </div>
                                                <div class="text-end">
                                                    <button type="submit"
                                                        class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm">
                                                        <i class="fas fa-shield-alt me-2"></i> Update
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>



                                <!-- 6. Social Presence -->
                                <div class="tab-pane fade" id="social" role="tabpanel">
                                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                                        <div class="card-header bg-white border-bottom border-danger border-opacity-25 py-0 px-4">
                                            <h5 class="mb-0 fw-bold text-danger d-flex align-items-center" style="height: 60px;">
                                                <i class="fas fa-globe fa-fw me-2"></i> <span>Social Media & Links</span>
                                            </h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <form id="updateSocialMedia" method="post">
                                                <input type="hidden" name="user_code" value="<?= esc($row->user_code); ?>">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label fw-bold">Website URL</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                                                            <input type="text" class="form-control" name="website"
                                                                value="<?= esc($row->website ?? '') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">LinkedIn</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text text-primary"><i
                                                                    class="fab fa-linkedin"></i></span>
                                                            <input type="text" class="form-control" name="linkedin"
                                                                value="<?= esc($row->linkedin ?? '') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Facebook</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text text-primary"><i
                                                                    class="fab fa-facebook"></i></span>
                                                            <input type="text" class="form-control" name="facebook"
                                                                value="<?= esc($row->facebook ?? '') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Instagram</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text text-danger"><i
                                                                    class="fab fa-instagram"></i></span>
                                                            <input type="text" class="form-control" name="instagram"
                                                                value="<?= esc($row->instagram ?? '') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Twitter / X</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text text-dark"><i class="fab fa-twitter"></i></span>
                                                            <input type="text" class="form-control" name="twitter"
                                                                value="<?= esc($row->twitter ?? '') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">YouTube Channel</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text text-danger"><i class="fab fa-youtube"></i></span>
                                                            <input type="text" class="form-control" name="youtube"
                                                                value="<?= esc($row->youtube ?? '') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Google Business Page</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text text-warning"><i class="fab fa-google"></i></span>
                                                            <input type="text" class="form-control" name="google_business"
                                                                value="<?= esc($row->google_business ?? '') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <button type="submit"
                                                            class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm">
                                                            <i class="fas fa-shield-alt me-2"></i> Update
                                                        </button>
                                                    </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 7. Additional Info -->
                            <div class="tab-pane fade" id="additional" role="tabpanel">
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-header bg-white border-bottom border-danger border-opacity-25 py-0 px-4">
                                        <h5 class="mb-0 fw-bold text-danger d-flex align-items-center" style="height: 60px;">
                                            <i class="fas fa-info-circle fa-fw me-2"></i> <span>Extras & Awards</span>
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <form id="updateAwardsData" method="post">
                                            <input type="hidden" name="user_code" value="<?= esc($row->user_code); ?>">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Awards & Achievements</label>
                                                <textarea class="form-control" name="awards"
                                                    rows="2"><?= esc($row->awards ?? '') ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Certifications</label>
                                                <textarea class="form-control" name="certifications"
                                                    rows="2"><?= esc($row->certifications ?? '') ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Profile Tags / Keywords</label>
                                                <input type="text" class="form-control" name="tags"
                                                    value="<?= esc($row->tags ?? '') ?>"
                                                    placeholder="e.g. Web Design, SEO, Digital Marketing">
                                            </div>
                                            <div class="text-end">
                                                <button type="submit"
                                                    class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm">
                                                    <i class="fas fa-shield-alt me-2"></i> Update
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- 8. Media & Docs -->
                            <div class="tab-pane fade" id="media" role="tabpanel">
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-header bg-white border-bottom border-danger border-opacity-25 py-0 px-4">
                                        <h5 class="mb-0 fw-bold text-danger d-flex align-items-center" style="height: 60px;">
                                            <i class="fas fa-images fa-fw me-2"></i> <span>Media & Document Management</span>
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <form id="updateMediaDocumentData" method="post" enctype="multipart/form-data">

                                            <input type="hidden" name="user_code" value="<?= esc($row->user_code); ?>">

                                            <div class="row g-4">

                                                <!-- PROFILE PHOTO -->
                                                <div class="col-md-6">
                                                    <label class="fw-bold d-flex align-items-center justify-content-between">
                                                        <span>Profile Photo</span>
                                                        <span class="badge bg-light text-muted fw-normal" style="font-size: 11px;">JPG, PNG up to 5MB</span>
                                                    </label>

                                                    <input type="file" class="form-control mb-2"
                                                        name="profile_photo"
                                                        accept=".jpg,.jpeg,.png">

                                                    <?php 
                                                    $editAvatar = get_member_avatar($row->profile_photo ?? '', $row->gender ?? '', $row->user_code ?? '', true);
                                                    ?>
                                                    <div class="d-flex align-items-center gap-3 p-2 rounded-3" style="background: rgba(16, 27, 53, 0.03); border: 1px dashed rgba(198, 166, 103, 0.4);">
                                                        <img src="<?= $editAvatar['url'] ?>"
                                                            class="rounded-3 shadow-sm"
                                                            style="height: 72px; width: 72px; object-fit: cover; border: 2px solid <?= $editAvatar['is_ai'] ? '#C6A667' : '#22C55E' ?>;">
                                                        <div>
                                                            <?php if ($editAvatar['is_ai']): ?>
                                                                <span class="badge" style="background: #FFF7ED; color: #C2410C; border: 1px solid #FDBA74; font-size: 11px; font-weight: 700;">
                                                                    <i class="ri-sparkling-fill me-1"></i> AI-Generated Default Headshot
                                                                </span>
                                                                <p class="small text-muted mb-0 mt-1" style="font-size: 11.5px; line-height: 1.35;">
                                                                    You haven't uploaded a photo yet. Choose an image above to personalize your profile with your real photograph anytime.
                                                                </p>
                                                            <?php else: ?>
                                                                <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 11px; font-weight: 700;">
                                                                    <i class="ri-checkbox-circle-fill me-1"></i> Custom Photograph Active
                                                                </span>
                                                                <p class="small text-muted mb-0 mt-1" style="font-size: 11.5px; line-height: 1.35;">
                                                                    Upload a new photo above whenever you'd like to update your executive picture.
                                                                </p>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- BUSINESS LOGO -->
                                                <div class="col-md-6">
                                                    <label class="fw-bold">Business Logo</label>

                                                    <input type="file" class="form-control mb-2"
                                                        name="business_logo"
                                                        accept="image/*"
                                                        onchange="previewImg(this,'logoPrev')">

                                                    <img id="logoPrev"
                                                        src="<?= !empty($row->business_logo)
                                                                    ? base_url('uploads/business_logo/' . $row->business_logo)
                                                                    : '' ?>"
                                                        class="img-thumbnail"
                                                        style="height:90px;<?= empty($row->business_logo) ? 'display:none' : '' ?>">
                                                </div>


                                                <!-- BUSINESS CARD -->
                                                <div class="col-md-6">
                                                    <label class="fw-bold">Business Card</label>

                                                    <input type="file" class="form-control mb-2"
                                                        name="business_card"
                                                        accept=".pdf,.doc,.docx,.jpg,.png">

                                                    <?php if (!empty($row->business_card)): ?>
                                                        <a href="<?= base_url('uploads/business_cards/' . $row->business_card) ?>"
                                                            target="_blank"
                                                            class="btn btn-sm btn-outline-danger">
                                                            Download Card
                                                        </a>
                                                    <?php endif; ?>
                                                </div>


                                                <!-- BROCHURE -->
                                                <div class="col-md-6">
                                                    <label class="fw-bold">Company Brochure</label>

                                                    <input type="file" class="form-control mb-2"
                                                        name="brochure"
                                                        accept=".pdf,.doc,.docx,.jpg,.png">

                                                    <?php if (!empty($row->brochure)): ?>
                                                        <a href="<?= base_url('uploads/brochures/' . $row->brochure) ?>"
                                                            target="_blank"
                                                            class="btn btn-sm btn-outline-primary">
                                                            Download Brochure
                                                        </a>
                                                    <?php endif; ?>
                                                </div>


                                                <!-- PORTFOLIO -->
                                                <div class="col-md-12">
                                                    <label class="fw-bold">Portfolio</label>

                                                    <input type="file" class="form-control mb-2"
                                                        name="portfolio"
                                                        accept=".pdf,.zip,.rar,.doc,.docx">

                                                    <?php if (!empty($row->portfolio)): ?>
                                                        <a href="<?= base_url('uploads/portfolios/' . $row->portfolio) ?>"
                                                            target="_blank"
                                                            class="btn btn-sm btn-outline-info">
                                                            Download Portfolio
                                                        </a>

                                                        <span class="text-muted small ms-2">
                                                            <?= esc($row->portfolio) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>

                                            </div>

                                            <div class="text-end mt-3">
                                                <button type="submit"
                                                    class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm">
                                                    <i class="fas fa-shield-alt me-2"></i> Update
                                                </button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- 9. Change Password -->
                            <div class="tab-pane fade" id="password" role="tabpanel">
                                <div class="card border-0 shadow-sm rounded-4 mb-4">

                                    <div class="card-header bg-white border-bottom border-danger border-opacity-25 py-0 px-4">
                                        <h5 class="mb-0 fw-bold text-danger d-flex align-items-center" style="height:60px;">
                                            <i class="fas fa-key me-2"></i> Security: Change Password
                                        </h5>
                                    </div>

                                    <div class="card-body p-4">

                                        <form id="updateChangePasswordtData" method="post">
                                            <input type="hidden" name="user_code" value="<?= esc($row->user_code); ?>">
                                            <div class="row">
                                                <!-- OLD PASSWORD -->
                                                <!-- <div class="col-md-12 mb-3">
                                                    <label class="fw-bold">Old Password</label>
                                                    <div class="input-group">
                                                        <input type="password" name="current_password"
                                                            id="current_password"
                                                            class="form-control"
                                                            required>
                                                        <button class="btn btn-outline-secondary"
                                                            type="button"
                                                            onclick="togglePass('current_password',this)">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div> -->
                                                <!-- NEW PASSWORD -->
                                                <div class="col-md-6 mb-3">
                                                    <label class="fw-bold">New Password</label>
                                                    <div class="input-group">
                                                        <input type="password" name="new_password"
                                                            id="new_password"
                                                            class="form-control"
                                                            minlength="6"
                                                            required>
                                                        <button class="btn btn-outline-secondary"
                                                            type="button"
                                                            onclick="togglePass('new_password',this)">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                    <small class="text-muted">Minimum 6 characters</small>
                                                </div>
                                                <!-- CONFIRM -->
                                                <div class="col-md-6 mb-3">
                                                    <label class="fw-bold">Confirm Password</label>
                                                    <div class="input-group">
                                                        <input type="password" name="confirm_password"
                                                            id="confirm_password"
                                                            class="form-control"
                                                            minlength="6"
                                                            required>
                                                        <button class="btn btn-outline-secondary"
                                                            type="button"
                                                            onclick="togglePass('confirm_password',this)">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-3 text-end">
                                                    <button type="submit"
                                                        class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm">
                                                        <i class="fas fa-shield-alt me-2"></i> Update
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        <?php } ?>

        </div>
        <!-- End Page-content -->
        <?php echo view('include/member/footer.php') ?>
    </div>
    <!-- end main content-->
    </div>
    <div class="rightbar-overlay"></div>
    <!-- JAVASCRIPT -->
    <?php echo view('include/member/popup/profile.php') ?>
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
    <script>
        function previewImg(input, id) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    $('#' + id).attr('src', e.target.result).show();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        function togglePass(id, btn) {
            let input = document.getElementById(id);
            let icon = btn.querySelector("i");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>
</body>

</html>