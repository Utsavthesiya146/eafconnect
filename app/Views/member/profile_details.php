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
    <link href="<?= base_url(); ?>assets2/css/profile_details.css" id="app-style" rel="stylesheet" type="text/css" />
    <!-- Google Fonts & Master EAF Connect Theme -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets2/css/eaf-revamp.css'); ?>?v=2.3">
</head>

<body data-sidebar="colored">

    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php echo view('include/member/header.php') ?>
        <?php echo view('include/member/sidebar.php') ?>
        <div class="main-content">
            <div class="page-content">
                <div class="row">
                    <h4>Member Profile</h4>
                </div>
                <div class="container-fluid p-3">
                    <?php foreach ($member as $row): ?>
                        <div class="row mb-4">
                            <!-- PROFILE LEFT -->
                            <div class="col-lg-4 mb-3">
                                <div class="card shadow-sm rounded-4 border-0 overflow-hidden profile-card" style="box-shadow: 0 10px 30px rgba(15,59,104,0.12) !important;">

                                    <div class="card-body text-center p-4 profile-gradient" style="background: linear-gradient(145deg, #091E36 0%, #0F3B68 60%, #152E4D 100%); color: #ffffff;">

                                        <!-- EAF Connect Member Badge -->
                                        <div class="d-inline-flex align-items-center gap-1 px-3 py-1 mb-3 rounded-pill" style="background: rgba(217, 119, 6, 0.18); border: 1px solid rgba(245, 158, 11, 0.5); color: #F59E0B; font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                                            <i class="ri-vip-crown-fill text-warning"></i> <?= strtoupper(esc($row->member_role ?: 'MEMBER')); ?> • EAF CONNECT
                                        </div>

                                        <!-- Profile Image with Gold Ring -->
                                        <?php $mAvatar = get_member_avatar($row->profile_photo ?? '', $row->gender ?? '', $row->user_code ?? '', true); ?>
                                        <div class="position-relative d-inline-block mb-3">
                                            <img src="<?= $mAvatar['url'] ?>"
                                                class="rounded-circle profile-img"
                                                style="width: 120px; height: 120px; object-fit: cover; border: 3.5px solid #F59E0B; box-shadow: 0 8px 24px rgba(217, 119, 6, 0.25);">
                                            <?php if ($mAvatar['is_ai']): ?>
                                                <span class="badge" style="position: absolute; top: -5px; right: -5px; background: linear-gradient(135deg, #C6A667, #E4D4AE); color: #101B35; font-size: 10px; font-weight: 700; border-radius: 12px; padding: 3px 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.3); border: 1.5px solid #fff;" title="AI-Generated Default Avatar">
                                                    <i class="ri-sparkling-fill me-1"></i>AI Avatar
                                                </span>
                                            <?php endif; ?>
                                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1 border border-2 border-white" title="Verified Active Member" style="width: 22px; height: 22px; display: flex; align-items: center; justify-content: center;">
                                                <i class="ri-check-line text-white" style="font-size: 12px;"></i>
                                            </span>
                                        </div>

                                        <!-- Name -->
                                        <h3 class="fw-bold mb-1" style="color:#ffffff; font-family: 'Outfit', sans-serif; letter-spacing: -0.3px;"><?= esc($row->name) ?></h3>
                                        <div class="badge px-3 py-1 mb-2" style="background: rgba(255,255,255,0.12); color: #94A3B8; font-weight: 600; font-size: 12px; border-radius: 6px;">
                                            <i class="ri-community-line me-1 text-warning"></i><?= esc($row->chapter_name) ?>
                                        </div>
                                        <div class="fw-semibold text-warning d-block mb-2" style="font-size: 13.5px;">
                                            <?= esc($row->business_name) ?>
                                        </div>
                                        <!-- Bio -->
                                        <p class="mb-3 small text-light opacity-75">
                                            <i class="fas fa-briefcase me-1 text-warning"></i>
                                            <?= esc($row->bio ?: 'Elite Business Member') ?>
                                        </p>

                                        <!-- One-Touch Quick Contact Dialer Buttons (BNI Standard) -->
                                        <div class="d-flex justify-content-center gap-2 mb-3">
                                            <?php if (!empty($row->phone)): ?>
                                                <a href="tel:<?= esc($row->phone) ?>" class="btn btn-sm text-white px-3 py-1 rounded-pill" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); font-weight: 600; font-size: 12px; transition: all 0.2s;" title="Direct Call">
                                                    <i class="ri-phone-fill text-success me-1"></i> Call
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($row->whatsapp) || !empty($row->phone)): 
                                                $wa_number = preg_replace('/[^0-9]/', '', $row->whatsapp ?: $row->phone);
                                            ?>
                                                <a href="https://wa.me/<?= $wa_number ?>?text=<?= urlencode('Hello ' . $row->name . ', connecting with you via EAF Connect.') ?>" target="_blank" class="btn btn-sm text-white px-3 py-1 rounded-pill" style="background: #25D366; font-weight: 600; font-size: 12px; box-shadow: 0 4px 12px rgba(37,211,102,0.3);" title="Direct WhatsApp">
                                                    <i class="ri-whatsapp-fill me-1"></i> WhatsApp
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($row->email)): ?>
                                                <a href="mailto:<?= esc($row->email) ?>" class="btn btn-sm text-white px-3 py-1 rounded-pill" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); font-weight: 600; font-size: 12px;" title="Direct Email">
                                                    <i class="ri-mail-send-fill text-warning me-1"></i> Email
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                        <hr class="profile-divider" style="border-color: rgba(255, 255, 255, 0.15); margin: 16px 0;">

                                        <!-- ================= CONTACT ================= -->
                                        <div class="info-section text-start">
                                            <h6 class="section-title text-uppercase" style="color: #F59E0B; font-size: 11px; letter-spacing: 1px; font-weight: 700;">
                                                <i class="ri-contacts-book-2-line me-1"></i> Contact Information
                                            </h6>

                                            <div class="info-row py-1">
                                                <i class="fas fa-envelope text-warning"></i>
                                                <span class="text-light opacity-90"><?= esc($row->email) ?></span>
                                            </div>

                                            <div class="info-row py-1">
                                                <i class="fas fa-phone text-warning"></i>
                                                <span class="text-light opacity-90"><?= esc($row->phone) ?></span>
                                            </div>

                                            <div class="info-row py-1">
                                                <i class="fab fa-whatsapp text-success"></i>
                                                <span class="text-light opacity-90"><?= esc($row->whatsapp) ?></span>
                                            </div>

                                            <?php if ($row->website): ?>
                                                <div class="info-row py-1">
                                                    <i class="fas fa-link text-info"></i>
                                                    <a href="<?= esc($row->website) ?>" target="_blank" class="text-info text-decoration-underline">
                                                        <?= esc($row->website) ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <hr class="profile-divider" style="border-color: rgba(255, 255, 255, 0.15); margin: 16px 0;">
                                        <div class="info-section text-start">
                                            <h6 class="section-title text-uppercase" style="color: #F59E0B; font-size: 11px; letter-spacing: 1px; font-weight: 700;">
                                                <i class="ri-briefcase-4-line me-1"></i> Business Details
                                            </h6>

                                            <?php if (!empty($row->business_name)) { ?>
                                                <p class="mb-1 small"><strong class="text-white-50">Business:</strong> <span class="text-white"><?= esc($row->business_name) ?></span></p>
                                            <?php } ?>

                                            <?php if (!empty($row->industry)) { ?>
                                                <p class="mb-1 small"><strong class="text-white-50">Industry:</strong> <span class="text-white"><?= esc($row->industry) ?></span></p>
                                            <?php } ?>

                                            <?php if (!empty($row->subcategory)) { ?>
                                                <p class="mb-1 small"><strong class="text-white-50">Category:</strong> <span class="text-white"><?= esc($row->subcategory) ?></span></p>
                                            <?php } ?>
                                            <?php if (!empty($row->services)) { ?>
                                                <p class="mb-1 small"><strong class="text-white-50">Services:</strong> <span class="text-white"><?= esc($row->services) ?></span></p>
                                            <?php } ?>

                                            <?php if (!empty($row->products)) { ?>
                                                <p class="mb-1 small"><strong class="text-white-50">Products:</strong> <span class="text-white"><?= esc($row->products) ?></span></p>
                                            <?php } ?>

                                            <?php if (!empty($row->description)) { ?>
                                                <p class="mb-1 small"><strong class="text-white-50">Description:</strong> <span class="text-white"><?= esc($row->description) ?></span></p>
                                            <?php } ?>
                                        </div>
                                        <hr class="profile-divider" style="border-color: rgba(255, 255, 255, 0.15); margin: 16px 0;">
                                        <!-- ================= SOCIAL ================= -->
                                        <div class="info-section text-start">
                                            <h6 class="section-title text-uppercase" style="color: #F59E0B; font-size: 11px; letter-spacing: 1px; font-weight: 700;">
                                                <i class="ri-share-forward-line me-1"></i> Social Network
                                            </h6>

                                            <div class="social-icons-row mt-2">

                                                <?php if ($row->linkedin): ?>
                                                    <a href="<?= esc($row->linkedin) ?>" target="_blank" class="social-icon linkedin">
                                                        <i class="fa fa-linkedin"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($row->facebook): ?>
                                                    <a href="<?= esc($row->facebook) ?>" target="_blank" class="social-icon facebook">
                                                        <i class="fa fa-facebook"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($row->instagram): ?>
                                                    <a href="<?= esc($row->instagram) ?>" target="_blank" class="social-icon instagram">
                                                        <i class="fa fa-instagram"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($row->twitter): ?>
                                                    <a href="<?= esc($row->twitter) ?>" target="_blank" class="social-icon twitter">
                                                        <i class="fa fa-twitter"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($row->youtube): ?>
                                                    <a href="<?= esc($row->youtube) ?>" target="_blank" class="social-icon youtube">
                                                        <i class="fa fa-youtube"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($row->google_business): ?>
                                                    <a href="<?= esc($row->google_business) ?>" target="_blank" class="social-icon google">
                                                        <i class="fa fa-google"></i>
                                                    </a>
                                                <?php endif; ?>

                                            </div>
                                        </div>

                                        <hr class="profile-divider" style="border-color: rgba(255, 255, 255, 0.15); margin: 16px 0;">
                                        
                                        <!-- Digital Collateral / Share Actions -->
                                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                                            <?php if (!empty($row->business_card)): ?>
                                                <a href="<?= base_url('uploads/business_cards/' . $row->business_card) ?>"
                                                    target="_blank"
                                                    class="btn btn-sm" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.25); color:#ffffff; font-size:11px; font-weight:600; border-radius:8px;">
                                                    <i class="fas fa-id-card me-1 text-warning"></i> Business Card
                                                </a>
                                            <?php endif; ?> 
                                            <?php if (!empty($row->brochure)): ?>
                                                <a href="<?= base_url('uploads/brochures/' . $row->brochure) ?>"
                                                    target="_blank"
                                                    class="btn btn-sm" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.25); color:#ffffff; font-size:11px; font-weight:600; border-radius:8px;">
                                                    <i class="fas fa-file-pdf me-1 text-warning"></i> Brochure
                                                </a>
                                            <?php endif; ?> 
                                            <?php if (!empty($row->portfolio)): ?>
                                                <a href="<?= base_url('uploads/portfolios/' . $row->portfolio) ?>"
                                                    target="_blank"
                                                    class="btn btn-sm" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.25); color:#ffffff; font-size:11px; font-weight:600; border-radius:8px;">
                                                    <i class="fas fa-briefcase me-1 text-warning"></i> Portfolio
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PROFILE RIGHT -->
                            <div class="col-md-8">

                                <!-- ================= ACTIVITY BREAKDOWN ================= -->
                                <div class="card activity-card mt-3">

                                    <div class="card-body">

                                        <!-- Header -->
                                        <div class="activity-header">
                                            <div>
                                                <span class="activity-eyebrow">
                                                    <i class="fas fa-chart-line"></i>
                                                    PERFORMANCE
                                                </span>

                                                <h5 class="activity-title mb-0">
                                                    Activity Breakdown
                                                </h5>

                                                <p class="activity-subtitle mb-0">
                                                    Your networking activity at a glance
                                                </p>
                                            </div>

                                            <div class="activity-header-icon">
                                                <i class="fas fa-chart-pie"></i>
                                            </div>
                                        </div>


                                        <!-- Activity Items -->
                                        <div class="row g-3 mt-2">

                                            <!-- Connect Given -->
                                            <div class="col-xl-3 col-md-6 col-6">
                                                <div class="activity-item">

                                                    <div class="activity-icon">
                                                        <i class="fas fa-share-alt"></i>
                                                    </div>

                                                    <div class="activity-content">
                                                        <span class="activity-label">
                                                            Connect Given
                                                        </span>

                                                        <strong class="activity-number">
                                                            <?= $r_g_c ?? 0 ?>
                                                        </strong>

                                                        <span class="activity-status">
                                                            <i class="fas fa-arrow-up"></i>
                                                            Given
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>


                                            <!-- Connect Received -->
                                            <div class="col-xl-3 col-md-6 col-6">
                                                <div class="activity-item">

                                                    <div class="activity-icon">
                                                        <i class="fas fa-handshake"></i>
                                                    </div>

                                                    <div class="activity-content">
                                                        <span class="activity-label">
                                                            Connect Received
                                                        </span>

                                                        <strong class="activity-number">
                                                            <?= $r_r_c ?? 0 ?>
                                                        </strong>

                                                        <span class="activity-status">
                                                            <i class="fas fa-arrow-down"></i>
                                                            Received
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>


                                            <!-- Visitors -->
                                            <div class="col-xl-3 col-md-6 col-6">
                                                <div class="activity-item">

                                                    <div class="activity-icon">
                                                        <i class="fas fa-user-plus"></i>
                                                    </div>

                                                    <div class="activity-content">
                                                        <span class="activity-label">
                                                            Visitor Count
                                                        </span>

                                                        <strong class="activity-number">
                                                            <?= $visitors_c ?? 0 ?>
                                                        </strong>

                                                        <span class="activity-status">
                                                            <i class="fas fa-users"></i>
                                                            Visitors
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>


                                            <!-- Meetings -->
                                            <div class="col-xl-3 col-md-6 col-6">
                                                <div class="activity-item">

                                                    <div class="activity-icon">
                                                        <i class="fas fa-calendar-check"></i>
                                                    </div>

                                                    <div class="activity-content">
                                                        <span class="activity-label">
                                                            F2F
                                                        </span>

                                                        <strong class="activity-number">
                                                            <?= $meetingsCount ?? 0 ?>
                                                        </strong>

                                                        <span class="activity-status">
                                                            <i class="fas fa-check-circle"></i>
                                                            Attended
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6 col-6">
                                                <div class="activity-item">

                                                    <div class="activity-icon">
                                                        <i class="fas fa-calendar-check"></i>
                                                    </div>

                                                    <div class="activity-content">
                                                        <span class="activity-label">
                                                            inducation
                                                        </span>

                                                        <strong class="activity-number">
                                                            <?= $inducationCount ?? 0 ?>
                                                        </strong>

                                                        <span class="activity-status">
                                                            <i class="fas fa-check-circle"></i>
                                                            Attended
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-xl-5 col-md-6 col-6">
                                                <div class="activity-item">
                                                    <div class="activity-icon">
                                                        <i class="fas fa-calendar-check"></i>
                                                    </div>
                                                    <div class="activity-content">
                                                        <span class="activity-label">
                                                            MITB Given
                                                        </span>
                                                        <strong class="activity-number" style="font-size: 18px;">
                                                            <?= $totalMITBGiven ?? 0 ?>
                                                        </strong>
                                                        <span class="activity-status">
                                                            <i class="fas fa-check-circle"></i>
                                                            MITB
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-6 col-6">
                                                <div class="activity-item">

                                                    <div class="activity-icon">
                                                        <i class="fas fa-calendar-check"></i>
                                                    </div>

                                                    <div class="activity-content">
                                                        <span class="activity-label">
                                                            MITB Received
                                                        </span>

                                                        <strong class="activity-number" style="font-size: 18px;">
                                                            <?= $totalMITBReceived ?? 0 ?>
                                                        </strong>

                                                        <span class="activity-status">
                                                            <i class="fas fa-check-circle"></i>
                                                            Received
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <div class="referral-wrapper">

                                    <!-- ================= TAB NAVIGATION ================= -->
                                    <div class="referral-tabs-wrapper">

                                        <ul class="nav referral-tabs" id="referralTabs" role="tablist">

                                            <li class="nav-item" role="presentation">
                                                <button
                                                    class="nav-link referral-tab active"
                                                    id="given-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#given"
                                                    type="button"
                                                    role="tab">

                                                    <span class="tab-icon">
                                                        <i class="fas fa-share"></i>
                                                    </span>

                                                    <span>
                                                        <strong>Given Connects </strong>
                                                        <small><?= count($given ?? []) ?> Connects </small>
                                                    </span>
                                                </button>
                                            </li>

                                            <li class="nav-item" role="presentation">
                                                <button
                                                    class="nav-link referral-tab"
                                                    id="mitb-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#mitb"
                                                    type="button"
                                                    role="tab">

                                                    <span class="tab-icon received-icon">
                                                        <i class="fas fa-hand-holding-usd"></i>
                                                    </span>

                                                    <span>
                                                        <strong>Received Connects </strong>
                                                        <small><?= count($mitb_received ?? []) ?> records</small>
                                                    </span>
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button
                                                    type="button"
                                                    class="nav-link referral-tab"
                                                    id="sponsored-tab2"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#sponsored2"
                                                    role="tab"
                                                    aria-controls="sponsored2"
                                                    aria-selected="false">

                                                    <span class="tab-icon received-icon">
                                                        <i class="fas fa-hand-holding-usd"></i>
                                                    </span>
                                                    <span>
                                                        <strong>Sponsored By</strong>
                                                        <small>
                                                            <?= esc($inducationCount ?? 0) ?> records
                                                        </small>
                                                    </span>

                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button
                                                    class="nav-link referral-tab"
                                                    id="sponsored-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#sponsored"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="sponsored"
                                                    aria-selected="false">
                                                    <span class="tab-icon received-icon">
                                                        <i class="fas fa-hand-holding-usd"></i>
                                                    </span>
                                                    <span>
                                                        <strong>Visitor</strong>
                                                        <small> <?= ($sponsoredCount ?? 0) ?> records</small>
                                                    </span>
                                                </button>
                                            </li>

                                        </ul>

                                    </div>


                                    <!-- ================= TAB CONTENT ================= -->
                                    <div class="tab-content referral-tab-content" id="referralTabsContent">


                                        <!-- =================================================
             GIVEN REFERRAL
        ================================================== -->
                                        <div
                                            class="tab-pane fade show active"
                                            id="given"
                                            role="tabpanel"
                                            aria-labelledby="given-tab">

                                            <?php if (!empty($given)): ?>

                                                <div class="referral-list">

                                                    <?php foreach ($given as $row): ?>

                                                        <?php
                                                        $status = strtolower(trim($row['status'] ?? ''));

                                                        $statusMap = [
                                                            'pending'        => ['pending', 'Pending', 'fa-clock'],
                                                            'contacted'      => ['contacted', 'Contacted', 'fa-phone'],
                                                            'completed'      => ['completed', 'Completed', 'fa-check'],
                                                            'closed business' => ['closed', 'Closed Business', 'fa-lock'],
                                                            'business_done'  => ['success', 'Business Done', 'fa-check-circle'],
                                                            'not converted'  => ['danger', 'Not Converted', 'fa-times-circle'],
                                                            'cancelled'      => ['cancelled', 'Cancelled', 'fa-ban'],
                                                        ];

                                                        $statusData = $statusMap[$status] ?? ['pending', 'Pending', 'fa-clock'];

                                                        $profileImage = get_member_avatar($row['to_profile'] ?? '', '', $row['to_user_code'] ?? '');
                                                        ?>

                                                        <div class="referral-item">

                                                            <!-- MEMBER -->
                                                            <div class="referral-member">

                                                                <div class="member-avatar-wrapper">

                                                                    <img
                                                                        src="<?= $profileImage ?>"
                                                                        class="member-avatar"
                                                                        alt="<?= esc($row['to_name']) ?>">

                                                                    <span class="online-dot"></span>

                                                                </div>

                                                                <div class="member-details">

                                                                    <h6 class="member-name">
                                                                        <?= esc($row['to_name']) ?>
                                                                    </h6>

                                                                    <?php if (!empty($row['to_email'])): ?>
                                                                        <div class="member-email">
                                                                            <i class="fas fa-envelope"></i>
                                                                            <?= esc($row['to_email']) ?>
                                                                        </div>
                                                                    <?php endif; ?>

                                                                </div>

                                                            </div>


                                                            <!-- BUSINESS -->
                                                            <div class="referral-business">

                                                                <span class="business-label">
                                                                    BUSINESS
                                                                </span>

                                                                <div class="business-name">
                                                                    <i class="fas fa-building"></i>
                                                                    <?= esc($row['business']) ?>
                                                                </div>

                                                                <?php if (!empty($row['contact_number'])): ?>
                                                                    <div class="business-phone">
                                                                        <i class="fas fa-phone"></i>
                                                                        <?= esc($row['contact_number']) ?>
                                                                    </div>
                                                                <?php endif; ?>

                                                            </div>


                                                            <!-- STATUS -->
                                                            <div class="referral-status">

                                                                <span
                                                                    class="status-badge status-<?= esc($statusData[0]) ?>"
                                                                    onclick="edit_refferral_status('<?= esc($row['referral_code']) ?>');">

                                                                    <i class="fas <?= $statusData[2] ?>"></i>
                                                                    <?= esc($statusData[1]) ?>

                                                                </span>

                                                                <div class="referral-date">

                                                                    <i class="far fa-calendar-alt"></i>

                                                                    <?= (new DateTime($row['created_at']))->format('d M Y') ?>

                                                                    <span class="date-time">
                                                                        <?= (new DateTime($row['created_at']))->format('h:i A') ?>
                                                                    </span>

                                                                </div>

                                                            </div>


                                                            <!-- ACTION -->
                                                            <div class="referral-action">

                                                                <a
                                                                    href="<?= site_url('member/connect/details/' . $row['referral_code']) ?>"
                                                                    class="view-referral-btn">

                                                                    <span>View</span>
                                                                    <i class="fas fa-arrow-right"></i>

                                                                </a>

                                                            </div>

                                                        </div>

                                                    <?php endforeach; ?>

                                                </div>

                                            <?php else: ?>

                                                <div class="empty-referral">

                                                    <div class="empty-icon">
                                                        <i class="fas fa-share-alt"></i>
                                                    </div>

                                                    <h6>No Given Connects </h6>

                                                    <p>
                                                        Connects you give will appear here.
                                                    </p>

                                                </div>

                                            <?php endif; ?>

                                        </div>



                                        <!-- =================================================
             MITB RECEIVED
        ================================================== -->
                                        <div
                                            class="tab-pane fade"
                                            id="mitb"
                                            role="tabpanel"
                                            aria-labelledby="mitb-tab">

                                            <?php if (!empty($mitb_received)): ?>

                                                <div class="mitb-list">

                                                    <?php foreach ($mitb_received as $row): ?>

                                                        <?php
                                                        $profileImage = get_member_avatar($row['from_profile'] ?? '', '', $row['from_user_code'] ?? ($row['user_code'] ?? ''));
                                                        ?>

                                                        <div class="mitb-item">

                                                            <!-- MEMBER -->
                                                            <div class="mitb-member">

                                                                <img
                                                                    src="<?= $profileImage ?>"
                                                                    class="mitb-avatar"
                                                                    alt="<?= esc($row['from_name']) ?>">

                                                                <div class="mitb-member-info">

                                                                    <h6>
                                                                        <?= esc($row['from_name']) ?>
                                                                    </h6>

                                                                    <?php if (!empty($row['category'])): ?>
                                                                        <span class="category-badge">
                                                                            <?= esc($row['category']) ?>
                                                                        </span>
                                                                    <?php endif; ?>

                                                                </div>

                                                            </div>


                                                            <!-- DESCRIPTION -->
                                                            <div class="mitb-description">

                                                                <span class="mitb-label">
                                                                    DESCRIPTION
                                                                </span>

                                                                <p>
                                                                    <?= esc($row['description']) ?>
                                                                </p>

                                                            </div>


                                                            <!-- AMOUNT -->
                                                            <div class="mitb-amount">

                                                                <span class="amount-label">
                                                                    AMOUNT
                                                                </span>

                                                                <strong>
                                                                    ₹ <?= (new NumberFormatter(
                                                                            'en_IN',
                                                                            NumberFormatter::DECIMAL
                                                                        ))->format($row['amount'], 2) ?>
                                                                </strong>

                                                            </div>


                                                            <!-- DATE -->
                                                            <div class="mitb-date">

                                                                <span class="mitb-label">
                                                                    DATE
                                                                </span>

                                                                <strong>
                                                                    <?= date('d M Y', strtotime($row['created_at'])) ?>
                                                                </strong>

                                                            </div>

                                                        </div>

                                                    <?php endforeach; ?>

                                                </div>

                                            <?php else: ?>

                                                <div class="empty-referral">

                                                    <div class="empty-icon">
                                                        <i class="fas fa-hand-holding-usd"></i>
                                                    </div>

                                                    <h6>No Records Found</h6>

                                                    <p>
                                                        Received MITB records will appear here.
                                                    </p>

                                                </div>

                                            <?php endif; ?>

                                        </div>







                                        <div
                                            class="tab-pane fade"
                                            id="sponsored2"
                                            role="tabpanel"
                                            aria-labelledby="sponsored-tab2">

                                            <div class="table-responsive">

                                                <table class="table">

                                                    <tbody>

                                                        <?php if (!empty($users)): ?>

                                                            <?php foreach ($users as $row2): ?>

                                                                <tr>

                                                                    <td>

                                                                        <div class="member-cell">

                                                                            <img
                                                                                src="<?= get_member_avatar($row2->profile_photo ?? '', $row2->gender ?? '', $row2->user_code ?? '') ?>"
                                                                                class="avatar"
                                                                                style="object-fit:cover;object-position:top center;">

                                                                            <div>

                                                                                <div class="name">
                                                                                    <?= esc($row2->name ?? '-') ?>
                                                                                </div>

                                                                                <small>
                                                                                    <?= esc($row2->email ?? '-') ?>
                                                                                </small>

                                                                            </div>

                                                                        </div>

                                                                    </td>

                                                                    <td>
                                                                        <?= esc($row2->phone ?? '-') ?>
                                                                    </td>

                                                                    <td>
                                                                        📍 <?= esc($row2->city ?? '-') ?>
                                                                    </td>

                                                                    <td>

                                                                        <span
                                                                            class="role-badge"
                                                                            onclick="edit_status('<?= esc($row2->user_code ?? '') ?>');">

                                                                            <?= esc($row2->member_role ?? '-') ?>

                                                                        </span>

                                                                    </td>

                                                                    <td>
                                                                        <?= esc($row2->join_date ?? '-') ?>
                                                                    </td>

                                                                </tr>

                                                            <?php endforeach; ?>

                                                        <?php else: ?>

                                                            <tr>
                                                                <td colspan="5" class="text-center p-4">
                                                                    No Sponsored Members Found
                                                                </td>
                                                            </tr>

                                                        <?php endif; ?>

                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>
                                        <div
                                            class="tab-pane fade"
                                            id="sponsored"
                                            role="tabpanel"
                                            aria-labelledby="sponsored-tab">
                                            <table>
                                                <!-- Sponsored By Content -->

                                                <?php if (!empty($visitors)): ?>

                                                    <?php $i = 1; ?>

                                                    <?php foreach ($visitors as $row): ?>

                                                        <?php
                                                        $status = trim((string) ($row['status'] ?? ''));

                                                        switch (strtolower($status)) {

                                                            case 'accepted':
                                                            case 'visited':
                                                            case 'completed':
                                                                $statusClass = 'crm-status-success';
                                                                $statusIcon  = 'fa-check-circle';
                                                                break;

                                                            case 'pending':
                                                                $statusClass = 'crm-status-warning';
                                                                $statusIcon  = 'fa-clock';
                                                                break;

                                                            case 'cancelled':
                                                                $statusClass = 'crm-status-danger';
                                                                $statusIcon  = 'fa-times-circle';
                                                                break;

                                                            default:
                                                                $statusClass = 'crm-status-default';
                                                                $statusIcon  = 'fa-info-circle';
                                                                break;
                                                        }

                                                        $visitorName = trim((string) ($row['visitor_name'] ?? 'Visitor'));
                                                        $initial = strtoupper(substr($visitorName, 0, 1));
                                                        ?>

                                                        <div class="crm-visitor-card">

                                                            <!-- Card Header -->
                                                            <div class="crm-card-header">

                                                                <div class="crm-visitor-profile">

                                                                    <div class="crm-avatar">
                                                                        <?= esc($initial) ?>
                                                                    </div>

                                                                    <div class="crm-visitor-main">

                                                                        <h6 class="crm-visitor-name">
                                                                            <?= esc($visitorName) ?>
                                                                        </h6>

                                                                        <?php if (!empty($row['email'])): ?>

                                                                            <div class="crm-email">
                                                                                <i class="fa fa-envelope"></i>
                                                                                <span><?= esc($row['email']) ?></span>
                                                                            </div>

                                                                        <?php endif; ?>

                                                                    </div>

                                                                </div>


                                                                <!-- Number -->
                                                                <div class="crm-card-number">
                                                                    #<?= (int) $i ?>
                                                                </div>

                                                            </div>


                                                            <!-- Card Details -->
                                                            <div class="crm-card-details">

                                                                <!-- Company -->
                                                                <div class="crm-detail-item">

                                                                    <div class="crm-detail-icon company">
                                                                        <i class="fa fa-building"></i>
                                                                    </div>

                                                                    <div class="crm-detail-content">

                                                                        <span class="crm-detail-label">
                                                                            Company
                                                                        </span>

                                                                        <strong>
                                                                            <?= esc($row['company'] ?? '-') ?>
                                                                        </strong>

                                                                    </div>

                                                                </div>


                                                                <!-- Phone -->
                                                                <div class="crm-detail-item">

                                                                    <div class="crm-detail-icon phone">
                                                                        <i class="fa fa-phone"></i>
                                                                    </div>

                                                                    <div class="crm-detail-content">

                                                                        <span class="crm-detail-label">
                                                                            Phone
                                                                        </span>

                                                                        <?php if (!empty($row['phone'])): ?>

                                                                            <a
                                                                                href="tel:<?= esc($row['phone']) ?>"
                                                                                class="crm-phone-link">
                                                                                <?= esc($row['phone']) ?>
                                                                            </a>

                                                                        <?php else: ?>

                                                                            <strong>—</strong>

                                                                        <?php endif; ?>

                                                                    </div>

                                                                </div>


                                                                <!-- Visit Date -->
                                                                <div class="crm-detail-item">

                                                                    <div class="crm-detail-icon date">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>

                                                                    <div class="crm-detail-content">

                                                                        <span class="crm-detail-label">
                                                                            Visit Date
                                                                        </span>

                                                                        <?php if (!empty($row['date_visited'])): ?>

                                                                            <strong>
                                                                                <?= date(
                                                                                    'd M Y',
                                                                                    strtotime($row['date_visited'])
                                                                                ) ?>
                                                                            </strong>

                                                                        <?php else: ?>

                                                                            <strong class="crm-empty">
                                                                                Not scheduled
                                                                            </strong>

                                                                        <?php endif; ?>

                                                                    </div>

                                                                </div>

                                                            </div>



                                                        </div>
                                                        <?php $i++; ?>

                                                    <?php endforeach; ?>

                                                <?php else: ?>
                                            </table>
                                            <div class="text-center p-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-users-slash fa-2x mb-2"></i>
                                                    <p class="mb-0">No Accepted Visitors Found</p>
                                                </div>
                                            </div>

                                        <?php endif; ?>

                                        </div>

                                    </div>
                                </div>

                                <style>
                                    /* =========================================================
   COMPACT CRM VISITOR CARD
========================================================= */

                                    .crm-visitor-card {
                                        width: 100%;
                                        background: #fff;
                                        border: 1px solid #e7ebf0;
                                        border-radius: 10px;
                                        padding: 11px 13px;
                                        margin-bottom: 8px;
                                        box-shadow: 0 1px 5px rgba(16, 24, 40, 0.04);
                                        transition: .2s ease;
                                    }

                                    .crm-visitor-card:hover {
                                        border-color: #d5dce5;
                                        box-shadow: 0 4px 12px rgba(16, 24, 40, 0.07);
                                    }


                                    /* ================= HEADER ================= */

                                    .crm-card-header {
                                        display: flex;
                                        align-items: center;
                                        justify-content: space-between;
                                        gap: 8px;
                                        padding-bottom: 9px;
                                        border-bottom: 1px solid #edf0f4;
                                    }

                                    .crm-visitor-profile {
                                        display: flex;
                                        align-items: center;
                                        gap: 9px;
                                        min-width: 0;
                                    }

                                    .crm-avatar {
                                        width: 36px;
                                        height: 36px;
                                        min-width: 36px;

                                        display: flex;
                                        align-items: center;
                                        justify-content: center;

                                        border-radius: 9px;

                                        background: linear-gradient(135deg,
                                                #1b4d8f,
                                                #3974bd);

                                        color: #fff;
                                        font-size: 13px;
                                        font-weight: 700;
                                    }

                                    .crm-visitor-main {
                                        min-width: 0;
                                    }

                                    .crm-visitor-name {
                                        margin: 0 0 2px;
                                        color: #172033;
                                        font-size: 13px;
                                        font-weight: 700;
                                        line-height: 1.25;
                                    }

                                    .crm-email {
                                        display: flex;
                                        align-items: center;
                                        gap: 4px;
                                        color: #7b8798;
                                        font-size: 10px;
                                        line-height: 1.2;
                                        max-width: 250px;
                                    }

                                    .crm-email i {
                                        font-size: 9px;
                                        color: #98a2b3;
                                    }

                                    .crm-email span {
                                        overflow: hidden;
                                        text-overflow: ellipsis;
                                        white-space: nowrap;
                                    }


                                    /* ================= NUMBER ================= */

                                    .crm-card-number {
                                        padding: 3px 7px;
                                        background: #f5f7fa;
                                        border-radius: 6px;

                                        color: #667085;
                                        font-size: 9px;
                                        font-weight: 700;
                                    }


                                    /* ================= DETAILS ================= */

                                    .crm-card-details {
                                        display: grid;
                                        grid-template-columns: repeat(3, minmax(0, 1fr));

                                        gap: 7px;

                                        padding: 9px 0;
                                    }


                                    /* ================= DETAIL ITEM ================= */

                                    .crm-detail-item {
                                        min-width: 0;

                                        display: flex;
                                        align-items: center;
                                        gap: 7px;

                                        padding: 7px 8px;

                                        background: #f8fafc;
                                        border-radius: 7px;
                                    }

                                    .crm-detail-icon {
                                        width: 27px;
                                        height: 27px;
                                        min-width: 27px;

                                        display: flex;
                                        align-items: center;
                                        justify-content: center;

                                        border-radius: 7px;

                                        font-size: 10px;
                                    }

                                    .crm-detail-icon.company {
                                        background: #eef4ff;
                                        color: #1b4d8f;
                                    }

                                    .crm-detail-icon.phone {
                                        background: #ecfdf3;
                                        color: #087443;
                                    }

                                    .crm-detail-icon.date {
                                        background: #fff7ed;
                                        color: #c2410c;
                                    }


                                    /* ================= DETAIL TEXT ================= */

                                    .crm-detail-content {
                                        min-width: 0;
                                    }

                                    .crm-detail-label {
                                        display: block;

                                        margin-bottom: 1px;

                                        color: #98a2b3;
                                        font-size: 8px;
                                        font-weight: 600;

                                        text-transform: uppercase;
                                        letter-spacing: .25px;
                                    }

                                    .crm-detail-content strong,
                                    .crm-phone-link {
                                        display: block;

                                        overflow: hidden;
                                        text-overflow: ellipsis;
                                        white-space: nowrap;

                                        color: #344054;
                                        font-size: 10px;
                                        font-weight: 600;
                                    }

                                    .crm-phone-link {
                                        text-decoration: none;
                                    }

                                    .crm-phone-link:hover {
                                        color: #1b4d8f;
                                    }

                                    .crm-empty {
                                        color: #98a2b3 !important;
                                    }


                                    /* ================= FOOTER ================= */

                                    .crm-card-footer {
                                        display: flex;
                                        align-items: center;
                                        justify-content: space-between;

                                        gap: 8px;

                                        padding-top: 8px;

                                        border-top: 1px solid #edf0f4;
                                    }


                                    /* ================= STATUS ================= */

                                    .crm-status {
                                        display: inline-flex;
                                        align-items: center;
                                        gap: 5px;

                                        border: 0;

                                        padding: 4px 8px;

                                        border-radius: 15px;

                                        font-size: 9px;
                                        font-weight: 700;

                                        cursor: pointer;

                                        transition: .2s ease;
                                    }

                                    .crm-status:hover {
                                        transform: translateY(-1px);
                                    }

                                    .crm-status-success {
                                        background: #ecfdf3;
                                        color: #087443;
                                    }

                                    .crm-status-warning {
                                        background: #fffaeb;
                                        color: #b54708;
                                    }

                                    .crm-status-danger {
                                        background: #fef3f2;
                                        color: #b42318;
                                    }

                                    .crm-status-default {
                                        background: #f2f4f7;
                                        color: #475467;
                                    }

                                    .crm-status-edit {
                                        margin-left: 1px;
                                        font-size: 7px;
                                        opacity: .5;
                                    }


                                    /* ================= ACTIONS ================= */

                                    .crm-actions {
                                        display: flex;
                                        align-items: center;
                                        gap: 5px;
                                    }

                                    .crm-action {
                                        height: 28px;

                                        display: inline-flex;
                                        align-items: center;
                                        justify-content: center;

                                        gap: 5px;

                                        border: 0;
                                        border-radius: 7px;

                                        padding: 0 8px;

                                        font-size: 10px;
                                        font-weight: 600;

                                        cursor: pointer;

                                        transition: .2s ease;
                                    }

                                    .crm-action:hover {
                                        transform: translateY(-1px);
                                    }

                                    .crm-mail {
                                        background: #fff7ed;
                                        color: #c2410c;
                                    }

                                    .crm-mail:hover {
                                        background: #ffedd5;
                                    }

                                    .crm-delete {
                                        width: 28px;
                                        padding: 0;

                                        background: #fef2f2;
                                        color: #dc2626;
                                    }

                                    .crm-delete:hover {
                                        background: #fee2e2;
                                    }


                                    /* ================= EMPTY ================= */

                                    .crm-empty-state {
                                        padding: 30px 15px;

                                        text-align: center;

                                        background: #fff;

                                        border: 1px dashed #d8dee8;
                                        border-radius: 10px;

                                        color: #98a2b3;
                                    }

                                    .crm-empty-state i {
                                        display: block;
                                        margin-bottom: 7px;
                                        font-size: 22px;
                                    }

                                    .crm-empty-state p {
                                        margin: 0;
                                        font-size: 11px;
                                    }


                                    /* ================= RESPONSIVE ================= */

                                    @media (max-width: 768px) {

                                        .crm-card-details {
                                            grid-template-columns: 1fr;
                                            gap: 5px;
                                        }

                                        .crm-detail-item {
                                            padding: 6px 7px;
                                        }

                                        .crm-card-footer {
                                            flex-wrap: wrap;
                                        }

                                        .crm-email {
                                            max-width: 180px;
                                        }
                                    }

                                    @media (max-width: 480px) {

                                        .crm-visitor-card {
                                            padding: 9px 10px;
                                        }

                                        .crm-avatar {
                                            width: 34px;
                                            height: 34px;
                                            min-width: 34px;
                                        }

                                        .crm-visitor-name {
                                            font-size: 12px;
                                        }

                                        .crm-card-details {
                                            margin-top: 1px;
                                        }

                                        .crm-card-footer {
                                            align-items: flex-start;
                                            flex-direction: column;
                                        }

                                        .crm-actions {
                                            width: 100%;
                                        }

                                        .crm-mail {
                                            flex: 1;
                                        }
                                    }
                                </style>
                                <!-- =========================================================
     PROFESSIONAL REFERRAL UI
========================================================= -->

                                <style>
                                    :root {
                                        --ref-primary: #0a3d61;
                                        --ref-primary-dark: #062b45;
                                        --ref-secondary: #1d6f9f;
                                        --ref-light: #eef6fa;
                                        --ref-border: #e2ebf1;
                                        --ref-text: #243746;
                                        --ref-muted: #7a8994;
                                    }


                                    /* =========================================================
   MAIN WRAPPER
========================================================= */

                                    .referral-wrapper {
                                        width: 100%;
                                        background: #fff;
                                    }


                                    /* =========================================================
   TABS
========================================================= */

                                    .referral-tabs-wrapper {
                                        border-bottom: 1px solid var(--ref-border);
                                        margin-bottom: 18px;
                                    }

                                    .referral-tabs {
                                        display: flex;
                                        gap: 8px;
                                        border: 0;
                                    }

                                    .referral-tab {
                                        position: relative;
                                        display: flex !important;
                                        align-items: center;
                                        gap: 10px;
                                        padding: 11px 16px !important;
                                        border: 1px solid transparent !important;
                                        border-radius: 10px 10px 0 0 !important;
                                        background: transparent !important;
                                        color: var(--ref-muted) !important;
                                        font-size: 12px;
                                        transition: all .25s ease;
                                    }

                                    .referral-tab:hover {
                                        color: var(--ref-primary) !important;
                                        background: var(--ref-light) !important;
                                    }

                                    .referral-tab.active {
                                        color: var(--ref-primary) !important;
                                        background: #fff !important;
                                        border-color: var(--ref-border) !important;
                                        border-bottom-color: #fff !important;
                                        margin-bottom: -1px;
                                    }

                                    .referral-tab.active::after {
                                        content: "";
                                        position: absolute;
                                        left: 14px;
                                        right: 14px;
                                        bottom: -1px;
                                        height: 3px;
                                        background: var(--ref-primary);
                                        border-radius: 5px 5px 0 0;
                                    }

                                    .tab-icon {
                                        width: 35px;
                                        height: 35px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        border-radius: 9px;
                                        background: #eaf3f8;
                                        color: var(--ref-primary);
                                        font-size: 12px;
                                    }

                                    .received-icon {
                                        background: #edf6f3;
                                        color: #21735d;
                                    }

                                    .referral-tab strong {
                                        display: block;
                                        font-size: 12px;
                                        font-weight: 700;
                                    }

                                    .referral-tab small {
                                        display: block;
                                        margin-top: 2px;
                                        color: var(--ref-muted);
                                        font-size: 9px;
                                    }


                                    /* =========================================================
   TAB CONTENT
========================================================= */

                                    .referral-tab-content {
                                        min-height: 100px;
                                    }


                                    /* =========================================================
   GIVEN REFERRAL
========================================================= */

                                    .referral-list {
                                        display: flex;
                                        flex-direction: column;
                                        gap: 10px;
                                    }

                                    .referral-item {
                                        display: grid;
                                        grid-template-columns: 1.4fr 1.2fr 1fr auto;
                                        align-items: center;
                                        gap: 20px;
                                        padding: 8px 15px;
                                        background: #fff;
                                        border: 1px solid var(--ref-border);
                                        border-radius: 12px;
                                        transition: all .25s ease;
                                    }

                                    .referral-item:hover {
                                        border-color: #c6dce8;
                                        box-shadow: 0 6px 20px rgba(10, 61, 97, .08);
                                        transform: translateY(-1px);
                                    }


                                    /* MEMBER */

                                    .referral-member {
                                        display: flex;
                                        align-items: center;
                                        gap: 11px;
                                        min-width: 0;
                                    }

                                    .member-avatar-wrapper {
                                        position: relative;
                                        flex-shrink: 0;
                                    }

                                    .member-avatar {
                                        width: 48px;
                                        height: 48px;
                                        object-fit: cover;
                                        object-position: top center;
                                        border-radius: 50%;
                                        border: 2px solid #fff;
                                        box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
                                    }

                                    .online-dot {
                                        position: absolute;
                                        right: 1px;
                                        bottom: 2px;
                                        width: 10px;
                                        height: 10px;
                                        background: #36a269;
                                        border: 2px solid #fff;
                                        border-radius: 50%;
                                    }

                                    .member-details {
                                        min-width: 0;
                                    }

                                    .member-name {
                                        margin: 0 0 4px;
                                        color: var(--ref-text);
                                        font-size: 13px;
                                        font-weight: 700;
                                    }

                                    .member-email {
                                        display: flex;
                                        align-items: center;
                                        gap: 5px;
                                        color: var(--ref-muted);
                                        font-size: 10px;
                                        white-space: nowrap;
                                        overflow: hidden;
                                        text-overflow: ellipsis;
                                    }

                                    .member-email i {
                                        color: var(--ref-primary);
                                        font-size: 9px;
                                    }


                                    /* BUSINESS */

                                    .referral-business {
                                        min-width: 0;
                                    }

                                    .business-label,
                                    .mitb-label {
                                        display: block;
                                        margin-bottom: 5px;
                                        color: #9aa6ae;
                                        font-size: 8px;
                                        font-weight: 700;
                                        letter-spacing: .8px;
                                    }

                                    .business-name {
                                        color: var(--ref-text);
                                        font-size: 12px;
                                        font-weight: 600;
                                        white-space: nowrap;
                                        overflow: hidden;
                                        text-overflow: ellipsis;
                                    }

                                    .business-name i {
                                        width: 17px;
                                        color: var(--ref-primary);
                                        font-size: 10px;
                                    }

                                    .business-phone {
                                        margin-top: 4px;
                                        color: var(--ref-muted);
                                        font-size: 10px;
                                    }

                                    .business-phone i {
                                        width: 17px;
                                        color: var(--ref-secondary);
                                    }


                                    /* STATUS */

                                    .referral-status {
                                        min-width: 120px;
                                    }

                                    .status-badge {
                                        display: inline-flex;
                                        align-items: center;
                                        gap: 5px;
                                        padding: 5px 9px;
                                        border-radius: 20px;
                                        font-size: 9px;
                                        font-weight: 700;
                                        cursor: pointer;
                                        transition: all .2s ease;
                                    }

                                    .status-badge:hover {
                                        transform: translateY(-1px);
                                    }

                                    .status-pending {
                                        background: #fff4d8;
                                        color: #9b6a00;
                                    }

                                    .status-contacted {
                                        background: #e2f4fb;
                                        color: #13718e;
                                    }

                                    .status-completed,
                                    .status-success {
                                        background: #e4f7ec;
                                        color: #187346;
                                    }

                                    .status-closed {
                                        background: #edf0f2;
                                        color: #56616a;
                                    }

                                    .status-danger {
                                        background: #fde8e8;
                                        color: #b52d35;
                                    }

                                    .status-cancelled {
                                        background: #e9ecef;
                                        color: #343a40;
                                    }

                                    .referral-date {
                                        margin-top: 7px;
                                        color: #87949d;
                                        font-size: 9px;
                                    }

                                    .referral-date i {
                                        margin-right: 3px;
                                        color: var(--ref-primary);
                                    }

                                    .date-time {
                                        margin-left: 3px;
                                        color: #a1adb5;
                                    }


                                    /* ACTION */

                                    .referral-action {
                                        display: flex;
                                        justify-content: flex-end;
                                    }

                                    .view-referral-btn {
                                        display: inline-flex;
                                        align-items: center;
                                        gap: 7px;
                                        padding: 8px 12px;
                                        border-radius: 8px;
                                        background: var(--ref-primary);
                                        color: #fff !important;
                                        text-decoration: none !important;
                                        font-size: 10px;
                                        font-weight: 600;
                                        transition: all .25s ease;
                                    }

                                    .view-referral-btn:hover {
                                        background: var(--ref-primary-dark);
                                        transform: translateX(2px);
                                        box-shadow: 0 4px 10px rgba(10, 61, 97, .2);
                                    }

                                    .view-referral-btn i {
                                        font-size: 9px;
                                    }


                                    /* =========================================================
   MITB
========================================================= */

                                    .mitb-list {
                                        display: flex;
                                        flex-direction: column;
                                        gap: 10px;
                                    }

                                    .mitb-item {
                                        display: grid;
                                        grid-template-columns: 1.2fr 1.6fr .8fr .7fr;
                                        align-items: center;
                                        gap: 18px;
                                        padding: 8px 15px;
                                        border: 1px solid var(--ref-border);
                                        border-radius: 12px;
                                        background: #fff;
                                        transition: all .25s ease;
                                    }

                                    .mitb-item:hover {
                                        border-color: #c6dce8;
                                        box-shadow: 0 6px 20px rgba(10, 61, 97, .07);
                                        transform: translateY(-1px);
                                    }

                                    .mitb-member {
                                        display: flex;
                                        align-items: center;
                                        gap: 10px;
                                    }

                                    .mitb-avatar {
                                        width: 44px;
                                        height: 44px;
                                        border-radius: 50%;
                                        object-fit: cover;
                                        object-position: top center;
                                        border: 2px solid #fff;
                                        box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
                                    }

                                    .mitb-member-info h6 {
                                        margin: 0 0 5px;
                                        color: var(--ref-text);
                                        font-size: 12px;
                                        font-weight: 700;
                                    }

                                    .category-badge {
                                        display: inline-block;
                                        padding: 3px 7px;
                                        border-radius: 5px;
                                        background: var(--ref-light);
                                        color: var(--ref-primary);
                                        font-size: 8px;
                                        font-weight: 600;
                                    }

                                    .mitb-description {
                                        min-width: 0;
                                    }

                                    .mitb-description p {
                                        margin: 0;
                                        color: #65747f;
                                        font-size: 10px;
                                        line-height: 1.5;
                                        display: -webkit-box;
                                        -webkit-line-clamp: 2;
                                        -webkit-box-orient: vertical;
                                        overflow: hidden;
                                    }

                                    .mitb-amount {
                                        padding: 8px 12px;
                                        border-radius: 9px;
                                        background: #f1f8f5;
                                    }

                                    .mitb-amount strong {
                                        display: block;
                                        color: #21735d;
                                        font-size: 15px;
                                        font-weight: 800;
                                    }

                                    .amount-label {
                                        display: block;
                                        margin-bottom: 2px;
                                        color: #7f9b90;
                                        font-size: 8px;
                                        font-weight: 700;
                                        letter-spacing: .7px;
                                    }

                                    .mitb-date strong {
                                        color: var(--ref-text);
                                        font-size: 10px;
                                        font-weight: 600;
                                    }


                                    /* =========================================================
   EMPTY STATE
========================================================= */

                                    .empty-referral {
                                        padding: 45px 20px;
                                        text-align: center;
                                        border: 1px dashed #d8e4eb;
                                        border-radius: 12px;
                                        background: #fbfdfe;
                                    }

                                    .empty-icon {
                                        width: 48px;
                                        height: 48px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        margin: 0 auto 10px;
                                        border-radius: 12px;
                                        background: var(--ref-light);
                                        color: var(--ref-primary);
                                        font-size: 16px;
                                    }

                                    .empty-referral h6 {
                                        margin: 0 0 4px;
                                        color: var(--ref-text);
                                        font-size: 13px;
                                        font-weight: 700;
                                    }

                                    .empty-referral p {
                                        margin: 0;
                                        color: var(--ref-muted);
                                        font-size: 10px;
                                    }


                                    /* =========================================================
   TABLET
========================================================= */

                                    @media (max-width: 991px) {

                                        .referral-item {
                                            grid-template-columns: 1.2fr 1fr;
                                            gap: 12px;
                                        }

                                        .referral-status {
                                            grid-column: 1;
                                        }

                                        .referral-action {
                                            grid-column: 2;
                                            grid-row: 2;
                                        }

                                        .mitb-item {
                                            grid-template-columns: 1fr 1fr;
                                            gap: 12px;
                                        }

                                    }


                                    /* =========================================================
   MOBILE
========================================================= */

                                    @media (max-width: 575px) {

                                        .referral-tabs {
                                            gap: 3px;
                                        }

                                        .referral-tab {
                                            flex: 1;
                                            padding: 9px 8px !important;
                                            gap: 7px;
                                        }

                                        .tab-icon {
                                            width: 30px;
                                            height: 30px;
                                            min-width: 30px;
                                        }

                                        .referral-tab strong {
                                            font-size: 10px;
                                        }

                                        .referral-tab small {
                                            font-size: 8px;
                                        }

                                        .referral-item {
                                            display: block;
                                            padding: 12px;
                                        }

                                        .referral-member {
                                            margin-bottom: 12px;
                                        }

                                        .referral-business {
                                            padding: 10px 0;
                                            border-top: 1px solid #edf2f5;
                                            border-bottom: 1px solid #edf2f5;
                                        }

                                        .referral-status {
                                            display: flex;
                                            align-items: center;
                                            justify-content: space-between;
                                            margin-top: 10px;
                                        }

                                        .referral-date {
                                            margin-top: 0;
                                        }

                                        .referral-action {
                                            display: block;
                                            margin-top: 10px;
                                        }

                                        .view-referral-btn {
                                            width: 100%;
                                            justify-content: center;
                                        }

                                        .mitb-item {
                                            display: block;
                                            padding: 12px;
                                        }

                                        .mitb-member {
                                            margin-bottom: 10px;
                                        }

                                        .mitb-description {
                                            padding: 10px 0;
                                            border-top: 1px solid #edf2f5;
                                            border-bottom: 1px solid #edf2f5;
                                        }

                                        .mitb-amount {
                                            margin-top: 10px;
                                        }

                                        .mitb-date {
                                            margin-top: 10px;
                                        }

                                        .mitb-date strong {
                                            font-size: 10px;
                                        }
                                    }
                                </style>


                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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