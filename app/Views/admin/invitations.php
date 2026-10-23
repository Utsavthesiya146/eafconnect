<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Meetings</title>
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
                        <h5>Manage invitations:</h5>
                    </div>

                    <div class="col-md-6 col-6">
                        <a class="btn btn-primary btn-sm"
                            onclick="add_new_invitations();"
                            style="font-size:12px;font-weight:500;float:right;background-color:#08426b;margin:2px;">
                            Send New Invitations <i class="fa fa-plus-circle ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-12 col-12">
                    <div class="card border shadow-sm filter-bar">
                        <div class="card-body p-2">
                            <form id="search-form" method="POST">
                                <div class="row g-2 align-items-center">
                                    <!-- SEARCH -->
                                    <div class="col-md-4 col-12">
                                        <label class="fw-bold">Search</label>
                                        <input type="text" id="search" name="search"
                                            class="form-control" placeholder="Search ...">
                                    </div>
                                    <!-- STATUS -->
                                    <div class="col-md-3">
                                        <label class="fw-bold">Status</label>
                                        <select class="form-select" id="status1" name="status">
                                            <option value="">Select Status</option>
                                            <option value="sent">sent</option>
                                            <option value="viewed">viewed</option>
                                            <option value="accepted">accepted</option>
                                            <option value="declined">declined</option>
                                        </select>
                                    </div>
                                    <!-- DATE -->
                                    <div class="col-md-2 col-12">
                                        <label class="fw-bold">Date</label>
                                        <input type="date" id="date" name="date"
                                            class="form-control">
                                    </div>
                                    <!-- RESET -->
                                    <div class="col-md-2 col-12">
                                        <label class="fw-bold"> </label>
                                        <button type="button"
                                            id="reset-btn"
                                            class="btn btn-sm btn-outline-secondary w-100">
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-1">
                    <div class="modern-table-wrapper">
                        <table class="modern-table">
                            <!-- TABLE HEADER -->
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Guest Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Company</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Sent At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <!-- TABLE BODY -->
                            <tbody id="old_results">
                                <?php if (!empty($invitations)): ?>
                                    <?php
                                    $page = $pager->getCurrentPage('default') ?? 1;
                                    $i = 1 + ($perPage * ($page - 1));
                                    ?>
                                    <?php foreach ($invitations as $row): ?>
                                        <?= view('include/admin/card/invitations_card.php', [
                                            'row' => $row,
                                            'i'   => $i
                                        ]) ?>
                                    <?php $i++;
                                    endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center p-4">
                                            No Invitations Found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tbody id="list_data"></tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    <div class="row mt-2">
                        <div class="d-flex justify-content-center">
                            <?= $pager->links('default', 'default_full') ?>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php echo view('include/admin/popup/invitations.php') ?>

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
                    url: '<?= base_url("admin/invitations/get_filtered_data") ?>',
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