<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Connects </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets2/css/eaf-revamp.css?v=8.0" rel="stylesheet" type="text/css" />
    <style>
     


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
        }

        /* ===== WRAPPER ===== */
        .connect-wrapper {
            background: #f4f6f9;
            padding: 15px 18px;
            /* reduced space */
        }

        /* ===== HEADER ===== */
        .connect-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            /* reduced */
        }

        .title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .subtitle {
            font-size: 13px;
            color: #7b8794;
            margin: 0;
        }

        /* ===== MAIN TABS ===== */
        .connect-tabs {
            display: flex;
            background: #e6e8eb;
            border-radius: 10px;
            padding: 4px;
            gap: 4px;
            margin-bottom: 12px;
        }

        .tab-btn {
            flex: 1;
            border: none;
            padding: 8px 10px;
            /* reduced */
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            background: transparent;
            transition: .25s;
        }

        .tab-btn.active {
            background: #fac532;
            color: #fff;
        }

        /* ===== CONTENT AREA ===== */
        .connect-content {
            margin-top: 5px;
        }

        /* ===== CARD ===== */
        .content-card {
            background: #fff;
            border-radius: 12px;
            padding: 18px;
            /* reduced */
            box-shadow: 0 3px 10px rgba(0, 0, 0, .06);
        }

        /* ===== FILTER TABS ===== */
        .filter-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .filter-btn {
            border: 1px solid #E2E8F0;
            background: #F8FAFC;
            color: #475569;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            transition: all .2s ease;
            cursor: pointer;
            user-select: none;
        }

        .filter-btn:hover {
            background: #F1F5F9;
            color: #0F172A;
            border-color: #CBD5E1;
        }

        .filter-btn.active {
            background: #fac532;
            color: #0F172A;
            border-color: #e5af1f;
            box-shadow: 0 2px 6px rgba(250, 197, 50, 0.35);
        }

        /* ===== TAB CONTENT VISIBILITY ===== */
        .tab-content-box {
            display: none;
        }

        .tab-content-box.active {
            display: block;
        }

        .connect-modal {
            font-size: 14px;
        }

        .member-source {
            display: flex;
            gap: 10px;
        }

        .radio-card {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
            text-align: center;
            transition: .25s;
        }

        .radio-card input {
            display: none;
        }

        .radio-card.active {
            background: #08426b;
            color: #fff;
            border-color: #08426b;
        }

        .radio-card:hover {
            transform: translateY(-2px);
        }




        /* ==========================================================================
           PERFECT 1-LINE LUXURY REFERRAL CARD (KVV & BNI Benchmark)
           ========================================================================== */
        .referral-wrapper {
            overflow-x: auto;
            padding-bottom: 6px;
        }

        .referral-card-row {
            width: 100%;
        }

        .referral-card {
            display: grid !important;
            grid-template-columns: 220px 32px 200px 230px 120px 160px 110px !important;
            align-items: center !important;
            gap: 12px !important;
            background: #FFFFFF !important;
            border: 1px solid #E2E8F0 !important;
            border-radius: 12px !important;
            padding: 0 16px !important;
            box-shadow: 0 2px 8px rgba(15, 59, 104, 0.04) !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
            width: 1200px !important;
            min-width: 1200px !important;
            height: 64px !important;
            min-height: 64px !important;
            max-height: 64px !important;
            white-space: nowrap !important;
            box-sizing: border-box !important;
            overflow: visible !important;
        }

        .referral-card:hover {
            transform: translateY(-2px) !important;
            border-color: #CBD5E1 !important;
            box-shadow: 0 8px 24px rgba(15, 59, 104, 0.08) !important;
            background: #FAFBFD !important;
        }

        /* Col 1: From Member */
        .ref-col.ref-from {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            width: 220px !important;
            min-width: 200px !important;
            max-width: 230px !important;
            flex-shrink: 0 !important;
        }

        /* Col 2: Directional Flow Arrow */
        .ref-col.ref-arrow {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 36px !important;
            min-width: 36px !important;
            flex-shrink: 0 !important;
        }

        .arrow-badge {
            width: 28px !important;
            height: 28px !important;
            background: rgba(15, 59, 104, 0.06) !important;
            color: #0F3B68 !important;
            border-radius: 50% !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 12px !important;
            transition: all 0.2s !important;
        }

        .referral-card:hover .arrow-badge {
            background: #0F3B68 !important;
            color: #FDE68A !important;
            transform: scale(1.08) !important;
        }

        /* Col 3: To Member */
        .ref-col.ref-to {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            width: 200px !important;
            min-width: 180px !important;
            max-width: 210px !important;
            flex-shrink: 0 !important;
        }

        /* Avatars */
        .ref-avatar {
            width: 42px !important;
            height: 42px !important;
            border-radius: 50% !important;
            object-fit: cover !important;
            object-position: top center !important;
            background: #F1F5F9 !important;
            flex-shrink: 0 !important;
        }

        .border-navy {
            border: 2px solid #0F3B68 !important;
        }

        .border-gold {
            border: 2px solid #D97706 !important;
        }

        .member-meta {
            overflow: hidden !important;
            line-height: 1.3 !important;
        }

        .ref-name {
            font-weight: 700 !important;
            font-size: 13.5px !important;
            color: #0F172A !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .ref-sub {
            font-size: 11.5px !important;
            color: #64748B !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        /* Col 4: Business & Contact */
        .ref-col.ref-business {
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            width: 220px !important;
            min-width: 190px !important;
            max-width: 240px !important;
            flex-shrink: 0 !important;
            line-height: 1.35 !important;
            overflow: hidden !important;
        }

        .biz-label {
            font-size: 10.5px !important;
            font-weight: 600 !important;
            color: #94A3B8 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.4px !important;
            margin-right: 4px !important;
        }

        .biz-title {
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .biz-title strong {
            font-size: 13px !important;
            color: #0F3B68 !important;
            font-weight: 700 !important;
        }

        .biz-contact {
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .contact-link {
            color: #334155 !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            transition: color 0.2s !important;
        }

        .contact-link:hover {
            color: #0F3B68 !important;
            text-decoration: underline !important;
        }

        /* Col 5: Status Badge */
        .ref-col.ref-status {
            width: 130px !important;
            min-width: 120px !important;
            flex-shrink: 0 !important;
            text-align: center !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .ref-status-pill {
            display: inline-block !important;
            padding: 5px 14px !important;
            border-radius: 50px !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            letter-spacing: 0.3px !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            border: 1px solid transparent !important;
            user-select: none !important;
        }

        .ref-status-pill:hover {
            transform: translateY(-1px) !important;
            filter: brightness(0.96) !important;
        }

        /* Status Colors */
        .ref-status-cancelled {
            background: rgba(239, 68, 68, 0.12) !important;
            color: #DC2626 !important;
            border-color: rgba(239, 68, 68, 0.25) !important;
        }

        .ref-status-completed {
            background: rgba(16, 185, 129, 0.12) !important;
            color: #059669 !important;
            border-color: rgba(16, 185, 129, 0.25) !important;
        }

        .ref-status-pending {
            background: rgba(245, 158, 11, 0.12) !important;
            color: #D97706 !important;
            border-color: rgba(245, 158, 11, 0.25) !important;
        }

        .ref-status-contacted {
            background: rgba(14, 165, 233, 0.12) !important;
            color: #0284C7 !important;
            border-color: rgba(14, 165, 233, 0.25) !important;
        }

        .ref-status-new {
            background: rgba(15, 59, 104, 0.1) !important;
            color: #0F3B68 !important;
            border-color: rgba(15, 59, 104, 0.2) !important;
        }

        .ref-status-notconverted {
            background: rgba(100, 116, 139, 0.12) !important;
            color: #475569 !important;
            border-color: rgba(100, 116, 139, 0.25) !important;
        }

        .ref-status-default {
            background: #F1F5F9 !important;
            color: #64748B !important;
            border-color: #E2E8F0 !important;
        }

        /* Col 6: Date & Time */
        .ref-col.ref-date {
            width: 160px !important;
            min-width: 150px !important;
            flex-shrink: 0 !important;
            text-align: center !important;
            font-size: 11.5px !important;
            color: #64748B !important;
            font-weight: 500 !important;
        }

        /* Col 7: Actions */
        .ref-col.ref-actions {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-end !important;
            gap: 6px !important;
            min-width: 110px !important;
            flex-shrink: 0 !important;
            margin-left: auto !important;
        }

        .ref-btn {
            width: 32px !important;
            height: 32px !important;
            border-radius: 8px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 13px !important;
            transition: all 0.2s ease !important;
            text-decoration: none !important;
            border: none !important;
        }

        .ref-btn-view {
            background: #0F3B68 !important;
            color: #FFFFFF !important;
            box-shadow: 0 2px 6px rgba(15, 59, 104, 0.2) !important;
        }

        .ref-btn-view:hover {
            background: #D97706 !important;
            color: #FFFFFF !important;
            transform: translateY(-2px) !important;
        }

        .ref-btn-call {
            background: rgba(15, 59, 104, 0.08) !important;
            color: #0F3B68 !important;
            border: 1px solid rgba(15, 59, 104, 0.15) !important;
        }

        .ref-btn-call:hover {
            background: #0F3B68 !important;
            color: #FFFFFF !important;
            transform: translateY(-2px) !important;
        }

        .ref-btn-wa {
            background: #25D366 !important;
            color: #FFFFFF !important;
            box-shadow: 0 2px 6px rgba(37, 211, 102, 0.25) !important;
        }

        .ref-btn-wa:hover {
            background: #1EBE5D !important;
            color: #FFFFFF !important;
            transform: translateY(-2px) !important;
        }

        /* Desktop & Tablet standard grid */
        .ref-card-row,
        .ref-grid-row {
            display: grid !important;
            grid-template-columns: 220px 32px 200px 230px 120px 160px 110px !important;
            align-items: center !important;
            gap: 12px !important;
            background: #FFFFFF !important;
            border: 1px solid #E2E8F0 !important;
            border-radius: 12px !important;
            padding: 0 16px !important;
            height: 64px !important;
            min-height: 64px !important;
            max-height: 64px !important;
            white-space: nowrap !important;
            box-shadow: 0 2px 8px rgba(15, 59, 104, 0.04) !important;
            box-sizing: border-box !important;
        }

        @media (min-width: 992px) {
            .referral-wrapper {
                overflow-x: auto !important;
                width: 100% !important;
                padding-bottom: 12px !important;
                -webkit-overflow-scrolling: touch !important;
            }
            .referral-card,
            .ref-header-row,
            .referral-item,
            .ref-card-col {
                min-width: 1200px;
                width: 1200px;
            }
        }

        /* Mobile Responsive Card UI (< 992px) - Zero Side Scrolling & Instant Action Buttons */
        @media (max-width: 991px) {
            .referral-wrapper {
                overflow-x: visible !important;
                width: 100% !important;
                padding-bottom: 0 !important;
            }
            .ref-header-row,
            .ref-grid-row,
            .referral-card.d-none {
                display: none !important;
            }
            .referral-item,
            .ref-card-col {
                min-width: 100% !important;
                width: 100% !important;
                display: block !important;
                margin-bottom: 0 !important;
            }
            .ref-mobile-card {
                display: flex !important;
                flex-direction: column !important;
                width: 100% !important;
                background: #FFFFFF !important;
                border: 1px solid #E2E8F0 !important;
                border-radius: 16px !important;
                padding: 14px 14px !important;
                margin-bottom: 14px !important;
                box-shadow: 0 3px 12px rgba(15, 23, 42, 0.05) !important;
                box-sizing: border-box !important;
                gap: 12px !important;
            }
            .ref-mob-header {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                gap: 8px !important;
                padding-bottom: 10px !important;
                border-bottom: 1px solid #F1F5F9 !important;
            }
            .ref-mob-status-pill {
                display: inline-flex !important;
                align-items: center !important;
                padding: 5px 12px !important;
                border-radius: 50px !important;
                font-size: 11px !important;
                font-weight: 700 !important;
                cursor: pointer !important;
                user-select: none !important;
            }
            .ref-mob-date {
                font-size: 11px !important;
                color: #64748B !important;
                font-weight: 500 !important;
                display: inline-flex !important;
                align-items: center !important;
                white-space: nowrap !important;
            }
            .ref-mob-flow-card {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                background: #F8FAFC !important;
                border: 1px solid #E2E8F0 !important;
                border-radius: 12px !important;
                padding: 10px 12px !important;
                gap: 8px !important;
            }
            .ref-mob-member-item {
                display: flex !important;
                align-items: center !important;
                gap: 8px !important;
                flex: 1 !important;
                min-width: 0 !important;
            }
            .ref-mob-avatar {
                width: 36px !important;
                height: 36px !important;
                min-width: 36px !important;
                border-radius: 50% !important;
                object-fit: cover !important;
                object-position: top center !important;
                flex-shrink: 0 !important;
            }
            .from-avatar {
                border: 2px solid #0F3B68 !important;
            }
            .to-avatar {
                border: 2px solid #D97706 !important;
            }
            .ref-mob-member-text {
                display: flex !important;
                flex-direction: column !important;
                min-width: 0 !important;
                line-height: 1.2 !important;
            }
            .ref-mob-tag {
                font-size: 8.5px !important;
                font-weight: 800 !important;
                letter-spacing: 0.5px !important;
                text-transform: uppercase !important;
            }
            .tag-from {
                color: #0F3B68 !important;
            }
            .tag-to {
                color: #D97706 !important;
            }
            .ref-mob-member-name {
                font-size: 12.5px !important;
                font-weight: 700 !important;
                color: #0F172A !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }
            .ref-mob-member-sub {
                font-size: 10.5px !important;
                color: #64748B !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }
            .ref-mob-flow-divider {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                width: 24px !important;
                height: 24px !important;
                min-width: 24px !important;
                border-radius: 50% !important;
                background: #FFFFFF !important;
                border: 1px solid #CBD5E1 !important;
                color: #0F3B68 !important;
                font-size: 12px !important;
                flex-shrink: 0 !important;
            }
            .ref-mob-business-box {
                background: #F8FAFC !important;
                border-radius: 10px !important;
                padding: 9px 12px !important;
                border: 1px solid #E2E8F0 !important;
                border-left: 3px solid #0F3B68 !important;
            }
            .ref-mob-business-label {
                font-size: 9px !important;
                font-weight: 700 !important;
                color: #64748B !important;
                text-transform: uppercase !important;
                letter-spacing: 0.5px !important;
                margin-bottom: 2px !important;
            }
            .ref-mob-business-desc {
                font-size: 12.5px !important;
                font-weight: 600 !important;
                color: #0F172A !important;
                line-height: 1.35 !important;
                word-break: break-word !important;
            }
            .ref-mob-action-footer {
                display: flex !important;
                align-items: center !important;
                gap: 8px !important;
                padding-top: 8px !important;
                border-top: 1px dashed #E2E8F0 !important;
            }
            .ref-mob-btn {
                flex: 1 !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 3px !important;
                min-height: 48px !important;
                border-radius: 10px !important;
                text-decoration: none !important;
                font-size: 11px !important;
                font-weight: 600 !important;
                border: none !important;
                cursor: pointer !important;
                transition: all 0.2s ease !important;
                padding: 6px 4px !important;
            }
            .ref-mob-btn i {
                font-size: 16px !important;
            }
            .ref-mob-btn.btn-call {
                background: rgba(2, 132, 199, 0.1) !important;
                color: #0284C7 !important;
                border: 1px solid rgba(2, 132, 199, 0.25) !important;
            }
            .ref-mob-btn.btn-call:hover,
            .ref-mob-btn.btn-call:active {
                background: #0284C7 !important;
                color: #FFFFFF !important;
            }
            .ref-mob-btn.btn-wa {
                background: rgba(37, 211, 102, 0.12) !important;
                color: #16A34A !important;
                border: 1px solid rgba(37, 211, 102, 0.3) !important;
            }
            .ref-mob-btn.btn-wa:hover,
            .ref-mob-btn.btn-wa:active {
                background: #25D366 !important;
                color: #FFFFFF !important;
            }
            .ref-mob-btn.btn-details {
                background: rgba(15, 59, 104, 0.1) !important;
                color: #0F3B68 !important;
                border: 1px solid rgba(15, 59, 104, 0.25) !important;
            }
            .ref-mob-btn.btn-details:hover,
            .ref-mob-btn.btn-details:active {
                background: #0F3B68 !important;
                color: #FFFFFF !important;
            }
            .ref-mob-btn.btn-status {
                background: rgba(217, 119, 6, 0.1) !important;
                color: #D97706 !important;
                border: 1px solid rgba(217, 119, 6, 0.25) !important;
            }
            .ref-mob-btn.btn-status:hover,
            .ref-mob-btn.btn-status:active {
                background: #D97706 !important;
                color: #FFFFFF !important;
            }
        }
            .connect-tabs {
                display: flex !important;
                gap: 8px !important;
                margin-bottom: 12px !important;
            }
            .tab-btn {
                flex: 1 !important;
                text-align: center !important;
                padding: 10px 14px !important;
                font-size: 13.5px !important;
            }
            .filter-tabs {
                display: flex !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
                white-space: nowrap !important;
                gap: 6px !important;
                flex-wrap: nowrap !important;
                padding-bottom: 8px !important;
            }
            .filter-btn {
                flex-shrink: 0 !important;
                font-size: 12px !important;
                padding: 6px 12px !important;
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
                <?php foreach ($user as $row) { 
                    $memberName = !empty($row->name) ? $row->name : (session()->get('name') ?: 'Member');
                    $memberChapter = !empty($row->chapter_name) ? $row->chapter_name : session()->get('chapter_name');
                    $memberRole = !empty($row->member_role) ? $row->member_role : session()->get('member_role');
                    $bAvatar = get_member_avatar($row->profile_photo ?? '', $row->gender ?? '', $row->user_code ?? '', true);
                    
                    $bizTitle = !empty($row->designation) ? $row->designation : '';
                    $bizCompany = !empty($row->business_name) ? $row->business_name : (!empty($row->company_name) ? $row->company_name : '');
                    $bizParts = array_filter([$bizTitle, $bizCompany]);
                    $bizLine = implode(' • ', $bizParts);

                    $cleanBio = trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $row->bio ?? ''));
                    if (empty($cleanBio)) {
                        $cleanBio = 'Active member contributing to strategic business networking and qualified referral exchanges across chapters.';
                    }
                ?>
                    <div class="eaf-member-hero-card">
                        <div class="eaf-hero-inner">
                            <!-- AVATAR & AI BADGE -->
                            <div class="eaf-hero-avatar-wrap">
                                <div class="eaf-hero-avatar-frame">
                                    <img src="<?= esc($bAvatar['url']) ?>"
                                         class="eaf-hero-avatar-img"
                                         alt="<?= esc($memberName) ?>"
                                         onerror="this.onerror=null; this.src='<?= base_url('images/empty.png'); ?>';">
                                    <?php if (!empty($bAvatar['is_ai'])): ?>
                                        <span class="eaf-avatar-ai-badge" title="AI Generated Avatar">
                                            <i class="ri-sparkling-fill"></i> AI
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- CORE MEMBER INFO -->
                            <div class="eaf-hero-content">
                                <!-- ROW 1: NAME + CHAPTER + ROLE BADGES -->
                                <div class="eaf-hero-title-row">
                                    <h2 class="eaf-hero-name"><?= esc($memberName) ?></h2>
                                    
                                    <?php if (!empty($memberChapter)): ?>
                                        <span class="eaf-hero-chip chip-chapter">
                                            <i class="ri-building-line"></i>
                                            <span><?= esc($memberChapter) ?></span>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($memberRole)): ?>
                                        <span class="eaf-hero-chip chip-role">
                                            <i class="ri-vip-crown-fill"></i>
                                            <span><?= esc($memberRole) ?></span>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- ROW 2: BUSINESS / COMPANY STRIP -->
                                <?php if (!empty($bizLine)): ?>
                                    <div class="eaf-hero-biz-strip">
                                        <i class="ri-briefcase-3-line"></i>
                                        <span><?= esc($bizLine) ?></span>
                                    </div>
                                <?php endif; ?>

                                <!-- ROW 3: BIO DESCRIPTION WITH PROPER TYPOGRAPHY & WIDTH -->
                                <div class="eaf-hero-bio">
                                    <p><?= esc($cleanBio) ?></p>
                                </div>

                                <!-- ROW 4: OFFERINGS / SERVICES PILLS -->
                                <?php if (!empty($row->services)): ?>
                                    <div class="eaf-hero-services">
                                        <span class="services-label"><i class="ri-price-tag-3-line"></i> Key Focus:</span>
                                        <div class="services-tags-list">
                                            <?php 
                                            $services = array_filter(array_map('trim', preg_split('/[,;\n]+/', $row->services)));
                                            foreach ($services as $srv): 
                                            ?>
                                                <span class="service-pill"><?= esc($srv) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- ACTION: CREATE NEW CONNECT -->
                            <div class="eaf-hero-action">
                                <a href="#" onclick="add_new_connects(); return false;" class="eaf-hero-connect-btn">
                                    <span class="btn-icon"><i class="ri-add-line"></i></span>
                                    <span class="btn-text">Create New Connect</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <style>
                    /* =========================================================
                       EAF CONNECT - ENTERPRISE MEMBER HERO CARD
                       Deep Midnight Navy (#101B35) + Champagne Gold (#C6A667)
                    ========================================================= */
                    .eaf-member-hero-card {
                        position: relative;
                        background: linear-gradient(135deg, #101B35 0%, #172B50 55%, #0B1325 100%);
                        border: 1px solid rgba(198, 166, 103, 0.32);
                        border-radius: 16px;
                        padding: 24px 28px;
                        box-shadow: 0 10px 30px rgba(16, 27, 53, 0.18), 0 1px 3px rgba(0, 0, 0, 0.08);
                        overflow: hidden;
                        margin-bottom: 24px;
                    }

                    /* Subtle Luxury Radial Accents */
                    .eaf-member-hero-card::before {
                        content: "";
                        position: absolute;
                        top: -90px;
                        right: -60px;
                        width: 260px;
                        height: 260px;
                        border-radius: 50%;
                        background: radial-gradient(circle, rgba(198, 166, 103, 0.09) 0%, transparent 70%);
                        pointer-events: none;
                    }

                    .eaf-member-hero-card::after {
                        content: "";
                        position: absolute;
                        bottom: -80px;
                        left: 25%;
                        width: 240px;
                        height: 240px;
                        border-radius: 50%;
                        background: radial-gradient(circle, rgba(23, 43, 80, 0.25) 0%, transparent 70%);
                        pointer-events: none;
                    }

                    .eaf-hero-inner {
                        position: relative;
                        z-index: 2;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        gap: 24px;
                    }

                    /* AVATAR FRAME */
                    .eaf-hero-avatar-wrap {
                        flex-shrink: 0;
                    }

                    .eaf-hero-avatar-frame {
                        position: relative;
                        width: 90px;
                        height: 90px;
                        border-radius: 18px;
                        padding: 3px;
                        background: linear-gradient(135deg, #C6A667 0%, rgba(198, 166, 103, 0.4) 100%);
                        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
                    }

                    .eaf-hero-avatar-img {
                        display: block;
                        width: 100%;
                        height: 100%;
                        border-radius: 15px;
                        object-fit: cover;
                        object-position: top center;
                        background: #101B35;
                    }

                    .eaf-avatar-ai-badge {
                        position: absolute;
                        bottom: -8px;
                        left: 50%;
                        transform: translateX(-50%);
                        background: linear-gradient(135deg, #C6A667 0%, #A88849 100%);
                        color: #101B35;
                        font-size: 9.5px;
                        font-weight: 800;
                        padding: 2px 7px;
                        border-radius: 50px;
                        white-space: nowrap;
                        display: inline-flex;
                        align-items: center;
                        gap: 3px;
                        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
                        border: 1px solid #FAF9F6;
                    }

                    /* CONTENT ZONE */
                    .eaf-hero-content {
                        flex: 1;
                        min-width: 0;
                    }

                    .eaf-hero-title-row {
                        display: flex;
                        align-items: center;
                        flex-wrap: wrap;
                        gap: 10px;
                        margin-bottom: 6px;
                    }

                    .eaf-hero-name {
                        font-family: 'Outfit', sans-serif;
                        font-size: 24px;
                        font-weight: 700;
                        color: #FFFFFF;
                        margin: 0;
                        line-height: 1.2;
                        letter-spacing: -0.01em;
                    }

                    /* CHIPS & BADGES */
                    .eaf-hero-chip {
                        display: inline-flex;
                        align-items: center;
                        gap: 5px;
                        padding: 4px 11px;
                        border-radius: 50px;
                        font-size: 11.5px;
                        font-weight: 600;
                        line-height: 1.2;
                    }

                    .eaf-hero-chip.chip-chapter {
                        background: rgba(198, 166, 103, 0.15);
                        color: #E4D4AE;
                        border: 1px solid rgba(198, 166, 103, 0.4);
                    }

                    .eaf-hero-chip.chip-role {
                        background: rgba(255, 255, 255, 0.1);
                        color: #FFFFFF;
                        border: 1px solid rgba(255, 255, 255, 0.22);
                    }

                    .eaf-hero-chip.chip-role i {
                        color: #C6A667;
                    }

                    /* BUSINESS STRIP */
                    .eaf-hero-biz-strip {
                        display: flex;
                        align-items: center;
                        gap: 7px;
                        font-size: 13.5px;
                        font-weight: 600;
                        color: #C6A667;
                        margin-bottom: 8px;
                    }

                    .eaf-hero-biz-strip i {
                        font-size: 14px;
                        color: #C6A667;
                    }

                    /* BIO PARAGRAPH */
                    .eaf-hero-bio {
                        max-width: 780px;
                        margin-bottom: 10px;
                    }

                    .eaf-hero-bio p {
                        font-family: 'Plus Jakarta Sans', sans-serif;
                        font-size: 13px;
                        line-height: 1.6;
                        color: #CBD5E1;
                        margin: 0;
                        display: -webkit-box;
                        -webkit-line-clamp: 3;
                        -webkit-box-orient: vertical;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    }

                    /* SERVICES & OFFERINGS */
                    .eaf-hero-services {
                        display: flex;
                        align-items: center;
                        flex-wrap: wrap;
                        gap: 8px;
                    }

                    .services-label {
                        font-size: 11px;
                        font-weight: 700;
                        color: #94A3B8;
                        text-transform: uppercase;
                        letter-spacing: 0.4px;
                        display: inline-flex;
                        align-items: center;
                        gap: 4px;
                    }

                    .services-tags-list {
                        display: flex;
                        align-items: center;
                        flex-wrap: wrap;
                        gap: 6px;
                    }

                    .service-pill {
                        background: rgba(255, 255, 255, 0.07);
                        color: #E2E8F0;
                        font-size: 11px;
                        font-weight: 500;
                        padding: 3px 9px;
                        border-radius: 6px;
                        border: 1px solid rgba(255, 255, 255, 0.12);
                    }

                    /* ACTION: CREATE NEW CONNECT BUTTON */
                    .eaf-hero-action {
                        flex-shrink: 0;
                    }

                    .eaf-hero-connect-btn {
                        display: inline-flex;
                        align-items: center;
                        gap: 10px;
                        padding: 11px 22px;
                        background: linear-gradient(135deg, #C6A667 0%, #D4B679 100%);
                        color: #101B35 !important;
                        font-family: 'Plus Jakarta Sans', sans-serif;
                        font-size: 13.5px;
                        font-weight: 700;
                        border-radius: 10px;
                        border: 1px solid #E4D4AE;
                        box-shadow: 0 6px 20px rgba(198, 166, 103, 0.28);
                        text-decoration: none !important;
                        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
                        white-space: nowrap;
                    }

                    .eaf-hero-connect-btn:hover {
                        transform: translateY(-2px);
                        background: linear-gradient(135deg, #D4B679 0%, #C6A667 100%);
                        box-shadow: 0 10px 26px rgba(198, 166, 103, 0.4);
                        color: #0B1325 !important;
                    }

                    .eaf-hero-connect-btn .btn-icon {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        width: 22px;
                        height: 22px;
                        border-radius: 6px;
                        background: rgba(16, 27, 53, 0.15);
                        font-size: 14px;
                    }

                    /* =========================================================
                       RESPONSIVE DESIGN (All Viewports)
                    ========================================================= */
                    @media (max-width: 1024px) {
                        .eaf-hero-inner {
                            flex-wrap: wrap;
                            gap: 18px;
                        }

                        .eaf-hero-action {
                            width: 100%;
                            display: flex;
                            justify-content: flex-start;
                        }
                    }

                    @media (max-width: 768px) {
                        .eaf-member-hero-card {
                            padding: 18px;
                            border-radius: 14px;
                        }

                        .eaf-hero-inner {
                            flex-direction: column;
                            align-items: flex-start;
                            gap: 16px;
                        }

                        .eaf-hero-avatar-frame {
                            width: 72px;
                            height: 72px;
                            border-radius: 14px;
                        }

                        .eaf-hero-name {
                            font-size: 20px;
                        }

                        .eaf-hero-action {
                            width: 100%;
                        }

                        .eaf-hero-connect-btn {
                            width: 100%;
                            justify-content: center;
                        }
                    }

                    @media (max-width: 480px) {
                        .eaf-hero-title-row {
                            flex-direction: column;
                            align-items: flex-start;
                            gap: 6px;
                        }

                        .eaf-hero-bio p {
                            -webkit-line-clamp: 4;
                            font-size: 12.5px;
                        }
                    }
                </style>
                <div class="container-fluid connect-wrapper p-0 mt-2">
                    <div class="connect-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h4 class="title m-0">
                            Total Connects
                            <span class="count"><?= $totalCount ?></span>
                        </h4>
                        <button type="button" class="btn btn-warning fw-bold rounded-pill px-3 shadow-sm" onclick="add_new_connects();" style="font-size:13px;">
                            <i class="ri-add-line me-1"></i> Give New Referral
                        </button>
                    </div>
                    <div class="connect-tabs">
                        <button class="tab-btn active" data-tab="received">
                            Received <span class="badge"><?= $receivedCount ?></span>
                        </button>

                        <button class="tab-btn" data-tab="given">
                            Given <span class="badge"><?= $givenCount ?></span>
                        </button>
                    </div>
                    <!-- CONTENT AREA -->
                    <div class="connect-content">

                        <!-- RECEIVED -->
                        <div class="tab-content-box active" id="received">
                            <div class="content-card">

                                <?php
                                $statusList = [
                                    'open for discussion' => 'Open For Discussion',
                                    'contacted'           => 'Contacted',
                                    'pending'             => 'Pending',
                                    'need sometime'       => 'Need Sometime',
                                    'mitb'                => 'MITB',
                                    'completed'           => 'Completed',
                                    'cancelled'           => 'Cancelled',
                                ];
                                ?>
                                <div class="filter-tabs">
                                    <button type="button" class="filter-btn active" data-status="all">
                                        All (<?= $receivedCount ?>)
                                    </button>
                                    <?php foreach ($statusList as $key => $label):
                                        $cnt = $statusCountsReceived[$key] ?? 0;
                                    ?>
                                        <button type="button" class="filter-btn" data-status="<?= $key ?>">
                                            <?= $label ?> (<?= $cnt ?>)
                                        </button>
                                    <?php endforeach; ?>
                                </div>

                                <h5 class="fw-bold text-navy mb-3"><i class="ri-arrow-down-circle-line me-1 text-primary"></i> Received Connects</h5>

                                <div class="referral-wrapper">

                                    <!-- TABLE HEADER ROW (Desktop Only) -->
                                    <div class="ref-header-row d-none d-lg-grid">
                                        <div>From Member</div>
                                        <div style="text-align: center;">Flow</div>
                                        <div>To Member</div>
                                        <div>Business Opportunity</div>
                                        <div style="text-align: center;">Status</div>
                                        <div style="text-align: center;">Date & Time</div>
                                        <div style="text-align: right; padding-right: 4px;">Action</div>
                                    </div>

                                    <?php if (!empty($received)): ?>
                                        <?php foreach ($received as $row): ?>
                                            <div class="referral-item"
                                                data-status="<?= esc($row['status_slug'] ?? strtolower(trim($row['status']))) ?>">
                                                <?= view('include/member/card/recived_card.php', ['row' => $row]) ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="col-12 text-center p-4 text-muted">
                                            No Received Connects
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                        <!-- GIVEN -->
                        <div class="tab-content-box" id="given">
                            <div class="content-card">

                                <div class="filter-tabs">
                                    <button type="button" class="filter-btn active" data-status="all">
                                        All (<?= $givenCount ?>)
                                    </button>
                                    <?php foreach ($statusList as $key => $label):
                                        $cnt = $statusCountsGiven[$key] ?? 0;
                                    ?>
                                        <button type="button" class="filter-btn" data-status="<?= $key ?>">
                                            <?= $label ?> (<?= $cnt ?>)
                                        </button>
                                    <?php endforeach; ?>
                                </div>

                                <h5 class="fw-bold text-navy mb-3"><i class="ri-arrow-up-circle-line me-1 text-warning"></i> Given Connects</h5>

                                <div class="referral-wrapper">

                                    <!-- TABLE HEADER ROW (Desktop Only) -->
                                    <div class="ref-header-row d-none d-lg-grid">
                                        <div>From Member</div>
                                        <div style="text-align: center;">Flow</div>
                                        <div>To Member</div>
                                        <div>Business Opportunity</div>
                                        <div style="text-align: center;">Status</div>
                                        <div style="text-align: center;">Date & Time</div>
                                        <div style="text-align: right; padding-right: 4px;">Action</div>
                                    </div>

                                    <?php if (!empty($given)): ?>
                                        <?php foreach ($given as $row): ?>
                                            <div class="referral-item"
                                                data-status="<?= esc($row['status_slug'] ?? strtolower(trim($row['status']))) ?>">
                                                <?= view('include/member/card/given_card.php', ['row' => $row]) ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="col-12 text-center p-4 text-muted">
                                            No Given Connects
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
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
    <script>
        function applyConnectFilter(containerId) {
            var $container = $('#' + containerId);
            if (!$container.length) return;

            var $activeBtn = $container.find('.filter-btn.active');
            if (!$activeBtn.length) {
                $activeBtn = $container.find('.filter-btn').first().addClass('active');
            }

            var selectedStatus = $.trim(String($activeBtn.attr('data-status') || '')).toLowerCase();
            var $items = $container.find('.referral-item');
            var visibleCount = 0;

            if (selectedStatus === 'all' || selectedStatus === '') {
                $items.show();
                visibleCount = $items.length;
            } else {
                $items.each(function() {
                    var itemStatus = $.trim(String($(this).attr('data-status') || '')).toLowerCase();
                    if (itemStatus === selectedStatus) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });
            }

            var $emptyMsg = $container.find('.empty-filter-placeholder');
            if (visibleCount === 0) {
                if (!$emptyMsg.length) {
                    $container.find('.referral-wrapper').append(
                        '<div class="empty-filter-placeholder col-12 text-center p-4 text-muted" style="font-size:13.5px; font-weight:500;">No connects found in this status.</div>'
                    );
                } else {
                    $emptyMsg.show();
                }
            } else {
                $emptyMsg.hide();
            }
        }

        $(document).ready(function() {
            // Restore active tab if stored in sessionStorage
            var savedTab = sessionStorage.getItem('activeConnectTab');
            if (savedTab && $('#' + savedTab).length) {
                $('.tab-btn').removeClass('active');
                $('.tab-btn[data-tab="' + savedTab + '"]').addClass('active');
                $('.tab-content-box').removeClass('active');
                $('#' + savedTab).addClass('active');
            }

            // Apply filter initially on both containers
            applyConnectFilter('received');
            applyConnectFilter('given');

            // Filter button click
            $(document).on('click', '.tab-content-box .filter-btn', function() {
                var $box = $(this).closest('.tab-content-box');
                $box.find('.filter-btn').removeClass('active');
                $(this).addClass('active');
                applyConnectFilter($box.attr('id'));
            });

            // Main tab switch (Received / Given)
            document.querySelectorAll(".tab-btn").forEach(function(button) {
                button.addEventListener("click", function() {
                    document.querySelectorAll(".tab-btn").forEach(function(btn) {
                        btn.classList.remove("active");
                    });
                    this.classList.add("active");

                    document.querySelectorAll(".tab-content-box").forEach(function(tab) {
                        tab.classList.remove("active");
                    });

                    var tabId = this.getAttribute("data-tab");
                    sessionStorage.setItem('activeConnectTab', tabId);
                    var targetBox = document.getElementById(tabId);
                    if (targetBox) {
                        targetBox.classList.add("active");
                    }
                    setTimeout(function() {
                        applyConnectFilter(tabId);
                    }, 30);
                });
            });
        });
    </script>
</body>

</html>