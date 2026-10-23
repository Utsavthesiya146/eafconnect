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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --theme: #08426b;
            --theme-light: #eef0ff;
            --theme-soft: rgba(207, 31, 46, .12);
            --theme-gradient: linear-gradient(135deg, #08426b, #08426b);
        }

        .dash-card {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .06);
        }

        .stat-box {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 15px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid #08426b;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .05);
            transition: .25s ease;
            height: 100%;
        }

        .stat-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
        }

        /* ICON */
        .stat-box i {
            font-size: 20px;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--theme-gradient);
            color: #fff;
            flex-shrink: 0;

        }

        /* TEXT AREA */
        .stat-box .stat-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .stat-box small {
            font-size: 12px;
            line-height: 1em;
            color: #888;
        }



        .stat-box h5 {
            margin: 2px 0 0;
            font-weight: 700;
            color: #222;
        }


        /* ===================================
   PROFILE PROGRESS BAR
=================================== */
        .profile-progress {
            height: 6px;
            border-radius: 10px;
            background: #f1f3ff;
            overflow: hidden;
        }

        .profile-progress .progress-bar {
            background: var(--theme-gradient);
            transition: width 1.5s ease-in-out;
        }


        /* ===================================
   MEETING ROW ALIGNMENT
=================================== */
        .meeting-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 14px 16px;
            border-radius: 12px;
            background: #fff;
            margin-bottom: 10px;
            border: 1px solid #eee;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .05);
            transition: .25s ease;
            flex-wrap: wrap;
        }

        .meeting-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        }

        .col-block {
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .col-block.number {
            font-weight: 600;
            color: var(--theme);
            min-width: 45px;
        }

        .col-block i {
            color: var(--theme);
        }


        /* ===================================
   WEEKLY CARD
=================================== */
        .weekly-card {
            border-radius: 14px;
            transition: .3s;
        }

        .weekly-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
        }


        /* ===================================
   REPORT FILTER TABS (FIXED ALIGNMENT)
=================================== */
        .custom-report-tabs {
            border: none;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .custom-report-tabs .nav-link {
            border: none;
            background: #f5f6ff;
            color: var(--theme);
            font-weight: 600;
            padding: 6px 18px;
            border-radius: 25px;
            transition: .3s;
        }

        .custom-report-tabs .nav-link:hover {
            background: rgba(207, 31, 46, .12);
        }

        .custom-report-tabs .nav-link.active {
            background: #1b4d8f;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(40, 172, 66, 0.35);
        }

        /* remove bootstrap border */
        .nav-tabs .nav-link:focus,
        .nav-tabs .nav-link:hover {
            border: none;
        }


        /* ===================================
   MOBILE FIX
=================================== */
        @media(max-width:768px) {

            .meeting-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .stat-box {
                padding: 12px;
            }

            .custom-report-tabs {
                justify-content: center;
                flex-wrap: wrap;
            }
        }

        /* ===== Section Title ===== */
        .chapter-title {
            color: #08426b;
            font-weight: 700;
        }

        /* ===== KPI Cards ===== */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 18px 14px;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            transition: all .3s ease;
            overflow: hidden;
        }

        /* left gradient bar */
        .stat-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 6px;
            border-radius: 14px 0 0 14px;
        }

        /* hover effect */
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
        }

        /* label */
        .stat-card span {
            font-size: 11px;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* value */
        .stat-card h3 {
            margin-top: 8px;
            font-size: 20px;
            font-weight: 700;
            color: #212529;
        }

        /* ===== Color Variants ===== */
        .stat-primary::before {
            background: linear-gradient(180deg, #4e73df, #224abe);
        }

        .stat-success::before {
            background: linear-gradient(180deg, #1cc88a, #13855c);
        }

        .stat-warning::before {
            background: linear-gradient(180deg, #f6c23e, #dda20a);
        }

        .stat-info::before {
            background: linear-gradient(180deg, #36b9cc, #258391);
        }

        .stat-secondary::before {
            background: linear-gradient(180deg, #858796, #60616f);
        }

        .stat-dark::before {
            background: linear-gradient(180deg, #343a40, #121416);
        }

        /* Responsive improvement */
        @media (max-width:768px) {
            .stat-card h3 {
                font-size: 20px;
            }
        }

        .report-card {
            border: 0;
            border-radius: 14px;
            padding: 10px 20px;
            background: #fff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .report-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.12);
        }

        .report-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
        }

        /* Theme colors */
        .bg-theme {
            background: #08426b;
        }

        .bg-theme-light {
            background: #ffecee;
            color: #08426b;
        }

        .report-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 0;
        }

        .report-arrow {
            font-size: 20px;
            color: #999;
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
            background: linear-gradient(90deg, #08426b, #0a3d61);
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
            <div class="page-content">

                <div class="row col-12">
                    <?php if (session()->getFlashdata('msg')) { ?>
                        <strong><?= session()->getFlashdata('msg'); ?></strong>
                    <?php } ?>
                </div>
                <!-- Header -->

                <div class="row g-2">
                    <div class="col-m-6">
                        <h4>My Personal Participation Report</h4>
                    </div>
                    <div class="col-m-6">
                        <div class=" d-flex justify-content-between align-items-center mb-3">

                            <h5 class="fw-bold text-danger mb-0">
                                <i class="bi bi-graph-up-arrow me-2"></i>
                                Business Performance
                            </h5>

                            <ul class="nav nav-tabs custom-report-tabs ">

                                <li class="nav-item">
                                    <a class="nav-link <?= ($filter == '180') ? 'active' : '' ?>"
                                        href="<?= current_url() . '?filter=180' ?>">
                                        6 Months
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?= ($filter == '365') ? 'active' : '' ?>"
                                        href="<?= current_url() . '?filter=365' ?>">
                                        12 Months
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?= ($filter == 'all') ? 'active' : '' ?>"
                                        href="<?= current_url() . '?filter=all' ?>">
                                        Lifetime
                                    </a>
                                </li>

                            </ul>

                        </div>
                    </div>

                    <!-- ===== Chart ===== -->
                    <div class="col-lg-6">
                        <div class="card dash-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-semibold mb-0">
                                        <i class="bi bi-bar-chart-line-fill me-2"></i>
                                        Business Report
                                    </h6>
                                    <select id="mitbFilter" class="form-select form-select-sm w-auto">
                                        <option value="7" <?= ($filter == '15') ? 'selected' : '' ?>>Last 15 Days</option>
                                        <option value="30" <?= ($filter == '30') ? 'selected' : '' ?>>1 Month</option>
                                        <option value="180" <?= ($filter == '180') ? 'selected' : '' ?>>6 Months</option>
                                        <option value="all" <?= ($filter == 'all') ? 'selected' : '' ?>>Lifetime</option>
                                    </select>
                                </div>
                                <canvas id="weeklyMITBChart" height="130"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- ===== Stats ===== -->
                    <div class="col-lg-6">
                        <div class="row g-3">
                            <!-- MITB Given -->
                            <div class="col-md-4 col-6">
                                <a href="<?= base_url(); ?>member/mitb">
                                    <div class="stat-box given">
                                        <i class="bi bi-arrow-up-circle-fill"></i>
                                        <div>
                                            <small style="line-height:1.2; display:block;">MITB Received</small>
                                            <h5>₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($totalMITBReceived ?? 0, 2); ?></h5>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- MITB Received -->
                            <div class="col-md-4 col-6">
                                <a href="<?= base_url(); ?>member/mitb">
                                    <div class="stat-box received">
                                        <i class="bi bi-arrow-down-circle-fill"></i>
                                        <div>
                                            <small style="line-height:1.2; display:block;">MITB Given</small>
                                            <h5>₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($totalMITBGiven ?? 0, 2); ?></h5>
                                        </div>
                                    </div>
                                </a>
                            </div>


                            <!-- Visitors -->
                            <div class="col-md-4 col-6"> <a href="<?= base_url(); ?>member/visitor">
                                    <div class="stat-box visitors">
                                        <i class="bi bi-people-fill"></i>
                                        <div>
                                            <small style="line-height:1.2; display:block;">Visitors</small>
                                            <h5><?= $visitors_c ?? 0; ?></h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- Referral Given -->
                            <div class="col-md-4 col-6"><a href="<?= base_url(); ?>member/connect">
                                    <div class="stat-box referral">
                                        <i class="bi bi-send-fill"></i>
                                        <div>
                                            <small style="line-height:1.2; display:block;">Connect Given</small>
                                            <h5><?= $r_g_c ?? 0; ?></h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- Referral Received -->
                            <div class="col-md-4 col-6"><a href="<?= base_url(); ?>member/connect">
                                    <div class="stat-box referral2">
                                        <i class="bi bi-inbox-fill"></i>
                                        <div>
                                            <small style="line-height:1.2; display:block;">Connect Received</small>
                                            <h5><?= $r_r_c ?? 0; ?></h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- Meetings -->
                            <div class="col-md-4 col-6"><a href="<?= base_url(); ?>member/meetings">
                                    <div class="stat-box meeting">
                                        <i class="bi bi-calendar-event-fill"></i>
                                        <div>
                                            <small style="line-height:1.2; display:block;">Meetings</small>
                                            <h5><?= $meetingsCount ?? 0; ?></h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- F2F Send -->
                            <div class="col-md-4 col-6"><a href="<?= base_url(); ?>member/f2f">
                                    <div class="stat-box meeting">
                                        <i class="bi bi-person-up"></i>
                                        <div>
                                            <small style="line-height:1.2; display:block;"><b style="font-weight: 700;color:#222222;">F2F</b> Initiated By Me</small>
                                            <h5><?= $f2f__sc ?? 0; ?></h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- F2F Received -->
                            <div class="col-md-4 col-6"><a href="<?= base_url(); ?>member/f2f">
                                    <div class="stat-box meeting">
                                        <i class="bi bi-person-down"></i>
                                        <div>
                                            <small style="line-height:1.2; display:block;"><b style="font-weight: 700;color:#222222;">F2F</b> Initiated With Me</small>
                                            <h5><?= $f2f__rc ?? 0; ?></h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- Chapter Members -->
                            <div class="col-md-4 col-6"><a href="<?= base_url(); ?>member/mychapter">
                                    <div class="stat-box meeting">
                                        <i class="bi bi-diagram-3-fill"></i>
                                        <div>
                                            <small style="line-height:1.2; display:block;">Chapter Members</small>
                                            <h5><?= $chapterMembers_c ?? 0; ?></h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- Notifications -->
                            <div class="col-md-4 col-6"><a href="<?= base_url(); ?>member/notifications">
                                    <div class="stat-box meeting">
                                        <i class="bi bi-bell-fill"></i>
                                        <div>
                                            <small style="line-height:1.2; display:block;">Notifications</small>
                                            <h5><?= $notifications_c ?? 0; ?></h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- Profile Strength -->
                            <div class="col-md-8 col-6"><a href="<?= base_url(); ?>member/profile">
                                    <div class="stat-box meeting profile-strength-box">
                                        <i class="bi bi-person-check-fill"></i>
                                        <div class="w-100">
                                            <small style="line-height:1.2; display:block;">Profile Strength</small>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="mb-1">
                                                    <span id="profilePercent">0</span>%
                                                </h5>
                                            </div>
                                            <!-- Progress Bar -->
                                            <div class="progress profile-progress">
                                                <div
                                                    id="profileProgressBar"
                                                    class="progress-bar"
                                                    role="progressbar"
                                                    style="width:0%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = <?= $dates ?>;
        const givenData = <?= $givenData ?>;
        const receivedData = <?= $receivedData ?>;

        new Chart(document.getElementById('weeklyMITBChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Business  Given',
                        data: givenData,
                        borderColor: '#cf2030',
                        backgroundColor: 'rgba(237,45,123,.1)',
                        tension: .4,
                        fill: true
                    },
                    {
                        label: 'Business  Received',
                        data: receivedData,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40,167,69,.1)',
                        tension: .4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
    <script>
        const dates = <?= $dates ?>;
        const givenData = <?= $givenData ?>;
        const receivedData = <?= $receivedData ?>;

        new Chart(document.getElementById('weeklyMITBChart'), {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                        label: 'Business  Given',
                        data: givenData,
                        borderColor: '#cf2030',
                        backgroundColor: '#c21920',
                        tension: 0.4,
                        fill: false
                    },
                    {
                        label: 'Business  Received',
                        data: receivedData,
                        borderColor: '#f4ba40',
                        backgroundColor: '#f4ba40',
                        tension: 0.4,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let target = <?= (int)$profileStrength ?>; // PHP value
            let count = 0;

            const percentText = document.getElementById("profilePercent");
            const progressBar = document.getElementById("profileProgressBar");

            let interval = setInterval(() => {

                if (count >= target) {
                    clearInterval(interval);
                } else {
                    count++;
                    percentText.innerText = count;
                    progressBar.style.width = count + "%";
                }

            }, 20); // speed (lower = faster)

        });
    </script>
    <script>
        const ctx = document.getElementById('weeklyMITBChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= $dates ?>,
                datasets: [{
                        label: 'Business  Given',
                        data: <?= $givenData ?>,
                        borderWidth: 2,
                        tension: .4
                    },
                    {
                        label: 'Business  Received',
                        data: <?= $receivedData ?>,
                        borderWidth: 2,
                        tension: .4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
    <script>
        document.getElementById('mitbFilter').addEventListener('change', function() {
            let filter = this.value;
            window.location.href =
                "<?= base_url('member/dashboard') ?>?filter=" + filter;
        });
    </script>

    <script>
        document.getElementById('mitbFilter')
            .addEventListener('change', function() {

                const value = this.value;
                const url = new URL(window.location.href);
                url.searchParams.set('filter', value);

                window.location.href = url.toString();
            });
    </script>
</body>

</html>