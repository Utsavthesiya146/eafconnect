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
    <style>
        .profile-banner {
            background: linear-gradient(135deg, #d81f26, #b5161c);
            padding: 20px 25px;
            /* ↓ reduced from 40px */
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        }

        /* PROFILE IMAGE */
        .profile-img-wrapper {
            position: relative;
        }

        .profile-img {
            width: 95px;
            /* ↓ reduced */
            height: 95px;
            border-radius: 12px;
            object-fit: cover;
            border: 3px solid #fff;
        }

        /* CAMERA ICON */
        .camera-icon {
            position: absolute;
            bottom: 3px;
            right: 3px;
            background: #fff;
            width: 28px;
            height: 28px;
            font-size: 12px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d81f26;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
        }

        /* USER TEXT AREA */
        .profile-banner h2 {
            font-size: 20px;
            margin-bottom: 4px !important;
        }

        .profile-banner .mb-2 {
            margin-bottom: 6px !important;
            font-size: 14px;
        }

        /* BADGES */
        .badge-pill,
        .badge-pill-dark {
            padding: 4px 12px;
            font-size: 12px;
            border-radius: 20px;
        }

        /* SAVE BUTTON */
        .save-btn {
            background: #fff;
            color: #d81f26;
            padding: 8px 18px;
            /* ↓ reduced */
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
        }

        /* STATS BOX */
        .stats-box {
            background: #fff;
            border-radius: 0 0 16px 16px;
            padding: 15px;
            /* ↓ reduced */
            box-shadow: 0 4px 10px rgba(0, 0, 0, .05);
            margin-top: -6px;
        }

        .stats-box h6 {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .stats-box h2 {
            font-size: 20px;
            margin: 0;
        }



        /* ===== Cards ===== */
        .profile-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .05);
        }

        /* ===== Card Header ===== */
        .profile-header {
            background: #fff;
            border-bottom: 1px solid #f1f1f1;
            padding: 14px 20px;
            font-weight: 600;
            color: #e11d48;
        }

        /* ===== Inputs ===== */
        .form-control,
        .form-select {
            border-radius: 10px;
            height: 44px;
            font-size: 14px;
            border: 1px solid #e5e7eb;
        }

        textarea.form-control {
            height: auto;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #e11d48;
            box-shadow: 0 0 0 .15rem rgba(225, 29, 72, .15);
        }

        /* ===== Labels ===== */
        .form-label {
            font-size: 13px;
            margin-bottom: 6px;
        }

        /* ===== Sidebar ===== */
        .profile-sidebar {
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .05);
        }

        /* ===== Sidebar Tabs ===== */
        .profile-sidebar .nav-link {
            border-radius: 10px;
            color: #444;
            font-size: 14px;
            padding: 12px 14px;
            transition: .2s;
        }

        .profile-sidebar .nav-link:hover {
            background: #fff1f2;
            color: #e11d48;
        }

        .profile-sidebar .nav-link.active {
            background: #e11d48;
            color: #fff;
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
    </style>
</head>

<body data-sidebar="colored">

    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php echo view('include/admin/header.php') ?>
        <?php echo view('include/admin/sidebar.php') ?>
        <div class="main-content">
            <div class="page-content">
                <?php foreach ($member as $row) { ?>
                    <div class="profile-banner">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <!-- LEFT SIDE -->
                            <div class="d-flex align-items-center">
                                <!-- PROFILE IMAGE -->
                                <div class="profile-img-wrapper">
                                    <img src="<?= base_url('uploads/profile/' . $row->profile_photo) ?>"
                                        class="profile-img"
                                        style="object-fit: cover; object-position: top center;">

                                    <span class="camera-icon">
                                        <i class="fas fa-camera"></i>
                                    </span>
                                </div>
                                <!-- USER INFO -->
                                <div class="ms-4 text-white">
                                    <h2 class="fw-bold text-warning mb-1">
                                        <?= esc($row->name) ?>
                                    </h2>

                                    <div class="mb-2">
                                        <i class="fas fa-briefcase"></i>
                                        <?= esc($row->bio ?: 'Member') ?>
                                    </div>
                                    <!-- BADGES -->
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge-pill">
                                            <?= esc($row->member_role) ?>
                                        </span>
                                        <span class="badge-pill">
                                            <?= esc($row->member_type) ?>
                                        </span>
                                        <span class="badge-pill-dark">
                                            Since <?= date('M Y', strtotime($row->join_date ?? $row->created_at)) ?>
                                        </span>

                                    </div>
                                </div>
                            </div>
                            <!-- SAVE BUTTON -->
                            <a href="#" class="btn save-btn">
                                <i class="fas fa-save me-2"></i>Profile
                            </a>
                        </div>
                    </div>
                    <div class="row text-center stats-box m-1">
                        <div class="col-md-4 border-end">
                            <h6>CONNECTS GIVEN</h6>
                            <h2 class="text-danger">4</h2>
                        </div>
                        <div class="col-md-4 border-end">
                            <h6>CONNECTS RECEIVED</h6>
                            <h2 class="text-success">0</h2>
                        </div>
                        <div class="col-md-4">
                            <h6>EVENTS ATTENDED</h6>
                            <h2 class="text-primary">0</h2>
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
                                                    <label class="fw-bold">Profile Photo</label>

                                                    <input type="file" class="form-control mb-2"
                                                        name="profile_photo"
                                                        accept=".jpg,.jpeg,.png">

                                                    <?php if (!empty($row->profile_photo)): ?>
                                                        <img src="<?= base_url('uploads/profile/' . $row->profile_photo) ?>"
                                                            class="img-thumbnail"
                                                            style="height:90px">
                                                    <?php endif; ?>
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
                                              
                                                <!-- NEW PASSWORD -->
                                                <div class="col-md-6 mb-3">
                                                    <label class="fw-bold">New Password</label>
                                                    <div class="input-group">
                                                        <input type="password" name="new_password"
                                                            id="new_password"
                                                            class="form-control"
                                                            minlength="8"
                                                            required>
                                                        <button class="btn btn-outline-secondary"
                                                            type="button"
                                                            onclick="togglePass('new_password',this)">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                    <small class="text-muted">Minimum 8 characters</small>
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
        <?php echo view('include/admin/footer.php') ?>
    </div>
    <!-- end main content-->
    </div>
    <div class="rightbar-overlay"></div>
    <!-- JAVASCRIPT -->
    <?php echo view('include/admin/popup/profile.php') ?>
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