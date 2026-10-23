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
    <link href="<?= base_url(); ?>assets2/css/meeting.css" id="app-style" rel="stylesheet" type="text/css" />
</head>

<body data-sidebar="colored">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php echo view('include/member/header.php') ?>
        <?php echo view('include/member/sidebar.php') ?>
        <div class="main-content">
            <div class="page-content">
                <div class="row p-2">
                    <div class="col-md-6 col-6">
                        <h5>Chapter Meetings:</h5>
                    </div>

                    <div class="col-md-6 col-6 d-flex justify-content-end align-items-center">

                        <button type="button"
                            class="add-meeting-btn"
                            onclick="add_new_meeting();">

                            <span class="add-meeting-icon">
                                <i class="fa fa-plus"></i>
                            </span>

                            <span class="add-meeting-text">
                                Add New Meeting
                            </span>

                        </button>

                    </div>
                </div>
                <div class="col-12">
                    <div class="meeting-filter-card">

                        <div class="meeting-filter-header">
                            <div class="filter-title">
                                <span class="filter-title-icon">
                                    <i class="fa fa-filter"></i>
                                </span>

                                <div>
                                    <h6>Meeting Filters</h6>
                                    <small>Search and filter meetings</small>
                                </div>
                            </div>

                            <button type="button"
                                id="reset-btn"
                                class="filter-reset-btn">
                                <i class="fa fa-refresh"></i>
                                <span>Reset</span>
                            </button>
                        </div>


                        <form id="search-form" method="POST">

                            <div class="meeting-filter-row">

                                <!-- SEARCH -->
                                <div class="meeting-filter-field search-field">

                                    <label for="search">
                                        <i class="fa fa-search"></i>
                                        Search
                                    </label>

                                    <div class="filter-input-wrap">

                                        <i class="fa fa-search"></i>

                                        <input type="text"
                                            id="search"
                                            name="search"
                                            class="meeting-filter-input"
                                            placeholder="Search meeting, chapter...">

                                    </div>

                                </div>


                                <!-- STATUS -->
                                <div class="meeting-filter-field">

                                    <label for="status1">
                                        <i class="fa fa-check-circle-o"></i>
                                        Status
                                    </label>

                                    <div class="filter-input-wrap">
                                        <select id="status1"
                                            name="status"
                                            class="meeting-filter-input">

                                            <option value="">All Status</option>
                                            <option value="1">Scheduled</option>
                                            <option value="2">Ongoing</option>
                                            <option value="3">Completed</option>

                                        </select>

                                    </div>

                                </div>


                                <!-- DATE -->
                                <div class="meeting-filter-field">

                                    <label for="date">
                                        <i class="fa fa-calendar"></i>
                                        Date
                                    </label>

                                    <div class="filter-input-wrap">
                                        <input
                                            type="text"
                                            id="date"
                                            name="date"
                                            class="form-control meeting-filter-input"
                                            placeholder="dd-mm-yyyy"
                                            value="<?= esc($date ?? '') ?>"
                                            autocomplete="off"
                                            readonly>
                                    </div>

                                </div>


                                <!-- MOBILE RESET -->
                                <div class="meeting-mobile-reset">

                                    <button type="button"
                                        id="reset-btn-mobile"
                                        class="filter-reset-btn">

                                        <i class="fa fa-refresh"></i>
                                        Reset Filters

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>
                </div>
                <div class="container-fluid px-2 px-md-3 mt-2">

                    <div class="meeting-card-grid" id="old_results">

                        <?php if (!empty($events)): ?>

                            <?php
                            $page = $pager->getCurrentPage('default') ?? 1;
                            $i = 1 + ($perPage * ($page - 1));
                            ?>

                            <?php foreach ($events as $row): ?>

                                <?= view('include/member/card/event_card.php', [
                                    'row' => $row,
                                    'i'   => $i
                                ]) ?>

                            <?php
                                $i++;
                            endforeach;
                            ?>

                        <?php else: ?>

                            <div class="meeting-empty">

                                <i class="fa fa-calendar-times-o"></i>

                                <h5>No Meetings Found</h5>

                                <p>
                                    There are no meetings available.
                                </p>

                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- AJAX -->
                    <div class="meeting-card-grid" id="list_data"></div>


                    <!-- PAGINATION -->
                    <div class="meeting-pagination">

                        <?= $pager->links('default', 'default_full') ?>

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
    <?php echo view('include/member/popup/meeting.php') ?>

    <script>
        $(document).ready(function() {

            function debounce(fn, delay) {
                let timer;
                return function() {
                    const args = arguments;
                    const context = this;
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        fn.apply(context, args);
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
                    url: '<?= base_url("member/meetings/get_filtered_data") ?>',
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

            // EVENTS
            $('#search').on('keyup', loadUsers);
            $('#status1, #date').on('change', loadUsers);

            // RESET
            $('#reset-btn').click(function() {
                $('#search').val('');
                $('#status1').val('');
                $('#date').val('');
                $('#list_data').hide().html('');
                $('#old_results').show();
                localStorage.removeItem('userFilters');
            });

            // LOAD SAVED
            let saved = loadFilters();
            if (saved && Object.values(saved).some(v => v)) {
                loadUsers();
            } else {
                $('#list_data').hide();
                $('#old_results').show();
            }

        });
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const dateInput = document.getElementById('date');

            if (!dateInput) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Meeting Day From Chapters Table
            |--------------------------------------------------------------------------
            */
            const meetingDay = <?= json_encode($meetingDay ?? '') ?>;

            console.log('Chapter Meeting Day:', meetingDay);

            /*
            |--------------------------------------------------------------------------
            | Convert Meeting Day To JavaScript Day Number
            |--------------------------------------------------------------------------
            |
            | Sunday    = 0
            | Monday    = 1
            | Tuesday   = 2
            | Wednesday = 3
            | Thursday  = 4
            | Friday    = 5
            | Saturday  = 6
            |
            |--------------------------------------------------------------------------
            */

            const dayMap = {
                sun: 0,
                sunday: 0,

                mon: 1,
                monday: 1,

                tue: 2,
                tues: 2,
                tuesday: 2,

                wed: 3,
                wednesday: 3,

                th: 4,
                thu: 4,
                thur: 4,
                thurs: 4,
                thursday: 4,

                fri: 5,
                friday: 5,

                sat: 6,
                saturday: 6
            };

            const dayName = String(meetingDay)
                .trim()
                .toLowerCase();

            const allowedDay = dayMap[dayName];

            console.log('Allowed Day:', allowedDay);


            flatpickr(dateInput, {

                dateFormat: 'Y-m-d',

                altInput: true,
                altFormat: 'd-m-Y',

                allowInput: false,

                clickOpens: true,

                disableMobile: true,


                disable: allowedDay !== undefined ? [
                    function(date) {

                        return date.getDay() !== allowedDay;

                    }
                ] : [],


                onReady: function(selectedDates, dateStr, instance) {

                    if (
                        selectedDates.length > 0 &&
                        allowedDay !== undefined
                    ) {

                        if (selectedDates[0].getDay() !== allowedDay) {

                            instance.clear();

                        }

                    }

                },

                onChange: function(selectedDates, dateStr, instance) {

                    if (
                        selectedDates.length > 0 &&
                        allowedDay !== undefined
                    ) {

                        const selectedDay =
                            selectedDates[0].getDay();

                        if (selectedDay !== allowedDay) {

                            instance.clear();

                            alert(
                                'Please select only the chapter meeting day: ' +
                                meetingDay
                            );

                        }

                    }

                }

            });

        });
    </script>
</body>

</html>