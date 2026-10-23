<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Blank</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>images/logo5.png">
    <link href="<?= base_url(); ?>assets2/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>assets2/js/layout.js"></script>
    <link href="<?= base_url(); ?>assets2/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets2/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* CARD */

        .attendance-card {
            border-radius: 16px;
            overflow: hidden;
        }

        /* HEADER */

        .attendance-count {
            background: #08426b;
            color: #fff;
            padding: 8px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
        }

        /* TABLE */

        .attendance-table thead th {
            background: linear-gradient(135deg, #08426b, #0a5a93);
            color: #fff;
            border: 0;
            padding: 14px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .attendance-table tbody td {
            padding: 8px;
            vertical-align: middle;
            border-color: #eef2f7;
        }

        .attendance-table tbody tr {
            transition: all .25s ease;
        }

        .attendance-table tbody tr:hover {
            background: #f8fbff;
            transform: translateY(-1px);
        }

        /* EMPTY STATE */

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            width: 70px;
            height: 70px;
            margin: auto;
            border-radius: 50%;
            background: #eef5fb;
            color: #08426b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin-bottom: 15px;
        }

        .empty-state h6 {
            font-weight: 700;
            color: #08426b;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #7a8793;
            margin: 0;
            font-size: 14px;
        }

        /* MOBILE */

        @media (max-width: 768px) {

            .attendance-table thead th,
            .attendance-table tbody td {
                padding: 10px;
                font-size: 12px;
            }

            .empty-state {
                padding: 40px 15px;
            }

            .empty-icon {
                width: 55px;
                height: 55px;
                font-size: 24px;
            }
        }

        /* =========================================================
   FILTER CARD
========================================================= */

        .attendance-filter-card {
            background: #fff;
            border: 1px solid #e3eaf0;
            border-radius: 12px;
            padding: 14px 16px;

            box-shadow: 0 4px 18px rgba(8, 66, 107, 0.06);

            position: relative;
        }


        /* =========================================================
   LABEL
========================================================= */

        .filter-label {
            display: block;

            margin-bottom: 5px;

            color: #435563;

            font-size: 11px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: .35px;
        }

        .filter-label i {
            color: #08426b;
            font-size: 11px;
        }


        /* =========================================================
   INPUT
========================================================= */

        .filter-input {
            height: 40px;

            border: 1px solid #dbe4ea;
            border-radius: 8px;

            background-color: #fbfcfd;

            color: #263746;

            font-size: 13px;

            box-shadow: none;

            transition: all .2s ease;
        }

        .filter-input:focus {
            border-color: #08426b;

            background: #fff;

            box-shadow: 0 0 0 3px rgba(8, 66, 107, .08);
        }


        /* =========================================================
   SEARCH INPUT
========================================================= */

        .filter-input-wrap {
            position: relative;
        }

        .filter-input-wrap .filter-input {
            padding-left: 36px;
        }

        .filter-input-icon {
            position: absolute;

            left: 13px;
            top: 50%;

            transform: translateY(-50%);

            color: #8a9aa7;

            font-size: 13px;

            z-index: 2;
        }


        /* =========================================================
   SELECT
========================================================= */

        select.filter-input {
            cursor: pointer;
        }


        /* =========================================================
   RESET BUTTON
========================================================= */

        .filter-reset-btn {
            height: 40px;

            border: 1px solid #08426b;

            border-radius: 8px;

            background: #08426b;

            color: #fff;

            font-size: 12px;
            font-weight: 600;

            transition: all .2s ease;
        }

        .filter-reset-btn:hover {
            background: #063653;
            border-color: #063653;

            color: #fff;

            transform: translateY(-1px);

            box-shadow: 0 5px 12px rgba(8, 66, 107, .18);
        }

        .filter-reset-btn:active {
            transform: translateY(0);
        }


        /* =========================================================
   RESPONSIVE
========================================================= */

        @media (max-width: 767px) {

            .attendance-filter-card {
                padding: 12px;
                border-radius: 10px;
            }

            .filter-label {
                font-size: 10px;
            }

            .filter-input {
                height: 38px;
                font-size: 12px;
            }

            .filter-reset-btn {
                height: 38px;
            }

        }

        .attendance-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 5px;
            margin-top: 0;
            border-top: 1px solid #edf1f4;
        }

        .pagination-info {
            font-size: 13px;
            color: #6c757d;
        }

        .pagination-info strong {
            color: #0a3d61;
        }

        .attendance-pagination .pagination {
            display: flex;
            gap: 5px;
        }

        .attendance-pagination .page-link {
            width: 36px;
            height: 36px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 0;

            border: 1px solid #dce3e8;
            border-radius: 7px !important;

            background: #fff;
            color: #0a3d61;

            font-size: 13px;
            font-weight: 600;

            transition: all .2s ease;
        }

        .attendance-pagination .page-link:hover {
            background: #0a3d61;
            border-color: #0a3d61;
            color: #fff;
        }

        .attendance-pagination .page-item.active .page-link {
            background: #0a3d61;
            border-color: #0a3d61;
            color: #fff;
        }

        .attendance-pagination .page-item.disabled .page-link {
            background: #f5f7f9;
            border-color: #e5e9ed;
            color: #adb5bd;
            cursor: not-allowed;
        }

        @media (max-width: 767px) {

            .attendance-pagination {
                flex-direction: column;
                gap: 15px;
            }

            .attendance-pagination .pagination {
                justify-content: center;
                flex-wrap: wrap;
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

                <div class="container ">
                    <h3 class="mb-2" style="color: #08426b; font-weight: 600;">Chapter attendance</h3>
                    <div class="col-12">

                        <div class="attendance-filter-card">

                            <form id="search-form" method="POST">

                                <div class="row g-1 align-items-end">

                                    <!-- =====================================================
                 SEARCH
            ====================================================== -->
                                    <div class="col-lg-3 col-md-5 col-12">

                                        <label
                                            for="search"
                                            class="filter-label">

                                            <i class="fa fa-search me-1"></i>
                                            Search

                                        </label>

                                        <div class="filter-input-wrap">

                                            <i class="fa fa-magnifying-glass filter-input-icon"></i>

                                            <input
                                                type="text"
                                                id="search"
                                                name="search"
                                                class="form-control filter-input"
                                                placeholder="Search member, event, chapter..."
                                                autocomplete="off">

                                        </div>

                                    </div>


                                    <!-- =====================================================
                 MEMBER
            ====================================================== -->
                                    <div class="col-lg-3 col-md-4 col-12">

                                        <label
                                            for="to_user"
                                            class="filter-label">

                                            <i class="fa fa-user me-1"></i>
                                            Member

                                        </label>

                                        <select
                                            name="to_user"
                                            id="to_user"
                                            class="form-select filter-input">

                                            <option value="">
                                                -- All Members --
                                            </option>

                                            <?php if (!empty($member)): ?>

                                                <?php foreach ($member as $row): ?>

                                                    <option
                                                        value="<?= esc($row['user_code']) ?>">

                                                        <?= esc($row['name']) ?>

                                                        <?php if (!empty($row['chapter_name'])): ?>

                                                            (<?= esc($row['chapter_name']) ?>)

                                                        <?php endif; ?>

                                                    </option>

                                                <?php endforeach; ?>

                                            <?php endif; ?>

                                        </select>

                                    </div>


                                    <!-- =====================================================
                 STATUS
            ====================================================== -->
                                    <div class="col-lg-2 col-md-3 col-6">

                                        <label
                                            for="status1"
                                            class="filter-label">

                                            <i class="fa fa-circle-check me-1"></i>
                                            Status

                                        </label>

                                        <select
                                            class="form-select filter-input"
                                            id="status1"
                                            name="status">

                                            <option value="">
                                                All Status
                                            </option>

                                            <option value="present">
                                                Present
                                            </option>

                                            <option value="absent">
                                                Absent
                                            </option>

                                            <option value="medical">
                                                Medical
                                            </option>

                                            <option value="substitute">
                                                Substitute
                                            </option>

                                        </select>

                                    </div>


                                    <!-- =====================================================
                 FROM DATE
            ====================================================== -->
                                    <div class="col-lg-1 col-md-3 col-6">

                                        <label
                                            for="from_date"
                                            class="filter-label">

                                            <i class="fa fa-calendar-days me-1"></i>
                                            From Date

                                        </label>

                                        <input
                                            type="date"
                                            id="from_date"
                                            name="from_date"
                                            class="form-control filter-input">

                                    </div>


                                    <!-- =====================================================
                 TO DATE
            ====================================================== -->
                                    <div class="col-lg-1 col-md-3 col-6">

                                        <label
                                            for="to_date"
                                            class="filter-label">

                                            <i class="fa fa-calendar-days me-1"></i>
                                            To Date

                                        </label>

                                        <input
                                            type="date"
                                            id="to_date"
                                            name="to_date"
                                            class="form-control filter-input">

                                    </div>


                                    <!-- =====================================================
                 MONTH
            ====================================================== -->
                                    <div class="col-lg-1 col-md-3 col-6">

                                        <label
                                            for="month"
                                            class="filter-label">

                                            <i class="fa fa-calendar-days me-1"></i>
                                            Month

                                        </label>

                                        <input
                                            type="month"
                                            id="month"
                                            name="month"
                                            class="form-control filter-input">

                                    </div>


                                    <!-- =====================================================
                 RESET
            ====================================================== -->
                                    <div class="col-lg-1 col-12">

                                        <div class="d-flex gap-2">

                                            <button
                                                type="button"
                                                id="reset-btn"
                                                class="btn btn-sm filter-reset-btn w-100">

                                                <i class="fa fa-rotate-left me-1"></i>

                                                Reset

                                            </button>

                                        </div>

                                    </div>


                                    <!-- =====================================================
                 ACTION BUTTONS
            ====================================================== -->
                                    <div class="col-lg-12 col-12">

                                        <button
                                            type="button"
                                            id="viewAttendanceBtn"
                                            class="btn btn-primary">

                                            <i class="fa fa-eye me-1"></i>

                                            View

                                        </button>


                                        <button
                                            type="button"
                                            id="exportPdfBtn"
                                            class="btn btn-danger">

                                            <i class="fa fa-file-pdf me-1"></i>

                                            PDF

                                        </button>


                                        <button
                                            type="button"
                                            id="exportExcelBtn"
                                            class="btn btn-success">

                                            <i class="fa fa-file-excel me-1"></i>

                                            Excel

                                        </button>


                                        <button
                                            type="button"
                                            id="attendanceReportBtn"
                                            class="btn btn-info">

                                            <i class="fa fa-chart-column me-1"></i>

                                            Overall Report

                                        </button>

                                    </div>

                                </div>

                            </form>

                        </div>
                    </div>


                    <div class="card border-0 shadow-sm attendance-card mt-2">
                        <div class="table-responsive">
                            <table class="table attendance-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>
                                            <i class="fa fa-hashtag me-1"></i>
                                            S.No
                                        </th>
                                        <!-- NAME -->
                                        <th>
                                            <i class="fa fa-user me-1"></i>
                                            Name
                                        </th>

                                        <!-- EVENT -->
                                        <th>
                                            <i class="fa fa-calendar-days me-1"></i>
                                            Event
                                        </th>

                                        <!-- CHAPTER -->
                                        <th>
                                            <i class="fa fa-users me-1"></i>
                                            Chapter
                                        </th>

                                        <!-- DATE -->
                                        <th>
                                            <i class="fa fa-clock me-1"></i>
                                            Date
                                        </th>

                                        <!-- STATUS -->
                                        <th>
                                            <i class="fa fa-check-circle me-1"></i>
                                            Status
                                        </th>

                                    </tr>
                                </thead>


                                <tbody id="old_results">

                                    <?php if (!empty($attendance)): ?>

                                        <?php
         
                                        $serialNo = (($currentPage - 1) * $perPage) + 1;
                                        ?>

                                        <?php foreach ($attendance as $a): ?>

                                            <?php
                                            echo view(
                                                'include/admin/card/chapter_attendance_card.php',
                                                [
                                                    'a'        => $a,
                                                    'serialNo' => $serialNo
                                                ]
                                            );

                                            $serialNo++;
                                            ?>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <tr>

                                            <td colspan="6" class="text-center">

                                                <div class="empty-state">

                                                    <div class="empty-icon">
                                                        <i class="fa fa-calendar-xmark"></i>
                                                    </div>

                                                    <h6>
                                                        No Attendance Records
                                                    </h6>

                                                    <p>
                                                        Attendance records will appear here once
                                                        you attend events.
                                                    </p>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endif; ?>

                                </tbody>


                                <!--
        |--------------------------------------------------------------------------
        | AJAX RESULTS
        |--------------------------------------------------------------------------
        -->

                                <tbody id="list_data"></tbody>

                            </table>


                            <!--
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    -->

                            <?php if ($totalRecords > 0): ?>

                                <?php
                                $startRecord = (($currentPage - 1) * $perPage) + 1;

                                $endRecord = min(
                                    $currentPage * $perPage,
                                    $totalRecords
                                );
                                ?>

                                <div class="attendance-pagination" id="attendancePagination">

                                    <div class="pagination-info">
                                        Showing
                                        <strong><?= $startRecord ?></strong>
                                        -
                                        <strong><?= $endRecord ?></strong>
                                        of
                                        <strong><?= $totalRecords ?></strong>
                                        records
                                    </div>

                                    <ul class="pagination mb-0">

                                        <?php if ($currentPage > 1): ?>

                                            <li class="page-item">
                                                <a
                                                    class="page-link"
                                                    href="<?= current_url() ?>?page=<?= $currentPage - 1 ?>">
                                                    <i class="fa fa-chevron-left"></i>
                                                </a>
                                            </li>

                                        <?php else: ?>

                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fa fa-chevron-left"></i>
                                                </span>
                                            </li>

                                        <?php endif; ?>


                                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>

                                            <li class="page-item <?= $p == $currentPage ? 'active' : '' ?>">

                                                <a
                                                    class="page-link"
                                                    href="<?= current_url() ?>?page=<?= $p ?>">
                                                    <?= $p ?>
                                                </a>

                                            </li>

                                        <?php endfor; ?>


                                        <?php if ($currentPage < $totalPages): ?>

                                            <li class="page-item">
                                                <a
                                                    class="page-link"
                                                    href="<?= current_url() ?>?page=<?= $currentPage + 1 ?>">
                                                    <i class="fa fa-chevron-right"></i>
                                                </a>
                                            </li>

                                        <?php else: ?>

                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fa fa-chevron-right"></i>
                                                </span>
                                            </li>

                                        <?php endif; ?>

                                    </ul>

                                </div>

                            <?php endif; ?>

                        </div>

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
    <script>
        $(document).ready(function() {

            /*
            |--------------------------------------------------------------------------
            | CONFIG
            |--------------------------------------------------------------------------
            */

            const FILTER_STORAGE_KEY = 'attendanceFilters';

            const FILTER_URL =
                '<?= base_url("admin/meetings/get_filtered_data3") ?>';


            /*
            |--------------------------------------------------------------------------
            | DEBOUNCE
            |--------------------------------------------------------------------------
            */

            function debounce(fn, delay) {

                let timer = null;

                return function() {

                    const context = this;
                    const args = arguments;

                    clearTimeout(timer);

                    timer = setTimeout(function() {
                        fn.apply(context, args);
                    }, delay);
                };
            }


            /*
            |--------------------------------------------------------------------------
            | COLLECT ALL FILTERS
            |--------------------------------------------------------------------------
            */

            function collectFilters() {

                return {

                    search: $.trim(
                        $('#search').val() || ''
                    ),

                    to_user: $('#to_user').val() || '',

                    status: $('#status1').val() || '',

                    from_date: $('#from_date').val() || '',

                    to_date: $('#to_date').val() || '',

                    month: $('#month').val() || ''
                };
            }


            /*
            |--------------------------------------------------------------------------
            | CHECK WHETHER ANY FILTER IS ACTIVE
            |--------------------------------------------------------------------------
            */

            function hasFilters(filters) {

                return Object.keys(filters).some(function(key) {

                    return $.trim(
                        String(filters[key] || '')
                    ) !== '';

                });
            }


            /*
            |--------------------------------------------------------------------------
            | SAVE FILTERS
            |--------------------------------------------------------------------------
            */

            function saveFilters() {

                const filters = collectFilters();

                localStorage.setItem(
                    FILTER_STORAGE_KEY,
                    JSON.stringify(filters)
                );
            }


            /*
            |--------------------------------------------------------------------------
            | LOAD FILTERS
            |--------------------------------------------------------------------------
            */

            function loadFilters() {

                const saved =
                    localStorage.getItem(
                        FILTER_STORAGE_KEY
                    );

                if (!saved) {
                    return null;
                }

                let filters;

                try {

                    filters = JSON.parse(saved);

                } catch (error) {

                    console.log(
                        'Invalid attendance filters:',
                        error
                    );

                    localStorage.removeItem(
                        FILTER_STORAGE_KEY
                    );

                    return null;
                }


                /*
                |--------------------------------------------------------------------------
                | RESTORE SEARCH
                |--------------------------------------------------------------------------
                */

                $('#search').val(
                    filters.search || ''
                );


                /*
                |--------------------------------------------------------------------------
                | RESTORE MEMBER
                |--------------------------------------------------------------------------
                */

                $('#to_user').val(
                    filters.to_user || ''
                );


                /*
                |--------------------------------------------------------------------------
                | RESTORE STATUS
                |--------------------------------------------------------------------------
                */

                $('#status1').val(
                    filters.status || ''
                );


                /*
                |--------------------------------------------------------------------------
                | RESTORE FROM DATE
                |--------------------------------------------------------------------------
                */

                $('#from_date').val(
                    filters.from_date || ''
                );


                /*
                |--------------------------------------------------------------------------
                | RESTORE TO DATE
                |--------------------------------------------------------------------------
                */

                $('#to_date').val(
                    filters.to_date || ''
                );


                /*
                |--------------------------------------------------------------------------
                | RESTORE MONTH
                |--------------------------------------------------------------------------
                */

                $('#month').val(
                    filters.month || ''
                );


                return filters;
            }


            /*
            |--------------------------------------------------------------------------
            | SHOW NORMAL RESULTS
            |--------------------------------------------------------------------------
            */

            function showNormalResults() {

                $('#list_data')
                    .hide()
                    .empty();

                $('#old_results')
                    .show();

                $('#attendancePagination')
                    .show();
            }


            /*
            |--------------------------------------------------------------------------
            | SHOW FILTERED RESULTS
            |--------------------------------------------------------------------------
            */

            function showFilteredResults() {

                $('#old_results')
                    .hide();

                $('#attendancePagination')
                    .hide();

                $('#list_data')
                    .show();
            }


            /*
            |--------------------------------------------------------------------------
            | LOADING HTML
            |--------------------------------------------------------------------------
            */

            function loadingHtml() {

                return `
            <tr>
                <td colspan="5" class="text-center py-5">

                    <div
                        class="spinner-border"
                        role="status">

                        <span class="visually-hidden">
                            Loading...
                        </span>

                    </div>

                    <div class="mt-2">
                        Loading attendance...
                    </div>

                </td>
            </tr>
        `;
            }


            /*
            |--------------------------------------------------------------------------
            | ERROR HTML
            |--------------------------------------------------------------------------
            */

            function errorHtml() {

                return `
            <tr>
                <td colspan="5" class="text-center py-4">

                    <div class="alert alert-danger mb-0">

                        <i class="fa fa-circle-exclamation me-1"></i>

                        Unable to load attendance.

                    </div>

                </td>
            </tr>
        `;
            }


            /*
            |--------------------------------------------------------------------------
            | DATE RANGE VALIDATION
            |--------------------------------------------------------------------------
            */

            function validateDateRange() {

                const fromDate =
                    $('#from_date').val() || '';

                const toDate =
                    $('#to_date').val() || '';


                /*
                |--------------------------------------------------------------------------
                | BOTH DATES SELECTED
                |--------------------------------------------------------------------------
                */

                if (
                    fromDate !== '' &&
                    toDate !== ''
                ) {

                    if (fromDate > toDate) {

                        return false;
                    }
                }

                return true;
            }


            /*
            |--------------------------------------------------------------------------
            | LOAD FILTERED ATTENDANCE
            |--------------------------------------------------------------------------
            */

            const loadAttendance = debounce(
                function() {

                    const filters =
                        collectFilters();


                    /*
                    |--------------------------------------------------------------------------
                    | VALIDATE DATE RANGE
                    |--------------------------------------------------------------------------
                    */

                    if (!validateDateRange()) {

                        $('#old_results')
                            .hide();

                        $('#attendancePagination')
                            .hide();

                        $('#list_data')
                            .html(`
                        <tr>
                            <td
                                colspan="5"
                                class="text-center py-4">

                                <div
                                    class="alert alert-warning mb-0">

                                    <i
                                        class="fa fa-triangle-exclamation me-1">
                                    </i>

                                    From Date cannot be greater than To Date.

                                </div>

                            </td>
                        </tr>
                    `)
                            .show();

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | NO FILTER
                    |--------------------------------------------------------------------------
                    */

                    if (!hasFilters(filters)) {

                        localStorage.removeItem(
                            FILTER_STORAGE_KEY
                        );

                        showNormalResults();

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | SAVE FILTERS
                    |--------------------------------------------------------------------------
                    */

                    saveFilters();


                    /*
                    |--------------------------------------------------------------------------
                    | SHOW FILTERED MODE
                    |--------------------------------------------------------------------------
                    */

                    showFilteredResults();


                    /*
                    |--------------------------------------------------------------------------
                    | AJAX
                    |--------------------------------------------------------------------------
                    */

                    $.ajax({

                        type: 'POST',

                        url: FILTER_URL,

                        data: filters,

                        dataType: 'html',


                        /*
                        |--------------------------------------------------------------------------
                        | BEFORE SEND
                        |--------------------------------------------------------------------------
                        */

                        beforeSend: function() {

                            showFilteredResults();

                            $('#list_data')
                                .html(
                                    loadingHtml()
                                )
                                .show();
                        },


                        /*
                        |--------------------------------------------------------------------------
                        | SUCCESS
                        |--------------------------------------------------------------------------
                        */

                        success: function(response) {

                            $('#attendancePagination')
                                .hide();

                            $('#old_results')
                                .hide();

                            $('#list_data')
                                .html(response)
                                .show();
                        },


                        /*
                        |--------------------------------------------------------------------------
                        | ERROR
                        |--------------------------------------------------------------------------
                        */

                        error: function(
                            xhr,
                            status,
                            error
                        ) {

                            console.log(
                                'Attendance AJAX Error:',
                                error
                            );

                            console.log(
                                xhr.responseText
                            );

                            $('#attendancePagination')
                                .hide();

                            $('#old_results')
                                .hide();

                            $('#list_data')
                                .html(
                                    errorHtml()
                                )
                                .show();
                        }

                    });

                },
                400
            );


            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            $('#search').on(
                'keyup',
                loadAttendance
            );


            /*
            |--------------------------------------------------------------------------
            | MEMBER
            |--------------------------------------------------------------------------
            */

            $('#to_user').on(
                'change',
                loadAttendance
            );


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $('#status1').on(
                'change',
                loadAttendance
            );


            /*
            |--------------------------------------------------------------------------
            | FROM DATE
            |--------------------------------------------------------------------------
            */

            $('#from_date').on(
                'change',
                function() {

                    /*
                    |--------------------------------------------------------------------------
                    | Clear Month
                    |--------------------------------------------------------------------------
                    */

                    if ($(this).val() !== '') {

                        $('#month').val('');
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Load
                    |--------------------------------------------------------------------------
                    */

                    loadAttendance();
                }
            );


            /*
            |--------------------------------------------------------------------------
            | TO DATE
            |--------------------------------------------------------------------------
            */

            $('#to_date').on(
                'change',
                function() {

                    /*
                    |--------------------------------------------------------------------------
                    | Clear Month
                    |--------------------------------------------------------------------------
                    */

                    if ($(this).val() !== '') {

                        $('#month').val('');
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Load
                    |--------------------------------------------------------------------------
                    */

                    loadAttendance();
                }
            );


            /*
            |--------------------------------------------------------------------------
            | MONTH
            |--------------------------------------------------------------------------
            */

            $('#month').on(
                'change',
                function() {

                    /*
                    |--------------------------------------------------------------------------
                    | Clear From Date / To Date
                    |--------------------------------------------------------------------------
                    */

                    if ($(this).val() !== '') {

                        $('#from_date').val('');

                        $('#to_date').val('');
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Load
                    |--------------------------------------------------------------------------
                    */

                    loadAttendance();
                }
            );


            /*
            |--------------------------------------------------------------------------
            | RESET
            |--------------------------------------------------------------------------
            */

            $('#reset-btn').on(
                'click',
                function() {

                    /*
                    |--------------------------------------------------------------------------
                    | Clear Search
                    |--------------------------------------------------------------------------
                    */

                    $('#search').val('');


                    /*
                    |--------------------------------------------------------------------------
                    | Clear Member
                    |--------------------------------------------------------------------------
                    */

                    $('#to_user').val('');


                    /*
                    |--------------------------------------------------------------------------
                    | Clear Status
                    |--------------------------------------------------------------------------
                    */

                    $('#status1').val('');


                    /*
                    |--------------------------------------------------------------------------
                    | Clear From Date
                    |--------------------------------------------------------------------------
                    */

                    $('#from_date').val('');


                    /*
                    |--------------------------------------------------------------------------
                    | Clear To Date
                    |--------------------------------------------------------------------------
                    */

                    $('#to_date').val('');


                    /*
                    |--------------------------------------------------------------------------
                    | Clear Month
                    |--------------------------------------------------------------------------
                    */

                    $('#month').val('');


                    /*
                    |--------------------------------------------------------------------------
                    | Clear LocalStorage
                    |--------------------------------------------------------------------------
                    */

                    localStorage.removeItem(
                        FILTER_STORAGE_KEY
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Clear Filtered Results
                    |--------------------------------------------------------------------------
                    */

                    $('#list_data')
                        .hide()
                        .empty();


                    /*
                    |--------------------------------------------------------------------------
                    | Show Original Results
                    |--------------------------------------------------------------------------
                    */

                    $('#old_results')
                        .show();


                    /*
                    |--------------------------------------------------------------------------
                    | Show Pagination
                    |--------------------------------------------------------------------------
                    */

                    $('#attendancePagination')
                        .show();
                }
            );


            /*
            |--------------------------------------------------------------------------
            | BUILD EXPORT URL
            |--------------------------------------------------------------------------
            */

            function buildAttendanceExportUrl(baseUrl) {

                const filters =
                    collectFilters();

                const params =
                    new URLSearchParams();


                /*
                |--------------------------------------------------------------------------
                | ADD FILTER PARAMETERS
                |--------------------------------------------------------------------------
                */

                Object.keys(filters).forEach(
                    function(key) {

                        const value =
                            String(
                                filters[key] || ''
                            ).trim();

                        if (value !== '') {

                            params.append(
                                key,
                                value
                            );
                        }

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | BUILD QUERY
                |--------------------------------------------------------------------------
                */

                const query =
                    params.toString();


                /*
                |--------------------------------------------------------------------------
                | RETURN URL
                |--------------------------------------------------------------------------
                */

                return query ?
                    baseUrl + '?' + query :
                    baseUrl;
            }


            /*
            |--------------------------------------------------------------------------
            | OVERALL ATTENDANCE REPORT
            |--------------------------------------------------------------------------
            */

            $('#attendanceReportBtn').on(
                'click',
                function(e) {

                    e.preventDefault();

                    const url =
                        buildAttendanceExportUrl(
                            '<?= base_url("admin/meetings/attendance_report") ?>'
                        );


                    window.open(
                        url,
                        '_blank'
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | VIEW ATTENDANCE
            |--------------------------------------------------------------------------
            */

            $('#viewAttendanceBtn').on(
                'click',
                function(e) {

                    e.preventDefault();

                    const url =
                        buildAttendanceExportUrl(
                            '<?= base_url("admin/meetings/attendance_view") ?>'
                        );


                    window.open(
                        url,
                        '_blank'
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | PDF EXPORT
            |--------------------------------------------------------------------------
            */

            $('#exportPdfBtn').on(
                'click',
                function(e) {

                    e.preventDefault();

                    const url =
                        buildAttendanceExportUrl(
                            '<?= base_url("admin/meetings/attendance_pdf") ?>'
                        );


                    window.open(
                        url,
                        '_blank'
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | EXCEL EXPORT
            |--------------------------------------------------------------------------
            */

            $('#exportExcelBtn').on(
                'click',
                function(e) {

                    e.preventDefault();

                    const url =
                        buildAttendanceExportUrl(
                            '<?= base_url("admin/meetings/attendance_excel") ?>'
                        );


                    window.location.href =
                        url;
                }
            );


            /*
            |--------------------------------------------------------------------------
            | LOAD SAVED FILTERS
            |--------------------------------------------------------------------------
            */

            const savedFilters =
                loadFilters();


            /*
            |--------------------------------------------------------------------------
            | APPLY SAVED FILTERS
            |--------------------------------------------------------------------------
            */

            if (
                savedFilters &&
                hasFilters(savedFilters)
            ) {

                setTimeout(
                    function() {

                        loadAttendance();

                    },
                    100
                );

            } else {

                showNormalResults();
            }

        });
    </script>
</body>

</html>