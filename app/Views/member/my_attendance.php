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
    </style>
</head>

<body data-sidebar="colored">

    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php echo view('include/member/header.php') ?>
        <?php echo view('include/member/sidebar.php') ?>
        <div class="main-content">
            <div class="page-content">

                <div class="container ">
                    <h3 class="mb-2" style="color: #08426b; font-weight: 600;">My Attendance Tracker</h3>
                    <div class="col-12">
                        <div class="attendance-filter-card">

                            <form id="search-form" method="POST">

                                <div class="row g-2 align-items-end">

                                    <!-- SEARCH -->
                                    <div class="col-lg-5 col-md-5 col-12">

                                        <label for="search" class="filter-label">
                                            <i class="fa fa-search me-1"></i>
                                            Search
                                        </label>

                                        <div class="filter-input-wrap">

                                            <i class="fa fa-magnifying-glass filter-input-icon"></i>

                                            <input type="text"
                                                id="search"
                                                name="search"
                                                class="form-control filter-input"
                                                placeholder="Search member, event...">

                                        </div>

                                    </div>


                                    <!-- STATUS -->
                                    <div class="col-lg-3 col-md-3 col-6">

                                        <label for="status1" class="filter-label">
                                            <i class="fa fa-circle-check me-1"></i>
                                            Status
                                        </label>

                                        <select class="form-select filter-input"
                                            id="status1"
                                            name="status">

                                            <option value="">All Status</option>

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


                                    <!-- DATE -->
                                    <div class="col-lg-2 col-md-2 col-6">

                                        <label for="date" class="filter-label">
                                            <i class="fa fa-calendar-days me-1"></i>
                                            Date
                                        </label>

                                        <input type="date"
                                            id="date"
                                            name="date"
                                            class="form-control filter-input">

                                    </div>


                                    <!-- RESET -->
                                    <div class="col-lg-2 col-md-2 col-12">

                                        <button type="button"
                                            id="reset-btn"
                                            class="btn filter-reset-btn w-100">

                                            <i class="fa fa-rotate-left me-1"></i>
                                            Reset Filters

                                        </button>

                                    </div>

                                </div>

                            </form>

                        </div>
                    </div>



                    <div class="card border-0 shadow-sm attendance-card mt-2">

                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold text-primary">
                                    <i class="fa fa-calendar-check me-2"></i>
                                    My Attendance
                                </h5>

                                <span class="badge attendance-count">
                                    <?= count($attendance ?? []) ?> Records
                                </span>
                            </div>
                        </div>

                        <div class="table-responsive">

                            <table class="table attendance-table align-middle mb-0">

                                <thead>
                                    <tr>
                                        <th>
                                            <i class="fa fa-calendar-days me-1"></i>
                                            Event
                                        </th>

                                        <th>
                                            <i class="fa fa-users me-1"></i>
                                            Chapter
                                        </th>

                                        <th>
                                            <i class="fa fa-clock me-1"></i>
                                            Date
                                        </th>

                                        <th>
                                            <i class="fa fa-check-circle me-1"></i>
                                            Status
                                        </th>
                                    </tr>
                                </thead>

                                <tbody id="old_results">

                                    <?php if (!empty($attendance)): ?>

                                        <?php foreach ($attendance as $a): ?>

                                            <?= view(
                                                'include/member/card/my_attendance_card.php',
                                                ['a' => $a]
                                            ) ?>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <tr>

                                            <td colspan="4">

                                                <div class="empty-state">

                                                    <div class="empty-icon">
                                                        <i class="fa fa-calendar-xmark"></i>
                                                    </div>

                                                    <h6>No Attendance Records</h6>

                                                    <p>
                                                        Attendance records will appear here once
                                                        you attend events.
                                                    </p>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                                <tbody id="list_data"></tbody>

                            </table>

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


    <script>
        $(document).ready(function() {

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
                    status: $('#status1').val(),
                    date: $('#date').val(),

                };
            }

            function saveFilters() {
                localStorage.setItem('userFilters', JSON.stringify(collectFilters()));
            }

            function loadFilters() {
                let filters = localStorage.getItem('userFilters');
                if (!filters) return null;

                filters = JSON.parse(filters);

                $('#search').val(filters.search || '');
                $('#status1').val(filters.status || '');
                $('#date').val(filters.date || '');


                return filters;
            }

            const loadUsers = debounce(function() {

                let formData = collectFilters();
                saveFilters();

                let hasFilter = Object.values(formData).some(v => v !== "");

                if (!hasFilter) {
                    $('#list_data').hide().html('');
                    $('#old_results').show();
                    localStorage.removeItem('userFilters');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: '<?= base_url("member/meetings/get_filtered_data2") ?>',
                    data: formData,
                    dataType: 'html',

                    beforeSend: function() {
                        $('#list_data').show().html('<div class="text-center py-3">Loading...</div>');
                        $('#old_results').hide();
                    },

                    success: function(res) {
                        $('#list_data').html(res).show();
                    },

                    error: function(xhr) {
                        console.log(xhr.responseText);
                        $('#list_data').html('<div class="alert alert-danger">Server Error</div>');
                    }
                });

            }, 400);

            // ✅ EVENTS
            $('#search, #status1, #date').on('keyup change', loadUsers);

            // ✅ RESET
            $('#reset-btn').click(function() {
                $('#search').val('');
                $('#status1').val('');
                $('#date').val('');


                $('#list_data').hide().html('');
                $('#old_results').show();

                localStorage.removeItem('userFilters');
            });

            // ✅ LOAD SAVED
            let saved = loadFilters();
            if (saved && Object.values(saved).some(v => v)) {
                loadUsers();
            } else {
                $('#list_data').hide();
                $('#old_results').show();
            }

        });
    </script>
</body>

</html>