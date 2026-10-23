<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Chapters</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts & Master KVV/BNI Revamp Theme -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets2/css/eaf-revamp.css'); ?>?v=3.5">
    <style>
        .filter-bar {
            border-radius: 8px;
        }

        .filter-bar .form-control,
        .filter-bar .form-select {
            height: 34px;
            font-size: 13px;
        }

        .filter-bar .input-group-text {
            padding: 4px 8px;
            font-size: 13px;
        }

        .filter-bar .btn {
            height: 34px;
            font-size: 13px;
            padding: 4px 10px;
        }

        .chapter-card-pro {
            background: #0a3d61;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            transition: all .3s ease;
            position: relative;
            overflow: hidden;
        }

        /* TOP STRIP */
        .chapter-card-pro::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 4px;
            width: 100%;
            background: linear-gradient(90deg, #fac532, #fbc531);
        }

        .chapter-card-pro:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
        }

        /* HEADER */
        .chapter-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chapter-top h4 {
            color: #ffffff;
            font-weight: 600;
            margin: 0;
            font-size: 16px;
        }

        .location-badge {
            background: rgba(187, 24, 30, 0.1);
            color: #ffffff;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            margin-top: 4px;
        }

        .card-menu {
            color: #999;
            cursor: pointer;
            font-size: 16px;
        }

        /* LEADER */
        .section-label {
            margin-top: 14px;
            font-size: 11px;
            font-weight: 700;
            color: #fac532;
            letter-spacing: .5px;
        }

        .leader-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 6px;
        }

        .leader-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fff3cd;
            color: #ffc107;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .leader-box strong {
            font-size: 12px;
            color: #ffffff;
        }

        .leader-box small {
            display: block;
            font-size: 12px;
            color: #fac532;
        }

        /* ===== STATS ===== */

        .chapter-stats {
            margin-top: 12px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .stat {
            background: #0a3d61;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            border: 1px solid #eee;
            transition: .25s;
        }

        .stat:hover {
            border-color: #1b4e8f33;
        }

        /* LABEL */
        .stat span {
            font-weight: 500;
            font-size: 12px;
            color: #fff;
        }

        /* ✅ REDUCED COUNT SIZE */
        .stat.large h3 {
            margin: 4px 0 0;
            font-size: 18px;
            /* reduced from big size */
            font-weight: 700;
            color: #fbc531;
        }

        .stat.small h5 {
            margin-top: 4px;
            font-size: 15px;
            /* smaller numbers */
            font-weight: 700;
            color: #fbc531;
        }

        /* SMALL STATS ROW FIX */
        .row.mt-2 {
            margin-top: 6px !important;
        }

        /* BUTTON */
        .dashboard-btn {
            display: block;
            margin-top: 20px;
            text-align: center;
            padding: 9px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            color: #fbc531;
            border: 2px solid #fbc531;
            transition: all .3s;
        }

        .dashboard-btn:hover {
            background: linear-gradient(90deg, #08426b, #fbc531);
            color: #fff;
            box-shadow: 0 5px 12px rgba(187, 24, 30, .3);
        }

        .leader-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
            background: #f2f2f2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .leader-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .leader-avatar {
            border: 2px solid #08426b;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
        }

        /* container */
        .card-menu {
            display: flex;
            gap: 3px;
        }

        /* base button */
        .action-btn {
            width: 22px;
            height: 22px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            text-decoration: none;
            color: #fff;
            transition: all .25s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .08);
        }

        /* view */
        .view-btn {
            background: #08426b;
        }

        /* edit */
        .edit-btn {
            background: #f59e0b;
        }

        /* delete */
        .delete-btn {
            background: #fbc531;
        }

        /* hover effect */
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, .15);
            opacity: .9;
        }

        /* icon alignment fix */
        .action-btn i {
            font-size: 12px;
        }

        .chapter-card-pro {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100%;
            border-radius: 14px;
        }

        /* middle content grows automatically */
        .chapter-card-pro .leader-section,
        .chapter-card-pro .chapter-stats,
        .chapter-card-pro .row {
            flex-shrink: 0;
        }

        /* push button to bottom */
        .dashboard-btn {
            margin-top: auto;
        }

        .chapter-status {
            display: inline-flex;
            align-items: center;
            gap: 0px;
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            user-select: none;
        }

        /* ACTIVE */
        .status-active {
            background: #e8f8f0;
            color: #1ca67a;
        }

        .status-active:hover {
            background: #1ca67a;
            color: #fff;
            transform: translateY(-2px);
        }

        /* PENDING */
        .status-pending {
            background: #fff6e5;
            color: #e6a100;
        }

        .status-pending:hover {
            background: #e6a100;
            color: #fff;
            transform: translateY(-2px);
        }

        /* BLOCKED */
        .status-blocked {
            background: #ffeaea;
            color: #fbc531;
        }

        .status-blocked:hover {
            background: #fbc531;
            color: #fff;
            transform: translateY(-2px);
        }

        /* ICON SIZE */
        .chapter-status i {
            font-size: 14px;
        }

        .pagination-wrapper {
            margin-top: 20px;
        }

        .pagination {
            display: flex;
            gap: 6px;
            padding: 0;
            list-style: none;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination li a,
        .pagination li span {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            color: #333;
            background: #fff;
            font-size: 14px;
            transition: all 0.25s ease;
            text-decoration: none;
        }

        /* Hover effect */
        .pagination li a:hover {
            background: #0a3d61;
            color: #fff;
            border-color: #0a3d61;
            transform: translateY(-1px);
        }

        /* Active page */
        .pagination li.active span {
            background: #fac532;
            color: #fff;
            border-color: #fac532;
            font-weight: 600;
        }

        /* Disabled buttons */
        .pagination li.disabled span {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* First/Last/Prev/Next styling */
        .pagination li:first-child a,
        .pagination li:last-child a {
            font-weight: 500;
        }

        .news-ticker {
            background: #08426b;
            color: #fff;
            padding: 7px;
            border-radius: 8px;
            font-size: 14px;
        }

        @keyframes tickerScroll {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .news-ticker {
            background: linear-gradient(90deg, #08426b, #08426b);
            color: #fff;
            font-weight: 500;
            line-height: 1em;
        }
    </style>
</head>

<body data-sidebar="colored">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php echo view('include/member/header.php') ?>
        <?php echo view('include/member/sidebar.php') ?>
        <div class="main-content">
            <div class="page-content" style="background-color:#f5f8f9 ;">

                <!-- ================= CHAPTERS HEADER ================= -->
                <div class="row align-items-center g-3 mb-3">

                    <!-- LEFT TITLE -->
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="chapter-heading">

                            <div class="chapter-icon">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>

                            <div class="chapter-title">
                                <h5 class="mb-0">All Chapters</h5>
                                <span>View and manage all chapters</span>
                            </div>

                        </div>
                    </div>

                    <!-- RIGHT SEARCH -->
                    <div class="col-lg-6 col-md-6 col-12">

                        <div class="chapter-filter">

                            <form id="search-form" method="POST">
                                <?= csrf_field() ?>

                                <div class="chapter-search">

                                    <div class="search-field">
                                        <i class="bi bi-search"></i>

                                        <input type="text"
                                            id="search"
                                            name="search"
                                            class="form-control"
                                            placeholder="Search chapters..."
                                            autocomplete="off">
                                    </div>

                                    <button type="button"
                                        id="reset-btn"
                                        class="reset-btn"
                                        title="Reset Search">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                        <span>Reset</span>
                                    </button>

                                </div>
                            </form>

                        </div>

                    </div>

                </div>

                <!-- HEADER DIVIDER -->
                <div class="chapter-divider"></div>


                <style>
                    /* =========================================================
   CHAPTER HEADER
========================================================= */

                    .chapter-heading {
                        min-height: 62px;
                        display: flex;
                        align-items: center;
                        gap: 12px;
                    }

                    /* Chapter Icon */
                    .chapter-icon {
                        width: 42px;
                        height: 42px;
                        flex: 0 0 42px;

                        display: flex;
                        align-items: center;
                        justify-content: center;

                        border-radius: 10px;

                        background: linear-gradient(135deg, #0a3d61, #176b9c);
                        color: #fff;

                        font-size: 19px;

                        box-shadow: 0 4px 12px rgba(10, 61, 97, 0.16);
                    }

                    /* Title */
                    .chapter-title h5 {
                        color: #173b56;
                        font-size: 18px;
                        font-weight: 700;
                        line-height: 1.2;
                    }

                    .chapter-title span {
                        display: block;
                        margin-top: 4px;
                        color: #8a98a7;
                        font-size: 11px;
                    }

                    /* =========================================================
   FILTER
========================================================= */

                    .chapter-filter {
                        width: 100%;
                        padding: 8px;

                        background: #fff;

                        border: 1px solid #e5ebf0;
                        border-radius: 11px;

                        box-shadow: 0 3px 12px rgba(15, 61, 92, 0.05);
                    }

                    .chapter-filter form {
                        width: 100%;
                    }

                    /* Search Layout */
                    .chapter-search {
                        display: flex;
                        align-items: center;
                        gap: 7px;
                    }

                    /* Search Field */
                    .search-field {
                        position: relative;
                        flex: 1;
                    }

                    .search-field i {
                        position: absolute;
                        left: 13px;
                        top: 50%;

                        transform: translateY(-50%);

                        color: #8997a4;
                        font-size: 14px;

                        z-index: 2;
                    }

                    .search-field .form-control {
                        height: 42px;

                        padding: 0 12px 0 36px;

                        border: 1px solid #dfe6ec;
                        border-radius: 8px;

                        background: #f8fafc;

                        color: #34495e;

                        font-size: 12px;

                        box-shadow: none;

                        transition: all .2s ease;
                    }

                    .search-field .form-control::placeholder {
                        color: #9aa7b3;
                    }

                    .search-field .form-control:focus {
                        background: #fff;

                        border-color: #0a3d61;

                        box-shadow:
                            0 0 0 3px rgba(10, 61, 97, .07);
                    }

                    /* =========================================================
   RESET BUTTON
========================================================= */

                    .reset-btn {
                        height: 42px;
                        min-width: 78px;

                        padding: 0 13px;

                        display: inline-flex;
                        align-items: center;
                        justify-content: center;

                        gap: 6px;

                        border: 1px solid #dfe4e8;
                        border-radius: 8px;

                        background: #f8f9fa;

                        color: #66727d;

                        font-size: 11px;
                        font-weight: 600;

                        transition: all .2s ease;
                    }

                    .reset-btn i {
                        font-size: 13px;
                    }

                    .reset-btn:hover {
                        background: #eef1f3;
                        border-color: #cbd3d9;

                        color: #263746;

                        transform: translateY(-1px);

                        box-shadow: 0 3px 8px rgba(0, 0, 0, .06);
                    }

                    /* =========================================================
   DIVIDER
========================================================= */

                    .chapter-divider {
                        height: 2px;

                        margin: 3px 0 18px;

                        border-radius: 10px;

                        background: linear-gradient(90deg,
                                #0a3d61 0%,
                                #176b9c 45%,
                                transparent 100%);
                    }

                    /* =========================================================
   RESPONSIVE
========================================================= */

                    @media (max-width: 767px) {

                        .chapter-heading {
                            min-height: auto;
                        }

                        .chapter-filter {
                            padding: 7px;
                        }

                        .chapter-search {
                            gap: 6px;
                        }

                        .reset-btn {
                            min-width: 72px;
                        }
                    }

                    @media (max-width: 480px) {

                        .chapter-title h5 {
                            font-size: 16px;
                        }

                        .chapter-title span {
                            font-size: 10px;
                        }

                        .chapter-icon {
                            width: 38px;
                            height: 38px;
                            flex-basis: 38px;
                            font-size: 17px;
                        }

                        .search-field .form-control,
                        .reset-btn {
                            height: 40px;
                        }

                        .reset-btn span {
                            display: none;
                        }

                        .reset-btn {
                            min-width: 42px;
                            width: 42px;
                            padding: 0;
                        }
                    }
                </style>


                <div class="container-fluid mt-1 ">
                    <div class="row g-2 mt-2" id="list_data"></div>
                    <div class="row g-2 mt-2" id="old_results">
                        <?php if (!empty($chapters)) : ?>
                            <?php foreach ($chapters as $row) : ?>
                                <?= view('include/member/card/chapter_card', ['row' => $row]) ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="col-12 text-center">
                                <p>No Chapters Found</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4">
                        <?= $pager->links() ?>
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
    <?php echo view('include/member/popup/chapter.php') ?>
    <script>
        $(document).ready(function() {

            // Debounce helper
            function debounce(fn, delay) {
                let timer;
                return function() {
                    const args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        fn.apply(null, args);
                    }, delay);
                };
            }

            function collectFilters() {
                return {
                    search: $('#search').val().trim(),
                    status: $('#status1').val()
                };
            }

            function saveFiltersToStorage() {
                localStorage.setItem('userFilters', JSON.stringify(collectFilters()));
            }

            function loadFiltersFromStorage() {
                let filters = localStorage.getItem('userFilters');
                if (!filters) return null;

                filters = JSON.parse(filters);

                if (filters) {
                    $('#search').val(filters.search || '');
                    $('#status1').val(filters.status || '');
                }
                return filters;
            }

            const loadUsers = debounce(function() {
                const formData = collectFilters();
                saveFiltersToStorage();

                const anyFilter = Object.values(formData).some(val => val !== "");
                if (!anyFilter) {
                    $('#list_data').hide().html('');
                    $('#old_results').show();
                    localStorage.removeItem('userFilters');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: '<?= base_url("member/chapter/get_filtered_data") ?>',
                    data: formData,
                    dataType: 'html',
                    beforeSend: function() {
                        $("#list_data").show().html('<div class="text-center py-3">Loading...</div>');
                        $('#old_results').hide();
                    },
                    success: function(response) {
                        $("#list_data").html(response).show();
                        $('#old_results').hide();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error);
                        $("#list_data").html('<div class="alert alert-danger">An error occurred. Please try again later.</div>');
                    }
                });
            }, 400);

            $('#search, #status1').on('keyup change', loadUsers);
            $('#reset-btn').on('click', function() {
                // Clear filter inputs
                $('#search').val('');
                $('#status1').val('');

                // Clear filtered data
                $('#list_data').hide().html('');

                // Show original table
                $('#old_results').show();

                // Clear localStorage
                localStorage.removeItem('userFilters');
            });

            const savedFilters = loadFiltersFromStorage();
            if (savedFilters && Object.values(savedFilters).some(val => val)) {
                loadUsers();
            } else {
                $('#list_data').hide();
                $('#old_results').show();
            }

        });
    </script>
</body>

</html>