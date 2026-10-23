<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>MITB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

        /* wrapper */
        .modern-table-wrapper {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            overflow: scroll;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* table */
        .modern-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            /* increased font size */
        }

        /* header */
        .modern-table thead {
            background: #08426b;
            color: #fff;
        }

        .modern-table th {
            padding: 14px 12px;
            /* increased padding */
            font-weight: 700;
            text-align: left;
        }

        /* rows */
        .modern-table td {
            padding: 14px 12px;
            /* increased padding */
            border-bottom: 1px solid #f1f1f1;
            vertical-align: middle;
        }

        .modern-table tbody tr {
            transition: background 0.25s ease;
        }

        .modern-table tbody tr:hover {
            background: #fff8e1;
        }

        /* member */
        .member-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            /* slightly bigger gap */
        }

        .avatar {
            width: 48px;
            /* slightly bigger avatar */
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f1f1f1;
            /* subtle border */
        }

        .name {
            font-weight: 600;
            font-size: 15px;
            /* increased font size */
        }

        .member-cell small {
            font-size: 13px;
            /* slightly bigger small text */
            color: #666;
        }

        /* business */
        .business {
            color: #08426b;
            font-weight: 600;
            font-size: 14px;
            /* increased font size */
        }

        /* role badge */
        .role-badge {
            background: #ffc107;
            padding: 6px 12px;
            /* more padding */
            border-radius: 20px;
            font-size: 12px;
            /* increased font size */
            font-weight: 600;
        }

        /* status badges */
        .status {
            padding: 6px 12px;
            /* more padding */
            border-radius: 20px;
            font-size: 12px;
            /* increased font size */
            font-weight: 600;
            display: inline-block;
            text-align: center;
        }

        .status.active {
            background: #e6f7ec;
            color: #198754;
        }

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.blocked {
            background: #fdeaea;
            color: #08426b;
        }

        /* action buttons */
        .btn-sm {
            padding: 6px 10px;
            font-size: 13px;
            /* slightly larger */
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-sm:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .card {
            margin-bottom: 10px;
        }

        .card:hover {
            transform: translateY(-3px);
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        /* STATUS BADGES */
        .status {
            padding: 6px 14px;
            /* more padding for better look */
            border-radius: 20px;
            font-size: 13px;
            /* readable size */
            font-weight: 600;
            display: inline-block;
            text-align: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        /* Colors for each referral status */
        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.contacted {
            background: #cce5ff;
            color: #004085;
        }

        .status.completed {
            background: #d4edda;
            color: #155724;
        }


        .status.cancelled {
            background: #f5c6cb;
            color: #721c24;
        }

        /* Hover effect */
        .status:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
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
            background: #bb171d;
            color: #fff;
            border-color: #bb171d;
            transform: translateY(-1px);
        }

        /* Active page */
        .pagination li.active span {
            background: #bb171d;
            color: #fff;
            border-color: #bb171d;
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

        /* ==========================
   MOBILE CARD VIEW
========================== */
        @media (max-width:768px) {

            .modern-table-wrapper {
                overflow: hidden !important;
                box-shadow: none;
                background: transparent;
            }

            .modern-table,
            .modern-table thead,
            .modern-table tbody,
            .modern-table tr,
            .modern-table td,
            .modern-table th {
                display: block;
                width: 100%;
            }

            .modern-table thead {
                display: none;
            }

            .modern-table tbody tr {
                background: #fff;
                border: 1px solid #eee;
                border-radius: 10px;
                padding: 8px;
                margin-bottom: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            }

            .modern-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 4px 0;
                border: 0;
                font-size: 12px;
            }

            .modern-table td:before {
                content: attr(data-label);
                font-weight: 600;
                color: #555;
            }

            /* Member block */
            .modern-table td.member-info {
                display: block;
                padding-bottom: 6px;
                margin-bottom: 6px;
                border-bottom: 1px solid #eee;
            }

            .modern-table td.member-info:before {
                display: block;
                margin-bottom: 5px;
                color: #08426b;
            }

            .member-cell {
                gap: 8px;
            }

            .avatar {
                width: 40px;
                height: 40px;
            }

            .name {
                font-size: 13px;
                margin-bottom: 2px;
            }

            .member-cell small {
                font-size: 11px;
                word-break: break-word;
            }

            .amount {
                color: #198754;
                font-size: 13px;
            }

            /* Actions */
            .modern-table td.actions {
                justify-content: center;
                gap: 6px;
                border-top: 1px solid #eee;
                margin-top: 6px;
                padding-top: 8px;
            }

            .modern-table td.actions:before {
                display: none;
            }

            .btn-sm {
                padding: 4px 8px;
                font-size: 11px;
                border-radius: 6px;
            }

            /* Pagination */
            .pagination {
                justify-content: center;
                flex-wrap: wrap;
                gap: 4px;
            }

            .pagination li a,
            .pagination li span {
                padding: 5px 8px;
                font-size: 11px;
            }
        }

        /* Stats Cards */
        .stats-card {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 18px;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .08);
            transition: all .3s ease;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
        }

        .stats-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stats-content {
            flex: 1;
        }

        .stats-label {
            display: block;
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .stats-content h3 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            line-height: 1.2;
        }

        /* Revenue */
        .revenue-card {
            border-left: 4px solid #198754;
        }

        .revenue-card .stats-icon {
            background: rgba(25, 135, 84, .12);
            color: #198754;
        }

        /* Member */
        .member-card {
            border-left: 4px solid #0d6efd;
        }

        .member-card .stats-icon {
            background: rgba(13, 110, 253, .12);
            color: #0d6efd;
        }

        /* Records */
        .records-card {
            border-left: 4px solid #08426b;
        }

        .records-card .stats-icon {
            background: rgba(209, 28, 47, .12);
            color: #08426b;
        }

        /* Mobile */
        @media (max-width:768px) {

            .stats-card {
                padding: 12px;
                gap: 10px;
                border-radius: 10px;
            }

            .stats-icon {
                width: 42px;
                height: 42px;
                font-size: 16px;
            }

            .stats-label {
                font-size: 11px;
                margin-bottom: 2px;
            }

            .stats-content h3 {
                font-size: 18px;
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
                <div class="row p-2">
                    <div class="col-md-6 col-6">
                        <h5>Manage MITB:</h5>
                    </div>
                    <div class="col-md-6 col-6">
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <div class="card shadow-sm p-3">
                            <h6 class="text-muted">Business Revenue</h6>
                            <h3 class="fw-bold text-success">
                                ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($totalRevenue, 2) ?>
                            </h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm p-3">
                            <h6 class="text-muted">Avg Member Value</h6>
                            <h3 class="fw-bold text-primary">
                                ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($avgMemberValue, 2) ?>
                            </h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm p-3">
                            <h6 class="text-muted">Members</h6>
                            <h3 class="fw-bold text-primary">
                                ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($member_c, 2) ?>
                            </h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm p-3">
                            <h6 class="text-muted">Total Records</h6>
                            <h3 class="fw-bold text-primary">
                                <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($totalRecords ?? 0) ?>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="card border shadow-sm mb-3 meeting-filter-card">
                    <div class="card-body p-3">
                        <form id="search-form">
                            <div class="meeting-filter-row">
                                <!-- SEARCH -->
                                <div class="meeting-search filter-col-search">
                                    <label class="filter-label" for="search">
                                        <i class="ri-search-line"></i>
                                        Search
                                    </label>
                                    <div class="filter-input-wrap">
                                        <i class="ri-search-line search-icon"></i>
                                        <input type="text" id="search" class="form-control meeting-input"
                                            placeholder="Search transactions, members...">
                                    </div>
                                </div>

                                <!-- CATEGORY -->
                                <div class="meeting-filter filter-col-cat">
                                    <label class="filter-label" for="category">
                                        <i class="ri-price-tag-3-line"></i>
                                        Category
                                    </label>
                                    <select class="form-select meeting-select" id="category">
                                        <option value="">Category</option>
                                        <option value="New">New</option>
                                        <option value="Visitor">Visitor</option>
                                    </select>
                                </div>

                                <!-- BUSINESS TYPE -->
                                <div class="meeting-filter filter-col-biz">
                                    <label class="filter-label" for="business_type">
                                        <i class="ri-briefcase-4-line"></i>
                                        Business
                                    </label>
                                    <select class="form-select meeting-select" id="business_type">
                                        <option value="">Business Type</option>
                                        <option value="New">New</option>
                                        <option value="Repeat">Repeat</option>
                                    </select>
                                </div>

                                <!-- CHAPTER -->
                                <div class="meeting-filter filter-col-chap">
                                    <label class="filter-label" for="chapter_code">
                                        <i class="ri-community-line"></i>
                                        Chapter
                                    </label>
                                    <select class="form-select meeting-select" id="chapter_code" name="chapter_code">
                                        <option value="">Chapter</option>
                                        <?php if (!empty($chapters)) : ?>
                                            <?php foreach ($chapters as $chapter) : ?>
                                                <option value="<?= esc($chapter['chapter_code']) ?>">
                                                    <?= esc($chapter['chapter_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <!-- DURATION -->
                                <div class="meeting-filter filter-col-dur">
                                    <label class="filter-label" for="month_filter">
                                        <i class="ri-time-line"></i>
                                        Duration
                                    </label>
                                    <select class="form-select meeting-select" id="month_filter">
                                        <option value="">Duration</option>
                                        <option value="7">7 Days</option>
                                        <option value="30">1 Month</option>
                                        <option value="180">6 Month</option>
                                        <option value="365">12 Month</option>
                                    </select>
                                </div>

                                <!-- DATE RANGE -->
                                <div class="meeting-filter filter-col-from">
                                    <label class="filter-label" for="from_date">
                                        <i class="ri-calendar-event-line"></i>
                                        From
                                    </label>
                                    <input type="date" id="from_date" class="form-control meeting-input">
                                </div>
                                <div class="meeting-filter filter-col-to">
                                    <label class="filter-label" for="to_date">
                                        <i class="ri-calendar-check-line"></i>
                                        To
                                    </label>
                                    <input type="date" id="to_date" class="form-control meeting-input">
                                </div>

                                <!-- ACTIONS -->
                                <div class="meeting-filter-actions filter-col-btns">
                                    <button type="button" id="reset-btn"
                                        class="filter-btn filter-btn-reset" title="Reset Filters">
                                        <i class="ri-refresh-line"></i>
                                        <span>Reset</span>
                                    </button>
                                    <!-- EXPORT EXCEL -->
                                    <button type="button" id="export-excel"
                                        class="filter-btn filter-btn-excel" title="Export to Excel">
                                        <i class="ri-file-excel-2-line"></i>
                                        <span>Excel</span>
                                    </button>
                                    <!-- EXPORT PDF -->
                                    <button type="button" id="export-pdf"
                                        class="filter-btn filter-btn-pdf" title="Export to PDF">
                                        <i class="ri-file-pdf-2-line"></i>
                                        <span>PDF</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- ================= TABLE ================= -->
                <div class="container-fluid mt-1">
                    <div class="modern-table-wrapper">

                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Giver</th>
                                    <th>Receiver</th>
                                    <th>Category</th>
                                    <th>Business Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="old_results">
                                <?php if (!empty($connect)): ?>
                                    <?php
                                    $page = $pager->getCurrentPage('default') ?? 1;
                                    $i = 1 + ($perPage * ($page - 1));
                                    ?>

                                    <?php foreach ($connect as $row): ?>
                                        <?= view('include/admin/card/mitb_card', [
                                            'row' => $row,
                                            'i'   => $i++
                                        ]) ?>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center p-4">No Records Found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                            <tbody id="list_data" style="display:none;"></tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-2">
                        <?= $pager->links('default', 'default_full') ?>
                    </div>
                </div>



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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php echo view('include/admin/popup/mitb.php') ?>
   <script>
$(document).ready(function () {

    /* =========================================================
       DEBOUNCE
    ========================================================= */
    function debounce(fn, delay) {

        let timer;

        return function () {

            let context = this;
            let args = arguments;

            clearTimeout(timer);

            timer = setTimeout(function () {
                fn.apply(context, args);
            }, delay);
        };
    }


    /* =========================================================
       COLLECT FILTERS
    ========================================================= */
    function collectFilters() {

        return {
            search: $.trim($('#search').val() || ''),
            category: $('#category').val() || '',
            business_type: $('#business_type').val() || '',
            chapter_code: $.trim($('#chapter_code').val() || ''),
            month_filter: $('#month_filter').val() || '',
            from_date: $('#from_date').val() || '',
            to_date: $('#to_date').val() || ''
        };
    }


    /* =========================================================
       CHECK WHETHER ANY FILTER IS SELECTED
    ========================================================= */
    function hasFilters(filters) {

        return (
            filters.search !== '' ||
            filters.category !== '' ||
            filters.business_type !== '' ||
            filters.chapter_code !== '' ||
            filters.month_filter !== '' ||
            filters.from_date !== '' ||
            filters.to_date !== ''
        );
    }


    /* =========================================================
       LOAD FILTERED DATA
    ========================================================= */
    const loadUsers = debounce(function () {

        const filters = collectFilters();

        /* -----------------------------------------------------
           NO FILTER
        ----------------------------------------------------- */
        if (!hasFilters(filters)) {

            $('#list_data')
                .hide()
                .html('');

            $('#old_results').show();

            return;
        }


        /* -----------------------------------------------------
           VALIDATE DATE RANGE
        ----------------------------------------------------- */
        if (filters.from_date && filters.to_date) {

            if (filters.from_date > filters.to_date) {

                $('#list_data')
                    .show()
                    .html(
                        '<tr>' +
                        '<td colspan="8" class="text-danger text-center">' +
                        'From Date cannot be greater than To Date' +
                        '</td>' +
                        '</tr>'
                    );

                $('#old_results').hide();

                return;
            }
        }


        /* -----------------------------------------------------
           AJAX
        ----------------------------------------------------- */
        $.ajax({

            type: "POST",

            url: "<?= base_url('admin/mitb/get_filtered_data') ?>",

            data: filters,

            dataType: "html",

            beforeSend: function () {

                $('#list_data')
                    .show()
                    .html(
                        '<tr>' +
                        '<td colspan="8" class="text-center py-4">' +
                        '<i class="fa fa-spinner fa-spin me-2"></i>' +
                        'Loading...' +
                        '</td>' +
                        '</tr>'
                    );

                $('#old_results').hide();
            },

            success: function (res) {

                $('#list_data')
                    .html(res)
                    .show();

                $('#old_results').hide();
            },

            error: function (xhr, status, error) {

                console.error('AJAX Error:', error);
                console.error(xhr.responseText);

                $('#list_data')
                    .show()
                    .html(
                        '<tr>' +
                        '<td colspan="8" class="text-danger text-center">' +
                        'Unable to load records. Please try again.' +
                        '</td>' +
                        '</tr>'
                    );

                $('#old_results').hide();
            }

        });

    }, 400);


    /* =========================================================
       FILTER BUTTON
    ========================================================= */
    $('#filter-btn').on('click', function (e) {

        e.preventDefault();

        loadUsers();
    });


    /* =========================================================
       LIVE FILTER
    ========================================================= */
    $(
        '#search, ' +
        '#category, ' +
        '#business_type, ' +
        '#chapter_code, ' +
        '#month_filter, ' +
        '#from_date, ' +
        '#to_date'
    ).on('keyup change', function () {

        loadUsers();
    });


    /* =========================================================
       RESET
    ========================================================= */
    $('#reset-btn').on('click', function (e) {

        e.preventDefault();

        $('#search-form')[0].reset();

        $('#search').val('');
        $('#category').val('');
        $('#business_type').val('');
        $('#chapter_code').val('');
        $('#month_filter').val('');
        $('#from_date').val('');
        $('#to_date').val('');

        $('#list_data')
            .hide()
            .html('');

        $('#old_results').show();
    });


    /* =========================================================
       EXPORT FILTERS
    ========================================================= */
    function getFilters() {

        return {
            search: $.trim($('#search').val() || ''),
            category: $('#category').val() || '',
            business_type: $('#business_type').val() || '',
            chapter_code: $.trim($('#chapter_code').val() || ''),
            month_filter: $('#month_filter').val() || '',
            from_date: $('#from_date').val() || '',
            to_date: $('#to_date').val() || ''
        };
    }


    /* =========================================================
       EXPORT EXCEL
    ========================================================= */
    $('#export-excel').on('click', function (e) {

        e.preventDefault();

        const params = $.param(getFilters());

        window.location.href =
            "<?= site_url('admin/mitb/export_excel') ?>?" + params;
    });


    /* =========================================================
       EXPORT PDF
    ========================================================= */
    $('#export-pdf').on('click', function (e) {

        e.preventDefault();

        const params = $.param(getFilters());

        window.location.href =
            "<?= site_url('admin/mitb/export_pdf') ?>?" + params;
    });

});
</script>

    
</body>

</html>