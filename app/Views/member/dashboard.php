<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>EAF Connect | Member Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    
    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Core Styles -->
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Master UI/UX Revamp Stylesheet (Phase 1) -->
    <link href="<?= base_url(); ?>assets2/css/eaf-revamp.css?v=8.0" rel="stylesheet" type="text/css" />

    <style>
        /* Legacy backward compatibility helpers */
        .chapter-performance {
            background: #fff;
            border-radius: var(--eaf-radius-xl, 20px);
            padding: 22px;
            border: 1px solid var(--eaf-border-subtle, #edf2f7);
            box-shadow: var(--eaf-shadow-sm, 0 2px 6px rgba(15, 23, 42, 0.04));
            margin-bottom: 24px;
        }

        .chapter-performance-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .chapter-performance-header .btn {
            white-space: nowrap;
            flex-shrink: 0;
        }

        .chapter-heading {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chapter-heading-icon {
            width: 44px;
            height: 44px;
            background: rgba(15, 59, 104, 0.08);
            color: #0F3B68;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .chapter-heading h4 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
            color: #0F3B68;
        }

        .chapter-heading span {
            font-size: 12px;
            color: #64748B;
        }

        .chapter-kpi-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
        }

        @media (max-width: 991px) {
            .chapter-kpi-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
            }
        }

        @media (max-width: 576px) {
            .chapter-kpi-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }
            .chapter-kpi-card {
                padding: 10px 12px;
            }
            .chapter-kpi-icon {
                width: 36px;
                height: 36px;
                font-size: 15px;
            }
            .chapter-kpi-content strong {
                font-size: 15px;
            }
        }


        .chapter-kpi-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.25s ease;
        }

        .chapter-kpi-card:hover {
            background: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(15, 59, 104, 0.08);
            border-color: #CBD5E1;
        }

        .chapter-kpi-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .members-kpi .chapter-kpi-icon { background: rgba(37, 99, 235, 0.1); color: #2563EB; }
        .connects-kpi .chapter-kpi-icon { background: rgba(217, 119, 6, 0.1); color: #D97706; }
        .referral-kpi .chapter-kpi-icon { background: rgba(5, 150, 105, 0.1); color: #059669; }
        .meeting-kpi .chapter-kpi-icon { background: rgba(124, 58, 237, 0.1); color: #7C3AED; }
        .visitor-kpi .chapter-kpi-icon { background: rgba(13, 148, 136, 0.1); color: #0D9488; }

        .chapter-kpi-content span {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .chapter-kpi-content strong {
            display: block;
            font-size: 18px;
            font-weight: 800;
            color: #0F172A;
        }

        /* Reports Quick Cards */
        .eaf-report-tile {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none !important;
            transition: all 0.25s ease;
            margin-bottom: 12px;
        }

        .eaf-report-tile:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(15, 59, 104, 0.08);
            border-color: #0F3B68;
        }

        .eaf-report-tile-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .eaf-report-tile-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .eaf-report-tile h6 {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            color: #0F172A;
        }

        .eaf-report-tile span {
            font-size: 11px;
            color: #64748B;
        }

        /* Ads Campaign Slider */
        .ads-campaign-wrapper {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
            padding: 16px;
            margin-bottom: 24px;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
        }

        .ads-campaign-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            padding: 0 4px;
        }

        .ads-campaign-header h5 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #0F3B68;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ads-live-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #FEE2E2;
            color: #DC2626;
            font-size: 10px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 50px;
            letter-spacing: 0.5px;
        }

        .ads-live-badge span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #DC2626;
            animation: pulse-dot 1.5s infinite;
        }

        @keyframes pulse-dot {
            0% { transform: scale(0.95); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.5; }
            100% { transform: scale(0.95); opacity: 1; }
        }

        .ads-banner {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            width: 100%;
            background: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.9);
        }

        .ads-banner img {
            width: 100%;
            height: auto;
            max-height: 480px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            border-radius: 14px;
            transition: transform 0.4s ease;
        }

        .ads-carousel:hover .ads-banner img {
            transform: scale(1.006);
        }

        .ads-banner-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(11, 25, 44, 0.88) 100%);
            display: flex;
            align-items: flex-end;
            padding: 16px 22px;
            color: #ffffff;
            pointer-events: none;
            border-bottom-left-radius: 14px;
            border-bottom-right-radius: 14px;
        }

        .ads-banner-overlay h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.7);
        }

        .ads-banner-overlay p {
            margin: 4px 0 0;
            font-size: 12px;
            color: #E2E8F0;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.7);
        }

        .ads-carousel .carousel-control-prev,
        .ads-carousel .carousel-control-next {
            width: 40px;
            height: 40px;
            background: rgba(15, 59, 104, 0.75);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            opacity: 0.85;
            transition: all 0.2s ease;
        }

        .ads-carousel .carousel-control-prev { left: 14px; }
        .ads-carousel .carousel-control-next { right: 14px; }

        .ads-carousel .carousel-control-prev:hover,
        .ads-carousel .carousel-control-next:hover {
            background: #0F3B68;
            opacity: 1;
            transform: translateY(-50%) scale(1.08);
        }

        @media (max-width: 991px) {
            .chapter-kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .chapter-kpi-grid {
                grid-template-columns: 1fr;
            }

            .chapter-performance {
                padding: 16px 14px;
                border-radius: 16px;
            }

            .chapter-performance-header {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
                margin-bottom: 14px;
            }

            .chapter-heading {
                gap: 10px;
            }

            .chapter-heading-icon {
                width: 38px;
                height: 38px;
                font-size: 18px;
                border-radius: 10px;
                flex-shrink: 0;
            }

            .chapter-heading h4 {
                font-size: 15px;
                line-height: 1.3;
            }

            .chapter-heading span {
                font-size: 11px;
            }

            .chapter-performance-header .btn {
                width: 100%;
                text-align: center;
                justify-content: center;
                display: inline-flex;
                align-items: center;
                padding: 8px 14px;
                font-size: 12px;
                font-weight: 600;
                white-space: nowrap;
            }

            .eaf-growth-card {
                padding: 16px 12px;
            }

            .eaf-growth-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .eaf-growth-header h5 {
                font-size: 14px;
                line-height: 1.35;
            }

            .eaf-chart-metric-tile {
                padding: 10px 10px;
                border-radius: 12px;
            }

            .eaf-chart-metric-header {
                gap: 4px;
                margin-bottom: 6px;
                min-height: 28px;
            }

            .eaf-chart-metric-title {
                font-size: 9.5px;
                letter-spacing: 0.2px;
                line-height: 1.25;
                flex: 1;
            }

            .eaf-chart-metric-badge {
                font-size: 8.5px !important;
                padding: 2px 5px !important;
            }

            .eaf-chart-metric-value {
                font-size: 14.5px;
                font-weight: 800;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
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
                <div class="container-fluid py-2">

                    <!-- Flash Message -->
                    <?php if (session()->getFlashdata('msg')): ?>
                        <div class="alert alert-info alert-dismissible fade show rounded-3 mb-3 border-0 shadow-sm" role="alert">
                            <i class="ri-information-line me-2"></i>
                            <strong><?= session()->getFlashdata('msg'); ?></strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php
                    // Safe helper queries for complete presentation enrichment (100% Read-Only, Zero DB Risk)
                    $db = \Config\Database::connect();
                    $user_code = session()->get('user_code');

                    $currentMember = $db->table('users')
                        ->where('user_code', $user_code)
                        ->get()
                        ->getRowArray();

                    $chapterDetails = $db->table('chapter_members cm')
                        ->select('c.chapter_name, c.chapter_code, cm.chapter_role')
                        ->join('chapters c', 'c.chapter_code = cm.chapter_code', 'left')
                        ->where('cm.user_code', $user_code)
                        ->where('cm.members_status', '1')
                        ->get()
                        ->getRowArray();

                    $companyName = !empty($currentMember['business_name']) ? $currentMember['business_name'] : (!empty($currentMember['company_name']) ? $currentMember['company_name'] : 'EAF Member');
                    $businessCategory = !empty($currentMember['industry']) ? $currentMember['industry'] : (!empty($currentMember['category']) ? $currentMember['category'] : 'Business Network');
                    $chapterName = !empty($chapterDetails['chapter_name']) ? $chapterDetails['chapter_name'] : (session()->get('chapter_name') ?: 'Executive Chapter');
                    $memberRole = !empty($currentMember['member_role']) ? $currentMember['member_role'] : (session()->get('member_role') ?: 'Active Member');
                    $profilePhoto = !empty($currentMember['profile_photo']) ? $currentMember['profile_photo'] : (session()->get('profile_photo') ?: 'empty.png');
                    $memberName = !empty($currentMember['name']) ? $currentMember['name'] : session()->get('name');

                    $formatInr = function($amount) {
                        return number_format((float)($amount ?? 0), 2);
                    };
                    ?>

                    <!-- ==========================================================================
                         1. TOP PROMOTIONAL AD BANNER CAROUSEL (KVV BENCHMARK)
                         ========================================================================== -->
                    <?php
                    $chapter_code = session()->get('chapter_code');
                    $today = date('Y-m-d');

                    $active_ads = $db->table('ads')
                        ->groupStart()
                        ->where('ads_status', '1')
                        ->orGroupStart()
                        ->where('ads_status', '2')
                        ->where('from_date <=', $today)
                        ->where('to_date >=', $today)
                        ->groupEnd()
                        ->groupEnd();

                    if ($chapter_code !== 'all' && !empty($chapter_code)) {
                        $active_ads->groupStart()
                            ->where('chapter_code', $chapter_code)
                            ->orWhere('chapter_code', 'all')
                            ->groupEnd();
                    }

                    $active_ads = $active_ads
                        ->orderBy('ads_id', 'DESC')
                        ->get()
                        ->getResultArray();
                    ?>

                    <!-- ==========================================================================
                         1. TOP PROMOTIONAL AD BANNER CAROUSEL (KVV BENCHMARK)
                         ========================================================================== -->
                    <div class="kvv-banner-wrapper">
                        <div id="kvvAdsCarousel" class="carousel slide kvv-carousel-container" data-bs-ride="carousel" data-bs-interval="5000" data-bs-touch="true">
                            <?php if (!empty($active_ads) && count($active_ads) > 1): ?>
                                <!-- Carousel Indicators -->
                                <div class="carousel-indicators kvv-carousel-indicators">
                                    <?php foreach ($active_ads as $k => $ad): ?>
                                        <button type="button" data-bs-target="#kvvAdsCarousel" data-bs-slide-to="<?= $k ?>" class="<?= $k === 0 ? 'active' : '' ?>" aria-current="<?= $k === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $k + 1 ?>"></button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="carousel-inner">
                                <?php if (!empty($active_ads)): ?>
                                    <?php foreach ($active_ads as $k => $ad): ?>
                                        <?php $adImageUrl = base_url('uploads/ads/' . $ad['ads_image']); ?>
                                        <div class="carousel-item <?= $k === 0 ? 'active' : '' ?>">
                                            <div class="kvv-banner-card">
                                                <img src="<?= $adImageUrl; ?>" 
                                                     alt="<?= esc($ad['ads_title'] ?: 'Member Spotlight'); ?>" 
                                                     class="kvv-banner-img"
                                                     onerror="this.onerror=null; this.src='<?= base_url('images/sample-banner.png'); ?>';">
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="carousel-item active">
                                        <div class="kvv-banner-card">
                                            <img src="<?= base_url('images/sample-banner.png'); ?>" 
                                                 alt="Member Spotlight"
                                                 class="kvv-banner-img"
                                                 onerror="this.onerror=null; this.src='<?= base_url('images/sample-banner.jpg'); ?>';">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($active_ads) && count($active_ads) > 1): ?>
                                <!-- Floating In-Banner Nav Buttons -->
                                <button class="carousel-control-prev kvv-carousel-arrow-btn" type="button" data-bs-target="#kvvAdsCarousel" data-bs-slide="prev" aria-label="Previous">
                                    <i class="ri-arrow-left-s-line"></i>
                                </button>
                                <button class="carousel-control-next kvv-carousel-arrow-btn" type="button" data-bs-target="#kvvAdsCarousel" data-bs-slide="next" aria-label="Next">
                                    <i class="ri-arrow-right-s-line"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ==========================================================================
                         2. HERO QUICK ACTION SLIPS (EAF BENCHMARK: 4 CLEAN ACTION CARDS)
                         ========================================================================== -->
                    <div class="kvv-quick-actions-grid">
                        <!-- Action 1: Give Connect -->
                        <div class="kvv-action-slip-card action-give" onclick="if(typeof add_new_connects==='function'){add_new_connects();}else{window.location.href='<?= base_url('member/connect'); ?>';}">
                            <div class="kvv-slip-top-row">
                                <div class="kvv-slip-icon-wrap icon-give">
                                    <i class="ri-send-plane-2-fill"></i>
                                </div>
                                <div class="kvv-action-slip-arrow">
                                    <i class="ri-arrow-right-line"></i>
                                </div>
                            </div>
                            <div class="kvv-slip-body">
                                <span class="kvv-action-slip-title">Give Connect</span>
                                <span class="kvv-action-slip-sub">New referral</span>
                            </div>
                        </div>

                        <!-- Action 2: Received Connects -->
                        <a href="<?= base_url('member/connect'); ?>" class="kvv-action-slip-card action-receive">
                            <div class="kvv-slip-top-row">
                                <div class="kvv-slip-icon-wrap icon-receive">
                                    <i class="ri-inbox-archive-fill"></i>
                                </div>
                                <div class="kvv-action-slip-arrow">
                                    <i class="ri-arrow-right-line"></i>
                                </div>
                            </div>
                            <div class="kvv-slip-body">
                                <span class="kvv-action-slip-title">Received Connects</span>
                                <span class="kvv-action-slip-sub">Inbound leads</span>
                            </div>
                        </a>

                        <!-- Action 3: F2F Meeting -->
                        <div class="kvv-action-slip-card action-f2f" onclick="if(typeof add_f2f==='function'){add_f2f();}else{window.location.href='<?= base_url('member/f2f'); ?>';}">
                            <div class="kvv-slip-top-row">
                                <div class="kvv-slip-icon-wrap icon-f2f">
                                    <i class="ri-user-voice-fill"></i>
                                </div>
                                <div class="kvv-action-slip-arrow">
                                    <i class="ri-arrow-right-line"></i>
                                </div>
                            </div>
                            <div class="kvv-slip-body">
                                <span class="kvv-action-slip-title">F2F Meeting</span>
                                <span class="kvv-action-slip-sub">1-to-1 meeting</span>
                            </div>
                        </div>

                        <!-- Action 4: MITB (Closed Business) -->
                        <div class="kvv-action-slip-card action-mitb" onclick="if(typeof add_mitb==='function'){add_mitb();}else{window.location.href='<?= base_url('member/mitb'); ?>';}">
                            <div class="kvv-slip-top-row">
                                <div class="kvv-slip-icon-wrap icon-mitb">
                                    <i class="ri-money-rupee-circle-fill"></i>
                                </div>
                                <div class="kvv-action-slip-arrow">
                                    <i class="ri-arrow-right-line"></i>
                                </div>
                            </div>
                            <div class="kvv-slip-body">
                                <span class="kvv-action-slip-title">MITB (Closed Business)</span>
                                <span class="kvv-action-slip-sub">Report revenue</span>
                            </div>
                        </div>
                    </div>

                    <!-- ==========================================================================
                         3. CORE PERFORMANCE ROW: PROGRESS THIS WEEK + NEXT MEETING (EAF BENCHMARK)
                         ========================================================================== -->
                    <div class="row g-3 mb-4">
                        <!-- Column 1: Your Business Progress This Week (2x2 Grid) -->
                        <div class="col-lg-6 col-md-12">
                            <div class="kvv-widget-box">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h5 class="kvv-widget-title mb-0">
                                        <i class="ri-bar-chart-grouped-fill text-primary me-2"></i>Your Business Progress This Week
                                    </h5>
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill font-size-11 px-2 py-1">Current Week</span>
                                </div>
                                <div class="kvv-progress-2x2-grid">
                                    <!-- Connects Received -->
                                    <div class="kvv-progress-cell cell-inbound">
                                        <div class="kvv-progress-cell-top">
                                            <span class="kvv-progress-number"><?= (int)($weekly_f2f_receive ?? $r_r_c ?? 0); ?></span>
                                            <span class="kvv-arrow-box arrow-inbound" title="Inbound Connects"><i class="ri-arrow-left-down-line"></i></span>
                                        </div>
                                        <span class="kvv-progress-caption">Connects Received</span>
                                    </div>

                                    <!-- Connects Given -->
                                    <div class="kvv-progress-cell cell-outbound">
                                        <div class="kvv-progress-cell-top">
                                            <span class="kvv-progress-number"><?= (int)($weekly_ref_given ?? 0); ?></span>
                                            <span class="kvv-arrow-box arrow-outbound" title="Outbound Connects"><i class="ri-arrow-right-up-line"></i></span>
                                        </div>
                                        <span class="kvv-progress-caption">Connects Given</span>
                                    </div>

                                    <!-- Business Received -->
                                    <div class="kvv-progress-cell cell-inbound">
                                        <div class="kvv-progress-cell-top">
                                            <span class="kvv-progress-number"><small class="kvv-currency">₹</small><?= number_format((float)($weekly_mitb_received ?? 0)); ?></span>
                                            <span class="kvv-arrow-box arrow-inbound" title="Business Inbound"><i class="ri-arrow-left-down-line"></i></span>
                                        </div>
                                        <span class="kvv-progress-caption">Business Received</span>
                                    </div>

                                    <!-- Business Given -->
                                    <div class="kvv-progress-cell cell-outbound">
                                        <div class="kvv-progress-cell-top">
                                            <span class="kvv-progress-number"><small class="kvv-currency">₹</small><?= number_format((float)($weekly_mitb_given ?? 0)); ?></span>
                                            <span class="kvv-arrow-box arrow-outbound" title="Business Outbound"><i class="ri-arrow-right-up-line"></i></span>
                                        </div>
                                        <span class="kvv-progress-caption">Business Given</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Next Meeting (KVV Benchmark) -->
                        <div class="col-lg-6 col-md-12">
                            <div class="kvv-widget-box d-flex flex-column justify-content-between">
                                <div>
                                    <div class="kvv-next-meeting-badge">Next Meeting</div>
                                    <?php
                                    $nextMeetingDateStr = 'Wednesday, 21 October';
                                    $nextMeetingLocStr = 'Kings Club, Nagarbhavi';
                                    $nextMeetingTimeStr = '8:00 AM to 9:30 AM';

                                    if (!empty($events)) {
                                        $m = $events[0];
                                        $ts = !empty($m['date']) ? strtotime($m['date']) : time();
                                        $nextMeetingDateStr = date('l, d F', $ts);
                                        $nextMeetingLocStr = !empty($m['venue']) ? $m['venue'] : (!empty($m['location']) ? $m['location'] : 'Kings Club, Nagarbhavi');
                                        if (strtolower($nextMeetingLocStr) === 'nagarbhavi') {
                                            $nextMeetingLocStr = 'Kings Club, Nagarbhavi';
                                        }
                                        $nextMeetingTimeStr = !empty($m['time']) ? $m['time'] . ' to 9:30 AM' : '8:00 AM to 9:30 AM';
                                    }
                                    ?>
                                    <div class="kvv-meeting-rows">
                                        <div class="kvv-meeting-row-inline">
                                            <div class="kvv-meeting-point">
                                                <i class="ri-calendar-2-fill"></i>
                                                <span><?= esc($nextMeetingDateStr); ?></span>
                                            </div>
                                            <div class="kvv-meeting-point">
                                                <i class="ri-map-pin-2-fill"></i>
                                                <span><?= esc($nextMeetingLocStr); ?></span>
                                            </div>
                                        </div>
                                        <div class="kvv-meeting-row-inline">
                                            <div class="kvv-meeting-point">
                                                <i class="ri-time-fill"></i>
                                                <span><?= esc($nextMeetingTimeStr); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($events) && !empty($events[0]['meeting_link']) && ($events[0]['mode'] ?? '') !== 'offline'): ?>
                                    <div class="mt-3 pt-2 border-top">
                                        <a href="<?= esc($events[0]['meeting_link']); ?>" target="_blank" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                            <i class="ri-video-line me-1"></i> Join Online
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- ==========================================================================
                         4. BNI CONNECT BENCHMARK: INTERACTIVE SPLINE GROWTH CHART & METRICS
                         ========================================================================== -->
                    <div class="eaf-growth-card">
                        <div class="eaf-growth-header">
                            <h5>
                                <i class="ri-line-chart-line text-warning"></i>
                                Networking Revenue Growth & Business Exchanged
                            </h5>
                            <div class="eaf-time-filter-wrap">
                                <a href="<?= current_url() . '?filter=15' ?>" class="eaf-time-chip <?= ($filter == '15') ? 'active' : '' ?>">15D</a>
                                <a href="<?= current_url() . '?filter=30' ?>" class="eaf-time-chip <?= ($filter == '30') ? 'active' : '' ?>">1M</a>
                                <a href="<?= current_url() . '?filter=180' ?>" class="eaf-time-chip <?= ($filter == '180') ? 'active' : '' ?>">6M</a>
                                <a href="<?= current_url() . '?filter=all' ?>" class="eaf-time-chip <?= ($filter == 'all') ? 'active' : '' ?>">All</a>
                            </div>
                        </div>

                        <!-- Canvas for Spline Curve -->
                        <div class="eaf-chart-container mb-3">
                            <canvas id="eafSplineGrowthChart" height="95"></canvas>
                        </div>

                        <!-- BNI Linked Metric Selector Cards -->
                        <div class="row g-2 pt-2 border-top border-light eaf-growth-metrics-row">
                            <!-- Revenue Received -->
                            <div class="col-xl-3 col-md-6 col-6">
                                <div class="eaf-chart-metric-tile">
                                    <div class="eaf-chart-metric-header">
                                        <span class="eaf-chart-metric-title">Business Received</span>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill eaf-chart-metric-badge">↙ Inbound</span>
                                    </div>
                                    <div class="eaf-chart-metric-value text-success">
                                        ₹ <?= $formatInr($totalMITBReceived ?? 0); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Revenue Given -->
                            <div class="col-xl-3 col-md-6 col-6">
                                <div class="eaf-chart-metric-tile">
                                    <div class="eaf-chart-metric-header">
                                        <span class="eaf-chart-metric-title">Business Given</span>
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill eaf-chart-metric-badge">↗ Outbound</span>
                                    </div>
                                    <div class="eaf-chart-metric-value" style="color:#0F3B68;">
                                        ₹ <?= $formatInr($totalMITBGiven ?? 0); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Referrals Given -->
                            <div class="col-xl-3 col-md-6 col-6">
                                <div class="eaf-chart-metric-tile">
                                    <div class="eaf-chart-metric-header">
                                        <span class="eaf-chart-metric-title">Referrals Given</span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill eaf-chart-metric-badge">↗ Connects</span>
                                    </div>
                                    <div class="eaf-chart-metric-value text-primary">
                                        <?= (int)($r_g_c ?? 0); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- 1-to-1 Meetings -->
                            <div class="col-xl-3 col-md-6 col-6">
                                <div class="eaf-chart-metric-tile">
                                    <div class="eaf-chart-metric-header">
                                        <span class="eaf-chart-metric-title">1-to-1 Conducted</span>
                                        <span class="badge bg-purple bg-opacity-10 text-purple rounded-pill eaf-chart-metric-badge" style="background:rgba(124,58,237,.1);color:#7C3AED;">🤝 F2F</span>
                                    </div>
                                    <div class="eaf-chart-metric-value" style="color:#7C3AED;">
                                        <?= (int)($f2f__sc ?? 0); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==========================================================================
                         5. CHAPTER OVERVIEW & QUICK REPORTS
                         ========================================================================== -->
                    <div class="chapter-performance">
                        <div class="chapter-performance-header">
                            <div class="chapter-heading">
                                <div class="chapter-heading-icon">
                                    <i class="ri-community-line"></i>
                                </div>
                                <div>
                                    <h4>My Chapter Performance: <?= esc($chapterName); ?></h4>
                                    <span>Live activity overview across chapter members</span>
                                </div>
                            </div>
                            <a href="<?= base_url('member/mychapter'); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                View Chapter Directory <i class="ri-arrow-right-s-line"></i>
                            </a>
                        </div>

                        <!-- KPI Grid -->
                        <div class="chapter-kpi-grid mb-3">
                            <div class="chapter-kpi-card members-kpi">
                                <div class="chapter-kpi-icon"><i class="ri-group-line"></i></div>
                                <div class="chapter-kpi-content">
                                    <span>Total Members</span>
                                    <strong><?= (int)($totalMembers ?? 0); ?></strong>
                                </div>
                            </div>

                            <div class="chapter-kpi-card connects-kpi">
                                <div class="chapter-kpi-icon"><i class="ri-money-rupee-circle-line"></i></div>
                                <div class="chapter-kpi-content">
                                    <span>Chapter MITB</span>
                                    <strong>₹<?= $formatInr($mitb ?? 0); ?></strong>
                                </div>
                            </div>

                            <div class="chapter-kpi-card referral-kpi">
                                <div class="chapter-kpi-icon"><i class="ri-shake-hands-line"></i></div>
                                <div class="chapter-kpi-content">
                                    <span>Total Connects</span>
                                    <strong><?= (int)($connects ?? 0); ?></strong>
                                </div>
                            </div>

                            <div class="chapter-kpi-card meeting-kpi">
                                <div class="chapter-kpi-icon"><i class="ri-user-voice-line"></i></div>
                                <div class="chapter-kpi-content">
                                    <span>F2F Initiated</span>
                                    <strong><?= (int)($meetings ?? 0); ?></strong>
                                </div>
                            </div>

                            <div class="chapter-kpi-card visitor-kpi">
                                <div class="chapter-kpi-icon"><i class="ri-user-add-line"></i></div>
                                <div class="chapter-kpi-content">
                                    <span>Total Visitors</span>
                                    <strong><?= (int)($visitors ?? 0); ?></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Reports Shortcuts -->
                        <div class="row g-2 pt-2 border-top border-light">
                            <div class="col-md-4">
                                <a href="<?= base_url('member/reports/weekly_slips'); ?>" class="eaf-report-tile">
                                    <div class="eaf-report-tile-left">
                                        <div class="eaf-report-tile-icon bg-primary bg-opacity-10 text-primary">
                                            <i class="ri-printer-line"></i>
                                        </div>
                                        <div>
                                            <h6>Print Weekly Slips</h6>
                                            <span>Export physical slip summary</span>
                                        </div>
                                    </div>
                                    <i class="ri-arrow-right-s-line text-muted"></i>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="<?= base_url('member/received_connects_report'); ?>" class="eaf-report-tile">
                                    <div class="eaf-report-tile-left">
                                        <div class="eaf-report-tile-icon bg-success bg-opacity-10 text-success">
                                            <i class="ri-file-list-3-line"></i>
                                        </div>
                                        <div>
                                            <h6>Received Connects Report</h6>
                                            <span>Conversion tracking & history</span>
                                        </div>
                                    </div>
                                    <i class="ri-arrow-right-s-line text-muted"></i>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="<?= base_url('member/my_participation_report'); ?>" class="eaf-report-tile">
                                    <div class="eaf-report-tile-left">
                                        <div class="eaf-report-tile-icon bg-warning bg-opacity-10 text-warning">
                                            <i class="ri-pie-chart-2-line"></i>
                                        </div>
                                        <div>
                                            <h6>Participation Report</h6>
                                            <span>Attendance & slip compliance</span>
                                        </div>
                                    </div>
                                    <i class="ri-arrow-right-s-line text-muted"></i>
                                </a>
                            </div>
                        </div>
                    </div>



                </div>
            </div>

            <!-- Footer (Includes Mobile Bottom Navigation Bar) -->
            <?php echo view('include/member/footer.php') ?>
        </div>
    </div>

    <!-- Modals for Native Quick Slips -->
    <?php echo view('include/member/popup/connects.php'); ?>
    <?php echo view('include/member/popup/mitb.php'); ?>
    <?php
    $f2f_members = $db->table('chapter_members cm')
        ->select('u.user_code, u.name, c.chapter_name')
        ->join('users u', 'u.user_code = cm.user_code', 'left')
        ->join('chapters c', 'c.chapter_code = cm.chapter_code', 'left')
        ->where('cm.members_status', '1')
        ->where('u.status', '1')
        ->groupBy(['u.user_code', 'u.name', 'c.chapter_name'])
        ->get()
        ->getResultArray();
    echo view('include/member/popup/f2f.php', ['member' => $f2f_members]);
    ?>
    <?php echo view('include/member/popup/visitor.php', ['events' => $events ?? []]); ?>

    <!-- JAVASCRIPT LIBRARIES -->
    <script src="<?= base_url(); ?>assets2/libs/jquery/jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/metismenu/metisMenu.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/simplebar/simplebar.min.js"></script>
    <script src="<?= base_url(); ?>assets2/libs/node-waves/waves.min.js"></script>
    <script src="<?= base_url(); ?>assets2/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Interactive Spline Growth Chart (BNI Connect Standard) -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Toggle Current / Previous Progress
        window.switchProgressTab = function(type) {
            const curBtn = document.getElementById('tabBtnCurrent');
            const prevBtn = document.getElementById('tabBtnPrevious');
            const curContent = document.getElementById('progressCurrentContent');
            const prevContent = document.getElementById('progressPreviousContent');

            if (type === 'current') {
                curBtn.classList.add('active');
                prevBtn.classList.remove('active');
                curContent.style.display = 'block';
                prevContent.style.display = 'none';
            } else {
                prevBtn.classList.add('active');
                curBtn.classList.remove('active');
                curContent.style.display = 'none';
                prevContent.style.display = 'block';
            }
        };

        // BNI Connect Spline Chart Init
        const chartCanvas = document.getElementById('eafSplineGrowthChart');
        if (chartCanvas) {
            const ctx = chartCanvas.getContext('2d');
            const labels = <?= !empty($dates) ? $dates : '[]' ?>;
            const givenData = <?= !empty($givenData) ? $givenData : '[]' ?>;
            const receivedData = <?= !empty($receivedData) ? $receivedData : '[]' ?>;

            // Gradient fills
            const gradientReceived = ctx.createLinearGradient(0, 0, 0, 200);
            gradientReceived.addColorStop(0, 'rgba(5, 150, 105, 0.22)');
            gradientReceived.addColorStop(1, 'rgba(5, 150, 105, 0.00)');

            const gradientGiven = ctx.createLinearGradient(0, 0, 0, 200);
            gradientGiven.addColorStop(0, 'rgba(15, 59, 104, 0.22)');
            gradientGiven.addColorStop(1, 'rgba(15, 59, 104, 0.00)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels.length ? labels : ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [
                        {
                            label: 'Business Received (₹)',
                            data: receivedData.length ? receivedData : [0, 0, 0, 0],
                            borderColor: '#059669',
                            backgroundColor: gradientReceived,
                            fill: true,
                            tension: 0.45,
                            borderWidth: 2.5,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#059669'
                        },
                        {
                            label: 'Business Given (₹)',
                            data: givenData.length ? givenData : [0, 0, 0, 0],
                            borderColor: '#0F3B68',
                            backgroundColor: gradientGiven,
                            fill: true,
                            tension: 0.45,
                            borderWidth: 2.5,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#0F3B68'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    layout: {
                        padding: {
                            top: 14,
                            right: 8,
                            bottom: 4,
                            left: 0
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                font: { family: 'Outfit', size: 12, weight: '600' }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += '₹' + new Intl.NumberFormat('en-IN').format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(226, 232, 240, 0.6)' },
                            ticks: {
                                font: { family: 'Outfit', size: 11 },
                                callback: function(value) {
                                    return '₹' + (value >= 1000 ? (value / 1000) + 'k' : value);
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Outfit', size: 11 } }
                        }
                    }
                }
            });
        }
    });
    </script>
</body>
</html>